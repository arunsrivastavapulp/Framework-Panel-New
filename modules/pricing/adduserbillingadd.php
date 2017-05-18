<?php
session_start();
require_once('../../config/db.php');

$connection = new Db();
$mysqli = $connection->dbconnection();


//function xyz($strfromAjaxPOST)
//{
//    $array = "";
//    $returndata = "";
//    $strArray = explode("&", $strfromAjaxPOST);
//    $i = 0;
//    foreach ($strArray as $str)
//    {
//        $array = explode("=", $str);
//        $returndata[$array[0]] = $array[1];
//        $i = $i + 1;
//    }
//    return($returndata);
//}

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
//Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
//HTTP_REFERER verification
        if ($_POST['token'] == $_SESSION['token']) {

            if (isset($_SESSION['custid']) && isset($_POST) && (isset($_SESSION['orderid']))) {
//                $data = unserialize($_POST['data']);               

                parse_str($_POST['data'], $searcharray);
                $data = $searcharray;
                
//                $data = xyz($_POST['data']);
                               
                $is_ajax = $_POST['is_ajax'];
                $custid = $_SESSION['custid'];
                $getuserid = "select id from author where custid='$custid'";
                $stmtuserid = $mysqli->prepare($getuserid);
                $stmtuserid->execute();
                $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
                $userId = $resultuserid;
                $serverip = $_SERVER['REMOTE_ADDR'];
                $user_id = $userId['id'];

                $queryMp = 'SELECT * from master_payment where author_id="' . $user_id . '" AND order_id="' . $_SESSION['orderid'] . '"';
                $stmtmp = $mysqli->prepare($queryMp);
                $stmtmp->execute();
                $resultmp = $stmtmp->fetch(PDO::FETCH_ASSOC);

                if (is_array($resultmp)) {
                    $count = count($resultmp);
                } else {
                    $count = 0;
                }
                if ($count > 0) {
                    if(!empty($data['billing_address2'])){
                        $add2 = ' - '.$data['billing_address2'];
                    }
                    
                    $queryU = 'UPDATE master_payment set first_name="' . $data['billing_fname'] . '", last_name="' . $data['billing_lname'] . '", email_address="' . $data['billing_email'] . '", phone="' . $data['billing_phone'] . '", country="' . $data['countryName'] . '",state="' . $data['stateName'] . '", city="' . $data['billing_city'] . '", address="' . $data['billing_address1'] .$add2.'", zip="' . $data['billing_zip'] . '" WHERE author_id="' . $user_id . '" AND order_id="' . $_SESSION['orderid'] . '"';
                    $stmtU = $mysqli->prepare($queryU);
                    if($stmtU->execute()){
                        echo '1';    
                    } else{
                        echo '2';
                    }
                    
                } else {
                    echo '2';
                }
            } else {
                echo 'Error in Parameters';
            }
        } else {
            echo 'Request is not completed';
        }
    } else {
        echo "Request is not completed";
    }
}
?>