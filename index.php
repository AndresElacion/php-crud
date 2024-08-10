<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>ENROLLMENT</title>
</head>
<body>
    <div class="container my-5">
        <h2>List of Users</h2>
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
                    $serverName = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "vanilla_enrollment";

                    // create connection
                    $conn = new mysqli($serverName, $username, $password, $database);

                    // check connection
                    if($conn->connect_error) {
                        die("Connection Failed:" . $conn->connect_error);
                    }

                    // select all row from database users table
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
</body>
</html>