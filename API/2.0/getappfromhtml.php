<?php

require 'Slim/Slim.php';
require 'includes/db.php';
$baseUrl = baseUrlWeb();
\Slim\Slim::registerAutoloader();
$dbCon = content_db();

$app = new \Slim\Slim();
$app->post('/getData', 'getData');

global $dom;
$dom = new DOMDocument;

	function send_notification($data){
		   $dbCon = content_db();
 		 $appid=$data['app_id'];
		$sql = "select push_token,platform from user_appdata where app_id=$appid";
		$stmt = $dbCon->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
         //   $iconId = $results['icomoon_code'];
	//	$title=$data['title'];
		//$desc=$data['desc'];
		$result="";
		$title=isset($title)?$title:'';
		 $title= utf8_encode($title);
		$msg=isset($desc)?$desc:'';
        $heading = ucfirst($title);
    //    $layoutType = $data['layoutType'];
        $message = ucfirst($msg);
        $imageUrl = '';
        $action_tag = '3';
        $action_data = $data['action_data'];
        $windowsimageUrl = '';
        if ($imageUrl != '') {
            $layoutType = 1;
        } else {
            $layoutType = 2;
        }


        $msgW = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
                "<wp:Notification xmlns:wp=\"WPNotification\">" .
                "<wp:Toast>" .
                "<wp:Text1>" . htmlspecialchars($heading) . "</wp:Text1>" .
                "<wp:Text2>" . htmlspecialchars($message) . "</wp:Text2>" .
                "<wp:Param>/Screen2.xaml?ScreenID=" . $action_data . "</wp:Param>" .
                "</wp:Toast>" .
                "</wp:Notification>";

        $array = array('heading' => $heading, 'message' => $message, 'imageUrl' => $imageUrl, 'action_tag' => $action_tag, 'action_data' => $action_data, 'layoutType' => $layoutType);

 foreach($results as $val){
	 if($val['platform']==1){
  $arr= send_gcm_notify($val['push_token'], $array);
	 }  
	 	 if($val['platform']==2){
  $arr= iosSendnotification($val['push_token'], $array);
	 } 
	
 }
//	echo "success";
	}
function send_gcm_notify($reg_id, $message) {
	
        $fields = array(
            'registration_ids'  => array( $reg_id ),
            'data'              => array( "message" => $message ),
        );
        $headers = array(
            'Authorization: key=AIzaSyAD6m3oCh2AeVNTM5uPE3cmeFrSVVHW7YA',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Problem occurred: ' . curl_error($ch));
        }
		//echo $result;
        curl_close($ch);
		$results=json_decode($result);
		print_r($results);
		if($results->success==1){
		return "success";		
		}
		else{
		return "fails";
		}		

		
    }
function iosSendnotification($pushtoken, $totalmsg) {
    $passphrase = 'testpush';
    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', __DIR__.'/ck.ppk');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
    $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp)
        exit("Failed to connect: $err $errstr" . PHP_EOL);
//    echo 'Connected to APNS' . PHP_EOL;
// Create the payload body
    $body['aps'] = array(
        'alert' => $totalmsg['heading'].' - '.$totalmsg['message'],
        'sound' => 'default',
        'push_detail' => $totalmsg
    );
    $authToken = $pushtoken;

// Encode the payload as JSON
    $payload = json_encode($body);

    // Build the binary notification
    $msg = chr(0) . pack('n', 32) . pack('H*', $authToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
    $result = fwrite($fp, $msg, strlen($msg));
    fclose($fp);
   if ($result) {
       return "success";
   } else {
       return "fails";
   }
	}



function html_to_obj($html) {
    global $dom;
    $local_dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $local_dom->loadHTML('<?xml encoding="UTF-8">' . $html);
    libxml_clear_errors();
    return element_to_obj($local_dom->documentElement);
}

function element_to_obj($element) {
    if (isset($element->tagName)) {
        $obj = array("tag" => $element->tagName);

        foreach ($element->attributes as $attribute) {
            $obj[$attribute->name] = $attribute->value;
        }
        foreach ($element->childNodes as $subElement) {
            if ($subElement->nodeType == XML_TEXT_NODE) {
                $obj["html"] = $subElement->wholeText;
            } else {
                $obj["children"][] = element_to_obj($subElement);
            }
        }
        return $obj;
    }
}

function ObjectToHtml($ObjectValues) {
    $html = '';
    $style = '';

    if (isset($ObjectValues['tag'])) {
        if (isset($ObjectValues['style'])) {
            $style = $ObjectValues['style'];
        }


        if ($style != '') {
            $pos = strpos($ObjectValues['style'], 'none');
            if ($pos === false) {
                $html .='<' . $ObjectValues['tag'] . " style=" . '"' . $style . '"' . '>';
            } else {

                $html .='<' . $ObjectValues['tag'] . '>';
            }
        } else {
            $html .='<' . $ObjectValues['tag'] . '>';
        }
        if (isset($ObjectValues['html'])) {
            $html .=$ObjectValues['html'];
        }
        if (isset($ObjectValues['children'])) {
            $cnt = count($ObjectValues['children']);
            for ($i = 0; $i < $cnt; $i++) {
                $html .=ObjectToHtml($ObjectValues['children'][$i]);
            }
        }


        $html .='</' . $ObjectValues['tag'] . '>';
    }
    return $html;
}

function rgb2hex($rgb) {
    $hex = "#";
    $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
    $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
    $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);
    return $hex; // returns the hex value including the number sign (#)
}

function getAttribute($cssProperty) {

    $resultAttributes[] = '';
    $resultAttributes['font_typeface'] = '';
    $resultAttributes['font_size'] = '';
    $resultAttributes['font_color'] = '';
    $resultAttributes['background_color'] = '';

    $bgcolr = explode(';', $cssProperty);
    foreach ($bgcolr as $KeyProp => $valueprop) {
        $bgcolrProp = explode(':', $valueprop);

        if ($bgcolrProp[0] == 'background-color' || $bgcolrProp[0] == ' background-color') {

            if (preg_match('/^#[a-f0-9]{6}$/i', $bgcolrProp[1])) { //hex color is valid
                $resultAttributes['background_color'] = $bgcolrProp[1];
            } else {

                $text = $bgcolrProp[1];
                preg_match('#\((.*?)\)#', $text, $match);
                $mycolor = $match[1];
                $bgcolrProp = explode(',', $mycolor);

                $finalHex = rgb2hex($bgcolrProp);

                $resultAttributes['background_color'] = $finalHex;
            }
        }
        if ($bgcolrProp[0] == 'background') {

            if (preg_match('/^#[a-f0-9]{6}$/i', $bgcolrProp[1])) { //hex color is valid
                $resultAttributes['background_color'] = $bgcolrProp[1];
            } else {

                $text = $bgcolrProp[1];
                preg_match('#\((.*?)\)#', $text, $match);
                if (isset($match[1])) {
                    $mycolor = $match[1];
                    $bgcolrProp = explode(',', $mycolor);
                    $finalHex = rgb2hex($bgcolrProp);
                    $resultAttributes['background_color'] = $finalHex;
                }
            }
        }

        if ($bgcolrProp[0] == 'font-family') {
            $resultAttributes['font_typeface'] = addslashes($bgcolrProp[1]);
        }

        if ($bgcolrProp[0] == ' font-size') {
            $resultAttributes['font_size'] = $bgcolrProp[1];
        } elseif ($bgcolrProp[0] == 'font-size') {
            $resultAttributes['font_size'] = $bgcolrProp[1];
        }

        if ($bgcolrProp[0] == ' color') {
            if (preg_match('/^#[a-f0-9]{6}$/i', $bgcolrProp[1])) { //hex color is valid
                $resultAttributes['font_color'] = $bgcolrProp[1];
            } else {

                $text = $bgcolrProp[1];
                preg_match('#\((.*?)\)#', $text, $match);
                $mycolor = $match[1];
                $bgcolrProp = explode(',', $mycolor);

                $finalHex = rgb2hex($bgcolrProp);

                $resultAttributes['font_color'] = $finalHex;
            }
        } elseif ($bgcolrProp[0] == 'color') {
            if (preg_match('/^#[a-f0-9]{6}$/i', $bgcolrProp[1])) { //hex color is valid
                $resultAttributes['font_color'] = $bgcolrProp[1];
            } else {

                $text = $bgcolrProp[1];
                preg_match('#\((.*?)\)#', $text, $match);
                $mycolor = $match[1];
                $bgcolrProp = explode(',', $mycolor);

                $finalHex = rgb2hex($bgcolrProp);

                $resultAttributes['font_color'] = $finalHex;
            }
        }
    }

    return $resultAttributes;
}

function getIcomoon($icoCode) {
    $dbCon = content_db();
    $iconName1 = str_replace("icon_upload_img ", "", $icoCode);
    $iconName2 = str_replace("icon-", "", $iconName1);
    $iconName = str_replace("_icon", "", $iconName2);
    $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%' limit 1";

    $appQueryicon = $dbCon->query($app_icon);
    $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
    $imgUrl = $rowiconFetch['icomoon_code'];
    return $imgUrl;
}

