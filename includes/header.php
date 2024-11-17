<?php

// Determine the correct path to the root based on the current page location
$basePath = '';
$sidebarPath = '';
if (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) {
    $basePath = '../';
   
    
}
if (strpos($_SERVER['PHP_SELF'], '/pages/application/') !== false || strpos($_SERVER['PHP_SELF'], '/pages/products/') !== false || strpos($_SERVER['PHP_SELF'], '/pages/bids/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/categories/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/orders/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/tags/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/users/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/stores/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/reviews/') !== false) {
    $basePath = '../../'; // For deeper subpages
    $sidebarPath = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Instabid
    </title>
    <link id="" href="<?php echo $basePath; ?>assets/css/index.css " rel="stylesheet" />
    <link id="" href="<?php echo $basePath; ?>assets/css/style.css " rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Load Chart.js -->
</head>

<body class="">
<?php include(''.$sidebarPath.'sidebar.php')?>

