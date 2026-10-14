<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit;
}

$message = '';

// Handle Resolve Ticket Action
if (isset($_POST['resolve_ticket'])) {
    $ticketId = $_POST['ticket_id'];
    $resolution = trim($_POST['resolution_details']);
    
    if (!empty($resolution)) {
        $stmt = $pdo->prepare("UPDATE tickets SET status = 'Resolved', resolution_details = ? WHERE ticket_id = ?");
        $stmt->execute([$resolution, $ticketId]);
        $message = "Ticket #$ticketId marked as Resolved.";
    } else {
        $message = "Resolution details cannot be empty.";
    }
}

// Handle Elevate Ticket Action
if (isset($_GET['elevate_id'])) {
    $ticketId = $_GET['elevate_id'];
    // Elevating a ticket keeps its status as Open, but you could log or flag support level changes if required
    $stmt = $pdo->prepare("UPDATE tickets SET ticket_description = CONCAT(ticket_description, ' [ELEVATED by agent]') WHERE ticket_id = ?");
    $stmt->execute([$ticketId]);
    $message = "Ticket #$ticketId has been elevated.";
}

// Fetch only Open tickets
$stmt = $pdo->prepare("SELECT tickets.*, users.full_name AS user_name FROM tickets JOIN users ON tickets.user_id = users.user_id WHERE status = 'Open' ORDER BY date_submitted DESC");
$stmt->execute();
$openTickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Open Tickets</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f4f9; }
        .container { max-width: 900px; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; font-size: 14px; }
        th { background-color: #6c757d; color: white; }
        textarea { width: 100%; height: 50px; margin-bottom: 5px; }
        .btn { padding: 5px 10px; color: white; border: none; cursor: pointer; border-radius: 3px; font-size: 12px; text-decoration: none; display: inline-block; }
        .btn-resolve { background: #28a745; }
        .btn-elevate { background: #ffc107; color: black; }
        .message { margin-bottom: 15px; color: green; font-weight: bold; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #007BFF; }
    </style>
</head>
<body>
<div class="container">
    <h2>Open Support Tickets</h2>
    
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (count($openTickets) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Subject & Description</th>
                <th>Date Submitted</th>
                <th>Actions / Resolve</th>
            </tr>
            <?php foreach ($openTickets as $ticket): ?>
            <tr>
                <td><?php echo $ticket['ticket_id']; ?></td>
                <td><?php echo htmlspecialchars($ticket['user_name']); ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($ticket['subject']); ?></strong><br>
                    <?php echo htmlspecialchars($ticket['ticket_description']); ?>
                </td>
                <td><?php echo $ticket['date_submitted']; ?></td>
                <td>
                    <!-- Form to resolve ticket -->
                    <form action="search_tickets.php" method="POST" style="margin-bottom: 5px;">
                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticket_id']; ?>">
                        <textarea name="resolution_details" placeholder="Enter solution..." required></textarea>
                        <button type="submit" name="resolve_ticket" class="btn btn-resolve">Resolve</button>
                    </main>
                    <!-- Link to elevate ticket -->
                    <a href="search_tickets.php?elevate_id=<?php echo $ticket['ticket_id']; ?>" class="btn btn-elevate" onclick="return confirm('Elevate this ticket?');">Elevate</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>There are currently no open tickets.</p>
    <?php endif; ?>
    
    <a href="agent_menu.php" class="back-link">&larr; Back to Agent Menu</a>
</div>
</body>
</html>