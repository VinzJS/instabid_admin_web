<?php include('../authenticate.php') ?>
<?php include('../../includes/header.php') ?>
<?php
// Include the data fetching file
$tags = include '../records/_lists.php';
?>
<div class="main-wrapper">

    <main>
        <div class="head">
            <h1>Tags</h1>
            <a href="Add.php" class="add-btn"> <span>Add Tag</span></a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>created</th>
                    <th>name</th>
                    <th>updated</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tags['items'] ?? [] as $tag): ?>
                    <tr>
                        <td>
                            <?php
                            $dateString = $tag['created'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td><?php echo htmlspecialchars($tag['name']); ?></td>
                        <td>
                            <?php
                            $dateString = $tag['updated'];
                            $date = new DateTime($dateString);
                            ?>
                            <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>
                        </td>
                        <td>
                            <a href="Detail.php?id=<?php echo htmlspecialchars($tag['id']); ?>" class="action-btn view-btn">View</a>
                            <a href="Edit.php?id=<?php echo htmlspecialchars($tag['id']); ?>" class="action-btn edit-btn">Edit</a>
                            <form method="post" action="../records/_delete.php?collection=tags" id="deleteForm<?php echo htmlspecialchars($tag['id']); ?>">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($tag['id']); ?>">
                                <button type="button" onclick="confirmDelete('<?php echo htmlspecialchars($tag['id']); ?>')" class="action-btn delete-btn">Delete</button>
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