<?php
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['token'])) {
    header('Location: /');
    exit();
}

?>

<?php
// Include the data fetching file
$collections = ['users', 'orders', 'products', 'tags', 'categories', 'bids', 'reviews', 'stores'];
$colors = ['blue', 'green', 'red', 'tag', 'yellow', 'bid', 'review', 'store'];
$newArray = [];

foreach ($collections as $index => $collection) {
    // Fetch product records from AP
    $apiUrl = $_SESSION['domain'] . "/api/collections/$collection/records";

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $_SESSION['token'],
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $record = json_decode($response, true);
    // Store the collection name and totalItems in the new array
    $totalItems = $record['totalItems'] ?? 0;  // Default to 0 if not set

    // Assign color based on the index of the collection
    $color = $colors[$index] ?? '';  // Default color if no match

    // Store the collection name, totalItems, color, and status in the new array
    $newArray[$collection] = [
        'totalItems' => $totalItems,
        'color' => $color
    ];
}


return $newArray ?? null;


