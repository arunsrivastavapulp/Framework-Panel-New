<?php
ini_set('max_execution_time', 0);

require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/bulkimageupload', 'bulkImageUpload'); // Using Post HTTP Method and process bulkImageUpload function
$app->run();

/* 
 * results catalog launch data to api
 * Added By Arun Srivastava on 25/6/15
 */
function bulkImageUpload()
{
	$cf                       = new Fwcore();
	$data                     = array();
	$data['auth_token']       = isset($_POST['auth_token']) ? $_POST['auth_token'] : '';
	$data['cart_sequence_id'] = isset($_POST['cart_sequence_id']) ? $_POST['cart_sequence_id'] : '';
	$data['product_id']       = isset($_POST['product_id']) ? $_POST['product_id'] : '';
	$data['app_id']           = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['excludedIds']      = isset($_POST['excludedIds']) ? $_POST['excludedIds'] : '';
	
	
	$appQueryData   = "SELECT * FROM app_data WHERE app_id='" . $data['app_id'] . "'";
	$app_screenData = $cf->query_run($appQueryData, 'select');
	
	$userquery  = "SELECT customer_id, email FROM oc_customer WHERE auth_token = '".$data['auth_token']."'";
	$ocuserdata = $cf->queryRun($userquery, 'select');
	
	$total = 0;
	if(isset($_FILES['uploaded_file']['name']))
	{
		$total = count($_FILES['uploaded_file']['name']);
	}
	
	$filesuccess = 0;
	$fileerror   = 0;
	$imageUrlArr = array();
	
	if($total > 0)
	{
		for($i=0; $i<$total; $i++)
		{
			$imagename = $_FILES['uploaded_file']['name'][$i];
			$imageArr  = explode('.', $imagename);
			$imagename = $imageArr[0].'_'.time().'.'.$imageArr[1];
			
			
			$file_path = "panel/frameworkphp/images/".$ocuserdata['email']."/".$app_screenData['id']."/".$data['product_id']."/";
			$finalpath = "../../../../".$file_path.basename($imagename);
			
			if(!is_dir('../../../../'.$file_path))
			{
				mkdir('../../../../'.$file_path, 0777, true);
			}
			
			if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$i], $finalpath))
			{
				//$imageurl      = "http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/framework/catalogue/ecommerce_catalog_api/1.5/".$file_path;
				$imageurl      = $file_path.basename($imagename);
				$imageUrlArr[] = $imageurl;
				$filesuccess++;
			}
			else
			{
				$fileerror++;
			}
		}
	}
	$data['image_urls'] = $imageUrlArr;
	
	
	$cf->bulkImageUpload($data);
}