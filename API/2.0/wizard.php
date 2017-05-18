<?php

require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();

$app->post('/wizardinit', 'wizardInit'); // Using Post HTTP Method and process app data function
$app->run();

/* 
 * app wizard api
 * Added By Arun Srivastava on 2/9/15
 */
function wizardInit()
{
	global $app;
    $req  = $app->request();
	$data = array();
	
	$data['email_id']  = $req->post('email_id') != '' ? $req->post('email_id') : '';
	$data['author_id'] = $req->post('author_id') != '' ? $req->post('author_id') : '';
	$data['app_name']  = $req->post('app_name') != '' ? strtolower($req->post('app_name')) : '';
	
	$cf = new Fwcore();
	$cf->wizardInit($data);  
}