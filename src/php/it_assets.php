<?php
session_start();
require_once '../php/component/db_connect.php';
require_once '../php/component/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /TrackIT/src/php/enter_cred.php');
    exit();
}

$uid = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id, role FROM users WHERE id = ?");
$stmt->execute([$uid]);
$current_user = $stmt->fetch(PDO::FETCH_ASSOC);

$isAdmin = $current_user['role'] === 'admin';

/* =========================
   STATUS HELPERS
========================= */

function normalizeStatus($status) {
    $map = [
        'Available' => 'available',
        'Borrowed' => 'borrowed',
        'Unavailable' => 'unavailable',
        'Under Maintenance' => 'under_maintenance',

        // already-safe DB values
        'available' => 'available',
        'borrowed' => 'borrowed',
        'unavailable' => 'unavailable',
        'under_maintenance' => 'under_maintenance',
    ];

    $status = trim($status);

    return $map[$status] ?? 'available';
}

function prettyStatus($status) {
    return match ($status) {
        'available' => 'Available',
        'borrowed' => 'Borrowed',
        'unavailable' => 'Unavailable',
        'under_maintenance' => 'Under Maintenance',
        default => 'Available'
    };
}

/* =========================
   HANDLE CRUD ACTIONS
========================= */

if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // ADD
    if (isset($_POST['add_asset'])) {

        $name = trim($_POST['asset_name']);
        $type = trim($_POST['type']);
        $status = normalizeStatus($_POST['status'] ?? 'available');

        // CHECK DUPLICATE
        $check = $pdo->prepare("SELECT COUNT(*) FROM assets WHERE asset_name = ?");
        $check->execute([$name]);

        if ($check->fetchColumn() > 0) {
            echo "<script>alert('Asset name already exists!'); window.history.back();</script>";
            exit();
        }

        $stmt = $pdo->prepare("
            INSERT INTO assets (asset_name, type, status)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$name, $type, $status]);

        header("Location: it_assets.php");
        exit();
    }

    // EDIT
    if (isset($_POST['edit_asset'])) {

        $id = (int) $_POST['asset_id'];
        $name = trim($_POST['asset_name']);
        $type = trim($_POST['type']);
        $status = normalizeStatus($_POST['status'] ?? 'available');

        $allowed_status = [
            'available',
            'borrowed',
            'unavailable',
            'under_maintenance'
        ];

        if (!in_array($status, $allowed_status, true)) {
            $status = 'available';
        }

        $stmt = $pdo->prepare("
            UPDATE assets
            SET asset_name = ?, type = ?, status = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $type, $status, $id]);

        header("Location: it_assets.php");
        exit();
    }

    // DELETE
    if (isset($_POST['delete_asset'])) {

        $id = (int) $_POST['asset_id'];

        $stmt = $pdo->prepare("DELETE FROM assets WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: it_assets.php");
        exit();
    }
}

/* =========================
   FETCH ASSETS
========================= */

