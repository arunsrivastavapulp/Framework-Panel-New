<?php

require_once ('config/db.php');

class Save_images extends Db {

    var $db, $url;

    function __construct() {
        $this->db = $this->dbconnection();
        $this->url = $this->siteurl();
    }

   function save_phone_img($data){	
		$app_id= $data['appid']; 
		$links=$this->get_phone_links($app_id,'phone_link');		
			  if($data['index']=='phone_1'){

				 $image_array[]=$data['phone_image'];
			  }
			  else{
				$image_array[]=$links[0];  
			  }
			  if($data['index']=='phone_2'){
				$image_array[]=$data['phone_image'];		
				  
			  }
			  else{
				  
				$image_array[]=$links[1];  
			  }
			  if($data['index']=='phone_3'){
				  $image_array[]=$data['phone_image'];
				  
			  }
			  else{
				$image_array[]=$links[2];    
				  
			  }


	  $images = implode(',', $image_array);
	  $id=$this->check_app_exist($app_id);
	  if($id>0){		  
			  $sql = "UPDATE android_app_data SET
			  phone_link = :phone_link
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':phone_link', $images, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
				if($stmt->execute()){
						echo "success";
				}
				else{
					echo "fails";
				}
	  }
	  else{
		$sql = "INSERT INTO android_app_data (app_id,phone_link)VALUES('".$app_id."','".$images."');";
		$stmt = $this->db->prepare($sql);
		if($stmt->execute()){
		echo "success";
		}
		else{
		echo "fails";
		}  
	  }

   }
   
function check_app_exist($app_id){	   
		$sql ="select id FROM android_app_data where app_id='".$app_id."'order by id desc limit 0,1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if($results['id']>0){
			return $results['id'];
			
		}
		else{
			return 0;
		}
   }
   function get_phone_links($app_id,$link_type){	   
		$sql ="select $link_type FROM android_app_data where app_id='".$app_id."'order by id desc limit 0,1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		$links=explode(",",$results[$link_type]);
		return $links;
		
   }
   function save_7_inch_tablet_img($data){	   
	   $app_id= $data['appid']; 
		$links=$this->get_phone_links($app_id,'tablet_link');
	   if($data['index']=='tablet_1'){

				 $image_array[]=$data['tablet_image'];
			  }
			  else{
				$image_array[]=$links[0];  
			  }
			  if($data['index']=='tablet_2'){
				$image_array[]=$data['tablet_image'];		
				  
			  }
			  else{
				  
				$image_array[]=$links[1];  
			  }
			  if($data['index']=='tablet_3'){
				  $image_array[]=$data['tablet_image'];
				  
			  }
			  else{
				$image_array[]=$links[2];    
				  
			  }


	  $images = implode(',', $image_array);
	  $id=$this->check_app_exist($app_id);
	  if($id>0){		  
			  $sql = "UPDATE android_app_data SET
			  tablet_link = :tablet_link
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tablet_link', $images, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
				if($stmt->execute()){
						echo "success";
				}
				else{
					echo "fails";
				}
	  }
	  else{
		$sql = "INSERT INTO android_app_data (app_id,tablet_link)VALUES('".$app_id."','".$images."');";
		$stmt = $this->db->prepare($sql);
		if($stmt->execute()){
		echo "success";
		}
		else{
		echo "fails";
		}  
	  }

   }
   function save_10_inch_tablet_img($data){
	  	   $app_id= $data['appid']; 
		$links=$this->get_phone_links($app_id,'large_tablet_link');
	   if($data['index']=='bigtablet_1'){

				 $image_array[]=$data['large_tablet_image'];
			  }
			  else{
				$image_array[]=$links[0];  
			  }
			  if($data['index']=='bigtablet_2'){
				$image_array[]=$data['large_tablet_image'];		
				  
			  }
			  else{
				  
				$image_array[]=$links[1];  
			  }
			  if($data['index']=='bigtablet_3'){
				  $image_array[]=$data['large_tablet_image'];
				  
			  }
			  else{
				$image_array[]=$links[2];    
				  
			  }


	  $images = implode(',', $image_array);
	  $id=$this->check_app_exist($app_id);
	  if($id>0){		  
			  $sql = "UPDATE android_app_data SET
			  large_tablet_link = :large_tablet_link
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':large_tablet_link', $images, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
				if($stmt->execute()){
						echo "success";
				}
				else{
					echo "fails";
				}
	  }
	  else{
		$sql = "INSERT INTO android_app_data (app_id,large_tablet_link)VALUES('".$app_id."','".$images."');";
		$stmt = $this->db->prepare($sql);
		if($stmt->execute()){
		echo "success";
		}
		else{
		echo "fails";
		}  
	  } 
	   
   }
   function save_featured_icons($data){
	    $app_id= $data['appid']; 
		$images=$data['icons_featured_image'];
	     $id=$this->check_app_exist($app_id);
	  if($id>0){		  
			  $sql = "UPDATE android_app_data SET
			  featured_banner_link = :featured_banner_link
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':featured_banner_link', $images, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
				if($stmt->execute()){
						echo "success";
				}
				else{
					echo "fails";
				}
	  }
	  else{
		$sql = "INSERT INTO android_app_data (app_id,featured_banner_link)VALUES('".$app_id."','".$images."');";
		$stmt = $this->db->prepare($sql);
		if($stmt->execute()){
		echo "success";
		}
		else{
		echo "fails";
		}  
	  } 
	   
   }  
   function save_graphic_icons($data){
	    $app_id= $data['appid']; 
		$images=$data['icons_graphic_image'];
	    $id=$this->check_app_exist($app_id);
	  if($id>0){		  
			  $sql = "UPDATE android_app_data SET
			  promo_banner_link = :promo_banner_link
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':promo_banner_link', $images, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
				if($stmt->execute()){
						echo "success";
				}
				else{
					echo "fails";
				}
	  }
	  else{
		$sql = "INSERT INTO android_app_data (app_id,promo_banner_link)VALUES('".$app_id."','".$images."');";
		$stmt = $this->db->prepare($sql);
		if($stmt->execute()){
		echo "success";
		}
		else{
		echo "fails";
		}  
	  }  
	   
   }
   function fetch_icons($app_id,$link_type){
	  $sql ="select $link_type FROM android_app_data where app_id='".$app_id."'order by id desc limit 0,1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		return 	$results[$link_type];	
	   
   }
   function check_ios_app_exist($app_id){
		$sql ="select id FROM ios_app_data where app_id='".$app_id."'order by id desc limit 0,1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		if($results['id']>0){
			return $results['id'];
			
		}
		else{
			return 0;
		} 
	   
   }
   function ios_publish_images($data){	   
	    $app_id= $data['appid'];
		$images=$data['icons_image'];
		if($data['index']=='one'){
		$this->insertupdate_ios_link($app_id,$images,'iphonesix_link');	
		}		
		if($data['index']=='two'){
		$this->insertupdate_ios_link($app_id,$images,'iphonesixplus_link');	
		}
		if($data['index']=='three'){
		$this->insertupdate_ios_link($app_id,$images,'iphonefive_link');	
		}
		if($data['index']=='four'){
		$this->insertupdate_ios_link($app_id,$images,'iphonefour_link');	
		}
		if($data['index']=='five'){
		$this->insertupdate_ios_link($app_id,$images,'ipad_link');	
		}	
   }
   function insertupdate_ios_link($app_id,$images,$column){
	    $id=$this->check_ios_app_exist($app_id);	    
	  if($id>0){		  
			  $sql = "UPDATE ios_app_data SET
			  $column = :$column
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':'.$column, $images, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
				if($stmt->execute()){
						echo "success";
				}
				else{
					echo "fails";
				}
	  }
	  else{
		$sql = "INSERT INTO ios_app_data (app_id,$column)VALUES('".$app_id."','".$images."');";
		$stmt = $this->db->prepare($sql);
		if($stmt->execute()){
		echo "success";
		}
		else{
		echo "fails";
		}  
	  }  
   }
   
   function fetch_ios_icons($app_id,$link){
	    $sql ="select $link FROM ios_app_data where app_id='".$app_id."'order by id desc limit 0,1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		return 	$results[$link];
	   
   }
}
