<?php
require_once '../../core/connect.php';
require_once '../../core/cauhinh.php';
require_once '../../core/set.php';

// Lấy thông tin người chơi
$query = "SELECT p.name, p.gender, p.pet_power, p.data_point, p.data_task, a.username, a.active, a.mkc2, a.vnd, a.tongnap, a.gmail, a.gioithieu, a.tichdiem
          FROM player p
          LEFT JOIN account a ON p.account_id = a.id
          WHERE a.username = ?";

$statement = $conn->prepare($query);
$statement->bindParam(1, $_username, PDO::PARAM_STR);
$statement->execute();

$result = $statement->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $tenNhanVat = $result['name'];
    $gioiTinhNhanVat = $result['gender'];
    $petNhanVat = $result['pet_power'];
    $chuoiChiSo = $result['data_point'];
    $chuoiNhiemVu = json_decode($result['data_task'], true);
    $diemTichLuy = $result['tichdiem'];
    $matkhaucap2 = $result['mkc2'];

    // Kiểm tra và xử lý biến $result['gmail'] trước khi sử dụng str_repeat
    if (isset($result['gmail']) && is_string($result['gmail'])) {
        $emailMasked = $result['gmail'] !== '' ? substr($result['gmail'], 0, 2) . str_repeat("*", strlen($result['gmail']) - 2) . "@gmail.com" : "Chưa cập nhật";
    } else {
        $emailMasked = "Chưa cập nhật";
    }


    // Danh hiệu (Màu)
    $danhHieu = '';
    $mauSac = '';
    if ($diemTichLuy >= 200) {
        $danhHieu = "Chuyên Gia";
        $mauSac = "#800000"; // Màu đỏ
    } elseif ($diemTichLuy >= 100) {
        $danhHieu = "Hỏi Đáp";
        $mauSac = "#A0522D"; // Màu vàng
    } elseif ($diemTichLuy >= 35) {
        $danhHieu = "Người Bắt Chuyện";
        $mauSac = "#6A5ACD"; // Màu xanh
    } else {
        $danhHieu = "Chưa sở hữu"; // Không có danh hiệu
        // $mauSac vẫn để trống
    }

    // THông tin tài khoản
    echo '<div class="container pt-5 pb-5" id="pageHeader" style ="color: #E9ECEF;">';
    echo '<div class="row pb-2 pt-2">';
    echo '<div class="col-lg-6">';
    echo "<h8>TÀI KHOẢN:</h8><br>";
    echo "<span>- Tài khoản: " . $_username . "</span><br>"; // tên tài khoản
    echo "<span>- Thành Viên: " . ($result['active'] == 0 ? "Chưa mở" : "Đã mở") . "</span><br><br>"; // xem tài khoản đẫ mở thành viên chưa
    echo "<span>- Số dư: " . number_format($result['vnd']) . " VNĐ</span><br>"; // hiển thị số dư tài khoản
    echo "<span>- Tổng nạp: " . $result['tongnap'] . " VNĐ</span><br><br>"; // hiển thị tổng nạp của tài khoản
    echo $danhHieu !== "" ? '- Danh hiệu: <span style="color:' . $mauSac . ' !important">' . $danhHieu . '</span><br>' : ""; // danh hiệu trên diễn đàn
    echo "<span>- Tích điểm: " . $diemTichLuy . "</span><br>"; // điểm tích lũy đăng bài, trả lời bài
    echo "<span>- Gmail: " . $emailMasked . "</span><br>"; // hiển thị gmail của tài khoản
    echo "<span>- Mã bảo vệ: " . ($result['mkc2'] != null ? "Đã cập nhật" : "Chưa có") . "</span><br><br>"; // hiển thị gmail của tài khoản
    echo "</div>";

    // Thông tin nhân vật
    echo '<div class="col-lg-6">';
    echo '<h8>NHÂN VẬT:</h8><br>';
    echo "<span>- Tên: $tenNhanVat</span><br>"; // tên nhân vật
    echo "<span>- Hành Tinh: " . ($gioiTinhNhanVat == '0' ? 'Trái Đất' : ($gioiTinhNhanVat == '1' ? 'Namec' : ($gioiTinhNhanVat == '2' ? 'Xayda' : 'Không xác định'))) . "</span><br>"; // hiện thị hành tinh nhân vật

    $nhiemVuQuery = "SELECT name FROM task_main_template WHERE id = ?";
    $nhiemVuStatement = $conn->prepare($nhiemVuQuery);
    $nhiemVuID = $chuoiNhiemVu[0];
    $nhiemVuStatement->bindParam(1, $nhiemVuID, PDO::PARAM_INT);
    $nhiemVuStatement->execute();

    $nhiemVuResult = $nhiemVuStatement->fetch(PDO::FETCH_ASSOC);

    $tenNhiemVu = $nhiemVuResult ? $nhiemVuResult['name'] : '';

    echo "<span>- Nhiệm Vụ: $tenNhiemVu</span><br><br>"; // hiển thị tên nhiệm vụ
    echo '<h8>CHỈ SỐ:</h8><br>'; // hiên thị chỉ số

    // Chuyển đổi JSON thành mảng
    $chiSo = json_decode($chuoiChiSo, true);

    // Lấy danh sách các chỉ số cần lấy
    $chiSoCanLay = array_intersect_key($chiSo, array_flip(['1', '2', '4', '5', '6', '7', '8']));

    // Hiển thị các chỉ số sư phụ nếu có
    foreach ($chiSoCanLay as $key => $value) {
        switch ($key) {
            case '1':
                $sucManh = number_format($value);
                echo "<span>- Sức Mạnh: $sucManh</span><br>"; // chỉ số sức mạnh
                break;

            case '2':
                $tiemNang = number_format($value);
                echo "<span>- Tiềm Năng: $tiemNang</span><br>"; // Tiềm năng
                break;

            case '4':
                $mau = number_format($value);
                echo "<span>- Máu: $mau</span><br>"; // chỉ số HP
                break;

            case '5':
                $theLuc = number_format($value);
                echo "<span>- Thể Lực: $theLuc</span><br>"; // chỉ số thể lực
                break;

            case '6':
                $sucDanhGoc = number_format($value);
                echo "<span>- Sức Đánh Gốc: $sucDanhGoc</span><br>"; // chỉ số sức đánh gốc
                break;

            case '7':
                $giapGoc = number_format($value);
                echo "<span>- Giáp Gốc: $giapGoc</span><br>"; // chỉ số giáp gốc
                break;

            case '8':
                $chiMang = number_format($value);
                echo "<span>- Chí Mạng: $chiMang</span><br>"; // chỉ số chí mạng
                break;
        }
    }
    echo "</div>";
    echo '</div>';
    echo '</div>';
} else {
    echo "Không tìm thấy thông tin nhân vật.";
}

$conn = null;

// Chưa đăng nhập, chuyển hướng đến trang khác bằng JavaScript
echo '<script>window.location.href = "/";</script>';
exit(); // Đảm bảo dừng thực thi code sau khi chuyển hướng

?>