<?php
require "connection.php";


header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message = "";
    $status = "error";

    $email = trim($_POST["email"] ?? "");
    $new_pw = $_POST["np"] ?? "";
    $retyped_pw = $_POST["rnp"] ?? "";
    $v_code = $_POST["vc"] ?? "";

    if (empty($email)) {
        echo ("Please enter your email address.");
    } else if (empty($new_pw)) {
        echo ("Please enter a New Password.");
    } else if (strlen($new_pw) < 5 || strlen($new_pw) > 20) {
        echo ("Invalid New Password.");
    } else if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$])[A-Za-z\d@#$]{8,16}$/", $new_pw)) {
        $message = "Password must contain letters, numbers and @ # $ symbols.";
    } else if (empty($retyped_pw)) {
        echo ("Please Retype the New Password.");
    } else if ($new_pw != $retyped_pw) {
        echo ("Password does not matched.");
    } else if (empty($v_code)) {
        echo ("Please enter your verification code.");
    } else {
        $rs = Database::search("SELECT * FROM `users` WHERE `email`='" . $email . "' AND 
    `verification_code`='" . $v_code . "'");

        $n = $rs->num_rows;

        if ($n == 1) {
            $hashedPassword = password_hash($new_pw, PASSWORD_DEFAULT);
            Database::iud("UPDATE `users` SET `password`='" . $hashedPassword . "' ,`verification_code`= NULL 
            WHERE `email`='" . $email . "' AND 
        `verification_code`='" . $v_code . "'");

            $status = "success";
            $message = "Password Reset Successfully Completed. ";
        } else {
            $message = "Invalid user details. '" . $email . "' ";
        }
    }

    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);
} else {

    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
