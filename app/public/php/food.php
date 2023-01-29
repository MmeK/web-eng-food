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
    $stmt = $pdo->prepare("SELECT * FROM foods");
    $stmt->execute();
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($foods);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.',
    ]);
}
