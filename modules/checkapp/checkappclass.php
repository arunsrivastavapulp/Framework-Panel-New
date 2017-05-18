<?php

require_once ('config/db.php');

class checkapp extends Db {

    var $db;

    public function __construct() {
        $this->db = $this->dbconnection();
    }

    public function indexpadedata($url) {
        $imgli = '';
        $catli=array();
        $catUl=array();
        $realescape = "SELECT ct.id AS cat_id,ct.name AS cat_name FROM `category_theme_rel`cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` JOIN category ct ON ct.id=cr.`category_id` WHERE cr.`deleted`=0 Group By ct.id";
        $getallcat = $this->db->prepare($realescape);
        $getallcat->execute();
        $getall = $getallcat->fetchAll(PDO::FETCH_ASSOC);
//        print_r($result);
//        die;
        foreach ($getall as $result ) {
            $catId = $result['cat_id'];
            $catli[] = '<li data-cat=' . $catId . '><a href="javascript:void(0)">' . $result['cat_name'] . '</a></li>';
			if($result['cat_name']=='Retail'){
						$ulstart = "<ul class='theme-list' id='$catId'>";
						$catthemeImg = "SELECT tt.id as id,tt.image_url as imageurL FROM `category_theme_rel` cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` where cr.`deleted`=0 and category_id='$catId'";
						$getallcatthemes = $this->db->prepare($catthemeImg);
						$getallcatthemes->execute();
						$getallthemes = $getallcatthemes->fetchAll(PDO::FETCH_ASSOC);
						foreach ($getallthemes as $resultImg) {
							$themeid = $resultImg['id'];
							
							$imgli .= '<li><a href="catalogue.php?themeid=' . $themeid . '&catid=' . $catId . '&app=create"><img src="' . $url . $resultImg['imageurL'] . '" alt="" /></a></li>';
						}
						$ulend = "</ul>";
						$catUl[] = $ulstart . $imgli . $ulend;
						$imgli = '';
				
			}
			else{
						$ulstart = "<ul class='theme-list' id='$catId'>";
						$catthemeImg = "SELECT tt.id as id,tt.image_url as imageurL FROM `category_theme_rel` cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` where cr.`deleted`=0 and category_id='$catId'";
						$getallcatthemes = $this->db->prepare($catthemeImg);
						$getallcatthemes->execute();
						$getallthemes = $getallcatthemes->fetchAll(PDO::FETCH_ASSOC);
						foreach ($getallthemes as $resultImg) {
							$themeid = $resultImg['id'];
							
							$imgli .= '<li><a href="panel.php?themeid=' . $themeid . '&catid=' . $catId . '&app=create"><img src="' . $url . $resultImg['imageurL'] . '" alt="" /></a></li>';
						}
						$ulend = "</ul>";
						$catUl[] = $ulstart . $imgli . $ulend;
						$imgli = '';
				}
		}
        $homepagedata = array('catli' => $catli, 'themes' => $catUl);
        return $homepagedata;
    }
	
