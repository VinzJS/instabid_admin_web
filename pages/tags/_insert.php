<?php include('../authenticate.php') ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the name from the form
    $name = htmlspecialchars($_POST['name']);
    
    // The API endpoint where data should be sent
    $api_url = $_SESSION['domain']."/api/collections/tags/records";

    // Data to be sent in the API request (as an array, to be converted to JSON)
    $data = array(
        'name' => $name
    );

    // Convert the data array to JSON format
    $json_data = json_encode($data);

    // Initialize cURL session
    $ch = curl_init($api_url);

    // Set cURL options for JSON request
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
    curl_setopt($ch, CURLOPT_POST, true);           // Use POST method
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data); // Send JSON data

    // Set headers for JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $_SESSION['token'],
        'Content-Length: ' . strlen($json_data)
    ));

    // Execute the API request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        // Handle the response (you can process or display it)
        echo "API Response: " . $response;
    }

    // Close the cURL session
    curl_close($ch);

    // Redirect back to the index page after deletion
    header("Location: index.php");
    exit();
}
?>