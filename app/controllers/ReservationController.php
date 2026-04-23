<?php
require_once __DIR__ . '/../models/ReservationModel.php';
require_once __DIR__ . '/../models/RoomModel.php';
require_once __DIR__ . '/../models/InvoiceModel.php';
require_once __DIR__ . '/../models/AuditLogModel.php';

class ReservationController {
    private $model;

    public function __construct($db) {
        $this->model = new ReservationModel($db);
    }

    public function view() {
        $limit = 5;

        $page = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
        if ($page < 1)
            $page = 1;

        $offset = ($page - 1) * $limit;

        $total_rows = $this->model->getTotalCount();
        $total_pages = ceil($total_rows / $limit);

        $reservations = $this->model->getPaginated($limit, $offset);

        return [
            'reservations' => $reservations,
            'total_pages' => $total_pages,
            'current_page' => $page
        ];
    }

    public function getOne($id) {
        return $this->model->getById($id);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Csrf::check();
            
            $id_room = $_POST['id_room'];
            $id_client = $_POST['id_client'];
            $id_employee = $_POST['id_employee'];
            $check_in = $_POST['check_in'];
            $check_out = $_POST['check_out'];
            $status = $_POST['status'];

            if ($check_in >= $check_out) {
                return "Error: Check-out date must be AFTER Check-in date!";
            }

            $conflict = $this->model->getConflictingReservation($id_room, $check_in, $check_out);

            if ($conflict) {
                $start = date("d/m/Y", strtotime($conflict['check_in']));
                $end = date("d/m/Y", strtotime($conflict['check_out']));
                $roomNum = $conflict['number'];

                return "Room $roomNum is unavailable from $start until $end. Please select another date or room!";
            }
            
            try {
                $this->model->conn->beginTransaction();

                if (!$this->model->create($id_room, $id_client, $id_employee, $check_in, $check_out, $status)) {
                    throw new Exception("Could not save reservation.");
                }

                $reservation_id = $this->model->conn->lastInsertId();

                $roomModel = new RoomModel($this->model->conn);
                $price_per_night = $roomModel->getPrice($id_room, $check_in);

                if ($price_per_night == 0) {
                    throw new Exception("No price found in 'Rate' table for this room/date.");
                }

                $d1 = new DateTime($check_in);
                $d2 = new DateTime($check_out);
                $diff = $d1->diff($d2);
                $days = $diff->days;

                $total_price = $days * $price_per_night;



                $invoiceModel = new InvoiceModel($this->model->conn);
                if (!$invoiceModel->create($reservation_id, $total_price)) {
                    throw new Exception("Could not create invoice.");
                }

                $this->model->conn->commit();


                $logger = new AuditLogModel($this->model->conn);
                $current_emp = $_SESSION['user_id'] ?? 1;
                $logger->log($current_emp, 'CREATE', 'Reservation', $reservation_id);

                header("Location: ReservationView.php");
                exit;
            } catch (Exception $e) {
                $this->model->conn->rollBack();
                return "Transaction Failed: " . $e->getMessage();
            }
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Csrf::check();
            
            $id_room = $_POST['id_room'];
            $id_client = $_POST['id_client'];
            $id_employee = $_POST['id_employee'];
            $check_in = $_POST['check_in'];
            $check_out = $_POST['check_out'];
            $status = $_POST['status'];

            if ($check_in >= $check_out) {
                return "Error: Check-out date must be AFTER Check-in date!";
            }

            $conflict = $this->model->getConflictingReservation($id_room, $check_in, $check_out, $id);

            if ($conflict) {
                $start = date("d/m/Y", strtotime($conflict['check_in']));
                $end = date("d/m/Y", strtotime($conflict['check_out']));
                $roomNum = $conflict['number'];

                return "Room $roomNum is unavailable from $start until $end. Please select another date or room!";
            }
            
            try {
                $this->model->conn->beginTransaction();

                if (!$this->model->update($id, $id_room, $id_client, $id_employee, $check_in, $check_out, $status)) {
                    throw new Exception("Could not update reservation details.");
                }

                require_once __DIR__ . '/../models/RoomModel.php';
                require_once __DIR__ . '/../models/InvoiceModel.php';

                $roomModel = new RoomModel($this->model->conn);

                $price_per_night = $roomModel->getPrice($id_room, $check_in);

                if ($price_per_night == 0) {
                    throw new Exception("Price not found for dates.");
                }

                $d1 = new DateTime($check_in);
                $d2 = new DateTime($check_out);
                $diff = $d1->diff($d2);
                $days = $diff->days;

                $new_total = $days * $price_per_night;

                
                $sqlInvoice = "UPDATE Invoice SET total = ?, issued_on = NOW() WHERE id_reservation = ?";
                $stmtInv = $this->model->conn->prepare($sqlInvoice);
                $stmtInv->execute([$new_total, $id]);

                require_once __DIR__ . '/../models/AuditLogModel.php';
                $logger = new AuditLogModel($this->model->conn);
                $current_emp = $_SESSION['user_id'] ?? 1;
                $logger->log($current_emp, 'UPDATE', 'Reservation', $id);

                $this->model->conn->commit();

                header("Location: ReservationView.php");
                exit;
            } catch (Exception $e) {
                $this->model->conn->rollBack();
                return "Update Failed: " . $e->getMessage();
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Csrf::check();

            if ($this->model->delete($id)) {

                $logger = new AuditLogModel($this->model->conn);
                $current_emp = $_SESSION['user_id'] ?? 1;
                $logger->log($current_emp, 'DELETE', 'Reservation', $id);

                header("Location: ReservationView.php");
                exit;
            } else {
                echo "Error deleting record.";
            }
        }
    }
}

?>