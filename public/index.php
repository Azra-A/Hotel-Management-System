<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_role = $_SESSION['user_role'];
$user_name = $_SESSION['user_name'] ?? 'User';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if ($page === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit;
}

switch ($page) {
    case 'employees':
        if ($user_role === 'admin') {
            header("Location: ../app/views/employee/EmployeeView.php");
            exit;
        } else {
            $error_msg = "Access Denied. Only Admins can view this.";
        }
        break;

    case 'clients': header("Location: ../app/views/client/ClientView.php");
        exit;
        break;
    case 'room_types': header("Location: ../app/views/roomtype/RoomTypeView.php");
        exit;
        break;
    case 'rooms': header("Location: ../app/views/room/RoomView.php");
        exit;
        break;
    case 'rates': header("Location: ../app/views/rate/RateView.php");
        exit;
        break;
    case 'reservations': header("Location: ../app/views/reservation/ReservationView.php");
        exit;
        break;
    case 'invoices': header("Location: ../app/views/invoice/InvoiceView.php");
        exit;
        break;
    case 'payments': header("Location: ../app/views/payment/PaymentView.php");
        exit;
        break;

    case 'queries':
        break;

    case 'home':
    default:
        break;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hotel Management System</title>
        <link rel="stylesheet" href="css/index_style.css">    
    </head>
    <body>

        <div class="topnav">
            <a href="?page=home" class="<?= $page == 'home' ? 'active' : '' ?>">Home</a>

            <?php if ($user_role === 'admin'): ?>
                <a href="../app/views/audit_log/AuditLogView.php" style="color: #ebb800;"> Audit Logs</a>
                <a href="?page=employees">Employees</a>
            <?php endif; ?>

            <a href="?page=clients">Clients</a>
            <a href="?page=room_types">Types</a>
            <a href="?page=rooms">Rooms</a>
            <a href="?page=rates">Rates</a>
            <a href="?page=reservations">Reservations</a>
            <a href="?page=invoices">Invoices</a>
            <a href="?page=payments">Payments</a>

            <a href="?page=queries" class="<?= $page == 'queries' ? 'active' : '' ?>">Queries</a>

            <a href="?page=logout" class="logout">Logout</a>

            <div class="topnav-right">
                User: <strong style="color:white"><?= htmlspecialchars($user_name) ?></strong> (<?= ucfirst($user_role) ?>)
            </div>
        </div>

        <div class="container">
            <?php
            if (isset($error_msg)) {
                echo "<h3 style='color:red'>$error_msg</h3>";
            }
            ?>

            <?php
            if ($page == 'queries') {
                ?>
                <h2>Reports & Queries</h2>
                <p>Select a report to generate:</p>

                <div class="query-box"><a href="../reports/search_by_type.php" class="query-link">Search Rooms by Type</a></div>
                <div class="query-box"><a href="../reports/search_by_date.php" class="query-link">Search Rooms by Date</a></div>
                <div class="query-box"><a href="../reports/reservations_by_status.php" class="query-link">Reservation by Status</a></div>
                <div class="query-box"><a href="../reports/free_rooms.php" class="query-link">Available Rooms</a></div>
                <div class="query-box"><a href="../reports/client_bill.php" class="query-link">Client Total Bill</a></div>
                <div class="query-box"><a href="../reports/revenue_report.php" class="query-link">Revenue Report</a></div>
                <div class="query-box"><a href="../reports/employee_reservations.php" class="query-link">Reservations by Employee</a></div>
                <div class="query-box"><a href="../reports/top_employees.php" class="query-link">Top 5 Employees</a></div>
                <div class="query-box"><a href="../reports/top_rooms.php" class="query-link">Most Popular Rooms</a></div>
                <?php
            } else {
                ?>
                <h1>Welcome back, <?= htmlspecialchars($user_name) ?>!</h1>
                <p>You are logged in as <strong><?= ucfirst($user_role) ?></strong>.</p>
                <hr>
                <h3>Quick Stats:</h3>
                <ul>
                    <li>System is secure and operational.</li>
                    <li>Database connection established.</li>
                </ul>
                <?php
            }
            ?>
        </div>

    </body>
</html>