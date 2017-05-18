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
$app->post('/search', 'searchData');

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

function searchData() {

    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id']) && isset($_POST['searchItem'])) {
        $offset = 0;
        $app_idString = $_POST['app_id'];
        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id']; //device_id  
        $searchItem = $_POST['searchItem'];

        if (isset($_POST['offset']) && $_POST['offset'] != '') {
            $offset = $_POST['offset'];
        }
    } else {
        $responce = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }

    $dbCon = getConnection();

    try {
        $authResult = authCheck($authToken, $device_id);
        if ($authResult == 0 || $device_id == '') {
            $response = array("result" => 'error', "msg" => '0');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
        } else {
		
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
            $response = array("result" => 'success', "msg" => '');
            $appEventAllQuery = "SELECT 
    cv.id,
    cv.app_id,
    cv.component_type_id,
    cv.componentfieldoption_id,
    ct.NAME AS component_type_name,
    cd.customname AS component_attr_name,
    cd.field_type,
    ct.is_except,
    cd.field_attributes,
    cd.list_type,
    cv.componentarraylink_id,
    cv.screen_id,
    cv.linkto_screenid,
    cv.list_no,
    cv.component_no,
    cv.defaultselect,
    cv.component_position,
    cv.description,
    cv.datevalue,
    cv.image_url,
    cv.video_url,
    cv.height,
    cv.width,
    cv.item_orientation,
    cv.background_type,
    cv.backgroundcolor,
    cv.texttodisplay,
    cv.font_color,
    cv.font_typeface,
    cv.font_size,
    cv.display,
    cv.visibility,
    cv.auto_update,
    cv.card_elevation,
    cv.card_corner_radius,
    cv.action_type_id,
    cv.action_data,
    cv.is_preference,
    cv.action_message,
    cd.top_level
FROM
    componentfieldvalue cv
        LEFT JOIN
    customfielddata cd ON (cv.component_type_id = cd.component_type_id
        AND cv.componentfieldoption_id = cd.componentfieldoption_id)
        JOIN
    component_type ct ON cv.component_type_id = ct.id
WHERE
    app_id = '".$app_id."' AND cv.description LIKE '%" . $searchItem . "%'
       group by app_id,screen_id,component_type_id 
ORDER BY component_no , list_no , componentarraylink_id limit $offset,10
";


            $app_AlleventNavigationquery = $dbCon->query($appEventAllQuery);
            $result_eventAll = $app_AlleventNavigationquery->fetchAll(PDO::FETCH_OBJ);


            foreach ($result_eventAll as $resultAllEventSet) {
               
                    $textlen = strlen($resultAllEventSet->description);
                    $testShow = substr("$resultAllEventSet->description", 0, 30);
                    if ($textlen > 30) {
                        $finalText = $testShow . "......";
                    } else {
                        $finalText = $testShow;
                    }
                    $searchText = $finalText;

                    $arraycomp_elements["image"] = array("media_type" => 'image', "image_url" => 'http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/images/search1.png', "height" => '24', "width" => '24');
                    $arraycomp_elements["left_label_up"] = array("text" => $searchText, "font_size" => "16", "font_color" => "#000000", "font_typeface" => "Arial");
                    $arraycomp_elements["left_label_down"] = array("text" => "", "font_size" => $resultAllEventSet->font_size, "font_color" => $resultAllEventSet->font_color, "font_typeface" => $resultAllEventSet->font_typeface);
                    $arraycomp_elements["right_label_up"] = array("text" => '', "font_size" => '', "font_color" => '', "font_typeface" => '');
                    $arraycomp_elements["right_label_down"] = array("text" => '', "font_size" => '', "font_color" => '', "font_typeface" => '');

                    $componentResultSet[] = array("id" => $resultAllEventSet->id, "background_color" => $resultAllEventSet->backgroundcolor, "card_elevation" => $resultAllEventSet->card_elevation, "card_corner_radius" => $resultAllEventSet->card_corner_radius, "comp_type" => '9', "linkto_screenid" => $resultAllEventSet->screen_id, "comp_elements" => $arraycomp_elements);
                
            }
            $countofComponemt = count($componentResultSet);
            $maths = $countofComponemt % 10;
            if ($maths == 0) {
                $pageStatus = 'cof';
            } else {
                $pageStatus = 'eof';
            }
            $Basearray = array("response" => $response, "pagination" => $pageStatus, "data" => $componentResultSet);
            $basejson = json_encode($Basearray);
            echo $basejson;
        }
    } catch (Exception $e) {
	   $response = array("result" => 'success', "msg" => '0');
        $Basearray = array("response" => $response, "pagination" => '', "data" => '');
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
