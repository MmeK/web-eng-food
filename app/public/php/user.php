<?php
session_start();
require_once 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset(getallheaders()['Http-X-Rest-Method'])) {
        if (getallheaders()['Http-X-Rest-Method'] == 'EDIT') {
            $id = $_SESSION['uid'];
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $location = $_POST['location'] ?? '';

            // Validate the parameters
            if (empty($id) || !is_numeric($id)) {
                http_response_code(400);
                echo json_encode(['error' => 'The id parameter is required and must be a number']);
                exit();
            }
            $query = "UPDATE users SET";

            $bindings = [];

            if (!empty($username)) {
                $query .= " username = :username,";
                $bindings[':username'] = $username;
            }

            if (!empty($email)) {
                $query .= " email = :email,";
                $bindings[':email'] = $email;
            }

            if (!empty($password)) {
                $query .= " password = :password,";
                $bindings[':password'] = $password;
            }

            if (!empty($location)) {
                $query .= " location = :location,";
                $bindings[':location'] = $location;
            }
            // Remove the trailing comma
            $query = rtrim($query, ',');

            $query .= " WHERE id = :id";
            $bindings[':id'] = $id;

            // Prepare the statement
            $stmt = $pdo->prepare($query);
            // Execute the query with the bindings
            try {
                $stmt->execute($bindings);
            } catch (PDOException $e) {
                http_response_code(400);
                echo json_encode(['error' => 'User Edit Failed.']);
                exit();
            }
            // Check if the query was successful
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'The user with id ' . $id . ' was not found']);
                exit();
            }

            // Return success
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'your account has been updated successfully']);

        }
    } else {

    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['uid'])) {
        $id = $_SESSION['uid'];
        $stmt = $pdo->prepare("SELECT username, email, location FROM users WHERE id = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($user);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($users);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.',
    ]);
}
