<?php
require_once '../../../config/config.php';
require_once '../../controllers/ReservationController.php';
require_once '../../models/AuditLogModel.php';

$controller = new ReservationController($conn);

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

$controller->delete($id);

$reservation = $controller->getOne($id);

if (!$reservation) {
    die("Reservation not found");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Delete Reservation</title>
        <link rel="stylesheet" href="../../../public/css/delete_style.css">
    </head>
    <body>

        <form method="POST">

            <?= Csrf::field() ?>
            
            <h2>Delete Reservation</h2>

            <div class="warning-box">
                <h3 style="margin-top:0; color:#a00;">Are you sure?</h3>
                <p>Action cannot be reversed!</p>
                <hr style="border: 0; border-top: 1px solid #ffcccc; margin: 10px 0;">

                <p><strong>Room:</strong> <?= htmlspecialchars($reservation['room_number'] ?? 'N/A') ?></p>
                <p><strong>Client:</strong> <?= htmlspecialchars(($reservation['c_fname'] ?? '') . ' ' . ($reservation['c_lname'] ?? '')) ?></p>
                <p><strong>Period:</strong> <?= htmlspecialchars($reservation['check_in'] . ' to ' . $reservation['check_out']) ?></p>
            </div>

            <input type="hidden" name="id" value="<?= $reservation['id_reservation'] ?? $reservation['id'] ?>">

            <button type="submit" class="btn btn-danger">Confirm Delete</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'ReservationView.php'">Cancel</button>

        </form>

    </body>
</html>