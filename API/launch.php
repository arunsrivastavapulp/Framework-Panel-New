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
$app->post('/launch', 'launchapp');

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

function launchapp() {

    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id'])) {
        $app_idString = $_POST['app_id'];
        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id']; //uuid  
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
	$adResult=array();
        $authResult = authCheck($authToken, $device_id);
		   $appQueryData = "select *  from app_data where app_id='" . $app_idString . "'";
            $app_screenData = $dbCon->query($appQueryData);
            $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);
	
			if($result_screenData != '')
			{
             $app_id = $result_screenData->id;
			}
			else{
			 $app_id =0;
			}
//Query for screen Data
        $app_navigation_query = "select * from app_navigation_drawer where app_id='" . $app_id . "' limit 1";
        $app_navigationExecution = $dbCon->query($app_navigation_query);
        $result_navigation = $app_navigationExecution->fetchAll(PDO::FETCH_OBJ);
        $countOfNav = count($result_navigation);

        if ($countOfNav != 0) {
            $id_navigation_drawer = $result_navigation[0]->id;
            $isDrawerEnable = $result_navigation[0]->is_drawer_enabled;
            $linkToscreen = $result_navigation[0]->link_to_screenid;
            $app_properties_query = "select * from app_navigation_properties where app_navigation_drawer_id='" . $id_navigation_drawer . "'";
            $app_propertiesExecution = $dbCon->query($app_properties_query);
            $result_properties = $app_propertiesExecution->fetchAll(PDO::FETCH_OBJ);
        }
// Query to fetch screen application data
        $app_screen_query = "select * from screen_title_id where app_id='" . $app_id . "' order by  background_type=0,CAST(background_type AS UNSIGNED) ASC";
        $app_screenqueryExecution = $dbCon->query($app_screen_query);
        $result_screen = $app_screenqueryExecution->fetchAll(PDO::FETCH_OBJ);
        $countOfScreen = count($result_screen);
        $objScreen = json_encode($result_screen);

// Query for theme Data                
        $themeQuery = "SELECT *  FROM themes_app_rel where app_id='" . $app_id . "' limit 1";
        $themeQueryExecution = $dbCon->query($themeQuery);
        $themeResult = $themeQueryExecution->fetch(PDO::FETCH_OBJ);

// Query for ad Data                
        $adQuery = "select aad.app_id,ads.servername,ad.published_id,ad.ad_serverid,ad.unit_id, aad.id as adserver_detail_id from app_adserver_details aad left join adserver_details ad on aad.adserver_details_id=ad.id
