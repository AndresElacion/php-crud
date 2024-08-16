<?php
    include("./layout/header.php");

    if (isset($_SESSION["email"])) {
        // redirect to login
        header("Location: /enrollment/index.php");
        exit;
    }

    $email = "";
    $error = "";

    // This will include the db.php from tools folder
    require_once __DIR__ . '/tools/db.php';

    // This will import the db class to instantiate and use it here
    use tools\db;    
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($email) || empty($password)) {
            $error = "Email and Password are required";
        } else {
            // Instantiate the db class
            $dbConn = new db();
            $conn = $dbConn->getDatabaseConnection();

            // create new statement using prepare
            $statement = $conn->prepare("SELECT id, first_name, last_name, phone, address, password, created_at FROM registration WHERE email = ?");

            // Bind variables to the prepared statement as parameters
            $statement->bind_param('s', $email);

            $statement->execute();

            // bind result variables
            $statement->bind_result($id, $first_name, $last_name, $phone, $address, $stored_password, $created_at);

            // fetch values
            if ($statement->fetch()) {
                // check if the password are correct or not
                if (password_verify($password, $stored_password)) {
                    // store data in session variables
                    $_SESSION["id"] = $id;
                    $_SESSION["first_name"] = $first_name;
                    $_SESSION["last_name"] = $last_name;
                    $_SESSION["email"] = $email;
                    $_SESSION["phone"] = $phone;
                    $_SESSION["address"] = $address;
                    $_SESSION["created_at"] = $created_at;

                    header("Location: /enrollment/index.php");
                    exit;
                }
            }

            $statement->close();

            $error = "Invalid credentials";
        }
    }
?>

<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="mx-auto border shadow p-4" style="width: 400px;">
        <h2 class="text-center mb-4">Login</h2>
        <hr/>

        <?php 
        if (!empty($error)) {
            echo "
            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>$error</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" name="email" value="<?= $email ?>" />
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" />
            </div>

            <div class="row mb-3">
            <p class="text-left">Don't have an account? <a href="./register.php" class="text-primary">Register</a> here</p>
                <div class="offset-sm-4">
                    <button type="submit" class="btn btn-primary">Log in</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
    include("./layout/footer.php");
?>