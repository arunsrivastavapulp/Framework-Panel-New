<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/myapp/myapps.php');
require_once('modules/checkapp/app_screen.php');
$apps = new MyApps();
$app_screen = new WebScreen();
$app_data = $apps->app_name($_SESSION['appid']);
?>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner">
            <div class="how_publish">
            	<div class="how_publish_left">
                    <img src="images/Android-App-Publish.png"><h1>Android App Publish :</h1>
                    <h2>Fields marked with * need to be filled before publishing.</h2>
                </div>
                <div class="clear"></div>
                <div class="how_publish_body">
                	<div class="how_publish_body_left">
                    	<div class="publish_content">
                            <h2>Product Details</h2>
							<form action='' method='post' name='android_app_publish' id='android_app_publish' enctype="multipart/form-data">
                            <div class="publish_content_form">
                                <div class="publish_content_label">
                                    <label>Title* :</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <input type="text" class="required" maxlength="50" name='app_name' value='<?php echo isset($app_data['summary']) ? $app_data['summary'] : ''?>' placeholder="Name of app appear in app store" readonly>
                                </div>
                                <div class="publish_content_label">
                                    <label>Short<br/> Description* :</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <textarea placeholder="Limit 0-80 characters" class="required" maxlength="80" name='app_short_desc'></textarea>
                                </div>
                                <div class="publish_content_label">
                                    <label>Full<br/> Description* :</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <textarea class="disc required" minlength="10" maxlength="4000" name='app_full_desc' placeholder="Limit 10-4000 characters"></textarea>
                                </div>
                                <div class="publish_content_info">
                                	<p>
                                    	Please check out these <a href="#">tips on how to create policy compliant app descriptions</a> to avoid some common reasons for app suspension.
                                    </p>
                                </div>
                                
                            </div>
                            
							<div class="publish_content_text">
								<span class="graphic_heading">GRAPHIC ASSETS</span>
								<p>
										If you haven’t added localized graphics for each language, graphics for your default language will be used. <a href="#"> Learn more about graphic assets</a>.
								</p>
								<span class="graphic_heading">Screenshots </span>
								<p>Default – English (United States) – en-US<br/>
									   JPEG or 24-bit PNG (no alpha). Min length for any side: 320px. Max length for any side: 3840px.
	At least 2 screenshots are required overall. Max 8 screenshots per type. Drag to reorder or to move between types.
								</p>
								<p>
								 For your app to be showcased in the 'Designed for tablets' list in the Play Store, you need to upload at least one 7-inch and one 10-inch screenshot. If you previously uploaded screenshots, make sure to move them into the right area below.<br/><a href="#">Learn how tablet screenshots will be displayed in the store listing.</a>
								</p>
                            </div>
                            <div class="publish_content_screenshot">
                            	<div class="publish_content_phone">
                                	<span>Phone</span>
                                    <div class="img_box phone_image">
                                    	<a href="javascript:void(0);" class="upload_screen"><img src="images/browse_nw.png" alt=""/></a>	
                                    </div>
									 <input type="file" multiple="multiple" name="phone_screen[]" class="upload_screen_file" style="display:none;">
                                    <div class="clear"></div>
                                </div>
								<div class="screen_message"></div>
                                <div class="publish_content_phone">
                                	<span>7-inch tablet</span>
                                    <div class="img_box">
                                    	<a href="#"> <img src="images/browse_nw.png" alt=""/> </a>		
                                    </div>
                                    <div class="img_box-text">
                                    	<p>
                                        	Add at least one 7-inch screenshot here to help tablet users see how your app will look on their device.
                                        </p>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="publish_content_phone">
                                	<span>10-inch tablet</span>
                                    <div class="img_box">
                                    	<a href="#"> <img src="images/browse_nw.png" alt=""/> </a>	
                                    </div>
                                    <div class="img_box-text">
                                    	<p>
                                        	Add at least one 10-inch screenshot here to help tablet users see how your app will look on their device.
                                        </p>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                
                            	
                            </div>
                            <div class="publish_content_icon">
								<?php $selected_app_icon = $app_screen->get_selected_app_icon($_SESSION['appid']);?>
                            	<div class="hi-res-icon">
                                	<p>Hi-res Icon*<br/>
                                    <span>Default - English (United Status) - en-US</span><br/>
                                    512 X 512<br/>
 									32-bit PNG <span>(with alpha)</span>
                                    </p>
                                    <div class="img_box">
                                    	<a href="#"> <img src="<?php echo $selected_app_icon;?>" alt=""/> </a>		
                                    </div>
                                </div>
                                <div class="hi-res-icon feature-icon">
                                	<p>Feature Graphic*<br/>
                                    <span>Default - English (United Status) - en-US</span><br/>
                                    1024 w X 500 h<br/>
 									JPG <span>or</span> 24-bit PNG<span> (no alpha)</span>
                                    </p>
                                    <div class="img_box">
                                    	<a href="#"> <img src="images/browse_nw.png" alt=""/> </a>	
                                    </div>
                                </div>
                                <div class="hi-res-icon prmo-icon">
                                	<p>Promo Graphic<br/>
                                    <span>Default - English (United Status) - en-US</span><br/>
                                    180 w X 120 h<br/>
 									JPG <span>or</span> 24-bit PNG<span> (no alpha)</span>
                                    </p>
                                    <div class="img_box">
                                    	<a href="#"> <img src="images/browse_nw.png" alt=""/> </a>	
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="publish_content_video">
                            	<div>
                                	<p>
                                    	<span>Promo Video</span>
                                        Default - English (United States) - en-US<br/>
                                        YouTube video<br/>
                                        Please enter a URL
                                    	
                                    </p>
                                    <input type="text" name='app_promo_url' id='app_promo_url' class="required url" value="">
                                </div>
                                <div class="clear"></div>
                            </div>
                            
                            
                            <div class="publish_content_form">
                            	<p class="cate-heading">CATEGORIZATION</p>
                            	<div class="publish_content_label">
                                    <label>Application<br/>type:</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <select name='app_type' id='app_type' class="required">
                                    	<option value="">Select an application type</option>
                                    	<option value="applications">Applications</option>
                                    </select>
                                </div>
                                <div class="publish_content_label">
                                    <label>Category :</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <select name='app_category' id='app_category' class="required">
                                    	<option value="">Select a category</option>
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
                                 <div class="publish_content_label">
                                    <label>Content<br/>rating :</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <select name='app_contant_rating' id='app_contant_rating' class="required">
                                    	<option value="MATURE">High Maturity</option>
										<option value="TEEN">Medium Maturity</option>
										<option value="PRE_TEEN">Low Maturity</option>
										<option value="SUITABLE_FOR_ALL">Everyone</option>
										<option value="NONE">Select a content rating</option>
									</select>
                                </div>
                               
                                <div class="publish_content_info">
                                	<p>
                                    	<a href="#">Learn more about content rating.</a>
                                    </p>
                                </div>
                                <div class="publish_content_label">
                                    <label>New content<br/>rating :</label>
                                </div>
                                <div class="publish_content_textbox">
                                     
                                        <p>
                                            You need to fill a rating questionnaire <a href="#">content rating.</a>
                                        </p>
                                
                                
                           		 </div>
                            </div>
                            
                            
                            
                            <div class="publish_content_form">
                            	<p class="cate-heading">CONTACT DETAILS</p>
                            	<div class="publish_content_label">
                                    <label>Website:</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <input type="text" class="required url" maxlength="50" name="app_website" value="" placeholder="Dummy Content">
                                </div>
                                <div class="publish_content_label">
                                    <label>Email:</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <input type="text" class="required email" maxlength="50" name="app_email" value="" placeholder="Dummy Content">
                                </div>
                                <div class="publish_content_info">
                                	<p>
                                    	Please provide an email address where you may be contacted. This address will be publicly displayed with you app.
                                    </p>
                                </div>
                                <div class="publish_content_label">
                                    <label>Phone:</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <input type="text" class="required" minlength="10" maxlength="15" name="app_phone" value="" onKeyPress="return isNumber(event)" placeholder="Dummy Content">
                                </div>
                                 
                            </div>
                            
                            
                            
                        
                            
                            
                            
                            
                            <div class="publish_content_form">
                            	<p class="cate-heading">PRIVACY POLICY</p>
                                <span>If you wish to provide a privacy policy URL for this application, please enter it below.</span>	
                            	<div class="publish_content_label">
                                    <label>Privacy Policy:</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <input type="text" class="required url" maxlength="50" name="app_privacy_url" value="" placeholder="Dummy Content">
                                </div>
                                <div class="publish_content_info">
                                	<p>
                                    	<span><input type="checkbox" id="app_privacy_url"></span>
                                    	Not submitting a privacy policy URL at this time. Learn more.
                                    </p>
                                </div>
                                
                                 
                            </div>
                            
                            <div class="publish_content_form">
                                
                            	<p class="cate-heading">CONTENT RATING</p>
                                <div class="publish_content_rating"><p>
                                	The Google Play content rating system for apps and games is designed to deliver reputable, locally relevant ratings to users around the world. The rating system includes official ratings from the International Age Rating Coalition (IARC) and its participating bodies.
                                </p>
                                <span>Developer responsibilities</span>
                                <ul>
                                	<li>
                                    	Complete the content rating questionnaire for each new app submitted to Developer Console, 		for all existing apps that are active on Google Play, and for all app updates where there has 		been a change to app content or features that would affect the responses to the. 	
                                    </li>
                                    <li>
                                    	Provide accurate responses to the content rating questionnaire. Misrepresentation of your 		app's content may result in removal or suspension.
                                    </li>
                                </ul>
                                 <span>Your rating will be used to:</span>
                                <ul>
                                	<li>
                                    	Inform consumers about the age appropriateness of your app. 	
                                    </li>
                                    <li>
                                    	Block or filter your content in certain territories or to specific users where legally required.
                                    </li>
                                    <li>
                                    	Evaluate your app's eligibility for special developer programs.
                                    </li>
                                </ul>
                            	<p>
                                The content rating questionnaire and the new Content Ratings Guidelines are a condition of your participation in the Google Play store under the Developer Distribution Agreement. Learn more
                                </p></div>
                                 
                            </div>
                            
                            <!--
                            <div class="publish_content_form">
                                
                            	<p class="cate-heading">PRICING & DISTRIBUTION</p>
                                <div class="publish_price_distribution">
                                	<div class="application">
                                        <p>This application is</p>
                                        <span class="paid paid_active">Paid</span>
                                        <span class="paid">Free</span>
                                        <div class="clear"></div>
                                    </div>  
                                    <div class="price">
                                        <p>Default price</p>
                                       <input type="text" placeholder="INR">
                                       <span>This price exclude tax.</span>
                                        <div class="clear"></div>
                                    </div>   
                                    <div class="autoprice">
                                       <p>Auto price<br/> conversion:</p>
                                       <p>
                                       You can set local prices for other countries manually or auto-convert the default price based on today's exchange rate and tax rates (if applicable).
                                       </p>
                                       <div class="clear"></div>
                                       <div class="autoprice_check">
                                       	<input type="checkbox"><span>Overwrite existing prices.</span>
                                       </div> 
                                        <div class="clear"></div>
                                        <div class="autoprice_button">
                                        	<span>Auto-convert prices now</span>
                                        </div>
                                    </div>   
                                    
                                
                                </div>
                                 
                            </div>
                            -->
                            
                           
                            
                            <div class="developer_account">
	                            <h3>Google Play Android Developer Account</h3>
                                <div class="developer_account_info">
                                	<p>You need to invite <a href="mailto:support@instaappy.com"> support@instaappy.com</a> to use your Android dev account as an administrator.
