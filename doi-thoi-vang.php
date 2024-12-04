<?php
require_once 'core/connect.php';
require_once 'core/set.php';
require_once 'core/cauhinh.php';
if ($_login === null) {
    echo '<script>window.location.href = "../dang-nhap.php";</script>';
}
?>
<?php include_once 'core/head.php'; ?>

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
<div id="data_news">

<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
<h4>ĐỔI THỎI VÀNG - TỰ ĐỘNG</h4>
            <?php if ($_login === null) { ?>
                <p>Bạn chưa đăng nhập? hãy đăng nhập để sử dụng chức năng này</p>
            <?php } else { ?>
                <span>- Máy chủ chưa hỗ trợ đổi trực tiếp khi đang trực tuyến trong máy chủ! </span>
                <p>- Vui lòng thoát game tránh lỗi khi đổi nhé:3 </p>
                <form method="POST">
                    <label for="">Tên tài khoản:</label>
                    <input type="text" value="<?php echo $_username; ?>" class="form-control form-control-alternative"
style="padding-right:30px;font-size:16px;width:365px" readonly autocomplete="text"></input>
                    <br>
                    <label for="vnd_amount">Số Dư Cần Đổi:</label>
                    <select class="form-control form-control-alternative" name="vnd_amount" id="vnd_amount" style="padding-right:30px;font-size:16px;width:365px" required>
                        <option value="">Chọn Số Dư</option>
                        <option value="10000">10,000 VNĐ</option>
                        <option value="20000">20,000 VNĐ</option>
                        <option value="30000">30,000 VNĐ</option>
                        <option value="50000">50,000 VNĐ</option>
                        <option value="100000">100,000 VNĐ</option>
                        <option value="200000">200,000 VNĐ</option>
                        <option value="500000">500,000 VNĐ</option>
                        <option value="1000000">1,000,000 VNĐ</option>
                    </select>
                    <br>
                    <label>Số thỏi vàng sẽ nhận: <span class="form-control form-control-alternative" style="padding-right:30px;font-size:16px;width:365px" id="gold">0</span> </label>
                    <br>
                    <br>
                    <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50"  name="doithoivang" type="submit">Thực hiện</button>
                </form>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['doithoivang'])) {
                        $account_id = $_SESSION['id'];
                        $vnd_amount = $_POST['vnd_amount'];

                        // Mệnh giá và số lượng thỏi vàng tương ứng
                        $options = array(
                            array("amount" => 10000, "quantity" => 20),
                            array("amount" => 20000, "quantity" => 40),
                            array("amount" => 30000, "quantity" => 60),
                            array("amount" => 50000, "quantity" => 100),
                            array("amount" => 100000, "quantity" => 200),
                            array("amount" => 200000, "quantity" => 430),
                            array("amount" => 500000, "quantity" => 1100),
                            array("amount" => 1000000, "quantity" => 2300),
                        );

                        $gold_quantity = 0;
                        foreach ($options as $option) {
                            if ($option["amount"] == $vnd_amount) {
                                $gold_quantity = $option["quantity"];
                                break;
                            }
                        }

                        if ($gold_quantity > 0) {
                                // Use prepared statements with parameter binding
                                $account_query = "SELECT * FROM account WHERE id = :account_id LIMIT 1";
                                $stmt = $conn->prepare($account_query);
                                $stmt->bindParam(":account_id", $account_id, PDO::PARAM_INT);
                                $stmt->execute();

                            if ($stmt->rowCount() > 0) {
                                    $account_data = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $current_vnd = $account_data['vnd'];

                                // Kiểm tra số dư VND đủ để trừ
                                if ($current_vnd >= $vnd_amount) {
                                                // Cập nhật số dư VND
                                                $new_vnd = $current_vnd - $vnd_amount;

                                                // Update the database using prepared statements
                                                $update_query = "UPDATE account SET vnd = :new_vnd WHERE id = :account_id";
                                                $stmt = $conn->prepare($update_query);
                                                $stmt->bindParam(":new_vnd", $new_vnd, PDO::PARAM_INT);
                                                $stmt->bindParam(":account_id", $account_id, PDO::PARAM_INT);
                                                $stmt->execute();

                                    // Lấy thông tin người chơi
                                    $account_query = "SELECT * FROM account WHERE id = :account_id LIMIT 1";
                                $stmt = $conn->prepare($account_query);
                                $stmt->bindParam(":account_id", $account_id, PDO::PARAM_INT);
                                $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        $player_data = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $_items_bag = $player_data['items_bag'];
                                        $thoivang_add = '[{"id":457,"quantity":' . $gold_quantity . '}]';

                                        // Thêm thông tin đổi thỏi vàng vào bảng `doithoivang`
                                        $doithoivang_query = "INSERT INTO doithoivang (name, thoivang) VALUES ('$name', '$thoivang_add')";
                                        $stmt = $conn->prepare($update_query);
                                                $stmt->bindParam(":escaped_items_bag", $escaped_items_bag);
                                                $stmt->bindParam(":account_id", $account_id, PDO::PARAM_INT);
                                                //$stmt->execute();

                                        echo '<div class="text-danger pb-2 font-weight-bold">';
                                        echo "Đổi quà thành công! Nhận được $gold_quantity thỏi vàng.";
                                        echo '</</div>';
                                    } else {
                                        echo '<div class="text-danger pb-2 font-weight-bold">';
                                        echo 'Không tìm thấy dữ liệu người chơi.';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<div class="text-danger pb-2 font-weight-bold">';
                                    echo 'Số dư của bạn không đủ.';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="text-danger pb-2 font-weight-bold">';
                                echo 'Không tìm thấy dữ liệu tài khoản.';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="text-danger pb-2 font-weight-bold">';
                            echo 'Không tìm thấy món quà phù hợp.';
                            echo '</div>';
                        }
                    }
                }
                                // Call the function with the database connection
                //processDoiThoivang($conn);

            }
                ?>


            <script>
                document.getElementById('vnd_amount').addEventListener('change', function () {
                    var vndAmount = parseInt(this.value);
                    var goldQuantity = 0;
                    var options = [
                        { amount: 10000, quantity: 20 },
                        { amount: 20000, quantity: 40 },
                        { amount: 30000, quantity: 60 },
                        { amount: 50000, quantity: 100 },
                        { amount: 100000, quantity: 200 },
                        { amount: 200000, quantity: 430 },
                        { amount: 500000, quantity: 1100 },
                        { amount: 1000000, quantity: 2300 }
                    ];

                    for (var i = 0; i < options.length; i++) {
                        if (options[i].amount === vndAmount) {
                            goldQuantity = options[i].quantity;
                            break;
                        }
                    }

                    document.getElementById('gold').textContent = goldQuantity;
                });
            </script>
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