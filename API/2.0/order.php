<?php
require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/myorders', 'myOrders'); // Using Post HTTP Method and process myOrders function
$app->post('/placeorder', 'placeOrder'); // Using Post HTTP Method and process placeOrder function
$app->post('/orderconfirm', 'orderConfirm'); // Using Post HTTP Method and process placeOrder function
$app->run();


/* 
 * results my order to api
 * Added By Arun Srivastava on 4/8/15
 */
function myOrders()
{
	$cf = new Fwcore();
	
	$data               = array();
	$data['auth_token'] = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['app_id']     = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['email']      = isset($_POST['email']) ? $_POST['email'] : '';
	$data['device_id']  = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$cf->getMyOrders($data);
}

/* 
 * results placing order (pending state)
 * Added By Arun Srivastava on 14/8/15
 */
function placeOrder()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['app_id']      = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['api_version'] = isset($_POST['api_version']) ? $_POST['api_version'] : '1.0';
	$data['email']       = isset($_POST['email']) ? $_POST['email'] : '';
	$data['device_id']   = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$cf->placeOrder($data);
}

/* 
 * results order confirmation
 * Added By Arun Srivastava on 26/8/15
 */
function orderConfirm()
{
	$cf = new Fwcore();
	
	$data                       = array();
	$data['auth_token']         = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['order_id']           = isset($_POST['order_id']) ? $_POST['order_id'] : '';
	$data['payment_type']       = isset($_POST['payment_type']) ? $_POST['payment_type'] : '';
	$data['payment_gateway']    = isset($_POST['payment_gateway']) ? $_POST['payment_gateway'] : '';
	$data['transaction_id']     = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : '';
	$data['transaction_status'] = isset($_POST['transaction_status']) ? $_POST['transaction_status'] : '';
	$data['app_id']             = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['email']              = isset($_POST['email']) ? $_POST['email'] : '';
	$data['device_id']          = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$cf->orderConfirm($data);
}