<?php
session_start();
require "connection.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $order_id = $_GET["orderId"];
    $email =  $_SESSION["u"]["email"];
    $uid =  $_SESSION["u"]["id"];
    $is_default = $_POST["is_default"];
    $shipping = 0;
    $w =   0;
    $netTotal = 0;
    $finalPrice = 0;
    $line1 = "";
    $line2 = " ";
    $city = "";
    $code = "";
    $name = "";
    $address_email = "";
    $mobile = "";
    $cart_rs = Database::search("SELECT * FROM `cart` WHERE `users_id`='" . $uid . "'");
    $cart_num = $cart_rs->num_rows;

    $d = new DateTime();
    $tz = new DateTimeZone("Asia/Colombo");
    $d->setTimezone($tz);
    $date = $d->format("Y-m-d H:i:s");


    Database::iud("INSERT INTO `order_data`(`order_id`,`shipping`,`status`,`total`,`users_id`,`created_at`,`updated_at`) VALUES 
    ('" . $order_id . "','" . $shipping . "','1','" . $netTotal . "','" . $uid . "','" . $date . "', '" . $date . "')");


    for ($x = 0; $x < $cart_num; $x++) {
        $cart_data = $cart_rs->fetch_assoc();

        $stock_rs = Database::search("SELECT * FROM `stock` WHERE `id`='" . $cart_data["stock_id"] . "'");
        $stock_data = $stock_rs->fetch_assoc();

        $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . $stock_data["product_id"] . "'");
        $product_data = $product_rs->fetch_assoc();


        $netTotal = $netTotal + ($stock_data["price"] * $cart_data["qty"]);

        $w = ($w + ($stock_data["weight"] / 1000) * $cart_data["qty"]);
        $fw = ceil(num: $w);

        if ($netTotal <= 5000) {
            if ($fw <= 1) {
                $shipping = $fw * 350;
            } else {
                $pwe = $fw - 1;
                $fwe = ceil(num: $pwe);
                $shipping = ($fwe * 180) + 350;
            }
        }
        $finalPrice =  $shipping + $netTotal;


        $title = $product_data["name"];
        $qty = $cart_data["qty"];
        $price = $stock_data["price"];
        $sid = $stock_data["id"];

        $invD_rs = Database::search("SELECT * FROM `order_data` WHERE `order_id`='" . $order_id . "'");
        $invD_Data = $invD_rs->fetch_assoc();
        $id = $invD_Data["id"];

        Database::iud("INSERT INTO `order_product`(`price`,`qty`,`stock_id`,`order_data_id`) VALUES 
     ('" . $price . "','" . $qty . "','" . $sid . "' ,'" . $id . "')");

        $current_qty = $stock_data["qty"];
        $new_qty = $current_qty - $qty;
        Database::iud("UPDATE `stock` SET `qty`='" . $new_qty . "' WHERE `id`='" . $sid . "'");
    }

    Database::iud("UPDATE `order_data` SET`shipping`='" . $shipping . "', `total`='" . $finalPrice . "' WHERE `order_id`='" . $order_id . "'");
    if ($is_default == "true") {
        $address_rs = Database::search("SELECT * FROM `address` WHERE `users_id`='" . $uid . "' AND `is_default` = 1");
        $address_data = $address_rs->fetch_assoc();
        $user_rs = Database::search("SELECT * FROM `users` WHERE `id`='" . $uid . "'");
        $user_data = $user_rs->fetch_assoc();

        $name = $user_data["name"];
        $mobile = $user_data["mobile"];
        $line1 = $address_data["line_one"];
        $line2 = $address_data["line_two"];
        $city = $address_data["city"];
        $code = $address_data["postal_code"];

        Database::iud("INSERT INTO `order_address`(`email`,`city`,`name`,`line_one`,`line_two`,`mobile`,`postal_code`,`order_data_id`) VALUES 
    ('" . $email . "','" . $city . "','" . $name . "','" . $line1 . "','" . $line2 . "','" . $mobile . "','" . $code . "','" . $id . "')");
    } else {
        $name = trim($_POST["first_name"]) . " " . trim($_POST["last_name"]);
        $address_email = $_POST["email"];
        $mobile = $_POST["mobile"];
        $line1 = $_POST["address1"];
        $line2 = $_POST["address2"];
        $city = $_POST["city"];
        $code = $_POST["postal_code"];

        Database::iud("INSERT INTO `order_address`(`email`,`city`,`name`,`line_one`,`line_two`,`mobile`,`postal_code`,`order_data_id`) VALUES 
    ('" . $address_email . "','" . $city . "','" . $name . "','" . $line1 . "','" . $line2 . "','" . $mobile . "','" . $code . "','" . $id . "')");
    }


    Database::iud("DELETE FROM `cart` WHERE `users_id`='" . $uid . "'");


    echo ("1");
} else {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
