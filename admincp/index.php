<?php
require_once '../core/set.php';
require_once '../core/connect.php';
require_once '../core/head.php';
require_once '../core/cauhinh.php';

// Xác thực người dùng, nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
if ($_login === null) {
    echo '<script>window.location.href = "../dang-nhap.php";</script>';
    exit;
}

// Chỉ cho phép tài khoản có quyền admin = 1 truy cập vào trang quản trị
if ($_admin != 1) {
    echo '<script>window.location.href="/"</script>';
    exit;
}

// Lấy các giá trị cần thiết từ URL (nếu có)
$_active = $_active ?? null;
$_tcoin = $_tcoin ?? 0;
$serverIP = $serverIP ?? '';
$serverPort = $serverPort ?? '';

// Đếm số lượng tài khoản trong cơ sở dữ liệu
$id_user_query = "SELECT COUNT(id) AS id FROM account";
$statement = $conn->prepare($id_user_query);
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);
$id = $row['id'];

// Đếm số lượng tài khoản bị cấm (ban) và số lượng tài khoản đang hoạt động (active)
$ban_count_query = "SELECT COUNT(*) AS ban FROM account WHERE ban = 1";
$active_count_query = "SELECT COUNT(*) AS active FROM account WHERE active = 1";

$result = $conn->prepare($ban_count_query);
$result->execute();
$row_ban = $result->fetch(PDO::FETCH_ASSOC); // Use fetch() with PDO::FETCH_ASSOC

$result = $conn->prepare($active_count_query);
$result->execute();
$row_active = $result->fetch(PDO::FETCH_ASSOC); // Use fetch() with PDO::FETCH_ASSOC

$_tongban = $row_ban["ban"];
$_tongactive = $row_active["active"];

// Đếm số lượng tài khoản có giá trị recaf khác null
$recaf_count_query = "SELECT COUNT(*) AS recaf FROM account WHERE recaf IS NOT NULL";
$result = $conn->prepare($recaf_count_query);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$_recaf = $row['recaf'];

function get_user_ip()
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $addr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($addr[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
?>

                    <div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
                        <div class="page-layout-body">
                            <!-- load view -->
                            <div class="ant-row">
    <div class="row">
        <div class="col">
            <a href="/" style="color: black" class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_">Quay lại diễn đàn</a>
        </div>
    </div>
</div>
<div class="ant-col ant-col-24">
    <div class="ant-list ant-list-split">
        <div class="ant-spin-nested-loading">
            <div class="ant-spin-container">
                <ul class="ant-list-items">
<div id="data_news">
<?php if ($_admin != 1) { ?>
    <p>Bạn không phải là admin! Không thể sài được chứ năng này</p>
<?php } else { ?>
    <div class="container pt-5 pb-5" id="pageHeader">
        <h4>MENU ADMIN</h4>
        <div class="row pb-2 pt-2">
            <div class="col-lg-6 text-left">
                <p>Tổng tài khoản:
                    <?php echo $id; ?>
                </p>
                <p>Thành viên:
                    <?php echo $_tongactive; ?>
                </p>
                <p>Tài khoản vi phạm:
                    <?php echo $_tongban; ?>
                </p>
                <!-- Trong phần HTML-->
                <div id="profile-update"></div>

                <script>
                    // Sử dụng JavaScript và AJAX để gửi yêu cầu đến máy chủ và cập nhật nội dung của vùng hiển thị kết quả
                    function updateRemainingTime() {
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function () {
                            if (this.readyState === 4 && this.status === 200) {
                                // Nhận phản hồi từ máy chủ và cập nhật nội dung của vùng hiển thị kết quả
                                document.getElementById("profile-update").innerHTML = this.responseText;
                            }
                        };
                        xhttp.open("GET", "../api/cauhinh/api-maychu.php", true); // Thay đổi đường dẫn đến tệp PHP xử lý
                        xhttp.send();
                    }

                    // Tự động cập nhật thời gian mỗi giây
                    setInterval(updateRemainingTime, 100);
                </script>
            </div>
            <div class="col-lg-6">
                <?php
                $month = date('m');
                $query = "SELECT SUM(amount) AS tongtiennap FROM trans_log WHERE status = 1 AND MONTH(date) = :month";
                $statement = $conn->prepare($query);
                $statement->bindParam(':month', $month, PDO::PARAM_INT);
                $statement->execute();
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                $tongtienthangnay = $row['tongtiennap'];

                $tienthangnay = number_format($tongtienthangnay);

                $query = "SELECT name, SUM(amount) AS topnap FROM trans_log WHERE status = 1 AND MONTH(date) = :month GROUP BY name ORDER BY topnap DESC LIMIT 3";
                $statement = $conn->prepare($query);
                $statement->bindParam(':month', $month, PDO::PARAM_INT);
                $statement->execute();

                if ($statement->rowCount() > 0) {
                    echo "<p class='mb-2'>Danh Sách Top Nạp Thẻ Tháng:</p>";
                    echo "<ol class='list-unstyled'>";

                    $count = 1; // Biến đếm

                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $name = $row['name'];
                        $topnap = $row['topnap'];

                        $tinhtopnap = number_format($topnap);

                        echo "<li class='mb-1'>TOP $count: $name - Tổng nạp: <span class='amount'>$tinhtopnap VNĐ</span></li> ";

                        $count++; // Tăng biến đếm sau mỗi lần lặp
                    }
                    echo "</ol><hr>";
                    echo "<span class='mb-3'>- Tổng doanh thu tháng này: </span>$tienthangnay VNĐ";
                } else {
                    echo "<p>Không có tài khoản nạp vào tháng này.</p>";
                }
}
?>
        </div>
    </div>
</div>
    <div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
                                <div class="ant-row ant-row-space-around ant-row-middle header-menu">
                                    <div class="ant-col ant-col-24">
                                        <div class="row ant-space ant-space-horizontal ant-space-align-center space-header-menu d-flex justify-content-center" style="flex-wrap:wrap;margin-bottom:-10px">
                                            <div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="thongtin-maychu.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-100" style ="color: #E9ECEF;">
                                                            <b>T.T MÁY CHỦ</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>                                      
                                            <div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="vatpham.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-100" style ="color: #E9ECEF;">
                                                            <b>BUFF VẬT PHẨM</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="giatri.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item w-100" style ="color: #E9ECEF;">
                                                            <b>GT NẠP</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="vipham.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item w-100" style ="color: #E9ECEF;">
                                                            <b>VI PHẠM</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="chiso.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item w-100" style ="color: #E9ECEF;">
                                                            <b>CỘNG CHỈ SỐ</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="congvnd.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item w-100" style ="color: #E9ECEF;">
                                                            <b>CỘNG TIỀN</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
											<div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="congtongnap.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item w-100" style ="color: #E9ECEF;">
                                                            <b>CỘNG TỔNG NẠP</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
											<div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="congxu.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item w-100" style ="color: #E9ECEF;">
                                                            <b>CỘNG XU</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="activetv.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item w-100" style ="color: #E9ECEF;">
                                                            <b>KICH HOẠT PLAYER</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="ant-space-item col-6 col-md-3 col-lg-2" style="padding-bottom:10px">
                                                <div>
                                                    <a href="lich-su-gd.php">
                                                        <button type="button" class="ant-btn ant-btn-default header-menu-item w-100" style ="color: #E9ECEF;">
                                                            <b>TẠO LỊCH SỬ GD</b>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
            </div>
        </div>
    </div>
</div>
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
<?php include_once '../core/footer.php'; ?>
</body>
</html>