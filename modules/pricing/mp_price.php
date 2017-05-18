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

            if (isset($_POST['appselected']) && isset($_POST['mpid'])) {

                $appselected = $_POST['appselected'];
                $mppackid = $_POST['mpid'];
                $spiltappid = explode(",", $appselected);
                $appcount = count($spiltappid);
                if (isset($_SESSION['custid'])) {

                    $custid = $_SESSION['custid'];


                    $getuserid = "select id from author where custid='$custid'";
                    $stmtuserid = $mysqli->prepare($getuserid);
                    $stmtuserid->execute();
                    $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
                    $userId = $resultuserid;
                    $serverip = $_SERVER['REMOTE_ADDR'];
                    $user_id = $userId['id'];

                    $sqlmp = "SELECT * FROM marketing_packages WHERE id='$mppackid' and deleted!='1'";
                    $appMP = $mysqli->prepare($sqlmp);
                    $appMP->execute();
                    $appMPid = $appMP->fetch(PDO::FETCH_ASSOC);

                    if ($currency == 1) {
                        $mpdamount = $appMPid['discounted_amount'];
                    } else {
                        $mpdamount = $appMPid['price_in_usd'];
                    }
//                    $mpdamount = $appMPid['discounted_amount'];


                    $sqlD = "SELECT mp.id AS masterpayment_id,aa.id as authpaymentid ,ad.* FROM author_payment_details ad
JOIN author_payment aa ON ad.author_payment_id=aa.id JOIN master_payment mp ON aa.master_payment_id=mp.id
WHERE ad.payment_type_id=3 AND mp.author_id='$user_id' AND aa.payment_done=0 AND ad.payment_type_value='$mppackid'";

                    $stmtD = $mysqli->prepare($sqlD);
                    $stmtD->execute();
                    $appmpD = $stmtD->fetchAll(PDO::FETCH_ASSOC);

                    if (is_array($appmpD)) {
                        $count = count($appmpD);
                    } else {
                        $count = 0;
                    }
                    $mastertableid = $appmpD[0]['masterpayment_id'];

                    if ($count > 0) {

                        foreach ($appmpD as $key => $value) {
                            $authpid = $value['authpaymentid'];

                            $sqlDelete = "delete from author_payment_details where author_payment_id='" . $authpid . "'";
                            $statementsqlDelete = $mysqli->prepare($sqlDelete);
                            $statementsqlDelete->execute();

                            $sqlDeleteap = "delete from author_payment where id='" . $authpid . "'";
                            $statementsqlDeleteap = $mysqli->prepare($sqlDeleteap);
                            $statementsqlDeleteap->execute();
                        }
                    }
                    if (($mastertableid != 0) && ($mastertableid != '')) {

                        for ($x = 0; $x < $appcount; $x++) {
                            $appID = $spiltappid[$x];

                            $sqlauthtableinsert = "INSERT author_payment(author_id,master_payment_id,author_ip,payment_status,created,app_id,total_amount) VALUES('$user_id','$mastertableid','$serverip','In Cart',NOW(),'$appID','0')";
                            $stmt2 = $mysqli->prepare($sqlauthtableinsert);
                            $stmt2->execute();

                            $authpaymentid = $mysqli->lastInsertId();

                            $sqlinsert = "INSERT INTO author_payment_details(author_payment_id,payment_type_id,payment_type_value,amount) VALUES('$authpaymentid','3','$mppackid','$mpdamount')";
                            $stmt3 = $mysqli->prepare($sqlinsert);
                            $stmt3->execute();
                        }
                    } else {
                        echo 'Master Payment Id Error';
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