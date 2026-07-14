<?php
session_start();
require "connection.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message = "";
    $status = "error";

    $u_id = $_SESSION["u"]["id"];
    $rating = $_POST["rating"] ?? "";
    $review = trim($_POST["review"] ?? "");
    $pid = $_POST["pid"] ?? "";

    $d = new DateTime();
    $tz = new DateTimeZone(timezone: "Asia/Colombo");
    $d->setTimezone(timezone: $tz);
    $date = $d->format(format: "Y-m-d H:i:s");

    if (empty($rating)) {
        $message = "Please enter your Rating.";
    } else if (empty($pid)) {
        $message = "Something Went Wrong";
    } else {
        Database::iud("INSERT INTO `reviews`(`review`,`rating`,`created_at`,`product_id`, `users_id`) VALUES 
     ('" . $review . "','" . $rating . "','" . $date . "','" . $pid . "' ,'" . $u_id . "')");

        $message = "Success";
        $status = "success";
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
