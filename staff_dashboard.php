<?php
include 'config.php';

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['staff_id'])) {
    header("Location: staff_login.php");
    exit();
}

$staff_name = $_SESSION['staff_name'];
$staff_role = $_SESSION['staff_role'];

// Get statistics
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM student"));
$total_internships = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM internship"));
$total_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM application"));
$pending_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM application WHERE status='Pending'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
        }
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #e67e22 100%);
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
            color: #e67e22;
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
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h2>KYU Internship System - Staff Portal</h2>
            </div>
            <div>
                Welcome, <?php echo $staff_name; ?> (<?php echo $staff_role; ?>)<br>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <a href="index.php" class="back-link">← Back to Home</a>
        
        <div class="welcome-card">
            <h2>Staff Dashboard</h2>
            <p>Manage internships, review applications, and monitor student placements.</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_students['count']; ?></div>
                <div>Total Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_internships['count']; ?></div>
                <div>Total Internships</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_applications['count']; ?></div>
                <div>Total Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pending_applications['count']; ?></div>
                <div>Pending Review</div>
            </div>
        </div>
        
        <div class="menu">
            <a href="post_internship.php" class="menu-card">
                <div class="menu-icon">➕</div>
                <div class="menu-title">Post Internship</div>
                <div>Create new internship opportunity</div>
            </a>
            <a href="manage_internships.php" class="menu-card">
                <div class="menu-icon">📋</div>
                <div class="menu-title">Manage Internships</div>
                <div>View and edit all internships</div>
            </a>
            <a href="review_applications.php" class="menu-card">
                <div class="menu-icon">📝</div>
                <div class="menu-title">Review Applications</div>
                <div>Review student applications</div>
            </a>
            <a href="view_students.php" class="menu-card">
                <div class="menu-icon">👨‍🎓</div>
                <div class="menu-title">View Students</div>
                <div>View registered students</div>
            </a>
        </div>
    </div>
</body>
</html>