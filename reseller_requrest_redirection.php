<?php
 require_once('website_header.php');
 require_once 'security/Security.php'; 

	$security = new Security();
 $code=isset($_GET['url'])?$_GET['url']:'';
 $url_info=base64_decode($code);
 $url_info=json_decode($url_info);
/* echo "<pre>";
print_r( $url_info);*/

 $app_id = $security->decrypt(base64_decode($url_info->app_id),$url_info->key);

  $author_id = $security->decrypt(base64_decode($url_info->author_id),$url_info->key);

$reseller_id = $security->decrypt(base64_decode($url_info->reseller_id),$url_info->key);
 $return_url = $security->decrypt(base64_decode($url_info->return_url),$url_info->key);

$_SESSION['reseller_id']=$reseller_id;
$_SESSION['return_url']=$return_url;


 
?>
<!DOCTYPE html>
<html>

<style>

	.main-container { background-color: #f8f8f8; }
	#screenoverlay { display: none; }
	.content-section { text-align: center; padding: 30px 0 70px; }
	.content-section h1 { font-size: 42px; margin-bottom: 20px; font-weight: normal; color: #373e46; }
	.content-section h3 {  font-size: 28px; margin-bottom: 5px; font-weight: normal; color: #5e6c77; }
	.content-section p { font-size: 22px; color: #5e6c77; }
	.footer-container { margin-top: 150px; background-color: #4e5e6b; min-height: 160px; position: relative; }
	.footer-container .img-frame { margin-left: -270px; position: absolute; width: 539px; height: 321px; position: absolute; left: 50%; top: -190px; background: url(images/img-frame.png) center top no-repeat; background-size: 90%; }
	.footer-container .img-frame img { position: absolute; left: 68px; top: 14px; width: 381px; height: 187px; display: block; border-radius: 5px; }
	.footer-container img.oops { display: block; width: 100%;  }

@media (max-width: 640px){
	.content-section { padding-bottom: 0; }
	.content-section h1 { font-size: 36px; }
	.content-section h3 { font-size: 24px; }
	.content-section p { font-size: 18px; }
	.footer-container.mob-view { min-height: 100px; }
	.footer-container .img-frame { margin-left: -50%; width: 100%; height: 173%; top: -110px; background-size: contain; }
	.footer-container .img-frame img { left: 50%; top: 10px; width: 226px; height: 110px; margin-left: -120px; }
}	

</style>
<body>

<div class="clear"></div>

<?php

if(!empty($url_info)){
 	require_once('modules/login/login-check.php');
 	$login=new Login();	
 	$user_details=$login->getUserByCustId(0,$author_id);
 	$data['username']=$user_details['email_address'];
 	$data['password']=$user_details['password'];
	$result_login=$login->check_autologin($data,$reseller_id);
	$final_user_info=explode("##", $result_login);
	if($final_user_info[0]=='success'){
	// echo '<p class="validcode">Request is proccessing please wait.....</p>';
	echo '<div class="main-container" style="font-family: Myriad Pro;">

 		<div class="content-section">
 			<h1>Please Wait!</h1>
 			<h3>You are being redirected to another page.</h3>
 			<p>It may take a few seconds, thank you for your patience.</p>
 		</div>
 		<div class="footer-container mob-view">
 			<div class="img-frame"><img src="images/redirection.gif" alt="redirection" ></div>
 		</div>
 	</div>';
	if($app_id){
		echo '<meta http-equiv="refresh" content="0;url=appedit.php?appid='.$app_id.'" />';
	}
	else{
		echo '<meta http-equiv="refresh" content="0;url=themes.php" />';
	}

	}
	else{

	// echo '<p class="invalidcode" >Opps Something went wrong . Please try again.</p>';
	echo '<div class="main-container">

 		<div class="content-section">
 			<h1>Opps!</h1>
 			<h3>Something went wrong . Please try again.</h3> 			
 		</div>
 		<div class="footer-container" style="margin: 0; margin-top: 25px; min-height: auto;">
 			<img class="oops" src="images/oops.jpg" >
 		</div>
 	</div>';
	}
 }
 else{

 	echo '<div class="main-container">

 		<div class="content-section">
 			<h1>Opps!</h1>
 			<h3>Something went wrong . Please try again.</h3> 			
 		</div>
 		<div class="footer-container" style="margin: 0; margin-top: 25px; min-height: auto;">
 			<img class="oops" src="images/oops.jpg" >
 		</div>
 	</div>';
 }


?>

<!--<p class="invalidcode" >This link has expired. Please try again.</p>
<meta http-equiv="refresh" content="10;url=<?php //echo $basicUrl; ?>" />-->


<style>
.logo_nav.fix {
    position: static !important;
}
</style>

<!-- Google Code for International Instappy Campaign Conversion Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 941023354;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "EqvBCLPy_2AQ-sDbwAM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/941023354/?label=EqvBCLPy_2AQ-sDbwAM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/942185908/?label=tfQXCNDR5F8QtLuiwQM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

</body>
</html>


<?php require_once('website_footer.php');?>
