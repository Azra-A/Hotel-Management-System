<?php
include '../config/config.php';

try {
    // Independent
    // Employee
    $sql = "CREATE TABLE IF NOT EXISTS Employee(
        id_employee INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(10) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->exec($sql);
    echo "Table Employee created.<br>";

    // Client
    $sql = "CREATE TABLE IF NOT EXISTS Client(
        id_client INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        phone VARCHAR(20) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->exec($sql);
    echo "Table Client created.<br>";

    // Room_type
    $sql = "CREATE TABLE IF NOT EXISTS Room_type(
        id_type INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        capacity INT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->exec($sql);
    echo "Table Room_type created.<br>";

    // Dependent
    // Rate
    $sql = "CREATE TABLE IF NOT EXISTS Rate(
        id_rate INT AUTO_INCREMENT PRIMARY KEY,
        id_type INT NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        price_per_night DECIMAL(10,2) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->exec($sql);
    echo "Table Rate created.<br>";

    // Room
    $sql = "CREATE TABLE IF NOT EXISTS Room(
        id_room INT AUTO_INCREMENT PRIMARY KEY,
        id_type INT NOT NULL,
        number VARCHAR(10) NOT NULL UNIQUE,
        floor INT NOT NULL,
        status VARCHAR(20) DEFAULT 'available'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->exec($sql);
    echo "Table Room created.<br>";

    // Reservation
    $sql = "CREATE TABLE IF NOT EXISTS Reservation(
        id_reservation INT AUTO_INCREMENT PRIMARY KEY,
        id_room INT NOT NULL,
        id_client INT NOT NULL,
        id_employee INT NOT NULL,
        check_in DATE NOT NULL,
        check_out DATE NOT NULL,
        status VARCHAR(20) DEFAULT 'confirmed'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->exec($sql);
    echo "Table Reservation created.<br>";

    // Invoice
    $sql = "CREATE TABLE IF NOT EXISTS Invoice(
        id_invoice INT AUTO_INCREMENT PRIMARY KEY,
        id_reservation INT NOT NULL,
        issued_on DATE NOT NULL,
        total DECIMAL(10,2) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->exec($sql);
    echo "Table Invoice created.<br>";

    // Payment
    $sql = "CREATE TABLE IF NOT EXISTS Payment(
        id_payment INT AUTO_INCREMENT PRIMARY KEY,
        id_invoice INT NOT NULL,
        paid_on DATE NOT NULL,
        amount DECIMAL(10,2) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->exec($sql);
    echo "Table Payment created.<br>";

    // Audit_log
    $sql = "CREATE TABLE IF NOT EXISTS Audit_log(
        id_log INT AUTO_INCREMENT PRIMARY KEY,
        id_employee INT NOT NULL,
        action VARCHAR(50) NOT NULL,
        table_name VARCHAR(50) NOT NULL,
        record_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->exec($sql);
    echo "Table Audit_log created.<br>";

    // Foreign keys
    echo "<br>Applying Foreign Keys...<br>";

    $fks = [
        "ALTER TABLE Rate ADD CONSTRAINT FK_Rate_Type FOREIGN KEY(id_type) REFERENCES Room_type(id_type) ON DELETE CASCADE",
        "ALTER TABLE Room ADD CONSTRAINT FK_Room_Type FOREIGN KEY(id_type) REFERENCES Room_type(id_type) ON DELETE CASCADE",
        "ALTER TABLE Reservation ADD CONSTRAINT FK_Res_Room FOREIGN KEY(id_room) REFERENCES Room(id_room)",
        "ALTER TABLE Reservation ADD CONSTRAINT FK_Res_Client+ FOREIGN KEY(id_client) REFERENCES Client(id_client)",
        "ALTER TABLE Reservation ADD CONSTRAINT FK_Res_Employee FOREIGN KEY(id_employee) REFERENCES Employee(id_employee)",
        "ALTER TABLE Invoice ADD CONSTRAINT FK_Inv_Res FOREIGN KEY(id_reservation) REFERENCES Reservation(id_reservation) ON DELETE CASCADE",
        "ALTER TABLE Payment ADD CONSTRAINT FK_Pay_Inv FOREIGN KEY(id_invoice) REFERENCES Invoice(id_invoice) ON DELETE CASCADE",
        "ALTER TABLE audit_logs ADD CONSTRAINT FK_Audit_Emp FOREIGN KEY(id_employee) REFERENCES Employee(id_employee)"
    ];

    foreach ($fks as $sql) {
        try {
            $conn->exec($sql);
            echo "<span>Foreign Key added successfully.</span><br>";
        } catch (PDOException $e) {
            echo "<span>Foreign Key exists or skipped.</span><br>";
        }
    }

    echo "<h3>Success! Database structure is ready.</h3>";
} catch (PDOException $e) {
    echo "<h3>Error: " . $e->getMessage() . "</h3>";
}
?>