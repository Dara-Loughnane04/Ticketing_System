<?php
// Start the session to identify who is logged in
session_start();

// Include the database connection file
require_once 'db.php';

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject']);
    $description = trim($_POST['ticket_description']);
    $userId = $_SESSION['user_id'];

    if (!empty($subject) && !empty($description)) {
        try {
            // Insert the new ticket into the database (status defaults to 'Open')
            $stmt = $pdo->prepare("INSERT INTO tickets (user_id, subject, ticket_description) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $subject, $description]);
            $message = "Ticket successfully opened! <a href='my_tickets.php'>Check your tickets</a>.";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
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
    <title>Open a Ticket</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f9; }
        .container { max-width: 500px; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type="text"], textarea { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        textarea { height: 120px; }
        input[type="submit"] { background: #007BFF; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; }
        input[type="submit"]:hover { background: #0056b3; }
        .back-link { display: block; margin-top: 15px; text-decoration: none; color: #007BFF; }
        .message { margin-bottom: 15px; color: green; }
    </style>
</head>
<body>
<div class="container">
    <h2>Open a New Support Ticket</h2>
    
    <!-- Display success or error messages -->
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Ticket Submission Form -->
    <form action="open_ticket.php" method="POST">
        <label>Subject:</label>
        <input type="text" name="subject" required>
        
        <label>Description:</label>
        <textarea name="ticket_description" required></textarea>
        
        <input type="submit" value="Submit Ticket">
    </form>
    
    <a href="user_menu.php" class="back-link">&larr; Back to Menu</a>
</div>
</body>
</html>