<?php
include 'config.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $year_of_study = mysqli_real_escape_string($conn, $_POST['year_of_study']);
    $registration_number = mysqli_real_escape_string($conn, $_POST['registration_number']);
    $password = md5($_POST['password']);
    $university_id = 'UNI-001';
    
    $student_id = "STU-" . date("Y") . "-" . rand(100, 999);
    
    $check = "SELECT * FROM student WHERE email = '$email'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Email already registered! Please use a different email.";
    } else {
        $sql = "INSERT INTO student (student_id, university_id, first_name, last_name, email, phone, course, year_of_study, registration_number, password) 
                VALUES ('$student_id', '$university_id', '$first_name', '$last_name', '$email', '$phone', '$course', '$year_of_study', '$registration_number', '$password')";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Registration successful! Your Student ID is: " . $student_id;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration - KYU Internship</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #27ae60;
            color: white;
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
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-home {
            text-align: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        label {
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-top: 10px;
        }
        .back-btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: #95a5a6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Registration</h2>
        
        <?php if ($success): ?>
            <div class="success">
                <?php echo $success; ?>
                <br><br>
                <a href="student_login.php">Click here to login</a>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>First Name:</label>
            <input type="text" name="first_name" required>
            
            <label>Last Name:</label>
            <input type="text" name="last_name" required>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Phone:</label>
            <input type="text" name="phone">
            
            <label>Course:</label>
            <input type="text" name="course" placeholder="e.g., Computer Science" required>
            
            <label>Year of Study:</label>
            <select name="year_of_study" required>
                <option value="">Select Year</option>
                <option>Year 1</option>
                <option>Year 2</option>
                <option>Year 3</option>
                <option>Year 4</option>
            </select>
            
            <label>Registration Number:</label>
            <input type="text" name="registration_number" placeholder="e.g., KYU/CS/2021/001" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Register</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="student_login.php">Login here</a>
        </div>
        
        <div class="back-home">
            <a href="index.php" class="back-btn">← Back to Home</a>
        </div>
    </div>
</body>
</html>