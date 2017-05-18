<?php
session_start();

if(isset($_SESSION['username']))
{
	require_once('modules/login/login-check.php');
	require_once('modules/savepackage/savepackage.php');
	$login       = new Login();
	$results     = $login->check_user_exist($_SESSION['username'],$_SESSION['custid']);	
	$currentuser = $results['id'];
	
	$package     = new Package();
	echo $package->savePackage($currentuser, $_POST['packagetype']);
}
else
{
	echo "Please login first"; 
}