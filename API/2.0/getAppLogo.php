<?php

require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/getapplogo', 'getapplogo');

function getapplogo() {
    $dbCon = content_db();
    if (isset($_POST['app_id'])) {
        $app_id = $_POST['app_id'];

        $query = "select app_signupdata.company_logo,app_signupdata.app_signupsource_id,app_signupdata.description from app_signupdata,app_data where app_signupdata.app_id=app_data.id and app_data.app_id=:appid";
        $queryRun = $dbCon->prepare($query);
        $queryRun->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $queryRun->execute();
        $queryFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
        $calRow = $queryRun->rowCount();
        $logoUrl = $queryFetch['company_logo'];
        $description = $queryFetch['description'];

        $loginVia = explode(',', $queryFetch['app_signupsource_id']);

        $loginMethods = array();
        foreach ($loginVia as $value) {

            $loginQuery = "select name from app_signupsource where id=:id";
            $loginData = $dbCon->prepare($loginQuery);
            $loginData->bindParam(':id', $value, PDO::PARAM_INT);
            $loginData->execute();
            $login = $loginData->fetch(PDO::FETCH_ASSOC);
            $loginMethod[] = $login['name'];
        }

        if ($calRow != '1') {

            $response = array("result" => 'error', "msg" => 'Invalid App ID');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
            die;
        } else {
            $response = array("result" => 'success', "msg" => '');
            $Basearray = array("response" => $response, "app_details" => array("login_method" => $loginMethod, "logo_url" => $logoUrl, "description" => $description));
            $basejson = json_encode($Basearray);
            echo $basejson;
            die;
        }
    } else {
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
}

$app->run();
