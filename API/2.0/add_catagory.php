<?php
require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/addcatagory', 'add_catagory'); // Using Post HTTP Method and process customer register function
$app->run();

/* 
 * Register customer
 * Added By Varun Srivastava on 15/7/15
 */
function add_catagory()
{
	global $app;
    $req = $app->request();
	$data=array();
	$data['custid']=$req->post('custid');
	$data['sugest_cat']=$req->post('sugest_cat');
	$data['parent']=$req->post('parent');
	$data['type_select']=$req->post('type_select');
	$cf = new Fwcore();
	$cf->add_suggest_catagory($data);
}

