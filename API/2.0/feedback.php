<?php

require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();

$app->post('/feedback', 'feedback'); // Using Post HTTP Method and process searchProducts function
$app->run();

/*
 * send feedback to app author
 * Added By Arun Srivastava on 23/9/15
 */

function feedback() {
    $cf = new Fwcore();
    $data = array();
    $data['auth_token'] = $_POST['auth_token'];
    $data['app_id'] = $_POST['app_id'];
    $data['device_id'] = $_POST['device_id'];
    $data['full_name'] = $_POST['full_name'];
    $data['title'] = $_POST['title'];
    $data['message'] = $_POST['message'];

    $cf->feedback($data);
}
