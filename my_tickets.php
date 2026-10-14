<?php
// Start the session to identify the logged-in user
session_start();

// Include the database connection file
require_once 'db.php';

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Handle ticket closing action if requested
if (isset($_GET['close_id'])) {
    $closeId = $_GET['close_id'];
    // Update the ticket status to 'Closed' only if it belongs to this user and is currently 'Resolved'
    $stmt = $pdo->prepare("UPDATE tickets SET status = 'Closed' WHERE ticket_id = ? AND user_id = ? AND status = 'Resolved'");
    $stmt->execute([$closeId, $userId]);
    header("Location: my_tickets.php");
    exit;
}

// Fetch all tickets belonging to this user
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY date_submitted DESC");
$stmt->execute([$userId]);
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check My Tickets</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f9; }
        .container { max-width: 800px; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007BFF; color: white; }
        .status-open { color: #d9534f; font-weight: bold; }
        .status-resolved { color: #f0ad4e; font-weight: bold; }
        .status-closed { color: #5cb85c; font-weight: bold; }
        .btn-close { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .btn-close:hover { background: #c82333; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #007BFF; }
    </style>
</head>
<body>
<div class="container">
    <h2>My Support Tickets</h2>
    
    <?php if (count($tickets) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Description</th>
                <th>Status</th>
                <th>Resolution Details</th>
                <th>Action</th>
            </tr>
            <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?php echo $ticket['ticket_id']; ?></td>
                <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                <td><?php echo htmlspecialchars($ticket['ticket_description']); ?></td>
                <td>
                    <span class="status-<?php echo strtolower($ticket['status']); ?>">
                        <?php echo $ticket['status']; ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($ticket['resolution_details'] ?? 'None yet'); ?></td>
                <td>
                    <!-- Allow user to close the ticket only if it has been marked as Resolved by an agent -->
                    <?php if ($ticket['status'] === 'Resolved'): ?>
                        <a href="my_tickets.php?close_id=<?php echo $ticket['ticket_id']; ?>" class="btn-close" onclick="return confirm('Are you sure you want to close this ticket?');">Close Ticket</a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You have not submitted any tickets yet.</p>
    <?php endif; ?>
    
    <a href="user_menu.php" class="back-link">&larr; Back to Menu</a>
</div>
</body>
</html>