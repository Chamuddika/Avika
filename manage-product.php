<?php

session_start();

require "connection.php";
if (isset($_SESSION["admin"])) {
    $pageTitle = "Manage Product";
    $product_rs = Database::search("SELECT * FROM `product`");
    $product_num = $product_rs->num_rows;
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products - Avika Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="resources/logo.png">
    </head>

    <body>

        <?php include './includes/admin-sidebar.php'; ?>
        <?php include './includes/admin-topbar.php'; ?>

        <div class="admin-table">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h5 class="fw-bold mb-0">All Products (<?php echo $product_num ?>)</h5>
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-circle me-2"></i>Add New Product
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        for ($x = 0; $x < $product_num; $x++) {
                            $product_data = $product_rs->fetch_assoc();
                            $stock_rs = Database::search("SELECT * FROM `stock` WHERE `product_id` = '" . $product_data["id"] . "'");
                            $stock_data = $stock_rs->fetch_assoc();
                        ?>
                            <tr>
                                <td><img src="<?php echo $product_data["img_url"]; ?>" width="40" height="40" class="rounded" style="object-fit:cover;"></td>
                                <td class="fw-bold text-dark"><?php echo $product_data["name"]; ?></td>
                                <?php
                                if ($product_data["category"] == "1") {
                                ?>
                                    <td>Shampoo</td>
                                <?php
                                } else if ($product_data["category"] == "2") {
                                ?>
                                    <td>Conditioner</td>
                                <?php
                                } else if ($product_data["category"] == "3") {
                                ?>
                                    <td>Hair Oil</td>
                                <?php
                                } else if ($product_data["category"] == "4") {
                                ?>
                                    <td>Hair Serum</td>
                                <?php
                                } else {
                                ?>
                                    <td>Hair Mask</td>
                                <?php
                                }
                                ?>
                                <td>RS. <?php echo $stock_data["price"] ?>.00</td>
                                <?php
                                if ($stock_data["qty"] >= 6) {
                                ?>
                                    <td><span class="badge bg-success">In Stock (<?php echo $stock_data["qty"] ?>)</span></td>
                                <?php
                                } else if ($stock_data["qty"] <= 5) {
                                ?>
                                    <td><span class="badge bg-warning text-dark">Low Stock (<?php echo $stock_data["qty"] ?>)</span></td>
                                <?php
                                } else {
                                ?>
                                    <td><span class="badge bg-danger">Out of Stock</span></td>
                                <?php
                                }
                                ?>

                                <td>
                                    <button class="action-btn btn-edit" onclick="editProduct(<?php echo $stock_data['id']; ?>)"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="action-btn btn-delete"  onclick="deleteProduct(<?php echo $stock_data['id']; ?>)"><i class="bi bi-trash-fill"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content rounded-4 overflow-hidden">
                    <div class="modal-header bg-cream border-0">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add New Product
                        </h5>
                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>
                    </div>
                    <form id="addProductForm"
                        method="POST"
                        enctype="multipart/form-data">
                        <input type="hidden" name="stock_id" id="stock_id">
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Product Name *
                                    </label>
                                    <input type="text"
                                        name="product_name"
                                        class="form-control"
                                        placeholder="Enter Product Name"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Category *
                                    </label>
                                    <select name="category"
                                        class="form-select"
                                        required>
                                        <option value="">Select Category</option>
                                        <option value="1">Shampoo</option>
                                        <option value="2">Conditioner</option>
                                        <option value="3">Hair Oil</option>
                                        <option value="4">Hair Serum</option>
                                        <option value="5">Hair Mask</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Hair Type *
                                    </label>
                                    <input type="text"
                                        name="hair_type"
                                        class="form-control"
                                        placeholder="Matching Hair Type"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">
                                        Price *
                                    </label>
                                    <input type="number"
                                        name="price"
                                        step="0.01"
                                        class="form-control"
                                        placeholder="0.00"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">
                                        Quantity *
                                    </label>
                                    <input type="number"
                                        name="quantity"
                                        class="form-control"
                                        placeholder="0"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Weight (g) *
                                    </label>
                                    <input type="number"
                                        name="weight"
                                        class="form-control"
                                        placeholder="Enter Product Weight"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Bottle Volume (ml) *
                                    </label>
                                    <input type="number"
                                        name="volume"
                                        class="form-control"
                                        placeholder="Enter Bottle Volume"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class=" form-label fw-bold">
                                        Product Image *
                                    </label>
                                    <input
                                        type="file"
                                        id="productImage"
                                        name="product_image"
                                        accept="image/*"
                                        hidden>
                                    <div id="imageBox"
                                        class="border rounded-3 d-flex flex-column align-items-center justify-content-center"
                                        style="height:250px;cursor:pointer;">

                                        <img
                                            id="imagePreview"
                                            src="resources/addproductimg.svg"
                                            style="
                                            max-width:100%;
                                            max-height:100%;
                                            object-fit:contain;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Instruction Video
                                    </label>
                                    <input type="file"
                                        id="videoInput"
                                        accept="video/*"
                                        name="video"
                                        hidden>
                                    <div id="videoBox">
                                        <div id="videoThumbnail"
                                            class="border rounded-3 d-flex flex-column align-items-center justify-content-center d-block"
                                            style="height:250px; cursor:pointer;">
                                            <i class="bi bi-play-circle fs-1"></i>
                                            <small class="text-muted">
                                                Click to Upload Video
                                            </small>
                                        </div>
                                        <video
                                            id="videoPreview"
                                            controls
                                            style="
                                                display:none;
                                                width:100%;
                                                border-radius:12px;
                                                cursor:pointer;
                                            ">
                                        </video>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">
                                        Product Description *
                                    </label>
                                    <textarea
                                        name="description"
                                        rows="4"
                                        class="form-control"
                                        placeholder="Enter Description"
                                        required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Product Ingredients *
                                    </label>
                                    <textarea
                                        name="ingredients"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Argan Oil, Coconut Oil, Vitamin E..."
                                        required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        How To Use *
                                    </label>
                                    <textarea
                                        name="instruction"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Apply to wet hair and massage..."
                                        required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" id="btn"
                                class="btn btn-primary-custom">
                                <i class="bi bi-check2-circle me-2"></i>
                                Save Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <?php include './includes/admin-footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="js/script.js"></script>
        <script src="js/main.js"></script>
    </body>

    </html>
<?php
} else {
    header("Location: admin-login.php");
}
?>