
<!-- WALAY MANGHILABOT ANI KAY MAO NI ANG GA CONNECT SA ATONG DATABASE -->
<?php
    $host   = "localhost";
    $dbname = "trackit_db"; // Ilisi lang ni ug ngalan kay basin dili 'trackit_db' ang imong database name
    $user   = "root";
    $pass   = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
?>