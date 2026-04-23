<?php
require_once '../config/config.php';

$results = [];

if (isset($_GET['check_in'])) {
    $in = $_GET['check_in'];
    $out = $_GET['check_out'];

    $sql = "SELECT Room.*, Room_type.name as type_name, Room_type.capacity
            FROM Room
            JOIN Room_type ON Room.id_type = Room_type.id_type
            WHERE id_room NOT IN (
                SELECT id_room FROM Reservation 
                WHERE (check_in <= :out AND check_out >= :in)
                AND status != 'cancelled'
            )";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':in' => $in, ':out' => $out]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="free_rooms_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Room Number', 'Type', 'Capacity', 'Status']);

    foreach ($results as $row) {
        fputcsv($output, [
            $row['number'],
            $row['type_name'],
            $row['capacity'],
            'Available'
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Free Rooms</title>
        <link rel="stylesheet" href="../public/css/queries_style.css">
    </head>
    <body>
        <div class="container">
            <div class="header-actions">
                <a href="../public/index.php?page=queries" class="btn-back">Back</a>
                <?php if (isset($_GET['check_in']) && isset($_GET['check_out'])): ?>
                    <a href="?export=csv&check_in=<?= $_GET['check_in'] ?>&check_out=<?= $_GET['check_out'] ?>" class="btn-export">Export</a>
                <?php endif; ?>
            </div>
            <h2>Find Available Rooms</h2>
            <form>
                From: <input type="date" name="check_in" required>
                To: <input type="date" name="check_out" required>
                <button type="submit">Find</button>
            </form>

            <?php if ($results): ?>
                <table>
                    <tr><th>Room №</th><th>Type</th><th>Capacity</th><th>Status</th></tr>
                    <?php foreach ($results as $r): ?>
                        <tr>
                            <td><strong><?= $r['number'] ?></strong></td>
                            <td><?= $r['type_name'] ?></td>
                            <td><?= $r['capacity'] ?></td>
                            <td>Available</td>  
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </body>
</html>