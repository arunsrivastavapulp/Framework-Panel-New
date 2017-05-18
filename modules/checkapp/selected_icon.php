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
            $dataurl = $_POST['data'];
            $appid = $_POST['hasid'];
            $is_ajax = $_POST['is_ajax'];

            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];
            $data = trim(str_replace("&amp;","&",$dataurl));

            if (trim($custid) != '' && $appid != '' && $data != '') {
				$query = 'UPDATE app_data SET app_image="'.$data.'" WHERE id="'.$appid.'"';
				$stmt = $mysqli->prepare($query);
                $stmt->execute();
			}
		}
	}
}	