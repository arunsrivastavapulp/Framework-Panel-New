<?php
require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/cataloglaunch', 'catalogLaunch'); // Using Post HTTP Method and process catalogLaunch function
$app->post('/screendetail', 'screenDetail'); // Using Post HTTP Method and process screenDetail function
$app->post('/viewproducts', 'viewProducts'); // Using Post HTTP Method and process viewProducts function
$app->post('/viewproductdetail', 'viewProductDetail'); // Using Post HTTP Method and process viewProductDetail function

/* Author : Varun Srivastava*/
$app->post('/vendorregister', 'vendorRegister'); // Using Post HTTP Method and process vendor register function
$app->post('/vendorlogin', 'vendorLogin'); // Using Post HTTP Method and process vendor login function
$app->post('/customerregister', 'customerRegister'); // Using Post HTTP Method and process customer register function
$app->post('/customerlogin', 'customerLogin'); // Using Post HTTP Method and process customer login function
$app->post('/customerforgot', 'customerForgot'); // Using Post HTTP Method and process customer login function
$app->post('/getcustomerprofile', 'getCustomerProfile'); // Using Post HTTP Method and process customer login function
$app->post('/updatecustomerprofile', 'updateCustomerProfile'); // Using Post HTTP Method and process customer login function
$app->run();



/* 
 * results catalog launch data to api
 * Added By Arun Srivastava on 25/6/15
 */
function catalogLaunch()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['app_id']      = $_POST['app_id'];
	$data['app_type']    = $_POST['app_type'];
	$data['device_id']   = $_POST['device_id'];
	$data['platform']    = $_POST['platform'];
	$data['app_version'] = $_POST['app_version'];
	$data['api_version'] = $_POST['api_version'];
	$data['first_launch'] = $_POST['first_launch'];
	$cf->getCatalogLaunchData($data);
}

/* 
 * results screen details to api
 * Added By Arun Srivastava on 26/6/15
 */
function screenDetail()
{
	$cf = new Fwcore();
	
	$data               = array();
	$data['auth_token'] = $_POST['auth_token'];
	$data['screen_id']  = $_POST['screen_id'];
	
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

/* 
 * results view products to api
 * Added By Arun Srivastava on 13/7/15
 */
function viewProducts()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = $_POST['auth_token'];
	$data['category_id'] = $_POST['category_id'];
	
	if(isset($_POST['offset']) && $_POST['offset'] != '')
	{
		$data['offset'] = $_POST['offset'];
	}
	else
	{
		$data['offset'] = 0;
	}
	$cf->viewProductsList($data);
}

/* 
 * results view products to api
 * Added By Arun Srivastava on 13/7/15
 */
function viewProductDetail()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = $_POST['auth_token'];
	$data['product_id']  = $_POST['product_id'];
	$cf->viewProductDetail($data);
}

/* 
 * register vendor
 * Added By Varun Srivastava on 09/7/15
 */
function vendorRegister()
{
	$cf = new Fwcore();
	$cf->registerVendor();
}

/* 
 * Login vendor
 * Added By Varun Srivastava on 10/7/15
 */
function vendorLogin()
{
	$cf = new Fwcore();
	$cf->loginVendor();
}	

/* 
 * Register customer
 * Added By Varun Srivastava on 15/7/15
 */
function customerRegister()
{
	$cf = new Fwcore();
	$cf->registerCustomer();
}

/* 
 * Login customer
 * Added By Varun Srivastava on 15/7/15
 */
function customerLogin()
{
	$cf = new Fwcore();
	$cf->loginCustomer();
}

/* 
 * Forgot Password customer
 * Added By Varun Srivastava on 16/7/15
 */
function customerForgot()
{
	$cf = new Fwcore();
	$cf->forgotCustomer();
}

/*
 * Get customer profile data
 * Added By Arun Srivastava on 29/7/15
 */
function getCustomerProfile()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = $_POST['auth_token'];
	$data['email']       = $_POST['email'];
	$cf->getCustomerProfile($data);
}

/*
 * save customer profile data
 * Added By Arun Srivastava on 29/7/15
 */
function updateCustomerProfile()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = $_POST['auth_token'];
	$data['firstname']   = $_POST['fname'];
	$data['email']       = $_POST['email'];
	$data['telephone']   = $_POST['phone'];
	$data['gender']      = $_POST['gender'];
	$cf->updateCustomerProfile($data);
}