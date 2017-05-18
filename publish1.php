<?php 
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/checkapp/app_screen.php');
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$appID = $_SESSION['appid'];
?>
<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
  <section class="main">
    
    <section class="middle publish_middle clear">
      <div class="right-area publish_pages">
         <!--<a href="javascript:void(0)" onclick="capture();" id="target" class="screenshot">Take Screen Shot</a>-->
         <div class="pageIndexArea">
                <div class="pageIndexdiv">
                    <img alt="" src="<?php echo $basicUrl; ?>images/instappy.png">
                </div>

            </div>
            <div class="mobile">
                <form method="POST" enctype="multipart/form-data" action="save.php" id="myForm">
                    <input type="hidden" name="img_val" id="img_val" value="" />
                </form>

         
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
      	<div class="mid_section ">
            <div class="mid_section_left">
            </div>
            <div class="mid_section_right">
                <div class="mid_right_box active">
                	<div class="mid_right_box_head">
                        <h1>Icons</h1>
                        <h2>Icons are images that represent your app on the cell phone screen in the apps section. We suggest that you choose an icon that represents your app and business best. E.g.: If you run a restaurant, a folk and a spoon could work well in helping your consumers recognise the nature of your service.</h2>
                        <a class="read_more">Read More...</a>
                    </div>
                	<div class="mid_right_box_body">
                    	<div class="choose_icon">
                        	<h2 class="icon_tip">Choose an icon for your awesome app:</span></h2>
                            <div class="icons">
							<?php 
								$webscreen = new WebScreen();
								$icons = $webscreen->get_app_icons();
								foreach($icons as $icon){
									if($icon['image_40'] != ''){
										?><img src="<?php echo $icon['image_40'];?>"><?php
									}
								} ?>
                                <div class="clear"></div>
                                <input type="button" value="Load More" class="load_more">
                            </div>
                        </div>
                        <div class="choose_icon">
							<?php if(isset($_SESSION['appid']) && $_SESSION['appid'] != ''){
							$self_icons = $webscreen->get_self_icons($_SESSION['appid']);
							//echo "<pre>"; print_r($self_icons); echo "</pre>"; 
							}
							
							?>
							<form name='upload_icons' id='upload_icons' method='post' action='' enctype="multipart/form-data">
                        	<h2>Or, you can even upload your very own icon for your app.</h2>
                            <p>We suggest that your icon image should not be more than <span>2 MB</span> in size, and <span>1024 x 1024 pixels</span> for iOS app, or <span>512 x 512 pixels</span> for Android app for best results.</p>
                            <a class="read_more">Read More...</a>
                            <div class="os_screen">
                            	<!-- <h2>iPhone</h2> -->
								<?php if(count($self_icons) > 0){
								$ios_size = array(1024,192);
								foreach($self_icons as $self_icon){
									//$img_ext = explode('.',$self_icon['image_40']);
									$size = end(explode('X',$self_icon['icon_name']));
									if(in_array($size,$ios_size)){
								?>
                                <div class="screen_size large">
                                	<img src="<?php echo $self_icon['image_40'];?>" data-size="<?php echo $size;?>">
                                    <div class="clear"></div>
                                    <span>App Store</span>
                                </div>
								<?php } }}else{ ?>
                                <div class="screen_size medium">
                                	<img src="images/iphone_180.jpg" data-size="180">
                                    <div class="clear"></div>
                                    <span>Home Screen</span>
                                </div>
								<?php } ?>
                                <div class="clear"></div>
                            </div>
                            <div class="os_screen ">
                            	<?php if(count($self_icons) > 0){
								?><!-- <h2>Android</h2> --><?php
								$android_size = array(512,96,72);
								foreach($self_icons as $self_icon){
									//$img_ext = explode('.',$self_icon['image_40']);
									$size = end(explode('X',$self_icon['icon_name']));
									if(in_array($size,$android_size)){
								?>
								
								<div class="screen_size large">
                                	<img src="<?php echo $self_icon['image_40'];?>" data-size="<?php echo $size;?>">
                                    <div class="clear"></div>
                                    <span>Google Play</span>
                                </div>
								<?php } }} ?>
                                <div class="clear"></div>
							</div>
							<input type='file' name='icons' value='' style='display:none;'>
							<input type="hidden" name='appid' value='<?php echo $_SESSION['appid'];?>'>
							<div id='upload_icons_message'></div>
							</form>
                        </div>
                        <a href="publish2.php" class="make_app_next">Save &amp; Continue</a>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
      </div>
      <div class="testContainer">
      	
      	<img src="" id="testImg">

      </div>
      <div class="hint_main"><img src="<?php echo $basicUrl; ?>images/ajax-loader.gif"></div>
    </section>
  </section>
