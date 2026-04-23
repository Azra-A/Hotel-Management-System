<?php
class RoomTypeModel {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM Room_type ORDER BY id_type ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Room_type WHERE id_type = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $capacity) {
        $sql = "INSERT INTO Room_type (name, capacity) VALUES (:name, :capacity)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':name' => $name, ':capacity' => $capacity]);
    }

    public function update($id, $name, $capacity) {
        $sql = "UPDATE Room_type SET name = :name, capacity = :capacity WHERE id_type = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':name' => $name, ':capacity' => $capacity, ':id' => $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM Room_type WHERE id_type = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getTotalCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM Room_type");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getPaginated($limit, $offset) {
        $sql = "SELECT * FROM Room_type ORDER BY id_type ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>