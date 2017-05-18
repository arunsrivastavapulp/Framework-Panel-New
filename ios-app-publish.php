<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/myapp/myapps.php');
require_once('modules/myapp/save_imgs.php');
require_once('modules/checkapp/app_screen.php');
$apps = new MyApps();
$apps_img = new WebScreen();
$ios_image= new Save_images();
$app_data = $apps->app_name($_SESSION['appid']);
$dataAndroid = $apps->getIosDetails($_SESSION['appid']);
$sel_app_icon = $apps_img->get_selected_app_icon($_SESSION['appid']);
?>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner">
            <div class="how_publish">
            	<div class="how_publish_left ios-left"> <img src="images/Ios-App-Publish.png">
            <h1>Ios App Publish :</h1>
            <h2>Fields marked with * need to be filled before publishing.</h2>
          </div>
                <div class="clear"></div>
                <div class="how_publish_body">
                	<div class="how_publish_body_left">
                    	<div class="publish_content">
                            <h2>Version Information</h2>
							<form action='' method='post' name='ios_app_publish' id='ios_app_publish' enctype="multipart/form-data">
                             <div class="publish_content_form">
                  <div class="publish_content_label">
                    <label>Name :</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input type="text"  maxlength="255" name='app_name' value='<?php echo isset($app_data['summary']) ? $app_data['summary'] : ''?>' placeholder="Less then 255 characters.">
                  </div>
                  <div class="publish_content_info">
                    <p> The name of your app as it will appear on the App Store. This can't be longer than 255 characters. </p>
                  </div>
                  <div class="publish_content_label">
                    <label>Description :</label>
                  </div>
                  <div class="publish_content_textbox">
                    <textarea  minlength="10" maxlength="4000" name='app_full_desc' style="height:230px" placeholder="Less then 4000 characters."><?php echo isset($dataAndroid['full_description']) ? $dataAndroid['full_description'] : ''?></textarea>
                  </div>
                  <div class="publish_content_info">
                    <p>A description of your app, detailing features and functionality. It will also be used for your Apple Watch app.</p>
                  </div>
                  <div class="publish_content_label">
                    <label>Keywords:</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input type="text"  maxlength="60" name='app_keywords' value='<?php echo isset($dataAndroid['keywords']) ? $dataAndroid['keywords'] : ''?>' placeholder="">
                  </div>
                  <div class="publish_content_info">
                    <p>One or more keywords that describe your app. Keywords make App Store search results more accurate. Separate keywords with a comma.</p>
                  </div>
                  <div class="publish_content_label">
                    <label>Support Url:</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input type="text" class="url" maxlength="255" name='support_url' value='<?php echo isset($dataAndroid['support_url']) ? $dataAndroid['support_url'] : ''?>' placeholder="http://example.com">
                  </div>
                  <div class="publish_content_info">
                    <p> A URL with support information for your app. This URL will be visible on the App Store.</p>
                  </div>
                  <div class="publish_content_label">
                    <label>Marketting Url:</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input type="text" class=" url" maxlength="255" name='meeting_url' value='<?php echo isset($dataAndroid['marketing_url']) ? $dataAndroid['marketing_url'] : ''?>' placeholder="http://example.com">
                  </div>
                  <div class="publish_content_info">
                    <p>A URL with support information for your app. This URL will be visible on the App Store. </p>
                  </div>
                  <div class="publish_content_label">
                    <label>Privacy policy <br/>
                      Url:</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input class=" url" maxlength="255" name='privacy_url' value='<?php echo isset($dataAndroid['privacy_policy_url']) ? $dataAndroid['privacy_policy_url'] : ''?>' type="text" placeholder="http://example.com">
                  </div>
                  <div class="publish_content_info">
                    <p> A URL that links to your organization's privacy policy. Privacy policies are required for apps that are Made for Kids or offer auto-renewable In-App Purchases or free subscriptions. They are also required for apps with account registration, apps that access a user’s existing account, or as otherwise required by law. Privacy policies are recommended for apps that collect user- or device-related data.</p>
                  </div>
                   </div>
                   <div class="upload_message"></div>
                   <!--image input-->
                   <div class="publish_content_form">
                   	   <ul class="brws">
                    <li class="image_1">
						<span class="inch">750 x 1334Px (4.7 inch) </span> <img src="
						
						<?php
						$img1=$ios_image->fetch_ios_icons($_SESSION['appid'],'iphonesix_link');
						if($img1){
						echo $img1;	
						}
						else{
							echo "images/browse.jpg";
						}
						?>
						
						" alt=""/>
						<input type="file" name="upload_image_1" class="upload_image_1 upload_image_scrn" style="display:none;">
						<div class="upload_message"></div>
					</li>
                    <li class="image_2">
						<span class="inch">1242 x 2208Px (5.5 inch)</span> <img src="
						<?php
						$img1=$ios_image->fetch_ios_icons($_SESSION['appid'],'iphonesixplus_link');
						if($img1){
						echo $img1;	
						}
						else{
							echo "images/browse.jpg";
						}
						?>" alt=""/>
						<input type="file" name="upload_image_2" class="upload_image_2 upload_image_scrn" style="display:none;">
						<div class="upload_message"></div>
					</li>
                    <li class="image_3">
						<span class="inch">640 x 1136Px (4.0 inch)</span> <img src="<?php
						$img1=$ios_image->fetch_ios_icons($_SESSION['appid'],'iphonefive_link');
						if($img1){
						echo $img1;	
						}
						else{
							echo "images/browse.jpg";
						}
						?>" alt=""/>
						<input type="file" name="upload_image_3" class="upload_image_3 upload_image_scrn" style="display:none;">
						<div class="upload_message"></div>
					</li>
                    <li class="image_4">
						<span class="inch">640 x 960Px (3.5 inch)</span> <img src="<?php
						$img1=$ios_image->fetch_ios_icons($_SESSION['appid'],'iphonefour_link');
						if($img1){
						echo $img1;	
						}
						else{
							echo "images/browse.jpg";
						}
						?>" alt=""/>
						<input type="file" name="upload_image_4" class="upload_image_4 upload_image_scrn" style="display:none;">
						<div class="upload_message"></div>
					</li>
                    <li class="image_5">
						<span class="inch">1536 x 2048Px (Ipad)</span> <img src="<?php
						$img1=$ios_image->fetch_ios_icons($_SESSION['appid'],'ipad_link');
						if($img1){
						echo $img1;	
						}
						else{
							echo "images/browse.jpg";
						}
						?>" alt=""/>
						<input type="file" name="upload_image_5" class="upload_image_5 upload_image_scrn" style="display:none;">
						<div class="upload_message"></div>
					</li>
                  </ul>
                  
                 </div>
                   <!--image input ends-->         
                            <div class="publish_content_form">
                            	<p class="cate-heading">CATEGORIZATION</p>
                            	<div class="publish_content_label">
                                    <label>Category:</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <select name='app_type' id='app_type' >
                                    	<option value="">Select an application type</option>
                                    	<option value="applications" selected>Applications</option>
                                    </select>
                                </div>
                                <div class="publish_content_label">
                                    <label>Secondary <br/> Category :</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <select name='app_category' id='app_category' >
                                    	<option value='<?php echo isset($dataAndroid['ios_category_two']) ? $dataAndroid['ios_category_two'] : ''?>'><?php echo isset($dataAndroid['ios_category_two']) ? $dataAndroid['ios_category_two'] : 'Select a category'?></option>
										<option value="BOOKS_AND_REFERENCE">Books &amp; Reference</option>
										<option value="BUSINESS">Business</option>
										<option value="COMICS">Comics</option>
										<option value="COMMUNICATION">Communication</option>
										<option value="EDUCATION">Education</option>
										<option value="ENTERTAINMENT">Entertainment</option>
										<option value="FINANCE">Finance</option>
										<option value="HEALTH_AND_FITNESS">Health &amp; Fitness</option>
										<option value="LIBRARIES_AND_DEMO">Libraries &amp; Demo</option>
										<option value="LIFESTYLE">Lifestyle</option>
										<option value="MEDIA_AND_VIDEO">Media &amp; Video</option>
										<option value="MEDICAL">Medical</option>
										<option value="MUSIC_AND_AUDIO">Music &amp; Audio</option>
										<option value="NEWS_AND_MAGAZINES">News &amp; Magazines</option>
										<option value="PERSONALIZATION">Personalization</option>
										<option value="PHOTOGRAPHY">Photography</option>
										<option value="PRODUCTIVITY">Productivity</option>
										<option value="SHOPPING">Shopping</option>
										<option value="SOCIAL">Social</option>
										<option value="SPORTS">Sports</option>
										<option value="TOOLS">Tools</option>
										<option value="TRANSPORTATION">Transportation</option>
										<option value="TRAVEL_AND_LOCAL">Travel &amp; Local</option>
										<option value="WEATHER">Weather</option>
                                    </select>
                                </div>
                                 <div class="publish_content_info">
                                	<p>
                                    	The category that best describes this app. For more information, see the <a href="#">App Store Category Definitions</a>.
                                    </p>
                                </div>
                               
                                <div class="publish_content_label full">
                    <label style="display:none">Edit License Agreement</label>
                  </div>
                                <ul class="rad-list">
                    <li>
                      <input type="checkbox" style="position:relative; top:2px"/>
                      <span>I agree to the all the terms and conditions given by Apple.</li>
                    <li style="display:none">
                      <input type="radio"/>
                      <span>Apply a custom EULA to all chosen territories. If you don't choose all territories, <a href="#">Apple's standard EULA</a> 
	will apply to all remaining ones. Custom EULAs must meet <a href="#">Apple's minimum terms</a>.</span> </li>
                  </ul>
                                <div class="publish_content_label">
                                    <label>Copyright :</label>
                                </div>
                                <div class="publish_content_textbox">
                                     
                                        <input  maxlength="255" name='copyright' type="text" placeholder="Select application type" value="<?php echo isset($dataAndroid['copyright']) ? $dataAndroid['copyright'] : ''?>"/>
                           		 </div>
                                 
                                 <div class="publish_content_info">
                                	<p>
                                    	The name of the person or entity that owns the exclusive rights to your app, preceded by the year the rights were obtained (for example, "2008 Acme Inc."). Do not provide a URL.
                                    </p>
                                </div>
                                 
                                 <div class="publish_content_label full">
                    <label>Trade Representative Contact Information</label>
                  </div>
                                 <ul class="rad-list">
                    <li>
                      <input type="radio"/>
                      <span>Display Trade Representative Contact Information on the Korean App Store.</span> </li>
                  </ul>
                                 
                            </div>
                            
                            
                            
                            <div class="publish_content_form" style="float:left">
                            	<p class="cate-heading" style="padding-bottom:0">App Review information. <small style="display:block; margin-top:10px;color:#222; font-size:14px; line-height:20px">contact Information (?)</small></p>
                            	
                                <div class="publish_content_textbox" style="width:48%; margin:1% 1% 1% 0; float:left">
                                    <input  maxlength="255" name='first_name' value='<?php echo isset($dataAndroid['publisher_first_name']) ? $dataAndroid['publisher_first_name'] : ''?>' type="text" placeholder="First name">
                                </div>
                                <div class="publish_content_textbox" style="width:48%; margin:1%; float:left">
                                    <input  maxlength="255" name='last_name' value='<?php echo isset($dataAndroid['publisher_last_name']) ? $dataAndroid['publisher_last_name'] : ''?>' type="text" placeholder="Last Name">
                                </div>
                                <div class="publish_content_textbox" style="width:48%; margin:1% 1% 1% 0; float:left">
                                    <input  maxlength="255" name='phone' onKeyPress="return isNumber(event)" value='<?php echo isset($dataAndroid['publisher_phone']) ? $dataAndroid['publisher_phone'] : ''?>' type="text" placeholder="Phone number">
                                </div>
                                <div class="publish_content_textbox" style="width:48%; margin:1%; float:left">
                                    <input class=" email" maxlength="255" name='email' value='<?php echo isset($dataAndroid['publisher_email']) ? $dataAndroid['publisher_email'] : ''?>' type="text" placeholder="Email">
                                </div>
                            </div>
                            
                            <div class="developer_account">
	                            <h3>Please make us the admin of the appstore account by just adding the following email

