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

$staff_id = $_SESSION['staff_id'];
$is_admin = $_SESSION['staff_is_admin'];

// Handle status update
if (isset($_POST['update_status'])) {
    $application_id = mysqli_real_escape_string($conn, $_POST['application_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $rejection_reason = isset($_POST['rejection_reason']) ? mysqli_real_escape_string($conn, $_POST['rejection_reason']) : '';
    
    $update_sql = "UPDATE application SET status = '$new_status', reviewed_by = '$staff_id', reviewed_at = NOW()";
    if ($new_status == 'Rejected' && $rejection_reason) {
        $update_sql .= ", rejection_reason = '$rejection_reason'";
    }
    $update_sql .= " WHERE application_id = '$application_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        $success = "Application status updated successfully!";
    } else {
        $error = "Error updating status: " . mysqli_error($conn);
    }
}

// Get filter
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Get all applications with details
if ($status_filter) {
    $sql = "SELECT a.*, s.first_name, s.last_name, s.email, s.course, s.year_of_study, s.registration_number, 
                   i.title as internship_title, i.location, o.company_name
            FROM application a 
            JOIN student s ON a.student_id = s.student_id
            JOIN internship i ON a.internship_id = i.internship_id
            JOIN organization o ON i.organization_id = o.organization_id
            WHERE a.status = '$status_filter'
            ORDER BY a.application_date DESC";
} else {
    $sql = "SELECT a.*, s.first_name, s.last_name, s.email, s.course, s.year_of_study, s.registration_number, 
                   i.title as internship_title, i.location, o.company_name
            FROM application a 
            JOIN student s ON a.student_id = s.student_id
            JOIN internship i ON a.internship_id = i.internship_id
            JOIN organization o ON i.organization_id = o.organization_id
            ORDER BY a.application_date DESC";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Review Applications - KYU Internship</title>
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
        .shortlisted {
            color: #3498db;
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .btn-accept {
            background: #27ae60;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-reject {
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-shortlist {
            background: #3498db;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
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
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 20px;
            width: 400px;
            border-radius: 10px;
        }
        .cv-link {
            background: #3498db;
            color: white;
            padding: 3px 8px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <a href="staff_dashboard.php" class="back-link">← Back to Dashboard</a>
        
        <div class="header">
            <h2>Review Applications</h2>
            <p>Review and manage student internship applications</p>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="filter-bar">
            <a href="review_applications.php" <?php echo !$status_filter ? 'class="active"' : ''; ?>>All</a>
            <a href="" <?php echo $status_filter == 'Pending' ? 'class="active"' : ''; ?>>Pending</a>
            <a href="" <?php echo $status_filter == 'Shortlisted' ? 'class="active"' : ''; ?>>Shortlisted</a>
            <a href="" <?php echo $status_filter == 'Accepted' ? 'class="active"' : ''; ?>>Accepted</a>
            <a href="" <?php echo $status_filter == 'Rejected' ? 'class="active"' : ''; ?>>Rejected</a>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Internship</th>
                        <th>Company</th>
                        <th>Applied Date</th>
                        <th>CV</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <strong><?php echo $row['first_name'] . " " . $row['last_name']; ?></strong><br>
                                <small><?php echo $row['email']; ?></small><br>
                                <small><?php echo $row['registration_number']; ?></small>
                            </td>
                            <td><?php echo $row['course']; ?><br><small><?php echo $row['year_of_study']; ?></small></td>
                            <td><strong><?php echo $row['internship_title']; ?></strong></td>
                            <td><?php echo $row['company_name']; ?></td>
                            <td><?php echo $row['application_date']; ?></td>
                            <td>
                                <?php if($row['cv_path']): ?>
                                    <a href="<?php echo $row['cv_path']; ?>" target="_blank" class="cv-link">View CV</a>
                                <?php else: ?>
                                    No CV
                                <?php endif; ?>
                            </td>
                            <td class="<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                            <td>
                                <?php if($row['status'] == 'Pending'): ?>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                                        <input type="hidden" name="status" value="Shortlisted">
                                        <button type="submit" name="update_status" class="btn-shortlist">Shortlist</button>
                                    </form>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                                        <input type="hidden" name="status" value="Accepted">
                                        <button type="submit" name="update_status" class="btn-accept">Accept</button>
                                    </form>
                                    <button onClick="showRejectModal('<?php echo $row['application_id']; ?>')" class="btn-reject">Reject</button>
                                <?php elseif($row['status'] == 'Shortlisted'): ?>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                                        <input type="hidden" name="status" value="Accepted">
                                        <button type="submit" name="update_status" class="btn-accept">Accept</button>
                                    </form>
                                    <button onClick="showRejectModal('<?php echo $row['application_id']; ?>')" class="btn-reject">Reject</button>
                                <?php else: ?>
                                    <span>Reviewed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 10px;">
                <p>No applications found.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <h3>Reject Application</h3>
            <form method="POST" action="">
                <input type="hidden" name="application_id" id="reject_application_id">
                <input type="hidden" name="status" value="Rejected">
                <label>Reason for rejection:</label>
                <textarea name="rejection_reason" rows="3" style="width: 100%; margin: 10px 0; padding: 8px;" required></textarea>
                <button type="submit" name="update_status" class="btn-reject">Submit Rejection</button>
                <button type="button" onClick="closeModal()" style="background: #95a5a6; padding: 8px 15px; border: none; border-radius: 3px; cursor: pointer; margin-left: 10px;">Cancel</button>
            </form>
        </div>
    </div>
    
    <script>
        function showRejectModal(applicationId) {
            document.getElementById('reject_application_id').value = applicationId;
            document.getElementById('rejectModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('rejectModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == document.getElementById('rejectModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>