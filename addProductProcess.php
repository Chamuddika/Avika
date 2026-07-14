<?php

require "connection.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $status = "error";
    $message = "";

    $product_name = trim($_POST["product_name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $hair_type = trim($_POST["hair_type"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $quantity = trim($_POST["quantity"] ?? "");
    $weight = trim($_POST["weight"] ?? "");
    $volume = trim($_POST["volume"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $ingredients = trim($_POST["ingredients"] ?? "");
    $instruction = trim($_POST["instruction"] ?? "");
    $imagePath = "";
    $videoPath = "";
    $imageExtension = "";
    $videoExtension = "";

    if (empty($product_name)) {
        $message = "Please enter Product Name.";
    } else if (empty($category)) {
        $message = "Please select Product Category.";
    } else if (empty($hair_type)) {
        $message = "Please select Hair Type.";
    } else if (empty($price)) {
        $message = "Please enter Product Price.";
    } else if (!is_numeric($price) || $price <= 0) {
        $message = "Invalid Product Price.";
    } else if (empty($quantity)) {
        $message = "Please enter Product Quantity.";
    } else if (!is_numeric($quantity) || $quantity < 0) {
        $message = "Invalid Product Quantity.";
    } else if (empty($weight)) {
        $message = "Please enter Product Weight.";
    } else if (empty($volume)) {
        $message = "Please enter Bottle Volume.";
    } else if (empty($description)) {
        $message = "Please enter Product Description.";
    } else if (empty($ingredients)) {
        $message = "Please enter Product Ingredients.";
    } else if (empty($instruction)) {
        $message = "Please enter Product Usage Instructions.";
    }

    // image validation
    if (empty($message)) {

        if (!isset($_FILES["product_image"]) || $_FILES["product_image"]["error"] != 0) {
            $message = "Please select a product image.";
        } else {

            $imageExtension = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));

            $allowedImageTypes = ["jpg", "jpeg", "png"];

            if (!in_array($imageExtension, $allowedImageTypes)) {
                $message = "Invalid image format. Only JPG, JPEG, PNG and WEBP are allowed.";
            } else if ($_FILES["product_image"]["size"] > (5 * 1024 * 1024)) {
                $message = "Image size must be less than 5MB.";
            }
        }
    }

    // video validation
    if (empty($message)) {

        if (!isset($_FILES["video"]) || $_FILES["video"]["error"] != 0) {
            $message = "Please select an instruction video.";
        } else {

            $videoExtension = strtolower(
                pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION)
            );

            $allowedVideoTypes = ["mp4", "mov", "webm"];

            if (!in_array($videoExtension, $allowedVideoTypes)) {
                $message = "Invalid video format. Only MP4, MOV and WEBM are allowed.";
            } else if ($_FILES["video"]["size"] > (50 * 1024 * 1024)) {
                $message = "Video size must be less than 50MB.";
            }
        }
    }

    // file uploding part
    if (empty($message)) {

        $imageFolder = "resources/product-images/";
        $videoFolder = "resources/product-videos/";

        if (!file_exists($imageFolder)) {
            mkdir($imageFolder, 0777, true);
        }

        if (!file_exists($videoFolder)) {
            mkdir($videoFolder, 0777, true);
        }

        $imageName = uniqid("product_", true) . "." . $imageExtension;
        $videoName = uniqid("video_", true) . "." . $videoExtension;

        $imagePath = $imageFolder . $imageName;
        $videoPath = $videoFolder . $videoName;

        if (!move_uploaded_file($_FILES["product_image"]["tmp_name"], $imagePath)) {
            $message = "Failed to upload product image.";
        } else if (!move_uploaded_file($_FILES["video"]["tmp_name"], $videoPath)) {
            $message = "Failed to upload product video.";
        }
    }

    // save data in db
    if (empty($message)) {

        $d = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $d->setTimezone($tz);
        $date = $d->format("Y-m-d H:i:s");

        Database::iud("INSERT INTO `product`
            (`name`,`description`,`img_url`,`instruction`,`instruction_video_url`,`ingredients`,`hair_type`,`category`,`created_at`,`updated_at`)
            VALUES
            ('" . $product_name . "',
            '" . $description . "',
            '" . $imagePath . "',
            '" . $instruction . "',
            '" . $videoPath . "',
            '" . $ingredients . "',
            '" . $hair_type . "',
            '" . $category . "',
            '" . $date . "',
            '" . $date . "')");

        $product_id = Database::$connection->insert_id;

        Database::iud("INSERT INTO `stock`
        (`price`,`qty`,`weight`,`capacity`,`created_at`,`updated_at`,`product_id`)
        VALUES
        ('" . $price . "','" . $quantity . "','" . $weight . "','" . $volume . "','" . $date . "','" . $date . "','" . $product_id . "')");

        $status = "success";
        $message = "Product added successfully.";
    }

    echo json_encode([
        "status" => $status,
        "message" => $message
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
