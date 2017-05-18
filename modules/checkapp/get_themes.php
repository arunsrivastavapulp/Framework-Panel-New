<?php
require_once('../../config/db.php');
if(isset($_POST) && $_POST['ID'] != '')
{
	$childcatId = $_POST['ID'];
	$db = new DB();
	$mysqli = $db->dbconnection();
	if($_POST['type'] == 'panel')
	{
		$catthemeImg = "SELECT tt.id as id,tt.image_url as imageurL,tt.`name` AS `name` FROM `category_theme_rel` cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` where cr.`deleted`=0 and category_id='$childcatId'";
		$getallcatthemes = $mysqli->prepare($catthemeImg);
		$getallcatthemes->execute();
		$getallthemes = $getallcatthemes->fetchAll(PDO::FETCH_ASSOC);
		$imgli = '';
		foreach ($getallthemes as $resultImg) {
			$themeid = $resultImg['id'];
			/* $imgli .= '<li><a href="panel.php?themeid=' . $themeid . '&catid=' . $catId . '&app=create"><img src="' . $url . $resultImg['imageurL'] . '" alt="" /></a></li>'; */
			$imgli .= '<li>
						<a href="panel.php?themeid=' . $themeid . '&catid=' . $childcatId . '&app=create">
							<img src="' . $url . $resultImg['imageurL'] . '" alt="">
							<span>'.$resultImg['name'].'</span>
						</a>
						<div><a href="panel.php?themeid=' . $themeid . '&catid=' . $childcatId . '&app=create">Create App</a></div>
					</li>';
		}
		echo $imgli;
	}
	elseif($_POST['type'] == 'catalogue')
	{
		$catthemeImg = "SELECT tt.id as id,tt.image_url as imageurL,tt.`name` AS `name` FROM `category_theme_rel` cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` where cr.`deleted`=0 and category_id='$childcatId'";
		$getallcatthemes = $mysqli->prepare($catthemeImg);
		$getallcatthemes->execute();
		$getallthemes = $getallcatthemes->fetchAll(PDO::FETCH_ASSOC);
		$imgli = '';
		foreach ($getallthemes as $resultImg) {
			$themeid = $resultImg['id'];
			//$imgli .= '<li><a href="panel.php?themeid=' . $themeid . '&catid=' . $catId . '&app=create"><img src="' . $url . $resultImg['imageurL'] . '" alt="" /></a></li>';
			$imgli .= '<li>
							<a href="catalogue.php?themeid=' . $themeid . '&catid=' . $childcatId . '&app=create">
								<img src="' . $url . $resultImg['imageurL'] . '" alt="">
								<span>'.$resultImg['name'].'</span>
							</a>
							<div><a href="catalogue.php?themeid=' . $themeid . '&catid=' . $childcatId . '&app=create">Create App</a></div>
						</li>';
		}
		echo $imgli;
	}
	else
	{
		echo 'error';
	}
}
else
{
	echo 'error';
}
?>