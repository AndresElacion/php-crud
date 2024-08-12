<?php
    include("./layout/header.php");

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