<?php include('../authenticate.php') ?>
<?php

$id = $_GET['id']; // Get the ID from the URL

// Replace {{domain}} and {{collection_reviews}} with actual values
$api_url = $_SESSION['domain']."/api/collections/tags/records/$id";

// Initialize cURL session
$ch = curl_init();

// Set the options for cURL
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $_SESSION['token'],
    'Content-Type: application/json'
]);

// Execute the request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Convert JSON response to a PHP array
    $data = json_decode($response, true);
}

// Close the cURL session
curl_close($ch); 
?>
<?php include('../../includes/header.php') ?>
<div class="main-wrapper">
<main>
    <h1>Edit Tag</h1>
    <h2>Enter Your New Name</h2>
    <form action="_update.php" method="POST">
        <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($data['id']);  ?>" required>
        <input type="hidden" id="updated" name="updated" value="  <?php
                        $date = new DateTime();
                        ?>
                        <?php echo htmlspecialchars($date->format('Y-m-d H:i:s')); ?>" 
                        required>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['name']);  ?>" required>
        <button type="submit" class="submit-btn">Submit</button>
    </form>
    <a href="index.php" class="back-btn"> <span>Back</span></a>
</main>
</div>
<?php include('../../includes/footer.php') ?>