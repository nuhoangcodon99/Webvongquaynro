<?php
require_once 'core/connect.php';
require_once 'core/set.php';
require_once 'core/head.php';
if ($_login === true) {
    echo '<script>window.location.href = "/";</script>';
} else {

}

?>

<div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
                        <div class="page-layout-body">
                            <!-- load view -->
                            <div class="ant-row">
    <div class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_">QUÊN MẬT KHẨU</div>
    </div>
<div class="ant-col ant-col-24">
    <div class="ant-list ant-list-split">
        <div class="ant-spin-nested-loading">
            <div class="ant-spin-container">
                <ul class="ant-list-items">
        </div>
    </div>
</div>
<div id="data_news">
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <h4 class="text-center">QUÊN MẬT KHẨU</h4>
            <?php
            require 'vendor/autoload.php';
            require 'vendor/phpmailer/phpmailer/src/Exception.php';
            require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
            require 'vendor/phpmailer/phpmailer/src/SMTP.php';
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;

            function generateRandomPassword($length = 6)
            {
                $characters = '0123456789';
                $password = '';

                for ($i = 0; $i < $length; $i++) {
                    $password .= $characters[rand(0, strlen($characters) - 1)];
                }

                return $password;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $gmail = $_POST['gmail'];

                // Thực hiện kết nối tới cơ sở dữ liệu MySQL bằng PDO
                $servername = "localhost";
                $dbname = "nro";
                $username_db = "root";
                $password_db = "";

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username_db, $password_db);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Kiểm tra tên người dùng và gmail trong cơ sở dữ liệu
                    $checkQuery = "SELECT gmail FROM account WHERE username = :username";
                    $stmt = $conn->prepare($checkQuery);
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();
                    $storedGmail = $stmt->fetchColumn();
                    $stmt->closeCursor();

                    if ($storedGmail === $gmail) {
                        // Tạo một mật khẩu mới ngẫu nhiên
                        $newPassword = generateRandomPassword();

                        // Cập nhật mật khẩu mới vào cơ sở dữ liệu
                        $updateQuery = "UPDATE account SET password = :newPassword WHERE username = :username";
                        $stmt = $conn->prepare($updateQuery);
                        $stmt->bindParam(':newPassword', $newPassword);
                        $stmt->bindParam(':username', $username);
                        $stmt->execute();
                        $stmt->closeCursor();

                        // Tạo một đối tượng PHPMailer và cấu hình
                        $mail = new PHPMailer(true);
                        try {
                            // Cấu hình gửi email thông qua Gmail
                            $mail->SMTPDebug = 0;
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'nrokong2024@gmail.com'; // Thay đổi thành email của bạn
                            $mail->Password = 'quowqcmfwmpuduvp'; // Thay đổi thành mật khẩu của bạn
                            $mail->SMTPSecure = 'tls';
                            $mail->Port = 587;

                            // Thiết lập thông tin email
                            $mail->setFrom('nrokong2024@gmail.com', 'Ngọc Rồng Kuroko '); // Thay đổi thành email của bạn và tên của bạn
                            $mail->addAddress($gmail);
                            $mail->Subject = '=?UTF-8?B?' . base64_encode('Quên Mật Khẩu - Ngọc Rồng Kuroko ') . '?=';
                            $mail->Body = "Xin chào bạn,\n\nTài khoản $username đang thực hiện Quên mật khẩu.\n\nThông tin tài khoản của bạn:\n- Tài khoản: $username \n- Mật khẩu mới: $newPassword \n\nLưu ý: nếu bạn không yêu cầu lấy lại mật khẩu mới, vui lòng bỏ qua email này và bảo mật thông tin tài khoản của bạn\n\nAdmin chân thành cảm ơn bạn đã tin tưởng và đồng hành cùng " . $_tenmaychu . "!\n\n" . $_tenmaychu;
                            $mail->CharSet = 'UTF-8';
                            $mail->Encoding = 'base64';

                            // Gửi thư
                            $mail->send();
                            echo "<br>Email đã được gửi thành công đến địa chỉ: " . $gmail;
                        } catch (Exception $e) {
                            echo "<br>Có lỗi xảy ra khi gửi email: " . $mail->ErrorInfo;
                        }
                    } else {
                        echo "<br>Không tìm thấy Gmail của người dùng với tài khoản: " . $username;
                    }
                } catch (PDOException $e) {
                    echo "Kết nối thất bại: " . $e->getMessage();
                }

                // Đóng kết nối cơ sở dữ liệu
                $conn = null;
            }
            ?>
            <form id="form" method="POST">
                <div class="form-group"><br>
                    <label>Tài khoản:</label>
                    <input class="form-control" type="text" name="username" id="username"
                        placeholder="Nhập tên tài khoản">
                </div>
                <div class="form-group">
                    <label>Gmail:</label>
                    <input class="form-control" type="gmail" name="gmail" id="gmail" placeholder="Nhập Gmail của bạn">
                </div>
                <br>
                <div id="notify" class="text-danger pb-2 font-weight-bold"></div>
                <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit">XÁC NHẬN</button>
            </form>
            <br>
            <div class="text-center">
                <p>Bạn đã lấy lại tài khoản? <a href="/dang-nhap.php">Đăng nhập tại đây</a></p>
            </div>
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