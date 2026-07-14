<?php
session_start();
require "connection.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message = "";
    $status = "error";
    $debug = [];

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $rememberme = $_POST["rememberMe"] ?? "";

    if (empty($email)) {
        $message = "Please enter your Email Address.";
    } else if (strlen($email) > 100) {
        $message = "Email must have less than 100 characters.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid Email Address.";
    } else if (empty($password)) {
        $message = "Please enter your Password.";
    } else if (strlen($password) < 8 || strlen($password) > 16) {
        $message = "Password length must be between 8 - 16 characters.";
    } else {

        // Find user
        $rs = Database::search("SELECT * FROM `users`
        WHERE `email`='" . $email . "'");

        if ($rs->num_rows == 1) {
            $user = $rs->fetch_assoc();
            $debug['db_is_active'] = $user['is_active'];
            $debug['db_status'] = $user['status'];

            if (intval($user["is_active"]) !== 1) {
                $message = "Please verify your email first.";
            } else if (intval($user["status"]) !== 0) {
                $message = "Your Account has bean banned.";
            } else if (password_verify($password, $user["password"])) {

                $_SESSION["u"] = $user;

                if ($rememberme == "true") {
                    setcookie("email", $email, time() + (60 * 60 * 24));
                    setcookie("password", $password, time() + (60 * 60 * 24));
                } else {
                    setcookie("email", "", -1);
                    setcookie("password", "", -1);
                }

                $status = "success";
                $message = "Login successful.";
            } else {

                $message = "Invalid Login Credentials. Renter Correct Credentials";
            }
        } else {

            $message = "User not found.";
        }
    }

    $response = [
        "status" => $status,
        "message" => $message
    ];

    if (!empty($debug)) {
        $response['debug'] = $debug;
    }

    echo json_encode($response);
} else {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
