<?php
require_once '../core/set.php';
require_once '../core/connect.php';
require_once '../core/head.php';
if ($_login === null) {
    echo '<script>window.location.href = "../dang-nhap.php";</script>';
}

// chỉ cho phép tài khoản có admin = 1 truy cập
if ($_admin != 1) {
    echo '<script>window.location.href="/"</script>';
    exit;
}
?>


<div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
                        <div class="page-layout-body">
                            <!-- load view -->
                            <div class="ant-row">
    <div class="row">
        <div class="col">
            <a href="/admincp" style="color: black" class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_">Quay lại Cpanel</a>
        </div>
    </div>
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
                <h4>Kích Hoạt Thành Viên</h4><br>
                <?php if ($_admin != 1) { ?>
                    <p>Bạn không phải là admin! Không thể sài được chứ năng này</p>
                <?php } else { ?>
                    <b class="text text-danger">Lưu Ý: </b><br>
                    - Hãy thoát game trước khi mở thành viên tránh lỗi không mong muốn!
                    <br>
                    - Chỉ dùng cho những tài khoản không bị khóa do vi phạm
                    <br>
                    <br>
                    <?php
                    $_alert = '';

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $_username = $_POST["username"];

                        // Check if the account exists
                        $stmt_check = $conn->prepare("SELECT * FROM account WHERE username = :username");
                        $stmt_check->bindParam(":username", $_username);
                        $stmt_check->execute();
                        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

                        if ($stmt_check->rowCount() == 0) {
                            $_alert = '<div class="alert alert-danger">Lỗi: Tài khoản không tồn tại!</div>';
                        } else {
                            if ($result_check["ban"] == 1) {
                                $_alert = '<div class="alert alert-danger">Lỗi: Tài khoản đã bị vi phạm và không thể kích hoạt!</div>';
                            } else {
                                // Update active status
                                $stmt_update = $conn->prepare("UPDATE account SET active = '1' WHERE username = :username");
                                $stmt_update->bindParam(":username", $_username);
                                if ($stmt_update->execute()) {
                                    if ($stmt_update->rowCount() == 1) {
                                        $_alert = '<div class="alert alert-success">Kích hoạt thành viên thành công cho tài khoản: ' . $_username . '!</div>';
                                    } else {
                                        $_alert = '<div class="alert alert-danger">Tài khoản: ' . $_username . ' đã kích hoạt thành viên rồi!</div>';
                                    }
                                } else {
                                    $_alert = '<div class="alert alert-danger">Lỗi: Kết nối đến máy chủ</div>';
                                }
                            }
                        }

                        $stmt_check = null;
                        $stmt_update = null;
                    }

                    $conn = null;
                    ?>

                    <!-- Hiển thị biến $_alert -->
                    <?php
                    echo $_alert;
                    ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Tên Tài Khoản:</label>
                            <input type="username" class="form-control" name="username" id="username"
                                placeholder="Nhập tên tài khoản" required autocomplete="username">
                        </div>
                        <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit">Kích Hoạt</button>
                    </form>
                <?php } ?>
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