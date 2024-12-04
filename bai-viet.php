<?php
require_once 'core/connect.php';
require_once 'core/set.php';
require_once 'core/cauhinh.php';
if (isset($_POST['username'])) {
    $_username = $_POST['username'];
}
?>
<?php include_once 'core/head.php'; ?>
                    <div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
                        <div class="page-layout-body">
                            <!-- load view -->
                                <div class="ant-row">
                                <a href="/Forum.php" style="color: black" class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_">Quay lại diễn đàn.</a>
        <div class="ant-col ant-col-24" style="padding: 10px 0px;">
            <table cellpadding="0" cellspacing="0" width="99%" style="table-layout: fixed; overflow-wrap: break-word; border-spacing: 0px 15px; border-collapse: separate; text-indent: initial;">
                <tbody>
                    <tr>
                        <td align="center" style="width: 90px; vertical-align: top;">
                            <?php
                                        if (isset($_GET['id'])) {
                                            // Xử lý lấy thông tin bài viết từ CSDL
                                            $post_id = $_GET['id'];
                                            $query = "SELECT posts.*, player.gender, account.tichdiem, account.admin, account.server_login, posts.image, posts.tinhtrang FROM posts
                                    LEFT JOIN player ON posts.username = player.name
                                    LEFT JOIN account ON player.account_id = account.id
                                    WHERE posts.id = :post_id";

                                            $stmt = $conn->prepare($query);
                                            $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
                                            $stmt->execute();
                                            $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                            if ($row) {
                                                $gender = $row['gender'];
                                                $hanhtinh = $row['gender'];
                                                $tichdiem = $row['tichdiem'];

                                                // Lấy Avatar và tên của người dùng
                                                $admin = $row['admin'];
                                                $tinhtrang = $row['tinhtrang'];
                                                $avatar_url = "";

                                                if ($admin == 1) {
                                                    if ($gender == 0) {
                                                        $avatar_url = "image/avatar99.png";
                                                    } elseif ($gender == 1) {
                                                        $avatar_url = "image/avatar4.png";
                                                    } else {
                                                        $avatar_url = "image/avatar13.png";
                                                    }
                                                } else {
                                                    if ($gender == 0) {
                                                        $avatar_url = "image/avatar0.png";
                                                    } elseif ($gender == 1) {
                                                        $avatar_url = "image/avatar1.png";
                                                    } else {
                                                        $avatar_url = "image/avatar2.png";
                                                    }
                                                }

                                                $name_hanhtinh = "";
                                                if ($hanhtinh == 1) {
                                                    $name_hanhtinh = "(Namec)";
                                                } elseif ($hanhtinh == 2) {
                                                    $name_hanhtinh = "(Xayda)";
                                                } else {
                                                    $name_hanhtinh = "(Trái Đất)";
                                                }
                                                $color = "";
                                                if ($tichdiem >= 52000000) {
                                                    $danh_hieu = "(Kaio Shin Cấp V)";
                                                    $color = "#800000"; // sets color to red
                                                } elseif ($tichdiem >= 200) {
                                                    $danh_hieu = "(Chuyên Gia)";
                                                    $color = "#800000"; // sets color to red
                                                } elseif ($tichdiem >= 100) {
                                                    $danh_hieu = "(Hỏi Đáp)";
                                                    $color = "#A0522D"; // sets color to yellow
                                                } elseif ($tichdiem >= 35) {
                                                    $danh_hieu = "(Người Bắt Chuyện)";
                                                    $color = "#6A5ACD";
                                                } else {
                                                    $danh_hieu = "";
                                                    $color = "";
                                                }

                                                echo '<div class="text-center"><img src="' . $avatar_url . '" alt="Avatar" style="width: 30px"><br></div>';
                                                if ($row['admin'] == 1) {
                                                    echo '<span class="text-danger font-weight-bold">' . $row['username'] . '</span><br>';
                                                    echo '<span class="text-danger pt-1 mb-0"><i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i></span></br>';
                                                    if ($danh_hieu !== "") {
                                                        echo '<span style="color:' . $color . ' !important">' . $danh_hieu . '</span></br>';
                                                    }
                                                    echo 'Điểm:' ;
                                                    echo number_format($tichdiem, 0, ',');
                                                } else {
                                                    echo '<div style="font-size: 9px; padding-top: 5px">' . $row['username'] . '</div>';
                                                    echo '<span style="font-size: 9px" class="text-dark pt-1 mb-0"> (' . $row['server_login'] . ' Sao)</span></br>';
                                                    if ($danh_hieu !== "") {
                                                        echo '<span style="color:' . $color . ' !important">' . $danh_hieu . '</span></br>';
                                                    }
                                                    echo 'Điểm:' ;
                                                    echo number_format($tichdiem, 0, ',');
                                                }
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '<td class="posts_boxContent__XjPnA">
                            <div class="posts_contentPost___PyGu">';
                            echo '<div class="posts_title__P1NsS"><span class="font-weight-bold">' . $row['tieude'] . '</span></div>';
                                                echo '<div class="fr-view" style="background-color: rgb(255, 255, 255);">';
                                                $created_at = strtotime($row['created_at']);
                                                $now = time();
                                                $time_diff = $now - $created_at;
                                                echo '<div class="col"><div style="font-size: 10px; padding-top: 1px">';
                                                if ($time_diff < 60) {
                                                    echo $time_diff . ' giây trước';
                                                } elseif ($time_diff < 3600) {
                                                    echo floor($time_diff / 60) . ' phút trước';
                                                } elseif ($time_diff < 86400) {
                                                    echo floor($time_diff / 3600) . ' giờ trước';
                                                } elseif ($time_diff < 2592000) {
                                                    echo floor($time_diff / 86400) . ' ngày trước';
                                                } elseif ($time_diff < 31536000) {
                                                    echo floor($time_diff / 2592000) . ' tháng trước';
                                                } else {
                                                    echo floor($time_diff / 31536000) . ' năm trước';
                                                }
                                                echo '<span style="float: right;">';

                                                // Kiểm tra và hiển thị chức năng "Đã trả lời" nếu có quyền admin và người dùng đã đăng nhập
                                                if ($_login === null) {

                                                } else {
                                                    // Kiểm tra và hiển thị chức năng "Đã trả lời"
                                                    $ghinhan = ($tinhtrang == 2);
                                                    $datraloi = ($tinhtrang == 1);
                                                    $thaoluan = ($tinhtrang == 0);

                                                    if ($_admin == 1) {
                                                        if ($tinhtrang == 0) {
                                                            if (!$ghinhan) {
                                                                echo '<a href="/api/tinhtrang.php?id=' . $post_id . '&tinhtrang=2">[Ghi nhận]</a> ';
                                                            }
                                                            if (!$datraloi) {
                                                                echo '<a href="/api/tinhtrang.php?id=' . $post_id . '&tinhtrang=1">[Đã trả lời]</a> ';
                                                            }
                                                        } else if ($tinhtrang == 1) {
                                                            if (!$ghinhan) {
                                                                echo '<a href="/api/tinhtrang.php?id=' . $post_id . '&tinhtrang=2">[Ghi nhận]</a> ';
                                                            }
                                                            if (!$thaoluan) {
                                                                echo '<a href="/api/tinhtrang.php?id=' . $post_id . '&tinhtrang=0">[Chưa trả lời]</a> ';
                                                            }
                                                        } else if ($tinhtrang == 2) {
                                                            if (!$datraloi) {
                                                                echo '<a href="/api/tinhtrang.php?id=' . $post_id . '&tinhtrang=1">[Đã trả lời]</a> ';
                                                            }
                                                            if (!$thaoluan) {
                                                                echo '<a href="/api/tinhtrang.php?id=' . $post_id . '&tinhtrang=0">[Chưa trả lời]</a> ';
                                                            }
                                                        }
                                                    }
                                                }

                                                echo '#0</span>';
                                                echo '</div>';
                                                echo '<div class="row">';

                                                //echo '<div class="col"><span class="font-weight-bold">' . $row['tieude'] . '</span>';
                                                echo '<br>';
                                                // Kiểm tra và hiển thị nội dung
                                                $content = $row['noidung'];

                                                // Chuyển đổi http:// và https:// thành liên kết
                                                $content = preg_replace('/(https?:\/\/[^\s]+(\.[^\s]+)+)/', '<a href="$1" class="link">$1</a>', $content);

                                                echo '<span style="white-space: pre-wrap;">' . $content . '</span><br>';

                                                $image_filenames = null;
                                                if ($row['image'] !== null) {
                                                    $image_filenames = json_decode($row['image'], true); // Chuyển đổi chuỗi JSON thành mảng
                                                }

                                                if (is_array($image_filenames) && !empty($image_filenames)) {
                                                    foreach ($image_filenames as $image_filename) {
                                                        $image_path = "uploads/" . $image_filename; // Đường dẫn đến thư mục chứa ảnh
                                                        // Kiểm tra nếu tệp tồn tại trong thư mục image
                                                        if (file_exists($image_path)) {
                                                            echo '<img src="' . $image_path . '" alt="Ảnh" class="img-thumbnail">';
                                                        } else {
                                                            echo 'Không tìm thấy hình ảnh';
                                                        }
                                                    }
                                                }
                                            }
                                            ?>

                                </td>
                            </tr>
                        </tbody>
                    </table>

        <?php
        $post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Tính toán số lượng comment cho bài viết hiện tại
        $query = "SELECT COUNT(*) AS count FROM comments WHERE post_id = :post_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_comments = $row['count'];

        // Thiết lập giới hạn cho mỗi trang
        $limit = 5;

        // Tính toán số lượng trang
        $total_pages = ceil($total_comments / $limit);

        // Calculate the highest page number
        $highest_page = max(1, $total_pages);

        // Lấy số trang từ tham số URL, nếu không có thì sử dụng trang cao nhất
        $page = isset($_GET['page']) ? intval($_GET['page']) : $highest_page;

        // Xác định vị trí của trang hiện tại trong danh sách các trang
        $page_position = min(max(1, $page), max(1, $total_pages - 2));

        // Tính toán giới hạn kết quả truy vấn theo biến $limit và $page
        $offset = max(0, ($page - 1) * $limit);

        // Query the `comments` table to retrieve all comments for the current post, along with the author name
        $query = "SELECT nguoidung, traloi, created_at, gender, image FROM comments WHERE post_id = :post_id ORDER BY comments.post_id ASC LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll();

        // Hiển thị số thứ tự của bình luận
        $count_start = ($page - 1) * $limit; // Tính toán giá trị ban đầu cho $count
        $count = $count_start; // Gán giá trị ban đầu cho $count
        ?>
        <div class="container pt-1 pb-1">
            <?php
            foreach ($comments as $comment):
                $count++; // Tăng giá trị của $count cho mỗi bình luận
                ?>

                        <table cellpadding="0" cellspacing="0" width="99%" style="table-layout: fixed; overflow-wrap: break-word; border-spacing: 0px 15px; border-collapse: separate; text-indent: initial;">
                            <tbody>
                                <tr>
                                    <td align="center" style="width: 90px; vertical-align: top;">
                                        <div class="text-center" style="margin-left: -10px;">
                                            <div style="font-size: 9px; padding-top: 1px">
                                                <?php
                                                // Lấy Avatar và tên người dùng
                                                $gender = $comment['gender'];
                                                $nguoidung = $comment['nguoidung'];

                                                // Lấy thông tin tài khoản và điểm tích lũy
                                                $sql = "SELECT account.tichdiem, account.admin, player.account_id, account.server_login FROM account INNER JOIN player ON player.account_id = account.id WHERE player.name = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindParam(1, $nguoidung, PDO::PARAM_STR);
                                                $stmt->execute();
                                                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                                if ($stmt->rowCount() > 0) {
                                                    $tichdiem = intval($result['tichdiem']);
                                                    $admin = $result['admin'];
                                                    $account_id = $result['account_id'];

                                                    $avatar_url = "";

                                                    if ($admin == 1) {
                                                        if ($gender == 0) {
                                                            $avatar_url = "image/avatar99.png";
                                                        } elseif ($gender == 1) {
                                                            $avatar_url = "image/avatar4.png";
                                                        } else {
                                                            $avatar_url = "image/avatar10.png";
                                                        }
                                                    } else {
                                                        if ($gender == 0) {
                                                            $avatar_url = "image/avatar0.png";
                                                        } elseif ($gender == 1) {
                                                            $avatar_url = "image/avatar1.png";
                                                        } else {
                                                            $avatar_url = "image/avatar2.png";
                                                        }
                                                    }

                                                    // Hiển thị avatar và tên người dùng
                                                    echo '<img src="' . $avatar_url . '" alt="Avatar" style="width: 30px">';
                                                    echo '<p>';

                                                    $query = "SELECT DISTINCT posts.*, account.admin, player.account_id, account.server_login FROM posts LEFT JOIN player ON posts.username = player.name LEFT JOIN account ON player.account_id = account.id WHERE posts.username = ? ORDER BY posts.id DESC";
                                                    $stmt = $conn->prepare($query);
                                                    $stmt->bindParam(1, $nguoidung, PDO::PARAM_STR);
                                                    $stmt->execute();
                                                    $result2 = $stmt->fetchAll();

                                                    // Hiển thị thông tin tài khoản và danh sách bài viết
                                                    $color = "";
                                                    if ($tichdiem >= 52000000) {
                                                        $danh_hieu = "(Kaio Shin)";
                                                        $color = "#800000"; // sets color to red
                                                    } elseif ($tichdiem >= 200) {
                                                        $danh_hieu = "(Chuyên Gia)";
                                                        $color = "#800000"; // sets color to red
                                                    } elseif ($tichdiem >= 100) {
                                                        $danh_hieu = "(Hỏi Đáp)";
                                                        $color = "#A0522D"; // sets color to yellow
                                                    } elseif ($tichdiem >= 35) {
                                                        $danh_hieu = "(Người Bắt Chuyện)";
                                                        $color = "#6A5ACD";
                                                    } else {
                                                        $danh_hieu = "";
                                                        $color = "";
                                                    }

                                                    if ($admin == 1) {
                                                        // Nếu tìm thấy giá trị 'admin' bằng 1 trong vòng lặp foreach, hiển thị tên người dùng với chữ màu đỏ
                                                        echo '<span class="text-danger font-weight-bold">' . $nguoidung . '</span><br>';
                                                        echo '<span class="text-danger pt-1 mb-0"><i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i></span></br>';
                                                        if ($danh_hieu !== "") {
                                                        echo '<span style="color:' . $color . ' !important">' . $danh_hieu . '</span></br>';
                                                        }

                                                    } else {
                                                        // Nếu không tìm thấy giá trị 'admin' bằng 1 hoặc biến $row không tồn tại, hiển thị tên người dùng với chữ màu đen.
                                                        echo '<span>' . $nguoidung . '</span><br>';
                                                        if ($danh_hieu !== "") {
                                                            echo '<span style="color:' . $color . ' !important">' . $danh_hieu . '</span><br>';
                                                        }
                                                    }

                                                    echo 'Điểm:' ;
                                                    echo number_format($tichdiem, 0, ',');
                                                }
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '<td class="bg bg-light" style=" border-radius: 7px">';
                                                echo '<div class="row" style="padding: 0 7px 10px 5px">';
                                                ?>

                                            <div class="col">
                                                <small>
                                                    <?php
                                                    $created_at = strtotime($comment['created_at']);
                                                    $now = time();
                                                    $time_diff = $now - $created_at;
                                                    if ($time_diff < 60) {
                                                        echo $time_diff . ' giây trước';
                                                    } elseif ($time_diff < 3600) {
                                                        echo floor($time_diff / 60) . ' phút trước';
                                                    } elseif ($time_diff < 86400) {
                                                        echo floor($time_diff / 3600) . ' giờ trước';
                                                    } elseif ($time_diff < 2592000) {
                                                        echo floor($time_diff / 86400) . ' ngày trước';
                                                    } elseif ($time_diff < 31536000) {
                                                        echo floor($time_diff / 2592000) . ' tháng trước';
                                                    } else {
                                                        echo floor($time_diff / 31536000) . ' năm trước';
                                                    }
                                                    echo '<span style="float: right; font-size: 9px;">';

                                                    echo '<span style="float: right;">#' . $count . '</span>'; // Hiển thị số thứ tự của bình luận
                                                    echo '</span>';
                                                    ?>
                                                </small>
                                                <p class="text-dark pt-1 pb-1 mb-1">
                                                    <?php
                                                    $content = $comment['traloi'];

                                                    $content = preg_replace_callback('/(https?:\/\/[^\s]+(\.[^\s]+)+)/', function ($matches) {
                                                        $url = $matches[0];
                                                        return '<a href="' . $url . '" class="link">' . (filter_var($url, FILTER_VALIDATE_URL) ? $url : substr($url, 0, -1)) . '</a>';
                                                    }, $content);


                                                    echo '<span style="white-space: pre-wrap;">' . $content . '</span><br>';

                                                    $image_filenames = $comment['image']; // Assuming $comment['image'] is a JSON string or null

                                                    if (!is_null($image_filenames)) {
                                                        $image_filenames = json_decode($image_filenames, true); // Convert JSON string to an array

                                                        if (is_array($image_filenames) && !empty($image_filenames)) {
                                                            foreach ($image_filenames as $image_filename) {
                                                                $image_path = "uploads/" . $image_filename;

                                                                if (file_exists($image_path)) {
                                                                    echo '<img src="' . $image_path . '" alt="Ảnh" class="img-thumbnail">';
                                                                } else {
                                                                    echo 'Không tìm thấy hình ảnh';
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </p>

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                </p>
            <?php endforeach; ?>
            <?php
            if ($_login === null) {
                ?>
                <br>
                </div>

                <div class="container pb-2">
                    <div class="row mt-3">
                        <div class="col-5">
                        </div>
                        <?php
            } else { // Lấy id bài viết từ URL

                // Hiển thị pagination
                echo '<div class="col text-right">';
                echo '<ul class="pagination justify-content-end">';
                if ($page > 1) {
                    echo '<li><a class="btn btn-trangdem btn-light" href="bai-viet.php?id=' . $post_id . '&page=' . ($page - 1) . '"><</a></li>';
                }
                $start_page = max(1, min($total_pages - 2, $page - 1));
                $end_page = min($total_pages, max(2, $page + 1));
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i >= $start_page && $i <= $end_page) {
                        $class_name = "btn btn-trangdem btn-light";
                        if ($i == $page) {
                            $class_name = "btn btn-trangdem page-active";
                        }
                        echo '<li><a class="' . $class_name . '" href="bai-viet.php?id=' . $post_id . '&page=' . $i . '">' . $i . '</a></li>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<li><a class="btn btn-trangdem btn-light" href="bai-viet.php?id=' . $post_id . '&page=' . ($page + 1) . '">></a></li>';
                }
                echo '</ul>';
                echo '</div>';
                ?>
                    <div class="border-secondary border-top"></div><br>
                    <table cellpadding="0" cellspacing="0" width="99%" style="font-size: 13px;">
                        <tbody>
                            <tr>
                                <table cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px;">
                                    <tbody>
                                        <tr>
                                            <td width="50px;" style="vertical-align: top">
                                                <div class="text-left" style="display: block;">
                                                    <?php
                                                    $query = "SELECT posts.*, account.admin FROM posts LEFT JOIN player ON posts.username = player.name LEFT JOIN account ON player.account_id = account.id WHERE posts.id = ?";
                                                    $stmt = $conn->prepare($query);
                                                    $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
                                                    $stmt->execute();
                                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                    if ($row && $row['trangthai'] == 0) {
                                                        // Lấy tên người dùng từ cơ sở dữ liệu
                                                        $sql = "SELECT player.name, player.gender, account.admin FROM player INNER JOIN account ON account.id = player.account_id WHERE account.username = :username";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->bindParam(':username', $_username, PDO::PARAM_STR);
                                                        $stmt->execute();
                                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                        // Hiển thị ảnh đại diện và tên người dùng
                                                        if ($row && isset($row['gender'])) {
                                                            // Lấy Avatar và tên của người dùng
                                                            $gender = $row['gender'];
                                                            $admin = $row['admin'];
                                                            $avatar_url = "";

                                                            if ($admin == 1) {
                                                                if ($gender == 0) {
                                                                    $avatar_url = "image/avatar99.png";
                                                                } elseif ($gender == 1) {
                                                                    $avatar_url = "image/avatar4.png";
                                                                } else {
                                                                    $avatar_url = "image/avatar10.png";
                                                                }
                                                            } else {
                                                                if ($gender == 0) {
                                                                    $avatar_url = "image/avatar0.png";
                                                                } elseif ($gender == 1) {
                                                                    $avatar_url = "image/avatar1.png";
                                                                } else {
                                                                    $avatar_url = "image/avatar2.png";
                                                                }
                                                            }
                                                            echo '<img src="' . $avatar_url . '" alt="Avatar" style="width: 35px">';
                                                        }
                                                    }
                                                    ?>
                                                    <br>
                                                </div>
                                            </td>
                                            <td style="border-radius: 7px">
                                                <div class="row">
                                                    <div class="col">
                                                        <?php
                                                        ob_start(); // start buffering output
                                                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['traloi'])) {
                                                            $comment = filter_var($_POST['traloi'], FILTER_SANITIZE_STRING);

                                                            if (isset($_GET['id'])) {
                                                                $id = intval($_GET['id']);

                                                                $select_stmt = $conn->prepare("SELECT player.name, player.gender, player.account_id, account.admin FROM player INNER JOIN account ON account.id = player.account_id WHERE account.username = ?");
                                                                $select_stmt->bindParam(1, $_username, PDO::PARAM_STR);
                                                                $select_stmt->execute();
                                                                $result = $select_stmt->fetch(PDO::FETCH_ASSOC);


                                                                if ($result && $result['account_id']) {
                                                                    $update_stmt = $conn->prepare("UPDATE account SET tichdiem = tichdiem + 1 WHERE id = ?");
                                                                    $update_stmt->bindParam(1, $result['account_id'], PDO::PARAM_INT);
                                                                    $update_stmt->execute();

                                                                    $data = "SELECT player.name FROM player INNER JOIN account ON account.id = player.account_id WHERE account.username=:username";
                                                                    $dulieu = $conn->prepare($data);
                                                                    $dulieu->bindParam(':username', $_username, PDO::PARAM_STR);
                                                                    $dulieu->execute();
                                                                    $connectdata = $dulieu->fetch(PDO::FETCH_ASSOC);
                                                                    $_name = $connectdata['name'];

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

                                                                        // Lưu thông tin bình luận và tệp tin ảnh vào cơ sở dữ liệu
                                                                        $insert_stmt = $conn->prepare("INSERT INTO comments (post_id, nguoidung, gender, image, traloi) VALUES (?, ?, ?, ?, ?)");
                                                                        $insert_stmt->bindParam(1, $id, PDO::PARAM_INT);
                                                                        $insert_stmt->bindParam(2, $_name, PDO::PARAM_STR);
                                                                        $insert_stmt->bindParam(3, $result['gender'], PDO::PARAM_STR);
                                                                        $insert_stmt->bindParam(4, $image_names_json, PDO::PARAM_STR);
                                                                        $insert_stmt->bindParam(5, $comment, PDO::PARAM_STR);
                                                                        $insert_stmt->execute();
                                                                    } else {
                                                                        // Lưu thông tin bình luận vào cơ sở dữ liệu (không có tệp tin ảnh)
                                                                        $insert_stmt = $conn->prepare("INSERT INTO comments (post_id, nguoidung, gender, traloi) VALUES (?, ?, ?, ?)");
                                                                        $insert_stmt->bindParam(1, $id, PDO::PARAM_INT);
                                                                        $insert_stmt->bindParam(2, $_name, PDO::PARAM_STR);
                                                                        $insert_stmt->bindParam(3, $result['gender'], PDO::PARAM_STR);
                                                                        $insert_stmt->bindParam(4, $comment, PDO::PARAM_STR);
                                                                        $insert_stmt->execute();
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        ob_end_flush();
                                                        ?>
                                                        <?php
                                                        $query = "SELECT posts.*, player.name FROM posts LEFT JOIN player ON posts.username = player.name LEFT JOIN account ON player.account_id = account.id WHERE posts.id = ?";
                                                        $stmt = $conn->prepare($query);
                                                        $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
                                                        $stmt->execute();
                                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                        if ($row && $row['trangthai'] == 0) {
                                                            ?>
                                                            <form id="form" method="POST" enctype="multipart/form-data" action="">
                                                                <div class="form-group position-relative">
                                                                    <div class="input-group">
                                                                        <textarea class="form-control" type="text" name="traloi"
                                                                            id="traloi" placeholder="Nhập bình luận của bạn..."
                                                                            required></textarea>
                                                                        <?php
                                                                        if ($_admin == 1) {
                                                                            echo '<label for="image" class="btn btn-trangdem top-0 end-0">
                                                    <i class="far fa-image" aria-hidden="true"></i>
                                                </label>';
                                                                        }
                                                                        ?>
                                                                        <input type="file" name="image[]" id="image" multiple
                                                                            style="display: none;">
                                                                    </div>
                                                                    <span id="image-count"
                                                                        class="text-muted position-absolute top-0 end-0"></span>
                                                                    <span id="notify" class="text-danger"></span>
                                                                </div>
                                                                <button class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-20" id="btn-cmt" type="submit">Bình
                                                                    luận</button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                        <!-- Your existing HTML form code ... -->
                                                        <script>
                                                            document.getElementById('image').addEventListener('change', function () {
                                                                var fileCount = this.files.length;
                                                                var imageCountElement = document.getElementById('image-count');
                                                                if (fileCount > 0) {
                                                                    imageCountElement.innerText = 'Đã chọn ' + fileCount + ' ảnh';
                                                                } else {
                                                                    imageCountElement.innerText = '';
                                                                }
                                                            });

                                                            // Xử lý sự kiện khi bình luận được gửi
                                                            document.getElementById('form').addEventListener('submit', function (event) {
                                                                // Prevent form submission if $_status is 0
                                                                if (<?php echo $_status; ?> === 0) {
                                                                    event.preventDefault();
                                                                    var thongbaoElement = document.createElement('div');
                                                                    thongbaoElement.innerHTML = '<span class="text-danger pb-2 font-weight-bold">Yêu cầu tài khoản đã mở Thành viên.</span>';
                                                                    document.getElementById('form').prepend(thongbaoElement);
                                                                    return;
                                                                }

                                                                event.preventDefault(); // Ngăn chặn gửi biểu mẫu một cách tự động

                                                                // Tạo một đối tượng FormData để chứa dữ liệu biểu mẫu
                                                                var formData = new FormData(this);

                                                                // Gửi yêu cầu AJAX
                                                                var xhr = new XMLHttpRequest();
                                                                xhr.open('POST', this.action, true);
                                                                xhr.onload = function () {
                                                                    if (xhr.status === 200) {
                                                                        // Xử lý thành công, tải lại trang
                                                                        window.location.href = 'bai-viet.php?id=<?php echo $post_id; ?>';
                                                                    } else {
                                                                        // Xử lý lỗi
                                                                        console.log('Đã xảy ra lỗi: ' + xhr.status);
                                                                    }
                                                                };

                                                                xhr.send(formData);
                                                            });
                                                        </script>
                                                        <?php
            }
                                        }
                                        ob_start(); // start buffering output

                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            if (isset($_POST['delete_post']) || isset($_POST['pin_post']) || isset($_POST['delete_pin_post']) || isset($_POST['block_comments']) || isset($_POST['unlock_comments'])) {
                                                $post_id = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['post_id']);

                                                // Function to check if the current user has admin permission
                                                function hasAdminPermission($conn, $user_id)
                                                {
                                                    $admin_query = "SELECT admin FROM account WHERE id = ?";
                                                    $admin_stmt = $conn->prepare($admin_query);
                                                    $admin_stmt->bindParam(1, $user_id, PDO::PARAM_INT);
                                                    $admin_stmt->execute();
                                                    $admin_row = $admin_stmt->fetch(PDO::FETCH_ASSOC);
                                                    return $admin_row['admin'] == 1;
                                                }

                                                // Function to delete a post and its comments
                                                function deletePostAndComments($conn, $post_id)
                                                {
                                                    $delete_comments_query = "DELETE FROM comments WHERE post_id = ?";
                                                    $delete_posts_query = "DELETE FROM posts WHERE id = ?";

                                                    $delete_comments_stmt = $conn->prepare($delete_comments_query);
                                                    $delete_comments_stmt->bindParam(1, $post_id, PDO::PARAM_INT);
                                                    if ($delete_comments_stmt->execute()) {
                                                        $delete_posts_stmt = $conn->prepare($delete_posts_query);
                                                        $delete_posts_stmt->bindParam(1, $post_id, PDO::PARAM_INT);
                                                        if ($delete_posts_stmt->execute()) {
                                                            return true;
                                                        }
                                                    }
                                                    return false;
                                                }

                                                // Function to pin a post
                                                function pinPost($conn, $post_id)
                                                {
                                                    $pin_query = "UPDATE posts SET ghimbai = 1 WHERE id = ?";
                                                    $pin_stmt = $conn->prepare($pin_query);
                                                    $pin_stmt->bindParam(1, $post_id, PDO::PARAM_INT);
                                                    return $pin_stmt->execute();
                                                }

                                                // Function to unpin a post
                                                function unpinPost($conn, $post_id)
                                                {
                                                    $unpin_query = "UPDATE posts SET ghimbai = 0 WHERE id = ?";
                                                    $unpin_stmt = $conn->prepare($unpin_query);
                                                    $unpin_stmt->bindParam(1, $post_id, PDO::PARAM_INT);
                                                    return $unpin_stmt->execute();
                                                }

                                                // Function to block/unblock comments on a post
                                                function updateCommentStatus($conn, $post_id, $status)
                                                {
                                                    $update_query = "UPDATE posts SET trangthai = ? WHERE id = ?";
                                                    $update_stmt = $conn->prepare($update_query);
                                                    $update_stmt->bindParam(1, $status, PDO::PARAM_INT);
                                                    $update_stmt->bindParam(2, $post_id, PDO::PARAM_INT);
                                                    return $update_stmt->execute();
                                                }

                                                $query = "SELECT posts.*, account.admin, player.account_id FROM posts
                                           LEFT JOIN player ON posts.username = player.name
                                           LEFT JOIN account ON player.account_id = account.id
                                           WHERE posts.id = ?";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
                                                $stmt->execute();
                                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                if ($row) {
                                                    $current_user_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : null;
                                                    $post_author_id = intval($row['account_id']);
                                                    $is_admin = hasAdminPermission($conn, $current_user_id);

                                                    if ($is_admin || ($current_user_id && $current_user_id === $post_author_id)) {
                                                        if (isset($_POST['delete_post'])) {
                                                            if (deletePostAndComments($conn, $post_id)) {
                                                                header("Location: /");
                                                                exit();
                                                            } else {
                                                                echo "Error: Failed to delete post or comments.";
                                                            }
                                                        } elseif (isset($_POST['pin_post'])) {
                                                            if (pinPost($conn, $post_id)) {
                                                                header("Location: /bai-viet.php?id=" . $post_id);
                                                                exit();
                                                            } else {
                                                                echo "Error: Failed to pin post.";
                                                            }
                                                        } elseif (isset($_POST['delete_pin_post'])) {
                                                            if (unpinPost($conn, $post_id)) {
                                                                header("Location: /bai-viet.php?id=" . $post_id);
                                                                exit();
                                                            } else {
                                                                echo "Error: Failed to unpin post.";
                                                            }
                                                        } elseif (isset($_POST['block_comments'])) {
                                                            if (updateCommentStatus($conn, $post_id, 1)) {
                                                                header("Location: /bai-viet.php?id=" . $post_id);
                                                                exit();
                                                            } else {
                                                                echo "Error: Failed to block comments.";
                                                            }
                                                        } elseif (isset($_POST['unlock_comments'])) {
                                                            if (updateCommentStatus($conn, $post_id, 0)) {
                                                                header("Location: /bai-viet.php?id=" . $post_id);
                                                                exit();
                                                            } else {
                                                                echo "Error: Failed to unlock comments.";
                                                            }
                                                        }
                                                    } else {
                                                        echo "Error: You don't have permission to perform this action.";
                                                    }
                                                } else {
                                                    echo "Error: Post not found.";
                                                }
                                            }
                                        }

                                        ob_end_flush(); // flush the output buffer

                                        if (isset($_SESSION['id'])) {
                                            $current_user_id = $_SESSION['id'];
                                            $admin_query = "SELECT admin FROM account WHERE id = ?";
                                            $admin_stmt = $conn->prepare($admin_query);
                                            $admin_stmt->bindParam(1, $current_user_id, PDO::PARAM_INT);
                                            $admin_stmt->execute();
                                            $admin_row = $admin_stmt->fetch(PDO::FETCH_ASSOC);
                                            $is_admin = $admin_row['admin'] == 1;
                                        }

                                        $query2 = "SELECT account.*, account.admin FROM account LEFT JOIN player ON player.account_id = account.id WHERE account.admin = 1";
                                        $result2 = $conn->query($query2);

                                        if ($row2 = $result2->fetch()) {
                                            if ($row2['admin'] == 1) {
                                                if (isset($is_admin) && $is_admin) {
                                                    // Display the buttons for an admin user
                                                    ?>
                                                            <form method="POST">
                                                                <button class="btn btn-trangdem btn-light" id="btn-delete"
                                                                    name="delete_post" type="submit">Xoá Bài</button>
                                                                <button class="btn btn-trangdem btn-light" id="btn-pin"
                                                                    name="pin_post" type="submit">Ghim Bài</button>
                                                                <button class="btn btn-trangdem btn-light" id="btn-delete-pin"
                                                                    name="delete_pin_post" type="submit">Bỏ Ghim</button>
                                                                <button class="btn btn-trangdem btn-light" id="btn-block-comments"
                                                                    name="block_comments" type="submit">Chặn Bình Luận</button>
                                                                <button class="btn btn-trangdem btn-light" id="btn-unlock-comments"
                                                                    name="unlock_comments" type="submit">Mở Bình Luận</button>
                                                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                                            </form>
                                                            <?php
                                                }
                                            }
                                        }
                                        ?>
                                                <script>
                                                    const form = document.querySelector('#form');
                                                    const submitBtn = form.querySelector('#btn-cmt');
                                                    const submitError = form.querySelector('#notify');
                                                    const traloiInput = document.getElementById('traloi');
                                                    form.addEventListener('submit', (event) => {
                                                        const traloi = traloiInput.value.trim().length;
                                                        if (traloi < script 1) {
                                                        event.preventDefault();
                                                        submitError.innerHTML =
                                                            '<strong>Lỗi:</strong> Bình luận phải có ít nhất 1 ký tự!';
                                                        submitError.style.display = 'block';
                                                        submitBtn.scrollIntoView({
                                                            behavior: 'smooth',
                                                            block: 'start'
                                                        });
                                                    }
                                                                                                                                                                                                                                                                                                                                                                                                                                 });
                                                    traloiInput.addEventListener('keydown', (event) => {
                                                        if (event.keyCode === 13 && !event.shiftKey) {
                                                            event.preventDefault();
                                                            submitBtn.click();
                                                        }
                                                    });
                                                </script>
</p>
    </div>
        </div>
            </div>
                </td>
                    </tr>
                        </tbody>
                            </table>
                                <div>
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