<?php
session_start();

//destroy username session variable

    unset($_SESSION['userId']);
    header("Location:login.php");

?>

