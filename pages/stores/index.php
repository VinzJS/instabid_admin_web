<?php include('../authenticate.php') ?>
<?php include('../../includes/header.php') ?>
<?php
// Include the data fetching file
$stores = include '../records/_lists.php';
?>
<div class="main-wrapper">
    <main>
        <h1>Stores</h1>
        <table>
            <thead>
                <tr>
                    <th>address</th>
                    <th>created</th>
                    <th>description</th>
                    <th>isPublished</th>
                    <th>name</th>
                    <th>updated</th>
                    <th>user</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stores['items'] ?? [] as $store): ?>
                    <?php
                    $data = $store['expand']['user'] ?? [];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($store['address']); ?></td>
                        <td>
                            <?php
                            $dateString = $store['created'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td><?php echo htmlspecialchars($store['description']); ?></td>
                        <td><?php echo htmlspecialchars($store['isPublished'] ? "Yes" : "No"); ?></td>
                        <td><?php echo htmlspecialchars($store['name']); ?></td>
                        <td>
                            <?php
                            $dateString = $store['updated'];
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
                            <a href="Detail.php?id=<?php echo htmlspecialchars($store['id']); ?>" class="action-btn edit-btn">View</a>
                            <form method="post" action="../records/_delete.php?collection=stores" id="deleteForm<?php echo htmlspecialchars($store['id']); ?>">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($store['id']); ?>">
                                <button type="button" onclick="confirmDelete('<?php echo htmlspecialchars($store['id']); ?>')" class="action-btn delete-btn">Delete</button>
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