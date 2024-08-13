<?php
    include("../enrollment/layout/header.php");

    // check if user is authenticated
    if (isset($_SESSION["email"])) {
        header("Location: /enrollment/index.php");
        exit();
    }

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

    $first_name_error = "";
    $last_name_error = "";
    $email_error = "";
    $phone_error = "";
    $address_error = "";
    $password_error = "";
    $confirm_password_error = "";

    $errorMessage = "";
    $error = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = filter_var(trim($_POST["first_name"]), FILTER_SANITIZE_SPECIAL_CHARS);
        $last_name = filter_var(trim($_POST["last_name"]), FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $phone = filter_var(trim($_POST["phone"]), FILTER_SANITIZE_SPECIAL_CHARS);
        $address = filter_var(trim($_POST["address"]), FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_password = filter_var(trim($_POST["confirm_password"]), FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($first_name)) {
            $first_name_error = "First name is required";
            $error = true;
        }

        if (empty($last_name)) {
            $last_name_error = "Last name is required";
            $error = true;
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = "Email format is not valid";
            $error = true;
        }

        $statement = $conn->prepare("SELECT id FROM registration WHERE email = ?");

        // bind the variable to the prepared statement as parameters
        $statement->bind_param("s", $email);

        // execute the statement
        $statement->execute();

        // check if the email is already in the database
        $statement->store_result();
        if ($statement->num_rows > 0) {
            $email_error = "Email is already used";
            $error = true;
        }

        // closing the statement otherwise we cannot prepare another statement
        $statement->close();

        if (!preg_match('/^[0-9]{11}$/', $phone)) {
            $phone_error = "Phone number must be 11 digits.";
            $error = true;
        }

        if (empty($address)) {
            $address_error = "Address is required";
            $error = true;
        }

        if (strlen($password) < 6) {
            $password_error = "Password must have at least 6 characters";
            $error = true;
        }

        if ($confirm_password != $password) {
            $confirm_password_error = "Password and Confirm Password not match";
            $error = true;
        }

        if (!$error) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $created_at = date('Y-m-d H:i:s');

            // let use the prepared statements to avoid sql injection attacks
            $statement = $conn->prepare("INSERT INTO registration (first_name, last_name, email, phone, address, password, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");

            // bind variables to the prepared statement as parameters
            // s => string; i => integer
            $statement->bind_param('sssssss', $first_name, $last_name, $email, $phone, $address, $password, $created_at);

            // execute the statement
            $statement->execute();

            $insert_id = $statement->insert_id;
            
            // close the statement to prepare for another statement
            $statement->close();

            // save session data
            $_SESSION["id"] = $insert_id;
            $_SESSION["first_name"] = $first_name;
            $_SESSION["last_name"] = $last_name;
            $_SESSION["email"] = $email;
            $_SESSION["phone"] = $phone;
            $_SESSION["address"] = $address;
            $_SESSION["created_at"] = $created_at;

            // redirect to login
            header("Location: /enrollment/index.php");
            exit;
        }
    }
?>

<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="row w-75">
        <div class="col-lg-6 mx-auto border shadow p-4">
            <h2 class="text-center mb-4">Register</h2>
            <hr/>
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
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                <div class="row mb-3">
                    <label for="first_name" class="col-sm-4 col-form-label">First Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="first_name" value="<?= $first_name ?>">
                        <span class="text-danger"><?= $first_name_error ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="last_name" class="col-sm-4 col-form-label">Last Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="last_name" value="<?= $last_name ?>">
                        <span class="text-danger"><?= $last_name_error ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-sm-4 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="email" value="<?= $email ?>">
                        <span class="text-danger"><?= $email_error ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="phone" class="col-sm-4 col-form-label">Phone</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="phone" value="<?= $phone ?>">
                        <span class="text-danger"><?= $phone_error ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="address" class="col-sm-4 col-form-label">Address</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="address" value="<?= $address ?>">
                        <span class="text-danger"><?= $address_error ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-sm-4 col-form-label">Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password">
                        <span class="text-danger"><?= $password_error ?></span>
                    </div>
                </div>

                <div class="row mb-2">
                    <label for="confirm_password" class="col-sm-4 col-form-label">Confirm Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="confirm_password">
                        <span class="text-danger"><?= $confirm_password_error ?></span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="offset-sm-4">
                        <p class="text-left">You have an account? <a href="./login.php" class="text-primary">Login</a> here</p>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
    include("../enrollment/layout/footer.php");
?>