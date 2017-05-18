<?php

require 'Slim/Slim.php';
require 'includes/db.php';
//AWS access info


\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();
$app->post('/settingsdetail', 'settingsdetail');

function authCheck($authToken, $device_id) {
    $dbCon = content_db();
    //  $auth_query = "select id from user_appdata where user_name=$device_id and auth_token='" . $authToken . "'";
    $auth_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token=:authtoken and u.device_id=:deviceid";
    $auth_queryExecution = $dbCon->prepare($auth_query);
    $auth_queryExecution->bindParam(':authtoken', $authToken, PDO::PARAM_STR);
    $auth_queryExecution->bindParam(':deviceid', $device_id, PDO::PARAM_STR);
    $auth_queryExecution->execute();
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
    return $result_auth;
}

function settingsdetail() {
    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id'])) {
        $dbCon = content_db();
        $app_id = $_POST['app_id'];
        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id'];
        $is_feedback = 1;
        $tn_app_id = '';
        $screen_type = 16;
        $is_visible = 1;
        $is_deleted = 0;
        $is_tnc = 0;
        $authResult = authCheck($authToken, $device_id);
        $tncApp = 0;

        if ($authResult == 0 || $device_id == '') {
            $response = array("result" => 'error', "msg" => 'Authentication Error');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
        } else {
            $appQueryData = "select *  from app_data where app_id=:appid";
            $app_userData = $dbCon->prepare($appQueryData);
            $app_userData->bindParam(':appid', $app_id, PDO::PARAM_STR);
            $app_userData->execute();
            $result_userData = $app_userData->fetch(PDO::FETCH_OBJ);
            $rowCount = $app_userData->rowCount(PDO::FETCH_NUM);
            if ($rowCount > 0) {
                $app_id = $result_userData->id;
                if ($result_userData->type_app == "1") {
                    if ($result_userData->jump_to_app_id != '' || $result_userData->jump_to_app_id != NULL) {
                        $tn_app_id = $result_userData->jump_to_app_id;
                        $jumpto = $result_userData->jump_to;
                        if($tn_app_id !=0 && $jumpto == 1)
                        {                  
                    $is_tnc = 0;
                    $tnc_link = '';
                    $tncApp = 1;
                    $appQueryData = "Select * from app_catalogue_attr where app_id=:appid";
                    $app_tncData = $dbCon->prepare($appQueryData);
                    $app_tncData->bindParam(':appid', $tn_app_id, PDO::PARAM_STR);
                    $app_tncData->execute();
                    $result_settingData = $app_tncData->fetch(PDO::FETCH_OBJ);
                    $rowCount = $app_tncData->rowCount(PDO::FETCH_NUM);

                    if ($rowCount > 0) {
                        $is_tnc = $result_settingData->is_tnc;
                        $tnc_link = $result_settingData->tnc_link;
                       
                    }
                        }
                    } else {
                        $tn_app_id = $app_id;
                    }

                    $appQueryData2 = "select *  from screen_title_id where app_id=:appid and screen_type=:screen_type and is_visible=:is_visible and deleted=:is_deleted";
                    $app_userData2 = $dbCon->prepare($appQueryData2);
                    $app_userData2->bindParam(':appid', $app_id, PDO::PARAM_STR);
                    $app_userData2->bindParam(':screen_type', $screen_type, PDO::PARAM_STR);
                    $app_userData2->bindParam(':is_visible', $is_visible, PDO::PARAM_STR);
                    $app_userData2->bindParam(':is_deleted', $is_deleted, PDO::PARAM_STR);
                    $app_userData2->execute();
                    $result_userData2 = $app_userData2->fetch(PDO::FETCH_OBJ);
                    $rowCount2 = $app_userData2->rowCount(PDO::FETCH_NUM);
                    if ($rowCount2 > 0) {
                      //  $is_feedback = 1;
                    }
                } else {
                    $tn_app_id = $app_id;
                    $is_tnc = 0;
                    $tnc_link = '';
                    $tncApp = 1;
                    $appQueryData = "Select * from app_catalogue_attr where app_id=:appid";
                    $app_tncData = $dbCon->prepare($appQueryData);
                    $app_tncData->bindParam(':appid', $tn_app_id, PDO::PARAM_STR);
                    $app_tncData->execute();
                    $result_settingData = $app_tncData->fetch(PDO::FETCH_OBJ);
                    $rowCount = $app_tncData->rowCount(PDO::FETCH_NUM);

                    if ($rowCount > 0) {
                        $is_tnc = $result_settingData->is_tnc;
                        $tnc_link = $result_settingData->tnc_link;
                    //    $is_feedback = $result_settingData->is_feedback;
                    }
                }
            } else {
                $response = array("result" => 'error', "msg" => 'No such App found');
                $Basearray = array("response" => $response);
                $basejson = json_encode($Basearray);
                echo $basejson;
                die;
            }

            $appQueryData = "Select * from setting_attr";
            $app_settingData = $dbCon->prepare($appQueryData);
            $app_settingData->execute();
            $result_settingData = $app_settingData->fetchAll(PDO::FETCH_OBJ);
            $rowCount = $app_settingData->rowCount(PDO::FETCH_NUM);
            if ($rowCount > 0) {
               
                foreach ($result_settingData as $settingData) {
                    if ($settingData->screen_type == 16 && $is_feedback == 1) {
                        $arraycomp_elements["id"] = $settingData->id;
                        $arraycomp_elements["itemName"] = $settingData->name;
                        $arraycomp_elements["type"] = $settingData->type;
                        $arraycomp_elements["screen_type"] = $settingData->screen_type;
                        $arraycomp_elements["icomoonId"] = $settingData->icomoonId;
                        if ($is_tnc == "1" && $settingData->screen_type == "23") {
                            $is_data = true;
                        } else {
                            $is_data = false;
                        }
                        $arraycomp_elements["isdata"] = $is_data;
                        if ($is_tnc == "1" && $settingData->screen_type == "23") {
                            $is_data = true;
                            $arraycomp_elements["data"] = array("url" => $tnc_link);
                        } else {
                            // $arr[]=array('0');
                            $is_data = false;
                            $arraycomp_elements["data"] = (object) [];
                        }
                        $componentResultSet[] = $arraycomp_elements;
                    } 
                    elseif ($settingData->screen_type != 16 && $settingData->screen_type != 23) {
                        $arraycomp_elements["id"] = $settingData->id;
                        $arraycomp_elements["itemName"] = $settingData->name;
                        $arraycomp_elements["type"] = $settingData->type;
                        $arraycomp_elements["screen_type"] = $settingData->screen_type;
                        $arraycomp_elements["icomoonId"] = $settingData->icomoonId;
                        if ($is_tnc == "1" && $settingData->screen_type == "23") {
                            $is_data = true;
                        } else {
                            $is_data = false;
                        }
                        $arraycomp_elements["isdata"] = $is_data;
                        if ($is_tnc == "1" && $settingData->screen_type == "23") {
                            $is_data = true;
                            $arraycomp_elements["data"] = array("url" => $tnc_link);
                        } else {
                            // $arr[]=array('0');
                            $is_data = false;
                            $arraycomp_elements["data"] = (object) [];
                        }
                        $componentResultSet[] = $arraycomp_elements;
                    } 
                    elseif ($settingData->screen_type == 23 && $tncApp == 1) {
                        $arraycomp_elements["id"] = $settingData->id;
                        $arraycomp_elements["itemName"] = $settingData->name;
                        $arraycomp_elements["type"] = $settingData->type;
                        $arraycomp_elements["screen_type"] = $settingData->screen_type;
                        $arraycomp_elements["icomoonId"] = $settingData->icomoonId;
                        if ($is_tnc == "1" && $settingData->screen_type == "23") {
                            $is_data = true;
                        } else {
                            $is_data = false;
                        }
                        $arraycomp_elements["isdata"] = $is_data;
                        if ($is_tnc == "1" && $settingData->screen_type == "23") {
                            $is_data = true;
                            $arraycomp_elements["data"] = array("url" => $tnc_link);
                        } else {
                            // $arr[]=array('0');
                            $is_data = false;
                            $arraycomp_elements["data"] = (object) [];
                        }
                        $componentResultSet[] = $arraycomp_elements;
                    }
                }

                $setting = array('count' => $rowCount, 'items' => $componentResultSet);

                $response = array("result" => 'success', "msg" => 'Yes');
                $Basearray = array("response" => $response, "Settings" => $setting);
                $basejson = json_encode($Basearray);
                echo $basejson;
            } else {
                $response = array("result" => 'error', "msg" => 'No such user found');
                $Basearray = array("response" => $response);
                $basejson = json_encode($Basearray);
                echo $basejson;
            }
        }
    } else {
        $responce = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
}

$app->run();
