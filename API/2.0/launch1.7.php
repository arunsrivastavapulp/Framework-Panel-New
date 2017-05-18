<?php

require 'Slim/Slim.php';
require 'includes/db.php';
require_once('launchData1.7.php');
\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();
$app->post('/launch', 'launch');
$app->run();

/* Function to check if user is autherized for data access */

function authCheck($authToken, $device_id, $app_id) {
    $dbCon = content_db();
    $auth_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token=:authtoken and u.device_id=:deviceid and uad.app_id=:appid";
    $auth_queryExecution = $dbCon->prepare($auth_query);
    $auth_queryExecution->bindParam(':authtoken', $authToken, PDO::PARAM_STR);
    $auth_queryExecution->bindParam(':deviceid', $device_id, PDO::PARAM_STR);
    $auth_queryExecution->bindParam(':appid', $app_id, PDO::PARAM_STR);
    $auth_queryExecution->execute();
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
    return $result_auth;
}

function getMainCat($contentAppId) {
    $categoryResult = array();
    $dbCon = content_db();
    $categoryQuery = "SELECT * FROM navigation_category WHERE app_id='" . $contentAppId . "' and deleted=0";
    $categoryQueryRun = $dbCon->query($categoryQuery);
    $categoryResult = $categoryQueryRun->fetchAll(PDO::FETCH_ASSOC);
    return $categoryResult;
}

function getnavigationValue($id = null, $app_id) {
    $childValues = array();
    $dbCon = content_db();
    $screenNavarray = array();

    if ($id == null) {
        $app_screen_query = "select * from screen_title_id where app_id=:appid and deleted=0 and  is_visible=1 and (nav_cat_id IS NULL OR nav_cat_id = 0) and background_type != 1001 and screen_type not IN(19,14,16) order by  seq=0,background_type=0,seq,CAST(background_type AS UNSIGNED) ASC";
        $app_screenqueryExecution = $dbCon->prepare($app_screen_query);
        $app_screenqueryExecution->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $app_screenqueryExecution->execute();
        $level = "0";
    } else {
        $app_screen_query = "select * from screen_title_id where app_id=:appid and deleted=0 and nav_cat_id=:nav and background_type != 1001 ORDER BY  sub_seq ASC";
        $app_screenqueryExecution = $dbCon->prepare($app_screen_query);
        $app_screenqueryExecution->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $app_screenqueryExecution->bindParam(':nav', $id, PDO::PARAM_INT);
        $app_screenqueryExecution->execute();
        $level = "1";
    }
    $result_screen = $app_screenqueryExecution->fetchAll(PDO::FETCH_OBJ);

    if (!empty($result_screen)) {
        foreach ($result_screen as $gcatvalue) {
            $screenNavarray[] = array("seq" => $gcatvalue->seq, "name" => $gcatvalue->title, "icon" => $gcatvalue->image_url, "is_child" => "0", "level" => "$level", "link_to_screenid" => $gcatvalue->id, "catalogue_component" => array("screen_type" => "0", "category_id" => "0", "app_type" => "1", "catalogue_app_id" => ""), "cat_array" => $childValues);
        }
    }
    return $screenNavarray;
}

