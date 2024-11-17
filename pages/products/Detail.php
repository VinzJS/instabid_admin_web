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
            $category = $data['expand']['category'] ?? [];
            $store = $data['expand']['store'] ?? [];
            $tags = $data['expand']['tags'] ?? [];
            ?>
            <div>
                <label for="bidStart">Bid Start:</label>
                <span id="bidStart"><?= $data['bidStart'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="bidEnd">Bid End:</label>
                <span id="bidEnd"><?= $data['bidEnd'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="category">Category:</label>
                <?php if (isset($category) && !empty($category)): ?>
                    <a href="../categories/Detail.php?id=<?php echo htmlspecialchars($category['id']) ?? 0; ?>"><?php echo htmlspecialchars($category['name']) ?? ""; ?></a>
                <?php endif; ?>
            </div>
            <div>
                <label for="created">Created:</label>
                <span id="created"><?= $data['created'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="description">Description:</label>
                <span id="description"><?= $data['description'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="images">Images:</label>
                <?php
                $images = $data['images'] ?? [];
                foreach ($images as $image) {

                    echo "Image Name: " . $image . "<br>";
                }
                ?>
            </div>
            <div>
                <label for="incrementAmount">Increment Amount:</label>
                <span id="incrementAmount"><?= $data['incrementAmount'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="isBiddable">Is Biddable:</label>
                <span id="isBiddable"><?= $data['isBiddable'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="isPublished">Is Published:</label>
                <span id="isPublished"><?= $data['isPublished'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="isPurchased">Is Purchased:</label>
                <span id="isPurchased"><?= $data['isPurchased'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="minBid">Minimum Bid:</label>
                <span id="minBid"><?= $data['minBid'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="name">Name:</label>
                <span id="name"><?= $data['name'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="price">Price:</label>
                <span id="price"><?= $data['price'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="purchaseNotes">Purchase Notes:</label>
                <span id="purchaseNotes"><?= $data['purchaseNotes'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="sizeGuide">Size Guide:</label>
                <span id="sizeGuide"><?= $data['sizeGuide'] ?? 'N/A'; ?></span>
            </div>
            <div>
                <label for="store">Store:</label>
                <?php if (isset($store) && !empty($store)): ?>
                    <a href="../stores/Detail.php?id=<?php echo htmlspecialchars($store['id']); ?>"><?php echo htmlspecialchars($store['name']); ?></a>
                <?php endif; ?>
            </div>
            <div>
                <label for="tags">Tags:</label>
                <span id="tags">
                    <?php foreach ($tags as $tag): ?><br/>
                        <a href="../tags/Detail.php?id=<?php echo htmlspecialchars($tag['id']); ?>"><?php echo htmlspecialchars($tag['name']); ?></a>
                    <?php endforeach; ?>
                </span>
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