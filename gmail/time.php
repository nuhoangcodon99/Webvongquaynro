<?php
require_once '../core/set.php';
require_once '../core/connect.php';

// Prepare the SQL query
$stmt = $conn->prepare("SELECT xacminh, thoigian_xacminh FROM account WHERE username = :username");
$stmt->bindParam(":username", $_username, PDO::PARAM_STR);
$stmt->execute();

// Fetch the result
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$xacminh = $row['xacminh'];
$thoigian_xacminh = $row['thoigian_xacminh'];

// Close the statement
$stmt->closeCursor();

// Check the 'xacminh' column and calculate the remaining time
if ($xacminh == 1) {
    $currentTimestamp = time();
    $remainingSeconds = $thoigian_xacminh - $currentTimestamp;
    $remainingMinutes = ceil($remainingSeconds / 270);

    // Check if the 30-minute time has expired
    if ($remainingMinutes <= 0) {
        // Update the 'xacminh' and 'thoigian_xacminh' columns to 0
        $updateStmt = $conn->prepare("UPDATE account SET xacminh = 0, thoigian_xacminh = 0 WHERE username = :username");
        $updateStmt->bindParam(":username", $_username, PDO::PARAM_STR);
        $updateStmt->execute();
        $updateStmt->closeCursor();

        echo "Thời gian xác minh đã hết";
    } else {
        echo "<p class='mb-1 mt-2'>Thư xác nhận xóa liên kết Gmail đã được gửi tới địa chỉ Gmail liên kết<br>
        Vui lòng kiểm tra <span class='font-weight-bold font-italic'>Hộp thư đến</span> bao gồm cả <span class='font-weight-bold font-italic'>Thư rác</span> và làm theo yêu cầu để hoàn tất xóa liên kết Gmail
      </p>Thời gian còn lại: <strong>$remainingMinutes phút</strong><br><br>";
    }
} else {
    echo "";
}
?>