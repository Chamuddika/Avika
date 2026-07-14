<?php

session_start();
require "connection.php";

header("Content-Type: application/json");

if (isset($_SESSION["u"])) {

    $user_id = $_SESSION["u"]["id"];
    $rs = Database::search("SELECT a.*, u.name, u.email, u.mobile FROM address a INNER JOIN users u ON a.users_id = u.id WHERE a.users_id = '" . $user_id . "' AND a.is_default = 1 ");

    if ($rs->num_rows == 1) {
        $data = $rs->fetch_assoc();

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No default address found."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Please login first."
    ]);
}
