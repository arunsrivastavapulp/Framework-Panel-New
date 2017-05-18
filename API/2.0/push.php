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
$app->post('/push', 'getPush');
$password = "efdsdgr343tref";

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */
class MCrypt {

    private $iv = 'fedcba9876543210'; #Same as in JAVA
    private $key = '0123456789abcdef'; #Same as in JAVA

    function encrypt($str, $key) {
        $key = str_pad($key, 16);
        //$key = $this->hex2bin($key);
        $iv = $this->iv;

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $key, $iv);
        $encrypted = mcrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($encrypted);
    }

    function decrypt($code, $key) {
        //$key = $this->hex2bin($key);
        $key = str_pad($key, 16);
        $code = $this->hex2bin($code);
        $iv = $this->iv;
        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);
        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $code);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return utf8_encode(trim($decrypted));
    }

    protected function hex2bin($hexdata) {
        $bindata = '';
        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }

}

function getPush() {
  $dbCon = content_db(); 
    if (isset($_POST['app_id']) && isset($_POST['device_id']) && isset($_POST['pushToken']) && isset($_POST['authToken'])) {
        $password = "password";
        $app_idString = $_POST['app_id'];
        $device_id = $_POST['device_id'];
        $authToken = $_POST['authToken'];
        $pushToken = $_POST['pushToken'];
    } else {
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
				$appQueryData = "select *  from app_data where app_id='" . $app_idString . "'";
				$app_screenData = $dbCon->query($appQueryData);
				$result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);
				if($result_screenData != '')
			{
             $app_id = $result_screenData->id;
			}
			else{
			   $response = array("result" => 'error', "msg" => 'No Such App Present');
			$Basearray = array("response" => $response);		
			$basejson = json_encode($Basearray);
			echo $basejson; 
			die;
			}
    $app_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token='" . $authToken . "' and u.device_id='" . $device_id . "'";

    try {
        if ($device_id != '' && $app_id != '' && $pushToken != '') {
          
            $appQueryRun = $dbCon->query($app_query);
            $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
            $calRow = $appQueryRun->rowCount();

            if ($calRow > 0) {
                $sqlUserInsert = "UPDATE user_appdata ua JOIN users u ON ua.user_id=u.id SET ua.push_token='" . $pushToken . "' WHERE ua.app_id='" . $app_id . "' AND u.device_id='" . $device_id . "'";

                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                $statementUserInsert->execute();

                  $response = array("result" => 'success', "msg" => '200');
                $Basearray = array("response" => $response);
                $basejson = json_encode($Basearray);
                echo $basejson;
            } else {
                $response = array("result" => 'error', "msg" => 'no such user exist with these credentials');
                $Basearray = array("response" => $response);
                $basejson = json_encode($Basearray);
                echo $basejson;
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
