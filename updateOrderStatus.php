<?php

session_start();
require "connection.php";
header("Content-Type: application/json");

if (!isset($_SESSION["admin"])) {

    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit;
}

$order_id = $_POST["order_id"] ?? "";
$status   = $_POST["status"] ?? "";

if (empty($order_id) || empty($status)) {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid Request"
    ]);
    exit;
}

$d = new DateTime();
$d->setTimezone(new DateTimeZone("Asia/Colombo"));
$date = $d->format("Y-m-d H:i:s");

Database::iud(" UPDATE `order_data` SET `status`='$status', `updated_at`='$date' WHERE `id`='$order_id' ");

echo json_encode([
    "status" => "success",
    "message" => "Order status updated successfully."
]);
