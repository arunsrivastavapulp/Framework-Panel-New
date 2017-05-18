<?php
// opencart
function retail_db() {
    try {
		$db_username = "ecommerce_cat";
        $db_password = "ecommerce@12312";
        $conn = new PDO('mysql:host=pulpstrategyinstance.cil4anb91ydi.us-west-2.rds.amazonaws.com;dbname=ecommerce_app', $db_username, $db_password);
		$conn->exec("set names utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		
		/*
		$db_username = "ecommerce_cat";
        $db_password = "ecommerce@12312";
        $conn = new PDO('mysql:host=framework.cuogjeymw1h7.us-west-2.rds.amazonaws.com;dbname=ecommerce_app_new', $db_username, $db_password);
		$conn->exec("set names utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		*/
 
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    return $conn;
}

// framework
function content_db() {
    try {
		
		$db_username = "hemant";
        $db_password = "Fdfjkhg%#$#@4312AS";
        $conn = new PDO('mysql:host=pulpstrategyinstance.cil4anb91ydi.us-west-2.rds.amazonaws.com;dbname=app_design_new', $db_username, $db_password);
		$conn->exec("set names utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		
		/*
		$db_username = "hemant";
        $db_password = "Fdfjkhg%#$#@4312AS";
        $conn = new PDO('mysql:host=framework.cuogjeymw1h7.us-west-2.rds.amazonaws.com;dbname=instappy_production', $db_username, $db_password);
		$conn->exec("set names utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		*/
 
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    return $conn;
}

function baseUrl() {
    //$baseURL = "http://www.instappy.com/catalogue/image/";
	//$baseURL = "http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/framework/catalogue/image/";
	$baseURL = "http://54.69.88.247/catalogue/image/";
    //$baseURL = "http://52.42.166.139/catalogue/image/";
    return $baseURL;
}
function base_url() {
    //$baseURL = "http://www.instappy.com/catalogue/image/";
	//$baseURL = "http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/framework/catalogue/image/";
	$baseURL = "http://54.69.88.247/";
    //$baseURL = "http://52.42.166.139/";
    return $baseURL;
}
function uploadImg() {
	$uploadImg = "../uploadImages/";
    return $uploadImg;
}
function uploadImgPath() {
    $uploadImgPath = "uploadImages/";
    return $uploadImgPath;
}
?>