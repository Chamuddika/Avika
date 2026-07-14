<?php

require "connection.php";

$price = $_POST["price"];
$categories = json_decode($_POST["categories"], true);

$sql = "SELECT * FROM product INNER JOIN stock ON stock.product_id=product.id WHERE stock.price<='$price'";

if (!empty($categories)) {
    $categoryList = "'" . implode("','", $categories) . "'";
    $sql .= " AND product.category IN ($categoryList)";
}

$limit = 12;

$page = isset($_POST["page"]) ? (int)$_POST["page"] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$count_rs = Database::search($sql);
$totalProducts = $count_rs->num_rows;

$totalPages = ceil($totalProducts / $limit);

$sql .= " ORDER BY product.id DESC LIMIT $offset,$limit";
$rs = Database::search($sql);

?>
    <?php
    while ($row = $rs->fetch_assoc()) {

    ?>
        <div class="col-xl-4 col-md-6  shadow-sm">
            <div class="product-card">
                <a href="product.php?id=<?php echo $row["product_id"] ?>">
                    <div class="product-img-wrap">
                        <img src="<?php echo $row["img_url"] ?>" alt="img">
                    </div>
                </a>
                <div class="product-body d-flex flex-column">
                    <small class="text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;"><?php
                                                                                                            if ($row["category"] == "1") {
                                                                                                            ?>
                        Shampoo
                    <?php
                                                                                                            } else if ($row["category"] == "2") {
                    ?>
                        Conditioner
                    <?php
                                                                                                            } else if ($row["category"] == "3") {
                    ?>
                        Hair Oil
                    <?php
                                                                                                            } else if ($row["category"] == "4") {
                    ?>
                        Hair Serum
                    <?php
                                                                                                            } else {
                    ?>
                        Hair Mask
                    <?php
                                                                                                            }
                    ?></small>
                    <h5 class="mb-1 mt-1"><?php echo $row["name"] ?></h5>
                    <p class="text-muted small mb-3 flex-grow-1"><?php echo substr($row["description"], 0, 65); ?>...</p>
                    <span class="product-price">RS. <?php echo number_format($row["price"], 2); ?></span>
                    <a href="product.php?id=<?php echo $row["product_id"] ?>" class=" btn-view-product">View Product <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    <?php } ?>