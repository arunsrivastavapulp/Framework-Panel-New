<?php

require 'Slim/Slim.php';
require 'includes/db.php';
require_once('common_functions.php');
require_once('S3.php');


\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();
$app->post('/profiledetail', 'profiledetail');
$app->post('/updateprofiledetail', 'updateprofiledetail');
$app->post('/uploadimage', 'uploadimage');

function authCheck($authToken, $device_id) {
    $dbCon = content_db();
    //  $auth_query = "select id from user_appdata where user_name=$device_id and auth_token='" . $authToken . "'";
    $auth_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token=:authtoken and u.device_id=:deviceid";
    $auth_queryExecution = $dbCon->prepare($auth_query);
    $auth_queryExecution->bindParam(':authtoken', $authToken, PDO::PARAM_STR);
    $auth_queryExecution->bindParam(':deviceid', $device_id, PDO::PARAM_STR);
    $auth_queryExecution->execute();
    $result_auth = $auth_queryExecution->rowCount(PDO::FETCH_NUM);
    return $result_auth;
}

function profiledetail() {
    if (isset($_POST['email_id']) && isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id'])) {
        $data               = array();
	$data['auth_token'] = isset($_POST['authToken']) ? $_POST['authToken'] : '';
	$data['app_id']     = isset($_POST['app_id']) ? $_POST['app_id'] : '';
	$data['email']      = isset($_POST['email_id']) ? $_POST['email_id'] : '';
	$data['device_id']  = isset($_POST['device_id']) ? $_POST['device_id'] : '';
        $email_id = $_POST['email_id'];
        $app_id = $_POST['app_id'];
        $dbCon = content_db();
        $appQueryData = "select *  from app_data where app_id=:appid";
        $app_userData = $dbCon->prepare($appQueryData);
        $app_userData->bindParam(':appid', $app_id, PDO::PARAM_STR);
        $app_userData->execute();
        $result_userData = $app_userData->fetch(PDO::FETCH_OBJ);
        $app_id = $result_userData->id;
        $app_idRetail = $result_userData->id;
        if($result_userData->jump_to == 1 && $result_userData->jump_to_app_id != '')
        {
            if($result_userData->jump_to_app_id != 0)
            { 
          $app_idRetail=  $result_userData->jump_to_app_id;
            }
        }
       
        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id'];
    } else {
        $responce = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
    $authResult = authCheck($authToken, $device_id);

    if ($authResult == 0 || $device_id == '') {
        $response = array("result" => 'error', "msg" => 'Authentication Error');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
    } else {
        $cartdatacount = 0;
        $orderCount = 0;
        $mf = new mainFunctions();
        $cf = new Fwcore();

        $customerlogin = $mf->insideCustomerLogin($email_id, '', $app_idRetail);
        if (!empty($customerlogin)) {
            $customer_id = $customerlogin['customer_id'];
            $cartdata = $cf->getCartData($customerlogin['customer_id'], $app_idRetail);

            if (!empty($cartdata)) {
                $cartdatacount = count($cartdata['cartdata']);
            }
        }
        $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE email='" . $email_id . "' AND app_id='" . $app_id . "'";
        $customer__data = $mf->queryRun($customerquery, 'select');

        if (!empty($customer__data)) {
       
            $orderDetail = $cf->getMyOrders($data,1);
           
         
            $orderCount = count($orderDetail);
        }   
        $appQueryData = "select *  from user_appdata where app_id=:appid and email_address=:emailid";
        $app_profileData = $dbCon->prepare($appQueryData);
        $app_profileData->bindParam(':emailid', $email_id, PDO::PARAM_STR);
        $app_profileData->bindParam(':appid', $app_id, PDO::PARAM_STR);
        $app_profileData->execute();
        $result_profileData = $app_profileData->fetch(PDO::FETCH_OBJ);
        $rowCount = $app_profileData->rowCount(PDO::FETCH_NUM);
        if ($rowCount > 0) {
            $profileimg=$result_profileData->profile_image;
            $coverimg=$result_profileData->cover_image;
            $address=$result_profileData->address;
            if($profileimg == '(null)')
            {
               $profileimg=""; 
            }
            if($coverimg == '(null)')
            {
               $coverimg=""; 
            }
            if($address == '(null)')
            {
               $address=""; 
            }
            $about = array('gender' => $result_profileData->gender, 'email' => $result_profileData->email_address, 'phone' => $result_profileData->phone);
            $profile = array("first_name" => $result_profileData->firstname, 'last_name' => $result_profileData->lastname, 'about' => $about, "address" => $address);
            $data = array("cartcount" => $cartdatacount, "ordercount" => $orderCount, "cover_image" => $coverimg, "profile_image" => $profileimg, "personal_details" => $profile);
            $basejson = json_encode($data);
            $responce = array("result" => 'success', "msg" => 'Profile Details');
            echo json_encode(array("response" => $responce, "profile" => $data));

        } else {
            $response = array("result" => 'error', "msg" => 'No such user found');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
        }
    }
}

function updateprofiledetail() {
    if (isset($_POST['email_id']) && isset($_POST['app_id']) && isset($_POST['authToken']) && isset($_POST['device_id'])) {
        $email_id = $_POST['email_id'];
        $app_id = $_POST['app_id'];
        $dbCon = content_db();
		
		 $dbCon2 = retail_db();

        $appQueryData = "select *  from app_data where app_id=:appid";
        $app_userData = $dbCon->prepare($appQueryData);
        $app_userData->bindParam(':appid', $app_id, PDO::PARAM_STR);
        $app_userData->execute();
        $result_userData = $app_userData->fetch(PDO::FETCH_OBJ);
        $app_id = $result_userData->id;

        $authToken = $_POST['authToken'];
        $device_id = $_POST['device_id'];
        if (isset($_POST['gender'])) {
            $gender = $_POST['gender'];
        } else {
            $gender = "";
        }
        if (isset($_POST['phone'])) {
            $phone = $_POST['phone'];
        } else {
            $phone = "";
        }
        if (isset($_POST['address'])) {
            $address = $_POST['address'] == '(null)' ? "" : $_POST['address'];
        } else {
            $address = "";
        }
        if (isset($_POST['firstname'])) {
            $firstname = $_POST['firstname'];
        } else {
            $firstname = "";
        }
        if (isset($_POST['lastname'])) {
            $lastname = $_POST['lastname'];
        } else {
            $lastname = "";
        }
        if (isset($_POST['cover_image'])) {
            $cover_image = $_POST['cover_image'];
        } else {
            $cover_image = "";
        }
        if (isset($_POST['profile_image'])) {
            $profile_image = $_POST['profile_image'];
        } else {
            $profile_image = "";
        }
    } else {
        $responce = array();
        $response = array("result" => 'error', "msg" => 'parameter missing or wrong parameter');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
        die;
    }
    $authResult = authCheck($authToken, $device_id);

    if ($authResult == 0 || $device_id == '') {
        $response = array("result" => 'error', "msg" => '0');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
    } else {
        $appQueryData = "select *  from user_appdata where app_id=:appid and email_address=:emailid";
        $app_userData = $dbCon->prepare($appQueryData);
        $app_userData->bindParam(':emailid', $email_id, PDO::PARAM_STR);
        $app_userData->bindParam(':appid', $app_id, PDO::PARAM_STR);
        $app_userData->execute();
        $result_userData = $app_userData->fetch(PDO::FETCH_OBJ);
        $rowCount = $app_userData->rowCount(PDO::FETCH_NUM);
        if ($rowCount == 0) {

            $appQueryData = "select *  from user_appdata where app_id=:appid and user_device_id=:device_id";
            $app_userData2 = $dbCon->prepare($appQueryData);
            $app_userData2->bindParam(':device_id', $device_id, PDO::PARAM_STR);
            $app_userData2->bindParam(':appid', $app_id, PDO::PARAM_STR);
            $app_userData2->execute();
            $result_userData2 = $app_userData2->fetch(PDO::FETCH_OBJ);
            $rowCount = $app_userData2->rowCount(PDO::FETCH_NUM);
            if ($rowCount == 0) {

                $appQueryData = "select *  from users where  device_id=:device_id";
                $app_userData3 = $dbCon->prepare($appQueryData);
                $app_userData3->bindParam(':device_id', $device_id, PDO::PARAM_STR);
                $app_userData3->execute();
                $rowCount = $app_userData3->rowCount(PDO::FETCH_NUM);
                $result_userData3 = $app_userData3->fetch(PDO::FETCH_OBJ);
                if ($rowCount == 0) {
                    $sqlUserInsert = "INSERT INTO users (user_name,device_id) VALUES ('" . $device_id . "','" . $device_id . "')";
                    $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                    $statementUserInsert->execute();
                    $user_id = $dbCon->lastInsertId();
                } else {
                    $user_id = $result_userData3->id;
                }
                $auth_token = bin2hex(openssl_random_pseudo_bytes(16));
                $sqlUserInsert = "INSERT INTO user_appdata (app_id,user_device_id,user_id,auth_token,firstname,lastname,email_address,phone,gender,address,cover_image,profile_image) VALUES ('" . $app_id . "','" . $device_id . "','" . $user_id . "','" . $auth_token . "','" . $firstname . "','" . $lastname . "','" . $email_id . "','" . $phone . "','" . $gender . "','" . $address . "','" . $cover_image . "','" . $profile_image . "')";
                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                $statementUserInsert->execute();
				
				$sqlUserInsert_oc = "INSERT INTO " . DB_PREFIX . "customer (firstname, email, telephone, gender, ip, user_id, app_id) VALUES('" . $firstname . "', '" . $email_id . "', '" . $phone . "', '" . $gender . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . $user_id . "', '" . $app_id . "')";
                $statementUserInsert_oc = $dbCon2->prepare($sqlUserInsert_oc);
                $statementUserInsert_oc->execute();
                
                $responce = array();
                $response = array("result" => 'success', "msg" => 'New Record inserted');
                $Basearray = array("response" => $response);
                $basejson = json_encode($Basearray);
                echo $basejson;
            } else {
                $sqlUserInsert = "update user_appdata set gender='" . $gender . "',phone='" . $phone . "',firstname='" . $firstname . "',lastname='" . $lastname . "',address='" . $address . "',cover_image='" . $cover_image . "',profile_image='" . $profile_image . "',email_address='" . $email_id . "' where user_device_id='" . $device_id . "' and app_id='" . $app_id . "'";
                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                $statementUserInsert->execute();
				
				$sqlUserInsert_oc = "UPDATE " . DB_PREFIX . "customer SET firstname = '" .$firstname. "',  telephone = '" . $phone . "', gender = '" . $gender . "' WHERE app_id = '" . $app_id . "' AND email = '" . $email_id. "'";
                $statementUserInsert_oc = $dbCon2->prepare($sqlUserInsert_oc);
                $statementUserInsert_oc->execute();
            
                $responce = array();
                $response = array("result" => 'success', "msg" => 'Profile Updated');
                $Basearray = array("response" => $response);
                $basejson = json_encode($Basearray);
                echo $basejson;
            }
        } else {
            $sqlUserInsert = "update user_appdata set gender='" . $gender . "',phone='" . $phone . "',firstname='" . $firstname . "',lastname='" . $lastname . "',address='" . $address . "',cover_image='" . $cover_image . "',profile_image='" . $profile_image . "' where email_address='" . $email_id . "' and app_id='" . $app_id . "'";
            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
            $statementUserInsert->execute();
			
			$sqlUserInsert_oc = "UPDATE " . DB_PREFIX . "customer SET firstname = '" .$firstname. "',  telephone = '" . $phone . "', gender = '" . $gender . "' WHERE app_id = '" . $app_id . "' AND email = '" . $email_id. "'";
                $statementUserInsert_oc = $dbCon2->prepare($sqlUserInsert_oc);
                $statementUserInsert_oc->execute();
            
            $responce = array();
            $response = array("result" => 'success', "msg" => 'Profile Updated');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
        }
    }
}

function uploadimage() {
    //AWS access info
    if (!defined('awsAccessKey'))
        define('awsAccessKey', 'AKIAIFSCRQOFUXP4HKCA');
    if (!defined('awsSecretKey'))
        define('awsSecretKey', '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');

//instantiate the class
    $s3 = new S3(awsAccessKey, awsSecretKey);
    $dbCon = content_db();

    $appQueryData = "select *  from app_data where app_id=:appid";
    $app_userData = $dbCon->prepare($appQueryData);
    $app_userData->bindParam(':appid', $_POST['app_id'], PDO::PARAM_STR);
    $app_userData->execute();
    $result_userData = $app_userData->fetch(PDO::FETCH_OBJ);
    $app_id = $result_userData->id;


    $img = $_POST['image'];
    $oldUrl = "";
    if (strpos($img, 'data:image/png') !== false) {
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
    } elseif ((strpos($img, 'data:image/jpg') !== false) || (strpos($img, 'data:image/jpeg') !== false)) {
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
    } elseif (strpos($img, 'data:image/bmp') !== false) {
        $img = str_replace('data:image/bmp;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
    }
    //session_start();
    $length = 10;
    $randomString = substr(str_shuffle(md5(time())), 0, $length);
    if (!file_exists('/var/www/html/panelimage/' . $app_id)) {
        mkdir('/var/www/html/panelimage/' . $app_id, 0777, true);
    }
    $directory = '/var/www/html/panelimage/' . $app_id . '/' . $randomString . '__' . date('d_m_Y_H_i_s') . '.' . "jpeg";

    $entry = base64_decode($img);
    $image = imagecreatefromstring($entry);
    header('Content-type:image/jpeg');
    imagejpeg($image, $directory);
    imagedestroy($image);
    $fileName = $app_id . '/' . $randomString . '__' . date('d_m_Y_H_i_s') . '.' . "jpeg";
    $fileTempName = $directory;

    if ($s3->putObjectFile($fileTempName, "instappy", $fileName, S3::ACL_PUBLIC_READ)) {
        list($width, $height) = getimagesize($directory);

        $image = array('imageurl' => 'https://s3-us-west-2.amazonaws.com/instappy/' . $fileName, 'height' => $height, 'width' => $width);
        $responce = array("result" => 'success', "msg" => 'Image Uploaded');
        echo json_encode(array("response" => $responce, "image" => $image));

        unlink($directory);
    } else {
        //echo "Something went wrong while uploading your file... sorry.";
        $image = array('imageurl' => '', 'height' => '', 'width' => '');
        $responce = array("result" => 'success', "msg" => 'Unable to upload image');
        echo json_encode(array("response" => $responce, "image" => $image));
    }
}

$app->run();
