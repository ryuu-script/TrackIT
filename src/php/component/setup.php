<!-- WALAY MANGHILABOT ANI KAY MAO NI ANG GA ADD UG TEST VALUES SA DATABASE -->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "trackit_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* =========================
   USERS TABLE
========================= */
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
)") or die($conn->error);

/* =========================
   ASSETS TABLE (FIXED ENUMS)
========================= */
$conn->query("CREATE TABLE IF NOT EXISTS assets (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    asset_name VARCHAR(50) NOT NULL,
    type ENUM('cpu', 'gpu', 'motherboard', 'ram', 'storage', 'psu', 'peripheral') NOT NULL,
    status ENUM('available', 'borrowed', 'unavailable', 'under_maintenance') NOT NULL DEFAULT 'available'
)") or die($conn->error);

/* =========================
   ACTIVITY LOGS
========================= */
$conn->query("CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    action ENUM('added','deleted','modified') NOT NULL,
    table_name VARCHAR(100) NOT NULL,
    record_id INT DEFAULT NULL,
    details TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)") or die($conn->error);

/* =========================
   SEED USERS
========================= */
$userCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

if ($userCount == 0) {
    $userStmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");

    $users = [
        ["admin", password_hash("sudo", PASSWORD_DEFAULT), "admin"],
        ["sinoy", password_hash("mark", PASSWORD_DEFAULT), "user"],
    ];

    foreach ($users as [$username, $hashed, $role]) {
        $userStmt->bind_param("sss", $username, $hashed, $role);
        $userStmt->execute();
    }

    $userStmt->close();
}

/* =========================
   SEED ASSETS 
========================= */
$assetCount = $conn->query("SELECT COUNT(*) as count FROM assets")->fetch_assoc()['count'];

if ($assetCount == 0) {

    $assetStmt = $conn->prepare("
        INSERT INTO assets (asset_name, type, status)
        VALUES (?, ?, ?)
    ");

    $assets = [
        ["Ryzen 5 9600X", "cpu", "borrowed"],
        ["MSI B550M PRO VDH-WIFI", "motherboard", "available"],
        ["RTX 5060 Ti", "gpu", "under_maintenance"],
        ["RX 9070 XT", "gpu", "available"],
        ["Ryzen 5 5600", "cpu", "available"],
        ["Gigabyte P650G", "psu", "borrowed"],
        ["Crucial P3 Plus", "storage", "under_maintenance"],
        ["Keychron K2 HE", "peripheral", "borrowed"],
    ];

    foreach ($assets as [$name, $type, $status]) {
        $assetStmt->bind_param("sss", $name, $type, $status);

        if (!$assetStmt->execute()) {
            die("Insert failed: " . $assetStmt->error);
        }
    }

    $assetStmt->close();
}

/* =========================
   SEED ACTIVITY LOGS
========================= */
$logCount = $conn->query("SELECT COUNT(*) as count FROM activity_logs")->fetch_assoc()['count'];

if ($logCount == 0) {

    $adminId = $conn->query("SELECT id FROM users WHERE username = 'admin'")->fetch_assoc()['id'];
    $sinoyId = $conn->query("SELECT id FROM users WHERE username = 'sinoy'")->fetch_assoc()['id'];

    $logStmt = $conn->prepare("
        INSERT INTO activity_logs (user_id, username, action, table_name, record_id, details)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $logs = [
        [$adminId, "admin", "added", "assets", 1, "Added Ryzen 5 9600X (cpu)"],
        [$sinoyId, "sinoy", "modified", "assets", 3, "Changed RTX 5060 Ti status to under_maintenance"],
        [$adminId, "admin", "deleted", "assets", 5, "Removed Ryzen 5 5600 from inventory"],
    ];

    foreach ($logs as [$uid, $uname, $action, $table, $recordId, $details]) {
        $logStmt->bind_param("isssis", $uid, $uname, $action, $table, $recordId, $details);

        if (!$logStmt->execute()) {
            die("Log insert failed: " . $logStmt->error);
        }
    }

    $logStmt->close();
}

$conn->close();
?>