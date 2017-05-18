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
//require_once 'config/includes/db.php';
//$db = new DB();
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
$app->post('/getnumberofuser', 'getnumberofuser');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */


   function getnumberofuser() {
    $ids = 'ga:105882894';
    $startdate = '2005-01-01';
    $enddate = date("Y-m-d");
    $metrics = 'ga:users';
    $access_token = 'ya29.GwIDe6yMh7Gm9SSvq9UcyfF3vPXSUOM2bsmwBZTydKp6zn4NATtESIvQHwCK41uSdTUKJg';

        $mainurl = 'https://www.googleapis.com/analytics/v3/data/ga?ids='.$ids.'&start-date='.$startdate.'&end-date='.$enddate.'&metrics='.$metrics.'&access_token='.$access_token.'';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$mainurl);
        $result=curl_exec($ch);
        curl_close($ch);
        $ravi = json_decode($result);
        print $ravi->totalsForAllResults->{'ga:users'};
        //echo $result;
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
