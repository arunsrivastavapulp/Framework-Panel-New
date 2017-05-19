<?php
 require_once('website_header.php');
 if(isset($_POST['submit'])&& $_POST['type']=='edm_mail_content' ){
	 require_once('modules/login/save_edm.php');
	$edm=new Edm();
	$edm->save_content_edm($_POST);
 }
  if(isset($_POST['submit'])&& $_POST['type']=='edm_mail_retail_catalogue_form' ){
	 require_once('modules/login/save_edm.php');
	$edm=new Edm();
	$edm->save_retail_edm($_POST);
 }
?> 
<div class="clear"></div>
<div class="brdcrm">
  <div class="big-cont mt0">
    <div class="crm"> <a href="index.php">Home</a><span> >> </span><a href="#" class="active-crum">Thank You</a> </div>
  </div>
</div>

<div class="big-cont mt0 suc">
<div class="thanksdiv">
Thank you for contacting us! You have taken the first step towards getting your very own instant, affordable, stunning and intuitive mobile app for iOS and Android! Isn't that great? <br/><br/>

Instappy is brilliantly simple, and is loaded with exclusive features that are available at affordable prices. No more development efforts, no more coding, and no fat bills! Instappy is very affordable and is built for ensuring the success of businesses just like yours.<br/><br/>

The Instappy team is here to help you succeed in your mobile presence by opening up the world of mobile commerce for your business! We shall connect with you at the earliest on the contact details provided by you.<br/><br/>
</div>
<div class="button-left"><a href="<?php echo $basicUrl; ?>">Back</a></div>
</div>

<style>
.logo_nav.fix {
    position: static !important;
}
</style>
<script type="text/javascript">
	$(window).load(function(){

		setInterval(function(){
			window.location="index.php";
		},10000)
	});
</script>

<?php require_once('website_footer.php');?>
