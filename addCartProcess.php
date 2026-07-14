<?php

session_start();
require "connection.php";

if (isset($_SESSION["u"])) {
    if (isset($_POST["id"])) {

        $current_qty =  0;
        $sid = $_POST["id"];
        (int)$qty = $_POST["qty"];
        $u_id = $_SESSION["u"]["id"];

        $cart_rs = Database::search("SELECT * FROM `cart` WHERE `stock_id`='" . $sid . "' AND `users_id`='" . $u_id . "'");
        $cart_num = $cart_rs->num_rows;
        $cart_data = $cart_rs->fetch_assoc();

        $stock_rs = Database::search("SELECT * FROM `stock` WHERE id = '" . $sid . "' ");
        $stock_data = $stock_rs->fetch_assoc();


        (int)$product_qty =  $stock_data["qty"];

        if ($cart_num == 1) {
            $current_qty = $cart_data["qty"];
            $new_qty = (int)$current_qty + (int)$qty;

            if ($product_qty >= $new_qty) {

                Database::iud(q: "UPDATE `cart` SET `qty`='" . $new_qty . "' WHERE `stock_id`='" . $sid . "' AND `users_id`='" . $u_id . "'");
                echo ("update");
            } else {
                echo ("Invalid Quantity");
            }
        } else {

            if ($product_qty == 0) {
                echo ("Invalid Quantity");
            } else {
                Database::iud(q: "INSERT INTO `cart`(`qty`,`stock_id`,`users_id`) VALUES ('" . $qty . "','" . $sid . "','" . $u_id . "')");
                echo ("success");
            }
        }
    } else {
        echo ("Something Went Wrong");
    }
} else {
    echo ("Please Log In or Sign Up");
}
