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
<div class="container pt-5 pb-5" id="pageHeader">
    <div class="row pb-2 pt-2">
        <div class="col-lg-6">
            <br>
            <br>
            <h4>THÔNG TIN MÁY CHỦ</h4><br>
            <?php if ($_admin != 1) { ?>
                <p>Bạn không phải là admin! Không thể sài được chứ năng này</p>
            <?php } else { ?>
                <b class="text text-danger">Lưu Ý: </b><br>
                - Tên Miền: Điền liên kết website của bạn vào!
                <br>
                - Logo: Điền liên kết ảnh hoặc nhập tên ảnh (Ví Dụ: logo.png) không cần thêm đuôi .png!
                <br>
                - Trạng Thái: Tình trạng website Bảo trì hoặc Hoạt Động
                <br>
                <?php
                $_alert = ''; // Khởi tạo biến $_alert với giá trị rỗng

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Lấy dữ liệu từ form
                    $_domain = $_POST['domain'];
                    $_logo = $_POST['logo'];
                    $_trangthai = $_POST['trangthai'];

                    // Truy vấn cơ sở dữ liệu để lấy dữ liệu hiện tại từ cột 'domain', 'logo' và 'trangthai'
                    $query = "SELECT * FROM adminpanel";
                    $statement = $conn->prepare($query);
                    $statement->execute();

                    if ($statement->rowCount() == 0) {
                        // Thông báo lỗi nếu không có dữ liệu
                        $_alert = 'Không có dữ liệu trong cơ sở dữ liệu!';
                    } else {
                        $row = $statement->fetch(PDO::FETCH_ASSOC);
                        $current_domain = $row['domain'];
                        $current_logo = $row['logo'];
                        $current_trangthai = $row['trangthai'];

                        // Tiến hành cập nhật thông tin domain, logo và trạng thái trong cơ sở dữ liệu nếu có sự thay đổi
                        $query_update = "UPDATE adminpanel SET ";
                        $params = array();
                        $update_required = false;

                        if ($_domain != $current_domain) {
                            $query_update .= "domain = :domain, ";
                            $params[':domain'] = $_domain;
                            $update_required = true;
                        }

                        if ($_logo != $current_logo) {
                            $query_update .= "logo = :logo, ";
                            $params[':logo'] = $_logo;
                            $update_required = true;
                        }

                        // Kiểm tra nếu $_logo là một URL
                        if (!filter_var($_logo, FILTER_VALIDATE_URL) && !pathinfo($_logo, PATHINFO_EXTENSION)) {
                            // Nếu $_logo không phải là URL, tự thêm đuôi .png
                            $_logo .= '.png';
                        } else {
                            // Nếu không phải URL, kiểm tra nếu tên file tồn tại trong thư mục 'images'
                            $imagePath = '../image/' . $_logo;
                            if (file_exists($imagePath)) {
                                echo '<img src="' . $imagePath . '" alt="Logo">';
                            } else {
                                echo '<p>Không tìm thấy ảnh.</p>';
                            }
                        }

                        if ($_trangthai != $current_trangthai) {
                            $query_update .= "trangthai = :trangthai, ";
                            $params[':trangthai'] = $_trangthai;
                            $update_required = true;
                        }

                        if ($update_required) {
                            // Xóa dấu ',' cuối cùng trong câu truy vấn
                            $query_update = rtrim($query_update, ', ');

                            // Bổ sung điều kiện cho câu truy vấn
                            $query_update .= " WHERE domain = :current_domain";
                            $params[':current_domain'] = $current_domain;

                            // Tiến hành cập nhật thông tin
                            $statement_update = $conn->prepare($query_update);
                            if ($statement_update->execute($params)) {
                                $_alert = 'Cập nhật thông tin thành công!';
                            } else {
                                $_alert = 'Lỗi: Không thể cập nhật thông tin.';
                            }
                        } else {
                            $_alert = 'Không có gì thay đổi.';
                        }
                    }
                }

                // Tiến hành truy vấn cơ sở dữ liệu để lấy dữ liệu hiện tại từ cột 'domain', 'logo' và 'trangthai'
                $query_select = "SELECT * FROM adminpanel";
                $statement_select = $conn->prepare($query_select);
                $statement_select->execute();

                if ($statement_select->rowCount() == 0) {
                    // Thông báo lỗi nếu không có dữ liệu
                    $_alert = 'Không có dữ liệu trong cơ sở dữ liệu!';
                } else {
                    $row = $statement_select->fetch(PDO::FETCH_ASSOC);
                    $_domain = $row['domain'];
                    $_logo = $row['logo'];
                    $_trangthai = $row['trangthai'];
                    $android = $row['android'];
                    $iphone = $row['iphone'];
                    $windows = $row['windows'];
                    $java = $row['java'];
                }
                ?>
                <!-- Hiển thị biến $_alert -->
                <?php echo $_alert; ?>
                <br>
                <br>
                <form method="POST">
                    <div class="mb-3">
                        <label class="font-weight-bold">Tên Miền:</label>
                        <input type="text" class="form-control" name="domain" id="domain" placeholder="Nhập domain" required
                            autocomplete="off" value="<?php echo $_domain; ?>">

                        <label class="font-weight-bold">Logo:</label>
                        <?php
                        // Loại bỏ phần ../image/ khỏi giá trị hiển thị
                        $displayLogo = str_replace('../image/', '', $_logo);
                        ?>
                        <input type="text" class="form-control" name="logo" id="logo" placeholder="Nhập logo" required
                            autocomplete="off" value="<?php echo $displayLogo; ?>">

                        <label class="font-weight-bold">Trạng Thái:</label>
                        <select class="form-control" name="trangthai" id="trangthai" required>
                            <option value="baotri" <?php if ($_trangthai === 'baotri')
                                echo 'selected'; ?>>Bảo trì</option>
                            <option value="hoatdong" <?php if ($_trangthai === 'hoatdong')
                                echo 'selected'; ?>>Hoạt Động
                            </option>
                        </select>
                    </div>
                    <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" type="submit">Cập Nhật</button>
                </form>

            <?php }
            // Đóng kết nối cơ sở dữ liệu
            $conn = null;
            ?>
        </div>
        <div class="col-lg-6 htop border-left">
            <br>
            <br>
            <h4>THÔNG TIN LIÊN KẾT</h4><br><br>
            <div class="transaction-item">
                <?php
                // Hiển thị thông tin liên kết và nút sửa tương ứng
                function displayLinkField($fieldName, $fieldValue)
                {
                    $fileExtensions = array(
                        'android' => 'apk',
                        'windows' => 'zip',
                        'iphone' => 'ipa',
                        'java' => 'jar'
                    );

                    $displayValue = $fieldValue;
                    if (preg_match('/\.(apk|zip|ipa|jar)$/', $fieldValue)) {
                        $displayValue = basename($fieldValue);
                    }
                    echo '<p><strong>' . ucfirst($fieldName) . ':</strong> ';
                    if (!empty($fieldValue)) {
                        echo '<span id="' . $fieldName . '_link">' . $displayValue . '</span> |  <a href="#" onclick="toggleEditInput(\'' . $fieldName . '_link\', \'' . $fieldName . '_input\', \'' . $fieldName . '_save\');">Sửa</a></p>';
                    } else {
                        echo 'Bạn chưa cài đặt liên kết | <a href="#" onclick="toggleEditInput(\'' . $fieldName . '_link\', \'' . $fieldName . '_input\', \'' . $fieldName . '_save\');">Sửa</a></p>';
                    }
                    echo '<input type="text" class="form-control" name="' . $fieldName . '" id="' . $fieldName . '_input" placeholder="Nhập liên kết ' . $fieldName . '" required autocomplete="off" value="' . $displayValue . '" style="display: none;">';
                    echo '<button id="' . $fieldName . '_save" class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" style="display: none;" onclick="saveFieldValue(\'' . $fieldName . '\', \'' . $fieldName . '_input\', \'' . $fieldName . '_link\', \'' . $fieldName . '_save\')">Lưu</button>';
                }

                // Hiển thị thông tin liên kết cho từng trường
                displayLinkField('android', $android);
                displayLinkField('iphone', $iphone);
                displayLinkField('windows', $windows);
                displayLinkField('java', $java);
                ?>
            </div>
        </div>
        <script>
            // Hàm gửi yêu cầu AJAX để lưu dữ liệu
            function saveFieldValue(fieldName, inputId, linkId, saveId) {
                const inputElement = document.getElementById(inputId);
                const newValue = inputElement.value;

                // Gửi yêu cầu AJAX để lưu dữ liệu
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../api/lienkettai.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Xử lý phản hồi từ API
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                // Hiển thị thông báo thành công (nếu cần)
                                alert(response.message);
                                // Tùy chỉnh các tùy chọn sau khi đã lưu thành công
                                const linkElement = document.getElementById(linkId);
                                const saveButton = document.getElementById(saveId);
                                linkElement.innerHTML = newValue;
                                linkElement.style.display = 'inline';
                                inputElement.style.display = 'none';
                                saveButton.style.display = 'none';
                            } else {
                                // Hiển thị thông báo lỗi (nếu cần)
                                alert(response.message);
                            }
                        } else {
                            // Hiển thị thông báo lỗi (nếu cần)
                            alert('Lỗi khi gửi yêu cầu AJAX.');
                        }
                    }
                };

                // Chuẩn bị dữ liệu để gửi trong yêu cầu POST
                const params = 'fieldName=' + encodeURIComponent(fieldName) + '&fieldValue=' + encodeURIComponent(newValue);

                // Gửi yêu cầu AJAX
                xhr.send(params);
            }

            function toggleEditInput(linkId, inputId, saveId) {
                const linkElement = document.getElementById(linkId);
                const inputElement = document.getElementById(inputId);
                const saveButton = document.getElementById(saveId);

                if (linkElement.style.display === 'none') {
                    linkElement.style.display = 'inline';
                    inputElement.style.display = 'none';
                    saveButton.style.display = 'none';
                } else {
                    linkElement.style.display = 'none';
                    inputElement.style.display = 'inline';
                    saveButton.style.display = 'inline';
                }
            }
        </script>

        <style type="text/css">
            .pagination-custom-style li {
                display: inline-block;
                margin-right: 5px;
                /* Adjust this value as needed for spacing */
            }

            .pagination-custom-style li:last-child {
                margin-right: 0;
                /* Remove the right margin from the last button */
            }
        </style>
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