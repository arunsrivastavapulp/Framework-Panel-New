<?php
require_once('config/db.php');
$db = new DB();
$mysqli = $db->dbconnection();
$url =  $db->siteurl();

$email = base64_decode($_REQUEST['token']);

if($_REQUEST['flag']!='1'){
	$pass = md5(base64_decode($_REQUEST['key']));
}else{
	$pass = base64_decode($_REQUEST['key']);
}
$app_id = base64_decode($_REQUEST['secret']);

$sql = "update user_appdata set is_verified='1' where email_address='".$email."' and password='".$pass."' and app_id='".$app_id."'";

	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	header("location:".$url);
?>