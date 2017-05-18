<?php
require 'Slim/Slim.php';
require_once('common_functions.php');
\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/customerregister', 'customerRegister'); // Using Post HTTP Method and process customer register function
$app->post('/customerlogin', 'customerLogin'); // Using Post HTTP Method and process customer login function
$app->post('/customerforgot', 'customerForgot'); // Using Post HTTP Method and process customer login function
$app->post('/getcustomerprofile', 'getCustomerProfile'); // Using Post HTTP Method and process customer login function
$app->post('/updatecustomerprofile', 'updateCustomerProfile'); // Using Post HTTP Method and process customer login function
$app->post('/updatecustomeraddress', 'updateCustomerAddress'); // Using Post HTTP Method and process customer login function
$app->post('/getcustomeraddress', 'getCustomerAddress'); // Using Post HTTP Method and process customer login function
$app->post('/updateforgotpassword', 'updateForgotPassword'); // Using Post HTTP Method and process update customer forgot password function
$app->post('/verifyregistereduser', 'verifyRegisteredUser'); // Using Post HTTP Method and process verify customer function
$app->post('/resendacticationcode', 'resendActivationCode'); // Using Post HTTP Method and process verify customer function
$app->post('/customerlogout', 'customerLogout'); // Using Post HTTP Method and process customer login function
$app->run();

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
 * Update Forgotted Password customer
 * Added By Arun Srivastava on 2/12/15
 */
function updateForgotPassword()
{
	$cf = new Fwcore();
	$cf->updateForgotPassword();
}

/* 
 * verify customer
 * Added By Arun Srivastava on 2/12/15
 */
function verifyRegisteredUser()
{
	$cf = new Fwcore();
	$cf->verifyRegisteredUser();
}

/* 
 * resend activation code
 * Added By Arun Srivastava on 2/12/15
 */
function resendActivationCode()
{
	$cf = new Fwcore();
	$cf->resendActivationCode();
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
	$data['device_id']   = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$data['app_id']      = isset($_POST['app_id']) ? $_POST['app_id'] : '';
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
	$data['old_email']   = isset($_POST['old_email']) ? $_POST['old_email'] : '';
	$data['device_id']   = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$data['app_id']      = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$cf->updateCustomerProfile($data);
}

/*
 * update customer address
 * Added By Arun Srivastava on 10/8/15
 */
function updateCustomerAddress()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = $_POST['auth_token'];
	$data['firstname']   = isset($_POST['fname']) ? trim($_POST['fname']) : '';
	$data['address_1']   = isset($_POST['address_1']) ? trim($_POST['address_1']) : '';
	$data['address_2']   = isset($_POST['address_2']) ? trim($_POST['address_2']) : '';
	$data['city']        = isset($_POST['city']) ? trim($_POST['city']) : '';
	$data['postcode']    = isset($_POST['postcode']) ? trim($_POST['postcode']) : '';
	$data['country']     = isset($_POST['country']) ? trim($_POST['country']) : '';
	$data['state']       = isset($_POST['state']) ? trim($_POST['state']) : '';
	$data['email']       = isset($_POST['email']) ? $_POST['email'] : '';
	$data['device_id']   = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$data['app_id']      = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$cf->updateCustomerAddress($data);
}

/*
 * get customer address
 * Added By Arun Srivastava on 10/8/15
 */
function getCustomerAddress()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = $_POST['auth_token'];
	$data['email']       = isset($_POST['email']) ? $_POST['email'] : '';
	$data['device_id']   = isset($_POST['device_id']) ? $_POST['device_id'] : '';
	$data['app_id']      = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$cf->getCustomerAddress($data);
}

/* 
 * Logout customer
 * Added By Varun Srivastava on 15/7/16
 */
function customerLogout()
{
	$cf = new Fwcore();
	$cf->logoutCustomer();
}