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
            $product = $data['expand']['product'] ?? [];
            $data = $data['expand']['user'] ?? [];
            ?>
            <div>
                <label for="amount">Amount:</label>
                <span id="amount"><?= $data['amount'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="bid">Bid:</label>
                <span id="bid"><?= $data['bid'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="created">Created:</label>
                <span id="created"><?= $data['created'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="product">Product:</label>
                <?php if (isset($product) && !empty($product)): ?>
                    <a href="../products/Detail.php?id=<?php echo htmlspecialchars($product['id']); ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                <?php endif; ?>
            </div>
            <div>
                <label for="status">Status:</label>
                <span id="status"><?= $data['status'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="updated">Updated:</label>
                <span id="updated"><?= $data['updated'] ?? 'N/A'; ?></span>
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