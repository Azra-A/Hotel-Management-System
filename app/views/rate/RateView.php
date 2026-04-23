<?php
require_once '../../../config/config.php';
require_once '../../controllers/RateController.php';

$controller = new RateController($conn);

$data = $controller->view();
$rates = $data['rates'];
$total_pages = $data['total_pages'];
$current_page = $data['current_page'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Rates</title>
        <link rel="stylesheet" href="../../../public/css/view_style.css">
    </head>
    <body>
        <h2>Rates (Prices)</h2>
        <a href="../../../public/index.php?page=home" class="btn btn-back">Back</a>
        <a href="RateAdd.php" class="btn btn-add">Add New Rate</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Price (BGN)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rates as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_rate']) ?></td>
                        <td><?= htmlspecialchars($r['type_name']) ?></td>
                        <td><?= htmlspecialchars($r['start_date']) ?></td>
                        <td><?= htmlspecialchars($r['end_date']) ?></td>
                        <td><strong><?= htmlspecialchars($r['price_per_night']) ?></strong></td>
                        <td>
                            <a href="RateEdit.php?id=<?= $r['id_rate'] ?>" class="btn btn-edit">Edit</a>
                            <a href="RateDelete.php?id=<?= $r['id_rate'] ?>" class="btn btn-del">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php require_once '../pagination/pagination.php'; ?>
        
    </body>
</html>