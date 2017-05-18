<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/myapp/myapps.php');
require_once('modules/myapp/save_imgs.php');
require_once('modules/checkapp/app_screen.php');
$apps = new MyApps();
$apps_img = new WebScreen();
$save_img= new Save_images();
$app_data = $apps->app_name($_SESSION['appid']);
$dataAndroid = $apps->getAndroidDetails($_SESSION['appid']);
$sel_app_icon = $apps_img->get_selected_app_icon($_SESSION['appid']);
?>
<section class="main">
    <section class="right_main">
        <div class="right_inner">
            <div class="how_publish">
                <div class="how_publish_left">
                    <img src="images/Android-App-Publish.png"><h1>Android App Publish :</h1>
                    
                </div>
                <div class="clear"></div>
                <div class="how_publish_body">
                    <div class="how_publish_body_left">
                        <div class="publish_content">
                            <h2>Product Details</h2>
                            <form action='' method='post' name='android_app_publish' id='android_app_publish' enctype="multipart/form-data">
                                <div class="publish_content_form">
                                    <div class="publish_content_label">
                                        <label>Title :</label>
                                    </div>
                                    <div class="publish_content_textbox">
                                        <input type="text"  maxlength="50" name='app_name' value='<?php echo isset($app_data['summary']) ? $app_data['summary'] : '' ?>' placeholder="Name of app appear in app store">
                                    </div>
                                    <div class="publish_content_label">
                                        <label>Short<br/> Description :</label>
                                    </div>
                                    <div class="publish_content_textbox">
                                        <textarea   maxlength="80" placeholder="Limit 0-80 characters" name='app_short_desc'><?php echo isset($dataAndroid['short_description']) ? $dataAndroid['short_description'] : '' ?></textarea>
                                    </div>
                                    <div class="publish_content_label">
                                        <label>Full<br/> Description :</label>
                                    </div>
                                    <div class="publish_content_textbox">
                                        <textarea class="disc " minlength="10" maxlength="4000" name='app_full_desc'  placeholder="Limit 10-4000 characters"><?php echo isset($dataAndroid['full_description']) ? $dataAndroid['full_description'] : '' ?></textarea>
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
                                        If you haven't added localized graphics for each language, graphics for your default language will be used. <a href="https://support.google.com/googleplay/android-developer/answer/1078870"> Learn more about graphic assets</a>.
                                    </p>
                                    <span class="graphic_heading">Screenshots </span>
                                    <p>Default – English (United States) – en-US<br/>
                                        JPEG or 24-bit PNG (no alpha). Min length for any side: 320px. Max length for any side: 3840px.
                                        At least 2 screenshots are required overall. Max 8 screenshots per type. Drag to reorder or to move between types.
                                    </p>
                                    <p>
                                        For your app to be showcased in the 'Designed for tablets' list in the Play Store, you need to upload at least one 7-inch and one 10-inch screenshot. If you previously uploaded screenshots, make sure to move them into the right area below.<br/><a href="https://support.google.com/googleplay/android-developer/answer/1078870">Learn how tablet screenshots will be displayed in the store listing.</a>
                                    </p>
                                </div>
                                <div class="publish_content_screenshot">
                                    <div class="publish_content_phone">
                                        <span>Phone</span>
									<?php $phones= $save_img->get_phone_links($_SESSION['appid'],'phone_link'); ?>
                                        <div class="img_box phone_image">
                                            <a href="javascript:void(0);" class="upload_screen"><img data-imageid="phone_1" src="
											<?php											
											if($phones[0]!=''){												
											echo $phones[0];	
											}
											else{												
											echo 'images/browse_nw.png';	
											}											
											?>
											" alt=""/></a>	
                                        </div>
                                        <div class="img_box phone_image">
                                            <a href="javascript:void(0);" class="upload_screen"><img data-imageid="phone_2" src="
											<?php											
											if($phones[1]!=''){												
											echo $phones[1];	
											}
											else{												
											echo 'images/browse_nw.png';	
											}											
											?>" alt=""/></a>    
                                        </div>
                                        <div class="img_box phone_image">
                                            <a href="javascript:void(0);" class="upload_screen"><img data-imageid="phone_3" src="
											<?php											
											if($phones[2]!=''){												
											echo $phones[2];	
											}
											else{												
											echo 'images/browse_nw.png';	
											}											
											?>" alt=""/></a>    
                                        </div>
                                        <input type="file" name="phone_screen[]"  class="upload_screen_file" style="display:none;">
                                        <?php
                                        // $phonelink = isset($dataAndroid['phone_link']) ? $dataAndroid['phone_link'] : '';
                                        // if ($phonelink != '') {
                                            // $myArray = explode(',', $phonelink);
                                            // $myArrayCount = count($myArray);
                                            // for ($i = 0; $i < $myArrayCount - 1; $i++) {

                                                // $linkvalue = $myArray[$i];
                                                    // $imagedata = file_get_contents("$linkvalue");
                                                    // // alternatively specify an URL, if PHP settings allow
                                                    // $base64 = base64_encode($imagedata);
                                                ?>
                                        <!--<img src="data:image/png;base64,<?php //echo $base64;?>" alt="" width="100" height="100">-->
                                                
                                                <?php
                                            //}
                                        //}
                                        ?>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="screen_message"></div>
                                    <div class="publish_content_phone">
                                        <span>7-inch tablet</span>
										<?php $teblets= $save_img->get_phone_links($_SESSION['appid'],'tablet_link'); ?>
                                        <div class="img_box tablet_image">
                                            <a href="javascript:void(0);" class="tablet_screen"> <img data-imageid="tablet_1" src="							
											<?php											
											if($teblets[0]!=''){												
											echo $teblets[0];	
											}
											else{												
											echo 'images/browse_nw.png';	
											}											
											?>" alt=""/> </a>		
                                        </div>
                                        <div class="img_box tablet_image">
                                            <a href="javascript:void(0);" class="tablet_screen"> <img data-imageid="tablet_2" src="
											<?php											
											if($teblets[1]!=''){												
											echo $teblets[1];	
											}
											else{												
											echo 'images/browse_nw.png';	
											}											
											?>" alt=""/> </a>      
                                        </div>
                                        <div class="img_box tablet_image">
                                            <a href="javascript:void(0);" class="tablet_screen"> <img data-imageid="tablet_3" src="<?php
											if($teblets[2]!=''){												
											echo $teblets[2];	
											}
											else{												
											echo 'images/browse_nw.png';	
											}											
											?>" alt=""/> </a>      
                                        </div>
                                        <input type="file"  name="tablet_screen[]" class="upload_tablet_screen_file" style="display:none;">
                                        
                                        <div class="clear"></div>
                                    </div>
                                    <div class="tablet_screen_message"></div>
                                    <div class="publish_content_phone">
                                        <span>10-inch tablet</span>
											<?php $large_teblets= $save_img->get_phone_links($_SESSION['appid'],'large_tablet_link'); ?>
                                        <div class="img_box ten_tablet_image">
                                            <a href="javascript:void(0);" class="ten_tablet_screen"> <img data-imageid="bigtablet_1" src="<?php											
											if($large_teblets[0]!=''){												
											echo $large_teblets[0];	
											}
											else{												
											echo 'images/browse_nw.png';	
											}											
											?>" alt=""/> </a>	
                                        </div>
                                         <div class="img_box ten_tablet_image">
                                            <a href="javascript:void(0);" class="ten_tablet_screen"> <img data-imageid="bigtablet_2" src="<?php											
											if($large_teblets[1]!=''){												
											echo $large_teblets[1];	
											}
											else{												
											echo 'images/browse_nw.png';	
											}											
											?>" alt=""/> </a>  
                                        </div>
                                         <div class="img_box ten_tablet_image">
                                            <a href="javascript:void(0);" class="ten_tablet_screen"> <img data-imageid="bigtablet_3" src="<?php											
											if($large_teblets[2]!=''){												
											echo $large_teblets[2];	
											}
											else{												
											echo 'images/browse_nw.png';	
											}											
											?>" alt=""/> </a>  
                                        </div>
                                        <input type="file"  name="ten_tablet_screen[]" class="upload_ten_tablet_screen_file" style="display:none;">
                                       
                                        <div class="clear"></div>
                                    </div>
                                    <div class="ten_tablet_screen_message"></div>

                                </div>
                                <div class="publish_content_icon">
                                    <div class="hi-res-icon">
                                        <p>Hi-res Icon*<br/>
                                            <span>Default - English (United Status) - en-US</span><br/>
                                            512 X 512<br/>
                                            32-bit PNG <span>(with alpha)</span>
                                        </p>
                                        <div class="img_box">
                                            <a href="javascript:void(0);"> <img src="<?php echo isset($sel_app_icon) ? $sel_app_icon : '' ?>" alt="" width="170"/> </a>		
                                        </div>
                                    </div>
                                    <div class="hi-res-icon feature-icon">
                                        <p>Feature Graphic*<br/>
                                            <span>Default - English (United Status) - en-US</span><br/>
                                            1024 w X 500 h<br/>
                                            JPG <span>or</span> 24-bit PNG<span> (no alpha)</span>
                                        </p>
                                        <div class="img_box featured_graphic">
										<?php $featured_img= $save_img->fetch_icons($_SESSION['appid'],'featured_banner_link'); ?>
                                            <a href="javascript:void(0);" class="featured_graphic_image"> <img src="
											<?php 
											if($featured_img){
												
												echo $featured_img;
											}
											else{
												echo "images/browse_nw.png";
											}
											?>" alt=""/> </a>	
                                        </div>
                                        <input type="file" name="featured_graphic_image[]" class="upload_featured_graphic_file" style="display:none;">
                                    </div>
                                    <div class="featured_graphic_message"></div>
                                    <div class="hi-res-icon prmo-icon">
									<?php $promo_img= $save_img->fetch_icons($_SESSION['appid'],'promo_banner_link'); ?>
                                        <p>Promo Graphic<br/>
                                            <span>Default - English (United Status) - en-US</span><br/>
                                            180 w X 120 h<br/>
                                            JPG <span>or</span> 24-bit PNG<span> (no alpha)</span>
                                        </p>
                                        <div class="img_box promo_graphic">
                                            <a href="javascript:void(0);" class="promo_graphic_image"> <img src="<?php 
											if($promo_img){
												
												echo $promo_img;
											}
											else{
												echo "images/browse_nw.png";
											}
											?>" alt=""/> </a>	
                                        </div>
                                        <input type="file" name="promo_graphic_image[]" class="upload_promo_graphic_file" style="display:none;">
                                    </div>
                                    <div class="promo_graphic_message"></div>
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
                                        <input type="text" name='app_promo_url' id='app_promo_url' class=" url" value='<?php echo isset($dataAndroid['promo_video_link']) ? $dataAndroid['promo_video_link'] : '' ?>'>
                                    </div>
                                    <div class="clear"></div>
                                </div>


                                <div class="publish_content_form">
                                    <p class="cate-heading">CATEGORIZATION</p>
                                    <div class="publish_content_label">
                                        <label>Application<br/>type:</label>
                                    </div>
                                    <div class="publish_content_textbox">
                                        <select name='app_type' id='app_type' >
                                            <option value="">Select an application type</option>
                                            <option value="applications" selected >Applications</option>
                                        </select>
                                    </div>
                                    <div class="publish_content_label">
                                        <label>Category :</label>
                                    </div>

                                    <div class="publish_content_textbox">
                                        <select name='app_category' id='app_category' >
                                            <option value='<?php echo isset($dataAndroid['googleplay_category']) ? $dataAndroid['googleplay_category'] : '' ?>'><?php echo isset($dataAndroid['googleplay_category']) ? $dataAndroid['googleplay_category'] : 'Select a category' ?></option>
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
                                        <select name='app_contant_rating' id='app_contant_rating' >
                                            <option value='<?php echo isset($dataAndroid['contentrating']) ? $dataAndroid['contentrating'] : '' ?>'><?php echo isset($dataAndroid['contentrating']) ? $dataAndroid['contentrating'] : 'Select a category' ?></option>
                                            <option value="MATURE">High Maturity</option>
                                            <option value="TEEN">Medium Maturity</option>
                                            <option value="PRE_TEEN">Low Maturity</option>
                                            <option value="SUITABLE_FOR_ALL">Everyone</option>
                                            <option value="NONE">Select a content rating</option>
                                        </select>
                                    </div>

                                    <div class="publish_content_info">
                                        <p>
                                            <a href="https://support.google.com/googleplay/android-developer/answer/188189">Learn more about content rating.</a>
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
                                        <input type="text" class=" url" maxlength="50" name="app_website" value='<?php echo isset($dataAndroid['website_link']) ? $dataAndroid['website_link'] : '' ?>' placeholder="http://www.abc.com">
                                    </div>
                                    <div class="publish_content_label">
                                        <label>Email:</label>
                                    </div>
                                    <div class="publish_content_textbox">
                                        <input type="text" class=" email" maxlength="50" name="app_email" value='<?php echo isset($dataAndroid['email_address']) ? $dataAndroid['email_address'] : '' ?>' placeholder="abc@xyz.com">
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
                                        <input type="text"  minlength="10" maxlength="15" name="app_phone" value='<?php echo isset($dataAndroid['phone_no']) ? $dataAndroid['phone_no'] : '' ?>' onKeyPress="return isNumber(event)" placeholder="9876543210">
                                    </div>

                                </div>








                                <div class="publish_content_form">
                                    <p class="cate-heading">PRIVACY POLICY</p>
                                    <span>If you wish to provide a privacy policy URL for this application, please enter it below.</span>	
                                    <div class="publish_content_label">
                                        <label>Privacy Policy:</label>
                                    </div>
                                    <div class="publish_content_textbox">
                                        <input type="text" class=" url" maxlength="50" name="app_privacy_url" value='<?php echo isset($dataAndroid['privacy_policy_link']) ? $dataAndroid['privacy_policy_link'] : '' ?>' placeholder="Enter Privacy Policy">
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
                                        <p>You need to invite <a href="mailto:support@instappy.com"> support@instappy.com</a> to use your Android dev account as an administrator.
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
                                            <label>Android Publisher Email ID:</label>
                                        </div>
                                        <div class="developer_details_textbox">
                                            <input type="text"  maxlength="50" name="app_dev_acc_email" class=" email"  value="<?php echo isset($dataAndroid['androidpublisher_email']) ? $dataAndroid['androidpublisher_email'] : '' ?>" >
                                            <a href="#">?</a>
                                        </div>
                                        <div class="developer_details_label">
                                            <label>Android Developer Console Account Name:</label>
                                        </div>
                                        <div class="developer_details_textbox">
                                            <input type="text"  maxlength="50" name="app_dev_acc_name" value="<?php echo isset($dataAndroid['developerconsole_name']) ? $dataAndroid['developerconsole_name'] : '' ?>" >
                                            <a href="#">?</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- <a href="#" class="make_app_next">Finish</a> -->
                                <input type="hidden" value="<?php echo $_SESSION['appid']; ?>" name="app_id">
                                <div class="android_message"></div>
                                <input type="submit" class="make_app_next" value="Save">
                                <div class="clear"></div>
                            </form>
                        </div>
                    </div>
                    <div class="how_publish_body_right">
                        <div class="common_publish_right_box">

                            <a href="ios-app-publish.php">Publish For IOS</a>
                            <div class="clear"></div>
                        </div>
                        <div class="common_publish_right_box">
                            <h2>Let us help you !</h2>
                            <p>Need any help at any point, Let us guide you till the end.</p>
                            <a href="support.php" target="_blank">Support</a>
                            <div class="clear"></div>
                        </div>
                        <div class="common_publish_right_box">
                            <h2>Don't have a Developer Account?</h2>
                        <!-- <p>For better view on the topic visit Devloper Console.</p> -->
                            <a href="https://play.google.com/apps/publish/signup/" target="_blank">Create a Developer Account</a>
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
    (function ($) {
        $(window).load(function () {
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
    $(document).ready(function (e) {
        $(".paid").click(function () {
            $(".paid").removeClass("paid_active")
            $(this).addClass("paid_active")

        });
		$(".confirm_name_form input[type='button'").click(function(){
		$(".popup_container").css("display", "none");
		$(".confirm_name").css("display", "none");
		});
    });


</script>
<script type="text/javascript" src="js/jquery1.4.4.min.js"></script>
<script type="text/javascript" src="js/validate.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script>
    $(document).ready(function (e) {
        /* Upload Phone Screens Starts */
        $(".upload_screen").click(function () {
            $("#present").removeAttr("id");
            $(this).find("img").attr("id","present");
            $('.upload_screen_file').trigger('click');
        });
        $(".upload_screen_file").change(function () {
            $(".screen_message").empty(); // To remove the previous error message
            var ttl_img = this.files.length;
            if (ttl_img > 0) {
                //$(".publish_content_phone .phone_image").hide();
                //for (i = 0; i < ttl_img; i++) {
                    var i=0;
                    var file = this.files[i];
                    var imagefile = file.type;
                    var match = ["image/jpeg", "image/png", "image/jpg"];
                    var fsize = file.size; //get file size
                    var ftype = file.type; // get file type
                      if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
						{

						$(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html('<p>Please Select A valid Image File. Only jpeg, jpg and png Images type allowed</p>');
						$(".confirm_name").css({'display': 'block'});
						return false;

                    }
				
                    else if (fsize > 1048576) /* Allowed file size is less than 1 MB (1048576) */
                    {
                       $(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html(bytesToSize(fsize) +'<p> Too big Image file!. Please reduce the size of your photo using an image editor.</p>');
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
											   $("#present").attr("src", e.target.result);
											   $('#screenoverlay').fadeOut();
											var image_seq=	$("#present").attr("data-imageid");										   
										//alert($("#present").attr("data-imageid"))  ;											   
											   $.ajax({
                                        type: "POST",
                                        url: "ajax.php",
                                        data: "phone_image=" + imagePath + "&index=" +image_seq+ "&type=save_phone_img",
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
                                reader.readAsDataURL(file);
                        reader.readAsDataURL(this.files[i]);
						
						
     
                    }
                
            }
			
           // $("#present").removeAttr("id");
        });
        /* Upload Phone Screens Ends */

        /* Upload Tablet Screens Starts */
        $(".tablet_screen").click(function () {
             $("#present").removeAttr("id");
            $(this).find("img").attr("id","present");
            $('.upload_tablet_screen_file').trigger('click');
        });
        $(".upload_tablet_screen_file").change(function () {
            $(".tablet_screen_message").empty(); // To remove the previous error message
            var ttl_img = this.files.length;
            if (ttl_img > 0) {
                //$(".publish_content_phone .phone_image").hide();
                //for (i = 0; i <= ttl_img; i++) {
                    var i=0;
                    var file = this.files[i];
                    var imagefile = file.type;
                    var match = ["image/jpeg", "image/png", "image/jpg"];
                    var fsize = file.size; //get file size
                    var ftype = file.type; // get file type
                      if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
						{

						$(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html('<p>Please Select A valid Image File. Only jpeg, jpg and png Images type allowed</p>');
						$(".confirm_name").css({'display': 'block'});
						return false;

                    }
				
                    else if (fsize > 1048576) /* Allowed file size is less than 1 MB (1048576) */
                    {
                       $(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html(bytesToSize(fsize) +'<p> Too big Image file!. Please reduce the size of your photo using an image editor.</p>');
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
											   $("#present").attr("src", e.target.result);
											   $('#screenoverlay').fadeOut();
											var image_seq=	$("#present").attr("data-imageid");										   
										//alert($("#present").attr("data-imageid"))  ;											   
											   $.ajax({
                                        type: "POST",
                                        url: "ajax.php",
                                        data: "tablet_image=" + imagePath + "&index=" +image_seq+ "&type=save_7_inch_tablet_img",
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
                                reader.readAsDataURL(file);
                        reader.readAsDataURL(this.files[i]);
						

                    }
                //}
            }
        });
        /* Upload Tablet Screens Ends */

        /* Upload 10 inch Tablet Screens Starts */
        $(".ten_tablet_screen").click(function () {
            $("#present").removeAttr("id");
            $(this).find("img").attr("id","present");
            $('.upload_ten_tablet_screen_file').trigger('click');
        });
        $(".upload_ten_tablet_screen_file").change(function () {
            $(".ten_tablet_screen_message").empty(); // To remove the previous error message
            var ttl_img = this.files.length;
            if (ttl_img > 0) {
                //$(".publish_content_phone .phone_image").hide();
                //for (i = 0; i <= ttl_img; i++) {
                    var i=0;
                    var file = this.files[i];
                    var imagefile = file.type;
                    var match = ["image/jpeg", "image/png", "image/jpg"];
                    var fsize = file.size; //get file size
                    var ftype = file.type; // get file type
                      if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
						{

						$(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html('<p>Please Select A valid Image File. Only jpeg, jpg and png Images type allowed</p>');
						$(".confirm_name").css({'display': 'block'});
						return false;

                    }
				
                    else if (fsize > 1048576) /* Allowed file size is less than 1 MB (1048576) */
                    {
                       $(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html(bytesToSize(fsize) +'<p> Too big Image file!. Please reduce the size of your photo using an image editor.</p>');
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
											   $("#present").attr("src", e.target.result);
											   $('#screenoverlay').fadeOut();
											var image_seq=	$("#present").attr("data-imageid");										   
										//alert($("#present").attr("data-imageid"))  ;											   
											   $.ajax({
                                        type: "POST",
                                        url: "ajax.php",
                                        data: "large_tablet_image=" + imagePath + "&index=" +image_seq+ "&type=save_10_inch_tablet_img",
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
                                reader.readAsDataURL(file);
                        reader.readAsDataURL(this.files[i]);
                    }
                //}
            }
        });
        /* Upload 10 inch Tablet Screens Ends */

        /* Upload Featured Graphic Screens Starts */
        $(".featured_graphic_image").click(function () {
            $('.upload_featured_graphic_file').trigger('click');
        });
        $(".upload_featured_graphic_file").change(function () {
            $(".featured_graphic_message").empty(); // To remove the previous error message
            var ttl_img = this.files.length;
            if (ttl_img > 0) {
                //$(".publish_content_phone .phone_image").hide();
					var i=0;
                    var file = this.files[i];
                    var imagefile = file.type;
                    var match = ["image/jpeg", "image/png", "image/jpg"];
                    var fsize = file.size; //get file size
                    var ftype = file.type; // get file type
					 var imgtest = $('.featured_graphic_image img');
					var testSize = imgtest[0].getBoundingClientRect();
                    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
						{

						$(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html('<p>Please Select A valid Image File. Only jpeg, jpg and png Images type allowed</p>');
						$(".confirm_name").css({'display': 'block'});
						return false;

                    }
					else if (testSize.width >= 1024 && testSize.height >= 500)
					{
						$(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html('<p> Please Upload valid Iimage Dimension 1024 w X 500 h</p>');
						$(".confirm_name").css({'display': 'block'});
						return false;
					}
                    else if (fsize > 1048576) /* Allowed file size is less than 1 MB (1048576) */
                    {
                       $(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html(bytesToSize(fsize) +'<p> Too big Image file!. Please reduce the size of your photo using an image editor.</p>');
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
												if(imgwidth>=1024 && imgheight>=500){
												$(".featured_graphic_image img").attr('src', e.target.result);											  	
												 $.ajax({
																						type: "POST",
																						url: "ajax.php",
																						data: "icons_featured_image=" + imagePath + "&type=save_featured_icons",
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
											else{
												$(".cropcancel").trigger("click");
												$("#page_ajax").html('').hide();
												$(".popup_container").css({'display': 'block'});
												$(".confirm_name .confirm_name_form").html('<p>Please Upload valid Iimage Dimension 1024 w X 500 h</p>');
												$(".confirm_name").css({'display': 'block'});
												return false;
												}									   
																																							   
                                                
                                                                                                                                                               
                                            }
                                            else
                                            {
                                                $('#screenoverlay').fadeOut();


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
                                reader.readAsDataURL(file);
                        reader.readAsDataURL(this.files[i]);
                        // var reader = new FileReader();
                        // reader.onload = function imageIsLoaded(e) {
                            // //$('<img src="'+e.target.result+'" alt="" width="100" height="100">').insertAfter(".feature-icon .featured_graphic_image");
                            // $(".featured_graphic_image img").attr('src', e.target.result);
                            // $(".featured_graphic_image img").attr('width', '170');
                        // };
                        // reader.readAsDataURL(this.files[i]);
                        //}
                        //}
                        //$('#android_app_publish').ajaxSubmit(options);
                    }
                
            }
        });
        /* Upload Featured Graphic Screens Ends */

        /* Upload Featured Graphic Screens Starts */
        $(".promo_graphic_image").click(function () {
            $('.upload_promo_graphic_file').trigger('click');
        });
        $(".upload_promo_graphic_file").change(function () {
            $(".promo_graphic_message").empty(); // To remove the previous error message
            var ttl_img = this.files.length;
            if (ttl_img > 0) {
                //$(".publish_content_phone .phone_image").hide();
					var i=0;
                    var file = this.files[i];
                    var imagefile = file.type;
                    var match = ["image/jpeg", "image/png", "image/jpg"];
                    var fsize = file.size; //get file size
                    var ftype = file.type; // get file type
					var imgtest = $('.promo_graphic_image img');
					var testSize = imgtest[0].getBoundingClientRect();
                     if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
						{

						$(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html('<p>Please Select A valid Image File. Only jpeg, jpg and png Images type allowed</p>');
						$(".confirm_name").css({'display': 'block'});
						return false;

                    }
				
                    else if (fsize > 1048576) /* Allowed file size is less than 1 MB (1048576) */
                    {
                       $(".cropcancel").trigger("click");
						$("#page_ajax").html('').hide();
						$(".popup_container").css({'display': 'block'});
						$(".confirm_name .confirm_name_form").html(bytesToSize(fsize) +'<p> Too big Image file!. Please reduce the size of your photo using an image editor.</p>');
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
												if(imgwidth>=180 && imgheight>=120){
												$(".promo_graphic_image img").attr('src', e.target.result);											  	
												 $.ajax({
																						type: "POST",
																						url: "ajax.php",
																						data: "icons_graphic_image=" + imagePath + "&type=save_graphic_icons",
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
											else{
												$(".cropcancel").trigger("click");
												$("#page_ajax").html('').hide();
												$(".popup_container").css({'display': 'block'});
												$(".confirm_name .confirm_name_form").html('<p>Please Upload valid Iimage Dimension 180 w X 120 h</p>');
												$(".confirm_name").css({'display': 'block'});
												return false;
												}									   
																																							   
                                                
                                                                                                                                                               
                                            }
                                            else
                                            {
                                                $('#screenoverlay').fadeOut();


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
                                reader.readAsDataURL(file);
                        reader.readAsDataURL(this.files[i]);
                        //var ttl_img = this.files.length;
                        //if(ttl_img > 0){
                        //$(".publish_content_phone .phone_image").hide();
                        //for(i = 0; i <= ttl_img; i++){
                        // var reader = new FileReader();
                        // reader.onload = function imageIsLoaded(e) {
                            // //$('<img src="'+e.target.result+'" alt="" width="100" height="100">').insertAfter(".feature-icon .featured_graphic_image");
                            // $(".promo_graphic_image img").attr('src', e.target.result);
                            // $(".promo_graphic_image img").attr('width', '170');
                        // };
                        // reader.readAsDataURL(this.files[i]);
                        //}
                        //}
                        //$('#android_app_publish').ajaxSubmit(options);
                    }
                
            }
        });
        /* Upload Featured Graphic Screens Ends */


        /* Android upload */
        var options = {
            target: '.android_message', // target element(s) to be updated with server response 
            url: BASEURL + 'API/uploadappscreen.php/uploadandroidpublish',
            beforeSubmit: beforeSubmit, // pre-submit callback 
            success: afterSuccess, // post-submit callback 
            resetForm: false        // reset the form after successful submit 
        };
        /* Android Ends */
        function afterSuccess(response)
        {
			if(response=='success'){
			$("#screenoverlay").css("display", "none");	
			$(".popup_container").css({'display': 'block'});
			$(".confirm_name,.confirm_name_form").css({'display': 'block'});
			$(".confirm_name_form p").text('Data Update successfully.');			
			setTimeout(function(){
			window.location='publish_android.php';	
			},3000);
			
			return false;
            
			}
			else{
			$("#screenoverlay").css("display", "none");
			$(".popup_container").css({'display': 'block'});
			$(".confirm_name,.confirm_name_form").css({'display': 'block'});
			$(".confirm_name_form p").text('Oops Something went wrong .please try again later.');
				
			}
        }
        //function to check file size before uploading.
        function beforeSubmit() {

            //check whether browser fully supports all File API
            if (window.File && window.FileReader && window.FileList && window.Blob)
            {
                $(".android_message").html("");
            }
            else
            {
                //Output error to older browsers that do not support HTML5 File API
                $(".android_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
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

        /* Upload Android Publish Starts */
        //$("#android_app_publish").validate();
        $("#android_app_publish").validate({
            submitHandler: function (form) {
                $("#screenoverlay").css("display", "block");
                $('#android_app_publish').ajaxSubmit(options);
                
            }
        });
        /* Upload Android Publish Ends */
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
