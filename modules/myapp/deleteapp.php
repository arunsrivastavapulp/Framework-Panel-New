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
            $hasid = $_POST['hasid'];
            $confirm = $_POST['confirm'];
            $is_ajax = $_POST['is_ajax'];

            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];
            if (trim($custid) != '') {
                $getuserid = "select id from author where custid='$custid'";
                $stmtuserid = $mysqli->prepare($getuserid);
                $stmtuserid->execute();
                $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
                $userId = $resultuserid['id'];
                
                
                $queryCf = "UPDATE componentfieldvalue set deleted='1' WHERE app_id IN(SELECT id FROM app_data WHERE author_id='$userId' and published='0' AND id='$hasid')";
                $stmtCF = $mysqli->prepare($queryCf);
                $stmtCF->execute();
                
                $queryAad = "UPDATE app_adserver_details set deleted='1' WHERE app_id IN(SELECT id FROM app_data WHERE author_id='$userId' and published='0' AND id='$hasid')";
                $stmtAad = $mysqli->prepare($queryAad);
                $stmtAad->execute();
                
                $queryAsh = "UPDATE app_screenmapping_html set deleted='1' WHERE app_id IN(SELECT id FROM app_data WHERE author_id='$userId' and published='0' AND id='$hasid')";
                $stmtASH = $mysqli->prepare($queryAsh);
                $stmtASH->execute();
                
                $queryAd = "UPDATE app_data set deleted='1' WHERE author_id='$userId' AND published='0' AND id='$hasid'";
                $stmtAD = $mysqli->prepare($queryAd);
                $stmtAD->execute();               
                
                echo '1';
            } else {
                echo "App is not deleted.";
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed.";
    }
}
?>