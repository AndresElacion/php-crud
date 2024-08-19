<?php
    // This will include the db.php from tools folder
    require_once __DIR__ . '/tools/db.php';

    // This will import the db class to instantiate and use it here
    use tools\db;

    // Instantiate the db class
    $dbConn = new db();
    $conn = $dbConn->getDatabaseConnection();

    $first_name = "";
    $last_name = "";
    $email = "";
    $phone = "";
    $address = "";
    $profile_image = "";

    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = htmlspecialchars(trim($_POST["first_name"]));
        $last_name = htmlspecialchars(trim($_POST["last_name"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $phone = htmlspecialchars(trim($_POST["phone"]));
        $address = htmlspecialchars(trim($_POST["address"]));

        if (!empty($_POST['captured_image'])) {
            $captured_image_data = $_POST['captured_image'];
            $image_parts = explode(";base64,", $captured_image_data);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file_name = 'uploads/' . uniqid() . '.' . $image_type;
            file_put_contents($file_name, $image_base64);
            $profile_image = $file_name;
        } elseif (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            // Directory where the uploaded image will be saved
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES['profile_image']['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if the image is a valid type
            $check = getimagesize($_FILES['profile_image']['tmp_name']);
            if ($check === false) {
                $errorMessage = "File is not an image.";
            } elseif ($_FILES['profile_image']['size'] > 2000000) { // Check file size (2MB limit)
                $errorMessage = "Sorry, your file is too large.";
            } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) { // Allow only certain formats
                $errorMessage = "Sorry, only JPG, JPEG, and PNG files are allowed.";
            } elseif (file_exists($target_file)) { // Check if file already exists
                $errorMessage = "Sorry, file already exists.";
            } else {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                    $profile_image = $target_file; // Set the path to the profile_image variable
                } else {
                    $errorMessage = "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            $errorMessage = "Image upload failed. Please select a valid image file.";
        }

        // Check if all fields are populated
        if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($address) || empty($profile_image)) {
            $errorMessage = "All fields are required.";
        } elseif (!filter_var($first_name, FILTER_SANITIZE_SPECIAL_CHARS)) {
            $errorMessage = "Invalid text format.";
        } elseif (!filter_var($last_name, FILTER_SANITIZE_SPECIAL_CHARS)) {
            $errorMessage = "Invalid text format.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Invalid email format.";
        } elseif (!preg_match('/^[0-9]{11}$/', $phone)) {
            $errorMessage = "Phone number must be 11 digits.";
        } elseif (!filter_var($address, FILTER_SANITIZE_SPECIAL_CHARS)) {
            $errorMessage = "Invalid text format.";
        } else {
            try {
                // Add new user to the database
                $sql = "INSERT INTO users (first_name, last_name, email, phone, address, profile_image) " . 
                        "VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$profile_image')";
                $result = $conn->query($sql);

                if (!$result) {
                    throw new Exception($conn->error);
                }

                // Clear the form fields
                $first_name = "";
                $last_name = "";
                $email = "";
                $phone = "";
                $address = "";
                $profile_image = "";

                $successMessage = "User added successfully";

                // Redirect to the index page
                header("Location: /enrollment/index.php");
                exit;
            } catch (mysqli_sql_exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $errorMessage = "This email is already registered. Please use a different email.";
                } else {
                    $errorMessage = "An error occurred: " . $e->getMessage();
                }
            }
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>ENROLLMENT | CREATE</title>
</head>
<body>
    <div class="container my-5">
        <h2>New User</h2>
        <?php 
            if (!empty($errorMessage)) {
                echo "
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>$errorMessage</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
                ";
            }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <label for="first_name" class="col-sm-3 col-form-label">First Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="first_name" value="<?php echo $first_name ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label for="last_name" class="col-sm-3 col-form-label">Last Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="last_name" value="<?php echo $last_name ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo $email ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" value="<?php echo $phone ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label for="address" class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" value="<?php echo $address ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label for="profile_image" class="cols-sm-3 col-form-label">Capture Image</label>
                <div class="col-sm-6">
                    <video id="video" width="100%" height="300" autoplay></video>
                    <button type="button" id="capture-btn" class="btn btn-primary mt-2">Capture Image</button>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <input type="hidden" name="captured_image" id="captured_image">
                </div>
            </div>

            <div class="row mb-3">
                <label for="profile_image" class="col-sm-3 col-form-label">Select image to upload</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="profile_image">
                </div>
            </div>

            <?php 
               if (!empty($successMessage)) {
                echo "
                <div class='row mb-3'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <strong>$successMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                </div>
                ";
            } 
            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="/enrollment/index.php" class="btn btn-outline-primary">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        // This is to access webcam
        const video = document.querySelector('video');
        const canvas = document.querySelector('canvas');
        const capturedImageInput = document.querySelector('#captured_image');

        navigator.mediaDevices.getUserMedia({video: true})
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(error => {
            console.error("Error accessing camera: ", error);
        })

        // capture the image when button clicked
        document.querySelector('#capture-btn').addEventListener('click', function() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoHeight;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // convert the canvas image to a data URL and store it in the hidden input field
        const imageDataURL = canvas.toDataURL('image/png');
        capturedImageInput.value = imageDataURL;
        });
    </script>
</body>
</html>