function launch() {
    $dbCon = content_db();
    $Retaildb = retail_db();
    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id']) && $_POST['app_id'] != '') {
        $app_idString = $_POST['app_id'];
        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id'];
        /* variable desclaration starts here */
        $Apiversion = 1;
        $app_version = '';
        $first_launch = '1';
        $appcall = '';
        $app_id = '0';
        $app_type = '1';
        $jump_to = '0';
        $jump_to_app_id = '0';
        $Scndapp_id = '0';
        $Scndapp_type = '0';
        $lastlogin = '';
        $P_backgroundcolor = '#FFFFFF';
        $S_backgroundcolor = '#FFFFFF';
        $P_backgroundimage = '';
        $analytics = '';
        $screenNavigation = array();
        $Retaildata = array();
        $contentAppId = '';
        $retailAppId = '';
        $contentAppIdString = '';
        $retailAppIdString = '';
        $notification = '';
        $contentAppType = '1';
        $retailAppType = '';
        $isContact = 0;
        $contactusemail = '';
        $contactusphone = '';
        $feedbackemail = '';
        $feedbackphone = '';
        $tnc = "";
        $order_email = "";
        $order_phone = "";
        $order_logo = "";
        /* variable desclaration ends here */

        if (isset($_POST['first_launch']) && $_POST['first_launch'] != '') {
            $first_launch = $_POST['first_launch'];
        }

        if (isset($_POST['api_version']) && $_POST['api_version'] != '') {
            $Apiversion = $_POST['api_version'];
        }

        if (isset($_POST['app_call'])) {
            $appcall = $_POST['app_call'];
        }

        if (isset($_POST['app_version'])) {
            $app_version = $_POST['app_version'];
        }

        $restrictedFiled = array();
        if ($Apiversion < '1.6') {
            $restrictedFiled = array('999');
        }
    } else {
        $response = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }

    $appQueryData = "select *  from app_data where app_id=:appid";
    $app_screenData = $dbCon->prepare($appQueryData);
    $app_screenData->bindParam(':appid', $app_idString, PDO::PARAM_STR);
    $app_screenData->execute();
    $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);

    if ($result_screenData != '') {
        $app_id = $result_screenData->id;
        $app_idString = $result_screenData->app_id;
        $app_type = $result_screenData->type_app;
        $jump_to = $result_screenData->jump_to;
        $jump_to_app_id = $result_screenData->jump_to_app_id;
        if ($app_type != 1) {
            $appReatldata = "select *  from app_catalogue_attr where app_id=:appid";
            $retail_screendata = $dbCon->prepare($appReatldata);
            $retail_screendata->bindParam(':appid', $app_id, PDO::PARAM_STR);
            $retail_screendata->execute();
            $result_retailData = $retail_screendata->fetch(PDO::FETCH_OBJ);
            if ($result_retailData != '') {
                $tnc = $result_retailData->tnc_link;
                $contactusemail = $result_retailData->contactus_email;
                $contact_phone = $result_retailData->contactus_no;
                $feedback_email = $result_retailData->feedback_email;
                $feedback_phone = $result_retailData->feedback_no;
                $order_email = $result_retailData->orderconfirm_email;
                $order_phone = $result_retailData->orderconfirm_no;
                $order_logo = $result_retailData->logo_link;
                $isContact = $result_retailData->is_contactus;

                $P_backgroundcolor = $result_retailData->background_color;

                $contactusemail = $result_retailData->contactus_email;
                $contactusphone = $result_retailData->contactus_no;
                $feedbackemail = $result_retailData->feedback_email;
            }
        } else {
            $P_backgroundcolor = $result_screenData->background_color;
            $P_backgroundimage = $result_screenData->background_image;
            $contactusemail = $result_screenData->contactus_email;
            $contactusphone = $result_screenData->contactus_phone;
            $feedbackemail = $result_screenData->feedback_email;
        }

        $analyticsCode = $result_screenData->analytics_code;
        $P_backgroundimage = $result_screenData->background_image;
        $getExpDate = $result_screenData->plan_expiry_date;
        $nowTime = date("Y-m-d");
        if (strtotime($getExpDate) >= strtotime($nowTime)) {
            $app_valid = "true";
        } else {
            $app_valid = "false";
        }
        if ($analyticsCode != '') {
            $anlyticsData = "select *  from app_analytics_mapping where id=:code";
            $anlyticsData = $dbCon->prepare($anlyticsData);
            $anlyticsData->bindParam(':code', $analyticsCode, PDO::PARAM_STR);
            $anlyticsData->execute();
            $anlyticsContentData = $anlyticsData->fetch(PDO::FETCH_OBJ);
            if ($anlyticsContentData != '')
                $analytics = $anlyticsContentData->analytics_id;
        }
    }


    $authResult = authCheck($authToken, $device_id, $app_id);

    if ($authResult == 0) {
        $response = array();
        $response = array("result" => 'error', "msg" => 'authentication failed');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
    if ($app_type == '1') {
        $contentAppId = $app_id;
        $contentAppIdString = $app_idString;
    } else {
        $retailAppId = $app_id;
        $retailAppType = $app_type;
        $retailAppIdString = $app_idString;
    }

    $Scndapp_idString = '';

    if ($jump_to != '0') {
        $ScndappQueryData = "select *  from app_data where id=:appid";
        $Scndapp_screenData = $dbCon->prepare($ScndappQueryData);
        $Scndapp_screenData->bindParam(':appid', $jump_to_app_id, PDO::PARAM_STR);
        $Scndapp_screenData->execute();
        $Scndresult_screenData = $Scndapp_screenData->fetch(PDO::FETCH_OBJ);
 
        if ($Scndresult_screenData != '') {
            $Scndapp_id = $Scndresult_screenData->id;
            $Scndapp_idString = $Scndresult_screenData->app_id;
            $Scndapp_type = $Scndresult_screenData->type_app;
            $S_backgroundcolor = $Scndresult_screenData->background_color;
          

            if ($Scndapp_type == '1') {
                $contentAppId = $Scndapp_id;
                $contentAppIdString = $Scndapp_idString;
                $contentAppType = $Scndapp_type;
            } else {
                $appReatldata = "select *  from app_catalogue_attr where app_id=:appid";
                $retail_screendata = $dbCon->prepare($appReatldata);
                $retail_screendata->bindParam(':appid', $jump_to_app_id, PDO::PARAM_STR);
                $retail_screendata->execute();
             
                $result_retailData = $retail_screendata->fetch(PDO::FETCH_OBJ);
                   if ($result_retailData != '') {
                $S_backgroundcolor = $result_retailData->background_color;
                }
                $retailAppId = $Scndapp_id;
                $retailAppIdString = $Scndapp_idString;
                $retailAppType = $Scndapp_type;
                
            }
        }
    }

    $cf = new LaunchData();
    $data = array();
    $data['app_id'] = $retailAppIdString;
    $data['app_type'] = $retailAppType;
    $data['device_id'] = isset($_POST['device_id']) ? $_POST['device_id'] : '';
    $data['platform'] = isset($_POST['platform']) ? $_POST['platform'] : '';
    $data['app_version'] = isset($_POST['app_version']) ? $_POST['app_version'] : '';
    $data['api_version'] = isset($_POST['api_version']) ? $_POST['api_version'] : '';
    $data['first_launch'] = isset($_POST['first_launch']) ? $_POST['first_launch'] : '';

    $RetaildataAll = $cf->getCatalogLaunchData($data);
    if (!empty($RetaildataAll['result'])) {
        $Retaildata = $RetaildataAll['result'];
        $retailNav = $RetaildataAll['navigation'];
    } else {
        $Retaildata = (object) [];
        
        $retailNav = array();
    }
    /* Theme data  Query */
    $themeQuery = "SELECT *  FROM themes_app_rel where app_id=:appid limit 1";
    $themeQueryExecution = $dbCon->prepare($themeQuery);
    $themeQueryExecution->bindParam(':appid', $app_id, PDO::PARAM_INT);
    $themeQueryExecution->execute();
    $themeResult = $themeQueryExecution->fetch(PDO::FETCH_OBJ);
    if (empty($themeResult)) {
        $themeResult = (object) [];
    }

    // Get screens which have categories
    $navcatarray = array();
    $childValues = array();

    if ($app_type != '1') {
        $navcatarray[] = array("name" => "Home", "icon" => "", "is_child" => "0", "level" => "0", "link_to_screenid" => "-1", "catalogue_component" => array("screen_type" => "1", "category_id" => "", "app_type" => "$app_type", "catalogue_app_id" => "$app_idString"), "cat_array" => $childValues);
    }
    $getcat = getMainCat($contentAppId);

    if (!empty($getcat)) {
        foreach ($getcat as $gcatvalue) {
            $childValues = getnavigationValue($gcatvalue['id'], $contentAppId);
            $navcatarray[] = array("name" => $gcatvalue['cat_name'], "icon" => $gcatvalue['icon'], "is_child" => "1", "level" => "0", "link_to_screenid" => "-1", "catalogue_component" => array("screen_type" => "", "category_id" => "", "app_type" => "", "catalogue_app_id" => ""), "cat_array" => $childValues);
        }
    }

    $restnavVal = getnavigationValue(null, $app_id);

    $navigationContent = array();
    if (!empty($navcatarray)) {
        if (!empty($restnavVal)) {
            $navigationContent = array_merge($navcatarray, $restnavVal);

            $price = array();

            foreach ($navigationContent as $key => $row) {
                if (isset($row['seq'])) {
                    $price[$key] = $row['seq'];
                } elseif (isset($row['cat_array'][0]['seq'])) {
                    $price[$key] = $row['cat_array'][0]['seq'];
                } else {
                    $price[$key] = '888';
                }
            }
            array_multisort($price, SORT_ASC, $navigationContent);
        } else {
            $navigationContent = $navcatarray;
        }
    } else {
        if (!empty($restnavVal)) {
            $navigationContent = $restnavVal;
        }
    }

    $navigation = array();

    if (!empty($navigationContent)) {
        if (!empty($retailNav)) {
            $navigation = array_merge($navigationContent, $retailNav);
        } else {
            $navigation = $navigationContent;
        }
    } else {
        if (!empty($retailNav)) {
            $navigation = array_merge($navigationContent, $retailNav);
        }
    }

    /* CODE FOR CONTACT US */
    $contactusarray = array();
    $app_screen_query = "select * from screen_title_id where app_id=:appid and screen_type = 19 and deleted=0 and  is_visible=1 ";
    $app_screenqueryExecution = $dbCon->prepare($app_screen_query);
    $app_screenqueryExecution->bindParam(':appid', $contentAppId, PDO::PARAM_INT);
    $app_screenqueryExecution->execute();
    $result_screen = $app_screenqueryExecution->fetchAll(PDO::FETCH_OBJ);
    $countofscreen = count($result_screen);

    if ($countofscreen != 0) {

        $contactusarray[] = array("name" => 'Contact Us', "icon" => "e023", "is_child" => "0", "level" => "0", "link_to_screenid" => "-1", "local_screen_type" => "1", "catalogue_component" => array("screen_type" => "", "category_id" => "", "app_type" => "", "catalogue_app_id" => ""), "cat_array" => $childValues);
    } elseif ($isContact != "0") {
        $contactusarray[] = array("name" => 'Contact Us', "icon" => "e023", "is_child" => "0", "level" => "0", "link_to_screenid" => "-1", "local_screen_type" => "1", "catalogue_component" => array("screen_type" => "", "category_id" => "", "app_type" => "", "catalogue_app_id" => ""), "cat_array" => $childValues);
    }

    if (!empty($contactusarray)) {
        if (!empty($navigation)) {
            $navigation = array_merge($navigation, $contactusarray);
        } else {
            $navigation = $contactusarray;
        }
    }

    $app_screen_query = "select * from screen_title_id where app_id=:appid and deleted=0 order by  seq=0,background_type=0,seq,CAST(background_type AS UNSIGNED) ASC";
    $app_screenqueryExecution = $dbCon->prepare($app_screen_query);
    $app_screenqueryExecution->bindParam(':appid', $contentAppId, PDO::PARAM_INT);
    $app_screenqueryExecution->execute();
    $result_screen = $app_screenqueryExecution->fetchAll(PDO::FETCH_OBJ);
    $countOfScreen = count($result_screen);
    $objScreen = json_encode($result_screen);

    $screenId = 0;
    $mStore = 0;
    $adindex = 1;
    $storefont = '12';
    $storefontcolor = '#FFFFFF';
    $storeTypeFace = 'Arial';
    $adResultScreen = array();
    $adResult = array();
    $catnamelist = array();
    if ($countOfScreen != 0) {

        foreach ($result_screen as $valueScreen) {
            $keywords[] = $valueScreen->keywords;
            if ($valueScreen->nav_cat_id == null) {
                
            } else {
                $getcat = getMainCat($valueScreen->nav_cat_id, $contentAppId);
            }

            $keywords[] = $valueScreen->keywords;
            if ($valueScreen->screen_type == 15) {
                $screenback = $valueScreen->background_image;
            } else {
                $screenback = "";
            }
            $screenNavigation[] = array("screen_id" => $valueScreen->id, "parent_id" => $valueScreen->parent_id, "screen_type" => $valueScreen->screen_type, "tag" => '', "dirtyflag" => '', "server_time" => $valueScreen->server_time, "screen_properties" => array("title" => $valueScreen->title, "popup_flag" => $valueScreen->popup_flag, "background_color" => $valueScreen->background_color, "background_image" => $screenback, "url" => $valueScreen->image_url), "AI_keyword" => array($valueScreen->keywords), "AI_description" => "app description");

            if ($adindex == 1) {
                $storefont = $valueScreen->font_size;
                $storefontcolor = $valueScreen->font_color;
                $storeTypeFace = $valueScreen->font_typeface;
            }
            if ($adindex % 3 == 0) {
                $adResultScreen[] = array("screen_id" => $valueScreen->id);
            }

            if ($valueScreen->screen_type == 16) {
                $mStore = 1;
            }
            $adindex++;
        }
    }
    //Code For Notification //
    $adQuery = "SELECT  notification FROM  user_appdata WHERE  app_id = :appid and user_device_id=:userdeviceid";
    $NotificationExecution = $dbCon->prepare($adQuery);
    $NotificationExecution->bindParam(':appid', $app_id, PDO::PARAM_INT);
    $NotificationExecution->bindParam(':userdeviceid', $device_id, PDO::PARAM_INT);
    $NotificationExecution->execute();
    $NotificationResult1 = $NotificationExecution->fetch(PDO::FETCH_OBJ);
    $NotificationCount = $NotificationExecution->rowCount(PDO::FETCH_NUM);
    if ($NotificationCount > 0) {
        $notification = $NotificationResult1->notification;
    }
    //Code For Notification End//
    // Query for ad Data                
    $adQuery = "SELECT  aad.app_id,ads.servername,ad.published_id,ad.ad_serverid,ad.unit_id,aad.id AS adserver_detail_id,
		apdt.plan_id,pls.plan_type FROM  app_adserver_details aad  LEFT JOIN adserver_details ad ON aad.adserver_details_id = ad.id
			LEFT JOIN adserver ads ON ad.ad_serverid = ads.id LEFT JOIN app_data apdt on aad.app_id=apdt.id left join plans pls
		on apdt.plan_id=pls.id WHERE  aad.app_id = :appid LIMIT 0 , 1";
    $adQueryExecution = $dbCon->prepare($adQuery);
    $adQueryExecution->bindParam(':appid', $app_id, PDO::PARAM_INT);
    $adQueryExecution->execute();
    $adResult1 = $adQueryExecution->fetch(PDO::FETCH_OBJ);
    $resultsCountad = $adQueryExecution->rowCount(PDO::FETCH_NUM);
    if ($resultsCountad != 0) {
        //  $adResult[]=$adResult1->app_id;
        if ($adResult1->plan_type != 5 && $appcall != 'wizard') {
            $adResult[] = array("app_id" => $adResult1->app_id, "servername" => $adResult1->servername, "published_id" => $adResult1->published_id, "ad_serverid" => $adResult1->ad_serverid, "unit_id" => $adResult1->unit_id, "screens" => $adResultScreen);
        }
    }
    /* common properties for retail and content apps */
      if($S_backgroundcolor == null)
            {
                $S_backgroundcolor='';
            }
    $commonProperties = array("primary_app_id" => $app_idString, "primary_app_type" => $app_type, "secondary_app_id" => $Scndapp_idString, "secondary_app_type" => $Scndapp_type, "primary_backgroound_color" => $P_backgroundcolor, "secondary_backgroound_color" => $S_backgroundcolor, "content_app_id" => $contentAppIdString, "retail_app_id" => $retailAppIdString, "retail_app_type" => $retailAppType, "theme" => $themeResult,
        "last_login" => $lastlogin,
        "notification" => $notification,
        "auth_token" => $authToken,
        "action_bar" => array(
            "background_color" => $P_backgroundcolor,
            "background_image" => $P_backgroundimage
        ),
        "google_analytics" => $analytics,
        "tnc" => "http://www.instappy.com/terms-of-service.php",
        "contact_details" => array(
            "contact_email" => "$contactusemail",
            "contact_phone" => "$contactusphone"
        ),
        "feedback_details" => array(
            "feedback_email" => "$feedbackemail",
            "feedback_phone" => "$feedbackphone"
        ),
        "order_details" => array(
            "order_email" => "$order_email",
            "order_phone" => "$order_phone",
            "order_logo" => "$order_logo"
        ),
        "adData" => $adResult);
    $catcount = count($navigation);
    $navigation1 = array("cat_count" => "$catcount", "font_typeface" => "Arial", "catlist" => $navigation);
    /* retail launch data starts here */


    $response = array("result" => 'success', "msg" => '', "app_valid" => $app_valid);
    $contentdata = array("screen_data" => $screenNavigation);
    $launchdata = array("response" => $response, "commonProperties" => $commonProperties, "navigation" => $navigation1, "retail" => $Retaildata, "content" => $contentdata);
    print_r(json_encode($launchdata));
}
