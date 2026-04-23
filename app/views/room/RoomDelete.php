<?php
require_once '../../../config/config.php';
require_once '../../controllers/RoomController.php';
require_once '../../models/AuditLogModel.php';

$controller = new RoomController($conn);

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

$controller->delete($id);

$room = $controller->getOne($id);

if (!$room) {
    die("Room not found");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Delete Room</title>
        <link rel="stylesheet" href="../../../public/css/delete_style.css">
    </head>
    <body>

        <form method="POST">

            <?= Csrf::field() ?>

            <h2>Delete Room</h2>

            <div class="warning-box">
                <h3 style="margin-top:0; color:#a00;">Are you sure?</h3>
                <p>Action cannot be reversed!</p>
                <hr style="border: 0; border-top: 1px solid #ffcccc; margin: 10px 0;">

                <p><strong>Room Number:</strong> <?= htmlspecialchars($room['number']) ?></p>
                <p><strong>Floor:</strong> <?= htmlspecialchars($room['floor']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars(ucfirst($room['status'])) ?></p>
            </div>

            <input type="hidden" name="id" value="<?= $room['id_room'] ?? $room['id'] ?>">

            <button type="submit" class="btn btn-danger">Confirm Delete</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'RoomView.php'">Cancel</button>

        </form>

    </body>
</html>