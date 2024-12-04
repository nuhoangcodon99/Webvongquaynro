<?php
require_once '../core/connect.php';
require_once '../core/set.php';

// Kiểm tra xem có yêu cầu POST đến hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ yêu cầu POST
    $fieldName = $_POST['fieldName'];
    $fieldValue = $_POST['fieldValue'];

    // Xử lý dữ liệu trước khi lưu vào cơ sở dữ liệu
    $fileExtensions = array(
        'android' => 'apk',
        'windows' => 'zip',
        'iphone' => 'ipa',
        'java' => 'jar'
    );

    // Đường dẫn thư mục download
    $downloadFolderPath = '../download/';

    // Kiểm tra nếu người dùng chỉ ghi tên file mà không có đuôi tệp
    if (!preg_match('/\.(apk|zip|ipa|jar)$/', $fieldValue)) {
        $extension = $fileExtensions[$fieldName] ?? ''; // Lấy đuôi tệp tương ứng từ mảng fileExtensions
        $fieldValue = $fieldValue . '.' . $extension; // Thêm đuôi tệp vào giá trị
    }

    // Tự động thêm đường dẫn vào giá trị tên tệp
    $fieldValue = $downloadFolderPath . $fieldValue;

    // Tiến hành cập nhật dữ liệu
    $query = "UPDATE adminpanel SET $fieldName = :fieldValue";
    $params = array(
        ':fieldValue' => $fieldValue
    );

    try {
        $statement = $conn->prepare($query);
        if ($statement->execute($params)) {
            // Nếu cập nhật thành công, gửi phản hồi về cho trình duyệt
            $response = array(
                'status' => 'success',
                'title' => 'Chỉnh Sửa Liên Kết Tải',
                'message' => 'Cập nhật thông tin thành công!'
            );
        } else {
            // Nếu xảy ra lỗi trong quá trình cập nhật
            $response = array(
                'status' => 'error',
                'title' => 'Chỉnh Sửa Liên Kết Tải',
                'message' => 'Lỗi: Không thể cập nhật thông tin.'
            );
        }
    } catch (PDOException $e) {
        // Xử lý lỗi nếu có lỗi trong quá trình thực hiện truy vấn
        $response = array(
            'status' => 'error',
            'title' => 'Chỉnh Sửa Liên Kết Tải',
            'message' => 'Lỗi: ' . $e->getMessage()
        );
    }

    // Đóng kết nối cơ sở dữ liệu
    $conn = null;

    // Gửi phản hồi dạng JSON về cho trình duyệt
    echo json_encode($response);
} else {
    // Nếu không có yêu cầu POST, gửi phản hồi lỗi về cho trình duyệt
    $response = array(
        'status' => 'error',
        'title' => 'Chỉnh Sửa Liên Kết Tải',
        'message' => 'Lỗi: Yêu cầu không hợp lệ.'
    );
    echo json_encode($response);
}
?>