$assets = $pdo->query("
    SELECT id, asset_name, type, status
    FROM assets
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/TrackIT/src/css/navbar.css">
    <link rel="stylesheet" href="/TrackIT/src/css/it_assets.css">

    <title>TrackIT | IT Assets</title>
</head>

<body class="assets-page">

<?php if ($isAdmin): ?>
<form id="inline-edit-form" method="POST" style="display:none;">
    <input type="hidden" name="edit_asset" value="1">
    <input type="hidden" name="asset_id" id="inline_asset_id">
    <input type="hidden" name="asset_name" id="inline_asset_name">
    <input type="hidden" name="type" id="inline_type">
    <input type="hidden" name="status" id="inline_status">
</form>
<?php endif; ?>

<div class="assets-wrap">

    <div class="assets-header fi">
        <div>
            <h1>Track<span>IT</span> — IT Assets</h1>
            <p>Inventory &amp; resource management</p>
        </div>

        <div class="assets-badge">
            <span class="assets-dot <?= $isAdmin ? 'dot-admin' : 'dot-user' ?>"></span>
            <?= $isAdmin ? 'Administrator Mode' : 'User Mode' ?>
        </div>
    </div>

    <div class="assets-log fi">

        <div class="assets-log-header">
            <div class="assets-title">
                <i class='bx bx-desktop'></i> Asset List
            </div>

            <?php if ($isAdmin): ?>
                <button class="assets-add-btn" onclick="openAddModal()">
                    <i class='bx bx-plus'></i> Add Asset
                </button>
            <?php endif; ?>
        </div>

        <div class="assets-scroll">
            <table class="assets-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Asset Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <?php if ($isAdmin): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($assets as $asset): ?>

                    <?php
                        $status_raw = $asset['status'];
                        $status_class = str_replace('_', '-', $status_raw);
                    ?>

                    <tr
                        data-id="<?= $asset['id'] ?>"
                        data-name="<?= htmlspecialchars($asset['asset_name'], ENT_QUOTES) ?>"
                        data-type="<?= htmlspecialchars($asset['type'], ENT_QUOTES) ?>"
                        data-status="<?= htmlspecialchars($status_raw, ENT_QUOTES) ?>"
                    >

                        <td>#<?= $asset['id'] ?></td>

                        <td>
                            <span class="cell-display">
                                <?= htmlspecialchars($asset['asset_name']) ?>
                            </span>
                            <input class="cell-input input-name" type="text"
                                value="<?= htmlspecialchars($asset['asset_name'], ENT_QUOTES) ?>"
                                style="display:none;">
                        </td>

                        <td>
                            <span class="cell-display type-badge">
                                <?= htmlspecialchars($asset['type']) ?>
                            </span>
                            <select class="cell-input input-type" style="display:none;">
                                <option value="">— select —</option>
                                <option value="cpu">CPU</option>
                                <option value="gpu">GPU</option>
                                <option value="motherboard">Motherboard</option>
                                <option value="memory">Memory</option>
                                <option value="storage">Storage</option>
                                <option value="psu">PSU</option>
                                <option value="peripheral">Peripheral</option>
                            </select>
                        </td>

                        <td>
                            <span class="cell-display status-badge status-<?= $status_class ?>">
                                <?= prettyStatus($status_raw) ?>
                            </span>

                            <!-- IMPORTANT: DB VALUES ONLY -->
                            <select class="cell-input input-status" style="display:none;">
                                <option value="available">Available</option>
                                <option value="borrowed">Borrowed</option>
                                <option value="unavailable">Unavailable</option>
                                <option value="under_maintenance">Under Maintenance</option>
                            </select>
                        </td>

                        <?php if ($isAdmin): ?>
                        <td class="actions">

                            <button class="action-btn edit-btn" onclick="startEdit(this)">
                                <i class='bx bx-edit-alt'></i>
                            </button>

                            <button class="action-btn confirm-btn" onclick="confirmEdit(this)" style="display:none;">
                                <i class='bx bx-check'></i>
                            </button>

                            <span class="delete-btn-wrap">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="asset_id" value="<?= $asset['id'] ?>">
                                    <button type="submit" name="delete_asset" class="action-btn delete-btn"
                                        onclick="return confirm('Delete this asset?')">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </span>

                            <button class="action-btn cancel-btn" onclick="cancelEdit(this)" style="display:none;">
                                <i class='bx bx-x'></i>
                            </button>

                        </td>
                        <?php endif; ?>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    </div>
</div>

<?php if ($isAdmin): ?>
<div class="users-modal" id="addModal">
    <div class="users-modal-box">

        <div class="users-modal-title">Add Asset</div>

        <form method="POST">

            <div class="users-input-group">
                <label>Asset Name</label>
                <input type="text" name="asset_name" required>
            </div>

            <div class="users-input-group">
                <label>Type</label>
                <select name="type" required>
                    <option value="">— select —</option>
                    <option value="cpu">CPU</option>
                    <option value="gpu">GPU</option>
                    <option value="motherboard">Motherboard</option>
                    <option value="memory">Memory</option>
                    <option value="storage">Storage</option>
                    <option value="psu">PSU</option>
                    <option value="peripheral">Peripheral</option>
                </select>
            </div>

            <div class="users-input-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="available">Available</option>
                    <option value="borrowed">Borrowed</option>
                    <option value="unavailable">Unavailable</option>
                    <option value="under_maintenance">Under Maintenance</option>
                </select>
            </div>

            <div class="users-modal-actions">
                <button type="button" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" name="add_asset">Create</button>
            </div>

        </form>

    </div>
</div>
<?php endif; ?>

<script src="/TrackIT/src/js/it_assets.js"></script>

</body>
</html>