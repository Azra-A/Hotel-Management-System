<?php
require_once '../../../config/config.php';
require_once '../../models/AuditLogModel.php';

$limit = 20;

$page = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
if ($page < 1)
    $page = 1;

$offset = ($page - 1) * $limit;

$model = new AuditLogModel($conn);

$total_rows = $model->getTotalCount();
$total_pages = ceil($total_rows / $limit);

$logs = $model->getPaginated($limit, $offset);

$current_page = $page;
?>

<!DOCTYPE html>
<html>
    <head>
        <title>System Audit Logs</title>
        <link rel="stylesheet" href="../../../public/css/audit_style.css">
    </head>
    <body>
        <div class="container">
            <a href="../../../public/index.php?page=home" class="btn-back">Back</a>
            <h2>System Audit Logs</h2>
            <p>Tracking all actions performed by employees.</p>

            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Employee</th>
                        <th>Action</th>
                        <th>Table</th>
                        <th>Record ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= $log['created_at'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($log['first_name'] . ' ' . $log['last_name']) ?></strong><br>                            
                            </td>

                            <td>
                                <?php
                                $text = 'text-update';

                                if ($log['action'] == 'CREATE') {
                                    $text = 'text-create';
                                } elseif ($log['action'] == 'DELETE') {
                                    $text = 'text-delete';
                                }
                                ?>
                                <span class="action-text <?= $text ?>">
                                    <?= $log['action'] ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($log['table_name']) ?></td>
                            <td>#<?= htmlspecialchars($log['record_id']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <br>
            
            <div class="pagination">
                <?php require_once '../pagination/pagination.php'; ?>
            </div>
        </div>
    </body>
</html>