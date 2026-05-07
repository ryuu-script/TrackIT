<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "trackit_db"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>");
}

$result = $conn->query("SELECT COUNT(*) as count FROM users"); // Para dili mag doble na kung naay sulod ang database
$row = $result->fetch_assoc();
if ($row['count'] > 0) {
    return;
}

echo "<h2>TrackIT Setup</h2>";

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
// SEED USERS
// ──────────────────────────────────────────────
$users = [
    ["admin", password_hash("sudo", PASSWORD_DEFAULT), "admin"],
    ["sinoy", password_hash("mark", PASSWORD_DEFAULT), "user"],
];

$userStmt = $conn->prepare("INSERT IGNORE INTO users (username, password, role) VALUES (?, ?, ?)");

echo "<h3>Users</h3><ul>";
foreach ($users as [$username, $hashed, $role]) {
    $userStmt->bind_param("sss", $username, $hashed, $role);
    if ($userStmt->execute()) {
        if ($userStmt->affected_rows > 0) {
            echo "<li>Added user: <strong>$username</strong> ($role)</li>";
        } else {
            echo "<li>Skipped: <strong>$username</strong> already exists</li>";
        }
    } else {
        echo "<li>Failed to add <strong>$username</strong>: " . $userStmt->error . "</li>";
    }
}
echo "</ul>";
$userStmt->close();

// ──────────────────────────────────────────────
// SEED ASSETS
// ──────────────────────────────────────────────
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

echo "<h3>Assets</h3><ul>";
foreach ($assets as [$name, $type, $status]) {
    $assetStmt->bind_param("sss", $name, $type, $status);
    if ($assetStmt->execute()) {
        echo "<li>Added: <strong>$name</strong> — $type / $status</li>";
    } else {
        echo "<li>Failed to add <strong>$name</strong>: " . $assetStmt->error . "</li>";
    }
}
echo "</ul>";
$assetStmt->close();

$conn->close();
echo "<p><strong>Setup complete!</strong> You can now delete this file.</p>";
?>