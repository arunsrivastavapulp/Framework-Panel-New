<?php
/* 
	Functionality : Statics
	Author : Prem
*/

require_once ('config/db.php');

class Statics extends Db{
	 
	var $db,$url;
	function __construct(){
		$this->db=$this->dbconnection();
		$this->url=$this->siteurl();
	}
	
	public function count_platform($app_id,$platform){	
			 $sql ="SELECT count(*) as total FROM user_appdata WHERE app_id=$app_id and platform=$platform";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			return $results['total'];
				
	}
	public function stataics_tables($user_id){
		$num_rec_per_page=10;
		if (isset($_GET["nextpage"])) { $page  = $_GET["nextpage"]; } else { $page=1; }; 
		$start_from = ($page-1) * $num_rec_per_page; 
		$sql ="SELECT id,summary ,req_analytics FROM app_data WHERE author_id=$user_id AND published=1 order by id DESC limit $start_from, $num_rec_per_page ";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$str='';
		$url=$this->url;
		$sql ="SELECT count(*) as total FROM app_data WHERE author_id=$user_id AND published=1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);				
		if(count($results)>0){
				$str.='<ul class="tabl-stat">
			  <li style=" background:#ffcc00" class="hdstat">
				<span>S.no.</span>
				<span>App Name</span>
				<span>iOS</span>
				<span>Android</span>
				<span>Total</span>
				<span>Analytics Request</span>
			</li>';
			$i=1;
					foreach($results as $val){
						if($val['req_analytics']==1){
							$request='Pending/Under Process';
						}
						else if($val['req_analytics']==2){
							$request='Completed';
						}
						else if($val['req_analytics']==3){
							$request='Rejected';
						}
						else{
							$request='<a href="Javascript:void(0);" onclick="send_request('.$val['id'].');">Request</a>';	
						}
					  $str.= '<li style="">
				<span>'.$i.'</span>
				<span>'.$val['summary'].'</span>
				<span>'.$this->count_platform($val['id'],2).'</span>
				<span>'.$this->count_platform($val['id'],1).'</span>
				<span>'.($this->count_platform($val['id'],1)+ $this->count_platform($val['id'],2)).' </span>
				<span id="request_'.$val['id'].'">'.$request.'</span>
			</li>';
			   $i++;
					}

			$str.='</ul>';

			$total_records = $res['total']; 
			$total_pages = ceil($total_records / $num_rec_per_page); 
			if($total_records>$num_rec_per_page)
			{
			$str.="<div class='pagination'>";
			$str.="<a href=".$url."statistics.php?nextpage=1>".'<<'."</a> "; // Goto 1st page  

			for ($j=1; $j<=$total_pages; $j++) { 
					$str.="<a href=".$url."statistics.php?nextpage=".$j.">".$j."</a> "; 
			}; 
			$str.="<a href=".$url."statistics.php?nextpage=$total_pages>".'>>'."</a> "; // Goto last page
			$str.='</div>';
			}
		 }
	return 	$str;
	}
	public function get_userdetails($custid){
		$sql ="SELECT *  FROM author WHERE custid=$custid";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		return $results;
	}
	function add_google_acc($data){
			$gmail=$data['gmail'];
			$id=$data['uid'];
			$publish_apps=$this->user_published_apps($id);
			if(count($publish_apps)>0){
					$uid = uniqid();
					$confirm=0;
					$sql = "UPDATE author SET 
					tracking_email_id = :tracking_email_id,
					gmail_verification_code = :gmail_verification_code,
					gamil_confirm = :gamil_confirm
					WHERE id = :id";
					$stmt = $this->db->prepare($sql);
					$stmt->bindParam(':tracking_email_id', $gmail, PDO::PARAM_STR);
					$stmt->bindParam(':gmail_verification_code', $uid, PDO::PARAM_STR);
					$stmt->bindParam(':gamil_confirm', $confirm, PDO::PARAM_INT);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					if ($stmt->execute()) {	
					//mail for user start
					$to = $gmail;
					$subject = "Validate Gmail Id for Analytics";
					/*
					$htmlcontent = "<html><body>
						<p>Hi,</p>		
						<p>Validate URL :: ".$this->url.'signup-verification.php?gcode='.$uid."</p>
						<p>Best Regards,<br />
						Team Instappy</p>
						</body>
						</html>
						";
					*/
					$htmlcontent  = file_get_contents('./edm/analytics_data.php');
					$gaurl        = $this->url.'signup-verification.php?gcode='.$uid;
					$htmlcontent  = str_replace('{analytics_link}', $gaurl, $htmlcontent);
					$htmlcontent  = str_replace('{base_url}', $this->url, $htmlcontent);
					
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
					//mail for user end
					
					// mail for Admin start		
					
					$to = "dev@instappy.com";
					//$to = "prem@pulpstrategy.com";
					$subject = "Addition of Gmail Id for Analytics";
					$htmlcontent = "<html><body>
						<p>Hi Instappy Team,</p>		
						".$this->get_all_publish_apps($id)."
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
						
						// Mail for admin end
					}
					else{
						
						echo "fails";
					}
			}
			else{
				echo "no_publish";
			}
	}		
function gmail_verification($code){
			$sql = "select id from author where gmail_verification_code='$code'";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			if($results['id']>0){
				$confirm=1;
				$sql = "UPDATE author SET 
				gamil_confirm = :gamil_confirm
				WHERE gmail_verification_code = :gmail_verification_code";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(':gamil_confirm', $confirm, PDO::PARAM_INT);
				$stmt->bindParam(':gmail_verification_code', $code, PDO::PARAM_STR);
				if ($stmt->execute()) {	
				return "valid";
				}
				else{
				return "invalid";	
				}
			}
			else{
				return "invalid";
			}
	}
	function get_app_details($app_id){
		$sql = "select * from app_data where id=$app_id";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			return $results;
		
	}
	function get_all_publish_apps($user_id){
			$sql = "select custid,tracking_email_id from author where id=$user_id";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);		
			$str='';
			$str.='<p>The '.$results['custid'].' added '.$results['tracking_email_id'].' for analytics</p><br/>';
			$str.='<table border="1"><tr><th>App Id</th><th>App Name</th><th>Analytics Id</th></tr>';
			$resultsapp=$this->user_published_apps($user_id);
			foreach($resultsapp as $val){	
			$acode=$this->analytics_code($val['id']);			
				$str.='<tr><td>'.$val['id'].'</td><td>'.$val['summary'].'</td><td>'.$acode['analytics_id'].'</td></tr>';
			}
			$str.='</table>';
		return $str; 
	}
	function user_published_apps($user_id){
		$sql = "select id,summary,analytics_code from app_data where  author_id=$user_id AND published=1";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$resultsapp = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $resultsapp;
	}
	function analytics_code($app_id){		
			$sql = "SELECT analytics_id FROM app_analytics_mapping
				WHERE app_id =$app_id";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			return $results;
		
	}
	function check_gmail_exist($author_id){
			$sql = "select * from author where id=$author_id";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);	
			return $results;
	}
	function send_analytics_request($data){		
		$app_id=$data['app_id'];
		$user_id=$data['uid'];
		$app_details=$this->get_app_details($app_id);
		$results=$this->check_gmail_exist($user_id);
		if(!empty($results['tracking_email_id'])){
			if($results['gamil_confirm']>0){
				$uniqid = uniqid();
				$req_analytics=1;
				$sql = "UPDATE app_data SET 
				req_analytics_code = :req_analytics_code,
				req_analytics = :req_analytics
				WHERE id = :id";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(':req_analytics_code', $uniqid, PDO::PARAM_STR);
				$stmt->bindParam(':req_analytics', $req_analytics, PDO::PARAM_STR);
				$stmt->bindParam(':id', $app_id, PDO::PARAM_INT);
				if ($stmt->execute()) {	
					//$to = "dev@instappy.com";
					$to = "tarunc@pulpstrategy.com";
					$subject = "Analytics Request For App Name '".$app_details['summary']."'";
					//$htmlcontent  = file_get_contents('./edm/analytics_data.php');
					$gaurl        = $this->url.'signup-verification.php?approve_gcode='.$uniqid;
					$rejected_url        = $this->url.'signup-verification.php?rejected_request='.$uniqid;
					$htmlcontent = "<html><body>
						<p>Hi Instappy Team,</p>		
						<p>Analytics Request For </p>
						<p>Author Name:<strong>".$results['first_name']."</strong></p>
						<p>Author CustID:<strong>".$results['custid']."</strong></p>
						<p>App Name:<strong>".$app_details['summary']."</strong></p>
						<p>App ID:<strong>".$app_details['app_id']."</strong></p>
						<p><a href =".$gaurl.">Accept</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=".$rejected_url.">Reject</a></p>
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
				else{
				echo  "fails";	
				}
			}
			else{
			echo "gamil_not_confirm";
			}
			
		}
		else{
			echo "gmail_not_exist";
		}
	}
	function approve_analytics_request($approve_code){
		$sql = "select author_id,id,summary,app_id from app_data where req_analytics_code='$approve_code'";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			if($results['id']>0){
				$confirm=2;
				$sql = "UPDATE app_data SET 
				req_analytics = :req_analytics
				WHERE req_analytics_code = :req_analytics_code";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(':req_analytics', $confirm, PDO::PARAM_INT);
				$stmt->bindParam(':req_analytics_code', $approve_code, PDO::PARAM_STR);
				if ($stmt->execute()) {
					$author_details=$this->check_gmail_exist($results['author_id']);
					$to = $author_details['tracking_email_id'];
					//$to = "prem@pulpstrategy.com";
					$subject = "Analytics Request Approved ";
					$htmlcontent = "<html><body>
						<p>Dear ".$author_details['first_name'].",</p>		
						<p>Analytics Request Approved </p>
						<p>App Name:<strong>".$results['summary']."</strong></p>
						<p>App ID:<strong>".$results['app_id']."</strong></p>
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
				return "approve_valid";
				}
				else{
				return "approve_invalid";	
				}
			}
			else{
				return "approve_invalid";
			}
	}
	function rejected_analytics_request($approve_code){
		$sql = "select author_id,id,summary,app_id from app_data where req_analytics_code='$approve_code'";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			if($results['id']>0){
				$confirm=3;
				$sql = "UPDATE app_data SET 
				req_analytics = :req_analytics
				WHERE req_analytics_code = :req_analytics_code";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(':req_analytics', $confirm, PDO::PARAM_INT);
				$stmt->bindParam(':req_analytics_code', $approve_code, PDO::PARAM_STR);
				if ($stmt->execute()) {
					$author_details=$this->check_gmail_exist($results['author_id']);
					$to = $author_details['tracking_email_id'];
					//$to = "prem@pulpstrategy.com";
					$subject = "Analytics Request Rejected ";
					$htmlcontent = "<html><body>
						<p>Dear ".$author_details['first_name'].",</p>		
						<p>Analytics Request Rejected </p>
						<p>App Name:<strong>".$results['summary']."</strong></p>
						<p>App ID:<strong>".$results['app_id']."</strong></p>
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
				return "rejected_valid";
				}
				else{
				return "rejected_invalid";	
				}
			}
			else{
				return "rejected_invalid";
			}
	}
}	