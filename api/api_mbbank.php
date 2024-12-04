<?php
class MBBANK
{
    public $user = '';
    public $pass = '';
    public $deviceIdCommon_goc = '';
    public function bypass_captcha_web2m($key_captcha)
    {
        $get_captcha = $this->get_captcha();
        $img_base64 = json_decode($get_captcha, true)['imageString'];

        $curl = curl_init();
        $dataPost = array(
         "api_key" => $key_captcha,
         "img_base64" =>$img_base64,
        );
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://ecaptcha.sieuthicode.net/api/captcha/mbbank',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$dataPost,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true)['data']['captcha'];
    }
    public function login($captcha)
    {
        $header = array(
            'Accept: application/json, text/plain, */*',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: en-US,en;q=0.9,vi;q=0.8',
            'Authorization: Basic QURNSU46QURNSU4=',
            'Connection: keep-alive',
            'Content-Type: application/json; charset=UTF-8',
            'elastic-apm-traceparent: 00-17620ad87b0b1e04da1d1cf8e8d8c287-bfd8deead47f0f3c-01',
            'Host: online.mbbank.com.vn',
            'Origin: https://online.mbbank.com.vn',
            'Referer: https://online.mbbank.com.vn/pl/login?logout=1',
            'sec-ch-ua: "Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'Sec-Fetch-Dest: empty',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Site: same-origin',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
            'X-Request-Id: 7ed665bc35bb47f19b263447bd1cc180-2022090511445886',
        );
        $Action = 'https://online.mbbank.com.vn/retail_web/internetbanking/doLogin';
        $Data = '{
            "password" : "' . $this->pass . '",
            "refNo" : "' . md5($this->user) . '-2022090511533744",
            "sessionId" : null,
            "userId" : "' . $this->user . '",
            "captcha" : "' . $captcha . '",
            "deviceIdCommon" : "' . $this->deviceIdCommon_goc . '"
          }';
        $result = $this->CURL2($Action, $header, $Data);
        return $result;
    }
    public function get_captcha()
    {
        $header = array(
            'Accept: application/json, text/plain, */*',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: en-US,en;q=0.9,vi;q=0.8',
            'Authorization: Basic QURNSU46QURNSU4=',
            'Connection: keep-alive',
            'Content-Type: application/json; charset=UTF-8',
            'elastic-apm-traceparent: 00-17620ad87b0b1e04da1d1cf8e8d8c287-bfd8deead47f0f3c-01',
            'Host: online.mbbank.com.vn',
            'Origin: https://online.mbbank.com.vn',
            'Referer: https://online.mbbank.com.vn/pl/login?logout=1',
            'sec-ch-ua: "Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'Sec-Fetch-Dest: empty',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Site: same-origin',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
            'X-Request-Id: 7ed665bc35bb47f19b263447bd1cc180-2022090511445886',
        );
        $Action = 'https://online.mbbank.com.vn/retail-web-internetbankingms/getCaptchaImage';
        $Data = '{
            "refNo" : "2022090511525326",
            "deviceIdCommon" : "' . $this->deviceIdCommon_goc . '",
            "sessionId" : ""
          }';
        $result = $this->CURL2($Action, $header, $Data);
        return $result;
    }
    public function get_lsgd($user, $session_id,$deviceId,$account, $day)
    {
        $header = array(
            'Accept: application/json, text/plain, */*',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: en-US,en;q=0.9,vi;q=0.8',
            'Authorization: Basic QURNSU46QURNSU4=',
            'Connection: keep-alive',
            'Content-Type: application/json; charset=UTF-8',
            'elastic-apm-traceparent: 00-17620ad87b0b1e04da1d1cf8e8d8c287-bfd8deead47f0f3c-01',
            'Host: online.mbbank.com.vn',
            'Origin: https://online.mbbank.com.vn',
            'Referer: https://online.mbbank.com.vn/information-account/source-account',
            'sec-ch-ua: "Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'Sec-Fetch-Dest: empty',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Site: same-origin',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
            'X-Request-Id: 7ed665bc35bb47f19b263447bd1cc180-2022090511445886',
        );
        $Action = 'https://online.mbbank.com.vn/retail_web/common/getTransactionHistory';
        $Data = '{
            "toDate" : "' . date("d/m/Y") . '",
            "accountNo" : "' . $account . '",
            "historyNumber" : "",
            "historyType" : "DATE_RANGE",
            "sessionId" : "' . $session_id . '",
            "fromDate" : "' . date("d/m/Y",strtotime("$day days ago")) . '",
            "refNo" : "' . $user . '-2022090511534975",
            "type" : "ACCOUNT",
            "deviceIdCommon" : "' . $deviceId . '"
          }';
        $result = $this->CURL2($Action, $header, $Data);
        return $result;
    }
     public function get_balance($account, $session_id,$deviceId)
    {
        $header = array(
            'Accept: application/json, text/plain, */*',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: en-US,en;q=0.9,vi;q=0.8',
            'Authorization: Basic QURNSU46QURNSU4=',
            'Connection: keep-alive',
            'Content-Type: application/json; charset=UTF-8',
            'elastic-apm-traceparent: 00-17620ad87b0b1e04da1d1cf8e8d8c287-bfd8deead47f0f3c-01',
            'Host: online.mbbank.com.vn',
            'Origin: https://online.mbbank.com.vn',
            'Referer: https://online.mbbank.com.vn/information-account/source-account',
            'sec-ch-ua: "Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'Sec-Fetch-Dest: empty',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Site: same-origin',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
            'X-Request-Id: 7ed665bc35bb47f19b263447bd1cc180-2022090511445886',
        );
        $Action = 'https://online.mbbank.com.vn/retail-web-accountms/getBalance';
        $Data = '{
            "refNo" : "'.$account.'-2022090511534518",
            "sessionId" : "'.$session_id.'",
            "deviceIdCommon" : "'.$deviceId.'"
          }';
        $result = $this->CURL2($Action, $header, $Data);
        return $result;
    }
    public function CURL2($Action, $header, $data)
    {
        $curl = curl_init();
        $opt = array(
            CURLOPT_URL => $Action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => empty($data) ? false : true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_CUSTOMREQUEST => empty($data) ? 'GET' : 'POST',
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_ENCODING => "",
            CURLOPT_HEADER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2,
            CURLOPT_TIMEOUT => 5,
        );
        curl_setopt_array($curl, $opt);
        $body = curl_exec($curl);

        return $body;
    }

    private function CURL($Action, $header, $data)
    {
        $Data = is_array($data) ? json_encode($data) : $data;
        $curl = curl_init();
        $header[] = 'Content-Type: application/json; charset=utf-8';
        $header[] = 'accept: application/json';
        $header[] = 'Content-Length: ' . strlen($Data);
        $opt = array(
            CURLOPT_URL => $this->URL[$Action],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => empty($data) ? false : true,
            CURLOPT_POSTFIELDS => $Data,
            CURLOPT_CUSTOMREQUEST => empty($data) ? 'GET' : 'POST',
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_ENCODING => "",
            CURLOPT_COOKIEJAR => "mb.txt",
            CURLOPT_COOKIEFILE => "mb.txt",
            CURLOPT_HEADER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT => 5,
        );
        curl_setopt_array($curl, $opt);
        $body = curl_exec($curl);
        if (is_object(json_decode($body))) {
            return json_decode($body, true);
        }
        return json_decode($body, true);
    }
     public function generateImei()
     {
         return $this->generateRandomString(8) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->get_time_request();
     }

     public function generateRandomString($length = 20)
     {
         $characters = '0123456789abcdef';
         $charactersLength = strlen($characters);
         $randomString = '';
         for ($i = 0; $i < $length; $i++) {
             $randomString .= $characters[rand(0, $charactersLength - 1)];
         }
         return $randomString;
     }
    public function get_TOKEN()
    {
        return $this->generateRandomString(39);
    }
    public function get_time_request()
    {
        $d=getdate();
        $today = $d['hours'].$d['minutes'].$d['seconds'];
        $day = date('Y').date('m').date('d');
        return $day.$today;
    }
}

// Chưa đăng nhập, chuyển hướng đến trang khác bằng JavaScript
echo '<script>window.location.href = "../index";</script>';
exit(); // Đảm bảo dừng thực thi code sau khi chuyển hướng
