<?php
session_start();
require_once ('../../config/db.php');
$connection = new Db();
$mysqli = $connection->dbconnection();


if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    //Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
        //HTTP_REFERER verification
        if ($_POST['token'] == $_SESSION['token']) {
//            echo 'success';
            $data = $_POST['data'];
//           print_r($data);
           
            $appidedit =$data['appidedit'];
            $is_ajax = $_POST['is_ajax'];

            $checkid = "select * from app_data where id='$appidedit'";
            $stmt = $mysqli->prepare($checkid);
            $stmt->execute();
            $totalid = $stmt->fetch(PDO::FETCH_ASSOC);
            $checkresult = count($totalid);
            if (($checkresult > 0) && ($is_ajax == 1)) {
                if ((isset($_SESSION['catid'])) || (isset($_SESSION['themeid']))) {

                    unset($_SESSION['catid']);
                    unset($_SESSION['themeid']);

                    $_SESSION['catid'] = $totalid['category'];
                    $_SESSION['themeid'] = $totalid['theme'];
                } else {
                    $_SESSION['catid'] = $totalid['category'];
                    $_SESSION['themeid'] = $totalid['theme'];
                }
                echo '1';
            } else {
                echo 'App is not found database';
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed";
    }
}

?>