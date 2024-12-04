<?php
require_once '../core/set.php';
require_once '../core/connect.php';
// Import thư viện PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Đường dẫn đến các tệp thư viện PHPMailer
require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

$message = '';
// Kiểm tra xem form đã được gửi hay chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy giá trị từ form
    $tieude = htmlspecialchars($_POST["tieude"]);
    $noidung = htmlspecialchars($_POST["noidung"]);
    $username = $_username; // Sử dụng thông tin username từ phiên đăng nhập

    // Kiểm tra từ cấm trong tiêu đề và nội dung
    $censoredWords = array(
        'sex',
        'địt',
        'súc vật',
        'fuck',
        'loz',
        'lozz',
        'lozzz',
        'óc chó',
        'ngu lồn',
        'nguu lồn',
        'nguu lồn',
        'ngulon',
        'nguu lonn',
        'ngu lon',
        'occho',
        'ditmemay',
        'dmm',
        'dcm',
        'địt cụ mày',
        'địt con mẹ mày',
        'fuck you',
        'chịch',
        'chịt',
        'sẽ gầy'
    );

    foreach ($censoredWords as $word) {
        if (stripos($tieude, $word) !== false || stripos($noidung, $word) !== false) {
            echo "<span class='text-danger pb-2'>Thông Báo:</span> Tiêu đề hoặc nội dung chứa từ không cho phép.";
            exit;
        }
    }

    // Gửi email
    $mail = new PHPMailer(true);

    try {
        // Cấu hình thông tin email
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username ='ngocrong@gmail.com'; // Tài khoản Gmail của bạn
        $mail->Password = 'palixbbrkrubxcsu'; // Mật khẩu Gmail của bạn
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Thiết lập địa chỉ email người gửi và tên người gửi
        $mail->setFrom('ngocrong@gmail.com', 'Báo Lỗi Máy Chủ');

        // Thiết lập địa chỉ email người nhận
        $mail->addAddress('ngocrong@gmail.com');

        // Thiết lập tiêu đề email
        $mail->Subject = '=?UTF-8?B?' . base64_encode($tieude) . '?=';

        // Thiết lập nội dung email
        $mail->Body = "- Tên tài khoản: " . $username . "\n- Nội dung: $noidung";
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // Gửi email
        $mail->send();

        // Gửi email thành công
        $successMessage = 'Email đã được gửi đi thành công.';
        $_SESSION['success'] = $successMessage;
        header('Location: ../bao-loi.php');
        exit;
    } catch (Exception $e) {
        $errorMessage = 'Gửi email thất bại. Lỗi: ' . $mail->ErrorInfo;
        $_SESSION['message'] = $errorMessage;
        header('Location:../bao-loi.php');
        exit;
    }
}
?>