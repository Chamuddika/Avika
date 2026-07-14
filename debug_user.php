<?php
require "connection.php";
header('Content-Type: application/json');

$email = trim($_GET['email'] ?? $_POST['email'] ?? '');
if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "Email required."]);
    exit();
}

$rs = Database::search("SELECT `id`, `email`, `is_active`, `status`, `verification_code`, `created_at`, `updated_at` FROM `users` WHERE `email`='" . $email . "'");
if (!$rs) {
    echo json_encode(["status" => "error", "message" => "DB query failed.", "error" => Database::$connection->error ?? null]);
    exit();
}

if ($rs->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "User not found."]);
    exit();
}

$user = $rs->fetch_assoc();
// Return values as-is to inspect raw DB values
echo json_encode(["status" => "success", "user" => $user]);
