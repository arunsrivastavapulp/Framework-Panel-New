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
require 'db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/appdetails', 'appdetails');


function appdetails() {
    $dbCon = getConnection();
    $app_id = $_POST['app_id'];
	try
	{
     
    $app_query = "select ad.app_id,ad.summary as app_name,ad.app_image,sc.image_link as splash_url,ad.fbsdk_id as facebook_id,ad.twittersdk_id as twitter_id from app_data ad left join splash_screen sc on ad.splash_screen_id=sc.id where ad.id='" . $app_id . "'";    
    $appQueryRun = $dbCon->query($app_query);
    $rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
	
	$response = array("result" => 'success', "details" => $rowFetch);
			$Basearray = array("response" => $response);		
			$basejson = json_encode($Basearray);
			echo $basejson; 
	}
	catch (Exception $e) {
$response = array("result" => 'error', "msg" => 'something went wrong');
			$Basearray = array("response" => $response);		
			$basejson = json_encode($Basearray);
			echo $basejson; 
    }

	
	
}




$app->run();
