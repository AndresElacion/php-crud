<?php
    // initialize the session

    session_start();

    // unset all the session variable
    $_SESSION = array();

    // destroy the session
    session_destroy();

    // redirect to login
    header("Location: /enrollment/register.php");
?>