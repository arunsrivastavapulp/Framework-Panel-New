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
$app->post('/saveData', 'saveData');
$app->post('/DeleteData', 'DeleteData');

function saveData() {
    $dbCon = content_db();
    $app_id = $_POST['app_id'];
    $author = $_POST['autherId'];
    $LinkTo = $_POST['linkTo'];
    $layoutType = $_POST['layoutType'];
    $title = $_POST['title'];
    $keywords = $_POST['keywords'];
    $banner_html = $_POST['banner_html'];
    $navigation_html = $_POST['navigation_html'];
    $htmlvalues = $_POST['html'];
    $screen_id = $_POST['screen_id'];
    $originalIndex = $_POST['originalIndex'];
    $ico_icon = json_decode($_POST['ico_icon']);
	$contactEmail =$_POST['contactEmail'];
	$contactPhone =$_POST['contactPhone'];
    $valuesOfHtml = '';
    $checkNull = '';

    $timeQuery = "update app_data set html_updatetime=now(),contactus_email='".$contactEmail."',contactus_phone='".$contactPhone."' where `id`='" . $app_id . "'";
    $timeQueryData = $dbCon->prepare($timeQuery);
    $timeQueryData->execute();

    $app_query = "select * from app_screenmapping_html where app_id='" . $app_id . "' and screen_sequence='" . $screen_id . "'";
    $appQueryRun = $dbCon->query($app_query);
    $rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $calRow = $appQueryRun->rowCount();


    //$errorSceck= strcmp("undefined<nav>undefined</nav>","$navigation_html");
    $checkNull = str_replace(" ", "", $htmlvalues);
    $pos = strpos($navigation_html, 'undefined<nav>');
    $pos1 = strpos($navigation_html, 'undefined</nav>');
    if ($pos === false || $pos1 === false) {

//	if($errorSceck != 0)
//	{

        if (!empty($ico_icon)) {
            foreach ($ico_icon as $iconset) {
                if ($iconset->icon != '' || $iconset->icon != NULL) {
                    $screenIndex = '';

                    if (isset($iconset->originalIndex)) {
                        $screenIndex = $iconset->originalIndex;
                    } else {
                        $screenIndex = $iconset->index;
                    }
                    $sqlUserInsertIcon = "update app_screenmapping_html set ico_icon='" . $iconset->icon . "' where `app_id`='" . $app_id . "' and `screen_sequence`='" . $screenIndex . "'";
                    $statementUserInsertIcon = $dbCon->prepare($sqlUserInsertIcon);
                    $statementUserInsertIcon->execute();
                }
            }
        }
        if ($navigation_html != '') {
            $sqlUserInsertNav = "update app_screenmapping_html set navigation_html='" . addslashes($navigation_html) . "' where `app_id`='" . $app_id . "'";
            $statementUserInsertNav = $dbCon->prepare($sqlUserInsertNav);
            $statementUserInsertNav->execute();
        }


        if ($calRow == 0 && $screen_id != 0 && $checkNull != '') {
            $sqlUserInsert = "INSERT INTO `app_screenmapping_html` ( `app_id`, `screen_sequence`,`title`,`banner_html`, `navigation_html`,`content_html`, `autherId`, `linkto_screenid`,`layoutType`,`updated`,`PageIndex`,`keywords`)
													VALUES ( '" . $app_id . "', '" . $screen_id . "', '" . addslashes($title) . "','" . addslashes($banner_html) . "','" . addslashes($navigation_html) . "','" . addslashes($htmlvalues) . "', '" . $author . "', '" . $LinkTo . "','" . $layoutType . "',now(),'" . $originalIndex . "','" . $keywords . "')";
            $statementUserInsert = $dbCon->prepare($sqlUserInsert);
            $statementUserInsert->execute();
            $sqlUserInsert_ach = "INSERT INTO `app_screenmapping_html_ach` ( `app_id`, `screen_sequence`,`title`,`banner_html`, `navigation_html`,`content_html`, `autherId`, `linkto_screenid`,`layoutType`,`PageIndex`,`keywords`)
													VALUES ( '" . $app_id . "', '" . $screen_id . "', '" . addslashes($title) . "','" . addslashes($banner_html) . "','" . addslashes($navigation_html) . "','" . addslashes($htmlvalues) . "', '" . $author . "', '" . $LinkTo . "','" . $layoutType . "','" . $originalIndex . "','" . $keywords . "')";
            $statementUserInsert_ach = $dbCon->prepare($sqlUserInsert_ach);
            $statementUserInsert_ach->execute();
        } elseif ($checkNull != '') {
            $sqlUserInsert_ach = "update app_screenmapping_html_ach set title='" . addslashes($title) . "',`banner_html`='" . addslashes($banner_html) . "', `navigation_html`='" . addslashes($navigation_html) . "',`content_html`='" . addslashes($htmlvalues) . "',`linkto_screenid`='" . $LinkTo . "',`layoutType`='" . $layoutType . "',`PageIndex`='" . $originalIndex . "',`keywords`='" . addslashes($keywords) . "', `deleted`=0 where `app_id`='" . $app_id . "' and `screen_sequence`='" . $screen_id . "'";
            $statementUserInsert_ach = $dbCon->prepare($sqlUserInsert_ach);
            if ($statementUserInsert_ach->execute()) {
                $sqlUserInsert = "update app_screenmapping_html set title='" . addslashes($title) . "',`banner_html`='" . addslashes($banner_html) . "', `navigation_html`='" . addslashes($navigation_html) . "',`content_html`='" . addslashes($htmlvalues) . "',`linkto_screenid`='" . $LinkTo . "',`layoutType`='" . $layoutType . "',`updated`=now(),`PageIndex`='" . $originalIndex . "',`keywords`='" . addslashes($keywords) . "', `deleted`=0 where `app_id`='" . $app_id . "' and `screen_sequence`='" . $screen_id . "'";
                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                $statementUserInsert->execute();
            }
        }
    } else {
        echo "undefined content";
    }
}

function DeleteData() {
    $dbCon = content_db();
    $app_id = $_POST['app_id'];
    $author = $_POST['autherId'];
    $screen_id = $_POST['screen_id'];

    $valuesOfHtml = '';
    $app_query = "select * from app_screenmapping_html where app_id='" . $app_id . "' and screen_sequence='" . $screen_id . "'";
    $appQueryRun = $dbCon->query($app_query);
    $rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $calRow = $appQueryRun->rowCount();
    if ($calRow == 0) {
        echo "no such screen present";
    } else {
        $sqlUserInsert_ach = "update app_screenmapping_html_ach set `deleted`='1' where `app_id`='" . $app_id . "' and `screen_sequence`='" . $screen_id . "'";
        $statementUserInsert_ach = $dbCon->prepare($sqlUserInsert_ach);
        $statementUserInsert_ach->execute();

        $sqlUserInsert = "update app_screenmapping_html set `deleted`='1' where `app_id`='" . $app_id . "' and `screen_sequence`='" . $screen_id . "'";
        $statementUserInsert = $dbCon->prepare($sqlUserInsert);
        $statementUserInsert->execute();
    }
}

$app->run();
