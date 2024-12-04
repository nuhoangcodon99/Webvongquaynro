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
            <br>
            <br>
            <h6> THÔNG TIN VỀ GIÁ TRỊ NẠP</h6>
            <?php if ($_admin != 1) { ?>
                <p>Bạn không phải là admin! Không thể sài được chứ năng này</p>
            <?php } else { ?>
                1. Thông tin chung
                <br>
                - Giá trị thực nhận: x1
                <br>
                - Có thể thay đổi giá trị
                <br>
                - Chỉ áp dụng cho thẻ cào
                <br>
                2. Sửa đổi
                <br>
                - Giá trị nạp sẽ được áp dụng trực tiếp vào CallBack
                <br>
                - Vẫn có thể thay đổi giá trị
                <br>
                <br>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Lấy giá trị nhập từ form
                    $giatri = $_POST['giatri'];

                    // Kiểm tra giá trị nạp
                    if ($giatri >= 1) {
                        // Cập nhật giá trị vào cột "giatri" trong bảng "trans_log"
                        $query = "UPDATE trans_log SET giatri = :giatri";
                        $statement = $conn->prepare($query);
                        $statement->bindParam(':giatri', $giatri, PDO::PARAM_STR);

                        if ($statement->execute()) {
                            echo "Sửa giá nạp thành công!";
                        } else {
                            echo "Lỗi khi cập nhật giá trị";
                        }
                    } else {
                        echo "Giá trị nạp không hợp lệ!";
                    }
                }

                // Đóng kết nối đến cơ sở dữ liệu
                $conn = null;
                ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="font-weight-bold">Giá trị nạp thẻ:</label>
                        <input type="number" class="form-control" name="giatri" id="giatri"
                            placeholder="Giá trị cần thay đổi nạp (số từ 1 trở đi)" required min="1">
                    </div>
                    <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit">Thực hiện</button>
                </form>
                <div id="notification"></div>
            <?php } ?>
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