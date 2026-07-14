<?php

session_start();
require "connection.php";

header("Content-Type: application/json");

if (!isset($_SESSION["u"])) {
    echo json_encode([
        "status" => "error",
        "message" => "Please login first."
    ]);
    exit;
}

$userId = $_SESSION["u"]["id"];
$addressId = $_POST["id"] ?? "";

if (empty($addressId)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid address."
    ]);
    exit;
}

$rs = Database::search("SELECT * FROM `address` WHERE `id`='" . $addressId . "' AND `users_id`='" . $userId . "'");

if ($rs->num_rows == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Address not found."
    ]);
    exit;
}

$address = $rs->fetch_assoc();

if ($address["is_default"] == 1) {
    echo json_encode([
        "status" => "error",
        "message" => "Default address cannot be deleted."
    ]);
    exit;
}

Database::iud("DELETE FROM `address` WHERE `id`='" . $addressId . "'");

echo json_encode([
    "status" => "success",
    "message" => "Address deleted successfully."
]);
