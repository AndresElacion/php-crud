<?php
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        $serverName = "localhost";
        $username = "root";
        $password = "";
        $database = "vanilla_enrollment";
    
        // Create connection
        $conn = new mysqli($serverName, $username, $password, $database);
    
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "DELETE FROM users WHERE id = $id";
        $conn->query($sql);

        header("Location: /enrollment/index.php");
        exit;
    }
?>