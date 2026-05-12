<!-- WALAY MANGHILABOT ANI KAY MAO NI ANG GA ADD UG TEST VALUES SA ATONG DATABASE -->

<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "trackit_db"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die();
}

// ──────────────────────────────────────────────
// CREATE USERS TABLE
// ──────────────────────────────────────────────
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
)");

// ──────────────────────────────────────────────
// CREATE ASSETS TABLE
// ──────────────────────────────────────────────
$conn->query("CREATE TABLE IF NOT EXISTS assets (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    asset_name VARCHAR(50) NOT NULL,
    type ENUM('cpu', 'gpu', 'motherboard', 'ram', 'storage', 'psu', 'peripheral') NOT NULL,
    status ENUM('available', 'borrowed', 'under_maintenance') NOT NULL DEFAULT 'available'
)");

// ──────────────────────────────────────────────
// CREATE ACTIVITY LOGS TABLE
// ──────────────────────────────────────────────
$conn->query("CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `user_id`     INT          NOT NULL,
    `username`    VARCHAR(100) NOT NULL,
    `action`      ENUM('added','deleted','modified') NOT NULL,
    `table_name`  VARCHAR(100) NOT NULL,
    `record_id`   INT          DEFAULT NULL,
    `details`     TEXT         DEFAULT NULL,
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
)");

// ──────────────────────────────────────────────
// SEED USERS (kung wala pa)
// ──────────────────────────────────────────────
$userCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

if ($userCount == 0) {
    $users = [
        ["admin", password_hash("sudo", PASSWORD_DEFAULT), "admin"],
        ["sinoy", password_hash("mark", PASSWORD_DEFAULT), "user"],
    ];

    $userStmt = $conn->prepare("INSERT IGNORE INTO users (username, password, role) VALUES (?, ?, ?)");
    foreach ($users as [$username, $hashed, $role]) {
        $userStmt->bind_param("sss", $username, $hashed, $role);
        $userStmt->execute();
    }
    $userStmt->close();
}

// ──────────────────────────────────────────────
// SEED ASSETS (kung wala pa)
// ──────────────────────────────────────────────
$assetCount = $conn->query("SELECT COUNT(*) as count FROM assets")->fetch_assoc()['count'];

if ($assetCount == 0) {
    $assets = [
        ["Ryzen 5 9600X",            "cpu",          "borrowed"],
        ["MSI B550M PRO VDH-WIFI",   "motherboard",  "available"],
        ["RTX 5060 Ti",              "gpu",          "under_maintenance"],
        ["RX 9070 XT",               "gpu",          "available"],
        ["Ryzen 5 5600",             "cpu",          "available"],
        ["Gigabyte P650G",           "psu",          "borrowed"],
        ["Crucial P3 Plus",          "storage",      "under_maintenance"],
        ["Keychron K2 HE",           "peripheral",   "borrowed"],
    ];

    $assetStmt = $conn->prepare("INSERT INTO assets (asset_name, type, status) VALUES (?, ?, ?)");
    foreach ($assets as [$name, $type, $status]) {
        $assetStmt->bind_param("sss", $name, $type, $status);
        $assetStmt->execute();
    }
    $assetStmt->close();
}

// ──────────────────────────────────────────────
// SEED ACTIVITY LOGS (kung wala pa)
// ──────────────────────────────────────────────
$logCount = $conn->query("SELECT COUNT(*) as count FROM activity_logs")->fetch_assoc()['count'];

if ($logCount == 0) {
    // Kuhaon ang user IDs sa mga gi-seed nga users
    $adminId = $conn->query("SELECT id FROM users WHERE username = 'admin'")->fetch_assoc()['id'];
    $sinoyId = $conn->query("SELECT id FROM users WHERE username = 'sinoy'")->fetch_assoc()['id'];

    $logs = [
        [$adminId, "admin", "added",    "assets", 1, "Added Ryzen 5 9600X (cpu)"],
        [$sinoyId, "sinoy", "modified", "assets", 3, "Changed RTX 5060 Ti status to under_maintenance"],
        [$adminId, "admin", "deleted",  "assets", 5, "Removed Ryzen 5 5600 from inventory"],
    ];

    $logStmt = $conn->prepare(
        "INSERT INTO activity_logs (user_id, username, action, table_name, record_id, details)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    foreach ($logs as [$uid, $uname, $action, $table, $recordId, $details]) {
        $logStmt->bind_param("isssis", $uid, $uname, $action, $table, $recordId, $details);
        $logStmt->execute();
    }
    $logStmt->close();
}

$conn->close();
?>