<?php
require_once '../../../config/config.php';
require_once '../../controllers/InvoiceController.php';
require_once '../../models/AuditLogModel.php';

$controller = new InvoiceController($conn);

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

$controller->delete($id);

$invoice = $controller->getOne($id);

if (!$invoice) {
    die("Invoice not found");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Delete Invoice</title>
        <link rel="stylesheet" href="../../../public/css/delete_style.css">
    </head>
    <body>

        <form method="POST">

            <?= Csrf::field() ?>
            
            <h2>Delete Invoice</h2>

            <div class="warning-box">
                <h3 style="margin-top:0; color:#a00;">Are you sure?</h3>
                <p>Action cannot be reversed!</p>
                <hr style="border: 0; border-top: 1px solid #ffcccc; margin: 10px 0;">

                <p><strong>Invoice ID:</strong> #<?= htmlspecialchars($invoice['id_invoice'] ?? $invoice['id']) ?></p>
                <p><strong>Total Amount:</strong> <?= htmlspecialchars($invoice['total']) ?> BGN</p>
                <p><strong>Issued On:</strong> <?= htmlspecialchars($invoice['issued_on']) ?></p>
            </div>

            <input type="hidden" name="id" value="<?= $invoice['id_invoice'] ?? $invoice['id'] ?>">

            <button type="submit" class="btn btn-danger">Confirm Delete</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'InvoiceView.php'">Cancel</button>

        </form>

    </body>
</html>