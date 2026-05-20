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

$org_name = $_SESSION['org_name'];
$org_id = $_SESSION['org_id'];

// Get statistics
$internships_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM internship WHERE organization_id = '$org_id'"));
$applications_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM application a JOIN internship i ON a.internship_id = i.internship_id WHERE i.organization_id = '$org_id'"));
$pending_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM application a JOIN internship i ON a.internship_id = i.internship_id WHERE i.organization_id = '$org_id' AND a.status='Pending'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Organization Dashboard - KYU Internship</title>
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
                <h2>KYU Internship System - Organization Portal</h2>
            </div>
            <div>
                Welcome, <?php echo $org_name; ?>!<br>
                <a href="logout_org.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <a href="index.php" class="back-link">← Back to Home</a>
        
        <div class="welcome-card">
            <h2>Organization Dashboard</h2>
            <p>Post internship opportunities and review applications from students.</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $internships_count['count']; ?></div>
                <div>Your Internships</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $applications_count['count']; ?></div>
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
            <a href="my_internships.php" class="menu-card">
                <div class="menu-icon">📋</div>
                <div class="menu-title">My Internships</div>
                <div>View and manage your postings</div>
            </a>
            <a href="review_applications_org.php" class="menu-card">
                <div class="menu-icon">📝</div>
                <div class="menu-title">Review Applications</div>
                <div>Review student applications</div>
            </a>
        </div>
    </div>
</body>
</html>