<?php

require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/index', 'index');

function index() {
$dbCon = content_db();
$baseUrl = baseUrlContent();

if(isset($_POST['code']))
{
 $countryCodeVal = $_POST['code'];
 $number=$_POST['mobile'];
$validate=$_POST['validate'];
$app_type=1;
$app_id=$_POST['app_id'];
$custid=$_POST['authorId'];
                $smsQuery = "update author set `sms_sent` = '1' where `custid`=:custid";				
                $smsQueryExe = $dbCon->prepare($smsQuery);
                $smsQueryExe->bindParam(':custid',$custid,PDO::PARAM_INT);
                $smsQueryExe->execute();
                 $query = "select * from app_data where id=:appid";
         $queryData = $dbCon->prepare($query);
                $queryData->bindParam(':appid',$app_id,PDO::PARAM_INT);
                $queryData->execute();
        $rowFetch = $queryData->fetch(PDO::FETCH_ASSOC);
		$calRow = $queryData->rowCount();
		if($validate==1 && $calRow > 0){
			if($app_type==1){
			$url = "http://bit.ly/25j3fTT";
			}else{
			$url = "http://bit.ly/1sAqD0G";
			}
             $smsHtml = "Congratulations! on your very own '".$rowFetch['summary']."'. It looks great! To Start testing your app on the wizard. Download the wizard from ".$url;
// $smsHtml = "Congratulations! You've just taken your first step towards having your very own mobile app '".$rowFetch['summary']."' Start testing your app on wizard. Download wizard from ".$url;
			
                        
                        $mobileinterN='+'.$countryCodeVal.$number;
                          $myto = $mobileinterN;

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
