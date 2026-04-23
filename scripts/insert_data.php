<?php
include '../config/config.php';

try {
    // Room type
    $sql = "INSERT INTO Room_type (name, capacity) VALUES (:name, :capacity)";
    $stmt = $conn->prepare($sql);

    $room_types = [
        [':name' => 'Single Room', ':capacity' => 1],
        [':name' => 'Double Room', ':capacity' => 2],
        [':name' => 'Triple Room', ':capacity' => 3],
        [':name' => 'Quad Room', ':capacity' => 4],
        [':name' => 'Apartment', ':capacity' => 4]
    ];

    echo "Inserting Room Types... ";
    foreach ($room_types as $row) {
        $stmt->execute($row);
    }
    echo "<span>Done!</span><br>";

    // Rate
    $sql = "INSERT INTO Rate (id_type, start_date, end_date, price_per_night) 
            VALUES (:id_type, :start_date, :end_date, :price)";
    $stmt = $conn->prepare($sql);

    $rates = [
        [':id_type' => 1, ':start_date' => '2025-01-01', ':end_date' => '2025-12-31', ':price' => 100.00],
        [':id_type' => 2, ':start_date' => '2025-01-01', ':end_date' => '2025-12-31', ':price' => 150.00],
        [':id_type' => 3, ':start_date' => '2025-01-01', ':end_date' => '2025-12-31', ':price' => 180.00],
        [':id_type' => 4, ':start_date' => '2025-01-01', ':end_date' => '2025-12-31', ':price' => 200.00],
        [':id_type' => 5, ':start_date' => '2025-01-01', ':end_date' => '2025-12-31', ':price' => 250.00]
    ];

    echo "Inserting Rates... ";
    foreach ($rates as $row) {
        $stmt->execute($row);
    }
    echo "<span>Done!</span><br>";

    // Room
    $sql = "INSERT INTO Room (id_type, number, floor, status) 
            VALUES (:id_type, :number, :floor, :status)";
    $stmt = $conn->prepare($sql);

    $rooms = [
        [':id_type' => 1, ':number' => '101', ':floor' => 1, ':status' => 'available'],
        [':id_type' => 2, ':number' => '102', ':floor' => 1, ':status' => 'available'],
        [':id_type' => 3, ':number' => '103', ':floor' => 1, ':status' => 'available'],
        [':id_type' => 4, ':number' => '104', ':floor' => 1, ':status' => 'available'],
        [':id_type' => 5, ':number' => '105', ':floor' => 1, ':status' => 'available'],
        [':id_type' => 1, ':number' => '201', ':floor' => 2, ':status' => 'available'],
        [':id_type' => 2, ':number' => '202', ':floor' => 2, ':status' => 'available'],
        [':id_type' => 3, ':number' => '203', ':floor' => 2, ':status' => 'available'],
        [':id_type' => 4, ':number' => '204', ':floor' => 2, ':status' => 'available'],
        [':id_type' => 2, ':number' => '205', ':floor' => 2, ':status' => 'available']
    ];

    echo "Inserting Rooms... ";
    foreach ($rooms as $row) {
        $stmt->execute($row);
    }
    echo "<span>Done!</span><br>";

    // Client
    $sql = "INSERT INTO Client (first_name, last_name, email, phone) 
            VALUES (:fname, :lname, :email, :phone)";
    $stmt = $conn->prepare($sql);

    $clients = [
        [':fname' => 'Petur', ':lname' => 'Velikov', ':email' => 'petur@abv.bg', ':phone' => '+3590876789807'],
        [':fname' => 'Iskra', ':lname' => 'Angelova', ':email' => 'iskra@mail.bg', ':phone' => '+3598786779852'],
        [':fname' => 'Radoslav', ':lname' => 'Ignatov', ':email' => 'rado@abv.bg', ':phone' => '+3597870277833'],
        [':fname' => 'Petya', ':lname' => 'Petrova', ':email' => 'petya@gmail.com', ':phone' => '+3598765437254'],
        [':fname' => 'Elica', ':lname' => 'Ilarionova', ':email' => 'elica@yahoo.com', ':phone' => '+3598976789834'],
        [':fname' => 'Dimitur', ':lname' => 'Todorov', ':email' => 'mitko@abv.bg', ':phone' => '+3598778980912'],
        [':fname' => 'Elena', ':lname' => 'Georgieva', ':email' => 'elena@mail.bg', ':phone' => '+3598983334566'],
        [':fname' => 'Georgi', ':lname' => 'Kolev', ':email' => 'gosho@gmail.com', ':phone' => '+35987909708453'],
        [':fname' => 'Zhorzheta', ':lname' => 'Iksreva', ':email' => 'zhorzhi@abv.bg', ':phone' => '+3598800789075'],
        [':fname' => 'Nevena', ':lname' => 'Todorova', ':email' => 'nevena@abv.bg', ':phone' => '+3598704328916']
    ];

    echo "Inserting Clients... ";
    foreach ($clients as $row) {
        $stmt->execute($row);
    }
    echo "<span>Done!</span><br>";

    // Employee
    $sql = "INSERT INTO Employee (first_name, last_name, email, password, role) 
            VALUES (:fname, :lname, :email, :pass, :role)";
    $stmt = $conn->prepare($sql);

    $hashed_pass = password_hash('123456', PASSWORD_DEFAULT);

    $employees = [
        [':fname' => 'Azra', ':lname' => 'Ahmedova', ':email' => 'azra@hotel.com', ':pass' => $hashed_pass, ':role' => 'admin'],
        [':fname' => 'Stefka', ':lname' => 'Popova', ':email' => 'stefka@hotel.com', ':pass' => $hashed_pass, ':role' => 'reception'],
        [':fname' => 'Marina', ':lname' => 'Stefanova', ':email' => 'marina@hotel.com', ':pass' => $hashed_pass, ':role' => 'manager'],
        [':fname' => 'Hristo', ':lname' => 'Kostov', ':email' => 'hristo@hotel.com', ':pass' => $hashed_pass, ':role' => 'reception'],
        [':fname' => 'Aleks', ':lname' => 'Vasilev', ':email' => 'aleks@hotel.com', ':pass' => $hashed_pass, ':role' => 'reception']
    ];

    echo "Inserting Employees... ";
    foreach ($employees as $row) {
        $stmt->execute($row);
    }
    echo "<span>Done!</span><br>";

    // Reservation
    $sql = "INSERT INTO Reservation (id_room, id_client, id_employee, check_in, check_out, status) 
            VALUES (:id_room, :id_client, :id_employee, :in, :out, :status)";
    $stmt = $conn->prepare($sql);

    $reservations = [
        [':id_room' => 2, ':id_client' => 1, ':id_employee' => 1, ':in' => '2025-07-02', ':out' => '2025-07-05', ':status' => 'confirmed'],
        [':id_room' => 6, ':id_client' => 3, ':id_employee' => 5, ':in' => '2025-07-12', ':out' => '2025-07-16', ':status' => 'confirmed'],
        [':id_room' => 1, ':id_client' => 7, ':id_employee' => 2, ':in' => '2025-08-11', ':out' => '2025-08-15', ':status' => 'confirmed'],
        [':id_room' => 3, ':id_client' => 2, ':id_employee' => 4, ':in' => '2025-06-08', ':out' => '2025-06-12', ':status' => 'confirmed'],
        [':id_room' => 7, ':id_client' => 6, ':id_employee' => 2, ':in' => '2025-07-24', ':out' => '2025-07-28', ':status' => 'confirmed']
    ];

    echo "Inserting Reservations... ";
    foreach ($reservations as $row) {
        $stmt->execute($row);
    }
    echo "<span>Done!</span><br>";

    // Invoice
    $sql = "INSERT INTO Invoice (id_reservation, issued_on, total) 
            VALUES (:id_res, :date, :total)";
    $stmt = $conn->prepare($sql);

    $invoices = [
        [':id_res' => 1, ':date' => '2025-07-02', ':total' => 450.00],
        [':id_res' => 2, ':date' => '2025-07-12', ':total' => 400.00]
    ];

    echo "Inserting Invoices... ";
    foreach ($invoices as $row) {
        $stmt->execute($row);
    }
    echo "<span>Done!</span><br>";

    // Payment
    $sql = "INSERT INTO Payment (id_invoice, paid_on, amount) 
            VALUES (:id_inv, :date, :amount)";
    $stmt = $conn->prepare($sql);

    $payments = [
        [':id_inv' => 1, ':date' => '2025-07-02', ':amount' => 450.00],
        [':id_inv' => 2, ':date' => '2025-07-12', ':amount' => 400.00]
    ];

    echo "Inserting Payments... ";
    foreach ($payments as $row) {
        $stmt->execute($row);
    }
    echo "<span>Done!</span><br>";

    echo "<h2>SUCCESS: All data inserted!</h2>";
} catch (PDOException $e) {
    echo "<h3>Error: " . $e->getMessage() . "</h3>";
}
?>