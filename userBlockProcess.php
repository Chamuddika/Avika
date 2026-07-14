<?php

require "connection.php";

if (isset($_GET["id"])) {

    $user_id = $_GET["id"];
    $user_rs = Database::search("SELECT * FROM `users` WHERE `id`='" . $user_id . "'");
    $user_num = $user_rs->num_rows;

    if ($user_num == 1) {

        $user_data = $user_rs->fetch_assoc();

        if ($user_data["status"] == 1) {
            Database::iud("UPDATE `users` SET `status`= 0 WHERE `id`='" . $user_id . "'");
            echo ("blocked");
        } else if ($user_data["status"] == 0) {
            Database::iud("UPDATE `users` SET `status`= 1 WHERE `id`='" . $user_id . "'");
            echo ("unblocked");
        }
    } else {
        echo ("Cannot find the user. Please try again later.");
    }
} else {
    echo ("Something went wrong.");
}