and give us the admin rights, by following process
</h3>
                                <div class="developer_account_info">
                                	<p>You need to <a href="mailto:invite support@instaappy.com">invite support@instappy.com</a> to use your Android dev account as an 
administrator.
</p>
                                    <ul>
                                    	<li> Visit <a href="https://itunesconnect.apple.com" target="_blank">https://itunesconnect.apple.com</a></li>
                                        <li> Sign in using your apple developer account.</li>
                                        <li>Go to "users and roles" section.</li>
                                        <li>Tap on + icon appearing on right side of “users” text.</li>
                                        <li>On the next page you will see title "Add iTunes Connect User”, Enter First Name, Last Name and Email id as following :- (We will provide the details to be entered for these fields).</li>
                                        <li>You may see this warning :- This email address is already associated with an Apple ID. After you add this user, they can use this existing Apple ID and password to sign in to iTunes Connect. Click on Next.</li>
                                        <li>Check on “Admin” under select roles. Click on Next.</li>
                                        <li>On the right hand side, select "All Territories”.Click on Save.</li>
                                        
                                    </ul>
                                </div>
                                
                            </div>
                        <!-- <a href="#" class="make_app_next" style="font-size:16px; text-transform:capitalize; padding:5px 20px">Finish</a> -->
						<input type="hidden" value="<?php echo $_SESSION['appid'];?>" name="app_id">
						<div class="ios_message"></div>
						<input type="submit" class="make_app_next" value="Save">
						<div class="clear"></div>
						</form>
                        <div class="clear"></div>
                        </div>
                    </div>
					
                	<div class="how_publish_body_right">
                  <div class="common_publish_right_box">

                            <a href="publish_android.php">Publish For Android</a>
                            <div class="clear"></div>
                        </div>
                    	<div class="common_publish_right_box">
                        	<h2>Let us help you !</h2>
                            <p>Need any help at any point, Let us guide you till the end.</p>
                            <a href="support.php ">Support</a>
                            <div class="clear"></div>
                        </div>
                    	<div class="common_publish_right_box">
                        	<h2>Need More Help ?</h2>
                            <p>For better view on the topic visit Devloper Console.</p>
                            <a href="https://developer.apple.com/programs/enroll/" target="_blank">Go to Developer Console</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  </section>
