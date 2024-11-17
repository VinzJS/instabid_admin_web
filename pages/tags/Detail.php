<?php include('../authenticate.php') ?>
<?php
$data = include '../records/_detail.php';
?>
<?php include('../../includes/header.php') ?>
<div class="main-wrapper">
<main>
    <h1>Detail</h1>

    <div class="details">
        <div class="detail-row">
        
        <label class="label">Name :  <?php echo htmlspecialchars($data['name']); ?> </label>
       
        <label class="label">Created :  <?php
                $dateString = $data['created'];
                $date = new DateTime($dateString);
                ?>
                <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
        </label>

        </div>
    </div>
    <a href="index.php" class="back-btn"> <span>Back</span></a>
</main>
</div>
<?php include('../../includes/footer.php') ?>