<?php
require_once __DIR__ . '/../models/InvoiceModel.php';
require_once __DIR__ . '/../models/AuditLogModel.php';

class InvoiceController {
    private $model;

    public function __construct($db) {
        $this->model = new InvoiceModel($db);
    }
    
    public function view() {
        $limit = 5; 
        
        $page = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
        if ($page < 1)
            $page = 1;

        $offset = ($page - 1) * $limit;

        $total_rows = $this->model->getTotalCount();
        $total_pages = ceil($total_rows / $limit);
        
        $invoices = $this->model->getPaginated($limit, $offset);

        return [
            'invoices' => $invoices,
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
            
            if ($this->model->create($_POST['id_reservation'], $_POST['issued_on'], $_POST['total'])) {

                $logger = new AuditLogModel($this->model->conn);
                $new_id = $this->model->conn->lastInsertId();
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'CREATE', 'Invoice', $new_id);

                header("Location: InvoiceView.php");
                exit;
            }
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Csrf::check();
            
            if ($this->model->update($id, $_POST['id_reservation'], $_POST['issued_on'], $_POST['total'])) {

                $logger = new AuditLogModel($this->model->conn);
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'UPDATE', 'Invoice', $id);

                header("Location: InvoiceView.php");
                exit;
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Csrf::check();

            if ($this->model->delete($id)) {

                $logger = new AuditLogModel($this->model->conn);
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'DELETE', 'Invoice', $id);

                header("Location: InvoiceView.php");
                exit;
            } else {
                echo "Error deleting record.";
            }
        }
    }
}
?>