<?php
require_once '../../../config/config.php';
require_once '../../controllers/RoomController.php';

$controller = new RoomController($conn);

$data = $controller->view();
$rooms = $data['rooms'];
$total_pages = $data['total_pages'];
$current_page = $data['current_page'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Rooms List</title>
        <link rel="stylesheet" href="../../../public/css/view_style.css">
    </head>
    <body>
        <h2>Rooms List</h2>
        <a href="../../../public/index.php?page=home" class="btn btn-back">Back</a>
        <a href="RoomAdd.php" class="btn btn-add">Add New Room</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Number</th>
                    <th>Type</th>
                    <th>Floor</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?= htmlspecialchars($room['id_room']) ?></td>
                        <td><strong><?= htmlspecialchars($room['number']) ?></strong></td>
                        <td><?= htmlspecialchars($room['type_name']) ?></td> 
                        <td><?= htmlspecialchars($room['floor']) ?></td>
                        <td>
                            <?php
                            $color = 'green';
                            $status = htmlspecialchars($room['status']);
                            if ($room['status'] == 'occupied')
                                $color = 'red';
                            if ($room['status'] == 'dirty')
                                $color = 'orange';
                            if ($room['status'] == 'maintenance')
                                $color = 'blue';
                            ?>
                            <span style="color:<?= $color ?>"><?= ucfirst($status) ?></span>
                        </td>
                        <td>
                            <a href="RoomEdit.php?id=<?= $room['id_room'] ?>" class="btn btn-edit">Edit</a>
                            <a href="RoomDelete.php?id=<?= $room['id_room'] ?>" class="btn btn-del">Delete</a>                        
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php require_once '../pagination/pagination.php'; ?>

    </body>
</html>