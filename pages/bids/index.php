<?php include('../authenticate.php') ?>
<?php include('../../includes/header.php') ?>
<?php
// Include the data fetching file
$bids = include '../records/_lists.php';
?>
<div class="main-wrapper">
    <main>
        <h1>Bids</h1>
        <table>
            <thead>
                <tr>
                    <th>name</th>
                    <th>address</th>
                    <th>description</th>
                    <th>user</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bids['items'] ?? [] as $bid): ?>
                    <?php
                    $data = $bid['expand']['user'] ?? [];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bid['name']); ?></td>
                        <td><?php echo htmlspecialchars($bid['address']); ?></td>
                        <td><?php echo htmlspecialchars($bid['description']); ?></td>
                        <td>
                            <?php if (isset($data) && !empty($data)): ?>
                                <a href="../users/Detail.php?id=<?php echo htmlspecialchars($data['id']); ?>"><?php echo htmlspecialchars($data['name']); ?></a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- <a href="#" class="action-btn view-btn">View</a> -->
                            <!-- <a href="Edit.php?id=<?php echo htmlspecialchars($bid['id']); ?>" class="action-btn edit-btn">Edit</a> -->
                            <form method="post" action="../records/_delete.php?collection=bids" id="deleteForm<?php echo htmlspecialchars($bid['id']); ?>">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($bid['id']); ?>">
                                <button type="button" onclick="confirmDelete('<?php echo htmlspecialchars($bid['id']); ?>')" class="action-btn delete-btn">Delete</button>
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