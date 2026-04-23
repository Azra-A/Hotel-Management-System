<?php
require_once '../../../config/config.php';
require_once '../../controllers/InvoiceController.php';
require_once '../../models/ReservationModel.php';

$controller = new InvoiceController($conn);
$controller->add();

$resModel = new ReservationModel($conn);
$reservations = $resModel->getAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Invoice</title>
        <link rel="stylesheet" href="../../../public/css/add_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Generate Invoice</h2>
            
            <?= Csrf::field() ?>
                        
            <label>Select Reservation:</label>
            <select name="id_reservation" required>
                <option value="">-- Choose Reservation --</option>
                <?php foreach ($reservations as $r): ?>
                    <option value="<?= $r['id_reservation'] ?>">
                        Res #<?= $r['id_reservation'] ?> - <?= $r['c_fname'] ?> <?= $r['c_lname'] ?> (Room <?= $r['room_number'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Issued On:</label>
            <input type="date" name="issued_on" required>

            <label>Total Amount (BGN):</label>
            <input type="number" step="1" name="total" required>

            <br><br>
            <button type="submit" class="btn btn-save">Save Invoice</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'InvoiceView.php'">Cancel</button>
        </form>
    </body>
</html>