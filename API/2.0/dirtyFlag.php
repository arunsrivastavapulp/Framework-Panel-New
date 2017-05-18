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
$app->post('/dirty', 'dityFlag');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */
function authCheck($authToken, $device_id) {
    $dbCon = content_db();
    $auth_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token=:authtoken and u.device_id=:deviceid";
    $auth_queryExecution = $dbCon->prepare($auth_query);
    $auth_queryExecution->bindParam(":authtoken", $authToken, PDO::PARAM_STR);
    $auth_queryExecution->bindParam(":deviceid", $device_id, PDO::PARAM_STR);
    $auth_queryExecution->execute();
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
    return $result_auth;
}

function dityFlag() {
    date_default_timezone_set("Asia/kolkata");
    $dbCon = content_db();

    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id']) && isset($_POST['screen_data'])) {
        $apiVersion = '';
        $FrmVersion = '';
        $app_idString = $_POST['app_id'];
        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id'];
        $dirtyLog = $_POST['screen_data'];
        if (isset($_POST['api_version']) && isset($_POST['Framework_Version'])) {
            $apiVersion = $_POST['api_version'];
            $FrmVersion = $_POST['Framework_Version'];
        }
        $device_platform = 1;
    } else {
        $response = array("result" => 'error', "msg" => 'required parameter missing or empty');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }

    $authResult = authCheck($authToken, $device_id);

    if ($authResult == 0) {
        $response = array("result" => 'error', "msg" => '0');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
    } else {

        $arrayFlags = json_decode($dirtyLog);
        $final_results = array();

        $appQueryData = "select *  from app_data where app_id=:appid";
        $app_screenData = $dbCon->prepare($appQueryData);
        $app_screenData->bindParam(":appid", $app_idString, PDO::PARAM_STR);
        $app_screenData->execute();
        $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);

        if ($result_screenData != '') {
            $app_id = $result_screenData->id;
        } else {
            $app_id = 0;
        }

        if ($apiVersion == '' && $FrmVersion == '') {
            $sqlUserInsert = "update user_appdata set last_login=now(),last_active=now() where user_id IN (select id from users where device_id=:deviceid) and auth_token=:auth and app_id=:appid";
            $statementUserInsert = $dbCon->prepare($sqlUserInsert);

            $statementUserInsert->bindParam(":deviceid", $device_id, PDO::PARAM_STR);
            $statementUserInsert->bindParam(":auth", $authToken, PDO::PARAM_STR);
            $statementUserInsert->bindParam(":appid", $app_id, PDO::PARAM_INT);
        } else {
            $sqlUserInsert = "update user_appdata set latest_version=:version,api_version=:apiversion,last_login=now(),last_active=now() where user_id IN (select id from users where device_id=:deviceid) and auth_token=:auth and app_id=:appid";
            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
            $statementUserInsert->bindParam(":version", $FrmVersion, PDO::PARAM_STR);
            $statementUserInsert->bindParam(":apiversion", $apiVersion, PDO::PARAM_STR);
            $statementUserInsert->bindParam(":deviceid", $device_id, PDO::PARAM_STR);
            $statementUserInsert->bindParam(":auth", $authToken, PDO::PARAM_STR);
            $statementUserInsert->bindParam(":appid", $app_id, PDO::PARAM_INT);
        }
        $statementUserInsert->execute();
        $appValid = "SELECT ad.plan_expiry_date FROM app_data ad JOIN author  a ON ad.author_id=a.id WHERE ad.id=:appid ";
        $auth_appValid = $dbCon->prepare($appValid);
        $auth_appValid->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $auth_appValid->execute();
        $result_appValid = $auth_appValid->fetch(PDO::FETCH_OBJ);

        $getExpDate = $result_appValid->plan_expiry_date;
        $nowTime = date("Y-m-d");
        if (strtotime($getExpDate) >= strtotime($nowTime)) {
            $app_valid = "true";
        } else {
            $app_valid = "false";
        }

        foreach ($arrayFlags as $value) {
            $idScreen = $value->screen_id;
            $ScreenTime = $value->server_time;
            if ($idScreen == '-4') {
                $ScreenCheck_query = "select updated from app_data where id=:appid";
                $app_navigationExecution = $dbCon->prepare($ScreenCheck_query);
                $app_navigationExecution->bindParam(':appid', $app_id, PDO::PARAM_INT);
                $app_navigationExecution->execute();
                $result_navigation = $app_navigationExecution->fetchAll(PDO::FETCH_OBJ);
                if (count($result_navigation) != 0) {
                    $getTime = $result_navigation[0]->updated;
                    $differTime = strtotime($getTime) - strtotime($ScreenTime);
                    if ($differTime != 0) {
                        $id = array("screen_id" => $idScreen, 'server_time' => $getTime);
                        $final_results[] = $id;
                    }
                }
            } elseif ($idScreen == 1) {
                $dt = new DateTime($ScreenTime);
                $date = $dt->format('d');
                $differTime = date("d") - $date;
                if ($differTime != 0) {

                    $nowTime = date("Y-m-d H:m:s");
                    $sqlUserInsert = "update screen_title_id set server_time= :nowtime where id=1";
                    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                    $statementUserInsert->bindParam(':nowtime', $nowTime, PDO::PARAM_STR);
                    $statementUserInsert->execute();
                    $id = array("screen_id" => $idScreen, "server_time" => $nowTime);
                    $final_results[] = $id;
                } else {
                    $ScreenCheck_query = "select event_type,server_time from screen_title_id where id=:id;";
                    $app_navigationExecution = $dbCon->prepare($ScreenCheck_query);
                    $app_navigationExecution->bindParam(':id', $idScreen, PDO::PARAM_INT);
                    $app_navigationExecution->execute();
                    $result_navigation = $app_navigationExecution->fetchAll(PDO::FETCH_OBJ);
                    if (count($result_navigation) != 0) {
                        $getTime = $result_navigation[0]->server_time;
                        $eventtype = $result_navigation[0]->event_type;
                        if ($eventtype == 0) {
                            $differTime = strtotime($getTime) - strtotime($ScreenTime);
                            if ($differTime != 0) {
                                $id = array("screen_id" => $idScreen, 'server_time' => $getTime);
                                $final_results[] = $id;
                            }
                        } else {
                            $app_event_query = "SELECT  et.recurring_interval
 FROM app_event_rel ad LEFT JOIN event_data ar ON ad.event_data_id=ar.id LEFT JOIN event_type et ON ar.event_type_id=et.id
WHERE ad.screen_id=:screenid AND ad.app_id=:appid AND DATE(start_datetime)>=CURDATE() order by ar.start_datetime ASC
LIMIT 1
";
                            $app_eventExecution = $dbCon->prepare($app_event_query);
                            $app_eventExecution->bindParam(':screenid', $idScreen, PDO::PARAM_INT);
                            $app_eventExecution->bindParam(':appid', $app_id, PDO::PARAM_INT);
                            $app_eventExecution->execute();
                            $result_event = $app_eventExecution->fetch(PDO::FETCH_OBJ);
                            $interval = $result_event->recurring_interval;
                            if ($interval == 1) {
                                $dt = new DateTime($ScreenTime);
                                $date = $dt->format('d');
                                $differTime = date("d") - $date;
                                if ($differTime != 0) {
                                    $id = array("screen_id" => $idScreen, 'server_time' => date("Y-m-d H:m:s"));
                                    $final_results[] = $id;
                                }
                            } elseif ($interval == 2) {
                                $dt = new DateTime($ScreenTime);
                                $month = $dt->format('m');
                                $differTime = date("m") - $month;
                                if ($differTime != 0) {
                                    $id = array("screen_id" => $idScreen, 'server_time' => date("Y-m-d H:m:s"));
                                    $final_results[] = $id;
                                }
                            }
                        }
                    }
                }
            } else {
                $ScreenCheck_query = "select event_type,server_time from screen_title_id where id=:idscreen;";
                $app_navigationExecution = $dbCon->prepare($ScreenCheck_query);
                $app_navigationExecution->bindParam(':idscreen', $idScreen, PDO::PARAM_INT);
                $app_navigationExecution->execute();
                $result_navigation = $app_navigationExecution->fetchAll(PDO::FETCH_OBJ);
                if (count($result_navigation) != 0) {
                    $getTime = $result_navigation[0]->server_time;
                    $eventtype = $result_navigation[0]->event_type;
                    if ($eventtype == 0) {
                        $differTime = strtotime($getTime) - strtotime($ScreenTime);
                        if ($differTime != 0) {
                            $id = array("screen_id" => $idScreen, 'server_time' => $getTime);
                            $final_results[] = $id;
                        }
                    } else {
						$result_event='';
                        $app_event_query = "SELECT  et.recurring_interval
 FROM app_event_rel ad LEFT JOIN event_data ar ON ad.event_data_id=ar.id LEFT JOIN event_type et ON ar.event_type_id=et.id
WHERE ad.screen_id=:idscreen AND ad.app_id=:appid AND DATE(start_datetime)>=CURDATE() order by ar.start_datetime ASC
LIMIT 1
";
                        $app_eventExecution = $dbCon->prepare($app_event_query);
                        $app_eventExecution->bindParam(':idscreen', $idScreen, PDO::PARAM_INT);
                        $app_eventExecution->bindParam(':appid', $app_id, PDO::PARAM_INT);
                        $app_eventExecution->execute();
                        $result_event = $app_eventExecution->fetch(PDO::FETCH_OBJ);
					if(empty($result_event))
					{
					$interval = 0;
					}
					else{
				$interval = $result_event->recurring_interval;		
					}
					
                        
                        if ($interval == 1) {
                            $dt = new DateTime($ScreenTime);
                            $date = $dt->format('d');
                            $differTime = date("d") - $date;
                            if ($differTime != 0) {
                                $id = array("screen_id" => $idScreen, 'server_time' => date("Y-m-d H:m:s"));
                                $final_results[] = $id;
                            }
                        } elseif ($interval == 2) {
                            $dt = new DateTime($ScreenTime);
                            $month = $dt->format('m');
                            $differTime = date("m") - $month;
                            if ($differTime != 0) {
                                $id = array("screen_id" => $idScreen, 'server_time' => date("Y-m-d H:m:s"));
                                $final_results[] = $id;
                            }
                        }
						else{
							$id = array("screen_id" => $idScreen, 'server_time' => date("Y-m-d H:m:s"));
                                $final_results[] = $id;
						}
                    }
                }
            }
        }

        $response = array("result" => 'success', "msg" => '', "app_valid" => $app_valid);
        $Basearray = array("response" => $response, "dirty_screen" => $final_results);
        echo json_encode($Basearray);
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
