<?php
// Start a session to remember that the user is logged in across pages
session_start();

// Include the database connection file
require_once 'db.php';

$message = '';

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Look up the user in the database by their username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Check if user exists and verify their password
        if ($user && password_verify($password, $user['password'])) {
            // Store user details in session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            
            // Redirect to the user menu page
            header("Location: user_menu.php");
            exit;
        } else {
            $message = "Invalid username or password.";
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
    <title>User Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f9; }
        .container { max-width: 400px; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        input[type="submit"] { background: #28a745; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; }
        input[type="submit"]:hover { background: #218838; }
        .message { margin-bottom: 15px; color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>User Login</h2>
    
    <!-- Show error message if login fails -->
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="login.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <input type="submit" value="Login">
    </form>
    
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>