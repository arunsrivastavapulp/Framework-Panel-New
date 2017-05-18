<?php

require 'Slim/Slim.php';
require 'includes/db.php';

\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();
$app->post('/paid', 'paid');

function authCheck($authToken, $device_id) {
    $dbCon = content_db();
    $auth_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token=:authtoken and u.device_id=:deviceid";
    $auth_queryExecution = $dbCon->prepare($auth_query);
    $auth_queryExecution->bindParam(':authtoken', $authToken, PDO::PARAM_STR);
    $auth_queryExecution->bindParam(':deviceid', $device_id, PDO::PARAM_STR);
    $auth_queryExecution->execute();
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
    return $result_auth;
}

function paid() {
    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id'])) {
        $dbCon = content_db();
        $app_id = $_POST['app_id'];
        $appQueryData = "select *  from app_data where app_id=:appid";
        $app_userData = $dbCon->prepare($appQueryData);
        $app_userData->bindParam(':appid', $app_id, PDO::PARAM_STR);
        $app_userData->execute();
        $result_userData = $app_userData->fetch(PDO::FETCH_OBJ);
        $rowCount = $app_userData->rowCount(PDO::FETCH_NUM);
        if ($rowCount > 0) {
          $app_id=$result_userData->id;
        } else {
            $response = array("result" => 'error', "msg" => 'No such App found');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson; 
            die;
        }

        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id'];
    } else {
        $responce = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }

    $authResult = authCheck($authToken, $device_id);

    if ($authResult == 0 || $device_id == '') {
        $response = array("result" => 'error', "msg" => 'Authentication Error');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
    } else {
        $others = 1;
        $appQueryData = "Select * from screen_title_id where others=:others and app_id=:appid and deleted=0";
        $app_Data = $dbCon->prepare($appQueryData);
        $app_Data->bindParam(':others', $others, PDO::PARAM_STR);
        $app_Data->bindParam(':appid', $app_id, PDO::PARAM_STR);
        $app_Data->execute();

        $settingData = $app_Data->fetchAll(PDO::FETCH_OBJ);
        $rowCount = $app_Data->rowCount(PDO::FETCH_NUM);
        if ($rowCount > 0) {
            foreach ($settingData as $gcatvalue) {
              //  $arraycomp_elements["screen_id"] = $gcatvalue->id;
                $componentResultSet[] = $gcatvalue->id;
            }

            $response = array("isSucess" => true, "responseCode" => "200");
            $Basearray = array("response" => $response, "screens" => $componentResultSet);
            $basejson = json_encode($Basearray);
            echo $basejson;
        } else {
            $response = array("result" => 'error', "msg" => 'No such screen found');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
        }
    }
}

$app->run();
