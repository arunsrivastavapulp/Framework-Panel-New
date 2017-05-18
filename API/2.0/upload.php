<?php
require 'Slim/Slim.php';
require 'includes/db.php';

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/index', 'index'); 

$app->run();

function index()
{
  $r='no';
 //$dbCon = getConnection();
if(isset($_POST['app_id']))
{
     $dbCon = content_db();
     $baseurl=baseUrlWeb();
    $app_idString=$_POST['app_id'];
 $appQueryData = "select *  from app_data where app_id=:appid";
        $app_screenData = $dbCon->prepare($appQueryData);
        $app_screenData->bindParam(':appid', $app_idString, PDO::PARAM_STR);
        $app_screenData->execute();
        $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);

        if ($result_screenData != '') {
            $app_id = $result_screenData->id;
             if (!file_exists('/var/www/html/panelimage/'.$app_id)) {
        mkdir('/var/www/html/panelimage/'.$app_id, 0777, true);
    }
        } else {
            $app_id = 11;
        }
}
  
   if(isset($_FILES['filename']['name']))
{
	
	if(!$_FILES['filename']['error'])
	{
         

			$new_file_name = time().'-'.strtolower($_FILES['filename']['name']); 
			//$new_file_name = $_FILES['filename']['name'];
			move_uploaded_file($_FILES['filename']['tmp_name'], '/var/www/html/panelimage/'.$app_id.'/'.$new_file_name);
                        $fileurls=$baseurl.'panelimage/'.$app_id.'/'.$new_file_name;
			
			$response = array("result" => 'success','msg_code' => '200',"file_url"=>$fileurls,"app_id"=>$app_id);
			$Basearray = array("response" => $response);
			$basejson = json_encode($Basearray);
			echo $basejson;  
		
	}
	//if there is an error...
	else
	{
		   $response = array("result" => 'error','msg' => 'error');
		   $Basearray = array("response" => $response);
		   $basejson = json_encode($Basearray);
		   echo $basejson;
	}


}else{
       $response = array("result" => 'error','msg' => 'parameter missing!');
      $Basearray = array("response" => $response);
      $basejson = json_encode($Basearray);
      echo $basejson;
}
} 


 