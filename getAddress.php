<?php

session_start();
require "connection.php";
header("Content-Type: application/json");

if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $rs = Database::search("SELECT * FROM `address` WHERE `id` = '" . $id . "' ");

    if ($rs->num_rows == 1) {

        $data = $rs->fetch_assoc();

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Address not found."
        ]);
    }
}
