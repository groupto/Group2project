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

$internship_id = isset($_GET['internship_id']) ? $_GET['internship_id'] : '';

// Get internship details
$internship_sql = "SELECT title FROM internship WHERE internship_id = '$internship_id' AND organization_id = '{$_SESSION['org_id']}'";
$internship_result = mysqli_query($conn, $internship_sql);
$internship = mysqli_fetch_assoc($internship_result);

if (!$internship) {
    header("Location: my_internships.php");
    exit();
}

// Get applications for this internship
$sql = "SELECT a.*, s.first_name, s.last_name, s.email, s.course, s.year_of_study, s.registration_number
        FROM application a 
        JOIN student s ON a.student_id = s.student_id
        WHERE a.internship_id = '$internship_id'
        ORDER BY a.application_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Applications - KYU Internship</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #3498db;
            color: white;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <a href="my_internships.php" class="back-link">← Back to My Internships</a>
        
        <h2>Applications for: <?php echo $internship['title']; ?></h2>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Registration No</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                            <td><?php echo $row['registration_number']; ?></td>
                            <td><?php echo $row['course']; ?></td>
                            <td><?php echo $row['year_of_study']; ?></td>
                            <td><?php echo $row['application_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No applications for this internship yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>