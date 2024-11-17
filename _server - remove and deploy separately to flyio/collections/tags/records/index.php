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
                $countQuery = "SELECT COUNT(*) as totalItems FROM tags";
                $countResult = $conn->query($countQuery);
                $totalItems = $countResult->fetch_assoc()['totalItems'];
                $totalPages = ceil($totalItems / $perPage);

                $query = "SELECT tag_id, name FROM tags LIMIT ? OFFSET ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $perPage, $offset);
                $stmt->execute();

                $result = $stmt->get_result();

                $tags = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $row['id'] = (string) $row['tag_id'];
                        $tags[] = $row;
                    }
                }

                echo json_encode(array(
                    'page' => $page,
                    'perPage' => $perPage,
                    'totalItems' => (int) $totalItems,
                    'totalPages' => $totalPages,
                    'items' => $tags
                ));
            } catch (Exception $e) {
                echo json_encode(array('success' => false, 'message' => $e->getMessage()));
            }
            exit;


        }

        try {
            $query = "SELECT tag_id, name FROM tags WHERE tag_id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $data['id'] = (string) $data['tag_id'];
                echo json_encode(array('success' => true, 'record' => $data));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Tag not found'));
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
            $query = "DELETE FROM tags WHERE tag_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Tag deleted successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Tag not found'));
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
            $query = "UPDATE tags SET name = ? WHERE tag_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $data['name'], $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Tag updated successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Tag not found or no changes made'));
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

    try {
        // Insert tag into the tags table
        $insertQuery = "INSERT INTO tags (tag_id, name) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $tagId = bin2hex(random_bytes(16));
        $stmt->bind_param('si', $tagId, $data['name']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Tag created successfully',
                'data' => array(
                    'id' => $tagId,
                    'name' => $data['name']
                )
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to create tag'));
        }
    } catch (Exception $e) {
        echo json_encode(array('success' => false, 'message' => $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
}

