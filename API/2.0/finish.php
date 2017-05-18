<?php

require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/index', 'index');

function index() {
    $dbCon = content_db();
    $baseUrl = baseUrlContent();

    if (isset($_POST['app_id'])) {
        $app_id = $_POST['app_id'];
        $query = "select au.mobile_country_code,au.sms_sent,au.mobile,ad.summary,au.mobile_validated,ad.type_app from author au join app_data ad on ad.author_id = au.id where ad.id=:appid";
        $queryData = $dbCon->prepare($query);
        $queryData->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $queryData->execute();
        $rowFetch = $queryData->fetch(PDO::FETCH_ASSOC);
        $calRow = $queryData->rowCount();

        if ($calRow != 0) {
            if (($rowFetch['mobile_validated'] == 1) && (($rowFetch['sms_sent'] == 0) || ($rowFetch['sms_sent'] == 1))) {
                if ($rowFetch['sms_sent'] == 0) {
                    $setValue = '3';
                }
                if ($rowFetch['sms_sent'] == 1) {
                    $setValue = '2';
                }
                if ($rowFetch['type_app'] == 1) {
                    $url = "http://bit.ly/25j3fTT";
                } else {
                    $url = "http://bit.ly/1sAqD0G";
                }

              //  $smsHtml = "Keep a check on your app " . $rowFetch['summary'] . "! Start testing real time on the wizard. Download wizard from " . $url . ". All the best!";
                $smsHtml = "Keep track of your app " . $rowFetch['summary'] . "! Start testing it real time on any device with our wizard. Download the wizard from " . $url . ". All the best!";
                $countryCode = $rowFetch['mobile_country_code'];
                $number = $rowFetch['mobile'];
                $MobileNumer = $countryCode . $number;
                $myto = $MobileNumer;
                $fromsms = 'INSTAP';
                $feedid = '354277';
                $username = '9958667744';
                $passwordsms = 'wmjat';
                $smstime = date('YmdHi', time());
                $url = 'http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=' . $feedid . '&username=' . $username . '&password=' . $passwordsms . '&To=' . $myto . '&Text=' . rawurlencode($smsHtml) . '&time=' . $smstime . '&senderid=' . $fromsms;

                $head = file_get_contents($url);
                $response = array("result" => 'success');
                $Basearray = array("response" => $response);
                $basejson = json_encode($Basearray);
                echo $basejson;

                $updateSmsData = "update author au join app_data ad on ad.author_id = au.id set au.sms_sent=:sms where ad.id=:appid";
                $update = $dbCon->prepare($updateSmsData);
                $update->bindParam(':sms', $setValue, PDO::PARAM_INT);
                $update->bindParam(':appid', $app_id, PDO::PARAM_INT);
                $update->execute();
                $update->execute();

            } else {
                
            }
        } else {
            $response = array("result" => 'error', "msg" => 'Invalid App ID');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
            die;
        }
    } else {
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
}

$app->run();
