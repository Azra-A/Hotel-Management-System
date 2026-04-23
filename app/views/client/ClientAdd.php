<?php
require_once '../../../config/config.php';
require_once '../../controllers/ClientController.php';

$controller = new ClientController($conn);
$error_message = $controller->add();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Client</title>
        <link rel="stylesheet" href="../../../public/css/add_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Add New Client</h2>
            
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

            <label>Phone:</label>
            <input type="text" name="phone" required>

            <br><br>
            <button type="submit" class="btn btn-save">Save Client</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'ClientView.php'">Cancel</button>
        </form>
    </body>
</html>