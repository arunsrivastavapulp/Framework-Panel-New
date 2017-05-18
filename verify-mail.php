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
?>
Thank you for verifying your E-mail.<br/><br/>


<?php
}
else
{
?>
Not a valid token
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
