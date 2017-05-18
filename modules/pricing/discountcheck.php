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

            $check = 0;
            if (isset($_POST['promocode'])) {
                if (isset($_SESSION['custid'])) {
                    $promo_code = $_POST['promocode'];
                    $custid = $_SESSION['custid'];

                    $sql = "SELECT *,(CASE WHEN expirydate >=NOW() THEN '0' ELSE '1' END) AS is_expired, is_consumed FROM promocodes
WHERE (author_id IN (SELECT id FROM author WHERE custid='$custid') OR author_id IS NULL) AND promocode_value='$promo_code'";

                    $appQueryRun = $mysqli->query($sql);
                    $fetchresult = $appQueryRun->fetch(PDO::FETCH_ASSOC);

                    if (is_array($fetchresult)) {
                        $count = count($fetchresult);
                    } else {
                        $count = 0;
                    }

                    if ($count > 0) {

                        $sql2 = "SELECT id FROM author WHERE custid='$custid'";
                        $appQueryRun2 = $mysqli->query($sql2);
                        $authid = $appQueryRun2->fetch(PDO::FETCH_ASSOC);
                        $author_ID = $authid['id'];

                        $promocodeid = $fetchresult['id'];
                        if ($fetchresult['is_expired'] == "1") {
                            $check = 0;
                            echo '1';
                        } else if ($fetchresult['is_consumed'] == "1") {
                            $check = 0;
                            echo '2';
                        } else if ($fetchresult['author_id'] != '') {
                            $author_ID = $authid['id'];
                            if ($author_ID != '') {
                                if ($author_ID != $fetchresult['author_id']) {
                                    $check = 0;
                                    echo '3';
                                } else {
                                    $check = 1;
                                }
                            } else {
                                echo '1';
                            }
                        } else {
                            $check = 1;
                        }
                        if ($check == 1) {
                            $orderid = $_POST['od'];
                            $totalam = round($_POST['totalam'], 2);
                            $dicountpercentage = $fetchresult['percentage_discount'];
                            $discount2 = $totalam * ($dicountpercentage / 100);
                            $discount = round($discount2, 2);
                            $afterdiscount = round($totalam - $discount, 2);
                            if ($currency == 1) {
                                $servicetax = round($afterdiscount * 14 / 100, 2);
                                $totalpay = round($afterdiscount, 2) + round($servicetax, 2);
                            } else {
                                $servicetax = round($afterdiscount * 14 / 100, 2);
                                $totalpay = round($afterdiscount, 2) + round($servicetax, 2);
                            }

                            $query = 'UPDATE master_payment set promocodes_id="' . $promocodeid . '",discount="' . $discount . '",total_amount="' . $totalpay . '",service_tax="' . $servicetax . '" WHERE author_id="' . $author_ID . '" AND  order_id="' . $orderid . '"';

                            $stmt1 = $mysqli->prepare($query);
                            $stmt1->execute();
//
//                            $query2 = 'UPDATE promocodes set is_consumed="1",author_id="' . $author_ID . '" WHERE id="' . $promocodeid . '" AND  promocode_value="' . $promo_code . '"';
//                            $stmt2 = $mysqli->prepare($query2);
//                            $stmt2->execute();

                            $return = array('discount' => $discount, 'afterdiscount' => $totalpay, 'coupon' => $promo_code);
                            echo json_encode($return);
                        }
                    } else {
                        echo '1';
                    }
                } else {
                    echo 'Login error';
                }
            } else {
                echo 'Error in Promocode';
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed";
    }
}
?>