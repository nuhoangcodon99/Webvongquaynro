<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../core/set.php';
require_once '../core/connect.php';
require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateRandomCode($length = 32)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $code;
}

// Kiểm tra xem mã xác nhận có hợp lệ hay không (thông qua cơ sở dữ liệu hoặc cách khác)
$isCodeValid = true; // Giả sử mã xác nhận hợp lệ

if ($isCodeValid) {
    // Kiểm tra trạng thái xác minh và thời gian xác minh trước khi thực hiện chức năng

    // Chuẩn bị truy vấn
    $stmt = $conn->prepare("SELECT xacminh, thoigian_xacminh, gmail FROM account WHERE username = :username");

    // Gán giá trị cho các tham số truy vấn
    $stmt->bindParam(":username", $_username);

    // Thực thi truy vấn
    $stmt->execute();

    // Lưu kết quả truy vấn vào biến
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Lấy giá trị từ kết quả truy vấn
    $xacminh = $row['xacminh'];
    $thoigian_xacminh = $row['thoigian_xacminh'];
    $gmail = $row['gmail'];

    // Đóng câu truy vấn
    $stmt->closeCursor();

    // Kiểm tra trạng thái xác minh, thời gian xác minh và giá trị cột gmail
    if ($xacminh != 0 && $thoigian_xacminh != 0) {
        // Xác nhận thành công, xóa dữ liệu trong cột gmail và cập nhật trạng thái xác minh và thời gian xác minh

        // Chuẩn bị truy vấn
        $stmt = $conn->prepare("UPDATE account SET gmail = NULL, xacminh = 0, thoigian_xacminh = 0 WHERE username = :username");

        // Gán giá trị cho các tham số truy vấn
        $stmt->bindParam(":username", $_username);

        // Thực thi truy vấn
        $stmt->execute();

        // Đóng câu truy vấn
        $stmt->closeCursor();

        // Đóng kết nối đến cơ sở dữ liệu
        $conn = null;

        $message = "Xác nhận thành công! Dữ liệu trong cột gmail đã được xóa và trạng thái xác minh đã được cập nhật.<br>";
        $messageClass = "success";

        // Chuyển hướng về trang cap-nhat-thong-tin.php với thông báo truyền qua tham số truy vấn
        header("Location:../cap-nhat-thong-tin.php?message=" . urlencode($message) . "&messageClass=" . urlencode($messageClass));
        exit;
    } else {
        // Kiểm tra giá trị cột gmail
        if ($gmail === null) {
            $message = "Tài khoản chưa cập nhật thông tin!<br>";
        } else {
            $message = "Tài khoản không đủ điều kiện để sử dụng chức năng.<br>";
        }
        $messageClass = "error";

        // Chuyển hướng về trang cap-nhat-thong-tin.php với thông báo truyền qua tham số truy vấn
        header("Location:../cap-nhat-thong-tin.php?message=" . urlencode($message) . "&messageClass=" . urlencode($messageClass));
        exit;
    }
} else {
    $message = "Mã xác nhận không hợp lệ.<br>";
    $messageClass = "error";

    // Chuyển hướng về trang cap-nhat-thong-tin.php với thông báo truyền qua tham số truy vấn
    header("Location:../cap-nhat-thong-tin.php?message=" . urlencode($message) . "&messageClass=" . urlencode($messageClass));
    exit;
}
?>