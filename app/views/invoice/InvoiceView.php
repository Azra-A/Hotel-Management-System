<?php
require_once '../../../config/config.php';
require_once '../../controllers/InvoiceController.php';

$controller = new InvoiceController($conn);

$data = $controller->view();
$invoices = $data['invoices'];
$total_pages = $data['total_pages'];
$current_page = $data['current_page'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Invoices</title>
        <link rel="stylesheet" href="../../../public/css/view_style.css">
    </head>
    <body>
        <h2>Invoices List</h2>
        <a href="../../../public/index.php?page=home" class="btn btn-back">Back</a>
        <a href="InvoiceAdd.php" class="btn btn-add">Add New Invoice</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reservation</th>
                    <th>Client Name</th>
                    <th>Issued On</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><?= htmlspecialchars($inv['id_invoice']) ?></td>
                        <td>Res #<?= htmlspecialchars($inv['id_reservation']) ?></td>
                        <td><?= htmlspecialchars($inv['first_name'] . ' ' . $inv['last_name']) ?></td>
                        <td><?= htmlspecialchars($inv['issued_on']) ?></td>
                        <td style="color:green;"><strong><?= htmlspecialchars($inv['total']) ?> BGN</td>
                        <td>
                            <a href="../payment/PaymentAdd.php?invoice_id=<?= $inv['id_invoice'] ?>" class="btn btn-pay">Pay</a>
                            
                            <a href="InvoiceEdit.php?id=<?= $inv['id_invoice'] ?>" class="btn btn-edit">Edit</a>
                            <a href="InvoiceDelete.php?id=<?= $inv['id_invoice'] ?>" class="btn btn-del">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php require_once '../pagination/pagination.php'; ?>

    </body>
</html>