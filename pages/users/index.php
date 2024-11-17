<?php include('../authenticate.php') ?>
<?php include('../../includes/header.php') ?>
<?php
// Include the data fetching file
$users = include '../records/_lists.php';
?>
<div class="main-wrapper">
    <main>
        <h1>Users</h1>
        <table style="  overflow-y: scroll;">
            <thead>
                <tr>
                    <th>name</th>
                    <th>username</th>
                    <th>isStoreOwner</th>
                    <th>created</th>
                    <th>updated</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users['items'] ?? [] as $data): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['name']); ?></td>
                        <td><?php echo htmlspecialchars($data['username']); ?></td>
                        <td><?php echo htmlspecialchars($data['isStoreOwner'] ? "Yes" : "No"); ?></td>
                        <td>
                            <?php
                            $dateString = $data['created'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td>
                            <?php
                            $dateString = $data['updated'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td>
                            <!-- <a href="#" class="action-btn view-btn">View</a> -->
                            <a href="Detail.php?id=<?php echo htmlspecialchars($data['id']); ?>" class="action-btn edit-btn">View</a>
                            <form method="post" action="../records/_delete.php?collection=users" id="deleteForm<?php echo htmlspecialchars($data['id']); ?>">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">
                                <button type="button" onclick="confirmDelete('<?php echo htmlspecialchars($data['id']); ?>')" class="action-btn delete-btn">Delete</button>
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