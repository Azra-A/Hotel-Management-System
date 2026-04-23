<?php
require_once '../../../config/config.php';
require_once '../../controllers/PaymentController.php';
require_once '../../models/AuditLogModel.php';

$controller = new PaymentController($conn);

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

$controller->delete($id);

$payment = $controller->getOne($id);

if (!$payment) {
    die("Payment not found");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Delete Payment</title>
        <link rel="stylesheet" href="../../../public/css/delete_style.css">
    </head>
    <body>

        <form method="POST">

            <?= Csrf::field() ?>
            
            <h2>Delete Payment</h2>

            <div class="warning-box">
                <h3 style="margin-top:0; color:#a00;">Are you sure?</h3>
                <p>Action cannot be reversed!</p>
                <hr style="border: 0; border-top: 1px solid #ffcccc; margin: 10px 0;">

                <p><strong>For Invoice:</strong> #<?= htmlspecialchars($payment['id_invoice']) ?></p>
                <p><strong>Amount:</strong> <?= htmlspecialchars($payment['amount']) ?> BGN</p>
                <p><strong>Paid On:</strong> <?= htmlspecialchars($payment['paid_on']) ?></p>
            </div>

            <input type="hidden" name="id" value="<?= $payment['id_payment'] ?? $payment['id'] ?>">

            <button type="submit" class="btn btn-danger">Confirm Delete</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'PaymentView.php'">Cancel</button>

        </form>

    </body>
</html>