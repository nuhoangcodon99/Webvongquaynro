<?php
// Bao gồm các file và khởi tạo session
require_once 'core/connect.php';
require_once 'core/set.php';
require_once 'core/head.php';

// Khởi tạo biến thông báo
$_alert = '';

// Khởi tạo các biến cho đăng ký
$username = '';
$password = '';
$ip_address = $_SERVER['REMOTE_ADDR'];
$recafcode = null;
$gmail = '';
$count = 0; // Thêm dòng này để khởi tạo biến $count với giá trị mặc định là 0

// Tạo captcha 6 chứ số
function generateCaptcha($length = 6)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha = '';
    for ($i = 0; $i < $length; $i++) {
        $captcha .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $captcha;
}

// load lại trang sẽ tạo captcha mới
function refreshCaptcha()
{
    unset($_SESSION['captcha']);
    $_SESSION['captcha'] = generateCaptcha(6);
}

// Kiểm tra nếu tồn tại recafcode từ URL thì lấy giá trị recafcode
if (isset($_GET['ref']) && !empty($_GET['ref'])) {
    $recafcode = $_GET['ref'];

    // Truy vấn để lấy thông tin của recafcode từ bảng "account"
    $stmt = $conn->prepare("SELECT username FROM account WHERE id = :recafcode");
    $stmt->bindParam(':recafcode', $recafcode, PDO::PARAM_INT); // Using PDO::PARAM_INT to ensure the parameter is an integer
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem recafcode có tồn tại trong bảng không
    if ($result) {
        $recafUsername = htmlspecialchars($result['username']); // Using htmlspecialchars to prevent XSS
    } else {
        $recafUsername = null;
        $_alert = '<div class="text-danger pb-2 font-weight-bold">Không tìm thấy mã giới thiệu này.</div>';
    }
} else {
    $recafcode = null;
}

if (!isset($_POST["captcha"])) {
    refreshCaptcha();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form đăng ký và sử dụng htmlspecialchars để ngăn chặn XSS
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    $gmail = isset($_POST["gmail"]) ? htmlspecialchars(trim($_POST["gmail"])) : '';

    if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $_alert = '<div class="text-danger pb-2 font-weight-bold">Tên đăng nhập không được chứa kí tự đặc biệt.</div>';
        refreshCaptcha();
    } elseif (empty($password) || !preg_match('/^[a-zA-Z0-9_]+$/', $password)) {
        $_alert = '<div class="text-danger pb-2 font-weight-bold">Mật khẩu không được chứa kí tự đặc biệt.</div>';
        refreshCaptcha();
    } else {
        // Kiểm tra captcha
        $captchaValue = isset($_POST["captcha"]) ? trim($_POST["captcha"]) : '';
        $captchaText = isset($_SESSION["captcha"]) ? $_SESSION["captcha"] : '';

        // Kiểm tra captcha
        if (empty($captchaValue) || empty($captchaText) || strtolower($captchaValue) !== strtolower($captchaText)) {
            $_alert = '<div class="text-danger pb-2 font-weight-bold">Captcha không đúng. Vui lòng nhập lại!</div>';
            if (!isset($_POST["captcha"])) {
                refreshCaptcha();
            }
        } else {
            // Tiến hành xử lý đăng ký tài khoản
            // Kiểm tra số lượng tài khoản đã đăng ký từ địa chỉ IP hiện tại
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM account WHERE ip_address=:ip_address");
            $stmt->bindParam(':ip_address', $ip_address);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $accountCount = $row['count'];

            if ($accountCount >= 20) {
                $_alert = '<div class="text-danger pb-2 font-weight-bold">Bạn đã đăng ký đủ số lượng tài khoản từ địa chỉ IP này.</div>';

                // Gọi hàm tạo lại CAPTCHA khi nhập sai CAPTCHA
                refreshCaptcha();
            } else {
                $stmt = $conn->prepare("SELECT * FROM account WHERE username=:username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($result) > 0) {
                    $_alert = "<div class='text-danger pb-2 font-weight-bold'>Tài khoản đã tồn tại.</div>";

                    // Gọi hàm tạo lại CAPTCHA khi nhập sai CAPTCHA
                    refreshCaptcha();

                } else {
                    if ($recafcode !== null) {
                        // Kiểm tra xem recafcode đã tồn tại trong bảng không
                        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM account WHERE id=:recaf");
                        $stmt->bindParam(':recaf', $recafcode, PDO::PARAM_INT); // Using PDO::PARAM_INT to ensure the parameter is an integer
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($row['count'] == 0) {
                            $_alert = '<div class="text-danger pb-2 font-weight-bold">Không tìm thấy mã giới thiệu này.</div>';
                            refreshCaptcha();
                        } else {
                            if ($count < 3) {
                                $stmt = $conn->prepare("UPDATE account SET gioithieu = gioithieu + 1 WHERE id = :recaf");
                                $stmt->bindParam(':recaf', $recafcode, PDO::PARAM_INT);
                                if ($stmt->execute()) {
                                    $count++;
                                    $stmt = $conn->prepare("INSERT INTO account (username, password, recaf, ip_address, gmail) VALUES (:username, :password, :recaf, :ip_address, :gmail)");
                                    $stmt->bindParam(':username', $username);
                                    $stmt->bindParam(':password', $password);
                                    $stmt->bindParam(':recaf', $recafcode, PDO::PARAM_INT);
                                    $stmt->bindParam(':ip_address', $ip_address);
                                    $stmt->bindParam(':gmail', $gmail);
                                    if ($stmt->execute()) {
                                        $_alert = '<div class="text-danger pb-2 font-weight-bold">Đăng kí thành công!!</div>';
                                        refreshCaptcha();
                                    } else {
                                        $_alert = '<div class="text-danger pb-2 font-weight-bold">Đăng ký thất bại.</div>';
                                        refreshCaptcha();
                                    }
                                } else {
                                    $_alert = '<div class="text-danger pb-2 font-weight-bold">Có lỗi khi cập nhật số lần nhập mã!</div>';
                                    refreshCaptcha();
                                }
                            } else {
                                $_alert = '<div class="text-danger pb-2 font-weight-bold">Mã giới thiệu này đã đạt đủ số người nhập mã!</div>';
                                refreshCaptcha();
                            }
                        }
                    } else {
                        $stmt = $conn->prepare("INSERT INTO account (username, password, ip_address, gmail) VALUES (:username, :password, :ip_address, :gmail)");
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':password', $password);
                        $stmt->bindParam(':ip_address', $ip_address);
                        $stmt->bindParam(':gmail', $gmail);
                        if ($stmt->execute()) {
                            $_alert = '<div class="text-danger pb-2 font-weight-bold">Đăng kí thành công!!</div>';

                            // Gọi hàm tạo lại CAPTCHA khi nhập sai CAPTCHA
                            refreshCaptcha();

                        } else {
                            $_alert = '<div class="text-danger pb-2 font-weight-bold">Đăng ký thất bại.</div>';

                            // Gọi hàm tạo lại CAPTCHA khi nhập sai CAPTCHA
                            refreshCaptcha();

                        }
                    }
                }
            }
        }
    }
    $conn = null;
}

