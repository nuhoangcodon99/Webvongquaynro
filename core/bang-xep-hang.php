<?php
include ('head.php');
?>

<div class="page-layout-body">

    <body>
        <style>
            th,
            td {
                white-space: nowrap;
                padding: 2px 4px !important;
                font-size: 11px;
            }
        </style>
        <div class="container color-forum pt-2">
            <div class="row">
                <div class="col">
                    <h6 class="text-center">BẢNG XẾP HẠNG ĐUA TOP SỨC MẠNH Ngọc Rồng</h6>
                    <table class="table table-borderless text-center">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Nhân vật</th>
                                <th>Sức Mạnh</th>
                                <th>Đệ Tử</th>
                                <th>Hành Tinh</th>
                                <th>Tổng</th>
                            </tr>
                        <tbody>
                            <?php
                            $query = "SELECT name, gender, 
    CASE 
        WHEN gender = 1 THEN CAST(JSON_UNQUOTE(JSON_EXTRACT(data_point, '$[11]')) AS SIGNED)
        WHEN gender = 2 THEN CAST(JSON_UNQUOTE(JSON_EXTRACT(data_point, '$[11]')) AS SIGNED)
        ELSE CAST(JSON_UNQUOTE(JSON_EXTRACT(data_point, '$[11]')) AS SIGNED)
    END AS second_value,
    SUBSTRING_INDEX(SUBSTRING_INDEX(JSON_UNQUOTE(JSON_EXTRACT(pet_power, '$[0]')), ',', 2), ',', -1) AS detu_sm,
    CAST(JSON_UNQUOTE(JSON_EXTRACT(data_point, '$[11]')) AS SIGNED) + CAST(COALESCE(SUBSTRING_INDEX(SUBSTRING_INDEX(JSON_UNQUOTE(JSON_EXTRACT(pet_power, '$[0]')), ',', 2), ',', -1), '0') AS SIGNED) AS tongdiem
