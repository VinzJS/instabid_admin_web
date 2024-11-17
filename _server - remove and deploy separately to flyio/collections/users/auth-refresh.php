<?php

/**
 * This endpoint is used to refresh the access token for a user
 * 
 * @url POST /collections/users/auth-refresh
 * @param {string} accessToken - The current access token for the user
 * @return {object} - The user's data with a new accessToken on success
 * @return {object} - An error message on failure
 */

include '../../db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
    exit;
}

$headers = apache_request_headers();

if (!isset($headers['Authorization'])) {
    echo json_encode(array('success' => false, 'message' => 'Authorization header not found'));
    exit;
}

$authHeader = $headers['Authorization'];

if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    echo json_encode(array('success' => false, 'message' => 'Invalid Authorization header format'));
    exit;
}

$accessToken = $matches[1];

try {
    $query = "SELECT * FROM tokens WHERE accessToken = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $accessToken);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(array('success' => false, 'message' => 'Invalid access token'));
        exit;
    }

    $tokenData = $result->fetch_assoc();

    if (time() > strtotime($tokenData['expiresAt'])) {
        echo json_encode(array('success' => false, 'message' => 'Access token has expired'));
        exit;
    }

    $newTokenPayload = [
        'userId' => $tokenData['userId'],
        'exp' => time() + 3600, // 1 hour expiration
    ];
    $newAccessToken = base64_encode(json_encode($newTokenPayload));

    $updateQuery = "UPDATE tokens SET accessToken = ?, updated = NOW() WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('ss', $newAccessToken, $tokenData['id']);
    $updateStmt->execute();

    $userQuery = "SELECT * FROM users WHERE id = ?";
    $userStmt = $conn->prepare($userQuery);
    $userStmt->bind_param('s', $tokenData['userId']);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $data = $userResult->fetch_assoc();

    $userRecord = array(
        "created" => $data['created'],
        "email" => $data['email'],
        "emailVisibility" => $data['emailVisibility'],
        "hasApplication" => $data['hasApplication'],
        "id" => (string) $data['id'],
        "isStoreOwner" => $data['isStoreOwner'],
        "name" => $data['name'],
        "profilePhoto" => $data['profilePhoto'],
        "updated" => $data['updated'],
        "username" => $data['username'],
        "verified" => $data['verified']
    );

    echo json_encode(array(
        'success' => true,
        'record' => $userRecord,
        'token' => $newAccessToken,
    ));
} catch (Exception $e) {
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
}

