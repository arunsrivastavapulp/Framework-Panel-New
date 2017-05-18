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
            $verifyotpno = $data['verifyotpno'];
            $resend = $_POST['resend'];
            $is_ajax = $_POST['is_ajax'];
            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];
            $mobvalidate = 0;
            if (trim($custid) != '') {
                if ($resend == 1) {

                    $sql2 = "select mobile from author where custid=:custid";
                    $stmt2 = $mysqli->prepare($sql2);
                    $stmt2->bindParam(':custid', $custid, PDO::PARAM_STR);
                    $stmt2->execute();
                    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                    $mobileno = $result2['mobile'];
                    if ($mobileno != '') {
                        
                        $otpCode = rand(1000, 9999);
                        $sql = "UPDATE author SET otpcode = :otp, mobile_validated = :mobvalidate, otptime =  NOW() WHERE custid =:custid";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bindParam(':otp', $otpCode, PDO::PARAM_STR);
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
                        echo "6";
                    }
                } else if ($resend == 0) {

                    $sql3 = "select id,otptime from author where custid=:custid and otpcode = :otp";
                    $stmt3 = $mysqli->prepare($sql3);
                    $stmt3->bindParam(':custid', $custid, PDO::PARAM_STR);
                    $stmt3->bindParam(':otp', $verifyotpno, PDO::PARAM_STR);
                    $stmt3->execute();
                    $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);

                    $userId3 = $result3['id'];

                    if ($userId3 != '') {
                        $otptime = $result3['otptime'];
                        $currentservertime = date('Y-m-d H:i:s');
                        $to_time = strtotime($otptime);
                        $from_time = strtotime($currentservertime);
                        $minutes = round(abs($from_time - $to_time) / 60, 2);
                        
                        if ($minutes < 5) {
                            $mobvalidate = 1;
                            $sql = "UPDATE author SET mobile_validated = :mobvalidate where custid=:custid and otpcode = :otp";
                            $stmt = $mysqli->prepare($sql);
                            $stmt->bindParam(':custid', $custid, PDO::PARAM_STR);
                            $stmt->bindParam(':otp', $verifyotpno, PDO::PARAM_STR);
                            $stmt->bindParam(':mobvalidate', $mobvalidate, PDO::PARAM_STR);
                            if ($stmt->execute()) {
                                echo "3";
                            } else {
                                echo "5";
                            }
                        } else{
                        $otpCode2 = rand(1000, 9999);
                        $sql = "UPDATE author SET otpcode = :otp, mobile_validated = :mobvalidate WHERE custid =:custid";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bindParam(':otp', $otpCode2, PDO::PARAM_STR);
                        $stmt->bindParam(':custid', $custid, PDO::PARAM_STR);
                        $stmt->bindParam(':mobvalidate', $mobvalidate, PDO::PARAM_STR);
                        $stmt->execute();
                            echo "7";
                        }
                    } else {
                        echo "4";
                    }
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