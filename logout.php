<?php
// Loads the config file and starts the session if needed
require_once 'config/config.php';

// Clears all session variables
session_unset();

// Destroys the current session
session_destroy();

// Sends the user back to the homepage
header('Location: index.php');
exit;
?>
