<?php
class AuditLogModel {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function log($id_employee, $action, $table_name, $record_id) {
        $sql = "INSERT INTO Audit_log (id_employee, action, table_name, record_id, created_at) 
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_employee, $action, $table_name, $record_id]);
    }

    public function getAll() {
        $sql = "SELECT Audit_log.*, 
                       Employee.first_name, Employee.last_name, Employee.email
                FROM Audit_log
                JOIN Employee ON Audit_log.id_employee = Employee.id_employee
                ORDER BY Audit_log.created_at DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getTotalCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) as count FROM Audit_log");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['count'] : 0;
    }

    public function getPaginated($limit, $offset) {
        $sql = "SELECT Audit_log.*, 
                       Employee.first_name, Employee.last_name, Employee.email
                FROM Audit_log
                LEFT JOIN Employee ON Audit_log.id_employee = Employee.id_employee
                ORDER BY Audit_log.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>