<?php
session_start();
require_once '../php/component/db_connect.php';
require_once '../php/component/navbar.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /TrackIT/src/login.php');
    exit();
}

// ── Current user ─────────────────────────────────────────────────────────────
$uid  = (int) $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE id = ?");
$stmt->execute([$uid]);
$current_user = $stmt->fetch(PDO::FETCH_ASSOC);

// ── Stats ─────────────────────────────────────────────────────────────────────
$total_users        = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_assets       = (int) $pdo->query("SELECT COUNT(*) FROM assets")->fetchColumn();
$available_assets   = (int) $pdo->query("SELECT COUNT(*) FROM assets WHERE status='available'")->fetchColumn();
$borrowed_assets    = (int) $pdo->query("SELECT COUNT(*) FROM assets WHERE status='borrowed'")->fetchColumn();
$maintenance_assets = (int) $pdo->query("SELECT COUNT(*) FROM assets WHERE status='under_maintenance'")->fetchColumn();

// ── Assets by type ────────────────────────────────────────────────────────────
$assets_by_type = $pdo->query("SELECT type, COUNT(*) c FROM assets GROUP BY type ORDER BY c DESC")
                       ->fetchAll(PDO::FETCH_ASSOC);

// ── Activity logs ─────────────────────────────────────────────────────────────
$logs = $pdo->query(
    "SELECT id, username, action, table_name, record_id, details, created_at
     FROM activity_logs
     ORDER BY created_at DESC
     LIMIT 60"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <link rel="stylesheet" href="/TrackIT/src/css/navbar.css">
    <link rel="stylesheet" href="/TrackIT/src/css/dashboard.css">

    <title>TrackIT | Dashboard</title>
</head>
<body>

<div class="dash-wrap">

    <!-- ── Page Header ─────────────────────────────────────────── -->
    <div class="page-header fi">
        <div>
            <h1>Track<span>IT</span> — Dashboard</h1>
            <p>System overview &amp; activity feed</p>
        </div>
        <div class="header-badge">
            <span class="dot"></span>
            System online &nbsp;·&nbsp; <?= date('D, d M Y  H:i') ?>
        </div>
    </div>

    <!-- ── Stat Cards ──────────────────────────────────────────── -->
    <div class="section-label fi">Overview</div>
    <div class="stats-grid fi">

        <div class="stat-card">
            <div class="stat-icon"><i class='bx bx-server'></i></div>
            <div class="stat-num"><?= $total_assets ?></div>
            <div class="stat-label">Total Assets</div>
        </div>

        <div class="stat-card s-added">
            <div class="stat-icon"><i class='bx bx-check-circle'></i></div>
            <div class="stat-num"><?= $available_assets ?></div>
            <div class="stat-label">Available</div>
        </div>

        <div class="stat-card s-modified">
            <div class="stat-icon"><i class='bx bx-transfer-alt'></i></div>
            <div class="stat-num"><?= $borrowed_assets ?></div>
            <div class="stat-label">Borrowed</div>
        </div>

        <div class="stat-card s-deleted">
            <div class="stat-icon"><i class='bx bx-wrench'></i></div>
            <div class="stat-num"><?= $maintenance_assets ?></div>
            <div class="stat-label">Maintenance</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class='bx bx-group'></i></div>
            <div class="stat-num"><?= $total_users ?></div>
            <div class="stat-label">Total Users</div>
        </div>

    </div>

    <!-- ── Mid Row ─────────────────────────────────────────────── -->
    <div class="section-label fi">Details</div>
    <div class="mid-row fi">

        <!-- Current User -->
        <div class="panel">
            <div class="panel-title"><i class='bx bx-user-circle'></i> Current Session</div>
            <div class="user-avatar">
                <?= strtoupper(substr($current_user['username'], 0, 1)) ?>
            </div>
            <div class="user-name"><?= htmlspecialchars($current_user['username']) ?></div>
            <span class="role-pill role-<?= $current_user['role'] ?>">
                <i class='bx bx-shield-quarter'></i>
                <?= ucfirst($current_user['role']) ?>
            </span>
            <div class="user-meta">
                <div class="meta-row">
                    <i class='bx bx-id-card'></i>
                    <span>User ID</span>
                    <strong>#<?= $current_user['id'] ?></strong>
                </div>
                <div class="meta-row">
                    <i class='bx bx-lock-alt'></i>
                    <span>Access</span>
                    <strong><?= $current_user['role'] === 'admin' ? 'Full Access' : 'Limited' ?></strong>
                </div>
                <div class="meta-row">
                    <i class='bx bx-time'></i>
                    <span>Session Start</span>
                    <strong><?= date('H:i') ?></strong>
                </div>
                <div class="meta-row">
                    <i class='bx bx-calendar'></i>
                    <span>Date</span>
                    <strong><?= date('d M Y') ?></strong>
                </div>
            </div>
        </div>

        <!-- Assets by type -->
        <div class="panel">
            <div class="panel-title">
                <i class='bx bx-pie-chart-alt-2'></i>
                Asset Distribution
            </div>

            <?php if (empty($assets_by_type)): ?>

                <p style="color:var(--purple-dim);font-family:var(--rajdhani);letter-spacing:1px;">
                    No assets found.
                </p>

            <?php else: ?>

                <?php
                    $total = array_sum(array_column($assets_by_type, 'c'));

                    $colors = [
                        '#ca9ee6',
                        '#a6d189',
                        '#85c1dc',
                        '#ef9f76',
                        '#e5c890',
                        '#e78284',
                        '#8caaee',
                        '#f4b8e4'
                    ];

                    $gradientParts = [];
                    $legendData = [];

                    $start = 0;

                    foreach ($assets_by_type as $index => $t) {

                        $percentage = ($t['c'] / $total) * 100;
                        $end = $start + $percentage;

                        $color = $colors[$index % count($colors)];

                        $gradientParts[] = "{$color} {$start}% {$end}%";

                        $legendData[] = [
                            'type' => $t['type'],
                            'count' => $t['c'],
                            'color' => $color
                        ];

                        $start = $end;
                    }

                    $gradient = implode(', ', $gradientParts);
                ?>

                <div class="asset-chart-wrap">

                    <div class="pie-chart"
                        style="background: conic-gradient(<?= $gradient ?>);">

                        <div class="pie-center">
                            <strong><?= $total ?></strong>
                            <span>Assets</span>
                        </div>

                    </div>

                    <div class="chart-legend">

                        <?php foreach ($legendData as $item): ?>

                            <div class="legend-row">

                                <div class="legend-left">

                                    <span class="legend-color"
                                        style="background:<?= $item['color'] ?>"></span>

                                    <span class="legend-name">
                                        <?= htmlspecialchars($item['type']) ?>
                                    </span>

                                </div>

                                <span class="legend-count">
                                    <?= $item['count'] ?>
                                </span>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php endif; ?>
        </div>

    </div>

    <!-- ── Activity Log ────────────────────────────────────────── -->
    <div class="section-label fi">Activity</div>
    <div class="log-wrap fi">

        <div class="log-header">
            <div class="log-title">
                <i class='bx bx-history'></i> History Log
            </div>
            <div class="log-filter">
                <button class="filter-btn active"   data-filter="all">All</button>
                <button class="filter-btn added"    data-filter="added">Added</button>
                <button class="filter-btn modified" data-filter="modified">Modified</button>
                <button class="filter-btn deleted"  data-filter="deleted">Deleted</button>
            </div>
        </div>

        <div class="log-scroll">
            <?php if (empty($logs)): ?>
                <div class="empty-state">
                    <i class='bx bx-history'></i>
                    <p>No activity recorded yet.<br>Changes to assets &amp; users will appear here.</p>
                </div>
            <?php else: ?>
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Table</th>
                            <th>Record</th>
                            <th>Details</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody">
                    <?php foreach ($logs as $log):
                        $icon = match($log['action']) {
                            'added'    => 'bx-plus-circle',
                            'deleted'  => 'bx-minus-circle',
                            'modified' => 'bx-edit-alt',
                            default    => 'bx-circle'
                        };
                    ?>
                        <tr data-action="<?= $log['action'] ?>">
                            <td class="log-id-cell"><?= $log['id'] ?></td>
                            <td class="log-user-cell"><?= htmlspecialchars($log['username']) ?></td>
                            <td>
                                <span class="action-badge badge-<?= $log['action'] ?>">
                                    <i class='bx <?= $icon ?>'></i>
                                    <?= ucfirst($log['action']) ?>
                                </span>
                            </td>
                            <td class="log-table-cell"><?= htmlspecialchars($log['table_name']) ?></td>
                            <td class="log-id-cell"><?= $log['record_id'] ? '#' . $log['record_id'] : '—' ?></td>
                            <td class="log-detail-cell" title="<?= htmlspecialchars($log['details'] ?? '') ?>">
                                <?= htmlspecialchars($log['details'] ?? '—') ?>
                            </td>
                            <td class="log-time-cell">
                                <?= date('d M Y  H:i', strtotime($log['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div><!-- /log-wrap -->

</div><!-- /dash-wrap -->

<?php require_once __DIR__ . '/component/footer.php'; ?>

<script src="/TrackIT/src/js/dashboard.js"></script>

</html>