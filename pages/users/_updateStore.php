<?php include('../authenticate.php') ?>

<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID and the new store owner value from the form
    $userId = $_POST['id'];
    $isStoreOwner = $_POST['isStoreOwner'];

    // Prepare the API URL
    $api_url = $_SESSION['domain'] . "/api/collections/users/records/$userId";

    // Data to be sent to the API
    $data = [
        'isStoreOwner' => $isStoreOwner
    ];

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options for PATCH request
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Send the data
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
          // Redirect back to the index page after deletion
         header("Location: index.php");
         exit();
    }

    // Close the cURL session
    curl_close($ch);
}
?>
