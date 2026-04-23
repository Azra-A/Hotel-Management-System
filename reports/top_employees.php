<?php
require_once '../config/config.php';

$sql = "SELECT Employee.first_name, Employee.last_name, Employee.role, 
               COUNT(Reservation.id_reservation) as total_res
        FROM Employee
        LEFT JOIN Reservation ON Employee.id_employee = Reservation.id_employee
        GROUP BY Employee.id_employee
        ORDER BY total_res DESC
        LIMIT 5";
$stmt = $conn->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="top_employees_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Rank', 'Employee Name', 'Role', 'Reservations Count']);

    $rank = 1;

    foreach ($data as $row) {
        fputcsv($output, [
            $rank++,
            $row['first_name'] . ' ' . $row['last_name'],
            ucfirst($row['role']),
            $row['total_res']
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Top Employees</title>
        <link rel="stylesheet" href="../public/css/queries_style.css">
    </head>
    <body>
        <div class="container">
            <div class="header-actions">
                <a href="../public/index.php?page=queries" class="btn-back">Back</a>
                <a href="?export=csv" class="btn-export">Export</a>
            </div>
            <h2>Top 5 Employees</h2>
            <table>
                <tr><th>Rank</th><th>Employee</th><th>Role</th><th>Reservations Made</th></tr>
                <?php
                $i = 1;
                foreach ($data as $row):
                    ?>
                    <tr>
                        <td>№<?= $i++ ?></td>
                        <td><?= $row['first_name'] ?> <?= $row['last_name'] ?></td>
                        <td><?= ucfirst($row['role']) ?></td>
                        <td><strong><?= $row['total_res'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </body>
</html>