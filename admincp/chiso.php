<?php
require_once '../core/set.php';
require_once '../core/connect.php';
require_once '../core/head.php';
require_once '../core/cauhinh.php';

if ($_login === null) {
    echo '<script>window.location.href = "../dang-nhap.php";</script>';
    exit;
}

// chỉ cho phép tài khoản có admin = 1 truy cập
if ($_admin != 1) {
    echo '<script>window.location.href="/"</script>';
    exit;
}

$_active = $_active ?? null;
$_tcoin = $_tcoin ?? 0;
$serverIP = $serverIP ?? '';
$serverPort = $serverPort ?? '';

$id_user_query = "SELECT COUNT(id) AS id FROM account";
$statement = $conn->prepare($id_user_query);
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);
$id = $row['id'];

$result = _select("COUNT(*) as ban", "account", "ban = 1");
$row = _fetch($result);
$_tongban = $row["ban"];

$result2 = _select("COUNT(*) as active", "account", "active = 1");
$row = _fetch($result2);
$_tongactive = $row["active"];

$sql = "SELECT COUNT(*) AS recaf FROM account WHERE recaf IS NOT NULL";
$statement = $conn->prepare($sql);
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);
$_recaf = $row['recaf'];

function get_user_ip()
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $addr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($addr[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
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
            <h4>VẬT PHẨM</h4><br>
            <?php if ($_admin != 1) { ?>
                <p>Bạn không phải là admin! Không thể sài được chứ năng này</p>
            <?php } else { ?>
                <b class="text text-danger">Phổ Biến Thông Tin: </b><br>
                <b>- Ví Dụ:
                    <br>
                    - ID: 457 (Thỏi Vàng)
                    <br>
                    - Số Lượng: 1 (Đây Là Số Lượng Vật Phẩm)
                    <br>
                    - Chỉ Số: Tấn Công (Chọn Chỉ Số Bất Kì)
                    <br>
                    - Phần Trăm Chỉ Số: 10 (Đây Là 10% Chỉ Số)
                    <br>
                    <br>
                    <br>
                    <?php

                    $_alert = '';

                    // Xử lý dữ liệu form khi submit
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Lấy giá trị từ form
                        $name = $_POST["name"];
                        $sucmanh = isset($_POST["sucmanh"]) ? intval($_POST["sucmanh"]) : 0;
                        $tiemnang = isset($_POST["tiemnang"]) ? intval($_POST["tiemnang"]) : 0;
                        $hp = isset($_POST["hp"]) ? intval($_POST["hp"]) : 0;
                        $mp = isset($_POST["mp"]) ? intval($_POST["mp"]) : 0;
                        $sdg = isset($_POST["sdg"]) ? intval($_POST["sdg"]) : 0;
                        $giapgoc = isset($_POST["giapgoc"]) ? intval($_POST["giapgoc"]) : 0;
                        $chimang = isset($_POST["chimang"]) ? intval($_POST["chimang"]) : 0;

                        // Kiểm tra tính hợp lệ của dữ liệu
                        if (!empty($name)) {

                            // Tìm nhân vật trong CSDL
                            $sql = "SELECT * FROM player WHERE name=:name";
                            $statement = $conn->prepare($sql);
                            $statement->bindParam(':name', $name, PDO::PARAM_STR);
                            $statement->execute();

                            if ($statement->rowCount() > 0) {
                                // Nhân vật tồn tại, cộng chỉ số cho nhân vật
                                $row = $statement->fetch(PDO::FETCH_ASSOC);
                                $data_point = json_decode($row["data_point"], true); // Chuyển đổi JSON thành mảng

                                $select_property = isset($_POST["select-property"]) ? $_POST["select-property"] : "";

                                // cập nhật giá trị mới cho các chỉ số trong mảng $data_point
                                switch ($select_property) {
                                    case 'sucmanh':
                                        $data_point[1] += $sucmanh;
                                        break;
                                    case 'tiemnang':
                                        $data_point[2] += $tiemnang;
                                        break;
                                    case 'hp':
                                        $data_point[5] += $hp;
                                        break;
                                    case 'mp':
                                        $data_point[6] += $mp;
                                        break;
                                    case 'sdg':
                                        $data_point[7] += $sdg;
                                        break;
                                    case 'giapgoc':
                                        $data_point[8] += $giapgoc;
                                        break;
                                    case 'chimang':
                                        $data_point[9] += $chimang;
                                        break;
                                    case 'congtoanbo':
                                        $data_point[1] += isset($_POST["congtoanbo-sucmanh"]) ? intval($_POST["congtoanbo-sucmanh"]) : 0;
                                        $data_point[2] += isset($_POST["congtoanbo-tiemnang"]) ? intval($_POST["congtoanbo-tiemnang"]) : 0;
                                        $data_point[5] += isset($_POST["congtoanbo-hp"]) ? intval($_POST["congtoanbo-hp"]) : 0;
                                        $data_point[6] += isset($_POST["congtoanbo-mp"]) ? intval($_POST["congtoanbo-mp"]) : 0;
                                        $data_point[7] += isset($_POST["congtoanbo-sdg"]) ? intval($_POST["congtoanbo-sdg"]) : 0;
                                        $data_point[8] += isset($_POST["congtoanbo-giapgoc"]) ? intval($_POST["congtoanbo-giapgoc"]) : 0;
                                        $data_point[9] += isset($_POST["congtoanbo-chimang"]) ? intval($_POST["congtoanbo-chimang"]) : 0;
                                        break;
                                    default:
                                        break;
                                }

                                // Chuyển đổi lại thành JSON
                                $updated_data_point = json_encode($data_point);

                                // Cập nhật chỉ số mới vào CSDL
                                $sql = "UPDATE player SET data_point=:data_point WHERE name=:name";
                                $statement_update = $conn->prepare($sql);
                                $statement_update->bindParam(':data_point', $updated_data_point, PDO::PARAM_STR);
                                $statement_update->bindParam(':name', $name, PDO::PARAM_STR);

                                if ($statement_update->execute()) {
                                    $_alert = '<div class="alert alert-success">Cộng chỉ số thành công!</div>';
                                } else {
                                    $_alert = '<div class="alert alert-danger">Lỗi kết nối đến máy chủ!</div>';
                                }

                            } else {
                                // Nhân vật không tồn tại
                                $_alert = '<div class="alert alert-warning">Nhân vật không tồn tại!</div>';
                            }
                        } else {
                            // Tên tài khoản không được để trống
                            $_alert = '<div class="alert alert-warning">Vui lòng nhập tên nhân vật!</div>';
                        }

                        // Ngắt kết nối CSDL
                        $conn = null;
                    }
                    ?>

                    <!-- Hiển thị biến $_alert -->
                    <?php
                    echo $_alert;
                    ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="font-weight-bold">Tên Tài Khoản:</label>
                            <input type="name" class="form-control" name="name" id="name" placeholder="Nhập tên tài khoản"
                                required autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Chỉ Số:</label>
                            <select class="form-control" id="select-property" name="select-property">
                                <option value="none">Chọn Chỉ Số</option>
                                <option value="sucmanh">Sức Mạnh</option>
                                <option value="sucmanh">Tiềm Năng</option>
                                <option value="hp">HP</option>
                                <option value="mp">MP</option>
                                <option value="sdg">Sức Đánh Gốc</option>
                                <option value="giapgoc">Giáp Gốc</option>
                                <option value="chimang">Chí Mạng</option>
                                <option value="congtoanbo">Cộng Toàn Bộ Chỉ Số</option>
                            </select>
                        </div>

                        <div class="mb-3" id="sucmanh-input" style="display:none;">
                            <label class="font-weight-bold">Sức Mạnh:</label>
                            <input type="sucmanh" class="form-control" name="sucmanh" id="sucmanh"
                                placeholder="Nhập chỉ số Sức Mạnh" required autocomplete="sucmanh">
                        </div>

                        <div class="mb-3" id="tiemnang-input" style="display:none;">
                            <label class="font-weight-bold">Sức Mạnh:</label>
                            <input type="tiemnang" class="form-control" name="tiemnang" id="tiemnang"
                                placeholder="Nhập tiềm năng cần cộng" required autocomplete="tiemnang">
                        </div>

                        <div class="mb-3" id="hp-input" style="display:none;">
                            <label class="font-weight-bold">HP:</label>
                            <input type="hp" class="form-control" name="hp" id="hp" placeholder="Nhập chỉ số HP" required
                                autocomplete="hp">
                        </div>

                        <div class="mb-3" id="mp-input" style="display:none;">
                            <label class="font-weight-bold">MP:</label>
                            <input type="mp" class="form-control" name="mp" id="mp" placeholder="Nhập chỉ số MP" required
                                autocomplete="mp">
                        </div>

                        <div class="mb-3" id="sdg-input" style="display:none;">
                            <label class="font-weight-bold">Sức Đánh Gốc:</label>
                            <input type="sdg" class="form-control" name="sdg" id="sdg"
                                placeholder="Nhập chỉ số Sức Đánh Gốc" required autocomplete="sdg">
                        </div>

                        <div class="mb-3" id="giapgoc-input" style="display:none;">
                            <label class="font-weight-bold">Giáp Gốc:</label>
                            <input type="giapgoc" class="form-control" name="giapgoc" id="giapgoc"
                                placeholder="Nhập chỉ số Giáp Gốc" required autocomplete="giapgoc">
                        </div>

                        <div class="mb-3" id="chimang-input" style="display:none;">
                            <label class="font-weight-bold">Chí Mạng:</label>
                            <input type="chimang" class="form-control" name="chimang" id="chimang"
                                placeholder="Nhập chỉ số Chí Mạng" required autocomplete="chimang">
                        </div>

                        <div class="mb-3" id="congtoanbo-input" style="display:none;">
                            <!-- Cộng Chỉ Số Sức Mạnh -->
                            <label class="font-weight-bold">Sức Mạnh:</label>
                            <input type="sucmanh" class="form-control" name="congtoanbo-sucmanh" id="congtoanbo-sucmanh"
                                placeholder="Nhập chỉ số Sức Mạnh" required autocomplete="sucmanh">
                            <!-- Cộng Chỉ Số Tiềm Năng -->
                            <label class="font-weight-bold">Tiềm Năng:</label>
                            <input type="tiemnang" class="form-control" name="congtoanbo-tiemnang" id="congtoanbo-tiemnang"
                                placeholder="Nhập chỉ số Tiềm Năng" required autocomplete="tiemnang">
                            <!-- Cộng Chỉ Số HP-->
                            <label class="font-weight-bold">HP:</label>
                            <input type="hp" class="form-control" name="congtoanbo-hp" id="congtoanbo-hp"
                                placeholder="Nhập chỉ số HP" required autocomplete="hp">
                            <!-- Cộng Chỉ Số MP -->
                            <label class="font-weight-bold">MP:</label>
                            <input type="mp" class="form-control" name="congtoanbo-mp" id="congtoanbo-mp"
                                placeholder="Nhập chỉ số MP" required autocomplete="mp">
                            <!-- Cộng Chỉ Số Sức Đánh Gốc -->
                            <label class="font-weight-bold">Sức Đánh Gốc:</label>
                            <input type="sdg" class="form-control" name="congtoanbo-sdg" id="congtoanbo-sdg"
                                placeholder="Nhập chỉ số Sức Đánh Gốc" required autocomplete="sdg">
                            <!-- Cộng Chỉ Số Giáp Gốc -->
                            <label class="font-weight-bold">Giáp Gốc:</label>
                            <input type="giapgoc" class="form-control" name="congtoanbo-giapgoc" id="congtoanbo-giapgoc"
                                placeholder="Nhập chỉ số Giáp Gốc" required autocomplete="giapgoc">
                            <!-- Cộng Chỉ Số Chí Mạng -->
                            <label class="font-weight-bold">Chí Mạng:</label>
                            <input type="chimang" class="form-control" name="congtoanbo-chimang" id="congtoanbo-chimang"
                                placeholder="Nhập chỉ số Chí Mạng" required autocomplete="chimang">
                        </div>
                        <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit">Thực Hiện</button>
                    </form>

                    <script>
                        document.getElementById('select-property').addEventListener('change', function () {
                            var value = this.value;
                            switch (value) {
                                case 'none':
                                    hideAllInputs();
                                    break;
                                case 'sucmanh':
                                    hideAllInputs();
                                    showInput('sucmanh-input');
                                    break;
                                case 'tiemnang':
                                    hideAllInputs();
                                    showInput('tiemnang-input');
                                    break;
                                case 'hp':
                                    hideAllInputs();
                                    showInput('hp-input');
                                    break;
                                case 'mp':
                                    hideAllInputs();
                                    showInput('mp-input');
                                    break;
                                case 'sdg':
                                    hideAllInputs();
                                    showInput('sdg-input');
                                    break;
                                case 'giapgoc':
                                    hideAllInputs();
                                    showInput('giapgoc-input');
                                    break;
                                case 'chimang':
                                    hideAllInputs();
                                    showInput('chimang-input');
                                    break;
                                case 'congtoanbo':
                                    hideAllInputs();
                                    showInput('congtoanbo-input');
                                    break;
                                default:
                                    break;
                            }
                        });

                        function hideAllInputs() {
                            var inputs = document.querySelectorAll('#sucmanh-input, #tiemnang-input, #hp-input, #mp-input, #sdg-input, #giapgoc-input, #chimang-input, #congtoanbo-input');
                            inputs.forEach(function (input) {
                                input.style.display = 'none';
                            });
                        }

                        function showInput(inputId) {
                            var input = document.getElementById(inputId);
                            input.style.display = 'block';
                        }
                    </script>
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