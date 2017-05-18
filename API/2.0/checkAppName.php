<?php

require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/index', 'index');

function index() {
    $dbCon = content_db();
    $baseUrl = baseUrlContent();
    $appId = '';
    if (isset($_POST['app_name']) && isset($_POST['author_id']) && $_POST['author_id'] != '') {
        $app_name = $_POST['app_name'];

        $author_id = $_POST['author_id'];
        if (isset($_POST['app_id'])) {
            $appId = $_POST['app_id'];
        }
        if ($appId == '') {
            $auth_query = "select id from app_data where summary=:appname and author_id=:authid  AND deleted = 0";
            $auth_queryExecution = $dbCon->prepare($auth_query);
            $auth_queryExecution->bindParam(':appname', $app_name, PDO::PARAM_STR);
            $auth_queryExecution->bindParam(':authid', $author_id, PDO::PARAM_INT);
        } else {
            $auth_query = "select id from app_data where summary=:appname and author_id=:authid  and id != :appid and deleted = 0";
            $auth_queryExecution = $dbCon->prepare($auth_query);
            $auth_queryExecution->bindParam(':appname', $app_name, PDO::PARAM_STR);
            $auth_queryExecution->bindParam(':authid', $author_id, PDO::PARAM_INT);
            $auth_queryExecution->bindParam(':appid', $appId, PDO::PARAM_INT);
        }

        $auth_queryExecution->execute();
        $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
        if ($result_auth > 0) {
            $AppExist = 0;
        } else {
            $AppExist = 1;
        }


        $response = array("app_name_available" => $AppExist);
        $Basearray = array("msg_code" => '200', "response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
    } else {
        if ($_POST['author_id'] == '') {
            echo json_encode(array("msg_code" => '501', "msg" => 'Please login'));
        } else {
            echo json_encode(array("msg_code" => '500', "msg" => 'oops! something went wrong.'));
        }
    }
}

$app->run();
