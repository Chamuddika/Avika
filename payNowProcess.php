<?php

session_start();
require "connection.php";

if (isset($_SESSION["u"]["email"])) {

    $sid = $_GET["id"];
    $qty = $_GET["qty"];
    $email = $_SESSION["u"]["email"];
    $id = $_SESSION["u"]["id"];
    $user = $_SESSION["u"];
    $error = '';
    $shipping = 0;
    $delivery = 0;

    $stock_rs = Database::search("SELECT * FROM `stock` WHERE id = '" . $sid . "' ");
    $stock_data = $stock_rs->fetch_assoc();

    $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" .  $stock_data["product_id"] . "'");
    $product_data = $product_rs->fetch_assoc();

    $user_rs = Database::search("SELECT * FROM `address` WHERE `users_id`='" . $id . "' AND `is_default`='1' LIMIT 1");
    $user_num = $user_rs->num_rows;
    $json = [];

    if ($user_num >= 1) {
        for ($i = 0; $i < $user_num; $i++) {
            $user_data = $user_rs->fetch_assoc();

            if ($user["status"] == 0) {

                if ($user_data["is_default"]) {

                    $merchantId = "1236668";
                    $merchantSecret = "NzkzNTI3OTE5MzQxODk5MjMxNTIzNTg4MjM5NTIyNTkzODY4OTI5";
                    $items = [];
                    $netTotal = 0;
                    $currency = "LKR";
                    $orderId = uniqid();

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

                    $item = $product_data["name"];
                    $qtyList[] = $qty;

                    $netTotal = ((int)$stock_data["price"] * (int)$qty) + (int)$delivery;


                    $nameParts = explode(" ", trim($user["name"]), 2);

                    $firstName = $nameParts[0];
                    $lastName = isset($nameParts[1]) ? $nameParts[1] : "";

                    $hash = strtoupper(
                        md5(
                            $merchantId .
                                $orderId .
                                number_format($netTotal, 2, '.', '') .
                                $currency .
                                strtoupper(md5($merchantSecret))
                        )
                    );
                    $payment = [];
                    $payment["sandbox"] = true;
                    $payment["merchant_id"] = $merchantId;
                    $payment["return_url"] =  "http://localhost/avika-v.1/index.php";
                    $payment["cancel_url"] =  "http://localhost/avika-v.1/index.php";
                    $payment["notify_url"] =  "http://sample.com/notify";
                    $payment["order_id"] = $orderId;
                    $payment["items"] = implode(", ", $items);
                    $payment["amount"] = number_format($netTotal, 2, '.', '');
                    $payment["currency"] = $currency;
                    $payment["hash"] = $hash;
                    $payment["first_name"] = $firstName;
                    $payment["last_name"] = $lastName;
                    $payment["email"] = $email;
                    $payment["phone"] = $user["mobile"];
                    $payment["address"] = $user_data["line_one"] . " , " . $user_data["line_two"];
                    $payment["city"] =  $user_data["city"];
                    $payment["country"] =    "Sri Lanka";

                    $json = [];

                    if (empty($error)) {
                        $json["status"] = "success";
                        $json["payment"] = $payment;
                    } else {
                        $json["status"] = "error";
                        $json["error"] = $error;
                    }
                } else {
                    $json["status"] = "3";
                    $json["error"] = "3";
                }
            } else {
                $json["status"] = "4";
                $json["error"] = "4";
            }
        }
    } else {
        $json["status"] = "2";
        $json["error"] = "2";
    }
} else {
    $json["status"] = "1";
    $json["error"] = "1";
}

echo json_encode($json);
