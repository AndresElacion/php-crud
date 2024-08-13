
    <?php
        include("../enrollment/layout/header.php");
        include("../enrollment/components/nav.php");
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

    <?php
        include("../enrollment/layout/footer.php");
    ?>