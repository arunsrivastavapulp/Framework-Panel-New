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
$app->post('/event', 'eventData');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */
function authCheck($authToken, $device_id) {
    $dbCon = content_db();

    $auth_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token='" . $authToken . "' and u.device_id='" . $device_id . "'";
    $auth_queryExecution = $dbCon->query($auth_query);
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);

    return $result_auth;
}

function eventData() {

    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id']) && isset($_POST['screen_id'])) {
        $offset = 0;
        $app_idString = $_POST['app_id'];
        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id']; //uuid  
        $screenId = $_POST['screen_id'];
        $years = $_POST['year'];
        $months = $_POST['month'];
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

	$titlefont_color ='';
				$titlefont_size = '';
				$titlefont_typeface = '';
				$datefont_color = '';
				$datefont_size = '';
				$datefont_typeface = '';
				$compType='9';

    $dbCon = content_db();

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
            $typeVisibility = "1";
            $bgColor = '';
            $response = array("result" => 'success', "msg" => '');

            $appEventQuery = "SELECT * FROM app_event_attribute aa LEFT JOIN customfielddata cd ON aa.component_type_id=cd.component_type_id AND aa.componentfieldoption_id=cd.componentfieldoption_id
WHERE  aa.screen_id='" . $screenId . "'";

            $app_eventNavigationquery = $dbCon->query($appEventQuery);
            $result_event = $app_eventNavigationquery->fetchAll(PDO::FETCH_OBJ);
            $arraycomp_elements = array();
            $ys = count($result_event);

            foreach ($result_event as $resultEventSet) {
                if ($resultEventSet->customname == 'title') {
                    $titlefont_color = $resultEventSet->font_color;
                    $titlefont_size = $resultEventSet->font_size;
                    $titlefont_typeface = $resultEventSet->font_typeface;
                } else {
                    $datefont_color = $resultEventSet->font_color;
                    $datefont_size = $resultEventSet->font_size;
                    $datefont_typeface = $resultEventSet->font_typeface;
                }
                $compType = $resultEventSet->component_type_id;
                $pos = $resultEventSet->sequence;
                $attributes = $resultEventSet->field_attributes;
            }


            $appEventAllQuery = "SELECT ad.id,ad.app_id,ad.screen_id,ar.title,ar.heading,ar.description,ad.linkto_screenid,ar.start_datetime,ar.end_datetime,
ar.image1,ar.image2,ar.allday,ar.event_type_id,et.NAME AS event_type_name,ar.updatable
 FROM app_event_rel ad LEFT JOIN event_data ar ON ad.event_data_id=ar.id LEFT JOIN event_type et ON ar.event_type_id=et.id
WHERE (ar.start_datetime BETWEEN '" . $years . "-$months-01 00:00:00" . "' AND '" . $years . "-$months-31 23:59:59" . "') and ad.app_id='" . $app_id . "' order by ar.start_datetime ASC ";

            $app_AlleventNavigationquery = $dbCon->query($appEventAllQuery);
            $result_eventAll = $app_AlleventNavigationquery->fetchAll(PDO::FETCH_OBJ);

            $arraycomp_elements['background_color'] = $bgColor;
            $arraycomp_elements['card_elevation'] = '2';
            $arraycomp_elements['card_corner_radius'] = '3';
            $arraycomp_elements['background_color'] = $bgColor;

            foreach ($result_eventAll as $resultAllEventSet) {
                if ($resultAllEventSet->image1 != '' || $resultAllEventSet->image1 != NULL) {
                    if ($resultAllEventSet->event_type_id == 2 || $resultAllEventSet->event_type_id == 3) {
                        $date = strtotime($resultAllEventSet->start_datetime);
                        $eventhours = date('H', $date);
                        if ($eventhours == 00) {
						 $myTime = date('H:i', $date);
                           // $myTime = strtotime($date, 'H:i');
                        } else {
                            $myTime = date('H:i', $date);
						   // $myTime = strtotime($date, 'm-d H:i');
                        }

                        $date1 = strtotime($resultAllEventSet->end_datetime);
                        $eventhours1 = date('H', $date1);
                        if ($eventhours1 == 00) {
						 $myTime1 = date('H:i', $date1);
                          //  $myTime1 = strtotime($date1, 'H:i');
                        } else {
						 $myTime1 = date('H:i', $date1);
                           // $myTime1 = strtotime($date1, 'm-d H:i');
                        }
                        $typeVisibility = "0";
                    } else {
                        $date = strtotime($resultAllEventSet->start_datetime);
                        $eventhours = date('H', $date);
                        if ($eventhours == 00) {
                            $myTime = date('m-d', $date);
						   // $myTime = strtotime($date, 'm-d');
                        } else {
                           $myTime = date('m-d H:i', $date);
						  //  $myTime = strtotime($date, 'm-d H:i');
                        }
                        $typeVisibility = "1";
                    }
                    $arraycomp_elements["linkto_screenid"] = $resultAllEventSet->linkto_screenid;
                    $arraycomp_elements["icon_text"] = array("text" => $resultAllEventSet->image2, "font_size" => '20', "font_color" => '#757575', "font_typeface" => 'icomoon.ttf');
                    $arraycomp_elements["icon_label_up"] = array("text" => $resultAllEventSet->heading, "font_size" => $titlefont_size, "font_color" => $titlefont_color, "font_typeface" => $titlefont_typeface);
                    $arraycomp_elements["icon_label_down"] = array("text" => $resultAllEventSet->$myTime . ' ' . $myTime1, "font_size" => $datefont_size, "font_color" => $datefont_color, "font_typeface" => $datefont_typeface);
                    $arraycomp_elements["default_reminder_time"] = '30';
                    $arraycomp_elements["remiender_default"] = 'true';
                    $arraycomp_elements["remiender_miunutes"] = '30';
                    $compId = $resultAllEventSet->id;
                    $headingEvent = array();
                    if ($compType == '41') {
                        $compType = 12;
                    }
                    $allday = '';
                    if ($resultAllEventSet->allday == 1) {
                        $allday = "true";
                    } else {
                        $allday = "false";
                    }
                } else {

                    if ($resultAllEventSet->event_type_id == 2 || $resultAllEventSet->event_type_id == 3) {
                        $date = strtotime($resultAllEventSet->start_datetime);
                        $eventhours = date('H', $date);
                        if ($eventhours == 00) {
                           $myTime = date('H:i', $date);
						  //  $myTime = strtotime($date, 'H:i');
                        } else {
                            $myTime = date('H:i', $date);
						   // $myTime = strtotime($date, 'm-d H:i');
                        }

                        $date1 = strtotime($resultAllEventSet->end_datetime);
                        $eventhours1 = date('H', $date1);
                        if ($eventhours1 == 00) {
                          $myTime1 = date('H:i', $date1);
						 //   $myTime1 = strtotime($date1, 'H:i');
                        } else {
                           $myTime1 = date('H:i', $date1);
						  //  $myTime1 = strtotime($date1, 'm-d H:i');
                        }
                        $typeVisibility = "0";
                    } else {
                        $date = strtotime($resultAllEventSet->start_datetime);
                        $myTime = date('M j, Y', $date);
					   // $myTime = strtotime($date, 'M j, Y');

                        $myTime1 = '';
                        $myTimeFinal = $myTime;
                        $typeVisibility = "1";
                    }

                    $arraycomp_elements["linkto_screenid"] = $resultAllEventSet->linkto_screenid;
                    $arraycomp_elements["image"] = array("media_type" => 'image', "image_url" => $resultAllEventSet->image2, "height" => '24', "width" => '24');
                    $arraycomp_elements["left_label_up"] = array("text" => $resultAllEventSet->heading, "font_size" => $titlefont_size, "font_color" => $titlefont_color, "font_typeface" => $titlefont_typeface);
                    $arraycomp_elements["left_label_down"] = array("text" => $myTime . ' ' . $myTime1, "font_size" => $datefont_size, "font_color" => $datefont_color, "font_typeface" => $datefont_typeface);
                    $arraycomp_elements["right_label_up"] = array("text" => '', "font_size" => '', "font_color" => '', "font_typeface" => '');
                    $arraycomp_elements["right_label_down"] = array("text" => '', "font_size" => '', "font_color" => '', "font_typeface" => '');


                    $arraycomp_elements["default_reminder_time"] = '30';
                    $arraycomp_elements["remiender_default"] = 'true';
                    $arraycomp_elements["remiender_miunutes"] = '30';
                    $compId = $resultAllEventSet->id;
                    $headingEvent = array();
                    if ($compType == '41') {
                        $compType = '9';
                    }
                    $allday = '';
                    if ($resultAllEventSet->allday == 1) {
                        $allday = "true";
                    } else {
                        $allday = "false";
                    }
                }
                $componentResultSet[] = array("event_id" => $resultAllEventSet->id, "kind" => 'regular', "calendar_name" => $resultAllEventSet->event_type_name, "all_day" => $allday, "start_time" => $resultAllEventSet->start_datetime, "end_time" => $resultAllEventSet->end_datetime, "etag" => 'etag', "id" => '1004', "event_type_visibility" => $typeVisibility, "event_type_id" => $resultAllEventSet->event_type_id, "comp_type" => $compType, "comp_elements" => $arraycomp_elements);
            }
            $calendar_date = array("calendar_date" => $componentResultSet);
            $Basearray = array("response" => $response, "data" => $calendar_date);
            $basejson = json_encode($Basearray);
            echo $basejson;
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
