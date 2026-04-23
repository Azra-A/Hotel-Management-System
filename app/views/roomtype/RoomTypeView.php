<?php
require_once '../../../config/config.php';
require_once '../../controllers/RoomTypeController.php';

$controller = new RoomTypeController($conn);

$data = $controller->view();
$types = $data['types'];
$total_pages = $data['total_pages'];
$current_page = $data['current_page'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Room Types List</title>
        <link rel="stylesheet" href="../../../public/css/view_style.css">
    </head>
    <body>

        <h2>Room Types List</h2>
        
        <a href="../../../public/index.php?page=home" class="btn btn-back">Back</a>
        <a href="RoomTypeAdd.php" class="btn btn-add">Add New Room Type</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type Name</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($types as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['id_type']) ?></td>
                        <td><?= htmlspecialchars($t['name']) ?></td>
                        <td><?= htmlspecialchars($t['capacity']) ?></td>
                        <td>
                            <a href="RoomTypeEdit.php?id=<?= $t['id_type'] ?>" class="btn btn-edit">Edit</a>
                            <a href="RoomTypeDelete.php?id=<?= $t['id_type'] ?>" class="btn btn-del">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php require_once '../pagination/pagination.php'; ?>
        
    </body>
</html>