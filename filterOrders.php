<?php

require "connection.php";

$status = $_POST["status"] ?? "";

$sql = "SELECT * FROM order_data";

if ($status != "") {
    $sql .= " WHERE status='" . $status . "'";
}

$sql .= " ORDER BY id DESC";

$order_rs = Database::search($sql);

while ($order_data = $order_rs->fetch_assoc()) {

    $year = date("M d, Y", strtotime($order_data["created_at"]));

    $users_rs = Database::search("SELECT * FROM users WHERE id='" . $order_data["users_id"] . "'");
    $users_data = $users_rs->fetch_assoc();

    $order_product_rs = Database::search("SELECT * FROM order_product WHERE order_data_id='" . $order_data["id"] . "'");
    $order_product_num = $order_product_rs->num_rows;

?>

    <tr>

        <td class="fw-bold text-dark">
            #AOID-<?php echo $order_data["order_id"]; ?>
        </td>

        <td><?php echo $users_data["name"]; ?></td>

        <td><?php echo $order_product_num; ?> Items</td>

        <td>RS.<?php echo number_format($order_data["total"], 2); ?></td>

        <td><?php echo $year; ?></td>

        <td>

            <?php

            switch ($order_data["status"]) {

                case 1:
                    echo '<span class="badge bg-warning">Pending</span>';
                    break;

                case 2:
                    echo '<span class="badge bg-primary">Processing</span>';
                    break;

                case 3:
                    echo '<span class="badge bg-info">Shipped</span>';
                    break;

                case 4:
                    echo '<span class="badge bg-success">Delivered</span>';
                    break;
            }

            ?>

        </td>

        <td>

            <select class="form-select form-select-sm order-status"
                data-id="<?php echo $order_data["id"]; ?>"
                style="width:150px;">

                <option value="1" <?php if ($order_data["status"] == 1) echo "selected"; ?>>Pending</option>

                <option value="2" <?php if ($order_data["status"] == 2) echo "selected"; ?>>Processing</option>

                <option value="3" <?php if ($order_data["status"] == 3) echo "selected"; ?>>Shipped</option>

                <option value="4" <?php if ($order_data["status"] == 4) echo "selected"; ?>>Delivered</option>

            </select>

        </td>

        <td>

            <a href="order-details.php?id=<?php echo $order_data['id']; ?>"
                class="action-btn btn-view">
                <i class="bi bi-eye-fill"></i>
            </a>

        </td>

    </tr>

<?php } ?>