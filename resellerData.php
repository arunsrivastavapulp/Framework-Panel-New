<?php
error_reporting(0);
require_once('config/db.php');
date_default_timezone_set("Asia/Kolkata");
$db = new DB();
$OpCartUrl=$db->catalogue_url();

$first_name = $_REQUEST['first_name'];
$last_name = $_REQUEST['last_name']; 
$email_address = $_REQUEST['email_address'];
$country_code = $_REQUEST['country_code'];
$mobile_number = $_REQUEST['mobile_number'];
$organization_name = $_REQUEST['organization_name'];
$website = $_REQUEST['website'];
$number_of_app = $_REQUEST['number_of_app'];
$interest = $_REQUEST['interest'];
$information = $_REQUEST['information'];
$ip_address = $_REQUEST['ip_address'];






 
	$mysqli = $db->dbconnection();
	 $insertQuery = "INSERT INTO reseller (first_name,last_name, email_address, country_code, mobile_number, organization_name,website,number_of_app,interest,ip_address,information,created) VALUES ('".$db->xss_clean($first_name)."','".$db->xss_clean($last_name)."','".$db->xss_clean($email_address)."', '".$db->xss_clean($country_code)."', '".$db->xss_clean($mobile_number)."','".$db->xss_clean($organization_name)."', '".$db->xss_clean($website)."','".$db->xss_clean($number_of_app)."','".$db->xss_clean($interest)."','".$db->xss_clean($ip_address)."', '".$db->xss_clean($information)."',NOW())";
	$stmtPrepared = $mysqli->prepare($insertQuery);
	$success = $stmtPrepared->execute(); 
	$cr_date=date("Y-d-m h:i:s");
	$up_date=date("Y-d-m h:i:s");
	$stamp = date("Ymdhis");
	$cust_id = strtotime($stamp);
	
	$verification_token=$cust_id.uniqid();
	
	
		/////insert into reseller_signup
$resellerSign="insert into `reseller_signup` 
	(`hash_id`, 
	`avtar`, 
	`first_name`, 
	`last_name`, 
	`created_at`, 
	`updated_at`, 
	`email_address`, 
	`agent_password`, 
	`remember_token`, 
	`alternate_email`, 
	`phone_country_code`, 
	`phone_no`, 
	`mobile_country_code`, 
	`mobile_no`, 
	`fax`, 
	`company_name`, 
	`pincode`, 
	`country`, 
	`state`, 
	`city`, 
	`website`, 
	`otp_code_mobile_no`, 
	`is_verified`, 
	`mobile_verified`, 
	`deleted`, 
	`ip_address`, 
	`is_premium`, 
	`premium_upto`, 
	`crm_roleid`, 
	`tax_registration_no`,
	`verify_token`
	)
	values
	('', 
	'', 
	'".$db->xss_clean($_POST['first_name'])."', 
	'".$db->xss_clean($_POST['last_name'])."', 
	'".$db->xss_clean($cr_date)."', 
	'".$db->xss_clean($up_date)."', 
	'".$db->xss_clean($_POST['email_address'])."', 
	'', 
	'', 
	'', 
	'".$db->xss_clean($_POST['country_code'])."', 
	'".$db->xss_clean($_POST['mobile_number'])."', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'".$db->xss_clean($_POST['ip_address'])."', 
	'', 
	'', 
	'', 
	'',
	'".$verification_token."'
	);";
	
	$stmtPrepared_reselSign = $mysqli->prepare($resellerSign);
	$success_reseller_sign = $stmtPrepared_reselSign->execute();
	$lastId_success_reseller_sign = $mysqli->lastInsertId();
	//echo $order_id = $success_reseller_sign->insert_id;
	
	/// insert into author
	 $InsertAuthor="insert into `author` 
	(`plan_id`, 
	`plan_expiry_date`, 
	`fb_token`, 
	`twitter_token`, 
	`gPlus_token`, 
	`created`, 
	`updated`, 
	`first_name`, 
	`last_name`, 
	`email_address`, 
	`password`, 
	`phone_no`, 
	`country`, 
	`state`, 
	`city`, 
	`street`, 
	`pincode`, 
	`active`, 
	`ip_address`, 
	`deleted`, 
	`auth_token`, 
	`fbid`, 
	`avatar`, 
	`organisation_name`, 
	`organisation_size`, 
	`category`, 
	`alternate_email`, 
	`fax`, 
	`mobile_country_code`, 
	`mobile`, 
	`website`, 
	`custid`, 
	`is_confirm`, 
	`verification_code`, 
	`otpcode`, 
	`mobile_validated`, 
	`otptime`, 
	`tracking_email_id`, 
	`gmail_verification_code`, 
	`gamil_confirm`, 
	`mid`, 
	`product_prefereed`, 
	`theme_preferred`, 
	`source`, 
	`api_key`, 
	`sms_sent`, 
	`reseller_signup_id`
	)
	values
	('1', 
	'', 
	'', 
	'', 
	'', 
	'".$cr_date."', 
	'".$up_date."', 
	'".$db->xss_clean($_POST['first_name'])."', 
	'".$db->xss_clean($_POST['last_name'])."',  
	'".$db->xss_clean($_POST['email_address'])."',
	'".md5($_POST['password_n'])."',
	'".$db->xss_clean($_POST['mobile_number'])."',
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'".$db->xss_clean($_POST['ip_address'])."',
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'".$db->xss_clean($_POST['mobile_number'])."', 
	'', 
	'".$cust_id."', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'', 
	'".$db->xss_clean($lastId_success_reseller_sign)."'
	);
