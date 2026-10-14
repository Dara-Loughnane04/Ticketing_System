<?php
// Include the database connection file to connect to MySQL via PDO
require_once 'db.php';

// Initialize an empty message variable to display status alerts
$message = '';

// Check if the form has been submitted using the HTTP POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and clean user inputs to remove unnecessary whitespace
    $fullName = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Ensure all fields are filled out before proceeding
    if (!empty($fullName) && !empty($username) && !empty($password)) {
        // Securely hash the password before saving it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Prepare an SQL statement to insert the new user into the database securely
            $stmt = $pdo->prepare("INSERT INTO users (full_name, username, password) VALUES (?, ?, ?)");
            $stmt->execute([$fullName, $username, $hashedPassword]);
            $message = "Registration successful! You can now <a href='login.php'>login</a>.";
        } catch (PDOException $e) {
            // Check if the error is due to a duplicate username (SQL error code 23000)
            if ($e->getCode() == 23000) {
                $message = "Error: Username already exists.";
            } else {
                $message = "Error: " . $e->getMessage();
            }
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <style>
        /* Basic clean styling for a beginner-friendly layout */
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f9; }
        .container { max-width: 400px; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        input[type="submit"] { background: #007BFF; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; }
        input[type="submit"]:hover { background: #0056b3; }
        .message { margin-bottom: 15px; color: green; }
    </style>
</head>
<body>
<div class="container">
    <h2>User Registration</h2>
    
    <!-- Display success or error messages dynamically -->
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Registration Form submitting back to this same PHP script -->
    <form action="register.php" method="POST">
        <label>Full Name:</label>
        <input type="text" name="full_name" required>
        
        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <input type="submit" value="Register">
    </form>
    
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>