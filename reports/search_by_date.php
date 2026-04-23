<?php
require_once '../config/config.php';

$results = [];

if (isset($_GET['date'])) {
    $date = $_GET['date'];

    $sql = "SELECT Room.number, Room.floor, Reservation.status, 
                   Client.first_name, Client.last_name
            FROM Reservation
            JOIN Room ON Reservation.id_room = Room.id_room
            JOIN Client ON Reservation.id_client = Client.id_client
            WHERE :date BETWEEN check_in AND check_out";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':date' => $date]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="occupied_rooms_' . $_GET['date'] . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Room Number', 'Floor', 'Guest Name', 'Status']);

    foreach ($results as $row) {
        fputcsv($output, [
            $row['number'],
            $row['floor'],
            $row['first_name'] . ' ' . $row['last_name'],
            ucfirst($row['status'])
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Occupied Rooms</title>
        <link rel="stylesheet" href="../public/css/queries_style.css">
    </head>
    <body>
        <div class="container">
            <div class="header-actions">
                <a href="../public/index.php?page=queries" class="btn-back">Back</a>
                <?php if (isset($_GET['date'])): ?>
                    <a href="?export=csv&date=<?= $_GET['date'] ?>" class="btn-export">Export</a>
                <?php endif; ?>
            </div>
            <h2>Check Occupied Rooms</h2>
            <form>
                Check Date: <input type="date" name="date" required>
                <button type="submit">Check</button>
            </form>

            <?php if ($results): ?>
                <h3>Occupied Rooms on <?= htmlspecialchars($_GET['date']) ?>:</h3>
                <table>
                    <tr><th>Room</th><th>Floor</th><th>Guest</th><th>Status</th></tr>
                    <?php foreach ($results as $r): ?>
                        <tr>
                            <td><strong><?= $r['number'] ?></strong></td>
                            <td><?= $r['floor'] ?></td>
                            <td><?= $r['first_name'] ?> <?= $r['last_name'] ?></td>
                            <td><?= ucfirst($r['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php elseif (isset($_GET['date'])): ?>
                <p>No rooms are occupied on this date.</p>
            <?php endif; ?>
        </div>
    </body>
</html>