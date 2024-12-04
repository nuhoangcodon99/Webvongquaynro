<?php
require_once 'core/set.php';
require_once 'core/connect.php';
$_alert = null;
require_once 'core/head.php';
if ($_login === null) {
    echo '<script>window.location.href = "../dang-nhap.php";</script>';
}
?>

                    <div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
                        <div class="page-layout-body">
                            <!-- load view -->
                            <div class="ant-row">
    <a href="/" style="color: black" class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_">Quay lại diễn đàn</a>
    </div>
<div class="ant-col ant-col-24">
    <div class="ant-list ant-list-split">
        <div class="ant-spin-nested-loading">
            <div class="ant-spin-container">
                <ul class="ant-list-items">
<div id="data_news">
<div class="container pt-3 pb-5">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="text-center pb-3">
</div>
<?php
function curl_get($url)
{
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    }

    // Close cURL resource
    curl_close($ch);

    return $response;
}

// Your token
//$token = "rIqbzoiMEKCP-UrKpOR-BxtE-wnbG-lvrT";

// Make the cURL request
$data = curl_get("https://api.sieuthicode.net/historyapitpbv3/Maivandai2003@/68051003478/e6fa2d9469615326598f9a73823f1637");

$data = json_decode($data, true);
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "racne";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function loc($noidung)
{
    preg_match('/big\s+(\d+)/', $noidung, $matches);

    if (count($matches) > 1) {
        // Lấy các chữ số sau từ "tingting"
        $extracted_digits = $matches[1];
        return $extracted_digits;
    }

    return null;
}



foreach ($data['transactions'] as $mb) {
    $sotien      = $mb['amount'];
    $mgd         = explode('\\', $mb['transactionID'])[0];
    $noidung     = $mb['description'];
    $idck          = loc($noidung);
    if ($sotien >= 10000) { //it nhat la 20k
      if ($idck) {
         $checki = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `mbbank` WHERE `tranId`='" . $mgd . "'"));
         $checkacc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `account` WHERE `username`='" . $idck . "'"));
         if ($checkacc && isset($checkacc['username']) && $checkacc['username'] == $idck) {
            if ($checki && isset($checki['tranId']) && $checki['tranId'] == $mgd) {
            } else {
               $result1 = mysqli_query($conn, "UPDATE `account` SET `vnd`=`vnd`+ {$sotien}, `tongnap`= `tongnap`+{$sotien} WHERE `username`='$idck'");
               if (!$result1) {
                  echo "Lỗi khi cập nhật tài khoản: " . mysqli_error($conn);
               }

               $result2 = mysqli_query($conn, "INSERT INTO `mbbank` (`tranId`, `io`, `amount`, `comment`) VALUES ('$mgd', '$idck', '$sotien', '$noidung')");
               if (!$result2) {
                  echo "Lỗi khi chèn vào mbbank: " . mysqli_error($conn);
               }
            }
         } else {
         }
      }
   }
}
?>
<?php
// Kết nối đến cơ sở dữ liệu

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thực hiện truy vấn SQL
$sql = "SELECT `id` FROM `account` WHERE `username` = '$_username'";
$result = $conn->query($sql);

