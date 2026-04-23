<?php
require_once '../../../config/config.php';
require_once '../../controllers/PaymentController.php';
require_once '../../models/InvoiceModel.php';

$controller = new PaymentController($conn);

$id = $_GET['id'] ?? null;
if (!$id)
    die("Invalid ID");

$controller->edit($id);

$pay = $controller->getOne($id);
if (!$pay)
    die("Payment not found");

$invModel = new InvoiceModel($conn);
$invoices = $invModel->getAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Payment</title>
        <link rel="stylesheet" href="../../../public/css/edit_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Edit Payment #<?= $pay['id_payment'] ?></h2>
            
            <?= Csrf::field() ?>

            <label>Invoice:</label>
            <select name="id_invoice" required>
                <?php foreach ($invoices as $inv): ?>
                    <option value="<?= $inv['id_invoice'] ?>" <?= ($inv['id_invoice'] == $pay['id_invoice']) ? 'selected' : '' ?>>
                        Invoice #<?= $inv['id_invoice'] ?> (Total: <?= $inv['total'] ?> BGN)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Payment Date:</label>
            <input type="date" name="paid_on" value="<?= $pay['paid_on'] ?>" required>

            <label>Amount Paid (BGN):</label>
            <input type="number" step="1" name="amount" value="<?= $pay['amount'] ?>" required>

            <br><br>
            <button type="submit" class="btn btn-update">Update Payment</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'PaymentView.php'">Cancel</button>
        </form>
    </body>
</html>