function component_2($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $data_shared_text, $data_shared_link, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 2;
    $appQueryData = "select * from customfielddata where component_type_id='2'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    $actionShareData = '';
    $actionShareData = json_encode(array('shared_text' => $data_shared_text, 'shared_link' => $data_shared_link), JSON_UNESCAPED_UNICODE);
    $resultSet['background_color'] = '#dbdbdb';
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#FFFFFF';
    $resultSet['font_typeface'] = 'icomoon.ttf';
    $resultSet['font_size'] = '16';

    $resultSet['font_color1'] = '#FFFFFF';
    $resultSet['font_typeface1'] = 'Arial';
    $resultSet['font_size1'] = '14';
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '-1';
    if (isset($result_screenData[0]->componentfieldoption_id)) {
        $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    } else {
        $resultSet['componentfieldoption_id'] = '';
    }
    $resultSet['listNo'] = 1;
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

    $listcomponent = count($componentData);
    $resultSet['heading'] = '';
    if (isset($componentData[0]['children'][0]['html'])) {
        $resultSet['heading'] = $componentData[0]['children'][0]['html'];
        if (isset($componentData[0]['children'][0]['style'])) {
            $cssProperty = $componentData[0]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }

    if (isset($componentData[0]['html']) && $resultSet['heading'] == '') {
        $resultSet['heading'] = str_replace("</a>", "", $componentData[0]['html']);
        if (isset($componentData[0]['style'])) {
            $cssProperty = $componentData[0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }
    $resultSet['tab_icon'] = 'e047';
    if (isset($componentData[1]['children'][0]['children'][0]['class'])) {
        $icoCode = $componentData[1]['children'][0]['children'][0]['class'];
        $iconName1 = str_replace("fa ", "", $icoCode);
        $iconName = str_replace("fa-", "", $iconName1);
        $resultSet['tab_icon'] = getIcomoon($iconName);
    }

    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $resultSet['font_size1'] = str_replace("px", "", $resultSet['font_size1']);
    if ($resultSet['font_size1'] == '0' || $resultSet['font_size1'] == '') {
        $resultSet['font_size1'] = '14';
    }

    for ($ii = 0; $ii <= 5; $ii++) {
        $listno = $ii + 1;
        $resultSet['tab_headingData' . $ii] = '';
        if (isset($componentData[$ii]['children'][0]['class'])) {
            $icoCode = $componentData[$ii]['children'][0]['class'];
            $iconName1 = str_replace("fa ", "", $icoCode);
            $iconName = str_replace("fa-", "", $iconName1);
            $resultSet['tab_headingData' . $ii] = getIcomoon($iconName);
            if ($resultSet['tab_headingData' . $ii] == 'e045') {
                $resultSet['action_type_id'] = '2';
                $resultSet['action_data'] = $actionShareData;
            } elseif ($resultSet['tab_headingData' . $ii] == 'e047') {
                $resultSet['action_type_id'] = '6';
                $resultSet['action_data'] = $lintoId;
            }
        }
        if ($ii == 4 || $ii == '2') {
            $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '0', '" . addslashes($resultSet['heading']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . addslashes($resultSet['font_typeface1']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "')";
            break;
        } else {
            if ($ii < 1) {
                $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '0', '" . addslashes($resultSet['tab_icon']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size1'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
            } else {
                $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '0', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size1'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
            }
        }
    }


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 2 inserted successfully";
    }
}

function component_3($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData) {
    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 3;
    $appQueryData = "select * from customfielddata where component_type_id='3'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['display'] = $display;
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }
    $resultSet['tab_heading'] = '';
    $resultSet['tab_headingData'] = '';

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $listcomponent = count($result_screenData);
    if (isset($componentData[1]['children'][0]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['children'][0]['src'];
    }
    if (isset($componentData[1]['children'][1]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[1]['children'][1]['children'][0]['html'];
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_heading']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";
    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";
    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 3 inserted successfully";
    }
}

function component_4($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData) {
    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 4;
    $appQueryData = "select * from customfielddata where component_type_id='4'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    $actionShareData = '';

    $resultSet['background_color'] = '#FFFFFF';
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['font_color1'] = '#000000';
    $resultSet['font_typeface1'] = 'icomoon.ttf';
    $resultSet['font_size1'] = '24';
    $resultSet['font_size2'] = '24';
    $resultSet['action_type_id'] = '0';
    $resultSet['action_data'] = '0';
    $resultSet['linkto_screenid'] = '-1';
    $height = '64';
    $width = '64';

    if (isset($result_screenData[0]->componentfieldoption_id)) {
        $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    } else {
        $resultSet['componentfieldoption_id'] = '';
    }
    $resultSet['listNo'] = 1;

    if (isset($componentData[1]['html'])) {
        $heading = $componentData[1]['html'];

        if (isset($componentData[1]['style'])) {
            $cssProperty = $componentData[1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    } else {
        $heading = '';
    }
    //if ($resultSet['font_size1'] == null || $resultSet['font_size1'] = '') {
    $resultSet['font_size2'] = '24';
    // }
    $CalCheck = "select * from screen_title_id where app_id='" . $app_id . "'  and background_type='9998' limit 0,1";
    $calCheckRun = $dbCon->query($CalCheck);
    $rowFetchNav = $calCheckRun->fetch(PDO::FETCH_ASSOC);
    $calRowCal = $calCheckRun->rowCount(PDO::FETCH_NUM);
    if ($calRowCal == 0) {
        $sqlcal = "INSERT INTO screen_title_id (app_id,screen_type,image_url,height,width,title,font_size,font_color,font_typeface,parent_id,popup_flag,background_type,background_color,is_visible,event_type,server_time,is_bypass,others,seq,deleted) VALUES ('" . $app_id . "','2','','" . $height . "','" . $width . "','" . addslashes($heading) . "','" . $resultSet['font_size'] . "','" . $resultSet['font_color'] . "','" . $resultSet['font_typeface'] . "','-1','9998','9998','','0','','','','','',0)";
        $stateCal = $dbCon->prepare($sqlcal);
        $stateCal->execute();
        $resultSet['linkto_screenid'] = $dbCon->lastInsertId();
    } else {
        $myCalCard = "update screen_title_id set deleted=0 where app_id='" . $app_id . "'  and background_type='9998'";
        $publisedAppcard = $dbCon->prepare($myCalCard);
        $publisedAppcard->execute();
        $resultSet['linkto_screenid'] = $rowFetchNav['id'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

    $listcomponent = count($componentData);

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size2'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '24', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size1'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[5]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size1'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[6]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[7]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "')";

    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 4 inserted successfully";
    }
}

function component_5($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $screen_type, $retailsData) {


    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 5;
    $appQueryData = "select * from customfielddata where component_type_id='5'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '0';
    $resultSet['card_corner_radius'] = '0';
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    if ($screen_type == '5' || $screen_type == '8') {
        $resultSet['card_elevation'] = '0';
        $resultSet['card_corner_radius'] = '0';
    }


    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['display'] = $display;
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = '14';
    $resultSet['font_typeface2'] = $resultSet['font_typeface'];
    $resultSet['font_color2'] = $resultSet['font_color'];
    $resultSet['font_size2'] = $resultSet['font_size'];
    $resultSet['font_typeface3'] = $resultSet['font_typeface'];
    $resultSet['font_color3'] = $resultSet['font_color'];
    $resultSet['font_size3'] = $resultSet['font_size'];
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $listcomponent = count($result_screenData);
    $resultSet['tab_heading'] = '';
    $resultSet['tab_headingData'] = '';
    $resultSet['tab_bottomData'] = '';


    if (isset($componentData[1]['html'])) {
        $resultSet['tab_heading'] = $componentData[1]['html'];
        if (isset($componentData[1]['style'])) {
            $cssProperty = $componentData[1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }

    if (isset($componentData[1]['children'][0]['html']) && $resultSet['tab_heading'] == '') {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['html'];
        if (isset($componentData[1]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }

    if (isset($componentData[2]['html'])) {
        $HtmlWithTags = $componentData[2];
        $resultSet['tab_headingData'] = ObjectToHtml($HtmlWithTags);

        if (isset($componentData[2]['style'])) {
            $cssProperty = $componentData[2]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface2'] = $resultAttributes['font_typeface'];
            $resultSet['font_color2'] = $resultAttributes['font_color'];
            $resultSet['font_size2'] = $resultAttributes['font_size'];
        }
    }

    if (isset($componentData[1]['children'][1]) && $resultSet['tab_headingData'] == '') {
        $HtmlWithTags = $componentData[1]['children'][1];
        $resultSet['tab_headingData'] = ObjectToHtml($HtmlWithTags);


        if (isset($componentData[1]['children'][1]['style'])) {
            $cssProperty = $componentData[1]['children'][1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface2'] = $resultAttributes['font_typeface'];
            $resultSet['font_color2'] = $resultAttributes['font_color'];
            $resultSet['font_size2'] = $resultAttributes['font_size'];
        }
    }

    if (isset($componentData[2]['children']) && $resultSet['tab_headingData'] == '') {
        $HtmlWithTags = $componentData[2];
        $resultSet['tab_headingData'] = ObjectToHtml($HtmlWithTags);
        if (isset($componentData[2]['children'][1]['style'])) {
            $cssProperty = $componentData[2]['children'][1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface2'] = $resultAttributes['font_typeface'];
            $resultSet['font_color2'] = $resultAttributes['font_color'];
            $resultSet['font_size2'] = $resultAttributes['font_size'];
        }
    }

    if (isset($componentData[3]['html'])) {
        $resultSet['tab_bottomData'] = $componentData[3]['html'];
        if (isset($componentData[3]['style'])) {
            $cssProperty = $componentData[3]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface3'] = $resultAttributes['font_typeface'];
            $resultSet['font_color3'] = $resultAttributes['font_color'];
            $resultSet['font_size3'] = $resultAttributes['font_size'];
        }
    }

    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }
    $resultSet['font_size1'] = str_replace("px", "", $resultSet['font_size1']);
    if ($resultSet['font_size1'] == '0' || $resultSet['font_size1'] == '') {
        $resultSet['font_size1'] = '14';
    }

    $resultSet['font_size2'] = str_replace("px", "", $resultSet['font_size2']);
    if ($resultSet['font_size2'] == '0' || $resultSet['font_size2'] == '') {
        $resultSet['font_size2'] = '12';
    }

    $resultSet['font_size3'] = str_replace("px", "", $resultSet['font_size3']);
    if ($resultSet['font_size3'] == '0' || $resultSet['font_size3'] == '') {
        $resultSet['font_size3'] = '12';
    }


    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;


    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_bottomData']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color3'] . "', '" . $resultSet['font_typeface3'] . "', '" . $resultSet['font_size3'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color2'] . "', '" . $resultSet['font_typeface2'] . "', '" . $resultSet['font_size2'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_heading']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 5 inserted successfully";
    }
}

function component_6($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData, $title) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $resultSet = array();
    $resultSet['comp_type'] = 6;
    $appQueryData = "select * from customfielddata where component_type_id='6'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    $actionShareData = '';
    $actionShareData = '';
    $resultSet['background_color'] = '#1ABC9C';
    if ($bgcolor != '') {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#FFFFFF';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';

    $resultSet['item_contenttypeface1'] = 'Arial';
    $resultSet['item_contentcolor1'] = '#FFFFFF';
    $resultSet['item_contentsize1'] = '16';
    if (isset($result_screenData[0]->componentfieldoption_id)) {
        $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    } else {
        $resultSet['componentfieldoption_id'] = '';
    }
    $resultSet['listNo'] = 1;
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }
    $heading = '';
    if (isset($componentData[1]['html'])) {
        $heading = $componentData[1]['html'];
        if (isset($componentData[1]['style'])) {
            $resultitemAttributes = getAttribute($componentData[1]['style']);
            $resultSet['item_contenttypeface1'] = $resultitemAttributes['font_typeface'];
            $resultSet['item_contentcolor1'] = $resultitemAttributes['font_color'];
            $resultSet['item_contentsize1'] = $resultitemAttributes['font_size'];
        }
    }

    if ($resultSet['item_contenttypeface1'] == '0' || $resultSet['item_contenttypeface1'] == '') {
        $resultSet['item_contenttypeface1'] = 'Arial';
    }
    if ($resultSet['item_contentsize1'] == '0' || $resultSet['item_contentsize1'] == '') {
        $resultSet['item_contentsize1'] = '16';
    }
    if ($resultSet['item_contentcolor1'] == '0' || $resultSet['item_contentcolor1'] == '') {
        $resultSet['item_contentcolor1'] = '#FFFFFF';
    }
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screenId . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

    $listcomponent = count($componentData);

    for ($ii = 0; $ii <= $listcomponent; $ii++) {
        $listno = $ii + 1;
        $resultSet['tab_headingData' . $ii] = '';
        if (isset($componentData[$ii]['children'][0]['class'])) {
            $icoCode = $componentData[$ii]['children'][0]['class'];
            $iconName1 = str_replace("fa ", "", $icoCode);
            $iconName = str_replace("fa-", "", $iconName1);
            $resultSet['tab_headingData' . $ii] = getIcomoon($iconName);
            if ($resultSet['tab_headingData' . $ii] == 'e045') {
                $resultSet['action_type_id'] = '2';
                $resultSet['action_data'] = $actionShareData;
            } elseif ($resultSet['tab_headingData' . $ii] == 'e047') {
                $resultSet['action_type_id'] = '6';
                $resultSet['action_data'] = $lintoId;
            }
        }
        if ($ii == $listcomponent) {
            $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screenId . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData' . $ii]) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "')";
        } else {
            if ($ii == '0') {
                $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screenId . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['item_contentcolor1'] . "', '" . addslashes($resultSet['item_contenttypeface1']) . "', '" . $resultSet['item_contentsize1'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
            } else {
                $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screenId . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData' . $ii]) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
            }
        }
    }


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
  VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 6 inserted successfully";
    }
}

function component_7($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $retailsData) {



    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 7;
    $appQueryData = "select * from customfielddata where component_type_id='7'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['display'] = $display;
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['height'] = '480';
    $resultSet['width'] = '480';
    $resultSet['heightImg'] = '480';
    $resultSet['widthImg'] = '480';
    $resultSet['tab_heading'] = '';

    if ($display == '2') {
        $resultSet['height'] = '150';
        $resultSet['width'] = '300';
        $resultSet['heightImg'] = '150';
        $resultSet['widthImg'] = '300';
    }
    if ($videoUrl == '') {
        $mediaType = 'image';
    } else {
        $mediaType = 'video';
    }
    $cssProperty = '';

    if (isset($componentData[1]['children'][1]['style']) && $bgcolor == '') {
        $cssProperty = $componentData[1]['children'][1]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['background_color'] = $resultAttributes['background_color'];
    }

    if (isset($componentData[2]['style']) && $resultSet['background_color'] == '#FFFFFF') {
        $cssProperty = $componentData[2]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['background_color'] = $resultAttributes['background_color'];
    }
    if ($resultSet['background_color'] == '') {
        $resultSet['background_color'] = '#FFFFFF';
    }

    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL, NULL, NULL,NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $resultSet['tab_headingData'] = '';
    $listcomponent = count($result_screenData);
    $resultSet['tab_heading'] = '';
    if (isset($componentData[1]['children'][0]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['children'][0]['src'];
        if (isset($componentData[1]['children'][0]['children'][0]['data-height'])) {
            $resultSet['heightImg'] = $componentData[1]['children'][0]['children'][0]['data-height'];
            $resultSet['widthImg'] = $componentData[1]['children'][0]['children'][0]['data-width'];
        }
    }
    if ($resultSet['tab_heading'] == '' && isset($componentData[1]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['src'];
        if (isset($componentData[1]['children'][0]['data-height'])) {
            $resultSet['heightImg'] = $componentData[1]['children'][0]['data-height'];
            $resultSet['widthImg'] = $componentData[1]['children'][0]['data-width'];
        }
    }
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;

    if (isset($componentData[1]['children'][1]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[1]['children'][1]['children'][0]['html'];
        if (isset($componentData[1]['children'][1]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][1]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
            $resultSet['font_color'] = $resultAttributes['font_color'];
            $resultSet['font_size'] = $resultAttributes['font_size'];
        }
    }
    if (isset($componentData[2]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
        if (isset($componentData[2]['children'][0]['style'])) {
            $cssProperty = $componentData[2]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
            $resultSet['font_color'] = $resultAttributes['font_color'];
            $resultSet['font_size'] = $resultAttributes['font_size'];
        }
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL, NULL, '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL,NULL,NULL, '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $mediaType . "', NULL, '" . addslashes($resultSet['tab_heading']) . "','" . $videoUrl . "','" . $resultSet['heightImg'] . "', '" . $resultSet['widthImg'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";
    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";
    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 7 inserted successfully";
    }
}

function component_9($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 9;
    $appQueryData = "select * from customfielddata where component_type_id='9'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['height'] = '18';
    $resultSet['width'] = '18';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['tab_headingDatatypeface'] = '';
    $resultSet['tab_headingDatacolor'] = '#8b8b8b';
    $resultSet['tab_headingDatasize'] = '20';
    $resultSet['item_contenttypeface'] = '';
    $resultSet['item_contentsize'] = '14';
    $resultSet['item_contentcolor'] = '#8b8b8b';
    $resultSet['Descriptiontypeface'] = '';
    $resultSet['Descriptionsize'] = '14';
    $resultSet['Descriptioncolor'] = '#8b8b8b';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $bgcolr = explode(';', $cssProperty);
        foreach ($bgcolr as $KeyProp => $valueprop) {
            $bgcolrProp = explode(':', $valueprop);
            if ($bgcolrProp[0] == 'font-family') {
                $resultSet['font_typeface'] = addslashes($bgcolrProp[1]);
            }
            if ($bgcolrProp[0] == ' font-size') {
                $resultSet['font_size'] = $bgcolrProp[1];
            }
            if ($bgcolrProp[0] == ' color') {
                $resultSet['font_color'] = $bgcolrProp[1];
            }
        }
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $listcomponent = count($result_screenData);
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    if (isset($componentData[1]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['src'];
    } else {
        $resultSet['tab_heading'] = '';
    }
    //$resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
    if (isset($componentData[2]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];

        if (isset($componentData[2]['children'][0]['style'])) {
            $resultAttributes = getAttribute($componentData[2]['children'][0]['style']);
            $resultSet['tab_headingDatatypeface'] = $resultAttributes['font_typeface'];
            $resultSet['tab_headingDatacolor'] = $resultAttributes['font_color'];
            $resultSet['tab_headingDatasize'] = $resultAttributes['font_size'];
        }
    } else {
        $resultSet['tab_headingData'] = '';
    }

    if (isset($componentData[2]['children'][1]['html'])) {
        $resultSet['item_content'] = $componentData[2]['children'][1]['html'];
        if (isset($componentData[2]['children'][1]['style'])) {
            $resultitemAttributes = getAttribute($componentData[2]['children'][1]['style']);
            $resultSet['item_contenttypeface'] = $resultitemAttributes['font_typeface'];
            $resultSet['item_contentcolor'] = $resultitemAttributes['font_color'];
            $resultSet['item_contentsize'] = $resultitemAttributes['font_size'];
        }
    } else {
        $resultSet['item_content'] = '';
    }

    if (isset($componentData[2]['children'][3]['html'])) {
        $resultSet['Description'] = $componentData[2]['children'][3]['html'];
        if (isset($componentData[2]['children'][3]['style'])) {
            $resultDescriptionAttributes = getAttribute($componentData[2]['children'][3]['style']);
            $resultSet['Descriptiontypeface'] = $resultDescriptionAttributes['font_typeface'];
            $resultSet['Descriptioncolor'] = $resultDescriptionAttributes['font_color'];
            $resultSet['Descriptionsize'] = $resultDescriptionAttributes['font_size'];
        }
    } elseif (isset($componentData[2]['children'][2]['html'])) {
        $resultSet['Description'] = $componentData[2]['children'][2]['html'];
        if (isset($componentData[2]['children'][2]['style'])) {
            $resultDescriptionAttributes = getAttribute($componentData[2]['children'][2]['style']);
            $resultSet['Descriptiontypeface'] = $resultDescriptionAttributes['font_typeface'];
            $resultSet['Descriptioncolor'] = $resultDescriptionAttributes['font_color'];
            $resultSet['Descriptionsize'] = $resultDescriptionAttributes['font_size'];
        }
    } else {
        $resultSet['Description'] = '';
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    if ($resultSet['tab_headingDatasize'] == '0' || $resultSet['tab_headingDatasize'] == '') {
        $resultSet['tab_headingDatasize'] = '20';
    }
    if ($resultSet['Descriptionsize'] == '0' || $resultSet['Descriptionsize'] == '') {
        $resultSet['Descriptionsize'] = '14';
    }
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'image', NULL,'" . addslashes($resultSet['tab_heading']) . "', '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['tab_headingDatacolor'] . "', '" . $resultSet['tab_headingDatatypeface'] . "', '" . $resultSet['tab_headingDatasize'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['Description']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['Descriptioncolor'] . "', '" . $resultSet['Descriptiontypeface'] . "', '" . $resultSet['Descriptionsize'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['item_content']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['item_contentcolor'] . "', '" . $resultSet['item_contenttypeface'] . "', '" . $resultSet['item_contentsize'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '6', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 9 inserted successfully";
    }
    // insertToDb($resultSet);
}

function component_13($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $actn1 = $actn2 = 0;
    $resultSet = array();
    $resultSet['comp_type'] = 13;
    $appQueryData = "select * from customfielddata where component_type_id='13'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($videoUrl == '') {
        $mediaType = 'image';
    } else {
        $mediaType = 'video';
    }

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';

    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = '20';

    $resultSet['font_typeface2'] = $resultSet['font_typeface'];
    $resultSet['font_color2'] = $resultSet['font_color'];
    $resultSet['font_size2'] = '20';

    $resultSet['font_typeface3'] = $resultSet['font_typeface'];
    $resultSet['font_color3'] = $resultSet['font_color'];
    $resultSet['font_size3'] = '14';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;
    $height = '1080';
    $width = '1920';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }


    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

    $listcomponent = count($result_screenData);

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['tab_img'] = '';
    if (isset($componentData[1]['children'][0]['src'])) {
        $resultSet['tab_img'] = $componentData[1]['children'][0]['src'];
        if (isset($componentData[1]['children'][0]['data-width'])) {
            $height = $componentData[1]['children'][0]['data-height'];
            $width = $componentData[1]['children'][0]['data-width'];
        }
    }

    if (isset($componentData[1]['children'][3]['children'][0]['html'])) {
        $resultSet['heading'] = $componentData[1]['children'][3]['children'][0]['html'];
        if (isset($componentData[1]['children'][3]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][3]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    } else {
        $resultSet['heading'] = '';
    }

    if (isset($componentData[2]['children'][0]['html'])) {
        $resultSet['subheading'] = $componentData[2]['children'][0]['html'];
        if (isset($componentData[2]['children'][0]['style'])) {
            $cssProperty = $componentData[2]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface2'] = $resultAttributes['font_typeface'];
            $resultSet['font_color2'] = $resultAttributes['font_color'];
            $resultSet['font_size2'] = $resultAttributes['font_size'];
        }
    } else {
        $resultSet['subheading'] = '';
    }
    $resultSet['description'] = '';
    if (isset($componentData[2]['children'][1]['html'])) {
        $resultSet['description'] = ObjectToHtml($componentData[2]['children'][1]);
        if (isset($componentData[2]['children'][1]['style'])) {
            $cssProperty = $componentData[2]['children'][1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface3'] = $resultAttributes['font_typeface'];
            $resultSet['font_color3'] = $resultAttributes['font_color'];
            $resultSet['font_size3'] = $resultAttributes['font_size'];
        }
    }
    if (isset($componentData[2]['children'][0]['children'][0]['html']) && $resultSet['description'] == '') {
        $resultSet['description'] = ObjectToHtml($componentData[2]['children'][1]);
        if (isset($componentData[2]['children'][1]['style'])) {
            $cssProperty = $componentData[2]['children'][1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface3'] = $resultAttributes['font_typeface'];
            $resultSet['font_color3'] = $resultAttributes['font_color'];
            $resultSet['font_size3'] = $resultAttributes['font_size'];
        }
    }
    if (isset($componentData[2]['children'][0]['children'][1]['html']) && $resultSet['description'] == '') {
        $resultSet['description'] = ObjectToHtml($componentData[2]['children'][1]);
        if (isset($componentData[2]['children'][1]['style'])) {
            $cssProperty = $componentData[2]['children'][1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface3'] = $resultAttributes['font_typeface'];
            $resultSet['font_color3'] = $resultAttributes['font_color'];
            $resultSet['font_size3'] = $resultAttributes['font_size'];
        }
    }
    $resultSet['tab_share'] = '';
    $actionShareData = array();
    $actionShareData = json_encode(array('shared_text' => $data_shared_text, 'shared_link' => $data_shared_link), JSON_UNESCAPED_UNICODE);
    if (isset($componentData[2]['children'][0]['children'][0]['src'])) {
        $iconSet = $componentData[2]['children'][0]['children'][0]['class'];
        $iconName1 = str_replace("icon_upload_img ", "", $iconSet);
        $iconName = str_replace("icon-", "", $iconName1);
        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $iconId = $rowiconFetch['icomoon_code'];
        $resultSet['tab_share'] = $iconId;
        $actn2 = 2;
    }
    if (isset($componentData[2]['children'][2]['children'][0]['class']) && $resultSet['tab_share'] == '') {
        $iconSet = $componentData[2]['children'][2]['children'][0]['class'];
        $iconName1 = str_replace("icon_upload_img ", "", $iconSet);
        $iconName = str_replace("icon-", "", $iconName1);
        if (preg_match('/share/', $iconName)) {
            $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
            $appQueryicon = $dbCon->query($app_icon);
            $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
            $iconId = $rowiconFetch['icomoon_code'];
            $resultSet['tab_share'] = $iconId;
            $actn2 = 2;
        }
    }
    if (isset($componentData[2]['children'][3]['children'][0]['class']) && $resultSet['tab_share'] == '') {
        $iconSet = $componentData[2]['children'][3]['children'][0]['class'];
        $iconName1 = str_replace("icon_upload_img ", "", $iconSet);
        $iconName = str_replace("icon-", "", $iconName1);
        if (preg_match('/share/', $iconName)) {
            $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
            $appQueryicon = $dbCon->query($app_icon);
            $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
            $iconId = $rowiconFetch['icomoon_code'];
            $resultSet['tab_share'] = $iconId;
            $actn2 = 2;
        }
    }

    $resultSet['tab_fav'] = '';
    if (isset($componentData[2]['children'][1]['children'][0]['class'])) {
        $iconSet = $componentData[2]['children'][1]['children'][0]['class'];

        $iconName1 = str_replace("icon_upload_img ", "", $iconSet);
        $iconName = str_replace("icon-", "", $iconName1);


        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";

        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $resultSet['tab_fav'] = $rowiconFetch['icomoon_code'];
        $actn1 = 4;
    }
    if (isset($componentData[2]['children'][2]['children'][0]['class']) && $resultSet['tab_fav'] == '') {

        $iconSet = $componentData[2]['children'][2]['children'][0]['class'];
        $iconName1 = str_replace("icon_upload_img ", "", $iconSet);
        $iconName = str_replace("icon-", "", $iconName1);
         
         if (preg_match('/favorite/', $iconName)) {
        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $iconId = $rowiconFetch['icomoon_code'];
        $resultSet['tab_fav'] = $iconId;
         $actn1=4;
         }
         
    }
    if (isset($componentData[2]['children'][3]['children'][0]['class']) && $resultSet['tab_fav'] == '') {
        $iconSet = $componentData[2]['children'][3]['children'][0]['class'];
        $iconName1 = str_replace("icon_upload_img ", "", $iconSet);
        $iconName = str_replace("icon-", "", $iconName1);
       
        if (preg_match('/favorite/', $iconName)) {
            $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
            $appQueryicon = $dbCon->query($app_icon);
            $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
            $iconId = $rowiconFetch['icomoon_code'];
            $resultSet['tab_fav'] = $iconId;
            $actn1 = 4;
        }
    }
    if (isset($componentData[2]['children'][3]['children'][0]['class']) && $resultSet['tab_fav'] == '') {
        $iconSet = $componentData[2]['children'][3]['children'][0]['class'];
        $iconName1 = str_replace("icon_upload_img ", "", $iconSet);
        $iconName = str_replace("icon-", "", $iconName1);

        if (preg_match('/favorite/', $iconName)) {
            $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
            $appQueryicon = $dbCon->query($app_icon);
            $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
            $iconId = $rowiconFetch['icomoon_code'];
            $resultSet['tab_fav'] = $iconId;
            $actn1 = 4;
        }
    }

    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }
    if ($resultSet['font_size1'] == '0' || $resultSet['font_size1'] == '') {
        $resultSet['font_size1'] = '12';
    }
    if ($resultSet['font_size2'] == '0' || $resultSet['font_size2'] == '') {
        $resultSet['font_size2'] = '12';
    }
    if ($resultSet['font_size3'] == '0' || $resultSet['font_size3'] == '') {
        $resultSet['font_size3'] = '12';
    }

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'image', NULL,'" . addslashes($resultSet['tab_img']) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "',  '" . $mediaType . "', NULL, '" . addslashes($resultSet['tab_img']) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "',  '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['heading']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['subheading']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color2'] . "',  '" . $resultSet['font_typeface2'] . "', '" . $resultSet['font_size2'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['description']) . "', NULL, NULL,'24', '24', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color3'] . "', '" . $resultSet['font_typeface3'] . "', '" . $resultSet['font_size3'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[5]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '6', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_fav']) . "', NULL, NULL,'16', '16', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b',   'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $actn1 . "', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[6]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '7', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_share']) . "', NULL, NULL,'16', '16', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $actn2 . "', '" . addslashes($actionShareData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 13 inserted successfully";
    }
}

function component_14($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 14;
    $appQueryData = "select * from customfielddata where component_type_id='14'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    $height = '1000';
    $width = '1500';
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#FFFFFF';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['font_typeface2'] = $resultSet['font_typeface'];
    $resultSet['font_color2'] = $resultSet['font_color'];
    $resultSet['font_size2'] = $resultSet['font_size'];
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $listcomponent = count($result_screenData);

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['tab_heading'] = '';

    if (isset($componentData[1]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['src'];
        if (isset($componentData[1]['children'][0]['data-height'])) {
            $height = $componentData[1]['children'][0]['data-height'];
            $width = $componentData[1]['children'][0]['data-width'];
        }
    }


    if ($resultSet['tab_heading'] == '' && isset($componentData[0]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[0]['children'][0]['src'];
        if (isset($componentData[0]['children'][0]['data-height'])) {
            $height = $componentData[0]['children'][0]['data-height'];
            $width = $componentData[0]['children'][0]['data-width'];
        }
    }

    //$resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
    if (isset($componentData[1]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[1]['children'][0]['html'];
        if (isset($componentData[1]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);

            if ($resultAttributes['font_typeface'] != '') {
                $resultSet['font_typeface2'] = $resultAttributes['font_typeface'];
            }
            if ($resultAttributes['font_color'] != '') {
                $resultSet['font_color2'] = $resultAttributes['font_color'];
            }
            if ($resultAttributes['font_size'] != '') {
                $resultSet['font_size2'] = $resultAttributes['font_size'];
            }
        }
    } else {
        $resultSet['tab_headingData'] = '';
    }
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];

    if (isset($componentData[0]['children'][3]['children'][0]['html'])) {
        $resultSet['heading'] = $componentData[0]['children'][3]['children'][0]['html'];
        if (isset($componentData[0]['children'][3]['children'][0]['style'])) {
            $cssProperty = $componentData[0]['children'][3]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);

            if ($resultAttributes['font_typeface'] != '') {
                $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            }
            if ($resultAttributes['font_color'] != '') {
                $resultSet['font_color1'] = $resultAttributes['font_color'];
            }
            if ($resultAttributes['font_size'] != '') {
                $resultSet['font_size1'] = $resultAttributes['font_size'];
            }
        }
    } elseif (isset($componentData[1]['children'][3]['children'][0]['html'])) {
        $resultSet['heading'] = $componentData[1]['children'][3]['children'][0]['html'];
        if (isset($componentData[1]['children'][3]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][3]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);

            if ($resultAttributes['font_typeface'] != '') {
                $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            }
            if ($resultAttributes['font_color'] != '') {
                $resultSet['font_color1'] = $resultAttributes['font_color'];
            }
            if ($resultAttributes['font_size'] != '') {
                $resultSet['font_size1'] = $resultAttributes['font_size'];
            }
        }
    } else {
        $resultSet['heading'] = '';
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'image', NULL,'" . addslashes($resultSet['tab_heading']) . "', '" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['heading']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[5]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '6', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color2'] . "', '" . $resultSet['font_typeface2'] . "', '" . $resultSet['font_size2'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 14 inserted successfully";
    }
    // insertToDb($resultSet);
}

function component_15($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData) {


    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 15;
    $appQueryData = "select * from customfielddata where component_type_id='15'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    $resultSet['background_color'] = '#FFFFFF';
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    if (isset($result_screenData[0]->componentfieldoption_id)) {
        $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    } else {
        $resultSet['componentfieldoption_id'] = '';
    }
    $resultSet['listNo'] = 1;
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $listcomponent = count($componentData);

    for ($ii = 0; $ii <= $listcomponent; $ii++) {

        $listno = $ii + 1;
        $resultSet['componentfieldoption_id'] = $result_screenData[$ii]->componentfieldoption_id;
        if (isset($componentData[0]['children'][0]['children'][$ii]['children'][0]['html'])) {
            $resultSet['tab_heading' . $ii] = $componentData[0]['children'][0]['children'][$ii]['children'][0]['html'];
            if (isset($componentData[0]['children'][0]['children'][$ii]['children'][0]['style'])) {/*
              $cssProperty = $componentData[0]['children'][0]['children'][$ii]['children'][0]['style'];
              $resultAttributes = getAttribute($cssProperty);
              if($resultAttributes['font_typeface'] !=''){
              $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
              }
              if($resultAttributes['font_color'] !=''){
              $resultSet['font_color'] = $resultAttributes['font_color'];
              }
              if($resultAttributes['font_size'] !=''){
              $resultSet['font_size'] = $resultAttributes['font_size'];
              } */
            }
        } else {
            $resultSet['tab_heading' . $ii] = '';
        }


        if (isset($componentData[1]['children'][$ii])) {
            $resultSet['tab_headingData' . $ii] = ObjectToHtml($componentData[1]['children'][$ii]);



            if (isset($componentData[1]['children'][$ii]['children'][0]['style'])) {/*
              $cssProperty = $componentData[1]['children'][$ii]['children'][0]['style'];
              $resultAttributes = getAttribute($cssProperty);
              if($resultAttributes['font_typeface'] !=''){
              $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
              }
              if($resultAttributes['font_color'] !=''){
              $resultSet['font_color'] = $resultAttributes['font_color'];
              }
              if($resultAttributes['font_size'] !=''){
              $resultSet['font_size'] = $resultAttributes['font_size'];
              } */
            }
        } else {
            $resultSet['tab_headingData' . $ii] = '';
        }
        $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
        if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
            $resultSet['font_size'] = '12';
        }
        $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_heading' . $ii]) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
        $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
        $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

        if ($ii == $listcomponent) {
            $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData' . $ii]) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";
        } else {
            $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData' . $ii]) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
        }
    }

    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 15 inserted successfully";
    }
}

function component_17($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 17;
    $appQueryData = "select * from customfielddata where component_type_id='17'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }


    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['display'] = $display;
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['height'] = '480';
    $resultSet['width'] = '480';

    $resultSet['tab_heading'] = '';

    if ($display == '2') {
        $resultSet['height'] = '150';
        $resultSet['width'] = '300';
        $resultSet['heightImg'] = '150';
        $resultSet['widthImg'] = '300';
    }
    if ($videoUrl == '') {
        $mediaType = 'image';
    } else {
        $mediaType = 'video';
    }
    $cssProperty = '';




    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "','" . json_encode($retailsData) . "', NULL, NULL, NULL, NULL,NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";


    $resultSet['tab_heading'] = '';

    if (isset($componentData['html'])) {
        $resultSet['tab_heading'] = $componentData['html'];
    }
    if (isset($componentData[0]['html']) && $resultSet['tab_heading'] == '') {
        $resultSet['tab_heading'] = $componentData[0]['html'];
    }

//    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL, NULL, '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
//    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL,NULL,NULL, '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_heading']) . "', NULL, '','" . $videoUrl . "','" . $resultSet['heightImg'] . "', '" . $resultSet['widthImg'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";
    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";
    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 17 inserted successfully";
    }
}

function component_19($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 19;
    $appQueryData = "select * from customfielddata where component_type_id='19'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '540';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

    $listcomponent = count($result_screenData);

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;

    if (isset($componentData[1]['children'][0]['src'])) {
        $resultSet['tab_img'] = $componentData[1]['children'][0]['src'];
        if (isset($componentData[1]['children'][0]['data-width'])) {
            $height = $componentData[1]['children'][0]['data-height'];
            $width = $componentData[1]['children'][0]['data-width'];
        }
    } else {
        $resultSet['tab_img'] = '';
    }
    $resultSet['tab_heading'] = '';
    //$resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
    if (isset($componentData[1]['children'][3]['children'][0]['html'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][3]['children'][0]['html'];

        if (isset($componentData[1]['children'][3]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][3]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }
    if ($resultSet['tab_heading'] == '' && isset($componentData[1]['children'][2]['children'][0]['html'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][2]['children'][0]['html'];

        if (isset($componentData[1]['children'][2]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][2]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }
    $actionShareData = array();
    $actionShareData = json_encode(array('shared_text' => $data_shared_text, 'shared_link' => $data_shared_link), JSON_UNESCAPED_UNICODE);

    if (isset($componentData[2]['children'][0]['children'][0]['src'])) {
        $iconSet = $componentData[2]['children'][0]['children'][0]['class'];
        $iconName1 = str_replace("icon_upload_img ", "", $iconSet);
        $iconName = str_replace("icon-", "", $iconName1);
        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $iconId = $rowiconFetch['icomoon_code'];

        $resultSet['tab_share'] = $iconId;
    } else {
        $resultSet['tab_share'] = 'e045';
    }
    if (isset($componentData[2]['children'][1]['children'][0]['class'])) {
        $iconSet = $componentData[2]['children'][1]['children'][0]['class'];

        $iconName1 = str_replace("icon_upload_img ", "", $iconSet);
        $iconName = str_replace("icon-", "", $iconName1);


        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";

        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $resultSet['tab_fav'] = $rowiconFetch['icomoon_code'];
    } else {
        $resultSet['tab_fav'] = 'e046';
    }
    if ($videoUrl == '') {
        $mediaType = 'image';
    } else {
        $mediaType = 'video';
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $resultSet['font_size1'] = str_replace("px", "", $resultSet['font_size1']);
    if ($resultSet['font_size1'] == '0' || $resultSet['font_size1'] == '') {
        $resultSet['font_size1'] = '12';
    }
    $cssProperty = '';
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $mediaType . "',null,'" . $resultSet['tab_img'] . "','" . $videoUrl . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_heading']) . "',NULL ,NULL, NULL,'24', '12', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL,NULL, NULL,'24', '12', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', 'icomoon.ttf', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_fav']) . "', NULL,NULL, NULL,'16', '16', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '4', '" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_share']) . "', NULL,NULL, NULL,'16', '16', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '2', '" . addslashes($actionShareData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 19 inserted successfully";
    }
}

function component_22($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 22;
    $appQueryData = "select * from customfielddata where component_type_id='22'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '1080';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }


    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['html'])) {
        $heading = $componentData[1]['html'];
    }

    $audio_duration = '';

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($dataaudiolength) . "',null,null, '" . addslashes($data_audiolink) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $heading . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', NULL, NULL)";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 22 inserted successfully";
    }
}

function component_24($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {
    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 24;
    $appQueryData = "select * from customfielddata where component_type_id='24'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';

    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';

    if (isset($componentData[1]['children']['0']['placeholder'])) {
        $hint = $componentData[1]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[1]['children']['0']['minlength'])) {
        $minlength = $componentData[1]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[1]['children']['0']['maxlength'])) {
        $maxlength = $componentData[1]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[1]['children']['0']['type'])) {
        $inputType = $componentData[1]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[1]['children']['0']['required'])) {
        $required = "1";
    }

    $audio_duration = '';

    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '14', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 24 inserted successfully";
    }
}

function component_25($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {
    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 25;
    $appQueryData = "select * from customfielddata where component_type_id='25'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '1080';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['children']['0']['html'])) {
        $heading = $componentData[1]['children']['0']['html'];
    }
    if (isset($componentData[2]['children']['0']['placeholder'])) {
        $hint = $componentData[2]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[2]['children']['0']['minlength'])) {
        $minlength = $componentData[2]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[2]['children']['0']['maxlength'])) {
        $maxlength = $componentData[2]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[2]['children']['0']['type'])) {
        $inputType = $componentData[2]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[2]['children']['0']['required'])) {
        $required = '1';
    }

    $audio_duration = '';
    

    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => ''));
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "',null,null, '" . addslashes($data_audiolink) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '14', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 25 inserted successfully";
    }
}

