<?php
require_once 'core/set.php';
require_once 'core/connect.php';
$_alert = null;
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
<div class="container pt-3 pb-5">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="text-center pb-3">
<a href="history.php" class="text-dark"><i class="fas fa-hand-point-right"></i>
Lịch sử nạp <i class="fas fa-hand-point-left"></i></a> </div>
            <h4>NẠP SỐ DƯ</h4>
            <?php if ($_login === null) { ?>
                <p>Bạn chưa đăng nhập? hãy đăng nhập để sử dụng chức năng này</p>
            <?php } else { ?>
                <script type="text/javascript">
                    new WOW().init();
                </script>
                <form method="POST" id="myform">
                    <tbody>
                        <label>Tài Khoản: </label><br>
                        <input type="text" class="form-control form-control-alternative"
                            style="background-color: #DCDCDC; font-weight: bold; color: #696969" name="username" value="<?php echo $_username; ?>
" readonly required>

                        <label>Loại thẻ:</label>
                        <select class="form-control form-control-alternative" name="card_type" required>
                            <option value="">Chọn loại thẻ</option>
                            <?php
                            $cdurl = curl_init("https://thesieutoc.net/card_info.php");
                            curl_setopt($cdurl, CURLOPT_FAILONERROR, true);
                            curl_setopt($cdurl, CURLOPT_FOLLOWLOCATION, true);
                            curl_setopt($cdurl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($cdurl, CURLOPT_CAINFO, __DIR__ . '/api/curl-ca-bundle.crt');
                            curl_setopt($cdurl, CURLOPT_CAPATH, __DIR__ . '/api/curl-ca-bundle.crt');
                            $obj = json_decode(curl_exec($cdurl), true);
                            curl_close($cdurl);
                            $length = count($obj);
                            for ($i = 0; $i < $length; $i++) {
                                if ($obj[$i]['status'] == 1) {
                                    echo '<option value="' . $obj[$i]['name'] . '">' . $obj[$i]['name'] . ' (' . $obj[$i]['chietkhau'] . '%)</option> ';
                                }
                            }
                            ?>
                        </select>
                        <label>Mệnh giá:</label>
                        <select class="form-control form-control-alternative" name="card_amount" required>
                            <option value="">Chọn mệnh giá</option>
                            <option value="10000">10.000</option>
                            <option value="20000">20.000</option>
                            <option value="30000">30.000 </option>
                            <option value="50000">50.000</option>
                            <option value="100000">100.000</option>
                            <option value="200000">200.000</option>
                            <option value="300000">300.000</option>
                            <option value="500000">500.000</option>
                        </select>
                        <label>Số seri:</label>
                        <input type="text" class="form-control form-control-alternative" name="serial" required />
                        <label>Mã thẻ:</label>
                        <input type="text" class="form-control form-control-alternative" name="pin" required /><br>
                        <button type="submit" class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" name="submit">NẠP NGAY</button>

                    </tbody>
                </form>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var lastSubmitTime = 0;
                        $("#myform").submit(function (e) {
                            var now = new Date().getTime();
                            if (now - lastSubmitTime < 5000) {
                                Swal.fire({
                                    title: 'Thông báo',
                                    text: 'Vui lòng đợi ít nhất 5 giây trước khi nạp tiếp',
                                    icon: 'error'
                                });
                                return false;
                            }
                            lastSubmitTime = now;

                            $("#status").html("");
                            e.preventDefault();
                            $.ajax({
                                url: "./ajax/card.php",
                                type: 'post',
                                data: $("#myform").serialize(),
                                success: function (data) {
                                    $("#status").html(data);
                                    document.getElementById("myform").reset();
                                    $("#load_hs").load("./history.php");
                                }
                            });
                        });
                    });
                </script>
            <?php } ?>
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