left join adserver ads on ad.ad_serverid=ads.id where aad.app_id='" . $app_id . "' limit 0,1";
        $adQueryExecution = $dbCon->query($adQuery);
        $adResult1 = $adQueryExecution->fetch(PDO::FETCH_OBJ);
	
    $resultsCountad = $adQueryExecution->rowCount(PDO::FETCH_NUM);	
        if ($authResult == 0 || $device_id == '') {
            $response = array("result" => 'error', "msg" => '0');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
        } else {
            $response = array("result" => 'success', "msg" => '');
            $data = array();

            if ($result_properties != '') {
// data for navigation_drawer_profile
                foreach ($result_properties as $value) {
                    if ($value->field_type == 2) {
                        $data2 = array("image_url" => $value->image_url, "height" => $value->height, "width" => $value->width);
                    } else {

                        $data2 = array("text" => $value->description, "font_size" => $value->font_size, "font_color" => $value->font_color, "font_typeface" => $value->font_typeface);
                    }
                    $data[$value->field] = $data2;
                }
            }
// end of data for navigation_drawer_profile
//            screen data
            $screenId = 0;
			 $adindex = 1;
			$adResultScreen=array();
            if ($countOfScreen != 0) {
                foreach ($result_screen as $valueScreen) {
				 if ($adindex % 2 == 0) {
				$adResultScreen[]=array("screen_id"=>$valueScreen->id);
				}
                    if ($valueScreen->is_visible == 1) {
                        $screen[] = array("drawer_icon" => array("text" => $valueScreen->image_url, "font_size" => $valueScreen->font_size, "font_color" => $valueScreen->font_color, "font_typeface" =>"icomoon.ttf"), "title" => array("text" => $valueScreen->title, "font_size" => $valueScreen->font_size, "font_color" => $valueScreen->font_color, "font_typeface" => $valueScreen->font_typeface), "linkto_screenid" => $valueScreen->id);
                        if ($valueScreen->parent_id == 0 || $valueScreen->parent_id == NULL) {
                            $screenId = $valueScreen->id;
                        }
                    }
                    $adindex++;
                }
				
					//	foreach($adResult1 as $keyadResult1 => $valadResult1)
		//{
		
		/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		here we are selecting screen for ad from database
		
		++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	/* 	$adQueryScreen="select screen_id from appserver_screenmapping where adserver_details_id=$details_id";		
        $adQueryScreen = $dbCon->query($adQueryScreen);
        $adResultScreen = $adQueryScreen->fetchAll(PDO::FETCH_OBJ); */	
		
		if($resultsCountad != 0)
		{
                 //  $adResult[]=$adResult1->app_id;
		$adResult[]=array("app_id"=>$adResult1->app_id,"servername"=>$adResult1->servername,"published_id"=>$adResult1->published_id,"ad_serverid"=>$adResult1->ad_serverid,"unit_id"=>$adResult1->unit_id,"screens"=>$adResultScreen);
			}
			//	}
				
                // Query to fetch screen application relation data
                $appQueryNavigation = "select *  from screen_title_id where app_id='" . $app_id . "' and id='" . $screenId . "'";
                $app_screenNavigationquery = $dbCon->query($appQueryNavigation);
                $result_screenNavigation = $app_screenNavigationquery->fetch(PDO::FETCH_OBJ);
				
				$ScreenCheck_query = "select updated from app_data where id='" . $app_id . "'";
                    $app_navigationExecution = $dbCon->query($ScreenCheck_query);
                    $result_navigation = $app_navigationExecution->fetchAll(PDO::FETCH_OBJ);
                    if (count($result_navigation) != 0) {
                        $getTime = $result_navigation[0]->updated;
                      
                     
$screenNavigation[] = array("screen_id" => "-4", "parent_id" => "", "screen_type" => "", "tag" => '', "dirtyflag" => '', "server_time" => $getTime, "screen_properties" => array("title" => "", "popup_flag" => "", "background_color" => "", "url" => ""));
                
				 }
			 foreach ($result_screen as $valueNavigationScreen) {
                    $screenNavigation[] = array("screen_id" => $valueNavigationScreen->id, "parent_id" => $valueNavigationScreen->parent_id, "screen_type" => $valueNavigationScreen->screen_type, "tag" => '', "dirtyflag" => '', "server_time" => $valueNavigationScreen->server_time, "screen_properties" => array("title" => $valueNavigationScreen->title, "popup_flag" => $valueNavigationScreen->popup_flag, "background_color" => $valueNavigationScreen->background_color, "url" => $valueNavigationScreen->image_url));
                }
		$backNavColor='';
		$analytics='';
                 $appQueryData = "select *  from app_data where app_id='" . $app_idString . "'";
            $app_screenData = $dbCon->query($appQueryData);
            $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);
            $backNavColor= $result_screenData->background_color;
            $analyticsCode= $result_screenData->analytics_code;
			
			if($analyticsCode != '')
			{
			  $anlyticsData = "select *  from app_analytics_mapping where id='" . $analyticsCode . "'";
            $anlyticsData = $dbCon->query($anlyticsData);
            $anlyticsContentData = $anlyticsData->fetch(PDO::FETCH_OBJ);
            $analytics= $anlyticsContentData->analytics_id;
			
			}
			
 $navigation_drawer = array("is_drawer_enabled" => $isDrawerEnable, "linkto_screenid" => $linkToscreen, "navigation_drawer_profile" => $data, "screens" => $screen);

                    $Basearray = array("response" => $response, "action_bar" => array("background_color" => $backNavColor),'analytics_code'=>array("google_analytics"=>$analytics), "navigation_drawer" => $navigation_drawer, "screen_navigation" => $screenNavigation, "theme" => $themeResult,"adData"=> $adResult);
                    $basejson = json_encode($Basearray);
                    echo $basejson;
                
            } else {
                $response = array("result" => 'error', "msg" => 'No Data Available');
                $Basearray = array("response" => $response);
                $basejson = json_encode($Basearray);
                echo $basejson;
            }



//screen_navigation data 
// screen data 
        }
    } catch (Exception $e) {
        $response = array("result" => 'error', "msg" => '-1');
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
