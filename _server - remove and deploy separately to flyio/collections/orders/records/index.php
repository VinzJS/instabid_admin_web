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
                $countQuery = "SELECT COUNT(*) as totalItems FROM orders";
                $countResult = $conn->query($countQuery);
                $totalItems = $countResult->fetch_assoc()['totalItems'];
                $totalPages = ceil($totalItems / $perPage);

                $query = "SELECT o.id, o.product_id, o.user_id, o.amount, o.price, o.status, o.created, o.updated FROM orders o ORDER BY o.id DESC LIMIT ? OFFSET ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $perPage, $offset);
                $stmt->execute();

                $result = $stmt->get_result();

                $orders = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $orders[] = array(
                            "id" => $row['id'],
                            "created" => $row['created'],
                            "updated" => $row['updated'],
                            "status" => $row['status'],
                            "product" => $row['product_id'],
                            "bid" => null,
                            "user" => $row['user_id'],
                            "amount" => $row['amount'],
                            "price" => $row['price'],
                            "total" => (int) $row['amount'],
                            "productCopy" => json_encode(array())
                        );
                    }
                }

                echo json_encode(array(
                    'page' => $page,
                    'perPage' => $perPage,
                    'totalItems' => (int) $totalItems,
                    'totalPages' => $totalPages,
                    'items' => $orders
                ));
            } catch (Exception $e) {
                echo json_encode(array('success' => false, 'message' => $e->getMessage()));
            }
            exit;

        }

        try {
            $query = "SELECT * FROM orders o WHERE o.id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $data = array(
                    "id" => $data['id'],
                    "created" => $data['created'],
                    "updated" => $data['updated'],
                    "status" => $data['status'],
                    "product" => $data['product_id'],
                    "bid" => $data['bid_id'],
                    "user" => $data['user_id'],
                    "amount" => (float) $data['amount'],
                    "status" => $data['status'],
                    "productCopy" => json_decode($data['productCopy']),
                    "total" => (float) $data['total'],
                    "price" => (float) $data['price'],
                );
                echo json_encode(array('success' => true, 'record' => $data));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Order not found'));
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
            $query = "DELETE FROM orders WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Order deleted successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Order not found'));
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
            $query = "UPDATE orders SET product_id = (SELECT id FROM products WHERE id = ?), user_id = (SELECT id FROM users WHERE id = ?), amount = ?, price = ?, status = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iiisii', $data['product']['id'], $data['user']['id'], $data['amount'], $data['price'], $data['status'], $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Order updated successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Order not found or no changes made'));
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
        // Insert order into the orders table
        $insertQuery = "INSERT INTO orders (status, product_id, bid_id, user_id, amount, product_copy, total, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('siisssii', $data['status'], $data['product'], $data['bid'], $data['user'], $data['amount'], $data['productCopy'], $data['total'], $data['price']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Order created successfully',
                'data' => array(
                    'id' => $conn->insert_id,
                    'status' => $data['status'],
                    'product' => $data['product'],
                    'bid' => $data['bid'],
                    'user' => $data['user'],
                    'amount' => $data['amount'],
                    'productCopy' => $data['productCopy'],
                    'total' => $data['total'],
                    'price' => $data['price']
                )
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to create order'));
        }
    } catch (Exception $e) {
        echo json_encode(array('success' => false, 'message' => $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
}

