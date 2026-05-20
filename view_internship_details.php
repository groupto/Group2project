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

$internship_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (!$internship_id) {
    header("Location: manage_internships.php");
    exit();
}

// Get internship details
$sql = "SELECT i.*, o.company_name, o.industry, o.contact_email, o.contact_phone 
        FROM internship i 
        JOIN organization o ON i.organization_id = o.organization_id 
        WHERE i.internship_id = '$internship_id'";
$result = mysqli_query($conn, $sql);
$internship = mysqli_fetch_assoc($result);

if (!$internship) {
    header("Location: manage_internships.php");
    exit();
}

// Get applications for this internship
$apps_sql = "SELECT a.*, s.first_name, s.last_name, s.email, s.course 
             FROM application a 
             JOIN student s ON a.student_id = s.student_id 
             WHERE a.internship_id = '$internship_id' 
             ORDER BY a.application_date DESC";
$apps_result = mysqli_query($conn, $apps_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Internship Details - KYU Internship</title>
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
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
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
        .pending { color: orange; }
        .accepted { color: green; }
        .rejected { color: red; }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <a href="manage_internships.php" class="back-link">← Back to Internships</a>
        
        <div class="card">
            <h2><?php echo $internship['title']; ?></h2>
            
            <div class="info-row">
                <div class="info-label">Company:</div>
                <div class="info-value"><?php echo $internship['company_name']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Industry:</div>
                <div class="info-value"><?php echo $internship['industry']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Location:</div>
                <div class="info-value"><?php echo $internship['location']; ?> (<?php echo $internship['location_type']; ?>)</div>
            </div>
            <div class="info-row">
                <div class="info-label">Stipend:</div>
                <div class="info-value"><?php echo $internship['stipend'] ?: 'Unpaid'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Duration:</div>
                <div class="info-value"><?php echo $internship['duration']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Course Required:</div>
                <div class="info-value"><?php echo $internship['course_required']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Year Required:</div>
                <div class="info-value"><?php echo $internship['year_required']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Start Date:</div>
                <div class="info-value"><?php echo $internship['start_date']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Application Deadline:</div>
                <div class="info-value"><?php echo $internship['application_deadline']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value"><?php echo $internship['status']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Description:</div>
                <div class="info-value"><?php echo nl2br($internship['description']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Required Skills:</div>
                <div class="info-value"><?php echo nl2br($internship['required_skills']); ?></div>
            </div>
        </div>
        
        <div class="card">
            <h2>Applications (<?php echo mysqli_num_rows($apps_result); ?>)</h2>
            
            <?php if (mysqli_num_rows($apps_result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($app = mysqli_fetch_assoc($apps_result)): ?>
                            <tr>
                                <td><?php echo $app['first_name'] . " " . $app['last_name']; ?></td>
                                <td><?php echo $app['course']; ?></td>
                                <td><?php echo $app['application_date']; ?></td>
                                <td class="<?php echo strtolower($app['status']); ?>"><?php echo $app['status']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No applications for this internship yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>