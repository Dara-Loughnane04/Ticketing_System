<?php
// Start the session so we can access the currently active session data
session_start();

// Clear/unset all session variables currently stored in memory
session_unset();

// Completely destroy the session on the server side
session_destroy();

// Redirect the user back to the login page after logging out
header("Location: login.php");
exit;
?>