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
        <div class="col-lg-6 offset-lg-3">
            <h4>GIỚI THIỆU NGƯỜI CHƠI MỚI</h4><br>
            <?php if ($_login === null) { ?>
                <p>Bạn chưa đăng nhập? Hãy đăng nhập để dùng được chức năng này</p>
            <?php } else { ?>
                <b class="text-danger">Cư dân đã giới thiệu được :
                </b>
                <b>
                    <?php echo $_gioithieu; ?> Người
                </b>
                <br><br>
                <b> Link Giới Thiệu:</b>
                <b>
                    <style>
                        #notification {
                            font-size: 12px;
                        }

                        a {
                            text-decoration: none;
                        }

                        a:hover {
                            text-decoration: none;
                        }
                    </style>
                </b>
                <a href="<?php echo $_domain ?>/dang-ky.php?ref=<?php echo $_SESSION['id'] ?>" onclick="copyLink(event)">
                    <span style="color: black;">
                        <?php echo $_domain ?>/dang-ky.php?ref=
                        <?php echo $_SESSION['id'] ?>
                    </span>
                </a>
                <br>
                <br>
                <div id="notification"></div>
                <script>
                    function copyLink(event) {
                        event.preventDefault(); // Ngăn chặn chuyển hướng khi nhấp vào liên kết

                        var link = event.currentTarget.getAttribute('href'); // Lấy đường dẫn từ liên kết
                        navigator.clipboard.writeText(link) // Sao chép đường dẫn vào clipboard
                            .then(function () {
                                // Sao chép thành công
                                document.getElementById("notification").innerText = "Bạn đã sao chép liên kết giới thiệu thành công!";
                            })
                            .catch(function (error) {
                                // Sao chép thất bại
                                console.error(error);
                                document.getElementById("notification").innerText = "Có lỗi xảy ra khi sao chép liên kết giới thiệu.";
                            });
                    }
                </script>
                </b>
                <br>
                <br>
                <?php
                if ($_gioithieu > 0) {
                    // Output the number of referrals
                    echo '<span class="text-danger"><strong>Số Điểm Giới Thiệu Là ' . htmlspecialchars($_gioithieu, ENT_QUOTES, 'UTF-8') . ' Người Bạn Nhận Được :</strong></span><br>';

                    // Determine the reward based on the number of referrals
                    $reward = ($_gioithieu == 1) ? '5,000 VNĐ' : (($_gioithieu == 2) ? '10,000 VNĐ' : '15,000 VNĐ');
                    echo "#<b><span>" . htmlspecialchars($reward, ENT_QUOTES, 'UTF-8') . "</span></b><br>";
                }
                ?>

                <br>
                <br>
                <b class="text text-danger">Phổ Biến Luật Sự Kiện: </b><br>
                <b>- Đây là Link riêng của mỗi cư dân Twitch
                    <br>
                    - Người chơi phải đăng ký thành công mới được tính điểm
                    <br>
                    - Chỉ tính điểm với người chơi mới, tối đa là 3 người chơi mới
                    <br>
                    <br>
                    <span style="color:red"><strong>Quan Trọng : <span style="color:212529"></strong></span></span>
                    <br>
                    <b>- Các cư dân lưu ý không <span style="color:red">spam</span> để tránh làm phiền người chơi khác
                        <br>
                        - Mỗi tài khoản chỉ đạt được <span style="color:red">3 Điểm</span> thôi và phần quà sẽ gửi vào <span
                            style="color:red">hành trang</span> của cư dân khi đạt đủ số điểm tích luỹ
                        <br>
                        - Khi cư dân đạt đủ <span style="color:red">1-3 Điểm</span> tích luỹ thì sẽ hiển thị nút <span
                            style="color:red">Đổi Quà</span>
                        <br>
                        <br>
                        <?php
                        // Các giá trị mốc quà và điểm tương ứng
                        $moc_qua = [
                            1 => [
                                'diem' => 1,
                                'gia_tri' => 5000
                            ],
                            2 => [
                                'diem' => 2,
                                'gia_tri' => 10000
                            ],
                            3 => [
                                'diem' => 3,
                                'gia_tri' => 15000
                            ]
                        ];

                        // Kiểm tra xem người chơi có điểm tích luỹ hay không
                        if ($_gioithieu > 0) {
                            // Kiểm tra xem điểm tích luỹ nằm trong mốc quà nào
                            if (array_key_exists($_gioithieu, $moc_qua)) {
                                $moc = $moc_qua[$_gioithieu];
                                $diem_moc = $moc['diem'];
                                $gia_tri_moc = $moc['gia_tri'];

                                // Kiểm tra xem người chơi đã nhấn nút "Đổi Quà" hay chưa
                                if (isset($_POST['doi_qua'])) {
                                    // Thêm giá trị của mốc quà vào cột "vnd"
                                    $_coin += $gia_tri_moc;

                                    // Thực hiện trừ điểm tích luỹ tương ứng với mốc đó
                                    $_gioithieu -= $diem_moc;

                                    // Cập nhật cơ sở dữ liệu với giá trị mới của cột "vnd" và "gioithieu"
                                    $sql = "UPDATE account SET vnd = :coin, gioithieu = :gioithieu WHERE id = :id";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bindParam(":coin", $_coin, PDO::PARAM_INT);
                                    $stmt->bindParam(":gioithieu", $_gioithieu, PDO::PARAM_INT);
                                    $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);

                                    if ($stmt->execute()) {
                                        echo "Đổi quà thành công!";
                                    } else {
                                        echo "Lỗi cập nhật cơ sở dữ liệu: " . htmlspecialchars($stmt->errorInfo()[2], ENT_QUOTES, 'UTF-8');
                                    }
                                }
                            }
                        }
                        ?>

                        <!-- Hiển thị nút "Đổi Quà" nếu người chơi có điểm tích luỹ -->
                        <?php if ($_gioithieu > 0): ?>
                            <form method="POST">
                                <input class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit" name="doi_qua" value="Đổi Quà">
                            </form>
                        <?php endif;
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
<?php include_once '/core/footer.php'; ?>
</body>
</html>