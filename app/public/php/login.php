<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":email", $email);

    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $hashed_password = $row["password"];
            if (password_verify($password, $hashed_password)) {
                echo json_encode(array("message" => "Login Successful"));
                $_SESSION['logged_in'] = ($row["is_admin"] ? "admin" : "user");
                $_SESSION['uid'] = $row["id"];
            } else {
                echo json_encode(array("message" => "Incorrect Password"));
            }
        } else {
            echo json_encode(array("message" => "User not found"));
        }
    } else {
        echo json_encode(array("message" => "Login Failed"));
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}
