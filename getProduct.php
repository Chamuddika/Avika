<?php

require "connection.php";

$id=$_GET["id"];

$stock_rs=Database::search("
SELECT *
FROM stock
INNER JOIN product
ON stock.product_id=product.id
WHERE stock.id='".$id."'
");

if($stock_rs->num_rows==1){

    $data=$stock_rs->fetch_assoc();

    echo json_encode([
        "status"=>"success",

        "stock"=>[
            "id"=>$data["id"],
            "price"=>$data["price"],
            "qty"=>$data["qty"],
            "weight"=>$data["weight"],
            "capacity"=>$data["capacity"]
        ],

        "product"=>[
            "id"=>$data["product_id"],
            "name"=>$data["name"],
            "description"=>$data["description"],
            "category"=>$data["category"],
            "hair_type"=>$data["hair_type"],
            "ingredients"=>$data["ingredients"],
            "instruction"=>$data["instruction"],
            "img_url"=>$data["img_url"],
            "instruction_video_url"=>$data["instruction_video_url"]
        ]
    ]);

}else{

    echo json_encode([
        "status"=>"error"
    ]);

}