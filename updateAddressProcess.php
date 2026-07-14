<?php

session_start();
require "connection.php";

header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["u"])) {
        $message = "";
        $status = "error";

        $id = $_POST["address_id"];

        $title = $_POST["title"];
        $line_one = $_POST["line_one"];
        $line_two = $_POST["line_two"];
        $city = $_POST["city"];
        $postal_code = $_POST["postal_code"];
        $is_default = isset($_POST["default"]) ? 1 : 0;
        $user_id = $_SESSION["u"]["id"];

        if (empty($title)) {
            $message = "Please enter address title.";
        } else if (empty($line_one)) {
            $message = "Please enter address line 1.";
        } else if (empty($city)) {
            $message = "Please enter city.";
        } else if (empty($postal_code)) {
            $message = "Please enter postal code.";
        } else {

            $d = new DateTime();
            $d->setTimezone(new DateTimeZone("Asia/Colombo"));
            $date = $d->format("Y-m-d H:i:s");

            if ($is_default == 1) {
                Database::iud("UPDATE address SET `is_default` = 0, `updated_at` = '" . $date . "'  WHERE `users_id` = '" . $user_id . "' ");
            }

            Database::iud(" UPDATE address SET `title`='" . $title . "',`line_one`='" . $line_one . "',`line_two`='" . $line_two . "',`city`='" . $city . "',`postal_code`='" . $postal_code . "',`is_default`='" . $is_default . "',`updated_at` = '" . $date . "' WHERE `id`='" . $id . "'");
            $status = "success";
            $message = "Address updated successfully.";
        }

        echo json_encode([
            "status" =>  $status,
            "message" =>  $message
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Please login first."
        ]);
    }
}
