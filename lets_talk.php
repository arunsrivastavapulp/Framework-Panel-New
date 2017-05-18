<?php
require_once('config/db.php');
$db = new DB();
$db->xss_clean($data);
session_start();
if($_SESSION['username'] != ''){
	require_once('modules/user/userprofile.php');
	$userprofile = new UserProfile();
	$user = $userprofile->getUserByEmail($_SESSION['username']);
}
else{
	$user = array();
	$user['id'] = '';
	$user['custid'] = '';
}
if(isset($_POST))
{
    
	$i=1;
	$time = date('');
	/*foreach($_POST as $field=>$value){
		$sql = "INSERT INTO wp_cf7dbplugin_submits (submit_time,form_name, field_name, field_value,field_order) VALUES ('".$time."','Lets Talk', '".$field."', '".$value."','".$i."')";
		$stmt = $wpdb->query($sql);
		$i++;
	}*/
	$usersId=$user['id'];
	$letstalkname=$db->xss_clean($_POST['lets_talk_name']);
	$letstalkmail=$db->xss_clean($_POST['lets_talk_email']);
	$lets_talk_org=$db->xss_clean($_POST['lets_talk_org']);
	$lets_talk_org_size=$db->xss_clean($_POST['lets_talk_org_size']);
	$letstalkphone=$db->xss_clean($_POST['lets_talk_phone']);
	$letsTalkIndustry=$db->xss_clean($_POST['lets_talk_industry']);
	$letsTalkAppType=$db->xss_clean($_POST['lets_talk_app_type']);
	$letsTalkAdditional=$db->xss_clean($_POST['lets_talk_additional']);
	$urlsource=$db->xss_clean($_POST['urlsource']);
	$mobile_country_code1 = $db->xss_clean(substr($_POST['mobile_country_code'],1));        
	$mobile_country_code2 = $db->xss_clean(substr($_POST['mobile_country_code2'],1));
        if($mobile_country_code1!=''){
            $mobile_country_code=$mobile_country_code1;
        } else{
            $mobile_country_code=$mobile_country_code2;
        }
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
	
	$mysqli = $db->dbconnection();
	$sql = "INSERT INTO contact_us_details (author_id, type, name, email, phone,organisation,Organisation_size,Industry,App_type,Additional,created,CustId,Source,is_subscribed,mobile_country_code,ip_address) VALUES ('".$usersId."',1,'".$letstalkname."', '".$letstalkmail."', '".$letstalkphone."','".$lets_talk_org."','".$lets_talk_org_size."','".$letsTalkIndustry."','".$letsTalkAppType."','".$letsTalkAdditional."',NOW(),'".$user['custid']."', '".$urlsource."','".$_POST['agree']."','".$mobile_country_code."','".$ip_address."')";
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	
	/* Mail to customer */
	//$to      = $_POST['lets_talk_email'];
	$subject = 'Thank you for your enquiry';
	/*
	$message = 'Thank you for your enquiry, we got you! A Strategist will get back to you as early as possible. 

In the meanwhile you can take a quick preview of our work <a href="http://youtu.be/m-BIb0leHuQ">HERE</a>.  Or simply  Join The conversation! @pulpstrategy we would love to tweet with you! 

We appreciate your patience, Have a great day!.

Regards,
Team Pulp Strategy';
	
	$headers = 'From: contact@instappy.com' . "\r\n" .
		'Reply-To: contact@instappy.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$formemail = 'contact@instappy.com';
	$myto 	   = $to;
	$key       = 'f894535ddf80bb745fc15e47e42a595e';
	$url       = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($subject).'&fromname='.rawurlencode($subject).'&from='.$formemail.'&content='.rawurlencode($message).'&recipients='.$myto;
	file_get_contents($url);
	*/
	//wp_mail($to, $subject, $message, $headers);
	$cto        = $_POST['lets_talk_email'];
	$csubject   = 'Thank you for reaching out to the Instappy team!';
	$cformemail = 'noreply@instappy.com';
	$key        = 'f894535ddf80bb745fc15e47e42a595e';
	$basicUrl   = $db->siteurl();
	
	$chtmlcontent = file_get_contents('edm/lets_talk.php');
	//$clastname = $data['last_name'] != '' ? ' ' . $data['last_name'] : $data['last_name'];
	$cname = $_POST['lets_talk_name'];
	$chtmlcontent = str_replace('{customer_name}', ucwords($cname).",", $chtmlcontent);
	$chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
	
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
	
	
	/* Mail to Admin */
	//$to      = 'contact@instappy.com';
    $to      = 'contact@instappy.com';

    
    
	$subject = 'Lets Talk Enquiry';
	$message = 'Below are the details filled in Lets Talk Enquiry form:'. "\r\n";

	$message .= 'Name: '.$_POST['lets_talk_name'].' '.$_POST['lastname']. "\r\n";

	$message .= 'Email: '.$_POST['lets_talk_email']. "\r\n";

	$message .= 'Phone: '.$_POST['lets_talk_phone']. "\r\n";

	$message .= 'Organization: '.$_POST['lets_talk_org']. "\r\n";

	$message .= 'Organization Size: '.$_POST['lets_talk_org_size']. "\r\n";
	
	$message .= 'Industry: '.$_POST['lets_talk_industry']. "\r\n";
	
	$message .= 'App Type: '.$_POST['lets_talk_app_type']. "\r\n";

	$message .= 'Message: '.$_POST['lets_talk_additional']. "\r\n";
	$headers = 'From: '.$_POST['email']. "\r\n" .
		'Reply-To: '.$_POST['email'] . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	//wp_mail($to, $subject, $message, $headers);
	$myto 	   = 'contact@instappy.com';
	//$myto = "ravi.tiwari@pulpstrategy.com";

	$url       = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($subject).'&fromname='.rawurlencode('Instappy').'&from='.$formemail.'&content='.rawurlencode($message).'&recipients='.$myto;
	file_get_contents($url);
//	echo "Success";
        
       // $mainurl = 'http://182.74.47.179/SQLServer.aspx?Process=Pulp&Campaign=Pulp&PhoneNo='.$_POST['lets_talk_phone'].'&SetCallBack=1&IsFilewise=1&First_name='.$_POST['lets_talk_name'].' '.$_POST['lastname'].'&Email='.$_POST['lets_talk_email'].'&Organisation='.$_POST['lets_talk_org'].'&Organisation_Size='.$_POST['lets_talk_org_size'].'&Industry='.$_POST['lets_talk_industry'].'&App_type='.$_POST['lets_talk_app_type'].'&CustomerRemark='.$_POST['lets_talk_additional'].'&Source='.$_POST['urlsource'];
    	$date = date('Y/m/d h:i:s', time());
        $customerid = '';
        $countryCode = $_POST['country_code'];
	$countryName = $_POST['country_name'];
	$code_name = $countryName.'(+'.$countryCode.')';
        
       //$mainurl = 'http://182.74.47.179/SQLServer.aspx?Process=Pulp&Campaign=Pulp&PhoneNo=' . $mobile_number . '&SetCallBack=1&IsFilewise=1&Source=SignUP&CallAgainType=SignUpCall&First_Name=' . $first_name . '&last_name=' . $last_name . '&Organisation=' . $company_name . '&Email=' . $username . '&App_type=' . $author_product . '&App_Template=' . $author_product_category . '';
       // $mainurl = 'http://192.168.75.173/universus/pulp_signup_api.php?customerid='.$customerid.'&fname=' . $_POST['lets_talk_name'] . '&lname=' . $_POST['lastname'] . '&organisation_name=' . $_POST['lets_talk_org'] . '&email=' . $_POST['lets_talk_email'] . '&mobileno=' . $_POST['lets_talk_phone'] . '&app_type=' . $_POST['lets_talk_app_type'] . '&app_template=' . $_POST['lets_talk_industry'] . '&created_date='.$date.'&type=LETSTALK';
       //$mainurl = 'http://182.74.47.179/universus/pulp_signup_api.php?customerid='.$customerid.'&fname=' . $_POST['lets_talk_name'] . '&lname=' . $_POST['lastname'] . '&organisation_name=' . $_POST['lets_talk_org'] . '&email=' . $_POST['lets_talk_email'] . '&mobileno=' . $_POST['lets_talk_phone'] . '&app_type=' . $_POST['lets_talk_app_type'] . '&app_template=' . $_POST['lets_talk_industry'] . '&created_date='.$date.'&type=LETSTALK';
	
        $mainurl = 'http://182.74.47.179/universus/pulp_signup_api_new.php?customerid='.$customerid.'&fname=' . $_POST['lets_talk_name'] . '&lname=' . $_POST['lastname'] . '&company='.$_POST['lets_talk_org'].'&email='.$_POST['lets_talk_email'].'&mobileno='.$_POST['lets_talk_phone'].'&app_type='.$_POST['lets_talk_app_type'].'&app_template='.$_POST['lets_talk_industry'].'&created_date='.$date.'&type=LETSTALK&organisation_size='.$_POST['lets_talk_org_size'].'&industry='.$_POST['lets_talk_industry'].'&cust_remarks='.$_POST['lets_talk_additional'].'&ccode='.$code_name.''; 
         
        $url = str_replace(' ', '-', $mainurl);

        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));
        $head2 = curl_exec($curl2);
        curl_close($curl2);

            echo "Success";  
}
else
{
	echo "Error";
}
?>