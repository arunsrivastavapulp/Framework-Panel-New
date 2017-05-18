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
$app->get('/register', 'getRegister');
/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */


function getRegister() {
$gPlus_token='';
$fb_token='';
if(isset($_GET['fname']) && isset($_GET['email']) && isset($_GET['userFbId']))
{
if(isset($_GET['fb_token']))
{
  $fb_token = $_GET['fb_token'];  
}
else
{
 $gPlus_token = $_GET['gPlus_token'];   
}
    
    $fname = $_GET['fname'];
    $lname = $_GET['lname'];
    $email = $_GET['email'];
    $userFbId = $_GET['userFbId'];  
}
else
{
			$response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
			$Basearray = array("response" => $response);		
			$basejson = json_encode($Basearray);
			echo $basejson; 
  die;
}

    $app_query = "select  id from author where email_address='" . $email . "'";
   
    try {
        if ($email != '' && $userFbId != '') {
          
            $dbCon = content_db();

            $appQueryRun = $dbCon->query($app_query);
            $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
            $calRow = $appQueryRun->rowCount();
        
            if ($calRow == 0) {
                $sqlUserInsert = "INSERT INTO author (`fb_token`,`gPlus_token`,`first_name`,`last_name`,`email_address`,`state`,`city`,`pincode`) VALUES ('" . $fb_token . "','" . $gPlus_token . "','" . $fname . "','" . $lname . "','" . $email . "','delhi','delhi','123')";
                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                $statementUserInsert->execute();        
 

            } else {
			
		die;	
                
            }
        } else {
          $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
			$Basearray = array("response" => $response);		
			$basejson = json_encode($Basearray);
			echo $basejson; 
        }
    } catch (Exception $e) {
$response = array("result" => 'error', "msg" => 'something went wrong');
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
