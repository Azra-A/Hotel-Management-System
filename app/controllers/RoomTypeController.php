<?php
require_once __DIR__ . '/../models/RoomTypeModel.php';
require_once __DIR__ . '/../models/AuditLogModel.php';

class RoomTypeController {
    private $model;

    public function __construct($db) {
        $this->model = new RoomTypeModel($db);
    }

    public function view() {
        $limit = 5;
        
        $page = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
        if ($page < 1)
            $page = 1;

        $offset = ($page - 1) * $limit;

        $total_rows = $this->model->getTotalCount();
        $total_pages = ceil($total_rows / $limit);

        $types = $this->model->getPaginated($limit, $offset);

        return [
            'types' => $types,
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
            
            $name = $_POST['name'];
            $capacity = $_POST['capacity'];

            if ($capacity <= 0) {
                return "Error: Capacity must be greater than 0!";
            }

            if ($this->model->create($name, $capacity)) {

                $logger = new AuditLogModel($this->model->conn);
                $new_id = $this->model->conn->lastInsertId();
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'CREATE', 'RoomType', $new_id);

                header("Location: RoomTypeView.php");
                exit;
            } else {
                echo "Error adding room type.";
            }
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Csrf::check();
            
            $name = $_POST['name'];
            $capacity = $_POST['capacity'];

            if ($capacity <= 0) {
                return "Error: Capacity must be greater than 0!";
            }

            if ($this->model->update($id, $name, $capacity)) {

                $logger = new AuditLogModel($this->model->conn);
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'UPDATE', 'RoomType', $id);

                header("Location: RoomTypeView.php");
                exit;
            } else {
                echo "Error updating room type.";
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Csrf::check();

            if ($this->model->delete($id)) {

                $logger = new AuditLogModel($this->model->conn);
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'DELETE', 'RoomType', $id);

                header("Location: RoomTypeView.php");
                exit;
            } else {
                echo "Error deleting record.";
            }
        }
    }
}
?>