<?php
require 'Slim/Slim.php';

require_once('fetch_address_data.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/fetchmultipleaddress', 'fetchMultipleAddress'); // Using Post HTTP Method and process catalogLaunch function
$app->run();

/* 
 * results catalog launch data to api
 * Added By Arun Srivastava on 25/6/15
 */
function fetchMultipleAddress()
{
	$cf = new addressData();
	
	$data               = array();
	$data['app_id']     = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['auth_token'] = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['domain_id']  = isset($_POST['domain_id']) ? $_POST['domain_id'] : '';
        
	$cf->fetchMultipleAddress($data);
}