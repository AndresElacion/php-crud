<?php
    // Initialize the session
    session_start();

    $authenticated = false;

    if (isset($_SESSION["email"])) {
        $authenticated = true;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta http-equiv="refresh" content="60"> <!-- Refreshes the page every 60 seconds -->
    <title>ENROLLMENT</title>
</head>
<body>