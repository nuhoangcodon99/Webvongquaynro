<?php
$ip_sv = "localhost";
$dbname_sv = "nro";
$user_sv = "root";
$pass_sv = "";

// GMT +7
date_default_timezone_set('Asia/Ho_Chi_Minh');

try {
    // Create connection
    $dsn = "mysql:host=$ip_sv;dbname=$dbname_sv;charset=utf8mb4";
    $conn = new PDO($dsn, $user_sv, $pass_sv);

    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kiểm tra trạng thái của máy chủ
    $query = "SELECT trangthai, domain FROM adminpanel";
    $statement = $conn->query($query);
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    $trangthai = $row['trangthai'];
    $domain = $row['domain']; // Thay thế bằng tên miền của bạn

    if ($trangthai === 'baotri') {
        die("Máy Chủ $domain đang bảo trì vui lòng chờ");
    }
} catch (PDOException $e) {
    die("Connection failed");
}
?>
