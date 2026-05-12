<?php
session_start();
require_once '../component/db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($account && password_verify($password, $account['password'])) {

        if ($account['role'] === 'user') {
            $error = "Access denied. This user is not an admin.";
        } else {
            $_SESSION['user_id']  = $account['id'];
            $_SESSION['username'] = $account['username'];
            $_SESSION['role']     = $account['role'];

            header("Location: /TrackIT/src/php/REGISTER/reg_user.php");
            exit();
        }

    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/TrackIT/src/css/enter_cred.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>TrackIT Login</title>
</head>
<body>
    <div class="login-wrapper">
    
    <img src="/TrackIT/src/image/TrackIT_logo.png" id="sinoy-logo" alt="hello">
    <form action="reg_verify_admin.php" method="POST">
        <h1>Login (Admin)</h1>  
        <div class="input-box">
            <input type="text" name="username" placeholder="Username" required>
            <i class='bx bxs-user'></i>
        </div>

        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
            <i class='bx bxs-lock' ></i>
        </div>

        <?php if (isset($error)): ?>
            <p style="color: red; font-size: 14px; text-align: center;"><?= $error ?></p>
        <?php endif; ?><br>
        <button type="submit" class="submit-btn">Login</button>

        <div class="no-account">
            <a href="/TrackIT/src/php/LOGIN/enter_cred.php">Go Back</a>
        </div>
    </form>
 
    </div>
 
</body>
</html>
