<?php 
session_start();
require 'db.php';

$error = "";

if (isset($_POST['agent_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM agents WHERE username = ?");
    $stmt->execute([$username]);
    $agent = $stmt->fetch();

    if ($agent && password_verify($password, $agent['password'])) {
        $_SESSION['agent_id'] = $agent['agent_id'];
        $_SESSION['agent_username'] = $agent['username'];
        header("Location: search_tickets.php");
        exit();
    } else {
        $error = "Invalid agent credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Agent Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 50px; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; background-color: #007bff; color: white; padding: 10px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
        .link { text-align: center; margin-top: 15px; font-size: 14px; }
        .link a { color: #007bff; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <form action="agent_login.php" method="POST">
        <h2>Support Agent Login</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit" name="agent_login">Login</button>
    </form>
    
    <div class="link">
        <p>Need an agent account? <a href="register_agent.php">Register here</a></p>
    </div>
</div>

</body>
</html>