";

$stmtPrepared = $mysqli->prepare($InsertAuthor);
	$success_reseller = $stmtPrepared->execute(); 

	
	if ($success) {
	
	//client email start
	/*echo	$csubject = "Thank you for reaching out to the Instappy team!";
        $basicUrl = $db->siteurl();

        $chtmlcontent = file_get_contents('edm/reseller.php');

        $chtmlcontent = str_replace('{customer_name}', ucwords($first_name) . ",", $chtmlcontent);
        $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
        $chtmlcontent = str_replace('{blog_url}', $basicUrl . 'blog', $chtmlcontent);

        $cformemail = 'noreply@instappy.com';
        $key = 'f894535ddf80bb745fc15e47e42a595e';
 
        $curl1 = curl_init();
        curl_setopt_array($curl1, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'api_key' => $key,
                'subject' => $csubject,
                'fromname' => 'Instappy',
                'from' => $cformemail,
                'content' => $chtmlcontent,
                'recipients' => $email_address
            )
        ));
        $customerhead = curl_exec($curl1);

        curl_close($curl1);
*/



	$csubject = "Thank you for reaching out to the Instappy team!";
        //$basicUrl = $db->siteurl();
       //  $verificationURL="http://52.41.29.218/crm_reseller/verifyreseller";
	   
	   $verificationURL="http://52.42.166.139/verify-mail.php?vtoken=".$verification_token;
       
		//$to = "harsh@pulpstrategy.com";
		//$to = "ravi.tiwari@pulpstrategy.com";
		//$subject = "Reseller";
		$htmlcontent = "<html>
		<body>
		<p>Hi " . $first_name.' '.$last_name. ",</p>		
		
		<p>Email :: " . $email_address . "</p>
		<p>Phone No :: " . $country_code . " - ".$mobile_number."</p>
		<p>Organisation Name :: " . $organization_name . "</p>
		<p>Website :: " . $website . "</p>
		<p>How many apps are you looking to bulid :: " . $number_of_app . "</p>
		<p>What best defines your organizations interest in mobile apps:: " . $interest . "</p>
		<p>Message :: " . $information . "</p>
        <p>Please click/copy below link to validate</p>
		<p>".$verificationURL."</p>
		<p>Best Regards,<br />
		Team Instappy</p>
		</body>
		</html>
		";
            $body = $htmlcontent;

        $cformemail = 'noreply@instappy.com';
        $key = 'f894535ddf80bb745fc15e47e42a595e';
 
        $curl1 = curl_init();
        curl_setopt_array($curl1, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'api_key' => $key,
                'subject' => $csubject,
                'fromname' => 'Instappy',
                'from' => $cformemail,
                'content' => $body,
                'recipients' => $email_address
            )
        ));
        $customerhead = curl_exec($curl1);

        curl_close($curl1);

	//client email end
	//to Instappy Team
	 
		$to = "contact@instappy.com";
		//$to = "harsh@pulpstrategy.com";
		//$to = "ravi.tiwari@pulpstrategy.com";
		$subject = "Reseller";
		$htmlcontent = "<html>
		<body>
		<p>Hi Strategist,</p>		
		<p>First Name :: " . $first_name . "</p>
		<p>Last Name :: " . $last_name . "</p>
		<p>Email :: " . $email_address . "</p>
		<p>Phone No :: " . $country_code . " - ".$mobile_number."</p>
		<p>Organisation Name :: " . $organization_name . "</p>
		<p>Website :: " . $website . "</p>
		<p>How many apps are you looking to bulid :: " . $number_of_app . "</p>
		<p>What best defines your organizations interest in mobile apps:: " . $interest . "</p>
		<p>Message :: " . $information . "</p>

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
			
			//to Instappy Team end

        } 
		
		$data=array('company'=>'  ', 'address_1'=>$db->xss_clean($_POST['Address']),'city'=>'  ','postcode'=>'  ','country_id'=>'  ','zone_id'=>'  ','confirm'=>$_POST['password_n'],'password'=>$_POST['password_n'],'appid'=>'  ','paypal'=>$db->xss_clean($email_address),'firstname'=>$db->xss_clean($_POST['first_name']),'lastname'=>$db->xss_clean($_POST['last_name']),'email'=>$db->xss_clean($email_address),'telephone'=>$db->xss_clean($mobile_number),'username'=>$db->xss_clean($email_address));
		
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL =>  $OpCartUrl.'catalogue/ecommerce_catalog_api/index.php/vendorregister',
			CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $data
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		print_r($resp);
		// Close request to clear up some resources
		curl_close($curl);
		$output=json_decode($resp);		
		  $code=$output->response->errorCode;
		  
	if($code==405){
		echo $output->response->errorMsg;
		
	}
	else if($output->response->code==200){
		echo "success";
	}
	else{
		echo "Opps something went wrong.please try again later.";
	}

?>