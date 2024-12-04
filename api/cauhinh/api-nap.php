<?php
require_once '../../core/cauhinh.php';
require_once '../../core/connect.php';

$response = array(); // Initialize the response array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the form data
    $_apikey = filter_input(INPUT_POST, 'apikey', FILTER_SANITIZE_STRING);
        // $_deviceId = filter_input(INPUT_POST, 'deviceId', FILTER_SANITIZE_STRING);
        // $_sessionId = filter_input(INPUT_POST, 'sessionId', FILTER_SANITIZE_STRING);
        // $_tenthemb = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        // $_stkmb = filter_input(INPUT_POST, 'stkmb', FILTER_SANITIZE_STRING);
        // $_usermb = filter_input(INPUT_POST, 'usermb', FILTER_SANITIZE_STRING);
        // $_matkhaumb = filter_input(INPUT_POST, 'matkhaumb', FILTER_SANITIZE_STRING);

    try {
        // Tiến hành truy vấn cơ sở dữ liệu để lấy dữ liệu hiện tại từ cột 'domain', 'logo' và 'trangthai'
        $query_select = "SELECT * FROM adminpanel";
        $statement_select = $conn->prepare($query_select);
        $statement_select->execute();

        if ($statement_select->rowCount() == 0) {
            // Thông báo lỗi nếu không có dữ liệu
            $response['type'] = 'error';
            $response['message'] = 'Không có dữ liệu trong cơ sở dữ liệu!';
        } else {
            $row = $statement_select->fetch(PDO::FETCH_ASSOC);
            $check_apikey = $row['apikey'];
            // $check_deviceId = $row['deviceId'];
            // $check_sessionId = $row['sessionId'];
            // $check_stkmb = $row['stkmb'];
            // $check_tenthemb = $row['name'];
            // $check_usermb = $row['usermb'];
            // $check_matkhaumb = $row['matkhaumb'];

            // Kiểm tra sự thay đổi trước khi thực hiện câu truy vấn UPDATE
            $update_required = false; // Biến flag để kiểm tra có thay đổi hay không

            // APIKEY THESIEUTOC
            if ($_apikey != $check_apikey) {
                $update_required = true;
            }

            // if ($_deviceId != $check_deviceId) {
            //     $update_required = true;
            // }

            // if($_sessionId != $check_sessionId) {
            //     $update_required = true;
            // }

            // // SỐ TÀI KHOẢN MBBANK
            // if ($_stkmb != $check_stkmb) {
            //     $update_required = true;
            // }

            // // TÊN TÀI KHOẢN
            // if ($_tenthemb != $check_tenthemb) {
            //     $update_required = true;
            // }

            // // SỐ ĐIỆN THOẠI
            // if ($_usermb != $check_usermb) {
            //     $update_required = true;
            // }

            // // TÊN TÀI KHOẢN
            // if ($_matkhaumb != $check_matkhaumb) {
            //     $update_required = true;
            // }

            if (!$update_required) {
                $response['type'] = 'info';
                $response['message'] = 'Bạn chưa thay đổi cấu hình nào!';
            } else {
                // Tiến hành cập nhật thông tin domain, logo và trạng thái trong cơ sở dữ liệu nếu có sự thay đổi
                $query_update = "UPDATE adminpanel SET apikey = :apikey
                -- , deviceId = :deviceId, sessionId = :sessionId, stkmb = :stkmb, name = :name, usermb = :usermb, matkhaumb = :matkhaumb
                ";
                $statement_update = $conn->prepare($query_update);

                // Bind the parameters for the UPDATE query
                $statement_update->bindParam(':apikey', $_apikey);
                // $statement_update->bindParam(':deviceId', $_deviceId);
                // $statement_update->bindParam(':sessionId', $_sessionId);
                // $statement_update->bindParam(':stkmb', $_stkmb);
                // $statement_update->bindParam(':name', $_tenthemb);
                // $statement_update->bindParam(':usermb', $_usermb);
                // $statement_update->bindParam(':matkhaumb', $_matkhaumb);


                // Execute the UPDATE query
                if ($statement_update->execute()) {
                    $response['type'] = 'success';
                    $response['message'] = 'Cập nhật cấu hình thông tin thành công!';
                } else {
                    $response['type'] = 'error';
                    $response['message'] = 'Lỗi: Không thể cập nhật thông tin.';
                }
            }
        }
    } catch (PDOException $e) {
        $response['type'] = 'error';
        $response['message'] = 'Lỗi: ' . $e->getMessage();
    }
} else {
    $response['type'] = 'error';
    $response['message'] = 'Lỗi: Yêu cầu không hợp lệ.';
}

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>