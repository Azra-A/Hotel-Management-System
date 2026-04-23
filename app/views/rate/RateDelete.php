<?php
require_once '../../../config/config.php';
require_once '../../controllers/RateController.php';
require_once '../../models/AuditLogModel.php';

$controller = new RateController($conn);

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

$controller->delete($id);

$rate = $controller->getOne($id);

if (!$rate) {
    die("Rate not found");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Delete Rate</title>
        <link rel="stylesheet" href="../../../public/css/delete_style.css">
    </head>
    <body>

        <form method="POST">

            <?= Csrf::field() ?>
            
            <h2>Delete Rate</h2>

            <div class="warning-box">
                <h3 style="margin-top:0; color:#a00;">Are you sure?</h3>
                <p>Action cannot be reversed!</p>
                <hr style="border: 0; border-top: 1px solid #ffcccc; margin: 10px 0;">

                <p><strong>Price:</strong> <?= htmlspecialchars($rate['price_per_night']) ?> BGN</p>
                <p><strong>Start Date:</strong> <?= htmlspecialchars($rate['start_date']) ?></p>
                <p><strong>End Date:</strong> <?= htmlspecialchars($rate['end_date']) ?></p>
            </div>

            <input type="hidden" name="id" value="<?= $rate['id_rate'] ?? $rate['id'] ?>">

            <button type="submit" class="btn btn-danger">Confirm Delete</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href = 'RateView.php'">Cancel</button>

        </form>

    </body>
</html>