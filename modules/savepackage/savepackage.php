<?php
require_once ('config/db.php');
class Package extends Db{

	var $db;
	function __construct(){
		$this->db=$this->dbconnection();	 
	}


	function savePackage($user, $package_id)
	{
		$package = $this->get_package($package_id);
		
		if(!empty($package))
		{
			$days = $package['validity_in_days'];
			$end_date = date('Y-m-d H:m:s', strtotime("+$days days"));
			
			$sql = "UPDATE author SET plan_id=:plan_id, plan_expiry_date=:plan_expiry_date WHERE id=:id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':plan_id', $package_id, PDO::PARAM_STR);
			$stmt->bindParam(':plan_expiry_date', $end_date, PDO::PARAM_STR);
			$stmt->bindParam(':id', $user, PDO::PARAM_STR);
			$stmt->execute();
			
			return "Package updated successfully";
		}
		else
		{
			return "Package not found"; 
		}
	}
	
	function get_package($package_id)
	{
		$sql ="SELECT * FROM plans WHERE id='$package_id' and deleted='0'";
		$stmt=$this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $results;
	}
}