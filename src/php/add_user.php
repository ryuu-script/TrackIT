<?php
require_once 'component/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username']; 
    $pass = $_POST['password'];
    $role = $_POST['role'];

    try {
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user, $pass, $role]);

        header("Location: users.php");
        exit();
    } catch (PDOException $e) {
        die("Error adding user: " . $e->getMessage());
    }
}
?>