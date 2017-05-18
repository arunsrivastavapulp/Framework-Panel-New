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
            $appidQR = $data['appidQR'];
            $appurl = $_POST['appurl'];
            $platform = $data['platform'];
            $is_ajax = $_POST['is_ajax'];

            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];
            if (trim($custid) != '') {
                $getuserid = "select id from author where custid='$custid'";
                $stmtuserid = $mysqli->prepare($getuserid);
                $stmtuserid->execute();
                $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
                $userId = $resultuserid['id'];
               $qrcodecheck=0;
                if ($platform == 1) {
                    $sql = "UPDATE app_data SET android_url = :android_url WHERE id = :appID and author_id = :userid";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bindParam(':android_url', $appurl, PDO::PARAM_STR);
                    $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':appID', $appidQR, PDO::PARAM_INT);
                    $stmt->execute();
                    $qrcodecheck=1;
                } else if ($platform == 2) {
                    $sql = "UPDATE app_data SET ios_url = :ios_url WHERE id = :appID and author_id = :userid";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':ios_url', $appurl, PDO::PARAM_STR);
                    $stmt->bindParam(':appID', $appidQR, PDO::PARAM_INT);
                    $stmt->execute();
                    $qrcodecheck=1;
                } else if ($platform == 3) {
                    $sql = "UPDATE app_data SET windows_url =  :windows_url WHERE id = :appID and author_id = :userid";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':windows_url', $appurl, PDO::PARAM_STR);
                    $stmt->bindParam(':appID', $appidQR, PDO::PARAM_INT);
                    $stmt->execute();
                    $qrcodecheck=1;
                }
                if ($qrcodecheck == 1) {
                    
                    $getQR = "select android_url,ios_url,windows_url from app_data where id = :appID and author_id = :userid";
                    $allgetQR = $mysqli->prepare($getQR);
                    $allgetQR->bindParam(':userid', $userId, PDO::PARAM_INT);
                    $allgetQR->bindParam(':appID', $appidQR, PDO::PARAM_INT);
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