<?php
require_once('../core/cauhinh.php');
include('api_mbbank.php');
$MBBANK = new MBBANK;

function getUserDataFromToken($_token)
{
    global $config;
    $sql = "SELECT * FROM adminpanel WHERE token = '" . xss($_token) . "' LIMIT 1";
    $result = $config->query($sql);
    return $result->fetch_assoc();
}

function updateUserData($getData, $login)
{
    global $config, $MBBANK;
    $MBBANK->deviceIdCommon_goc = $MBBANK->generateImei();
    $MBBANK->user = $getData['userlogin'];
    $MBBANK->pass = $getData['password'];
    $text_captcha = $MBBANK->bypass_captcha_web2m('413145b2f6d981e32d0ee69a56b0e839');
    $loginResponse = json_decode($MBBANK->login($text_captcha), true);
    if ($loginResponse['result']['message'] == "Capcha code is invalid") {
        exit(json_encode(array('status' => '1', 'msg' => 'Captcha không chính xác')));
    } else if ($loginResponse['result']['message'] == 'Customer is invalid') {
        exit(json_encode(array('status' => '1', 'msg' => 'Thông tin không chính xác')));
    } else {
        $sql = "UPDATE adminpanel SET name = '" . $loginResponse['cust']['nm'] . "', password = '" . $getData['password'] . "', sessionId = '" . $loginResponse['sessionId'] . "', deviceId = '" . $MBBANK->deviceIdCommon_goc . "', time = " . time() . " WHERE userlogin = '" . $getData['userlogin'] . "'";
        $config->query($sql);
    }
}

if (isset($_token) && !empty($_token)) {
    $getData = getUserDataFromToken($_token);
    if ($getData) {
        $lichsu = json_decode($MBBANK->get_lsgd($getData['userlogin'], $getData['sessionId'], $getData['deviceId'], $getData['stk'], 2), true);
        if ($getData['time'] < time() - 60 && $lichsu['result']['message'] == 'Session invalid') {
            updateUserData($getData, $MBBANK);
        }

        $tranList = array();
        if ($lichsu['transactionHistoryList'] !== null) {
            foreach ($lichsu['transactionHistoryList'] as $transaction) {
                $noidung = $transaction['description'];
                $thoigian = $transaction['transactionDate'];
                $username = nduckien_nap('naptien ', $noidung);
                $sotien = $transaction['creditAmount'];
                $tranId = $transaction['refNo'];
                $benAccountName = $transaction['benAccountName'];
                $accountNo = $transaction['accountNo'];
                $bankName = $transaction['bankName'];

                if (empty($benAccountName)) {
                    $benAccountName = "Ngân Hàng Quân Đội - MBBank";
                }
                if (empty($bankName)) {
                    $bankName = "Ngọc Rồng Kuroko";
                }

                if ($sotien >= 5000) {
                    // Kiểm tra xem tranid đã tồn tại trong bảng atm_check chưa
                    $sql_check_tranid = "SELECT tranid FROM atm_check WHERE tranid = '$tranId'";
                    $result_check = $config->query($sql_check_tranid);

                    if ($result_check->num_rows == 0) {
                        // Tranid chưa tồn tại, thực hiện UPDATE và INSERT
                        $sql_idaccount = "SELECT id FROM user WHERE id = '$username'";
                        $result = $config->query($sql_idaccount);

                        if ($result->num_rows > 0) {
                            // Lấy id từ kết quả truy vấn
                            $row = $result->fetch_assoc();
                            $accountId = $row["id"];
                        }

                        $sql_account = "UPDATE user SET vnd = vnd + $sotien, tongnap = tongnap + $sotien WHERE id = '$username'";
                        $config->query($sql_account);

                        $sql_atm_lichsu = "INSERT INTO atm_lichsu (user_nap, magiaodich, thoigian, sotien, status, benAccountName, accountNo, bankName) VALUES ('$username', '$tranId', '$thoigian', '$sotien', 1, '$benAccountName', '$accountNo', '$bankName')";
                        $config->query($sql_atm_lichsu);

                        $sql_tranId = "INSERT INTO atm_check (tranid) VALUES ('$tranId')";
                        $config->query($sql_tranId);
                    }
                }
                $tranList[] = array(
                    "tranId" => $transaction['refNo'],
                    "postingDate" => $transaction['postingDate'],
                    "transactionDate" => $transaction['transactionDate'],
                    "accountNo" => $transaction['accountNo'],
                    "creditAmount" => $transaction['creditAmount'],
                    "debitAmount" => $transaction['debitAmount'],
                    "currency" => $transaction['currency'],
                    "description" => $transaction['description'],
                    "availableBalance" => $transaction['availableBalance'],
                    "beneficiaryAccount" => $transaction['beneficiaryAccount'],

                );
            }
        }
        print_r(
            json_encode(
                array(
                    "status" => "success",
                    "message" => "Thành công",
                    "TranList" => $tranList
                )
            )
        );
    }
}

if (isset($_token) && !empty($_token)) {
    $getData = getUserDataFromToken($_token);
    if ($getData) {
        $balance = json_decode($MBBANK->get_balance($getData['userlogin'], $getData['sessionId'], $getData['deviceId']), true);
        if ($getData['time'] < time() - 60 && $balance['result']['message'] == 'Session invalid') {
            updateUserData($getData, $MBBANK);
        }

        if ($balance['result']['message'] == 'OK') {
            foreach ($balance['acct_list'] as $data) {
                if ($data['acctNo'] == $getData['stk']) {
                    $status = true;
                    $message = 'Giao dịch thành công';
                    exit(json_encode(array('status' => '200', 'SoDu' => '' . $data['currentBalance'] . '')));
                }
            }

        } else {
            exit(json_encode(array('status' => '99', 'SoDu' => '0')));
        }

    }
}
?>