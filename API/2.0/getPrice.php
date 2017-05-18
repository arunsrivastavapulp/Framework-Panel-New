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
	require 'includes/db.php';
	\Slim\Slim::registerAutoloader();
	
	$app = new \Slim\Slim();
	$app->post('/getPrice', 'getPrice');
	$app->post('/getDiscount', 'getDiscount');
	
	function getdays($years){
		$totaldays = $years*365;
		return $totaldays;
	}
	
	function getPrice() {
		$dbCon = content_db();
		if (isset($_POST['timePeriod']) && isset($_POST['appAllowed']) && isset($_POST['packageType']) && isset($_POST['appid']) &&  isset($_POST['currency'])) {
			$timePeriod = $_POST['timePeriod'];
			$totaldays = getdays($timePeriod);
			$currencyid = $_POST['currency'];
			$cust_plan = $_POST['cust_plan'];
			$part_payment = $_POST['part_payment'];
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
			$jumpto=0;
			$jumptoapp=0;
			$query3 = 'select jump_to,jump_to_app_id,type_app from app_data where id="' . $appid . '" limit 1';      
			$appQueryRun3 = $dbCon->query($query3);
			$rowFetch3 = $appQueryRun3->fetch(PDO::FETCH_ASSOC);
			$appType = $rowFetch3['type_app'];        
			$jumpto = $rowFetch3['jump_to'];
			$jumptoapp = $rowFetch3['jump_to_app_id'];
			
if($jumpto == 1 && $jumptoapp != 0)
						{
						$query4 = 'select * from app_data where id=:appid limit 1';
						$appQueryRun4 = $dbCon->prepare($query4);
						$appQueryRun4->bindParam(':appid',$jumptoapp,PDO::PARAM_INT);              
						$appQueryRun4->execute();
						 $rowFetch4 = $appQueryRun4->fetch(PDO::FETCH_ASSOC);						
						 $appType=$rowFetch4['type_app'];
						}		
			} else {
			$timePeriod = '';
			$appAllowed = '';
			$packageType = '';
		}
		if($cust_plan==1){
			$app_query = "select * from custom_plans where apps_allowed='" . $appAllowed . "' and plan_type='" . $packageType . "' and validity_in_days='" . $totaldays . "' and app_type='" . $appType . "' and currency_type_id='" . $currencyid . "' limit 1";
		}
		else{
			$app_query = "select * from  plans  where apps_allowed='" . $appAllowed . "' and plan_type='" . $packageType . "' and validity_in_days='" . $totaldays . "' and app_type='" . $appType . "' and currency_type_id='" . $currencyid . "' limit 1";	
			
		}
		
		$appQueryRun = $dbCon->query($app_query);
		$rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
		$main_query="select ap.crm_pricing_id from master_payment as mp join author_payment as ap on mp.id=master_payment_id where ap.app_id=$appid and ap.plan_id IS NOT NULL and ap.plan_id<>'' order by ap.id desc limit 1";
		$main_query_run = $dbCon->query($main_query);
		$main_master = $main_query_run->fetch(PDO::FETCH_ASSOC);
		$crm_pricing_id=$main_master['crm_pricing_id'];
		if($crm_pricing_id>0){
		$crm_pricing_query="select discount_perc from crm_appdata_pricing where id=$crm_pricing_id";
		$crm_query_run = $dbCon->query($crm_pricing_query);
		$crm_details = $crm_query_run->fetch(PDO::FETCH_ASSOC);
		$distcount=$crm_details['discount_perc'];
		$rowFetch[0]['actual_price']=$rowFetch[0]['actual_price']-$rowFetch[0]['actual_price']*$distcount/100;
		}

		

		if($part_payment==1){
			$sql_part="select * from master_payment_part where app_id='$appid' limit 1";
			$appQueryRun_part = $dbCon->query($sql_part);
		$rowFetch_part = $appQueryRun_part->fetch(PDO::FETCH_ASSOC);
		 $rowFetch[0]['actual_price']=	$rowFetch_part['part_amount'];
			}
			
		$myvalues = json_encode($rowFetch);
		
		
		//    if (isset($_SESSION['totalPrice'])) {
		//        unset($_SESSION['totalPrice']);
		//    }
		//    $_SESSION['totalPrice'] = $rowFetch[0]['amount_intransaction'];
		echo $myvalues;
	}
	
	function getDiscount() {
		$dbCon = content_db();
		$promocode = $_POST['promo'];
		$app_query = "select * from promocodes where promocode_value='" . $promocode . "' limit 1";
		$appQueryRun = $dbCon->query($app_query);
		$rowFetch = $appQueryRun->fetchAll(PDO::FETCH_ASSOC);
		$myvalues = json_encode($rowFetch);
		echo $myvalues;
	}
	$app->run();
