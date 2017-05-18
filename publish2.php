<?php 
require_once('includes/header.php');
require_once('includes/leftbar.php');
if(file_exists('modules/checkapp/app_screen.php'))
{
	require_once('modules/checkapp/app_screen.php');
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
           
             
            
            
            <div class="theme_head restro_theme">
                <a class="nav_open"> <img src="images/menu_btn.png"> </a>
                <h1>IndiEat</h1>
                <a href="#" class="search"> <i class="fa fa-search"></i> </a>
            </div>
                    
            <nav>
                <ul>
                    <li data-link="1"><a href="#">Home</a></li>
                    <li data-link="2"><a href="#">About Us</a></li>
                    <li data-link="1"><a href="#">Menu</a></li>
                    <li data-link="1"><a href="#">Notification</a></li>
                    <li data-link="1"><a href="#">Chef &apos;s View</a></li>
                </ul>
            </nav>
            <div class="overlay">
            </div>
            
            
             <div class="banner">
                <img src="images/restaurant_banner.jpg" class="active">
                <img src="images/restaurant_banner.jpg">
                <img src="images/restaurant_banner.jpg">
                <div class="pager">
                	<div class="active"></div>
                	<div></div>
                	<div></div>
                </div>
                <div class="clear"></div>
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
                	<div class="mid_right_box_head">
                        <h1>Splash Screen</h1>
                        <h2>Splash Screen is the image that displays while an app is getting loaded. It acts as a sneak-peek into your app for a first time user. We suggest that you choose an image that represents the nature of your business best. E.g.: If you run a restaurant, you can use a blurred image of one of your hottest selling food item, with your logo at the centre.</h2>
                        <a class="read_more">Read More...</a>
                    </div>
                	<div class="mid_right_box_body">
                    	<div class="choose_screen">
                        	<h2>Choose a splash screen for your awesome app:</span></h2>
                            <div class="screens">
                            	<?php 
							$webscreen = new WebScreen();
							$screens = $webscreen->get_app_screen();
							foreach($screens as $screen){
								if($screen['image_link'] != ''){ ?>
                            	<img src="<?php echo $screen['image_link'];?>" width='158'>
							<?php } } ?>
                            </div>
                        </div>
                        <div class="choose_icon">
							<?php if(isset($_SESSION['appid']) && $_SESSION['appid'] != ''){
							$self_screens = $webscreen->get_self_screens($_SESSION['appid']);
							//echo "<pre>"; print_r($self_screens); echo "</pre>";
							}?>
							<form name='upload_screen' id='upload_screen' method='post' action='' enctype="multipart/form-data">
                        	<h2>Or, you can even upload your very own splash screen for your app.</h2>
                            <p>We suggest that your splash screen image should not be more than <span>2 MB</span> in size, and <span>1242 x 2208 pixels</span> for any device.</p>
                            <div class="os_screen">
                            	<h2>iPhone</h2>
								<?php if(count($self_screens) > 0){
								$ios_size = array(320,768,640,750);
								foreach($self_screens as $self_screen){
									$img_ext = explode('.',$self_screen['name']);
									//$size = end(explode('X',$img_ext[0]));
									$size = substr(strrchr($self_screen['name'], "X"), 1);
									if(in_array($size,$ios_size)){
								?>
                                <div class="screen_size splashLarge">
                                	<img src="<?php echo $self_screen['image_link'];?>" data-size="1242*2208">
                                    <div class="clear"></div>
                                    <span>iPhone 6+ / 6 / 5S / 5C</span>
                                </div>
								<?php }}}else{?>
                                <div class="screen_size splashMedium">
                                	<img src="images/iphone_screen_2.jpg" data-size="640*960">
                                    <div class="clear"></div>
                                    <span>iPhone 4S</span>
                                </div>
								<?php } ?>
                                <div class="clear"></div>
                            </div>
                            <div class="os_screen">
                            	<h2>Android</h2>
								<?php if(count($self_screens) > 0){
								$android_size = array(1024,1280);
								foreach($self_screens as $self_screen){
									$img_ext = explode('.',$self_screen['name']);
									//$size = end(explode('X',$img_ext[0]));
									$size = substr(strrchr($self_screen['name'], "X"), 1);
									if(in_array($size,$android_size)){
								?>
                                <div class="screen_size splashLarge" >
                                	<img src="<?php echo $self_screen['image_link'];?>" data-size="768*1280">
                                    <div class="clear"></div>
                                    <span>Samsung Galaxy</span>
                                </div>
									<?php }}}else{ ?>
									<div class="screen_size splashLarge" >
										<img src="images/android_screen_1.jpg" data-size="768*1280">
										<div class="clear"></div>
										<span>Samsung Galaxy</span>
									</div>
									<?php } ?>
                                <div class="clear"></div>
                            </div>
                            <div class="os_screen">
                            	<h2>Tab</h2>
								<?php if(count($self_screens) > 0){
								$android_size = array(1242,2048,1920,2560);
								foreach($self_screens as $self_screen){
									$img_ext = explode('.',$self_screen['name']);
									//$size = end(explode('X',$img_ext[0]));
									$size = substr(strrchr($self_screen['name'], "X"), 1);
									if(in_array($size,$android_size)){
								?>
                                <div class="screen_size splashLarge"  >
                                	<img src="<?php echo $self_screen['image_link'];?>" data-size="1536*2048">
                                    <div class="clear"></div>
                                    <span>Portrait</span>
                                </div>
								<?php }}}else{ ?>
                                <div class="screen_size splashLargePortrait" >
                                	<img src="images/tab_screen_2.jpg" data-size="2048*1536">
                                    <div class="clear"></div>
                                    <span>Landscape</span>
                                </div>
								<?php } ?>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
							<input type='file' name='screens' style='display:none;'>
							<input type="hidden" name='appid' value='<?php print_r($_SESSION['appid']);?>'>
							<div id='upload_screens_message'></div>
							</form>
                        </div>
                        <a href="publish3.php" class="make_app_next">Save &amp; Continue</a>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="mid_right_box">
                	<div class="mid_right_box_head">
                        <h1>Advertisements &amp; Push Notifications</h1>
                        <h2>Advertisements are a great way to earn from your app. By ‘ticking’ the check box, you will authorize third-party to display their apps on your app, which will be an add-on revenue generator for you. All you have to do is fill in the relevant fields with required information, and voila! You are all set to go.</h2>
                        <a class="read_more">Read More...</a>
                    </div>
                	<div class="mid_right_box_body">
                    	<div class="advertising_notification">
                        	<div class="advertising_notification_list">
                            	<div class="advertising_notification_left">
                                	<h2>Display Ads</h2>
                                </div>
                            	<div class="advertising_notification_right">
                                	<input type="checkbox" id="enable">
                                    <label for="enable">Enable</label>
                                </div>
                                <div class="clear"></div>
                            </div>
                        	<div class="advertising_notification_list">
                            	<div class="advertising_notification_left">
                                	<label>Provider Type</label>
                                </div>
                            	<div class="advertising_notification_right">
                                	<select>
                                    	<option>Flury</option>
                                    	<option>AdMob</option>
                                    </select>
                                </div>
                                <div class="clear"></div>
                            </div>
                        	<div class="advertising_notification_list">
                            	<div class="advertising_notification_left">
                                	<label>iPhone Unit Code</label>
                                </div>
                            	<div class="advertising_notification_right">
                                	<input type="text">
                                </div>
                                <div class="clear"></div>
                            </div>
                        	<div class="advertising_notification_list">
                            	<div class="advertising_notification_left">
                                	<label>Android Unit Code</label>
                                </div>
                            	<div class="advertising_notification_right">
                                	<input type="text">
                                </div>
                                <div class="clear"></div>
                            </div>
                        	<div class="advertising_notification_list">
                            	<div class="advertising_notification_left">
                                	<label>MobileWeb Unit Code</label>
                                </div>
                            	<div class="advertising_notification_right">
                                	<input type="text">
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="push_notification">
                        	<h2>Push Notification</h2>
                            <p>Push Notifications are a great way to update your app users updated with the hottest deals and offers you are running with. When push a notification, it lands on your app users’ cell phone in the Notifications section.</p>
	                        <a class="read_more">Read More...</a>
                            <div class="clear"></div>
                            <input type="checkbox" id="notification">
                            <label for="notification">Enable Push notification (cost)</label>
                            <div class="clear"></div>
                            </div>
                        <a href="publish3.php" class="make_app_next">Save &amp; Continue</a>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
      </div>
      

      <input type="file" id="uploadImgFile">
      <div class="testContainer">
        
        <img src="" id="testImg">

      </div>
      <div class="hint_main"><img src="<?php echo $basicUrl; ?>images/ajax-loader.gif"></div>
    </section>
  </section>
</section>
<style>
.mid_section .mid_section_right .mid_right_box .mid_right_box_body .choose_icon .os_screen{
	display:none;
}
.mid_section .mid_section_right .mid_right_box .mid_right_box_body .choose_icon .os_screen:nth-child(3){
	display:block;
}
</style>
<script type="text/javascript" src="js/colpick.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.shapeshift.js" type="text/javascript"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script src="js/chosen.jquery.js"></script>
<script src="js/ImageSelect.jquery.js"></script>
<script type="text/javascript" src="js/publishJS.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type='text/javascript'>
	$(document).ready(function(){
		$('#upload_screen .screen_size img').click(function(){
			$('#upload_screen input[type=file]').trigger("click");
		});
		var options = { 
			target: '#upload_screens_message',   // target element(s) to be updated with server response 
			url : 'API/index.php/uploadappscreens',
			beforeSubmit: beforeSubmit,  // pre-submit callback 
			success: afterSuccess,  // post-submit callback 
			resetForm: true        // reset the form after successful submit 
		};
		$('#upload_screen input[type=file]').change(function(){
			$('#upload_screens_message').empty();
			var file = this.files[0];
			var imagefile = file.type;
			var match= ["image/jpeg","image/png","image/jpg"];
			//var imgtest = document.getElementById("testImg");
			//var imgtest = $('#upload_screen img');
			//var testSize = imgtest.getBoundingClientRect();
			
			if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
			{
				//$('#previewing').attr('src','noimage.png');
				$("#upload_screens_message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
				return false;
			}
			else
			{
				var reader = new FileReader();
				reader.onload = function(e){
					
				};
				reader.readAsDataURL(this.files[0]);
				$('#screenoverlay').fadeIn();
				$('#upload_screen').ajaxSubmit(options);
				
			}
			
			/* if(testSize.width>2048&&testSize.height>2048)
			{
				$("#upload_screens_message").html("<p id='error'>Please Select an Image of 2048 size</p>");
				return false;
			}*/
		});
		function afterSuccess(response)
		{
			$('#screenoverlay').fadeOut();
			$('#upload_screens_message').html(response); //hide submit button
			if(response == 'Screens Uploaded Successfully'){
				window.location = window.location;
			}
		}
		//function to check file size before uploading.
		function beforeSubmit(){
			//check whether browser fully supports all File API
		   if (window.File && window.FileReader && window.FileList && window.Blob)
			{
				
				if( !$('#upload_screen input[type=file]').val()) //check empty input filed
				{
					$("#upload_screens_message").html("Are you kidding me?");
					return false
				}
				
				var fsize = $('#upload_screen input[type=file]')[0].files[0].size; //get file size
				var ftype = $('#upload_screen input[type=file]')[0].files[0].type; // get file type
				

				//allow only valid image file types 
				switch(ftype)
				{
					case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
						break;
					default:
						$("#upload_screens_message").html("<b>"+ftype+"</b> Unsupported file type!");
						return false
				}
				
				//Allowed file size is less than 3 MB (3048576)
				if(fsize>3048576) 
				{
					$("#upload_screens_message").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
					return false
				}
						
				//$('#submit-btn').hide(); //hide submit button
				//$('#loading-img').show(); //hide submit button
				$("#upload_screens_message").html("");  
			}
			else
			{
				//Output error to older browsers that do not support HTML5 File API
				$("#upload_screens_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
				return false;
			}
		}
		function bytesToSize(bytes) {
			var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
			if (bytes == 0) return 'n/a';
			var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		};
	});	
</script>
</body>
</html>
