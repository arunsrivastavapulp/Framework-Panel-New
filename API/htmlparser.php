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
$dbCon = getConnection();
/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();
$app->post('/getData', 'getData');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */
global $dom;
$dom = new DOMDocument;

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
    $dbCon = getConnection();
    $iconName1 = str_replace("icon_upload_img ", "", $icoCode);
    $iconName = str_replace("icon-", "", $iconName1);
    $app_icon = "select * from icomoon_icons where name like '%" . $iconName . "%'";

    $appQueryicon = $dbCon->query($app_icon);
    $rowiconFetch = $appQueryicon->fetch(PDO::FETCH_ASSOC);
    $imgUrl = $rowiconFetch['icomoon_code'];
    return $imgUrl;
}


function component_2($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor,$data_shared_text, $data_shared_link) {
 
    $dbCon = getConnection();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 2;
    $appQueryData = "select * from customfielddata where component_type_id='2'";
    $app_screenData = $dbCon->query($appQueryData);
    $result_screenData = $app_screenData->fetchAll(PDO::FETCH_OBJ);
    $actionShareData='';
$actionShareData = json_encode(array('shared_text' => $data_shared_text, 'shared_link' => $data_shared_link),JSON_UNESCAPED_UNICODE);
    $resultSet['background_color'] = '#FFFFFF';
    $resultSet['card_elevation'] = '2';
    $resultSet['card_corner_radius'] = '3';
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['action_type_id']='';
    $resultSet['action_data']='';
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
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

    $listcomponent = count($componentData);

    for ($ii = 0; $ii <= $listcomponent; $ii++) {       
        $listno = $ii + 1;
        $resultSet['tab_headingData' . $ii]='';
        if(isset($componentData[$ii]['children'][0]['class']))
        {
            $icoCode=$componentData[$ii]['children'][0]['class'];
            $iconName1 = str_replace("fa ", "", $icoCode);
            $iconName = str_replace("fa-", "", $iconName1);
           $resultSet['tab_headingData' . $ii] = getIcomoon($iconName); 
           if($resultSet['tab_headingData' . $ii] == 'e045')
           {
            $resultSet['action_type_id']='2';
    $resultSet['action_data']=$actionShareData;   
           }elseif($resultSet['tab_headingData' . $ii] == 'e047')
           {
                $resultSet['action_type_id']='6';
                $resultSet['action_data']=$lintoId;    
           }
           
        }
         if ($ii == $listcomponent) {
            $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '0', '" . addslashes($resultSet['tab_headingData' . $ii]) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "')";  
         }
         else
         {
          $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '" . $listno . "', '" . $componentNumber . "', NULL, '0', '" . addslashes($resultSet['tab_headingData' . $ii]) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . addslashes($resultSet['font_typeface']) . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
         }
    }
    
    
    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";


    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 2 inserted successfully";
    }
}


function component_3($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor) {
    $dbCon = getConnection();
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

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

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

function component_5($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor,$screen_type) {

    $dbCon = getConnection();
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
if($screen_type == '5' || $screen_type == '8')
{
  $resultSet['card_elevation'] = '0';
    $resultSet['card_corner_radius'] = '0';  
}

    
    $resultSet['linkto_screenid'] = $lintoId;
    $resultSet['position'] = '1';
    $resultSet['font_color'] = '#8b8b8b';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['display'] = $display;
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['font_typeface1'] = $resultSet['font_typeface'];
    $resultSet['font_color1'] = $resultSet['font_color'];
    $resultSet['font_size1'] = $resultSet['font_size'];
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

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

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
        $resultSet['font_size1'] = '12';
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


    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_bottomData']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color3'] . "', '" . $resultSet['font_typeface3'] . "', '" . $resultSet['font_size3'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_headingData']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color2'] . "', '" . $resultSet['font_typeface2'] . "', '" . $resultSet['font_size2'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_heading']) . "', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);

    if ($statementUserInsert->execute()) {
        echo "component 5 inserted successfully";
    }
}

