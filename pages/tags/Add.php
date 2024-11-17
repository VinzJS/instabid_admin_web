<?php include('../authenticate.php') ?>
<?php include('../../includes/header.php') ?>
<div class="main-wrapper">
<main>
    <h1>Add Tag</h1>
    <h2>Enter Your Name</h2>
    <form action="_insert.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit" class="submit-btn">Submit</button>
    </form>
    <a href="index.php" class="back-btn"> <span>Back</span></a>
</main>
</div>
<?php include('../../includes/footer.php') ?>