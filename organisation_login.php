<?php
include 'config.php';

if (!isset($_SESSION)) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['org_id'])) {
    header("Location: organization_dashboard.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    
    $sql = "SELECT * FROM organization WHERE contact_email = '$email' AND password = '$password' AND is_active = 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $org = mysqli_fetch_assoc($result);
        $_SESSION['org_id'] = $org['organization_id'];
        $_SESSION['org_name'] = $org['company_name'];
        $_SESSION['org_email'] = $org['contact_email'];
        
        header("Location: organization_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Organization Login - KYU Internship</title>
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
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background: #2980b9;
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
        }
        .back-btn:hover {
            background: #95a5a6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🏢 Organization Login</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Company Email:</label>
            <input type="email" name="email" placeholder="Enter company email" required>
            
            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="register-link">
            New organization? <a href="organization_register.php">Register here</a>
        </div>
        
        <div class="back-home">
            <a href="index.php" class="back-btn">← Back to Home</a>
        </div>
    </div>
</body>
</html>