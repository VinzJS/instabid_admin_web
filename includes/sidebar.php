<?php
// Determine the correct path to the root based on the current page location
$basePath = '';
if (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) {
    $basePath = '';
}
if (strpos($_SERVER['PHP_SELF'], '/pages/products/') !== false || strpos($_SERVER['PHP_SELF'], '/pages/bids/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/categories/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/orders/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/tags/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/users/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/stores/') !== false|| strpos($_SERVER['PHP_SELF'], '/pages/reviews/') !== false) {
    $basePath = '../'; // For deeper subpages
}
?>
<nav>
    <a class="nav-link text-white" href="<?php echo $basePath; ?>dashboard.php">
        <span class="nav-link-text ">Dashboard</span>
    </a>
    <a class="nav-link text-white" href="<?php echo $basePath; ?>users/index.php">
          <span class="nav-link-text ">Users</span>
      </a>
    <a class="nav-link text-white " href="<?php echo $basePath; ?>products/index.php">
        <span class="nav-link-text ">Products</span>
    </a>
    <a class="nav-link text-white" href="<?php echo $basePath; ?>categories/index.php">
        <span class="nav-link-text ">Categories</span>
      </a>

     <a class="nav-link text-white" href="<?php echo $basePath; ?>orders/index.php">
          <span class="nav-link-text ">Orders</span>
      </a>
      <a class="nav-link text-white" href="<?php echo $basePath; ?>stores/index.php">
          <span class="nav-link-text ">Stores</span>
      </a>
      <a class="nav-link text-white " href="<?php echo $basePath; ?>bids/index.php">
      <span class="nav-link-text ">Bids</span>
      </a>

      <a class="nav-link text-white" href="<?php echo $basePath; ?>reviews/index.php">
          <span class="nav-link-text ">Reviews</span>
      </a>

      <a class="nav-link text-white" href="<?php echo $basePath; ?>tags/index.php">
          <span class="nav-link-text ">Tags</span>
      </a>
      <a class="nav-link text-white " href="<?php echo $basePath; ?>logout.php">
      <span class="nav-link-text ">Logout</span>
      </a>


</nav>
