<?php
require_once '../../../config/config.php';
require_once '../../controllers/EmployeeController.php';
require_once '../../models/AuditLogModel.php';

$controller = new EmployeeController($conn);

$id = $_GET['id'] ?? $_POST['id'] ??  null;

if (!$id) {
    die("Invalid ID");
}

$controller->delete($id);

$employee = $controller->getOne($id);

if (!$employee) {
    die("Employee not found!");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Delete Employee</title>
        <link rel="stylesheet" href="../../../public/css/delete_style.css">
    </head>
    <body>
        <form method="POST">
            
            <?= Csrf::field() ?>
            
            <h2>Delete Employee</h2>

            <div class="warning-box">
                <h3>Are you sure?</h3>
                <p>Action cannot be reversed!</p>

                <hr style="border: 0; border-top: 1px solid #ffcccc; margin: 10px 0;">

                <p><strong>Name:</strong> <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($employee['email']) ?></p>
                <p><strong>Role:</strong> <?= htmlspecialchars(ucfirst($employee['role'] ?? '')) ?></p>
            </div>

            <input type="hidden" name="id" value="<?= $employee['id_employee'] ?>">

            <button type="submit" class="btn btn-danger">Confirm Delete</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'EmployeeView.php'">Cancel</button>

        </form>

    </body>
</html>