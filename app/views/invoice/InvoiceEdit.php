<?php
require_once '../../../config/config.php';
require_once '../../controllers/InvoiceController.php';
require_once '../../models/ReservationModel.php';

$controller = new InvoiceController($conn);

$id = $_GET['id'] ?? null;
if (!$id)
    die("Invalid ID");

$controller->edit($id);

$inv = $controller->getOne($id);
if (!$inv)
    die("Invoice not found");

$resModel = new ReservationModel($conn);
$reservations = $resModel->getAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Invoice</title>
        <link rel="stylesheet" href="../../../public/css/edit_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Edit Invoice #<?= $inv['id_invoice'] ?></h2>
            
            <?= Csrf::field() ?>

            <label>Reservation:</label>
            <select name="id_reservation" required>
                <?php foreach ($reservations as $r): ?>
                    <option value="<?= $r['id_reservation'] ?>" <?= ($r['id_reservation'] == $inv['id_reservation']) ? 'selected' : '' ?>>
                        Res #<?= $r['id_reservation'] ?> - <?= $r['c_fname'] ?> <?= $r['c_lname'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Issued On:</label>
            <input type="date" name="issued_on" value="<?= $inv['issued_on'] ?>" required>

            <label>Total Amount (BGN):</label>
            <input type="number" step="1" name="total" value="<?= $inv['total'] ?>" required>

            <br><br>
            <button type="submit" class="btn btn-update">Update Invoice</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'InvoiceView.php'">Cancel</button>
        </form>
    </body>
</html>