function component_7($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor) {
    $dbCon = getConnection();
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



    if (isset($componentData[0]['style'])) {
        $cssProperty = $componentData[0]['style'];
        $resultAttributes = getAttribute($cssProperty);
        $resultSet['font_typeface'] = $resultAttributes['font_typeface'];
        $resultSet['font_color'] = $resultAttributes['font_color'];
        $resultSet['font_size'] = $resultAttributes['font_size'];
    }

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL, NULL, NULL,NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

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
    }
    if (isset($componentData[2]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
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

function component_9($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor) {

    $dbCon = getConnection();
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
    $resultSet['font_color'] = '#000000';
    $resultSet['font_typeface'] = 'Arial';
    $resultSet['font_size'] = '12';
    $resultSet['height'] = '18';
    $resultSet['width'] = '18';
    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['listNo'] = 1;
    $resultSet['tab_headingDatatypeface'] = '';
    $resultSet['tab_headingDatacolor'] = '';
    $resultSet['tab_headingDatasize'] = '';
    $resultSet['item_contenttypeface'] = '';
    $resultSet['item_contentsize'] = '';
    $resultSet['item_contentcolor'] = '';
    $resultSet['Descriptiontypeface'] = '';
    $resultSet['Descriptionsize'] = '';
    $resultSet['Descriptioncolor'] = '';
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

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

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
    } else {
        $resultSet['Description'] = '';
    }
    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
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

function component_13($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link) {

    $dbCon = getConnection();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
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
    $resultSet['font_size1'] = $resultSet['font_size'];

    $resultSet['font_typeface2'] = $resultSet['font_typeface'];
    $resultSet['font_color2'] = $resultSet['font_color'];
    $resultSet['font_size2'] = $resultSet['font_size'];

    $resultSet['font_typeface3'] = $resultSet['font_typeface'];
    $resultSet['font_color3'] = $resultSet['font_color'];
    $resultSet['font_size3'] = $resultSet['font_size'];
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


    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

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
    $actionShareData = array();
    $actionShareData = json_encode(array('shared_text' => $data_shared_text, 'shared_link' => $data_shared_link),JSON_UNESCAPED_UNICODE);
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

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'image', NULL,'" . addslashes($resultSet['tab_img']) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "',  '" . $mediaType . "', NULL, '" . addslashes($resultSet['tab_img']) . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "',  '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['heading']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['subheading']) . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color2'] . "',  '" . $resultSet['font_typeface2'] . "', '" . $resultSet['font_size2'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['description']) . "', NULL, NULL,'24', '24', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color3'] . "', '" . $resultSet['font_typeface3'] . "', '" . $resultSet['font_size3'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[5]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '6', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_fav']) . "', NULL, NULL,'16', '16', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b',   'icomoon.ttf', '16', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '4', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[6]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '7', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_share']) . "', NULL, NULL,'16', '16', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '2', '" . $actionShareData . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 13 inserted successfully";
    }
}

