<?php

require "connection.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $status = "error";
    $message = "";

    $stock_id = trim($_POST["stock_id"] ?? "");

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

    if (empty($stock_id)) {
        $message = "Invalid product.";
    } elseif (empty($product_name)) {
        $message = "Please enter Product Name.";
    } elseif (empty($category)) {
        $message = "Please select Category.";
    } elseif (empty($hair_type)) {
        $message = "Please enter Hair Type.";
    } elseif (empty($price)) {
        $message = "Please enter Price.";
    } elseif (empty($quantity)) {
        $message = "Please enter Quantity.";
    } elseif (empty($weight)) {
        $message = "Please enter Weight.";
    } elseif (empty($volume)) {
        $message = "Please enter Bottle Volume.";
    } elseif (empty($description)) {
        $message = "Please enter Description.";
    } elseif (empty($ingredients)) {
        $message = "Please enter Ingredients.";
    } elseif (empty($instruction)) {
        $message = "Please enter Instructions.";
    } else {
        $rs = Database::search(" SELECT * FROM stock INNER JOIN product ON stock.product_id = product.id WHERE stock.id = '" . $stock_id . "' ");

        if ($rs->num_rows != 1) {
            $message = "Product not found.";
        } else {
            $data = $rs->fetch_assoc();
            $product_id = $data["product_id"];
            $imagePath = $data["img_url"];
            $videoPath = $data["instruction_video_url"];

            if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
                $imageExtension = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));
                $allowedImages = ["jpg", "jpeg", "png", "webp"];
                if (!in_array($imageExtension, $allowedImages)) {
                    $message = "Only JPG, JPEG, PNG and WEBP images are allowed.";
                } elseif ($_FILES["product_image"]["size"] > (5 * 1024 * 1024)) {
                    $message = "Image size must be less than 5MB.";
                }
            }

            if (empty($message)) {
                if (isset($_FILES["video"]) && $_FILES["video"]["error"] == 0) {
                    $videoExtension = strtolower(pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION));
                    $allowedVideos = ["mp4", "mov"];
                    if (!in_array($videoExtension, $allowedVideos)) {
                        $message = "Only MP4 and MOV videos are allowed.";
                    } elseif ($_FILES["video"]["size"] > (50 * 1024 * 1024)) {
                        $message = "Video size must be less than 50MB.";
                    }
                }
            }

            if (empty($message)) {
                if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $imageName = uniqid("product_") . "." . $imageExtension;
                    $imagePath = "resources/product-images/" . $imageName;
                    move_uploaded_file(
                        $_FILES["product_image"]["tmp_name"],
                        $imagePath
                    );
                }

                if (isset($_FILES["video"]) && $_FILES["video"]["error"] == 0) {
                    if (file_exists($videoPath)) {
                        unlink($videoPath);
                    }
                    $videoName = uniqid("video_") . "." . $videoExtension;
                    $videoPath = "resources/product-videos/" . $videoName;
                    move_uploaded_file(
                        $_FILES["video"]["tmp_name"],
                        $videoPath
                    );
                }

                $d = new DateTime();
                $tz = new DateTimeZone("Asia/Colombo");
                $d->setTimezone($tz);
                $date = $d->format("Y-m-d H:i:s");

                Database::iud(" UPDATE product SET name = '" . $product_name . "', description = '" . $description . "', img_url = '" . $imagePath . "', 
                instruction = '" . $instruction . "', instruction_video_url = '" . $videoPath . "', ingredients = '" . $ingredients . "', hair_type = '" . $hair_type . "',
                 category = '" . $category . "', updated_at = '" . $date . "' WHERE id = '" . $product_id . "' ");

                Database::iud(" UPDATE stock SET price = '" . $price . "', qty = '" . $quantity . "', weight = '" . $weight . "', capacity = '" . $volume . "',
                    updated_at = '" . $date . "' WHERE id = '" . $stock_id . "'");

                $status = "success";
                $message = "Product updated successfully.";
            }
        }
    }

    echo json_encode([
        "status" => $status,
        "message" => $message
    ]);
} else {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid request."
    ]);
}
