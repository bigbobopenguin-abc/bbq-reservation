<?php
// redirect un-authenticated users to the login page)
session_start();

if (!isset($_SESSION["uid"])) {
    header("Location: login.php");
    exit();
}
?>
