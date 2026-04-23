<?php
class PaymentModel {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT Payment.*, Invoice.total as inv_total 
                FROM Payment 
                JOIN Invoice ON Payment.id_invoice = Invoice.id_invoice 
                ORDER BY Payment.paid_on ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Payment WHERE id_payment = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($id_inv, $date, $amount) {
        $sql = "INSERT INTO Payment (id_invoice, paid_on, amount) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_inv, $date, $amount]);
    }

    public function update($id, $id_inv, $date, $amount) {
        $sql = "UPDATE Payment SET id_invoice=?, paid_on=?, amount=? WHERE id_payment=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_inv, $date, $amount, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM Payment WHERE id_payment = ?");
        return $stmt->execute([$id]);
    }

    
    public function getTotalCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM Payment");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getPaginated($limit, $offset) {
        $sql = "SELECT Payment.*, Invoice.total as inv_total 
                FROM Payment 
                JOIN Invoice ON Payment.id_invoice = Invoice.id_invoice 
                ORDER BY Payment.paid_on ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>