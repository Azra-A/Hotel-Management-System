<?php
require_once '../../../config/config.php';
require_once '../../controllers/EmployeeController.php';

$controller = new EmployeeController($conn);

$data = $controller->view();
$employees = $data['employees'];
$total_pages = $data['total_pages'];
$current_page = $data['current_page'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employees List</title>
        <link rel="stylesheet" href="../../../public/css/view_style.css">
    </head>
    <body>

        <h2>Employees List</h2>
        <a href="../../../public/index.php?page=home" class="btn btn-back">Back</a>
        <a href="EmployeeAdd.php" class="btn btn-add">Add New Employee</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><?= htmlspecialchars($emp['id_employee']) ?></td>
                        <td><?= htmlspecialchars($emp['first_name']) ?></td>
                        <td><?= htmlspecialchars($emp['last_name']) ?></td>
                        <td><?= htmlspecialchars($emp['email']) ?></td>
                        <td><span style="font-weight:bold;"><?= htmlspecialchars(ucfirst($emp['role'])) ?></span></td>
                        <td>
                            <a href="EmployeeEdit.php?id=<?= $emp['id_employee'] ?>" class="btn btn-edit">Edit</a>
                            <a href="EmployeeDelete.php?id=<?= $emp['id_employee'] ?>" class="btn btn-del">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php require_once '../pagination/pagination.php'; ?>

    </body>
</html>