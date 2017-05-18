<?php

session_start();
require_once ('../../config/db.php');
$connection = new Db();
$mysqli = $connection->dbconnection();


if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
//Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
//HTTP_REFERER verification
        if ($_POST['token'] == $_SESSION['token']) {
//            echo 'success';
            $data = $_POST['data'];

            $mobileno = $data['mobileno'];
            $is_ajax = $_POST['is_ajax'];
            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];
            $mobvalidate = 0;

            if (trim($custid) != '') {

                if (strlen($mobileno) == 10) {
                    $otpCode = rand(1000, 9999);
                    $sql = "UPDATE author SET mobile = :mobile, otpcode = :otp, mobile_validated = :mobvalidate, otptime =  NOW() WHERE custid=:custid";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bindParam(':mobile', $mobileno, PDO::PARAM_STR);
                    $stmt->bindParam(':otp', $otpCode, PDO::PARAM_INT);
                    $stmt->bindParam(':custid', $custid, PDO::PARAM_STR);
                    $stmt->bindParam(':mobvalidate', $mobvalidate, PDO::PARAM_STR);
                    if ($stmt->execute()) {

$smsHtml = "Thank you for registering with INSTAPPY.COM. Your mobile verification code is $otpCode. Please enter this code to validate your number.
Thank You
Team INSTAPPY";
                        $myto = $mobileno;
                        $fromsms = 'INSTAP';
                        $feedid = '351192';
                        $username = '9958667744';
                        $passwordsms = 'wmjat';
                        $smstime = date('YmdHi', time());
                        $url = 'http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=' . $feedid . '&username=' . $username . '&password=' . $passwordsms . '&To=' . $myto . '&Text=' . rawurlencode($smsHtml) . '&time=' . $smstime . '&senderid=' . $fromsms;

                        $head = file_get_contents($url);

                        echo "1";
                    } else {
                        echo "2";
                    }
                } else {
                    echo "3";
                }
            } else {
                echo "User not login";
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed";
    }
}
?>