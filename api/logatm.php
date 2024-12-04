<?php
session_start();
require_once('../core/cauhinh.php');
include('api_mbbank.php');
$mbbank = new MBBANK;
$userloginmbbank = $userloginmbbank_config;
$passmbbank = $passmbbank_config;
$stkmbbank = $stkmbbank_config;
if (empty($userloginmbbank)) {
    exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng điền tài khoản đăng nhập')));
}

if (empty($passmbbank)) {
    exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng điền mật khẩu')));
}

if (empty($stkmbbank)) {
    exit(json_encode(array('status' => '1', 'msg' => 'Vui lòng điền số tài khoản')));
}
$mbbank->user = $userloginmbbank;
$mbbank->pass = $passmbbank;
$time = time();
$text_captcha = $mbbank->bypass_captcha_web2m('413145b2f6d981e32d0ee69a56b0e839');
$login = json_decode($mbbank->login($text_captcha), true);

if ($login['result']['message'] == "Capcha code is invalid") {
    exit(json_encode(array('status' => '1', 'msg' => 'Captcha không chính xác')));
} else if ($login['result']['message'] == 'Customer is invalid') {
    exit(json_encode(array('status' => '1', 'msg' => 'Thông tin không chính xác')));
} else {
    // Check if the userlogin number already exists in the adminpanel table
    $checkQuery = "SELECT userlogin FROM adminpanel WHERE userlogin = '$userloginmbbank'";
    $checkResult = mysqli_query($config, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // userlogin number already exists, don't insert
        exit(json_encode(array('status' => '1', 'msg' => 'Số điện thoại đã tồn tại trong hệ thống')));
    } else {
        $create = "INSERT INTO adminpanel (userlogin, stk, name, password, sessionId, deviceId, token, time)
                   VALUES ('$userloginmbbank', '$stkmbbank', '{$login['cust']['nm']}', '$passmbbank', '{$login['sessionId']}', '$mbbank->deviceIdCommon_goc', '" . CreateToken() . "', " . time() . ")";
        if (mysqli_query($config, $create)) {
            // Insert successful
            // You can display a success message if needed
            exit(json_encode(array('status' => '2', 'msg' => 'Thêm tài khoản thành công')));
        } else {
            // Insert failed
            // You can display an error message if needed
            exit(json_encode(array('status' => '1', 'msg' => 'Lỗi khi thêm tài khoản')));
        }
    }
}
?>
