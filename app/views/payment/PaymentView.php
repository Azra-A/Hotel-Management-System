<?php
require_once '../../../config/config.php';
require_once '../../controllers/PaymentController.php';

$controller = new PaymentController($conn);

$data = $controller->view();
$payments = $data['payments'];
$total_pages = $data['total_pages'];
$current_page = $data['current_page'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Payments</title>
        <link rel="stylesheet" href="../../../public/css/view_style.css">
    </head>
    <body>
        <h2>Payments List</h2>
        <a href="../../../public/index.php?page=home" class="btn btn-back">Back</a>
        <a href="PaymentAdd.php" class="btn btn-add">Add New Payment</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>For Invoice</th>
                    <th>Paid On</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id_payment']) ?></td>
                        <td>Invoice #<?= htmlspecialchars($p['id_invoice']) ?></td>
                        <td><?= htmlspecialchars($p['paid_on']) ?></td>
                        <td style="color:green;"><strong><?= htmlspecialchars($p['amount']) ?> BGN</td>
                        <td>
                            <a href="PaymentEdit.php?id=<?= $p['id_payment'] ?>" class="btn btn-edit">Edit</a>
                            <a href="PaymentDelete.php?id=<?= $p['id_payment'] ?>" class="btn btn-del">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php require_once '../pagination/pagination.php'; ?>
        
    </body>
</html>