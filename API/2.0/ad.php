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
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/myads', 'myads');

function myads() {
    $dbCon = content_db();
    $app_id = htmlentities($_POST['app_id']);
    $customer = htmlentities($_POST['customer']);
    $adserverCode = $_POST['adserverCode'];
    $checked = $_POST['checked'];
    $AndroidCode = $_POST['AndroidCode'];
    $app_query = "select * from app_data where id=:appid and author_id IN (select id from author where custid= :custid)";
    $appQueryRun = $dbCon->prepare($app_query);
    $appQueryRun->bindParam(":appid", $app_id, PDO::PARAM_INT);
    $appQueryRun->bindParam(":custid", $customer, PDO::PARAM_INT);
    $appQueryRun->execute();
    $rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $calRow = $appQueryRun->rowCount();
    if ($checked == '1') {
        $sqlUserdeleted = "update app_adserver_details set `deleted`='1'  where `app_id`=:appid";
        $statementUserdeleted = $dbCon->prepare($sqlUserdeleted);
        $statementUserdeleted->bindParam(":appid", $app_id, PDO::PARAM_INT);
        $statementUserdeleted->execute(); 
    }
    if ($calRow > 0) {
        $appadserver = "select * from app_adserver_details where app_id=:appid";
        $appQueryadserver = $dbCon->prepare($appadserver);
        $appQueryadserver->bindParam(":appid", $app_id, PDO::PARAM_INT);
        $appQueryadserver->execute();
        $rowFetchServer = $appQueryadserver->fetch(PDO::FETCH_ASSOC);
        $calServer = $appQueryadserver->rowCount();
        $conditionalID = $rowFetchServer['adserver_details_id'];
        if ($conditionalID != '1') {
            if ($calServer > 0) {
                $sqlUserInsert = "update adserver_details set `ad_serverid`=:adid ,published_id='123',`unit_id`=:unid where `id`=:id";
                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                $statementUserInsert->bindParam(":adid", $adserverCode, PDO::PARAM_STR);
                $statementUserInsert->bindParam(":unid", $AndroidCode, PDO::PARAM_STR);
                $statementUserInsert->bindParam(":id", $rowFetchServer['adserver_details_id'], PDO::PARAM_INT);
                $statementUserInsert->execute();
                echo "updated adserver_details successfully";
            } else {
                $sqlUserInsert = "INSERT INTO adserver_details ( `ad_serverid`, `server_type`,`published_id`,`unit_id`)
					VALUES ( :adid, '1', '123',:unid)";
                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
              $statementUserInsert->bindParam(":adid", $adserverCode, PDO::PARAM_STR);
                $statementUserInsert->bindParam(":unid", $AndroidCode, PDO::PARAM_STR);
                       $statementUserInsert->execute();

                $lastid = $dbCon->lastInsertId();

                $sqlUserInsert_ach = "INSERT INTO `app_adserver_details` ( `app_id`, `adserver_details_id`)
					VALUES ( :appid, :lastid)";
                $statementUserInsert_ach = $dbCon->prepare($sqlUserInsert_ach);
                $statementUserInsert_ach->bindParam(":appid", $app_id, PDO::PARAM_INT);
                $statementUserInsert_ach->bindParam(":lastid", $lastid, PDO::PARAM_INT);
          
                $statementUserInsert_ach->execute();
                echo "inserted adserver_details successfully";
            }
        }
    } else {
        echo "no such data";
    }
}

$app->run();
