<?php
require_once '../../../config/config.php';
require_once '../../controllers/ClientController.php';

$controller = new ClientController($conn);

$id = $_GET['id'] ?? null;
if (!$id)
    die("Invalid ID");

$error_message = $controller->edit($id);


$client = $controller->getOne($id);
if (!$client)
    die("Client not found");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Client</title>
        <link rel="stylesheet" href="../../../public/css/edit_style.css">
    </head>
    <body>
        <form method="POST">
            <h2>Edit Client</h2>
            
            <?= Csrf::field() ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-box">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>
            
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($client['first_name']) ?>" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($client['last_name']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($client['phone']) ?>" required>

            <br><br>
            <button type="submit" class="btn btn-update">Update Client</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'ClientView.php'">Cancel</button>
        </form>
    </body>
</html>