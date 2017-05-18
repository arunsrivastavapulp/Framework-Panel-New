<?php
require_once ('config/db.php');
class Send extends Db{
	 
	 var $db,$url;
function __construct(){
		$this->db=$this->dbconnection();
		$this->url=$this->siteurl(); 		
		 
	 }
	 

function send_app_details($data){
		$resutls=$this->check_user_exit($data['author']);
		$data['author']=$resutls['id'];
		$lid=$this->save_detail($data);
		if($lid){		
				$csubject='Instappy Trial App';
				$basicUrl     = $this->url;
				$chtmlcontent = file_get_contents($basicUrl.'edm/Registration.php');
				$clastname    = $lname != '' ? ' '.$lname : $lname;
				$cname        = $data['email'];
				$chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent); 
				$chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
				$chtmlcontent = str_replace('{verify_link}', $basicUrl, $chtmlcontent);
				$cto=$data['email'];
				$cformemail   = 'strategist@pulpstrategy.com';
				$key          = 'f894535ddf80bb745fc15e47e42a595e';
				$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
				$customerhead = file_get_contents($url);
					
				if($customerhead)	
				{
					echo "success";
				}			
				else
				{
					echo "fail";
				}
		
	}
	else{
		
		echo "fail";
	}
	

}
function check_user_exist($email,$custid){
	$sql ="select id,first_name,avatar from author where custid=$custid";
	$stmt=$this->db->prepare($sql);
	$stmt->execute();
	$results=$stmt->fetch(PDO::FETCH_ASSOC);
	return $results;
}
function save_detail($data){
		$author_id=$data['author'];
		$app_id=$data['appid'];
		$platform=$data['device'];
		$plan_id=1;
		$email_id=$data['email'];
		$sql = "INSERT INTO app_author_tracking(author_id,app_id,platform,plan_id,email_id,created) VALUES (:author_id,:app_id,:platform,:plan_id,:email_id,NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':author_id', $author_id, PDO::PARAM_STR);
        $stmt->bindParam(':app_id', $app_id, PDO::PARAM_STR);
        $stmt->bindParam(':platform', $platform, PDO::PARAM_STR);
        $stmt->bindParam(':plan_id', $plan_id, PDO::PARAM_STR);
        $stmt->bindParam(':email_id', $email_id, PDO::PARAM_STR);
        $stmt->execute();
		$lid=$this->db->lastInsertId(); 
		return $lid;
	
}
 }