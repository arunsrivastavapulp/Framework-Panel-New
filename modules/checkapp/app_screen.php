<?php
/* 
	Functionality : Fetch App Icon/Screen 
	Author : Varun Srivastava
*/

require_once('config/db.php');	

class WebScreen extends Db{
	 
	var $db;
	function __construct(){
		$this->db=$this->dbconnection();
	}
	
	public function get_app_icons($appID,$premium=0){		
		//$sql ="select * FROM default_icons";
		$results=$this->get_category($appID);
		$cat_id=$results['category'];
		if($premium == 0){
			$sql ="SELECT * FROM default_icons WHERE id IN (SELECT default_icon_id FROM default_icons_category_mapping WHERE deleted=0 AND category_id=$cat_id) AND is_premium=0 LIMIT 0,14";
		}
		else{
			$sql ="SELECT * FROM default_icons WHERE id IN (SELECT default_icon_id FROM default_icons_category_mapping WHERE deleted=0 AND category_id=$cat_id) AND is_premium=1 LIMIT 0,14";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($results) > 0)	{
			return $results;
		}	
		
	}
	
	public function get_selected_app_icon($appid){		
		$sql ="select app_image FROM app_data WHERE id='".$appid."' limit 50";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($results) > 0)	{
			return $url = $results[0]['app_image'];
			/*$sql ="SELECT * FROM default_icons WHERE image_40='".$url."'";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(count($result) > 0)	{
				return $result[0]['id'];
			}*/
		}		
	}
	
	public function get_total_app_icon($appID,$premium=0){		
		//$sql ="select * FROM default_icons";
		$results=$this->get_category($appID);
		$cat_id=$results['category'];
		if($premium == 0){
			$sql ="SELECT * FROM default_icons WHERE id IN (SELECT default_icon_id FROM default_icons_category_mapping WHERE deleted=0 AND category_id=$cat_id) AND is_premium=0";
		}
		else{
			$sql ="SELECT * FROM default_icons WHERE id IN (SELECT default_icon_id FROM default_icons_category_mapping WHERE deleted=0 AND category_id=$cat_id) AND is_premium=1";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($results) > 0)	{
			return count($results);
		}	
		
	}
	
	public function get_app_screen($appID,$premium=0){
		$results=$this->get_category($appID);
		$cat_id=$results['category'];
		if($premium == 0){
			$sql ="select * FROM splash_screen where splash_type=1 and category_id=$cat_id AND is_premium=0 LIMIT 8";
		}
		else{
			$sql ="select * FROM splash_screen where splash_type=1 and category_id=$cat_id AND is_premium=1 LIMIT 8";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($results) > 0)	{
			return $results;
		}	
	}
	
	public function get_total_app_screen($appID,$premium=0){
		$results=$this->get_category($appID);
		$cat_id=$results['category'];
		if($premium == 0){
			$sql ="select * FROM splash_screen where splash_type=1 and category_id=$cat_id AND is_premium=0";
		}
		else{
			$sql ="select * FROM splash_screen where splash_type=1 and category_id=$cat_id AND is_premium=1";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($results) > 0)	{
			return count($results);
		}	
	}
	
	public function get_self_icons($appID){		
		$sql ="select * FROM self_icons WHERE app_id='$appID'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($results) > 0)	{
			return $results;
		}	
	}
	
	public function get_self_screens($appID){
		
		$sql ="select * FROM splash_screen WHERE app_id='$appID'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($results) > 0)	{
			return $results;
		}	
	}
	
	public function get_selected_splash_screen($appid){
		
		$sql ="select splash_screen_id FROM app_data WHERE id='".$appid."'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($results) > 0)	{
			return $results[0]['splash_screen_id'];
		}	
		
	}
	public function get_category($app_id){		
		$sql ="select category FROM app_data WHERE id='".$app_id."'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		return $results;
	}
}	