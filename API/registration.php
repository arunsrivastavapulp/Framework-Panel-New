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
require 'db.php';
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
$app->post('/register', 'getRegister');
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

function getRegister() {
$dbCon = getConnection();
if(isset($_POST['app_id']) && isset($_POST['device_id']) && isset($_POST['Framework_Version']) && isset($_POST['platform']))
{
  $password = "password";
    $app_idString = $_POST['app_id'];
    $device_id = $_POST['device_id'];
    $Framework_Version = $_POST['Framework_Version'];
    $plateform = $_POST['platform'];  
}
else
{
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
			
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    // $e = new MCrypt();
    // $encrpt_token = $e->encrypt($token, $password);
    $encrpt_token = $token;
    //   $decrypt_device_id = new MCrypt();
    //  $decrypt_uid = $decrypt_device_id->decrypt($device_id, $password);
 try {
    $app_query = "select u.id,u.device_id , u.user_name from users u where u.device_id='" . $device_id . "'";
 
        if ($device_id != '' && $Framework_Version != '' && $plateform != '' && $app_idString != '') {
            $lastId = '';
            $appQueryRun = $dbCon->query($app_query);
            $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
            $calRow = $appQueryRun->rowCount();
            if ($encrpt_token == NULL) {
                $encrpt_token = 'dfsf133rfggdggd12';
            }
            if ($calRow == 0) {
                $sqlUserInsert = "INSERT INTO users (`user_name`,`email_address`,`first_name`,`last_name`,`country`,`state`,`city`,`device_id`) VALUES ('" . $device_id . "','','','','','','','" . $device_id . "')";
                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                $statementUserInsert->execute();
                $lastId = $dbCon->lastInsertId();				

                $sqlUserDetails = "INSERT INTO user_appdata (`user_id`,`app_id`,`platform`,`latest_version`,`advt_data`,`auth_token`) VALUES ('" . $lastId . "','" . $app_id . "','" . $plateform . "','" . $Framework_Version . "','','" . $encrpt_token . "')";
                $UserDetailsInsert = $dbCon->prepare($sqlUserDetails);
                $UserDetailsInsert->execute();

				$response = array("result" => 'success', "msg" => '');
				$Basearray = array("response" => $response,"register"=>array("success"=>'200',"device_id"=>"".$device_id."","EncryptoAuth"=>"".$encrpt_token."" ));		
				$basejson = json_encode($Basearray);
				echo $basejson; 

            } 
			else
			{
 $app_CheckUser = "select * from user_appdata where user_id='" . $rowFetch['id'] . "' and app_id='".$app_id."'";
$appCheckUser = $dbCon->query($app_CheckUser);
            $rowFetchApp = $appCheckUser->fetch(PDO::FETCH_ASSOC);
            $FetchApp = $appCheckUser->rowCount();
			if($FetchApp == 0)
			{
				$sqlUserDetails = "INSERT INTO user_appdata (`user_id`,`app_id`,`platform`,`latest_version`,`advt_data`,`auth_token`) VALUES ('" . $rowFetch['id']. "','" . $app_id . "','" . $plateform . "','" . $Framework_Version . "','','" . $encrpt_token . "')";
				$UserDetailsInsert = $dbCon->prepare($sqlUserDetails);
				$UserDetailsInsert->execute();
				
				$response = array("result" => 'success', "msg" => '');
					$Basearray = array("response" => $response,"register"=>array("success"=>'200',"device_id"=>"".$rowFetch['device_id']."","EncryptoAuth"=>"".$encrpt_token."" ));		
					$basejson = json_encode($Basearray);
					echo $basejson; 
				
			}
			else
			{
					/* $sqlUserInsert = "update user_appdata set platform= '".$plateform ."' where user_id='".$rowFetch['id']."'"; 
					$statementUserInsert = $dbCon->prepare($sqlUserInsert);
					$statementUserInsert->execute(); */
						$response = array("result" => 'success', "msg" => '');
					$Basearray = array("response" => $response,"register"=>array("success"=>'200',"device_id"=>"".$rowFetch['device_id']."","EncryptoAuth"=>"".$rowFetchApp['auth_token']."" ));		
					$basejson = json_encode($Basearray);
					echo $basejson; 
					
			}		
					

					
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
