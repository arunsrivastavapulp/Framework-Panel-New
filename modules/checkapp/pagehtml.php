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

            $appid = $_POST['hasid'];
            $is_ajax = $_POST['is_ajax'];

            $getpagehtml = "select * from app_screenmapping_html where app_id='$appid' ORDER BY screen_sequence ASC";
            $stmt = $mysqli->prepare($getpagehtml);
            $stmt->execute();
            $phtml = $stmt->fetchAll(PDO::FETCH_ASSOC);
//            print_r($phtml);
//            die;
$pageindex=1;
$deletepages=0;
            $checkresult = count($phtml);
            if (($checkresult > 0) && ($is_ajax == 2)) {
                foreach ($phtml as $key => $value) {
				if($value['deleted'] == 0)
				{
                    $allpageDetails[storedPages][] = array(
                        'index' => "$pageindex",
                        'contentarea' => $value['content_html'],
                        'banner' => $value['banner_html'],
                        'layouttype' => $value['layoutType'],
						'originalIndex'=>$value['screen_sequence']
                    );
                    $allpageDetails[pageDetails][] = array(
                        'index' => "$pageindex",
						'icon' => $value['ico_icon'],
						'originalIndex'=>$value['screen_sequence'],
                        'name' => $value['title']
                    );
                    if ($value['navigation_html'] != "" || $value['navigation_html'] != NULL) {
                        $allpageDetails[navbar] = $value['navigation_html'];
                    }
					$pageindex++;
                }
				elseif($value['deleted'] == 1)
				{
				$deletepages=$deletepages+1;
				}
				
				}
				$allpageDetails[deletedScreenCount]=$deletepages;

                echo json_encode($allpageDetails);
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