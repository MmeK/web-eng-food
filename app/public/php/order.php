<?php
session_start();
require_once 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset(getallheaders()['Http-X-Rest-Method'])) {
        if (getallheaders()['Http-X-Rest-Method'] == 'EDIT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $order_id = $data['order_id'] ?? '';
            $rating = $data['rating'] ?? '';

            if (empty($order_id) || !is_numeric($order_id)) {
                http_response_code(400);
                echo json_encode(['error' => 'The id parameter is required and must be a number']);
                exit();
            }
            if (empty($rating) || !is_numeric($rating)) {
                http_response_code(400);
                echo json_encode(['error' => 'The rating parameter is required and must be a number']);
                exit();
            }
            $query = "UPDATE orders SET rating=:rating WHERE id=:id";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue('rating', $rating);
            $stmt->bindValue('id', $order_id);
            $stmt->execute();

            // Check if the query was successful
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'The order with id ' . $order_id . ' was not found']);
                exit();
            }

            // Return success
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'order rating changed successfully']);

        }
    } else {
        $data = json_decode(file_get_contents('php://input'), true);
        // get the data from the request
        $userId = $_SESSION['uid'];
        $totalPrice = $data['total_price'];
        $foodItems = $data['food_items'];

        if (!is_array($foodItems) || count($foodItems) == 0 || !is_numeric($totalPrice) || !is_numeric($userId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }

        try {
            // start a transaction
            $pdo->beginTransaction();

            // insert the order into the orders table
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':total_price', $totalPrice);
            $stmt->execute();

            // get the order id of the inserted order
            $orderId = $pdo->lastInsertId();

            // insert the food items into the order_items table
            $stmt = $pdo->prepare("INSERT INTO order_items (food_id, order_id, price) VALUES (:food_id, :order_id, :price)");
            foreach ($foodItems as $item) {
                $foodId = $item['id'];
                $price = $item['price'];
                $stmt->bindParam(':food_id', $foodId);
                $stmt->bindParam(':order_id', $orderId);
                $stmt->bindParam(':price', $price);
                $stmt->execute();

            }

            // deduct food item quantity
            $stmt = $pdo->prepare("UPDATE foods SET quantity = quantity-1 WHERE id = :food_id");
            foreach ($foodItems as $item) {
                $foodId = $item['id'];
                $stmt->bindParam(':food_id', $foodId);
                $stmt->execute();
            }

            // commit the transaction
            $pdo->commit();

            // return success
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            // rollback the transaction if something went wrong
            $pdo->rollback();

            // return error
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['uid'])) {
        $stmt = $pdo->prepare("SELECT orders.*, GROUP_CONCAT(foods.name) as items
        FROM orders join order_items on order_items.order_id=orders.id
        join foods on foods.id=order_items.food_id
        WHERE orders.user_id=:id
         GROUP BY orders.id
        ");
        $stmt->bindValue('id', $_SESSION['uid']);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($orders);
    } else {
        $stmt = $pdo->prepare("SELECT orders.* , users.username FROM orders JOIN users ON orders.user_id=users.id");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($orders);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.',
    ]);
}
