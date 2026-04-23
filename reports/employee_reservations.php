<?php
require_once '../config/config.php';

$results = [];
$selected_employee_name = "";

$employees = $conn->query("SELECT * FROM Employee")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['emp_id']) && !empty($_GET['emp_id'])) {
    $emp_id = $_GET['emp_id'];

    foreach ($employees as $e) {
        if ($e['id_employee'] == $emp_id) {
            $selected_employee_name = $e['first_name'] . ' ' . $e['last_name'];
            break;
        }
    }

    $sql = "SELECT Reservation.*, Room.number, Client.first_name, Client.last_name
            FROM Reservation
            JOIN Room ON Reservation.id_room = Room.id_room
            JOIN Client ON Reservation.id_client = Client.id_client
            WHERE Reservation.id_employee = :id
            ORDER BY check_in DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $emp_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="employee_reservations_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Res ID', 'Room Number', 'Client Name', 'Check In']);

    foreach ($results as $row) {
        fputcsv($output, [
            $row['id_reservation'],
            $row['number'],
            $row['first_name'] . ' ' . $row['last_name'],
            $row['check_in']
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employee Reservations</title>
        <link rel="stylesheet" href="../public/css/queries_style.css">
    </head>
    <body>
        <div class="container">
            <div class="header-actions">
                <a href="../public/index.php?page=queries" class="btn-back">Back</a>
                <?php if (isset($_GET['emp_id']) && $_GET['emp_id']): ?>
                    <a href="?export=csv&emp_id=<?= htmlspecialchars($_GET['emp_id']) ?>" class="btn-export">Export</a>
                <?php endif; ?>
            </div>
            <h2>Reservations by Employee</h2>
            <form>
                Select Employee:
                <select name="emp_id">
                    <option value="">-- Select Employee --</option>

                    <?php foreach ($employees as $e): ?>
                        <option value="<?= $e['id_employee'] ?>" 
                                <?= (isset($_GET['emp_id']) && $_GET['emp_id'] == $e['id_employee']) ? 'selected' : '' ?>>
                                    <?= $e['first_name'] ?> <?= $e['last_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Show</button>
            </form>

            <?php if ($results): ?>
                <h3>Reservations list for: <?= htmlspecialchars($selected_employee_name) ?></h3>

                <table>
                    <tr><th>Res ID</th><th>Room</th><th>Client</th><th>Check In</th></tr>
                    <?php foreach ($results as $r): ?>
                        <tr>
                            <td><?= $r['id_reservation'] ?></td>
                            <td><?= $r['number'] ?></td>
                            <td><?= $r['first_name'] ?> <?= $r['last_name'] ?></td>
                            <td><?= $r['check_in'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php elseif (isset($_GET['emp_id']) && !empty($_GET['emp_id'])): ?>
                <p>No reservations found for <strong><?= htmlspecialchars($selected_employee_name) ?></strong>.</p>
            <?php endif; ?>
        </div>
    </body>
</html>