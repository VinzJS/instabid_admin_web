<?php include('../authenticate.php') ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form values
    $name = $_POST['name'];
    $id = $_POST['id'];
    $updated = $_POST['updated'];

    // API endpoint (replace with the actual user ID in your case)
    $url = $_SESSION['domain']."/api/collections/tags/records/$id";

    // Prepare the data to send in the PATCH request
    $data = [
        "name" => $name,
        "updated" => $updated
    ];

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options for PATCH request
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $_SESSION['token'], // If authentication token is needed
    ]);

    // Execute the request and get the response
    $response = curl_exec(handle: $ch);

    // Check if the request was successful
    if ($response === false) {
        echo 'Error: ' . curl_error($ch);
    } else {
        header("Location: index.php");
        exit();
    }

    // Close cURL session
    curl_close($ch);
}
?>
