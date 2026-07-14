<?php
require "connection.php";
header("Content-Type: application/json");

$email = trim($_POST["email"] ?? "");
$code = trim($_POST["code"] ?? "");

if (empty($email) || empty($code)) {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid request."
    ]);

    exit();
}

// Check user
$rs = Database::search("SELECT * FROM `users`
WHERE `email`='" . $email . "'
AND `verification_code`='" . $code . "'");

if ($rs->num_rows == 1) {

    $user = $rs->fetch_assoc();
    if ($user["is_active"] == 1) {
        echo json_encode([
            "status" => "success",
            "message" => "Account already verified."
        ]);
        return;
    }

    // Verify account
    Database::iud("UPDATE `users`
    SET
    `is_active`='1',
    `verification_code`=NULL
    WHERE `email`='" . $email . "'");

    echo json_encode([
        "status" => "success",
        "message" => "Verification successful."
    ]);
} else {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid or expired verification link."
    ]);
}
