<?php
error_reporting(0);
require_once('config/db.php');
$db = new DB();

$filename = $_POST['uploadImages'];
$createLoginInput = $_REQUEST['createLoginInput']; 
$description = $_REQUEST['description'];
$loginVia = $_REQUEST['loginVia'];
$appid = $_REQUEST['app_id'];
$colorCode = $_REQUEST['colorCode'];
$facebook_app_id = $_REQUEST['facebook_app_id'];

$addStore = $_REQUEST['addStore'];
$selectedAppId = $_REQUEST['selectedAppId'];
if($addStore=='0'){
	$typeApp = '1';
}elseif($addStore=='1'){
	$typeApp = '5';
}




	$mysqli = $db->dbconnection();

	//if($addStore!='' && $selectedAppId!='' && $selectedAppId!='0'){
	$updateAddStoreQuery = "update app_data set jump_to='".$addStore."',jump_to_app_id='".$selectedAppId."' where id='".$appid."'";
	$stmt5 = $mysqli->prepare($updateAddStoreQuery);
	$stmt5->execute();
    //}

	$checkDataExistQuery = "select app_id from app_signupdata where app_id='".$appid."'";
    $stmt = $mysqli->prepare($checkDataExistQuery);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  
   
    if($result['app_id']!=$appid){
	$updateQuery = "update app_data set is_signup='".$createLoginInput."' where id='".$appid."'";
	$stmt1 = $mysqli->prepare($updateQuery);
	$stmt1->execute();

	$insertQuery = "INSERT INTO app_signupdata (app_id, app_signupsource_id, description, company_logo, background_color,fb_id) VALUES ('".$appid."','".$loginVia."', '".$description."', '".$filename."','".$colorCode."','".$facebook_app_id."')";
	$stmt2 = $mysqli->prepare($insertQuery);
	$stmt2->execute();
}else{


	$updateQuery4 = "update app_data set is_signup='".$createLoginInput."' where id='".$appid."'";
	$stmt4 = $mysqli->prepare($updateQuery4);
	$stmt4->execute();

	$secondUpdateQuery = "update app_signupdata set app_signupsource_id ='".$loginVia."', description ='".$description."', company_logo ='".$filename."', background_color ='".$colorCode."',fb_id ='".$facebook_app_id."' where  app_id='".$appid."'";
	$stmt4 = $mysqli->prepare($secondUpdateQuery);
	$stmt4->execute();
	}

?>