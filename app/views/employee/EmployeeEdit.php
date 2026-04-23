<?php
require_once '../../../config/config.php';
require_once '../../controllers/EmployeeController.php';

$controller = new EmployeeController($conn);

$id = $_GET['id'] ?? null;
if (!$id)
    die("Invalid ID");

$error_message = $controller->edit($id);

$emp = $controller->getOne($id);
if (!$emp)
    die("Employee not found");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Employee</title>
        <link rel="stylesheet" href="../../../public/css/edit_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Edit Employee</h2>
            
            <?= Csrf::field() ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-box">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>
            
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($emp['first_name']) ?>" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($emp['last_name']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($emp['email']) ?>" required>

            <label>New Password (leave blank to keep current):</label>
            <input type="password" name="password" placeholder="********">

            <label>Role:</label>
            <select name="role">
                <option value="reception" <?= $emp['role'] == 'reception' ? 'selected' : '' ?>>Reception</option>
                <option value="manager" <?= $emp['role'] == 'manager' ? 'selected' : '' ?>>Manager</option>
                <option value="admin" <?= $emp['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <button type="submit" class="btn btn-update">Update Employee</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'EmployeeView.php'">Cancel</button>

        </form>
    </body>
</html>