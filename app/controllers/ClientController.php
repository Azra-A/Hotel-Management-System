<?php
require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/AuditLogModel.php';

class ClientController {
    private $model;

    public function __construct($db) {
        $this->model = new ClientModel($db);
    }

    public function view() {
        $limit = 5;
        
        $page = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
        if ($page < 1)
            $page = 1;

        $offset = ($page - 1) * $limit;

        $total_rows = $this->model->getTotalCount();
        $total_pages = ceil($total_rows / $limit);

        $clients = $this->model->getPaginated($limit, $offset);

        return [
            'clients' => $clients,
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
            
            $fname = $_POST['first_name'];
            $lname = $_POST['last_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];

            if (strlen($phone) != 13) {
                return "Error: Phone number is too short or too long! Must be 12 digits!";
            }

            if ($this->model->create($fname, $lname, $email, $phone)) {

                $logger = new AuditLogModel($this->model->conn);
                $new_id = $this->model->conn->lastInsertId();
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'CREATE', 'Client', $new_id);

                header("Location: ClientView.php");
                exit;
            } else {
                echo "Error adding client.";
            }
        }
    }

    public function edit($id) {        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::check();
            
            $fname = $_POST['first_name'];
            $lname = $_POST['last_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];

            if (strlen($phone) != 13) {
                return "Error: Phone number is too short or too long! Must be 12 digits!";
            }

            if ($this->model->update($id, $fname, $lname, $email, $phone)) {

                $logger = new AuditLogModel($this->model->conn);
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'UPDATE', 'Client', $id);

                header("Location: ClientView.php");
                exit;
            } else {
                echo "Error updating client.";
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Csrf::check();

            if ($this->model->delete($id)) {
                
                $logger = new AuditLogModel($this->model->conn);
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'DELETE', 'Client', $id);

                header("Location: ClientView.php");
                exit;
            } else {
                return "Error deleting record.";
            }
        }
    }
}
?>