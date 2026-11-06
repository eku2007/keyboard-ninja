
<?php

session_start();

// Clear all session data
$_SESSION = array();

// Destroy the session
session_destroy();

// Return to profile page
header("Location: profile.php");
exit();

?>
