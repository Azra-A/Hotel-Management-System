<?php
require_once '../../../config/config.php';
require_once '../../controllers/RoomController.php';
require_once '../../models/RoomTypeModel.php';

$controller = new RoomController($conn);

$id = $_GET['id'] ?? null;
if (!$id)
    die("Invalid ID");

$controller->edit($id);

$room = $controller->getOne($id);
if (!$room)
    die("Room not found");

$typeModel = new RoomTypeModel($conn);
$allTypes = $typeModel->getAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Room</title>
        <link rel="stylesheet" href="../../../public/css/edit_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Edit Room</h2>

            <?= Csrf::field() ?>

            <label>Room Number:</label>
            <input type="text" name="number" value="<?= htmlspecialchars($room['number']) ?>" required>

            <label>Floor:</label>
            <input type="number" name="floor" value="<?= htmlspecialchars($room['floor']) ?>" required>

            <label>Room Type:</label>
            <select name="id_type" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($allTypes as $type): ?>
                    <option value="<?= $type['id_type'] ?>" 
                            <?= ($type['id_type'] == $room['id_type']) ? 'selected' : '' ?>>
                        <?= $type['name'] ?> (Cap: <?= $type['capacity'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Status:</label>
            <select name="status">
                <option value="available" <?= $room['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                <option value="occupied" <?= $room['status'] == 'occupied' ? 'selected' : '' ?>>Occupied</option>
                <option value="dirty" <?= $room['status'] == 'dirty' ? 'selected' : '' ?>>Dirty</option>
                <option value="maintenance" <?= $room['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
            </select>

            <br><br>
            <button type="submit" class="btn btn-update">Update Room</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'RoomView.php'">Cancel</button>
        </form>
    </body>
</html>