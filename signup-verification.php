<?php
 require_once('website_header.php');
 $code=isset($_GET['verification'])?$_GET['verification']:'';
 $gcode=isset($_GET['gcode'])?$_GET['gcode']:'';
 $approve_gcode=isset($_GET['approve_gcode'])?$_GET['approve_gcode']:'';
 $rejected_gcode=isset($_GET['rejected_request'])?$_GET['rejected_request']:'';
 if(!empty($code)){
 require_once('modules/login/login-check.php');
 $login=new Login();	
 $results=$login->signup_verification($code); 
	$array=explode('##',$results);
	if($array[3]=='catalogue'){
	$page=	'catalogue.php';		
	}
	else{
	$page=	'panel.php';
	}
	
 }
 if(!empty($gcode)){
	require_once('modules/user/statistic.php');
	$statics=new Statics();	
	 $results=$statics->gmail_verification($gcode); 
 }
  if(!empty($approve_gcode)){
	require_once('modules/user/statistic.php');
	$statics=new Statics();	
	$results=$statics->approve_analytics_request($approve_gcode); 
 }
   if(!empty($rejected_gcode)){
	require_once('modules/user/statistic.php');
	$statics=new Statics();	
	$results=$statics->rejected_analytics_request($rejected_gcode); 
 }
?>

<div class="clear"></div>
<div class="brdcrm">
  <div class="big-cont mt0">
    <div class="crm"> <a href="index.php">Home</a><span> >> </span><a href="#" class="active-crum">
	<?php if($gcode)echo "Verification"; else if($approve_gcode)  echo "Request Approve"; else if($rejected_gcode)  "Request Reject"; else echo "Register"; ?></a> </div>
  </div>
</div>
<div class="clear"></div>
<div class="big-cont mt0 suc">
<?php if($array[0]=='success'){?>
<p class="validcode">Thank you for the verification. Start creating awesome apps with Instappy.</p>
<meta http-equiv="refresh" content="5;url=<?php echo $basicUrl.$page.'?themeid='.$array[1].'&catid='.$array[2].'&app=create'; ?>" />
<?php }
 else if($results=='valid'){?>
<p class="validcode">Thank you for validating your GmailID. You will be redirected to stats page within 10 Seconds.</p>
<?php if(!empty($_SESSION['custid'])){?>
<meta http-equiv="refresh" content="10;url=<?php echo $basicUrl; ?>statistics.php" />
<?php 
}
else{?>
<meta http-equiv="refresh" content="10;url=<?php echo $basicUrl; ?>" />
<?php
}
} 
else if($results=='approve_valid'){?>
<p class="validcode">Request has been Approved. You will be redirected to stats page within 10 Seconds.</p>
<meta http-equiv="refresh" content="10;url=<?php echo $basicUrl; ?>" />
<?php
}
else if($results=='rejected_valid'){?>
<p class="validcode">Request has been Rejected.. You will be redirected to stats page within 10 Seconds.</p>
<meta http-equiv="refresh" content="10;url=<?php echo $basicUrl; ?>" />
<?php
}
else{
?>
<p class="invalidcode" >This link has expired. Please try again.</p>
<meta http-equiv="refresh" content="10;url=<?php echo $basicUrl; ?>" />
<?php }?>
</div>

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
<?php require_once('website_footer.php');?>
