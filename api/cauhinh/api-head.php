<?php
require_once '../../core/set.php';
require_once '../../core/connect.php';

$query = "SELECT player.name, player.gender, account.admin, account.tichdiem
          FROM player
          LEFT JOIN account ON player.account_id = account.id
          WHERE account.username = :username";

$statement = $conn->prepare($query);
$statement->bindParam(":username", $_username);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $gender = $row['gender'];
    $tichdiem = $row['tichdiem'];
    $admin = $row['admin'];
    $avatar_url = "";;
    $avatar_url = "";

    if ($admin == 1) {
        if ($gender == 0) {
            $avatar_url = (date('m') == 12 && date('d') == 25) ? "../image/avatar99.png" : "../image/avatar99.png";
        } elseif ($gender == 1) {
            $avatar_url = (date('m') == 12 && date('d') == 25) ? "../image/avatar99.png" : "../image/avatar99.png";
        } else {
            $avatar_url = (date('m') == 12 && date('d') == 25) ? "../image/avatar99.png" : "../image/avatar99.png";
        }
    } else {
        if ($gender == 0) {
            $avatar_url = (date('m') == 12 && date('d') == 25) ? "../image/avatar15.png" : "../image/avatar0.png";
        } elseif ($gender == 1) {
            $avatar_url = (date('m') == 12 && date('d') == 25) ? "../image/avatar16.png" : "../image/avatar1.png";
        } else {
            $avatar_url = (date('m') == 12 && date('d') == 25) ? "../image/avatar17.png" : "../image/avatar2.png";
        }
    }

    $color = "";
    if ($tichdiem >= 500) {
        $danh_hieu = "(Chuyên Gia)";
        $color = "#800000"; // sets color to red
    } elseif ($tichdiem >= 300) {
        $danh_hieu = "(Hỏi Đáp)";
        $color = "#A0522D"; // sets color to yellow
    } elseif ($tichdiem >= 200) {
        $danh_hieu = "(Người Bắt Chuyện)";
        $color = "#6A5ACD";
    } else {
        $danh_hieu = "";
        $color = "";
    }

    if ($admin == 1) {
        $name_str = '<span class="ant-btn ant-btn-default header-btn-login" "style = "color: #D1D3D4; style="margin-right: 5px;">' . $row['name'] . '</span><br>';
        $name_str .= '<span class=" pt-1 mb-0" style = "color: #A1D4E3;">(Admin)</span>';
    } else {
        $name_str = '<span class="ant-btn ant-btn-default header-btn-login" style="color: red; style="margin-right: 5px;">' . $row['name'] . '</span></p>';
        if ($danh_hieu !== "") {
            $name_str .= '<div style="font-size: 9px; padding-top: 5px"><span style="color:' . $color . ' !important">' . $danh_hieu . '</span></div>';
        }
    }

    echo '<div><img src="' . $avatar_url . '" alt="Avatar" style="width: 50px"></div>';
    echo $name_str;
}
?>
<p class="text-main font-weight-bold pt-1 mb-0"></p>
<p class="pt-0">Số dư:
    <?php echo number_format($_coin, 0, ',') ?> VNĐ
</p>
</div>

<?php
// Chưa đăng nhập, chuyển hướng đến trang khác bằng JavaScript
echo '<script>window.location.href = "../../dien-dan";</script>';
exit(); // Đảm bảo dừng thực thi code sau khi chuyển hướng
?>