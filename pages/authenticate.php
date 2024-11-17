<?php
// Determine the correct path to the root based on the current page location
$basePath = '';
$sidebarPath = '';
if (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) {
    $basePath = '../';
 
}
if (strpos($_SERVER['PHP_SELF'], '/pages/products/') !== false || strpos($_SERVER['PHP_SELF'], '/pages/bids/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/categories/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/orders/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/tags/') !== false || strpos($_SERVER['PHP_SELF'], '/pages/users/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/stores/') !== false || strpos($_SERVER['PHP_SELF'], '/pages/reviews/') !== false || strpos($_SERVER['PHP_SELF'], '/pages/records/') !== false) {
    $basePath = '../../'; // For deeper subpages
    $sidebarPath = '';


}
// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started yet
}
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['token'])) {
    header('Location: '.$basePath.'index.php');
    exit();
}
