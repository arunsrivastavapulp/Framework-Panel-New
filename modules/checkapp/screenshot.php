<?php
session_start();
require_once ('../../config/db.php');
$connection = new Db();
$mysqli = $connection->dbconnection();


if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    //Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
        //HTTP_REFERER verification
//        print_r($_POST);
        if ($_POST['token'] == $_SESSION['token']) {
//            echo 'success';
            $data = $_POST['data'];
            $imgVal = $data['imgVal'];
            $appid = $_POST['hasid'];
            $is_ajax = $_POST['is_ajax'];

            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];

            if (trim($custid) != '') {
                $sql = "select id from author where custid='$custid'";
                $stmt = $mysqli->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $userId = $result['id'];

                if ($userId != '') {
                    $hash = md5(trim($appid)).time();
                    $name_full = $hash . '-screen.jpg';

                    $url = $connection->siteurl();
                    $string = getcwd();
                    $expStr=explode("modules",$string);
                    $pathdir = $expStr[0].'panelimage/' . $appid;
                    $pathfull = $expStr[0] . 'panelimage/' . $appid . '/' . $name_full;
                    $pathfullUrl = $url . 'panelimage/' . $appid . '/' . $name_full;

                    if (!file_exists($pathdir)) {
                        @mkdir($pathdir, 0777, true);
                    }
                    
                    $data=substr($imgVal, strpos($imgVal, ",")+1);
                    $dataD = base64_decode($data);
                    file_put_contents($pathfull, $dataD);

                    $sqlinsert = "INSERT INTO app_screenshot(app_id,author_id,screenshot_url,created) VALUES (:app_id,:author_id,:screenshot_url,NOW())";
                    $screenshotinsert = $mysqli->prepare($sqlinsert);
                    $screenshotinsert->bindParam(':app_id', $appid, PDO::PARAM_STR);
                    $screenshotinsert->bindParam(':author_id', $userId, PDO::PARAM_STR);
                    $screenshotinsert->bindParam(':screenshot_url', $pathfullUrl, PDO::PARAM_STR);                    
                    if ($screenshotinsert->execute()) {
                        echo 0;
                    } else {
                        echo 1;
                    }
                }
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed";
    }
}
?>