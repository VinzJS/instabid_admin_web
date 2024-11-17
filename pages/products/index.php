<?php include('../authenticate.php') ?>
<?php include('../../includes/header.php') ?>
<?php
// Include the data fetching file
$products = include '../records/_lists.php';
?>
<main>
    <h1>Products</h1>
    <div class="row" style="  overflow-y: scroll;">
        <table>
            <thead>
                <tr>
                    <th>name</th>
                    <th>price</th>
                    <th>bidStart</th>
                    <th>bidEnd</th>
                    <th>category</th>
                    <th>description</th>
                    <th>images</th>
                    <th>store</th>
                    <th>tags</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products['items'] ?? [] as $product): ?>
                    <?php
                    $category = $product['expand']['category'] ?? [];
                    $store = $product['expand']['store'] ?? [];
                    $tags = $product['expand']['tags'] ?? [];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td>
                            <?php
                            $dateString = $product['bidStart'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td>
                            <?php
                            $dateString = $product['bidEnd'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td>
                            <?php if (isset($category) && !empty($category)): ?>
                                <a href="../categories/Detail.php?id=<?php echo htmlspecialchars($category['id']) ?? 0; ?>"><?php echo htmlspecialchars($category['name']) ?? ""; ?></a>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>
                            <?php
                            $images = $product['images'] ?? [];
                            foreach ($images as $image) {

                                echo "Image Name: " . $image . "<br>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php if (isset($store) && !empty($store)): ?>
                                <a href="../stores/Detail.php?id=<?php echo htmlspecialchars($store['id']); ?>"><?php echo htmlspecialchars($store['name']); ?></a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php foreach ($tags as $tag): ?>
                                <a href="../tags/Detail.php?id=<?php echo htmlspecialchars($tag['id']); ?>"><?php echo htmlspecialchars($tag['name']); ?></a>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <!-- <a href="#" class="action-btn view-btn">View</a> -->
                            <a href="Detail.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="action-btn edit-btn">View</a>
                            <form method="post" action="../records/_delete.php?collection=products" id="deleteForm<?php echo htmlspecialchars($product['id']); ?>">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                <button type="button" onclick="confirmDelete('<?php echo htmlspecialchars($product['id']); ?>')" class="action-btn delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>
</main>
<!-- JavaScript function to handle confirmation -->
<script type="text/javascript">
    function confirmDelete(userId) {
        if (confirm("Are you sure you want to delete this?")) {
            // If confirmed, submit the form for the respective user
            document.getElementById('deleteForm' + userId).submit();
        }
    }
</script>
<?php include('../../includes/footer.php') ?>