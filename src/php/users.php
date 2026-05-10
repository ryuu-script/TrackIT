<?php
session_start();
require_once '../php/component/db_connect.php'; 
require_once '../php/component/navbar.php';

// Start coding from here

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/TrackIT/src/css/navbar.css">

    <title>TrackIT | Users</title>

    <!-- Start coding from here -->
     <style>
    .btn-edit {
        background-color: #6a11cb;
        color: white;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 4px;
        margin-right: 5px;
        font-size: 14px;
        display: inline-block;
    }

    .btn-delete {
        background-color: #ff4b2b;
        color: white;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        display: inline-block;
    }

    .btn-edit:hover { background-color: #2575fc; }
    .btn-delete:hover { background-color: #ff416c; }
</style>
</head>
<body>
    <div class="container">
    <h2>User Management</h2>
    
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search by username...">
        <select id="roleFilter">
            <option value="">All Roles</option>
            <option value="administrator">Administrator</option>
            <option value="user">User</option>
        </select>
    </div>
</div>

        <div class="add-user-form glass-card p-3 mb-4">
            <h4 class="text-light">Add New User</h4>
            <form action="add_user.php" method="POST" class="d-flex gap-2">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <select name="role" class="form-select">
                    <option value="user">User</option>
                    <option value="administrator">Administrator</option>
                </select>
                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>
        <table>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <?php
            $sql = "SELECT id, username, role FROM users";
            $stmt = $pdo->query($sql);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["username"] . "</td>
                        <td>" . $row["role"] . "</td>
                        <td>
                            <a href='edit_user.php?id=" . $row["id"] . "' class='btn-edit'>Edit</a>
                            <a href='delete_user.php?id=" . $row["id"] . "' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                        </td>
                      </tr>";
            }
            ?>
            </tbody>
    </table>
</div>
</body>
</html>