	public function indexCatTheme($url) {
        $imgli = '';
        $catli=array();
        $catUl=array();
        /* for featured */
		//$featurequery = "SELECT * FROM themes WHERE id IN (SELECT theme_id FROM category_theme_rel WHERE category_id IN (SELECT id FROM category WHERE NAME='Featured') AND deleted=0)";
		$featurequery = "SELECT * FROM themes WHERE is_featured=1";
		$getfeccat = $this->db->prepare($featurequery);
        $getfeccat->execute();
        $getfeature = $getfeccat->fetchAll(PDO::FETCH_ASSOC);		
		$catli[] = '<li data-cat="1"><div class="uprdiv"><em></em>Featured <i class="fa fa-angle-down"></i></div><div class="innr-list">';
		
		//foreach($getfeature as $feature){
			//$featurecatId = $feature['id'];
			//$catli[] = '<li data-cat=' . $catId . '><a href="javascript:void(0)">' . $result['cat_name'] . '</a></li>';
			$catli[] = '<span><a href="#sht-featured">All Featured </a></span>';
					
			//$ulstart = "<ul class='theme-list' id='$catId'>";
			$ulstart = "<ul class='sht-lst' id='sht-featured'>";
			//$catthemeImg = "SELECT tt.id as id,tt.image_url as imageurL,tt.`name` AS `name` FROM `category_theme_rel` cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` where cr.`deleted`=0 and category_id='$featurecatId'";
			//$catthemeImg = "SELECT tt.id AS theme_id,vv.id AS category_id,tt.NAME AS theme_name,tt.image_url,tt.theme_html FROM themes tt JOIN category_theme_rel uu ON tt.id=uu.theme_id JOIN category vv ON uu.category_id=vv.id WHERE vv.NAME='Featured' AND  uu.deleted=0";
			$catthemeImg = "SELECT cc.id AS category_id,cc.NAME AS category_name,tt.id AS theme_id,tt.NAME AS theme_name,tt.image_url,tt.theme_html FROM themes tt JOIN category_theme_rel cr ON tt.id=cr.theme_id JOIN category cc ON cr.category_id=cc.id WHERE tt.is_featured=1 AND cr.category_id<>464 AND cr.deleted=0";
			$getallcatthemes = $this->db->prepare($catthemeImg);
			$getallcatthemes->execute();
			$getallthemes = $getallcatthemes->fetchAll(PDO::FETCH_ASSOC);
			foreach ($getallthemes as $resultImg) {
				$themeid = $resultImg['theme_id'];
				$childcatId = $resultImg['category_id'];
				//$imgli .= '<li><a href="panel.php?themeid=' . $themeid . '&catid=' . $catId . '&app=create"><img src="' . $url . $resultImg['imageurL'] . '" alt="" /></a></li>';
				$imgli .= '<li>
							<a href="panel.php?themeid=' . $themeid . '&catid=' . $childcatId . '&app=create">
								<img src="' . $url . $resultImg['image_url'] . '" alt="">
								<span>'.$resultImg['theme_name'].'</span>
							</a>
							<div><a href="panel.php?themeid=' . $themeid . '&catid=' . $childcatId . '&app=create">Create App</a></div>
						</li>';
			}
			$ulend = "</ul>";
			$catUl[] = $ulstart . $imgli . $ulend;
			$imgli = '';
		//}
		$catli[] = '</div></li>';
		
		/* End Featured */
		//$realescape = "SELECT ct.id AS cat_id,ct.name AS cat_name FROM `category_theme_rel`cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` JOIN category ct ON ct.id=cr.`category_id` WHERE cr.`deleted`=0 Group By ct.id";
        $realescape = "SELECT id,NAME AS category_name FROM category WHERE parent_id=40 AND NAME != 'Featured'";
		$getallcat = $this->db->prepare($realescape);
        $getallcat->execute();
        $getall = $getallcat->fetchAll(PDO::FETCH_ASSOC);
        //print_r($getall);
        //die;
        foreach ($getall as $result ) {
			$catId = $result['id'];
			$realescapechild = "SELECT id,NAME AS category_name FROM category WHERE parent_id='".$catId."'";
			$getchildcat = $this->db->prepare($realescapechild);
			$getchildcat->execute();
			$getchildcats = $getchildcat->fetchAll(PDO::FETCH_ASSOC);
			$catli[] = '<li data-cat="1"><div class="uprdiv"><em></em>' . $result['category_name'] . ' <i class="fa fa-angle-down"></i></div><div class="innr-list">';
			foreach($getchildcats as $child){
				$childcatId = $child['id'];
            //$catli[] = '<li data-cat=' . $catId . '><a href="javascript:void(0)">' . $result['cat_name'] . '</a></li>';
				$catli[] = '<span><a href="#sht-'.$childcatId.'">'.$child['category_name'].'</a></span>';
						
				//$ulstart = "<ul class='theme-list' id='$catId'>";
				$ulstart = "<ul class='sht-lst' id='sht-".$childcatId."'>";
				/*$catthemeImg = "SELECT tt.id as id,tt.image_url as imageurL,tt.`name` AS `name` FROM `category_theme_rel` cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` where cr.`deleted`=0 and category_id='$childcatId'";
				$getallcatthemes = $this->db->prepare($catthemeImg);
				$getallcatthemes->execute();
				$getallthemes = $getallcatthemes->fetchAll(PDO::FETCH_ASSOC);
				foreach ($getallthemes as $resultImg) {
					$themeid = $resultImg['id'];
					//$imgli .= '<li><a href="panel.php?themeid=' . $themeid . '&catid=' . $catId . '&app=create"><img src="' . $url . $resultImg['imageurL'] . '" alt="" /></a></li>';
					$imgli .= '<li>
								<a href="panel.php?themeid=' . $themeid . '&catid=' . $childcatId . '&app=create">
									<img src="' . $url . $resultImg['imageurL'] . '" alt="">
									<span>'.$resultImg['name'].'</span>
								</a>
							</li>';
				}*/
				$ulend = "</ul>";
				$catUl[] = $ulstart . $imgli . $ulend;
				$imgli = '';
			}
			$catli[] = '</div></li>';
        }
        $homepagedata = array('catli' => $catli, 'themes' => $catUl);
        return $homepagedata;
    }
	
