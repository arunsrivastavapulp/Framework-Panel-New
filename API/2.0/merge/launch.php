<?php
require 'Slim/Slim.php';

require_once('launchData.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/cataloglaunch', 'catalogLaunch'); // Using Post HTTP Method and process catalogLaunch function
$app->post('/getappbasicdata', 'getAppBasicData'); // Using Post HTTP Method and process getAppBasicData function
$app->run();

/* 
 * results catalog launch data to api
 * Added By Arun Srivastava on 25/6/15
 */
function catalogLaunch()
{
	$cf = new LaunchData();
	
	$data                 = array();
	$data['app_id']       = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['app_type']     = isset($_POST['app_type']) ? $_POST['app_type'] : '';
	$data['device_id']    = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$data['platform']     = isset($_POST['platform']) ? $_POST['platform'] : '';
	$data['app_version']  = isset($_POST['app_version']) ? $_POST['app_version'] : '';
	$data['api_version']  = isset($_POST['api_version']) ? $_POST['api_version'] : '';
	$data['first_launch'] = isset($_POST['first_launch']) ? $_POST['first_launch'] : '';
        
        
	$cf->getCatalogLaunchData($data);
}


function getAppBasicData() 
{
	$cf = new LaunchData();
	
	$data           = array();
	$data['app_id'] = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$cf->getAppBasicData($data);
}