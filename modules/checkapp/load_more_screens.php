<?php
session_start();
require_once ('../../config/db.php');
$connection = new Db();
$mysqli = $connection->dbconnection();

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    if (@isset($_SERVER['HTTP_REFERER'])) {
		if ($_POST['token'] == $_SESSION['token']) {
			$appID	 =	$_POST['hasid'];		
			$premium =	$_POST['premium'];		
			$from   =  ($_POST['page']*8);
			$sql ="select category FROM app_data WHERE id='".$appID."'";
			$stmt = $mysqli->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			$cat_id=$results['category'];
			if($premium == 0){
			$sql ="select * FROM splash_screen where splash_type=1 and category_id=$cat_id AND is_premium=0 LIMIT ".$from.",8";
			}
			else{
				$sql ="select * FROM splash_screen where splash_type=1 and category_id=$cat_id AND is_premium=1 LIMIT ".$from.",8";
			}
			//echo $sql;
			$stmt = $mysqli->prepare($sql);
			$stmt->execute();
			$screens = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(count($screens) > 0)	{
				$html = '';
				foreach ($screens as $screen) {
					if ($screen['image_link'] != '') {
						$sql ="select splash_screen_id FROM app_data WHERE id='".$appID."'";
						$stmt = $mysqli->prepare($sql);
						$stmt->execute();
						$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
						if(count($results) > 0)	{
							$sel_screen = $results[0]['splash_screen_id'];
						}
						
						if (trim($sel_screen) == $screen['id']) {
							$html .= '<img class="selected" src="'.$screen['image_link'].'" data-id="'.$screen['id'].'" width="158">';
						} else {
							$html .= '<img src="'.$screen['image_link'].'" data-id="'.$screen['id'].'" width="158">';
						}
					}
				}
				echo $html;
			}
		}
	}
}