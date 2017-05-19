<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/apphtml/apphtml.php');
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$app_id=isset($_GET['app_id'])?$_GET['app_id']:'';
if($app_id){
require_once('modules/opencart-integration/opencart-integration.php');
$obj=new Opencart();
$app_data=$obj->edit_catalogue_app($app_id);
$html=$obj->get_cuurent_app_html($app_data['theme']);
?>
<section class="main">

    <section class="middle publish_middle clear">
        <div class="right-area publish_pages">
            <div class="pageIndexArea">
                <div class="pageIndexdiv">
                    <img alt="" src="<?php echo $basicUrl; ?>images/instappy.png">
                </div>

            </div>
            <div class="mobile">




                <!-- Replacement Area Starts -->
                <div id="content-1" class="content mCustomScrollbar clear first-page " >

                    <div class="overlay">
                    </div>

                    <div class="container droparea" style="float:left;width:100%;">

                        <div class="half_widget" component="">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="half_widget_img">
                                    <img src="images/restaurant_widget1.png">
                                    <div class="clear"></div>
                                </div>
                                <div class="half_widget_text">
                                    <p>Chef's View</p>
                                </div>
                            </div>
                        </div>
                        <div class="half_widget">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="half_widget_img">
                                    <img src="images/restaurant_widget2.png">
                                    <div class="clear"></div>
                                </div>
                                <div class="half_widget_text">
                                    <p>Chef's View</p>
                                </div>
                            </div>
                        </div>
                        <div class="full_widget" data-ss-colspan="2">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="full_widget_img">
                                    <img src="images/restaurant_widget3.png">
                                    <div class="clear"></div>
                                </div>
                                <div class="full_widget_text">
                                    <p>Special Offers</p>
                                </div>
                            </div>
                        </div> 
                        <div class="small_widget addon">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="small_widget_img">
                                    <img src="images/zomato.png">
                                </div>
                                <div class="small_widget_text">
                                    <p>Review Us</p>
                                    <p>On</p>
                                    <h2>Zomato</h2>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="contact_small_widget">
                            <p class="widgetClose">x</p>
                            <p class="contact_small_heading">Heading</p>
                            <p class="contact_small_subheading">Subheading</p>
                            <div class="contact_btns">
                                <a href="#"><img src="images/contact_mail.png" /></a>
                                <a href="#"><img src="images/contact_phone.png" /></a>
                            </div>
                        </div>
                    </div>




                </div>

                <!-- Replacement Area Ends -->
            </div>
            <div class="devices"> 
                <button>Device</button>
                <div class="device_options">
                    <div class="device_list android">
                        <img src="images/android.png" />
                        <span>Android</span>
                    </div>
                    <div class="device_list iphone">
                        <img src="images/iphone.png" />
                        <span>Iphone</span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="publish_content_area">
            <div class="mid_section">
                <div class="mid_section_left">
                </div>
                <div class="mid_section_right">
                    <div class="mid_right_box active" style="padding:20px 0px;">
                        <div class="mid_right_box_body">
                            <div class="congrats">
                                <h1>Thank You!</h1>
                                <h2>Looks like you are a pro!</h2>
                                <p>Your app is ready to be published for Android as well as iOS devices. <br/>You can preview it with the Instappy Wizard before you go ahead and purchase it.</p>
                                <div class="congrats_banner">
                                <img src="images/retail_thanks.png" alt="not found">
                                <h3>Download Instappy Wizard</h3>
                                <p>For the first time ever, you can get the preview of your app while you are creating it!</p>

                                <div class="banner_button_img">
                                    <img src="images/gPlay.png" alt="not found">
                                    <img src="images/appstore.png" alt="not found">
                                </div>
                                    

                                </div>
                                <div class="congrats_btns">
                                    <!--<a  href="#" class="free_down" id="appcreate" >Download Trial</a>-->
                                    <p>Or buy it, now</p>
                                    <a href="#" class="buy" id="appcreate1" >Buy</a>
                                   
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>

    </section>
</section>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.shapeshift.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script type="text/javascript" src="js/publishJS.js"></script>
<script>
			var page = '<?php echo addslashes(json_decode($html,false)); ?>';
            //if(page != '')
            //{
            var obj = JSON.parse(page);
			
            //        alert(obj.navigationbar);
            if (obj.banner == undefined || obj.banner == "")
            {
                $(obj.navigationbar).insertBefore(".overlay");
                $(".container.droparea").html(obj.contentarea);
            }
            else
            {
                $(obj.navigationbar).insertBefore(".overlay");
                $(".container.droparea").html(obj.contentarea);
                $("<div class='banner'>" + obj.banner + "</div>").insertAfter(".overlay");
            }
			for(var i=0;i<3;i++){
			if(i==0)
			$(".banner").find("img").eq(i).attr('src', '<?php echo $app_data['banner1'];?>');	
			if(i==1)
			$(".banner").find("img").eq(i).attr('src', '<?php echo $app_data['banner2'];?>');	
			if(i==2)
			$(".banner").find("img").eq(i).attr('src','<?php echo $app_data['banner3'];?>');
		
			}
			$(".theme_head").css("background-color","<?php if($app_data['background_color'])echo $app_data['background_color'];else echo '#ff8800';?>");
			var download =0;

$("#appcreate").click(function(){
download =1;
createApp();
});

$("#appcreate1").click(function(){
download =2;
createApp();
});
        function createApp() {
            $.ajax({
                type: "POST",
                url: BASEURL+'API/htmlparser.php/getData',
                data: "app_id=" + "<?php echo $app_id; ?>" + "&themeId=1",
                success: function (response) {
				
					if(download==2){
					
					location.href = BASEURL+"payment_details.php";
					}
                },
            });

        }

    </script>
<?php
}
else{
$appID = $_SESSION['appid'];
$username = $_SESSION['username'];
$save = new Saveapphtml();
if($username){
$author = $save->check_user_exit($username);
if($appID)
$showHtml = 1;	
}
else{	
  echo "<script>alert('Please login first');window.location.href='" . $basicUrl . "'</script>";
    exit();	
}
?>
<section class="main">

    <section class="middle publish_middle clear">
        <div class="right-area publish_pages">
            <div class="pageIndexArea">
                <div class="pageIndexdiv">
                    <img alt="" src="<?php echo $basicUrl; ?>images/instappy.png">
                </div>

            </div>
            <div class="mobile">




                <!-- Replacement Area Starts -->
                <div id="content-1" class="content mCustomScrollbar clear first-page " >

                    <div class="overlay">
                    </div>

                    <div class="container droparea" style="float:left;width:100%;">

                        <div class="half_widget" component="">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="half_widget_img">
                                    <img src="images/restaurant_widget1.png">
                                    <div class="clear"></div>
                                </div>
                                <div class="half_widget_text">
                                    <p>Chef's View</p>
                                </div>
                            </div>
                        </div>
                        <div class="half_widget">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="half_widget_img">
                                    <img src="images/restaurant_widget2.png">
                                    <div class="clear"></div>
                                </div>
                                <div class="half_widget_text">
                                    <p>Chef's View</p>
                                </div>
                            </div>
                        </div>
                        <div class="full_widget" data-ss-colspan="2">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="full_widget_img">
                                    <img src="images/restaurant_widget3.png">
                                    <div class="clear"></div>
                                </div>
                                <div class="full_widget_text">
                                    <p>Special Offers</p>
                                </div>
                            </div>
                        </div> 
                        <div class="small_widget addon">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="small_widget_img">
                                    <img src="images/zomato.png">
                                </div>
                                <div class="small_widget_text">
                                    <p>Review Us</p>
                                    <p>On</p>
                                    <h2>Zomato</h2>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="contact_small_widget">
                            <p class="widgetClose">x</p>
                            <p class="contact_small_heading">Heading</p>
                            <p class="contact_small_subheading">Subheading</p>
                            <div class="contact_btns">
                                <a href="#"><img src="images/contact_mail.png" /></a>
                                <a href="#"><img src="images/contact_phone.png" /></a>
                            </div>
                        </div>
                    </div>




                </div>

                <!-- Replacement Area Ends -->
            </div>
            <div class="devices"> 
                <button>Device</button>
                <div class="device_options">
                    <div class="device_list android">
                        <img src="images/android.png" />
                        <span>Android</span>
                    </div>
                    <div class="device_list iphone">
                        <img src="images/iphone.png" />
                        <span>Iphone</span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="publish_content_area">
            <div class="mid_section">
                <div class="mid_section_left">
                </div>
                <div class="mid_section_right">
                    <div class="mid_right_box active">
                        <div class="mid_right_box_body">
                            <div class="congrats">
                                <h1>Thank You!</h1>
                                <h2>Looks like you are a Pro!</h2>
                                <p>It is now available for all platforms. <br/>You can either try it for free, or go ahead and purchase it.</p>
                                <div class="congrats_banner">

                                <img src= "<?php echo $basicUrl; ?>images/wizard-icon_Retail.png" alt="not found">
                                <h3>Download Instappy Wizard Retail</h3>
                                <p>to get the preview of your app now!</p>

                                <div class="banner_button_img">
                                    <img src="<?php echo $basicUrl; ?>images/gPlay.png" alt="not found">
                                    <img src="<?php echo $basicUrl; ?>images/appstore.png" alt="not found">
                                </div>
                                    

                                </div>
                                <div class="congrats_btns">
                                    <!--<a  href="#" class="free_down" id="appcreate" >Download Trial</a>-->
                                    <p>Or Buy it NOW !</p>
                                    <a href="#" class="buy" id="appcreate1" >Buy</a>
                                   
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>

    </section>
</section>
</section>

    
       
    

<script type="text/javascript" src="js/colpick.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.shapeshift.js" type="text/javascript"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script src="js/chosen.jquery.js"></script>
<script type="text/javascript" src="js/publishJS.js"></script>

<?php if (empty($appID) && empty($username)) { ?>
    <script>
                                        var pageDetails = [];
                                        var navbar = '';

                                        var storedPages = [];
    </script>
    <?php
} else {
    //print_r($html['storedPages']);
    ?>
  
<?php } ?>
</body>
</html>
<?php } ?>