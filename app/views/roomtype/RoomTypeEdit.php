<?php
require_once '../../../config/config.php';
require_once '../../controllers/RoomTypeController.php';

$controller = new RoomTypeController($conn);

$id = $_GET['id'] ?? null;
if (!$id)
    die("Invalid ID");

$error_message = $controller->edit($id);

$roomtype = $controller->getOne($id);
if (!$roomtype)
    die("Room type not found");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Room Type</title>
        <link rel="stylesheet" href="../../../public/css/edit_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Edit Room Type</h2>

            <?= Csrf::field() ?>

            <?php if (!empty($error_message)): ?>
                <div class="error-box">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>


            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($roomtype['name']) ?>" required>

            <label>Capacity:</label>
            <input type="text" name="capacity" value="<?= htmlspecialchars($roomtype['capacity']) ?>" required>

            <br><br>
            <button type="submit" class="btn btn-update">Update Room Type</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'RoomTypeView.php'">Cancel</button>
        </form>
    </body>
</html>