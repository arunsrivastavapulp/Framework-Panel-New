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
$app->post('/sendmail', 'sendmail');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */


function sendmail() {

 /*    if (isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['auther_id']&& isset($_POST['email'])) {
        $app_idString = $_POST['app_id'];
        $authToken = $_POST['authToken'];
        $auther_id = $_POST['auther_id'];
		$email = $_POST['email'];
    } else {
        $response = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
    $dbCon = content_db(); */
   
    try {

  $subject = 'Thank you for creating Application with us';
  $content = 'We will be back soon with your fantastic application. You can create apps as many as you want';
  
$key       = 'f894535ddf80bb745fc15e47e42a595e';
$formemail='hemant@pulpstrategy.com';
$myto='hemant@pulpstrategy.com';
$url= 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($subject).'&fromname='.rawurlencode("Instappy Reminders").'&from='.$formemail.'&content='.rawurlencode($content).'&recipients='.$myto;
$head = file_get_contents($url);
	  echo "Thank you for contacting us!";
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