</p>
                                    <ul>
                                    	<li>Log in to your android developer account</li>
                                        <li>Go to setting option on left side of the page</li>
                                        <li>Click ‘Manage user accounts & rights’ link	</li>
                                        <li>Click 'Invite a new user button'</li>
                                        <li>Enter 'support@instappy.com' and send invitation</li>
                                        <li>Choose ‘Administrator’ in choose a role for this user.</li>
                                        <li>Send Invite.</li>
                                    </ul>
                                </div>
                                <div class="developer_account_details">
                                	<div class="developer_details_label">
                                        <label>Android Publisher Email ID:*</label>
                                    </div>
                                    <div class="developer_details_textbox">
                                        <input type="text" class="required" maxlength="50" name="app_dev_acc_email" value="" >
                                        <a href="#">?</a>
                                    </div>
                                	<div class="developer_details_label">
                                        <label>Android Developer Console Account Name:*</label>
                                    </div>
                                    <div class="developer_details_textbox">
                                        <input type="text" class="required" maxlength="50" name="app_dev_acc_name" value="" >
                                        <a href="#">?</a>
                                    </div>
                                </div>
                            </div>
							<!-- <a href="#" class="make_app_next">Finish</a> -->
							<input type="submit" class="make_app_next" value="Finish">
							<div class="clear"></div>
							</form>
                        </div>
                    </div>
                	<div class="how_publish_body_right">
                    	<div class="common_publish_right_box">
                        	<h2>Let us help you !</h2>
                            <p>Need any help at any point, Let us guide you till the end.</p>
                            <a href="#">Support</a>
                            <div class="clear"></div>
                        </div>
                    	<div class="common_publish_right_box">
                        	<h2>Don't have a Developer Account?</h2>
                            <!-- <p>For better view on the topic visit Devloper Console.</p> -->
                            <a href="https://play.google.com/apps/publish/signup/">Create a Developer Account</a>
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
    <script src="js/ImageSelect.jquery.js"></script>
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
	<script type="text/javascript" src="js/jquery1.4.4.min.js"></script>
	<script type="text/javascript" src="js/validate.min.js"></script>
	<script type="text/javascript" src="js/jquery.form.min.js"></script>
	<script>
	$(document).ready(function(e) {
		/* Upload Screens Starts */
			$(".upload_screen").click(function () {
				$('.upload_screen_file').trigger('click');
			});
			
			/* User Profile upload */
			var options = {
				target: '.screen_message', // target element(s) to be updated with server response 
				url: BASEURL + 'API/uploadappscreen.php/upload_screen_file',
				beforeSubmit: beforeSubmit, // pre-submit callback 
				success: afterSuccess, // post-submit callback 
				resetForm: true        // reset the form after successful submit 
			};
			/* User Profile Upload Ends */
			$(".upload_screen_file").change(function () {
				$(".screen_message").empty(); // To remove the previous error message
				var file = this.files[0];
				var imagefile = file.type;
				var match = ["image/jpeg", "image/png", "image/jpg"];
				if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
				{
					//$('#previewing').attr('src','noimage.png');
					$(".screen_message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
					return false;
				}
				else
				{
					var ttl_img = this.files.length;
					if(ttl_img > 0){
						$(".publish_content_phone .phone_image").hide();
						for(i = 0; i <= ttl_img; i++){
							var reader = new FileReader();
							reader.onload = imageIsLoaded;
							reader.readAsDataURL(this.files[i]);
						}
					}
					//$('#android_app_publish').ajaxSubmit(options);
				}
			});
			function afterSuccess()
			{
				//$('#submit-btn').show(); //hide submit button
				//$('#loading-img').hide(); //hide submit button

			}
			//function to check file size before uploading.
			function beforeSubmit() {
				//check whether browser fully supports all File API
				if (window.File && window.FileReader && window.FileList && window.Blob)
				{

					if (!$('.upload_screen_file').val()) //check empty input filed
					{
						$(".screen_message").html("Are you kidding me?");
						return false;
					}

					var fsize = $('.upload_screen_file')[0].files[0].size; //get file size
					var ftype = $('.upload_screen_file')[0].files[0].type; // get file type


					//allow only valid image file types 
					switch (ftype)
					{
						case 'image/png':
						case 'image/gif':
						case 'image/jpeg':
						case 'image/pjpeg':
							break;
						default:
							$(".screen_message").html("<b>" + ftype + "</b> Unsupported file type!");
							return false;
					};

					//Allowed file size is less than 1 MB (1048576)
					if (fsize > 1048576)
					{
						$(".screen_message").html("<b>" + bytesToSize(fsize) + "</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
						return false;
					}

					//$('#submit-btn').hide(); //hide submit button
					//$('#loading-img').show(); //hide submit button
					$(".screen_message").html("");
				}
				else
				{
					//Output error to older browsers that do not support HTML5 File API
					$(".screen_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
					return false;
				}
			}

			//function to format bites bit.ly/19yoIPO
			function bytesToSize(bytes) {
				var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
				if (bytes == 0)
					return '0 Bytes';
				var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
			}
			function imageIsLoaded(e) {
				//$("#file").css("color","green");
				//$('#image_preview').css("display", "block");
				$('<img src="'+e.target.result+'" alt="" width="100" height="100">').insertAfter(".publish_content_phone .phone_image");
				//$('#preview_avatar').attr('src', e.target.result);
				//$('#preview_avatar').attr('width', '250px');
				//$('#preview_avatar').attr('height', '230px');
			}
		/* Upload Screens Ends */
		$("#android_app_publish").validate();
		/*$("#user_profile").validate({
			submitHandler: function (form) {
				//$('#user_profile .preloader').show();
				$("#screenoverlay").css("display", "block");
				jQuery.ajax({
					url: BASEURL + 'API/user.php/userregister',
					type: "post",
					data: $(form).serialize(),
					success: function (response) {
					  $("#screenoverlay").css("display", "none");
						$(".popup_container").css("display", "block");
						$(".confirm_name").css("display", "block");
						$(".userprofile_popup").css("display", "none");
						$(".userprofile").css("display", "none");
						$(".confirm_name_form p").text(response);
						//$('#user_profile .preloader').hide();
						//console.log(response);
					},
					error: function () {
						$("#screenoverlay").css("display", "none");
						$(".popup_container").css("display", "block");
						$(".confirm_name").css("display", "block");
						$(".userprofile_popup").css("display", "none");
						$(".userprofile").css("display", "none");
						$(".confirm_name_form p").text("Error in Saving.Please try again.");
						
					}
				});
			}
		});*/
	});
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
	</script>
</body>
</html>
