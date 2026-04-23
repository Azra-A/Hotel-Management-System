<?php
require_once '../../../config/config.php';
require_once '../../controllers/PaymentController.php';
require_once '../../models/InvoiceModel.php';

$controller = new PaymentController($conn);
$controller->add();

$invModel = new InvoiceModel($conn);
$invoices = $invModel->getAll();


$preselected_invoice_id = "";
$suggested_amount = "";
$invoice_details = null;

if (isset($_GET['invoice_id'])) {
    $preselected_invoice_id = $_GET['invoice_id'];

    $invoice_details = $invModel->getWithBalance($preselected_invoice_id);

    if ($invoice_details) {
        $suggested_amount = $invoice_details['balance'];
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Payment</title>
        <link rel="stylesheet" href="../../../public/css/add_style.css">
    </head>
    <body>
        <form method="POST">

            <h2>Record New Payment</h2>

            <?= Csrf::field() ?>

            <?php if ($invoice_details): ?>
                <div class="info-box">
                    <strong>Paying for Invoice #<?= $invoice_details['id_invoice'] ?></strong><br>
                    Client: <?= $invoice_details['first_name'] ?> <?= $invoice_details['last_name'] ?><br>
                    Total: <?= $invoice_details['total'] ?> | 
                    Paid: <?= $invoice_details['paid_so_far'] ?> | 
                    <strong>Remaining: <?= $invoice_details['balance'] ?></strong>
                </div>
            <?php endif; ?>

            <label>Select Invoice:</label>
            <select name="id_invoice" required>
                <option value="">-- Choose Invoice --</option>
                <?php foreach ($invoices as $inv): ?>
                    <option value="<?= $inv['id_invoice'] ?>">
                        Invoice #<?= $inv['id_invoice'] ?> (Total: <?= $inv['total'] ?> BGN)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Payment Date:</label>
            <input type="date" name="paid_on" required>

            <label>Amount Paid (BGN):</label>
            <input type="number" step="1" name="amount" required>

            <br><br>
            <button type="submit" class="btn btn-save">Save Payment</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'PaymentView.php'">Cancel</button>
        </form>
    </body>
</html>