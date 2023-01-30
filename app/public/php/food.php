<?php

require_once 'connect.php';

// check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset(getallheaders()['Http-X-Rest-Method'])) {
        if (getallheaders()['Http-X-Rest-Method'] == 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id']) && is_int($data['id'])) {
                $id = (int) $data['id'];
                try {
                    $stmt = $pdo->prepare("DELETE FROM foods WHERE id=:id");
                    $stmt->bindValue('id', $id);
                    $stmt->execute();
                    echo json_encode([
                        'success' => true,
                        'message' => 'Menu item Deleted successfully.',
                    ]);
                } catch (PDOException $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => "couldn't delete.",
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid input.',
                ]);
            }

        } else if (getallheaders()['Http-X-Rest-Method'] == 'EDIT') {
            // print_r($_POST);
            $id = $_POST['id'];
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';
            $description = $_POST['description'] ?? '';
            $quantity = $_POST['quantity'] ?? '';
            $ingredients = $_POST['ingredients'] ?? '';
            $image = $_FILES['image']['name'] ?? '';

            // Validate the parameters
            if (empty($id) || !is_numeric($id)) {
                http_response_code(400);
                echo json_encode(['error' => 'The id parameter is required and must be a number']);
                exit();
            }

            // Prepare the update query
            $query = "UPDATE foods SET";

            $bindings = [];

            if (!empty($name)) {
                $query .= " name = :name,";
                $bindings[':name'] = $name;
            }

            if (!empty($price)) {
                $query .= " price = :price,";
                $bindings[':price'] = $price;
            }

            if (!empty($description)) {
                $query .= " description = :description,";
                $bindings[':description'] = $description;
            }

            if (!empty($quantity)) {
                $query .= " quantity = :quantity,";
                $bindings[':quantity'] = $quantity;
            }

            if (!empty($ingredients)) {
                $query .= " ingredients = :ingredients,";
                $bindings[':ingredients'] = $ingredients;
            }

            // Upload the image
            if (!empty($image)) {
                $image_name = uniqid() . '.' . pathinfo($image, PATHINFO_EXTENSION);
                $image_path = '../images/' . $image_name;

                if ($_FILES['image']['error'] == 0) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                        $query .= " image = :image,";
                        $bindings[':image'] = $image_path;
                    }
                }

            }
            // Remove the trailing comma
            $query = rtrim($query, ',');

            $query .= " WHERE id = :id";
            $bindings[':id'] = $id;

            // Prepare the statement
            $stmt = $pdo->prepare($query);

            // Execute the query with the bindings
            $stmt->execute($bindings);

            // Check if the query was successful
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'The food with id ' . $id . ' was not found']);
                exit();
            }

            // Return success
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'The food was updated successfully']);

        }
    } else {
        // validate and sanitize the request data
        $name = htmlspecialchars(trim($_POST['name']));
        $description = htmlspecialchars(trim($_POST['description']));
        $ingredients = htmlspecialchars(trim($_POST['ingredients']));
        $price = filter_var(trim($_POST['price']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $quantity = filter_var(trim($_POST['quantity']), FILTER_SANITIZE_NUMBER_INT);

        // Get the image file from the POST request
        $image = $_FILES['image'];

        // Check if the image file is uploaded successfully
        if ($_FILES['image']['error'] == 0) {
            // Generate a unique file name for the uploaded image
            $image_name = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
            $image_path = '../images/' . $image_name;

            // if all the data is valid, insert a new menu item into the database
            if ($name && $description && $ingredients && $price) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    try {
                        $query = "INSERT INTO foods (name, description, ingredients, quantity, price, image) VALUES (:name, :description, :ingredients,:quantity, :price, :image)";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
                        $stmt->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
                        $stmt->bindValue(':ingredients', $_POST['ingredients'], PDO::PARAM_STR);
                        $stmt->bindValue(':quantity', $_POST['quantity'], PDO::PARAM_INT);
                        $stmt->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
                        $stmt->bindValue(':image', $image_path, PDO::PARAM_STR);
                        $stmt->execute();

                        echo json_encode([
                            'success' => true,
                            'message' => 'Menu item added successfully.',
                        ]);
                    } catch (PDOException $e) {
                        echo json_encode([
                            'success' => false,
                            'message' => $e,
                        ]);
                    }
                } else {
                    // Return an error message
                    http_response_code(500);
                    echo json_encode(['message' => 'Failed to upload image']);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid data. Please check your inputs.',
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false,
                'message' => 'No image file was uploaded']);
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['food_id'])) {
        $id = $_GET['food_id'];
        $stmt = $pdo->prepare("SELECT * FROM foods WHERE id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $food = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($food);
    } else {
        $stmt = $pdo->prepare("SELECT foods.*, AVG(orders.rating) as rating FROM foods
        left join order_items on order_items.food_id=foods.id
        left join orders on orders.id=order_items.order_id
        GROUP BY foods.id");
        $stmt->execute();
        $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($foods);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.',
    ]);
}
