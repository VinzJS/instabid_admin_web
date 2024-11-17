<?php
// This script is used to check if the user is authenticated.
// It is called from different parts of the application and
// is used to verify that the user is logged in and has a valid
// token. If the user is not logged in, the script will stop
// execution and return a JSON response with an error message.

// Get all headers
$headers = getallheaders();

// Check if the Authorization header is set
if (!isset($headers['Authorization'])) {
    echo json_encode(array('success' => false, 'message' => 'Authorization header not found'));
    exit();
}

// Extract the token from the Authorization header
list($bearer, $token) = explode(' ', $headers['Authorization']);

if ($bearer !== 'Bearer') {
    echo json_encode(array('success' => false, 'message' => 'Invalid authorization header format'));
    exit();
}

try {
    // Decode the token (this is a placeholder, replace with actual JWT decoding code)
    $decodedToken = json_decode(base64_decode($token), true);

    // Check if token has expired
    if (isset($decodedToken['exp']) && time() > $decodedToken['exp']) {
        echo json_encode(array('success' => false, 'message' => 'Token has expired'));
        exit();
    }
} catch (Exception $e) {
    echo json_encode(array('success' => false, 'message' => 'Invalid token'));
    exit();
}