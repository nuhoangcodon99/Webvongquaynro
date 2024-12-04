<?php
$apikey = 'A5811DDF253B56F411218B793511052E'; //API key, lấy từ website thesieutoc.net thay vào trong cặp dấu ''
// database Mysql config
$local_db = "localhost";
$user_db = "root";
$pass_db = "";
$name_db = "nro";

try {
    $conn = new PDO("mysql:host={$local_db};dbname={$name_db}", $user_db, $pass_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối Cơ sở dữ liệu: " . $e->getMessage());
}

$conn->exec("SET NAMES 'utf8'");

$validate = ValidateCallback($_POST);
if ($validate != false) { //Nếu xác thực callback đúng thì chạy vào đây.
    $status = $validate['status']; //Trạng thái thẻ nạp, thẻ thành công = thanhcong , Thẻ sai, thẻ sai mệnh giá = thatbai
    $serial = $validate['serial']; //Số serial của thẻ.
    $pin = $validate['pin']; //Mã pin của thẻ.
    $card_type = $validate['card_type']; //Loại thẻ. vd: Viettel, Mobifone, Vinaphone.
    $amount = $validate['amount']; //Mệnh giá của thẻ. nếu bạn sài thêm hàm sai mệnh giá vui lòng sử dụng thêm hàm này tự cập nhật mệnh giá thật kèm theo desc là mệnh giá củ
    $real_amount = $validate['real_amount']; // thực nhận đã trừ chiết khấu
    $content = $validate['content']; // id transaction

    $stmt = $conn->prepare("SELECT * FROM `trans_log` WHERE status = 0 AND trans_id = :content AND pin = :pin AND seri = :serial AND type = :card_type");
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':pin', $pin);
    $stmt->bindParam(':serial', $serial);
    $stmt->bindParam(':card_type', $card_type);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        print_r($result);
        if ($status == 'thanhcong') {

            // Fetch giatri from trans_log table
            $stmt = $conn->prepare("SELECT giatri FROM `trans_log` WHERE `id` = :id");
            $stmt->bindParam(':id', $result['id']);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $giatri = $row['giatri']; // Assuming giatri is a column in the trans_log table

            //Xử lý nạp thẻ thành công tại đây.
            $price = $amount * $giatri;

            //Xử lý nạp thẻ thành công tại đây.
            $price = $amount * $giatri;
            $stmt = $conn->prepare("UPDATE account SET tongnap = tongnap + :price, vnd = vnd + :price WHERE username = :username");
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':username', $result['name']);
            $stmt->execute();
            $stmt = $conn->prepare("UPDATE `trans_log` SET `status` = 1 WHERE `id` = :id");
            $stmt->bindParam(':id', $result['id']);
            $stmt->execute();
        } else if ($status == 'saimenhgia') {
            //Xử lý nạp thẻ sai mệnh giá tại đây.
            $stmt = $conn->prepare("UPDATE `trans_log` SET status = 3, `amount` = :amount WHERE `id` = :id");
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':id', $result['id']);
            $stmt->execute();
        } else {
            //Xử lý nạp thẻ thất bại tại đây.
            $stmt = $conn->prepare("UPDATE `trans_log` SET status = 2 WHERE `id` = :id");
            $stmt->bindParam(':id', $result['id']);
            $stmt->execute();
        }

        # Lưu log Nạp Thẻ
        $file = "card.log";
        $fh = fopen($file, 'a') or die("cant open file");
        fwrite($fh, "Tai khoan: " . $result['name'] . ", data: " . json_encode($_POST));
        fwrite($fh, "\r\n");
        fclose($fh);
    }
}

function ValidateCallback($out)
{ //Hàm kiểm tra callback từ server
    if (
        isset(
        $out['status'],
        $out['serial'],
        $out['pin'],
        $out['card_type'],
        $out['amount'],
        $out['content'],
        $out['real_amount']
    )
    ) {
        return $out; //xác thực thành công, return mảng dữ liệu từ server trả về.
    } else {
        return false; //Xác thực callback thất bại.
    }
}