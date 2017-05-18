<?php
session_start();
require_once('../../config/db.php');

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
//Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
//HTTP_REFERER verification
        if ($_POST['token'] == $_SESSION['token']) {

            if (isset($_POST['appid'])) {
                $mpid = $_POST['mpid'];
                $appid = $_POST['appid'];

                if (isset($_SESSION['custid'])) {

                    $custid = $_SESSION['custid'];
                    $db = new DB();
                    $mysqli = $db->dbconnection();
                    
                    $query3 = 'select type_app from app_data where id="' . $appID . '" limit 1';
            $appQueryRun3 = $mysqli->prepare($query3);
            $appQueryRun3->execute();
            $rowFetch3 = $appQueryRun3->fetch(PDO::FETCH_ASSOC);
            $appType = $rowFetch3['type_app'];

                    $getuserid = "select id from author where custid='$custid'";
                    $stmtuserid = $mysqli->prepare($getuserid);
                    $stmtuserid->execute();
                    $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
                    $userId = $resultuserid;
                    $serverip = $_SERVER['REMOTE_ADDR'];
                    $user_id = $userId['id'];

                   

                         if ($_POST['appType'] == '1') {

                        $sql = "SELECT mp.id as masterpayment_id,ap.id as authpaymentid,ap.app_id FROM master_payment mp 
JOIN author_payment ap on ap.master_payment_id=mp.id WHERE ap.payment_done='0' AND mp.author_id='$user_id'";

                        $stmt = $mysqli->prepare($sql);
                        $stmt->execute();
                        $appmp = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (is_array($appmp)) {
                            $count = count($appmp);
                        } else {
                            $count = 0;
                        }

                        if ($count > 0) {

                            $sqlap = "SELECT * FROM author_payment WHERE payment_done='0' AND plan_id IS NOT NULL AND author_id='$user_id' AND app_id='$appid'";
                            $stmtap = $mysqli->prepare($sqlap);
                            $stmtap->execute();
                            $authappdelete = $stmtap->fetch(PDO::FETCH_ASSOC);

                            $authpid = $authappdelete['id'];


                            $sqlDelete = "delete from author_payment_details where author_payment_id='" . $authpid . "'";
                            $statementsqlDelete = $mysqli->prepare($sqlDelete);
                            $statementsqlDelete->execute();

                            $sqlDeleteap = "delete from author_payment where id='" . $authpid . "'";
                            $statementsqlDeleteap = $mysqli->prepare($sqlDeleteap);
                            $statementsqlDeleteap->execute();

                            $sqlcheckres = "SELECT mp.id as masterpayment_id,ap.id as authpaymentid,ap.app_id FROM master_payment mp 
JOIN author_payment ap on ap.master_payment_id=mp.id WHERE ap.payment_done='0' AND mp.author_id='$user_id'";

                            $stmtres = $mysqli->prepare($sqlcheckres);
                            $stmtres->execute();
                            $appres = $stmtres->fetchAll(PDO::FETCH_ASSOC);

                            if (empty($appres)) {
                                $sqlDeletemp = "delete from master_payment where id='" . $appres['masterpayment_id'] . "'";
                                $statementsqlDeletemp = $mysqli->prepare($sqlDeletemp);
                                $statementsqlDeletemp->execute();
                            }
                            echo '1';
                        } else {
                            echo '2';
                        }
                    } else if ($_POST['appType'] == '2') {

                        $sql2 = "SELECT mp.id as masterpayment_id,ap.id as authpaymentid,ap.app_id FROM master_payment mp 
JOIN author_payment ap on ap.master_payment_id=mp.id WHERE ap.payment_done='0' AND mp.author_id='$user_id'";

                        $stmt2 = $mysqli->prepare($sql2);
                        $stmt2->execute();
                        $appmp2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                        if (is_array($appmp2)) {
                            $count2 = count($appmp2);
                        } else {
                            $count2 = 0;
                        }

                        if ($count2 > 0) {

                            $sqlap = "SELECT mp.id as masterpayment_id,ap.id as authpaymentid,ap.app_id,apd.payment_type_id,apd.payment_type_value FROM master_payment mp 
JOIN author_payment ap on ap.master_payment_id=mp.id 
JOIN author_payment_details apd on apd.author_payment_id=ap.id 
WHERE ap.payment_done='0' AND mp.author_id='$user_id' AND apd.payment_type_id=3 AND apd.payment_type_value='$mpid'";

                            $stmtap = $mysqli->prepare($sqlap);
                            $stmtap->execute();
                            $authappdelete = $stmtap->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($authappdelete as $key => $value) {
                                $authpid = $value['authpaymentid'];

                                $sqlDelete = "delete from author_payment_details where author_payment_id='" . $authpid . "'";
                                $statementsqlDelete = $mysqli->prepare($sqlDelete);
                                $statementsqlDelete->execute();

                                $sqlDeleteap = "delete from author_payment where id='" . $authpid . "'";
                                $statementsqlDeleteap = $mysqli->prepare($sqlDeleteap);
                                $statementsqlDeleteap->execute();
                            }

                            $sqlcheckres = "SELECT mp.id as masterpayment_id,ap.id as authpaymentid,ap.app_id FROM master_payment mp 
JOIN author_payment ap on ap.master_payment_id=mp.id WHERE ap.payment_done='0' AND mp.author_id='$user_id'";

                            $stmtres = $mysqli->prepare($sqlcheckres);
                            $stmtres->execute();
                            $appres = $stmtres->fetchAll(PDO::FETCH_ASSOC);

                            if (empty($appres)) {
                                $sqlDeletemp = "delete from master_payment where id='" . $appres['masterpayment_id'] . "'";
                                $statementsqlDeletemp = $mysqli->prepare($sqlDeletemp);
                                $statementsqlDeletemp->execute();
                            }
                            echo '1';
                        } else {
                            echo '2';
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