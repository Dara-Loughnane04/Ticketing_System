<?php
session_start();
if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Menu</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f9; }
        .container { max-width: 400px; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .menu-link { display: block; background: #6c757d; color: white; padding: 10px; margin: 10px 0; text-align: center; text-decoration: none; border-radius: 4px; }
        .menu-link:hover { background: #5a6268; }
        .logout-link { background: #dc3545; }
        .logout-link:hover { background: #c82333; }
    </style>
</head>
<body>
<div class="container">
    <h2>Agent Dashboard: <?php echo htmlspecialchars($_SESSION['agent_name']); ?></h2>
    <p>Support Level: <?php echo $_SESSION['support_level']; ?></p>
    
    <a href="search_tickets.php" class="menu-link">Search Open Tickets</a>
    <a href="logout.php" class="menu-link logout-link">Logout</a>
</div>
</body>
</html>