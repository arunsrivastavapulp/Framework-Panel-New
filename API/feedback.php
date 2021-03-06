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
require 'db.php';
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
$app->post('/feedback', 'feedback');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */
function authCheck($authToken, $device_id) {
    $dbCon = getConnection();
    //  $auth_query = "select id from user_appdata where user_name=$device_id and auth_token='" . $authToken . "'";
    $auth_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token='" . $authToken . "' and u.device_id='" . $device_id . "'";
    $auth_queryExecution = $dbCon->query($auth_query);
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
    return $result_auth;
}

function feedback() {
$screen_id='';
    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id'])) {
        $app_idString = $_POST['app_id'];
        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id'];
        $full_name = $_POST['full_name'];
        $title = $_POST['title'];
        $message = $_POST['message'];
    } else {
        $response = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
    $dbCon = getConnection();	
    $result_properties = '';
    $data = '';
    $screen = '';
    $screenNavigation = '';
    $screenCompNavigation = '';
    $componentResultSet = array();
    $isDrawerEnable = 0;
    $countofComponemt = 0;
 
    try {
        $authResult = authCheck($authToken, $device_id);     
        if ($authResult == 0 || $device_id == '') {
            $response = array("result" => 'error', "msg" => '0');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
			die;
        } else {
 $app_name ='';		
		   $appQueryData = "select *  from app_data where app_id='" . $app_idString . "'";
            $app_screenData = $dbCon->query($appQueryData);
            $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);	
			if($result_screenData != '')
			{
             $app_id = $result_screenData->id;
			  $app_name = $result_screenData->summary;
			}
			else{
			 $app_id =0;
			}		
            $response = array("result" => 'success', "msg" => '200');
           /*  $sqlUserInsert = "INSERT INTO report_abuse (`app_id`,`user_id`,`message`,`email`,`screen_id`,`content_type`) VALUES ('" . $app_id . "','" . $device_id . "','" . $message . "','" . $ra_email . "','" . $screen_id . "','" . $content . "')";
     
    
            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
            $statementUserInsert->execute(); */
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
			$chtmlcontent="Appname : $app_name<br /> Name:  $full_name <br />Message: $message";
			
			 $csubject = "Feedback for $app_name";
$app_mail = "select * from screen_title_id where app_id='" . $app_id . "' and title like '%feedback%' limit 1";
$appQuerymail = $dbCon->query($app_mail);
$rowmailFetch = $appQuerymail->fetch(PDO::FETCH_ASSOC);										
$cto  = $rowmailFetch['others'];
if($cto != '' || $cto != null)
{
			// $cto = 'legal@instappy.com';
		//	$cto = 'hemant@pulpstrategy.com';
            $cformemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'api_key' => $key,
                    'subject' => $csubject,
                    'fromname' => 'Instappy',
                    'from' => $cformemail,
                    'content' => $chtmlcontent,
                    'recipients' => $cto
                )
            ));
            $customerhead = curl_exec($curl);

            curl_close($curl);

}
			
			
        }
    } catch (Exception $e) {
        $response = array("result" => 'error', "msg" => '-11');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
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
