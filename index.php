<?php
    include("../enrollment/layout/header.php");
    include("../enrollment/components/nav.php");

    // set timeout duration in seconds
    $timeout_duration = 60; // 30 minutes

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
?>

<div class="container my-5">
    <h2>List of Students</h2>
    <a href="/enrollment/create.php" class="btn btn-primary" role="button">New User</a>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // This will include the db.php from tools folder
                require_once __DIR__ . '/tools/db.php';
                // This will import the db class to instantiate and use it here
                use tools\db;

                // Instantiate the db class
                $dbConn = new db();
                $conn = $dbConn->getDatabaseConnection();

                // select all rows from the database users table
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);

                if(!$result) {
                    die("invalid query:" . $conn->error);
                }

                // read data of each row
                while($row = $result->fetch_assoc()) {
                    echo "
                        <tr>
                            <td>$row[id]</td>
                            <td>$row[first_name]</td>
                            <td>$row[last_name]</td>
                            <td>$row[email]</td>
                            <td>$row[phone]</td>
                            <td>$row[address]</td>
                            <td>$row[created_at]</td>
                            <td>
                                <a href='/enrollment/edit.php?id=$row[id]' class='btn btn-primary btn-sm' >Edit</a>
                                <a href='/enrollment/delete.php?id=$row[id]' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                        </tr>
                    ";
                }
            ?>
        </tbody>
    </table>
</div>

<?php
    include("../enrollment/layout/footer.php");
?>
