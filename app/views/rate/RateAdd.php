<?php
require_once '../../../config/config.php';
require_once '../../controllers/RateController.php';
require_once '../../models/RoomTypeModel.php';

$controller = new RateController($conn);
$controller->add();

$typeModel = new RoomTypeModel($conn);
$types = $typeModel->getAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Rate</title>
        <link rel="stylesheet" href="../../../public/css/add_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Add New Rate (Price)</h2>
            
            <?= Csrf::field() ?>

            <label>Room Type:</label>
            <select name="id_type" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($types as $t): ?>
                    <option value="<?= $t['id_type'] ?>"><?= $t['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label>Start Date:</label>
            <input type="date" name="start_date" required>

            <label>End Date:</label>
            <input type="date" name="end_date" required>

            <label>Price per Night (BGN):</label>
            <input type="number" step="1" name="price" required>

            <br><br>
            <button type="submit" class="btn btn-save">Save Rate</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'RateView.php'">Cancel</button>
        </form>
    </body>
</html>