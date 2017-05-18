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
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $platform = $data['platform'];
            $appName = $data['appName'];
            $themeid = $_POST['themeid'];
            $catid = $_POST['catid'];
            $confirmVal = $_POST['confirm'];
            $hasid = $_POST['hasid'];

            $is_ajax = $_POST['is_ajax'];
            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];

            if (trim($custid) != '') {
                $sql = "select id from author where custid='$custid'";
                $stmt = $mysqli->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $userId = $result['id'];
               
                if ($hasid != 0) {
                    $checkname = "select * from app_data where summary='$appName' and author_id='$userId' and id!='$hasid'";
                } else {
                    $checkname = "select * from app_data where summary='$appName' and author_id='$userId'";
                }
                $stmt_name = $mysqli->prepare($checkname);
                $stmt_name->execute();
                $result_name = $stmt_name->fetchAll(PDO::FETCH_ASSOC);
                $checkresult = count($result_name);
                if (($checkresult > 0) && ($is_ajax == 1)) {
                    echo '1';
                } else {
                    if ($hasid != 0) {
                        $checkforid = "select * from app_data where id='$hasid' and author_id='$userId'";
                        $idExist = $mysqli->prepare($checkforid);
                        $idExist->execute();
                        $totalid = $idExist->fetchAll(PDO::FETCH_ASSOC);
                        $check_id = count($totalid);
                        if ($check_id > 0) {
                            $sql = "UPDATE app_data SET summary = :summary, 
								platform = :platform, 
								created =  NOW()
								WHERE id = :appID";
                            $stmt = $mysqli->prepare($sql);
                            $stmt->bindParam(':summary', $appName, PDO::PARAM_STR);
                            $stmt->bindParam(':platform', $platform, PDO::PARAM_STR);
                            $stmt->bindParam(':appID', $hasid, PDO::PARAM_INT);
                            $stmt->execute();
                            echo $hasid;
                            $createNew = 0;
                        } else {
                            $createNew = 1;
                        }
                    } else {
                        $createNew = 1;
                    }
                    if ($createNew == 1) {
                        $screenid = 1;
                        $sql = "INSERT INTO app_data(app_id,plan_expiry_date,summary,author_id,launch_screen_id,splash_screen_id,category,platform,theme,created) VALUES (:app_id,'2015-10-08 00:00:00',:summary,:author_id,:launch_screen_id,:splash_screen_id,:category,:platform,:theme, NOW())";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bindParam(':app_id', $token, PDO::PARAM_STR);
                        $stmt->bindParam(':summary', $appName, PDO::PARAM_STR);
                        $stmt->bindParam(':author_id', $userId, PDO::PARAM_STR);
                        $stmt->bindParam(':launch_screen_id', $screenid, PDO::PARAM_STR);
                        $stmt->bindParam(':splash_screen_id', $screenid, PDO::PARAM_STR);
                        $stmt->bindParam(':category', $catid, PDO::PARAM_STR);
                        $stmt->bindParam(':platform', $platform, PDO::PARAM_STR);
                        $stmt->bindParam(':theme', $themeid, PDO::PARAM_STR);
                        $stmt->execute();
                        if(isset($_SESSION['appid'])){
                            unset($_SESSION['appid']);
                            
                        }                        
                        $appid = $mysqli->lastInsertId();

                        $_SESSION['appid'] = $appid;
                        echo $appid;
                    }
                }
            } else {
                echo "User not login";
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed";
    }
}
?>