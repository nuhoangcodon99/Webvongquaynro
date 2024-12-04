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
<div class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_" style ="color: #C0C0C0;">Bài viết mới</div></div>
<div class="ant-col ant-col-24">
    <div class="ant-list ant-list-split">
        <div class="ant-spin-nested-loading">
            <div class="ant-spin-container">
                <ul class="ant-list-items">
                    <div id="data_news">
                    <li class="ant-list-item home_page_listItem__GD_iE">
<img src="public/images/osFJ5m8.png" class="home_page_listItemAvatar__cXjbm" />
    <div class="ant-list-item-meta home_page_listItemTitle__YB3V5">
        <div class="ant-list-item-meta-content">
            <h4 class="ant-list-item-meta-title">
                <a href="/news/11.php">CƠ CHẾ ĐỆ TỬ</a></h4>
                    <div class="ant-list-item-meta-description">
                    Đăng bởi: <b style="color: red;">ADMIN</b> - Ngày: 01/01/2025
                    </div>
                </div>
            </div>
                </li>
                    <li class="ant-list-item home_page_listItem__GD_iE">
<img src="public/images/osFJ5m8.png" class="home_page_listItemAvatar__cXjbm" />
    <div class="ant-list-item-meta home_page_listItemTitle__YB3V5">
        <div class="ant-list-item-meta-content">
            <h4 class="ant-list-item-meta-title">
                <a href="/news/10.php">HƯỚNG DẪN NÂNG SET KÍCH HOẠT</a></h4>
                    <div class="ant-list-item-meta-description">
                    Đăng bởi: <b style="color: red;">ADMIN</b> - Ngày: 01/01/2025
                    </div>
                </div>
            </div>
                </li>
                    <li class="ant-list-item home_page_listItem__GD_iE">
