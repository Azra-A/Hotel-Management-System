<?php
require_once '../../../config/config.php';
require_once '../../controllers/ReservationController.php';

$controller = new ReservationController($conn);

$data = $controller->view();
$reservations = $data['reservations'];
$total_pages = $data['total_pages'];
$current_page = $data['current_page'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reservations</title>
        <link rel="stylesheet" href="../../../public/css/view_style.css">
    </head>
    <body>
        <h2>Reservations</h2>
        <a href="../../../public/index.php?page=home" class="btn btn-back">Back</a>
        <a href="ReservationAdd.php" class="btn btn-add">Add New Reservation</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room</th>
                    <th>Client</th>
                    <th>Dates</th>
                    <th>Status</th>
                    <th>By Employee</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_reservation']) ?></td>
                        <td style="color:darkblue"><strong>Room <?= htmlspecialchars($r['room_number']) ?></td>
                        <td><?= htmlspecialchars($r['c_fname'] . ' ' . $r['c_lname']) ?></td>
                        <td><?= htmlspecialchars($r['check_in'] . ' to ' . $r['check_out']) ?></td>
                        <td>
                            <?php
                            $color = 'black';
                            $status = htmlspecialchars($r['status']);
                            if ($r['status'] == 'confirmed')
                                $color = 'green';
                            elseif ($r['status'] == 'pending')
                                $color = 'orange';
                            elseif ($r['status'] == 'cancelled')
                                $color = 'red';
                            ?>
                            <span style="color: <?= $color ?>"><strong>
                                <?= ucfirst($status) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($r['e_fname']) ?></td>
                        <td>
                            <a href="ReservationEdit.php?id=<?= $r['id_reservation'] ?>" class="btn btn-edit">Edit</a>
                            <a href="ReservationDelete.php?id=<?= $r['id_reservation'] ?>" class="btn btn-del">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php require_once '../pagination/pagination.php'; ?>
        
    </body>
</html>