function component_14($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor) {

    $dbCon = getConnection();
    $myAppId = $app_id;
    $screen_id = $screenId;
    $position = 1;
    $resultSet = array();
    $resultSet['comp_type'] = 14;
    $appQueryData = "select * from customfielddata where component_type_id='14'";
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

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

    $listcomponent = count($result_screenData);

    $resultSet['componentfieldoption_id'] = $result_screenData[0]->componentfieldoption_id;
    $resultSet['tab_heading'] = '';

    if (isset($componentData[1]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[1]['children'][0]['src'];
    }

    if ($resultSet['tab_heading'] == '' && isset($componentData[0]['children'][0]['src'])) {
        $resultSet['tab_heading'] = $componentData[0]['children'][0]['src'];
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
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', 'image', NULL,'" . addslashes($resultSet['tab_heading']) . "', '1000', '1150', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
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

function component_15($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor) {

 
    $dbCon = getConnection();
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
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";

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

function component_17($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor) {

    $dbCon = getConnection();
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




    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL, NULL, NULL,NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";


    $resultSet['tab_heading'] = '';

    if (isset($resultSet['tab_heading'])) {
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

function component_19($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link) {
                                                
    $dbCon = getConnection();

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

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL,NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '".$display."', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

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
    $actionShareData = json_encode(array('shared_text' => $data_shared_text, 'shared_link' => $data_shared_link),JSON_UNESCAPED_UNICODE);

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
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $mediaType . "',null,'".$resultSet['tab_img']."','" . $videoUrl . "','" . $height . "', '" . $width . "', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '".$display."', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_heading']) . "',NULL ,NULL, NULL,'24', '12', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color1'] . "', '" . $resultSet['font_typeface1'] . "', '" . $resultSet['font_size1'] . "', '".$display."', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '3', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '', NULL,NULL, NULL,'24', '12', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', 'icomoon.ttf', '" . $resultSet['font_size'] . "', '".$display."', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '" . $resultSet['action_type_id'] . "', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[3]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '4', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_fav']) . "', NULL,NULL, NULL,'16', '16', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '".$display."', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '4', '" . $resultSet['action_data'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[4]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . addslashes($resultSet['tab_share']) . "', NULL,NULL, NULL,'16', '16', NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '#8b8b8b', 'icomoon.ttf', '16', '".$display."', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "', '2', '" . $actionShareData . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`,`video_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`,`action_type_id`,`action_data`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 19 inserted successfully";
    }
}

function component_29($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $linkphone, $bgcolor) {

    $dbCon = getConnection();
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

    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "',NULL,NULL),";

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

function component_30($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $linkphone, $bgcolor) {

    $dbCon = getConnection();
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


    if ($resultSet['font_size2'] == '0' || $resultSet['font_size2'] == '') {
        $resultSet['font_size2'] = '12';
    }
    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . addslashes($resultSet['action_data']) . "'),";
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

    $dbCon = getConnection();
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
    $resultSet['display'] = '1';
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

function component_33($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor) {

    $dbCon = getConnection($componentData);
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



    $valueQuery = "('" . $myAppId . "', '" . $resultSet['comp_type'] . "',  NULL,NULL,  '" . $screen_id . "', '-1',NULL, '" . $componentNumber . "', NULL, '" . $componentNumber . "', NULL, NULL, NULL,NULL, NULL, NULL, 'solid', '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', '" . $resultSet['display'] . "', NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "','" . $resultSet['action_type_id'] . "','" . $resultSet['action_data'] . "'),";

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

function component_42($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor) {

    $dbCon = getConnection();
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
    $resultSet['accesstoken'] = 'CAAXblGnn7joBAK0EoCneOflaEY5QAhcPsPp4PodGpgYXCz3GPylirvjLgfXHIrWo4ADxxNtJ4FptWDMAs8WZBHZBuAvdbKt78ODp5f7Hdk9xgZCmIutrzTyyb05bHxbzJUQZAXx1pBEPE3c8xKx7DMvIzx5mSiB6qmEPvWH3kIdaYVuPChBroQP8I8BcQ9jagL9lxy37VQZDZD';

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


    $dbCon = getConnection();
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

    $dbCon = getConnection();
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

    if (isset($componentData[2]['children'][0]['html'])) {
        $resultSet['tab_headingData'] = $componentData[2]['children'][0]['html'];
    } else {
        $resultSet['tab_headingData'] = '';
    }

    $resultSet['font_size'] = str_replace("px", "", $resultSet['font_size']);
    if ($resultSet['font_size'] == '0' || $resultSet['font_size'] == '') {
        $resultSet['font_size'] = '12';
    }

    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[0]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '1', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $lat . "', NULL,NULL, NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[1]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '2', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $long . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "'),";
    $valueQuery .="( '" . $myAppId . "', '" . $resultSet['comp_type'] . "',  '" . $result_screenData[2]->componentfieldoption_id . "','',  '" . $screen_id . "', '" . $resultSet['linkto_screenid'] . "', '5', '" . $componentNumber . "', NULL, '" . $componentNumber . "', '" . $resultSet['tab_headingData'] . "', NULL, NULL,NULL, NULL, NULL, NULL, '" . $resultSet['background_color'] . "',NULL, '" . $resultSet['font_color'] . "', '" . $resultSet['font_typeface'] . "', '" . $resultSet['font_size'] . "', NULL, NULL, '0','" . $resultSet['card_elevation'] . "','" . $resultSet['card_corner_radius'] . "')";


    $sqlUserInsert = "INSERT INTO `componentfieldvalue` ( `app_id`, `component_type_id`, `componentfieldoption_id`, `componentarraylink_id`, `screen_id`, `linkto_screenid`, `list_no`, `component_no`, `defaultselect`, `component_position`, `description`, `datevalue`,`image_url`, `height`, `width`, `item_orientation`, `background_type`, `backgroundcolor`, `texttodisplay`, `font_color`, `font_typeface`, `font_size`, `display`, `visibility`, `auto_update`,`card_elevation`,`card_corner_radius`)
	VALUES $valueQuery";

    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
    if ($statementUserInsert->execute()) {
        echo "component 44 inserted successfully";
    }
}

function component_100($componentData, $componentNumber, $app_id, $screenIdHome, $lintoId, $display, $bgcolor) {

    $dbCon = getConnection();
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
    $dbCon = getConnection();
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
    $dbCon = getConnection();
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
    $dbCon = getConnection();
    $theme_query = "select * from themes_app_rel where app_id='" . $app_id . "'";
    $themeQueryRun = $dbCon->query($theme_query);
    $rowFetchtheme = $themeQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $calRowtheme = $themeQueryRun->rowCount();
    if ($calRowtheme == 0) {
        $sqlDrawerInsert = "INSERT INTO `themes_app_rel` (`app_id`, `themes_id`, `typefaceHeading`, `typefaceSubHeading`, `typefaceNormal`, `heading_size`, `subHeading_size`, `Normal`, `headingColor`, `subHeadingColor`, `defaultTextColor`,  `addTexture`,`liked_item_color`) VALUES ( '" . $app_id . "', '" . $themeId . "', 'Roboto-Regular.ttf', 'Roboto-Regular.ttf', 'Roboto-Regular.ttf', '25', '20', '20', '15', '#dfdfdf', '#dfdfdf', NULL,'#FF0000');";
        $statementDrawerInsert = $dbCon->prepare($sqlDrawerInsert);
        $statementDrawerInsert->execute();
    } else {
        $publisedApp = "update themes_app_rel set liked_item_color='#FF0000' where app_id='" . $app_id . "'";
        $publisedAppParent = $dbCon->prepare($publisedApp);
        $publisedAppParent->execute();
    }
}

function authCheck($app_id, $autherId) {
    $dbCon = getConnection();
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
        $dbCon = getConnection();
        $app_query = "select ash.id,ash.app_id,ash.screen_sequence,ash.title,ash.banner_html,ash.navigation_html,ash.content_html,ash.autherId,ash.ico_icon,ash.deleted,ash.screen_title_id,slm.layout_type_id as layoutType from app_screenmapping_html ash left join screen_layout_mapping slm on ash.layoutType = slm.screen_type_id where app_id='" . $app_id . "'";
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
            /*   $sqlDeleteAll = "delete from `screen_title_id` where app_id='" . $app_id . "'";
              $statementsqlDeleteAll = $dbCon->prepare($sqlDeleteAll);
              $statementsqlDeleteAll->execute(); */
            $sqlDelete = "delete from `componentfieldvalue` where app_id='" . $app_id . "'";
            $statementsqlDelete = $dbCon->prepare($sqlDelete);
            $statementsqlDelete->execute();
            navbar($app_id);
            themerelation($app_id, $themeId);

            $countOfPage = count($rowFetch);
            $TotalNav = 1;
            foreach ($rowFetch as $keyAs => $valueAs) {
                $screen_type = '';
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
                $font_size = '17';
                $font_color = '#FFFFFF';
                $font_typeface = 'Arial';
                $parent_id = '-1';
                $popup_flag = '0';
                $background_color = '#8b8b8b';
                $is_visible = '0';
                $event_type = '0';
                $server_time = '';
                $is_bypass = '0';

                $dateTime = date("Y-m-d");

                if ($background_type == '1') {
                    $parent_id = '0';
                }
                $iconSet = $valueAs['ico_icon'];
                $imgUrl = '';
                if ($iconSet != '') {

                    $imgUrl = getIcomoon($iconSet);
                }
                $screenid_Html = $valueAs['screen_title_id'];
                if ($valueAs['deleted'] == '1') {
                    $sqlDeleteAll = "delete from `screen_title_id` where app_id='" . $app_id . "' and id='" . $screenid_Html . "'";
                    $statementsqlDeleteAll = $dbCon->prepare($sqlDeleteAll);
                    $statementsqlDeleteAll->execute();
                } else {
                    if ($screenid_Html == 0) {
                        $CheckScreenEntry = "select * from screen_title_id where app_id='" . $app_id . "'  and title='" . $title . "' and background_type='" . $background_type . "'";

                        $CheckScreenEntryRun = $dbCon->query($CheckScreenEntry);
                        $rowFetchNav = $CheckScreenEntryRun->fetch(PDO::FETCH_ASSOC);
                        $CheckScreenEntryRunNav = $CheckScreenEntryRun->rowCount();
                        if ($CheckScreenEntryRunNav == 0) {
                            $sqlUserInsert = "INSERT INTO screen_title_id (app_id,screen_type,image_url,height,width,title,font_size,font_color,font_typeface,parent_id,popup_flag,background_type,background_color,is_visible,event_type,server_time,is_bypass) VALUES ('" . $app_id . "','" . $screen_type . "','" . $imgUrl . "','" . $height . "','" . $width . "','" . $title . "','" . $font_size . "','" . $font_color . "','" . $font_typeface . "','" . $parent_id . "','" . $popup_flag . "','" . $background_type . "','" . $background_color . "','" . $is_visible . "','" . $event_type . "','" . $dateTime . "','" . $is_bypass . "')";
                            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                            $statementUserInsert->execute();
                            $screenId = $dbCon->lastInsertId();
                            $sqlscreenmapping_html = "update app_screenmapping_html set screen_title_id='" . $screenId . "' where app_id='" . $app_id . "'  and id='" . $valueAs['id'] . "'";
                            $statement_screenmapping = $dbCon->prepare($sqlscreenmapping_html);
                            $statement_screenmapping->execute();
                        } else {

                            $sqlUserInsert = "update screen_title_id set screen_type='" . $screen_type . "',image_url='" . $imgUrl . "',height='" . $height . "',width='" . $width . "',title='" . $title . "',font_size='" . $font_size . "',font_color='" . $font_color . "',font_typeface='" . $font_typeface . "',parent_id='" . $parent_id . "',popup_flag='" . $popup_flag . "',background_type='" . $background_type . "',background_color='" . $background_color . "',is_visible='" . $is_visible . "',event_type='" . $event_type . "',server_time=now(),is_bypass='" . $is_bypass . "' where app_id='" . $app_id . "' and id='" . $rowFetchNav['id'] . "'";
                            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                            $statementUserInsert->execute();
                            $screenId = $valueAs['id'];

                            $sqlscreenmapping_html = "update app_screenmapping_html set screen_title_id='" . $rowFetchNav['id'] . "' where app_id='" . $app_id . "'  and id='" . $valueAs['id'] . "'";
                            $statement_screenmapping = $dbCon->prepare($sqlscreenmapping_html);
                            $statement_screenmapping->execute();
                        }
                    } else {
                        $sqlUserInsert = "update screen_title_id set screen_type='" . $screen_type . "',image_url='" . $imgUrl . "',height='" . $height . "',width='" . $width . "',title='" . $title . "',font_size='" . $font_size . "',font_color='" . $font_color . "',font_typeface='" . $font_typeface . "',parent_id='" . $parent_id . "',popup_flag='" . $popup_flag . "',background_type='" . $background_type . "',background_color='" . $background_color . "',is_visible='" . $is_visible . "',event_type='" . $event_type . "',server_time=now(),is_bypass='" . $is_bypass . "' where app_id='" . $app_id . "' and id='" . $screenid_Html . "'";
                        $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                        $statementUserInsert->execute();
                        $screenId = $screenid_Html;
                        $sqlscreenmapping_html = "update app_screenmapping_html set screen_title_id='" . $screenId . "' where app_id='" . $app_id . "'  and id='" . $valueAs['id'] . "'";
                        $statement_screenmapping = $dbCon->prepare($sqlscreenmapping_html);
                        $statement_screenmapping->execute();
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
                        $htmltags = array("<strong>", "</strong>","<u>", "</u>", "<br>", "<b", "<i>", "<I>", "</b>", "</i>", "</I>", "<font", "</font>");
                        $repalcedHtml = array("&lt;strong&gt;", "&lt;/strong&gt;","&lt;u&gt;", "&lt;/u&gt;", "&lt;br&gt;", "&lt;b", "&lt;i&gt;", "&lt;I&gt;", "&lt;/b&gt;", "&lt;/i&gt;", "&lt;/I&gt;", "&lt;font", "&lt;/font&gt;");
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
                                    if (isset($componentDataAll['data-shared-link'])) {
                                        $data_shared_link = $componentDataAll['data-shared-link'];
                                    } else {
                                        $data_shared_link = '';
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



                                        if (isset($componentNo)) {
                                            switch ($componentNo) {

                                                case "1":
                                                    component_1($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "2":
                                                    component_2($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor,$data_shared_text, $data_shared_link);
                                                    $componentNumber++;
                                                    break;
                                                case "3":

                                                    component_3($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "4":
                                                    component_4($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "5":
                                                    component_5($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor,$screen_type);
                                                    $componentNumber++;
                                                    break;
                                                case "6":
                                                    component_6($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "7":
                                                    component_7($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "8":
                                                    component_8($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "9":
                                                    component_9($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                         break;

                                                case "10":

                                                    component_10($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "13":
                                                    component_13($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link);
                                                    $componentNumber++;
                                                    break;

                                                case "14":

                                                    component_14($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "15":
                                                   
                                                    component_15($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "17":
                                                    component_17($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor);
                                                    $componentNumber++;
                                                    break;

                                                case "19":

                                                    component_19($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $videoUrl, $bgcolor, $data_shared_text, $data_shared_link);
                                                    $componentNumber++;
                                                    break;



                                                case "29":
                                                    component_29($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $linkphone, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "30":
                                                    component_30($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $linkphone, $bgcolor);
                                                    $componentNumber++;
                                                    break;
                                                case "33":
                                                    component_33($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
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
                                                //   case "100":
                                                //  component_100($componentData, $componentNumber, $app_id, $screenId, $lintoId, $display, $bgcolor);
                                                //  $componentNumber++;
                                                //   break;

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
                                                default:
                                                    echo "undefined component";
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

                        if (isset($jsonConvertnav['children'][0]['children'][0])) {
                            if (isset($jsonConvertnav['children'][0]['children'][0]['data-appbackground'])) {

                                $background_color = $jsonConvertnav['children'][0]['children'][0]['data-appbackground'];
                            }

                            if (isset($jsonConvertnav['children'][0]['children'][0]['style'])) {
                                $cssProperty = $jsonConvertnav['children'][0]['children'][0]['style'];
                                $resultAttributes = getAttribute($cssProperty);
                                $background_color_nav = $resultAttributes['background_color'];

                                $sqlNavColor = "update app_data set background_color='" . $background_color_nav . "' where id='" . $app_id . "' ";

                                $statementNavColor = $dbCon->prepare($sqlNavColor);
                                $statementNavColor->execute();
                            }


                            $arrayOfStyle = $jsonConvertnav['children'][0]['children'];

                            if (isset($arrayOfStyle[1]['children'][0]['children'])) {
                                $countOfnav = $arrayOfStyle[1]['children'][0]['children'];
                                $inrmnt = 0;
                                foreach ($countOfnav as $navKey => $navValue) {
                                    $iconId = '';
                                    $icomoon = '';
                                    if (isset($navValue['children'][0]['children'][0]['class'])) {
                                        $icocode = $navValue['children'][0]['children'][0]['class'];
                                        $icomoon = getIcomoon($icocode);
                                    }
                                    $others = '';
                                    if (isset($navValue['data-email'])) {
                                        $others = $navValue['data-email'];
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
                                            if ($icomoon == '') {
                                                $icomoon = 'e049';
                                            }
                                        } elseif ($navevisible == 'Feedback') {
                                            $screen_type = '16';
                                            $background_type = '1000';
                                            if ($icomoon == '') {
                                                $icomoon = 'e050';
                                            }
                                        }
                                        $navCheck = "select * from screen_title_id where app_id='" . $app_id . "'  and title='" . $navevisible . "'";
                                        $navCheckRun = $dbCon->query($navCheck);
                                        $rowFetchNav = $navCheckRun->fetchAll(PDO::FETCH_ASSOC);
                                        $calRowNav = $navCheckRun->rowCount(PDO::FETCH_NUM);
                                        if ($calRowNav == 0) {
                                            if ($navevisible == 'Report Misconduct' || $navevisible == 'Feedback') {
                                                $sqlNav = "INSERT INTO screen_title_id (app_id,screen_type,image_url,height,width,title,font_size,font_color,font_typeface,parent_id,popup_flag,background_type,background_color,is_visible,event_type,server_time,is_bypass,others) VALUES ('" . $app_id . "','" . $screen_type . "','" . $icomoon . "','" . $height . "','" . $width . "','" . $navevisible . "','" . $font_size . "','" . $font_color . "','" . $font_typeface . "','-1','" . $popup_flag . "','" . $background_type . "','" . $background_color . "','1','" . $event_type . "','" . $dateTime . "','" . $is_bypass . "','" . $others . "')";
                                                $stateNav = $dbCon->prepare($sqlNav);
                                                $stateNav->execute();
                                            }
                                        } else {
                                            $sqlUserInsert = "update screen_title_id set is_visible='1',background_color='" . $background_color . "' where app_id='" . $app_id . "'  and title='" . $navevisible . "'";
                                            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                                            $statementUserInsert->execute();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $app_navquery = "select * from screen_title_id where app_id='" . $app_id . "'";
        $appQueryNavRun = $dbCon->query($app_navquery);
        $rowNavFetch = $appQueryNavRun->fetchAll(PDO::FETCH_ASSOC);

        $app_data = "select p.plan_type from plans p left join app_data ad on p.id=ad.plan_id where ad.id='" . $app_id . "'";
        $appQuerydata = $dbCon->query($app_data);
        $rowparentFetchdata = $appQuerydata->fetch(PDO::FETCH_ASSOC);
        $countofPlan = count($rowparentFetchdata);
        $screenplanId = $rowparentFetchdata['plan_type'];

        if ($screenplanId == 2 || $screenplanId == 1 || $screenplanId == null || $countofPlan == 0) {
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
