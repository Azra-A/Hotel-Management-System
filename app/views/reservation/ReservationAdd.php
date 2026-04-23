<?php
require_once '../../../config/config.php';
require_once '../../controllers/ReservationController.php';
require_once '../../models/RoomModel.php';
require_once '../../models/ClientModel.php';
require_once '../../models/EmployeeModel.php';

$controller = new ReservationController($conn);

$error_message = $controller->add();

$roomModel = new RoomModel($conn);
$rooms = $roomModel->getAll();

$clientModel = new ClientModel($conn);
$clients = $clientModel->getAll();

$empModel = new EmployeeModel($conn);
$employees = $empModel->getAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>New Reservation</title>
        <link rel="stylesheet" href="../../../public/css/add_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>New Reservation</h2>


            <?= Csrf::field() ?>

            <?php if (!empty($error_message)): ?>
                <div class="error-box">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>

            <label>Select Room:</label>
            <select name="id_room" required>
                <option value="">-- Choose Room --</option>
                <?php foreach ($rooms as $rm): ?>
                    <option value="<?= $rm['id_room'] ?>">
                        Room <?= $rm['number'] ?> (Type: <?= $rm['type_name'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Select Client:</label>
            <select name="id_client" required>
                <option value="">-- Choose Client --</option>
                <?php foreach ($clients as $cl): ?>
                    <option value="<?= $cl['id_client'] ?>">
                        <?= $cl['first_name'] ?> <?= $cl['last_name'] ?> (<?= $cl['email'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Select Employee (You):</label>
            <select name="id_employee" required>
                <?php foreach ($employees as $em): ?>
                    <option value="<?= $em['id_employee'] ?>">
                        <?= $em['first_name'] ?> <?= $em['last_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Check In:</label>
            <input type="date" name="check_in" required>

            <label>Check Out:</label>
            <input type="date" name="check_out" required>

            <label>Status:</label>
            <select name="status">
                <option value="confirmed">Confirmed</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <br><br>
            <button type="submit" class="btn btn-save">Create Reservation</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'ReservationView.php'">Cancel</button>
        </form>
    </body>
</html>