<?php
require_once '../../../config/config.php';
require_once '../../controllers/ClientController.php';

$controller = new ClientController($conn);

$data = $controller->view();
$clients = $data['clients'];
$total_pages = $data['total_pages'];
$current_page = $data['current_page'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Clients List</title>
        <link rel="stylesheet" href="../../../public/css/view_style.css">
    </head>
    <body>

        <h2>Clients List</h2>
        <a href="../../../public/index.php?page=home" class="btn btn-back">Back</a>
        <a href="ClientAdd.php" class="btn btn-add">Add New Client</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['id_client']) ?></td>
                        <td><?= htmlspecialchars($client['first_name']) ?></td>
                        <td><?= htmlspecialchars($client['last_name']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['phone']) ?></td>
                        <td>
                            <a href="ClientEdit.php?id=<?= $client['id_client'] ?>" class="btn btn-edit">Edit</a>
                            <a href="ClientDelete.php?id=<?= $client['id_client'] ?>" class="btn btn-del">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php require_once '../pagination/pagination.php'; ?>
        
    </body>
</html>