</section>
<script type="text/javascript">

    function capture() {
        $('#mCSB_1_container').html2canvas({
            onrendered: function (canvas) {
                $('#img_val').val(canvas.toDataURL("image/png"));
                                savescreen_shot();
//                document.getElementById("myForm").submit();
            }
        });
    }

    function savescreen_shot() {
        $(".popup_container").css({'display': 'block'});
         $("#page_ajax").html('<img src="images/ajax-loader.gif">');
        var param = '';
        param = {'imgVal': $('#img_val').val()};
        var form_data = {
            data: param, //your data being sent with ajax
            token: '<?php echo $token; ?>', //used token here.
            hasid: '<?php echo $appID; ?>',
            is_ajax: 2
        };
        $.ajax({
            type: "POST",
            url: 'modules/checkapp/screenshot.php',
            data: form_data,
            success: function (response)
            {
//                        alert(response);
                if (response == 0) {
                    $(".popup_container").css({'display': 'none'});
                     $("#page_ajax").html('');
//                    alert('done');

                } else if (response == 1) {
                    $(".popup_container").css({'display': 'none'});
                    $("#page_ajax").html('');
                    console.log(response);
                }
            },
            error: function () {
                console.log("error in ajax call");
                alert("error in ajax call");
            }
        });
    }

</script>
<script type="text/javascript" src="js/colpick.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.shapeshift.js" type="text/javascript"></script>
	 <script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
	
    <script src="js/publishJS.js"></script>    
<script src="js/html2canvas.js" type="text/javascript"></script>
<script src="js/jquery.plugin.html2canvas.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
 <script type='text/javascript'>
		$(document).ready(function(){
			$('.screen_size img').click(function(){
				$('#upload_icons input[type=file]').trigger("click");
			});
			var options = { 
				target: '#upload_icons_message',   // target element(s) to be updated with server response 
				url : BASEURL+'API/index.php/uploadappicons',
				beforeSubmit: beforeSubmit,  // pre-submit callback 
				success: afterSuccess,  // post-submit callback 
				resetForm: true        // reset the form after successful submit 
			};
			$('#upload_icons input[type=file]').change(function(){
				$('#upload_icons_message').empty();
				var file = this.files[0];
				var imagefile = file.type;
				var match= ["image/jpeg","image/png","image/jpg"];
				//var imgtest = document.getElementById("testSize");
				var imgtest = $('#upload_icons img');
                var testSize = imgtest[0].getBoundingClientRect();
                
				if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
				{
					//$('#previewing').attr('src','noimage.png');
					$("#upload_icons_message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
					return false;
				}
				else if(testSize.width>1024&&testSize.height>1024)
                {
                    $("#upload_icons_message").html("<p id='error'>Please Select an Image of 1024 size</p>");
					return false;
                }
				else
				{
					var reader = new FileReader();
					reader.onload = function(e){
						
					};
					reader.readAsDataURL(this.files[0]);
					$('#screenoverlay').fadeIn();
					$('#upload_icons').ajaxSubmit(options);
					
				}
			});
			function afterSuccess(response)
			{
				$('#screenoverlay').fadeOut();
				$('#upload_icons_message').html(response); //hide submit button
				if(response == 'Icons Uploaded Successfully'){
					window.location = window.location;
				}
			}
			//function to check file size before uploading.
			function beforeSubmit(){
				//check whether browser fully supports all File API
			   if (window.File && window.FileReader && window.FileList && window.Blob)
				{
					
					if( !$('#upload_icons input[type=file]').val()) //check empty input filed
					{
						$("#upload_icons_message").html("Are you kidding me?");
						return false
					}
					
					var fsize = $('#upload_icons input[type=file]')[0].files[0].size; //get file size
					var ftype = $('#upload_icons input[type=file]')[0].files[0].type; // get file type
					

					//allow only valid image file types 
					switch(ftype)
					{
						case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
							break;
						default:
							$("#upload_icons_message").html("<b>"+ftype+"</b> Unsupported file type!");
							return false
					}
					
					//Allowed file size is less than 1 MB (1048576)
					if(fsize>1048576) 
					{
						$("#upload_icons_message").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
						return false
					}
							
					//$('#submit-btn').hide(); //hide submit button
					//$('#loading-img').show(); //hide submit button
					$("#upload_icons_message").html("");  
				}
				else
				{
					//Output error to older browsers that do not support HTML5 File API
					$("#upload_icons_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
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
