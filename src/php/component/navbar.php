<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /TrackIT/index.php");
    exit();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar">

    <div class="nav-left">
        <img src="/TrackIT/src/image/TrackIT_logo.png" alt="TrackIT Logo">
        <span>TrackIT</span>
    </div>

    <div class="nav-middle">
        <a href="/TrackIT/src/php/dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="/TrackIT/src/php/it_assets.php" class="<?= $currentPage === 'it_assets.php' ? 'active' : '' ?>">Assets</a>
        <a href="/TrackIT/src/php/users.php" class="<?= $currentPage === 'users.php' ? 'active' : '' ?>">Users</a>
    </div>

    <div class="nav-right">
        <a href="settings.php" class="user-profile">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></div>
            <span><?= $_SESSION['username'] ?></span>
            <i class='bx bxs-chevron-right'></i>
        </a>
    </div>

</nav>