<?php
require_once '../core/set.php';
require_once '../core/connect.php';

// Kiểm tra và hiển thị chức năng "Đã trả lời" nếu có quyền admin và người dùng đã đăng nhập
if ($_login === null) {

} else {
    if ($_admin == 1) {
        if (isset($_GET['id']) && isset($_GET['tinhtrang'])) {
            $post_id = $_GET['id'];
            $tinhtrang = $_GET['tinhtrang'];

            // Kiểm tra giá trị "tinhtrang" hợp lệ (1 hoặc 2)
            if ($tinhtrang != 2 && $tinhtrang != 1 && $tinhtrang != 0) {
                // Giá trị "tinhtrang" không hợp lệ, xử lý lỗi tại đây
                echo "Giá trị 'tinhtrang' không hợp lệ.";
                exit;
            }

            // Thực hiện truy vấn cập nhật "tinhtrang" của bài viết
            try {
                $query = "UPDATE posts SET tinhtrang = :tinhtrang WHERE id = :post_id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":tinhtrang", $tinhtrang, PDO::PARAM_INT);
                $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
                if ($stmt->execute()) {
                    // Cập nhật thành công, chuyển hướng về trang chi tiết bài viết
                    header("Location:../bai-viet.php?id=" . $post_id);
                    exit;
                } else {
                    // Xử lý lỗi khi không thể cập nhật "tinhtrang" của bài viết
                    echo "Có lỗi xảy ra khi cập nhật 'tinhtrang' của bài viết.";
                    exit;
                }
            } catch (PDOException $e) {
                // Xử lý lỗi khi có exception xảy ra
                echo "Lỗi truy vấn: " . $e->getMessage();
                exit;
            }
        } else {
            // Tham số không hợp lệ, xử lý lỗi tại đây
            echo "Tham số không hợp lệ.";
            exit;
        }
    }
    // Chưa đăng nhập, chuyển hướng đến trang khác bằng JavaScript
    echo '<script>window.location.href = "../dien-dan";</script>';
    exit(); // Đảm bảo dừng thực thi code sau khi chuyển hướng
}
?>
