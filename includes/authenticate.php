<?php
if (session_status() == PHP_SESSION_NONE) {
   session_start(); // Start the session only if it hasn't been started yet
}

// Check if already logged in, redirect to dashboard
if (!isset($_SESSION['token'])) {
   header(header: 'Location: /pages/auth');
   exit();
}
?>