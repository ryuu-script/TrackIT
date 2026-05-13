<?php
session_start();
require_once '../php/component/db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: /TrackIT/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_destroy();
    header("Location: /TrackIT/src/php/LOGIN/enter_cred.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackIT | Settings</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600&family=DM+Sans:wght@300;400&display=swap');

        :root {
            --bg-from: rgba(10, 4, 22, 1);
            --bg-mid: rgba(18, 6, 38, 1);
            --purple-core: rgb(150, 60, 255);
            --purple-light: rgb(185, 100, 255);
            --purple-pale: rgb(220, 175, 255);
            --purple-dim: rgba(200, 170, 230, 0.6);
            --border-clr: rgba(185, 100, 255, 0.25);
            --glass-bg: rgba(185, 100, 255, 0.05);
            --glow: rgba(125, 59, 246, 0.25);
            --radius: 18px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(
                180deg,
                var(--bg-from) 0%,
                var(--bg-mid) 50%,
                var(--bg-from) 100%
            );
            color: white;
        }

        .settings-wrap {
            width: 100%;
            max-width: 700px;
            margin: 100px auto;
            padding: 90px;
        }

        .settings-card {
            background: var(--glass-bg);
            border: 1px solid var(--border-clr);
            border-radius: var(--radius);
            padding: 40px;
            box-shadow: 0 0 40px var(--glow);
        }

        .settings-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 36px;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .settings-title span {
            color: var(--purple-core);
        }

        .settings-sub {
            color: var(--purple-dim);
            margin-bottom: 35px;
            letter-spacing: 1px;
        }

        .settings-user {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 35px;
            padding: 18px;
            border-radius: 14px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .settings-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(
                135deg,
                var(--purple-core),
                var(--purple-light)
            );

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 28px;
            font-family: 'Rajdhani', sans-serif;
        }

        .settings-info h2 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .settings-info p {
            color: var(--purple-dim);
            font-size: 14px;
            letter-spacing: 1px;
        }

        .settings-section {
            margin-top: 25px;
        }

        .settings-label {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--purple-dim);
            margin-bottom: 14px;
        }

        .logout-btn {
            width: 100%;
            height: 55px;

            border: none;
            border-radius: 14px;

            background: rgba(237, 135, 150, 0.12);
            border: 1px solid rgba(237, 135, 150, 0.25);

            color: #ed8796;

            font-size: 14px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;

            cursor: pointer;
            transition: 0.25s ease;
        }

        .logout-btn:hover {
            background: rgba(237, 135, 150, 0.2);
            transform: translateY(-2px);
        }

    </style>
</head>

<body>

    <div class="settings-wrap">

        <div class="settings-card">

            <h1 class="settings-title">
                Account <span>Settings</span>
            </h1>

            <p class="settings-sub">
                Manage your TrackIT account
            </p>

            <div class="settings-user">

                <div class="settings-avatar">
                    <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
                </div>

                <div class="settings-info">
                    <h2><?= $_SESSION['username'] ?></h2>
                    <p>TRACKIT SYSTEM USER</p>
                </div>

            </div>

            <div class="settings-section">

                <div class="settings-label">
                    Session Controls
                </div>

                <form method="POST">
                    <button type="submit" class="logout-btn">
                        <i class='bx bx-log-out'></i>
                        Logout Account
                    </button>
                </form>

            </div>

        </div>

    </div>

</body>
</html>
