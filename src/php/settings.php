<?php
session_start();
require_once '../php/component/db_connect.php';

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: /TrackIT/src/php/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TrackIT | Settings</title>
    <style>
        body { background: #0c0b1d; color: white; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #161530; padding: 40px; border-radius: 12px; border: 1px solid #2e2c55; text-align: center; }
        .logout-btn { background: #ff4d4d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Settings</h2>
        <form method="POST">
            <button type="submit" name="logout" class="logout-btn">Log Out</button>
        </form>
        <br>
        <a href="users.php" style="color: gray; text-decoration: none;">← Back</a>
    </div>
</body>
</html>