</section>
<script>window.jQuery || document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"><\/script>')</script> 
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script>
	(function($){
		$(window).load(function(){
			$("#content-1").mCustomScrollbar();
			$("#content-2").mCustomScrollbar();
			
			
		});
	})(jQuery);
</script>
	<script src="js/chosen.jquery.js"></script>
    <!--<script src="js/ImageSelect.jquery.js"></script>-->
    <script>
    $(".my-select").chosen();
    </script>
    <script>
	   $(document).ready(function(e) {
        	$(".paid").click(function(){
				 $(".paid").removeClass("paid_active")
				$(this).addClass("paid_active")
				
			});
    });
	</script>
    
    <!--dpk script--> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script>
    $(document).ready(function(){
		$('.brws li img').click(function(){
			//$(this).parent().next('input[type="file"]').trigger('click');
			$(this).next('input[type="file"]').trigger('click');
			});
		});
    </script>
	<script>
	$(document).ready(function(e) {
		/* Upload Phone Screens Starts */
			$(".upload_image_scrn").change(function () {
				var current_item = $(this);
				$(this).next(".upload_message").empty(); // To remove the previous error message
				var ttl_img = this.files.length;
					if(ttl_img > 0){
						var index=$(this).parents("li").attr("class").split("_")[1];
						if(index==1){
						var type='one';	
						}
						if(index==2){
						var type='two';	
						}
						if(index==3){
							var type='three';
						}
						if(index==4){
						var type='four';	
						}
						if(index==5){
						var type='five';	
						}
							var file = this.files[0];
							var imagefile = file.type;
							var match = ["image/jpeg", "image/png", "image/jpg"];
							var fsize = file.size; //get file size
							var ftype = file.type; // get file type
							if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
							{
								$(".cropcancel").trigger("click");
								$("#page_ajax").html('').hide();
								$(".popup_container").css({'display': 'block'});
								$(".confirm_name .confirm_name_form").html('<p>Please Select A valid Image File</p>Only jpeg, jpg and png Images type allowed');
								$(".confirm_name").css({'display': 'block'});
								return false;
								
							}
							else if (fsize > 1048576) /* Allowed file size is less than 1 MB (1048576) */
							{								
								$(".cropcancel").trigger("click");
								$("#page_ajax").html('').hide();
								$(".popup_container").css({'display': 'block'});
								$(".confirm_name .confirm_name_form").html('<p>Too big Image file!</p>Please reduce the size of your photo using an image editor.');
								$(".confirm_name").css({'display': 'block'});
								return false;
							}
							else
							{
								$('#screenoverlay').fadeIn();
                         var timenow = Date.now();
                        var reader = new FileReader();						
                       reader.onload = function (e) {
						   
                                    $.ajax({
                                        type: "POST",
                                        url: "imageload.php",
                                        data: "image=" + reader.result + "&imgname=" + "panelimage/" + timenow + file.name,
                                        async: false,
                                        success: function (response) {
                                            if (response)
                                            {
                                                var newresponse = $.parseJSON(response);
                                                var imagename = newresponse.imageurl;
                                                var imgwidth = newresponse.width;
                                                var imgheight = newresponse.height;
                                                var imagePath = BASEURL + imagename;
												if(index==1 && ((imgwidth>=750 && imgwidth<=1334) && (imgheight>=750 && imgheight<=1334))==false){
												$(".popup_container").css({'display': 'block'});
												$(".confirm_name .confirm_name_form").html('<p>Please Upload valid Iimage Dimension   750w X 1334h</p>');
												$(".confirm_name").css({'display': 'block'});
												return false;													
												}

												if(index==2 && ((imgwidth>=1242 && imgwidth<=2208) && (imgheight>=1242 && imgheight<=2208))==false){
												$(".popup_container").css({'display': 'block'});
												$(".confirm_name .confirm_name_form").html('<p>Please Upload valid Iimage Dimension 1242w X 2208h </p>');
												$(".confirm_name").css({'display': 'block'});
												return false;	
													
												}
												if(index==3 && ((imgwidth>=640 && imgwidth<=1136) && (imgheight>=640 && imgheight<=1136))==false){
												$(".popup_container").css({'display': 'block'});
												$(".confirm_name .confirm_name_form").html('<p>Please Upload valid Iimage Dimension 640w X 1136h</p>');
												$(".confirm_name").css({'display': 'block'});
												return false;	
												}
												if(index==4 && ((imgwidth>=640 && imgwidth<=960) && (imgheight>=640 && imgheight<=960))==false){
												$(".popup_container").css({'display': 'block'});
												$(".confirm_name .confirm_name_form").html('<p>Please Upload valid Iimage Dimension 640w X 960h </p>');
												$(".confirm_name").css({'display': 'block'});
												return false;	
												}
												if(index==5 && ((imgwidth>=1536 && imgwidth<=2048) && (imgheight>=1536 && imgheight<=2048))==false){
												$(".popup_container").css({'display': 'block'});
												$(".confirm_name .confirm_name_form").html('<p>Please Upload valid Iimage Dimension  1536w X 2048h </p>');
												$(".confirm_name").css({'display': 'block'});
												return false;	
												}
																								
													$(current_item).prev().attr('src',e.target.result);										  	
												 $.ajax({
																						type: "POST",
																						url: "ajax.php",
																						data: "icons_image=" + imagePath +"&index="+type +"&type=ios_publish_images",
																						async: false,
																						success: function (response) {
																							if (response=="success")
																							{                                               
																							   $('#screenoverlay').fadeOut();
																						   }
																							else
																							{
																							$('#screenoverlay').fadeOut();
																							$(".popup_container").css({'display': 'block'});
																							$(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
																							}
																						},
																						error: function () {
																							$(".cropcancel").trigger("click");
																							$("#page_ajax").html('').hide();
																							$(".popup_container").css({'display': 'block'});
																							$(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
																							$(".confirm_name").css({'display': 'block'});
																							return false;
																						}
																					});                  	
													
																			   
																																							   
                                                
                                                                                                                                                               
                                            }
                                            else
                                            {
                                                $('#screenoverlay').fadeOut();
												$(".popup_container").css({'display': 'block'});
                                            $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                                            $(".confirm_name").css({'display': 'block'});
                                            return false;

                                            }
                                        },
                                        error: function () {
                                            $(".cropcancel").trigger("click");
                                            $("#page_ajax").html('').hide();
											 $('#screenoverlay').fadeOut();
                                            $(".popup_container").css({'display': 'block'});
                                            $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                                            $(".confirm_name").css({'display': 'block'});
                                            return false;
                                        }
                                    });
                                }
                                reader.readAsDataURL(file);
                        reader.readAsDataURL(this.files[0]);
 
										
									//}
								//}
								//$('#android_app_publish').ajaxSubmit(options);
							}
						
					}
			});
			
			/* Upload Phone Screens Ends */
		});	
		function isNumber(evt) {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
		//function to format bites bit.ly/19yoIPO
		function bytesToSize(bytes) {
			var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
			if (bytes == 0)
				return '0 Bytes';
			var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		}
</script>
<script type="text/javascript" src="js/jquery1.4.4.min.js"></script>
<script type="text/javascript" src="js/validate.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script>
$(document).ready(function(){
	/* IOS upload */
	var options = {
		target: '.ios_message', // target element(s) to be updated with server response 
		url: BASEURL + 'API/uploadappscreen.php/uploadiospublish',
		beforeSubmit: beforeSubmit, // pre-submit callback 
	success: afterSuccess,
				// post-submit callback 
				resetForm: true        // reset the form after successful submit 
			};
			/* Android Ends */
		function afterSuccess()
			{
				//$('#submit-btn').show(); //hide submit button
				//$('#loading-img').hide(); //hide submit button
				$("#screenoverlay").css("display", "none");
			}
	//function to check file size before uploading.
	function beforeSubmit() {
		
		//check whether browser fully supports all File API
		if (window.File && window.FileReader && window.FileList && window.Blob)
		{
			$(".ios_message").html("");
		}
		else
		{
			//Output error to older browsers that do not support HTML5 File API
			$(".ios_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
			return false;
		}
	}

	
	$("#ios_app_publish").validate({
		submitHandler: function (form) {
			$("#screenoverlay").css("display", "block");
			$('#ios_app_publish').ajaxSubmit(options);	
		}		
	});
});

</script>
</body>
</html>
