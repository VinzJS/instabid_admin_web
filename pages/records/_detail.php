<?php
include '../../includes/authenticate.php';

$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parsed_url = parse_url($current_url, PHP_URL_PATH);
$path_segments = explode('/', trim($parsed_url, '/'));

$collection = $path_segments[1];

$id = $_GET['id']; // Get the ID from the URL

function getData($id, $collection, $expand)
{
    // Replace {{domain}} and {{collection_reviews}} with actual values
    $api_url = $_SESSION['domain'] . "/api/collections/$collection/records/$id?expand=$expand";

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
        $data = json_decode($response, true) ?? [];

        return $data;
    }


    // Close the cURL session
    curl_close($ch);
}

switch ($collection) {
    case "tags":
        $result = getData($id, $collection,null);
        break;
    case "orders":
        $result = getData($id, $collection,'product,user');
        break;
    case "bids":
        $result = getData($id, $collection, 'user');
        break;
    case "reviews":
        $result = getData($id, $collection, 'product,user');
        break;
    case "categories":
        $result = getData($id, $collection,'store');
        break;
    case "products":
        $result = getData($id, $collection,'category,store,tags');
        break;
    case "users":
        $result = getData($id, $collection,null);
        break;
    case "stores":
        $result = getData($id, $collection,'user');
        break;
}

return $result ?? [];