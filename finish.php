<?php

require 'Slim/Slim.php';
require 'db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/index', 'index');

function index() {
$dbCon = getConnection();
$baseUrl = baseUrl();

if(isset($_POST['app_id']))
{
 $app_id = $_POST['app_id'];
 $query = "select au.mobile_country_code,au.mobile,ad.summary,au.mobile_validated,ad.type_app from author au join app_data ad on ad.author_id = au.id where ad.id='".$app_id."'";
        $queryData = $dbCon->query($query);
        $rowFetch = $queryData->fetch(PDO::FETCH_ASSOC);
		$calRow = $queryData->rowCount();

	if($calRow!=0){
		if($rowFetch['mobile_validated']==1){
			if($rowFetch['type_app']==1){
			$url = "http://goo.gl/0TU63g";
			}else{
			$url = "http://goo.gl/7vpzLo";
			}
			$smsHtml = "Congratulations! You've just taken your first step towards having your very own mobile app '".$rowFetch['summary']."' Start testing your app on wizard. Download wizard from ".$url;
			$countryCode = $rowFetch['mobile_country_code'];
			$number = $rowFetch['mobile'];
			
			$MobileNumer = $countryCode . $number;
			$myto = $MobileNumer;

			$fromsms = 'INSTAP';
			$feedid = '354277';
			$username = '9958667744';
			$passwordsms = 'wmjat';
			$smstime = date('YmdHi', time());
			$url = 'http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=' . $feedid . '&username=' . $username . '&password=' . $passwordsms . '&To=' . $myto . '&Text=' . rawurlencode($smsHtml) . '&time=' . $smstime . '&senderid=' . $fromsms;
	
			$head = file_get_contents($url);
			$response = array("result" => 'success');
			$Basearray = array("response" => $response);		
			$basejson = json_encode($Basearray);
			echo $basejson; 
			die;
		}         
    } 
    else{
            $response = array("result" => 'error', "msg" => 'Invalid App ID');
            $Basearray = array("response" => $response);        
            $basejson = json_encode($Basearray);
            echo $basejson; 
            die;
    }	
}
else
{
			$response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
			$Basearray = array("response" => $response);		
			$basejson = json_encode($Basearray);
			echo $basejson; 
            die;
}
}



$app->run();
