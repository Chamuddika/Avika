<?php

session_start();
require "connection.php";

if (isset($_SESSION["u"])) {

    $uid =  $_SESSION["u"]["id"];
    $order_id = $_POST["oid"];
    $sid = $_POST["sid"];
    $qty = $_POST["qty"];
    $total = 0;
    $shipping = 0;
    $delivery  = 0;

    $d = new DateTime();
    $tz = new DateTimeZone(timezone: "Asia/Colombo");
    $d->setTimezone(timezone: $tz);
    $date = $d->format(format: "Y-m-d H:i:s");

    $stock_rs = Database::search("SELECT * FROM `stock` WHERE `id`='" . $sid . "'");
    $stock_data = $stock_rs->fetch_assoc();

    $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . $stock_data["product_id"] . "'");
    $product_data = $product_rs->fetch_assoc();

    $price = $stock_data["price"];


    $w = ($stock_data["weight"] / 1000) * $qty;
    $fw = ceil($w);

    if ($fw <= 1) {
        $shipping = $fw * 350;
    } else {
        $pwe = $fw - 1;
        $fwe = ceil($pwe);
        $shipping = ($fwe * 180) + 350;
    }
    $delivery = $delivery + $shipping;
    $total = ((int) $stock_data["price"] * (int)$qty) + (int)$delivery;

    Database::iud("INSERT INTO `order_data`(`order_id`,`shipping`,`status`,`total`,`users_id`,`created_at`,`updated_at`) VALUES 
    ('" . $order_id . "','" . $shipping . "','1','" . $total . "','" . $uid . "','" . $date . "', '" . $date . "')");

    $invD_rs = Database::search("SELECT * FROM `order_data` WHERE `order_id`='" . $order_id . "'");
    $invD_Data = $invD_rs->fetch_assoc();
    $id = $invD_Data["id"];

    Database::iud("INSERT INTO `order_product`(`qty`,`price`,`stock_id`,`order_data_id`) VALUES
     ('" . $qty . "','" . $price . "','" . $sid . "','" . $id . "')");

    $current_qty = $stock_data["qty"];
    $new_qty = $current_qty - $qty;
    Database::iud("UPDATE `stock` SET `qty`='" . $new_qty . "' WHERE `id`='" . $sid . "'");

    $address_rs = Database::search("SELECT * FROM `address` WHERE `users_id`='" . $uid . "' AND `is_default` = 1");
    $address_data = $address_rs->fetch_assoc();
    $user_rs = Database::search("SELECT * FROM `users` WHERE `id`='" . $uid . "'");
    $user_data = $user_rs->fetch_assoc();

    $email = $user_data["email"];
    $name = $user_data["name"];
    $mobile = $user_data["mobile"];
    $line1 = $address_data["line_one"];
    $line2 = $address_data["line_two"];
    $city = $address_data["city"];
    $code = $address_data["postal_code"];


    Database::iud("INSERT INTO `order_address`(`email`,`city`,`name`,`line_one`,`line_two`,`mobile`,`postal_code`,`order_data_id`) VALUES 
    ('" . $email . "','" . $city . "','" . $name . "','" . $line1 . "','" . $line2 . "','" . $mobile . "','" . $code . "','" . $id . "')");

    echo ("1");
}
