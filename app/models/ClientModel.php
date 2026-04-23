<?php
class ClientModel {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM Client ORDER BY id_client ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Client WHERE id_client = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($fname, $lname, $email, $phone) {
        $sql = "INSERT INTO Client (first_name, last_name, email, phone) 
                VALUES (:fname, :lname, :email, :phone)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
                    ':fname' => $fname,
                    ':lname' => $lname,
                    ':email' => $email,
                    ':phone' => $phone
        ]);
    }

    public function update($id, $fname, $lname, $email, $phone) {
        $sql = "UPDATE Client SET first_name = :fname, last_name = :lname, 
                email = :email, phone = :phone WHERE id_client = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
                    ':fname' => $fname,
                    ':lname' => $lname,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':id' => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM Client WHERE id_client = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getTotalCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM Client");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getPaginated($limit, $offset) {
        $sql = "SELECT * FROM Client ORDER BY id_client ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>