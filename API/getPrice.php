<?php
session_start();
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
$app->post('/getPrice', 'getPrice');
$app->post('/getDiscount', 'getDiscount');

function getdays($years){
    $totaldays = $years*365;
    return $totaldays;
}

function getPrice() {
    $dbCon = getConnection();
    if (isset($_POST['timePeriod']) && isset($_POST['appAllowed']) && isset($_POST['packageType']) && isset($_POST['appid']) &&  isset($_POST['currency'])) {
        $timePeriod = $_POST['timePeriod'];
        $totaldays = getdays($timePeriod);
        $currencyid = $_POST['currency'];
        $appAllowedTxt = $_POST['appAllowed'];
        if($appAllowedTxt=="Android-iOS"){
                $appAllowed=2;
            } else if($appAllowedTxt=="iOS"){
                $appAllowed=1;
            } else if($appAllowedTxt=="Android"){
                $appAllowed=1;
            }
        $packageType = $_POST['packageType'];
        $appid = $_POST['appid'];        
        
        $query3 = 'select type_app from app_data where id="' . $appid . '" limit 1';      
        $appQueryRun3 = $dbCon->query($query3);
        $rowFetch3 = $appQueryRun3->fetch(PDO::FETCH_ASSOC);
        $appType = $rowFetch3['type_app'];        
        
    } else {
        $timePeriod = '';
        $appAllowed = '';
        $packageType = '';
    }

    $app_query = "select * from plans where apps_allowed='" . $appAllowed . "' and plan_type='" . $packageType . "' and validity_in_days='" . $totaldays . "' and app_type='" . $appType . "' and currency_type_id='" . $currencyid . "' limit 1";
    
    $appQueryRun = $dbCon->query($app_query);
    $rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $myvalues = json_encode($rowFetch);


//    if (isset($_SESSION['totalPrice'])) {
//        unset($_SESSION['totalPrice']);
//    }
//    $_SESSION['totalPrice'] = $rowFetch[0]['amount_intransaction'];
    echo $myvalues;
}

function getDiscount() {
    $dbCon = getConnection();
    $promocode = $_POST['promo'];
    $app_query = "select * from promocodes where promocode_value='" . $promocode . "' limit 1";
    $appQueryRun = $dbCon->query($app_query);
    $rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $myvalues = json_encode($rowFetch);
    echo $myvalues;
}
$app->run();
