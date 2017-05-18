<?php
require 'Slim/Slim.php';

require_once('retail_class.php');
require_once('retail_class_second.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/setretail', 'setretail'); // Using Post HTTP Method and process catalogLaunch function
$app->post('/gettemplate', 'gettemplate');
$app->post('/catalogLaunch', 'catalogLaunch');
$app->post('/gettemplate2', 'gettemplate2');
$app->run();

function catalogLaunch()
{
	$retail = new Retail();
	
	$data                 = array();
	$data['app_id']       = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['app_type']     = isset($_POST['app_type']) ? $_POST['app_type'] : '';
	$data['device_id']    = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$data['platform']     = isset($_POST['platform']) ? $_POST['platform'] : '';
	$data['app_version']  = isset($_POST['app_version']) ? $_POST['app_version'] : '';
	$data['api_version']  = isset($_POST['api_version']) ? $_POST['api_version'] : '';
	$data['first_launch'] = isset($_POST['first_launch']) ? $_POST['first_launch'] : '';
	$retail->getCatalogLaunchData($data);
}

function setretail()
{
	$retail = new Retail();
	$data   = array();
	$data['app_data']   = isset($_POST['app_data']) ? $_POST['app_data'] : '';
	$data['author_id']  = isset($_POST['author_id']) ? $_POST['author_id'] : '';
	$data['userId']     = isset($_SESSION['userId']) ? $_SESSION['userId'] : '';
	$data['catid']      = isset($_SESSION['catid']) ? $_SESSION['catid'] : '';
	$data['themeid']    = isset($_SESSION['themeid']) ? $_SESSION['themeid'] : '';
	//print_r($data['app_data']);
	$retail->setretailData($data);
}

function gettemplate()
{
	$retail = new Retail();
	$data['theme_id']   = trim($_POST['theme_id']);
	$retail->gettemplateData($data);
}

function gettemplate2()
{
	$retail = new Retailsecond();
	$data['theme_id']   = trim($_POST['theme_id']);
	$retail->gettemplateData($data);
}

