<?php
session_start();
require_once '../config/config.php';
require_once '../app/models/EmployeeModel.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    Csrf::check();
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $empModel = new EmployeeModel($conn);

    $user = $empModel->getByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id_employee'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="css/login_style.css"> 
    </head>
    <body>

        <div class="login-container">
            <h2>Hotel Login</h2>

            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                
                <?= Csrf::field() ?>
                
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>

    </body>
</html>