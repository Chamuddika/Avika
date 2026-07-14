<?php

require "connection.php";

header("Content-Type: application/json");

if (!isset($_GET["id"])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request."
    ]);
    exit();
}

$stock_id = $_GET["id"];

$rs = Database::search(" SELECT * FROM stock INNER JOIN product ON stock.product_id=product.id WHERE stock.id='" . $stock_id . "' ");

if ($rs->num_rows != 1) {
    echo json_encode([
        "status" => "error",
        "message" => "Product not found."
    ]);
    exit();
}
$data = $rs->fetch_assoc();
$product_id = $data["product_id"];
$image = $data["img_url"];
$video = $data["instruction_video_url"];

if (!empty($image) && file_exists($image)) {
    unlink($image);
}

if (!empty($video) && file_exists($video)) {
    unlink($video);
}

Database::iud(" DELETE FROM stock WHERE id='" . $stock_id . "' ");


Database::iud(" DELETE FROM product WHERE id='" . $product_id . "' ");

echo json_encode([
    "status" => "success",
    "message" => "Product deleted successfully."
]);