	public function indexRetailTheme($url) {
        $imgli = '';
        $catli=array();
        $catUl=array();
        //$realescape = "SELECT * FROM (SELECT ct.id AS cat_id,ct.NAME AS cat_name,ct2.NAME AS parent_cat_name FROM `category_theme_rel`cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` JOIN category ct ON ct.id=cr.`category_id` LEFT JOIN category ct2 ON ct.parent_id=ct2.id WHERE cr.`deleted`=0 GROUP BY ct.id) AS a WHERE parent_cat_name='Retail'";
        $realescape = "SELECT id,NAME AS category_name FROM category WHERE parent_id=30";
		$getallcat = $this->db->prepare($realescape);
        $getallcat->execute();
        $getall = $getallcat->fetchAll(PDO::FETCH_ASSOC);
        //print_r($getall);
		//die;
		$catli[] = '<li data-cat="1"><div class="uprdiv"><em></em>All Themes <i class="fa fa-angle-down"></i></div><div class="innr-list">';
        foreach ($getall as $result ) {
            $catId = $result['id'];
			//$realescapechild = "SELECT id,NAME AS category_name FROM category WHERE parent_id='".$catId."'";
			//$getchildcat = $this->db->prepare($realescapechild);
			//$getchildcat->execute();
			//$getchildcats = $getchildcat->fetchAll(PDO::FETCH_ASSOC);
			//echo "<pre>"; print_r($result); echo "</pre>"; exit;
			//$catli[] = '<li data-cat="1"><div class="uprdiv"><em></em>' . $result['category_name'] . ' <i class="fa fa-angle-down"></i></div><div class="innr-list">';
			//foreach($getchildcats as $child){
				$childcatId = $catId;
				//$catli[] = '<li data-cat=' . $catId . '><a href="javascript:void(0)">' . $result['cat_name'] . '</a></li>';
				//$catli[] = '<li data-cat=' . $catId . '><div class="uprdiv"><em></em>' . $result['cat_name'] . ' <i class="fa fa-angle-down"></i></div>
				$catli[] = '<span><a href="#sht-'.$childcatId.'">'.$result['category_name'].'</a></span>';
				//$ulstart = "<ul class='theme-list' id='$catId'>";
				$ulstart = "<ul class='sht-lst' id='sht-".$childcatId."'>";
				$catthemeImg = "SELECT tt.id as id,tt.image_url as imageurL,tt.`name` AS `name` FROM `category_theme_rel` cr JOIN `themes` tt ON cr.`theme_id`=tt.`id` where cr.`deleted`=0 and category_id='$childcatId'";
				$getallcatthemes = $this->db->prepare($catthemeImg);
				$getallcatthemes->execute();
				$getallthemes = $getallcatthemes->fetchAll(PDO::FETCH_ASSOC);
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
				$ulend = "</ul>";
				$catUl[] = $ulstart . $imgli . $ulend;
				$imgli = '';
			//}
			//$catli[] = '</div></li>';
        }
		$catli[] = '</div></li>';
        $homepagedata = array('catli' => $catli, 'themes' => $catUl);
        return $homepagedata;
    }
	
    public function paneltracking($useripadd, $catid, $themeid) {
        $sql = "INSERT INTO panel_tracking(user_ip,category_id,theme_id,created) VALUES (:user_ip,:category_id,:theme_id,NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_ip', $useripadd, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $catid, PDO::PARAM_STR);
        $stmt->bindParam(':theme_id', $themeid, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getthemeHtml($currentthemeid) {
        $sql = "select * from themes where id='$currentthemeid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $htmldata = $results['theme_html'];
        return $htmldata;
    }
    
    public function myappname($appid){
        $sql = "select * from app_data where id='$appid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $appname = $results[0]['summary'];
        return $appname;
    }
    
    public function myapppages($appid){
        $sql = "select * from screen_title_id where app_id='$appid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        return $results;
    }

//     public function realescape($var){
//         $result  = mysqli_real_escape_string($this->db, $var);
//     }
}

?>