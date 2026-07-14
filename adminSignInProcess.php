<?php

session_start();
require "connection.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message = "";
    $status = "error";

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($email)) {
        $message = "Please enter your Email Address.";
    } else if (strlen($email) > 100) {
        $message = "Email must have less than 100 characters.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid Email Address.";
    } else if (empty($password)) {
        $message = "Please enter your Password.";
    } else {

        // Check admin
        $rs = Database::search("SELECT * FROM `admin` WHERE `email`='" . $email . "'");

        if ($rs->num_rows == 1) {

            $admin = $rs->fetch_assoc();

            if ($password === $admin["password"]) {

                $_SESSION["admin"] = $admin;

                $status = "success";
                $message = "Admin login successful.";
            } else {
                $message = "Invalid Login Credentials. Renter Correct Credentials";
            }
        } else {
            $message = "Admin account not found.";
        }
    }

    echo json_encode([
        "status" => $status,
        "message" => $message
    ]);
} else {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
