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
$app->post('/verifysignup', 'verifysignup');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */
function verifysignup() {
    $dbCon = content_db();
    if (isset($_POST['app_id']) && isset($_POST['email_address']) && isset($_POST['otp'])) {
        $app_id = $_POST['app_id'];
        $email = $_POST['email_address'];
        $otp = $_POST['otp'];
        $query = "select id from app_data where app_id =:id";
        $queryRun = $dbCon->prepare($query);
        $queryRun->bindParam(":id", $app_id,PDO::PARAM_INT);
        $queryRun->execute();
        $queryFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
        $appID = $queryFetch['id'];
        
        $app_query = "select id from user_appdata where app_id =:appid and email_address=:email and verification_code=:opt";
        $queryRun = $dbCon->prepare($app_query);
        $queryRun->bindParam(":appid", $appID,PDO::PARAM_INT);
        $queryRun->bindParam(":email", $email,PDO::PARAM_STR);
        $queryRun->bindParam(":opt", $otp,PDO::PARAM_INT);
        $queryRun->execute();
        $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
        $calRow = $appQueryRun->rowCount();

        if ($calRow != '1') {

            $response = array("result" => 'error', "msg" => '318');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
            die;
        } else {
            $digits = 4;
            $auto_otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $updateQuery = "update user_appdata set verification_code=:vfcode,is_verified='1' where  app_id =:appid and email_address=:email";
            $updated = $dbCon->prepare($updateQuery);
            $updated->bindParam(":vfcode", $auto_otp,PDO::PARAM_STR);
            $updated->bindParam(":appid", $appID,PDO::PARAM_INT);
            $updated->bindParam(":email", $email,PDO::PARAM_STR);
            $updated->execute();

            $response = array("result" => 'success', "msg" => '319');
            $Basearray = array("response" => $response);
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
