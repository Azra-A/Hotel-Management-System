<?php
require_once __DIR__ . '/../models/EmployeeModel.php';
require_once __DIR__ . '/../models/AuditLogModel.php';

class EmployeeController {
    private $model;

    public function __construct($db) {
        $this->model = new EmployeeModel($db);
    }

    public function view() {
        $limit = 5; 
        
        $page = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
        if ($page < 1)
            $page = 1;

        $offset = ($page - 1) * $limit;

        $total_rows = $this->model->getTotalCount();
        $total_pages = ceil($total_rows / $limit);

        $employees = $this->model->getPaginated($limit, $offset);

        return [
            'employees' => $employees,
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
            $pass = $_POST['password'];
            $role = $_POST['role'];

            if (!str_ends_with($email, '@hotel.com')) {
                return "Invalid email domain! Only '@hotel.com' addresses are allowed.";
            }


            if ($this->model->create($fname, $lname, $email, $pass, $role)) {

                $logger = new AuditLogModel($this->model->conn);
                $new_id = $this->model->conn->lastInsertId();
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'CREATE', 'Employee', $new_id);

                header("Location: EmployeeView.php");
                exit;
            } else {
                echo "Error adding employee.";
            }
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            Csrf::check();

            $fname = $_POST['first_name'];
            $lname = $_POST['last_name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $pass = $_POST['password'];

            if (!str_ends_with($email, '@hotel.com')) {
                return "Invalid email domain! Only '@hotel.com' addresses are allowed.";
            }

            if ($this->model->update($id, $fname, $lname, $email, $role, $pass)) {

                $logger = new AuditLogModel($this->model->conn);
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'UPDATE', 'Employee', $id);

                header("Location: EmployeeView.php");
                exit;
            } else {
                echo "Error updating employee.";
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            Csrf::check();

            if ($this->model->delete($id)) {

                $logger = new AuditLogModel($this->model->conn);
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'DELETE', 'Employee', $id);

                header("Location: EmployeeView.php");
                exit;
            } else {
                echo "Error deleting record.";
            }
        }
    }
}
?>