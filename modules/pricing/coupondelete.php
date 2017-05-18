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

            if (isset($_SESSION['custid'])) {
                $orderid = $_POST['od'];
                $custid = $_SESSION['custid'];
                
                 $sql2 = "SELECT id FROM author WHERE custid='$custid'";                            
                            $appQueryRun2 = $mysqli->query($sql2);
                            $authid = $appQueryRun2->fetch(PDO::FETCH_ASSOC);
                            $author_ID = $authid['id'];
                           
                $query = 'UPDATE master_payment set promocodes_id=NULL,discount="0" WHERE author_id="' . $author_ID . '" AND  order_id="' . $orderid . '"';
                $stmt1 = $mysqli->prepare($query);
                $stmt1->execute();
                echo "1";
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed";
    }
}
?>