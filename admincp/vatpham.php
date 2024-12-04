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
                    <b class="text text-danger">Lưu Ý: </b><br>
                    - Hãy thoát game trước khi buff tránh lỗi không mong muốn!
                    <br>
                    - Chỉ dùng những chỉ số thực sự có ích không chọn chỉ số lỗi nhé
                    <br>
                    <br>
                    <?php
                    echo '<form method="post" onsubmit="return validateForm()">';
                    echo '<label for="player_name">Tên nhân vật:</label>';
                    echo '<input class="form-control" type="text" name="player_name" id="player_name" required>';
                    echo '<br>';
                    echo '<label for="id">ID:</label>';
                    echo '<input class="form-control" type="text" name="id" id="id" required>';
                    echo '<br>';
                    echo '<label for="soluong">Số lượng:</label>';
                    echo '<input class="form-control" type="text" name="soluong" id="soluong" required>';
                    echo '<br>';

                    echo '<label for="option_type">Chọn Option:</label>';
                    echo '<select class="form-control" name="option_type" id="option_type" onchange="toggleOptionFields()">';
                    echo '<option value="no_option">Không chọn chỉ số</option>';
                    echo '<option value="has_option">Có chỉ số</option>';
                    echo '</select>';
                    echo '<br>';

                    $item_query = "SELECT id, name FROM item_option_template";
                    $statement = $conn->prepare($item_query);
                    $statement->execute();
                    $options = $statement->fetchAll(PDO::FETCH_ASSOC);

                    echo '<div id="optionFields" style="display: none;">'; // hiển thị option chỉ số có trong item_option_template
                    echo '<label for="option">Chỉ Số:</label>';
                    echo '<select class="form-control" name="option" id="option">';
                    foreach ($options as $option) {
                        echo '<option value="' . $option["id"] . '">' . $option["name"] . '</option>';
                    }
                    echo '</select>';
                    echo '<br>';

                    echo '<label for="param">Phần Trăm Chỉ Số:</label>';
                    echo '<input class="form-control" type="text" name="param" id="param">';
                    echo '<br>';
                    echo '</div>';

                    echo '<button type="submit" name="redeem_gift" class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50">Đổi quà</button>';
                    echo '</form>';


                    // Kiểm tra xem người dùng đã thực hiện đổi quà hay chưa
                    if (isset($_POST['redeem_gift'])) {
                        $account_id = $_SESSION['id'];
                        $player_name = $_POST['player_name'];

                        $player_query = "SELECT * FROM player WHERE name = :player_name LIMIT 1";
                        $statement = $conn->prepare($player_query);
                        $statement->bindParam(':player_name', $player_name, PDO::PARAM_STR);
                        $statement->execute();

                        if ($statement->rowCount() > 0) {
                            $player_data = $statement->fetch(PDO::FETCH_ASSOC);
                            $player_id = $player_data['id'];

                            $gift_items = "";

                            $id = $_POST['id'];
                            $soluong = $_POST['soluong'];
                            $option_type = $_POST['option_type'];

                            if ($option_type === "has_option" && !empty($id) && !empty($soluong) && isset($_POST['option']) && isset($_POST['param'])) {
                                $option = $_POST['option'];
                                $param = $_POST['param'];
                                $gift_items = "[$id, $soluong,\"[\"[$option, $param]\"]";
                            } else {
                                $option = 73; // Giá trị mặc định cho option
                                $param = 1; // Giá trị mặc định cho param
                                $gift_items = "[$id, $soluong,\"[\"[73, 1]\"]";
                            }

                            if (!empty($gift_items)) {
                                $_items_bag = $player_data['items_bag'];

                                $replacement = "[$id, $soluong,\\\"[\\\\\\\\\\\"[$option,$param]\\\\\\\\\\\"]\\\"";
                                $_items_bag = preg_replace('/\[-1,0,\\\"\[\]\\\"/', $replacement, $_items_bag, 1, $count);

                                if ($count === 0) {
                                    echo '<div class="text-danger pb-2 font-weight-bold">';
                                    echo 'Không tìm thấy vật phẩm phù hợp';
                                    echo '</div>';
                                    exit; // Dừng việc thực thi tiếp nếu không tìm thấy vật phẩm phù hợp
                                }

                                if (empty($_items_bag)) {
                                    echo '<div class="text-danger pb-2 font-weight-bold">';
                                    echo 'Hành trang đầy, không thể nhận quà.';
                                    echo '</div>';
                                    exit; // Dừng việc thực thi tiếp nếu hành trang đã đầy
                                }

                                // Cập nhật dữ liệu ngay lập tức vào cơ sở dữ liệu
                                $escaped_gift_items = $conn->quote($gift_items);
                                $escaped_items_bag = $conn->quote($_items_bag);
                                $update_query = "UPDATE player SET items_bag = $escaped_items_bag WHERE id = $player_id";
                                $conn->exec($update_query);

                                echo '<div class="text-danger pb-2 font-weight-bold">';
                                echo "BUFF thành công cho người chơi $player_name";
                                echo '</div>';
                            } else {
                                echo '<div class="text-danger pb-2 font-weight-bold">';
                                echo 'Không tìm thấy vật phẩm phù hợp';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="text-danger pb-2 font-weight-bold">';
                            echo 'Tên nhân vật không tồn tại.';
                            echo '</div>';
                        }
                    }
                    ?>

                    <script>
                        function toggleOptionFields() {
                            var optionType = document.getElementById("option_type").value;
                            var optionFieldsContainer = document.getElementById("optionFields");

                            if (optionType === "has_option") {
                                optionFieldsContainer.style.display = "block";
                            } else {
                                optionFieldsContainer.style.display = "none";
                            }
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