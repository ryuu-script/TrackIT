<?php
require_once 'component/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    try {
        $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $role, $id]);

        header("Location: users.php?message=updated");
        exit();
    } catch (PDOException $e) {
        die("Error updating user: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User | TrackIT</title>
    <link rel="stylesheet" href="/TrackIT/src/css/navbar.css">
    <style>
        body { background: #0f0c29; color: white; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .edit-container { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 30px; border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.2); width: 400px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: none; }
        button { width: 100%; padding: 10px; background: #6a11cb; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit User</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
            
            <label>Role:</label>
            <select name="role">
                <option value="user" <?php if($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="administrator" <?php if($user['role'] == 'administrator') echo 'selected'; ?>>Administrator</option>
            </select>
            
            <button type="submit">Update User</button>
            <a href="users.php" style="color: #aaa; text-decoration: none; display: block; text-align: center; margin-top: 15px;">Cancel</a>
        </form>
    </div>
</body>
</html>