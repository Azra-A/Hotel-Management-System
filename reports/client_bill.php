<?php
require_once '../config/config.php';

$sql = "SELECT 
            Client.id_client,
            Client.first_name, 
            Client.last_name, 
            Client.email, 
            SUM(Invoice.total) as total_bill
        FROM Client
        LEFT JOIN Reservation ON Client.id_client = Reservation.id_client
        LEFT JOIN Invoice ON Reservation.id_reservation = Invoice.id_reservation
        GROUP BY Client.id_client
        ORDER BY total_bill DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="client_bill_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Rank', 'Client Name', 'Email', 'Total Bill']);

    $rank = 1;

    foreach ($results as $row) {
        fputcsv($output, [
            $rank++,
            $row['first_name'] . ' ' . $row['last_name'],
            $row['email'],
            isset($row['total_bill']) ? $row['total_bill'] : '0.00'
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Client Bill Report</title>
        <link rel="stylesheet" href="../public/css/queries_style.css">
    </head>
    <body>
        <div class="container">
            <div class="header-actions">
                <a href="../public/index.php?page=queries" class="btn-back">Back</a>
                <a href="?export=csv" class="btn-export">Export</a>
            </div>
            <h2>All Clients Total Bills</h2>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Client Name</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rank = 1;
                    foreach ($results as $r):
                        $total = $r['total_bill'] ?? 0;
                        ?>
                        <tr>
                            <td>№<?= $rank++ ?></td>
                            <td>
                                <?= htmlspecialchars($r['first_name']) ?> 
                                <?= htmlspecialchars($r['last_name']) ?>
                            </td>
                            <td>
                                <?php if ($total > 0): ?>
                                    <span class="money"><?= number_format($total, 2) ?> BGN</span>
                                <?php else: ?>
                                    <span class="zero">0.00 BGN</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>