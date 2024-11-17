<?php include('../authenticate.php') ?>
<?php include('../../includes/header.php') ?>
<?php
// Include the data fetching file
$orders = include '../records/_lists.php';
?>
<div class="main-wrapper">
    <main>
        <h1>Order</h1>
        <table>
            <thead>
                <tr>
                    <th>amount</th>
                    <th>bid</th>
                    <th>created</th>
                    <th>product</th>
                    <th>status</th>
                    <th>updated</th>
                    <th>user</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders['items'] ?? [] as $order): ?>
                    <?php
                    $product = $order['expand']['product'] ?? [];
                    $data = $order['expand']['user'] ?? [];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['amount']); ?></td>
                        <td><?php echo htmlspecialchars($order['bid']); ?></td>
                        <td>
                            <?php
                            $dateString = $order['created'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td>
                            <?php if (isset($product) && !empty($product)): ?>
                                <a href="../products/Detail.php?id=<?php echo htmlspecialchars($product['id']); ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                            <?php endif; ?>

                        </td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td>
                            <?php
                            $dateString = $order['updated'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td>
                            <?php if (isset($data) && !empty($data)): ?>
                                <a href="../users/Detail.php?id=<?php echo htmlspecialchars($data['id']); ?>"><?php echo htmlspecialchars($data['name']); ?></a>
                            <?php endif; ?>

                        </td>
                        <td>
                            <!-- <a href="#" class="action-btn view-btn">View</a> -->
                            <a href="Detail.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="action-btn edit-btn">View</a>
                            <form method="post" action="../records/_delete.php?collection=orders" id="deleteForm<?php echo htmlspecialchars($order['id']); ?>">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                <button type="button" onclick="confirmDelete('<?php echo htmlspecialchars($order['id']); ?>')" class="action-btn delete-btn">Delete</button>
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