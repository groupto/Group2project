<?php
include 'config.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

// Get internship ID from URL
$internship_id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($internship_id)) {
    header("Location: view_internships.php");
    exit();
}

// Get internship details
$sql = "SELECT i.*, o.company_name 
        FROM internship i 
        JOIN organization o ON i.organization_id = o.organization_id 
        WHERE i.internship_id = '$internship_id'";
$result = mysqli_query($conn, $sql);
$internship = mysqli_fetch_assoc($result);

if (!$internship) {
    header("Location: view_internships.php");
    exit();
}

// Check if already applied
$check_app = "SELECT * FROM application WHERE student_id = '{$_SESSION['student_id']}' AND internship_id = '$internship_id'";
$app_result = mysqli_query($conn, $check_app);

if (mysqli_num_rows($app_result) > 0) {
    echo "<script>alert('You have already applied for this internship!'); window.location='view_internships.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Internship - KYU Internship</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            margin-top: 0;
        }
        .internship-info {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 15px 0 5px 0;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            background: #27ae60;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        button:hover {
            background: #229954;
        }
        .back-btn {
            background: #7f8c8d;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <a href="view_internships.php" class="back-btn">← Back</a>
        
        <h2>📝 Apply for Internship</h2>
        
        <div class="internship-info">
            <h3><?php echo $internship['title']; ?></h3>
            <p><strong>Company:</strong> <?php echo $internship['company_name']; ?></p>
            <p><strong>Location:</strong> <?php echo $internship['location']; ?></p>
            <p><strong>Duration:</strong> <?php echo $internship['duration']; ?></p>
            <p><strong>Stipend:</strong> <?php echo $internship['stipend']; ?></p>
            <p><strong>Required Skills:</strong> <?php echo $internship['required_skills']; ?></p>
            <p><strong>Description:</strong> <?php echo nl2br($internship['description']); ?></p>
            <form name="form1" method="post" action="">
              <label for="select">GENDER</label>
              <select name="select" id="select">
                <option value="2">DOG</option>
                <option value="4">PIG</option>
              </select>
            </form>
            <p>&nbsp;</p>
        </div>
        
        <form method="POST" action="submit_application.php" enctype="multipart/form-data">
            <input type="hidden" name="internship_id" value="<?php echo $internship_id; ?>">
            
            <label>Cover Letter:</label>
            <textarea name="cover_letter" placeholder="Tell us why you're a good fit for this internship..." required></textarea>
            
            <label>Upload CV (PDF or DOC):</label>
            <input type="file" name="cv" accept=".pdf,.doc,.docx" required>
            
            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>
</html>