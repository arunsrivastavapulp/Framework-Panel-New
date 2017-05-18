<?php
require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/viewproducts', 'viewProducts'); // Using Post HTTP Method and process viewProducts function
$app->post('/viewproductdetail', 'viewProductDetail'); // Using Post HTTP Method and process viewProductDetail function
$app->post('/gettagdata', 'getTagData'); // Using Post HTTP Method and process getAppBasicData function
$app->post('/viewproductreviews', 'viewProductReviews'); // Using Post HTTP Method and process viewProductReviews function
$app->post('/saveproductreviews', 'saveProductReview'); // Using Post HTTP Method and process saveProductReview function
$app->run();



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
	$data['app_id']      = $_POST['app_id'];
	
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
	$data['app_id']      = isset($_POST['app_id']) != '' ? $_POST['app_id'] : '';
	$cf->viewProductDetail($data);
}

/* 
 * results view products to api
 * Added By Arun Srivastava on 13/7/15
 */
function gettagdata()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = $_POST['auth_token'];
	$data['tag_id']  = $_POST['category_id'];
    $data['api_version']  = $_POST['api_version'];
	$data['app_id']      = isset($_POST['app_id']) != '' ? $_POST['app_id'] : '';
	$cf->viewProductsTagList($data);
}

/* 
 * results view product reviews to api
 * Added By Arun Srivastava on 17/8/16
 */
function viewProductReviews()
{
	$cf = new Fwcore();
	
	$data                = array();
	$data['auth_token']  = $_POST['auth_token'];
	$data['product_id']  = $_POST['product_id'];
	$data['app_id']      = isset($_POST['app_id']) != '' ? $_POST['app_id'] : '';
	
	if(isset($_POST['offset']) && $_POST['offset'] != '')
	{
		$data['offset'] = $_POST['offset'];
	}
	else
	{
		$data['offset'] = 0;
	}
	$cf->viewProductReviews($data);
}

/* 
 * results view product reviews to api
 * Added By Arun Srivastava on 17/8/16
 */
function saveProductReview()
{
	$cf = new Fwcore();
	
	$data                 = array();
	$data['auth_token']   = $_POST['auth_token'];
	$data['product_id']   = $_POST['product_id'];
	$data['email']        = $_POST['email'];
	$data['rating']       = $_POST['rating'];
	$data['review_title'] = isset($_POST['review_title']) != '' ? $_POST['review_title'] : '';
	$data['review']       = isset($_POST['review']) != '' ? $_POST['review'] : '';
	$data['app_id']       = isset($_POST['app_id']) != '' ? $_POST['app_id'] : '';
	$data['rating_id']    = isset($_POST['rating_id']) != '' ? $_POST['rating_id'] : '';
	
	$cf->saveProductReview($data);
}