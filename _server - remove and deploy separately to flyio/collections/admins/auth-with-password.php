<?php
/**
 * This endpoint is used to authenticate admins with their email and password
 *
 * @url POST /collections/admins/auth-with-password
 * @param {string} identity - The admin's email address
 * @param {string} password - The admin's password
 * @return {object} - The admin's data with an accessToken on success
 * @return {object} - An error message on failure
 */
include '../../db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['identity']) || !isset($data['password'])) {
    echo json_encode(array('success' => false, 'message' => 'Invalid request payload'));
    exit;
}

$email = $data['identity'];
$password = $data['password'];

try {
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(array('success' => false, 'message' => 'Failed to authenticate. User not found'));
        exit;
    }

    $data = $result->fetch_assoc();
    if (!password_verify($password, $data['password'])) {
        echo json_encode(array('success' => false, 'message' => 'Failed to authenticate. Wrong password'));
        exit;
    }

    // Password is correct, generate JWT token (this is a placeholder, replace with actual JWT generation code)
    $tokenPayload = [
        'userId' => $data['id'],
        'exp' => time() + 2592000, // 30 days expiration
    ];
    $token = base64_encode(json_encode($tokenPayload));

    // Insert token into the tokens table
    $insertQuery = "INSERT INTO tokens (id, userId, refreshToken, accessToken, expiresAt) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $tokenId = bin2hex(random_bytes(16));
    $refreshToken = bin2hex(random_bytes(64));
    $expiresAt = date('Y-m-d H:i:s', time() + 2592000); // 1 hour expiration
    $stmt->bind_param('sssss', $tokenId, $data['id'], $refreshToken, $token, $expiresAt);
    $stmt->execute();

    echo json_encode(array(
        "record" => array(
            "created" => $data['created'],
            "email" => $data['email'],
            "emailVisibility" => false,
            "hasApplication" => false,
            "id" => (string) $data['id'],
            "isStoreOwner" => $data['isStoreOwner'],
            "name" => $data['name'],
            "profilePhoto" => "",
            "updated" => $data['updated'],
            "username" => $data['username'],
            "verified" => false
        ),
        "token" => $token
    ));
} catch (Exception $e) {
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
}
