<?php

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/myads', 'myads');


function myads() {
    $dbCon = getConnection();
    $app_id = $_POST['app_id'];
    $customer = $_POST['customer'];
    $adserverCode = $_POST['adserverCode'];
    $checked = $_POST['checked'];
    $AndroidCode = $_POST['AndroidCode']; 
   $app_query = "select * from app_data where id='" . $app_id . "' and author_id IN (select id from author where custid= '".$customer."')";    
  $appQueryRun = $dbCon->query($app_query);
    $rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $calRow = $appQueryRun->rowCount();	
	if($checked == '1')
	{
		$sqlUserdeleted = "update app_adserver_details set `deleted`='1'  where `app_id`='" .  $app_id . "'";
					$statementUserdeleted = $dbCon->prepare($sqlUserdeleted);
					$statementUserdeleted->execute();
	
	}
    if ($calRow > 0) {	
				 	$appadserver = "select * from app_adserver_details where app_id='" . $app_id . "'";    
					$appQueryadserver = $dbCon->query($appadserver);
					$rowFetchServer = $appQueryadserver->fetch(PDO::FETCH_ASSOC);
					$calServer = $appQueryadserver->rowCount();	
					if($calServer > 0)
					{
					$sqlUserInsert = "update adserver_details set `ad_serverid`='".$adserverCode."' ,published_id='123',`unit_id`='" . $AndroidCode . "' where `id`='" . $rowFetchServer['adserver_details_id'] . "'";
					$statementUserInsert = $dbCon->prepare($sqlUserInsert);
					$statementUserInsert->execute();
					echo "updated adserver_details successfully";
					}	
					else
					{	
					$sqlUserInsert = "INSERT INTO adserver_details ( `ad_serverid`, `server_type`,`published_id`,`unit_id`)
					VALUES ( '" . $adserverCode . "', '1', '123','" . $AndroidCode . "')";
					$statementUserInsert = $dbCon->prepare($sqlUserInsert);
					$statementUserInsert->execute();
				
					$lastid = $dbCon->lastInsertId();
					
					$sqlUserInsert_ach = "INSERT INTO `app_adserver_details` ( `app_id`, `adserver_details_id`)
					VALUES ( '" . $app_id . "', '" . $lastid . "')";
					$statementUserInsert_ach = $dbCon->prepare($sqlUserInsert_ach);
					$statementUserInsert_ach->execute();
					echo "inserted adserver_details successfully";
					}
}
else
{
echo "no such data";
}		 
		 
		 

}


$app->run();