// Kiểm tra và xử lý kết quả truy vấn
if ($result->num_rows > 0) {
    // Lặp qua các hàng kết quả
    while ($row = $result->fetch_assoc()) {
        // Lấy dữ liệu từ cột 'id'
        $id = $row["id"];
        // Xử lý dữ liệu id ở đây (ví dụ: in ra)
    }
} else {
    echo "Không có kết quả.";
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
            <?php if ($_login === null) { ?>
                <p>Bạn chưa đăng nhập? Hãy đăng nhập để dùng được chức năng này</p>
            <?php } else { ?>
                <h4>Nạp Tiền - MBBANK</h4>
                <style>
                    .info-input {
                        border: 1px solid #ccc;
                        padding: 8px;
                        border-radius: 10px;
                        background-color: #f3e8d5;
                        /* Light brown background color */
                        margin-bottom: 10px;
                        width: 100%;
                    }

                    .info-input:focus {
                        outline: none;
                        /* Remove focus outline when clicked */
                    }

                    .notification-container {
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #f3e8d5;
                        padding: 5px;
                        /* Tăng giá trị của padding */
                        border-radius: 20px;
                        /* Tăng giá trị của border-radius */
                        z-index: 9999;
                        display: none;
                        /* Hide initially */
                    }
                </style>

                <p>Thông tin nạp MBBANK:</p>
                <br>
                <label>Số Tài Khoản:</label>
                <input class="info-input" type="text" value="<?php echo $_stkmbbank; ?>" id="stk-input" readonly
                    onclick="copyText('stk-input')">
                <label>Ngân Hàng:</label>
                <input class="info-input" type="text" value="<?php echo $_mbbank; ?>" readonly>
                <label>Tên Tài Khoản:</label>
                <input class="info-input" type="text" value="<?php echo $_tenmbbank; ?>" readonly>
                <label>Nội Dung:</label>
                <input class="info-input" type="text" value="big <?php echo $_username; ?>" id="admin-input" readonly
                    onclick="copyText('admin-input')">
                <br>
                <div class="notification-container font-weight-blood" id="notification"></div>
                - Xây dựng, ủng hộ Ngọc Rồng hoạt động.<br>

                <br>
                <br>
                <p><i>Khi giao dịch hãy kiểm tra lại kĩ thông tin nhé!.</i></p>
                <p><i>Khi xác thực xong làm mới trang sau 1 - 3 phút để kiểm tra tình trạng nạp.</i></p>

                <script>
                    // Function to handle copying content to clipboard and show notification
                    function copyText(inputId) {
                        var inputElement = document.getElementById(inputId);
                        inputElement.select();
                        document.execCommand("copy");

                        // Show notification with the copied content in red
                        var notification = document.getElementById("notification");
                        notification.innerHTML = "Nội dung đã được sao chép: " + inputElement.value;
                        notification.style.display = "block"; // Show the notification

                        // Hide the notification after a short delay (e.g., 3 seconds)
                        setTimeout(function () {
                            notification.style.display = "none";
                        }, 3000);
                    }
                </script>
                </small>
            <?php } ?>
        </div>
        <div class="col-lg-6 htop border-left">
            <?php
            if (isset($_id)) {
                $currentMonth = date('m');
                $currentYear = date('Y');
                $currentMonthFormatted = $currentMonth . '/' . $currentYear;

                $stmt = $conn->prepare("SELECT * FROM mbbank WHERE io = :io AND DATE_FORMAT(created_at, '%m/%Y') = :currentMonthFormatted ORDER BY id DESC");

                $stmt->bindParam(":id", $_id);
                $stmt->bindParam(":currentMonthFormatted", $currentMonthFormatted);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo '<h6 class="text-center">LỊCH SỬ CHUYỂN KHOẢN THÁNG ' . $currentMonth . '</h6>';
                echo '<br><br>';

                if (count($result) > 0) {
                    $itemsPerPage = 3;
                    $totalItems = count($result);
                    $totalPages = ceil($totalItems / $itemsPerPage);

                    $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

                    // Tính chỉ số bắt đầu và chỉ số kết thúc dựa trên trang hiện tại và thứ tự ngược của dãy giao dịch
                    $startIndex = ($currentPage - 1) * $itemsPerPage; // Chỉ số bắt đầu của trang hiện tại
                    $endIndex = min($startIndex + $itemsPerPage - 1, $totalItems - 1); // Chỉ số kết thúc của trang hiện tại
                    echo '<div class="transaction-list">';
                    $count = $totalItems - $startIndex; // Tính stt bắt đầu từ tổng số phần tử

                    // Display data for the current page
                    for ($i = $startIndex; $i <= $endIndex; $i++) {
                        $row = $result[$i];

                        $status = '';
                        switch ($row['status']) {
                            case 1:
                                $status = '<span>Đã thanh toán</span>';
                                break;
                            case 2:
                                $status = '<span>Chưa thanh toán</span>';
                                break;
                            default:
                                $status = '<span>Đang xử lý</span>';
                        }

                        echo '<div class="transaction-item">';
                        echo '<p><strong>GIAO DỊCH SỐ:</strong> ' . $count . '</p>';
                        echo '<p><strong>SỐ TÀI KHOẢN:</strong> ' . $row['accountNo'] . '</p>';
                        echo '<p><strong>TÊN NGƯỜI CHUYỂN:</strong> ' . $row['bankName'] . '</p>';
                        echo '<p><strong>NGÂN HÀNG:</strong> ' . $row['benAccountName'] . '</p>';
                        echo '<p><strong>TRẠNG THÁI:</strong> ' . $status . '</p>';
                        echo '<p><strong>SỐ TIỀN:</strong> ' . number_format($row['sotien']) . 'đ</p>';
                        echo '<p><strong>NGÀY THÁNG:</strong> ' . $row['thoigian'] . '</p>';
                        echo '</div>';

                        // Add <hr> tag if there are more items after this one
                        if ($i < $endIndex) {
                            echo '<hr>';
                        }
                        // Giảm giá trị của $count sau mỗi vòng lặp để cộng dần số thứ tự
                        $count--;
                    }

                    echo '</div>';

                    // Display pagination links
                    echo '<div class="col text-right">';
                    echo '<ul class="pagination justify-content-end pagination-custom-style">';
                    if ($currentPage > 1) {
                        echo '<li><a class="btn btn-sm btn-light" href="?page=' . ($currentPage - 1) . '"><</a></li>';
                    }

                    // Determine the page range to display
                    $pageRangeStart = max(1, $currentPage - 1);
                    $pageRangeEnd = min($totalPages, $pageRangeStart + 2);

                    // Display the page links within the range
                    for ($i = $pageRangeStart; $i <= $pageRangeEnd; $i++) {
                        if ($i == $currentPage) {
                            echo '<li><a class="btn btn-sm page-active">' . $i . '</a></li>';
                        } else {
                            echo '<li><a class="btn btn-sm btn-light" href="?page=' . $i . '">' . $i . '</a></li>';
                        }
                    }

                    if ($currentPage < $totalPages) {
                        echo '<li><a class="btn btn-sm btn-light" href="?page=' . ($currentPage + 1) . '">></a></li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                } else {
                    echo '<div class="text-center">Không có giao dịch nào trong tháng này.</div>';
                }
            } else {
                echo '<div class="text-center">Không tìm thấy tên người dùng trong bảng user.</div>';
            }

            ?>
                                                    </div>
                        <div id="paging" class="d-flex justify-content-end align-items-center flex-wrap">
                        </div>
                                    </ul>
            </div>
        </div>
    </div>
</div>
</div>                            <!-- end load view -->
                        </div>
                    </div>
<?php include_once 'core/footer.php'; ?>