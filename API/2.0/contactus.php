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

$app = new \Slim\Slim();
$app->post('/contact', 'contact');

function authCheck($authToken, $device_id) {
    $dbCon = content_db();
    $auth_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token=:authtoken and u.device_id=:deviceid";
    $auth_queryExecution = $dbCon->prepare($auth_query);
    $auth_queryExecution->bindParam(':authtoken', $authToken, PDO::PARAM_STR);
    $auth_queryExecution->bindParam(':deviceid', $device_id, PDO::PARAM_STR);
    $auth_queryExecution->execute();
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
    return $result_auth;
}

function contact() {
    $screen_id = '';
    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id']) && isset($_POST['email'])) {
        $app_idString = $_POST['app_id'];
        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id'];
        $email = $_POST['email'];

        if (isset($_POST['full_name'])) {
            $full_name = $_POST['full_name'];
        } else {
            $full_name = '';
        }

        if (isset($_POST['phone'])) {
            $phone = $_POST['phone'];
        } else {
            $phone = '';
        }



        if (isset($_POST['title'])) {
            $title = $_POST['title'];
        } else {
            $title = '';
        }

        if (isset($_POST['message'])) {
            $message = $_POST['message'];
        } else {
            $message = '';
        }
    } else {
        $response = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
    $dbCon = content_db();
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
            $app_name = '';
            $cto = '';
            $appQueryData = "select *  from app_data where app_id=:appid";
            $auth_queryExecution = $dbCon->prepare($appQueryData);
            $auth_queryExecution->bindParam(':appid', $app_idString, PDO::PARAM_STR);
            $auth_queryExecution->execute();
            $result_screenData = $auth_queryExecution->fetch(PDO::FETCH_OBJ);           
		   if ($result_screenData != '') {
                $app_id = $result_screenData->id;
                $app_name = $result_screenData->summary;
				 $app_type= $result_screenData->type_app; 
			  if ($app_type == '1') {
					$contentAppId = $app_id;
					$contentAppIdString = $app_idString;
					  $cto = $result_screenData->contactus_email;
				} else {
					$retailAppId = $app_id;
					$retailAppType = $app_type;
					$retailAppIdString = $app_idString;
					$appReatldata = "select *  from app_catalogue_attr where app_id=:appid";
					$retail_screendata = $dbCon->prepare($appReatldata);
					$retail_screendata->bindParam(':appid', $app_id, PDO::PARAM_STR);
					$retail_screendata->execute();
					$result_retailData = $retail_screendata->fetch(PDO::FETCH_OBJ);
					if ($result_retailData != '') {
						$tnc = $result_retailData->tnc_link;
						$cto = $result_retailData->contactus_email;
					 }
				}
			} else {
                $app_id = 0;
            }
			
            $response = array("result" => 'success', "msg" => '200');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
            $chtmlcontent = "Appname : $app_name<br /> Name:  $full_name <br />Email: $email <br />Phone: $phone <br />Message: $message";

            $csubject = "Contact Us Details for $app_name";

            if ($cto != '' || $cto != null) {
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
