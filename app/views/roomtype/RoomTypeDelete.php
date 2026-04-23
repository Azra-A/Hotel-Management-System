<?php
require_once '../../../config/config.php';
require_once '../../controllers/RoomTypeController.php';
require_once '../../models/AuditLogModel.php';

$controller = new RoomTypeController($conn);

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

$controller->delete($id);

$type = $controller->getOne($id);

if (!$type) {
    die("Room Type not found");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Delete Room Type</title>
        <link rel="stylesheet" href="../../../public/css/delete_style.css">
    </head>
    <body>

        <form method="POST">

            <?= Csrf::field() ?>
            
            <h2>Delete Room Type</h2>

            <div class="warning-box">
                <h3 style="margin-top:0; color:#a00;">Are you sure?</h3>
                <p>Action cannot be reversed!</p>
                <hr style="border: 0; border-top: 1px solid #ffcccc; margin: 10px 0;">

                <p><strong>Name:</strong> <?= htmlspecialchars($type['name']) ?></p>
                <p><strong>Capacity:</strong> <?= htmlspecialchars($type['capacity']) ?></p>
            </div>

            <input type="hidden" name="id" value="<?= $type['id_type'] ?? $type['id'] ?>">

            <button type="submit" class="btn btn-danger">Confirm Delete</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'RoomTypeView.php'">Cancel</button>

        </form>

    </body>
</html>