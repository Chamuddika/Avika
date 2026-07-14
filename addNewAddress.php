<?php

session_start();
require "connection.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["u"])) {

        $message = "";
        $status = "error";


        $title = trim($_POST["title"] ?? "");
        $line_one = trim($_POST["line_one"] ?? "");
        $line_two = trim($_POST["line_two"] ?? "");
        $city = trim($_POST["city"] ?? "");
        $postal_code = trim($_POST["postal_code"] ?? "");
        $is_default = isset($_POST["defaultAddress"]) ? 0 : 1;

        if (empty($title)) {
            $message = "Please enter address title.";
        } else if (empty($line_one)) {
            $message = "Please enter address line 1.";
        } else if (empty($city)) {
            $message = "Please enter city.";
        } else if (empty($postal_code)) {
            $message = "Please enter postal code.";
        } else {

            $user_id = $_SESSION["u"]["id"];

            $d = new DateTime();
            $d->setTimezone(new DateTimeZone("Asia/Colombo"));
            $date = $d->format("Y-m-d H:i:s");

            if ($is_default == 1) {
                Database::iud("UPDATE `address` SET `is_default` = 0,`updated_at` = '" . $date . "' WHERE `users_id` = '" . $user_id . "' ");
            }

            Database::iud(" INSERT INTO `address` ( `title`, `line_one`,`line_two`,`city`,`postal_code`,`is_default`,`users_id`,`created_at`,`updated_at`)
            VALUES('" . $title . "','" . $line_one . "','" . $line_two . "','" . $city . "','" . $postal_code . "','" . $is_default . "','" . $user_id . "','" . $date . "','" . $date . "')");

            $status = "success";
            $message = "Address added successfully.";
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
