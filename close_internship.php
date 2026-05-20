<?php
include 'config.php';

if (!isset($_SESSION)) {
    session_start();
}

// Check if organization is logged in
if (!isset($_SESSION['org_id'])) {
    header("Location: organization_login.php");
    exit();
}

$internship_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($internship_id) {
    $sql = "UPDATE internship SET status = 'Closed' WHERE internship_id = '$internship_id' AND organization_id = '{$_SESSION['org_id']}'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: my_internships.php?msg=closed");
    } else {
        header("Location: my_internships.php?error=" . mysqli_error($conn));
    }
} else {
    header("Location: my_internships.php");
}
exit();
?>