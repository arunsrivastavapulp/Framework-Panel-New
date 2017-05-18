<?php
require_once ('config/db.php');
class SaveHtml extends Db{
	 
	 var $db, $url;
function __construct(){
		$this->db=$this->dbconnection();
		$this->url = $this->siteurl();		
		 
	 }
function savehtml($data){ 
	$app_id=$data['app_id'];
	$autherId=$data['autherId'];
	$linkTo=$data['linkTo'];
	$layoutType=$data['layoutType'];
	$html=$data['html'];
	$screen_id=$data['screen_id'];
	$originalIndex=$data['originalIndex'];
	$title =$data['title'];
	$banner_html =$data['banner_html'];
	$navigation_html =$data['navigation_html'];
	$ico_icon =$data['ico_icon'];	
	


	$curl = curl_init();
	curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'API/appHtml.php/saveData',
			CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'app_id' =>$app_id,
				'autherId' => $autherId,
				'linkTo' => $linkTo,
				'layoutType' => $layoutType,
				'html' => $html,
				'screen_id' => $screen_id,
				'originalIndex' => $originalIndex,
				'title' => $title,
				'banner_html' => $banner_html,
				'navigation_html' => $navigation_html,
				'ico_icon' => $ico_icon
			)
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		$results=json_decode($resp);
		// Close request to clear up some resources
		curl_close($curl);
		
		$result=$this->check_user_exist($_SESSION['username'],$_SESSION['custid']);
		echo $result['id'].'##'.$_SESSION['appid'].'##'.$title;
}

function deletehtml($data){
	$app_id=$data['app_id'];
	$autherId=$data['autherId'];
	
	$screen_id=$data['screen_id'];
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'API/appHtml.php/DeleteData',
			CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'app_id' =>$app_id,
				'autherId' => $autherId,				
				'screen_id' => $screen_id
			)
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		$results=json_decode($resp);
		// Close request to clear up some resources
		curl_close($curl);
		
		$result=$this->check_user_exist($_SESSION['username'],$_SESSION['custid']);
		echo $result['id'].'##'.$_SESSION['appid'].'##';
}



function check_user_exist($email,$custid){
	$sql ="select id,first_name,avatar from author where custid=$custid";
	$stmt=$this->db->prepare($sql);
	$stmt->execute();
	$results=$stmt->fetch(PDO::FETCH_ASSOC);
	return $results;
 }
 }