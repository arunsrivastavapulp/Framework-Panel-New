<?php
session_start();
require_once ('../../config/db.php');

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    //Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
        //HTTP_REFERER verification
        if ($_POST['token'] == $_SESSION['token']) {
            $db = new DB();
            $mysqli = $db->dbconnection();
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

//            $appid = $_POST['hasid'];
            $is_ajax = $_POST['is_ajax'];

            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];
            $appID = $_POST['hasid'];

            $query3 = 'select type_app from app_data where id="' . $appID . '" limit 1';
            $appQueryRun3 = $mysqli->prepare($query3);
            $appQueryRun3->execute();
            $rowFetch3 = $appQueryRun3->fetch(PDO::FETCH_ASSOC);

            $appType = $rowFetch3['type_app'];

            $orderid = strtotime('now');
//            $orderid = $order_id . $custid;
            $mastertableid = '';
            if (trim($custid) != '') {
                $getuserid = "select id from author where custid='$custid'";
                $stmtuserid = $mysqli->prepare($getuserid);
                $stmtuserid->execute();
                $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);

                $serverip = $_SERVER['REMOTE_ADDR'];
                $user_id = $resultuserid['id'];

                if ($user_id != '') {
                    $sqlcheck = "SELECT mp.id as masterpayment_id,ap.id as authpaymentid,ap.app_id as appiD,ap.plan_id,ad.* FROM master_payment mp 
LEFT JOIN author_payment ap on ap.master_payment_id=mp.id
LEFT JOIN author_payment_details ad ON ap.id =ad.author_payment_id
LEFT JOIN payment_type tt ON ad.payment_type_id=tt.id
WHERE mp.payment_done='0' AND mp.author_id IN (SELECT id FROM author WHERE custid='$custid')";

                    $getallappplandetails = $mysqli->prepare($sqlcheck);
                    $getallappplandetails->execute();
                    $getallplan = $getallappplandetails->fetchAll(PDO::FETCH_ASSOC);

                    if (is_array($getallplan)) {
                        $count = count($getallplan);
                    } else {
                        $count = 0;
                    }
                    $check = 0;
                    if ($count > 0) {

                        foreach ($getallplan as $key => $value) {

                            $authid = $value['authpaymentid'];
                            $mpid = $value['masterpayment_id'];


                            if (($appID == $value['appiD']) && ($value['plan_id'] != '')) {

                                $sqlDelete = "delete from `author_payment_details` where author_payment_id='$authid'";
                                $statementsqlDelete = $mysqli->prepare($sqlDelete);
                                $statementsqlDelete->execute();

                                $sqlDeleteAP = "delete from `author_payment` where id='$authid'";
                                $statementsqlDeleteAP = $mysqli->prepare($sqlDeleteAP);
                                $statementsqlDeleteAP->execute();

                                $mastertableid = $mpid;
                                $check = 1;
                            } else {
                                $check = 1;
                                $mastertableid = $mpid;
                            }
                        }
                    } else {

                        $sql = "INSERT INTO master_payment(author_id,order_id,currency_type_id) VALUES ('$user_id','$orderid','$currency')";

                        $stmt = $mysqli->prepare($sql);
                        $stmt->execute();

                        $mastertableid = $mysqli->lastInsertId();
                        $check = 1;
                    }
                    if ($check == 1) {
//                        if ($is_ajax == 1) {
                        $sql2 = "SELECT REPLACE(app_image,'&amp;','&') AS icon_image_link,aa.splash_screen_id AS splashscreenid,pp.id AS planid,pp.* FROM app_data aa
JOIN plans pp ON aa.type_app=pp.app_type WHERE aa.author_id=$user_id AND aa.splash_screen_id IS NOT NULL AND pp.plan_type<>1 
AND pp.validity_in_days=730 AND pp.plan_type=3 AND pp.apps_allowed=2 and pp.app_type=$appType and aa.id=$appID and pp.currency_type_id=$currency ORDER BY aa.app_id,actual_price DESC";
//                        } 
//                        else if($is_ajax == 2) {
//                            $sql2 = "SELECT REPLACE(app_image,'&amp;','&') AS icon_image_link,aa.splash_screen_id AS splashscreenid,pp.id AS planid,pp.actual_price AS planamount FROM app_data aa
//JOIN plans pp ON aa.type_app=pp.app_type WHERE aa.author_id=$user_id AND aa.splash_screen_id IS NOT NULL AND pp.plan_type<>1 
//AND pp.validity_in_days=730 AND pp.plan_type=3 AND pp.apps_allowed=2 and pp.app_type=2 and aa.id=$appID ORDER BY aa.app_id,actual_price DESC";
//                        }
                        $getappiconSplash = $mysqli->prepare($sql2);
                        $getappiconSplash->execute();
                        $getIS = $getappiconSplash->fetch(PDO::FETCH_ASSOC);

                        $iconLink = $getIS['icon_image_link'];
                        $splashscreenid = $getIS['splashscreenid'];
                        $planid = $getIS['planid'];
                        $planamount = $getIS['actual_price'];



                        $platform = 3;


                        if ($mastertableid != '') {

                            $sql5 = "INSERT author_payment(author_id,master_payment_id,author_ip,plan_id,platform,payment_status,created,app_id,total_amount) VALUES(:userid1,:mastertableid,:serverip,:planid,:platform,'In Cart',NOW(),:appid,:planamount)";

                            $stmt5 = $mysqli->prepare($sql5);
                            $stmt5->bindParam(':userid1', $user_id, PDO::PARAM_INT);
                            $stmt5->bindParam(':mastertableid', $mastertableid, PDO::PARAM_INT);
                            $stmt5->bindParam(':appid', $appID, PDO::PARAM_INT);
                            $stmt5->bindParam(':serverip', $serverip, PDO::PARAM_STR);
                            $stmt5->bindParam(':planid', $planid, PDO::PARAM_INT);
                            $stmt5->bindParam(':planamount', $planamount, PDO::PARAM_STR);
                            $stmt5->bindParam(':platform', $platform, PDO::PARAM_STR);
                            $stmt5->execute();

                            $authorpaymentid = $mysqli->lastInsertId();


                            $sql3 = "SELECT *,(SELECT id FROM payment_type WHERE NAME='Icon') AS payment_type_id,id AS payment_type_value FROM default_icons WHERE image_40='$iconLink'";

                            $geticonid = $mysqli->prepare($sql3);
                            $geticonid->execute();
                            $getIconlink = $geticonid->fetch(PDO::FETCH_ASSOC);

                            $paymenttypeidI = $getIconlink['payment_type_id'];
                            $paymenttypevalueI = $getIconlink['payment_type_value'];

                            if ($currency == 1) {
                                $priceI = $getIconlink['price'];
                            } else {
                                $priceI = $getIconlink['price_in_usd'];
                            }

                            $sql4 = "SELECT *,(SELECT id FROM payment_type WHERE NAME='Splash Screen') AS payment_type_id,id AS payment_type_value FROM splash_screen WHERE id='$splashscreenid'";

                            $getsplashid = $mysqli->prepare($sql4);
                            $getsplashid->execute();
                            $getsplash = $getsplashid->fetch(PDO::FETCH_ASSOC);

                            $paymenttypeidS = $getsplash['payment_type_id'];
                            $paymenttypevalueS = $getsplash['payment_type_value'];
                            if ($currency == 1) {
                                $priceS = $getsplash['price'];
                            } else {
                                $priceS = $getsplash['price_in_usd'];
                            }

                            if ($authorpaymentid != '') {

                                if (!empty($paymenttypevalueI)) {
                                    $sql6 = "INSERT INTO author_payment_details(author_payment_id,payment_type_id,payment_type_value,amount) VALUES(:author_payment_id,:paymenttypeid,:paymenttypevalue,:price)";
                                    $stmt6 = $mysqli->prepare($sql6);
                                    $stmt6->bindParam(':author_payment_id', $authorpaymentid, PDO::PARAM_INT);
                                    $stmt6->bindParam(':paymenttypeid', $paymenttypeidI, PDO::PARAM_INT);
                                    $stmt6->bindParam(':paymenttypevalue', $paymenttypevalueI, PDO::PARAM_INT);
                                    $stmt6->bindParam(':price', $priceI, PDO::PARAM_STR);
                                    $stmt6->execute();
                                } else {
                                    $sql3Self = "SELECT *,(SELECT id FROM payment_type WHERE NAME='Icon') AS payment_type_id,id AS payment_type_value FROM self_icons WHERE image_40='$iconLink'";

                                    $geticonSelfid = $mysqli->prepare($sql3Self);
                                    $geticonSelfid->execute();
                                    $getIconlinkSelf = $geticonSelfid->fetch(PDO::FETCH_ASSOC);

                                    $paymenttypeidSI = $getIconlinkSelf['payment_type_id'];
                                    $paymenttypevalueSI = $getIconlinkSelf['payment_type_value'];
                                    $priceSI = '0.00';
                                    $sql7 = "INSERT INTO author_payment_details(author_payment_id,payment_type_id,payment_type_value,amount) VALUES(:author_payment_id,:paymenttypeid,:paymenttypevalue,:price)";
                                    $stmt7 = $mysqli->prepare($sql7);
                                    $stmt7->bindParam(':author_payment_id', $authorpaymentid, PDO::PARAM_INT);
                                    $stmt7->bindParam(':paymenttypeid', $paymenttypeidSI, PDO::PARAM_INT);
                                    $stmt7->bindParam(':paymenttypevalue', $paymenttypevalueSI, PDO::PARAM_INT);
                                    $stmt7->bindParam(':price', $priceSI, PDO::PARAM_STR);
                                    $stmt7->execute();
                                }

                                if (!empty($paymenttypevalueS)) {
                                    $sql6 = "INSERT INTO author_payment_details(author_payment_id,payment_type_id,payment_type_value,amount) VALUES(:author_payment_id,:paymenttypeid,:paymenttypevalue,:price)";
                                    $stmt6 = $mysqli->prepare($sql6);
                                    $stmt6->bindParam(':author_payment_id', $authorpaymentid, PDO::PARAM_INT);
                                    $stmt6->bindParam(':paymenttypeid', $paymenttypeidS, PDO::PARAM_INT);
                                    $stmt6->bindParam(':paymenttypevalue', $paymenttypevalueS, PDO::PARAM_INT);
                                    $stmt6->bindParam(':price', $priceS, PDO::PARAM_STR);
                                    $stmt6->execute();
                                } 

                                echo 'Add in cart';
                            } else {
                                echo "Author Table id error";
                            }
                        } else {
                            echo "Master Table id error";
                        }
                    }
                } else {
                    echo "User custid error";
                }
            } else {
                echo "User login error";
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed.";
    }
}
?>