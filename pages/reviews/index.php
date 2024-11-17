<?php include('../authenticate.php') ?>
<?php include('../../includes/header.php') ?>
<?php
// Include the data fetching file
$reviews = include '../records/_lists.php';
?>
<div class="main-wrapper">
    <main>
        <h1>Reviews</h1>
        <table>
            <thead>
                <tr>
                    <th>created</th>
                    <th>message</th>
                    <th>product</th>
                    <th>rating</th>
                    <th>updated</th>
                    <th>user</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews['items'] ?? [] as $review): ?>
                    <?php
                    $product = $review['expand']['product'] ?? [];
                    $data = $review['expand']['user'] ?? [];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['created']); ?></td>
                        <td><?php echo htmlspecialchars($review['message']); ?></td>
                        <td>
                            <?php if (isset($product) && !empty($product)): ?>
                                <a href="../products/Detail.php?id=<?php echo htmlspecialchars($product['id']); ?>"><?php echo htmlspecialchars($product['name']); ?>
                                <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($review['rating']); ?></td>
                        <td>
                            <?php
                            $dateString = $review['updated'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td>
                            <?php if (isset($data) && !empty($data)): ?>
                                <a href="../users/Detail.php?id=<?php echo htmlspecialchars($data['id']); ?>"><?php echo htmlspecialchars($data['name']); ?>
                                <?php endif; ?>
                        </td>

                        <td>
                            <!-- <a href="#" class="action-btn view-btn">View</a> -->
                            <a href="Detail.php?id=<?php echo htmlspecialchars($review['id']); ?>" class="action-btn edit-btn">View</a>
                            <form method="post" action="../records/_delete.php?collection=reviews" id="deleteForm<?php echo htmlspecialchars($review['id']); ?>">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($review['id']); ?>">
                                <button type="button" onclick="confirmDelete('<?php echo htmlspecialchars($review['id']); ?>')" class="action-btn delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
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