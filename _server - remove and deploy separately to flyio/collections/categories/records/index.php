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
                $countQuery = "SELECT COUNT(*) as totalItems FROM categories";
                $countResult = $conn->query($countQuery);
                $totalItems = $countResult->fetch_assoc()['totalItems'];
                $totalPages = ceil($totalItems / $perPage);

                $query = "SELECT category_id, name, store_id FROM categories LIMIT ? OFFSET ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $perPage, $offset);
                $stmt->execute();

                $result = $stmt->get_result();

                $categories = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $row['category_id'] = (string) $row['category_id'];
                        $categories[] = $row;
                    }
                }

                echo json_encode(array(
                    'page' => $page,
                    'perPage' => $perPage,
                    'totalItems' => (int) $totalItems,
                    'totalPages' => $totalPages,
                    'items' => $categories
                ));
            } catch (Exception $e) {
                echo json_encode(array('success' => false, 'message' => $e->getMessage()));
            }
            exit;


        }

        try {
            $query = "SELECT category_id, name, store_id FROM categories WHERE category_id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $data['category_id'] = (string) $data['category_id'];
                echo json_encode(array('success' => true, 'record' => $data));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Category not found'));
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
            $query = "DELETE FROM categories WHERE category_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Category deleted successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Category not found'));
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
            $query = "UPDATE categories SET name = ?, store_id = ? WHERE category_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssi', $data['name'], $data['store'], $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Category updated successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Category not found or no changes made'));
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
        // Insert category into the categories table
        $insertQuery = "INSERT INTO categories (category_id, name, store_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $categoryId = bin2hex(random_bytes(16));
        $stmt->bind_param('ssi', $categoryId, $data['name'], $data['store']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Category created successfully',
                'data' => array(
                    'category_id' => $categoryId,
                    'name' => $data['name'],
                    'store' => $data['store']
                )
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to create category'));
        }
    } catch (Exception $e) {
        echo json_encode(array('success' => false, 'message' => $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
}

