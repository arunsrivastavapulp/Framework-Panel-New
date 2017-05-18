<?php

require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/register', 'getRegister');
$password = "efdsdgr343tref";

function getRegister() {
    $dbCon = content_db();
    if (isset($_POST['app_id']) && isset($_POST['device_id']) && isset($_POST['Framework_Version']) && isset($_POST['platform'])) {
        $password = "password";
        $app_idString = $_POST['app_id'];
        $device_id = $_POST['device_id'];
        $Framework_Version = $_POST['Framework_Version'];
        $plateform = $_POST['platform'];
        $encrpt_token = '';
    } else {
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
    if($device_id == null && $device_id =='')
    {
      $response = array("result" => 'error', "msg" => 'device id is null');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;  
    }
    $app_type = '';
    $mergedRegstr = "1";
    $updateauthPrimary="1";
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    $encrpt_token = $token;
    $appQueryData = "select *  from app_data where app_id=:appid";
    $app_screenData = $dbCon->prepare($appQueryData);
    $app_screenData->bindParam(":appid", $app_idString, PDO::PARAM_STR);
    $app_screenData->execute();
    $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);
    if ($result_screenData != '') {
        $app_id = $result_screenData->id;
        $app_type = $result_screenData->type_app;
        $jumpto = $result_screenData->jump_to;
        $jumptoapp = $result_screenData->jump_to_app_id;
        if ($jumpto == 1 && ($jumptoapp != 0 || $jumptoapp != '')) {
            $updateauthPrimary="0";
            $app_CheckUser = "select * from user_appdata where user_device_id=:userid and app_id=:appid limit 1";
            $appCheckUser = $dbCon->prepare($app_CheckUser);
            $appCheckUser->bindParam(":userid", $device_id, PDO::PARAM_STR);
            $appCheckUser->bindParam(":appid", $jumptoapp, PDO::PARAM_INT);
            $appCheckUser->execute();
            $rowFetchApp = $appCheckUser->fetch(PDO::FETCH_ASSOC);
            $FetchApp = $appCheckUser->rowCount();
            if ($FetchApp > 0) {
                $encrpt_token = $rowFetchApp['auth_token'];
                $mergedRegstr = "1";
            } else {

                $mergedRegstr = "0";
            }
        }
    } else {
        $response = array("result" => 'error', "msg" => 'No Such App Present');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
    $app_query = "select u.id,u.device_id , u.user_name from users u where u.device_id='" . $device_id . "'";
    if ($device_id != '' && $Framework_Version != '' && $plateform != '' && $app_idString != '') {
        $lastId = '';
        $appQueryRun = $dbCon->prepare($app_query);
        $appQueryRun->bindParam(":appid", $app_idString, PDO::PARAM_STR);
        $appQueryRun->execute();
        $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
       
        $calRow = $appQueryRun->rowCount();
        if ($encrpt_token == NULL) {
            $encrpt_token = 'dfsf133rfggdggd12';
        }
        if ($calRow == 0) {
            $sqlUserInsert = "INSERT INTO users (`user_name`,`email_address`,`first_name`,`last_name`,`country`,`state`,`city`,`device_id`) VALUES (:deviceid,'','','','','','',:deviceid)";
            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
            $statementUserInsert->bindParam(":deviceid", $device_id, PDO::PARAM_STR);
            $statementUserInsert->execute();
            $lastId = $dbCon->lastInsertId();
            if ($mergedRegstr == 0 && $jumptoapp != 0) {
                $sqlUserjump = "INSERT INTO user_appdata (`user_id`,`app_id`,`platform`,`user_device_id`,`latest_version`,`advt_data`,`auth_token`) VALUES (:lastid,:appid,:platform,:deviceid,:version,'',:token)";
                $UserDetailsjump = $dbCon->prepare($sqlUserjump);
                $UserDetailsjump->bindParam(":lastid", $lastId, PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":appid", $jumptoapp, PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":platform", $plateform, PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":deviceid", $device_id, PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":version", $Framework_Version, PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":token", $encrpt_token, PDO::PARAM_STR);
                $UserDetailsjump->execute();
            }
            $sqlUserDetails = "INSERT INTO user_appdata (`user_id`,`app_id`,`platform`,`user_device_id`,`latest_version`,`advt_data`,`auth_token`) VALUES (:lastid,:appid,:platform,:deviceid,:version,'',:token)";
            $UserDetailsInsert = $dbCon->prepare($sqlUserDetails);
            $UserDetailsInsert->bindParam(":lastid", $lastId, PDO::PARAM_STR);
            $UserDetailsInsert->bindParam(":appid", $app_id, PDO::PARAM_STR);
            $UserDetailsInsert->bindParam(":platform", $plateform, PDO::PARAM_STR);
            $UserDetailsInsert->bindParam(":deviceid", $device_id, PDO::PARAM_STR);
            $UserDetailsInsert->bindParam(":version", $Framework_Version, PDO::PARAM_STR);
            $UserDetailsInsert->bindParam(":token", $encrpt_token, PDO::PARAM_STR);
            $UserDetailsInsert->execute();
        } else {
            $app_CheckUser = "select * from user_appdata where user_id=:userid and app_id=:appid";
            $appCheckUser = $dbCon->prepare($app_CheckUser);
            $appCheckUser->bindParam(":userid", $rowFetch['id'], PDO::PARAM_INT);
            $appCheckUser->bindParam(":appid", $app_id, PDO::PARAM_INT);
            $appCheckUser->execute();
            $rowFetchApp = $appCheckUser->fetch(PDO::FETCH_ASSOC);
           
            $FetchApp = $appCheckUser->rowCount();
            $device_id = $rowFetch['device_id'];
         
            if ($mergedRegstr == 0 && $jumptoapp !=0) {
               
                $sqlUserjump = "INSERT INTO user_appdata (`user_id`,`app_id`,`platform`,`user_device_id`,`latest_version`,`advt_data`,`auth_token`) VALUES (:lastid,:appid,:platform,:deviceid,:version,'',:token)";
                 $UserDetailsjump = $dbCon->prepare($sqlUserjump); 
                 
                $UserDetailsjump->bindParam(":lastid", $rowFetch['id'], PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":appid", $jumptoapp, PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":platform", $plateform, PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":deviceid", $device_id, PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":version", $Framework_Version, PDO::PARAM_STR);
                $UserDetailsjump->bindParam(":token", $encrpt_token, PDO::PARAM_STR);
             
                $UserDetailsjump->execute();
                  
            }
            if ($FetchApp == 0) {
                $sqlUserDetails = "INSERT INTO user_appdata (`user_id`,`app_id`,`platform`,`user_device_id`,`latest_version`,`advt_data`,`auth_token`) VALUES (:lastid,:appid,:platform,:deviceid,:version,'',:token)";
                $UserDetailsInsert = $dbCon->prepare($sqlUserDetails);
                $UserDetailsInsert->bindParam(":lastid", $rowFetch['id'], PDO::PARAM_STR);
                $UserDetailsInsert->bindParam(":appid", $app_id, PDO::PARAM_STR);
                $UserDetailsInsert->bindParam(":platform", $plateform, PDO::PARAM_STR);
                $UserDetailsInsert->bindParam(":deviceid", $device_id, PDO::PARAM_STR);
                $UserDetailsInsert->bindParam(":version", $Framework_Version, PDO::PARAM_STR);
                $UserDetailsInsert->bindParam(":token", $encrpt_token, PDO::PARAM_STR);
                $UserDetailsInsert->execute();
            } else {  
                   
                if($updateauthPrimary == "1")
                {
                $encrpt_token=$rowFetchApp['auth_token'];
                  $query = "update  user_appdata set auth_token=:auth where app_id=:appid and user_device_id=:device";
                $updated = $dbCon->prepare($query);
                $updated->bindParam(':auth', $encrpt_token, PDO::PARAM_INT);
                $updated->bindParam(':appid', $app_id, PDO::PARAM_INT);
                $updated->bindParam(':device', $device_id, PDO::PARAM_INT);
                $updated->execute();
                }
                else
                {
                      $encrpt_token=$rowFetchApp['auth_token'];
                }
              
            }
        }
            $appInfo = "select * from app_data ad left join app_signupdata asd on ad.id=asd.app_id where ad.id=:appid  and ad.deleted=0 limit 1";
            $appInfoM = $dbCon->prepare($appInfo);
            $appInfoM->bindParam(":appid", $app_id, PDO::PARAM_INT);
            $appInfoM->execute();
            $rowFetchApp = $appInfoM->fetch(PDO::FETCH_ASSOC);
            $FetchAppData = $appInfoM->rowCount();
            $enableLogin = '';
            $loginMethod = '';
            $logoUrl = '';
            $description = '';
            if ($FetchAppData == 0) {
                $enableLogin = '';
            } else {
                $enableLogin = $rowFetchApp['is_signup'];
                $logoUrl = $rowFetchApp['company_logo'];
                $description = $rowFetchApp['description'];
                $loginVia = explode(',', $rowFetchApp['app_signupsource_id']);
                $loginMethods = array();
                foreach ($loginVia as $value) {
                    $loginQuery = "select name from app_signupsource where id=:value";
                    $loginData = $dbCon->prepare($loginQuery);
                    $loginData->bindParam(":value", $value, PDO::PARAM_INT);
                    $loginData->execute();
                    $login = $loginData->fetch(PDO::FETCH_ASSOC);
                    $loginMethod[] = $login['name'];
                }
            }

            $response = array("result" => 'success', "msg" => '');
            $Basearray = array("response" => $response, "login_enable" => $enableLogin, "login_details" => array("login_method" => $loginMethod, "logo_url" => $logoUrl, "description" => $description), "register" => array("success" => '200', "app_type" => "" . $app_type . "", "device_id" => "" . $device_id . "", "EncryptoAuth" => "" . $encrpt_token . ""));
            $basejson = json_encode($Basearray);
            echo $basejson;
        
        } else {
            $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
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

    $app->run();
    