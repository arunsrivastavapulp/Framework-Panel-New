<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
$custid = $_SESSION['custid'];
if (file_exists('modules/checkapp/app_screen.php')) {
    require_once('modules/checkapp/app_screen.php');
    $webscreen = new WebScreen();
}

if (isset($_SESSION['currencyid'])) {
                    $checkcountry = $_SESSION['country'];
                    $currency = $_SESSION['currencyid'];
                    $currencyIcon = $_SESSION['currency'];
                } else {
                    $db->get_country();
                    $checkcountry = $_SESSION['country'];
                    $currency = $_SESSION['currencyid'];
                    $currencyIcon = $_SESSION['currency'];
                }

require_once('modules/apphtml/apphtml.php');
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
$appID = $_SESSION['appid'];
 require_once('modules/opencart-integration/opencart-integration.php');
    $obj = new Opencart();
	$app_details=$obj->get_app_details($appID);
?>

<?php 
if ($app_id)
 {
	 $published = $webscreen->get_category($app_id); 
 $isPublished=$published['published'];
    $screenShot='';
    $appType=1;
    $app_data = $obj->edit_catalogue_app($app_id);
    $html = $obj->get_cuurent_app_html($app_data['theme']);
     $pageScreen = $webscreen->app_screenshot($app_id); /* 1 == premium */
    if(!empty($pageScreen))
    {
        $screenShot=$pageScreen['screenshot_url'];
         $appType=$pageScreen['type_app'];
    
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
                    <?php 
                    if($appType == 1)
                    {
                        ?>
                    <div id="content-1" class="content mCustomScrollbar clear first-page " >

                        <div class="overlay">
                        </div>

                        <div class="container droparea" style="float:left;width:100%;">
                        </div>
                    </div>
                    <?php
                    }
                    else
                    {
                        ?>
                   <img src="<?php echo $screenShot;?>" />
                    
                    <?php
                    
                    }
                    ?>
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
                                <div class="clear"></div>
                            </div>
                            <div class="mid_right_box_body">
                                <div class="choose_screen">
												<?php if($isPublished == 0)
												{ 
												?> 
                                                                <h2>Choose a premium splash screen for your awesome app <span style="color:#ffcc00;"><?php if($currency==1){ ?>(Price- Rs.1,500)<?php } else {?>(Price- $23)<?php }?></span></h2>
                                    <div class="screens">
                                        <?php
                                        $webscreen = new WebScreen();
                                        $screens = $webscreen->get_app_screen($app_id, 1); /* 1 == premium */
										if(count($screens) > 0){
                                        $sel_screen = $webscreen->get_selected_splash_screen($app_id,1);
                                        foreach ($screens as $screen) {
									
                                            if ($screen['image_link'] != '') {
                                                if (trim($sel_screen) == $screen['id']) {
                                                    ?>
                                                    <img class="selected" src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'>
                                                <?php } else { ?>
                                                    <img src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'>
                                                    <?php
                                                }
                                            }
                                        }
										if($webscreen->get_total_app_screen($app_id,1) > 8){
										?><div class="more_paid_screen"></div>
										<div class="clear"></div>
										<input type="button" data-page="1" data-total_page="<?php echo intval($webscreen->get_total_app_screen($app_id,1)/8);?>" value="Load More" id="load_more_paid_screen" class="load_more">
										<?php } } ?>
                                    </div>
									<script>$('.hint_content').show();</script>
												<?php
												} 
												else
												{
												?>
												
												<div class="screens">
												<?php
												$webscreen = new WebScreen();
												$screens = $webscreen->get_app_screen($app_id, 1); /* 1 == premium */
												if(count($screens) > 0){
												$sel_screen = $webscreen->get_selected_splash_screen($app_id,1);
												foreach ($screens as $screen) {

												if ($screen['image_link'] != '') {
												if (trim($sel_screen) == $screen['id']) {
												?>
												<script>$('.hint_content').hide();</script>
												<h2 class="icon_tip">App Splash Screen</h2>
												<img class="selected" src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'><div class="clear"></div>
												<?php 
												} 
												}
												}
											 
												} ?>
												</div>
												<?php	
												}
												?>
                                    <h2>Choose a free splash screen for your awesome app:</h2>
                                    <div class="screens">
                                        <?php
                                        $webscreen = new WebScreen();
                                        $screens = $webscreen->get_app_screen($app_id);
										if(count($screens) > 0){
                                        $sel_screen = $webscreen->get_selected_splash_screen($app_id);
                                        foreach ($screens as $screen) {
											
                                            if ($screen['image_link'] != '') {
                                                if (trim($sel_screen) == $screen['id']) {
                                                    ?>
                                                    <img class="selected" src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'>
                                                <?php } else { ?>
                                                    <img src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'>
                                                    <?php
                                                }
                                            }
                                        }
                                        if($webscreen->get_total_app_screen($app_id) > 8){
										?><div class="more_default_screen"></div>
										<div class="clear"></div>
										<input type="button" data-page="1" data-total_page="<?php echo intval($webscreen->get_total_app_screen($app_id)/8);?>" value="Load More" id="load_more_default_screen" class="load_more">
										<?php } } ?>
                                    </div>
                                    
                                </div>
                                <div class="choose_icon">
                                    <?php
                                    if ($app_id) {
                                        $self_screens = $webscreen->get_self_screens($app_id);
										//echo count($self_screens);
										//die;
                                        //echo "<pre>"; print_r($self_screens); echo "</pre>";
										$sel_screen = $webscreen->get_selected_splash_screen($app_id);
                                    }
                                    ?>
                                    <form name='upload_screen' id='upload_screen' method='post' action='' enctype="multipart/form-data">
                                        <h2>Or, you can even upload your very own splash screen for your app. </h2>
                                        <p>We suggest that your splash screen image should not be more than <span>2 MB</span> in size, and <span>1080 x 1920 pixels</span> for any device.</p>
                                        <div class="os_screen">
                                            <!-- <h2>iPhone</h2> -->
                                            <?php
                                            if (count($self_screens) > 0) {
												
                                                //$ios_size = array(320,768,640,750);
												
                                                $ios_size = array(1080);
												
                                                foreach ($self_screens as $self_screen) {
                                                    $img_ext = explode('X',$self_screen['name']);
                                                    //$size = end(explode('X',$img_ext[0]));
													//print_r($img_ext);
													
                                                     $size = substr($img_ext[0],-4);
													 //$size = substr(strrchr($self_screen['name'], "X"), 1);
													// $size = explode('_',$img_ext[0]);
													// $size = $size[1];
													
                                                    if (in_array($size, $ios_size)) {
														
                                                        ?>
                                                        <div class="screen_size splashLarge">
                                                            <?php if (trim($sel_screen) == $self_screen['id']) { ?>
                                                                <img name="up" class="selected" src="<?php echo $self_screen['image_link']; ?>" defaultSplash="1" data-id="<?php echo $self_screen['id']; ?>" data-size="1242*2208">
                                                            <?php } else { ?>
                                                                <img name="down" src="<?php echo $self_screen['image_link']; ?>" defaultSplash="1" data-id="<?php echo $self_screen['id']; ?>" data-size="1080*1920">
                                                            <?php }?>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <?php
                                                    }
                                                    else{?>
                                                      <div class="screen_size splashMedium">
                                                     <img name="down" src="<?php echo $self_screen['image_link']; ?>" defaultSplash="1" data-id="<?php echo $self_screen['id']; ?>" data-size="1080*1920">
                                                    <div class="clear"></div>
                                                </div>   

                                                    <?php
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <div class="screen_size splashMedium">
                                                    <img src="images/iphone_screen_2.jpg" data-size="640*960">
                                                    <div class="clear"></div>
                                                </div>
                                            <?php } ?>
                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="clear"></div>
                                        <input type='file' name='screens' style='display:none;'>
                                        <input type="hidden" name='appid' value='<?php print_r($app_id); ?>'>
                                        <div class="selected_screen" style="display:none;"><?php if($sel_screen != '' && $sel_screen > 1){ echo $sel_screen; }?></div>
                                        <div id='validate_upload_screens_message'></div>
                                    </form>
                                </div>
								<div class="continue_screens_message"></div>
                                <a href="javascript:void(0);" id="splash_btn1" class="make_app_next">Save & Continue</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                            <div class="mid_right_box">
                                <div class="mid_right_box_head">
                                    <h1>Share Text via Facebook- We need your Facebook APP ID.</h1>
                                    <h2>To create a Facebook Application ID:<br/>Go to the Facebook Developers Apps page, and sign in with your Facebook username and password.<br/>Click the "Create New App" button.<br>Enter a name for the application in the "App Name" field.<br>Read the Facebook Platform Policies and decide if you accept them.</h2>
                                    <a class="read_more">Read More...</a>
                                    <div class="clear"></div>
                                </div>
                        <div class="mid_right_box_body">
                            <div class="design_menu_box social_sharing">

                                <div class="content_label">
                                    <label>Facebook App Id</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="facebook_id_text" maxlength="20" value="<?php echo isset($app_details['fbsdk_id'])?$app_details['fbsdk_id']:'';?>">
                                    <em>Facebook APP ID is required for sharing text on Facebook via Mobile Apps<a href="<?php echo $basicUrl ?>docs/Facebook_App_ID_Tutorial.pdf" target="_blank">Learn more...</a></em>
                                </div>

                            </div>
                            <div class="advertising_notification">
                                    <h2>Advertisements</h2>
                                    <p>Advertisements are a great way to earn from your app. By 'ticking' the check box, you will authorize third-party to display their apps on your app, which will be an add-on revenue generator for you. All you have to do is fill in the relevant fields with required information, and voila! You are all set to go.</p>
                                    <a class="read_more">Read more...</a>
                                    <div class="clear"></div>
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
                                            <select id="adserver">
                                              <!--  <option>Flury</option> -->
											  
                                            <?php 
                                             $save = new Saveapphtml();       
                                                  $addata = $save->checkAdData();  
                                                  foreach($addata as $adDetails)
                                                  {
                                                  ?>
                                                  <option value="<?php echo $adDetails['id'];?>"><?php echo$adDetails['servername'];?></option>
                                                  <?php } ?>
                                          </select>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                   
                                    <div class="advertising_notification_list">
                                        <div class="advertising_notification_left">
                                            <label>Ad Unit Code</label>
                                        </div>
                                        <div class="advertising_notification_right">
                                            <input type="text" name="Android" id="Android">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <!--<div class="advertising_notification_list">
                                        <div class="advertising_notification_left">
                                            <label>MobileWeb Unit Code</label>
                                        </div>
                                        <div class="advertising_notification_right">
                                            <input type="text">
                                        </div>
                                        <div class="clear"></div>
                                    </div>-->
                                </div>
                             <a href="javascript:void(0);" class="make_app_next" id="save_fbapp">Save & Continue</a>
                            <div class="clear"></div>
                        </div>
                        </div>

                        <!--<div class="mid_right_box">
                            <div class="mid_right_box_head">
                                <h1>Advertisements & Push Notifications</h1>
                                <h1>Advertisements</h1>
                                <h2>Advertisements are a great way to earn from your app. By 'ticking' the check box, you will authorize third-party to display their apps on your app, which will be an add-on revenue generator for you. All you have to do is fill in the relevant fields with required information, and voila! You are all set to go.</h2>
                                <a class="read_more">Read More...</a>
                                <div class="clear"></div>
                            </div>
                                                     <div class="mid_right_box_body">
                                
                            <div class="push_notification">
                                <h2>Push Notification</h2>
                                <p>Push Notifications are a great way to update your app users updated with the hottest deals and offers you are running with. When push a notification, it lands on your app users’ cell phone in the Notifications section.</p>
                                 <a class="read_more">Read More...</a>
                                <div class="clear"></div>
                                <input type="checkbox" id="notification">
                                 <label for="notification">Enable Push notification (cost)</label>
                                 <div class="clear"></div>
                             </div>
                            <div class="continue_screens_message"></div>
                             <a href="javascript:void(0);" class="ad" id="save_add">Save & Continue</a>
                            <div class="clear"></div>
                            </div>
                        </div>-->
                        
                       
                    </div>
                   
                    <div class="clear"></div>
                </div>
            </div>


          <!-- <input type="file" id="uploadImgFile"> -->
            <div class="testContainer">

                <img src="" id="testImg">

            </div>
            <div class="hint_main"><img src="<?php echo $basicUrl; ?>images/ajax-loader.gif"></div>
        </section>
    </section>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.shapeshift.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
    <script type="text/javascript" src="js/publishJS.js"></script>
    <script>
        var page = '<?php echo addslashes(json_decode($html, false)); ?>';
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
        for (var i = 0; i < 3; i++) {
            if (i == 0)
                $(".banner").find("img").eq(i).attr('src', '<?php echo $app_data['banner1']; ?>');
            if (i == 1)
                $(".banner").find("img").eq(i).attr('src', '<?php echo $app_data['banner2']; ?>');
            if (i == 2)
                $(".banner").find("img").eq(i).attr('src', '<?php echo $app_data['banner3']; ?>');

        }
        $(".theme_head").css("background-color", "<?php if ($app_data['background_color']) echo $app_data['background_color'];
                                    else echo '#ff8800'; ?>");
        $(document).ready(function () {
			
	var curruntURL = window.location;
	
            $('#upload_screen .screen_size img').click(function () {
				//alert(```````+'up');
                $('#upload_screen input[type=file]').trigger("click");
            });
			 $(document).off("click", "nav > ul >li")
			if ($('.screens img, .os_screen img').find('.selected')) {
                var sel_img_url = $('.screens img.selected, .os_screen img.selected').data('id');
                $('.selected_screen').html(sel_img_url);
            }
			
            $('.screens, .os_screen').on('click','img',function () {
                $('.screens img, .os_screen img').removeClass('selected');
                var sel_img_url = $(this).data('id');
                $(this).addClass('selected');
                $('.selected_screen').html(sel_img_url);
            });
            var options = {
                target: '#validate_upload_screens_message', // target element(s) to be updated with server response 
                url: 'API/index.php/uploadappscreens',
                beforeSubmit: beforeSubmit, // pre-submit callback 
                //success: afterSuccess, // post-submit callback 
				complete: afterSuccess,
                resetForm: true        // reset the form after successful submit 
            };
            $('#upload_screen input[type=file]').change(function () {
                $('#validate_upload_screens_message').empty();
                var file = this.files[0];
                var imagefile = file.type;
                var match = ["image/jpeg", "image/png", "image/jpg"];
                //var imgtest = document.getElementById("testImg");
                //var imgtest = $('#upload_screen img');
                //var testSize = imgtest.getBoundingClientRect();

                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
                {
					$('#screenoverlay').fadeOut();
                    //$('#previewing').attr('src','noimage.png');
                    $("#validate_upload_screens_message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                    return false;
                }
                else
                {
                    var reader = new FileReader();
                    reader.onload = function (e) {

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
                $('#validate_upload_screens_message').html(response.responseText); //hide submit button
				$('#validate_upload_screens_message').focus();
                if (response.responseText == 'Screens Uploaded Successfully') {
                    window.location.href = curruntURL;
					
                }
            }
            //function to check file size before uploading.
            function beforeSubmit() {
                //check whether browser fully supports all File API
                if (window.File && window.FileReader && window.FileList && window.Blob)
                {

                    if (!$('#upload_screen input[type=file]').val()) //check empty input filed
                    {
						$('#screenoverlay').fadeOut();
                        $("#validate_upload_screens_message").html("Are you kidding me?");
                        return false
                    }

                    var fsize = $('#upload_screen input[type=file]')[0].files[0].size; //get file size
                    var ftype = $('#upload_screen input[type=file]')[0].files[0].type; // get file type


                    //allow only valid image file types 
                    switch (ftype)
                    {
                        case 'image/png':
                        case 'image/gif':
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            break;
                        default:
						$('#screenoverlay').fadeOut();
                            $("#validate_upload_screens_message").html("<b>" + ftype + "</b> Unsupported file type!");
                            return false
                    }

                    //Allowed file size is less than 3 MB (3048576)
                    if (fsize > 2048576)
                    {
						$('#screenoverlay').fadeOut();
                        $("#validate_upload_screens_message").html("Splash image size is greater than 2 MB . Please update splash image and try and again");
                        return false
                    }

                    //$('#submit-btn').hide(); //hide submit button
                    //$('#loading-img').show(); //hide submit button
                    $("#validate_upload_screens_message").html("");
                }
                else
                {
					$('#screenoverlay').fadeOut();
                    //Output error to older browsers that do not support HTML5 File API
                    $("#validate_upload_screens_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
                    return false;
                }
            }
            function bytesToSize(bytes) {
                var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                if (bytes == 0)
                    return 'n/a';
                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
            }
            ;
            // $('.make_app_next').click(function () {
				// var currentclick = $(this);
				// //createApp();
                // var sel_img_url = $('.selected_screen').html();
			// if($.trim(sel_img_url) != '' )
				// {
					// var form_data = {
						// data: sel_img_url, //your data being sent with ajax
						// token: '<?php echo $token; ?>', //used token here.
						// hasid: '<?php echo $app_id; ?>',
						// is_ajax: 2
					// };
					// $.ajax({
						// type: "POST",
						// url: 'modules/checkapp/selected_screen.php',
						// data: form_data,
						// success: function (response)
						// {
							// //console.log(response);
                                                        // $('html, body').animate({
                                                            // scrollTop: $("#splash_btn1").parents(".mid_right_box").next(".mid_right_box").offset().top - $("header.top-area").height()
                                                        // }, 2000);
							// //window.location = BASEURL + 'app-complete-retail.php?app_id=<?php echo $app_id; ?>'
						// },
						// error: function () {
							// console.log("error in ajax call");
							// //alert("error in ajax call");
						// }
					// });
				// }
				// else{
					// currentclick.prev('.continue_screens_message').html('Please Select A Splash Screen To Continue.');
				// }
            // });
		
        });
		/* Load More Starts */
	$(document).ready(function(){
		if ($('.screens img, .os_screen img').find('.selected')) {
			var sel_img_url = $('.screens img.selected, .os_screen img.selected').data('id');
			$('.selected_screen').html(sel_img_url);
		}
		$("#load_more_default_screen").on("click",function(){
			var page = parseInt($(this).attr('data-page'));
			var form_data = {
				token: '<?php echo $token; ?>', //used token here.
				hasid: '<?php echo $app_id; ?>',
				page : page,
				premium : 0
			};
			$.ajax({
				type: "POST",
				url: 'modules/checkapp/load_more_screens.php',
				data: form_data,
				success: function (response)
				{
					$(response).insertBefore('.more_default_screen');
					$("#load_more_default_screen").attr('data-page',page+1);
					if(page+1 > $("#load_more_default_screen").attr('data-total_page')){
						$("#load_more_default_screen").hide();
					}
					if ($('.screens img, .os_screen img').find('.selected')) {
						var sel_img_url = $('.screens img.selected, .os_screen img.selected').data('id');
						$('.selected_screen').html(sel_img_url);
					}
				},
				error: function () {
					console.log("error in ajax call");
				}
            });
		});
		
		$("#load_more_paid_screen").on("click",function(){
			var page = parseInt($(this).attr('data-page'));
			var form_data = {
				token: '<?php echo $token; ?>', //used token here.
				hasid: '<?php echo $app_id; ?>',
				page : page,
				premium : 1
			};
			$.ajax({
				type: "POST",
				url: 'modules/checkapp/load_more_screens.php',
				data: form_data,
				success: function (response)
				{
					$(response).insertBefore('.more_paid_screen');
					$("#load_more_paid_screen").attr('data-page',page+1);
					if(page+1 > $("#load_more_paid_screen").attr('data-total_page')){
						$("#load_more_paid_screen").hide();
					}
					if ($('.screens img, .os_screen img').find('.selected')) {
						var sel_img_url = $('.screens img.selected, .os_screen img.selected').data('id');
						$('.selected_screen').html(sel_img_url);
					}
				},
				error: function () {
					console.log("error in ajax call");
				}
            });
		});
	});
	/* Load More Ends */
    </script>
    <?php
}
else 
{   
     $screenShot='';
    $appType=1;
    require_once('modules/apphtml/apphtml.php');
    $username = $_SESSION['username'];   
    $custid = $_SESSION['custid'];  
    $save = new Saveapphtml(); 
      $pageScreen = $webscreen->app_screenshot($appID); /* 1 == premium */
	  	$published = $webscreen->get_category($appID); 
			 $isPublished=$published['published'];
    if(!empty($pageScreen))
    {
        $screenShot=$pageScreen['screenshot_url'];
         $appType=$pageScreen['type_app'];    
    }
    if ($custid != '') {        
        $author = $save->check_user_exist($username,$custid);      
        if ($appID) {           
            $showHtml = 1;
        }
    } else {
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
                    <?php
                     
                    if($appType == '1')
                    {
                        ?>
                    <div id="content-1" class="content mCustomScrollbar clear first-page " >

                        <div class="overlay">
                        </div>

                        <div class="container droparea" style="float:left;width:100%;">
                        </div>
                    </div>
                    <?php
                    }
                    else
                    {
                        ?>
                   <img src="<?php echo $screenShot;?>" />
                    
                    <?php
                    
                    }
                    ?>
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
                                <div class="clear"></div>
                            </div>
                            <div class="mid_right_box_body">
                                <div class="choose_screen">
									<?php if($isPublished == 0)
									{
									?> 
									<h2>Choose a premium splash screen for your awesome app <span style="color:#ffcc00;"><?php if($currency==1){ ?>(Price- Rs.1,500)<?php } else {?>(Price- $23)<?php }?></span></h2>
                                    <div class="screens">
                                        <?php
                                        $webscreen = new WebScreen();
                                        $screens = $webscreen->get_app_screen($appID, 1); /* 1 == premium */
										if(count($screens) > 0){
                                        $sel_screen = $webscreen->get_selected_splash_screen($_SESSION['appid']);
                                        foreach ($screens as $screen) {
                                            if ($screen['image_link'] != '') {
                                                if (trim($sel_screen) == $screen['id']) {
                                                    ?>
                                                    <img class="selected" src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'>
                                                <?php } else { ?>
                                                    <img src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'>
                                                    <?php
                                                }
                                            }
                                        }
                                        if($webscreen->get_total_app_screen($appID,1) > 8){
										?><div class="more_paid_screen"></div>
										<div class="clear"></div>
										<input type="button" data-page="1" data-total_page="<?php echo intval($webscreen->get_total_app_screen($appID,1)/8);?>" value="Load More" id="load_more_paid_screen" class="load_more">
										<?php } } ?>
                                    </div>
									<?php 
									}
else
{
	?>
															
														<div class="screens">
														<?php
														$webscreen = new WebScreen();
														$screens = $webscreen->get_app_screen($appID, 1); /* 1 == premium */
														if(count($screens) > 0){
														$sel_screen = $webscreen->get_selected_splash_screen($_SESSION['appid']);
														foreach ($screens as $screen) {
														if ($screen['image_link'] != '') {
														if (trim($sel_screen) == $screen['id']) {
														?>
														<h2 class="icon_tip" >App Splash Screen</h2>
														<img class="selected" src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'>
														<?php } 
														}
														}
														 }
														 ?>
														</div>

	<?php
}	
									?>
                                    <h2>Choose a free splash screen for your awesome app:</h2>
                                    <div class="screens">
                                        <?php
                                        $webscreen = new WebScreen();
                                        $screens = $webscreen->get_app_screen($appID);
										if(count($screens) > 0){
                                        $sel_screen = $webscreen->get_selected_splash_screen($_SESSION['appid']);
                                        foreach ($screens as $screen) {
                                            if ($screen['image_link'] != '') {
                                                if (trim($sel_screen) == $screen['id']) {
                                                    ?>
                                                    <img class="selected" src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'>
                                                <?php } else { ?>
                                                    <img src="<?php echo $screen['image_link']; ?>" defaultSplash="0" data-id="<?php echo $screen['id']; ?>" width='158'>
                                                    <?php
                                                }
                                            }
                                        }
                                        if($webscreen->get_total_app_screen($appID) > 8){
										?><div class="more_default_screen"></div>
										<div class="clear"></div>
										<input type="button" data-page="1" data-total_page="<?php echo intval($webscreen->get_total_app_screen($appID)/8);?>" value="Load More" id="load_more_default_screen" class="load_more">
										<?php } } ?>
                                    </div>
                                    
                                </div>
                                <div class="choose_icon">
                                    <?php
                                    if (isset($_SESSION['appid']) && $_SESSION['appid'] != '') {
                                        $self_screens = $webscreen->get_self_screens($_SESSION['appid']);
                                     	$sel_screen = $webscreen->get_selected_splash_screen($_SESSION['appid']);
										$set_size = $webscreen->set_selected_splash_screen($sel_screen);
										$exploded = explode('X',$set_size);
										$get_size = substr($exploded[0],-4);										
                                    }
                                    ?>


                                    <form name='upload_screen' id='upload_screen' method='post' action='' enctype="multipart/form-data">
                                        <h2>Or, you can even upload your very own splash screen for your app.</h2>
                                        <p>We suggest that your splash screen image should not be more than <span>2 MB</span> in size, and <span>1080 x 1920 pixels</span> for any device.</p>
                                        <div class="os_screen">
                                            <!-- <h2>iPhone</h2> -->
                                            <?php
                                            if (count($self_screens) > 0) {

                                                //$ios_size = array(320,768,640,750);
                                                $ios_size = array($get_size);
                                               
                                                foreach ($self_screens as $self_screen) {
                                                    $img_ext = explode('X',$self_screen['name']);
                                                    //$size = end(explode('X',$img_ext[0]));
                                                    // $size = substr($img_ext[0],14);
													// $size = explode('X',$img_ext[0]);
													// $size = explode('x',$size[0]);
													// $size = explode('_',$size[1]);
													// $size = $size[1];
													 $size = substr($img_ext[0],-4);
                                                    if (in_array($size, $ios_size)) {
                                                        ?>
                                                        <div class="screen_size splashLarge">
                                                            <?php if (trim($sel_screen) == $self_screen['id']) { ?>
                                                                <img <?php echo $sel_screen.'--'.$self_screen['id'];?>  class="selected uploaded" src="<?php echo $self_screen['image_link']; ?>" defaultSplash="1" data-id="<?php echo $self_screen['id']; ?>" data-size="1242*2208">
                                                            <?php } else { ?>
                                                                <img  class="uploaded" src="<?php echo $self_screen['image_link']; ?>" defaultSplash="1" data-id="<?php echo $self_screen['id']; ?>" data-size="1242*2208">
                                                            <?php } ?>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <?php
                                                    }
                                                    else{?>
                                                      <div class="screen_size splashMedium">
                                                    <img name="down" src="<?php echo $self_screen['image_link']; ?>" defaultSplash="1" data-id="<?php echo $self_screen['id']; ?>" data-size="1080*1920">
                                                    <div class="clear"></div>
                                                </div>   

                                                    <?php
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <div class="screen_size splashMedium">
                                                    <img src="images/iphone_screen_2.jpg" data-size="640*960">
                                                    <div class="clear"></div>
                                                </div>
                                            <?php } ?>
                                            <div class="clear"></div>
                                        </div>
                                        <!-- <div class="os_screen">
                                            <h2>Android</h2>
                                        <?php /* if(count($self_screens) > 0){
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
                                          <?php } */ ?>
                                            <div class="clear"></div>
                                        </div> -->
                                        <!-- <div class="os_screen">
                                            <h2>Tab</h2>
                                        <?php /* if(count($self_screens) > 0){
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
                                          <?php } */ ?>
                                            <div class="clear"></div>
                                        </div> -->
                                        <div class="clear"></div>
                                        <input type='file' name='screens' style='display:none;'>
                                        <input type="hidden" name='appid' value='<?php print_r($_SESSION['appid']); ?>'>
                                        <div class="selected_screen" style="display:none;"><?php if($sel_screen != '' && $sel_screen > 1){ echo $sel_screen; }?></div>
                                        <div id='validate_upload_screens_message'></div>
                                    </form>
                                </div>
                                <a href="javascript:void(0);" id="splash_btn1" class="make_app_next">Save & Continue</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                          <div class="mid_right_box">
                        <div class="mid_right_box_head">
                            <h1>Share Text via Facebook- We need your Facebook APP ID.</h1>
                            <h2>To create a Facebook Application ID:<br/>Go to the Facebook Developers Apps page, and sign in with your Facebook username and password.<br/>Click the "Create New App" button.<br>Enter a name for the application in the "App Name" field.<br>Read the Facebook Platform Policies and decide if you accept them.</h2>
                            <a class="read_more">Read More...</a>
                            <div class="clear"></div>

                        </div>
                        <div class="mid_right_box_body">
                            <div class="design_menu_box social_sharing">

                                <div class="content_label">
                                    <label>Facebook App Id</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="facebook_id_text" maxlength="20" value="<?php echo isset($app_details['fbsdk_id'])?$app_details['fbsdk_id']:'';?>">
                                    <em>Facebook APP ID is required for sharing text on Facebook via Mobile Apps<a href="<?php echo $basicUrl ?>docs/Facebook_App_ID_Tutorial.pdf" target="_blank">Learn more...</a></em>
                                </div>
                                <!--<div class="content_label">
                                    <label>Twitter App Id</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="facebook_id_text" maxlength="25">
                                    <em>Twitter APP ID is required for sharing text on Twitter via Mobile Apps<a href="#" target="_blank">Learn more...</a></em>
                                </div>-->
                                <div class="advertising_notification">
                                    <h2>Advertisements</h2>
                                    <p>Advertisements are a great way to earn from your app. By 'ticking' the check box, you will authorize third-party to display their apps on your app, which will be an add-on revenue generator for you. All you have to do is fill in the relevant fields with required information, and voila! You are all set to go.</p>
                                    <a class="read_more">Read more...</a>
                                    <div class="clear"></div>
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
                                            <select id="adserver">
                                              <!--  <option>Flury</option> -->
                                                 <?php 
											   $save = new Saveapphtml();       
												$addata = $save->checkAdData();  
												foreach($addata as $adDetails)
												{
												?>
												<option value="<?php echo $adDetails['id'];?>"><?php echo$adDetails['servername'];?></option>
												<?php 
												} 
												?>
                                            </select>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                   
                                    <div class="advertising_notification_list">
                                        <div class="advertising_notification_left">
                                            <label>Ad Unit Code</label>
                                        </div>
                                        <div class="advertising_notification_right">
                                            <input type="text" name="Android" id="Android">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <!--<div class="advertising_notification_list">
                                        <div class="advertising_notification_left">
                                            <label>MobileWeb Unit Code</label>
                                        </div>
                                        <div class="advertising_notification_right">
                                            <input type="text">
                                        </div>
                                        <div class="clear"></div>
                                    </div>-->
                                </div>

                            </div>
                             <a href="javascript:void(0);" class="make_app_next" id="save_fbapp">Save & Continue</a>
                            <div class="clear"></div>

                        </div>
                        </div>

                        <!--<<div class="mid_right_box">
                            <div class="mid_right_box_head">
                                h1>Advertisements & Push Notifications</h1>
                                <h1>Advertisements</h1>
                                <h2>Advertisements are a great way to earn from your app. By ‘ticking’ the check box, you will authorize third-party to display their apps on your app, which will be an add-on revenue generator for you. All you have to do is fill in the relevant fields with required information, and voila! You are all set to go.</h2>
                                <a class="read_more">Read More...</a>
                                <div class="clear"></div>
                            </div>
                            <div class="mid_right_box_body">
                                
                                
                                <div class="push_notification">
                                        <h2>Push Notification</h2>
                                    <p>Push Notifications are a great way to update your app users updated with the hottest deals and offers you are running with. When push a notification, it lands on your app users’ cell phone in the Notifications section.</p>
                                        <a class="read_more">Read More...</a>
                                    <div class="clear"></div>
                                    <input type="checkbox" id="notification">
                                    <label for="notification">Enable Push notification (cost)</label>
                                    <div class="clear"></div>
                                    </div>
                                <a href="javascript:void(0);" class="ad" id="save_add">Save & Continue</a>
                                <div class="clear"></div>
                            </div>
                        </div>-->
                      


                    </div>
                   
                    <div class="clear"></div>
                </div>
            </div>


          <!-- <input type="file" id="uploadImgFile"> -->
            <div class="testContainer">

                <img src="" id="testImg">

            </div>
            <div class="hint_main"><img src="<?php echo $basicUrl; ?>images/ajax-loader.gif"></div>
        </section>
    </section>
    </section>
    <script type="text/javascript" src="js/colpick.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.shapeshift.js" type="text/javascript"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
    <script src="js/chosen.jquery.js"></script>
    <script src="js/ImageSelect.jquery.js"></script>
    <script type="text/javascript" src="js/jquery.form.min.js"></script>
    <script type="text/javascript" src="js/publishJS.js"></script>
    <script type='text/javascript'>
        $(document).ready(function () {
			var curruntURL = window.location;
            $('#upload_screen .screen_size img').click(function () {
				
				//alert(curruntURL+'down');
                $('#upload_screen input[type=file]').trigger("click");
            });
			if ($('.screens img, .os_screen img').find('.selected')) {
                var sel_img_url = $('.screens img.selected, .os_screen img.selected').data('id');
                $('.selected_screen').html(sel_img_url);
            }
            $('.screens, .os_screen').on('click', 'img', function () {
                $('.screens img, .os_screen img').removeClass('selected');
                var sel_img_url = $(this).data('id');
                $(this).addClass('selected');
                $('.selected_screen').html(sel_img_url);
            });
            var options = {
                target: '#validate_upload_screens_message', // target element(s) to be updated with server response 
                url: 'API/index.php/uploadappscreens',
                beforeSubmit: beforeSubmit, // pre-submit callback 
                //success: afterSuccess, // post-submit callback 
                resetForm: true,
				complete:afterSuccess// reset the form after successful submit 
            };
            $('#upload_screen input[type=file]').change(function () {
                $('#validate_upload_screens_message').empty();
                var file = this.files[0];
                var imagefile = file.type;
                var match = ["image/jpeg", "image/png", "image/jpg"];
                //var imgtest = document.getElementById("testImg");
                //var imgtest = $('#upload_screen img');
                //var testSize = imgtest.getBoundingClientRect();

                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
                {
					$('#screenoverlay').fadeOut();
                    //$('#previewing').attr('src','noimage.png');
                    $("#validate_upload_screens_message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                    return false;
                }
                else
                {
                    var reader = new FileReader();
                    reader.onload = function (e) {

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
				//alert(JSON.stringify(response));
				//alert("success"+response);
                $('#screenoverlay').fadeOut();
                $('#validate_upload_screens_message').html(response.responseText); //hide submit button
                if (response.responseText == 'Screens Uploaded Successfully') {
                    window.location.href = curruntURL;
                }
            }
            //function to check file size before uploading.
            function beforeSubmit() {
                //check whether browser fully supports all File API
                if (window.File && window.FileReader && window.FileList && window.Blob)
                {

                    if (!$('#upload_screen input[type=file]').val()) //check empty input filed
                    {
						$('#screenoverlay').fadeOut();
                        $("#validate_upload_screens_message").html("Are you kidding me?");
                        return false
                    }

                    var fsize = $('#upload_screen input[type=file]')[0].files[0].size; //get file size
                    var ftype = $('#upload_screen input[type=file]')[0].files[0].type; // get file type


                    //allow only valid image file types 
                    switch (ftype)
                    {
                        case 'image/png':
                        case 'image/gif':
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            break;
                        default:
						$('#screenoverlay').fadeOut();
                            $("#validate_upload_screens_message").html("<b>" + ftype + "</b> Unsupported file type!");
                            return false
                    }

                    //Allowed file size is less than 3 MB (3048576)
                    if (fsize > 2048576)
                    {
						$('#screenoverlay').fadeOut();
                        $("#validate_upload_screens_message").html("Splash image size is greater than 2 MB . Please update splash image and try and again");
                        return false
                    }

                    //$('#submit-btn').hide(); //hide submit button
                    //$('#loading-img').show(); //hide submit button
                    $("#validate_upload_screens_message").html("");
                }
                else
                {
					$('#screenoverlay').fadeOut();
                    //Output error to older browsers that do not support HTML5 File API
                    $("#validate_upload_screens_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
                    return false;
                }
            }
            function bytesToSize(bytes) {
                var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                if (bytes == 0)
                    return 'n/a';
                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
            }
	
			
			
			
			
            
     
			    function createApp() {
            $.ajax({
                type: "POST",
                url: BASEURL+'API/htmlparser.php/getData',
                data: "app_id=" + "<?php echo $appID ?>" + "&themeId=1"+"&app_type=1",
         
            });

        }
			
        });
    </script>
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
        <script>
            $(document).ready(function () {
                var showHtml = '<?php echo $showHtml; ?>';
                if (showHtml == 1) {
                    var param = '';
                    var form_data = {
                        data: param, //your data being sent with ajax
                        token: '<?php echo $token; ?>', //used token here.
                        hasid: '<?php echo $appID; ?>',
                        is_ajax: 2
                    };
                    $.ajax({
                        type: "POST",
                        url: 'modules/checkapp/pagehtml.php',
                        data: form_data,
                        success: function (response)
                        {
                            //                        alert(response);
                            if (response) {

                                obj = JSON.parse(response);
                                storedPages = obj.storedPages;
                                navbar = obj.navbar;

                                pageDetails = obj.pageDetails;

                                if (storedPages[0].banner == "undefined" || storedPages[0].banner == undefined || storedPages[0].banner == "")
                                {

                                    //                            alert('banner check');
                                    $(navbar).insertBefore(".overlay");
                                    $(".container.droparea").html(storedPages[0].contentarea);


                                }
                                else
                                {
                                    $("<div class='banner'>" + storedPages[0].banner + "</div>").insertAfter(".overlay");
                                    $(navbar).insertBefore(".overlay");
                                    $(".container.droparea").html(storedPages[0].contentarea);

                                }
                                $(".theme_head h1:first").text(pageDetails[0].name);
                               $(".droparea.container").css("background-color", $(".mobile .theme_head").attr("data-appbackground"));

                                //firstUpdateSlideNames();
        //                            updateSlideNames();
                                $(".container.droparea").shapeshift({
                                    colWidth: 134.5,
                                    minColumns: 2,
									enableDrag: false
                                });
                                //                            console.log(storedPages[0]);

                            } else if (response == 1) {
                                console.log(response);
                            }
                        },
                        error: function () {
                            console.log("error in ajax call");
                            // alert("error in ajax call");
                        }
                    });
                }
                $('ul.tabs>li').eq(0).click();
                $(".mobile nav").css("background-color",$(".theme_head").eq(1).css("background-color"))
            });
			/* Load More Starts */
			$(document).ready(function(){
				$('#screenoverlay').css('display','block');
				if ($('.screens img, .os_screen img').find('.selected')) {
					var sel_img_url = $('.screens img.selected, .os_screen img.selected').data('id');
					$('.selected_screen').html(sel_img_url);
				}
				$("#load_more_default_screen").on("click",function(){
					var page = parseInt($(this).attr('data-page'));
					var form_data = {
						token: '<?php echo $token; ?>', //used token here.
						hasid: '<?php echo $appID; ?>',
						page : page,
						premium : 0
					};
					$.ajax({
						type: "POST",
						url: 'modules/checkapp/load_more_screens.php',
						data: form_data,
						success: function (response)
						{
							$(response).insertBefore('.more_default_screen');
							$("#load_more_default_screen").attr('data-page',page+1);
							if(page+1 > $("#load_more_default_screen").attr('data-total_page')){
								$("#load_more_default_screen").hide();
							}
							if ($('.screens img, .os_screen img').find('.selected')) {
								var sel_img_url = $('.screens img.selected, .os_screen img.selected').data('id');
								$('.selected_screen').html(sel_img_url);
							}
						},
						error: function () {
							console.log("error in ajax call");
						}
					});
				});
				
				$("#load_more_paid_screen").on("click",function(){
					var page = parseInt($(this).attr('data-page'));
					var form_data = {
						token: '<?php echo $token; ?>', //used token here.
						hasid: '<?php echo $appID; ?>',
						page : page,
						premium : 1
					};
					$.ajax({
						type: "POST",
						url: 'modules/checkapp/load_more_screens.php',
						data: form_data,
						success: function (response)
						{
							$(response).insertBefore('.more_paid_screen');
							$("#load_more_paid_screen").attr('data-page',page+1);
							if(page+1 > $("#load_more_paid_screen").attr('data-total_page')){
								$("#load_more_paid_screen").hide();
							}
							if ($('.screens img, .os_screen img').find('.selected')) {
								var sel_img_url = $('.screens img.selected, .os_screen img.selected').data('id');
								$('.selected_screen').html(sel_img_url);
							}
						},
						error: function () {
							console.log("error in ajax call");
						}
					});
				});

			});
			/* Load More Ends */
			$(window).load(function(){
				$('#screenoverlay').fadeOut();
			})

        </script>
    <?php } ?>
    </body>
    </html>
<?php
 }
 ?>
 <script>
$(document).ready(function(){
	$("#save_fbapp").click(function(){
		var fb_app_id=$(".facebook_id_text").val();
		$("#splash_btn1").trigger("click");
		var sel_img_url = $('.selected_screen').html();
		if(fb_app_id!='')
			{
			if(fb_app_id>0)
					{
						if(fb_app_id.length<15)
						{						
						$(".popup_container").css("display", "block");
						$(".confirm_name").css("display", "block");
						 $(".confirm_name_form p").text("Please provide valid Facebook Application ID");
						 return false;
						}
					else{
								$("#screenoverlay").css("display", "block");
								$.ajax({
												type: "POST",
												url: 'ajax.php',
												data: "fb_app_id="+fb_app_id+"&app_id="+'<?php echo $appID; ?>'+"&type=save_fb_app_id",
												success: function (response)
												{
													if (response == 'success') {  
																				$('html, body').animate({
																					scrollTop: $("#save_fbapp").parents(".mid_right_box").next(".mid_right_box").offset().top - $("header.top-area").height()
																				}, 2000);
												$("#screenoverlay").css("display", "none");
												$(".popup_container").css("display", "block");
												$(".confirm_name").css("display", "block");
												$(".confirm_name_form p").text("Facebook ID saved successfully.");
											}                
										else {
											$("#screenoverlay").css("display", "none");
											$(".popup_container").css("display", "block");
											$(".confirm_name").css("display", "block");
											 $(".confirm_name_form p").text("OOps something went wrong.Try again later");
										}
												},
												error: function () {
													console.log("error in ajax call");
												}
											});
						}
					}
				else{
						
						$(".popup_container").css("display", "block");
						$(".confirm_name").css("display", "block");
						 $(".confirm_name_form p").text("Please provide valid Facebook Application ID.");
						 return false;
					}
			}

		if($.trim(sel_img_url) != '' )
		{
		var catalogue_app_id='<?php echo $app_id; ?>';
			var customer=<?php echo $custid ?>;
			var app_id='<?php echo $appID ?>';
			if($("#enable").prop("checked") == true){
			var adserverCode=document.getElementById('adserver').value;
			var AndroidCode=document.getElementById('Android').value;
			 var remember = document.getElementById('enable');
			 var checked ='0';
    if (remember.checked){
        checked ='1';
    }
			if(AndroidCode == '')
			{
							$(".cropcancel").trigger("click");
                            $(".confirm_name .close_popup").css({'display': 'none'});
                            $(".confirm_name .confirm_name_form").html('<p>Please provide ad unit to enable advertisements.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(".close_popup").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('').hide();
			}
			else{
				 	$.ajax({
						type: "POST",
						url: BASEURL+'API/ad.php/myads',
						data:'customer='+customer+'&app_id='+app_id+'&adserverCode='+adserverCode+'&AndroidCode='+AndroidCode+'&checked='+checked,
						success: function (response)
						{
							$("#page_ajax").html('').hide();
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form").html('<p>Your data has been updated successfully.</p><input type="button" value="OK">');
												if(catalogue_app_id>0){
							window.location = BASEURL + 'app-complete-retail.php?app_id='+catalogue_app_id;
						}
						else{
							window.location = BASEURL + 'app-complete.php';
						}
						},
						error: function () {
							$("#page_ajax").html('').hide();
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form").html('<p>Oops!! Something went wrong.</p><input type="button" value="OK">');
                             }
					}); 
				}
			 }
			 else{
				 setTimeout(function(){ if(catalogue_app_id>0){
							window.location = BASEURL + 'app-complete-retail.php?app_id='+catalogue_app_id;
						}
						else{
							window.location = BASEURL + 'app-complete.php';
						}  }, 1000);
				
			 }
			 
				}
		
		
	});
	       $('#splash_btn1').click(function () {
				var sel_img_url = $('.selected_screen').html();
                                var defaultvalue=$(".selected").attr("defaultSplash");
				if($.trim(sel_img_url) != '' )
				{
					
					var form_data = {
						data: sel_img_url, //your data being sent with ajax
						token: '<?php echo $token; ?>', //used token here.
						hasid: '<?php echo $appID; ?>',
						is_ajax: 2,
                                                defaultvalue:defaultvalue
					};
                                        
					$.ajax({
						type: "POST",
						url: 'modules/checkapp/selected_screen.php',
						data: form_data,
						success: function (response)
						{
							
                                                        $('html, body').animate({
                                                            scrollTop: $("#splash_btn1").parents(".mid_right_box").next(".mid_right_box").offset().top - $("header.top-area").height()
                                                        }, 2000);
							//window.location = BASEURL + 'app-complete.php';
						},
						error: function () {
							console.log("error in ajax call");
							//alert("error in ajax call");
						}
					});
				}
				else
				{
					
					$('#validate_upload_screens_message').text('Please Select A Splash Screen To Continue.');
					$('html, body').animate({
						scrollTop: $("#splash_btn1").siblings(".choose_icon").children("#upload_screen").offset().top - $("header.top-area").height()
					}, 2000);
					return false;
					
				}
            });
	// $('.ad').click(function () {
		// $(".make_app_next").trigger("click");
		// var sel_img_url = $('.selected_screen').html();
		

		// if($.trim(sel_img_url) != '' )
		// {
		// var catalogue_app_id='<?php echo $app_id; ?>';
			// var customer=<?php echo $custid ?>;
			// var app_id='<?php echo $appID ?>';
			// if($("#enable").prop("checked") == true){
			// var adserverCode=document.getElementById('adserver').value;
			// var AndroidCode=document.getElementById('Android').value;
			 // var remember = document.getElementById('enable');
			 // var checked ='0';
    // if (remember.checked){
        // checked ='1';
    // }
			// if(AndroidCode == '')
			// {
							// $(".cropcancel").trigger("click");
                            // $(".confirm_name .close_popup").css({'display': 'none'});
                            // $(".confirm_name .confirm_name_form").html('<p>Please provide ad unit to enable advertisements.</p><input type="button" value="OK">');
                            // $(".confirm_name").css({'display': 'block'});
                            // $(".close_popup").css({'display': 'block'});
                            // $(".popup_container").css({'display': 'block'});
                            // $("#page_ajax").html('').hide();
			// }
			// else{
				 	// $.ajax({
						// type: "POST",
						// url: BASEURL+'API/ad.php/myads',
						// data:'customer='+customer+'&app_id='+app_id+'&adserverCode='+adserverCode+'&AndroidCode='+AndroidCode+'&checked='+checked,
						// success: function (response)
						// {
							// $("#page_ajax").html('').hide();
                        // $(".popup_container").css("display", "block");
                        // $(".confirm_name").css("display", "block");
                        // $(".confirm_name .confirm_name_form").html('<p>Your data has been updated successfully.</p><input type="button" value="OK">');
												// if(catalogue_app_id>0){
							// window.location = BASEURL + 'app-complete-retail.php?app_id='+catalogue_app_id;
						// }
						// else{
							// window.location = BASEURL + 'app-complete.php';
						// }
						// },
						// error: function () {
							// $("#page_ajax").html('').hide();
                        // $(".popup_container").css("display", "block");
                        // $(".confirm_name").css("display", "block");
                        // $(".confirm_name .confirm_name_form").html('<p>Oops!! Something went wrong.</p><input type="button" value="OK">');
                             // }
					// }); 
				// }
			 // }
			 // else{
				 // setTimeout(function(){ if(catalogue_app_id>0){
							// window.location = BASEURL + 'app-complete-retail.php?app_id='+catalogue_app_id;
						// }
						// else{
							// window.location = BASEURL + 'app-complete.php';
						// }  }, 2000);
				
			 // }
			 
				// }
				// else{
					// $('html, body').animate({
                                                            // scrollTop: $(".screen_size.splashMedium").offset().top + $("header.top-area").height()
                                                        // }, 2000);
				// }
            // });
	

	
});
	</script>