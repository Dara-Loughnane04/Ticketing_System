<?php
require 'db.php';

$error = "";

if (isset($_POST['register_agent'])) {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO agents (username, password, full_name) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$username, $password, $full_name])) {
        header("Location: agent_login.php");
        exit();
    } else {
        $error = "Agent registration failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Agent Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 50px; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; background-color: #28a745; color: white; padding: 10px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
        .link { text-align: center; margin-top: 15px; font-size: 14px; }
        .link a { color: #007bff; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container"> 
    <form action="register_agent.php" method="POST">
        <h2>Agent Registration</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <label>Full Name:</label>
        <input type="text" name="full_name" required>
        
        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit" name="register_agent">Register Agent</button>
    </form>
    
    <div class="link">
        <p>Already have an agent account? <a href="agent_login.php">Log in here</a></p>
    </div>
</div>

</body>
</html>