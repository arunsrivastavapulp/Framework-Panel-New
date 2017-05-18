<?php
require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/screendetail', 'screenDetail'); // Using Post HTTP Method and process screenDetail function
$app->run();



/* 
 * results screen details to api
 * Added By Arun Srivastava on 26/6/15
 */
function screenDetail()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['category_id'] = isset($_POST['category_id']) ? $_POST['category_id'] : '';
	$data['app_id']      = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	
	if(isset($_POST['offset']) && $_POST['offset'] != '')
	{
		$data['offset'] = $_POST['offset'];
	}
	else
	{
		$data['offset'] = 0;
	}
	$cf->getScreenDetailData($data);
}