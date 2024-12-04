<?php
require_once 'connect.php';
require_once 'set.php';
$items = json_decode(file_get_contents(__DIR__ . "/listItem.json"), true);

function generateGiftCode($length)
{
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789QWERTYUIOASDFGHJKLZXCVBNM';
    $giftCode = '';
    for ($i = 0; $i < $length; $i++) {
        $giftCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $giftCode;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // $type = $_POST['type'];
    $spins = 10;
    $currentTimestamp = time() * 5000;

    // Lấy thông tin người dùng từ cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT * FROM account WHERE username = ?");
    $stmt->execute([$_username]);
    $getUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($getUser) {
        if ($getUser['thoi_vang'] < $spins) {
            echo json_encode([
                'error' => true,
                'message' => 'Bạn không có đủ lượt quay!'
            ]);
            exit;
        }

        // Chuẩn bị mảng tỷ lệ cho việc chọn ngẫu nhiên
        $totalRatio = array_sum(array_column($items, 'ratio'));
        $ratioArray = [];

        foreach ($items as $index => $item) {
            for ($i = 0; $i < $item['ratio']; $i++) {
                $ratioArray[] = $index;
            }
        }

        $selectedItems = [];
        for ($i = 0; $i < $spins; $i++) {
            $randomIndex = rand(0, $totalRatio - 1);
            $selectedItemIndex = $ratioArray[$randomIndex];
            $selectedItems[] = $items[$selectedItemIndex];
        }

        $giftCodes = [];
        foreach ($selectedItems as $selectedItem) {
            $giftCode = generateGiftCode(10);
            $giftCodes[] = $giftCode;

            // Thêm vào bảng giftcode
            $insertGiftCodeQuery = "INSERT INTO giftcode (code, listItem, `limit`, type, listUser) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertGiftCodeQuery);
            $stmt->execute([$giftCode, json_encode($selectedItem['params']), 1, 1, $getUser['id']]);

            // Thêm vào bảng spin
            $insertSpinQuery = "INSERT INTO spin (account_id, code, name, time_stamps) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertSpinQuery);
            $stmt->execute([$getUser['id'], $giftCode, $selectedItem['name'], $currentTimestamp]);
        }

        // Cập nhật lại số lượt quay của người dùng
        $updateUserQuery = "UPDATE account SET thoi_vang = thoi_vang - ? WHERE username = ?";
        $stmt = $conn->prepare($updateUserQuery);
        $stmt->execute([$spins, $getUser['username']]);


        echo json_encode([
            'error' => false,
            'data' => $selectedItems,
            'giftCodes' => $giftCodes
        ]);


        // } else {
        //     echo json_encode([
        //         'error' => true,
        //         'message' => 'Người dùng không tồn tại!'
        //     ]);
    }
}

?>