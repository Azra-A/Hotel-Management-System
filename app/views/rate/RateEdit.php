<?php
require_once '../../../config/config.php';
require_once '../../controllers/RateController.php';
require_once '../../models/RoomTypeModel.php';

$controller = new RateController($conn);

$id = $_GET['id'] ?? null;
if (!$id)
    die("Invalid ID");

$controller->edit($id);
$rate = $controller->getOne($id);
if (!$rate)
    die("Rate not found");

$typeModel = new RoomTypeModel($conn);
$types = $typeModel->getAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Rate</title>
        <link rel="stylesheet" href="../../../public/css/edit_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Edit Rate</h2>

            <?= Csrf::field() ?>

            <label>Room Type:</label>
            <select name="id_type" required>
                <?php foreach ($types as $t): ?>
                    <option value="<?= $t['id_type'] ?>" <?= ($t['id_type'] == $rate['id_type']) ? 'selected' : '' ?>>
                        <?= $t['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Start Date:</label>
            <input type="date" name="start_date" value="<?= $rate['start_date'] ?>" required>

            <label>End Date:</label>
            <input type="date" name="end_date" value="<?= $rate['end_date'] ?>" required>

            <label>Price per Night (BGN):</label>
            <input type="number" step="1" name="price" value="<?= $rate['price_per_night'] ?>" required>

            <br><br>
            <button type="submit" class="btn btn-update">Update Rate</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'RateView.php'">Cancel</button>
        </form>
    </body>
</html>