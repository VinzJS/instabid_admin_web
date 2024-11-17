<?php
include '../../includes/authenticate.php';

$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parsed_url = parse_url($current_url, PHP_URL_PATH);
$path_segments = explode('/', trim($parsed_url, '/'));

$collection = $path_segments[1];



function getData($collection, $page = 1, $perPage = 30, ?string $expand = null): mixed
{
    // Fetch product records from AP
    $apiUrl = $_SESSION['domain'] . "/api/collections/$collection/records?page=$page&perPage=$perPage&expand=$expand";

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $_SESSION['token'],
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Set current page and per-page limit
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 100;



switch ($collection) {
    case "tags":
        $result = getData($collection, $page, $perPage, null);
        break;
    case "orders":
        $result = getData($collection, $page, $perPage, 'product,user');
        break;
    case "bids":
        $result = getData($collection, $page, $perPage, 'user');
        break;
    case "reviews":
        $result = getData($collection, $page, $perPage, 'product,user');
        break;
    case "categories":
        $result = getData($collection, $page, $perPage,'store');
        break;
    case "products":
        $result = getData($collection, $page, $perPage,'category,store,tags');
        break;
    case "users":
        $result = getData($collection, $page, $perPage,null);
        break;
    case "stores":
        $result = getData($collection, $page, $perPage,'user');
        break;
}


$totalPages = $result['totalPages'] ?? 0;
return $result ?? [];
