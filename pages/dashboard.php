<?php
include('../includes/initialize.php');


$Items = include '_fetchallrecords.php';
include('../includes/header.php');
?>
<div class="main-wrapper">
    <main>
        <h1>Overview</h1>
        <div class="dashboard">
            <?php foreach ($Items as $collection => $data): ?>
                <div class="stat-box <?php echo strtolower($data['color']) ?>">
                    <div class="stat-number"><?php echo htmlspecialchars($data['totalItems']); ?></div>
                    <div class="stat-title"><?php echo ucfirst($collection); ?></div>
                    <a href="<?php echo strtolower($collection) ?>/index.php" class="stat-link">More info</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>
<?php include('_charts.php') ?>
<?php include('../includes/footer.php') ?>