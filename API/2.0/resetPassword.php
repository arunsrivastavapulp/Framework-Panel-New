<?php

require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/resetpassword', 'resetpassword');

function resetpassword() {
$dbCon = content_db();
$baseUrl = baseUrlWeb();

if(isset($_POST['email_address']) && isset($_POST['app_id']) && isset($_POST['password']) && isset($_POST['otp']))
{

     $app_idString = $_POST['app_id'];
     $appQueryData = "select *  from app_data where app_id=:id";
     $app_screenData = $dbCon->prepare($appQueryData);  
            $app_screenData->bindParam("id", $app_idString);
			 $app_screenData->execute();
     $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);
     

    $app_id = $result_screenData->id;
	$email = $_POST['email_address'];
    $password = $_POST['password'];
   
    $otp = $_POST['otp'];
    $app_query = "select password from user_appdata where email_address=:email and app_id=:appid and verification_code=:otp"; 
     $appQueryRun = $dbCon->prepare($app_query);  
            $appQueryRun->bindParam(":email", $email);
            $appQueryRun->bindParam(":appid", $app_id);
            $appQueryRun->bindParam(":otp", $otp);
            $appQueryRun->execute();
    $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
    $calRow = $appQueryRun->rowCount();
    if($calRow!='1'){
            $response = array("result" => 'error', "msg" => '321');
            $Basearray = array("response" => $response);        
            $basejson = json_encode($Basearray);
            echo $basejson; 
            die;

    }else{
   
             $digits      = 4;
             $auto_otp    = rand(pow(10, $digits-1), pow(10, $digits)-1);
             $newPass = md5($password);
             $sql = "update user_appdata set password='".$newPass."',verification_code=:vfcode where email_address=:email and app_id=:appid and verification_code=:otp";
             $passwordInsert = $dbCon->prepare($sql);
             $passwordInsert->bindParam(":email", $email,PDO::PARAM_STR);
             $passwordInsert->bindParam(":vfcode", $auto_otp,PDO::PARAM_STR);
             $passwordInsert->bindParam(":appid", $app_id,PDO::PARAM_STR);
             $passwordInsert->bindParam(":otp", $otp,PDO::PARAM_STR);
             $passwordInsert->execute();
             $response = array("result" => 'success', "msg" => '320');
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

// POST route
$app->post(
        '/post', function () {
    echo 'This is a POST route';
}
);

// PUT route
$app->put(
        '/put', function () {
    echo 'This is a PUT route';
}
);

// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

// DELETE route
$app->delete(
        '/delete', function () {
    echo 'This is a DELETE route';
}
);

$app->run();
