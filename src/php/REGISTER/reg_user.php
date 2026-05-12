<?php
session_start();
require_once '../component/db_connect.php'; 

// Start coding from here
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /TrackIT/src/php/LOGIN/enter_cred.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
    $role     = $_POST['role'];

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->fetch()) {
        $error = "User already exists.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $insert = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $insert->execute([$username, $hashed, $role]);
        
        sleep(2);
        header("Location: /TrackIT/src/php/REGISTER/reg_success.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackIT | Registration</title>

    <!-- Start coding from here -->
    <link rel="stylesheet" href="/TrackIT/src/css/enter_cred.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Start coding from here -->
    <div class="login-wrapper">

        <img src="/TrackIT/src/image/TrackIT_logo.png" id="sinoy-logo" alt="TrackIT Logo">

        <form action="reg_user.php" method="POST">
            <h1>Register User</h1>

            <div class="input-box">
                <input type="text" name="username" placeholder="Username"
                       value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock'></i>
            </div>

            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="input-box">
                <select name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="user"  <?= (isset($role) && $role === 'user')  ? 'selected' : '' ?>>User</option>
                </select>
                <i class='bx bxs-id-card'></i>
            </div>

            <?php if (isset($error)): ?>
                <p style="color: red; font-size: 14px; text-align: center;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <br>
            <button type="submit" class="submit-btn">Register</button>

            <div class="no-account">
                <a href="/TrackIT/src/php/LOGIN/enter_cred.php">Cancel</a>
            </div>
        </form>

    </div>
</body>
</html> 