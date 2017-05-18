<?php

require 'Slim/Slim.php';
require 'includes/db.php';
//AWS access info


\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();$app->post('/settingsdetail', 'settingsdetail');

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


function settingsdetail(){	
	  if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id']) ) {
 $dbCon = content_db();       
	   $app_id = $_POST['app_id'];
	   $appQueryData = "select *  from app_data where app_id=:appid" ;
			$app_userData = $dbCon->prepare($appQueryData);
			$app_userData->bindParam(':appid', $app_id, PDO::PARAM_STR);
			$app_userData->execute();
			$result_userData = $app_userData->fetch(PDO::FETCH_OBJ);
			$rowCount = $app_userData->rowCount(PDO::FETCH_NUM);
			if($rowCount>0){
			$app_id='5500';
			}
			else
			{
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
		} 
		else 
		{
			$appQueryData = "Select * from app_catalogue_attr where app_id=:appid " ;
			$app_settingData = $dbCon->prepare($appQueryData);
			$app_settingData->bindParam(':appid', $app_id, PDO::PARAM_STR);
			$app_settingData->execute(); 
			
			$settingData = $app_settingData->fetch(PDO::FETCH_OBJ);
			$rowCount = $app_settingData->rowCount(PDO::FETCH_NUM);
			if($rowCount>0){
				
			if($settingData->is_feedback=='1'){	
			$arraycomp_elements["itemName"] ="Feedback";
			$arraycomp_elements["type"] =0;
			$arraycomp_elements["icomoonId"] =90;
			$arraycomp_elements["isdata"] ="false";
			
			$arraycomp_elements["data"]=[];	
			$componentResultSet[] = $arraycomp_elements;
			}
			
			
			
			if($settingData->is_tnc=='1'){	
			$arraycomp_elements["itemName"] ="Terms & Conditions";
			$arraycomp_elements["type"] =0;
			$arraycomp_elements["icomoonId"] ="092";
			$arraycomp_elements["isdata"] ="true";
		
			$arraycomp_elements["data"] = array("url" => $settingData->tnc_link);	
			$componentResultSet[] = $arraycomp_elements;
			}
	
				
			$setting = array('count' => $rowCount,'items' => $componentResultSet);
		
			$response = array("result" => 'success', "msg" => 'Yes');
				$Basearray = array("response" => $response,"Settings"=>$setting);
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
