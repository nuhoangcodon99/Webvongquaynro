<?php
require_once 'config.php';
require_once 'cauhinh.php';
require_once 'connect.php';

function has_mkc2($username)
{
    global $conn; // Use the global $conn variable from connect.php

    try {
        // Thực hiện truy vấn để lấy giá trị của cột "mkc2" từ bảng "account"
        $sql = "SELECT mkc2 FROM account WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Kiểm tra và trả về kết quả là true/nếu giá trị khác rỗng và false/ngược lại
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['mkc2'] != '') {
                    return true;
                }
            }
        }

        // Trả về giá trị mặc định là false
        return false;
    } catch (PDOException $e) {
        // Xử lý lỗi khi có exception xảy ra
        echo "Lỗi truy vấn: " . $e->getMessage();
        exit;
    }
}
?>