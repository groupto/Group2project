<?php
include 'config.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$sql = "SELECT a.*, i.title, i.location, o.company_name 
        FROM application a 
        JOIN internship i ON a.internship_id = i.internship_id 
        JOIN organization o ON i.organization_id = o.organization_id 
        WHERE a.student_id = '$student_id' 
        ORDER BY a.application_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Applications - KYU Internship</title>
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
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            margin: 0 0 10px 0;
            color: #2c3e50;
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
        .pending {
            color: orange;
            font-weight: bold;
        }
        .accepted {
            color: green;
            font-weight: bold;
        }
        .rejected {
            color: red;
            font-weight: bold;
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
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <a href="student_dashboard.php" class="back-btn">← Back to Dashboard</a>
        
        <div class="header">
            <h2>📝 My Applications</h2>
            <p>Track the status of your internship applications</p>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Internship Title</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong><?php echo $row['title']; ?></strong></td>
                            <td><?php echo $row['company_name']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['application_date']; ?></td>
                            <td class="<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>You haven't applied for any internships yet.</p>
                <a href="view_internships.php">Browse Internships</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>