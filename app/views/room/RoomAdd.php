<?php
require_once '../../../config/config.php';
require_once '../../controllers/RoomController.php';
require_once '../../models/RoomTypeModel.php';

$controller = new RoomController($conn);
$controller->add();

$typeModel = new RoomTypeModel($conn);
$allTypes = $typeModel->getAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Room</title>
        <link rel="stylesheet" href="../../../public/css/add_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Add New Room</h2>

            <?= Csrf::field() ?>

            <label>Room Number:</label>
            <input type="text" name="number" required>

            <label>Floor:</label>
            <input type="number" name="floor" required>

            <label>Room Type:</label>
            <select name="id_type" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($allTypes as $type): ?>
                    <option value="<?= $type['id_type'] ?>">
                        <?= $type['name'] ?> (Cap: <?= $type['capacity'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Status:</label>
            <select name="status">
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="dirty">Dirty</option>
                <option value="maintenance">Maintenance</option>
            </select>

            <br><br>
            <button type="submit" class="btn btn-save">Save Room</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'RoomView.php'">Cancel</button>
        </form>
    </body>
</html>