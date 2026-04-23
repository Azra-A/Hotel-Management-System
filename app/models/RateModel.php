<?php
class RateModel {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT Rate.*, Room_type.name as type_name 
                FROM Rate 
                JOIN Room_type ON Rate.id_type = Room_type.id_type 
                ORDER BY Rate.start_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Rate WHERE id_rate = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($id_type, $start, $end, $price) {
        $sql = "INSERT INTO Rate (id_type, start_date, end_date, price_per_night) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_type, $start, $end, $price]);
    }

    public function update($id, $id_type, $start, $end, $price) {
        $sql = "UPDATE Rate SET id_type=?, start_date=?, end_date=?, price_per_night=? WHERE id_rate=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_type, $start, $end, $price, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM Rate WHERE id_rate = ?");
        return $stmt->execute([$id]);
    }


    public function getTotalCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM Rate");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getPaginated($limit, $offset) {
        $sql = "SELECT Rate.*, Room_type.name as type_name 
                FROM Rate 
                JOIN Room_type ON Rate.id_type = Room_type.id_type 
                ORDER BY Rate.start_date ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>