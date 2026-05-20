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

$org_id = $_SESSION['org_id'];
$org_name = $_SESSION['org_name'];

// Get all internships for this organization
$sql = "SELECT * FROM internship WHERE organization_id = '$org_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Internships - KYU Internship</title>
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
        .org-info {
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
        .status-open {
            color: green;
            font-weight: bold;
        }
        .status-closed {
            color: red;
            font-weight: bold;
        }
        .status-filled {
            color: orange;
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .btn-view {
            background: #3498db;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
        }
        .btn-view:hover {
            background: #2980b9;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            color: #7f8c8d;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .btn-close {
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
        }
        .btn-close:hover {
            background: #c0392b;
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <a href="organization_dashboard.php" class="back-link">← Back to Dashboard</a>
        
        <div class="header">
            <h2>My Internships</h2>
            <p>View and manage all internships posted by <?php echo $org_name; ?></p>
        </div>
        
        <div class="org-info">
            <strong>Organization ID:</strong> <?php echo $org_id; ?> | 
            <strong>Total Internships:</strong> <?php echo mysqli_num_rows($result); ?>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Course Required</th>
                        <th>Year</th>
                        <th>Location</th>
                        <th>Stipend</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['internship_id']; ?></td>
                            <td><strong><?php echo $row['title']; ?></strong></td>
                            <td><?php echo $row['course_required']; ?></td>
                            <td><?php echo $row['year_required']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['stipend']; ?></td>
                            <td><?php echo $row['application_deadline']; ?></td>
                            <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                            <td class="actions">
                                <a href="view_applications_org.php?internship_id=<?php echo $row['internship_id']; ?>" class="btn-view">View Apps</a>
                                <?php if($row['status'] == 'Open'): ?>
                                    <a href="close_internship.php?id=<?php echo $row['internship_id']; ?>" class="btn-close" onClick="return confirm('Are you sure you want to close this internship?')">Close</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>You haven't posted any internships yet.</p>
                <a href="post_internship.php">Post Your First Internship</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>