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

    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = htmlspecialchars(trim($_POST["first_name"]));
        $last_name = htmlspecialchars(trim($_POST["last_name"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $phone = htmlspecialchars(trim($_POST["phone"]));
        $address = htmlspecialchars(trim($_POST["address"]));

        
            if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($address)) {
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
                $sql = "INSERT INTO users (first_name, last_name, email, phone, address) " . 
                        "VALUES ('$first_name', '$last_name', '$email', '$phone', '$address')";
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
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
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
</body>
</html>
