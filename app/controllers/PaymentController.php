<?php
require_once __DIR__ . '/../models/PaymentModel.php';
require_once __DIR__ . '/../models/AuditLogModel.php';

class PaymentController {

    private $model;

    public function __construct($db) {
        $this->model = new PaymentModel($db);
    }

    public function view() {
        $limit = 5;

        $page = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
        if ($page < 1)
            $page = 1;

        $offset = ($page - 1) * $limit;

        $total_rows = $this->model->getTotalCount();
        $total_pages = ceil($total_rows / $limit);

        $payments = $this->model->getPaginated($limit, $offset);

        return [
            'payments' => $payments,
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

            $inv_id = $_POST['id_invoice'];
            $paid_on = $_POST['paid_on'];
            $amount = (float) $_POST['amount'];

            $stmtInvoice = $this->model->conn->prepare("SELECT total FROM Invoice WHERE id_invoice = ?");
            $stmtInvoice->execute([$inv_id]);
            $invoice = $stmtInvoice->fetch(PDO::FETCH_ASSOC);

            if (!$invoice) {
                die("Invoice not found!");
            }

            $total_bill = (float) $invoice['total'];

            $stmtPaid = $this->model->conn->prepare("SELECT SUM(amount) as paid_so_far FROM Payment WHERE id_invoice = ?");
            $stmtPaid->execute([$inv_id]);
            $paidData = $stmtPaid->fetch(PDO::FETCH_ASSOC);

            $paid_so_far = (float) ($paidData['paid_so_far'] ?? 0);

            $remaining = $total_bill - $paid_so_far;

            if (round($amount, 2) > round($remaining, 2)) {
                die("<div style='font-family: sans-serif; color: red; text-align: center; margin-top: 50px;'>
                    <h2>Error!</h2>
                    <br>
                    <p>The amount ($amount BGN) exceeds the remaining balance ($remaining BGN)!</p>
                </div>");
            }

            if ($this->model->create($inv_id, $paid_on, $amount)) {

                $logger = new AuditLogModel($this->model->conn);
                $new_id = $this->model->conn->lastInsertId();
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'CREATE', 'Payment', $new_id);

                header("Location: PaymentView.php");
                exit;
            }
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            Csrf::check();

            if ($this->model->update($id, $_POST['id_invoice'], $_POST['paid_on'], $_POST['amount'])) {

                $logger = new AuditLogModel($this->model->conn);
                $emp_id = $_SESSION['user_id'] ?? 1;
                $logger->log($emp_id, 'UPDATE', 'Payment', $id);

                header("Location: PaymentView.php");
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
                $logger->log($emp_id, 'DELETE', 'Payment', $id);

                header("Location: PaymentView.php");
                exit;
            } else {
                echo "Error deleting record.";
            }
        }
    }
}

?>