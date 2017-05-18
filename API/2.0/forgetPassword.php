<?php

require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/forgetpassword', 'forgetpassword');

function forgetpassword() {
$dbCon = content_db();
$baseUrl = baseUrlWeb();
 $app_idString = $_POST['app_id'];
 $appQueryData = "select *  from app_data where app_id=:appid";
        $app_screenData =$dbCon->prepare($appQueryData);
        $app_screenData->bindParam(':appid',$app_idString,PDO::PARAM_STR);
        $app_screenData->execute();
        $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);

        if ($result_screenData != '') {
            $app_id = $result_screenData->id;
            $email=$result_screenData->contactus_email;
            $phoneNumber=$result_screenData->contactus_phone;
        } else {
            $app_id = 0;
        }
if(isset($_POST['email_address']))
{
        	$email = $_POST['email_address'];

            $app_query = "select email_address,password from user_appdata where email_address=:email and app_id=:appid"; 
            $appQueryRun =$dbCon->prepare($app_query);
           $appQueryRun->bindParam(':email',$email,PDO::PARAM_STR);
           $appQueryRun->bindParam(':appid',$app_id,PDO::PARAM_INT);
            $appQueryRun->execute();
            $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
            $calRow = $appQueryRun->rowCount();


	if($calRow!=0){
	$cto  = $email;				
    if($cto != '' || $cto != null)
    {           
                $encrrptedEmail = base64_encode($email);

                $cformemail = 'noreply@instappy.com';
                $key = 'f894535ddf80bb745fc15e47e42a595e';
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => array(
                        'api_key' => $key,
                        'subject' => 'Instappy - Forget Password',
                        'fromname' => 'Instappy',
                        'from' => $cformemail,
                        'content' => "<a href=".$baseUrl."forgetpassword.php?token=".$encrrptedEmail."&secret=".$rowFetch['password'].">Click here to reset your password</a>",
                        'recipients' => $cto
                    )
                ));
                $customerhead = curl_exec($curl);
                curl_close($curl);


                $response = array("result" => 'success', "msg" => 'password reset link has been sent to your email address');
			$Basearray = array("response" => $response);		
			$basejson = json_encode($Basearray);
			echo $basejson; 
  die;
    } 
    }else{
            $response = array("result" => 'error', "msg" => 'Invalid email address');
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
