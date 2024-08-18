<?php
    include("./layout/header.php");

    $timeout_duration = 1800; // 30 minutes 1800

    // Check if 'last_activity' is set in the session
    if (isset($_SESSION['last_activity'])) {
        // calculate the time difference between now and last_activity
        $time_inactive = time() - $_SESSION['last_activity'];

        // check if the time difference exceeds the timeout duration
        if ($time_inactive > $timeout_duration) {
            // if the user is inactive for more than the timeout duration, destroy the session
            session_unset();
            session_destroy();

            // redirect to login
            header("Location: /enrollment/login.php");
            exit();
        }
    }

    // update the last activity time to the current time
    $_SESSION['last_activity'] = time();

    if (!isset($_SESSION["email"])) {
        // check if the user is logged in, if not redirect to login page
        header("Location: /enrollment/login.php");
        exit;
    }
?>

<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="row w-75">
        <div class="col-lg-6 mx-auto border shadow p-4">
            <h2 class="text-center mb-4">Profile</h2>
            <hr />

            <div class="row mb-3">
                <div class="col-sm-4">First Name</div>
                <div class="col-sm-8"><?php echo $_SESSION["first_name"]?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4">Last Name</div>
                <div class="col-sm-8"><?php echo $_SESSION["last_name"]?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4">Email</div>
                <div class="col-sm-8"><?php echo $_SESSION["email"]?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4">Phone</div>
                <div class="col-sm-8"><?php echo $_SESSION["phone"]?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4">Address</div>
                <div class="col-sm-8"><?php echo $_SESSION["address"]?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4">Registered At</div>
                <div class="col-sm-8"><?php echo $_SESSION["created_at"]?></div>
            </div>
        </div>
    </div>
</div>

<?php
    include("./layout/footer.php");
?>