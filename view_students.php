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

// Handle delete student (admin only)
if (isset($_GET['delete']) && $is_admin) {
    $student_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_sql = "DELETE FROM student WHERE student_id = '$student_id'";
    if (mysqli_query($conn, $delete_sql)) {
        $success = "Student deleted successfully!";
    } else {
        $error = "Error deleting student: " . mysqli_error($conn);
    }
}

// Handle deactivate student
if (isset($_GET['deactivate'])) {
    $student_id = mysqli_real_escape_string($conn, $_GET['deactivate']);
    $deactivate_sql = "UPDATE student SET is_active = 0 WHERE student_id = '$student_id'";
    if (mysqli_query($conn, $deactivate_sql)) {
        $success = "Student deactivated successfully!";
    } else {
        $error = "Error deactivating student: " . mysqli_error($conn);
    }
}

// Handle activate student
if (isset($_GET['activate'])) {
    $student_id = mysqli_real_escape_string($conn, $_GET['activate']);
    $activate_sql = "UPDATE student SET is_active = 1 WHERE student_id = '$student_id'";
    if (mysqli_query($conn, $activate_sql)) {
        $success = "Student activated successfully!";
    } else {
        $error = "Error activating student: " . mysqli_error($conn);
    }
}

// Get filter
$course_filter = isset($_GET['course']) ? $_GET['course'] : '';
$year_filter = isset($_GET['year']) ? $_GET['year'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build query
$sql = "SELECT * FROM student WHERE 1=1";
if ($course_filter) {
    $sql .= " AND course = '$course_filter'";
}
if ($year_filter) {
    $sql .= " AND year_of_study = '$year_filter'";
}
if ($status_filter) {
    $sql .= " AND is_active = " . ($status_filter == 'active' ? 1 : 0);
}
$sql .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

// Get distinct courses for filter
$courses_result = mysqli_query($conn, "SELECT DISTINCT course FROM student ORDER BY course");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students - KYU Internship</title>
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
        .active {
            color: green;
            font-weight: bold;
        }
        .inactive {
            color: red;
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
        }
        .btn-deactivate {
            background: #e67e22;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
        }
        .btn-activate {
            background: #27ae60;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
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
        .filter-bar {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        .filter-bar select, .filter-bar input {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .filter-bar button {
            background: #e67e22;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .filter-bar a {
            padding: 8px 15px;
            background: #ecf0f1;
            text-decoration: none;
            color: #2c3e50;
            border-radius: 20px;
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
        .student-count {
            background: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="staff_dashboard.php" class="back-link">← Back to Dashboard</a>
        
        <div class="header">
            <h2>View Students</h2>
            <p>View and manage all registered students</p>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="filter-bar">
            <form method="GET" action="" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <select name="course">
                    <option value="">All Courses</option>
                    <?php while($course = mysqli_fetch_assoc($courses_result)): ?>
                        <option value="<?php echo $course['course']; ?>" <?php echo ($course_filter == $course['course']) ? 'selected' : ''; ?>>
                            <?php echo $course['course']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <select name="year">
                    <option value="">All Years</option>
                    <option value="Year 1" <?php echo ($year_filter == 'Year 1') ? 'selected' : ''; ?>>Year 1</option>
                    <option value="Year 2" <?php echo ($year_filter == 'Year 2') ? 'selected' : ''; ?>>Year 2</option>
                    <option value="Year 3" <?php echo ($year_filter == 'Year 3') ? 'selected' : ''; ?>>Year 3</option>
                    <option value="Year 4" <?php echo ($year_filter == 'Year 4') ? 'selected' : ''; ?>>Year 4</option>
                </select>
                
                <select name="status">
                    <option value="">All Status</option>
                    <option value="active" <?php echo ($status_filter == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($status_filter == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
                
                <button type="submit">Filter</button>
                <a href="view_students.php">Clear Filters</a>
            </form>
        </div>
        
        <div class="student-count">
            Total Students: <?php echo mysqli_num_rows($result); ?>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Reg Number</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['student_id']; ?></td>
                            <td><strong><?php echo $row['first_name'] . " " . $row['last_name']; ?></strong></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['course']; ?></td>
                            <td><?php echo $row['year_of_study']; ?></td>
                            <td><?php echo $row['registration_number']; ?></td>
                            <td class="<?php echo $row['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="view_student_details.php?id=<?php echo $row['student_id']; ?>" class="btn-view">View</a>
                                <?php if($row['is_active']): ?>
                                    <a href="?deactivate=<?php echo $row['student_id']; ?>" class="btn-deactivate" onclick="return confirm('Deactivate this student?')">Deactivate</a>
                                <?php else: ?>
                                    <a href="?activate=<?php echo $row['student_id']; ?>" class="btn-activate" onclick="return confirm('Activate this student?')">Activate</a>
                                <?php endif; ?>
                                <?php if($is_admin): ?>
                                    <a href="?delete=<?php echo $row['student_id']; ?>" class="btn-delete" onclick="return confirm('Delete this student? This cannot be undone!')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 10px;">
                <p>No students found.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>