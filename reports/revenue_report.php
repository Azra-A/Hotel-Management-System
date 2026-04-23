<?php
require_once '../config/config.php';

$payments = [];
$total = 0;

if (isset($_GET['from'])) {
    $sql = "SELECT Payment.*, Client.first_name, Client.last_name
            FROM Payment
            JOIN Invoice ON Payment.id_invoice = Invoice.id_invoice
            JOIN Reservation ON Invoice.id_reservation = Reservation.id_reservation
            JOIN Client ON Reservation.id_client = Client.id_client
            WHERE paid_on BETWEEN :from AND :until";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':from' => $_GET['from'], ':until' => $_GET['until']]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sqlSum = "SELECT SUM(amount) as total FROM Payment WHERE paid_on BETWEEN ? AND ?";
    $stmtSum = $conn->prepare($sqlSum);
    $stmtSum->execute([$_GET['from'], $_GET['until']]);
    $total = $stmtSum->fetch()['total'] ?? 0;
}

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="revenue_report_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Payment Date', 'Client Name', 'Amount']);

    foreach ($payments as $row) {
        fputcsv($output, [
            $row['paid_on'],
            $row['first_name'] . ' ' . $row['last_name'],
            $row['amount']
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Revenue Report</title>
        <link rel="stylesheet" href="../public/css/queries_style.css">
    </head>
    <body>
        <div class="container">
            <div class="header-actions">
                <a href="../public/index.php?page=queries" class="btn-back">Back</a>
                <?php if (isset($_GET['from']) && isset($_GET['until'])): ?>
                    <a href="?export=csv&from=<?= $_GET['from'] ?>&until=<?= $_GET['until'] ?>" class="btn-export">Export</a>
                <?php endif; ?>
            </div>
            <h2>Revenue Report</h2>
            <form>
                From: <input type="date" name="from" required>
                To: <input type="date" name="until" required>
                <button type="submit">Generate</button>
            </form>

            <?php if ($payments): ?>
                <table>
                    <tr><th>Date</th><th>Client</th><th>Amount</th></tr>
                    <?php foreach ($payments as $p): ?>
                        <tr>
                            <td><?= $p['paid_on'] ?></td>
                            <td><?= $p['first_name'] ?> <?= $p['last_name'] ?></td>
                            <td><?= $p['amount'] ?> BGN</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="total">Total Revenue: <?= number_format($total, 2) ?> BGN</div>
            <?php endif; ?>
        </div>
    </body>
</html>