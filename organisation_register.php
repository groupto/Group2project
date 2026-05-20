<?php
include 'config.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $company_size = mysqli_real_escape_string($conn, $_POST['company_size']);
    $contact_person = mysqli_real_escape_string($conn, $_POST['contact_person']);
    $contact_email = mysqli_real_escape_string($conn, $_POST['contact_email']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $password = md5($_POST['password']);
    
    // Generate organization ID
    $org_id = "ORG-" . date("Y") . "-" . rand(100, 999);
    
    // Check if email already exists
    $check = "SELECT * FROM organization WHERE contact_email = '$contact_email'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Email already registered! Please use a different email.";
    } else {
        $sql = "INSERT INTO organization (organization_id, company_name, industry, company_size, contact_person, contact_email, contact_phone, address, city, website, description, password, is_active) 
                VALUES ('$org_id', '$company_name', '$industry', '$company_size', '$contact_person', '$contact_email', '$contact_phone', '$address', '$city', '$website', '$description', '$password', 1)";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Registration successful! Your Organization ID is: " . $org_id;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Organization Registration - KYU Internship</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
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
            min-height: 80px;
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
    <div class="container">
        <h2>🏢 Organization Registration</h2>
        
        <?php if ($success): ?>
            <div class="success">
                <?php echo $success; ?>
                <br><br>
                <a href="organization_login.php">Click here to login</a>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label>Company Name:*</label>
            <input type="text" name="company_name" placeholder="Enter company name" required>
            
            <label>Industry:*</label>
            <input type="text" name="industry" placeholder="e.g., IT, Banking, Healthcare" required>
            
            <label>Company Size:</label>
            <select name="company_size">
                <option value="">Select size</option>
                <option>1-10 employees</option>
                <option>11-50 employees</option>
                <option>51-200 employees</option>
                <option>201-500 employees</option>
                <option>500+ employees</option>
            </select>
            
            <label>Contact Person:*</label>
            <input type="text" name="contact_person" placeholder="Full name of contact person" required>
            
            <label>Contact Email:*</label>
            <input type="email" name="contact_email" placeholder="company@email.com" required>
            
            <label>Contact Phone:*</label>
            <input type="text" name="contact_phone" placeholder="+256-XXX-XXXXXX" required>
            
            <label>Address:*</label>
            <input type="text" name="address" placeholder="Physical address" required>
            
            <label>City:</label>
            <input type="text" name="city" placeholder="e.g., Kampala">
            
            <label>Website:</label>
            <input type="url" name="website" placeholder="https://www.company.com">
            
            <label>Company Description:</label>
            <textarea name="description" placeholder="Brief description of your company..."></textarea>
            
            <label>Password:*</label>
            <input type="password" name="password" placeholder="Create password" required>
            
            <button type="submit">Register Organization</button>
        </form>
        
        <div class="login-link">
            Already registered? <a href="organization_login.php">Login here</a>
        </div>
        
        <div class="back-home">
            <a href="index.php" class="back-btn">← Back to Home</a>
        </div>
    </div>
</body>
</html>