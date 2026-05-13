<?php
session_start();
require_once '../php/component/db_connect.php';
require_once '../php/component/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /TrackIT/src/login.php');
    exit();
}

$uid = (int) $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE id = ?");
$stmt->execute([$uid]);
$current_user = $stmt->fetch(PDO::FETCH_ASSOC);
$isAdmin = $current_user['role'] === 'admin';

// HANDLE ADMIN ACTIONS
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_user'])) {

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // CHECK FIRST (prevents unnecessary insert attempt)
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$username]);

    if ($check->fetch()) {
        $_SESSION['error'] = "Username already exists.";
        header('Location: users.php');
        exit();
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO users (username, password, role)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([$username, $password, $role]);

        $_SESSION['success'] = "User created successfully.";

    } catch (PDOException $e) {
        // fallback safety (in case race condition happens)
        $_SESSION['error'] = "Username already exists.";
    }

    header('Location: users.php');
    exit();
}

    if (isset($_POST['delete_user'])) {

        $deleteId = (int) $_POST['delete_id'];

        if ($deleteId !== $uid) {

            $stmt = $pdo->prepare("
                DELETE FROM users
                WHERE id = ?
            ");

            $stmt->execute([$deleteId]);
        }

        header('Location: users.php');
        exit();
    }

    if (isset($_POST['edit_user'])) {

        $editId = (int) $_POST['edit_id'];
        $username = trim($_POST['edit_username']);
        $role = $_POST['edit_role'];

        if (!empty($_POST['edit_password'])) {

            $password = password_hash($_POST['edit_password'], PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                UPDATE users
                SET username = ?, password = ?, role = ?
                WHERE id = ?
            ");

            $stmt->execute([$username, $password, $role, $editId]);

        } else {

            $stmt = $pdo->prepare("
                UPDATE users
                SET username = ?, role = ?
                WHERE id = ?
            ");

            $stmt->execute([$username, $role, $editId]);
        }

        header('Location: users.php');
        exit();
    }
}

$users = $pdo->query("
    SELECT id, username, role
    FROM users
    ORDER BY role DESC, username ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/TrackIT/src/css/navbar.css">
    <link rel="stylesheet" href="/TrackIT/src/css/users.css">

    <title>TrackIT | Users</title>

</head>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color:red;">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </p>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <p style="color:green;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </p>
<?php endif; ?>

<body>

    <div class="users-wrap">

        <!-- HEADER -->
        <div class="users-page-header users-fi">

            <div>
                <h1>Track<span>IT</span> — Users</h1>
                <p>User management & access control</p>
            </div>

            <div class="users-header-badge">
                <span class="users-dot"></span>

                <?= $isAdmin ? 'Administrator Mode' : 'User Mode' ?>
            </div>

        </div>

        <!-- TOOLBAR -->
        <div class="users-toolbar users-fi">

            <div class="users-search-box">
                <i class='bx bx-search'></i>

                <input
                    type="text"
                    id="userSearch"
                    placeholder="Search users..."
                >
            </div>

            <div class="users-select-wrapper">

                <select id="roleFilter">

                    <option value="all">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>

                </select>

                <i class="fas fa-angle-down"></i>

            </div>

        </div>

        <!-- USERS GRID -->
        <div class="users-grid users-fi" id="usersGrid">

            <?php foreach ($users as $user): ?>

                <div
                    class="users-card"
                    data-username="<?= htmlspecialchars(strtolower($user['username'])) ?>"
                    data-role="<?= $user['role'] ?>"
                >

                    <!-- CARD TOP -->
                    <div class="users-top">

                        <div class="users-avatar">
                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                        </div>

                        <div>

                            <div class="users-name">
                                <?= htmlspecialchars($user['username']) ?>
                            </div>

                            <span class="users-role-pill users-role-<?= $user['role'] ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>

                        </div>

                    </div>

                    <!-- META -->
                    <div class="users-meta">

                        <div class="users-meta-row">
                            <span>ID</span>
                            <strong>#<?= $user['id'] ?></strong>
                        </div>

                        <div class="users-meta-row">
                            <span>Access</span>

                            <strong>
                                <?= $user['role'] === 'admin'
                                    ? 'Full Access'
                                    : 'Limited Access'
                                ?>
                            </strong>
                        </div>

                    </div>

                    <!-- ACTIONS -->
                    <?php if ($isAdmin): ?>

                        <div class="users-actions">

                            <button
                                class="users-edit-btn"
                                onclick="openEditModal(
                                    <?= $user['id'] ?>,
                                    '<?= htmlspecialchars($user['username']) ?>',
                                    '<?= $user['role'] ?>'
                                )"
                            >
                                Edit
                            </button>

                            <?php if ($user['id'] != $uid): ?>

                                <form
                                    method="POST"
                                    style="display:inline;"
                                >

                                    <input
                                        type="hidden"
                                        name="delete_id"
                                        value="<?= $user['id'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="delete_user"
                                        class="users-delete-btn"
                                        onclick="return confirm('Delete user?')"
                                    >
                                        Delete
                                    </button>

                                </form>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            <!-- ADD USER CARD -->
            <?php if ($isAdmin): ?>

                <div class="users-add-card open-add-btn">
                    <i class='bx bx-plus'></i>
                </div>

            <?php endif; ?>

        </div>

    </div>

    <!-- MODALS -->
    <?php if ($isAdmin): ?>

        <!-- ADD MODAL -->
        <div class="users-modal" id="addModal">

            <div class="users-modal-box">

                <div class="users-modal-title">
                    Add User
                </div>

                <form method="POST">

                    <div class="users-input-group">

                        <label>Username</label>

                        <input
                            type="text"
                            name="username"
                            required
                        >

                    </div>

                    <div class="users-input-group">

                        <label>Password</label>

                        <input
                            type="password"
                            name="password"
                            required
                        >

                    </div>

                    <div class="users-input-group">

                        <label>Role</label>

                        <div class="users-select-wrapper">

                            <select name="role">

                                <option value="user">User</option>
                                <option value="admin">Admin</option>

                            </select>

                            <i class="fas fa-angle-down"></i>

                        </div>

                    </div>

                    <div class="users-modal-actions">

                        <button
                            type="button"
                            class="users-cancel-btn"
                            onclick="closeModal('addModal')"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            name="add_user"
                            class="users-save-btn"
                            id="createUserBtn"
                        >
                            Create User
                        </button>

                    </div>

                </form>

            </div>

        </div>

        <!-- EDIT MODAL -->
        <div class="users-modal" id="editModal">

            <div class="users-modal-box">

                <div class="users-modal-title">
                    Edit User
                </div>

                <form method="POST">

                    <input
                        type="hidden"
                        name="edit_id"
                        id="edit_id"
                    >

                    <div class="users-input-group">

                        <label>Username</label>

                        <input
                            type="text"
                            name="edit_username"
                            id="edit_username"
                            required
                        >

                    </div>

                    <div class="users-input-group">

                        <label>
                            New Password (Leave blank to keep current)
                        </label>

                        <input
                            type="password"
                            name="edit_password"
                        >

                    </div>

                    <div class="users-input-group">

                        <label>Role</label>

                        <div class="users-select-wrapper">

                            <select
                                name="edit_role"
                                id="edit_role"
                            >

                                <option value="user">User</option>
                                <option value="admin">Admin</option>

                            </select>

                            <i class="fas fa-angle-down"></i>

                        </div>

                    </div>

                    <div class="users-modal-actions">

                        <button
                            type="button"
                            class="users-cancel-btn"
                            onclick="closeModal('editModal')"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            name="edit_user"
                            class="users-save-btn"
                        >
                            Save Changes
                        </button>

                    </div>

                </form>

            </div>

        </div>

    <?php endif; ?>

    <script src="/TrackIT/src/js/users.js"></script>

</body>

<?php require_once __DIR__ . '/component/footer.php'; ?>

</html>
