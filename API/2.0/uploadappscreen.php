<?php

require 'Slim/Slim.php';

require_once('common_functions_content.php');

\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();

$app->post('/uploadandroidpublish', 'upload_android_publish'); // Using Post HTTP Method and process customer login function
$app->post('/uploadiospublish', 'upload_ios_publish'); // Using Post HTTP Method and process customer login function
$app->run();

/* 
 * Upload app screen from publish android screen
 * Added By Varun Srivastava on 18/8/15
 */

function upload_android_publish()
{
	$cf = new Fwcore();
	$cf->uploadAndroidPublish();
}

/* 
 * Upload app screen from publish IOS screen
 * Added By Varun Srivastava on 23/8/15
 */

function upload_ios_publish()
{
	$cf = new Fwcore();
	$cf->uploadIOSPublish();
} 