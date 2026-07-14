<?php

session_start();
require "connection.php";

if (isset($_SESSION["u"]["email"])) {

    $user = $_SESSION["u"];
    $id = $_SESSION["u"]["id"];
    $rs = Database::search("SELECT * FROM `users` WHERE `id`='" . $id . "'");
    $user = $rs->fetch_assoc();

    $first_name = trim($_GET["first_name"] ?? "");
    $last_name = trim($_GET["last_name"] ?? "");
    $email = trim($_GET["email"] ?? "");
    $mobile = trim($_GET["mobile"] ?? "");
    $address1 = trim($_GET["address1"] ?? "");
    $address2 = trim($_GET["address2"] ?? "");
    $city = trim($_GET["city"] ?? "");
    $postal_code = trim($_GET["postal_code"] ?? "");

    $error = '';
    $shipping = 0;

    $stockList = [];
    $qtyList = [];


    if (isset($first_name, $last_name, $email, $mobile, $address1, $address2, $city, $postal_code)) {

        if ($user["status"] == 0) {

            if (isset($_GET["cart"]) && $_GET["cart"] == "true") {

                $rs = Database::search("SELECT * FROM `cart` WHERE `users_id`='" . $id . "'");
                $num = $rs->num_rows;

                for ($i = 0; $i < $num; $i++) {
                    $row = $rs->fetch_assoc();

                    $stockList[] = $row["stock_id"];
                    $qtyList[] = $row["qty"];
                }
            }

            $merchantId = "1236668";
            $merchantSecret = "NzkzNTI3OTE5MzQxODk5MjMxNTIzNTg4MjM5NTIyNTkzODY4OTI5";
            $items = [];
            $netTotal = 0;
            $currency = "LKR";
            $orderId = uniqid();

            for ($x = 0; $x < sizeof($stockList); $x++) {

                $stockRs = Database::search("SELECT * FROM `stock` WHERE `id`='" . $stockList[$x] . "'");
                $stock =  $stockRs->fetch_assoc();

                $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . $stockList[$x] . "'");
                $product =  $product_rs->fetch_assoc();

                $stockQty = $stock["qty"];

                if ($stockQty >= $qtyList[$x]) {
                    $item[] = $product["name"];
                    $netTotal += $stock["price"] * $qtyList[$x];
                } else {
                    $error = "Insufficient Quantity";
                }

                $w = ($stock["weight"] / 1000) * $row["qty"];
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
            }
            $netTotal += (int)$shipping;

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
            $payment["first_name"] = $first_name;
            $payment["last_name"] = $last_name;
            $payment["email"] = $email;
            $payment["phone"] = $mobile;
            $payment["address"] = $address1 . " , " . $address2;
            $payment["city"] =  $city;
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
        $json["status"] = "2";
        $json["error"] = "2";
    }
} else {

    $json["status"] = "1";
    $json["error"] = "1";
}

echo json_encode($json);
