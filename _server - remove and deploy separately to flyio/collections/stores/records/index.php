<?php
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
                $countQuery = "SELECT COUNT(*) as totalItems FROM stores";
                $countResult = $conn->query($countQuery);
                $totalItems = $countResult->fetch_assoc()['totalItems'];
                $totalPages = ceil($totalItems / $perPage);

                $query = "SELECT store_id, created, updated, name, address, description, user_id as user, isPublished, rating, displayImage, contactNumber FROM stores LIMIT ? OFFSET ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $perPage, $offset);
                $stmt->execute();

                $result = $stmt->get_result();

                $stores = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $row['id'] = (string) $row['store_id'];
                        $stores[] = $row;
                    }
                }

                echo json_encode(array(
                    'page' => $page,
                    'perPage' => $perPage,
                    'totalItems' => (int) $totalItems,
                    'totalPages' => $totalPages,
                    'items' => $stores
                ));
            } catch (Exception $e) {
                echo json_encode(array('success' => false, 'message' => $e->getMessage()));
            }
            exit;


        }

        try {
            $query = "SELECT id, created, updated, name, address, description, user_id as user, isPublished, rating, displayImage, contactNumber FROM stores WHERE id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $data['id'] = (string) $data['id'];
                echo json_encode(array('success' => true, 'record' => $data));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Store not found'));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => $e->getMessage()));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Record ID not specified'));
    }
}


/* 

    This block of code will handle the delete of the record

*/
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($_GET['recordId'])) {
        $recordId = $_GET['recordId'];

        try {
            $query = "DELETE FROM stores WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Store deleted successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Store not found'));
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
            $query = "UPDATE stores SET name = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssi', $data['name'], $data['description'], $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Store updated successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Store not found or no changes made'));
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

    $storeId = bin2hex(random_bytes(16));
    $created = date('Y-m-d H:i:s.000Z');
    $updated = date('Y-m-d H:i:s.000Z');
    $name = $data['name'];
    $address = $data['address'];
    $description = $data['description'];
    $user = $data['user'];
    $isPublished = $data['isPublished'];
    $rating = $data['rating'];
    $displayImage = $data['displayImage'];
    $contactNumber = $data['contactNumber'];

    try {
        // Insert store into the stores table
        $insertQuery = "INSERT INTO stores (id, created, updated, name, address, description, user_id, isPublished, rating, displayImage, contactNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('ssssssiisss', $storeId, $created, $updated, $name, $address, $description, $user, $isPublished, $rating, $displayImage, $contactNumber);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Store created successfully',
                'data' => array(
                    'id' => $storeId,
                    'created' => $created,
                    'updated' => $updated,
                    'name' => $name,
                    'address' => $address,
                    'description' => $description,
                    'user' => $user,
                    'isPublished' => $isPublished,
                    'rating' => $rating,
                    'displayImage' => $displayImage,
                    'contactNumber' => $contactNumber
                )
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to create store'));
        }
    } catch (Exception $e) {
        echo json_encode(array('success' => false, 'message' => $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
}

