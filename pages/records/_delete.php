<?php 

// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started yet
}

$collection = $_GET['collection'] ?? null;  
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // API endpoint to delete the user (replace with your actual API URL)
    $api_url = $_SESSION['domain']."/api/collections/$collection/records/$id";

    // Initialize cURL session
    $ch = curl_init();

    // Set the cURL options for DELETE request
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");  // Use DELETE method
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     // Return the response
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $_SESSION['token'],
        'Content-Type: application/json'
    ]);

    // Execute the request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        // Check the response from the API
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
            echo "Deleted successfully!";
        } else {
            echo "Failed to delete user. HTTP Status Code: " . $http_code;
        }
    }



    // Redirect back to the index page after deletion
    header("Location: ../$collection/index.php");
    
    // Close the cURL session
    curl_close($ch);

    exit();

    
}

