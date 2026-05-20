<?php
include 'config.php';

if (!isset($_SESSION)) {
    session_start();
}

// Check if staff is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: staff_login.php");
    exit();
}

$student_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (!$student_id) {
    header("Location: view_students.php");
    exit();
}

// Get student details
$sql = "SELECT * FROM student WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $sql);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    header("Location: view_students.php");
    exit();
}

// Get student's applications
$apps_sql = "SELECT a.*, i.title, i.location, o.company_name 
             FROM application a 
             JOIN internship i ON a.internship_id = i.internship_id 
             JOIN organization o ON i.organization_id = o.organization_id 
             WHERE a.student_id = '$student_id' 
             ORDER BY a.application_date DESC";
$apps_result = mysqli_query($conn, $apps_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Details - KYU Internship</title>
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
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            margin: 0 0 20px 0;
            border-bottom: 2px solid #e67e22;
            padding-bottom: 10px;
        }
        .info-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }
        .info-value {
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #e67e22;
            color: white;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .pending { color: orange; font-weight: bold; }
        .accepted { color: green; font-weight: bold; }
        .rejected { color: red; font-weight: bold; }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-active {
            background: #e8f5e9;
            color: green;
        }
        .status-inactive {
            background: #ffebee;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="view_students.php" class="back-link">← Back to Students</a>
        
        <div class="card">
            <h2>Student Information</h2>
            
            <div class="info-row">
                <div class="info-label">Student ID:</div>
                <div class="info-value"><?php echo $student['student_id']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value"><?php echo $student['first_name'] . " " . $student['last_name']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo $student['email']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value"><?php echo $student['phone'] ?: 'Not provided'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Course:</div>
                <div class="info-value"><?php echo $student['course']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Year of Study:</div>
                <div class="info-value"><?php echo $student['year_of_study']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Registration Number:</div>
                <div class="info-value"><?php echo $student['registration_number']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge status-<?php echo $student['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $student['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Registered On:</div>
                <div class="info-value"><?php echo $student['created_at']; ?></div>
            </div>
        </div>
        
        <div class="card">
            <h2>Application History</h2>
            
            <?php if (mysqli_num_rows($apps_result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Internship</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($app = mysqli_fetch_assoc($apps_result)): ?>
                            <tr>
                                <td><?php echo $app['title']; ?></td>
                                <td><?php echo $app['company_name']; ?></td>
                                <td><?php echo $app['location']; ?></td>
                                <td><?php echo $app['application_date']; ?></td>
                                <td class="<?php echo strtolower($app['status']); ?>"><?php echo $app['status']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No applications submitted yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>