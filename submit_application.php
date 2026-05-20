<?php
include 'config.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id'];
    $internship_id = mysqli_real_escape_string($conn, $_POST['internship_id']);
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);
    
    // Create uploads folder if it doesn't exist
    $target_dir = "uploads/cv/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Handle file upload
    $file_name = time() . "_" . basename($_FILES["cv"]["name"]);
    $target_file = $target_dir . $file_name;
    $upload_ok = 1;
    
    // Check file type
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($file_type != "pdf" && $file_type != "doc" && $file_type != "docx") {
        echo "<script>alert('Sorry, only PDF, DOC, and DOCX files are allowed.'); window.history.back();</script>";
        exit();
    }
    
    if ($upload_ok && move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {
        // Generate application ID
        $application_id = "APP-" . date("Y") . "-" . rand(1000, 9999);
        
        // Insert application
        $sql = "INSERT INTO application (application_id, student_id, internship_id, cover_letter, cv_path, status) 
                VALUES ('$application_id', '$student_id', '$internship_id', '$cover_letter', '$target_file', 'Pending')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Application submitted successfully!'); window.location='my_applications.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    
    mysqli_close($conn);
}
?>