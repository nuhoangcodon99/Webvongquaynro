<?php
// Start the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to the login page or wherever you want the user to go after logging out
header("Location: /dang-nhap.php");
exit;
?>