FROM player
ORDER BY tongdiem DESC
LIMIT 10;";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            $countTop = 1;
                            if ($stmt->rowCount() > 0) {// Check the number of returned results
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                    <tr class="top_<?php echo $countTop; ?>">
                                        <td>
                                            <?php echo $countTop++; ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </td>
                                        <td>
                                            <?php
                                            $value = $row['second_value'];

                                            if ($value != '') {
                                                if ($value > 1000000000) {
                                                    echo number_format($value / 1000000000, 1, '.', '') . ' tỷ';
                                                } elseif ($value > 1000000) {
                                                    echo number_format($value / 1000000, 1, '.', '') . ' Triệu';
                                                } elseif ($value >= 1000) {
                                                    echo number_format($value / 1000, 1, '.', '') . ' k';
                                                } else {
                                                    echo number_format($value, 0, ',', '');
                                                }
                                            } else {
                                                echo 'Không có chỉ số sức mạnh';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $value = $row['detu_sm'];

                                            if ($value != '') {
                                                if ($value > 1000000000) {
                                                    echo number_format($value / 1000000000, 1, '.', '') . ' tỷ';
                                                } elseif ($value > 1000000) {
                                                    echo number_format($value / 1000000, 1, '.', '') . ' Triệu';
                                                } elseif ($value >= 1000) {
                                                    echo number_format($value / 1000, 1, '.', '') . ' k';
                                                } else {
                                                    echo number_format($value, 0, ',', '');
                                                }
                                            } else {
                                                echo 'Không đệ tử';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['gender'] == 0) {
                                                echo "Trái đất";
                                            } elseif ($row['gender'] == 1) {
                                                echo "Namec";
                                            } elseif ($row['gender'] == 2) {
                                                echo "Xayda";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $total = $row['tongdiem'];

                                            if ($total > 1000000000) {
                                                echo number_format($total / 1000000000, 1, '.', '') . ' tỷ';
                                            } elseif ($total > 1000000) {
                                                echo number_format($total / 1000000, 1, '.', '') . ' Triệu';
                                            } elseif ($total >= 1000) {
                                                echo number_format($total / 1000, 1, '.', '') . ' k';
                                            } else {
                                                echo number_format($total, 0, ',', '');
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo 'Máy Chủ 1 chưa có thông kê bảng xếp hạng!';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div class="container color-forum pt-2">
            <div class="row">
                <div class="col">
                    <h6 class="text-center">BẢNG XẾP HẠNG ĐUA TOP NẠP Ngọc Rồng</h6>
                    <table class="table table-borderless text-center">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Nhân vật</th>
                                <th>Tổng Nạp</th>
                            </tr>
                        <tbody>
                            <?php
                            $query = "SELECT p.name, a.tongnap FROM player p JOIN account a ON p.account_id = a.id ORDER BY a.tongnap DESC LIMIT 10;";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            $stt = 1;
                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '
                        <tr>
                            <td>' . $stt . '</td>
                            <td>' . $row['name'] . '</td>
                            <td>' . number_format($row['tongnap'], 0, ',') . 'đ</td>
                        </tr>';
                                    $stt++;
                                }
                            } else {
                                echo '<center><h6>Chưa có thống kê bảng xếp hạng top nạp tháng này!</h6></center>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div class="container color-forum pt-2">
            <div class="row">
                <div class="col">
                    <h6 class="text-center">BẢNG XẾP HẠNG ĐUA TOP NHIỆM VỤ Ngọc Rồng</h6>
                    <table class="table table-borderless text-center">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Nhân vật</th>
                                <th>Nhiệm vụ</th>
                            </tr>
                        <tbody>
                            <?php
                            $query = "SELECT name, JSON_EXTRACT(data_task, '$[1]') AS second_value FROM player ORDER BY CAST(JSON_EXTRACT(data_task, '$[1]') AS UNSIGNED) DESC LIMIT 20;";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            $stt = 1;
                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '
                        <tr>
                            <td>' . $stt . '</td>
                            <td>' . $row['name'] . '</td>
                            <td>' . number_format($row['second_value'], 0, ',') . '</td>
                        </tr>';
                                    $stt++;
                                }
                            } else {
                                echo '<center><h6>Chưa có thống kê bảng xếp hạng top nạp tháng này!</h6></center>';
                            }
                            ?>
                        </tbody>
                    </table>
					<hr>
        <div class="container color-forum pt-2">
            <div class="row">
                <div class="col">
                    <h6 class="text-center">BẢNG XẾP HẠNG ĐIỂM 20/11 Ngọc Rồng</h6>
                    <table class="table table-borderless text-center">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Nhân vật</th>
                                <th>Điểm</th>
                            </tr>
                        <tbody>
                            <?php
                            $query = "SELECT name, JSON_EXTRACT(point_trungthu, '$[0]') AS second_value FROM player ORDER BY CAST(JSON_EXTRACT(point_trungthu, '$[0]') AS UNSIGNED) DESC LIMIT 20;";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            $stt = 1;
                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '
                        <tr>
                            <td>' . $stt . '</td>
                            <td>' . $row['name'] . '</td>
                            <td>' . number_format($row['second_value'], 0, ',') . '</td>
                        </tr>';
                                    $stt++;
                                }
                            } else {
                                echo '<center><h6>Chưa có thống kê bảng xếp hạng top nạp tháng này!</h6></center>';
                            }
                            ?>
                        </tbody>
                    </table>
					<hr>
        <div class="container color-forum pt-2">
            <div class="row">
                <div class="col">
                    <h6 class="text-center">BẢNG XẾP HẠNG ĐIỂM SỰ KIỆN HÈ Ngọc Rồng Đom Đóm</h6>
                    <table class="table table-borderless text-center">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Nhân vật</th>
                                <th>Điểm</th>
                            </tr>
                        <tbody>
                            <?php
                            $query = "SELECT name, JSON_EXTRACT(point_sukienhe, '$[0]') AS second_value FROM player ORDER BY CAST(JSON_EXTRACT(point_sukienhe, '$[0]') AS UNSIGNED) DESC LIMIT 20;";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            $stt = 1;
                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '
                        <tr>
                            <td>' . $stt . '</td>
                            <td>' . $row['name'] . '</td>
                            <td>' . number_format($row['second_value'], 0, ',') . '</td>
                        </tr>';
                                    $stt++;
                                }
                            } else {
                                echo '<center><h6>Chưa có thống kê bảng xếp hạng top nạp tháng này!</h6></center>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <small>Cập nhật lúc:
                            <?php echo date('H:i d/m/Y'); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-secondary border-top"></div>
        <div class="container pt-4 pb-4 text-white">
            <div class="row">
                <div class="col">
                    <div class="text-center">
                        <div style="font-size: 13px" class="text-dark">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<div style="line-height:15px;font-size:12px;padding-bottom:10px;padding-top:6px;text-align:center">
    <img height="12" src="/public/images/12.png" style="vertical-align:middle" />
    <span style="vertical-align:middle">Dành cho người chơi trên 12 tuổi. Chơi quá 180 phút mỗi ngày sẽ hại sức
        khỏe.</span><br /><br />

    <div>
        <h5>
            2024© Được vận hành bởi <a href="Nhập link zalo của box">Ngọc Rồng</a>
        </h5>
    </div>
</div>
</body>

</html>