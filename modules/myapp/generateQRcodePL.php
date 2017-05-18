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
            $data = $_POST['data'];
            $appidQRPL = $data['appidQRPL'];
            $is_ajax = $_POST['is_ajax'];

            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];
            if (trim($custid) != '') {
                $getuserid = "select id from author where custid='$custid'";
                $stmtuserid = $mysqli->prepare($getuserid);
                $stmtuserid->execute();
                $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
                $userId = $resultuserid['id'];

                $getQR = "select android_url,ios_url,windows_url from app_data where id = :appID and author_id = :userid and published=1";
                $allgetQR = $mysqli->prepare($getQR);
                $allgetQR->bindParam(':userid', $userId, PDO::PARAM_INT);
                $allgetQR->bindParam(':appID', $appidQRPL, PDO::PARAM_INT);
                $allgetQR->execute();
                $resultQR = $allgetQR->fetch(PDO::FETCH_ASSOC);
              

                $size = 150;
                $qrcode = '';
                if ($resultQR['android_url'] != '') {
                    $url = $resultQR['android_url'];
                    $qrcode .= '<div class="up_qr_code">
                        	<img src="http://chart.apis.google.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($url) . '" />
                            <p><img src="images/android_icon.png"> Android</p>
                        </div>';
                } 
                if ($resultQR['ios_url'] != '') {
                    $url = $resultQR['ios_url'];
                    $qrcode .= '<div class="up_qr_code">
                        	<img src="http://chart.apis.google.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($url) . '" />
                            <p><img src="images/apple_icon.png"> IOS</p>
                        </div>';
                } 
                if ($resultQR['windows_url'] != '') {
                    $url = $resultQR['windows_url'];
                    $qrcode .= '<div class="up_qr_code">
                        	<img src="http://chart.apis.google.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($url) . '" />
                            <p><img src="images/windows_icon.png"> Windows</p>
                        </div>';
                }
                if ($qrcode != '') {
                    echo $qrcode;
                } 
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