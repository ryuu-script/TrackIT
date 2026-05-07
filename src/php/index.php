<?php require_once '../php/component/setup.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Welcome To TrackIT</title>
    <link rel="stylesheet" href="/TrackIT/src/css/index.css">
</head>

<body>

    <div id="loader-container">
        <div class="loader-box">

            <h2>Loading TrackIT</h2>

            <div class="progress-bar">
                <div class="progress"></div>
            </div>

            <p id="loading-text">0%</p>

        </div>
    </div>

    <div id="intro-wrapper" style="display: none;">

        <img src="../image/sinoyTechLogo.png"
             id="sinoyLogo"
             alt="mark_sinoy's logo">

        <div class="intro-description">
            <h1><strong>Powered by:</strong> Sinoy Technologies.</h1>
        </div>

    </div>

    <script src="../js/index.js"></script>

</body>

</html>