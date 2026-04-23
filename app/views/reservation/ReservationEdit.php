<?php
require_once '../../../config/config.php';
require_once '../../controllers/ReservationController.php';
require_once '../../models/RoomModel.php';
require_once '../../models/ClientModel.php';
require_once '../../models/EmployeeModel.php';

$controller = new ReservationController($conn);

$id = $_GET['id'] ?? null;
if (!$id)
    die("Invalid ID");

$error_message = $controller->edit($id);

$res = $controller->getOne($id);
if (!$res)
    die("Reservation not found");

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
        <title>Edit Reservation</title>
        <link rel="stylesheet" href="../../../public/css/edit_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Edit Reservation #<?= $res['id_reservation'] ?></h2>

            <?= Csrf::field() ?>

            <?php if (!empty($error_message)): ?>
                <div class="error-box">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>

            <label>Room:</label>
            <select name="id_room" required>
                <?php foreach ($rooms as $rm): ?>
                    <option value="<?= $rm['id_room'] ?>" <?= ($rm['id_room'] == $res['id_room']) ? 'selected' : '' ?>>
                        Room <?= $rm['number'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Client:</label>
            <select name="id_client" required>
                <?php foreach ($clients as $cl): ?>
                    <option value="<?= $cl['id_client'] ?>" <?= ($cl['id_client'] == $res['id_client']) ? 'selected' : '' ?>>
                        <?= $cl['first_name'] ?> <?= $cl['last_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Employee:</label>
            <select name="id_employee" required>
                <?php foreach ($employees as $em): ?>
                    <option value="<?= $em['id_employee'] ?>" <?= ($em['id_employee'] == $res['id_employee']) ? 'selected' : '' ?>>
                        <?= $em['first_name'] ?> <?= $em['last_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Check In:</label>
            <input type="date" name="check_in" value="<?= $res['check_in'] ?>" required>

            <label>Check Out:</label>
            <input type="date" name="check_out" value="<?= $res['check_out'] ?>" required>

            <label>Status:</label>
            <select name="status">
                <option value="confirmed" <?= $res['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="pending" <?= $res['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="cancelled" <?= $res['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>

            <br><br>
            <button type="submit" class="btn btn-update">Update Reservation</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'ReservationView.php'">Cancel</button>
        </form>
    </body>
</html>