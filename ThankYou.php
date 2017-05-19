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
    <div class="crm"> <a href="#" class="active-crum">Thank You</a> </div>
  </div>
</div>

<div class="big-cont mt0 suc">
<div class="thanksdiv">
Thank you for contacting us. Now you are closer to offering great quality apps to your clients. Excited?  <br/><br/>

Our reseller program is designed to enable and equip design agencies, advertising, marketing and web development agencies to add app development to their existing service portfolio instantly, and enable individuals to set up an app development service company within a few days. Now you can earn additional revenue with app marketing for your clients.<br/><br/>

Think about designing mobile apps and releasing them under your or your client’s accounts! Build hundreds of apps with Instappy without investing in any coding or development. We have discounts with over 80% off for our partners on bulk orders.<br/><br/>

So, let nothing hold you back. The Instappy team is here to help you with training and premium support. Partner with Instappy, today! <br/><br/>


</div>
<div class="button-left"><a href="<?php echo $basicUrl; ?>">Go to Home</a></div>
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

<!-- Google Code for Reseller Partner Program Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 925328737;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "NFqACIGutGUQ4cqduQM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/925328737/?label=NFqACIGutGUQ4cqduQM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<script type="text/javascript">
_linkedin_data_partner_id = "43241";
</script><script type="text/javascript">
(function(){var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})();
</script>

<?php require_once('website_footer.php');?>
