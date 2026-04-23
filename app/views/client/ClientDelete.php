<?php
require_once '../../../config/config.php';
require_once '../../controllers/ClientController.php';
require_once '../../models/AuditLogModel.php';

$controller = new ClientController($conn);

$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id) {
    die("Invalid ID");
}

$error_message = $controller->delete($id);

$client = $controller->getOne($id);

if (!$client) {
    die("Client not found");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Delete Client</title>
        <link rel="stylesheet" href="../../../public/css/delete_style.css">
    </head>
    <body>

        <form method="POST">
            
            <?= Csrf::field() ?>

            <h2>Delete Client</h2>

            <div class="warning-box">
                <h3>Are you sure?</h3>
                <p>Action cannot be reversed!</p>
                
                <hr style="border: 0; border-top: 1px solid #ffcccc; margin: 10px 0;">

                <p><strong>Name:</strong> <?= htmlspecialchars($client['first_name'] . ' ' . $client['last_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
            </div>

            <input type="hidden" name="id" value="<?= $client['id_client'] ?>">
            
            <button type="submit" class="btn btn-danger">Confirm Delete</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'ClientView.php'">Cancel</button>

        </form>

    </body>
</html>