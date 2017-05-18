<?php
require_once ('config/db.php');
class Opencart extends Db{
	
var $db,$opencart_url;
function __construct(){
		$this->db=$this->dbconnection();	 
		$this->opencart_url=$this->catalogue_url();	 
		 
	 }

function save_catalogue_app($data,$files){	
		$app_id = bin2hex(openssl_random_pseudo_bytes(18));
		$author_id=$data['author'];
		$app_name=addslashes($data['appName']);
		$response=$this->app_name_exit($app_name,$author_id,$app_insert=0);
		$pos = strpos($data['bg'], '#');
		if($pos===false)
		$bg=$this->rgb2hex($data['bg']);
		else
		$bg=$data['bg'];	
		$catalogue_app_type=$data['catalogue_app_type'];
		if($catalogue_app_type==1 || $catalogue_app_type==2)
		$type_app=2;
		else{
		$type_app=3;
		}
		$basicUrl=$data['baseurl'];
		$theme=$data['theme'];
		$category=$data['category'];
		
		if($response>0){
			echo "app_exit";
		}
		else{
				$sql = "INSERT INTO app_data(author_id,app_id,summary,type_app,category,theme,created) VALUES (:author_id,:app_id,:summary,:type_app,:category,:theme,NOW())";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(':author_id', $author_id, PDO::PARAM_STR);
				$stmt->bindParam(':app_id', $app_id, PDO::PARAM_STR);
				$stmt->bindParam(':summary', $app_name, PDO::PARAM_STR);
				$stmt->bindParam(':type_app', $type_app, PDO::PARAM_STR);
				$stmt->bindParam(':category', $category, PDO::PARAM_STR);
				$stmt->bindParam(':theme', $theme, PDO::PARAM_STR);
				$stmt->execute();
				$lid=$this->db->lastInsertId();
				$result=$this->add_attributes($lid,$catalogue_app_type,$app_name,$bg,$data,$basicUrl);
				if($result=='success')
				{
					$sql122 ="select * FROM author WHERE id='".$author_id."'";
					$stmt122 = $this->db->prepare($sql122);
					$stmt122->execute();
					$results122 = $stmt122->fetch(PDO::FETCH_ASSOC);
					
					$user = $results122;
					
					if($user['email_address'] != '')
					{
						$pdflink = 'edm/Ready-Checklist-Retail.pdf';
						
						$app_name          = $app_name;
						
						$currentdate       = date('d/m/Y');
						$thirtydays        = date('d/m/Y', strtotime('+30 days', time()));
						$app_start_to_date = $currentdate.' to '.$thirtydays;
						
						$csubject          = 'Congratulations!! Your 30-day Instappy trial period starts now!';
						$basicUrl          = $this->siteurl();
						$chtmlcontent      = file_get_contents('./edm/first_step_registration.php');
						$clastname         = $user['last_name'] != '' ? ' ' . $user['last_name'] : $user['last_name'];
						$cname             = ucwords($user['first_name'] . $clastname);
						$chtmlcontent      = str_replace('{customer_name}', ucwords($cname), $chtmlcontent);
						$chtmlcontent      = str_replace('{base_url}', $basicUrl, $chtmlcontent);
						$chtmlcontent      = str_replace('{app_name}', $app_name, $chtmlcontent);
						$chtmlcontent      = str_replace('{app_start_to_date}', $app_start_to_date, $chtmlcontent);
						$chtmlcontent      = str_replace('{pdflink}', $pdflink, $chtmlcontent);
						 
						//$chtmlcontent      = str_replace('{verify_link}', $basicUrl . 'signup-varification.php?verification=' . $uid, $chtmlcontent);
						//$chtmlcontent      = str_replace('{verify_link}', $basicUrl, $chtmlcontent);

						$cto = $user['email_address'];
						$cformemail = 'noreply@instappy.com';
						$key = 'f894535ddf80bb745fc15e47e42a595e';

						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
							CURLOPT_POST => 1,
							CURLOPT_POSTFIELDS => array(
								'api_key' => $key,
								'subject' => $csubject,
								'fromname' => 'Instappy',
								'from' => $cformemail,
								'content' => $chtmlcontent,
								'recipients' => $cto
							)
						));
						$customerhead = curl_exec($curl);

						curl_close($curl);
					}
					
					$_SESSION['appid']=$lid;
					echo $lid;
				}
				else
				{
					echo "fails";
				}
		}
}
function app_name_exit($app_name,$author_id,$app_id=0){
	if($app_id>0){
	$sql ="select id from app_data where summary='$app_name' and author_id=$author_id and id NOT IN($app_id) AND deleted = 0";
	}
	else{
	$sql ="select id from app_data where summary='$app_name' and author_id=$author_id AND deleted = 0";
	}
	$stmt=$this->db->prepare($sql);
	$stmt->execute();
	$results=$stmt->fetch(PDO::FETCH_ASSOC);
	return $results['id'];	
}	 
function send_opencart($username,$password){
			$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->opencart_url.'catalogue/ecommerce_catalog_api/index.php/vendorlogin',
			CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'username' =>$username,
				'password' => $password
			)
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);

	if($resp=='success'){
		echo $username.'##'.$password;
	}
	else{		
		echo "fails";
	}
	
}	
function vendor_register($data){
		unset($data['type']);
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->opencart_url.'catalogue/ecommerce_catalog_api/index.php/vendorregister',
			CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $data
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		$output=json_decode($resp);		
		 $code=$output->response->errorCode;
	if($code==405){
		echo $output->response->errorMsg;
		
	}
	else if($code==200){
		echo "success";
	}
	else{
		echo "Opps something went wrong.please try again later.";
	}
	
}
function add_attributes($app_id,$app_type,$app_name,$bg,$data,$basicUrl){
			/*
			$b1=$data['banner_img_1']['name'];
			$b2=$data['banner_img_2']['name'];
			$b3=$data['banner_img_3']['name'];
			if(!file_exists('panelimage/'.$app_id))
			{
				mkdir('panelimage/'.$app_id, 0777, true);
			}
			$uploaddir = 'panelimage/'.$app_id.'/';
			$banner1 = $uploaddir . basename(time().'_'.$b1);
			$banner2= $uploaddir . basename(time().'_'.$b2);
			$banner3 = $uploaddir . basename(time().'_'.$b3);
			if (move_uploaded_file($data['banner_img_1']['tmp_name'], $banner1)
			&& move_uploaded_file($data['banner_img_2']['tmp_name'], $banner2)
			&& move_uploaded_file($data['banner_img_3']['tmp_name'], $banner3))
			{
				$mbanner1=$basicUrl.$banner1;
				$mbanner2=$basicUrl.$banner2;
				$mbanner3=$basicUrl.$banner3;
			*/
				$mbanner1  = $data['banner_img_1'];
				$mbanner2  = $data['banner_img_2'];
				$mbanner3  = $data['banner_img_3'];
				$app_tag   = $data['app_tag'];
			
				$logo_link          = $data['logo_link'];
				
				// check if image was previously added or not
				$sql1  = "SELECT app_id, banner1, banner2, banner3, logo_link FROM app_catalogue_attr WHERE app_id = :app_id";
				$stmt1 = $this->db->prepare($sql1);
				$stmt1->bindParam(':app_id', $app_id, PDO::PARAM_STR);
				$stmt1->execute();
				$results = $stmt1->fetch(PDO::FETCH_ASSOC);
			
			
				if(!empty($results))
				{
					// unlink banner 1 if there is a change in banner 1
					if($results['banner1'] != '' && $results['banner1'] != $mbanner1)
					{
						$mybanner1 = str_replace($basicUrl, './', $results['banner1']);
						@unlink($mybanner1);
					}
					
					// unlink banner 2 if there is a change in banner 2
					if($results['banner2'] != '' && $results['banner2'] != $mbanner2)
					{
						$mybanner2 = str_replace($basicUrl, './', $results['banner2']);
						@unlink($mybanner2);
					}
					
					// unlink banner 3 if there is a change in banner 3
					if($results['banner3'] != '' && $results['banner3'] != $mbanner3)
					{
						$mybanner3 = str_replace($basicUrl, './', $results['banner3']);
						@unlink($mybanner3);
					}
				
					// unlink logo_link if there is a change in logo_link
					if($results['logo_link'] != '' && $results['logo_link'] != $logo_link)
					{
						$mlogo_link = str_replace($basicUrl, './', $results['logo_link']);
						@unlink($mlogo_link);
					}
				}
				
				$is_feedback     = $data['is_feedback'];
				$feedback_email  = $data['feedback_email'];
				$feedback_no     = $data['feedback_no'];
				
				$is_contactus    = $data['is_contactus'];
				$contactus_email = $data['contactus_email'];
				$contactus_no    = $data['contactus_no'];
				
				$is_tnc          = $data['is_tnc'];
				$tnc_link        = $data['tnc_link'];
			
				$is_order           = $data['is_order'];
				$package            = $data['companypackage'];
				$orderconfirm_email = $data['orderusername'].'@'.$data['orderdomain'];
				$orderconfirm_no    = $data['orderconfirm_no'];
				
				$curr_id            = $data['curr_id'];
	
				// text color
				$pos = strpos($data['text_color'], '#');
				if($pos===false)
				$text_color=$this->rgb2hex($data['text_color']);
				else
				$text_color=$data['text_color'];
			
				// background color
				$pos = strpos($data['discount_color'], '#');
				if($pos===false)
				$discount_color=$this->rgb2hex($data['discount_color']);
				else
				$discount_color=$data['discount_color'];
		
		
				$title=ucfirst($app_name);	
				$sql = "INSERT INTO app_catalogue_attr(app_id,catalogue_type,title,banner1,banner2,banner3,background_color,text_color,discount_color,created,
							app_tag_id, is_feedback, feedback_email, feedback_no, is_contactus, contactus_email, contactus_no, is_tnc, tnc_link, is_order,
							logo_link, package, orderconfirm_email, orderconfirm_no, curr_id) 
							VALUES (:app_id,:catalogue_type,:title,:banner1,:banner2,:banner3,:background_color,:text_color,:discount_color,NOW(),
							:app_tag_id, :is_feedback, :feedback_email, :feedback_no, :is_contactus, :contactus_email, :contactus_no, :is_tnc, :tnc_link, :is_order,
							:logo_link, :package, :orderconfirm_email, :orderconfirm_no, :curr_id)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(':app_id', $app_id, PDO::PARAM_STR);
				$stmt->bindParam(':catalogue_type',$app_type, PDO::PARAM_STR);
				$stmt->bindParam(':title',$title, PDO::PARAM_STR);
				$stmt->bindParam(':banner1',$mbanner1, PDO::PARAM_STR);
				$stmt->bindParam(':banner2',$mbanner2, PDO::PARAM_STR);
				$stmt->bindParam(':banner3',$mbanner3, PDO::PARAM_STR);
				$stmt->bindParam(':background_color',$bg, PDO::PARAM_STR);
				$stmt->bindParam(':text_color',$text_color, PDO::PARAM_STR);
				$stmt->bindParam(':discount_color',$discount_color, PDO::PARAM_STR);
				$stmt->bindParam(':app_tag_id',$app_tag, PDO::PARAM_STR);
				$stmt->bindParam(':is_feedback',$is_feedback, PDO::PARAM_STR);
				$stmt->bindParam(':feedback_email',$feedback_email, PDO::PARAM_STR);
				$stmt->bindParam(':feedback_no',$feedback_no, PDO::PARAM_STR);
				$stmt->bindParam(':is_contactus',$is_contactus, PDO::PARAM_STR);
				$stmt->bindParam(':contactus_email',$contactus_email, PDO::PARAM_STR);
				$stmt->bindParam(':contactus_no',$contactus_no, PDO::PARAM_STR);
				$stmt->bindParam(':is_tnc',$is_tnc, PDO::PARAM_STR);
				$stmt->bindParam(':tnc_link',$tnc_link, PDO::PARAM_STR);
				$stmt->bindParam(':is_order', $is_order, PDO::PARAM_STR);
				$stmt->bindParam(':logo_link', $logo_link, PDO::PARAM_STR);
				$stmt->bindParam(':package', $package, PDO::PARAM_STR);
				$stmt->bindParam(':orderconfirm_email', $orderconfirm_email, PDO::PARAM_STR);
				$stmt->bindParam(':orderconfirm_no', $orderconfirm_no, PDO::PARAM_STR);
				$stmt->bindParam(':curr_id', $curr_id, PDO::PARAM_STR);
				$stmt->execute();
				return "success";
			/*
			} 
			else 
			{
			if($b1==''&&$b2==''&& $b3==''){
				$sql = "INSERT INTO app_catalogue_attr(app_id,catalogue_type,title,banner1,banner2,banner3,background_color,created) VALUES (:app_id,:catalogue_type,:title,:banner1,:banner2,:banner3,:background_color,NOW())";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(':app_id', $app_id, PDO::PARAM_STR);
				$stmt->bindParam(':catalogue_type',$app_type, PDO::PARAM_STR);
				$stmt->bindParam(':title',$title, PDO::PARAM_STR);
				$stmt->bindParam(':banner1',$b1, PDO::PARAM_STR);
				$stmt->bindParam(':banner2',$b2, PDO::PARAM_STR);
				$stmt->bindParam(':banner3',$b3, PDO::PARAM_STR);
				$stmt->bindParam(':background_color',$bg, PDO::PARAM_STR);
				$stmt->execute();
				return "success";
			}
			else{
				 return "fails";
			}			
		  
			}
			*/	
	
}

