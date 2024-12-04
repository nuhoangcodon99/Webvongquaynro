<?php
require_once 'core/head.php';
require_once 'core/set.php';
require_once 'core/connect.php';
if ($_login === null) {
    echo '<script>window.location.href = "../dang-nhap.php";</script>';
}

?>

                    <div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
                        <div class="page-layout-body">
                            <!-- load view -->
                            <div class="ant-row">
    <a href="/" style ="color: #E9ECEF;" class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_">Quay lại diễn đàn</a>
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
            <?php
            $query = "SELECT password, gmail FROM account WHERE username = :username";
            $statement = $conn->prepare($query);
            $statement->bindParam(":username", $_username);
            $statement->execute();
            $row = $statement->fetch();
            $primaryPassword = $row['password'];
            $primaryGmail = $row['gmail'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $password = $_POST['password'] ?? '';
                $newGmail = $_POST['new_gmail'] ?? '';
                $newGmailConfirm = $_POST['new_gmail_confirm'] ?? '';

                if (!empty($primaryGmail)) {
                    $oldGmail = $_POST['old_gmail'] ?? '';

                    if (!empty($password) && !empty($newGmail) && !empty($newGmailConfirm) && !empty($oldGmail)) {
                        // Check if the entered current password matches the one in the database.
                        // If not, display an error message.
                        if ($password !== $primaryPassword) {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Sai mật khẩu hiện tại</div>";
                        } elseif ($oldGmail !== $primaryGmail) {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Sai Gmail hiện tại</div>";
                        } elseif ($newGmail === $primaryGmail) {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Gmail mới không được giống với Gmail hiện tại</div>";
                        } elseif ($newGmail !== $newGmailConfirm) {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Gmail mới không giống nhau</div>";
                        } elseif (!filter_var($newGmail, FILTER_VALIDATE_EMAIL) || substr($newGmail, -10) !== "@gmail.com") {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Vui lòng nhập địa chỉ email Gmail (ví dụ: example@gmail.com)</div>";
                        } elseif (!filter_var($newGmailConfirm, FILTER_VALIDATE_EMAIL) || substr($newGmailConfirm, -10) !== "@gmail.com") {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Vui lòng nhập địa chỉ email Gmail (ví dụ: example@gmail.com)</div>";
                        } else {
                            // Update the new Gmail in the database
                            $updateQuery = "UPDATE account SET gmail = :newGmail WHERE username = :username";
                            $updateStatement = $conn->prepare($updateQuery);
                            $updateStatement->bindParam(":newGmail", $newGmail);
                            $updateStatement->bindParam(":username", $_username);

                            if ($updateStatement->execute()) {
                                echo "<div class='text-danger pb-2 font-weight-bold'>Cập nhật Gmail thành công</div>";
                            } else {
                                echo "<div class='text-danger pb-2 font-weight-bold'>Lỗi khi cập nhật Gmail</div>";
                            }
                        }
                    } else {
                        echo "<div class='text-danger pb-2 font-weight-bold'>Vui lòng điền đầy đủ thông tin trong form</div>";
                    }
                } else {
                    if (!empty($password) && !empty($newGmail) && !empty($newGmailConfirm)) {
                        if ($password !== $primaryPassword) {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Sai mật khẩu hiện tại</div>";
                        } elseif ($newGmail !== $newGmailConfirm) {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Gmail không giống nhau</div>";
                        } elseif (!filter_var($newGmail, FILTER_VALIDATE_EMAIL) || substr($newGmail, -10) !== "@gmail.com") {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Vui lòng nhập địa chỉ email Gmail (ví dụ: example@gmail.com)</div>";
                        } elseif (!filter_var($newGmailConfirm, FILTER_VALIDATE_EMAIL) || substr($newGmailConfirm, -10) !== "@gmail.com") {
                            echo "<div class='text-danger pb-2 font-weight-bold'>Vui lòng nhập địa chỉ email Gmail (ví dụ: example@gmail.com)</div>";
                        } else {
                            // Update Gmail in the database
                            $updateQuery = "UPDATE account SET gmail = :newGmail WHERE username = :username";
                            $updateStatement = $conn->prepare($updateQuery);
                            $updateStatement->bindParam(":newGmail", $newGmail);
                            $updateStatement->bindParam(":username", $_username);

                            if ($updateStatement->execute()) {
                                echo "<div class='text-danger pb-2 font-weight-bold'>Cập nhật Gmail thành công</div>";
                            } else {
                                echo "<div class='text-danger pb-2 font-weight-bold'>Lỗi khi cập nhật Gmail</div>";
                            }
                        }
                    } else {
                        echo "<div class='text-danger pb-2 font-weight-bold'>Vui lòng điền đầy đủ thông tin trong form</div>";
                    }
                }
            }

            if (!empty($primaryGmail)) {
                ?>

                <p style ="color: #E9ECEF;">Tài khoản của bạn đã được liên kết Gmail</p>
                <!-- Trang HTML -->
                <div id="remaining-time"></div>

                <script>
                    // Sử dụng JavaScript và AJAX để gửi yêu cầu đến máy chủ và cập nhật nội dung của vùng hiển thị kết quả
                    function updateRemainingTime() {
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function () {
                            if (this.readyState === 4 && this.status === 200) {
                                // Nhận phản hồi từ máy chủ và cập nhật nội dung của vùng hiển thị kết quả
                                document.getElementById("remaining-time").innerHTML = this.responseText;
                            }
                        };
                        xhttp.open("GET", "gmail/time.php", true); // Thay đổi đường dẫn đến tệp PHP xử lý
                        xhttp.send();
                    }

                    // Tự động cập nhật thời gian mỗi giây
                    setInterval(updateRemainingTime, 1000);
                </script>

                <div style ="color: #E9ECEF;">Gmail liên kết: <span class="font-weight-bold">
                        <?php echo $primaryGmail; ?>
                    </span></div>
            <?php } else {
                // Lấy thông báo và lớp thông báo từ tham số truy vấn
                $message = $_GET['message'] ?? '';
                $messageClass = $_GET['messageClass'] ?? '';

                // Hiển thị thông báo và lớp thông báo
                if ($message && $messageClass) {
                    echo '<div class="' . $messageClass . '">' . $message . '</div>';
                }
                ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="font-weight-bold">Mật khẩu hiện tại:</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Mật khẩu hiện tại" required autocomplete="password">
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Gmail mới:</label>
                        <input type="text" class="form-control" name="new_gmail" id="new_gmail" placeholder="Gmail mới"
                            required autocomplete="new_gmail">
                    </div>
                    <div class="mb-3">
                        <label class="font-weight-bold">Xác nhận Gmail mới:</label>
                        <input type="text" class="form-control" name="new_gmail_confirm" id="new_gmail_confirm"
                            placeholder="Xác nhận Gmail mới" required autocomplete="new_gmail_confirm">
                    </div>
                    <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" style ="color: #E9ECEF;" type="submit">Thực hiện</button>
                </form>
                <br>
                <br>
            <?php }
            ?>
            <div id="notification"></div>
        </div>
        <div class="col-lg-6 htop " style ="color: #E9ECEF;">
            <br>
            <br>
            <h6 style ="color: #E9ECEF;"> THÔNG TIN VỀ CẬP NHẬT THÔNG TIN</h6>
            1. Thông tin chung
            <br>
            - Cập nhật Gmail
            <br>
            - Dùng để lấy lại thông tin khi quên
            <br>
            - Có thể dùng hoặc không dùng
            <br>
            - Có thể đổi được gmail mới
            <br>
            - Nhấn vào nút HỦY GMAIL HIỆN TẠI là nó sẽ gửi gmail nha :3
            <br>
            <br>
            2. Hủy gmail hiện tại
            <br>
            - Gmail sẽ được huỷ luôn nếu như bạn xác nhận
            <br>
            - Vẫn có thể bật lại sau khi Hủy
            <br>
            <br>

            <?php if (!empty($primaryGmail)) { ?>
                <div class="mt-2 mb-2">
                    <?php if (!empty($primaryGmail)) { ?>
                        <div class="mt-2 mb-2">
                            <a class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-50" style ="color: #E9ECEF;" href="#" id="sendEmailLink">
                                <i class="fas fa-ban text-danger"></i> HỦY GMAIL HIỆN TẠI
                            </a>

                            <script>
                                document.getElementById('sendEmailLink').addEventListener('click', function (event) {
                                    event.preventDefault();

                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', 'gmail/guithu.php', true);
                                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                    xhr.onreadystatechange = function () {
                                        if (xhr.readyState === XMLHttpRequest.DONE) {
                                            if (xhr.status === 200) {
                                                var response = xhr.responseText;
                                                if (response === "success") {
                                                    alert("Gửi gmail thành công");
                                                    updateRemainingTime(); // Cập nhật thời gian sau khi gửi gmail thành công
                                                } else {
                                                    console.error(response);
                                                }
                                            } else {
                                                console.error(xhr.statusText);
                                            }
                                        }
                                    };
                                    xhr.send();
                                });
                            </script>
                        </div>
                    <?php } ?>
                </div>
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
<?php include_once 'core/footer.php'; ?>
</body>
</html>