<?php
require "connection.php";
$keyword = trim($_POST["keyword"] ?? "");
$sql = "SELECT * FROM users";
if (!empty($keyword)) {
    $sql .= " WHERE name LIKE '%$keyword%' OR email LIKE '%$keyword%'";
}
$sql .= " ORDER BY created_at DESC";
$rs = Database::search($sql);

?>

<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Joined Date</th>
                <th>Orders</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php
            while ($row = $rs->fetch_assoc()) {

                $firstChar = strtoupper(mb_substr($row["name"], 0, 1));
                $joined = date("M d, Y", strtotime($row["created_at"]));
                $order_rs = Database::search("SELECT COUNT(*) AS total FROM order_data WHERE users_id='" . $row["id"] . "'");
                $order = $order_rs->fetch_assoc();
            ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <span
                                class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold me-3"
                                style="width:40px;height:40px;background:#6c757d;font-size:18px;">

                                <?= $firstChar ?>

                            </span>
                            <span class="fw-bold">
                                <?= htmlspecialchars($row["name"]) ?>
                            </span>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= $joined ?></td>
                    <td><?= $order["total"] ?></td>
                    <td>
                        <?php if ($row["status"] == 1) { ?>
                            <span class="badge bg-danger">
                                Suspended
                            </span>
                        <?php } else { ?>
                            <span class="badge bg-success">Active</span>
                        <?php } ?>
                    </td>

                    <td>
                        <?php if ($row["status"] == 1) { ?>
                            <button class="action-btn btn-active" id="ub<?= $row["id"] ?>" onclick="blockUser('<?= $row['id'] ?>')"> <i class="bi bi-person-check"></i></button>
                        <?php } else { ?>
                            <button
                                class="action-btn btn-delete"
                                id="ub<?= $row["id"] ?>"
                                onclick="blockUser('<?= $row['id'] ?>')">
                                <i class="bi bi-person-dash"></i>
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>