<?php
require_once '../../../config/config.php';
require_once '../../controllers/EmployeeController.php';

$controller = new EmployeeController($conn);
$error_message = $controller->add();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Employee</title>
        <link rel="stylesheet" href="../../../public/css/add_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Add New Employee</h2>
            
            <?= Csrf::field() ?>

            <?php if (!empty($error_message)): ?>
                <div class="error-box">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>

            <label>First Name:</label>
            <input type="text" name="first_name" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Role:</label>
            <select name="role">
                <option value="reception">Reception</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit" class="btn btn-save">Save Employee</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'EmployeeView.php'">Cancel</button>
        </form>
    </body>
</html>