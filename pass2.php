<?php
require_once 'core/set.php';
require_once 'core/connect.php';
require_once 'core/head.php';

if ($_login === null) {
    echo '<script>window.location.href = "../dang-nhap.php";</script>';
    exit; // Add exit to stop executing further code
}

function renderForm($mkc2)
{
    if (!empty($mkc2)) {
        $oldPasswordLabel = "Mã Bảo Vệ Hiện Tại";
        $oldPasswordName = "old_passwordcap2";
    } else {
        $oldPasswordLabel = "Mật Khẩu Hiện Tại";
        $oldPasswordName = "password";
    }
    ?>
    <form method="POST">
        <div class="mb-3">
            <label class="font-weight-bold">
                <?php echo $oldPasswordLabel; ?>:
            </label>
            <input type="password" class="form-control" name="<?php echo $oldPasswordName; ?>"
                id="<?php echo $oldPasswordName; ?>" placeholder="<?php echo $oldPasswordLabel; ?>" required
                autocomplete="off">
        </div>
        <div class="mb-3">
            <label class="font-weight-bold">Mã Bảo Vệ Mới:</label>
            <input type="password" class="form-control" name="new_passwordcap2" id="new_passwordcap2"
                placeholder="Mã Bảo Vệ mới" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label class="font-weight-bold">Nhập Lại Mã Bảo Vệ:</label>
            <input type="password" class="form-control" name="new_passwordcap2xacnhan" id="new_passwordcap2xacnhan"
                placeholder="Xác nhận Mã Bảo Vệ" required autocomplete="off">
        </div>
        <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit">Thực hiện</button>
    </form>
    <?php
}

function displayMessage($message)
{
    echo "<div class='text-danger pb-2 font-weight-bold'>$message</div>";
}

$stmt = $conn->prepare("SELECT password, mkc2 FROM account WHERE username=:username");
$stmt->bindParam(":username", $_username);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$primaryPassword = $row['password'];
$mkc2 = $row['mkc2'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user input here

    if (!empty($mkc2)) {
        $password = $_POST['password'] ?? '';
        $oldPassword = $_POST['old_passwordcap2'] ?? '';

        if (!empty($password) && !empty($oldPassword)) {
            if ($password !== $primaryPassword) {
                displayMessage("Sai mật khẩu hiện tại");
            } elseif ($oldPassword !== $mkc2) {
                displayMessage("Sai Mã bảo vệ hiện tại");
            } elseif ($_POST['new_passwordcap2'] === $password) {
                displayMessage("Mã bảo vệ không được giống mật khẩu hiện tại");
            } elseif ($_POST['new_passwordcap2'] !== $_POST['new_passwordcap2xacnhan']) {
                displayMessage("Mã bảo vệ không giống nhau");
            } else {
                $newPasswordCap2 = $_POST['new_passwordcap2'];
                $stmt = $conn->prepare("UPDATE account SET mkc2=:new_passwordcap2 WHERE username=:username");
                $stmt->bindParam(":new_passwordcap2", $newPasswordCap2);
                $stmt->bindParam(":username", $_username);

                if ($stmt->execute()) {
                    displayMessage("Cập nhật Mã bảo vệ thành công");
                } else {
                    displayMessage("Lỗi khi cập nhật Mã bảo vệ");
                }
            }
        } else {
            displayMessage("Vui lòng điền đầy đủ thông tin trong form");
        }
    } else {
        $password = $_POST['password'] ?? '';

        if (!empty($password)) {
            if ($password !== $primaryPassword) {
                displayMessage("Sai mật khẩu hiện tại");
            } elseif ($_POST['new_passwordcap2'] === $password) {
                displayMessage("Mã bảo vệ không được giống mật khẩu hiện tại");
            } elseif ($_POST['new_passwordcap2'] !== $_POST['new_passwordcap2xacnhan']) {
                displayMessage("Mã bảo vệ không giống nhau");
            } else {
                $newPasswordCap2 = $_POST['new_passwordcap2'];
                $stmt = $conn->prepare("UPDATE account SET mkc2=:new_passwordcap2 WHERE username=:username");
                $stmt->bindParam(":new_passwordcap2", $newPasswordCap2);
                $stmt->bindParam(":username", $_username);

                if ($stmt->execute()) {
                    displayMessage("Cập nhật Mã bảo vệ thành công");
                } else {
                    displayMessage("Lỗi khi cập nhật Mã bảo vệ");
                }
            }
        } else {
            displayMessage("Vui lòng điền đầy đủ thông tin trong form");
        }
    }
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
            <h4>BẢO MẬT CẤP 2 - BẢO VỆ TÀI KHOẢN</h4>
            <?php
            renderForm($mkc2);
            ?>
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