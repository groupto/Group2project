<?php
include 'config.php';

// Get system statistics
$students_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM student"));
$internships_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM internship WHERE status='Open' AND is_approved=1"));
$orgs_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM organization"));
$applications_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM application"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYU Internship Placement Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Navigation Bar */
        .navbar {
            background: rgba(255,255,255,0.95);
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .logo {
            font-size: 1.5em;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .logo span {
            color: #3498db;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #2c3e50;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            background: #3498db;
            color: white;
        }
        
        .nav-links a.active {
            background: #3498db;
            color: white;
        }
        
        /* Main Container */
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }
        
        /* Hero Section */
        .hero {
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .hero h1 {
            font-size: 2.5em;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .hero h1 span {
            color: #3498db;
        }
        
        .hero p {
            font-size: 1.1em;
            color: #7f8c8d;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .kyu-badge {
            display: inline-block;
            background: #2c3e50;
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.9em;
        }
        
        /* Statistics Cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #3498db;
        }
        
        .stat-label {
            color: #7f8c8d;
            margin-top: 10px;
            font-size: 0.9em;
        }
        
        /* Role Buttons Section */
        .role-section {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .role-section h2 {
            color: white;
            margin-bottom: 30px;
            font-size: 1.8em;
        }
        
        .role-buttons {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .role-btn {
            background: white;
            padding: 30px 40px;
            border-radius: 15px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            min-width: 200px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .role-btn:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }
        
        .role-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }
        
        .role-title {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .role-desc {
            font-size: 0.85em;
            color: #7f8c8d;
        }
        
        .btn-student .role-title { color: #27ae60; }
        .btn-staff .role-title { color: #e67e22; }
        .btn-org .role-title { color: #3498db; }
        
        /* Features Section */
        .features {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-top: 40px;
        }
        
        .features h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .feature {
            text-align: center;
            padding: 20px;
        }
        
        .feature-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
        }
        
        .feature h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .feature p {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        
        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 50px;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .hero h1 {
                font-size: 1.8em;
            }
            
            .role-btn {
                padding: 20px 30px;
                min-width: 150px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="nav-container">
            <div class="logo">
                KYU <span>Internship</span>
            </div>
            <div class="nav-links">
                <a href="index.php" class="active">Home</a>
                <a href="student_login.php">Student Login</a>
                <a href="staff_login.php">Staff Login</a>
                <a href="organization_login.php">Organization Login</a>
                <a href="organization_register.php">Organization Register</a>
                <a href="student_register.php">Student Register</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <h1>Welcome to <span>KYU Internship</span> Placement System</h1>
            <p>Your centralized platform for finding and managing internship opportunities<br>
            at Kyambogo University</p>
            <div class="kyu-badge">Kyambogo University • Faculty of Computing & Information Science</div>
        </div>
        
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $students_count['count']; ?></div>
                <div class="stat-label">Registered Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $internships_count['count']; ?></div>
                <div class="stat-label">Open Internships</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $orgs_count['count']; ?></div>
                <div class="stat-label">Partner Organizations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $applications_count['count']; ?></div>
                <div class="stat-label">Applications Submitted</div>
            </div>
        </div>
        
        <!-- Role Selection -->
        <div class="role-section">
            <h2>I am a...</h2>
            <div class="role-buttons">
                <a href="student_login.php" class="role-btn btn-student">
                    <div class="role-icon">👨‍🎓</div>
                    <div class="role-title">Student</div>
                    <div class="role-desc">Find and apply for internships</div>
                </a>
                <a href="staff_login.php" class="role-btn btn-staff">
                    <div class="role-icon">👔</div>
                    <div class="role-title">Staff / Admin</div>
                    <div class="role-desc">Post and manage internships</div>
                </a>
                <a href="organization_login.php" class="role-btn btn-org">
                    <div class="role-icon">🏢</div>
                    <div class="role-title">Organization</div>
                    <div class="role-desc">Offer internship opportunities</div>
                </a>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="organization_register.php" style="background: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; margin: 0 10px; display: inline-block;">Register as Organization</a>
            <a href="student_register.php" style="background: #27ae60; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; margin: 0 10px; display: inline-block;">Register as Student</a>
        </div>
        
        <!-- Features -->
        <div class="features">
            <h2>Why Choose Our System?</h2>
            <div class="feature-grid">
                <div class="feature">
                    <div class="feature-icon">🎯</div>
                    <h3>Smart Matching</h3>
                    <p>Get internships that match your course and year of study automatically</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">📱</div>
                    <h3>Centralized Platform</h3>
                    <p>All internship opportunities in one place - no more checking multiple sources</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">📊</div>
                    <h3>Track Applications</h3>
                    <p>Monitor your application status in real-time</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">📧</div>
                    <h3>Instant Alerts</h3>
                    <p>Receive notifications about new opportunities and application updates</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>© 2026 Kyambogo University - Internship Placement Management System</p>
        <p>Faculty of Computing & Information Science | Empowering Students for the Future</p>
    </div>
</body>
</html>