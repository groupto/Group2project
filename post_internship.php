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

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $required_skills = mysqli_real_escape_string($conn, $_POST['required_skills']);
    $course_required = mysqli_real_escape_string($conn, $_POST['course_required']);
    $year_required = mysqli_real_escape_string($conn, $_POST['year_required']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $location_type = mysqli_real_escape_string($conn, $_POST['location_type']);
    $stipend = mysqli_real_escape_string($conn, $_POST['stipend']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $application_deadline = mysqli_real_escape_string($conn, $_POST['application_deadline']);
    $available_positions = mysqli_real_escape_string($conn, $_POST['available_positions']);
    
    // Generate internship ID
    $internship_id = "INT-" . date("Y") . "-" . rand(100, 999);
    
    // Get a staff member to post (using first staff as default)
    $staff_query = "SELECT staff_id FROM staff LIMIT 1";
    $staff_result = mysqli_query($conn, $staff_query);
    $staff = mysqli_fetch_assoc($staff_result);
    $posted_by = $staff['staff_id'];
    
    $sql = "INSERT INTO internship (internship_id, organization_id, posted_by, title, description, required_skills, course_required, year_required, location, location_type, stipend, duration, start_date, application_deadline, available_positions, status, is_approved) 
            VALUES ('$internship_id', '$org_id', '$posted_by', '$title', '$description', '$required_skills', '$course_required', '$year_required', '$location', '$location_type', '$stipend', '$duration', '$start_date', '$application_deadline', '$available_positions', 'Open', 1)";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Internship posted successfully! Your Internship ID is: " . $internship_id;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post Internship - KYU Internship</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .org-info {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: Arial;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            background: #27ae60;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background: #229954;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background: #e8f5e9;
            border-radius: 5px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background: #ffebee;
            border-radius: 5px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
        label {
            font-weight: bold;
            color: #2c3e50;
            display: block;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <a href="organization_dashboard.php" class="back-link">← Back to Dashboard</a>
        
        <h2>Post New Internship</h2>
        
        <div class="org-info">
            <strong>Organization:</strong> <?php echo $org_name; ?>
        </div>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Internship Title:*</label>
            <input type="text" name="title" placeholder="e.g., Software Development Intern" required>
            
            <label>Description:*</label>
            <textarea name="description" placeholder="Describe the internship responsibilities and expectations..." required></textarea>
            
            <label>Required Skills:*</label>
            <textarea name="required_skills" placeholder="List required skills (e.g., Python, JavaScript, Communication)" required></textarea>
            
            <div class="form-row">
                <div>
                    <label>Course Required:*</label>
                    <input type="text" name="course_required" placeholder="e.g., Computer Science" required>
                </div>
                <div>
                    <label>Year Required:*</label>
                    <select name="year_required" required>
                        <option value="">Select Year</option>
                        <option>Year 1</option>
                        <option>Year 2</option>
                        <option>Year 3</option>
                        <option>Year 4</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div>
                    <label>Location:*</label>
                    <input type="text" name="location" placeholder="e.g., Kampala, Uganda" required>
                </div>
                <div>
                    <label>Location Type:</label>
                    <select name="location_type">
                        <option value="On-site">On-site</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Remote">Remote</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div>
                    <label>Stipend:</label>
                    <input type="text" name="stipend" placeholder="e.g., UGX 300,000">
                </div>
                <div>
                    <label>Duration:*</label>
                    <input type="text" name="duration" placeholder="e.g., 3 months" required>
                </div>
            </div>
            
            <div class="form-row">
                <div>
                    <label>Start Date:*</label>
                    <input type="date" name="start_date" required>
                </div>
                <div>
                    <label>Application Deadline:*</label>
                    <input type="date" name="application_deadline" required>
                </div>
            </div>
            
            <div class="form-row">
                <div>
                    <label>Available Positions:</label>
                    <input type="number" name="available_positions" value="1">
                </div>
            </div>
            
            <button type="submit">Post Internship</button>
        </form>
    </div>
</body>
</html>