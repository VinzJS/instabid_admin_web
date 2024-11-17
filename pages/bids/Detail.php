<?php include('../authenticate.php') ?>
<?php
$data = include '../records/_detail.php';
?>
<?php include('../../includes/header.php') ?>
<div class="main-wrapper">
    <main>
        <h1>Detail</h1>

        <div class="data-info">
            <?php
            $data = $bid['expand']['user'] ?? [];
            ?>
            <div>
                <label for="name">Name:</label>
                <span id="name"><?= $data['name'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="address">Address:</label>
                <span id="address"><?= $data['address'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="description">Description:</label>
                <span id="description"><?= $data['description'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="user">User:</label>
                <?php if (isset($data) && !empty($data)): ?>
                    <a href="../users/Detail.php?id=<?php echo htmlspecialchars($data['id']); ?>"><?php echo htmlspecialchars($data['name']); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <a href="index.php" class="back-btn"> <span>Back</span></a>
    </main>
</div>
<?php include('../../includes/footer.php') ?>