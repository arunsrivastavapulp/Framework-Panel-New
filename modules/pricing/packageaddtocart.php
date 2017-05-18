<?php
session_start();
require_once ('../../config/db.php');

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
//Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
//HTTP_REFERER verification
        if ($_POST['token'] == $_SESSION['token']) {
            $db = new Db();
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

            $packageid = $_POST['packageid'];

            $custid = $_SESSION['custid'];

            if (trim($custid) != '') {
                $getuserid = "select id from author where custid='$custid'";
                $stmtuserid = $mysqli->prepare($getuserid);
                $stmtuserid->execute();
                $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
                $userId = $resultuserid;
                $serverip = $_SERVER['REMOTE_ADDR'];
                $user_id = $userId['id'];

                if ($user_id != '') {
                    $sqlcheck = "SELECT aa.id AS app_id,summary AS AppName,aa.author_id,CONCAT_WS(au.first_name,au.last_name) AS author_name,
aa.published,ap.payment_done FROM app_data aa JOIN author au ON aa.author_id=au.id LEFT JOIN author_payment ap ON ap.app_id=aa.id
LEFT JOIN master_payment pp ON pp.id=ap.master_payment_id WHERE aa.author_id ='$user_id' ORDER BY aa.published DESC LIMIT 1";

                    $getallappplandetails = $mysqli->prepare($sqlcheck);
                    $getallappplandetails->execute();
                    $getallplan = $getallappplandetails->fetchAll(PDO::FETCH_ASSOC);

                    if (is_array($getallplan)) {
                        $count = count($getallplan);
                    } else {
                        $count = 0;
                    }

                    if ($count > 0) {
                        $sqlmp = "SELECT * FROM marketing_packages WHERE id='$packageid'";

                        $appMP = $mysqli->prepare($sqlmp);
                        $appMP->execute();
                        $appMPid = $appMP->fetch(PDO::FETCH_ASSOC);

                        if ($currency == 1) {
                            $mpdamount = $appMPid['discounted_amount'];
                        } else {
                            $mpdamount = $appMPid['price_in_usd'];
                        }

//                        $mpdamount = $appMPid['discounted_amount'];

                        $hasid = 0;
//                        foreach ($getallplan as $key => $value) {
                        $appid = $getallplan[0]['app_id'];

                        $sql2 = "SELECT mp.id as masterpayment_id,mp.order_id as masterpayment_orderid,ap.*,apd.* FROM master_payment mp 
join author_payment ap on ap.master_payment_id=mp.id
join author_payment_details apd on apd.author_payment_id=ap.id
WHERE ap.payment_done=0 AND apd.payment_type_id=3 AND apd.payment_type_value='$packageid' AND mp.author_id='$user_id'";

                        $getallappMP = $mysqli->prepare($sql2);
                        $getallappMP->execute();
                        $getallMP = $getallappMP->fetchAll(PDO::FETCH_ASSOC);

                        if (is_array($getallMP)) {
                            $count4 = count($getallMP);
                        } else {
                            $count4 = 0;
                        }
                        if ($count4 > 0) {
                            echo '2';
                        } else {
                            $sql = "SELECT aa.id AS author_payment_id,aa.payment_done ,ad.* FROM author_payment_details ad
JOIN author_payment aa ON ad.author_payment_id=aa.id WHERE author_payment_id IN
(SELECT id FROM author_payment WHERE app_id='$appid' AND author_id='$user_id') AND 
    ad.payment_type_id=3 AND aa.payment_done=0 AND aa.plan_id IS NULL AND ad.payment_type_value='$packageid'";

                            $checkapp = $mysqli->prepare($sql);
                            $checkapp->execute();
                            $checkappid = $checkapp->fetch(PDO::FETCH_ASSOC);

                            if (is_array($checkappid)) {
                                $count2 = count($checkappid);
                            } else {
                                $count2 = 0;
                            }

                            if ($count2 > 0) {
                                echo '2';
                            } else {
                                $sql_auth_payment = "SELECT mp.id as masterpayment_id,ap.id as authpaymentid,ad.* FROM master_payment mp 
LEFT JOIN author_payment ap on ap.master_payment_id=mp.id LEFT JOIN author_payment_details ad ON ap.id =ad.author_payment_id
LEFT JOIN payment_type tt ON ad.payment_type_id=tt.id WHERE mp.payment_done='0' AND mp.author_id ='$user_id'";

                                $auth_payment = $mysqli->prepare($sql_auth_payment);
                                $auth_payment->execute();
                                $checkhasauthtable = $auth_payment->fetchAll(PDO::FETCH_ASSOC);

                                if (is_array($checkhasauthtable)) {
                                    $count3 = count($checkhasauthtable);
                                } else {
                                    $count3 = 0;
                                }

                                if ($count3 > 0) {

                                    $masterpayment_id = $checkhasauthtable[0]['masterpayment_id'];
                                    $sqlauthtableinsert = "INSERT author_payment(author_id,master_payment_id,author_ip,payment_status,created,app_id,total_amount) VALUES('$user_id','$masterpayment_id','$serverip','In Cart',NOW(),'$appid','0')";
                                    $stmt2 = $mysqli->prepare($sqlauthtableinsert);
                                    $stmt2->execute();

                                    $authpaymentid = $mysqli->lastInsertId();

                                    $sqlinsert = "INSERT INTO author_payment_details(author_payment_id,payment_type_id,payment_type_value,amount) VALUES('$authpaymentid','3','$packageid','$mpdamount')";
                                    $stmt = $mysqli->prepare($sqlinsert);
                                    $stmt->execute();
                                    echo '4';
                                } else {
                                    $sql = "INSERT INTO master_payment(author_id,order_id,currency_type_id) VALUES(:userid,:orderid,'$currency')";
                                    $stmt = $mysqli->prepare($sql);
                                    $stmt->bindParam(':orderid', $orderid, PDO::PARAM_INT);
                                    $stmt->bindParam(':userid', $user_id, PDO::PARAM_INT);
                                    $stmt->execute();

                                    $mastertableid = $mysqli->lastInsertId();

                                    $sql5 = "INSERT author_payment(author_id,master_payment_id,author_ip,payment_status,created,app_id,total_amount) VALUES(:userid1,:mastertableid,:serverip,'In Cart',NOW(),:appid,:planamount)";

                                    $stmt5 = $mysqli->prepare($sql5);
                                    $stmt5->bindParam(':userid1', $user_id, PDO::PARAM_INT);
                                    $stmt5->bindParam(':mastertableid', $mastertableid, PDO::PARAM_INT);
                                    $stmt5->bindParam(':appid', $appID, PDO::PARAM_INT);
                                    $stmt5->bindParam(':serverip', $serverip, PDO::PARAM_STR);
                                    $stmt5->bindParam(':planamount', $mpdamount, PDO::PARAM_STR);
                                    $stmt5->execute();

                                    $authorpaymentid = $mysqli->lastInsertId();

                                    $sqlAPD = "INSERT INTO author_payment_details(author_payment_id,payment_type_id,payment_type_value,amount) VALUES('$authorpaymentid','3','$packageid','$mpdamount')";
                                    $stmtAPD = $mysqli->prepare($sqlAPD);
                                    $stmtAPD->execute();

                                    echo '4';
                                }
                            }
                        }
//                        }
//                        echo 'Add in cart';
                    } else {
                        echo "1";
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