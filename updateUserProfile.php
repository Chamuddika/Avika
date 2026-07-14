<?php

session_start();
require "connection.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["u"])) {

        $message = "";
        $status = "error";

        $first_name = trim($_POST["first_name"] ?? "");
        $last_name = trim($_POST["last_name"] ?? "");
        $mobile = trim($_POST["mobile"] ?? "");

        $d = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $d->setTimezone($tz);
        $date = $d->format("Y-m-d H:i:s");

        if (empty($first_name)) {
            $message = "Please enter First Name.";
        } else if (strlen($first_name) > 25) {
            $message = "Your First Name must have less than 25 characters.";
        } else if (empty($last_name)) {
            $message = "Please enter Last Name.";
        } else if (strlen($last_name) > 25) {
            $message = "Your Last Name must have less than 25 characters.";
        } else if (empty($mobile)) {
            $message = "Please enter Mobile Number.";
        } else if (!preg_match("/^07[01245678][0-9]{7}$/", $mobile)) {
            $message = "Invalid Mobile Number.";
        } else {

            $full_name = $first_name . " " . $last_name;

            $user_id = $_SESSION["u"]["id"];

            Database::iud(" UPDATE `users` SET `name` = '" . $full_name . "',`mobile` = '" . $mobile . "', `updated_at` = '". $date ."' WHERE `id` = '" . $user_id . "'");
            $message = "Profile updated successfully.";
            $status = "success";
        }

        echo json_encode([
            'status' => $status,
            'message' => $message
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Please login first."
        ]);
    }
}
