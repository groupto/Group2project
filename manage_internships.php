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

$is_admin = $_SESSION['staff_is_admin'];

// Handle delete internship
if (isset($_GET['delete']) && $is_admin) {
    $internship_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_sql = "DELETE FROM internship WHERE internship_id = '$internship_id'";
    if (mysqli_query($conn, $delete_sql)) {
        $success = "Internship deleted successfully!";
    } else {
        $error = "Error deleting internship: " . mysqli_error($conn);
    }
}

// Handle approve internship
if (isset($_GET['approve']) && $is_admin) {
    $internship_id = mysqli_real_escape_string($conn, $_GET['approve']);
    $approve_sql = "UPDATE internship SET is_approved = 1, approved_by = '{$_SESSION['staff_id']}', approved_at = NOW() WHERE internship_id = '$internship_id'";
    if (mysqli_query($conn, $approve_sql)) {
        $success = "Internship approved successfully!";
    } else {
        $error = "Error approving internship: " . mysqli_error($conn);
    }
}

// Handle close internship
if (isset($_GET['close'])) {
    $internship_id = mysqli_real_escape_string($conn, $_GET['close']);
    $close_sql = "UPDATE internship SET status = 'Closed' WHERE internship_id = '$internship_id'";
    if (mysqli_query($conn, $close_sql)) {
        $success = "Internship closed successfully!";
    } else {
        $error = "Error closing internship: " . mysqli_error($conn);
    }
}

// Get all internships with organization names
$sql = "SELECT i.*, o.company_name 
        FROM internship i 
        JOIN organization o ON i.organization_id = o.organization_id 
        ORDER BY i.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Internships - KYU Internship</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1300px;
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
            background: #e67e22;
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
        .badge-approved {
            background: green;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        .badge-pending {
            background: orange;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .btn-approve {
            background: #27ae60;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
        }
        .btn-close {
            background: #e67e22;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
        }
        .btn-view {
            background: #3498db;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
        }
        .success {
            background: #e8f5e9;
            color: green;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error {
            background: #ffebee;
            color: red;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-bar {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .filter-bar a {
            padding: 5px 15px;
            background: #ecf0f1;
            text-decoration: none;
            color: #2c3e50;
            border-radius: 20px;
        }
        .filter-bar a.active {
            background: #e67e22;
            color: white;
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <a href="staff_dashboard.php" class="back-link">← Back to Dashboard</a>
        
        <div class="header">
            <h2>Manage Internships</h2>
            <p>View, approve, and manage all internship postings</p>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="filter-bar">
            <a href="manage_internships.php" class="active">All Internships</a>
            <a href="manage_internships.php?filter=pending">Pending Approval</a>
            <a href="manage_internships.php?filter=approved">Approved</a>
            <a href="manage_internships.php?filter=open">Open</a>
            <a href="manage_internships.php?filter=closed">Closed</a>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Location</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Approval</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['internship_id']; ?></td>
                            <td><strong><?php echo $row['title']; ?></strong></td>
                            <td><?php echo $row['company_name']; ?></td>
                            <td><?php echo $row['course_required']; ?></td>
                            <td><?php echo $row['year_required']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['application_deadline']; ?></td>
                            <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                            <td>
                                <?php if($row['is_approved']): ?>
                                    <span class="badge-approved">Approved</span>
                                <?php else: ?>
                                    <span class="badge-pending">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="view_internship_details.php?id=<?php echo $row['internship_id']; ?>" class="btn-view">View</a>
                                <?php if(!$row['is_approved'] && $is_admin): ?>
                                    <a href="" class="btn-approve" onClick="return confirm('Approve this internship?')">Approve</a>
                                <?php endif; ?>
                                <?php if($row['status'] == 'Open'): ?>
                                    <a href="" class="btn-close" onClick="return confirm('Close this internship?')">Close</a>
                                <?php endif; ?>
                                <?php if($is_admin): ?>
                                    <a href="" class="btn-delete" onClick="return confirm('Delete this internship? This cannot be undone!')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 10px;">
                <p>No internships found.</p>
                <a href="post_internship.php">Post First Internship</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>