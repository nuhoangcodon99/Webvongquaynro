<?php
require_once 'core/connect.php';
require_once 'core/set.php';

// Lấy giá trị Mã bảo vệ được gửi từ client
$deletePasswordcap2 = $_POST['passwordcap2'] ?? '';

// Kiểm tra Mã bảo vệ nhập vào có khớp với giá trị trong cơ sở dữ liệu hay không
$sql = "SELECT * FROM account WHERE mkc2 = :deletePasswordcap2";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':deletePasswordcap2', $deletePasswordcap2);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Mã bảo vệ khớp, thực hiện xóa Mã bảo vệ và cập nhật vào cơ sở dữ liệu
    $sqlUpdate = "UPDATE account SET mkc2 = NULL WHERE mkc2 = :deletePasswordcap2";

    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bindParam(':deletePasswordcap2', $deletePasswordcap2);

    if ($stmtUpdate->execute()) {
        // Xóa Mã bảo vệ thành công
        echo "Xóa Mã bảo vệ thành công";
    } else {
        // Xảy ra lỗi khi cập nhật vào cơ sở dữ liệu
        echo "Lỗi: " . $stmtUpdate->errorInfo()[2];
    }
} else {
    // Mã bảo vệ không khớp
    echo "Mã bảo vệ không chính xác";
}

// Đóng kết nối cơ sở dữ liệu
$conn = null;
?>
