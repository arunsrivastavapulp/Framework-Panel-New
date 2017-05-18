<?php

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/userformdata', 'userformdata');

function userformdata() {
    $dbCon = content_db();

    if (isset($_POST['app_id']) && isset($_POST['device_id']) && isset($_POST['screen_id']) && isset($_POST['form_data'])) {
        $app_id = $_POST['app_id'];
        $device_id = $_POST['device_id'];
        $screen_id = $_POST['screen_id'];
        $form_data = $_POST['form_data'];
        $created = date("Y-m-d H:i:s");
        $decoded = json_decode($form_data);
        $send_email = 0;
        if (isset($_POST['send_email'])) {
            $send_email = $_POST['send_email'];
        }
       try {
        $appIDQuery = "select id,contactus_email from app_data where app_id=:id";

        $appIDQueryRun = $dbCon->prepare($appIDQuery);
        $appIDQueryRun->bindParam(":id", $app_id, PDO::PARAM_INT);
        $appIDQueryRun->execute();
        $appIDresult = $appIDQueryRun->fetch(PDO::FETCH_OBJ);
        $appID = $appIDresult->id;
        $to = $appIDresult->contactus_email;
        $deviceIDQuery = "select id from users where device_id=:deviceid";
        $deviceIDQueryRun = $dbCon->prepare($deviceIDQuery);
        $deviceIDQueryRun->bindParam(":deviceid", $device_id, PDO::PARAM_STR);
        $deviceIDQueryRun->execute();
        $deviceIDresult = $deviceIDQueryRun->fetch(PDO::FETCH_OBJ);
        if(!empty($deviceIDresult))
        {
        $user_id = $deviceIDresult->id;
        }
        else
        {
           $user_id=0; 
        }


        $sql = "insert into formfieldvalue (app_id, screen_id, user_id, form_data, created) values (:appid,:screenid,:userid,:formdata,:created)";
        $insert = $dbCon->prepare($sql);
        $insert->bindParam(":appid", $appID, PDO::PARAM_INT);
        $insert->bindParam(":screenid", $screen_id, PDO::PARAM_INT);
        $insert->bindParam(":userid", $user_id, PDO::PARAM_INT);
        $insert->bindParam(":formdata", $form_data, PDO::PARAM_STR);
        $insert->bindParam(":created", $created, PDO::PARAM_STR);
        $insert->execute();
        //$to = "ravi.tiwari@pulpstrategy.com";
        if ($send_email == 1 && $to != '') {

            $subject = "Instappy Form";
            $htmlcontent = "<html>
				<body>
				<p>Hi,</p>";
            foreach ($decoded->form_data as $key => $val) {
                $htmlcontent .= "<p>" . ucwords($key) . " :: " . ucwords($val) . "</p>";
            }
            $htmlcontent .= "<p>Best Regards,<br />
				Team Instappy</p>
				</body>
				</html>
				";
            $body = $htmlcontent;

            $formemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'api_key' => $key,
                    'subject' => $subject,
                    'fromname' => 'Instappy',
                    'from' => $formemail,
                    'content' => $body,
                    'recipients' => $to
                )
            ));
            $head = curl_exec($curl);


            curl_close($curl);
            if ($head == 'success') {
                $mailCountQuery = "SELECT id FROM form_email_count where app_id=:id and screen_id=:screenid";
                $mailCountRun = $dbCon->prepare($mailCountQuery);
                $mailCountRun->bindParam(":id", $appID, PDO::PARAM_INT);
                $mailCountRun->bindParam(":screenid", $screen_id, PDO::PARAM_INT);
                $mailCountRun->execute();
                $calRow = $mailCountRun->rowCount();
                if ($calRow != 1) {
                    $insert = "INSERT INTO form_email_count (`app_id`,`screen_id`,`email_count`) VALUES (:appid,:screenid,'1')";
                    $statementInsert = $dbCon->prepare($insert);
                    $statementInsert->bindParam(":appid", $appID, PDO::PARAM_INT);
                    $statementInsert->bindParam(":screenid", $screen_id, PDO::PARAM_INT);
                    $statementInsert->execute();
                } else {
                    $getEmailCountQuery = "SELECT email_count FROM form_email_count where app_id=:id and screen_id=:screenid";
                    $getEmailRun = $dbCon->prepare($getEmailCountQuery);
                    $getEmailRun->bindParam(":id", $appID, PDO::PARAM_INT);
                    $getEmailRun->bindParam(":screenid", $screen_id, PDO::PARAM_INT);
                    $getEmailRun->execute();
                    $emailresult = $getEmailRun->fetch(PDO::FETCH_OBJ);
                    $email_count = $emailresult->email_count;
                    $addOne = $email_count + 1;
                    $sqlUserDetails = "UPDATE form_email_count set email_count=:countadd where app_id=:appid and screen_id=:screenid ";
                    $statementUpdate = $dbCon->prepare($sqlUserDetails);
                    $statementUpdate->bindParam(":countadd", $addOne, PDO::PARAM_INT);
                    $statementUpdate->bindParam(":appid", $appID, PDO::PARAM_INT);
                    $statementUpdate->bindParam(":screenid", $screen_id, PDO::PARAM_INT);
                    $statementUpdate->execute();
                }
            }
        }

        $response = array("result" => 'success');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        } catch (Exception $e) {
            $response = array("result" => 'error', "msg" => 'something went wrong');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
        }
    } else {
        $response = array("result" => 'error', "msg" => 'parameter missing');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
    }
}

$app->run();
