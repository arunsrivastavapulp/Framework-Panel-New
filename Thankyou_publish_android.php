<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/user/userprofile.php');
session_start();
$userprofile = new UserProfile();
$user = $userprofile->getUserByCustId($_SESSION['custid']);
if ($user == '') {
    echo "<script>alert('Please login first');window.location.href='" . $basicUrl . "'</script>";
    exit();
}
?>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner support_hold">
            <div class="statistics">
                <div class="sp-hol" style="border:none; height:398px; padding:40px; text-align:left">
                    <h2 style="font-size:30px; color:#ffcc00">Thank You for choosing Instappy.</h2>
                    <p style="font-size:18px; color:#000;line-height:35px; margin-top:10px; padding:0">Your Android app will be launched on Google Play Store in 3 business days.
<span style="font-size:18px; color:#000; display:block; line-height:inherit">Best of Luck!</span></p>
<div class="sbtn" style="margin-top:50px">
	<a href="<?php echo $basicUrl;?>applisting.php" class="make_app_next2" style=" float:left; margin-right:100px">My Apps</a>
    <a href="<?php echo $basicUrl;?>Landing_Page_ContentPublishing.php?id=check" class="make_app_next2" style=" float:left">Create New</a>
</div>
                </div>
                
                
            </div>
	   </div>
    </section>
  </section>
</section>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
	<script src="js/chosen.jquery.js"></script>
    <script src="js/ImageSelect.jquery.js"></script>
     <script>
        $(document).ready(function() {
             $("aside ul li").removeClass("active");
            $("aside ul").find(".support").addClass("active");
        });
    </script>
    <style>
	a.make_app_next2 {
    text-transform: uppercase;
    text-decoration: none;
    font-weight: 300;
    float: right;
    margin-top: 35px;
    padding: 5px 10px;
    font-size: 14px;
    background: #ffcc00;
    color: #FFF; line-height:20px
}
body{
	background:#fff;
}
</style>



</body>
</html>
