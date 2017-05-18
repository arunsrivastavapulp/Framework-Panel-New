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
			$from   =  ($_POST['page']*14);
			$sql ="select category FROM app_data WHERE id='".$appID."'";
			$stmt = $mysqli->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			$cat_id=$results['category'];
			if($premium == 0){
				$sql ="SELECT * FROM default_icons WHERE id IN (SELECT default_icon_id FROM default_icons_category_mapping WHERE deleted=0 AND category_id=$cat_id) AND is_premium=0 LIMIT ".$from.",14";
			}
			else{
				$sql ="SELECT * FROM default_icons WHERE id IN (SELECT default_icon_id FROM default_icons_category_mapping WHERE deleted=0 AND category_id=$cat_id) AND is_premium=1 LIMIT ".$from.",14";
			}
			//echo $sql;
			$stmt = $mysqli->prepare($sql);
			$stmt->execute();
			$icons = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//print_r($icons);
			if(count($icons) > 0)	{
				$sql ="select app_image FROM app_data WHERE id='".$appID."'";
				$stmt = $mysqli->prepare($sql);
				$stmt->execute();
				$selresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if(count($selresults) > 0)	{
					$sel_icon = $url = $selresults[0]['app_image'];
				}
				else{
					$sel_icon = '';
				}
				$html = '';
				foreach ($icons as $icon) {
					if ($icon['image_40'] != '') {
						if(trim(str_replace("&amp;","&",$sel_icon)) == trim($icon['image_40'])){
							$html .= '<div class="uploaded_icons"><img class="selected" src="'.$icon['image_40'].'"></div>';
						} else {
							$html .= '<div class="uploaded_icons"><img src="'.$icon['image_40'].'"></div>';
						}
					}
				}
				echo $html;
			}
		}
	}
}