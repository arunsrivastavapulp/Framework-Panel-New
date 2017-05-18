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
$app->post('/appsize', 'getAppSize');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */


function getAppSize() {
    if (isset($_POST['app_id'])) {        
        $appID = $_POST['app_id'];
        $dbCon = content_db();
        $planIdQuery = "select plan_id from app_data where id='" . $appID . "'";
        $planIdData = $dbCon->query($planIdQuery);
        $planIdResult = $planIdData->fetch(PDO::FETCH_ASSOC);
        $planID = $planIdResult['plan_id'];
        if(!isset($planID)){
          $planID = '1';
        }else{
          $planID = $planIdResult['plan_id'];
        }
        $limtQuery = "select plan_type,data_use_limit_gb from plans where id='".$planID."'";
         $limitData = $dbCon->query($limtQuery);
        $limitDataResult = $limitData->fetch(PDO::FETCH_ASSOC);
        $plan_type = $limitDataResult['plan_type'];
        $data_use_limit_gb = $limitDataResult['data_use_limit_gb'];
        if(($plan_type=='1') && ($data_use_limit_gb=='')){
          $data_use_limit_gb = '1';
        }else{
          $data_use_limit_gb = $limitDataResult['data_use_limit_gb'];
        }
        $gb = $data_use_limit_gb;
        $data_use_limit_gb = $data_use_limit_gb*1073741824;
//echo  $plan_type."--". $data_use_limit_gb;die;

        $baseurl = baseUrlContent();
        $appDirName= "../panelimage/$appID";

       
            if (file_exists($appDirName) && is_dir($appDirName)) {
    
                 $dh = opendir($appDirName);
                      $size = 0;
                      while ($file = @readdir($dh))
                      {
                        if ($file != "." and $file != "..") 
                        {
                          $path = $appDirName."/".$file;
                          if (is_dir($path))
                          {
                            $size += dirsize($path); // recursive in sub-folders
                          }
                          elseif (is_file($path))
                          {
                            $size += filesize($path); // add file
                          }
                        }
                      }
                      closedir($dh);
                      //fire mail when data uses rechead at 5MB
                      $compared = $size;
                     // $bytes = '5242880';
                      if($compared>=$data_use_limit_gb){

                        $cto  = 'heamnt@pulpstrategy.com';
                        if($cto != '' || $cto != null)
                        {
                                    // $cto = 'legal@instappy.com';
                                //  $cto = 'hemant@pulpstrategy.com';
                                    $cformemail = 'noreply@instappy.com';
                                    $key = 'f894535ddf80bb745fc15e47e42a595e';
                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                        CURLOPT_RETURNTRANSFER => 1,
                                        CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                                        CURLOPT_POST => 1,
                                        CURLOPT_POSTFIELDS => array(
                                            'api_key' => $key,
                                            'subject' => 'Instappy - Data Uses',
                                            'fromname' => 'Instappy',
                                            'from' => $cformemail,
                                            'content' => 'Data uses reached at'.$gb.' GB',
                                            'recipients' => $cto
                                        )
                                    ));
                                    $customerhead = curl_exec($curl);
                                    curl_close($curl);
                        }                           
                            }
                            
                      //end mail
                      $sizeCal =  $size/1048576;
                     $setFloatPoint =  number_format($sizeCal,2);
                      $finalSize =  $setFloatPoint." MB";

                       $responce = array();
                    $response = array("result" => 'success', "msg" => 'App Size is '.$finalSize);
                                        $Basearray = array("response" => $response);
                                        $basejson = json_encode($Basearray);
                                        echo $basejson;
                                        die;
                    
            }else{

                die("There is no App");
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
