<?php

require 'Slim/Slim.php';
require 'includes/db.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/login', 'login');

function login() {
    if (isset($_POST['app_id']) && isset($_POST['email_address']) && isset($_POST['password'])) {
        $dbCon = content_db();
        $appID = $_POST['app_id'];
        $appIdQuery = "select id  from app_data where app_id=:appid";
        $appIdData = $dbCon->prepare($appIdQuery);
        $appIdData->bindParam(':appid', $appID, PDO::PARAM_INT);
        $appIdData->execute();
        $appIdResult = $appIdData->fetch(PDO::FETCH_OBJ);

        if ($appIdResult != '') {
            $app_id = $appIdResult->id; 
        }

        $emailAddress = $_POST['email_address'];
        $password = md5($_POST['password']);

        $query = "select email_address,is_verified,password,app_id from user_appdata where app_id=:appid and email_address =:email and password =:password ";
        $queryData = $dbCon->prepare($query);
        $queryData->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $queryData->bindParam(':email', $emailAddress, PDO::PARAM_STR);
        $queryData->bindParam(':password', $password, PDO::PARAM_STR);
        $queryData->execute();
        $queryResult = $queryData->fetch(PDO::FETCH_ASSOC);
        $count = $queryData->rowCount();


        if ($count != 1) {
            $responce = array();
            $response = array("result" => 'error', "msg" => '311');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
            die;
        } else {
            if ($queryResult['is_verified'] != 1) {
                $responce = array();
                $response = array("result" => 'success', "msg" => '310');
                $Basearray = array("response" => $response);
                $basejson = json_encode($Basearray);
                echo $basejson;
                die;
            }
            $responce = array();
            $response = array("result" => 'success', "msg" => '309');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
            die;
        }
    } else {
        $responce = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
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
