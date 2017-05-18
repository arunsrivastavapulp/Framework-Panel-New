<?php
require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/getcurrency', 'get_currency'); // Using Post HTTP Method and process customer register function
$app->run();

/* 
 * get currency
 * Added By Arun Srivastava on 8/10/15
 */ 
function get_currency()
{
	global $app;
    
	$cf = new Fwcore();
	$cf->getcurrency();
	
}

