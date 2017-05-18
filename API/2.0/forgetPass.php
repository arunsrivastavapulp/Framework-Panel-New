<?php

require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/forgetpassword', 'forgetpassword');

function forgetpassword() {
    $dbCon = content_db();
    $baseUrl = baseUrlWeb();

    if (isset($_POST['email_address']) && isset($_POST['app_id'])) {



        $app_idString = $_POST['app_id'];
        $appQueryData = "select *  from app_data where app_id=:appid";
        $app_screenData = $dbCon->prepare($appQueryData);
        $app_screenData->bindParam(':appid', $app_idString, PDO::PARAM_INT);
        $updated->execute();
        $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);

        if ($result_screenData != '') {
            $app_id = $result_screenData->id;
            $email = $result_screenData->contactus_email;
            $phoneNumber = $result_screenData->contactus_phone;
        } else {
            $app_id = 0;
        }

        $email = $_POST['email_address'];

        $app_query = "select email_address,password,user_id from user_appdata where email_address=:email and app_id=:appid";
        $appQueryRun = $dbCon->prepare($app_query);
        $appQueryRun->bindParam(':email', $email, PDO::PARAM_STR);
        $appQueryRun->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $appQueryRun->execute();
        $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
        $calRow = $appQueryRun->rowCount();




        if ($calRow != 0) {
            $cto = $email;
            if ($cto != '' || $cto != null) {
                $digits = 4;
                $auto_otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
                $sqlUserDetails = "UPDATE user_appdata set verification_code=:code where email_address=:email and app_id=:appid";
                $UserDetailsInsert = $dbCon->prepare($sqlUserDetails);
                $UserDetailsInsert->bindParam(':code', $auto_otp, PDO::PARAM_STR);
                $UserDetailsInsert->bindParam(':email', $email, PDO::PARAM_STR);
                $UserDetailsInsert->bindParam(':appid', $app_id, PDO::PARAM_INT);
                $UserDetailsInsert->execute();



                $name_query = "select first_name from users where id=:id";
                $name_queryRun = $dbCon->prepare($name_query);
                $name_queryRun->bindParam(':id', $rowFetch['user_id'], PDO::PARAM_INT);
                 $name_queryRun->execute();
                $name_queryFetch = $name_queryRun->fetch(PDO::FETCH_ASSOC);
                $username = $name_queryFetch['first_name'];
                $cto = $email;
                if ($cto != '' || $cto != null) {
                    $subject = 'Instappy - Reset Password';
                    $formemail = 'noreply@instappy.com';
                    $key = 'f894535ddf80bb745fc15e47e42a595e';

                    $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Instappy - Reset Password</title>
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
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:20px;font-family:Arial, Helvetica, sans-serif;">Dear ' . $username . ',</p><br/>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">
                        Oops! It seems like you have forgotten your password. But dont worry,
                      </p>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">just use this code to reset your password.</p>
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
</html>';

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

                    $response = array("result" => "success", "msg" => '316');
                    $Basearray = array("response" => $response);
                    $basejson = json_encode($Basearray);
                    echo $basejson;
                }
            }
        } else {
            $response = array("result" => 'error', "msg" => '317');
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

$app->run();
