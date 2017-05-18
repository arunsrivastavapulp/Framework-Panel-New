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
$app->post('/appdetails', 'appdetails');


function appdetails() {
    $dbCon = content_db();
    $app_id = $_POST['app_id'];
	$typeapp=1;
		
	$newResult=array();
	try
	{

		 $app_query1 = "select is_default_splash from app_data where id=:appid";    
	
            $appQueryRun1 = $dbCon->prepare($app_query1);
$appQueryRun1->bindParam(':appid',$app_id,PDO::PARAM_INT);
            $appQueryRun1->execute();
	    $rowFetch1 = $appQueryRun1->fetch(PDO::FETCH_OBJ);

	     if($rowFetch1->is_default_splash == '0'){
	    $app_query = "select ad.app_id,ad.type_app,ad.summary as app_name,ad.background_color,ad.app_image,ad.fbsdk_id,ad.twittersdk_id,sc.image_link as splash_url from app_data ad left join default_splash_screen sc on ad.splash_screen_id=sc.id where ad.id=:appid";    
	    }else{
	    	    $app_query = "select ad.app_id,ad.type_app,ad.summary as app_name,ad.background_color,ad.app_image,ad.fbsdk_id,ad.twittersdk_id,sc.image_link as splash_url from app_data ad left join splash_screen sc on ad.splash_screen_id=sc.id where ad.id=:appid"; 
	    }
		$appQueryRun = $dbCon->prepare($app_query);
		$appQueryRun->bindParam(':appid',$app_id,PDO::PARAM_INT);
		$appQueryRun->execute();
		$rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
		$typeapp= $rowFetch['type_app'];
		$bgcolor=$rowFetch['background_color'];
		
				if($typeapp != 1)
				{
				$app_query2 = "select * from app_catalogue_attr where app_id=:appid"; 
				$appQueryRun2 = $dbCon->prepare($app_query2);
				$appQueryRun2->bindParam(':appid',$app_id,PDO::PARAM_INT);
				$appQueryRun2->execute();
				$rowFetch2 = $appQueryRun2->fetch(PDO::FETCH_ASSOC);	
				
				 $bgcolor=$rowFetch2['background_color'];
				}

				$newResult=array("app_id"=> $rowFetch['app_id'],"type_app"=> $rowFetch['type_app'], "app_name"=> $rowFetch['app_name'],"background_color"=> $bgcolor,"app_image"=> $rowFetch['app_image'],"fbsdk_id"=> $rowFetch['fbsdk_id'],"twittersdk_id"=> $rowFetch['twittersdk_id'],"splash_url"=> $rowFetch['splash_url']);
				$response = array("result" => 'success', "details" => array($newResult));
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