// Generate captcha when the page loads initially
if (!isset($_SESSION['captcha'])) {
    $captchaText = generateCaptcha(6);
    $_SESSION['captcha'] = $captchaText;
}
?>
<?php include_once 'core/head.php'; ?>

                    <div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
                        <div class="page-layout-body">
                            <!-- load view -->
                            <div class="ant-row">
    <div class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_"><?php echo $_dangky; ?></div>
    </div>
<div class="ant-col ant-col-24">
    <div class="ant-list ant-list-split">
        <div class="ant-spin-nested-loading">
            <div class="ant-spin-container">
                <ul class="ant-list-items">
                                     
    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h4>ĐĂNG KÝ</h4>
                <form id="form" method="POST">
                    <?php if (!empty($recafcode)) { ?>
                        <div class="form-group">
                            <div class="text-center" for="referral_code">
                                Bạn đang đăng ký qua mã giới thiệu:
                                <span>
                                    <?php echo $recafcode; ?>
                                </span>
                                <?php if (!empty($recafUsername)) { ?>
                                    <br>
                                    <span>bởi:
                                        <?php echo $recafUsername; ?>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                   
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Tài khoản:</label>
                        <input class="form-control" type="text" name="username" id="username"
                            placeholder="Nhập tài khoản">
                    </div>
                    <div class="form-group">
                        <label>Gmail:</label>
                        <input class="form-control" type="text" name="gmail" id="gmail"
                            placeholder="Nhập gmail (bắt buộc)">
                    </div>
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Mật khẩu:</label>
                        <!--<input class="form-control" type="password" name="password" id="password"
                            placeholder="Nhập mật khẩu">-->
                    </div>
                    
                    <div class="ant-col ant-form-item-control">
                        <div class="ant-form-item-control-input">
                            <div class="ant-form-item-control-input-content">
                            <span class="ant-input-affix-wrapper ant-input-password">
                        <input placeholder="Nhập mật khẩu" id="password" aria-required="true" type="password" name="password" class="ant-input">
                    <span class="ant-input-suffix">
                <span role="img" aria-label="eye-invisible" tabindex="-1" class="anticon anticon-eye-invisible ant-input-password-icon">
<svg viewBox="64 64 896 896" focusable="false" data-icon="eye-invisible" width="1em" height="1em" fill="currentColor" aria-hidden="true"> <path d="M942.2 486.2Q889.47 375.11 816.7 305l-50.88 50.88C807.31 395.53 843.45 447.4 874.7 512 791.5 684.2 673.4 766 512 766q-72.67 0-133.87-22.38L323 798.75Q408 838 512 838q288.3 0 430.2-300.3a60.29 60.29 0 000-51.5zm-63.57-320.64L836 122.88a8 8 0 00-11.32 0L715.31 232.2Q624.86 186 512 186q-288.3 0-430.2 300.3a60.3 60.3 0 000 51.5q56.69 119.4 136.5 191.41L112.48 835a8 8 0 000 11.31L155.17 889a8 8 0 0011.31 0l712.15-712.12a8 8 0 000-11.32zM149.3 512C232.6 339.8 350.7 258 512 258c54.54 0 104.13 9.36 149.12 28.39l-70.3 70.3a176 176 0 00-238.13 238.13l-83.42 83.42C223.1 637.49 183.3 582.28 149.3 512zm246.7 0a112.11 112.11 0 01146.2-106.69L401.31 546.2A112 112 0 01396 512z"></path> <path d="M508 624c-3.46 0-6.87-.16-10.25-.47l-52.82 52.82a176.09 176.09 0 00227.42-227.42l-52.82 52.82c.31 3.38.47 6.79.47 10.25a111.94 111.94 0 01-112 112z"></path>
                    </svg>
                        </span>
                        </span>
                    </span>                    
                    <label><span class="text-danger">*</span> Mã xác minh:</label>
                    <div class="row">
                        <div class="form-group col-6">
                            <input type="text" class="form-control" name="captcha" id="captcha" maxlength="6"
                                spellcheck="false" style="padding: 6px;" placeholder="Nhập captcha ...">
                        </div>
                        <div class="form-group col-6">
                            <span class="captcha" id="captcha" style="font-size: 15px;">
                                <?php echo isset($_SESSION['captcha']) ? $_SESSION['captcha'] : ''; ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-check form-group">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="accept" id="accept" checked="">
                            Đồng ý <a href=" ">Điều khoản sử dụng</a>
                        </label>
                    </div>
                    <?php if (!empty($_alert)) {
                        echo $_alert;
                    } ?>
                    <div id="notify" class="text-danger pb-2 font-weight-bold"></div>
                    <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit">ĐĂNG KÝ</button>
                </form>

                <script>
                    function redirectToRegisterPage() {
                        <?php if (isset($_SESSION['id'])) { ?>
                            var url = "<?php echo $_domain ?>/dang-ky.php?ref=<?php echo $_SESSION['id'] ?>";
                            window.location.href = url;
                        <?php } else { ?>
                            // Nếu không có phiên đăng nhập, thực hiện chuyển hướng đến trang đăng ký thông thường
                            var url = "<?php echo $_domain ?>/dang-ky.php";
                            window.location.href = url;
                        <?php } ?>
                    }

                    // Kiểm tra định dạng Gmail
                    document.getElementById("form").addEventListener("submit", function (event) {
                        var gmailInput = document.getElementById("gmail");
                        var gmailValue = gmailInput.value.trim();

                        if (gmailValue !== "") {
                            var gmailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
                            if (!gmailPattern.test(gmailValue)) {
                                event.preventDefault();
                                document.getElementById("notify").innerHTML = "<div class='text-danger pb-2 font-weight-bold'>Vui lòng nhập định dạng gmail: @gmail.com.</div>";
                            }
                        }
                    });

                    window.onload = function () {
                        var loggedIn = <?php echo ($_login ? 'true' : 'null'); ?>; // Lấy giá trị từ biến $_login
                        if (loggedIn) {
                            window.location.href = "/"; // Chuyển hướng nếu đã đăng nhập
                        }
                    };
                </script>
        
                                                                    </li>
                                                    </div>
                        <div id="paging" class="d-flex justify-content-end align-items-center flex-wrap">
                        </div>
                                    </ul>
            </div>
        </div>
    </div>
</div>                        <!-- end load view -->
                        </div>
                    </div>
<?php include_once 'core/footer.php'; ?>
</body>
</html>