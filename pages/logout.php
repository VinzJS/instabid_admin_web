<?php
// Start session
// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started yet
}

// Destroy session and redirect to login page
session_destroy();
header('Location: ../index.php');
exit();
