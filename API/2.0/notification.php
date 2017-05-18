<?php

require 'Slim/Slim.php';
require 'includes/db.php';
require_once('S3.php');
 
//AWS access info


\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();
$app->post('/notification', 'setnotification');
//$app->post('/updateprofiledetail', 'updateprofiledetail');
//$app->post('/uploadimage', 'uploadimage');

function authCheck($authToken, $device_id) {
    $dbCon = content_db();
    //  $auth_query = "select id from user_appdata where user_name=$device_id and auth_token='" . $authToken . "'";
    $auth_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token=:authtoken and u.device_id=:deviceid";
    $auth_queryExecution = $dbCon->prepare($auth_query);
    $auth_queryExecution->bindParam(':authtoken', $authToken, PDO::PARAM_STR);
    $auth_queryExecution->bindParam(':deviceid', $device_id, PDO::PARAM_STR);
    $auth_queryExecution->execute();
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
    return $result_auth;
}


function setnotification(){	
	  if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id']) && isset($_POST['notification']))  {
        $app_id = $_POST['app_id'];
		$dbCon = content_db();
$appQueryData = "select *  from app_data where app_id=:appid" ;
			$app_userData = $dbCon->prepare($appQueryData);
			$app_userData->bindParam(':appid', $app_id, PDO::PARAM_STR);
			$app_userData->execute();
			$result_userData = $app_userData->fetch(PDO::FETCH_OBJ);
			$app_id=$result_userData->id;
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
		} 
		else 
		{
			$notificationstatus=$_POST['notification'];
			$appQueryData = "select *  from user_appdata where app_id=:appid and user_device_id=:device_id" ;
			$app_profileData = $dbCon->prepare($appQueryData);
			$app_profileData->bindParam(':device_id', $device_id, PDO::PARAM_STR);
			$app_profileData->bindParam(':appid', $app_id, PDO::PARAM_STR);
			$app_profileData->execute();
			$result_profileData = $app_profileData->fetch(PDO::FETCH_OBJ);
			 $rowCount = $app_profileData->rowCount(PDO::FETCH_NUM);
			if($rowCount>0){
			$sqlUserInsert = "update user_appdata set notification='" . $notificationstatus . "' where user_device_id='" . $device_id . "' and app_id='" . $app_id . "'";
                            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                            $statementUserInsert->execute();
							 $responce = array();
							$response = array("result" => 'success', "msg" => 'Notification Updated', "notificationvalue" => $notificationstatus);
							$Basearray = array("response" => $response);
							$basejson = json_encode($Basearray);
							echo $basejson;
       
		}
			else
			{
				$response = array("result" => 'error', "msg" => 'No such user found');
				$Basearray = array("response" => $response);
				$basejson = json_encode($Basearray);
				echo $basejson;
			}
		}
}




		



$app->run();