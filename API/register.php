<?php

require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();

$app->post('/register', 'user_registration'); // Using Post HTTP Method and process register function
$app->post('/fb-register', 'user_fb_registration'); // Using Post HTTP Method and process register function
$app->post('/gplus-register', 'user_gplus_registration'); // Using Post HTTP Method and process register function
$app->run();


/*
 * results app data w.r.t auth token to api
 * Added By Arun Srivastava on 15/6/15
 */

function user_registration() {
    global $app;
    $req = $app->request();
    $cf = new Fwcore();

    $userdata = array();

    $userdata['email_address'] = $req->post('email');
    $userdata['author_product'] = $req->post('author_product');
    $userdata['author_product_category'] = $req->post('author_product_category');
    $userdata['company_name'] = $req->post('company_name');
    $userdata['first_name'] = $req->post('first_name');
    $userdata['last_name'] = $req->post('last_name');
    $userdata['mobile_number'] = $req->post('mobile_number');
    $userdata['password'] = $req->post('password');
    $userdata['source'] = $req->post('source');
    $userdata['verification_code'] = $req->post('verification_code');

    try {
        if ((trim($userdata['email_address']) != '') && (trim($userdata['password']) != '')) {
            $checkEmail = $cf->email_check($userdata);
            if ($checkEmail > 0) {
                $json = $cf->real_json_encode('', 'error','Email id is already register','415');
                echo $json;
            } else {
                $cf->newUserRegister($userdata);
            }
        } else {
            $json = $cf->real_json_encode('', 'error','Parameter empty','405');
            echo $json;
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
function user_fb_registration() {
	global $app;
	$req = $app->request();
	$cf = new Fwcore();
	$userdata = array();
	$userdata['email_address'] = $req->post('email');
	$userdata['password'] = $req->post('password');
	$userdata['fb_token'] = $req->post('fb_token');
	$userdata['first_name'] = $req->post('first_name');
	$userdata['avatar'] = $req->post('avatar');
	$userdata['last_name'] = $req->post('last_name');
	$userdata['source'] = $req->post('source');
	$userdata['fbid'] = $req->post('fbid');
	$userdata['verification_code'] = $req->post('verification_code');
	$userdata['base_url'] = $req->post('base_url');
	    try {
        if ((trim($userdata['fbid']) != '') && (trim($userdata['password']) != '')) {
            $fb_check = $cf->fb_check($userdata);
            if ($fb_check > 0) {
				 $cf->updateuser_fb($userdata);              
            } else {
                $cf->newuserregister_fb($userdata);
            }
        } else {
            $json = $cf->real_json_encode('', 'error','Parameter empty','405');
            echo $json;
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
	

   
}
function user_gplus_registration() {
	global $app;
	$req = $app->request();
	$cf = new Fwcore();
	$userdata = array();
	$userdata['email_address'] = $req->post('email');
	$userdata['password'] = $req->post('password');
	$userdata['gPlus_token'] = $req->post('gPlus_token');
	$userdata['first_name'] = $req->post('first_name');
	$userdata['last_name'] = $req->post('last_name');
	$userdata['avatar'] = $req->post('avatar');
	$userdata['fbid'] = $req->post('fbid');
	$userdata['verification_code'] = $req->post('verification_code');
	    try {
        if ((trim($userdata['email_address']) != '') && (trim($userdata['password']) != '')) {
            $checkEmail = $cf->email_check($userdata);
            if ($checkEmail > 0) {
				 $cf->updateuser_gplus($userdata);              
            } else {
                $cf->newuserregister_gplus($userdata);
            }
        } else {
            $json = $cf->real_json_encode('', 'error','Parameter empty','405');
            echo $json;
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
	

   
}
