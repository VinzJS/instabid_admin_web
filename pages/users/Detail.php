<?php include('../authenticate.php') ?>
<?php
$userData = include '../records/_detail.php';
?>
<?php include('../../includes/header.php') ?>
<div class="main-wrapper">
<main>
    <h1>User Information</h1>
    <div class="data-info">
         <div class="profile-photo">
            <label for="profilePhoto">Profile Photo:</label>
            <?php if (!empty($userData['profilePhoto'])): ?>
                <img id="profilePhoto" src="<?= $_SESSION['domain'] . "/api/files/users/$id/" . $userData['profilePhoto']; ?>" alt="Profile Photo">
            <?php else: ?>
                <span>No profile photo available</span>
            <?php endif; ?>
        </div>
        <div>
            <label for="name">Name:</label>
            <span id="name"><?= $userData['name'] ?? 'N/A'; ?></span>
        </div>       
        <div>
            <label for="username">Username:</label>
            <span id="username"><?= $userData['username'] ?? 'N/A'; ?></span>
        </div>

        <div>
            <label for="created">Created:</label>
            <span id="created"><?= $userData['created'] ?? 'N/A'; ?></span>
        </div>
        
        <div>
            <label for="emailVisibility">Email Visibility:</label>
            <span id="emailVisibility"><?= $userData['emailVisibility'] ? 'Visible' : 'Hidden'; ?></span>
        </div>
        <div>
            <label for="updated">Last Updated:</label>
            <span id="updated"><?= $userData['updated'] ?? 'N/A'; ?></span>
        </div>
        
        
        <div>
            <label for="verified">Verified:</label>
            <span id="verified"><?= $userData['verified'] ? 'Yes' : 'No'; ?></span>
        </div>


         <div>
            <label for="isStoreOwner">Is Store Owner:</label>
            <form method="post" action="_updateStore.php" id="updateForm<?= htmlspecialchars($userData['id']); ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id']); ?>">
                
                <!-- Radio Button for Yes -->
                <input type="radio" id="isStoreOwnerYes" name="isStoreOwner" value="1" <?= isset($userData['isStoreOwner']) && $userData['isStoreOwner'] ? 'checked' : ''; ?>>
                <label for="isStoreOwnerYes">Yes</label>

                <!-- Radio Button for No -->
                <input type="radio" id="isStoreOwnerNo" name="isStoreOwner" value="0" <?= isset($userData['isStoreOwner']) && !$userData['isStoreOwner'] ? 'checked' : ''; ?>>
                <label for="isStoreOwnerNo">No</label>
                
                <!-- Submit Button -->
                <button type="submit" class="update-btn">Update</button>
                <a href="index.php" class="back-btn"> <span>Back</span></a>
            </form>
        </div>
    </div>
  
</main>
</div>
<?php include('../../includes/footer.php') ?>