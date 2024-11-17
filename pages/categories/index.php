<?php include('../authenticate.php') ?>
<?php include('../../includes/header.php') ?>
<?php
// Include the data fetching file
$categories = include '../records/_lists.php';
?>
<div class="main-wrapper">
    <main>
        <h1>Categories</h1>
        <table>
            <thead>
                <tr>
                    <th>name</th>
                    <th>parent</th>
                    <th>photo</th>
                    <th>store</th>
                    <th>updated</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories['items'] ?? [] as $category): ?>
                    <?php
                    $store = $category['expand']['store'] ?? [];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['parent']); ?></td>
                        <td><?php echo htmlspecialchars($category['photo']); ?></td>
                        <td>
                            <?php if (isset($store) && !empty($store)): ?> 
                                <a href="../stores/Detail.php?id=<?php echo htmlspecialchars($store['id']); ?>"><?php echo htmlspecialchars($store['name']); ?></a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $dateString = $category['updated'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td>
                            <!-- <a href="#" class="action-btn view-btn">View</a> -->
                            <a href="Detail.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="action-btn edit-btn">View</a>
                            <form method="post" action="../records/_delete.php?collection=categories" id="deleteForm<?php echo htmlspecialchars($category['id']); ?>">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($category['id']); ?>">
                                <button type="button" onclick="confirmDelete('<?php echo htmlspecialchars($category['id']); ?>')" class="action-btn delete-btn">Delete</button>
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