<?php

session_start();

require "connection.php";
if (isset($_SESSION["admin"])) {
    $pageTitle = "Manage Users";

    $users_rs = Database::search("SELECT * FROM `users`");
    $users_num = $users_rs->num_rows;
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Users - Avika Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="icon" href="resources/logo.png">
    </head>

    <body>

        <?php include './includes/admin-sidebar.php'; ?>
        <?php include './includes/admin-topbar.php'; ?>

        <div class="admin-table">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Registered Users (<?php echo $users_num ?>)</h5>
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" class="form-control" placeholder="Search users..." id="searchUser" onkeyup="searchUsers();">
                </div>
            </div>
            <div id="userTable">
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
                            for ($x = 0; $x < $users_num; $x++) {
                                $users_data = $users_rs->fetch_assoc();
                                $firstChar = strtoupper(
                                    mb_substr($users_data["name"], 0, 1, "UTF-8")
                                );
                                $year = date("M d, Y", strtotime($users_data["created_at"]));

                                $order_rs = Database::search("SELECT * FROM `order_data` WHERE `users_id` = '" . $users_data["id"] . "'");
                                $order_num = $order_rs->num_rows;
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold me-3" style="object-fit:cover; background:#6c757d; font-size:30px; width:40px; height:40px; "><?php echo $firstChar ?></span>
                                            <span class="fw-bold text-dark"><?php echo $users_data["name"] ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $users_data["email"] ?></td>
                                    <td><?php echo $year ?></td>
                                    <td><?php echo $order_num ?></td>
                                    <td>
                                        <?php if ($users_data["status"] ==  1) { ?>
                                            <span class="badge bg-danger">Suspended</span>
                                        <?php } else { ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($users_data["status"] ==  1) { ?>
                                            <button class="action-btn btn-active status-inactive" id="ub<?php echo $users_data['id']; ?>" onclick="blockUser('<?php echo $users_data['id']; ?>');"><i class="bi bi-person-check"></i></button>
                                        <?php } else { ?>
                                            <button class="action-btn btn-delete status-inactive" id="ub<?php echo $users_data['id']; ?>" onclick="blockUser('<?php echo $users_data['id']; ?>');"><i class="bi bi-person-dash"></i></button>
                                        <?php } ?>

                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <?php include './includes/admin-footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="./js/script.js"></script>
    </body>

    </html>
<?php
} else {
    header("Location: admin-login.php");
}
?>