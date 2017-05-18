<?php
function getConnection() {
    try {
    
$db_username = "hemant";
        $db_password = "Fdfjkhg%#$#@4312AS";
        $conn = new PDO('mysql:host=pulpstrategyinstance.cil4anb91ydi.us-west-2.rds.amazonaws.com;dbname=app_design_new', $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
 
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    return $conn;
}

function get_connection() {
    try {
    
		$db_username = "ecommerce_cat";
        $db_password = "ecommerce@12312";
        $conn = new PDO('mysql:host=pulpstrategyinstance.cil4anb91ydi.us-west-2.rds.amazonaws.com;dbname=ecommerce_app', $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
 
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    return $conn;
}

function baseUrlWeb() {
    $baseURL = "http://52.42.166.139/";
    return $baseURL;
}

function baseImageUrl() {
    //$baseURL = "http://www.instappy.com/catalogue/image/";
    $baseURL = "http://52.42.166.139/image/";
    return $baseURL;
}

function base_image_url() {
    //$baseURL = "http://www.instappy.com/catalogue/image/";
    $baseURL = "http://52.42.166.139/catalogue/image/";
    return $baseURL;
}

function baseUrl() {
    $baseURL = "http://52.42.166.139/API/"; 
    return $baseURL;
}

/*
 * XSS filter 
 *
 * This was built from numerous sources
 * (thanks all, sorry I didn't track to credit you)
 * 
 * It was tested against *most* exploits here: http://ha.ckers.org/xss.html
 * WARNING: Some weren't tested!!!
 * Those include the Actionscript and SSI samples, or any newer than Jan 2011
 *
 *
 * TO-DO: compare to SymphonyCMS filter:
 * https://github.com/symphonycms/xssfilter/blob/master/extension.driver.php
 * (Symphony's is probably faster than my hack)
 */

function xss_clean($data)
{
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {
                // Remove really unwanted tags
                $old_data = $data;
                $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

        // we are done...
        return $data;
}

function uploadImg() {
	$uploadImg = "../uploadImages/";
    return $uploadImg;
}
function uploadImgPath() {
    $uploadImgPath = "uploadImages/";
    return $uploadImgPath;
}
?>