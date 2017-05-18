<?php 
require_once('includes/header.php');
require_once('includes/leftbar.php');
?>

  <section class="main">
    <section class="admin_main">
    	<div class="right_inner">
            <div class="admin_mid_right_box admin_box">
            		<img src="images/Admin_Dashboard_6.png">
                    <p>
                    	<span>Welcome, </span><?php echo isset($_SESSION['first_name'])?$_SESSION['first_name']:'User'?> </p>
                        <p style="font-size:18px;">Looks like, you don't have any notifications right now.
                    </p>
            	<div class="clear"></div>
            </div>
           
                       <div class="admin_mid_right_box admin_box" style="display:none;">
            		<img src="images/Admin_Dashboard_6.png">
                    <p>
                    	You have unread notification(s).
                    </p>
            	<div class="clear"></div>
            </div>
            
                                   <div class="admin_mid_right_box admin_box" style="display:none;">
            		<img src="images/Admin_Dashboard_6.png">
                    <p>
                    	There are no unread notifications.
                    </p>
            	<div class="clear"></div>
            </div>
        </div>
        <div class="right_inner">
            <div class="admin_mid_right_box admin_box">
                
                    <p>
                        <p style="font-size:18px;">Notifications will only apply when your app is live on App Store and/or Play Store
                    </p>
                <div class="clear"></div>
            </div>
        </div>
        
    </section>
  </section>
</section>
</body>
<script>
    $(document).ready(function() {
         $("aside ul li").removeClass("active");
        $("aside ul").find(".truck").eq(1).addClass("active");
        $(".payment_files_name .files_name_right span").click(function() {
            if($(this).is(':empty')){
                $(this).parent().parent().addClass("item_disabled"); //line 2125
                $(this).css("background","none").append("<input type='checkbox'>");
            } else {
                $(this).parent().parent().removeClass("item_disabled");
                $(this).css("background","url('images/menu_delete.png')");
                $(this).children("input").remove();
            }
        });
        $(".stats_download a").click(function() {
            $(this).next().toggleClass("show_pop");
            $(this).parent().siblings().children("div").removeClass("show_pop");
        });
        $(document).click(function() {
            $(".stats_download a + div").removeClass("show_pop");
        });
        $('.stats_download a').on('click', function(e) {
            e.stopPropagation();
        });
        $('.stats_download_tooltip').on('click', function(e) {
            e.stopPropagation();
        });

/*var rightHeight = $(window).height() - 45;
$(".right_main").css("height", rightHeight + "px")*/
    });
</script>

</html>
