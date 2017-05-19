<?php
require_once ('config/db.php');
$db = new DB();
$url = $db->siteurl();

 $app_id = htmlentities($_POST['app_id']);
 $themeId = htmlentities($_POST['themeId']);
$app_type = htmlentities($_POST['app_type']);
$autherId = htmlentities($_POST['autherId']);

$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url.'API/getappfromhtml.php/getData',
            CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'app_id' => $app_id,
                'themeId' => $themeId,
                'app_type' => $app_type,
                'autherId' => $autherId
            )
        ));
        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);


?>