<img src="public/images/osFJ5m8.png" class="home_page_listItemAvatar__cXjbm" />
    <div class="ant-list-item-meta home_page_listItemTitle__YB3V5">
        <div class="ant-list-item-meta-content">
            <h4 class="ant-list-item-meta-title">
                <a href="/news/9.php">Hướng Dẫn Mở Thành Viên</a></h4>
                    <div class="ant-list-item-meta-description">
                    Đăng bởi: <b style="color: red;">ADMIN</b> - Ngày: 01/01/2025
                    </div>
                </div>
            </div>
                  </li>
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
            // Tính toán số lượng bài viết
            $query_count = "SELECT COUNT(*) AS count FROM posts";
            $result_count = $conn->query($query_count);
            $row_count = $result_count->fetch(PDO::FETCH_ASSOC);
            $count = $row_count['count'];

            // Thiết lập giới hạn cho mỗi trang
            $limit = 20;

            // Tính toán số lượng trang
            $total_pages = ceil($count / $limit);

            // Lấy số trang từ tham số URL
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

            // Xác định vị trí của trang hiện tại trong danh sách các trang
            $page_position = min(max(1, $page - 1), max(1, $total_pages - 2));

            // Tính toán giới hạn kết quả truy vấn theo biến $limit và $page
            $offset = ($page - 1) * $limit;
            $query = "SELECT posts.*, player.gender, account.admin, account.server_login FROM posts
      LEFT JOIN player ON posts.username = player.name
      LEFT JOIN account ON player.account_id = account.id
      WHERE posts.username = player.name
      ORDER BY posts.id DESC LIMIT :limit OFFSET :offset";

            $stmt = $conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $post_id = $row['id'];

                // Retrieve user avatar and name
                $gender = $row['gender'];
                    $admin = $row['admin'];
                    $avatar_url = "";

                    if ($admin == 1) {
                        if ($gender == 1) {
                            $avatar_url = "image/avatar4.png";
                        } elseif ($gender == 2) {
                            $avatar_url = "image/avatar11.png";
                        } else {
                            $avatar_url = "image/avatar99.png";
                        }
                    } else {
                        if ($gender == 1) {
                            $avatar_url = "image/avatar1.png";
                        } elseif ($gender == 2) {
                            $avatar_url = "image/avatar2.png";
                        } else {
                            $avatar_url = "image/avatar0.png";
                        }
                    }

                // Display post title and author name
                echo '<hr>';
                echo '<div class="box-stt"><div style="width: 35px; float:left; margin-right: 5px;"><img src="' . $avatar_url . '" alt="Avatar" style="width: 30px"></div>';
                echo '<div class="box-right">';

                if ($row['admin'] == 1) {
                    echo '<a href="bai-viet.php?id=' . $row['id'] . '"><h3 class="ant-list-item-meta-title"><span class="text-danger font-weight-bold">' . $row['tieude'] . '</span></a></h3>';
                    echo '<div class="box-name" style="font-size: 9px;"> Bởi <span class="text-danger font-weight-bold">';
                    echo $row['username'];
                    echo '<span class="hide-menu text-capitalize fw-bolder fs-9"> (' . $row['server_login'] . ' Sao)</span>';
                    echo ' <span class="" style="flex-wrap:wrap;margin-bottom:-10px" style="padding-bottom:10px"><i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i>'; // Icon sao cho quản trị viên
                    echo '</span>';
                } else {
                    echo '<a href="bai-viet.php?id=' . $row['id'] . '">' . $row['tieude'] . '</a>';
                    echo '<div class="box-name" style="font-size: 9px;"> Bởi ' . $row['username'] . '';
                    echo '<span class="hide-menu text-capitalize fw-bolder fs-9"> (' . $row['server_login'] . ' Sao)</span>';
                }

                $query2 = "SELECT player.account_id, account.admin, account.tichdiem, posts.tinhtrang AS post_tinhtrang
                    FROM comments
                    INNER JOIN player ON comments.nguoidung = player.name
                    INNER JOIN account ON player.account_id = account.id
                    INNER JOIN posts ON comments.post_id = posts.id
                    WHERE comments.post_id = :post_id
                    ORDER BY comments.id ASC";

                $stmt2 = $conn->prepare($query2);
                $stmt2->bindValue(':post_id', $post_id, PDO::PARAM_INT);
                $stmt2->execute();

                $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                $danhhieu_hienthi = false;
                $tat_danhhieu = false;
                $binhluan_dau = false;
                $binhluan_diem = 0;
                $binhluan_mau = "";
                $binhluan_danhhieu = "";

                foreach ($rows as $row2) {
                    $tichdiem = $row2['tichdiem'];
                    $tinhtrang_post = $row2['post_tinhtrang'];

                    if ($row2['admin'] == 1 && !$danhhieu_hienthi) {
                        // Check if the post is marked as "Đã trả lời" (tinhtrang = 1) and an admin has commented
                        if ($tinhtrang_post == 1) {
                            echo '<span class="text-success"> Đã trả lời</span>';
                        } elseif ($tinhtrang_post == 2) {
                            echo '<span class="text-primary"> Đã ghi nhận</span>';
                        } else {
                            echo '<span class="text-info"> Đang thảo luận</span>';
                        }
                        $danhhieu_hienthi = true;
                        $tat_danhhieu = true;
                        // Đánh dấu rằng đã hiển thị thông tin của admin
                        // và không cần hiển thị danh hiệu nữa
                    } elseif ($row2['admin'] != 1) {
                        if ($tichdiem >= 200) {
                            $danh_hieu = "(Chuyên Gia Đã Giải Đáp)";
                            $color = "#800000";
                        } elseif ($tichdiem >= 100) {
                            $danh_hieu = "(Người Hỏi Đáp Đã Trả Lời)";
                            $color = "#A0522D";
                        } elseif ($tichdiem >= 35) {
                            $danh_hieu = "(Người Bắt Chuyện Đã Trả Lời)";
                            $color = "#6A5ACD";
                        } else {
                            $danh_hieu = "";
                        }
                        if ($danh_hieu !== "" && !$tat_danhhieu) {
                            if (!$binhluan_dau) {
                                $binhluan_mau = $color;
                                $binhluan_danhhieu = $danh_hieu;
                                $binhluan_dau = true;
                            }
                        }
                    }
                }

                if ($binhluan_danhhieu !== "" && !$tat_danhhieu) {
                    echo '<span style="color:' . $binhluan_mau . ' !important"> ' . $binhluan_danhhieu . '</span>';
                }

                echo '</div></div></div>';
            }
            ?>
        </div>
    </div>
</div>
<div class="container pb-2">
    <div class="row mt-3">
        <div class="col-5">
            <?php
            if ($_login === null) {

            } else {
                echo '<a class="ant-btn ant-btn-default header-menu-item header-menu-item-active w-80" href="dang-bai.php">Đăng bài mới</a>';
            }
            ?>
        </div>
        <?php
        echo '<div class="col-7 text-right">';
        echo '<ul class="pagination" style="justify-content: flex-end;">';
        if ($page > 1) {
            echo '<li><a class="ant-btn header-menu-item-active w-1" href="?page=' . ($page - 1) . '"><</a></li></br>';
        }
        $start_page = max(1, min($total_pages - 2, $page - 1));
        $end_page = min($total_pages, max(2, $page + 1));
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i >= $start_page && $i <= $end_page) {
                $class_name = "btn btn-sm btn-light";
                if ($i == $page) {
                    $class_name = "ant-btn header-menu-item-active w-1";
                }
                echo '<li><a class="' . $class_name . '" href="?page=' . $i . '">' . $i . '</a></li></br>';
            }
        }
        if ($page < $total_pages) {
            echo '<li><a class="ant-btn header-menu-item-active w-1" href="?page=' . ($page + 1) . '">></a></li>';
        }
        echo '</ul>';
        echo '</div>';
        ?>

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