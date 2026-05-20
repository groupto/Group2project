<?php
include 'config.php';

// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['student_id'])) {
    header("Location: student_dashboard.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    
    $sql = "SELECT * FROM student WHERE email = '$email' AND password = '$password' AND is_active = 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $student = mysqli_fetch_assoc($result);
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['student_name'] = $student['first_name'] . " " . $student['last_name'];
        $_SESSION['student_course'] = $student['course'];
        $_SESSION['student_year'] = $student['year_of_study'];
        $_SESSION['student_email'] = $student['email'];
        
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Login - KYU Internship</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 400px;
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
        input {
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
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background: #ffebee;
            border-radius: 5px;
        }
        .register-link {
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
        a:hover {
            text-decoration: underline;
        }
        label {
            font-weight: bold;
            color: #2c3e50;
            display: block;
        }
        .back-btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .back-btn:hover {
            background: #95a5a6;
            text-decoration: none;
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <h2>Student Login</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            
            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="student_register.php">Register here</a>
        </div>
        
        <div class="back-home">
            <a href="index.php" class="back-btn">← Back to Home</a>
        </div>
    </div>
</body>
</html>