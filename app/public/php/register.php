<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $location = $_POST["location"];

    // Validate the request data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid email format."]);
        return;
    }

    if (empty($username) || strlen($username) > 50) {
        http_response_code(400);
        echo json_encode(["error" => "Username is required and must be less than 50 characters."]);
        return;
    }

    if (empty($password) || strlen($password) < 6) {
        http_response_code(400);
        echo json_encode(["error" => "Password is required and must be at least 6 characters."]);
        return;
    }

    if (empty($location) || strlen($location) > 100) {
        http_response_code(400);
        echo json_encode(["error" => "Location is required and must be less than 100 characters."]);
        return;
    }

    // Hash the password
    $password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to the database
        require_once 'connect.php';

        // Insert the new user
        $stmt = $pdo->prepare("INSERT INTO users (email, username, password, location) VALUES (:email, :username, :password, :location)");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":location", $location);
        $stmt->execute();

        http_response_code(201);
        echo json_encode(["message" => "User registered successfully."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}
