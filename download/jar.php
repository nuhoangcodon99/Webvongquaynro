<?php
require_once '../core/set.php';
require_once '../core/connect.php';
require_once '../core/head.php';
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
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col">
            <h4>Phiên bản Java</h4>
            <?php
            require_once '../core/connect.php'; // Kết nối đến cơ sở dữ liệu

            // Hàm thực hiện truy vấn và lấy giá trị cột android
            function getJavaLink()
            {
                global $conn; // Sử dụng biến kết nối từ bên ngoài

                try {
                    $query = "SELECT java FROM adminpanel";
                    $statement = $conn->prepare($query);
                    $statement->execute();

                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                    return $result['java'];
                } catch (PDOException $e) {
                    // Xử lý lỗi nếu có
                    return null;
                }
            }

            // Gọi hàm và lấy giá trị cột android
            $javaLink = getJavaLink();
            ?>

            <!-- Hiển thị liên kết tải phiên bản Android -->
            <?php if ($javaLink): ?>
                <p>Link Tải Phiên Bản Java: <a class="text-dark font-weight-bold"
                        href="https://www.mediafire.com/file/2qh3tk5vkuny504/NRO_KONG.jar/file">Tại đây</a></p>
            <?php else: ?>
                <p>Chưa có liên kết tải phiên bản JAR.</p>
            <?php endif; ?>

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