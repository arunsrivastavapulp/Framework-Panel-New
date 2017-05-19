<?php
 require_once('website_header.php');
require_once('config/db.php');
date_default_timezone_set("Asia/Kolkata");
require_once('modules/login/resellerCheck.php');
	$check=new ResellerCheck();
	$res=$check->verify_email($_GET['vtoken']);
		//print_r($res);
		
		//die;
?> 
<div class="clear"></div>
<div class="brdcrm">
  <div class="big-cont mt0">
    <div class="crm"> <a href="#" class="active-crum">Thank You</a> </div>
  </div>
</div>

<div class="big-cont mt0 suc">
<div class="thanksdiv">
<?php
if($res !=0)
{
		$to = "prem@pulpstrategy.com";
		$subject = "Reseller Link Verification ";
		$htmlcontent = "<html>
		<body>
		<p>Hi Strategist,</p>		
		<p>First Name :: " . $res['first_name']. "</p>
		<p>Email :: " . $res['email_address'] . "</p>
		<p>Phone No :: " . $res['phone_country_code'] . " - ".$res['phone_no']."</p>
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
?>
Thank you for verifying your E-mail.<br/><br/>
<meta http-equiv="refresh" content="5; url=<?php echo $basicUrl; ?>" />

<?php
}
else
{
?>
Link has expired.
<meta http-equiv="refresh" content="5; url=<?php echo $basicUrl; ?>" />
<?php
}
?>
</div>
<div class="button-left"><a href="<?php echo $basicUrl; ?>">Go to Home</a></div>
</div>

<style>
.logo_nav.fix {
    position: static !important;
}
</style>
<!--<script type="text/javascript">
	$(window).load(function(){

		setInterval(function(){
			window.location="index.php";
		},10000)
	});-->
</script>



<?php require_once('website_footer.php');?>
