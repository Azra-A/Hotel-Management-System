<?php
class EmployeeModel {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM Employee ORDER BY id_employee ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Employee WHERE id_employee = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM Employee WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($fname, $lname, $email, $password, $role) {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Employee (first_name, last_name, email, password, role) 
                VALUES (:fname, :lname, :email, :pass, :role)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
                    ':fname' => $fname,
                    ':lname' => $lname,
                    ':email' => $email,
                    ':pass' => $hashed_pass,
                    ':role' => $role
        ]);
    }

    public function update($id, $fname, $lname, $email, $role, $password = null) {

        if (!empty($password)) {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE Employee SET first_name = :fname, last_name = :lname, 
                    email = :email, role = :role, password = :pass WHERE id_employee = :id";
            $params = [':fname' => $fname, ':lname' => $lname, ':email' => $email, ':role' => $role, ':pass' => $hashed_pass, ':id' => $id];
        } else {
            $sql = "UPDATE Employee SET first_name = :fname, last_name = :lname, 
                    email = :email, role = :role WHERE id_employee = :id";
            $params = [':fname' => $fname, ':lname' => $lname, ':email' => $email, ':role' => $role, ':id' => $id];
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM Employee WHERE id_employee = :id");
        return $stmt->execute([':id' => $id]);
    }


    public function getTotalCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM Employee");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getPaginated($limit, $offset) {
        $sql = "SELECT * FROM Employee ORDER BY id_employee ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>