function check_user($email,$custid){
	$sql ="select * from author where  custid=$custid";
	$stmt=$this->db->prepare($sql);
	$stmt->execute();
	$results=$stmt->fetch(PDO::FETCH_ASSOC);
	return $results;
}
function edit_catalogue_app($app_id){
	$sql ="select ad.summary ,ad.author_id,ad.theme,att.catalogue_type,att.banner1,att.banner2,att.banner3,att.background_color,att.text_color,att.discount_color,att.app_tag_id, att.is_feedback, att.feedback_email, att.feedback_no, att.is_contactus, att.contactus_email, att.contactus_no, att.is_tnc, att.tnc_link, att.is_order, att.logo_link, att.package, att.orderconfirm_email, att.orderconfirm_no, att.curr_id 
	       from app_data as ad 
		   right join app_catalogue_attr as att on ad.id=att.app_id 
		   where ad.id=$app_id";
	$stmt=$this->db->prepare($sql);
	$stmt->execute();
	$results=$stmt->fetch(PDO::FETCH_ASSOC);
	return $results;
}
function update_catalogue_app($data,$files){
		$pos = strpos($data['bg'], '#');
		if($pos===false)
		$bg=$this->rgb2hex($data['bg']);
		else
		$bg=$data['bg'];
	
	$catalogue_app_type=$data['catalogue_app_type'];
	if($catalogue_app_type==1 || $catalogue_app_type==2)
		$type_app=2;
		else{
		$type_app=3;
		}
	$app_name=addslashes($data['appName']);
	$app_id=$data['app_id'];
	$basicUrl=$data['baseurl'];
	$author_id=$data['author'];
	$response=$this->app_name_exit($app_name,$author_id,$app_id);
			if($response>0){
			echo "app_exit";
		}
		else{
			$sql = "UPDATE app_data SET 
					summary = :summary, 
					type_app = :type_app, 
					updated =NOW() 
					WHERE id = :id";
				$stmt = $this->db->prepare($sql);                                  
				$stmt->bindParam(':summary', $app_name, PDO::PARAM_STR);          
				$stmt->bindParam(':type_app', $type_app, PDO::PARAM_INT);
				$stmt->bindParam(':id', $app_id, PDO::PARAM_INT);
				$stmt->execute();
			$result=$this->edit_app_attributes($app_id,$catalogue_app_type,$app_name,$bg,$data,$basicUrl);		
			if($result=="success"){
				echo $data['app_id'];
			}
			else{
				echo "fails";
			}
	}	
}
function edit_app_attributes($app_id,$app_type,$app_name,$bg,$data,$basicUrl){
			$results=$this->edit_catalogue_app($app_id);
			$title=ucfirst($app_name);
			/*
			if(!file_exists('panelimage/'.$app_id))
			{
				mkdir('panelimage/'.$app_id, 0777, true);
			}
			$title=ucfirst($app_name);
			$uploaddir = 'panelimage/'.$app_id.'/';
			if($data['banner_img_1']['name']){
				$banner1 = $uploaddir . basename(time().'_'.$data['banner_img_1']['name']);
				move_uploaded_file($data['banner_img_1']['tmp_name'], $banner1);
				$mbanner1=$basicUrl.$banner1;
				}
			else
				$mbanner1=$results['banner1'];
			if($data['banner_img_2']['name']){
				$banner2= $uploaddir . basename(time().'_'.$data['banner_img_2']['name']);
				 move_uploaded_file($data['banner_img_2']['tmp_name'], $banner2);
				$mbanner2=$basicUrl.$banner2;
			}
			else{
				$mbanner2=$results['banner2'];
			}
			if($data['banner_img_3']['name']){
				$banner3 = $uploaddir . basename(time().'_'.$data['banner_img_3']['name']);
				move_uploaded_file($data['banner_img_3']['tmp_name'], $banner3);
				$mbanner3=$basicUrl.$banner3;				
			} 
			else 
			$mbanner3=$results['banner3'];	
			*/
			$mbanner1 = $data['banner_img_1'];
			$mbanner2 = $data['banner_img_2'];
			$mbanner3 = $data['banner_img_3'];
			$app_tag  = $data['app_tag'];
			
			$is_feedback     = $data['is_feedback'];
			$feedback_email  = $data['feedback_email'];
			$feedback_no     = $data['feedback_no'];
			
			$is_contactus    = $data['is_contactus'];
			$contactus_email = $data['contactus_email'];
			$contactus_no    = $data['contactus_no'];
			
			$is_tnc          = $data['is_tnc'];
			$tnc_link        = $data['tnc_link'];
			
			$is_order        = $data['is_order'];
			$logo_link       = $data['logo_link'];
			$package         = $data['companypackage'];
			$orderconfirm_email = $data['orderusername'].'@'.$data['orderdomain'];
			$orderconfirm_no = $data['orderconfirm_no'];
			
			$curr_id         = $data['curr_id'];
			
			// check if image was previously added or not
			$sql1  = "SELECT app_id, banner1, banner2, banner3 FROM app_catalogue_attr WHERE app_id = :app_id";
			$stmt1 = $this->db->prepare($sql1);
			$stmt1->bindParam(':app_id', $app_id, PDO::PARAM_STR);
			$stmt1->execute();
			$results = $stmt1->fetch(PDO::FETCH_ASSOC);
			
			
			if(!empty($results))
			{
				// unlink banner 1 if there is a change in banner 1
				if($results['banner1'] != '' && $results['banner1'] != $mbanner1)
				{
					$mybanner1 = str_replace($basicUrl, './', $results['banner1']);
					@unlink($mybanner1);
				}
				
				// unlink banner 2 if there is a change in banner 2
				if($results['banner2'] != '' && $results['banner2'] != $mbanner2)
				{
					$mybanner2 = str_replace($basicUrl, './', $results['banner2']);
					@unlink($mybanner2);
				}
				
				// unlink banner 3 if there is a change in banner 3
				if($results['banner3'] != '' && $results['banner3'] != $mbanner3)
				{
					$mybanner3 = str_replace($basicUrl, './', $results['banner3']);
					@unlink($mybanner3);
				}
				
				// unlink logo_link if there is a change in logo_link
				if($results['logo_link'] != '' && $results['logo_link'] != $logo_link)
				{
					$mlogo_link = str_replace($basicUrl, './', $results['logo_link']);
					@unlink($mlogo_link);
				}
			}
	
			// text color
			$pos = strpos($data['text_color'], '#');
			if($pos===false)
			$text_color=$this->rgb2hex($data['text_color']);
			else
			$text_color=$data['text_color'];
		
			// background color
			$pos = strpos($data['discount_color'], '#');
			if($pos===false)
			$discount_color=$this->rgb2hex($data['discount_color']);
			else
			$discount_color=$data['discount_color'];
			
			$sql = "UPDATE app_catalogue_attr SET catalogue_type = :catalogue_type, title=:title,banner1=:banner1,banner2=:banner2,banner3=:banner3,background_color=:background_color,
							text_color=:text_color,
							discount_color=:discount_color,
							app_tag_id = :app_tag,
							is_feedback = :is_feedback,
							feedback_email = :feedback_email,
							feedback_no = :feedback_no,
							is_contactus = :is_contactus,
							contactus_email = :contactus_email,
							contactus_no = :contactus_no,
							is_tnc = :is_tnc,
							tnc_link = :tnc_link,
							is_order = :is_order,
							logo_link = :logo_link,
							package = :package,
							orderconfirm_email = :orderconfirm_email,
							orderconfirm_no = :orderconfirm_no,
							curr_id = :curr_id,
							updated =NOW() 
							WHERE app_id = :app_id";
							$stmt = $this->db->prepare($sql);
				$stmt->bindParam(':catalogue_type',$app_type, PDO::PARAM_STR);
				$stmt->bindParam(':title',$title, PDO::PARAM_STR);
				$stmt->bindParam(':banner1',$mbanner1, PDO::PARAM_STR);
				$stmt->bindParam(':banner2',$mbanner2, PDO::PARAM_STR);
				$stmt->bindParam(':banner3',$mbanner3, PDO::PARAM_STR);
				$stmt->bindParam(':background_color',$bg, PDO::PARAM_STR);
				$stmt->bindParam(':text_color',$text_color, PDO::PARAM_STR);
				$stmt->bindParam(':discount_color',$discount_color, PDO::PARAM_STR);
				$stmt->bindParam(':app_tag',$app_tag, PDO::PARAM_STR);
				$stmt->bindParam(':app_id', $app_id, PDO::PARAM_STR);
				$stmt->bindParam(':is_feedback', $is_feedback, PDO::PARAM_STR);
				$stmt->bindParam(':feedback_email', $feedback_email, PDO::PARAM_STR);
				$stmt->bindParam(':feedback_no', $feedback_no, PDO::PARAM_STR);
				$stmt->bindParam(':is_contactus', $is_contactus, PDO::PARAM_STR);
				$stmt->bindParam(':contactus_email', $contactus_email, PDO::PARAM_STR);
				$stmt->bindParam(':contactus_no', $contactus_no, PDO::PARAM_STR);
				$stmt->bindParam(':is_tnc', $is_tnc, PDO::PARAM_STR);
				$stmt->bindParam(':is_order', $is_order, PDO::PARAM_STR);
				$stmt->bindParam(':logo_link', $logo_link, PDO::PARAM_STR);
				$stmt->bindParam(':package', $package, PDO::PARAM_STR);
				$stmt->bindParam(':orderconfirm_email', $orderconfirm_email, PDO::PARAM_STR);
				$stmt->bindParam(':orderconfirm_no', $orderconfirm_no, PDO::PARAM_STR);
				$stmt->bindParam(':tnc_link', $tnc_link, PDO::PARAM_STR);
				$stmt->bindParam(':curr_id', $curr_id, PDO::PARAM_STR);
				$stmt->execute();
		   return "success";
}
function get_cuurent_app_html($theme_id){
	$sql ="select * from themes where id=$theme_id";
	$stmt=$this->db->prepare($sql);
	$stmt->execute();
	$results=$stmt->fetch(PDO::FETCH_ASSOC);
return json_encode($results['theme_html']);	
	
}
function get_app_details($appid){
	$sql ="select * from app_data where id=$appid";
	$stmt=$this->db->prepare($sql);
	$stmt->execute();
	$results=$stmt->fetch(PDO::FETCH_ASSOC);
	return $results;
	
}
function rgb2hex($rgb) {
$rgb=substr($rgb,3); 
$rgb=ltrim ($rgb, '(');
$rgb=rtrim ($rgb, ')');
$rgb=explode(",",$rgb);
$hex = "#";
$hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
$hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
$hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);
return $hex; // returns the hex value including the number sign (#)
}
function suggest_catagory($data){
	$arr=$data['main_cat'];
	$custid=$data['custid'];
	$app_id=$data['app_id'];
	$user_deatls=$this->check_user($email='',$custid);
	$app_id_deatls=$this->get_app_details($app_id);
	$prent='';
	$main_cat='';
	$child_cat='';
	for($i=0;$i<count($arr);$i++){
		
		
		if($arr[$i]=='Parent'){
			$sugest=$data['suggest_cat'][$i];
			$main_cat.='<p>Parent Category :::: '.$sugest.'</p><br/>';
		}
		if($arr[$i]=='Child'){
		$prent=$data['parant_cat'][$i];
		$sugest=$data['suggest_cat'][$i];
		$child_cat.='<p>Child Category :::: '.$prent.'::::'.$sugest.'</p><br/>';
		}
		$type=$arr[$i];
			$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->opencart_url.'catalogue/ecommerce_catalog_api/add_catagory.php/addcatagory',
			CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'sugest_cat' =>$sugest,
				'parent' => $prent,
				'type_select' =>$type,
				'custid' => $custid
			)
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
	}	
	if($resp>0){
		$to = "dev@instappy.com,neha@instappy.com";
		$subject = "Suggest a Category";
		$htmlcontent = "<html>
		<body>
		<p>Hi Strategist,</p>
		<p>Customer Email Id::".$user_deatls['email_address']."</p><br/>
		<p>App Name::".$app_id_deatls['summary']."</p><br/>
		$main_cat<br/>
		$child_cat<br/>
		<p>Best Regards,<br />
		Team Instappy</p>
		</body>
		</html>
		";
            $body = $htmlcontent;
           
            $formemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'api_key' => $key,
                    'subject' => $subject,
                    'fromname' => 'Instappy',
                    'from' => $formemail,
                    'content' => $body,
                    'recipients' => $to
                )
            ));
            $head = curl_exec($curl);
            curl_close($curl);
            if ($head) {
                echo "success";
            } else {
                echo "fail";
            }

	}
}

	/* 
	 * code added by Arun Srivastava
	 * Added on 8/10/15
	 */
	function getDefaultCurr()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->opencart_url.'catalogue/ecommerce_catalog_api/currency.php/getcurrency');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $output = curl_exec($ch);

        curl_close($ch);
		
		return json_decode($output);
	}	
}
