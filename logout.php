<?php
// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Destroy all session data
session_destroy();

// Redirect to login page
header("Location: student_login.php");
exit();
?>