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

            function getdays($years) {
                $totaldays = $years * 365;
                return $totaldays;
            }

            if (isset($_POST['timeperiod']) && isset($_POST['appAllowed']) && isset($_POST['packageType']) && isset($_POST['appid'])) {
                $time_period = $_POST['timeperiod'];
                $appAllowedTxt = $_POST['appAllowed'];
                if ($appAllowedTxt == "Android-iOS") {
                    $appAllowed = 2;
                    $appAllowedType = 3;
                } else if ($appAllowedTxt == "iOS") {
                    $appAllowed = 1;
                    $appAllowedType = 2;
                } else if ($appAllowedTxt == "Android") {
                    $appAllowed = 1;
                    $appAllowedType = 1;
                }
                $packageType = $_POST['packageType'];
                $appid = $_POST['appid'];

                $query3 = 'select type_app from app_data where id="' . $appid . '" limit 1';
                $stmt3 = $mysqli->prepare($query3);
                $stmt3->execute();
                $apptypefetch = $stmt3->fetch(PDO::FETCH_ASSOC);
                $appType = $apptypefetch['type_app']; /* As Discussed with Hemant */

                if (isset($_SESSION['custid'])) {
                    $appID = $appid;
                    $custid = $_SESSION['custid'];

                    $cquery = "SELECT * FROM author WHERE custid='" . $custid . "'";
                    $stmt = $mysqli->prepare($cquery);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    $author_id = $user['id'];
                    $query = 'SELECT * FROM author_payment WHERE author_id="' . $author_id . '" AND app_id="' . $appID . '" AND payment_done="0"';
                    $stmt2 = $mysqli->prepare($query);
                    $stmt2->execute();
                    $auth_payment = $stmt2->fetch(PDO::FETCH_ASSOC);

                    if (is_array($auth_payment)) {
                        $count = count($auth_payment);
                    } else {
                        $count = 0;
                    }

                    if ($count > 0) {
                        /* for plan ID */
                        $years = getdays($time_period);

                        $app_query = "select * from plans where apps_allowed='" . $appAllowed . "' and plan_type='" . $packageType . "' and validity_in_days='" . $years . "' AND app_type='" . $appType . "' AND currency_type_id='" . $currency . "' AND deleted!='1'";
                        $stmt1 = $mysqli->prepare($app_query);
                        $stmt1->execute();
                        $plan = $stmt1->fetch(PDO::FETCH_ASSOC);
                        $mpdamount = $plan['actual_price'];

                        if (is_array($plan)) {
                            $count = count($plan);
                        } else {
                            $count = 0;
                        }
                        /* for plan ID Ends */
                        if ($count > 0) {
                            $query = 'UPDATE author_payment set plan_id="' . $plan['id'] . '",platform="' . $appAllowedType . '",total_amount="' . $mpdamount . '" WHERE author_id="' . $author_id . '" AND app_id="' . $appID . '"  AND payment_done="0"';

                            $stmt = $mysqli->prepare($query);
                            $stmt->execute();
                            echo 'Proceed to Checkout >';
                        } else {
                            echo 'Error in Auth Payment';
                        }
                    } else {
                        echo 'Error in Auth Payment';
                    }
                } else {
                    echo 'Login error';
                }
            } else {
                echo 'Error in post variables';
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed";
    }
}
?>