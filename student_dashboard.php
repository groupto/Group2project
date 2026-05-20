<?php
include 'config.php';

// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$student_name = $_SESSION['student_name'];
$student_course = $_SESSION['student_course'];
$student_year = $_SESSION['student_year'];

// Get counts for dashboard
$internship_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM internship WHERE course_required = '$student_course' AND year_required = '$student_year' AND status='Open' AND is_approved=1"));
$application_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM application WHERE student_id = '{$_SESSION['student_id']}'"));
$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM application WHERE student_id = '{$_SESSION['student_id']}' AND status='Pending'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard - KYU Internship</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 20px;
        }
        .header-content {
            max-width: 1200px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            color: white;
            text-decoration: none;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .welcome-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .badge {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            margin-top: 10px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #3498db;
        }
        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .menu-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: #2c3e50;
            transition: transform 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: block;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .menu-icon {
            font-size: 3em;
        }
        .menu-title {
            font-size: 1.2em;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h2>KYU Internship System</h2>
            </div>
            <div>
                Welcome, <?php echo $student_name; ?>!<br>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <h2>Welcome to Your Dashboard</h2>
            <p>Find and apply for internships that match your course and year of study.</p>
            <div class="badge">Smart Matching: <?php echo $student_course; ?> • <?php echo $student_year; ?></div>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $internship_count['count']; ?></div>
                <div>Available Internships</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $application_count['count']; ?></div>
                <div>Total Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pending_count['count']; ?></div>
                <div>Pending Review</div>
            </div>
        </div>
        
        <div class="menu">
            <a href="view_internships.php" class="menu-card">
                <div class="menu-icon">📋</div>
                <div class="menu-title">View Internships</div>
                <div>Browse available opportunities</div>
            </a>
            <a href="my_applications.php" class="menu-card">
                <div class="menu-icon">📝</div>
                <div class="menu-title">My Applications</div>
                <div>Track your application status</div>
            </a>
        </div>
    </div>
</body>
</html>