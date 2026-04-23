<?php
require_once '../config/config.php';

$sql = "SELECT Room.number, Room_type.name, 
               COUNT(Reservation.id_reservation) as count
        FROM Room
        JOIN Room_type ON Room.id_type = Room_type.id_type
        LEFT JOIN Reservation ON Room.id_room = Reservation.id_room
        GROUP BY Room.id_room
        ORDER BY count DESC
        LIMIT 5";
$stmt = $conn->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="top_rooms_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Rank', 'Room Number', 'Type', 'Times Booked']);

    $rank = 1;

    foreach ($data as $row) {
        fputcsv($output, [
            $rank++,
            $row['number'],
            $row['name'],
            $row['count']
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Top Rooms</title>
        <link rel="stylesheet" href="../public/css/queries_style.css">
    </head>
    <body>
        <div class="container">
            <div class="header-actions">
                <a href="../public/index.php?page=queries" class="btn-back">Back</a>
                <a href="?export=csv" class="btn-export">Export</a>
            </div>
            <h2>Most Popular Rooms</h2>
            <table>
                <tr><th>Rank</th><th>Room Number</th><th>Type</th><th>Times Booked</th></tr>
                <?php
                $i = 1;
                foreach ($data as $row):
                    ?>
                    <tr>
                        <td>№<?= $i++ ?></td>
                        <td><?= $row['number'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><strong><?= $row['count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </body>
</html>