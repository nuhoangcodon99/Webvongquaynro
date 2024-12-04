<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../core/set.php';
require_once '../core/connect.php';
require_once '../core/head.php';
require_once '../core/cauhinh.php';
require '../cap-nhat-thong-tin.php';
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

// Save the current timestamp
$currentTimestamp = time();

// Check if verification information exists in the database
$stmt = $conn->prepare("SELECT xacminh, thoigian_xacminh FROM account WHERE username = :username");
$stmt->bindParam(":username", $_username, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$xacminh = $row['xacminh'];
$thoigian_xacminh = $row['thoigian_xacminh'];

if ($xacminh == 1 && $currentTimestamp > $thoigian_xacminh) {
    // Time has expired, update the 'xacminh' and 'thoigian_xacminh' columns to 0
    $stmt = $conn->prepare("UPDATE account SET xacminh = 0, thoigian_xacminh = 0 WHERE username = :username");
    $stmt->bindParam(":username", $_username, PDO::PARAM_STR);
    $stmt->execute();
    $stmt = null;

    $xacminh = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the account is linked to an email
    // If the account is already linked to an email
    $isLinked = true; // Assume it is linked

    if ($isLinked) {
        $verificationCode = generateRandomCode(); // Function to generate a random verification code

        if ($xacminh == 0) {
            // Update 'xacminh' and 'thoigian_xacminh' columns in the database
            $newXacminh = 1;
            $newThoigianXacminh = $currentTimestamp + 1800;
            $stmt = $conn->prepare("UPDATE account SET xacminh = :xacminh, thoigian_xacminh = :thoigian_xacminh WHERE username = :username");
            $stmt->bindParam(":xacminh", $newXacminh, PDO::PARAM_INT);
            $stmt->bindParam(":thoigian_xacminh", $newThoigianXacminh, PDO::PARAM_INT);
            $stmt->bindParam(":username", $_username, PDO::PARAM_STR);
            $stmt->execute();
            $stmt = null;
        }

        // Retrieve the verification time from the database
        $stmt = $conn->prepare("SELECT thoigian_xacminh FROM account WHERE username = :username");
        $stmt->bindParam(":username", $_username, PDO::PARAM_STR);
        $stmt->execute();
        $thoigian_xacminh = $stmt->fetchColumn();
        $stmt = null;

        // Calculate the remaining time
        $expirationTimestamp = $thoigian_xacminh;
        $remainingSeconds = $expirationTimestamp - $currentTimestamp;
        $remainingMinutes = ceil($remainingSeconds / 60);

        if ($remainingMinutes <= 0) {
            // Time has expired, update the 'xacminh' and 'thoigian_xacminh' columns to 0
            $stmt = $conn->prepare("UPDATE account SET xacminh = 0, thoigian_xacminh = 0 WHERE username = :username");
            $stmt->bindParam(":username", $_username, PDO::PARAM_STR);
            $stmt->execute();
            $stmt = null;

            echo "expired";
        } else {
            // Create email content with the remaining minutes
            $emailContent = "Xin chào bạn,\n\nTài khoản " . $_username . " đang thực hiện xóa liên kết với Email này.\n\nĐể xác nhận và hoàn tất xóa liên kết Email, bạn vui lòng truy cập vào đường dẫn sau:\n\n" . $_domain . "/gmail/verify-gmail?" . $verificationCode . "\n\nĐường dẫn sẽ hết hạn sau: " . $remainingMinutes . " phút.\n\nAdmin chân thành cảm ơn bạn đã tin tưởng và đồng hành cùng " . $_tenmaychu . "!\n\n" . $_tenmaychu . "";

            // Send the email with the created content
            $mail = new PHPMailer(true);
            try {
                // Configure email sending through Gmail
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ngocrong@gmail.com'; // Change to your email
                $mail->Password = 'palixbbrkrubxcsu'; // Change to your password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Set email information
                $mail->setFrom('ngocrong@gmail.com', 'Ngọc Rồng Kuroko'); // Change to your email and name
                $mail->addAddress($primaryGmail);
                $mail->Subject = '=?UTF-8?B?' . base64_encode('Xác nhận xóa liên kết Email - Ngọc Rồng Kuroko') . '?=';
                $mail->Body = $emailContent;
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                // Send the email
                if ($mail->send()) {
                    echo "Gửi gmail thành công";
                } else {
                    echo "Gửi gmail thất bại";
                }
            } catch (Exception $e) {
                echo "error";
            }
        }
    } else {
        echo "unlinked";
    }
}
?>