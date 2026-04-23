<?php
require_once '../../../config/config.php';
require_once '../../controllers/RoomTypeController.php';

$controller = new RoomTypeController($conn);
$error_message = $controller->add();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Room Type</title>
        <link rel="stylesheet" href="../../../public/css/add_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Add New Room Type</h2>
            
            <?= Csrf::field() ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-box">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>
            
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Capacity:</label>
            <input type="text" name="capacity" required>

            <br><br>
            <button type="submit" class="btn btn-save">Save Room Type</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'RoomTypeView.php'">Cancel</button>
        </form>
    </body>
</html>