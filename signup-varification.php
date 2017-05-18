<?php
 require_once('website_header.php');
 require_once('modules/login/login-check.php');
 $login=new Login();
 $code=isset($_GET['verification'])?$_GET['verification']:'';
 $results=$login->signup_verification($code);
?> 
<div class="clear"></div>
<div class="brdcrm">
  <div class="big-cont mt0">
    <div class="crm"> <a href="index.php">Home</a><span> >> </span><a href="#" class="active-crum">Register</a> </div>
  </div>
</div>

<div class="big-cont mt0 suc">
<?php if($results=='success'){?>
Thanks for verifying your email. <a href="Landing_Page_ContentPublishing.php?id=createApp">Click here to continue </a> 
<?php }
else{
?>
Invalid Request.
<?php }?>
</div>
<?php require_once('website_footer.php');?>
