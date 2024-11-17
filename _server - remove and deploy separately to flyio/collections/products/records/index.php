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
                $countQuery = "SELECT COUNT(*) as totalItems FROM products";
                $countResult = $conn->query($countQuery);
                $totalItems = $countResult->fetch_assoc()['totalItems'];
                $totalPages = ceil($totalItems / $perPage);

                $query = "SELECT * FROM products LIMIT ? OFFSET ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $perPage, $offset);
                $stmt->execute();

                $result = $stmt->get_result();

                $products = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $products[] = array(
                            "product_id" => $row['product_id'],
                            "created" => $row['created'],
                            "updated" => $row['updated'],
                            "name" => $row['name'],
                            "description" => $row['description'],
                            "is_biddable" => $row['is_biddable'],
                            "bid_start" => $row['bid_start'],
                            "bid_end" => $row['bid_end'],
                            "purchase_notes" => $row['purchase_notes'],
                            "size_guide" => $row['size_guide'],
                            "increment_amount" => $row['increment_amount'],
                            "min_bid" => $row['min_bid'],
                            "price" => $row['price'],
                            "is_published" => $row['is_published'],
                            "is_purchased" => $row['is_purchased'],
                            "category_id" => $row['category_id'],
                            "store" => $row['store']
                        );
                    }
                }

                echo json_encode(array(
                    'page' => $page,
                    'perPage' => $perPage,
                    'totalItems' => (int) $totalItems,
                    'totalPages' => $totalPages,
                    'items' => $products
                ));
            } catch (Exception $e) {
                echo json_encode(array('success' => false, 'message' => $e->getMessage()));
            }
            exit;

        }

        try {
            $query = "SELECT * FROM products WHERE product_id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $data = array(
                    "id" => $data['product_id'],
                    "created" => $data['created'],
                    "updated" => $data['updated'],
                    "name" => $data['name'],
                    "description" => $data['description'],
                    "isBiddable" => $data['is_biddable'],
                    "bidStart" => $data['bid_start'],
                    "bidEnd" => $data['bid_end'],
                    "purchaseNotes" => $data['purchase_notes'],
                    "sizeGuide" => $data['size_guide'],
                    "incrementAmount" => $data['increment_amount'],
                    "minBid" => $data['min_bid'],
                    "price" => $data['price'],
                    "isPublished" => $data['is_published'],
                    "isPurchased" => $data['is_purchased'],
                    "categoryId" => $data['category_id'],
                    "store" => $data['store']
                );
                echo json_encode(array('success' => true, 'record' => $data));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Product not found'));
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
            $query = "DELETE FROM products WHERE product_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Product deleted successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Product not found'));
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
            $query = "UPDATE products SET name = ?, description = ?, is_biddable = ?, bid_start = ?, bid_end = ?, purchase_notes = ?, size_guide = ?, increment_amount = ?, min_bid = ?, price = ?, is_published = ?, is_purchased = ?, category_id = ?, store = ? WHERE product_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssddsssbsbss', $data['name'], $data['description'], $data['is_biddable'], $data['bid_start'], $data['bid_end'], $data['purchase_notes'], $data['size_guide'], $data['increment_amount'], $data['min_bid'], $data['price'], $data['is_published'], $data['is_purchased'], $data['category_id'], $data['store'], $recordId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array('success' => true, 'message' => 'Product updated successfully'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Product not found or no changes made'));
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

    $productId = bin2hex(random_bytes(16));

    try {
        // Insert user into the users table
        $insertQuery = "INSERT INTO products (product_id, name, description, is_biddable, bid_start, bid_end, purchase_notes, size_guide, increment_amount, min_bid, price, is_published, is_purchased, category_id, store) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('sssssddsssbsbs', $productId, $data['name'], $data['description'], $data['is_biddable'], $data['bid_start'], $data['bid_end'], $data['purchase_notes'], $data['size_guide'], $data['increment_amount'], $data['min_bid'], $data['price'], $data['is_published'], $data['is_purchased'], $data['category_id'], $data['store']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Product created successfully',
                'data' => array(
                    'id' => $data['product_id'],
                    'created' => $data['created'],
                    'updated' => $data['updated'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'isBiddable' => $data['is_biddable'],
                    'bidStart' => $data['bid_start'],
                    'bidEnd' => $data['bid_end'],
                    'purchaseNotes' => $data['purchase_notes'],
                    'sizeGuide' => $data['size_guide'],
                    'incrementAmount' => $data['increment_amount'],
                    'minBid' => $data['min_bid'],
                    'price' => $data['price'],
                    'isPublished' => $data['is_published'],
                    'isPurchased' => $data['is_purchased'],
                    'categoryId' => $data['category_id'],
                    'store' => $data['store']
                )
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to create product'));
        }
    } catch (Exception $e) {
        echo json_encode(array('success' => false, 'message' => $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
}