function component_26($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {
    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 26;
    $appQueryData = "select * from customfielddata where component_type_id='26'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '1080';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['html'])) {
        $heading = $componentData[1]['html'];
    }
    if (isset($componentData[2]['children']['0']['placeholder'])) {
        $hint = $componentData[2]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[2]['children']['0']['minlength'])) {
        $minlength = $componentData[2]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[2]['children']['0']['maxlength'])) {
        $maxlength = $componentData[2]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[2]['children']['0']['type'])) {
        $inputType = $componentData[2]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[2]['children']['0']['required'])) {
        $required = '1';
    }

    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "',null,null, '" . addslashes($data_audiolink) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '14', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 26 inserted successfully";
    }
}

function component_28($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {


    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 28;
    $appQueryData = "select * from customfielddata where component_type_id='28'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';

    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';

    if (isset($componentData[1]['children'][2]['placeholder'])) {
        $hint = $componentData[1]['children'][2]['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[1]['children'][2]['minlength'])) {
        $minlength = $componentData[1]['children'][2]['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[1]['children'][2]['maxlength'])) {
        $maxlength = $componentData[1]['children'][2]['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[1]['children'][2]['tag'])) {
        $inputType = $componentData[1]['children'][2]['tag'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[1]['children'][2]['required'])) {
        $required = "1";
    }

    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 28 inserted successfully";
    }
}

function component_29($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $linkphone, $bgcolor, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 29;
    $appQueryData = "select * from customfielddata where component_type_id='29'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['font_typeface2'] = $resultSet['font_typeface'];
    $resultSet['font_color2'] = $resultSet['font_color'];
    $resultSet['font_size2'] = $resultSet['font_size'];
    $resultSet['display'] = $display;
    $resultSet['action_type_id'] = '9';
    $resultSet['action_data'] = '';
    $resultSet['action_data1'] = '';
    $resultSet['action_data2'] = '';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

    $listcomponent = count($result_screenData);
    $resultSet['heading'] = '';
    if (isset($componentData[2]['html'])) {
        $resultSet['heading'] = $componentData[2]['html'];
        if (isset($componentData[2]['style'])) {
            $cssProperty = $componentData[2]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface2'] = $resultAttributes['font_typeface'];
            $resultSet['font_color2'] = $resultAttributes['font_color'];
            $resultSet['font_size2'] = $resultAttributes['font_size'];
        }
    }
    if ($resultSet['heading'] && isset($componentData[2]['children'][0]['html'])) {
        $resultSet['heading'] = $componentData[2]['children'][0]['html'];
        if (isset($componentData[2]['children'][0]['style'])) {
            $cssProperty = $componentData[2]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface2'] = $resultAttributes['font_typeface'];
            $resultSet['font_color2'] = $resultAttributes['font_color'];
            $resultSet['font_size2'] = $resultAttributes['font_size'];
        }
    }

    $resultSet['subheading'] = '';
    if (isset($componentData[1]['html'])) {
        $resultSet['subheading'] = $componentData[1]['html'];
        if (isset($componentData[1]['style'])) {
            $cssProperty = $componentData[1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }
    if ($resultSet['subheading'] == '' && isset($componentData[1]['children'][1]['children'][0]['html'])) {
        $resultSet['subheading'] = $componentData[1]['children'][1]['children'][0]['html'];
        if (isset($componentData[1]['children'][1]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][1]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }

    if ($resultSet['subheading'] == '' && isset($componentData[2]['children'][1]['html'])) {
        $resultSet['subheading'] = $componentData[2]['children'][0]['html'];
    }

    if (isset($componentData[3]['children'][1]['children'][0]['src'])) {
        $resultSet['image1'] = $componentData[3]['children'][1]['children'][0]['src'];
        if (isset($componentData[3]['children'][1]['children'][0]['data-linkphone'])) {
            $resultSet['action_data1'] = $componentData[3]['children'][1]['children'][0]['data-linkphone'];
        }
    } else {
        $resultSet['image1'] = '';
    }
    if (isset($componentData[3]['children'][0]['children'][0]['src'])) {
        $resultSet['image2'] = $componentData[3]['children'][0]['children'][0]['src'];
        if (isset($componentData[3]['children'][0]['children'][0]['data-linkemail'])) {
            $resultSet['action_data2'] = $componentData[3]['children'][0]['children'][0]['data-linkemail'];
        }
    } else {
        $resultSet['image2'] = '';
    }
    if (isset($componentData[1]['children'][0]['children'][0]['src'])) {
        $resultSet['image'] = $componentData[1]['children'][0]['children'][0]['src'];
    } else {
        $resultSet['image'] = '';
    }

    if ($linkphone != '') {
        $Contactnumber = $resultSet['image1'] = "http://instappy.com/images/contact_phone.png";
        $resultSet['action_data1'] = $linkphone;
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }
    $resultSet['font_size1'] = str_replace("px", "", $resultSet['font_size1']);
    if ($resultSet['font_size1'] == '0' || $resultSet['font_size1'] == '') {
        $resultSet['font_size1'] = '12';
    }
    $resultSet['font_size2'] = str_replace("px", "", $resultSet['font_size2']);
    if ($resultSet['font_size2'] == '0' || $resultSet['font_size2'] == '') {
        $resultSet['font_size2'] = '12';
    }
    if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'image', '" . addslashes($resultSet['image']) . "',NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['subheading']) . "',NULL, NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['heading']) . "',NULL, NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color2'] . "', '" . $resultSet['font_typeface2'] . "', '" . $resultSet['font_size2'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "','image', '" . addslashes($resultSet['image2']) . "', NULL, '1', '1', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','5','" . addslashes($resultSet['action_data2']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "','image', '" . addslashes($resultSet['image1']) . "', NULL, '1', '1', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','1','" . addslashes($resultSet['action_data1']) . "')";
    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`,`image_url`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 29 inserted successfully";
    }
}

function component_30($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $linkphone, $bgcolor, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 30;
    $appQueryData = "select * from customfielddata where component_type_id='30'";

    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['display'] = $display;
    $actiondata1 = '';
    $actiondata2 = '';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['font_typeface2'] = $resultSet['font_typeface'];
    $resultSet['font_color2'] = $resultSet['font_color'];
    $resultSet['font_size2'] = $resultSet['font_size'];
    $resultSet['font_typeface3'] = $resultSet['font_typeface'];
    $resultSet['font_color3'] = $resultSet['font_color'];
    $resultSet['font_size3'] = $resultSet['font_size'];
    $resultSet['listNo'] = 1;
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $html2 = '';
    if (isset($componentData[2]['html'])) {
        $html2 = $componentData[2]['html'];
        if (isset($componentData[2]['style'])) {
            $cssProperty = $componentData[2]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface2'] = $resultAttributes['font_typeface'];
            $resultSet['font_color2'] = $resultAttributes['font_color'];
            $resultSet['font_size2'] = $resultAttributes['font_size'];
        }
    }

    $html1 = '';
    if (isset($componentData[1]['children'][1]['children'][0]['html'])) {
        $html1 = $componentData[1]['children'][1]['children'][0]['html'];
        if (isset($componentData[1]['children'][1]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][1]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }
    if ($html1 == '' && isset($componentData[1]['html'])) {
        $html1 = $componentData[1]['html'];
        if (isset($componentData[1]['style'])) {
            $cssProperty = $componentData[1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }


    if (isset($componentData[3]['children'][0]['children'][0]['src'])) {
        $iconName = 'envelope';
        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $src1 = $rowiconFetch['icomoon_code'];


        if (isset($componentData[3]['children'][1]['children'][0]['data-linkemail'])) {
            $actiondata1 = $componentData[3]['children'][1]['children'][0]['data-linkemail'];
        }
    } else {
        $src1 = '';
    }

    if (isset($componentData[3]['children'][0]['children'][0]['src'])) {
        $iconName = 'phone-heandset';
        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $src2 = $rowiconFetch['icomoon_code'];
        if (isset($componentData[3]['children'][0]['children'][0]['data-linkphone'])) {
            $actiondata2 = $componentData[3]['children'][0]['children']['data-linkphone'];
        }
    } else {
        $src2 = '';
    }
    $src3 = '';
    if (isset($componentData[1]['children'][0]['children'][1]['class'])) {
        $iconSet = $componentData[1]['children'][0]['children'][1]['class'];

        $iconName = str_replace("icon-", "", $iconSet);


        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";

        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $src3 = $rowiconFetch['icomoon_code'];
        if (isset($componentData[1]['children'][0]['children'][1]['style'])) {
            $cssProperty = $componentData[1]['children'][0]['children'][1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface3'] = $resultAttributes['font_typeface'];
            $resultSet['font_color3'] = $resultAttributes['font_color'];
            $resultSet['font_size3'] = $resultAttributes['font_size'];
        }
    }
    if ($src3 == '' && isset($componentData[1]['children'][0]['children'][0]['class'])) {
        $iconSet = $componentData[1]['children'][0]['children'][0]['class'];

        $iconName = str_replace("icon-", "", $iconSet);


        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";

        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $src3 = $rowiconFetch['icomoon_code'];
        if (isset($componentData[1]['children'][0]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][0]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface3'] = $resultAttributes['font_typeface'];
            $resultSet['font_color3'] = $resultAttributes['font_color'];
            $resultSet['font_size3'] = $resultAttributes['font_size'];
        }
    }

    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }
    $resultSet['font_size1'] = str_replace("px", "", $resultSet['font_size1']);
    if ($resultSet['font_size1'] == '0' || $resultSet['font_size1'] == '') {
        $resultSet['font_size1'] = '12';
    }
    if ($resultSet['font_size2'] == '0' || $resultSet['font_size2'] == '') {
        $resultSet['font_size2'] = '12';
    }
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . addslashes($resultSet['action_data']) . "'),";
    $listcomponent = count($result_screenData);
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($html2) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color2'] . "', '" . $resultSet['font_typeface2'] . "', '" . $resultSet['font_size2'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . addslashes($resultSet['action_data']) . "'),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', 'icomoon.ttf', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($html1) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . addslashes($resultSet['action_data']) . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($src1) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', 'icomoon.ttf', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','5','" . $actiondata1 . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[5]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '6', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($src3) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color3'] . "', 'icomoon.ttf', '37', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','1','" . $linkphone . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($src2) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', 'icomoon.ttf', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','1','" . $actiondata2 . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";
    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 30 inserted successfully";
    }
}

function component_32($bannervalue, $componentNumber, $app_id, $screenId) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 32;
    $appQueryData = "select * from customfielddata where component_type_id='32'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    $resultSet['background_color'] = '#FFFFFF';
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = '-1';
    $resultSet['position'] = '1';
    $resultSet['width'] = '1080';
    $resultSet['height'] = '540';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';

    $resultSet['font_size'] = '12';
    $resultSet['display'] = '2';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    if (isset($componentData[1]['children'][0]['class'])) {
        if ($componentData[1]['children'][0]['class'] == 'full_widget_img')
            $resultSet['display'] = '2';
    }
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";


    $i = 0;
    if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}

    foreach ($bannervalue as $bannerKey => $bannerValues) {

        
        if (isset($bannerValues['src']) ? $bannerValues['src'] : '') {

            $listno = $i + 1;
            $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;

            $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '" . $componentNumber . "','image',NULL, '" . $bannerValues['src'] . "',  '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
        }

        $i++;
    }
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '','',  '', '', '', '', NULL, NULL, '', NULL,NULL, NULL, NULL, NULL, NULL, '',NULL, '', '', '', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";

    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";
    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 32 inserted successfully";
    }
}

function component_33($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData) {

    $dbCon = content_db($componentData);
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 33;
    $appQueryData = "select * from customfielddata where component_type_id='33'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['action_type_id'] = '10';
    $resultSet['display'] = $display;
    $resultSet['action_data'] = '';
    $resultSet['tab_link'] = '';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }
    if (isset($componentData[1]['href'])) {
        $linkData = $componentData[1]['href'];
        $pos = strpos($linkData, 'http');
        if ($pos === false) {
            $linkData = 'http://' . $linkData;
        }

        $resultSet['tab_link'] = $linkData . "," . $linkData . "," . $linkData;
    }


    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['action_data'] . "'),";

    $listcomponent = count($result_screenData);

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;

    if (isset($componentData[0]['children'][0]['children'][0]['src'])) {
        $resultSet['tab_heading'] = str_replace("<br>", "", $componentData[0]['children'][0]['children'][0]['src']);
    } elseif (isset($componentData[1]['children'][0]['children'][0]['src'])) {
        $resultSet['tab_heading'] = str_replace("<br>", "", $componentData[1]['children'][0]['children'][0]['src']);
    } elseif (isset($componentData[2]['children'][0]['children'][0]['children'][0]['src'])) {
        $resultSet['tab_heading'] = str_replace("<br>", "", $componentData[2]['children'][0]['children'][0]['children'][0]['src']);
    } else {
        $resultSet['tab_heading'] = '';
    }


    //$resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
    if (isset($componentData[0]['children'][1]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = str_replace("<br>", "", $componentData[0]['children'][1]['children'][0]['html']);
    } elseif (isset($componentData[1]['children'][1]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = str_replace("<br>", "", $componentData[1]['children'][1]['children'][0]['html']);
    } elseif (isset($componentData[2]['children'][0]['children'][1]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = str_replace("<br>", "", $componentData[2]['children'][0]['children'][1]['children'][0]['html']);
    } else {
        $resultSet['tab_headingData'] = '';
    }


    if (isset($componentData[0]['children'][1]['children'][1]['html'])) {
        $resultSet['tab_text'] = str_replace("<br>", "", $componentData[0]['children'][1]['children'][1]['html']);
    } elseif (isset($componentData[1]['children'][1]['children'][2]['html'])) {
        $resultSet['tab_text'] = str_replace("<br>", "", $componentData[1]['children'][1]['children'][2]['html']);
    } elseif (isset($componentData[2]['children'][0]['children'][1]['children'][2]['html'])) {
        $resultSet['tab_text'] = str_replace("<br>", "", $componentData[2]['children'][0]['children'][1]['children'][2]['html']);
    } elseif (isset($componentData[1]['children'][1]['children'][0]['html'])) {
        $resultSet['tab_text'] = str_replace("<br>", "", $componentData[1]['children'][1]['children'][0]['html']);
    } else {
        $resultSet['tab_text'] = '';
    }

    if (isset($componentData[1]['children'][1]['children'][0]['children'][0]['html'])) {
        $resultSet['tab_text3'] = str_replace("<br>", "", $componentData[1]['children'][1]['children'][0]['children'][0]['html']);
    } elseif (isset($componentData[1]['children'][1]['children'][1]['html'])) {
        $resultSet['tab_text3'] = str_replace("<br>", "", $componentData[1]['children'][1]['children'][1]['html']);
    } else {
        $resultSet['tab_text3'] = '';
    }


    if (isset($componentData[1]['children'][1]['children'][0]['children'][1]['html'])) {
        $componentData[1]['children'][1]['children'][0]['children'][1]['html'];
        $resultSet['tab_text2'] = str_replace("&lt;br&gt;", "", $componentData[1]['children'][1]['children'][0]['children'][1]['html']);
    } else {
        $resultSet['tab_text2'] = '';
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'image', NULL,'" . addslashes($resultSet['tab_heading']) . "', '1000', '1000', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_text']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_text2']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_text3']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 33 inserted successfully";
    }
}

function component_36($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 36;
    $appQueryData = "select * from customfielddata where component_type_id='36'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '1080';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    /*   if (isset($componentData[1]['children']['0']['html'])) {
      $heading = $componentData[1]['children']['0']['html'];
      } */
    if (isset($componentData[1]['children'][2]['placeholder'])) {
        $hint = $componentData[1]['children'][2]['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[1]['children'][2]['minlength'])) {
        $minlength = $componentData[1]['children'][2]['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[1]['children'][2]['maxlength'])) {
        $maxlength = $componentData[1]['children'][2]['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[1]['children'][2]['tag'])) {
        $inputType = $componentData[1]['children'][2]['tag'];
    } else {
        $inputType = 'text';
    }

    if (isset($componentData[1]['children'][3]['children']['0']['class'])) {
        $icomoon1 = $componentData[1]['children'][3]['children']['0']['class'];
        $icomoon = getIcomoon($icomoon1);
    } else {
        $icomoon = '';
    }




    $required = '0';
    if (isset($componentData[1]['children'][3]['children']['0']['required'])) {
        $required = '1';
    }

    $audio_duration = '';

    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "icomoon" => "$icomoon", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "',null,null, '" . addslashes($data_audiolink) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 36 inserted successfully";
    }
}

function component_37($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 37;
    $appQueryData = "select * from customfielddata where component_type_id='37'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
//    if (isset($componentData[1]['html'])) {
//        $heading = $componentData[1]['html'];
//    }
    if (isset($componentData[2]['children']['0']['placeholder'])) {
        $hint = $componentData[2]['children']['0']['placeholder'];
    } else {
        $hint = 'Select';
    }
    if (isset($componentData[2]['children']['0']['minlength'])) {
        $minlength = $componentData[2]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[2]['children']['0']['maxlength'])) {
        $maxlength = $componentData[2]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }

    $inputType = 'Dropdown';

    $required = '0';
    if (isset($componentData[1]['children']['0']['required'])) {
        $required = '1';
    }

    if (isset($componentData[1]['children'][0]['children'])) {
        $droplist = $componentData[1]['children'][0]['children'];
        $listCount = count($droplist);
    } else {
        $droplist = '';
    }


    $audio_duration = '';

    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => '', "options" => $droplist));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '14', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 37 inserted successfully";
    }
}

function component_38($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 38;
    $appQueryData = "select * from customfielddata where component_type_id='38'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['children']['0']['html'])) {
        $heading = $componentData[1]['children']['0']['html'];
    }
    if (isset($componentData[2]['children']['0']['placeholder'])) {
        $hint = $componentData[2]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[2]['children']['0']['minlength'])) {
        $minlength = $componentData[2]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[2]['children']['0']['maxlength'])) {
        $maxlength = $componentData[2]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[2]['children']['0']['type'])) {
        $inputType = $componentData[2]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[2]['children']['0']['required'])) {
        $required = '1';
    }
    if (isset($componentData[1]['children']['0']['required'])) {
        $required = '1';
    }

    if (isset($componentData[2]['children'][0]['children'])) {
        $droplist = $componentData[2]['children'][0]['children'];
        $listCount = count($droplist);
    } else {
        $droplist = '';
    }
    $audio_duration = '';

    if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => '', "options" => $droplist));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "',null,null, '" . addslashes($data_audiolink) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '14', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 38 inserted successfully";
    }
}

function component_39($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType, $required) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 39;
    $appQueryData = "select * from customfielddata where component_type_id='39'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';

    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);

    $heading = '';
    $hint = '';
    $minlength = '';
    $maxlength = '';
    $inputType = 'ckeckbox';






    $numOfRadio = count($componentData);
    $radioVal = array();
    for ($i = 1; $i < $numOfRadio - 1; $i++) {
        if (isset($componentData[$i]['children']['1']['children']['0']['html'])) {
            $radioVal[] = $componentData[$i]['children']['1']['children']['0']['html'];
        }
    }

    $audio_duration = '';

    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => '', "options" => $radioVal));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 39 inserted successfully";
    }
}

function component_40($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 40;
    $appQueryData = "select * from customfielddata where component_type_id='40'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';

    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    if (isset($componentData[1]['children']['1']['children']['0']['style'])) {
        $cssProperty = $componentData[1]['children']['1']['children']['0']['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }


    $listcomponent = count($result_screenData);

    $heading = '';
    $hint = '';
    $minlength = '';
    $maxlength = '';
    $inputType = 'radio';
    $required = '0';





    $numOfRadio = count($componentData);
    $radioVal = array();
    for ($i = 1; $i < $numOfRadio - 1; $i++) {
        $radioVal[] = $componentData[$i]['children']['1']['children']['0']['html'];
    }


    $audio_duration = '';

    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => '', "options" => $radioVal));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL,'" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 40 inserted successfully";
    }
}

function component_42($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 42;
    $appQueryData = "select * from customfielddata where component_type_id='42'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['action_type_id'] = '10';
    $resultSet['display'] = $display;
    $resultSet['action_data'] = '';
    $resultSet['datalink'] = '';
    $resultSet['tab_image'] = '';
    $resultSet['tab_link'] = '';
    $resultSet['pageid'] = '';
    $resultSet['feedcount'] = '2';
    $resultSet['accesstoken'] = 'EAAXblGnn7joBAOyOciVXIAFUcHAFb2GNfNb35lSSkHIhqz1GhSD4Q2s5BormYZAmLEDHkR9S0eEH0BhIZCwE6S60nB92mZATsTU8eGEgTWOpmIzUTnGSy7x0PaBKdLwzKB8uRR6ZBpSotlL8Lp1RjXhksCpF7IMZD';
    /* $resultSet['accesstoken'] = 'EAAXblGnn7joBALZCFXvbR2HkPuoMaFDVn1PSyvPWnfNg9sGQn8L8jjLf1O7bTs9PzcuY0hFeXqpFPB9L8VJOlgw0qx32ZABYN6hZCK2zqoEfAPu7jLQLnD8jDcmOIqbG3SphPBfT8U84MBjZADdZCzlZArGqur2LUZD';
     */
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    if (isset($componentData[1]['href'])) {
        $resultSet['datalink'] = $componentData[1]['href'];
    } else {
        $resultSet['datalink'] = '';
    }
    $FBiconId = '';
    $arrowiconId = '';
    if (isset($componentData[1]['children'][0]['src'])) {
        $iconSet = $componentData[1]['children'][0]['src'];
        $iconName1 = str_replace("images/", "", $iconSet);
        $iconName = str_replace("Feed.jpg", "", $iconName1);
        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $FBiconId = $rowiconFetch['icomoon_code'];
        $arrow_icon = "select * from icomoon_icons where name like '%arrow%'";
        $arrow_iconQuery = $dbCon->query($arrow_icon);
        $rowarrowFetch = $arrow_iconQuery->fetch(PDO::FETCH_ASSOC);
        $arrowiconId = $rowarrowFetch['icomoon_code'];
    } else {
        $resultSet['tab_image'] = '';
    }

    if (isset($componentData[1]['href'])) {
        $resultSet['pageid'] = $componentData[1]['href'];
    } else {
        $resultSet['pageid'] = '';
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['action_data'] . "'),";
    $listcomponent = count($result_screenData);

    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    //$resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['pageid']) . "', NULL,'', '1000', '1150', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $resultSet['accesstoken'] . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $resultSet['feedcount'] . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $FBiconId . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#3c5998', 'icomoon.ttf', '26', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'Facebook', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '15', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[5]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '6', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $arrowiconId . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[6]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '7', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[7]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '8', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[8]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '9', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[9]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '10', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[10]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '11', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[11]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '12', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 42 inserted successfully";
    }
}

function component_43($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 43;
    $appQueryData = "select * from customfielddata where component_type_id='43'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['action_type_id'] = '10';
    $resultSet['display'] = $display;
    $resultSet['action_data'] = '';
    $resultSet['datalink'] = '';
    $resultSet['tab_image'] = '';
    $resultSet['tab_link'] = '';
    $resultSet['pageid'] = '';
    $resultSet['feedcount'] = '2';
    $resultSet['accesstoken'] = '8ToSgs6awJHVJJEHOZJmuLKyw';

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    if (isset($componentData[1]['href'])) {
        $resultSet['datalink'] = $componentData[1]['href'];
    }

    if (isset($componentData[1]['children'][0]['src'])) {
        $resultSet['tab_image'] = $componentData[1]['children'][0]['src'];
    }

    if (isset($componentData[1]['href'])) {
        $resultSet['pageid'] = $componentData[1]['href'];
    }
    $FBiconId = '';
    $arrowiconId = '';
    if (isset($componentData[1]['children'][0]['src'])) {
        $iconSet = $componentData[1]['children'][0]['src'];
        $iconName1 = str_replace("images/", "", $iconSet);
        $iconName = str_replace("Feed.jpg", "", $iconName1);
        $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";
        $appQueryicon = $dbCon->query($app_icon);
        $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
        $FBiconId = $rowiconFetch['icomoon_code'];
        $arrow_icon = "select * from icomoon_icons where name like '%arrow%'";
        $arrow_iconQuery = $dbCon->query($arrow_icon);
        $rowarrowFetch = $arrow_iconQuery->fetch(PDO::FETCH_ASSOC);
        $arrowiconId = $rowarrowFetch['icomoon_code'];
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['action_data'] . "'),";
    $listcomponent = count($result_screenData);
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $resultSet['pageid'] . "', NULL,'', '1000', '1150', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $resultSet['accesstoken'] . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '5WJP4yVfKQJD0B6LipVm5R6VIAVOzE5HlBmzuStGk77U8gEkQw', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '3235979882-5bnGagVFUBvkuTgNLzmQJLHzJbgQGcRecywB7Xo', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#3c5998', 'icomoon.ttf', '26', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '78Kw7Q3Cj7RgaYwjsD0pMUPdOTSh8D4ChNiJCvaPEABpx', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '15', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[5]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '6', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '1', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[6]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '7', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $FBiconId . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', 'icomoon.ttf', '26', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[7]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '8', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'Twitter', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '15', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[8]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '9', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $arrowiconId . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', 'icomoon.ttf', '16', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[9]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '10', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[10]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '11', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[11]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '12', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[12]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '13', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[13]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '14', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[14]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '15', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 43 inserted successfully";
    }
}

function component_44($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $lat, $long, $bgcolor) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 44;
    $appQueryData = "select * from customfielddata where component_type_id='44'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $listcomponent = count($result_screenData);

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['tab_headingData'] = '';
    if (isset($componentData[2]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
    }
    if (isset($componentData[1]['children'][0]['html']) && $resultSet['tab_headingData'] == '') {
        $resultSet['tab_headingData'] = $componentData[1]['children'][0]['html'];
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($lat) . "', NULL,NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($long) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 44 inserted successfully";
    }
}

function component_45($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData) {
    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 45;
    $appQueryData = "select * from customfielddata where component_type_id='45'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';


    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = 'button';
    if (isset($componentData[0]['value'])) {
        $heading = $componentData[0]['value'];
    }
    $inputType = 'submit';


    $audio_duration = '';

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '', '')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 50 inserted successfully";
    }
}

function component_46($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData) {


    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 46;
    $appQueryData = "select * from customfielddata where component_type_id='46'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#fab23d';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';


    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = 'button';
    if (isset($componentData[0]['value'])) {
        $heading = $componentData[0]['value'];
    }
    $inputType = 'submit';


    $audio_duration = '';
    if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '', '')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 46 inserted successfully";
    }
}

function component_47($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {
    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 47;
    $appQueryData = "select * from customfielddata where component_type_id='47'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';

    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);


    if (isset($componentData[1]['children']['0']['html'])) {
        $heading = $componentData[1]['children']['0']['html'];
    } else {
        $heading = '';
    }
    $hint = '';
    $minlength = '1';
    $maxlength = '8';
    $inputType = 'text';
    $required = '0';

    $count = count($componentData[2]['children']['0']['children']);
    $textValue = array();
    for ($i = 0; $i < $count; $i++) {
        $textValue[] = str_replace("</a>", "", $componentData[2]['children']['0']['children'][$i]['children']['0']['html']);
    }
    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => '', "options" => $textValue));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '', ''),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 47 inserted successfully";
    }
}

function component_49($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 49;
    $appQueryData = "select * from customfielddata where component_type_id='49'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '1080';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['children']['0']['html'])) {
        $heading = $componentData[1]['children']['0']['html'];
    }
    if (isset($componentData[2]['children']['0']['placeholder'])) {
        $hint = $componentData[2]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[2]['children']['0']['minlength'])) {
        $minlength = $componentData[2]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[2]['children']['0']['maxlength'])) {
        $maxlength = $componentData[2]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[2]['children']['0']['type'])) {
        $inputType = $componentData[2]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[2]['children']['0']['required'])) {
        $required = '1';
    }

    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "',null,null, '" . addslashes($data_audiolink) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "','" . addslashes($heading) . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 49 inserted successfully";
    }
}

function component_50($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 50;
    $appQueryData = "select * from customfielddata where component_type_id='50'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';




    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';

    if (isset($componentData[1]['children']['0']['placeholder'])) {
        $hint = $componentData[1]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }

    if (isset($componentData[1]['children']['0']['data-dateformat'])) {
        $formate = $componentData[1]['children']['0']['data-dateformat'];
    } else {
        $formate = '';
    }
    if (isset($componentData[1]['children']['0']['minlength'])) {
        $minlength = $componentData[1]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[1]['children']['0']['maxlength'])) {
        $maxlength = $componentData[1]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[1]['children']['0']['type'])) {
        $inputType = $componentData[1]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[1]['children']['0']['required'])) {
        $required = '1';
    }

    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "mendatory" => "$required", "formate" => $formate, "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "','" . addslashes($heading) . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 50 inserted successfully";
    }
}

function component_51($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 51;
    $appQueryData = "select * from customfielddata where component_type_id='51'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '1080';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['children']['0']['html'])) {
        $heading = $componentData[1]['children']['0']['html'];
    }
    if (isset($componentData[2]['children'][2]['placeholder'])) {
        $hint = $componentData[2]['children'][2]['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[2]['children'][2]['minlength'])) {
        $minlength = $componentData[2]['children'][2]['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[2]['children'][2]['maxlength'])) {
        $maxlength = $componentData[2]['children'][2]['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[2]['children'][2]['tag'])) {
        $inputType = $componentData[2]['children'][2]['tag'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[2]['children'][2]['required'])) {
        $required = '1';
    }

    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "',null,null, '" . addslashes($data_audiolink) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 51 inserted successfully";
    }
}

function component_52($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 52;
    $appQueryData = "select * from customfielddata where component_type_id='52'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '1080';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    /*   if (isset($componentData[1]['children']['0']['html'])) {
      $heading = $componentData[1]['children']['0']['html'];
      } */
    if (isset($componentData[1]['children']['0']['placeholder'])) {
        $hint = $componentData[1]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[1]['children']['0']['minlength'])) {
        $minlength = $componentData[1]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[1]['children']['0']['maxlength'])) {
        $maxlength = $componentData[1]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[1]['children']['0']['type'])) {
        $inputType = $componentData[1]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }

    if (isset($componentData[1]['children']['1']['children']['0']['class'])) {
        $icomoon1 = $componentData[1]['children']['1']['children']['0']['class'];
        $icomoon = getIcomoon($icomoon1);
    } else {
        $icomoon = '';
    }


if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}

    $required = '0';
    if (isset($componentData[1]['children']['1']['children']['0']['required'])) {
        $required = '1';
    }

    $audio_duration = '';

    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "icomoon" => "$icomoon", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 52 inserted successfully";
    }
}

function component_53($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {


    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 53;
    $appQueryData = "select * from customfielddata where component_type_id='53'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';

    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['children'][0]['html'])) {
        $heading = addslashes($componentData[1]['children'][0]['html']);
    }

    if (isset($componentData[1]['children']['0']['placeholder'])) {
        $hint = $componentData[1]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[1]['children']['0']['minlength'])) {
        $minlength = $componentData[1]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[1]['children']['0']['maxlength'])) {
        $maxlength = $componentData[1]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[1]['children']['0']['type'])) {
        $inputType = $componentData[1]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[1]['children']['0']['required'])) {
        $required = "1";
    }

    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $heading . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 53 inserted successfully";
    }
}

function component_54($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 54;
    $appQueryData = "select * from customfielddata where component_type_id='54'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '1080';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['html'])) {
        $heading = $componentData[1]['html'];
    }
    if (isset($componentData[2]['children'][2]['placeholder'])) {
        $hint = $componentData[2]['children'][2]['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[2]['children'][2]['minlength'])) {
        $minlength = $componentData[2]['children'][2]['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[2]['children'][2]['maxlength'])) {
        $maxlength = $componentData[2]['children'][2]['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[2]['children'][2]['type'])) {
        $inputType = $componentData[2]['children'][2]['type'];
    } else {
        $inputType = 'text';
    }





    $required = '0';
    if (isset($componentData[2]['children'][2]['required'])) {
        $required = '1';
    }

    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($heading) . "',null,null, '" . addslashes($data_audiolink) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 54 inserted successfully";
    }
}

function component_55($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType) {


    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 55;
    $appQueryData = "select * from customfielddata where component_type_id='55'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '';
    $width = '';

    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['html'])) {
        $heading = addslashes($componentData[1]['html']);
    }

    if (isset($componentData[1]['children']['0']['placeholder'])) {
        $hint = $componentData[1]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[1]['children']['0']['minlength'])) {
        $minlength = $componentData[1]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[1]['children']['0']['maxlength'])) {
        $maxlength = $componentData[1]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[1]['children']['0']['type'])) {
        $inputType = $componentData[1]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[1]['children']['0']['required'])) {
        $required = "1";
    }

    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => ''));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $heading . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 55 inserted successfully";
    }
}

function component_56($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType, $fileType) {
    $baseUrl = baseUrlWeb();
    $filepath = $baseUrl . 'panelimage/' . $app_id;
    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 56;
    $appQueryData = "select * from customfielddata where component_type_id='56'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['action_type_id'] = '';
    $resultSet['action_data'] = '';
    $resultSet['listNo'] = 1;

    $height = '1080';
    $width = '1080';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }



    $listcomponent = count($result_screenData);
    $heading = '';
    if (isset($componentData[1]['children']['0']['html'])) {
        $heading = $componentData[1]['children']['0']['html'];
    }
    if (isset($componentData[2]['children']['0']['placeholder'])) {
        $hint = $componentData[2]['children']['0']['placeholder'];
    } else {
        $hint = '';
    }
    if (isset($componentData[2]['children']['0']['minlength'])) {
        $minlength = $componentData[2]['children']['0']['minlength'];
    } else {
        $minlength = '1';
    }
    if (isset($componentData[2]['children']['0']['maxlength'])) {
        $maxlength = $componentData[2]['children']['0']['maxlength'];
    } else {
        $maxlength = '';
    }
    if (isset($componentData[2]['children']['0']['type'])) {
        $inputType = $componentData[2]['children']['0']['type'];
    } else {
        $inputType = 'text';
    }
    $required = '0';
    if (isset($componentData[2]['children']['0']['required'])) {
        $required = '1';
    }

    $audio_duration = '';
if($resultSet['font_size'] == null || $resultSet['font_size'] == '0')
{
    $resultSet['font_size']='14';
}
if($resultSet['font_color'] == null || $resultSet['font_color'] == '')
{
    $resultSet['font_color']='#000000';
}
if($resultSet['font_typeface'] == null || $resultSet['font_typeface'] == '')
{
    $resultSet['font_typeface']='Arial';
}
    $formData = json_encode(array("min_length" => "$minlength", "max_length" => "$maxlength", "hint" => "$hint", "validation" => $fieldType, "mendatory" => "$required", "error_msg" => '', "filetype" => "$fileType", "fileurl" => "$filepath"));

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $cssProperty = '';
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','11',''),";

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $inputType . "', NULL,NULL,NULL,'" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '12', '" . addslashes($formData) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	 VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 56 inserted successfully";
    }
}

function component_58($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $retailsData, $ex_url) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 58;
    $appQueryData = "select * from customfielddata where component_type_id='58'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#FFFFFF';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['font_color1'] = '#FFFFFF';
    $resultSet['font_typeface1'] = 'Arial';
    $resultSet['font_size1'] = '18';
    $resultSet['font_color2'] = '#FFFFFF';
    $resultSet['font_typeface2'] = 'Arial';
    $resultSet['font_size2'] = '14';
    $resultSet['display'] = $display;
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['height'] = '480';
    $resultSet['width'] = '480';
    $resultSet['heightImg'] = '480';
    $resultSet['widthImg'] = '480';
    $resultSet['tab_heading'] = '';
    $actionType = '';
    if ($ex_url != '') {
        $actionType = '10';
    }
    if ($display == '2') {
        $resultSet['height'] = '150';
        $resultSet['width'] = '300';
        $resultSet['heightImg'] = '150';
        $resultSet['widthImg'] = '300';
    }
    if ($videoUrl == '') {
        $mediaType = 'image';
    } else {
        $mediaType = 'video';
    }
    $cssProperty = '';



    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL, NULL, NULL,NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','',''),";

    $resultSet['tab_headingData'] = '';
    $resultSet['subheading'] = '';
    $listcomponent = count($result_screenData);
    $resultSet['img'] = '';
    if (isset($componentData[1]['children'][0]['children'][0]['src'])) {
        $resultSet['img'] = $componentData[1]['children'][0]['children'][0]['src'];
        if (isset($componentData[1]['children'][0]['children'][0]['data-height'])) {
            $resultSet['heightImg'] = $componentData[1]['children'][0]['children'][0]['data-height'];
            $resultSet['widthImg'] = $componentData[1]['children'][0]['children'][0]['data-width'];
        }
    }
    if ($resultSet['img'] == '' && isset($componentData[1]['children'][0]['src'])) {
        $resultSet['img'] = $componentData[1]['children'][0]['src'];
        if (isset($componentData[1]['children'][0]['data-height'])) {
            $resultSet['heightImg'] = $componentData[1]['children'][0]['data-height'];
            $resultSet['widthImg'] = $componentData[1]['children'][0]['data-width'];
        }
    }
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;

    if (isset($componentData[1]['children'][1]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[1]['children'][1]['children'][0]['html'];
        if (isset($componentData[1]['children'][1]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][1]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }
    if (isset($componentData[1]['children'][2]['children'][0]['html']) && $resultSet['tab_headingData'] == '') {
        $resultSet['tab_headingData'] = $componentData[1]['children'][2]['children'][0]['html'];
        if (isset($componentData[1]['children'][2]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][2]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface1'] = $resultAttributes['font_typeface'];
            $resultSet['font_color1'] = $resultAttributes['font_color'];
            $resultSet['font_size1'] = $resultAttributes['font_size'];
        }
    }
    if (isset($componentData[1]['children'][2]['children'][1]['html'])) {
        $resultSet['subheading'] = $componentData[1]['children'][2]['children'][1]['html'];
        if (isset($componentData[1]['children'][2]['children'][1]['style'])) {
            $cssProperty = $componentData[1]['children'][2]['children'][1]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface2'] = $resultAttributes['font_typeface'];
            $resultSet['font_color2'] = $resultAttributes['font_color'];
            $resultSet['font_size2'] = $resultAttributes['font_size'];
        }
    }

    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }
    if ($resultSet['font_size1'] == '0' || $resultSet['font_size1'] == '') {
        $resultSet['font_size1'] = '18';
    }
    if ($resultSet['font_size2'] == '0' || $resultSet['font_size2'] == '') {
        $resultSet['font_size2'] = '14';
    }
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL, NULL, '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $actionType . "','" . $ex_url . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['subheading']) . "', NULL,NULL,NULL, '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color2'] . "', '" . $resultSet['font_typeface2'] . "', '" . $resultSet['font_size2'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $actionType . "','" . $ex_url . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $mediaType . "', NULL, '" . addslashes($resultSet['img']) . "','" . $videoUrl . "','" . $resultSet['heightImg'] . "', '" . $resultSet['widthImg'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $actionType . "','" . $ex_url . "')";
    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";
    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 58 inserted successfully";
    }
}

function component_59($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 59;
    $appQueryData = "select * from customfielddata where component_type_id='59'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '14';
    $resultSet['height'] = '18';
    $resultSet['width'] = '18';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['tab_headingDatatypeface'] = '';
    $resultSet['tab_headingDatacolor'] = '#8b8b8b';
    $resultSet['tab_headingDatasize'] = '20';
    $resultSet['item_contenttypeface'] = '';
    $resultSet['item_contentsize'] = '14';
    $resultSet['item_contentcolor'] = '#8b8b8b';
    $resultSet['Descriptiontypeface'] = '';
    $resultSet['Descriptionsize'] = '14';
    $resultSet['Descriptioncolor'] = '#8b8b8b';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $bgcolr = explode(';', $cssProperty);
        foreach ($bgcolr as $KeyProp => $valueprop) {
            $bgcolrProp = explode(':', $valueprop);
            if ($bgcolrProp[0] == 'font-family') {
                $resultSet['font_typeface'] = addslashes($bgcolrProp[1]);
            }
            if ($bgcolrProp[0] == ' font-size') {
                $resultSet['font_size'] = $bgcolrProp[1];
            }
            if ($bgcolrProp[0] == ' color') {
                $resultSet['font_color'] = $bgcolrProp[1];
            }
        }
    }
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $listcomponent = count($result_screenData);
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    if (isset($componentData[1]['children'][0]['html'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['html'];
        if (isset($componentData[1]['children'][0]['style'])) {
            $resultDescriptionAttributes = getAttribute($componentData[1]['children'][0]['style']);
            $resultSet['font_typeface'] = $resultDescriptionAttributes['font_typeface'];
            $resultSet['font_color'] = $resultDescriptionAttributes['font_color'];
            $resultSet['font_size'] = $resultDescriptionAttributes['font_size'];
        }
    } else {
        $resultSet['tab_heading'] = '';
    }

    if (isset($componentData[1]['children'][1]['html'])) {
        $resultSet['Description'] = $componentData[1]['children'][1]['html'];
        if (isset($componentData[1]['children'][1]['style'])) {
            $resultDescriptionAttributes = getAttribute($componentData[1]['children'][1]['style']);
            $resultSet['Descriptiontypeface'] = $resultDescriptionAttributes['font_typeface'];
            $resultSet['Descriptioncolor'] = $resultDescriptionAttributes['font_color'];
            $resultSet['Descriptionsize'] = $resultDescriptionAttributes['font_size'];
        }
    } else {
        $resultSet['Description'] = '';
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    if ($resultSet['tab_headingDatasize'] == '0' || $resultSet['tab_headingDatasize'] == '') {
        $resultSet['tab_headingDatasize'] = '20';
    }
    if ($resultSet['Descriptionsize'] == '0' || $resultSet['Descriptionsize'] == '') {
        $resultSet['Descriptionsize'] = '14';
    }
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_heading']) . "', NULL,'', '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['Description']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['Descriptioncolor'] . "', '" . $resultSet['Descriptiontypeface'] . "', '" . $resultSet['Descriptionsize'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 59 inserted successfully";
    }
    // insertToDb($resultSet);
}

function component_60($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 60;
    $appQueryData = "select * from customfielddata where component_type_id='60'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }


    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_typeface1'] = 'icomoon.ttf';

    $resultSet['font_size'] = '14';
    $resultSet['height'] = '18';
    $resultSet['width'] = '18';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['tab_headingDatatypeface'] = '';
    $resultSet['tab_headingDatacolor'] = '#000000';
    $resultSet['tab_headingDatasize'] = '20';
    $resultSet['item_contenttypeface'] = '';
    $resultSet['item_contentsize'] = '14';
    $resultSet['item_contentcolor'] = '#000000';
    $resultSet['Descriptiontypeface'] = '';
    $resultSet['Descriptionsize'] = '14';
    $resultSet['Descriptioncolor'] = '#000000';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $bgcolr = explode(';', $cssProperty);
        foreach ($bgcolr as $KeyProp => $valueprop) {
            $bgcolrProp = explode(':', $valueprop);
            if ($bgcolrProp[0] == 'font-family') {
                $resultSet['font_typeface'] = addslashes($bgcolrProp[1]);
            }
            if ($bgcolrProp[0] == ' font-size') {
                $resultSet['font_size'] = $bgcolrProp[1];
            }
            if ($bgcolrProp[0] == ' color') {
                $resultSet['font_color'] = $bgcolrProp[1];
            }
        }
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $listcomponent = count($result_screenData);
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    if (isset($componentData[1]['children'][0]['html'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['html'];
        if (isset($componentData[1]['children'][0]['style'])) {
            $resultDescriptionAttributes = getAttribute($componentData[1]['children'][0]['style']);
            $resultSet['font_typeface'] = $resultDescriptionAttributes['font_typeface'];
            $resultSet['font_color'] = $resultDescriptionAttributes['font_color'];
            $resultSet['font_size'] = $resultDescriptionAttributes['font_size'];
        }
    } else {
        $resultSet['tab_heading'] = '';
    }

    if (isset($componentData[1]['children'][1]['html'])) {
        $resultSet['Description'] = $componentData[1]['children'][1]['html'];
        if (isset($componentData[1]['children'][1]['style'])) {
            $resultDescriptionAttributes = getAttribute($componentData[1]['children'][1]['style']);
            $resultSet['Descriptiontypeface'] = $resultDescriptionAttributes['font_typeface'];
            $resultSet['Descriptioncolor'] = $resultDescriptionAttributes['font_color'];
            $resultSet['Descriptionsize'] = $resultDescriptionAttributes['font_size'];
        }
    } else {
        $resultSet['Description'] = '';
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    if ($resultSet['tab_headingDatasize'] == '0' || $resultSet['tab_headingDatasize'] == '') {
        $resultSet['tab_headingDatasize'] = '20';
    }
    if ($resultSet['Descriptionsize'] == '0' || $resultSet['Descriptionsize'] == '') {
        $resultSet['Descriptionsize'] = '14';
    }


    if (isset($componentData[1]['children'][0]['children'][0]['src'])) {
        $resultSet['image'] = $componentData[1]['children'][0]['children'][0]['src'];
    } else {
        $resultSet['image'] = '';
    }
    $parts = explode("/", $resultSet['image']);
    $parts = end($parts);
    $parts = explode(".", $parts);
    $parts2 = $parts['0'];

    $imgUrltt = getIcomoon($parts2);

    //   $imgUrltt=$parts2;
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "',  '" . $imgUrltt . "', NULL,'" . addslashes($resultSet['image']) . "', '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['Description']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['Descriptioncolor'] . "', '" . $resultSet['Descriptiontypeface'] . "', '" . $resultSet['Descriptionsize'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL,NULL, '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 60 inserted successfully";
    }
    // insertToDb($resultSet);
}

function component_61($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $retailsData) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 61;
    $appQueryData = "select * from customfielddata where component_type_id='61'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['display'] = $display;
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['height'] = '480';
    $resultSet['width'] = '480';
    $resultSet['heightImg'] = '480';
    $resultSet['widthImg'] = '480';
    $resultSet['tab_heading'] = '';

    if ($display == '2') {
        $resultSet['height'] = '150';
        $resultSet['width'] = '300';
        $resultSet['heightImg'] = '150';
        $resultSet['widthImg'] = '300';
    }
    if ($videoUrl == '') {
        $mediaType = 'image';
    } else {
        $mediaType = 'video';
    }
    $cssProperty = '';

    if (isset($componentData[1]['children'][1]['style']) && $bgcolor == '') {
        $cssProperty = $componentData[1]['children'][1]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['background_color'] = $resultAttributes['background_color'];
    }

    if (isset($componentData[2]['style']) && $resultSet['background_color'] == '#FFFFFF') {
        $cssProperty = $componentData[2]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['background_color'] = $resultAttributes['background_color'];
    }

    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL, NULL, NULL, NULL,NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $resultSet['tab_headingData'] = '';
    $listcomponent = count($result_screenData);
    $resultSet['tab_heading'] = '';
    if (isset($componentData[1]['children'][0]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['children'][0]['src'];
        if (isset($componentData[1]['children'][0]['children'][0]['data-height'])) {
            $resultSet['heightImg'] = $componentData[1]['children'][0]['children'][0]['data-height'];
            $resultSet['widthImg'] = $componentData[1]['children'][0]['children'][0]['data-width'];
        }
    }
    if ($resultSet['tab_heading'] == '' && isset($componentData[1]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['src'];
        if (isset($componentData[1]['children'][0]['data-height'])) {
            $resultSet['heightImg'] = $componentData[1]['children'][0]['data-height'];
            $resultSet['widthImg'] = $componentData[1]['children'][0]['data-width'];
        }
    }
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;

    if (isset($componentData[1]['children'][1]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[1]['children'][1]['children'][0]['html'];
        if (isset($componentData[1]['children'][1]['children'][0]['style'])) {
            $cssProperty = $componentData[1]['children'][1]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
            $resultSet['font_color'] = $resultAttributes['font_color'];
            $resultSet['font_size'] = $resultAttributes['font_size'];
        }
    }
    if (isset($componentData[2]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
        if (isset($componentData[2]['children'][0]['style'])) {
            $cssProperty = $componentData[2]['children'][0]['style'];
            $resultAttributes = getAttribute($cssProperty);
            $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
            $resultSet['font_color'] = $resultAttributes['font_color'];
            $resultSet['font_size'] = $resultAttributes['font_size'];
        }
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL, NULL, '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL,NULL,NULL, '" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $mediaType . "', NULL, '" . addslashes($resultSet['tab_heading']) . "','" . $videoUrl . "','" . $resultSet['heightImg'] . "', '" . $resultSet['widthImg'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";
    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 61 inserted successfully";
    }
}

function component_62($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $lat, $long, $bgcolor) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 62;
    $appQueryData = "select * from customfielddata where component_type_id='62'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);

    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $listcomponent = count($result_screenData);

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;

    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($lat) . "', NULL,NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($long) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 62 inserted successfully";
    }
}

function component_63($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData, $zoopim_welcome, $zoopim_key, $zoopim_name, $zoopim_mobile, $zoopim_email) {

    $dbCon = content_db();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 63;
    $appQueryData = "select * from customfielddata where component_type_id='63'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }


    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_typeface1'] = 'icomoon.ttf';

    $resultSet['font_size'] = '14';
    $resultSet['height'] = '18';
    $resultSet['width'] = '18';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['tab_headingDatatypeface'] = '';
    $resultSet['tab_headingDatacolor'] = '#8b8b8b';
    $resultSet['tab_headingDatasize'] = '20';
    $resultSet['item_contenttypeface'] = '';
    $resultSet['item_contentsize'] = '14';
    $resultSet['item_contentcolor'] = '#8b8b8b';
    $resultSet['Descriptiontypeface'] = '';
    $resultSet['Descriptionsize'] = '14';
    $resultSet['Descriptioncolor'] = '#8b8b8b';
    $resultSet['action_type_id'] = '11';
    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $bgcolr = explode(';', $cssProperty);
        foreach ($bgcolr as $KeyProp => $valueprop) {
            $bgcolrProp = explode(':', $valueprop);
            if ($bgcolrProp[0] == 'font-family') {
                $resultSet['font_typeface'] = addslashes($bgcolrProp[1]);
            }
            if ($bgcolrProp[0] == ' font-size') {
                $resultSet['font_size'] = $bgcolrProp[1];
            }
            if ($bgcolrProp[0] == ' color') {
                $resultSet['font_color'] = $bgcolrProp[1];
            }
        }
    }


    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . json_encode($retailsData) . "', NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";


    $listcomponent = count($result_screenData);


    $cssProperty = '';
    $resultSet['action_data'] = array();
    $resultSet['action_data'] = json_encode(array('zoopim_key' => $zoopim_key, 'zoopim_name' => $zoopim_name, 'zoopim_mobile' => $zoopim_mobile, 'zoopim_email' => $zoopim_email, 'zoopim_welcome' => $zoopim_welcome), JSON_UNESCAPED_UNICODE);



    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL,null,NULL,NULL,'" . $resultSet['height'] . "', '" . $resultSet['width'] . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $display . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . addslashes($resultSet['action_data']) . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 63 inserted successfully";
    }
}

function event_data($jsonConvert, $app_id, $title, $screenId, $lintoId, $display, $bgcolor, $retailsData, $date_form, $all_day_event) {

    $dbCon = content_db();
    $forntColor = '#000000';
    $fontType = 'Arial';
    $dbCon = content_db();
    $query = "SELECT st.app_id,ett.id AS event_type_id,st.title FROM screen_title_id st
				JOIN app_event_rel aer ON aer.screen_id=st.id
				JOIN event_data ed ON ed.id=aer.event_data_id
				JOIN event_type ett ON ett.id=ed.event_type_id
				WHERE aer.app_id = '" . $app_id . "' AND screen_id= '" . $screenId . "' limit 1";
    $fetchQuery = $dbCon->query($query);
    $result = $fetchQuery->fetchAll(PDO::FETCH_OBJ);
    $rowCount = $fetchQuery->rowCount();
    $eventID = '';


    if ($rowCount != 0) {
        $eventID = $result[0]->event_type_id;
        $query3 = "update event_type set name='" . $result[0]->title . "',format_date='" . $date_form . "' where id='" . $eventID . "'";
        $updateQuery3 = $dbCon->prepare($query3);
        $updateQuery3->execute();
        $query4 = "update event_data set updatable='1' where event_type_id='" . $eventID . "'";
        $updateQuery4 = $dbCon->prepare($query4);
        $updateQuery4->execute();
    } else {
        $insertEventQuery = "insert into event_type (name,visible_in_calender,is_visible,recurring_interval,app_data_id,format_date) values ('" . $title . "','1','0','0','" . $app_id . "','" . $date_form . "')";

        $insertEventPrepare = $dbCon->prepare($insertEventQuery);
        $insertEventPrepare->execute();
        $eventID = $dbCon->lastInsertId();
    }

    $queryAttr = "select * from app_event_attribute where screen_id='" . $screenId . "'";
    $fetchQueryAttr = $dbCon->query($queryAttr);
    $resultAttr = $fetchQueryAttr->fetchAll(PDO::FETCH_OBJ);
    $rowCountAttr = $fetchQueryAttr->rowCount();
    if ($rowCountAttr == 0) {
        $insertEventQuery = "insert into app_event_attribute (screen_id,component_type_id,componentfieldoption_id) values ('" . $screenId . "','41','94'),('" . $screenId . "','41','95')";
        $insertEventPrepare = $dbCon->prepare($insertEventQuery);
        $insertEventPrepare->execute();
    }

    $dataCount = count($jsonConvert['children'][0]['children']);
    for ($k = 0; $k < $dataCount; $k++) {
        $linkTo = '';
        $eventImage = $eventHeading = $eventDate = $startTime = $endTime = $allday = '';
        $format = 'DD-MM-YYYY';
        if (isset($jsonConvert['children'][0]['children'][$k]['all-day-event'])) {
            $allday = $jsonConvert['children'][0]['children'][$k]['all-day-event'];
        }
        if (isset($jsonConvert['children'][0]['children'][$k]['children'][1]['children'][0]['src'])) {
            $eventImage = $jsonConvert['children'][0]['children'][$k]['children'][1]['children'][0]['src'];
        }

        if (isset($jsonConvert['children'][0]['children'][$k]['children'][2]['children'][0]['html'])) {
            $eventHeading = $jsonConvert['children'][0]['children'][$k]['children'][2]['children'][0]['html'];
        }
        if (isset($jsonConvert['children'][0]['children'][$k]['data-link'])) {
            $linkTo = $jsonConvert['children'][0]['children'][$k]['data-link'];

            if ($linkTo > 0 || $linkTo != '') {
                if (isset($jsonConvert['children'][0]['children'][$k]['data-originalindex'])) {
                    $linkTo = $jsonConvert['children'][0]['children'][$k]['data-originalindex'];
                } else {
                    $linkTo = $lintoIdCore;
                }
            }
        }

        if (isset($jsonConvert['children'][0]['children'][$k]['children'][2]['children'][3]['html'])) {
            $eventDate = $jsonConvert['children'][0]['children'][$k]['children'][2]['children'][3]['html'];
            if (isset($jsonConvert['children'][0]['children'][$k]['children'][2]['children'][3]['date-form'])) {
                $format = $jsonConvert['children'][0]['children'][$k]['children'][2]['children'][3]['date-form'];
            }
        }

        if (isset($jsonConvert['children'][0]['children'][$k]['data-starttime'])) {
            $startTime = $jsonConvert['children'][0]['children'][$k]['data-starttime'];
        }

        if (isset($jsonConvert['children'][0]['children'][$k]['data-endtime'])) {
            $endTime = $jsonConvert['children'][0]['children'][$k]['data-endtime'];
        }
        if (isset($jsonConvert['children'][0]['children'][$k]['children'][2]['children'][0]['style'])) {

            $resultDescriptionAttributes = getAttribute($jsonConvert['children'][0]['children'][$k]['children'][2]['children'][0]['style']);


            $fontType = $resultDescriptionAttributes['font_typeface'];
            $forntColor = $resultDescriptionAttributes['font_color'];
        }
        $eventStartTime = '';
        $eventEndTime = '';
        ;
        $exploded = explode('-', $eventDate);


        if (isset($exploded[2])) {
            $eventStartTime = $exploded[2] . '-' . $exploded[0] . '-' . $exploded[1] . ' ' . $startTime;
            $eventEndTime = $exploded[2] . '-' . $exploded[0] . '-' . $exploded[1] . ' ' . $endTime;


            $query3 = "select * from event_data where event_type_id='" . $eventID . "' and title='" . $eventHeading . "' limit 1";
            $fetchQuery3 = $dbCon->query($query3);
            $result3 = $fetchQuery3->fetchAll(PDO::FETCH_OBJ);
            $rowCount3 = $fetchQuery3->rowCount();
            if ($rowCount3 == 0) {
                $query6 = "insert into event_data (title,heading,start_datetime,end_datetime,image2,allday,event_type_id) values ('" . $eventHeading . "','" . $eventHeading . "','" . $eventStartTime . "','" . $eventEndTime . "','" . $eventImage . "','" . $allday . "','" . $eventID . "')";
                $query6Prepared = $dbCon->prepare($query6);
                $query6Prepared->execute();
                $eventDataId = $dbCon->lastInsertId();
            } else {
                $eventDataId = $result3[0]->id;
                $query5 = "update event_data set title='" . $eventHeading . "',heading='" . $eventHeading . "',start_datetime='" . $eventStartTime . "',end_datetime='" . $eventEndTime . "',image2='" . $eventImage . "',allday='" . $allday . "',updatable='0' where event_type_id='" . $eventID . "' and title='" . $eventHeading . "' ";
                $updateQuery5 = $dbCon->prepare($query5);
                $updateQuery5->execute();
            }
            $query4 = "select * from app_event_rel where event_data_id='" . $eventDataId . "' and app_id = '" . $app_id . "' AND screen_id= '" . $screenId . "'";
            $fetchQuery4 = $dbCon->query($query4);
            $result4 = $fetchQuery4->fetchAll(PDO::FETCH_OBJ);
            $rowCount4 = $fetchQuery4->rowCount();
            if ($rowCount4 == 0) {
                $relationInsertQuery = "insert into app_event_rel (app_id,screen_id,event_data_id,linkto_screenid) values ('" . $app_id . "','" . $screenId . "','" . $eventDataId . "','" . $linkTo . "')";
                $relationPrepared = $dbCon->prepare($relationInsertQuery);
                $relationPrepared->execute();
            } else {
                $Id = $result4[0]->id;
                $query6 = "update app_event_rel set linkto_screenid='" . $linkTo . "' where id='" . $Id . "' ";
                $updateQuery6 = $dbCon->prepare($query6);
                $updateQuery6->execute();
            }
        }
    }
    $myCalCard = "update app_event_attribute set font_color='" . $forntColor . "',font_typeface='" . $fontType . "' where screen_id= '" . $screenId . "'";
    $publisedAppcard = $dbCon->prepare($myCalCard);
    $publisedAppcard->execute();
    echo "events successfully updated";
}

function component_100($componentData, $componentNumber, $app_id, $screenIdHome, $lintoId, $display, $bgcolor) {

    $dbCon = content_db();
    $myAppId = $app_id;

    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 100;
    $appQueryData = "select * from customfielddata where component_type_id='100'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);


    if ($bgcolor == '') {
        $resultSet['background_color'] = '#FFFFFF';
    } else {
        $resultSet['background_color'] = $bgcolor;
    }
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['action_type_id'] = '10';
    $resultSet['display'] = $display;
    $resultSet['action_data'] = '';
    $resultSet['tab_link'] = '';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $seldAds = getSelfAdData('1');
    $resultSet['tab_link'] = $seldAds['tab_link'];
    $resultSet['tab_heading'] = $seldAds['tab_heading'];
    $resultSet['tab_headingData'] = $seldAds['tab_headingData'];
    $resultSet['tab_text'] = $seldAds['tab_text'];

    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screenIdHome . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['action_data'] . "'),";

    $listcomponent = count($result_screenData);
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screenIdHome . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'image', NULL,'" . addslashes($resultSet['tab_heading']) . "', '1000', '1000', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screenIdHome . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screenIdHome . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screenIdHome . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_text']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['tab_link'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 100 inserted successfully";
    }
}

function getSelfAdData($adid) {
    $dbCon = content_db();
    $resultAttributes[] = '';
    $resultAttributes['tab_link'] = '';
    $resultAttributes['tab_heading'] = '';
    $resultAttributes['tab_headingData'] = '';
    $resultAttributes['tab_text'] = '';
    $AdData = "select * from self_advert where id='" . $adid . "'";
    $appQueryAdData = $dbCon->query($AdData);
    $rowappQueryAdData = $appQueryAdData->fetch(PDO::FETCH_ASSOC);

    $resultAttributes['tab_link'] = $rowappQueryAdData['linkto_url'];
    $resultAttributes['tab_heading'] = $rowappQueryAdData['image_url'];
    $resultAttributes['tab_headingData'] = $rowappQueryAdData['heading'];
    $resultAttributes['tab_text'] = $rowappQueryAdData['subheading'];

    return $resultAttributes;
}

function navbar($app_id) {
    $dbCon = content_db();
    $drawer_query = "select * from app_navigation_drawer where app_id='" . $app_id . "'";
    $drawerQueryRun = $dbCon->query($drawer_query);
    $rowFetchDrawer = $drawerQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $calRowDrawer = $drawerQueryRun->rowCount();

    if ($calRowDrawer == 0) {
        $drawer = '1';
        $linkto = '-1';
        $sqlDrawerInsert = "INSERT INTO app_navigation_drawer (`app_id`,`is_drawer_enabled`,`link_to_screenid`) VALUES ('" . $app_id . "','" . $drawer . "','" . $linkto . "')";
        $statementDrawerInsert = $dbCon->prepare($sqlDrawerInsert);
        $statementDrawerInsert->execute();
        $DrawerId = $dbCon->lastInsertId();
        $sqlPropertyInsert = "INSERT INTO app_navigation_properties (`app_navigation_drawer_id`,`field_type`,`field`,`image_url`,`height`,`width`,`description`,`font_size`,`font_color`,`font_typeface`) VALUES ('" . $DrawerId . "','2','profile_pic','','','','','20','#f29f05','icomoon.ttf'),('" . $DrawerId . "','2','cover_pic','','','','','20','#f29f05','icomoon.ttf'),('" . $DrawerId . "','1','heading','','','','','20','#f29f05','icomoon.ttf')";
        $statementPropertyInsert = $dbCon->prepare($sqlPropertyInsert);
        $statementPropertyInsert->execute();
    }
}

function themerelation($app_id, $themeId) {
    $dbCon = content_db();
    $theme_query = "select * from themes_app_rel where app_id='" . $app_id . "'";
    $themeQueryRun = $dbCon->query($theme_query);
    $rowFetchtheme = $themeQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $calRowtheme = $themeQueryRun->rowCount();
    if ($calRowtheme == 0) {
        $sqlDrawerInsert = "INSERT INTO `themes_app_rel` (`app_id`, `themes_id`, `typefaceHeading`, `typefaceSubHeading`, `typefaceNormal`, `heading_size`, `subHeading_size`, `Normal`, `headingColor`, `subHeadingColor`, `defaultTextColor`,  `addTexture`,`liked_item_color`) VALUES ( '" . $app_id . "', '" . $themeId . "', 'Roboto-Regular.ttf', 'Roboto-Regular.ttf', 'Roboto-Regular.ttf', '18', '16', '14', '#000000', '#939393', '#4d4d4d', NULL,'#FF0000');";
        $statementDrawerInsert = $dbCon->prepare($sqlDrawerInsert);
        $statementDrawerInsert->execute();
    } else {
        $publisedApp = "update themes_app_rel set liked_item_color='#FF0000', heading_size= '18',  subHeading_size='16' ,  Normal='14' ,  headingColor='#000000' ,  subHeadingColor= '#939393' ,  defaultTextColor ='#4d4d4d',backgroundColor='#FFFFFF' where app_id='" . $app_id . "'";
        $publisedAppParent = $dbCon->prepare($publisedApp);
        $publisedAppParent->execute();
    }
}

function authCheck($app_id, $autherId) {
    $dbCon = content_db();
    $auth_query = "select * from app_data where author_id = '" . $autherId . "' and  id='" . $app_id . "'";
    $auth_queryExecution = $dbCon->query($auth_query);
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
    return $result_auth;
}

function getData() {
    $screenIdHome = 1;
    $pageLayoutType = 1;
    $app_id = $_POST['app_id'];
    $themeId = $_POST['themeId'];
    $autherId = $_POST['autherId'];
    $componentNumber = 1;
    try {
        $loginStatus = authCheck($app_id, $autherId);
        if ($loginStatus == 0 || $loginStatus == NULL || $loginStatus == '') {
            echo "Dont Try to hack !!";
            die;
        }
        $dbCon = content_db();
        $app_query = "select ash.id,ash.app_id,ash.screen_sequence,ash.title,ash.banner_html,ash.navigation_html,ash.content_html,ash.autherId,ash.ico_icon,ash.deleted,ash.screen_title_id,ash.keywords,slm.layout_type_id as layoutType from app_screenmapping_html ash left join screen_layout_mapping slm on ash.layoutType = slm.screen_type_id where app_id='" . $app_id . "'";
        $appQueryRun = $dbCon->query($app_query);
        $rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
        $calRow = $appQueryRun->rowCount();

        if ($calRow == 0) {
            echo "no app html present";
            die;
        } else {
            $publisedApp = "update app_data set updated=now() where id='" . $app_id . "'";
            $publisedAppParent = $dbCon->prepare($publisedApp);
            $publisedAppParent->execute();
            // $sqlDeleteAll = "delete from `screen_title_id` where app_id='" . $app_id . "' and title='Contact Us' and background_type='999'";
            $sqlDeleteAll = "UPDATE screen_title_id SET deleted='1' WHERE app_id='" . $app_id . "' AND background_type IN (999,1000,9998)";
            $statementsqlDeleteAll = $dbCon->prepare($sqlDeleteAll);
            $statementsqlDeleteAll->execute();
            $sqlDelete = "delete from `componentfieldvalue` where app_id='" . $app_id . "'";
            $statementsqlDelete = $dbCon->prepare($sqlDelete);
            $statementsqlDelete->execute();
            navbar($app_id);
            themerelation($app_id, $themeId);

            $countOfPage = count($rowFetch);
			$countofupdatescreen=0;
            $TotalNav = 1;
            $countcc=0;
            $Basearray[]='';
            foreach ($rowFetch as $keyAs => $valueAs) {
                $screen_type = '';
                $countcc++;
                $imgUrl = '';
                $height = '';
                $width = '';
                $title = '';
                $font_size = '';
                $font_color = '';
                $font_typeface = '';
                $parent_id = '';
                $popup_flag = '';
                $background_color = '';
                $is_visible = '';
                $event_type = '';
                $server_time = '';
                $is_bypass = '';
                $background_type = '';
                $screen_type = $valueAs['layoutType'];
                /* Note:: background_type is actually a page indexvalue which indicates the sequence of page */
                $background_type = $valueAs['screen_sequence'];
                $imgUrl = '';
                $height = '64';
                $width = '64';
                $title = addslashes($valueAs['title']);
                $keywords = addslashes($valueAs['keywords']);
                $font_size = '17';
                $font_color = '#FFFFFF';
                $font_typeface = 'Arial';
                $parent_id = '-1';
                $popup_flag = '0';
                $background_color = '#dbdbdb';
                $is_visible = '0';
                $event_type = '0';
                $server_time = '';
                $is_bypass = '0';
                $dateTime = date("Y-m-d");
                if ($background_type == '1') {
                    $parent_id = '0';
                }
                if ($screen_type == 999) {
                    $event_type = '1';
                }
                $iconSet = $valueAs['ico_icon'];
                $imgUrl = '';
                if ($iconSet != '') {
                    $imgUrl = getIcomoon($iconSet);
                }
                $screenid_Html = $valueAs['screen_title_id'];
                $sqlDelete = "delete from `app_event_rel` where screen_id='" . $screenid_Html . "'";
                $statementsqlDelete = $dbCon->prepare($sqlDelete);
                $statementsqlDelete->execute();
                if ($valueAs['deleted'] == '1') {
                    //  $sqlDeleteAll = "delete from `screen_title_id` where app_id='" . $app_id . "' and id='" . $screenid_Html . "'";
                    $sqlDeleteAll = "update  `screen_title_id` set deleted =1 where app_id='" . $app_id . "' and id='" . $screenid_Html . "'";
                    $statementsqlDeleteAll = $dbCon->prepare($sqlDeleteAll);
                    $statementsqlDeleteAll->execute();
					
					$id = array("screen_id" => '-4', 'server_time' => '');
                                $final_results[] = $id;
                                $response = array("result" => 'success', "msg" => '');
                                $Basearray = array("response" => $response, "dirty_screen" => $final_results);
								$data['app_id'] = $app_id;
						 echo json_encode($Basearray);
						 $data['action_data']=json_encode($Basearray);
						 $data['action_tag']=3;
                            send_notification($data);
			
                } else {
                    if ($screenid_Html == 0) {
                        $CheckScreenEntry = "select * from screen_title_id where app_id='" . $app_id . "'  and title='" . $title . "' and background_type='" . $background_type . "' and deleted=0";
                        $CheckScreenEntryRun = $dbCon->query($CheckScreenEntry);
                        $rowFetchNav = $CheckScreenEntryRun->fetch(PDO::FETCH_ASSOC);
                        $CheckScreenEntryRunNav = $CheckScreenEntryRun->rowCount();
                        if ($CheckScreenEntryRunNav == 0) {
                            $sqlUserInsert = "INSERT INTO screen_title_id (app_id,screen_type,image_url,height,width,title,font_size,font_color,font_typeface,parent_id,popup_flag,background_type,background_color,is_visible,event_type,server_time,is_bypass,keywords) VALUES ('" . $app_id . "','" . $screen_type . "','" . $imgUrl . "','" . $height . "','" . $width . "','" . $title . "','" . $font_size . "','" . $font_color . "','" . $font_typeface . "','" . $parent_id . "','" . $popup_flag . "','" . $background_type . "','" . $background_color . "','" . $is_visible . "','" . $event_type . "','" . $dateTime . "','" . $is_bypass . "','" . $keywords . "')";
                            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                            $statementUserInsert->execute();
                            $screenId = $dbCon->lastInsertId();
                            $sqlscreenmapping_html = "update app_screenmapping_html set screen_title_id='" . $screenId . "' where app_id='" . $app_id . "'  and id='" . $valueAs['id'] . "'";
                            $statement_screenmapping = $dbCon->prepare($sqlscreenmapping_html);
                            $statement_screenmapping->execute();
                            /* push */
                                  $id = array("screen_id" => '-4', 'server_time' => $dateTime);
                                $final_results[] = $id;
                                $response = array("result" => 'success', "msg" => '');
                                $Basearray = array("response" => $response, "dirty_screen" => $final_results);
								$data['app_id'] = $app_id;
						 echo json_encode($Basearray);
						 $data['action_data']=json_encode($Basearray);
						 $data['action_tag']=3;
                            send_notification($data);
							
                       //Push Notification
                        } else {
                            $sqlUserInsert = "update screen_title_id set screen_type='" . $screen_type . "',image_url='" . $imgUrl . "',height='" . $height . "',width='" . $width . "',title='" . $title . "',font_size='" . $font_size . "',font_color='" . $font_color . "',font_typeface='" . $font_typeface . "',parent_id='" . $parent_id . "',popup_flag='" . $popup_flag . "',background_type='" . $background_type . "',background_color='" . $background_color . "',is_visible='" . $is_visible . "',event_type='" . $event_type . "',is_bypass='" . $is_bypass . "',keywords='" . $keywords . "' where app_id='" . $app_id . "' and id='" . $rowFetchNav['id'] . "'";
                            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                            $statementUserInsert->execute();
                            $screenId = $valueAs['id'];
                            $sqlscreenmapping_html = "update app_screenmapping_html set screen_title_id='" . $rowFetchNav['id'] . "' where app_id='" . $app_id . "'  and id='" . $valueAs['id'] . "'";
                            $statement_screenmapping = $dbCon->prepare($sqlscreenmapping_html);
                            $statement_screenmapping->execute();

                            /* server time */

$appQueryData2 = "select * from app_screenmapping_html  where app_id='" . $app_id . "'  and id='" . $valueAs['id'] . "'";
                        $app_screenData2 = $dbCon->query($appQueryData2);
                        $result_screenData2 = $app_screenData2->fetchAll(PDO::FETCH_OBJ);
                        $sertime2 = $result_screenData2[0]->updated;

                        $appQueryData3 = "select * from screen_title_id where  app_id='" . $app_id . "'  and id='" . $screenid_Html . "'";
                        $app_screenData3 = $dbCon->query($appQueryData3);
                        $result_screenData3 = $app_screenData3->fetchAll(PDO::FETCH_OBJ);
                        $sertime3 = $result_screenData3[0]->server_time;

                        $st_time2 = strtotime($sertime2);
                        $st_time3 = strtotime($sertime3);
                     					  echo $st_time2."---".$st_time3;
                              if($st_time2<=$st_time3){}else
                        {
                        $sqlscreenmapping_html33 = "update screen_title_id set server_time='" . $sertime2 . "'  where  app_id='" . $app_id . "'  and id='" . $screenid_Html . "'";
                        $statement_screenmapping33 = $dbCon->prepare($sqlscreenmapping_html33);
                        $statement_screenmapping33->execute();
                            
                
                                  $id = array("screen_id" => $screenid_Html, 'server_time' => $sertime2);
                                $final_results[] = $id;
                                
								$response = array("result" => 'success', "msg" => '');
                                $Basearray = array("response" => $response, "dirty_screen" => $final_results);
								$countofupdatescreen++;
                            
                              
                           }
				            
                            /*sever time */
                            
                        
  
                            
                            
                            /*sever time */    
                        }
                    } else {
                        $sqlUserInsert = "update screen_title_id set screen_type='" . $screen_type . "',image_url='" . $imgUrl . "',height='" . $height . "',width='" . $width . "',title='" . $title . "',font_size='" . $font_size . "',font_color='" . $font_color . "',font_typeface='" . $font_typeface . "',parent_id='" . $parent_id . "',popup_flag='" . $popup_flag . "',background_type='" . $background_type . "',background_color='" . $background_color . "',is_visible='" . $is_visible . "',event_type='" . $event_type . "',is_bypass='" . $is_bypass . "',keywords='" . $keywords . "' where app_id='" . $app_id . "' and id='" . $screenid_Html . "'";
                        $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                        $statementUserInsert->execute();
                        $screenId = $screenid_Html;
                        $sqlscreenmapping_html = "update app_screenmapping_html set screen_title_id='" . $screenId . "' where app_id='" . $app_id . "'  and id='" . $valueAs['id'] . "'";
                        $statement_screenmapping = $dbCon->prepare($sqlscreenmapping_html);
                        $statement_screenmapping->execute();

                        /* server time */

                        $appQueryData2 = "select * from app_screenmapping_html  where app_id='" . $app_id . "'  and id='" . $valueAs['id'] . "'";
                        $app_screenData2 = $dbCon->query($appQueryData2);
                        $result_screenData2 = $app_screenData2->fetchAll(PDO::FETCH_OBJ);
                        $sertime2 = $result_screenData2[0]->updated;

                        $appQueryData3 = "select * from screen_title_id where  app_id='" . $app_id . "'  and id='" . $screenid_Html . "'";
                        $app_screenData3 = $dbCon->query($appQueryData3);
                        $result_screenData3 = $app_screenData3->fetchAll(PDO::FETCH_OBJ);
                        $sertime3 = $result_screenData3[0]->server_time;

                        $st_time2 = strtotime($sertime2);
                        $st_time3 = strtotime($sertime3);
                     					  echo $st_time2."---".$st_time3;
                              if($st_time2<=$st_time3){}else
                        {
                        $sqlscreenmapping_html33 = "update screen_title_id set server_time='" . $sertime2 . "'  where  app_id='" . $app_id . "'  and id='" . $screenid_Html . "'";
                        $statement_screenmapping33 = $dbCon->prepare($sqlscreenmapping_html33);
                        $statement_screenmapping33->execute();
                            
                
                                  $id = array("screen_id" => $screenid_Html, 'server_time' => $sertime2);
                                $final_results[] = $id;
                                
								$response = array("result" => 'success', "msg" => '');
                                $Basearray = array("response" => $response, "dirty_screen" => $final_results);
								$countofupdatescreen++;
                            
                              
                           }
				            
                            /*sever time */
                            
                        
                    } 
                    if ($parent_id == '0') {
                        $screenIdHome = $screenId;
                    }
                    $screenType = $valueAs['layoutType'];
                    $appName = '';
                    $myAppId = $valueAs['app_id'];
                    $TotalNav++;
                    if ($valueAs['banner_html']) {
                        $banner = $valueAs['banner_html'];
                        $bannerhtml = <<<EOF
$banner
EOF;
                        $jsonHtml = json_encode(html_to_obj($bannerhtml), JSON_PRETTY_PRINT);
                        $jsonConvert = json_decode($jsonHtml, true);
                        $i = 0;
                        $banners = $jsonConvert['children'][0]['children'];
                        component_32($banners, $componentNumber, $app_id, $screenId);
                        $componentNumber++;
                    }

                    if ($valueAs['content_html']) {


                        /*                         * ************************************************************
                         * *************************************************************
                          It will replace html with HTML entities for DOM  parsing
                         * ************************************************************ 
                         * *************************************************************
                         */
                        $htmltags = array("<a data-link", "</a>", "<strong>", "</strong>", "<u>", "</u>", "<br>", "<b", "<i>", "<I>", "</b>", "</i>", "</I>", "<font", "</font>");
                        $repalcedHtml = array("&lt;a data-link", "&lt;/a&gt;", "&lt;strong&gt;", "&lt;/strong&gt;", "&lt;u&gt;", "&lt;/u&gt;", "&lt;br&gt;", "&lt;b", "&lt;i&gt;", "&lt;I&gt;", "&lt;/b&gt;", "&lt;/i&gt;", "&lt;/I&gt;", "&lt;font", "&lt;/font&gt;");
                        $htmlvalues = str_replace($htmltags, $repalcedHtml, $valueAs['content_html']);
                        $Finalhtml2 = <<<EOF
$htmlvalues
EOF;
                        $jsonHtml = json_encode(html_to_obj($Finalhtml2), JSON_UNESCAPED_UNICODE);
                        $jsonConvert = json_decode($jsonHtml, true);
                        $i = 0;
                        foreach ($jsonConvert as $pageKey => $componentDatavalue) {
                            if ($pageKey == 'children') {
                                $valueOfLeft = $componentDatavalue[0]['children'];



                                foreach ($valueOfLeft as $keypage => $componentDataAll) {
                                    $lintoId = '-1';
                                    $display = '1';
                                    $videoUrl = '';
                                    $lintoIdCore = '';
                                    $product = '';
                                    $subcategory = array();
                                    $maincategory = '';
                                    $retailsData = array();
                                    $is_child = '';
                                    $ex_url = '';


                                    if (isset($componentDataAll['data-maincategory'])) {
                                        $maincategory = $componentDataAll['data-maincategory'];
                                        $allCat = 0;
                                        for ($subcat = 0; $subcat >= $allCat; $subcat++) {
                                            if ($maincategory != '' && isset($componentDataAll['select-subcategory' . $subcat])) {
                                                $subcategory[] = $componentDataAll['select-subcategory' . $subcat];
                                            } else {
                                                $allCat = $subcat + 10;
                                                if (isset($componentDataAll['data-product'])) {
                                                    $product = $componentDataAll['data-product'];
                                                }
                                            }
                                        }
                                    }
                                    if (isset($componentDataAll['data-haschild'])) {
                                        $is_child = $componentDataAll['data-haschild'];
                                    }
                                    $retailsData = array("main_cat" => $maincategory, "subcategory" => $subcategory, "product" => $product, "is_child" => $is_child);

                                    if (isset($componentDataAll['data-link'])) {
                                        $lintoIdCore = $componentDataAll['data-link'];
                                        if ($lintoIdCore > 0 || $lintoIdCore != '') {
                                            if (isset($componentDataAll['data-originalindex'])) {
                                                $lintoId = $componentDataAll['data-originalindex'];
                                            } else {
                                                $lintoId = $lintoIdCore;
                                            }
                                        }
                                    }
                                    if (isset($componentDataAll['data-ss-colspan'])) {
                                        $display = $componentDataAll['data-ss-colspan'];
                                    }
                                    if (isset($componentDataAll['ex_url'])) {
                                        $ex_url = $componentDataAll['ex_url'];
                                    }

                                    if (isset($componentDataAll['data-videolink'])) {
                                        $videoUrl = $componentDataAll['data-videolink'];
                                    }

                                    if (isset($componentDataAll['data-linkphone'])) {
                                        $linkphone = $componentDataAll['data-linkphone'];
                                    } else {
                                        $linkphone = '';
                                    }
                                    if (isset($componentDataAll['data-shared-text'])) {
                                        $data_shared_text = $componentDataAll['data-shared-text'];
                                    } else {
                                        $data_shared_text = '';
                                    }
                                    if (isset($componentDataAll['data-audiolength'])) {
                                        $dataaudiolength = $componentDataAll['data-audiolength'];
                                    } else {
                                        $dataaudiolength = '';
                                    }
                                    if (isset($componentDataAll['data-shared-link'])) {
                                        $data_shared_link = $componentDataAll['data-shared-link'];
                                    } else {
                                        $data_shared_link = '';
                                    }
                                    if (isset($componentDataAll['data-audiolink'])) {
                                        $data_audiolink = $componentDataAll['data-audiolink'];
                                    } else {
                                        $data_audiolink = '';
                                    }
                                    if (isset($componentDataAll['data-fieldtype'])) {
                                        $fieldType = $componentDataAll['data-fieldtype'];
                                    } else {
                                        $fieldType = '';
                                    }

                                    if (isset($componentDataAll['date-form'])) {
                                        $date_form = $componentDataAll['date-form'];
                                    } else {
                                        $date_form = 'DD-MM-YYYY';
                                    }
                                    if (isset($componentDataAll['all-day-event'])) {
                                        $all_day_event = $componentDataAll['all-day-event'];
                                    } else {
                                        $all_day_event = '';
                                    }
                                    if (isset($componentDataAll['data-filetype'])) {
                                        $fileType = $componentDataAll['data-filetype'];
                                    } else {
                                        $fileType = '';
                                    }

                                    if (isset($componentDataAll['style'])) {

                                        $cssProperty = $componentDataAll['style'];

                                        $resultAttributes = getAttribute($cssProperty);

                                        $bgcolor = $resultAttributes['background_color'];
                                    } else {
                                        $bgcolor = '#FFFFFF';
                                    }

                                    if (isset($componentDataAll['data-component'])) {
                                        $componentNo = $componentDataAll['data-component'];
                                        if (isset($componentDataAll['children'])) {
                                            $componentData = $componentDataAll['children'];
                                        } else {
                                            $componentData = '';
                                        }
                                        if (isset($componentDataAll['required'])) {
                                            $required = $componentDataAll['required'];
                                        } else {
                                            $required = '0';
                                        }


                                        if (isset($componentNo)) {
                                            if ($screen_type == 999) {
                                                event_data($jsonConvert, $app_id, $title, $screenId, $lintoId, $display, $bgcolor, $retailsData, $date_form, $all_day_event);
                                            } else {
                                                switch ($componentNo) {

                                                    case "1":
                                                        component_1($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "2":
                                                        component_2($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $data_shared_text, $data_shared_link, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "3":

                                                        component_3($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "4":
                                                        component_4($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "5":
                                                        component_5($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $screen_type, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "6":
                                                        component_6($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData, $title);
                                                        $componentNumber++;
                                                        break;
                                                    case "7":
                                                        component_7($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "8":
                                                        component_8($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "9":
                                                        component_9($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;

                                                        break;

                                                    case "10":

                                                        component_10($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "13":
                                                        component_13($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $retailsData);
                                                        $componentNumber++;
                                                        break;

                                                    case "14":

                                                        component_14($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "15":

                                                        component_15($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "17":
                                                        component_17($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;

                                                    case "19":
                                                        component_19($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $retailsData);
                                                        $componentNumber++;
                                                        break;

                                                    case "22":
                                                        component_22($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData);
                                                        $componentNumber++;
                                                        break;

                                                    case "24":
                                                        component_24($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;

                                                    case "25":
                                                        component_25($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;

                                                    case "26":

                                                        component_26($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;

                                                    case "28":
                                                        component_28($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;
                                                    case "29":
                                                        component_29($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $linkphone, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "30":
                                                        component_30($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $linkphone, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "33":
                                                        component_33($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "36":
                                                        component_36($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;
                                                    case "37":
                                                        component_37($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;
                                                    case "38":
                                                        component_38($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;
                                                    case "39":
                                                        component_39($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType, $required);
                                                        $componentNumber++;
                                                        break;

                                                    case "40":
                                                        component_40($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;
                                                    case "42":
                                                        component_42($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                        $componentNumber++;
                                                        break;
                                                    case "43":
                                                        component_43($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                        $componentNumber++;
                                                        break;

                                                    case "44":
                                                        $lat = '';
                                                        $long = '';
                                                        if (isset($componentDataAll['data-latitude'])) {
                                                            $lat = $componentDataAll['data-latitude'];
                                                        }

                                                        if (isset($componentDataAll['data-longitude'])) {
                                                            $long = $componentDataAll['data-longitude'];
                                                        }
                                                        component_44($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $lat, $long, $bgcolor);
                                                        $componentNumber++;
                                                        break;
                                                    case "45":

                                                        component_45($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "46":

                                                        component_46($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData);
                                                        $componentNumber++;
                                                        break;

                                                    case "47":
                                                        component_47($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;

                                                    case "49":
                                                        component_49($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;
                                                    case "50":
                                                        component_50($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData);
                                                        $componentNumber++;
                                                        break;
                                                    case "51":
                                                        component_51($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;

                                                    case "52":
                                                        component_52($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;
                                                    case "54":
                                                        component_54($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;
                                                    case "53":
                                                        component_53($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;

                                                    case "55":
                                                        component_55($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType);
                                                        $componentNumber++;
                                                        break;
                                                    case "56":
                                                        component_56($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link, $data_audiolink, $dataaudiolength, $retailsData, $fieldType, $fileType);
                                                        $componentNumber++;
                                                        break;
                                                    case "58":
                                                        component_58($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $retailsData, $ex_url);
                                                        $componentNumber++;
                                                        break;
                                                    case "59":
                                                        component_59($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;

                                                        break;

                                                    case "60":
                                                        component_60($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData);
                                                        $componentNumber++;

                                                        break;
                                                    case "61":
                                                        component_61($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $retailsData);
                                                        $componentNumber++;
                                                        break;

                                                    case "62":
                                                        $lat = '';
                                                        $long = '';
                                                        if (isset($componentDataAll['business-latitude'])) {
                                                            $lat = $componentDataAll['business-latitude'];
                                                        }

                                                        if (isset($componentDataAll['business-longitude'])) {
                                                            $long = $componentDataAll['business-longitude'];
                                                        }
                                                        component_62($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $lat, $long, $bgcolor);
                                                        $componentNumber++;
                                                        break;

                                                    case "63":

                                                        $zoopim_welcome = '';
                                                        $zoopim_key = '';
                                                        $zoopim_name = '';
                                                        $zoopim_mobile = '';
                                                        $zoopim_email = '';

                                                        if (isset($componentDataAll['zoopim_welcome'])) {
                                                            $zoopim_welcome = $componentDataAll['zoopim_welcome'];
                                                        }
                                                        if (isset($componentDataAll['zoopim_account_key'])) {
                                                            $zoopim_key = $componentDataAll['zoopim_account_key'];
                                                        }
                                                        if (isset($componentDataAll['zoopim_visitor_name'])) {
                                                            $zoopim_name = $componentDataAll['zoopim_visitor_name'];
                                                        }
                                                        if (isset($componentDataAll['zoopim_visitor_mobile'])) {
                                                            $zoopim_mobile = $componentDataAll['zoopim_visitor_mobile'];
                                                        }
                                                        if (isset($componentDataAll['zoopim_visitor_email'])) {
                                                            $zoopim_email = $componentDataAll['zoopim_visitor_email'];
                                                        }


                                                        component_63($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor, $retailsData, $zoopim_welcome, $zoopim_key, $zoopim_name, $zoopim_mobile, $zoopim_email);
                                                        $componentNumber++;

                                                        break;



                                                    default:
                                                        echo "undefined component";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $i++;
                        }
                    }

                    if ($valueAs['navigation_html'] != '' || $valueAs['navigation_html'] != NULL) {
                        $htmlnavvalues = $valueAs['navigation_html'];
                        $navhtml = <<<EOF
$htmlnavvalues
EOF;
                        $jsonHtmlnav = json_encode(html_to_obj($navhtml), JSON_PRETTY_PRINT);
                        $jsonConvertnav = json_decode($jsonHtmlnav, true);

                        $i = 0;
                        $background_image = '';

                        if (isset($jsonConvertnav['children'][0]['children'][0])) {

                            if (isset($jsonConvertnav['children'][0]['children'][0]['data-appbackground'])) {

                                $background_color = $jsonConvertnav['children'][0]['children'][0]['data-appbackground'];
                            }

                            if (isset($jsonConvertnav['children'][0]['children'][0]['data-appbackgroundimage'])) {

                                $background_image = $jsonConvertnav['children'][0]['children'][0]['data-appbackgroundimage'];
                            }


                            if (isset($jsonConvertnav['children'][0]['children'][0]['style'])) {


                                $cssProperty = $jsonConvertnav['children'][0]['children'][0]['style'];
                                $resultAttributes = getAttribute($cssProperty);
                                $background_color_nav = $resultAttributes['background_color'];
                                $appbackcolor = $background_color_nav;
                                if (isset($jsonConvertnav['children'][0]['children'][0]['data-appbackground'])) {
                                    $appbackcolor = $jsonConvertnav['children'][0]['children'][0]['data-appbackground'];
                                }

                                $sqlNavColor = "update app_data set background_color='" . $background_color_nav . "',background_image='" . $background_image . "' where id='" . $app_id . "' ";

                                $statementNavColor = $dbCon->prepare($sqlNavColor);
                                $statementNavColor->execute();
                                 $sqlNavColor1 = "update screen_title_id set background_color='" . $appbackcolor . "' where app_id='" . $app_id . "' ";
                              
                                $statementNavColor1 = $dbCon->prepare($sqlNavColor1);
                                $statementNavColor1->execute();
                            }


                            $arrayOfStyle = $jsonConvertnav['children'][0]['children'];

                            if (isset($arrayOfStyle[1]['children'][0]['children'])) {
                                $countOfnav = $arrayOfStyle[1]['children'][0]['children'];
                                $inrmnt = 0;
                                $seq = 1;
                                $sequence = 1000;
                                foreach ($countOfnav as $navKey => $navValue) {
                                    $iconId = '';
                                    $icomoon = '';
                                    if (isset($navValue['children'][0]['children'][0]['class'])) {
                                        $icocode = $navValue['children'][0]['children'][0]['class'];
                                        $icomoon = getIcomoon($icocode);
                                    }
                                    $others = '';
                                    $original_index = '';
                                    if (isset($navValue['data-email'])) {
                                        $others = $navValue['data-email'];
                                    }
                                    if (isset($navValue['data-original-index'])) {
                                        $original_index = $navValue['data-original-index'];
                                    }
                                    $navScreen = '';
                                    if (isset($navValue['style'])) {
                                        if ($navValue['style'] == 'display: none;') {
                                            $navScreen = 'this is null value';
                                        } else {

                                            $navScreen = '';
                                        }
                                    }
                                    if (isset($navValue['children'][0]['children'][1]['html']) && $navScreen == '') {
                                        $navevisible = addslashes($navValue['children'][0]['children'][1]['html']);

                                        if ($navevisible == 'Report' || $navevisible == 'Report Misconduct') {
                                            $navevisible = 'Report Misconduct';
                                            $screen_type = '14';
                                            $background_type = '1001';
                                            $seqReport = '1001';
                                            if ($icomoon == '') {
                                                $icomoon = 'e049';
                                            }
                                        } elseif ($navevisible == 'Feedback') {
                                            $screen_type = '16';
                                            $background_type = '1000';
                                            $seqReport = '1000';
                                            if ($icomoon == '') {
                                                $icomoon = 'e050';
                                            }
                                        } elseif ($navevisible == 'Contact Us') {
                                            $screen_type = '19';
                                            $background_type = '999';
                                            $seqReport = '999';
                                            if ($icomoon == '') {
                                                $icomoon = 'e050';
                                            }
                                        }
                                        $navCheck = "select * from screen_title_id where app_id='" . $app_id . "'  and title='" . $navevisible . "'";
                                        $navCheckRun = $dbCon->query($navCheck);
                                        $rowFetchNav = $navCheckRun->fetchAll(PDO::FETCH_ASSOC);
                                        $calRowNav = $navCheckRun->rowCount(PDO::FETCH_NUM);
                                        if ($calRowNav == 0) {
                                            if ($navevisible == 'Report Misconduct' || $navevisible == 'Feedback' || $navevisible == 'Contact Us') {
                                                $sqlNav = "INSERT INTO screen_title_id (app_id,screen_type,image_url,height,width,title,font_size,font_color,font_typeface,parent_id,popup_flag,background_type,background_image,background_color,is_visible,event_type,server_time,is_bypass,others,seq) VALUES ('" . $app_id . "','" . $screen_type . "','" . $icomoon . "','" . $height . "','" . $width . "','" . $navevisible . "','" . $font_size . "','" . $font_color . "','" . $font_typeface . "','-1','" . $popup_flag . "','" . $background_type . "','" . $background_image . "','" . $background_color . "','1','" . $event_type . "','" . $dateTime . "','" . $is_bypass . "','" . $others . "','" . $seqReport . "')";
                                                $stateNav = $dbCon->prepare($sqlNav);
                                                $stateNav->execute();
                                            }
                                        } else {
                                            if ($navevisible == 'Report Misconduct' || $navevisible == 'Feedback') {
                                                if ($original_index == '') {
                                                    $sqlUserInsert = "update screen_title_id set is_visible='1',background_color='" . $background_color . "',background_image='" . $background_image . "',deleted=0 where app_id='" . $app_id . "'  and title='" . $navevisible . "'";
                                             
                                                 } else {
                                                    $sqlUserInsert = "update screen_title_id set is_visible='1',background_color='" . $background_color . "',background_image='" . $background_image . "',deleted=0 where app_id='" . $app_id . "'  and title='" . $navevisible . "' and background_type='" . $original_index . "'";
                                               
                                                  }
                                                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                                                $statementUserInsert->execute();
                                                $sequence++;
                                            } else {
                                                if ($original_index == '') {
                                                    $sqlUserInsert = "update screen_title_id set is_visible='1',background_color='" . $background_color . "',background_image='" . $background_image . "',seq=$seq,deleted=0 where app_id='" . $app_id . "'  and title='" . $navevisible . "'";
                                             
                                                 } else {
                                                    $sqlUserInsert = "update screen_title_id set is_visible='1',background_color='" . $background_color . "',background_image='" . $background_image . "',seq=$seq,deleted=0 where app_id='" . $app_id . "'  and title='" . $navevisible . "' and background_type='" . $original_index . "'";
                                             
                                                 }
                                                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                                                $statementUserInsert->execute();
                                            }
                                        }
                                    }
                                    $seq++;
                                }
                            }
                        }
                    }
                }
            }
        }
        $app_navquery = "select * from screen_title_id where app_id='" . $app_id . "' and deleted=0";
        $appQueryNavRun = $dbCon->query($app_navquery);
        $rowNavFetch = $appQueryNavRun->fetchAll(PDO::FETCH_ASSOC);

        $app_data = "select p.plan_type from plans p left join app_data ad on p.id=ad.plan_id where ad.id='" . $app_id . "'";
        $appQuerydata = $dbCon->query($app_data);
        $rowparentFetchdata = $appQuerydata->fetch(PDO::FETCH_ASSOC);
        $countofPlan = count($rowparentFetchdata);
        $screenplanId = $rowparentFetchdata['plan_type'];

        if ($screenplanId != 5 || $screenplanId == null || $countofPlan == 0) {
            /* ad server insertion */
            $sqlDelete = "delete from `app_adserver_details` where app_id='" . $app_id . "'";
            $statementsqlDelete = $dbCon->prepare($sqlDelete);
            $statementsqlDelete->execute();
            $lastAdid = '';
            $sqlad = "INSERT INTO app_adserver_details (app_id,adserver_details_id) VALUES ('" . $app_id . "','1')";
            $statementad = $dbCon->prepare($sqlad);
            $statementad->execute();
            $lastAdid = $dbCon->lastInsertId();
            $sqlDelete = "delete from `appserver_screenmapping` where adserver_details_id='" . $lastAdid . "'";
            $statementsqlDelete = $dbCon->prepare($sqlDelete);
            $statementsqlDelete->execute();
            $componentData = '';
            $lintoId = '-1';
            $display = '2';
            $bgcolor = '#FFFFFF';
            component_100($componentData, $componentNumber, $app_id, $screenIdHome, $lintoId, $display, $bgcolor);
        }
        $adindex = 1;
        foreach ($rowNavFetch as $rowNavKey => $rowNavValue) {
            $idOfScreen = $rowNavValue['id'];

            if ($screenplanId == 2 && $adindex % 2 == 0) {
                $sqlAdmap = "INSERT INTO appserver_screenmapping (adserver_details_id,screen_id) VALUES ('" . $lastAdid . "','" . $idOfScreen . "')";
                $statementAdmap = $dbCon->prepare($sqlAdmap);
                $statementAdmap->execute();
            }
            $adindex++;
            $screenSequence = $rowNavValue['background_type'];
            $sqlUserInsert = "update componentfieldvalue set linkto_screenid='" . $idOfScreen . "' where app_id='" . $app_id . "'  and linkto_screenid='" . $screenSequence . "'";

            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
            $statementUserInsert->execute();

            $sqlUserevent = "update app_event_rel set linkto_screenid='" . $idOfScreen . "' where app_id='" . $app_id . "'  and linkto_screenid='" . $screenSequence . "'";
            $statementUserevent = $dbCon->prepare($sqlUserevent);
            $statementUserevent->execute();

            $app_parent = "select * from componentfieldvalue where app_id='" . $app_id . "' and linkto_screenid='" . $idOfScreen . "'";
            $appQueryparent = $dbCon->query($app_parent);
            $rowparentFetch = $appQueryparent->fetch(PDO::FETCH_ASSOC);
            $screenId = $rowparentFetch['screen_id'];
            if ($screenId != 0) {
                $sqlParent = "update screen_title_id set parent_id='" . $screenId . "' where app_id='" . $app_id . "'  and id='" . $idOfScreen . "' and parent_id !=0";
                $statementParent = $dbCon->prepare($sqlParent);
                $statementParent->execute();
            }
        }
		
		if($countofupdatescreen>0)
                        {       
                         
							$data['app_id'] = $app_id;
							//$data['title'] = $val['title'];
							//$data['desc'] = $val['message'];
							
						 echo json_encode($Basearray);
						 $data['action_data']=json_encode($Basearray);
						 $data['action_tag']=3;
                            send_notification($data);
                    
                        }
		


        /*  $sqlUserInsert = "INSERT INTO screen_title_id (app_id,screen_type,image_url,height,width,title,font_size,font_color,font_typeface,parent_id,popup_flag,background_type,background_color,is_visible,event_type,server_time,is_bypass) VALUES ('" . $app_id . "','14','e049','" . $height . "','" . $width . "','Report Misconduct','" . $font_size . "','" . $font_color . "','" . $font_typeface . "','-1','0','','','1','0','','0'),('" . $app_id . "','16','e050','" . $height . "','" . $width . "','Feedback','" . $font_size . "','" . $font_color . "','" . $font_typeface . "','-1','0','','','1','0','','0')";
          $statementUserInsert = $dbCon->prepare($sqlUserInsert);
          $statementUserInsert->execute(); */
    } catch (Exception $e) {
        echo $e;
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
?>