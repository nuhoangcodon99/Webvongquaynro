<?php
require_once 'core/set.php';
require_once 'core/connect.php';
require_once 'core/head.php';
if ($_login === null) {
    echo '<script>window.location.href = "../dang-nhap.php";</script>';
}
?>
<style>
    th,
    td {
        white-space: nowrap;
        padding: 2px 4px !important;
        font-size: 11px;
    }
</style>

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
<div class="container color-forum pt-2">
    <div class="row">
        <div class="col">
            <h6 class="text-center">LỊCH SỬ NẠP THẺ</h6>
            <table class="table table-borderless text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>TÀI KHOẢN</th>
                        <th>MỆNH GIÁ</th>
                        <th>LOẠI THẺ</th>
                        <th>TRẠNG THÁI</th>
                        <th>THỜI GIAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_username)) {
                        $stmt = $conn->prepare("SELECT * FROM trans_log WHERE name = :username ORDER BY id DESC LIMIT 10");
                        $stmt->bindParam(":username", $_username);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($result) > 0) {
                            echo '<tbody>';
                            $count = 1; // Initialize a count variable

                            foreach ($result as $row) {
                                $status = '';

                                switch ($row['status']) {
                                    case 1:
                                        $status = '<span>Thành Công</span>';
                                        break;
                                    case 2:
                                        $status = '<span>Thất Bại</span>';
                                        break;
                                    case 3:
                                        $status = '<span>Sai Mệnh Giá</span>';
                                        break;
                                    default:
                                        $status = '<span>Chờ Duyệt</span>';
                                }

                                echo '<tr>
                <td>' . $count . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . number_format($row['amount']) . 'đ</td>
                <td>' . $row['type'] . '</td>
                <td>' . $status . '</td>
                <td>' . $row['date'] . '</td>
                </tr>';

                                $count++; // Increment the count for the next row
                            }

                            echo '</tbody>';
                        } else {
                            echo '<tbody>
            <tr>
                <td colspan="6" align="center"><span style="font-size:100%;"><< Lịch Sử Trống >></span></td>
            </tr>
        </tbody>';
                        }
                    } else {
                        echo 'Chưa có tên người dùng được cung cấp.';
                    }
                    ?>
                </tbody>
            </table>
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