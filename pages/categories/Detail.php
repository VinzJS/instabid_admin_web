<?php include('../authenticate.php') ?>
<?php
$data = include '../records/_detail.php';
?>
<?php include('../../includes/header.php') ?>
<div class="main-wrapper">
    <main>
        <h1>Category Detail</h1>

        <div class="data-info">
            <?php
            $store = $data['expand']['store'] ?? [];
            ?>
            <div>
                <label for="name">Name:</label>
                <span id="name"><?= $data['name'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="parent">Parent:</label>
                <span id="parent"><?= $data['parent'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="photo">Photo:</label>
                <span id="photo"><?= $data['photo'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="store">Store:</label>
                <?php if (isset($store) && !empty($store)): ?>
                    <a href="../stores/Detail.php?id=<?php echo htmlspecialchars($store['id']); ?>"><?php echo htmlspecialchars($store['name']); ?></a>
                <?php endif; ?>
            </div>
            <div>
                <label for="updated">Updated:</label>
                <span id="updated"><?= $data['updated'] ?? 'N/A'; ?></span>
            </div>
        </div>
        <a href="index.php" class="back-btn"> <span>Back</span></a>
    </main>
</div>
<?php include('../../includes/footer.php') ?>