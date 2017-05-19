<?php
@ob_start();
error_reporting(0);
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/pricing/send-ack-invoice.php');
$ack_invoice = new SendAckInvoice();
if (isset($_SESSION['currencyidselect'])) {
    if ($_SESSION['currencyidselect'] != '') {
        $currency = $_SESSION['currencyidselect'];
        if ($currency == 1) {
            $currencyIcon = "Rs. ";
        } else {
            $currencyIcon = "$ ";
        }
    } else {
        $setcurrency = 1;
    }
} else {
    $setcurrency = 1;
}
if ($setcurrency != 0) {

    if (isset($_SESSION['currencyid'])) {
        $checkcountry = $_SESSION['country'];
        $currency = $_SESSION['currencyid'];
        $currencyIcon = $_SESSION['currency'];
    } else {
        $db->get_country();
        $checkcountry = $_SESSION['country'];
        $currency = $_SESSION['currencyid'];
        $currencyIcon = $_SESSION['currency'];
    }
    /* 	if ($checkcountry == "IN") {
      $currency = 1;
      $currencyIcon = "Rs.";
      } else {
      $currency = 2;
      $currencyIcon = "$";
      } */
}



require_once('paypal_sdk/sample/bootstrap.php');

use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

if ($currency == 2) {
    $custid = $_SESSION['custid'];

    if (isset($_GET['success']) && isset($_GET['paymentId']) && isset($_GET['PayerID'])) {

        if (isset($_GET['success']) && $_GET['success'] == 'true') {
            $tracking_id = $_SESSION['paymentId'];
            $payment = Payment::get($tracking_id, $apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($_GET['PayerID']);
            $oderid = '';
            $totalamount = '';
            $result = $payment->execute($execution, $apiContext);
            //        echo '<pre>';
            //	print_r($result);  // all parameters
            $paypalarray = (array) $result->transactions[0];

            if (!empty($paypalarray)) {
                foreach ($paypalarray as $key => $value) {
                    $amount = (array) $value['amount'];
                    $itemlist = (array) $value['item_list'];

                    foreach ($amount as $key4 => $value4) {
                        $totalamount = $value4['total'];
                    }
                    foreach ($itemlist as $key2 => $value2) {
                        $items = (array) $value2['items'][0];
                        foreach ($items as $key3 => $value3) {
                            $order_id = $value3['sku'];
                        }
                    }
                }
            }

            /* 	$order_id='1471351468';
              $amount=110.40;
              $totalamount=$amount;
              $itemlist=1;
              $tracking_id=$_GET['paymentId']; */
            require_once 'config/db.php';
            $db = new DB();
            $service_tax = $_SESSION['service_tax'];
            $mysqli = $db->dbconnection();

            $basicUrl = $db->siteurl();

            $sql6 = "SELECT * FROM master_payment WHERE payment_done!='1' AND order_id='$order_id' LIMIT 1";
            $stmt6 = $mysqli->prepare($sql6);
            $stmt6->execute();
            $results6 = $stmt6->fetch(PDO::FETCH_ASSOC);
            $promocode = $results6['promocodes_id'];
            $author_id = $results6['author_id'];
            $is_custom_promocode = $results6['is_custom_promocode'];
            $is_partpayment = $results6['is_partpayment'];
            if (is_array($results6)) {
                $count1 = count($results6);
            } else {
                $count1 = 0;
            }
            if ($count1 > 0) {

                $queryU = 'UPDATE master_payment set payment_gateway_type_id="2", payment_done="1", transaction_id="' . $tracking_id . '", transaction_date=NOW(),gateway_payment="' . $totalamount . '",currency_type_id="2" WHERE order_id="' . $order_id . '"';
                $stmtU = $mysqli->prepare($queryU);
                if ($is_custom_promocode == 1) {
                    $query2 = 'UPDATE custom_promocodes set is_consumed="1"  WHERE id="' . $promocode . '" ';
                } else {
                    $query2 = 'UPDATE promocodes set is_consumed="1"  WHERE id="' . $promocode . '" and author_id IN (select id from author where custid="' . $custid . '")';
                }

                $stmt2 = $mysqli->prepare($query2);
                $stmt2->execute();

                $appname = '';
                $count = 0;
//                $basetotal = 0;
                $currappbaseprice = 0;
                $order_items = '';
                $inclusiveTaxCheck='';
                if ($stmtU->execute()) {

                    $queryAP = 'UPDATE author_payment set payment_status="success", published_date=NOW(), payment_done="1" WHERE master_payment_id IN (SELECT id FROM master_payment WHERE order_id="' . $order_id . '")';
                    $stmtAP = $mysqli->prepare($queryAP);
                    if ($stmtAP->execute()) {


//                        $sql2 = "SELECT DISTINCT ap.app_id, ap.id,ap.plan_id FROM master_payment mp join author_payment ap on ap.master_payment_id=mp.id WHERE ap.payment_done='1' and mp.order_id='$order_id'";
//                        $stmt2 = $mysqli->prepare($sql2);
//                        $stmt2->execute();
//                        $results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                        $sqlforApp = "SELECT DISTINCT ap.app_id, ap.id,ap.plan_id FROM master_payment mp JOIN author_payment ap ON ap.master_payment_id=mp.id WHERE ap.payment_done='1' AND mp.order_id=$order_id AND ap.plan_id IS NOT NULL";
//                        $sqlforApp = "SELECT DISTINCT ap.app_id, ap.id,ap.plan_id,apd.payment_type_value FROM master_payment mp JOIN author_payment ap ON ap.master_payment_id=mp.id JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE ap.payment_done='1' AND mp.order_id='$order_id' AND ap.plan_id IS NOT NULL";
                        $stmt_APP = $mysqli->prepare($sqlforApp);
                        $stmt_APP->execute();
                        $results2 = $stmt_APP->fetchAll(PDO::FETCH_ASSOC);
                        $order_items_App='';
                        $basetotal_App=0;
                        $orderInvoiceHead ='<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0;border-left: 1px solid #a7a7a7;vertical-align: top;padding-top: 10px;padding-bottom: 10px;padding-left: 10px; "><p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Instappy Subscription Fee</p>
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Inclusions:</p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; "></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;"></td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; "></td>
</tr>';
                        if (!empty($results2)) {
                            $kk = 1;
                            foreach ($results2 as $key => $value) {
                                $sql5 = "SELECT ap.plan_id,ap.is_custom,pl.validity_in_days,ap.showMainPlan FROM author_payment ap join plans pl on pl.id=ap.plan_id WHERE ap.app_id='" . $value['app_id'] . "' AND ap.payment_done=1 AND ap.plan_id<>'' ORDER BY ap.id DESC LIMIT 1";

                                $stmt5 = $mysqli->prepare($sql5);
                                $stmt5->execute();
                                $results5 = $stmt5->fetch(PDO::FETCH_ASSOC);
                                $planID = $results5['plan_id'];
                                $plandays = $results5['validity_in_days'];
                                $showMainPlan = $results5['showMainPlan'];

                                if (function_exists('date_default_timezone_set')) {
                                    date_default_timezone_set('Asia/Calcutta');
                                    $currentdate = date('Y-m-d H:i:s');
                                }
                                $order_itemsAso='';
                                $order_itemsApp='';
                                $queryApp = 'SELECT * FROM app_data WHERE id="' . $value['app_id'] . '"';
                                $stmtApp = $mysqli->prepare($queryApp);
                                $stmtApp->execute();
                                $resultsApp = $stmtApp->fetch(PDO::FETCH_ASSOC);
                                $linkedAppId = $resultsApp['jump_to_app_id'];
                                $linkto = $resultsApp['jump_to'];
                                if($showMainPlan==1){
                                if (strtotime($resultsApp['plan_expiry_date']) > strtotime($currentdate)) {
                                    $planexpiredatedays = date('Y-m-d H:i:s', strtotime('+' . $plandays . ' days', strtotime($resultsApp['plan_expiry_date'])));
                                } else {
                                    $planexpiredatedays = date('Y-m-d H:i:s', strtotime('+' . $plandays . ' days', strtotime($currentdate)));
                                }
                                } else{
                                   $planexpiredatedays= $resultsApp['plan_expiry_date'];
                                }
                                if ($resultsApp['analytics_code'] == NULL || $resultsApp['analytics_code'] == '') {
                                    $sql4 = "SELECT * FROM app_analytics_mapping WHERE app_id IS NULL LIMIT 1";
                                    $stmt4 = $mysqli->prepare($sql4);
                                    $stmt4->execute();
                                    $results4 = $stmt4->fetch(PDO::FETCH_ASSOC);
                                    $analyticsid = $results4['id'];

                                    $queryAD = 'UPDATE app_data set plan_id="' . $planID . '",plan_expiry_date="' . $planexpiredatedays . '",published="1",analytics_code="' . $analyticsid . '" WHERE id="' . $value['app_id'] . '"';
                                    $stmtAD = $mysqli->prepare($queryAD);
                                    $stmtAD->execute();

                                    $queryUapp = 'UPDATE app_analytics_mapping set app_id="' . $value['app_id'] . '" WHERE id="' . $analyticsid . '"';
                                    $stmtUapp = $mysqli->prepare($queryUapp);
                                    $stmtUapp->execute();
                                } else {
                                    $queryAD = 'UPDATE app_data set plan_id="' . $planID . '",plan_expiry_date="' . $planexpiredatedays . '",published="1" WHERE id="' . $value['app_id'] . '"';
                                    $stmtAD = $mysqli->prepare($queryAD);
                                    $stmtAD->execute();
                                }
                                if ($linkedAppId != 0 && $linkto == 1) {
                                    $queryAD = 'UPDATE app_data set plan_id="' . $planID . '",plan_expiry_date="' . $planexpiredatedays . '",published="1" WHERE id="' . $linkedAppId . '"';
                                    $stmtAD = $mysqli->prepare($queryAD);
                                    $stmtAD->execute();
                                }

                                // part payment update;
                                if ($is_partpayment == 1 && $kk == 1) {
                                    $sql_part = "SELECT * FROM master_payment_part WHERE  master_payment_id='" . $results6['id'] . "' LIMIT 1";
                                    $stmt_part = $mysqli->prepare($sql_part);
                                    $stmt_part->execute();
                                    $results_part = $stmt_part->fetch(PDO::FETCH_ASSOC);

                                    //custom plane check 
                                    $sql_planc = "SELECT is_custom FROM author_payment WHERE  master_payment_id='" . $results6['id'] . "' and plan_id IS NOT NULL LIMIT 1";
                                    $stmt_planc = $mysqli->prepare($sql_planc);
                                    $stmt_planc->execute();
                                    $results_planc = $stmt_planc->fetch(PDO::FETCH_ASSOC);
                                    if ($results_planc['is_custom'] == 1) {
                                        $sql_cplan = "SELECT id,validity_in_days FROM custom_plans WHERE  id='" . $planID . "' LIMIT 1";
                                    } else {
                                        $sql_cplan = "SELECT id,validity_in_days FROM plans WHERE  id in (select plan_id from author_payment WHERE  id='" . $planID . "') LIMIT 1";
                                    }

                                    $stmt_cplan = $mysqli->prepare($sql_cplan);
                                    $stmt_cplan->execute();
                                    $results_cplan = $stmt_cplan->fetch(PDO::FETCH_ASSOC);

                                    //plane ckeck end
//                                    $amount_paid = round($results_part['part_amount'], 2) + round($results_part['part_amount'] * $service_tax / 100, 2);
                                    $amount_paid = round($results_part['part_amount'], 2);
                                    $queryAP = 'UPDATE master_payment_part set  payment_done=1 ,updated_at=now() ,transaction_id="' . $tracking_id . '",transaction_date=NOW(),part_paid_amount="' . $amount_paid . '" WHERE master_payment_id=' . $results6['id'] . ' AND app_id=' . $results_part['app_id'] . ' AND payment_done=0 LIMIT 1';
                                    $stmtAP = $mysqli->prepare($queryAP);
                                    $stmtAP->execute();
                                    $maser_part_id = $results_part['id'];
                                    $maser_app_id = $results_part['app_id'];
                                    $ack_invoice->sendinvoice_performa(array('id' => $maser_part_id, 'app_id' => $maser_app_id));
                                    if ($results_part['next_paymentdate'] != '') {
                                        //$exp_date=date('Y-m-d H:i:s', strtotime($results_part['next_paymentdate'].'+ 15 day'));
                                        $exp_date = date('Y-m-d H:i:s', strtotime($results_part['next_paymentdate']));
                                        $publish_date = date('Y-m-d H:i:s');
                                        $queryAP_exp = 'UPDATE app_data set  plan_expiry_date="' . $exp_date . '",plan_id="' . $results_cplan['id'] . '" ,publish_date="' . $publish_date . '" where id= ' . $results_part['app_id'] . ' LIMIT 1';
                                        $stmtAP_exp = $mysqli->prepare($queryAP_exp);
                                        $stmtAP_exp->execute();
                                    }
                                }


                                if ($appname == '') {
                                    $appname = '<strong>"' . $resultsApp['summary'] . '"</strong>';
                                } else {
                                    $appname = $appname . ', <strong>"' . $resultsApp['summary'] . '"</strong>';
                                }
                                if ($results5['is_custom'] == 1) {
                                    $queryInv = 'SELECT * FROM custom_plans WHERE id="' . $planID . '" and deleted=0';
                                } else {
                                $queryInv = 'SELECT * FROM plans WHERE id="' . $planID . '" and deleted=0';
                                }
                                $stmtInv = $mysqli->prepare($queryInv);
                                $stmtInv->execute();
                                $resultsInv = $stmtInv->fetch(PDO::FETCH_ASSOC);
                                if (!empty($resultsInv)) {
                                    $years = ($resultsInv['validity_in_days'] / 365); // days / 365 days
                                    $years = floor($years); // Remove all decimals

                                    if ($years > 1) {
                                        $yyyyy = $years . ' Years';
                                    } else {
                                        $yyyyy = $years . ' Year';
                                    }

                                    /*
                                      $queryInv12 = 'SELECT a.id,mp.id AS masterpaymentid,mp.promocodes_id,a.plan_id, ad.amount AS breakup_some,a.total_amount AS plan_amount, a.platform, ad.payment_type_id FROM
                                      (
                                      SELECT id,author_id,master_payment_id,app_id,plan_id,payment_done,total_amount, platform FROM author_payment
                                      ) AS a
                                      JOIN author_payment_details ad ON a.id=ad.author_payment_id
                                      JOIN master_payment mp ON mp.id=a.master_payment_id
                                      WHERE a.author_id IN (select id from author where custid="'.$custid.'") AND a.payment_done=1 AND a.id = "'.$value['id'].'" AND ad.amount!=0';
                                     */
                                    $queryInv12 = 'SELECT a.id,mp.id as masterpaymentid,mp.promocodes_id,a.plan_id,SUM(ad.amount) AS breakup_some,a.total_amount AS plan_amount, a.platform,mp.showService_tax,a.crm_discount,a.app_customization,
											GROUP_CONCAT( 
											CASE 
											WHEN ad.payment_type_id = 1 && ad.amount != 0 THEN 1
											WHEN ad.payment_type_id = 2 && ad.amount != 0 THEN 2 
											ELSE 0
											END) as payid FROM
											(
											SELECT id,author_id,master_payment_id,app_id,plan_id,payment_done,total_amount,platform,app_customization,crm_discount FROM author_payment
											) AS a
											LEFT JOIN author_payment_details ad ON a.id=ad.author_payment_id
											LEFT JOIN master_payment mp ON mp.id=a.master_payment_id
											WHERE a.author_id IN (select id from author where custid="' . $custid . '") AND a.payment_done=1 AND a.id="' . $value['id'] . '"
											GROUP BY a.id';
                                    $stmtInv12 = $mysqli->prepare($queryInv12);
                                    $stmtInv12->execute();
                                    $resultsInv12 = $stmtInv12->fetch(PDO::FETCH_ASSOC);
                                        $inclusiveTaxCheck = $resultsInv12['showService_tax'];
                                    $crm_discount = $resultsInv12['crm_discount'];
                                    // app icon and spash screen price start here 
                                    $sql_apd = 'SELECT payment_type_id,payment_type_value,is_custom FROM author_payment_details WHERE author_payment_id="' . $value['id'] . '"';
                                    $stmt_apd = $mysqli->prepare($sql_apd);
                                    $stmt_apd->execute();
                                    $results_apd = $stmt_apd->fetchAll(PDO::FETCH_ASSOC);
                                    if ($results_apd[0]['payment_type_id'] == 1)
                                        $icon_id = $results_apd[0]['payment_type_value'];
                                    if ($results_apd[1]['payment_type_id'] == 2)
                                        $splash_id = $results_apd[1]['payment_type_value'];
                                    if ($results_apd[0]['is_custom'] == 2) {
                                        $sql_icon = 'SELECT price,price_in_usd FROM custom_icons WHERE id="' . $icon_id . '"';
                                    } else {
                                        $sql_icon = 'SELECT price,price_in_usd FROM default_icons WHERE id="' . $icon_id . '"';
                                    }
                                    if ($results_apd[1]['is_custom'] == 2) {
                                        $sql_splash = 'SELECT price,price_in_usd FROM custom_splashscreen WHERE id="' . $splash_id . '"';
                                    } else {
                                        $sql_splash = 'SELECT price,price_in_usd FROM default_splash_screen WHERE id="' . $splash_id . '"';
                                    }
                                    $stmt_icons = $mysqli->prepare($sql_icon);
                                    $stmt_icons->execute();
                                    $results_icons = $stmt_icons->fetch(PDO::FETCH_ASSOC);


                                    $stmt_splash = $mysqli->prepare($sql_splash);
                                    $stmt_splash->execute();
                                    $results_splash = $stmt_splash->fetch(PDO::FETCH_ASSOC);

                                    $icon_splash_price = $results_icons['price_in_usd'] + $results_splash['price_in_usd'];
                                    if ($crm_discount > 0) {
                                        $icon_splash_price = $icon_splash_price - ($icon_splash_price * $crm_discount / 100);
                                    }

                                    $p_platform = '';
                                    if (!empty($resultsInv12)) {
                                        $app__type = explode(',', $resultsInv12['payid']);
                                        $currappbaseprice = $icon_splash_price + $resultsInv12['plan_amount'];


                                        $queryInv1 = 'SELECT * FROM platform WHERE id="' . $resultsInv12['platform'] . '"';
                                        $stmtInv1 = $mysqli->prepare($queryInv1);
                                        $stmtInv1->execute();
                                        $resultsInv1 = $stmtInv1->fetch(PDO::FETCH_ASSOC);

                                        if (!empty($resultsInv1)) {
                                            if ($resultsInv1['name'] == 'Cross Platform') {
                                                $p_platform = 'Android + iOS';
                                            } else {
                                                $p_platform = $resultsInv1['name'];
                                            }
                                        }

                                        $queryInv123 = 'SELECT * FROM app_type WHERE id="' . $resultsApp['type_app'] . '"';
                                        $stmtInv123 = $mysqli->prepare($queryInv123);
                                        $stmtInv123->execute();
                                        $resultsInv123 = $stmtInv123->fetch(PDO::FETCH_ASSOC);

                                        $my_app_type = '';
                                        if (!empty($resultsInv123)) {
                                            $my_app_type = $resultsInv123['name'];
                                        }

                                        if ($resultsInv12['app_customization'] == 1) {
                                            $planName = $resultsInv['plan_name'] . "+ Customize";
                                        } else {
                                            $planName = $resultsInv['plan_name'];
                                        }
                                            $order_itemsApp.='<tr><td style=" width: 250px;border-right: 1px solid #e0e0e0; border-left: 1px solid #a7a7a7;vertical-align: top; padding-top: 10px;padding-bottom: 10px; ">
																						
																						
																						
																						
					  
                                            <table cellpadding="0" cellspacing="0" style="padding-left: 10px;">
							<tr>
								<td>
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td style="line-height: 18px;">
												<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $resultsApp['summary'] . '</p>
							<p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">' . $my_app_type . '</p>
							<p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">OS </b>' . $p_platform . '</b></p>
							<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Package <b>' . $planName . '</b></p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="line-height: 22px;">';
                                        if ($app__type[0] > 0 && $app__type[1] > 0) {
                                                $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">App Icon</p>';
                                                $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">Splash Screen</p>';
                                        } else {
                                            if ($app__type[0] > 0 && $app__type[1] == 0) {
                                                    $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">App Icon</p>';
                                            } elseif ($app__type[0] == 0 && $app__type[1] > 0) {
                                                    $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">Splash Screen</p>';
                                            }
                                        }
                                            $order_itemsApp .= '</td></tr></table></td>
																							
																							
																                            <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; ">
                                        <p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">1</p></td>
																                            <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;  ">
																                            	<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $yyyyy . ' </p>
                                        </td><td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; ">
					                            									<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $currencyIcon . ' ' . $currappbaseprice . '</p>
                                        </td></tr>';




                                    }
                                }
                                $order_items_App .= $order_itemsApp;
                                $basetotal_App += $currappbaseprice;
                                $count++;
                                $kk++;
                            }
                        }
                        $sqlforAso = "SELECT apd.payment_type_value,ap.crm_discount FROM master_payment mp JOIN author_payment ap ON ap.master_payment_id=mp.id JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE ap.payment_done='1' AND mp.order_id='$order_id' AND ap.plan_id IS NULL GROUP BY apd.payment_type_value";
                        $stmt_Aso = $mysqli->prepare($sqlforAso);
                        $stmt_Aso->execute();
                        $resultsASo = $stmt_Aso->fetchAll(PDO::FETCH_ASSOC);
                        $order_items_Aso='';
                        $orderDetailsAsohead='';
                        $basetotal_Aso=0;
                        if (!empty($resultsASo)) {
                            $asoexsit='';
                            $asoAppexsit ='';
                            if(count($resultsASo)>1){
                                $asoexsit='s';
                            } 
                            $orderDetailsAsohead = '<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0;border-left: 1px solid #a7a7a7;vertical-align: top;padding-top: 10px;padding-bottom: 10px;padding-left: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;"><strong>ASO Package'.$asoexsit.':<strong></p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; "></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;"></td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; "></td>
</tr>';
                            foreach ($resultsASo as $key => $value) {
                                $crm_discount = $value['crm_discount'];
                                    $sqlApd = "SELECT ap.app_id,apd.* FROM author_payment ap LEFT JOIN master_payment mp ON mp.id=ap.master_payment_id LEFT JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE mp.id = (SELECT id FROM master_payment WHERE order_id=$order_id) AND apd.payment_type_value=".$value['payment_type_value'];
                                    $stmtApd = $mysqli->prepare($sqlApd);
                                    $stmtApd->execute();
                                    $resultsApd = $stmtApd->fetchAll(PDO::FETCH_ASSOC);
                                    if(!empty($resultsApd)){
                                        $asoname='';
                                        $aso_appname='';
                                        $isCustom='';
                                        $currappbaseprice='';
                                        $order_itemsAso='';
                                        $asoexsit='';
                                        $asO_count = count($resultsApd);
                            if($asO_count>1){
                                $asoAppexsit='s';
                            } 
                            $quantity=$asO_count;
                                    foreach ($resultsApd as $keyAso => $valueAso) {
                                    $asoid = $valueAso['payment_type_value'];
                                    $isCustom = $valueAso['is_custom'];
                                    if ($isCustom == 1) {
                                        $sql_aso = 'SELECT name,discounted_amount,price_in_usd FROM custom_marketing_packages WHERE id="' . $asoid . '"';
                                    } else {
                                        $sql_aso = 'SELECT name,discounted_amount,price_in_usd FROM marketing_packages WHERE id="' . $asoid . '"';
                                    }
                                    $stmt_aso = $mysqli->prepare($sql_aso);
                                    $stmt_aso->execute();
                                    $results_aso = $stmt_aso->fetch(PDO::FETCH_ASSOC);
                                    if($asoname==''){
                                        $asoname = $results_aso['name'] . ',';
                                    } else if($asoname!='' && $valueAso['is_custom'] != $isCustom){
                                         $asoname = $results_aso['name'] . ',';
                                    } 
                                        $sql_asoApp = 'SELECT summary FROM app_data WHERE id=' . $valueAso['app_id'];
                                        $stmt_asoApp = $mysqli->prepare($sql_asoApp);
                                    $stmt_asoApp->execute();
                                    $results_asoAppName = $stmt_asoApp->fetch(PDO::FETCH_ASSOC);
                                    $aso_appname .= $results_asoAppName['summary'] . ',';
                                        if ($crm_discount > 0) {
                                            $currappbaseprice += $results_aso['price_in_usd'] - ($results_aso['price_in_usd'] * $crm_discount / 100);
                                        } else {
                                        $currappbaseprice += $results_aso['price_in_usd'];                                        
                                        }
                                    }
                                    $order_itemsAso ='<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0; border-left: 1px solid #a7a7a7;vertical-align: top; padding-top: 10px;padding-bottom: 10px; ">
<table cellpadding="0" cellspacing="0" style="padding-left: 10px;">
<tr>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td style="line-height: 18px;">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . substr($asoname,0,-1) . '</p>							
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">App'.$asoAppexsit.':'.substr($aso_appname,0,-1).'</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">'.$quantity.'</p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">One Time Package</p>
</td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $currencyIcon . ' ' . $currappbaseprice . '</p>
</td>
</tr>
';
                                    }
                                $order_items_Aso .= $order_itemsAso;
                                $basetotal_Aso += $currappbaseprice;
                                $count++;
                            }
                        }
                    }
                }
                if($order_items_Aso!=''){
                    $order_items = $orderInvoiceHead.$order_items_App.$orderDetailsAsohead.$order_items_Aso;
                } else{
                    $order_items = $orderInvoiceHead.$order_items_App;
                }
                $basetotal = $basetotal_App+$basetotal_Aso;

                if ($count > 1) {
                    $app_is = 'apps are';
                } else {
                    $app_is = 'app is';
                }


                $sql = "select * from author where custid='$custid'";
                $stmt = $mysqli->prepare($sql);
                $stmt->execute();
                $results = $stmt->fetch(PDO::FETCH_ASSOC);

                $queryDis = 'SELECT * FROM master_payment WHERE order_id="' . $order_id . '"';
                $stmtDis = $mysqli->prepare($queryDis);
                $stmtDis->execute();
                $resultsDis = $stmtDis->fetch(PDO::FETCH_ASSOC);

                if (!empty($resultsDis) && $resultsDis['promocodes_id'] != '') {
                    $promocode = $resultsDis['promocodes_id'];
                    if ($is_custom_promocode == 1) {
                        $discountamount = $resultsDis['discount'];
                        $discount = $discountamount;
                    } else {
                    $sqlmp = "SELECT * FROM promocodes WHERE id='$promocode'";
                    $appMP = $mysqli->prepare($sqlmp);
                    $appMP->execute();
                    $appMPid = $appMP->fetch(PDO::FETCH_ASSOC);
                    $discountamount = $appMPid['percentage_discount'];
                    $discount = round($basetotal * ($discountamount / 100), 2);
                    }
                } else {
                    $discount = '0.00';
                }

                //                $discount = round($basetotal * ($discountamount / 100), 2);
                $subtotal = round($basetotal - $discount, 2);
                if ($currency == 1) {
                $servicetax = round($subtotal * $service_tax / 100, 2);
                $order_total = round($resultsDis['total_amount'], 2);
                } else{
                    $servicetax=0;
                    $order_total=$subtotal;
                }


                $csubject = 'Congratulations! Your app is now in QA!';
                //$basicUrl = $this->url;
                $chtmlcontent = file_get_contents('edm-new/app_qa.php');
                $clastname = $results['last_name'] != '' ? ' ' . $results['last_name'] : $results['last_name'];
                $cname = ucwords($results['first_name'] . $clastname);
                if (trim($cname) == '') {
                    $cname = 'Hi';
                } else {
                    $cname = 'Dear ' . $cname;
                }
                $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                $chtmlcontent = str_replace('{app_name}', $appname, $chtmlcontent);
                $chtmlcontent = str_replace('{custid}', $custid, $chtmlcontent);
                $chtmlcontent = str_replace('{app_is}', $app_is, $chtmlcontent);

                $cto = $results['email_address'];
                //$cto = 'ppsingh2288@gmail.com';
                $cformemail = 'noreply@instappy.com';
                $key = 'f894535ddf80bb745fc15e47e42a595e';
                //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
                //$customerhead = file_get_contents($url);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => array(
                        'api_key' => $key,
                        'subject' => $csubject,
                        'fromname' => 'Instappy',
                        'from' => $cformemail,
                        'content' => $chtmlcontent,
                        'recipients' => $cto
                    )
                ));
                $customerhead = curl_exec($curl);

                curl_close($curl);
                $payment_part = '';
                $serviceTaxhtml = '';
                
                if ($currency == 2) {
        $inclusiveTax = '<p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Subtotal(inclusive of all taxes): </p>';
        $inclusiveTax2 = '<p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($subtotal, 2) . '</p>';
    } else {
        $inclusiveTax = '<p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Subtotal: </p><p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Service Tax (15%): </p>';
        $inclusiveTax2 = '<p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($subtotal, 2) . '</p><p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($servicetax, 2) . '</p>';
    }
                $planimg = "market_packages2.png";
                // invoice email
                $csubject = 'Instappy Invoice for order ' . $order_id;
                //$basicUrl = $this->url;
                $chtmlcontent = file_get_contents('edm-new/invoice-website.php');
                $clastname = $results['last_name'] != '' ? ' ' . $results['last_name'] : $results['last_name'];
                $cname = ucwords($results['first_name'] . $clastname);





                $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                $chtmlcontent = str_replace('{address}', $resultsDis['address'] . ',' . $resultsDis['city'], $chtmlcontent);
                $chtmlcontent = str_replace('{state}', $resultsDis['state'], $chtmlcontent);
                $chtmlcontent = str_replace('{order_no}', $order_id, $chtmlcontent);
                $chtmlcontent = str_replace('{custid}', $custid, $chtmlcontent);
//    $chtmlcontent = str_replace('{app_details_summary}', $resultsApp['summary'], $chtmlcontent);
//    $chtmlcontent = str_replace('{my_app_type}', $my_app_type, $chtmlcontent);
//    $chtmlcontent = str_replace('{p_platform}', $p_platform, $chtmlcontent);
//    $chtmlcontent = str_replace('{plan_title}', $planName, $chtmlcontent);
//    $chtmlcontent = str_replace('{yyyyy}', $yyyyy, $chtmlcontent);
//    $chtmlcontent = str_replace('{currencyIconPrice}', ($currencyIcon . ' ' . $currappbaseprice), $chtmlcontent);
//    $chtmlcontent = str_replace('{payment_part}', $payment_part, $chtmlcontent);
                $chtmlcontent = str_replace('{order_items}', $order_items, $chtmlcontent);
                $chtmlcontent = str_replace('{discount}', round($discount, 2), $chtmlcontent);
//                $chtmlcontent = str_replace('{subtotal}', round($subtotal, 2), $chtmlcontent);
//                $chtmlcontent = str_replace('{tax}', $servicetax, $chtmlcontent);
                $chtmlcontent = str_replace('{order_total}', round($order_total, 2), $chtmlcontent);
                $chtmlcontent = str_replace('{currentdate}', date('d-m-Y', time()), $chtmlcontent);
                $chtmlcontent = str_replace('{currency}', $currencyIcon, $chtmlcontent);
                $chtmlcontent = str_replace('{service_tax}', $service_tax, $chtmlcontent);
                $chtmlcontent = str_replace('{planimg}', $planimg, $chtmlcontent);
    $chtmlcontent = str_replace('{inclusiveTax}', $inclusiveTax, $chtmlcontent);
    $chtmlcontent = str_replace('{inclusiveTax2}', $inclusiveTax2, $chtmlcontent);








                $string = getcwd();

                $pathdir = $string . '/pdf/' . $author_id;
                $directory = $pathdir;
                if (!file_exists($directory)) {
                    mkdir($string . '/pdf/' . $author_id, 0777, true);
                }

                // make pdf                
                $file = fopen("convertTopdf.php", "w");
                fwrite($file, $chtmlcontent);
                fclose($file);
                $pdfname = $order_id . '-invoice.pdf';
                ob_start();
                ob_get_clean();

                include('mpdf60/mpdf.php');
                $mpdf = new mPDF('c', 'A4', '', '', 10, 10, 10, 10);
                $mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("Instappy.com - Invoice");
                $mpdf->SetAuthor("Instappy.com");
                $mpdf->SetWatermarkText("Paid");
                $mpdf->showWatermarkText = true;
                $mpdf->watermark_font = 'DejaVuSansCondensed';
                $mpdf->watermarkTextAlpha = 0.1;
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->WriteHTML(file_get_contents("convertTopdf.php"));
                $mpdf->Output($directory . '/' . $pdfname, 'F');

                $queryUInvoice = 'UPDATE master_payment set invoice_link="' . $pdfname . '" WHERE order_id="' . $order_id . '"';
                $stmtUInvoice = $mysqli->prepare($queryUInvoice);
                $stmtUInvoice->execute();

                $cbcc = 'admin@instappy.com';
                $cto = $results['email_address'];
                //$cto = 'ppsingh2288@gmail.com';
                $cformemail = 'noreply@instappy.com';
                $key = 'f894535ddf80bb745fc15e47e42a595e';
                //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
                //$customerhead = file_get_contents($url);
                if ($is_partpayment) {
                    
                } else {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => array(
                            'api_key' => $key,
                            'subject' => $csubject,
                            'fromname' => 'Instappy',
                            'from' => $cformemail,
                            'content' => $chtmlcontent,
                            'recipients' => $cto,
                            'bcc' => $cbcc
                        )
                    ));
                    $customerhead = curl_exec($curl);

                    curl_close($curl);
                }
                if (isset($_SESSION['source'])) {
                    if ($_SESSION['source'] == "LinkConnector") {
                        ?>
                        <!-- Start UTS Confirm Code -->
                        <!-- Generic Cart Cart Variables -->
                        <script type="text/javascript">
                            var uts_orderid = '<?php echo $order_id; ?>';
                            var uts_saleamount = '<?php echo $totalamount; ?>';
                            var uts_coupon = '';
                            var uts_discount = '<?php echo $discount; ?>';
                            var uts_currency = 'USD';
                            var uts_eventid = '3863';
                        </script>
                        <script type="text/javascript" src="//www.linkconnector.com/uts_tm.php?cgid=900558"></script>
                        <!-- End UTS Confirm Code -->
                        <?php
                    }
                }
                ?>

                <section class="main">
                    <section class="right_main">
                        <div class="right_inner">
                            <div class="bannertitle">
                                <h2 style="font-size:16px;line-height:28px">You have qualified for multiple money saving offers. <br/>We have automatically applied the best offers for you giving you the lowest price.</h2>
                                <p style="margin-top:10px">Apps built using the Instappy.com are packed with features that are built for your success.
                                    Instappy apps are <span>instant, affordable, stunning, intuitive</span> and will <span>change the way you interact 
                                        with your customers.</span> Our business model allows us to provide everyone with world-class, feature-packed 
                                    applications on Android and iOS for a fraction of the cost it takes to develop an app otherwise.</p>


                            </div>
                            <div class="payment_steps pay-status">
                                <ul>
                                    <li class="done">
                                        <em>1</em>
                                        <span>Cart</span>
                                        <hr noshade>
                                    </li>
                                    <li class="done">
                                        <em>2</em>
                                        <span>Billing &amp; Payment</span>
                                        <hr noshade>
                                    </li>
                                    <li class="done">
                                        <em>3</em>
                                        <span>Place	Your Order</span>
                                        <hr noshade>
                                    </li>
                                    <li class="active">
                                        <em>4</em>
                                        <span>Finalise</span>
                                        <hr noshade>
                                    </li>
                                </ul>
                            </div>
                            <div class="clear"></div>
                            <div class="payment_box">
                                <div class="payment_left view-area">
                                    <div class="payment_thankyou">
                                        <h2>Congratulations!</h2>
                                        <p>Your awesome app is up and ready.</p>
                                        <span>We know, you can't wait to launch your brand new app for the world to see. You just have to wait a little bit more! Your app is under review and QA so that we can ensure everything is in its rightful place and your customers get truly fantastic experience every time they engage with your app. Your app APK will be available for download ideally in 2 business days.</span>
                                        <div class="thankyou_publishing_app">
                                            <h3>Would you like us to help you publish your app to the store?</h3>
                                            <input type="radio" id="publish1" name="publishapp" checked="checked">
                                            <label for="publish1" class="optn1">I have an account. I can manage my way through.</label>
                                            <div class="clear"></div>
                                            <input type="radio" id="publish2" name="publishapp">
                                            <label for="publish2" class="optn2">Yes, I think I need help from here onwards</label>
                                            <div class="clear"></div>
                                            <div class="inner_radio" style="display:none;">
                                                <input type="radio" id="inpublish1" name="publishappin" value="" checked="checked">
                                                <label for="inpublish1">I am publishing an Android app to Play Store.</label>
                                                <div class="clear"></div>
                                                <input type="radio" id="inpublish2" name="publishappin" value="" >
                                                <label for="inpublish2">I am publishing an iOS app to App Store.</label>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <a href="javascript:void(0);" class="make_app_next">Finish</a>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="payment_bottom">
                                    <p>Meanwhile, it is a good idea to get the App Store Optimisation (ASO) in place, so that the moment your app is launched it shows up the ranks.</p>
                                    <p>Need help? <a href="applisting.php">Click here</a> for some great tips on ASO.</p>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </section>
                </section>
                <?php
            } else {
                //$order_id=1471351468;
                $sql6 = "SELECT * FROM master_payment WHERE payment_done=1 and order_id='$order_id' LIMIT 1";
                $stmt6 = $mysqli->prepare($sql6);
                $stmt6->execute();
                $results6 = $stmt6->fetch(PDO::FETCH_ASSOC);
                $promocode = $results6['promocodes_id'];
                $author_id = $results6['author_id'];
                $is_partpayment = $results6['is_partpayment'];
                // part payment update;
                if ($is_partpayment == 1 && ($_SESSION['part_payment_id'] > 0)) {
                    $sql_part = "SELECT * FROM master_payment_part WHERE  id='" . $_SESSION['part_payment_id'] . "' LIMIT 1";
                    $stmt_part = $mysqli->prepare($sql_part);
                    $stmt_part->execute();
                    $results_part = $stmt_part->fetch(PDO::FETCH_ASSOC);


                    //custom plane check 
                    $sql_planc = "SELECT * FROM author_payment WHERE  master_payment_id='" . $results6['id'] . "' and plan_id IS NOT NULL LIMIT 1";
                    $stmt_planc = $mysqli->prepare($sql_planc);
                    $stmt_planc->execute();
                    $results_planc = $stmt_planc->fetch(PDO::FETCH_ASSOC);
                    $sql_planAso = "SELECT apd.payment_type_value FROM master_payment mp JOIN author_payment ap ON ap.master_payment_id=mp.id JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE ap.payment_done='1' AND mp.id=".$results6['id']." AND ap.plan_id IS NULL GROUP BY apd.payment_type_value";
                    $stmt_planAso = $mysqli->prepare($sql_planAso);
                    $stmt_planAso->execute();
                    $results_planAso = $stmt_planAso->fetchAll(PDO::FETCH_ASSOC);                    

                    // for platform 
                    $sql_plat = "SELECT * FROM platform WHERE  id='" . $results_planc['platform'] . "'  LIMIT 1";
                    $stmt_plat = $mysqli->prepare($sql_plat);
                    $stmt_plat->execute();
                    $results_platform = $stmt_plat->fetch(PDO::FETCH_ASSOC);

                    // for currency

                    $sql_curr = "SELECT * FROM currency_type WHERE  id='" . $results6['currency_type_id'] . "'  LIMIT 1";
                    $stmt_curr = $mysqli->prepare($sql_curr);
                    $stmt_curr->execute();
                    $results_curr = $stmt_curr->fetch(PDO::FETCH_ASSOC);


                    // for author payment details

                    $sql_apd = 'SELECT a.id,mp.id as masterpaymentid,mp.promocodes_id,a.plan_id,SUM(ad.amount) AS breakup_some,a.total_amount AS plan_amount, a.platform,mp.showService_tax,a.showMainPlan,
											GROUP_CONCAT( 
											CASE 
											WHEN ad.payment_type_id = 1 && ad.amount != 0 THEN 1
											WHEN ad.payment_type_id = 2 && ad.amount != 0 THEN 2 
											ELSE 0
											END) as payid FROM
											(
											SELECT id,author_id,master_payment_id,app_id,plan_id,payment_done,total_amount,platform,showMainPlan FROM author_payment
											) AS a
											LEFT JOIN author_payment_details ad ON a.id=ad.author_payment_id
											LEFT JOIN master_payment mp ON mp.id=a.master_payment_id
											WHERE a.author_id =' . $results6['author_id'] . ' AND a.payment_done=1 AND a.id="' . $results_planc['id'] . '"
											GROUP BY a.id';
                    $stmt_apd = $mysqli->prepare($sql_apd);
                    $stmt_apd->execute();
                                        $results_apd = $stmt_apd->fetch(PDO::FETCH_ASSOC);
//                    $sql_apd = 'SELECT * FROM author_payment_details WHERE author_payment_id="' . $results_planc['id'] . '"';
//                    $stmt_apd = $mysqli->prepare($sql_apd);
//                    $stmt_apd->execute();
//                    $results_apd = $stmt_apd->fetchAll(PDO::FETCH_ASSOC);
                    if ($results_planc['is_custom'] == 1) {
                        $sql_cplan = "SELECT id,validity_in_days,plan_name FROM custom_plans WHERE  app_id='" . $results_part['app_id'] . "' LIMIT 1";
                    } else {
                        $sql_cplan = "SELECT id,validity_in_days,plan_name FROM plans WHERE  id in (select plan_id from author_payment WHERE  app_id='" . $results_part['app_id'] . "') LIMIT 1";
                    }

                    $stmt_cplan = $mysqli->prepare($sql_cplan);
                    $stmt_cplan->execute();
                    $results_cplan = $stmt_cplan->fetch(PDO::FETCH_ASSOC);



                    $left_amount = round($results6['part_amount_left'], 2) - round($results_part['part_amount'], 2);
                    $queryAP_mp = 'UPDATE master_payment set  part_amount_left="' . $left_amount . '"  where id= "' . $results6['id'] . '" LIMIT 1';
                    $stmtAP_mp = $mysqli->prepare($queryAP_mp);
                    $stmtAP_mp->execute();

                    // update master payment
                    $queryAP = 'UPDATE master_payment_part set  payment_done=1 ,updated_at=now() ,transaction_id="' . $tracking_id . '",transaction_date=NOW(),part_paid_amount="' . $_SESSION['part_total_amount'] . '" WHERE master_payment_id=' . $results6['id'] . ' AND app_id=' . $results_part['app_id'] . ' AND payment_done=0 LIMIT 1';
                    $stmtAP = $mysqli->prepare($queryAP);
                    $stmtAP->execute();
                    $maser_part_id = $results_part['id'];
                    $maser_app_id = $results_part['app_id'];

                    $queryApp = 'SELECT * FROM app_data WHERE id="' . $maser_app_id . '"';
                    $stmtApp = $mysqli->prepare($queryApp);
                    $stmtApp->execute();
                    $resultsApp = $stmtApp->fetch(PDO::FETCH_ASSOC);
                    $ack_invoice->sendinvoice_performa(array('id' => $maser_part_id, 'app_id' => $maser_app_id));
                    if ($results_part['next_paymentdate'] != '' && $resultsApp['plan_expiry_date']<$results_part['next_paymentdate']) {
                        //$exp_date=date('Y-m-d H:i:s', strtotime($results_part['next_paymentdate'].'+ 15 day'));
                        $exp_date = date('Y-m-d H:i:s', strtotime($results_part['next_paymentdate']));
                    } else {
                        if($results_apd['showMainPlan']==1){
                        $exp_date = date('Y-m-d H:i:s', strtotime($results6['transaction_date'] . '+ ' . $results_cplan['validity_in_days'] . ' day'));
                        }
                    }
                    $planExp='';
                    if($exp_date!=''){
                        $planExp = "plan_expiry_date='$exp_date',";
                    }
                    $publish_date = date('Y-m-d H:i:s');
                    $queryAP_exp = 'UPDATE app_data set  '.$planExp.' plan_id="' . $results_cplan['id'] . '" ,publish_date="' . $publish_date . '" where id= ' . $results_part['app_id'] . ' LIMIT 1';
                    $stmtAP_exp = $mysqli->prepare($queryAP_exp);
                    $stmtAP_exp->execute();

                    // send invoice for part payment

                    $queryDis = 'SELECT * FROM master_payment WHERE order_id="' . $order_id . '"';
                    $stmtDis = $mysqli->prepare($queryDis);
                    $stmtDis->execute();
                    $resultsDis = $stmtDis->fetch(PDO::FETCH_ASSOC);


                    //

                    $sql = 'SELECT sum(part_amount) as total  FROM master_payment_part WHERE app_id="' . $maser_app_id . '" and payment_done=1 and master_payment_id=' . $results6['id'];
                    $sts = $mysqli->prepare($sql);
                    $sts->execute();
                    $results_part_all = $sts->fetch(PDO::FETCH_ASSOC);

                    $order_items = '';
                    $order_itemsApp='';
                    $results_pm = $ack_invoice->part_payment_details($maser_app_id);

                    if (!empty($results_cplan)) {
                        $years = ($results_cplan['validity_in_days'] / 365); // days / 365 days
                        $years = floor($years); // Remove all decimals

                        if ($years > 1) {
                            $yyyyy = $years . ' Years';
                        } else {
                            $yyyyy = $years . ' Year';
                        }
                    }
//                    $queryApp = 'SELECT * FROM app_data WHERE id="' . $maser_app_id . '"';
//                    $stmtApp = $mysqli->prepare($queryApp);
//                    $stmtApp->execute();
//                    $resultsApp = $stmtApp->fetch(PDO::FETCH_ASSOC);
                    $queryInv123 = 'SELECT * FROM app_type WHERE id="' . $resultsApp['type_app'] . '"';
                    $stmtInv123 = $mysqli->prepare($queryInv123);
                    $stmtInv123->execute();
                    $resultsInv123 = $stmtInv123->fetch(PDO::FETCH_ASSOC);

                    $my_app_type = '';
                    $orderInvoiceHead ='<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0;border-left: 1px solid #a7a7a7;vertical-align: top;padding-top: 10px;padding-bottom: 10px;padding-left: 10px; "><p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Instappy Subscription Fee</p>
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Inclusions:</p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; "></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;"></td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; "></td>
</tr>';
                    if (!empty($resultsInv123)) {
                        $my_app_type = $resultsInv123['name'];
                    }
$order_itemsApp .= '<tr>
                    <td align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:5px 10px; border-left:1px solid #a7a7a7; border-right:1px solid #dbdbdb  ; color:#323232  ;">
																						
																						
																						
					  
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">"' . $resultsApp['summary'] . '"</p>
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">"' . $my_app_type . '"</p>
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">OS <b>' . $results_platform['name'] . '</b></p>
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Package <b>' . $results_cplan['plan_name'] . '</b></p>';

$app__type = explode(',', $results_apd['payid']);
if ($app__type[0] > 0 && $app__type[1] > 0) {
                                                $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">App Icon</p>';
                                                $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">Splash Screen</p>';
                    } else {
                                                if ($app__type[0] > 0 && $app__type[1] == 0) {
                                                    $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">App Icon</p>';
                                                } elseif ($app__type[0] == 0 && $app__type[1] > 0) {
                                                    $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">Splash Screen</p>';
                        }
                    }
//if ($results_apd[0]['id'] > 0 && $results_apd[1]['id'] > 0) {
//    $order_itemsApp .= '<p style="margin:10px 0 0 20px;">App Icon</p>';
//    $order_itemsApp .= '<p style="margin:10px 0 0 20px;">Splash Screen</p>';
//} else {
//    if ($results_apd[0]['id'] > 0 && $results_apd[1]['id'] == 0) {
//        $order_itemsApp .= '<p style="margin:10px 0 0 20px;">App Icon</p>';
//    } elseif ($results_apd[0]['id'] == 0 && $results_apd[1]['id'] > 0) {
//        $order_itemsApp .= '<p style="margin:10px 0 0 20px;">Splash Screen</p>';
//    }
//}
$order_itemsApp .= '</td>
                    <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; ">
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">1</p></td>
                    <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;">
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $yyyyy . '</p>
                    </td><td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; ">
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $results_curr['Name'] . ' ' . round($results_part_all['total'], 2) . '</p>
                    </td></tr>';
                    $order_items_App = $order_itemsApp;
                    $order_items_Aso='';
                        $orderDetailsAsohead='';
                    if(!empty($results_planAso)){
                        $asoAppexsit='';
                        $orderDetailsAsohead = '<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0;border-left: 1px solid #a7a7a7;vertical-align: top;padding-top: 10px;padding-bottom: 10px;padding-left: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;"><strong>ASO Package:<strong></p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; "></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;"></td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; "></td>
												</tr>';
                            foreach ($results_planAso as $key => $value) {
                                    $sqlApd = "SELECT ap.app_id,apd.* FROM author_payment ap LEFT JOIN master_payment mp ON mp.id=ap.master_payment_id LEFT JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE mp.id = (SELECT id FROM master_payment WHERE order_id=$order_id) AND apd.payment_type_value=".$value['payment_type_value'];
                                    $stmtApd = $mysqli->prepare($sqlApd);
                                    $stmtApd->execute();
                                    $resultsApd = $stmtApd->fetchAll(PDO::FETCH_ASSOC);
                                    if(!empty($resultsApd)){
                                        $asoname='';
                                        $aso_appname='';
                                        $isCustom='';
                                        $currappbaseprice='';
                                        $order_itemsAso='';
                                        $asoexsit='';
                            $quantity = 1;
                            foreach ($resultsApd as $keyAso => $valueAso) {
                                    $asoid = $valueAso['payment_type_value'];
                                    $isCustom = $valueAso['is_custom'];
                                    if ($isCustom == 1) {
                                        $sql_aso = 'SELECT name,discounted_amount,price_in_usd FROM custom_marketing_packages WHERE id="' . $asoid . '"';
                                    } else {
                                        $sql_aso = 'SELECT name,discounted_amount,price_in_usd FROM marketing_packages WHERE id="' . $asoid . '"';
                                    }
                                    $stmt_aso = $mysqli->prepare($sql_aso);
                                    $stmt_aso->execute();
                                    $results_aso = $stmt_aso->fetch(PDO::FETCH_ASSOC);
                                    if($asoname==''){
                                        $asoname = $results_aso['name'] . ',';
                                    } else if($asoname!='' && $valueAso['is_custom'] != $isCustom){
                                         $asoname = $results_aso['name'] . ',';
                                    } 
                                        $sql_asoApp = 'SELECT summary FROM app_data WHERE id=' . $valueAso['app_id'];
                                        $stmt_asoApp = $mysqli->prepare($sql_asoApp);
                                    $stmt_asoApp->execute();
                                    $results_asoAppName = $stmt_asoApp->fetch(PDO::FETCH_ASSOC);
                                    $aso_appname .= $results_asoAppName['summary'] . ',';
//                                        $currappbaseprice += $results_aso['price_in_usd'];                                        
                                    }
                                    $order_itemsAso ='<tr>

<td style=" width: 250px;border-right: 1px solid #e0e0e0; border-left: 1px solid #a7a7a7;vertical-align: top; padding-top: 0px;padding-bottom: 10px; ">

<table cellpadding="0" cellspacing="0" style="padding-left: 10px;">
<tr>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td style="line-height: 18px;">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . substr($asoname,0,-1) . '</p>							
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">App:'.substr($aso_appname,0,-1).'</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">'.$quantity.'</p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">One Time Package</p>
</td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;"></p>
</td>
</tr>
';
                                    }
                                $order_items_Aso = $order_itemsAso;
//                                $basetotal_Aso = $currappbaseprice;
                            }
                    }
if($order_items_Aso!=''){
                    $order_items = $orderInvoiceHead.$order_items_App.$orderDetailsAsohead.$order_items_Aso;
                } else{
                    $order_items = $orderInvoiceHead.$order_items_App;
                }
                    $sql = "select * from author where custid='$custid'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->execute();
                    $results = $stmt->fetch(PDO::FETCH_ASSOC);
                    $serviceTaxhtml = '';
//                    $payment_part='';
                    if ($currency == 2) {
                        $orderTotal = round($results_part_all['total'], 2) - $results6['discount'];
        $inclusiveTax = '<p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Subtotal(inclusive of all taxes): </p>';
        $inclusiveTax2 = '<p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($results_part_all['total'], 2) . '</p>';
    } else {
        $orderTotal = (round($results_part_all['total'], 2) + round($results_part_all['total'] * $service_tax / 100, 2)) - $results6['discount'];
        $inclusiveTax = '<p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Subtotal: </p><p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Service Tax (15%): </p>';
        $inclusiveTax2 = '<p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($results_part_all['total'], 2) . '</p><p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($results_part_all['total'] * $service_tax / 100, 2) . '</p>';
    }
                    $planimg = "market_packages2.png";
                    // invoice email
                    $csubject = 'Instappy Invoice for order ' . $order_id;
                    //$basicUrl = $this->url;
                    $chtmlcontent = file_get_contents('edm-new/invoice.php');
                    $clastname = $results['last_name'] != '' ? ' ' . $results['last_name'] : $results['last_name'];
                    $cname = ucwords($results['first_name'] . $clastname);


                    $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                    $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                    $chtmlcontent = str_replace('{address}', $resultsDis['address'] . ',' . $resultsDis['city'], $chtmlcontent);
                    $chtmlcontent = str_replace('{state}', $resultsDis['state'], $chtmlcontent);
                    $chtmlcontent = str_replace('{order_no}', $order_id, $chtmlcontent);
                    $chtmlcontent = str_replace('{custid}', $custid, $chtmlcontent);
//                    $chtmlcontent = str_replace('{app_details_summary}', $resultsApp['summary'], $chtmlcontent);
//                    $chtmlcontent = str_replace('{my_app_type}', $my_app_type, $chtmlcontent);
//                    $chtmlcontent = str_replace('{p_platform}', $results_platform['name'], $chtmlcontent);
//                    $chtmlcontent = str_replace('{plan_title}', $results_cplan['plan_name'], $chtmlcontent);
//                    $chtmlcontent = str_replace('{yyyyy}', $yyyyy, $chtmlcontent);
//                    $chtmlcontent = str_replace('{currencyIconPrice}', ($currencyIcon . ' ' . round($results_part_all['total'], 2)), $chtmlcontent);
//    $chtmlcontent = str_replace('{payment_part}', $payment_part, $chtmlcontent);
                $chtmlcontent = str_replace('{order_items}', $order_items, $chtmlcontent);
                    $chtmlcontent = str_replace('{discount}', $results6['discount'], $chtmlcontent);
//                    $chtmlcontent = str_replace('{subtotal}', round($results_part_all['total'], 2), $chtmlcontent);
//                    $chtmlcontent = str_replace('{tax}', round($results_part_all['total'] * $service_tax / 100, 2), $chtmlcontent);
                    $chtmlcontent = str_replace('{order_total}', $orderTotal, $chtmlcontent);
                    $chtmlcontent = str_replace('{currentdate}', date('d-m-Y', time()), $chtmlcontent);
                    $chtmlcontent = str_replace('{currency}', $results_curr['Name'], $chtmlcontent);
                    $chtmlcontent = str_replace('{service_tax}', $service_tax, $chtmlcontent);
                    $chtmlcontent = str_replace('{planimg}', $planimg, $chtmlcontent);
    $chtmlcontent = str_replace('{inclusiveTax}', $inclusiveTax, $chtmlcontent);
    $chtmlcontent = str_replace('{inclusiveTax2}', $inclusiveTax2, $chtmlcontent);

                    $string = getcwd();

                    $pathdir = $string . '/pdf/' . $author_id;
                    $directory = $pathdir;
                    if (!file_exists($directory)) {
                        mkdir($string . '/pdf/' . $author_id, 0777, true);
                    }

                    // make pdf                
                    $file = fopen("convertTopdf.php", "w");
                    fwrite($file, $chtmlcontent);
                    fclose($file);
                    $pdfname = $order_id . '-part-payment-invoice.pdf';
                    ob_start();
                    ob_get_clean();

                    include('mpdf60/mpdf.php');
                    $mpdf = new mPDF('c', 'A4', '', '', 10, 10, 10, 10);
                    $mpdf->SetProtection(array('print'));
                    $mpdf->SetTitle("Instappy.com - Invoice");
                    $mpdf->SetAuthor("Instappy.com");
                    $mpdf->SetWatermarkText("Paid");
                    $mpdf->showWatermarkText = true;
                    $mpdf->watermark_font = 'DejaVuSansCondensed';
                    $mpdf->watermarkTextAlpha = 0.1;
                    $mpdf->SetDisplayMode('fullpage');
                    $mpdf->WriteHTML(file_get_contents("convertTopdf.php"));
                    $mpdf->Output($directory . '/' . $pdfname, 'F');

                    $queryUInvoice = 'UPDATE master_payment_part set invoice_link="' . $pdfname . '" WHERE id="' . $results_part['id'] . '"';
                    $stmtUInvoice = $mysqli->prepare($queryUInvoice);
                    $stmtUInvoice->execute();

                    $cbcc = 'admin@instappy.com';
                    $cto = $results['email_address'];
                    $cformemail = 'noreply@instappy.com';
                    $key = 'f894535ddf80bb745fc15e47e42a595e';
                    //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
                    //$customerhead = file_get_contents($url);

                    if (empty($results_part['next_paymentdate'])) {
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                            CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => array(
                                'api_key' => $key,
                                'subject' => $csubject,
                                'fromname' => 'Instappy',
                                'from' => $cformemail,
                                'content' => $chtmlcontent,
                                'recipients' => $cto,
                                'bcc' => $cbcc
                            )
                        ));
                        $customerhead = curl_exec($curl);

                        curl_close($curl);
                    }
                    $app_amount = 0;
                    $kk = 0;
                    $not_done = 0;
                    $done = 0;
                    $left_amt = 0;
                    foreach ($results_pm as $value) {
                        $app_amount+= $value->part_amount;

                        if ($value->payment_done == 0) {
                            if ($kk == 0) {
                                $next_part_payment = $value->paymentdate;
                                $kk++;
                            }
                            $not_done++;
                            $left_amt+= $value->part_amount;
                        } else {
                            $done++;
                        }
                    }
                    if ($done == 2) {
                        $done = $done . "nd";
                    } elseif ($done == 3) {
                        $done = $done . "rd";
                    } else {
                        $done = $done . "th";
                    }
                    ?>
                    <section class="main">
                        <section class="right_main">
                            <div class="right_inner">
                                <div class="payment_box">
                                    <div class="payment_left view-area">
                                        <div class="payment_thankyou">
                                            <h2>Congratulations!</h2>
                                            <p>Your awesome app is up and ready.</p>
                    <?php if ($next_part_payment) { ?>
                                                <span>
                                                    Thank you for the prompt payment on your Instappy account. This represents the <?php echo $done; ?> instalment of amount <?php echo $currencyIcon . '' . $_SESSION['part_total_amount']; ?> on your plan, bringing your remaining balance to 
                                                        <?php echo $currencyIcon . '' .$left_amt;?> out of <?php echo $currencyIcon . '' . $app_amount; ?>. We will contact you again on <?php echo $next_part_payment; ?> when your next payment is due.</span>
                    <?php
                    } else {
                        ?>
                                                <span>Thank you for the prompt payment on your Instappy account. This represents the confirmation of <b>final</b> installment payment on your plan.</span>
                    <?php } ?>											
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>


                                </div>
                            </div>
                        </section>
                    </section>

                                            <?php
                                        }
                                    }
                                } else {
                                    ?>
            <section class="main">
                <section class="right_main">
                    <div class="right_inner">
                        <div class="payment_box">
                            <div class="container">
                                <div class="alert alert-success" style="margin-top:40px;font-size: 33px;width: 100%;">
                                    <center><h1><strong>Oops!</strong> Your transaction has been failed. .</h1></center>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </section>
            </section>
            <?php
        }
    } else {
        ?>
        <section class="main">
            <section class="right_main">
                <div class="right_inner">
                    <div class="payment_box">
                        <div class="container">
                            <div class="alert alert-success" style="margin-top:40px;font-size: 33px;width: 100%;">
                                <center><h1><strong>Oops!</strong> Your transaction has failed. .</h1></center>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </section>
        </section>
        <?php
        //    echo "<br>Security Error. Illegal access detected";
    }
    unset($_SESSION['source']);
} else if ($currency == 1) {
    include('Crypto.php');
    $workingKey = '011B4AB3FAF2FCCE3B0678D730048C42';  //Working Key should be provided here.
    $encResponse = $_POST["encResp"];   //This is the response sent by the CCAvenue Server
    $rcvdString = decrypt($encResponse, $workingKey);  //Crypto Decryption used as per the specified working key.

    function xyz($rcvdString) {
        $array = "";
        $returndata = "";
        $strArray = explode("&", $rcvdString);
        $i = 0;
        foreach ($strArray as $str) {
            $array = explode("=", $str);
            $returndata[$array[0]] = $array[1];
            $i = $i + 1;
        }
        return($returndata);
    }

    $datatrans = xyz($rcvdString);

    $orderstatus = isset($datatrans['order_status']) ? $datatrans['order_status'] : '';
    $tracking_id = $datatrans['tracking_id'];
    $order_id = $datatrans['order_id'];
    $totalamount = $datatrans['mer_amount'];
    $gatewaydiscount = $datatrans['discount_value'];
    //print_r($datatrans);
    /* 	$orderstatus = "Success";
      $tracking_id = "sxbjsd";
      $order_id =   1471415604;
      $totalamount = "4669.0";
      $gatewaydiscount = "0"; */
    $custid = $_SESSION['custid'];

    if (($orderstatus != '') && ($tracking_id != '') && ($order_id != '')) {
        if ($orderstatus === "Success") {
            require_once 'config/db.php';
            $db = new DB();
            $mysqli = $db->dbconnection();
            $service_tax = $_SESSION['service_tax'];
            $basicUrl = $db->siteurl();

            $sql6 = "SELECT * FROM master_payment WHERE payment_done!='1' AND order_id='$order_id' LIMIT 1";
            $stmt6 = $mysqli->prepare($sql6);
            $stmt6->execute();
            $results6 = $stmt6->fetch(PDO::FETCH_ASSOC);
            $promocode = $results6['promocodes_id'];
            $author_id = $results6['author_id'];
            $is_custom_promocode = $results6['is_custom_promocode'];
            $is_partpayment = $results6['is_partpayment'];
            if (is_array($results6)) {
                $count1 = count($results6);
            } else {
                $count1 = 0;
            }
            if ($count1 > 0) {


                $queryU = 'UPDATE master_payment set payment_gateway_type_id="1",payment_done="1", transaction_id="' . $tracking_id . '", transaction_date=NOW(),gateway_payment="' . $totalamount . '",gateway_discount="' . $gatewaydiscount . '",currency_type_id="1" WHERE order_id="' . $order_id . '"';
                $stmtU = $mysqli->prepare($queryU);

                if ($is_custom_promocode == 1) {
                    $query2 = 'UPDATE custom_promocodes set is_consumed="1"  WHERE id="' . $promocode . '" ';
                } else {
                $query2 = 'UPDATE promocodes set is_consumed="1"  WHERE id="' . $promocode . '" and author_id IN (select id from author where custid="' . $custid . '")';
                }
                $stmt2 = $mysqli->prepare($query2);
                $stmt2->execute();

                $appname = '';
                $count = 0;
//                $basetotal = 0;
                $currappbaseprice = 0;
                $order_items = '';
                $inclusiveTaxCheck='';
                if ($stmtU->execute()) {

                    $queryAP = 'UPDATE author_payment set payment_status="success", published_date=NOW(), payment_done="1" WHERE master_payment_id IN (SELECT id FROM master_payment WHERE order_id="' . $order_id . '")';
                    $stmtAP = $mysqli->prepare($queryAP);
                    if ($stmtAP->execute()) {

//                        $sql2 = "SELECT DISTINCT ap.app_id, ap.id,ap.plan_id FROM master_payment mp join author_payment ap on ap.master_payment_id=mp.id WHERE ap.payment_done='1' and mp.order_id='$order_id'";
//                        $stmt2 = $mysqli->prepare($sql2);
//                        $stmt2->execute();
//                        $results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);                        
//                        $sqlforApp = "SELECT DISTINCT ap.app_id, ap.id,ap.plan_id,apd.payment_type_value FROM master_payment mp JOIN author_payment ap ON ap.master_payment_id=mp.id JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE ap.payment_done='1' AND mp.order_id='$order_id' AND ap.plan_id IS NOT NULL";
                        $sqlforApp = "SELECT DISTINCT ap.app_id, ap.id,ap.plan_id FROM master_payment mp JOIN author_payment ap ON ap.master_payment_id=mp.id WHERE ap.payment_done='1' AND mp.order_id=$order_id AND ap.plan_id IS NOT NULL";
                        $stmt_APP = $mysqli->prepare($sqlforApp);
                        $stmt_APP->execute();
                        $results2 = $stmt_APP->fetchAll(PDO::FETCH_ASSOC);
                        $order_items_App='';
                        $basetotal_App=0;
                        $orderInvoiceHead ='<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0;border-left: 1px solid #a7a7a7;vertical-align: top;padding-top: 10px;padding-bottom: 10px;padding-left: 10px; "><p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Instappy Subscription Fee</p>
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Inclusions:</p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; "></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;"></td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; "></td>
</tr>';
                        if (!empty($results2)) {
                            $kk = 1;
                            foreach ($results2 as $key => $value) {
                                $sql5 = "SELECT ap.plan_id,ap.is_custom,pl.validity_in_days,ap.showMainPlan FROM author_payment ap join plans pl on pl.id=ap.plan_id WHERE ap.app_id='" . $value['app_id'] . "' AND ap.payment_done=1 AND ap.plan_id<>'' ORDER BY ap.id DESC LIMIT 1";
                                $stmt5 = $mysqli->prepare($sql5);
                                $stmt5->execute();
                                $results5 = $stmt5->fetch(PDO::FETCH_ASSOC);
                                $planID = $results5['plan_id'];
                                $plandays = $results5['validity_in_days'];
                                $showMainPlan = $results5['showMainPlan'];

                                if (function_exists('date_default_timezone_set')) {
                                    date_default_timezone_set('Asia/Calcutta');
                                    $currentdate = date('Y-m-d H:i:s');
                                }

                                $order_itemsAso='';
                                $order_itemsApp='';
                                $queryApp = 'SELECT * FROM app_data WHERE id="' . $value['app_id'] . '"';
                                $stmtApp = $mysqli->prepare($queryApp);
                                $stmtApp->execute();
                                $resultsApp = $stmtApp->fetch(PDO::FETCH_ASSOC);
                                $linkedAppId = $resultsApp['jump_to_app_id'];
                                    $linkto = $resultsApp['jump_to'];
                                if($showMainPlan==1){
                                if (strtotime($resultsApp['plan_expiry_date']) > strtotime($currentdate)) {
                                    $planexpiredatedays = date('Y-m-d H:i:s', strtotime('+' . $plandays . ' days', strtotime($resultsApp['plan_expiry_date'])));
                                } else {
                                    $planexpiredatedays = date('Y-m-d H:i:s', strtotime('+' . $plandays . ' days', strtotime($currentdate)));
                                }
                                } else{
                                   $planexpiredatedays= $resultsApp['plan_expiry_date'];
                                }

                                if ($resultsApp['analytics_code'] == NULL || $resultsApp['analytics_code'] == '') {
                                    $sql4 = "SELECT * FROM app_analytics_mapping WHERE app_id IS NULL LIMIT 1";
                                    $stmt4 = $mysqli->prepare($sql4);
                                    $stmt4->execute();
                                    $results4 = $stmt4->fetch(PDO::FETCH_ASSOC);
                                    $analyticsid = $results4['id'];

                                    $queryAD = 'UPDATE app_data set plan_id="' . $planID . '",plan_expiry_date="' . $planexpiredatedays . '",published="1",analytics_code="' . $analyticsid . '" WHERE id="' . $value['app_id'] . '"';
                                    $stmtAD = $mysqli->prepare($queryAD);
                                    $stmtAD->execute();

                                    $queryUapp = 'UPDATE app_analytics_mapping set app_id="' . $value['app_id'] . '" WHERE id="' . $analyticsid . '"';
                                    $stmtUapp = $mysqli->prepare($queryUapp);
                                    $stmtUapp->execute();
                                } else {
                                    $queryAD = 'UPDATE app_data set plan_id="' . $planID . '",plan_expiry_date="' . $planexpiredatedays . '",published="1" WHERE id="' . $value['app_id'] . '"';
                                    $stmtAD = $mysqli->prepare($queryAD);
                                    $stmtAD->execute();
                                }
                                if ($linkedAppId != 0 && $linkto == 1) {
                                        $queryAD = 'UPDATE app_data set plan_id="' . $planID . '",plan_expiry_date="' . $planexpiredatedays . '",published="1" WHERE id="' . $linkedAppId . '"';
                                        $stmtAD = $mysqli->prepare($queryAD);
                                        $stmtAD->execute();
                                    }
                                // part payment update;
                                    if ($is_partpayment == 1 && $kk == 1) {
                                    $sql_part = "SELECT * FROM master_payment_part WHERE  master_payment_id='" . $results6['id'] . "' LIMIT 1";
                                    $stmt_part = $mysqli->prepare($sql_part);
                                    $stmt_part->execute();
                                    $results_part = $stmt_part->fetch(PDO::FETCH_ASSOC);
                                    //custom plane check 
                                    $sql_planc = "SELECT is_custom FROM author_payment WHERE  master_payment_id='" . $results6['id'] . "' and plan_id IS NOT NULL LIMIT 1";
                                    $stmt_planc = $mysqli->prepare($sql_planc);
                                    $stmt_planc->execute();
                                    $results_planc = $stmt_planc->fetch(PDO::FETCH_ASSOC);
                                    if ($results_planc['is_custom'] == 1) {
                                        $sql_cplan = "SELECT id,validity_in_days FROM custom_plans WHERE  id='" . $planID . "' LIMIT 1";
                                    } else {
                                        $sql_cplan = "SELECT id,validity_in_days FROM plans WHERE  id in (select plan_id from author_payment WHERE  id='" . $planID . "') LIMIT 1";
                                    }

                                    $stmt_cplan = $mysqli->prepare($sql_cplan);
                                    $stmt_cplan->execute();
                                    $results_cplan = $stmt_cplan->fetch(PDO::FETCH_ASSOC);

                                    //plane ckeck end
//                                    $amount_paid = round($results_part['part_amount'], 2) + round($results_part['part_amount'] * $service_tax / 100, 2);
                                      $amount_paid = round($results_part['part_amount'], 2);
                                    $queryAP = 'UPDATE master_payment_part set  payment_done=1 ,updated_at=now() ,transaction_id="' . $tracking_id . '",transaction_date=NOW(),part_paid_amount="' . $amount_paid . '" WHERE master_payment_id=' . $results6['id'] . ' AND app_id=' . $results_part['app_id'] . ' AND payment_done=0 LIMIT 1';
                                    $stmtAP = $mysqli->prepare($queryAP);
                                    $stmtAP->execute();
                                    $maser_part_id = $results_part['id'];
                                    $maser_app_id = $results_part['app_id'];
                                    $ack_invoice->sendinvoice_performa(array('id' => $maser_part_id, 'app_id' => $maser_app_id));
                                    if ($results_part['next_paymentdate'] != '') {
                                        //$exp_date=date('Y-m-d H:i:s', strtotime($results_part['next_paymentdate'].'+ 15 day'));
                                        $exp_date = date('Y-m-d H:i:s', strtotime($results_part['next_paymentdate']));
                                        $publish_date = date('Y-m-d H:i:s');
                                        $queryAP_exp = 'UPDATE app_data set  plan_expiry_date="' . $exp_date . '",plan_id="' . $results_cplan['id'] . '" ,publish_date="' . $publish_date . '" where id= ' . $results_part['app_id'] . ' LIMIT 1';
                                        $stmtAP_exp = $mysqli->prepare($queryAP_exp);
                                        $stmtAP_exp->execute();
                                    }
                                }

                                if ($appname == '') {
                                    $appname = '<strong>"' . $resultsApp['summary'] . '"</strong>';
                                } else {
                                    $appname = $appname . ', <strong>"' . $resultsApp['summary'] . '"</strong>';
                                }

                                if ($results5['is_custom'] == 1) {
                                    $queryInv = 'SELECT * FROM custom_plans WHERE id="' . $planID . '" and deleted=0';
                                } else {
                                $queryInv = 'SELECT * FROM plans WHERE id="' . $planID . '" and deleted=0';
                                }
                                $stmtInv = $mysqli->prepare($queryInv);
                                $stmtInv->execute();
                                $resultsInv = $stmtInv->fetch(PDO::FETCH_ASSOC);
                                if (!empty($resultsInv)) {
                                    $years = ($resultsInv['validity_in_days'] / 365); // days / 365 days
                                    $years = floor($years); // Remove all decimals

                                    if ($years > 1) {
                                        $yyyyy = $years . ' Years';
                                    } else {
                                        $yyyyy = $years . ' Year';
                                    }

                                    /*
                                      $queryInv12 = 'SELECT a.id,mp.id AS masterpaymentid,mp.promocodes_id,a.plan_id, ad.amount AS breakup_some,a.total_amount AS plan_amount, a.platform, ad.payment_type_id FROM
                                      (
                                      SELECT id,author_id,master_payment_id,app_id,plan_id,payment_done,total_amount, platform FROM author_payment
                                      ) AS a
                                      JOIN author_payment_details ad ON a.id=ad.author_payment_id
                                      JOIN master_payment mp ON mp.id=a.master_payment_id
                                      WHERE a.author_id IN (select id from author where custid="'.$custid.'") AND a.payment_done=1 AND a.id = "'.$value['id'].'" AND ad.amount!=0';
                                     */
                                    $queryInv12 = 'SELECT a.id,mp.id as masterpaymentid,mp.promocodes_id,a.plan_id,SUM(ad.amount) AS breakup_some,a.total_amount AS plan_amount, a.platform,mp.showService_tax,a.crm_discount,a.app_customization,
											GROUP_CONCAT( 
											CASE 
											WHEN ad.payment_type_id = 1 && ad.amount != 0 THEN 1
											WHEN ad.payment_type_id = 2 && ad.amount != 0 THEN 2 
											ELSE 0
											END) as payid FROM
											(
											SELECT id,author_id,master_payment_id,app_id,plan_id,payment_done,total_amount,platform,app_customization,crm_discount FROM author_payment
											) AS a
											LEFT JOIN author_payment_details ad ON a.id=ad.author_payment_id
											LEFT JOIN master_payment mp ON mp.id=a.master_payment_id
											WHERE a.author_id IN (select id from author where custid="' . $custid . '") AND a.payment_done=1 AND a.id="' . $value['id'] . '"
											GROUP BY a.id';
                                    $stmtInv12 = $mysqli->prepare($queryInv12);
                                    $stmtInv12->execute();
                                    $resultsInv12 = $stmtInv12->fetch(PDO::FETCH_ASSOC);
                                    $inclusiveTaxCheck = $resultsInv12['showService_tax'];
                                    $crm_discount = $resultsInv12['crm_discount'];
                                    // app icon and spash screen price start here 
                                    $sql_apd = 'SELECT payment_type_id,payment_type_value,is_custom FROM author_payment_details WHERE author_payment_id="' . $value['id'] . '"';
                                    $stmt_apd = $mysqli->prepare($sql_apd);
                                    $stmt_apd->execute();
                                    $results_apd = $stmt_apd->fetchAll(PDO::FETCH_ASSOC);
                                    if ($results_apd[0]['payment_type_id'] == 1)
                                        $icon_id = $results_apd[0]['payment_type_value'];
                                    if ($results_apd[1]['payment_type_id'] == 2)
                                        $splash_id = $results_apd[1]['payment_type_value'];
                                    if ($results_apd[0]['is_custom'] == 2) {
                                        $sql_icon = 'SELECT price,price_in_usd FROM custom_icons WHERE id="' . $icon_id . '"';
                                    } else {
                                        $sql_icon = 'SELECT price,price_in_usd FROM default_icons WHERE id="' . $icon_id . '"';
                                    }
                                    if ($results_apd[1]['is_custom'] == 2) {
                                        $sql_splash = 'SELECT price,price_in_usd FROM custom_splashscreen WHERE id="' . $splash_id . '"';
                                    } else {
                                        $sql_splash = 'SELECT price,price_in_usd FROM default_splash_screen WHERE id="' . $splash_id . '"';
                                    }
                                    $stmt_icons = $mysqli->prepare($sql_icon);
                                    $stmt_icons->execute();
                                    $results_icons = $stmt_icons->fetch(PDO::FETCH_ASSOC);


                                    $stmt_splash = $mysqli->prepare($sql_splash);
                                    $stmt_splash->execute();
                                    $results_splash = $stmt_splash->fetch(PDO::FETCH_ASSOC);

                                    $icon_splash_price = $results_icons['price'] + $results_splash['price'];
                                    if ($crm_discount > 0) {
                                        $icon_splash_price = $icon_splash_price - ($icon_splash_price * $crm_discount / 100);
                                    }
                                    // app icon and spash screen price start here 

                                    $p_platform = '';
                                    if (!empty($resultsInv12)) {
                                        $app__type = explode(',', $resultsInv12['payid']);
                                        $currappbaseprice = $icon_splash_price + $resultsInv12['plan_amount'];


                                        $queryInv1 = 'SELECT * FROM platform WHERE id="' . $resultsInv12['platform'] . '"';
                                        $stmtInv1 = $mysqli->prepare($queryInv1);
                                        $stmtInv1->execute();
                                        $resultsInv1 = $stmtInv1->fetch(PDO::FETCH_ASSOC);

                                        if (!empty($resultsInv1)) {
                                            if ($resultsInv1['name'] == 'Cross Platform') {
                                                $p_platform = 'Android + iOS';
                                            } else {
                                                $p_platform = $resultsInv1['name'];
                                            }
                                        }

                                        $queryInv123 = 'SELECT * FROM app_type WHERE id="' . $resultsApp['type_app'] . '"';
                                        $stmtInv123 = $mysqli->prepare($queryInv123);
                                        $stmtInv123->execute();
                                        $resultsInv123 = $stmtInv123->fetch(PDO::FETCH_ASSOC);

                                        $my_app_type = '';
                                        if (!empty($resultsInv123)) {
                                            $my_app_type = $resultsInv123['name'];
                                        }
                                        if ($resultsInv12['app_customization'] == 1) {
                                            $planName = $resultsInv['plan_name'] . "+ Customize";
                                        } else {
                                            $planName = $resultsInv['plan_name'];
                                        }
$order_itemsApp.='<tr><td style=" width: 250px;border-right: 1px solid #e0e0e0; border-left: 1px solid #a7a7a7;vertical-align: top; padding-top: 10px;padding-bottom: 10px; ">
																						
																						
																						
																						
					  
                                            <table cellpadding="0" cellspacing="0" style="padding-left: 10px;">
							<tr>
								<td>
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td style="line-height: 18px;">
												<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $resultsApp['summary'] . '</p>
							<p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">' . $my_app_type . '</p>
							<p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">OS <b>' . $p_platform . '</b></p>
							<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Package <b>' . $planName . '</b></p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="line-height: 22px;">';
                                        if ($app__type[0] > 0 && $app__type[1] > 0) {
                                            $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">App Icon</p>';
                                            $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">Splash Screen</p>';
                                        } else {
                                            if ($app__type[0] > 0 && $app__type[1] == 0) {
                                                $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">App Icon</p>';
                                            } elseif ($app__type[0] == 0 && $app__type[1] > 0) {
                                                $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">Splash Screen</p>';
                                            }
                                        }
                                        $order_itemsApp .= '</td></tr></table></td>
                                        <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; ">
                                        <p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">1</p></td>
                                        <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;">
                                        <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $yyyyy . ' </p>
                                        </td><td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; ">
                                        <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $currencyIcon . ' ' . $currappbaseprice . '</p>
                                        </td></tr>';
                                        }
                                    }
                                $order_items_App .= $order_itemsApp;
                                $basetotal_App += $currappbaseprice;
                                $count++;
                                $kk++;
                            }
                        }
                        $sqlforAso = "SELECT apd.payment_type_value,ap.crm_discount FROM master_payment mp JOIN author_payment ap ON ap.master_payment_id=mp.id JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE ap.payment_done='1' AND mp.order_id='$order_id' AND ap.plan_id IS NULL GROUP BY apd.payment_type_value";
                        $stmt_Aso = $mysqli->prepare($sqlforAso);
                        $stmt_Aso->execute();
                        $resultsASo = $stmt_Aso->fetchAll(PDO::FETCH_ASSOC);
                        $order_items_Aso='';
                        $orderDetailsAsohead='';
                        $basetotal_Aso=0;
                        if (!empty($resultsASo)) {
                            $asoexsit='';
                            $asoAppexsit ='';
                            if(count($resultsASo)>1){
                                $asoexsit='s';
                            } 
                            $orderDetailsAsohead = '<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0;border-left: 1px solid #a7a7a7;vertical-align: top;padding-top: 10px;padding-bottom: 10px;padding-left: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;"><strong>ASO Package'.$asoexsit.':<strong></p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; "></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;"></td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; "></td>
</tr>';
                            foreach ($resultsASo as $key => $value) {
                                $crm_discount = $value['crm_discount'];
                                $sqlApd = "SELECT ap.app_id,ap.crm_discount,apd.* FROM author_payment ap LEFT JOIN master_payment mp ON mp.id=ap.master_payment_id LEFT JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE mp.id = (SELECT id FROM master_payment WHERE order_id=$order_id) AND apd.payment_type_value=" . $value['payment_type_value'];
                                    $stmtApd = $mysqli->prepare($sqlApd);
                                    $stmtApd->execute();
                                    $resultsApd = $stmtApd->fetchAll(PDO::FETCH_ASSOC);
                                    if(!empty($resultsApd)){
                                        $asoname='';
                                        $aso_appname='';
                                        $isCustom='';
                                        $currappbaseprice='';
                                        $order_itemsAso='';
                                        $asoexsit='';
                                        $asO_count = count($resultsApd);
                            if($asO_count>1){
                                $asoAppexsit='s';
                            } 
                            $quantity=$asO_count;
                                    foreach ($resultsApd as $keyAso => $valueAso) {
                                    $asoid = $valueAso['payment_type_value'];
                                    $isCustom = $valueAso['is_custom'];
                                    if ($isCustom == 1) {
                                        $sql_aso = 'SELECT name,discounted_amount,price_in_usd FROM custom_marketing_packages WHERE id="' . $asoid . '"';
                                    } else {
                                        $sql_aso = 'SELECT name,discounted_amount,price_in_usd FROM marketing_packages WHERE id="' . $asoid . '"';
                                    }
                                    $stmt_aso = $mysqli->prepare($sql_aso);
                                    $stmt_aso->execute();
                                    $results_aso = $stmt_aso->fetch(PDO::FETCH_ASSOC);
                                    if($asoname==''){
                                        $asoname = $results_aso['name'] . ',';
                                    } else if($asoname!='' && $valueAso['is_custom'] != $isCustom){
                                         $asoname = $results_aso['name'] . ',';
                                    } 
                                        $sql_asoApp = 'SELECT summary FROM app_data WHERE id=' . $valueAso['app_id'];
                                        $stmt_asoApp = $mysqli->prepare($sql_asoApp);
                                    $stmt_asoApp->execute();
                                    $results_asoAppName = $stmt_asoApp->fetch(PDO::FETCH_ASSOC);
                                    $aso_appname .= $results_asoAppName['summary'] . ',';
                                        if ($crm_discount > 0) {
                                            $currappbaseprice += $results_aso['discounted_amount'] - ($results_aso['discounted_amount'] * $crm_discount / 100);
                                        } else {
                                        $currappbaseprice += $results_aso['discounted_amount'];                                        
                                        }
                                    }
                                    $order_itemsAso ='<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0; border-left: 1px solid #a7a7a7;vertical-align: top; padding-top: 10px;padding-bottom: 10px; ">
<table cellpadding="0" cellspacing="0" style="padding-left: 10px;">
<tr>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td style="line-height: 18px;">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . substr($asoname,0,-1) . '</p>							
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">App'.$asoAppexsit.':'.substr($aso_appname,0,-1).'</p>
</td>
</tr>
																													</table>
																                            </td>
</tr>
</table>
</td>
																							
																                            <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">'.$quantity.'</p></td>
																                            <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;  ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">One Time Package</p>
																                            </td>
																                            <td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; ">
					                            									<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $currencyIcon . ' ' . $currappbaseprice . '</p>
																                            </td>
																                        </tr>
														';

                                    }
                                $order_items_Aso .= $order_itemsAso;
                                $basetotal_Aso += $currappbaseprice;

                                $count++;
                            }
                        }
                    }
                }
                if($order_items_Aso!=''){
                    $order_items = $orderInvoiceHead.$order_items_App.$orderDetailsAsohead.$order_items_Aso;
                } else{
                    $order_items = $orderInvoiceHead.$order_items_App;
                }
                $basetotal = $basetotal_App+$basetotal_Aso;


                if ($count > 1) {
                    $app_is = 'apps are';
                } else {
                    $app_is = 'app is';
                }


                $sql = "select * from author where custid='$custid'";
                $stmt = $mysqli->prepare($sql);
                $stmt->execute();
                $results = $stmt->fetch(PDO::FETCH_ASSOC);

                $queryDis = 'SELECT * FROM master_payment WHERE order_id="' . $order_id . '"';
                $stmtDis = $mysqli->prepare($queryDis);
                $stmtDis->execute();
                $resultsDis = $stmtDis->fetch(PDO::FETCH_ASSOC);

                if (!empty($resultsDis) && $resultsDis['promocodes_id'] != '') {
                    $promocode = $resultsDis['promocodes_id'];
                    if ($is_custom_promocode == 1) {
                        $discountamount = $resultsDis['discount'];
                        $discount = $discountamount;
                    } else {
                    $sqlmp = "SELECT * FROM promocodes WHERE id='$promocode'";
                    $appMP = $mysqli->prepare($sqlmp);
                    $appMP->execute();
                    $appMPid = $appMP->fetch(PDO::FETCH_ASSOC);
                    $discountamount = $appMPid['percentage_discount'];
                    $discount = round($basetotal * ($discountamount / 100), 2);
                    }
                } else {
                    $discount = '0.00';
                }

                //                $discount = round($basetotal * ($discountamount / 100), 2);
                $subtotal = round($basetotal - $discount, 2);
                if ($currency == 1) {
                $servicetax = round($subtotal * $service_tax / 100, 2);
                $order_total = round($resultsDis['total_amount'], 2);
                } else{
                    $servicetax=0;
                    $order_total=$subtotal;
                }

                $csubject = 'Congratulations! Your app is now in QA!';
                //$basicUrl = $this->url;
                $chtmlcontent = file_get_contents('edm-new/app_qa.php');
                $clastname = $results['last_name'] != '' ? ' ' . $results['last_name'] : $results['last_name'];
                $cname = ucwords($results['first_name'] . $clastname);
                $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                $chtmlcontent = str_replace('{custid}', $custid, $chtmlcontent);
                $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                $chtmlcontent = str_replace('{app_name}', $appname, $chtmlcontent);
                $chtmlcontent = str_replace('{app_is}', $app_is, $chtmlcontent);

                $cto = $results['email_address'];
                $cformemail = 'noreply@instappy.com';
                $key = 'f894535ddf80bb745fc15e47e42a595e';
                //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
                //$customerhead = file_get_contents($url);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => array(
                        'api_key' => $key,
                        'subject' => $csubject,
                        'fromname' => 'Instappy',
                        'from' => $cformemail,
                        'content' => $chtmlcontent,
                        'recipients' => $cto
                    )
                ));
                $customerhead = curl_exec($curl);

                curl_close($curl);
                $serviceTaxhtml = '';
                    $payment_part='';
//                $inclusiveTaxCheck = $resultsInv12['showService_tax'];
                if ($currency == 2) {
        $inclusiveTax = '<p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Subtotal(inclusive of all taxes): </p>';
        $inclusiveTax2 = '<p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($subtotal, 2) . '</p>';
    } else {
        $inclusiveTax = '<p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Subtotal: </p><p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Service Tax (15%): </p>';
        $inclusiveTax2 = '<p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($subtotal, 2) . '</p><p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($servicetax, 2) . '</p>';
    }
                $paymentgateway = 1;

                $planimg = "market_packages1.png";
                // invoice email
                $csubject = 'Instappy Invoice for order ' . $order_id;
                //$basicUrl = $this->url;
                $chtmlcontent = file_get_contents('edm-new/invoice-website.php');
                $clastname = $results['last_name'] != '' ? ' ' . $results['last_name'] : $results['last_name'];
                $cname = ucwords($results['first_name'] . $clastname);
                if (trim($cname) == '') {
                    $cname = 'Hi';
                } else {
                    $cname = 'Dear ' . $cname;
                }
                $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                $chtmlcontent = str_replace('{address}', $resultsDis['address'] . ',' . $resultsDis['city'], $chtmlcontent);
                $chtmlcontent = str_replace('{state}', $resultsDis['state'], $chtmlcontent);
                $chtmlcontent = str_replace('{order_no}', $order_id, $chtmlcontent);
                $chtmlcontent = str_replace('{custid}', $custid, $chtmlcontent);
//    $chtmlcontent = str_replace('{app_details_summary}', $resultsApp['summary'], $chtmlcontent);
//    $chtmlcontent = str_replace('{my_app_type}', $my_app_type, $chtmlcontent);
//    $chtmlcontent = str_replace('{p_platform}', $p_platform, $chtmlcontent);
//    $chtmlcontent = str_replace('{plan_title}', $planName, $chtmlcontent);
//    $chtmlcontent = str_replace('{yyyyy}', $yyyyy, $chtmlcontent);
//    $chtmlcontent = str_replace('{currencyIconPrice}', ($currencyIcon . ' ' . $currappbaseprice), $chtmlcontent);
//    $chtmlcontent = str_replace('{payment_part}', $payment_part, $chtmlcontent);
                $chtmlcontent = str_replace('{order_items}', $order_items, $chtmlcontent);
                $chtmlcontent = str_replace('{discount}', round($discount, 2), $chtmlcontent);
//                $chtmlcontent = str_replace('{subtotal}', round($subtotal, 2), $chtmlcontent);
//                $chtmlcontent = str_replace('{tax}', $servicetax, $chtmlcontent);
                $chtmlcontent = str_replace('{order_total}', round($order_total, 2), $chtmlcontent);
                $chtmlcontent = str_replace('{currentdate}', date('d-m-Y', time()), $chtmlcontent);
                $chtmlcontent = str_replace('{gateway}', $paymentgateway, $chtmlcontent);
                $chtmlcontent = str_replace('{currency}', $currencyIcon, $chtmlcontent);
                $chtmlcontent = str_replace('{service_tax}', $service_tax, $chtmlcontent);
                $chtmlcontent = str_replace('{planimg}', $planimg, $chtmlcontent);
    $chtmlcontent = str_replace('{inclusiveTax}', $inclusiveTax, $chtmlcontent);
    $chtmlcontent = str_replace('{inclusiveTax2}', $inclusiveTax2, $chtmlcontent);

                $string = getcwd();

                $pathdir = $string . '/pdf/' . $author_id;
                $directory = $pathdir;
                if (!file_exists($directory)) {
                    mkdir($string . '/pdf/' . $author_id, 0777, true);
                }

                // make pdf                
                $file = fopen("convertTopdf.php", "w");
                fwrite($file, $chtmlcontent);
                fclose($file);
                $pdfname = $order_id . '-invoice.pdf';
                ob_start();
                ob_get_clean();

                include('mpdf60/mpdf.php');
                $mpdf = new mPDF('c', 'A4', '', '', 10, 10, 10, 10);
                $mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("Instappy.com - Invoice");
                $mpdf->SetAuthor("Instappy.com");
                $mpdf->SetWatermarkText("Paid");
                $mpdf->showWatermarkText = true;
                $mpdf->watermark_font = 'DejaVuSansCondensed';
                $mpdf->watermarkTextAlpha = 0.1;
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->WriteHTML(file_get_contents("convertTopdf.php"));
                $mpdf->Output($directory . '/' . $pdfname, 'F');

                $queryUInvoice = 'UPDATE master_payment set invoice_link="' . $pdfname . '" WHERE order_id="' . $order_id . '"';
                $stmtUInvoice = $mysqli->prepare($queryUInvoice);
                $stmtUInvoice->execute();

                $cbcc = 'admin@instappy.com';
                $cto = $results['email_address'];
                $cformemail = 'noreply@instappy.com';
                $key = 'f894535ddf80bb745fc15e47e42a595e';
                //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
                //$customerhead = file_get_contents($url);
                if ($is_partpayment) {
                    
                } else {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => array(
                            'api_key' => $key,
                            'subject' => $csubject,
                            'fromname' => 'Instappy',
                            'from' => $cformemail,
                            'content' => $chtmlcontent,
                            'recipients' => $cto,
                            'bcc' => $cbcc
                        )
                    ));
                    $customerhead = curl_exec($curl);

                    curl_close($curl);
                }
                if (isset($_SESSION['source'])) {
                    if ($_SESSION['source'] == "LinkConnector") {
                        ?>
                        <!-- Start UTS Confirm Code -->
                        <!-- Generic Cart Cart Variables -->
                        <script type="text/javascript">
                                            var uts_orderid = '<?php echo $order_id; ?>';
                                            var uts_saleamount = '<?php echo $totalamount; ?>';
                                            var uts_coupon = '';
                                            var uts_discount = '<?php echo $discount; ?>';
                                            var uts_currency = 'INR';
                                            var uts_eventid = '3863';
                        </script>
                        <script type="text/javascript" src="//www.linkconnector.com/uts_tm.php?cgid=900558"></script>
                        <!-- End UTS Confirm Code -->
                    <?php
                    }
                }
                ?>
                <section class="main">
                    <section class="right_main">
                        <div class="right_inner">
                            <div class="bannertitle">
                                <h2 style="font-size:16px;line-height:28px">You have qualified for multiple money saving offers. <br/>We have automatically applied the best offers for you giving you the lowest price.</h2>
                                <p style="margin-top:10px">Apps built using the Instappy.com are packed with features that are built for your success.
                                    Instappy apps are <span>instant, affordable, stunning, intuitive</span> and will <span>change the way you interact 
                                        with your customers.</span> Our business model allows us to provide everyone with world-class, feature-packed 
                                    applications on Android and iOS for a fraction of the cost it takes to develop an app otherwise.</p>


                            </div>
                            <div class="payment_steps">
                                <ul>
                                    <li class="done">
                                        <em>1</em>
                                        <span>Cart</span>
                                        <hr noshade>
                                    </li>
                                    <li class="done">
                                        <em>2</em>
                                        <span>Billing &amp; Payment</span>
                                        <hr noshade>
                                    </li>
                                    <li class="done">
                                        <em>3</em>
                                        <span>Place	Your Order</span>
                                        <hr noshade>
                                    </li>
                                    <li class="active">
                                        <em>4</em>
                                        <span>Finalise</span>
                                        <hr noshade>
                                    </li>
                                </ul>
                            </div>
                            <div class="clear"></div>
                            <div class="payment_box">
                                <div class="payment_left view-area">
                                    <div class="payment_thankyou">
                                        <h2>Congratulations!</h2>
                                        <p>Your awesome app is up and ready.</p>
                                        <span>We know, you can't wait to launch your brand new app for the world to see. You just have to wait a little bit more! Your app is under review and QA so that we can ensure everything is in its rightful place and your customers get truly fantastic experience every time they engage with your app. Your app APK will be available for download ideally in 2 business days.</span>
                                        <div class="thankyou_publishing_app">
                                            <h3>Would you like us to help you publish your app to the store?</h3>
                                            <input type="radio" id="publish1" name="publishapp" checked="checked">
                                            <label for="publish1" class="optn1">I have an account. I can manage my way through.</label>
                                            <div class="clear"></div>
                                            <input type="radio" id="publish2" name="publishapp">
                                            <label for="publish2" class="optn2">Yes, I think I need help from here onwards</label>
                                            <div class="clear"></div>
                                            <div class="inner_radio" style="display:none;">
                                                <input type="radio" id="inpublish1" name="publishappin" value="" checked="checked">
                                                <label for="inpublish1">I am publishing an Android app to Play Store.</label>
                                                <div class="clear"></div>
                                                <input type="radio" id="inpublish2" name="publishappin" value="" >
                                                <label for="inpublish2">I am publishing an iOS app to App Store.</label>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <a href="javascript:void(0);" class="make_app_next">Finish</a>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="payment_bottom">
                                    <p>Meanwhile, it is a good idea to get the App Store Optimisation (ASO) in place, so that the moment your app is launched it shows up the ranks.</p>
                                    <p>Need help? <a href="applisting.php">Click here</a> for some great tips on ASO.</p>
                                </div>
                                <div class="clear"></div>
                            </div>

                        </div>
                    </section>
                </section>
                <?php
            } else {
                //$order_id=1471415604;
                $sql6 = "SELECT * FROM master_payment WHERE payment_done=1 and order_id='$order_id' LIMIT 1";
                $stmt6 = $mysqli->prepare($sql6);
                $stmt6->execute();
                $results6 = $stmt6->fetch(PDO::FETCH_ASSOC);
                $promocode = $results6['promocodes_id'];
                $author_id = $results6['author_id'];
                $is_partpayment = $results6['is_partpayment'];
                // part payment update;
                if ($is_partpayment == 1 && ($_SESSION['part_payment_id'] > 0)) {
                    $sql_part = "SELECT * FROM master_payment_part WHERE  id='" . $_SESSION['part_payment_id'] . "' LIMIT 1";
                    $stmt_part = $mysqli->prepare($sql_part);
                    $stmt_part->execute();
                    $results_part = $stmt_part->fetch(PDO::FETCH_ASSOC);



                    //custom plane check 
                    $sql_planc = "SELECT * FROM author_payment WHERE  master_payment_id='" . $results6['id'] . "' and plan_id IS NOT NULL LIMIT 1";
                    $stmt_planc = $mysqli->prepare($sql_planc);
                    $stmt_planc->execute();
                    $results_planc = $stmt_planc->fetch(PDO::FETCH_ASSOC);

                    $sql_planAso = "SELECT apd.payment_type_value FROM master_payment mp JOIN author_payment ap ON ap.master_payment_id=mp.id JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE ap.payment_done='1' AND mp.id=".$results6['id']." AND ap.plan_id IS NULL GROUP BY apd.payment_type_value";
                    $stmt_planAso = $mysqli->prepare($sql_planAso);
                    $stmt_planAso->execute();
                    $results_planAso = $stmt_planAso->fetchAll(PDO::FETCH_ASSOC); 
                    // for platform 
                    $sql_plat = "SELECT * FROM platform WHERE  id='" . $results_planc['platform'] . "'  LIMIT 1";
                    $stmt_plat = $mysqli->prepare($sql_plat);
                    $stmt_plat->execute();
                    $results_platform = $stmt_plat->fetch(PDO::FETCH_ASSOC);

                    // for currency

                    $sql_curr = "SELECT * FROM currency_type WHERE  id='" . $results6['currency_type_id'] . "'  LIMIT 1";
                    $stmt_curr = $mysqli->prepare($sql_curr);
                    $stmt_curr->execute();
                    $results_curr = $stmt_curr->fetch(PDO::FETCH_ASSOC);


                    // for author payment details

                    $sql_apd = 'SELECT a.id,mp.id as masterpaymentid,mp.promocodes_id,a.plan_id,SUM(ad.amount) AS breakup_some,a.total_amount AS plan_amount, a.platform,,mp.showService_tax,a.showMainPlan,
											GROUP_CONCAT( 
											CASE 
											WHEN ad.payment_type_id = 1 && ad.amount != 0 THEN 1
											WHEN ad.payment_type_id = 2 && ad.amount != 0 THEN 2 
											ELSE 0
											END) as payid FROM
											(
											SELECT id,author_id,master_payment_id,app_id,plan_id,payment_done,total_amount,platform,showMainPlan FROM author_payment
											) AS a
											LEFT JOIN author_payment_details ad ON a.id=ad.author_payment_id
											LEFT JOIN master_payment mp ON mp.id=a.master_payment_id
											WHERE a.author_id =' . $results6['author_id'] . ' AND a.payment_done=1 AND a.id="' . $results_planc['id'] . '"
											GROUP BY a.id';
                    $stmt_apd = $mysqli->prepare($sql_apd);
                    $stmt_apd->execute();
                                    $results_apd = $stmt_apd->fetch(PDO::FETCH_ASSOC);
//                    $sql_apd = 'SELECT * FROM author_payment_details WHERE author_payment_id="' . $results_planc['id'] . '"';
//                    $stmt_apd = $mysqli->prepare($sql_apd);
//                    $stmt_apd->execute();
//                    $results_apd = $stmt_apd->fetchAll(PDO::FETCH_ASSOC);
                    if ($results_planc['is_custom'] == 1) {
                        $sql_cplan = "SELECT id,validity_in_days,plan_name FROM custom_plans WHERE  app_id='" . $results_part['app_id'] . "' LIMIT 1";
                    } else {
                        $sql_cplan = "SELECT id,validity_in_days,plan_name FROM plans WHERE  id in (select plan_id from author_payment WHERE  app_id='" . $results_part['app_id'] . "') LIMIT 1";
                    }

                    $stmt_cplan = $mysqli->prepare($sql_cplan);
                    $stmt_cplan->execute();
                    $results_cplan = $stmt_cplan->fetch(PDO::FETCH_ASSOC);



                    $left_amount = round($results6['part_amount_left'], 2) - round($results_part['part_amount'], 2);
                    $queryAP_mp = 'UPDATE master_payment set  part_amount_left="' . $left_amount . '"  where id= "' . $results6['id'] . '" LIMIT 1';
                    $stmtAP_mp = $mysqli->prepare($queryAP_mp);
                    $stmtAP_mp->execute();

                    // update master payment
                    $queryAP = 'UPDATE master_payment_part set  payment_done=1 ,updated_at=now() ,transaction_id="' . $tracking_id . '",transaction_date=NOW(),part_paid_amount="' . $_SESSION['part_total_amount'] . '" WHERE master_payment_id=' . $results6['id'] . ' AND app_id=' . $results_part['app_id'] . ' AND payment_done=0 LIMIT 1';
                    $stmtAP = $mysqli->prepare($queryAP);
                    $stmtAP->execute();
                    $maser_part_id = $results_part['id'];
                    $maser_app_id = $results_part['app_id'];
                    $queryApp = 'SELECT * FROM app_data WHERE id="' . $maser_app_id . '"';
                    $stmtApp = $mysqli->prepare($queryApp);
                    $stmtApp->execute();
                    $resultsApp = $stmtApp->fetch(PDO::FETCH_ASSOC);
                    $ack_invoice->sendinvoice_performa(array('id' => $maser_part_id, 'app_id' => $maser_app_id));
                    if ($results_part['next_paymentdate'] != '' && $resultsApp['plan_expiry_date']<$results_part['next_paymentdate']) {
                        //$exp_date=date('Y-m-d H:i:s', strtotime($results_part['next_paymentdate'].'+ 15 day'));
                        $exp_date = date('Y-m-d H:i:s', strtotime($results_part['next_paymentdate']));
                    } else {
                        if($results_apd['showMainPlan']==1){
                        $exp_date = date('Y-m-d H:i:s', strtotime($results6['transaction_date'] . '+ ' . $results_cplan['validity_in_days'] . ' day'));
                        }
                    }
                    $planExp='';
                    if($exp_date!=''){
                        $planExp = "plan_expiry_date='$exp_date',";
                    }
                    $publish_date = date('Y-m-d H:i:s');
                    $queryAP_exp = 'UPDATE app_data set  ' . $planExp . ' plan_id="' . $results_cplan['id'] . '" ,publish_date="' . $publish_date . '" where id= ' . $results_part['app_id'] . ' LIMIT 1';
                    $stmtAP_exp = $mysqli->prepare($queryAP_exp);
                    $stmtAP_exp->execute();

                    // send invoice for part payment

                    $queryDis = 'SELECT * FROM master_payment WHERE order_id="' . $order_id . '"';
                    $stmtDis = $mysqli->prepare($queryDis);
                    $stmtDis->execute();
                    $resultsDis = $stmtDis->fetch(PDO::FETCH_ASSOC);


                    //

                    $sql = 'SELECT sum(part_amount) as total  FROM master_payment_part WHERE app_id="' . $maser_app_id . '" and payment_done=1 and master_payment_id=' . $results6['id'];
                    $sts = $mysqli->prepare($sql);
                    $sts->execute();
                    $results_part_all = $sts->fetch(PDO::FETCH_ASSOC);

                    $order_items = '';
                    $order_itemsApp='';

                    $results_pm = $ack_invoice->part_payment_details($maser_app_id);

                    if (!empty($results_cplan)) {
                        $years = ($results_cplan['validity_in_days'] / 365); // days / 365 days
                        $years = floor($years); // Remove all decimals

                        if ($years > 1) {
                            $yyyyy = $years . ' Years';
                        } else {
                            $yyyyy = $years . ' Year';
                        }
                    }
//                    $queryApp = 'SELECT * FROM app_data WHERE id="' . $maser_app_id . '"';
//                    $stmtApp = $mysqli->prepare($queryApp);
//                    $stmtApp->execute();
//                    $resultsApp = $stmtApp->fetch(PDO::FETCH_ASSOC);
                    $queryInv123 = 'SELECT * FROM app_type WHERE id="' . $resultsApp['type_app'] . '"';
                    $stmtInv123 = $mysqli->prepare($queryInv123);
                    $stmtInv123->execute();
                    $resultsInv123 = $stmtInv123->fetch(PDO::FETCH_ASSOC);

                    $my_app_type = '';
                    $orderInvoiceHead ='<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0;border-left: 1px solid #a7a7a7;vertical-align: top;padding-top: 10px;padding-bottom: 10px;padding-left: 10px; "><p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Instappy Subscription Fee</p>
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Inclusions:</p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; "></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;"></td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; "></td>
</tr>';
                    if (!empty($resultsInv123)) {
                        $my_app_type = $resultsInv123['name'];
                    }

                    $order_itemsApp .= '<tr>
		<td align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:5px 10px; border-left:1px solid #a7a7a7; border-right:1px solid #dbdbdb  ; color:#323232  ;">
																						
																						
																						
					  
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">"' . $resultsApp['summary'] . '"</p>
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">"' . $my_app_type . '"</p>
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">OS <b>' . $results_platform['name'] . '</b></p>
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">Package <b>' . $results_cplan['plan_name'] . '</b></p>';
//                    if ($results_apd[0]['id'] > 0 && $results_apd[1]['id'] > 0) {
//                        $order_itemsApp .= '<p style="margin:10px 0 0 20px;">App Icon</p>';
//                        $order_itemsApp .= '<p style="margin:10px 0 0 20px;">Splash Screen</p>';
//                    } else {
//                        if ($results_apd[0]['id'] > 0 && $results_apd[1]['id'] == 0) {
//                            $order_itemsApp .= '<p style="margin:10px 0 0 20px;">App Icon</p>';
//                        } elseif ($results_apd[0]['id'] == 0 && $results_apd[1]['id'] > 0) {
//                            $order_itemsApp .= '<p style="margin:10px 0 0 20px;">Splash Screen</p>';
//                        }
//                    }
                    $app__type = explode(',', $results_apd['payid']);
if ($app__type[0] > 0 && $app__type[1] > 0) {
                                                $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">App Icon</p>';
                                                $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">Splash Screen</p>';
                    } else {
                                                if ($app__type[0] > 0 && $app__type[1] == 0) {
                                                    $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">App Icon</p>';
                                                } elseif ($app__type[0] == 0 && $app__type[1] > 0) {
                                                    $order_itemsApp .= '<p style="margin:10px 0 0 20px; font-family:Arial; font-size:12px;">Splash Screen</p>';
                        }
                    }
                    $order_itemsApp .= '</td>
                    <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; ">
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">1</p></td>
                    <td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;">
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $yyyyy . '</p>
                    </td><td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; ">
                    <p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . $results_curr['Name'] . ' ' . round($results_part_all['total'], 2) . '</p>
                    </td></tr>';
                    $order_items_App = $order_itemsApp;
                    $order_items_Aso='';
                        $orderDetailsAsohead='';
                    if(!empty($results_planAso)){
                        $asoAppexsit='';
                        $orderDetailsAsohead = '<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0;border-left: 1px solid #a7a7a7;vertical-align: top;padding-top: 10px;padding-bottom: 10px;padding-left: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;"><strong>ASO Package:<strong></p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; "></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;"></td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; "></td>
												</tr>';
                            foreach ($results_planAso as $key => $value) {
                                    $sqlApd = "SELECT ap.app_id,apd.* FROM author_payment ap LEFT JOIN master_payment mp ON mp.id=ap.master_payment_id LEFT JOIN author_payment_details apd ON apd.author_payment_id=ap.id WHERE mp.id = (SELECT id FROM master_payment WHERE order_id=$order_id) AND apd.payment_type_value=".$value['payment_type_value'];
                                    $stmtApd = $mysqli->prepare($sqlApd);
                                    $stmtApd->execute();
                                    $resultsApd = $stmtApd->fetchAll(PDO::FETCH_ASSOC);
                                    if(!empty($resultsApd)){
                                        $asoname='';
                                        $aso_appname='';
                                        $isCustom='';
                                        $currappbaseprice='';
                                        $order_itemsAso='';
                                        $asoexsit='';
                            $quantity = 1;
                            foreach ($resultsApd as $keyAso => $valueAso) {
                                    $asoid = $valueAso['payment_type_value'];
                                    $isCustom = $valueAso['is_custom'];
                                    if ($isCustom == 1) {
                                        $sql_aso = 'SELECT name,discounted_amount,price_in_usd FROM custom_marketing_packages WHERE id="' . $asoid . '"';
                                    } else {
                                        $sql_aso = 'SELECT name,discounted_amount,price_in_usd FROM marketing_packages WHERE id="' . $asoid . '"';
                                    }
                                    $stmt_aso = $mysqli->prepare($sql_aso);
                                    $stmt_aso->execute();
                                    $results_aso = $stmt_aso->fetch(PDO::FETCH_ASSOC);
                                    if($asoname==''){
                                        $asoname = $results_aso['name'] . ',';
                                    } else if($asoname!='' && $valueAso['is_custom'] != $isCustom){
                                         $asoname = $results_aso['name'] . ',';
                                    } 
                                        $sql_asoApp = 'SELECT summary FROM app_data WHERE id=' . $valueAso['app_id'];
                                        $stmt_asoApp = $mysqli->prepare($sql_asoApp);
                                    $stmt_asoApp->execute();
                                    $results_asoAppName = $stmt_asoApp->fetch(PDO::FETCH_ASSOC);
                                    $aso_appname .= $results_asoAppName['summary'] . ',';
//                                        $currappbaseprice += $results_aso['price_in_usd'];                                        
                                    }
                                    $order_itemsAso ='<tr>
<td style=" width: 250px;border-right: 1px solid #e0e0e0; border-left: 1px solid #a7a7a7;vertical-align: top; padding-top: 0px;padding-bottom: 10px; ">
<table cellpadding="0" cellspacing="0" style="padding-left: 10px;">
<tr>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td style="line-height: 18px;">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">' . substr($asoname,0,-1) . '</p>							
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">App:'.substr($aso_appname,0,-1).'</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px; ">'.$quantity.'</p></td>
<td style="border-right: 1px solid #e0e0e0;text-align: center; vertical-align: top; padding-top: 10px;">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;">One Time Package</p>
</td>
<td style=" border-right: 1px solid #a7a7a7;text-align: center; vertical-align: top; padding-top: 10px; ">
<p style="margin:0; padding:0; font-family:Arial; font-size:12px;"></p>
</td>
</tr>
';
                                    }
                                $order_items_Aso = $order_itemsAso;
//                                $basetotal_Aso = $currappbaseprice;
                            }
                    }
if($order_items_Aso!=''){
                    $order_items = $orderInvoiceHead.$order_items_App.$orderDetailsAsohead.$order_items_Aso;
                } else{
                    $order_items = $orderInvoiceHead.$order_items_App;
                }


                    $sql = "select * from author where custid='$custid'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->execute();
                    $results = $stmt->fetch(PDO::FETCH_ASSOC);
                    $serviceTaxhtml = '';
                    $payment_part='';
                    if ($currency == 2) {
                        $orderTotal = round($results_part_all['total'], 2) - $results6['discount'];
        $inclusiveTax = '<p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Subtotal(inclusive of all taxes): </p>';
        $inclusiveTax2 = '<p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($results_part_all['total'], 2) . '</p>';
    } else {
        $orderTotal = (round($results_part_all['total'], 2) + round($results_part_all['total'] * $service_tax / 100, 2)) - $results6['discount'];
        $inclusiveTax = '<p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Subtotal: </p><p style="margin:0;padding:0;  font-family:Arial; font-size:12px;">Service Tax (15%): </p>';
        $inclusiveTax2 = '<p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($results_part_all['total'], 2) . '</p><p style="margin:0;padding:0">' . $currencyIcon . ' ' . round($results_part_all['total'] * $service_tax / 100, 2) . '</p>';
    }
                    $planimg = "market_packages2.png";
                    // invoice email
                    $csubject = 'Instappy Invoice for order ' . $order_id;
                    //$basicUrl = $this->url;
                    $chtmlcontent = file_get_contents('edm-new/invoice.php');
                    $clastname = $results['last_name'] != '' ? ' ' . $results['last_name'] : $results['last_name'];
                    $cname = ucwords($results['first_name'] . $clastname);
                    $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                    $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                    $chtmlcontent = str_replace('{address}', $resultsDis['address'] . ',' . $resultsDis['city'], $chtmlcontent);
                    $chtmlcontent = str_replace('{state}', $resultsDis['state'], $chtmlcontent);
                    $chtmlcontent = str_replace('{order_no}', $order_id, $chtmlcontent);
                    $chtmlcontent = str_replace('{custid}', $custid, $chtmlcontent);
//                    $chtmlcontent = str_replace('{app_details_summary}', $resultsApp['summary'], $chtmlcontent);
//                    $chtmlcontent = str_replace('{my_app_type}', $my_app_type, $chtmlcontent);
//                    $chtmlcontent = str_replace('{p_platform}', $results_platform['name'], $chtmlcontent);
//                    $chtmlcontent = str_replace('{plan_title}', $results_cplan['plan_name'], $chtmlcontent);
//                    $chtmlcontent = str_replace('{yyyyy}', $yyyyy, $chtmlcontent);
//                    $chtmlcontent = str_replace('{currencyIconPrice}', ($currencyIcon . ' ' . round($results_part_all['total'], 2)), $chtmlcontent);
//    $chtmlcontent = str_replace('{payment_part}', $payment_part, $chtmlcontent);
                $chtmlcontent = str_replace('{order_items}', $order_items, $chtmlcontent);
                    $chtmlcontent = str_replace('{discount}', $results6['discount'], $chtmlcontent);
//                    $chtmlcontent = str_replace('{subtotal}', round($results_part_all['total'], 2), $chtmlcontent);
//                    $chtmlcontent = str_replace('{tax}', round($results_part_all['total'] * $service_tax / 100, 2), $chtmlcontent);
                    $chtmlcontent = str_replace('{order_total}', $orderTotal, $chtmlcontent);
                    $chtmlcontent = str_replace('{currentdate}', date('d-m-Y', time()), $chtmlcontent);
                    $chtmlcontent = str_replace('{currency}', $results_curr['Name'], $chtmlcontent);
                    $chtmlcontent = str_replace('{service_tax}', $service_tax, $chtmlcontent);
                    $chtmlcontent = str_replace('{planimg}', $planimg, $chtmlcontent);
    $chtmlcontent = str_replace('{inclusiveTax}', $inclusiveTax, $chtmlcontent);
    $chtmlcontent = str_replace('{inclusiveTax2}', $inclusiveTax2, $chtmlcontent);

                    $string = getcwd();

                    $pathdir = $string . '/pdf/' . $author_id;
                    $directory = $pathdir;
                    if (!file_exists($directory)) {
                        mkdir($string . '/pdf/' . $author_id, 0777, true);
                    }

                    // make pdf                
                    $file = fopen("convertTopdf.php", "w");
                    fwrite($file, $chtmlcontent);
                    fclose($file);
                    $pdfname = $order_id . '-part-payment-invoice.pdf';
                    ob_start();
                    ob_get_clean();

                    include('mpdf60/mpdf.php');
                    $mpdf = new mPDF('c', 'A4', '', '', 10, 10, 10, 10);
                    $mpdf->SetProtection(array('print'));
                    $mpdf->SetTitle("Instappy.com - Invoice");
                    $mpdf->SetAuthor("Instappy.com");
                    $mpdf->SetWatermarkText("Paid");
                    $mpdf->showWatermarkText = true;
                    $mpdf->watermark_font = 'DejaVuSansCondensed';
                    $mpdf->watermarkTextAlpha = 0.1;
                    $mpdf->SetDisplayMode('fullpage');
                    $mpdf->WriteHTML(file_get_contents("convertTopdf.php"));
                    $mpdf->Output($directory . '/' . $pdfname, 'F');

                    $queryUInvoice = 'UPDATE master_payment_part set invoice_link="' . $pdfname . '" WHERE id="' . $results_part['id'] . '"';
                    $stmtUInvoice = $mysqli->prepare($queryUInvoice);
                    $stmtUInvoice->execute();

                    $cbcc = 'dev@instappy.com';
                    $cto = $results['email_address'];
                    $cformemail = 'noreply@instappy.com';
                    $key = 'f894535ddf80bb745fc15e47e42a595e';
                    //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
                    //$customerhead = file_get_contents($url);
                    if (empty($results_part['next_paymentdate'])) {
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                            CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => array(
                                'api_key' => $key,
                                'subject' => $csubject,
                                'fromname' => 'Instappy',
                                'from' => $cformemail,
                                'content' => $chtmlcontent,
                                'recipients' => $cto,
                                'bcc' => $cbcc
                            )
                        ));
                        $customerhead = curl_exec($curl);

                        curl_close($curl);
                    }

                    $app_amount = 0;
                    $kk = 0;
                    $not_done = 0;
                    $done = 0;
                    $left_amt = 0;
                    foreach ($results_pm as $value) {
                        $app_amount+= $value->part_amount;

                        if ($value->payment_done == 0) {
                            if ($kk == 0) {
                                $next_part_payment = $value->paymentdate;
                                $kk++;
                            }
                            $not_done++;
                            $left_amt+= $value->part_amount;
                        } else {
                            $done++;
                        }
                    }
                    if ($done == 2) {
                        $done = $done . "nd";
                    } elseif ($done == 3) {
                        $done = $done . "rd";
                    } else {
                        $done = $done . "th";
                    }
                    ?>
                    <section class="main">
                        <section class="right_main">
                            <div class="right_inner">
                                <div class="payment_box">
                                    <div class="payment_left view-area">
                                        <div class="payment_thankyou">
                                            <h2>Congratulations!</h2>
                                            <p>Your awesome app is up and ready.</p>
                    <?php if ($next_part_payment) { ?>
                                                <span>Thank you for the prompt payment on your Instappy account. This represents the <?php echo $done; ?> instalment of amount <?php echo $currencyIcon . '' . $_SESSION['part_total_amount']; ?> on your plan, bringing your remaining balance to <?php
                                                    echo $currencyIcon . '' .
                        ($left_amt + $left_amt * $service_tax / 100);
                        ?> out of <?php echo $currencyIcon . '' . ($app_amount + $app_amount * $service_tax / 100); ?>. We will contact you again on <?php echo $next_part_payment; ?> when your next payment is due.</span>
                    <?php
                    } else {
                        ?>
                                                <span>Thank you for the prompt payment on your Instappy account. This represents the confirmation of <b>final</b> installment payment on your plan.</span>
                    <?php } ?>		
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>


                                </div>
                            </div>
                        </section>
                    </section>

                    <?php
                }
            }
        } else {
            ?>
            <section class="main">
                <section class="right_main">
                    <div class="right_inner">
                        <div class="payment_box">
                            <div class="container">
                                <div class="alert alert-success" style="margin-top:40px;font-size: 33px;width: 100%;">
                                    <center><h1><strong>Oops!</strong> Your transaction has been failed. .</h1></center>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </section>
            </section>
            <?php
        }
    } else {
        ?>
        <section class="main">
            <section class="right_main">
                <div class="right_inner">
                    <div class="payment_box">
                        <div class="container">
                            <div class="alert alert-success" style="margin-top:40px;font-size: 33px;width: 100%;">
                                <center><h1><strong>Oops!</strong> Your transaction has failed. .</h1></center>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </section>
        </section>
        <?php
        //    echo "<br>Security Error. Illegal access detected";
    }
    unset($_SESSION['source']);
}
?>
</section>
<script>
            $(document).ready(function () {
                $("#appShow").show();
                $("#hideit").hide();
                $(".btn1").click(function () {
                    $("#hideit").hide();
                    $("#appShow").show();
                });
                $(".btn2").click(function () {
                    $("#hideit").show();
                    $("#appShow").hide();
                });


            });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(".leftsidemenu li").removeClass("active");
        $(".leftsidemenu li.cart").addClass("active");

        $(".stats_download a").click(function () {
            $(this).next().toggleClass("show_pop");
            $(this).parent().siblings().children("div").removeClass("show_pop");
        });
        $(document).click(function () {
            $(".stats_download a + div").removeClass("show_pop");
        });
        $('.stats_download a').on('click', function (e) {
            e.stopPropagation();
        });
        $('.stats_download_tooltip').on('click', function (e) {
            e.stopPropagation();
        });
        /*var rightHeight = $(window).height() - 45;
         $(".right_main").css("height", rightHeight + "px")*/
        $('.optn1').trigger('click');

    });

    $('.optn2').on('click', function () {
        $('.inner_radio').show();
        $('.make_app_next').text('Next');
        $('#inpublish1').next('label').trigger('click');
    });
    $('.optn1').on('click', function () {
        $('.inner_radio').hide();
        $('.make_app_next').text('Finish');
    });

    /* Edited By Varun Srivastava */
    $('.make_app_next').on('click', function () {
        var sel_app_radio = $('.thankyou_publishing_app input[type="radio"]:checked, .inner_radio input[type="radio"]:checked').attr('id');
        if (sel_app_radio == 'publish2') {
            var sel_app_radio = $('.inner_radio input[type="radio"]:checked').attr('id');
            if (sel_app_radio == 'inpublish1')
            {
                window.location = BASEURL + 'publish_android.php';
            }
            else if (sel_app_radio == 'inpublish2')
            {
                window.location = BASEURL + 'ios-app-publish.php';
            }
        }
        else if (sel_app_radio == 'publish1')
        {
            window.location = BASEURL + 'Thankyou_publish.php';
        }
    });
</script>

</body>
</html>
