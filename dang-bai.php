<?php
require_once 'core/connect.php';
require_once 'core/set.php';
require_once 'core/cauhinh.php';
?>
<?php include_once 'core/head.php'; ?>

<div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
    <div class="page-layout-body">
        <!-- load view -->
            <div class="ant-row">
<div class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_"><a href="/Forum.php">
<i class="fas fa-reply"></i> Quay Lại</a></div></div>
<div class="ant-col ant-col-24">
    <div class="ant-list ant-list-split">
        <div class="ant-spin-nested-loading">
            <div class="ant-spin-container">
                <ul class="ant-list-items">
                    <div id="data_news">
                      </div>
<div id="paging" class="d-flex justify-content-end align-items-center flex-wrap">
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="ant-col ant-col-24">
    <div class="home_page_listItem__GD_iE" style="display:flex">
    <div class="ant-list-item-meta home_page_listItemTitle__YB3V5">
        <div class="ant-list-item-meta-content">
                <?php
                require_once 'core/connect.php';
                require_once 'core/set.php';

                // Lấy thông tin người dùng từ cơ sở dữ liệu
                $query = "SELECT account.*, account.admin FROM account LEFT JOIN player ON player.account_id = account.id";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $row = $stmt->fetch();

                if ($row) {
                    // Lấy thông tin từ form sử dụng phương thức POST
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Lấy giá trị của tiêu đề và nội dung bài viết
                        $tieude = htmlspecialchars($_POST["tieude"]);
                        $noidung = htmlspecialchars($_POST["noidung"]);

                        if (isset($_POST['username'])) {
                            $_username = $_POST['username'];
                        }
                        $sql = "SELECT player.name FROM player INNER JOIN account ON account.id = player.account_id WHERE account.username=:username";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':username', $_username);
                        $stmt->execute();
                        $row = $stmt->fetch();
                        $_name = $row['name'];

                        // Kiểm tra từ cấm trong tiêu đề và nội dung
                        $censoredWords = array(
                            'sex',
                            'địt',
                            'súc vật',
                            'sv',
                            'fuck',
                            'loz',
                            'lozz',
                            'lozzz',
                            'óc chó',
                            'ngu lồn',
                            'nguu lồn',
                            'nguu lồn',
                            'ngulon',
                            'nguu lonn',
                            'ngu lon',
                            'occho',
                            'ditmemay',
                            'dmm',
                            'dcm',
                            'địt cụ mày',
                            'địt con mẹ mày',
                            'fuck you',
                            'chịch',
                            'chịt',
                            'sẽ gầy'
                        );

                        // kiểm tra cột theloai dành cho người chơi không có admin = 1
                        $theloai = isset($_POST["theloai"]) ? htmlspecialchars($_POST["theloai"]) : 0;

                        foreach ($censoredWords as $word) {
                            if (stripos($tieude, $word) !== false || stripos($noidung, $word) !== false) {
                                echo "<span class='text-danger pb-2'>Thông Báo:</span> Tiêu đề hoặc nội dung chứa từ không cho phép.";
                                exit;
                            }
                        }


                        // Kiểm tra nếu có tệp tin ảnh được tải lên
                        if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
                            $image_files = $_FILES['image'];
                            $total_files = count($image_files['name']);

                            $image_names = array(); // Mảng để lưu trữ tên tệp tin ảnh
                            $upload_directory = "uploads/"; // Thư mục lưu trữ ảnh

                            for ($i = 0; $i < $total_files; $i++) {
                                $image_filename = $image_files['name'][$i];
                                $image_tmp = $image_files['tmp_name'][$i];

                                $targetFile = $upload_directory . basename($image_filename);

                                // Di chuyển tệp tin ảnh vào thư mục lưu trữ
                                move_uploaded_file($image_tmp, $targetFile);

                                // Thêm tên tệp tin vào mảng
                                $image_names[] = basename($image_filename);
                            }

                            // Chuyển đổi mảng thành chuỗi JSON
                            $image_names_json = json_encode($image_names);

                            // Lưu dữ liệu (bao gồm username và danh sách tên tệp tin ảnh) vào cơ sở dữ liệu bằng câu lệnh INSERT INTO
                            $sql = "INSERT INTO posts (tieude, noidung, image, username) VALUES (:tieude, :noidung, :image_names, :username)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':tieude', $tieude);
                            $stmt->bindParam(':noidung', $noidung);
                            $stmt->bindParam(':image_names', $image_names_json);
                            $stmt->bindParam(':username', $_name);
                        } else {
                            // Nếu không có tệp tin ảnh được tải lên, lưu dữ liệu (bao gồm username) vào cơ sở dữ liệu bằng câu lệnh INSERT INTO
                            $sql = "INSERT INTO posts (tieude, noidung, username) VALUES (:tieude, :noidung, :username)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':tieude', $tieude);
                            $stmt->bindParam(':noidung', $noidung);
                            $stmt->bindParam(':username', $_name);
                        }
                        if ($_status === 0) {
                            echo "<span class='text-danger pb-2 font-weight-bold'>Yêu cầu tài khoản đã mở Thành viên.</span>";
                        } else 
                        
                          if ($stmt->execute()) {

                            // Lấy số điểm tích lũy hiện tại của người dùng
                            $sql_select = "SELECT a.tichdiem FROM account a INNER JOIN player p ON a.id = p.account_id WHERE p.name = :name";
                            $stmt_select = $conn->prepare($sql_select);
                            $stmt_select->bindParam(':name', $_name);
                            $stmt_select->execute();
                            $row_select = $stmt_select->fetch();
                            $tichdiem = $row_select['tichdiem'];

                            // Cập nhật giá trị tichdiem trong bảng account
                            $sql_update = "UPDATE account SET tichdiem = (:tichdiem + 1) WHERE id = (SELECT account_id FROM player WHERE name = :name)";
                            $stmt_update_account = $conn->prepare($sql_update);
                            $stmt_update_account->bindParam(':tichdiem', $tichdiem);
                            $stmt_update_account->bindParam(':name', $_name);
                            $stmt_update_account->execute();

                            echo "<span class='text-danger pb-2 font-weight-bold'>Thông Báo:</span> Bài viết đã được đăng thành công.";
                        } else {
                            echo "Lỗi: " . $stmt->errorInfo()[2];
                        }
                    }
                }

                // Đóng kết nối với cơ sở dữ liệu
                $conn = null;
                ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Tiêu đề:</label>
                        <input class="form-control" type="text" name="tieude" id="tieude"
                            placeholder="Nhập tiêu đề bài viết" required>

                        <label><span class="text-danger">*</span> Nội dung:</label>
                        <textarea class="form-control" type="text" name="noidung" id="noidung"
                            placeholder="Nhập nội dung bài viết" required></textarea>

                        <?php
                        // Kiểm tra nếu trường admin tồn tại trong mảng $row và có giá trị bằng 1 thì hiển thị trường "Thể loại"
                        if ($_admin == 1) {
                            ?>
                            <label>Chọn ảnh:</label>
                            <input class="form-control" type="file" name="image[]" id="image" multiple>
                            <?php
                        }
                        ?>
                        <?php
                        // Kiểm tra nếu trường admin tồn tại trong mảng $row và có giá trị bằng 1 thì hiển thị trường "Thể loại"
                        if ($_admin == 1) {
                            ?>
                            <label><span class="text-danger">*</span> Thể loại:</label>
                                <select class="form-control" name="theloai" id="theloai" required>
                                    <option value="0">Thường</option>
                                    <script>
                                        var isAdmin = <?php echo ($row['admin'] == 1) ? 'true' : 'false'; ?>;
                                        if (isAdmin) {
                                            var option1 = new Option("Thông Báo", "1");
                                            var option2 = new Option("Sự Kiện", "2");
                                            var option3 = new Option("Cập Nhật", "3");
                                            document.getElementById("theloai").add(option1);
                                            document.getElementById("theloai").add(option2);
                                            document.getElementById("theloai").add(option3);
                                        }
                                    </script>
                                </select>
                        <?php
                             }
                        ?>
                        <div id="submit-error" class="alert alert-danger mt-2" style="display: none;"></div>
                    </div>
                    <span class="text-danger">*</span> Mọi bài biết vi phạm 
                    <a href="/dieu-khoan.php" target="_blank">
                    Nội quy diễn đàn</a> như spam, lừa đảo, 
                    ngôn từ chửi bới xúc phạm... sẽ bị xử lý.
                    <div id="notify" class="text-danger pb-2 font-weight-bold"></div>
                    <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-80" type="submit">ĐĂNG BÀI</button>
                </form>
                <script>
                    const form = document.querySelector('form');
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const submitError = form.querySelector('#submit-error');

                    form.addEventListener('submit', (event) => {
                        const titleLength = document.getElementById('tieude').value.trim().length;
                        const contentLength = document.getElementById('noidung').value.trim().length;

                        if (titleLength < 1 || contentLength < 1) {
                            event.preventDefault();

                            submitError.innerHTML = '<strong>Lỗi:</strong> Tiêu đề và nội dung phải có ít nhất 5 ký tự!';
                            submitError.style.display = 'block';
                            submitBtn.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                </script>
            <?php  ?>

        <br />
    </div>
</div>
</div>
</div>
</div>
    <!-- end load view -->
        </div>
            </div>                   
<?php include_once 'core/footer.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>
</body>
</html>