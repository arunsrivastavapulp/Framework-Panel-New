<?php
session_start();
require_once('../../config/db.php');

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
//Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
//HTTP_REFERER verification
        if ($_POST['token'] == $_SESSION['token']) {
            $db = new DB();
            $mysqli = $db->dbconnection();
            $currencytotal = $_POST['currency'];
            if ($currencytotal != '') {
                $currency = $currencytotal;
            } else {

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
            }

            if (isset($_POST['total']) && isset($_POST['totalad'])) {
                $total = $_POST['total'];
                $totalad = $_POST['totalad'];
                $orderid = $_POST['od'];
                $totalsave = $_POST['totalsave'];
                $discount = $_POST['discount'];

                if (isset($_SESSION['custid'])) {

                    $custid = $_SESSION['custid'];


                    $getuserid = "select id from author where custid='$custid'";
                    $stmtuserid = $mysqli->prepare($getuserid);
                    $stmtuserid->execute();
                    $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
                    $userId = $resultuserid;
                    $serverip = $_SERVER['REMOTE_ADDR'];
                    $user_id = $userId['id'];

                    $sql = "SELECT a.id,mp.id as masterpaymentid,mp.promocodes_id,a.plan_id,SUM(ad.amount) AS breakup_some,a.total_amount AS plan_amount,a.app_id FROM
(
SELECT id,author_id,master_payment_id,app_id,plan_id,payment_done,total_amount FROM author_payment
) AS a
JOIN author_payment_details ad ON a.id=ad.author_payment_id
JOIN master_payment mp ON mp.id=a.master_payment_id
WHERE a.author_id='$user_id' AND a.payment_done=0
GROUP BY a.id";

                    $stmt = $mysqli->prepare($sql);
                    $stmt->execute();
                    $appmp1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (is_array($appmp1)) {
                        $count = count($appmp1);
                    } else {
                        $count = 0;
                    }

                    if ($count > 0) {

                        $promocode = $appmp1[0]["promocodes_id"];
                        if ($discount != 0) {
                            if (($promocode != '') && (strlen($promocode) != 0)) {

                                $sqlmp = "SELECT * FROM promocodes WHERE id='$promocode'";
                                $appMP = $mysqli->prepare($sqlmp);
                                $appMP->execute();
                                $appMPid = $appMP->fetch(PDO::FETCH_ASSOC);
                                $discountamount = $appMPid['percentage_discount'];

                                $plansum = '';
                                $adsum = '';
                                $plansTS = '';

                                foreach ($appmp1 as $key => $value) {
                                    $palnid = $value['plan_id'];
                                    $breaksum = $value['breakup_some'];
                                    $plan_amount = $value['plan_amount'];

                                    $sqlplan = "SELECT * FROM plans WHERE id='$palnid' and currency_type_id='$currency' and deleted!='1'";
                                    $appplan = $mysqli->prepare($sqlplan);
                                    $appplan->execute();
                                    $appplanid = $appplan->fetch(PDO::FETCH_ASSOC);

                                    $plansaving = $appplanid['total_saving'];
                                    $plansTS = $plansTS + $plansaving;

                                    $plansum = $plansum + $plan_amount;

                                    $authpayid = $value['id'];
                                     $appid = $value['app_id'];

                                    $authpaymentid = "SELECT * FROM author_payment_details WHERE author_payment_id='$authpayid'";
                                    $authpaymentgetid = $mysqli->prepare($authpaymentid);
                                    $authpaymentgetid->execute();
                                    $authpaymentDid = $authpaymentgetid->fetchAll(PDO::FETCH_ASSOC);
                                    if (!empty($authpaymentDid)) {
                                        foreach ($authpaymentDid as $key2 => $value2) {
                                            $payid = $value2['payment_type_id'];

                                            if ($payid != 3) {
                                                if ($payid == 2) {
                                                    $splashscreenid = $value2['payment_type_value'];
                                                    $getsplashscreenprice = "SELECT *,(SELECT id FROM payment_type WHERE NAME='Splash Screen') AS payment_type_id,id AS payment_type_value FROM splash_screen WHERE id='$splashscreenid'";
                                                    $getsplashscreen = $mysqli->prepare($getsplashscreenprice);
                                                    $getsplashscreen->execute();
                                                    $getsplashscreenP = $getsplashscreen->fetch(PDO::FETCH_ASSOC);
                                                }
                                                if ($payid == 1) {
                                                    $geticondata = "SELECT * FROM app_data where id='$appid'";
                                                $geticonlink = $mysqli->prepare($geticondata);
                                                $geticonlink->execute();
                                                $geticonlinkAD = $geticonlink->fetch(PDO::FETCH_ASSOC);
                                                $iconid = $value2['payment_type_value'];
                                                $geticonprice = "SELECT *,(SELECT id FROM payment_type WHERE NAME='Icon') AS payment_type_id,id AS payment_type_value FROM default_icons WHERE image_40='".$geticonlinkAD['app_image']."'";
                                                $geticon = $mysqli->prepare($geticonprice);
                                                $geticon->execute();
                                                $geticonP = $geticon->fetch(PDO::FETCH_ASSOC);
                                                }
                                                if ($currency == 1) {
                                                    $breaksum = $getsplashscreenP['price'] + $geticonP['price'];
                                                } else {
                                                    $breaksum = $getsplashscreenP['price_in_usd'] + $geticonP['price_in_usd'];
                                                }
                                            } else {
                                                $marketpackage = $value2['payment_type_value'];
                                                $getmpprice = "SELECT * FROM marketing_packages where id='$marketpackage'";
                                                $getmp = $mysqli->prepare($getmpprice);
                                                $getmp->execute();
                                                $getmpPriceC = $getmp->fetch(PDO::FETCH_ASSOC);
                                                if ($currency == 1) {
                                                    $breaksum = $getmpPriceC['discounted_amount'];
                                                } else {
                                                    $breaksum = $getmpPriceC['price_in_usd'];
                                                }
                                            }
                                        }
                                    }

                                    $adsum = $adsum + $breaksum;
                                }
                                $totalsum = $plansum + $adsum;
                                $discount = round($totalsum * ($discountamount / 100), 2);
                                $afterdiscount = round($totalsum - $discount, 2);

                                $servicEtax = round($afterdiscount * (14 / 100), 2);
                                $addservicetax = round($afterdiscount + $servicEtax, 2);


                                if (round($addservicetax, 2) == round($totalad, 2)) {

//                                $query2 = 'UPDATE promocodes set is_consumed="1"  WHERE id="' . $appMPid['id'] . '" and author_id="' . $user_id . '"';
//                                $stmt2 = $mysqli->prepare($query2);
//                                $stmt2->execute();

                                    $queryU = 'UPDATE master_payment set discount="' . $discount . '",total_amount="' . $addservicetax . '",service_tax="' . $servicEtax . '" WHERE author_id="' . $user_id . '" AND order_id="' . $orderid . '"';
                                    $stmtU = $mysqli->prepare($queryU);
                                    $stmtU->execute();

                                    if (isset($_SESSION['totalPrice']) || isset($_SESSION['total_saving']) || isset($_SESSION['orderid']) || isset($_SESSION['totalsave'])) {
                                        unset($_SESSION['totalPrice']);
                                        unset($_SESSION['total_saving']);
                                        unset($_SESSION['orderid']);
                                        unset($_SESSION['totalsave']);

                                        $_SESSION['totalPrice'] = round($addservicetax, 2);
                                        $_SESSION['total_saving'] = round($plansTS, 2);
                                        $_SESSION['orderid'] = $orderid;
                                        $_SESSION['totalsave'] = round($totalsave, 2);
                                    } else {
                                        $_SESSION['totalPrice'] = round($addservicetax, 2);
                                        $_SESSION['total_saving'] = round($plansTS, 2);
                                        $_SESSION['orderid'] = $orderid;
                                        $_SESSION['totalsave'] = round($totalsave, 2);
                                    }
                                   
                                    echo '1';
                                } else {
                                    
                                    echo '2';
                                }
                            }
                        } else {

                            $query = 'UPDATE master_payment set promocodes_id=NULL,discount="0.00" WHERE author_id="' . $user_id . '" AND  order_id="' . $orderid . '"';
                            $stmt1 = $mysqli->prepare($query);
                            $stmt1->execute();

                            $plansum = '';
                            $adsum = '';
                            $plansTS = '';

                            foreach ($appmp1 as $key => $value) {
                                $palnid = $value['plan_id'];
                                $breaksum = $value['breakup_some'];
                                $plan_amount = round($value['plan_amount'], 2);

                                $sqlplan = "SELECT * FROM plans WHERE id='$palnid' and currency_type_id='$currency' and deleted!='1'";
                                $appplan = $mysqli->prepare($sqlplan);
                                $appplan->execute();
                                $appplanid = $appplan->fetch(PDO::FETCH_ASSOC);

                                $plansaving = $appplanid['total_saving'];
                                $plansTS = $plansTS + $plansaving;
                                
                                $plansum = $plansum + $plan_amount;
                               
                                $authpayid = $value['id'];
                                $appid = $value['app_id'];

                                $authpaymentid = "SELECT * FROM author_payment_details WHERE author_payment_id='$authpayid'";
                                $authpaymentgetid = $mysqli->prepare($authpaymentid);
                                $authpaymentgetid->execute();
                                $authpaymentDid = $authpaymentgetid->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($authpaymentDid)) {
                                    foreach ($authpaymentDid as $key2 => $value2) {
                                        $payid = $value2['payment_type_id'];

                                        if ($payid != 3) {
                                            if ($payid == 2) {
                                                $splashscreenid = $value2['payment_type_value'];
                                                $getsplashscreenprice = "SELECT *,(SELECT id FROM payment_type WHERE NAME='Splash Screen') AS payment_type_id,id AS payment_type_value FROM splash_screen WHERE id='$splashscreenid'";
                                                $getsplashscreen = $mysqli->prepare($getsplashscreenprice);
                                                $getsplashscreen->execute();
                                                $getsplashscreenP = $getsplashscreen->fetch(PDO::FETCH_ASSOC);
                                            }
                                            if ($payid == 1) {
                                                $geticondata = "SELECT * FROM app_data where id='$appid'";
                                                $geticonlink = $mysqli->prepare($geticondata);
                                                $geticonlink->execute();
                                                $geticonlinkAD = $geticonlink->fetch(PDO::FETCH_ASSOC);
                                                $iconid = $value2['payment_type_value'];
                                                $geticonprice = "SELECT *,(SELECT id FROM payment_type WHERE NAME='Icon') AS payment_type_id,id AS payment_type_value FROM default_icons WHERE image_40='".$geticonlinkAD['app_image']."'";
                                                $geticon = $mysqli->prepare($geticonprice);
                                                $geticon->execute();
                                                $geticonP = $geticon->fetch(PDO::FETCH_ASSOC);
                                            }
                                            if ($currency == 1) {
                                                 $breaksum = $getsplashscreenP['price']+$geticonP['price'];                                                
                                                 
                                            } else {
                                                $breaksum = $getsplashscreenP['price_in_usd'] +$geticonP['price_in_usd'];
                                                
                                            }
                                            
                                        } else {
                                            $marketpackage = $value2['payment_type_value'];
                                            $getmpprice = "SELECT * FROM marketing_packages where id='$marketpackage'";
                                            $getmp = $mysqli->prepare($getmpprice);
                                            $getmp->execute();
                                            $getmpPriceC = $getmp->fetch(PDO::FETCH_ASSOC);
                                            if ($currency == 1) {
                                              $breaksum = $getmpPriceC['discounted_amount'];
                                            } else {
                                                $breaksum = $getmpPriceC['price_in_usd'];
                                            }
                                        }
                                    }
                                }

                                $adsum = $adsum + $breaksum;
                                
                            }
                            
                            $totalsum = $plansum + $adsum;
                            
                            $servicEtax = round($totalsum * (14 / 100), 2);
                            $addservicetax = round($totalsum + $servicEtax, 2);
                           
                            if (round($addservicetax, 2) == round($totalad, 2)) {

                                $queryU = 'UPDATE master_payment set discount="0.00",total_amount="' . $addservicetax . '",service_tax="' . $servicEtax . '" WHERE author_id="' . $user_id . '" AND order_id="' . $orderid . '"';

                                $stmtU = $mysqli->prepare($queryU);
                                $stmtU->execute();

                                $_SESSION['totalPrice'] = round($addservicetax, 2);
                                $_SESSION['total_saving'] = round($plansTS, 2);
                                $_SESSION['orderid'] = $orderid;
                                $_SESSION['totalsave'] = round($totalsave, 2);

                                
                                echo '1';
                            } else {
                                
                                echo '2';
                            }
                        }
                    }
                } else {
                    echo 'Login error';
                }
            } else {
                echo "Parameter Empty";
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed";
    }
}
?>