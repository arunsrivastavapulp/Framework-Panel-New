 <?php

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();
$app->post('/resendverification', 'resendverification');
/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */


function resendverification() {
$dbCon = content_db();
$baseUrl = baseUrlWeb();
if(isset($_POST['app_id']) && isset($_POST['email_address']))
{
	$app_id = $_POST['app_id'];
	$email = $_POST['email_address'];

			$app_query = "select user_appdata.email_address,user_appdata.user_id,user_appdata.verification_code,user_appdata.is_verified,app_data.id from user_appdata,app_data where user_appdata.app_id=app_data.id and app_data.app_id=:appid and user_appdata.email_address=:email"; 
                  $appQueryRun = $dbCon->prepare($app_query);
                  $appQueryRun->bindParam(':appid',$app_id,PDO::PARAM_INT);
                  $appQueryRun->bindParam(':email',$email,PDO::PARAM_STR);
            $appQueryRun->execute();
            
            $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
            $calRow = $appQueryRun->rowCount();

            $userID=$rowFetch['user_id'];
             $name_query = "select first_name from users where id=:userid"; 
              $name_queryRun = $dbCon->prepare($name_query);
                  $name_queryRun->bindParam(':userid',$userID,PDO::PARAM_INT);
            $name_queryFetch = $name_queryRun->fetch(PDO::FETCH_ASSOC);
            $username = $name_queryFetch['first_name'];
        
        if($calRow != '1'){

        	$response = array("result" => 'error', "msg" => '313');
			$Basearray = array("response" => $response);		
			$basejson = json_encode($Basearray);
			echo $basejson; 
 			die;

        }
        else{

        	if($rowFetch['is_verified']!='1'){

        	$cto  = $email;
	        if($cto != '' || $cto != null)
	        {
	        			
	        			$verification_code = $rowFetch['verification_code'];

			
		
			$subject   = 'Thank you for registering with Instappy. Verify your e-mail';
			$formemail = 'noreply@instappy.com';
			$key       = 'f894535ddf80bb745fc15e47e42a595e';
			//$content   = 'Your account activation code is : '.$auto_otp;
			$content   = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Thank you for registering with Instappy. Verify your e-mail</title>
</head>

<body>
<table cellpadding="0" cellspacing="0" width="650" align="center" style="border:1px solid #7c7c7c;">
    <tr>
        <td>
            <img src="'.$baseUrl.'/edm/images/Welcome-to-Instappy-header.png" />
        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="570" align="center" >
                <tr>
                    <td>
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:14px; line-height:40px;font-family:Arial, Helvetica, sans-serif;">&nbsp;</p>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:20px;font-family:Arial, Helvetica, sans-serif;">Dear '.$username.',</p><br/>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">
                        Thank you for registering with Instappy. Verify your e-mail before we go ahead, we would like to verify your email for security purposes.
                      </p>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">Please use the Authentication code below in the app to signin.</p>
                       <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:14px; line-height:px;font-family:Arial, Helvetica, sans-serif;">&nbsp;</p>
                      <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif; font-weight:bold;">Code: '.$verification_code.'</p>
               <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">Reach us at support@instappy.com</p>
                     
                    </td>
                </tr>
                 <tr>
                    <td colspan="2">
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:14px; line-height:20px;font-family:Arial, Helvetica, sans-serif;">&nbsp;</p>
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">See you!</p>
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:16px; line-height:28px;font-family:Arial, Helvetica, sans-serif;">Instappy Team</p>
                        <p style="margin:0;color:#8b8b8a; margin:0;padding:0; font-size:14px; line-height:20px;font-family:Arial, Helvetica, sans-serif;">&nbsp;</p> 
                    </td>
                </tr>
                
            </table>
            
        </td>
    </tr>
     <tr>
      <td style="color:#bcbcbc;margin:0;padding:15px; font-size:10px;font-family:Arial, Helvetica, sans-serif;text-align:justify">
        Disclaimer: You have received this email as you are a valued customer subscribed to Instappy.com or have registered for an account on Instappy.com. The information provided herein regarding products, services/offers, benefits etc of Instappy are governed by the detailed terms and conditions accepted by you. The information provided herein above does not amount to a discount, offer or sponsor or advice as regards any products or services of Instappy or any of its group companies unless explicitly specified and is not intended to create any rights or obligations. Any reference to service levels in this document are indicative and should not be construed to refer to any commitment by Instappy and are subject to change at any time at the sole discretion of Pulp Strategy Technologies or its group companies as it case maybe. The use of any information set out herein is entirely at the recipients own risk and discretion. The "Instappy" logo is a trade mark and property of Pulp Strategy Technologies Pvt Ltd. Misuse of any intellectual property or any other content displayed herein is strictly prohibited. Images used herein are for the purposes of illustration only.
     </td>
    </tr>
    <tr>
        <td  bgcolor="ffcc00" height="30">
            <img src="'.$baseUrl.'/edm/images/Anyone-footer.png">
        </td>
    </tr>
</table>
</body>
</html>';
					$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'api_key' => $key,
						'subject' => $subject,
						'fromname' => 'Instappy',
						'from' => $formemail,
						'content' => $content,
						'recipients' => $cto
				)
			));
			$customerhead = curl_exec($curl);

			curl_close($curl);
	        } 

        		$response = array("result" => 'success', "msg" => '312');
				$Basearray = array("response" => $response);		
				$basejson = json_encode($Basearray);
				echo $basejson; 
	 			die;
        	}else{

        		$response = array("result" => 'error', "msg" => '314');
				$Basearray = array("response" => $response);		
				$basejson = json_encode($Basearray);
				echo $basejson; 
	 			die;
        	}	
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

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
