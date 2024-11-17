<?php
/**
 * This endpoint handles the creation, update, delete and retrieval of records
 * of admins stored in the admins collection.
 *
 * GET: Retrieve a list of records from the admins collection.
 *
 * POST: Create a new record in the admins collection.
 *       The request body should contain the following:
 *       {
 *           "name": "John Doe",
 *           "email": "john@example.com",
 *           
 *       }
 *
 * PATCH: Update an existing record in the admins collection.
 *        The request body should contain the following:
 *        {
 *            "name": "John Doe",
 *            "email": "john@example.com"
 *        }
 *
 * DELETE: Delete a record in the admins collection. The request
 *         body should contain the following:
 *         {
 *             "recordId": "5e9f8f8f8f8f8f8f8f8f8f"
 *         }
 */
header('Content-Type: application/json');
include '../../../db_connection.php';

/* 

    The verification of the page will only be done if the request method is not POST

*/
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    include '../../../need_authentication.php';
}



/* 

    This block of code will handle the get and list of record

*/
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['recordId'])) {
        $recordId = $_GET['recordId'];

        if ($recordId == 'index.php') {

            $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
            $perPage = (isset($_GET['perPage']) && is_numeric($_GET['perPage'])) ? $_GET['perPage'] : 30;
            $offset = ($page - 1) * $perPage;

            try {
                $countQuery = "SELECT COUNT(*) as totalItems FROM admins";
                $countResult = $conn->query($countQuery);
                $totalItems = $countResult->fetch_assoc()['totalItems'];
                $totalPages = ceil($totalItems / $perPage);

                $query = "SELECT id, email, name, username, isStoreOwner, created, updated FROM admins LIMIT ? OFFSET ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $perPage, $offset);
                $stmt->execute();

                $result = $stmt->get_result();

                $users = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $row['emailVisibility'] = false;
                        $row['hasApplication'] = false;
                        $row['id'] = (string) $row['id'];
                        $row['profilePhoto'] = ''; // Adjust as necessary
                        $row['verified'] = false; // Adjust as necessary
                        $users[] = $row;
                    }
                }

                echo json_encode(array(
                    'page' => $page,
                    'perPage' => $perPage,
                    'totalItems' => (int) $totalItems,
                    'totalPages' => $totalPages,
                    'items' => $users
                ));
            } catch (Exception $e) {
                echo json_encode(array('success' => false, 'message' => $e->getMessage()));
            }
            exit;


        }

        try {
            $query = "SELECT id, email, name, username, isStoreOwner, created, updated FROM admins WHERE id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $data['emailVisibility'] = false; // Adjust as necessary
                $data['hasApplication'] = false; // Adjust as necessary
                $data['id'] = (string) $data['id'];
                $data['profilePhoto'] = ''; // Adjust as necessary
                $data['verified'] = false; // Adjust as necessary
                echo json_encode(array('success' => true, 'record' => $data));
            } else {
                echo json_encode(array('success' => false, 'message' => 'User not found'));
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => $e->getMessage()));
            exit;
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Record ID not specified'));
        exit;
    }
}


/* 

    This block of code will handle the delete of the record

*/
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($_GET['recordId'])) {
        $recordId = $_GET['recordId'];

        try {
            $query = "DELETE FROM admins WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'User deleted successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'User not found'));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => $e->getMessage()));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Record ID not specified'));
    }
}


/* 

    This block of code will handle the update of the record

*/
if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    if (isset($_GET['recordId'])) {
        $recordId = $_GET['recordId'];
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $query = "UPDATE admins SET name = ?, username = ?, isStoreOwner = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssis', $data['name'], $data['username'], $data['isStoreOwner'], $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'User updated successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'User not found or no changes made'));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => $e->getMessage()));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Record ID not specified'));
    }
}

/* 
    
    This block of code will handle the creation of the record

*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $email = $data['email'];
    $password = $data['password'];
    $name = $data['name'];
    $passwordConfirm = $data['passwordConfirm'];

    if ($password !== $passwordConfirm) {
        echo json_encode(array('success' => false, 'message' => 'Passwords are not matching'));
        exit;
    }

    // Check if the email is already used
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(array('success' => false, 'message' => 'Email is already used'));
        exit;
    }

    // Hash the password before saving it
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Insert user into the users table
        $insertQuery = "INSERT INTO admins (id, email, name, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $userId = bin2hex(random_bytes(16));
        $stmt->bind_param('ssss', $userId, $email, $name, $hashedPassword);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Admin created successfully',
                'data' => array(
                    'id' => $userId,
                    'name' => $name
                )
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to create user'));
        }
    } catch (Exception $e) {
        echo json_encode(array('success' => false, 'message' => $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
}

