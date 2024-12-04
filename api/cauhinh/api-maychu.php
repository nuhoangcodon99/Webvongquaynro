<?php
require_once '../../core/connect.php';
require_once '../../core/cauhinh.php';
require_once '../../core/set.php';

$serverIP = (isset($serverIP)) ? $serverIP : '';
$serverPort = (isset($serverPort)) ? $serverPort : '';

// Kiểm tra nếu biến serverIP và serverPort không có giá trị
if (empty($serverIP) || empty($serverPort)) {
    $errorMessage = "Không tìm thấy cấu hình máy chủ.";
} else {
    // Thực hiện vòng lặp một lần duy nhất
    $counter = 0;

    // Tạo một yêu cầu cURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://{$serverIP}:{$serverPort}/api/cauhinh/api-cauhinh.php?action=config");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Thực hiện yêu cầu và lấy phản hồi
    $response = curl_exec($curl);
    curl_close($curl);

    // Xử lý và hiển thị thông tin từ phản hồi
    if (!empty($response)) {
        $configData = json_decode($response, true);

        if (isset($configData['cpuUsage'])) {
            $cpuUsage = $configData['cpuUsage'];
            echo "<p>Tỉ lệ sử dụng CPU: {$cpuUsage}%</p>";
        }

        if (isset($configData['ramUsage'])) {
            $ramUsage = $configData['ramUsage'];
            echo "<p>Tổng RAM đã dùng: {$ramUsage}%</p>";
        }

        if (isset($configData['ssdUsage']) && isset($configData['ssdTotal'])) {
            $ssdUsage = $configData['ssdUsage'];
            $ssdTotal = $configData['ssdTotal'];
            $ssdUsed = round(($ssdUsage / 100) * $ssdTotal, 2);
            $ssdFree = $ssdTotal - $ssdUsed;
            echo "<p>Dung lượng: {$ssdUsed} / {$ssdFree} GB</p>";
        }

        if (!isset($configData['cpuUsage']) && !isset($configData['ramUsage']) && !isset($configData['ssdUsage'])) {
            $errorMessage = "Không tìm thấy cấu hình bạn đã cài đặt.";
        }
    } else {
        $errorMessage = "Không nhận được phản hồi từ máy chủ.";
    }
}
// Chưa đăng nhập, chuyển hướng đến trang khác bằng JavaScript
echo '<script>window.location.href = "../../dien-dan";</script>';
exit(); // Đảm bảo dừng thực thi code sau khi chuyển hướng

// Hiển thị thông báo lỗi nếu có
if (isset($errorMessage)) {
    echo $errorMessage;
}
?>
</div>