<?php
// Start the session
session_start();

// Destroy the session to log the user out
session_unset(); // Unsets all session variables
session_destroy(); // Destroys the session

// Redirect the user to the desired URL after logging out
header("Location: ../login.php");
exit(); // Ensure no further code is executed
?>
