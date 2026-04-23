<?php
require_once '../config/config.php';

$results = [];
$types = $conn->query("SELECT * FROM Room_type")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['type_id']) && !empty($_GET['type_id'])) {
    $sql = "SELECT Room.*, Room_type.name as type_name, Room_type.capacity 
            FROM Room 
            JOIN Room_type ON Room.id_type = Room_type.id_type 
            WHERE Room.id_type = :type_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':type_id' => $_GET['type_id']]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="rooms_by_type_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Room Number', 'Type', 'Floor', 'Status']);

    foreach ($results as $row) {
        fputcsv($output, [
            $row['number'],
            $row['type_name'] . ' (Cap: ' . $row['capacity'] . ')',
            $row['floor'],
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
        <title>Search by Type</title>
        <link rel="stylesheet" href="../public/css/queries_style.css">
    </head>
    <body>
        <div class="container">
            <div class="header-actions">
                <a href="../public/index.php?page=queries" class="btn-back">Back</a>
                <?php if (isset($_GET['type_id']) && $_GET['type_id']): ?>
                    <a href="?export=csv&type_id=<?= $_GET['type_id'] ?>" class="btn-export">Export</a>
                <?php endif; ?>
            </div> 
            <h2>Search Rooms by Type</h2>
            <form>
                <select name="type_id">
                    <option value="">-- Select Type --</option>
                    <?php foreach ($types as $t): ?>
                        <option value="<?= $t['id_type'] ?>"><?= $t['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Search</button>
            </form>

            <?php if ($results): ?>
                <table>
                    <tr><th>Room №</th><th>Type</th><th>Floor</th><th>Status</th></tr>
                    <?php foreach ($results as $r): ?>
                        <tr>
                            <td><?= $r['number'] ?></td>
                            <td><?= $r['type_name'] ?> (Cap: <?= $r['capacity'] ?>)</td>
                            <td><?= $r['floor'] ?></td>
                            <td><?= ucfirst($r['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </body>
</html>