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
                if ($currency == 1) {
                    $currencyIcon = "Rs. ";
                } else {
                    $currencyIcon = "$ ";
                }
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

            if (isset($_POST['mpid'])) {

                $mppackid = $_POST['mpid'];
                $existmp = $_POST['existmp'];

                if (isset($_SESSION['custid'])) {

                    $custid = $_SESSION['custid'];

//                    $sql4 = "SELECT COUNT(*) AS mpcount FROM
//(
//SELECT apd.payment_type_value FROM master_payment mp 
//JOIN author_payment ap ON ap.master_payment_id=mp.id
//JOIN author_payment_details apd ON apd.author_payment_id=ap.id
//WHERE ap.payment_done=0 AND apd.payment_type_id=3 AND mp.author_id= IN (SELECT id FROM author WHERE custid='$custid') 
//GROUP BY apd.payment_type_value
//) AS a";
//
//                    $getexistMP = $mysqli->prepare($sql2);
//                    $getexistMP->execute();
//                    $getexistMPcount = $getexistMP->fetch(PDO::FETCH_ASSOC);
//                    $countMP = $getexistMPcount['mpcount'];

                    $y = $existmp;

                    $sql2 = "SELECT mp.id as masterpayment_id,mp.order_id as masterpayment_orderid,ap.*,apd.* FROM master_payment mp 
join author_payment ap on ap.master_payment_id=mp.id
join author_payment_details apd on apd.author_payment_id=ap.id
WHERE ap.payment_done=0 AND apd.payment_type_id=3 AND apd.payment_type_value='$mppackid' AND mp.author_id IN (SELECT id FROM author WHERE custid='$custid')";

                    $getallappMP = $mysqli->prepare($sql2);
                    $getallappMP->execute();
                    $getallMP = $getallappMP->fetchAll(PDO::FETCH_ASSOC);
                    $appid = $getallMP[0]['app_id'];
                    $mpaymentid = $getallMP[0]['masterpayment_orderid'];

                    $sqlmp = "SELECT * FROM marketing_packages WHERE id='$mppackid'";
                    $appMP = $mysqli->prepare($sqlmp);
                    $appMP->execute();
                    $appMPid = $appMP->fetch(PDO::FETCH_ASSOC);
                    $mpname = $appMPid['name'];

                    if ($currency == 1) {
                        $mpamount = $appMPid['discounted_amount'];
                    } else {
                        $mpamount = $appMPid['price_in_usd'];
                    }

//                    $mpamount = $appMPid['discounted_amount'];

                    $sql3 = "SELECT a.id as app_id,a.summary AS AppName,a.author_id,a.published,CONCAT_WS(' ',b.first_name,b.last_name) as author_name FROM app_data a
join author b on a.author_id=b.id WHERE b.custid='$custid'";
                    $getallPaidapp = $mysqli->prepare($sql3);
                    $getallPaidapp->execute();
                    $getallPaid = $getallPaidapp->fetchAll(PDO::FETCH_ASSOC);

                    $var = '<div class="payment_left_common mp">
                        <div class="payment_left_common_head">
                            <ul>
                                <li>Order No. ' . $mpaymentid . '</li>
                                <li> </li>
                                <li><a class="deleteincart" id="mpackage_' . $y . '" data-mid="' . $mppackid . '" data-appid=""><i class="fa fa-trash"></i></a></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class="payment_left_common_body no_padding">
                            <div class="package_box">
                                <div class="package_box_left">
                                    <p>' . $mpname . '</p>
                                    <input type="hidden" value="' . $mppackid . '" id="mp_id_' . $y . '"/>
                                </div>
                                <div class="package_box_right">
                                    
                                    ' . $currencyIcon . ' <p class="mpackageINR" id="mptotal_' . $y . '">' . round($mpamount, 2) . '</p>
                                    <input type="hidden" value="' . round($mpamount, 2) . '" class="mpamount"/>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="selectdivBox">
                                <p class="selectApptxt">Select App</p>
                                <div class="selectdiv">
                                    <select multiple="multiple" class="appName" id="appName_' . $y . '">';

                    foreach ($getallPaid as $key3 => $value3) {
                        $appname = $value3['AppName'];

                        if ($value3['app_id'] == $appid) {
                            $option .= '<option value="' . $value3['app_id'] . '" selected>' . $appname . '</option>';
                        } else {
                            $option .= '<option value="' . $value3['app_id'] . '">' . $appname . '</option>';
                        }
                    }
                    $var .= $option . '                                        
                                   </select>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>';
                    echo $var;
                } else {
                    echo '2';
//                    echo 'Login error';
                }
            } else {
                echo "2";
//                echo "Parameter Empty";
            }
        } else {
            echo "2";
//            echo "Request is not completed";
        }
    } else {
        echo "2";
//        echo "Request is not completed";
    }
}
?>