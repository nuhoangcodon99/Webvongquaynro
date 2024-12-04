<?php
require_once 'connect.php';

function _query($sql)
{
    global $conn;
    return $conn->query($sql);
}

function _fetch($sql)
{
    return _query($sql)->fetch(PDO::FETCH_ASSOC);
}

function isset_sql($txt)
{
    global $conn;
    return $conn->quote($txt);
}

function _insert($table, $input, $output)
{
    return "INSERT INTO $table($input) VALUES($output)";
}

function _select($select, $from, $where)
{
    return "SELECT $select FROM $from WHERE $where";
}

function _update($tabname, $input_output, $where)
{
    return "UPDATE $tabname SET $input_output WHERE $where";
}

function _delete($table, $condition)
{
    global $conn;
    _query("DELETE FROM $table WHERE $condition");
}

function show_alert($alert)
{
    echo '<div class="' . $alert[0] . '">' . $alert[1] . '</div>';
}

function _num_rows($result)
{
    return $result->rowCount();
}

function has_mkc2($username)
{
    global $conn; // Use the global $conn variable from connect.php

    try {
        // Thực hiện truy vấn để lấy giá trị của cột "mkc2" từ bảng "account"
        $sql = "SELECT mkc2 FROM account WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Kiểm tra và trả về kết quả là true/nếu giá trị khác rỗng và false/ngược lại
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['mkc2'] != '') {
                    return true;
                }
            }
        }

        // Trả về giá trị mặc định là false
        return false;
    } catch (PDOException $e) {
        // Xử lý lỗi khi có exception xảy ra
        echo "Lỗi truy vấn: " . $e->getMessage();
        exit;
    }
}
?>