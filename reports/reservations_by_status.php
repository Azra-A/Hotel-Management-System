<?php
require_once '../config/config.php';

$results = [];
$selected_status = isset($_GET['status']) ? $_GET['status'] : '';

if ($selected_status) {
    $sql = "SELECT Reservation.*, Room.number, Client.first_name, Client.last_name
            FROM Reservation
            JOIN Room ON Reservation.id_room = Room.id_room
            JOIN Client ON Reservation.id_client = Client.id_client
            WHERE Reservation.status = :status
            ORDER BY check_in DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':status' => $selected_status]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="reservations_' . $selected_status . '_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['ID', 'Room', 'Client', 'Check In', 'Check Out', 'Status']);

    foreach ($results as $row) {
        fputcsv($output, [
            $row['id_reservation'],
            $row['number'],
            $row['first_name'] . ' ' . $row['last_name'],
            $row['check_in'],
            $row['check_out'],
            ucfirst($selected_status)
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reservations by Status</title>
        <link rel="stylesheet" href="../public/css/queries_style.css">
    </head>
    <body>
        <div class="container">
            <div class="header-actions">
                <a href="../public/index.php?page=queries" class="btn-back">Back</a>
                <?php if ($selected_status): ?>
                    <a href="?export=csv&status=<?= $selected_status ?>" class="btn-export">Export</a>
                <?php endif; ?>            
            </div> 
            <h2>Filter Reservations</h2>
            <form>
                <label>Select Status:</label>
                <select name="status" required>
                    <option value="">-- Choose Status --</option>
                    <option value="confirmed" <?= $selected_status == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="pending" <?= $selected_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="cancelled" <?= $selected_status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit">Show</button>
            </form>

            <?php if ($selected_status): ?>

                <h3 class="result-count">
                    <?= ucfirst($selected_status) ?> reservations: <?= count($results) ?>
                </h3>

                <?php if (count($results) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Room</th>
                                <th>Client</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $r): ?>
                                <tr>
                                    <td><?= $r['id_reservation'] ?></td>
                                    <td><strong><?= $r['number'] ?></strong></td>
                                    <td><?= htmlspecialchars($r['first_name']) ?> <?= htmlspecialchars($r['last_name']) ?></td>
                                    <td><?= $r['check_in'] ?></td>
                                    <td><?= $r['check_out'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php else: ?>
                    <p style="color:red;"><strong>No reservations found with this status.</p>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </body>
</html>