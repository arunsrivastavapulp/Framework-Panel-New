<?php

$currentTimeout = ini_get('session.gc_maxlifetime');

// Change session timeout value for a particular page load  - 1 month = ~2678400 seconds
ini_set('session.gc_maxlifetime', 2678400);
session_start();

if (isset($_POST['username'])) {
    require_once('modules/login/login-check.php');
    $login = new Login();
    if ($_POST['type'] == 'login')
        $login->check_login($_POST);
    if ($_POST['type'] == 'register') {
       
        $login->signuprequest($_POST);
    } 
 
}

?>