<?php
require_once 'core/set.php';
require_once 'core/connect.php';
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
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <h4>ĐỔI MẬT KHẨU</h4>
            <?php if ($_login === null) { ?>
                <p>Bạn chưa đăng nhập? hãy đăng nhập để sử dụng chức năng này</p>
            <?php } else { ?>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Lấy mật khẩu hiện tại của người dùng từ cơ sở dữ liệu
                    $stmt = $conn->prepare("SELECT password, mkc2 FROM account WHERE username=:username");
                    $stmt->bindParam(":username", $_username);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $matkhaucu = $row['password'];
                    $mkc2 = $row['mkc2'];

                    $matKhauHienTai = $_POST['password'] ?? '';
                    $matKhauMoi = $_POST['new_password'] ?? '';
                    $xacNhanMatKhauMoi = $_POST['new_password_confirmation'] ?? '';

                    if (!empty($matkhaucu) && !empty($mkc2)) {
                        $matKhauCap2 = isset($_POST['passwordcap2']) ? $_POST['passwordcap2'] : '';

                        if (!empty($matKhauHienTai) && !empty($matKhauMoi) && !empty($xacNhanMatKhauMoi) && !empty($matKhauCap2)) {
                            // Xác thực mật khẩu hiện tại
                            if ($matKhauHienTai !== $matkhaucu) {
                                echo "<div class='alert alert-danger'>Sai mật khẩu hiện tại</div>";
                            } elseif ($matKhauCap2 !== $mkc2) {
                                echo "<div class='alert alert-danger'>Sai Mã bảo vệ</div>";
                            } elseif ($matKhauMoi === $matKhauHienTai) {
                                echo "<div class='alert alert-danger'>Mật khẩu mới không được giống mật khẩu hiện tại</div>";
                            } elseif ($matKhauMoi !== $xacNhanMatKhauMoi) {
                                echo "<div class='alert alert-danger'>Mật khẩu mới không giống nhau</div>";
                            } else {
                                // Cập nhật mật khẩu mới vào cơ sở dữ liệu
                                $stmt = $conn->prepare("UPDATE account SET password=:matKhauMoi WHERE username=:username");
                                $stmt->bindParam(":matKhauMoi", $matKhauMoi);
                                $stmt->bindParam(":username", $_username);

                                if ($stmt->execute()) {
                                    echo "<div class='alert alert-success'>Cập nhật mật khẩu mới thành công</div>";
                                    $matkhaucu = $matKhauMoi; // Cập nhật giá trị của biến matkhaucu sau khi cập nhật thành công
                                } else {
                                    echo "<div class='alert alert-danger'>Lỗi khi cập nhật mật khẩu mới</div>";
                                }
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Vui lòng điền đầy đủ thông tin trong form</div>";
                        }
                    } else {
                        if (!empty($matKhauHienTai) && !empty($matKhauMoi) && !empty($xacNhanMatKhauMoi)) {
                            // Kiểm tra mật khẩu hiện tại
                            if ($matKhauHienTai !== $matkhaucu) {
                                echo "<div class='alert alert-danger'>Sai mật khẩu hiện tại</div>";
                            } else {
                                // Tạo mật khẩu mới
                                $stmt = $conn->prepare("UPDATE account SET password=:matKhauMoi WHERE username=:username");
                                $stmt->bindParam(":matKhauMoi", $matKhauMoi);
                                $stmt->bindParam(":username", $_username);

                                if ($stmt->execute()) {
                                    echo "<div class='alert alert-success'>Tạo mật khẩu mới thành công</div>";
                                    $matkhaucu = $matKhauMoi; // Cập nhật giá trị của biến matkhaucu sau khi tạo thành công
                                } else {
                                    echo "<div class='alert alert-danger'>Lỗi khi tạo mật khẩu mới</div>";
                                }
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Vui lòng điền đầy đủ thông tin trong form</div>";
                        }
                    }
                }

                if (!empty($mkc2)) {
                    ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="font-weight-bold">Mật Khẩu hiện tại:</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Mật khẩu hiện tại" required autocomplete="password">
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Mã bảo vệ:</label>
                            <input type="password" class="form-control" name="passwordcap2" id="passwordcap2"
                                placeholder="Mã bảo vệ" required autocomplete="passwordcap2">
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Mật Khẩu Mới:</label>
                            <input type="password" class="form-control" name="new_password" id="new_password"
                                placeholder="Mật khẩu mới" required autocomplete="new_password">
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Xác Nhận Mật Khẩu Mới:</label>
                            <input type="password" class="form-control" name="new_password_confirmation"
                                id="new_password_confirmation" placeholder="Xác nhận mật khẩu mới" required
                                autocomplete="new_password_confirmation">
                        </div>
                        <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit">Thực hiện</button>
                    </form>
                <?php } else { ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="font-weight-bold">Mật Khẩu hiện tại:</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Mật khẩu hiện tại" required autocomplete="password">
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Mã bảo vệ:</label>
                            <input type="password" class="form-control" name="passwordcap2" id="passwordcap2"
                                placeholder="Mã bảo vệ" required autocomplete="passwordcap2">
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Mật Khẩu Mới:</label>
                            <input type="password" class="form-control" name="new_password" id="new_password"
                                placeholder="Mật khẩu mới" required autocomplete="new_password">
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Xác Nhận Mật Khẩu Mới:</label>
                            <input type="password" class="form-control" name="new_password_confirmation"
                                id="new_password_confirmation" placeholder="Xác nhận mật khẩu mới" required
                                autocomplete="new_password_confirmation">
                        </div>
                        <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit">Thực hiện</button>
                    </form>
                <?php }
            } ?>
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
<?php include_once 'core/footer.php'; ?>
</body>
</html>