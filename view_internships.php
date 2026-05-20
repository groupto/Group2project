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

$student_course = $_SESSION['student_course'];
$student_year = $_SESSION['student_year'];

// Query to show only relevant internships
$sql = "SELECT i.*, o.company_name 
        FROM internship i 
        JOIN organization o ON i.organization_id = o.organization_id 
        WHERE i.course_required = '$student_course' 
        AND i.year_required = '$student_year' 
        AND i.application_deadline > CURDATE() 
        AND i.status = 'Open'
        AND i.is_approved = 1
        ORDER BY i.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Internships - KYU Internship</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        .filter-info {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #3498db;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .apply-btn {
            background: #27ae60;
            color: white;
            padding: 5px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .apply-btn:hover {
            background: #229954;
        }
        .back-btn {
            background: #7f8c8d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="student_dashboard.php" class="back-btn">Back to Dashboard</a>
        
        <div class="header">
            <h2>Available Internships</h2>
            <p>Showing internships matching your profile</p>
        </div>
        
        <div class="filter-info">
            <strong>Smart Filter Active:</strong> Showing only internships for <strong><?php echo $student_course; ?></strong> • <strong><?php echo $student_year; ?></strong>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Stipend</th>
                        <th>Duration</th>
                        <th>Deadline</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><strong><?php echo $row['title']; ?></strong></td>
                        <td><?php echo $row['company_name']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <td><?php echo $row['stipend']; ?></td>
                        <td><?php echo $row['duration']; ?></td>
                        <td><?php echo $row['application_deadline']; ?></td>
                        <td>
                            <a href="apply.php?id=<?php echo $row['internship_id']; ?>" class="apply-btn">Apply</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>No internships available matching your course and year at the moment.</p>
                <p>Check back later or contact your internship coordinator.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>