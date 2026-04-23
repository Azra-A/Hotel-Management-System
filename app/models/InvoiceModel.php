<?php
class InvoiceModel {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT Invoice.*, 
                       Reservation.id_reservation,
                       Client.first_name, Client.last_name
                FROM Invoice
                JOIN Reservation ON Invoice.id_reservation = Reservation.id_reservation
                JOIN Client ON Reservation.id_client = Client.id_client
                ORDER BY Invoice.issued_on ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Invoice WHERE id_invoice = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($id_reservation, $total) {
        $sql = "INSERT INTO Invoice (id_reservation, issued_on, total) VALUES (?, NOW(), ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_reservation, $total]);
    }

    public function update($id, $id_res, $issued_on, $total) {
        $sql = "UPDATE Invoice SET id_reservation=?, issued_on=?, total=? WHERE id_invoice=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_res, $issued_on, $total, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM Invoice WHERE id_invoice = ?");
        return $stmt->execute([$id]);
    }

    
    public function getTotalCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM Invoice");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getPaginated($limit, $offset) {
        $sql = "SELECT Invoice.*, 
                       Client.first_name, 
                       Client.last_name
                FROM Invoice
                JOIN Reservation ON Invoice.id_reservation = Reservation.id_reservation
                JOIN Client ON Reservation.id_client = Client.id_client
                ORDER BY Invoice.id_invoice ASC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getWithBalance($id) {
        $sql = "SELECT Invoice.*, 
                       (SELECT COALESCE(SUM(amount), 0) FROM Payment WHERE id_invoice = Invoice.id_invoice) as paid_so_far,
                       Client.first_name, Client.last_name
                FROM Invoice
                JOIN Reservation ON Invoice.id_reservation = Reservation.id_reservation
                JOIN Client ON Reservation.id_client = Client.id_client
                WHERE Invoice.id_invoice = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $data['balance'] = $data['total'] - $data['paid_so_far'];
        }

        return $data;
    }
}
?>