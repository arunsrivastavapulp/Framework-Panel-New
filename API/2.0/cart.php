<?php
require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/addtocart', 'addToCart'); // Using Post HTTP Method and process addToCart function
$app->post('/viewcart', 'viewCart'); // Using Post HTTP Method and process addToCart function
$app->post('/deletecart', 'deleteCart'); // Using Post HTTP Method and process addToCart function
$app->post('/updatecartquantity', 'updateCartQuantity'); // Using Post HTTP Method and process updateCartQuantity function
$app->post('/checkcouponcode', 'checkCouponCode'); // Using Post HTTP Method and process updateCartQuantity function
$app->run();


/* 
 * add to cart api
 * Added By Arun Srivastava on 3/8/15
 */
function addToCart()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['app_id']      = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['cart_data']   = isset($_POST['cart_data']) ? $_POST['cart_data'] : '';
	$data['email']       = isset($_POST['email']) ? $_POST['email'] : '';
	$data['device_id']   = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$cf->addToCart($data);
}

/* 
 * add to cart api
 * Added By Arun Srivastava on 3/8/15
 */
function viewCart()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['app_id']      = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['email']       = isset($_POST['email']) ? $_POST['email'] : '';
	$data['device_id']   = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$cf->viewCart($data);
}

/* 
 * delete cart api
 * Added By Arun Srivastava on 20/8/15
 */
function deleteCart()
{
	$cf = new Fwcore();
	
	$data                     = array();
	$data['auth_token']       = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['cart_sequence_id'] = isset($_POST['cart_sequence_id']) ? $_POST['cart_sequence_id'] : '';
	$data['app_id']           = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['email']            = isset($_POST['email']) ? $_POST['email'] : '';
	$data['device_id']        = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$cf->deleteCart($data);
}

/* 
 * update cart quantity api
 * Added By Arun Srivastava on 28/9/15
 */
function updateCartQuantity()
{
	$cf = new Fwcore();
	
	$data                     = array();
	$data['auth_token']       = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['cart_sequence_id'] = isset($_POST['cart_sequence_id']) ? $_POST['cart_sequence_id'] : '';
	$data['quantity']         = isset($_POST['quantity']) ? $_POST['quantity'] : '';
	$data['app_id']           = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['email']            = isset($_POST['email']) ? $_POST['email'] : '';
	$data['device_id']        = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$cf->updateCartQuantity($data);
}

/* 
 * coupon code api
 * Added By Arun Srivastava on 29/6/16
 */
function checkCouponCode()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['coupon']      = isset($_POST['coupon']) ? $_POST['coupon'] : '';
	$data['app_id']      = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$cf->checkCouponCode($data);
}