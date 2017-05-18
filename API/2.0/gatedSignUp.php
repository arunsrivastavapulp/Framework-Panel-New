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

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();
$app->post('/signup', 'signup');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */
function signup() {
    $dbCon = content_db();
    $baseUrl = baseUrlWeb();
    if (isset($_POST['app_id']) && isset($_POST['device_id']) && isset($_POST['name']) && isset($_POST['device_os'])) {


        //$password = md5($_POST['password']);
        $app_idString = $_POST['app_id'];
        $device_id = $_POST['device_id'];
        $name = htmlentities($_POST['name']);
        $plateform = $_POST['device_os'];

        //$email = $_POST['email_address'];
        //$api_version = $_POST['api_version'];
        //$app_call = $_POST['app_call'];

        if (isset($_POST['app_call'])) {
            $app_call = $_POST['app_call'];
        } else {
            $app_call = '';
        }

        if (isset($_POST['api_version'])) {
            $api_version = $_POST['api_version'];
        } else {
            $api_version = '';
        }

        if (isset($_POST['email_address'])) {
            $email = $_POST['email_address'];
        } else {
            $email = '';
        }

        if (isset($_POST['password'])) {
            $password = md5($_POST['password']);
        } else {
            $password = '';
        }


        if (isset($_POST['fb_token'])) {
            $fb_token = $_POST['fb_token'];
        } else {
            $fb_token = '';
        }
    } else {
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
    $appQueryData = "select *  from app_data where app_id=:appid";
    $app_screenData = $dbCon->prepare($appQueryData);
    $app_screenData->bindParam(':appid', $app_idString, PDO::PARAM_STR);
    $app_screenData->execute();
    $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);

    if ($result_screenData != '') {
        $app_id = $result_screenData->id;
        $summary = $result_screenData->summary;
    }


    try {

        $app_query = "select u.id,u.device_id , u.user_name from users u where u.device_id=:deviceid";
        $appQueryRun = $dbCon->prepare($app_query);
        $appQueryRun->bindParam(':deviceid', $device_id, PDO::PARAM_STR);
        $appQueryRun->execute();
        $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
        $calRow = $appQueryRun->rowCount();

        $userId = '';

        if ($calRow == 0) {
            $sqlUserInsert = "INSERT INTO users (`user_name`,`email_address`,`first_name`,`last_name`,`country`,`state`,`city`,`device_id`) VALUES ('','','" . $name . "','','','','','" . $device_id . "')";
            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
            $statementUserInsert->execute();
            $userId = $dbCon->lastInsertId();
        } else {
            $userId = $rowFetch['id'];
        }

        $appQueryData = "select *  from user_appdata where app_id='" . $app_id . "' and email_address = '" . $email . "'";
        $app_screenData = $dbCon->query($appQueryData);
        $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);
        $FetchCount = $app_screenData->rowCount();

        if ($FetchCount == 0) {
            $digits = 4;
            $auto_otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $sqlUserDetails = "INSERT INTO user_appdata (`user_id`,`app_id`,`platform`,`api_version`,`email_address`,`password`,`phone`,`user_device_id`,`fb_token`,`verification_code`) VALUES ('" . $rowFetch['id'] . "','" . $app_id . "','" . $plateform . "','" . $api_version . "','" . $email . "','" . $password . "','" . $app_call . "','" . $device_id . "','" . $fb_token . "','" . $auto_otp . "')";

            $UserDetailsInsert = $dbCon->prepare($sqlUserDetails);
            $UserDetailsInsert->execute();

            $cto = $email;

            if ($cto != '' || $cto != null) {

                $subject = 'Instappy App Registration';
                $formemail = 'noreply@instappy.com';
                $key = 'f894535ddf80bb745fc15e47e42a595e';

                $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Instappy App Registration</title>
</head>

<body>
<table cellpadding="0" cellspacing="0" width="650" align="center" style="border:1px solid #7c7c7c;">
    <tr>
        <td>
            <img src="' . $baseUrl . '/edm/images/Welcome-to-Instappy-header.png" />
        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="570" align="center" >
                <tr>
                    <td>
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:14px; line-height:40px;font-family:Arial, Helvetica, sans-serif;">&nbsp;</p>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:20px;font-family:Arial, Helvetica, sans-serif;">Dear ' . $_POST['name'] . ',</p><br/>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">
                        Thank you for registering with ' . $summary . '.
                      </p>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">Please use this code to activate your account.</p>
                       <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:14px; line-height:px;font-family:Arial, Helvetica, sans-serif;">&nbsp;</p>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif; font-weight:bold;">Code: ' . $auto_otp . '</p>
               <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">Reach us at support@instappy.com</p>
                     
                    </td>
                </tr>
                 <tr>
                    <td colspan="2">
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:14px; line-height:20px;font-family:Arial, Helvetica, sans-serif;">&nbsp;</p>
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">See you!</p>
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">Instappy Team</p>
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:14px; line-height:20px;font-family:Arial, Helvetica, sans-serif;">&nbsp;</p> 
                    </td>
                </tr>
                
            </table>
            
        </td>
    </tr>
     <tr>
      <td style="color:#bcbcbc;margin:0;padding:15px; font-size:10px;font-family:Arial, Helvetica, sans-serif;text-align:justify">
        Disclaimer: You have received this email as you are a valued customer subscribed to Instappy.com or have registered for an account on Instappy.com. The information provided herein regarding products, services/offers, benefits etc of Instappy are governed by the detailed terms and conditions accepted by you. The information provided herein above does not amount to a discount, offer or sponsor or advice as regards any products or services of Instappy or any of its group companies unless explicitly specified and is not intended to create any rights or obligations. Any reference to service levels in this document are indicative and should not be construed to refer to any commitment by Instappy and are subject to change at any time at the sole discretion of Pulp Strategy Technologies or its group companies as it case maybe. The use of any information set out herein is entirely at the recipients own risk and discretion. The "Instappy" logo is a trade mark and property of Pulp Strategy Technologies Pvt Ltd. Misuse of any intellectual property or any other content displayed herein is strictly prohibited. Images used herein are for the purposes of illustration only.
     </td>
    </tr>
    <tr>
        <td  bgcolor="ffcc00" height="30">
            <img src="' . $baseUrl . '/edm/images/Anyone-footer.png">
        </td>
    </tr>
</table>
</body>
</html>
';





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
                        'content' => $content,
                        'recipients' => $cto
                    )
                ));
                $customerhead = curl_exec($curl);

                curl_close($curl);
                /*
                  $encrrptedEmail = base64_encode($email);
                  $encrrptedPassword = base64_encode($_POST['password']);
                  $encrrptedAppId = base64_encode($app_id);

                  $cformemail = 'noreply@instappy.com';
                  $key = 'f894535ddf80bb745fc15e47e42a595e';
                  $curl = curl_init();
                  curl_setopt_array($curl, array(
                  CURLOPT_RETURNTRANSFER => 1,
                  CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                  CURLOPT_POST => 1,
                  CURLOPT_POSTFIELDS => array(
                  'api_key' => $key,
                  'subject' => 'Instappy - SignUp Verification',
                  'fromname' => 'Instappy',
                  'from' => $cformemail,
                  'content' => "<a href=".$baseUrl."verifysignup.php?token=".$encrrptedEmail."&key=".$encrrptedPassword."&secret=".$encrrptedAppId.">Click here to verify your account</a>",
                  'recipients' => $cto
                  )
                  ));
                  $customerhead = curl_exec($curl);
                  curl_close($curl);
                 */
            }






            $response = array("result" => "success", "msg" => '200');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
        } else {
            $response = array("result" => "error", "msg" => '250');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
        }
    } catch (Exception $e) {
        $response = array("result" => 'error', "msg" => 'something went wrong');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
    }
}

// POST route
$app->post(
        '/post', function () {
    echo 'This is a POST route';
}
);

// PUT route
$app->put(
        '/put', function () {
    echo 'This is a PUT route';
}
);

// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

// DELETE route
$app->delete(
        '/delete', function () {
    echo 'This is a DELETE route';
}
);

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
