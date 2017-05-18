<?php

require 'Slim/Slim.php';

require_once('common_functions_content.php');

\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();

$app->post('/getcomponents', 'getComponents'); // Using Post HTTP Method and process bulkImageUpload function
$app->run();

/* 
 * results retail component data to api
 * Added By Arun Srivastava on 21/3/16
 */
function getComponents()
{
	$cf = new Fwcore();
	$cf->getComponents();
}