<?php
class RoomModel {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT Room.*, Room_type.name as type_name 
                FROM Room 
                JOIN Room_type ON Room.id_type = Room_type.id_type 
                ORDER BY Room.number ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Room WHERE id_room = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($id_type, $number, $floor, $status) {
        $sql = "INSERT INTO Room (id_type, number, floor, status) VALUES (:id_type, :number, :floor, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id_type' => $id_type, ':number' => $number, ':floor' => $floor, ':status' => $status]);
    }

    public function update($id, $id_type, $number, $floor, $status) {
        $sql = "UPDATE Room SET id_type=:id_type, number=:number, floor=:floor, status=:status WHERE id_room=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id_type' => $id_type, ':number' => $number, ':floor' => $floor, ':status' => $status, ':id' => $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM Room WHERE id_room = :id");
        return $stmt->execute([':id' => $id]);
    }


    public function getTotalCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM Room");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getPaginated($limit, $offset) {
        $sql = "SELECT Room.*, Room_type.name as type_name 
                FROM Room 
                JOIN Room_type ON Room.id_type = Room_type.id_type 
                ORDER BY Room.number ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrice($room_id, $check_in_date) {
        $sql = "SELECT Rate.price_per_night 
                FROM Room 
                JOIN Rate ON Room.id_type = Rate.id_type 
                WHERE Room.id_room = :room_id 
                AND :check_in >= Rate.start_date 
                AND :check_in <= Rate.end_date
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':room_id' => $room_id,
            ':check_in' => $check_in_date
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row['price_per_night'];
        }

        $sqlFallback = "SELECT Rate.price_per_night 
                        FROM Room 
                        JOIN Rate ON Room.id_type = Rate.id_type 
                        WHERE Room.id_room = ? 
                        ORDER BY Rate.id_rate DESC LIMIT 1";
        $stmtFallback = $this->conn->prepare($sqlFallback);
        $stmtFallback->execute([$room_id]);
        $rowFallback = $stmtFallback->fetch(PDO::FETCH_ASSOC);

        return $rowFallback ? $rowFallback['price_per_night'] : 0;
    }
}

?>