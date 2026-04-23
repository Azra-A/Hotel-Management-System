<?php
class ReservationModel {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT Reservation.*, 
                       Room.number as room_number, 
                       Client.first_name as c_fname, Client.last_name as c_lname,
                       Employee.first_name as e_fname, Employee.last_name as e_lname
                FROM Reservation
                JOIN Room ON Reservation.id_room = Room.id_room
                JOIN Client ON Reservation.id_client = Client.id_client
                JOIN Employee ON Reservation.id_employee = Employee.id_employee
                ORDER BY Reservation.id_reservation ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT r.*, 
                       rm.number AS room_number, 
                       c.first_name AS c_fname, 
                       c.last_name AS c_lname
                FROM Reservation r
                LEFT JOIN Room rm ON r.id_room = rm.id_room
                LEFT JOIN Client c ON r.id_client = c.id_client
                WHERE r.id_reservation = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($id_room, $id_client, $id_emp, $in, $out, $status) {
        $sql = "INSERT INTO Reservation (id_room, id_client, id_employee, check_in, check_out, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_room, $id_client, $id_emp, $in, $out, $status]);
    }

    public function update($id, $id_room, $id_client, $id_emp, $in, $out, $status) {
        $sql = "UPDATE Reservation SET id_room=?, id_client=?, id_employee=?, check_in=?, check_out=?, status=? WHERE id_reservation=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_room, $id_client, $id_emp, $in, $out, $status, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM Reservation WHERE id_reservation = ?");
        return $stmt->execute([$id]);
    }


    public function getTotalCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM Reservation");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getPaginated($limit, $offset) {
        $sql = "SELECT Reservation.*, 
                       Room.number as room_number, 
                       Client.first_name as c_fname, Client.last_name as c_lname,
                       Employee.first_name as e_fname, Employee.last_name as e_lname
                FROM Reservation
                JOIN Room ON Reservation.id_room = Room.id_room
                JOIN Client ON Reservation.id_client = Client.id_client
                JOIN Employee ON Reservation.id_employee = Employee.id_employee
                ORDER BY Reservation.id_reservation ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getConflictingReservation($room_id, $check_in, $check_out, $exclude_id = null) {
        $sql = "SELECT r.check_in, r.check_out, rm.number 
                    FROM Reservation r
                    JOIN Room rm ON r.id_room = rm.id_room
                    WHERE r.id_room = :room_id 
                    AND r.status != 'cancelled'
                    AND (r.check_in < :check_out AND r.check_out > :check_in)";

        if ($exclude_id) {
            $sql .= " AND r.id_reservation != :exclude_id";
        }

        $sql .= " LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $params = [
            ':room_id' => $room_id,
            ':check_in' => $check_in,
            ':check_out' => $check_out
        ];

        if ($exclude_id) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>