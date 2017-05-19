<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
require_once('modules/checkapp/app_screen.php');
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$webscreen = new WebScreen();
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

if ($app_id) {
    
    $screenShot='';
    $appType=1;
    require_once('modules/opencart-integration/opencart-integration.php');
    $obj = new Opencart();
    $app_data = $obj->edit_catalogue_app($app_id);
    $html = $obj->get_cuurent_app_html($app_data['theme']);
     
    $icons = $webscreen->get_app_icons($app_id, 1); /* 1 == premium */
    $pageScreen = $webscreen->app_screenshot($app_id); /* 1 == premium */
    if(!empty($pageScreen))
    {
        $screenShot=$pageScreen['screenshot_url'];
         $appType=$pageScreen['type_app'];
    
    }
    ?>
    <!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
    
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
                <div class="mid_section ">
                    <div class="mid_section_left">
                    </div>
                    <div class="mid_section_right">
                        <div class="mid_right_box active">
                            <div class="mid_right_box_head">
                                <h1>Icons</h1>
                                <h2>An icon represents your app and its features pictorially. We suggest that you choose an icon that best describes your app and business. For e.g., if you run a restaurant, a folk and a spoon could work well in helping your consumers recognise the nature of your service.</h2>
                                <a class="read_more">Read More...</a>
                                <div class="clear"></div>
                            </div>
                            <div class="mid_right_box_body">
                                <div class="choose_icon">
                                    <h2 class="icon_tip">Choose a premium icon for your awesome app <span style="color:#ffcc00;"><?php if($currency==1){ ?>(Price- Rs.1,500)<?php } else {?>(Price- $23)<?php }?></span></h2>
                                    <div class="icons">
    <?php
   
    if (count($icons) > 0) {
        $sel_icon = $webscreen->get_selected_app_icon($app_id);
        foreach ($icons as $icon) {
            if ($icon['image_40'] != '') {
                if (trim(str_replace("&amp;", "&", $sel_icon)) == trim($icon['image_40'])) {
                    ?><div><img class="selected" src="<?php echo $icon['image_40']; ?>"></div>
                                                    <?php } else { ?>
                                                        <div><img src="<?php echo $icon['image_40']; ?>"></div>
                                                        <?php
                                                    }
                                                }
                                            }
                                            if ($webscreen->get_total_app_icon($app_id, 1) > 14) {
                                                ?><div class="more_paid_icon"></div>
                                                <div class="clear"></div>
                                                <input type="button" data-page="1" data-total_page="<?php echo intval($webscreen->get_total_app_icon($app_id, 1) / 14); ?>" value="Load More" id="load_more_paid_icon" class="load_more">
        <?php
        } else {
            echo '<div class="clear"></div>';
        }
    }
    ?>
                                    </div>

                                    <h2 class="icon_tip">Choose a free icon for your awesome app</span></h2>
                                    <div class="icons">
                                        <?php
                                        $webscreen = new WebScreen();
                                        $icons = $webscreen->get_app_icons($app_id);
                                        if (count($icons) > 0) {
                                            $sel_icon = $webscreen->get_selected_app_icon($app_id);
                                            foreach ($icons as $icon) {
                                                if ($icon['image_40'] != '') {
                                                    if (urldecode($sel_icon) == urldecode($icon['image_40'])) {
                                                        ?><div><img class="selected" src="<?php echo $icon['image_40']; ?>"></div>
                                                    <?php } else { ?>
                                                       <div><img src="<?php echo $icon['image_40']; ?>"></div>
                                                        <?php
                                                    }
                                                }
                                            }
                                            if ($webscreen->get_total_app_icon($app_id) > 14) {
                                                ?><div class="more_default_icon"></div>
                                                <div class="clear"></div>
                                                <input type="button" data-page="1" data-total_page="<?php echo intval($webscreen->get_total_app_icon($app_id) / 14); ?>" value="Load More" id="load_more_default_icon" class="load_more">
        <?php }else {
            echo '<div class="clear"></div>';
        }
    } ?>
                                    </div>

                                </div>
                                <div class="choose_icon">
                                    <?php
                                    if ($app_id) {
                                        $self_icons = $webscreen->get_self_icons($app_id);
                                        //echo "<pre>"; print_r($self_icons); echo "</pre>"; 
										$sel_icon = $webscreen->get_selected_app_icon($app_id);
                                    }
                                    ?>
                                    <form name='upload_icons' id='upload_icons' method='post' action='' enctype="multipart/form-data">
                                        <h2>Or, you can even upload your very own icon for your app.</h2>
                                        <p>Your icon image should not be more than <span>1 MB</span> in size, and <span>1024 x 1024</span> pixels for best results. Your app will be rejected if you don't comply with these guidelines.</p>
                                        <a class="read_more">Read More...</a>
                                        <div class="clear"></div>
                                        <div class="os_screen">
                                            <!-- <h2>iPhone</h2> -->
                                            <?php
                                            if (count($self_icons) > 0) {
                                                $ios_size = array(1024);
                                                foreach ($self_icons as $self_icon) {
                                                    //$img_ext = explode('.',$self_icon['image_40']);
                                                    $size = end(explode('X', $self_icon['icon_name']));
                                                    if (in_array($size, $ios_size)) {
                                                        ?>
                                                        <div class="screen_size large">
                                                            <?php if (trim(str_replace("&amp;","&",$sel_icon)) == trim($self_icon['image_40'])) { ?>
                                                                <div><img class="selected" src="<?php echo $self_icon['image_40']; ?>" data-size="<?php echo $size; ?>"></div>
                                                            <?php } else { ?>
                                                                <div><img src="<?php echo $self_icon['image_40']; ?>" data-size="<?php echo $size; ?>"></div>
                <?php } ?>
                                                            <div class="clear"></div>
                                                          
                                                        </div>
                                                    <?php
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <div class="screen_size medium">
                                                    <img src="images/iphone_1024.jpg" data-size="1024">
                                                    <div class="clear"></div>
                                                    <!-- <span>Home Screen</span> -->
                                                </div>
                                        <?php } ?>
                                            <div class="clear"></div>
                                        </div>
                                        <!-- <div class="os_screen ">
                                        <?php /* if(count($self_icons) > 0){
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
                                          <?php } }} */ ?>
                                            <div class="clear"></div>
                                                                    </div> -->
                                        <input type='file' name='icons' value='' style='display:none;'>
                                        <input type="hidden" name='appid' value='<?php echo $_SESSION['appid']; ?>'>
                                        <div id='upload_icons_message'></div>
                                    </form>
                                </div>
                                <!-- <a href="add-splash-screens.php" class="make_app_next">Save &amp; Continue</a> -->
                                <div class="selected_app_icon" style="display:none;"><?php if (trim(str_replace("&amp;","&",$sel_icon)) != ''){ echo trim(str_replace("&amp;","&",$sel_icon)); }?></div>
                                <div id="continue_icons_message"></div>
								<a href="javascript:void(0);" class="make_app_next">Save &amp; Continue</a>
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
            $('.screen_size img').click(function () {
                $('#upload_icons input[type=file]').trigger("click");
            });
            $(document).off("click", "nav > ul >li");
            var options = {
                target: '#upload_icons_message', // target element(s) to be updated with server response 
                url: '<?php echo $basicUrl; ?>' + 'API/index.php/uploadappicons',
                beforeSubmit: beforeSubmit, // pre-submit callback 
                //success: afterSuccess, // post-submit callback 
                complete: afterSuccess, // post-submit callback 
                resetForm: true        // reset the form after successful submit 
            };
            $('#upload_icons input[type=file]').change(function () {
                $('#upload_icons_message').text('');
                var file = this.files[0];
                var imagefile = file.type;
                var match = ["image/jpeg", "image/png", "image/jpg"];
                //var imgtest = document.getElementById("testSize");
                var imgtest = $('#upload_icons img');
                var testSize = imgtest[0].getBoundingClientRect();

                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
                {
                   $('#screenoverlay').fadeOut();
                    $("#upload_icons_message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                    return false;
                }
                else if (testSize.width >= 1024 && testSize.height >= 1024)
                {
					 $('#screenoverlay').fadeOut();
                    $("#upload_icons_message").html("<p id='error'>Please Select an Image of dimensions 1024px X 1024px </p>");
                    return false;
                }
                else
                {
                    var reader = new FileReader();
                    reader.onload = function (e) {

                    };
                    reader.readAsDataURL(this.files[0]);
                    $('#screenoverlay').fadeIn();
                    $('#upload_icons').ajaxSubmit(options);
					$('#screenoverlay').fadeOut();
                }
            });
            function afterSuccess(response)
            {
                 $('#screenoverlay').fadeOut();
                $('#upload_icons_message').html(response.responseText);
                if (response.responseText == 'Icons Uploaded Successfully') {
                    window.location = window.location;
                }
            }
            //function to check file size before uploading.
            function beforeSubmit() {
                //check whether browser fully supports all File API
                if (window.File && window.FileReader && window.FileList && window.Blob)
                {

                    if (!$('#upload_icons input[type=file]').val()) //check empty input filed
                    {
						 $('#screenoverlay').fadeOut();
                        $("#upload_icons_message").html("Are you kidding me?");
                        return false
                    }

                    var fsize = $('#upload_icons input[type=file]')[0].files[0].size; //get file size
                    var ftype = $('#upload_icons input[type=file]')[0].files[0].type; // get file type


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
                            $("#upload_icons_message").html("<b>" + ftype + "</b> Unsupported file type!");
                            return false
                    }

                    //Allowed file size is less than 1 MB (1048576)
                    if (fsize > 1048576)
                    {
						 $('#screenoverlay').fadeOut();
                        $("#upload_icons_message").html("Icon image size is greater than 1 MB . Please update app icon and try and again!");
                        return false
                    }

                    //$('#submit-btn').hide(); //hide submit button
                    //$('#loading-img').show(); //hide submit button
                    $("#upload_icons_message").html("");
                }
                else
                {
					 $('#screenoverlay').fadeOut();
                    //Output error to older browsers that do not support HTML5 File API
                    $("#upload_icons_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
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
   
        });

        /* Load More Starts */
        $(document).ready(function () {
            $("#load_more_default_icon").on("click", function () {
                var page = parseInt($(this).attr('data-page'));
                var form_data = {
                    token: '<?php echo $token; ?>', //used token here.
                    hasid: '<?php echo $app_id; ?>',
                    page: page,
                    premium: 0
                };
                $.ajax({
                    type: "POST",
                    url: 'modules/checkapp/load_more_default_icon.php',
                    data: form_data,
                    success: function (response)
                    {
                        //$('.more_default_icon').append(response);
                        $(response).insertBefore('.more_default_icon');
                        $("#load_more_default_icon").attr('data-page', page + 1);
                        if (page + 1 > $("#load_more_default_icon").attr('data-total_page')) {
                            $("#load_more_default_icon").hide();
                        }
						if ($('.icons img, .os_screen img').find('.selected')) {
							var sel_img_url = $('.icons img.selected, .os_screen img.selected').attr('src');
							$('.selected_app_icon').html(sel_img_url);
						}
                    },
                    error: function () {
                        console.log("error in ajax call");
                    }
                });
            });

            $("#load_more_paid_icon").on("click", function () {
                var page = parseInt($(this).attr('data-page'));
                var form_data = {
                    token: '<?php echo $token; ?>', //used token here.
                    hasid: '<?php echo $app_id; ?>',
                    page: page,
                    premium: 1
                };
                $.ajax({
                    type: "POST",
                    url: 'modules/checkapp/load_more_default_icon.php',
                    data: form_data,
                    success: function (response)
                    {
                        //$('.more_paid_icon').append(response);
                        $(response).insertBefore('.more_paid_icon');
                        $("#load_more_paid_icon").attr('data-page', page + 1);
                        if (page + 1 > $("#load_more_paid_icon").attr('data-total_page')) {
                            $("#load_more_paid_icon").hide();
                        }
						if ($('.icons img, .os_screen img').find('.selected')) {
							var sel_img_url = $('.icons img.selected, .os_screen img.selected').attr('src');
							$('.selected_app_icon').html(sel_img_url);
						}
                    },
                    error: function () {
                        console.log("error in ajax call");
                    }
                });
            });
        });
        /* Load More Ends */
     

     
        $(document).ready(function () {
            if ($('.icons img, .os_screen img').find('.selected')) {
                var sel_img_url = $('.icons img.selected, .os_screen img.selected').attr('src');
                $('.selected_app_icon').html(sel_img_url);
            }
			else{
				
				var sel_img_url = $('.os_screen img').attr('src');
                $('.selected_app_icon').html(sel_img_url);
			}

            $('.icons, .os_screen').on('click', 'img', function(){
				$('.icons img, .os_screen img').removeClass('selected');
                var sel_img_url = $(this).attr('src');
                $(this).addClass('selected');
                $('.selected_app_icon').html(sel_img_url);
			});

            $('.make_app_next').click(function () {
				var sel_img_url = $('.selected_app_icon').html();
				var upload_img=$(".selected").attr("src");
				if(($("#upload_icons_message").text()=='Icons Uploaded Successfully')|| (upload_img !='images/iphone_1024.jpg' && upload_img !=undefined )|| (sel_img_url!='' && sel_img_url!='images/iphone_1024.jpg' )){
					var form_data = {
						data: sel_img_url, //your data being sent with ajax
						token: '<?php echo $token; ?>', //used token here.
						hasid: '<?php echo $app_id; ?>',
						is_ajax: 2
					};
					$.ajax({
						type: "POST",
						url: 'modules/checkapp/selected_icon.php',
						data: form_data,
						success: function (response)
						{
							//console.log(response);
							window.location = BASEURL + 'add-splash-screens.php?app_id=<?php echo $app_id; ?>'
						},
						error: function () {
							console.log("error in ajax call");
							//alert("error in ajax call");
						}
					});
				}
				else{
					$('#continue_icons_message').html('Please Select An App Icon To Continue.');
					return false;
				}
            });
        });
    </script>

    <?php
} else {
    $screenShot='';
    $appType=1;
     $appID = $_SESSION['appid'];   
     
    require_once('modules/apphtml/apphtml.php');
    $username = $_SESSION['username'];
   
    $custid = $_SESSION['custid'];
  
    $save = new Saveapphtml();
 
 $webscreen = new WebScreen();
       $pageScreen = $webscreen->app_screenshot($appID); /* 1 == premium */
      
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
    <!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
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
                <div class="mid_section ">
                    <div class="mid_section_left">
                    </div>
                    <div class="mid_section_right">
                        <div class="mid_right_box active">
                            <div class="mid_right_box_head">
                                <h1>Icons</h1>
                                <h2>An icon represents your app and its features pictorially. We suggest that you choose an icon that best describes your app and business. For e.g., if you run a restaurant, a folk and a spoon could work well in helping your consumers recognise the nature of your service.</h2>
                                <a class="read_more">Read More...</a>
                                <div class="clear"></div>
                            </div>
                            <div class="mid_right_box_body">
                                <div class="choose_icon">
                                    <h2 class="icon_tip">Choose a premium icon for your awesome app <span style="color:#ffcc00;"><?php if($currency==1){ ?>(Price- Rs.1,500)<?php } else {?>(Price- $23)<?php }?></span></h2>
                                    <div class="icons">
                                        <?php
                                        $webscreen = new WebScreen();
                                        $icons = $webscreen->get_app_icons($appID, 1); /* 1 == premium */
                                        
                                        print_r($icons);
                                        echo '+++++++++++++++++++'.$appID.'++++++++++++++++++++';
                                      
                                        if (count($icons) > 0) {
                                            $sel_icon = $webscreen->get_selected_app_icon($appID);
                                         
                                            foreach ($icons as $icon) {
                                                if ($icon['image_40'] != '') {
                                                    if ( !empty($sel_icon)  && (trim(str_replace("&amp;", "&", $sel_icon)) == trim($icon['image_40']))) {
                                                        ?><div class="uploaded_icons"><img class="selected" src="<?php echo $icon['image_40']; ?>"></div>
                                                    <?php } else { ?>
                                                        <div class="uploaded_icons"><img src="<?php echo $icon['image_40']; ?>"></div>
                                                        <?php
                                                    }
                                                }
                                            }
                                            
                                            if ($webscreen->get_total_app_icon($appID, 1) > 14) {
                                                ?><div class="more_paid_icon"></div>
                                                <div class="clear"></div>
                                                <input type="button" data-page="1" data-total_page="<?php echo intval($webscreen->get_total_app_icon($appID, 1) / 14); ?>" value="Load More" id="load_more_paid_icon" class="load_more">
        <?php
        } else {
            echo '<div class="clear"></div>';
        }
    }
    ?>
                                    </div>

                                    <h2 class="icon_tip">Choose a free icon for your awesome app <span style="color:#ffcc00;"></span></h2>
                                    <div class="icons">
                                        <?php
                                        $webscreen = new WebScreen();
                                        $icons = $webscreen->get_app_icons($appID);
                                        if (count($icons) > 0) {
                                            $sel_icon = $webscreen->get_selected_app_icon($_SESSION['appid']);
                                            foreach ($icons as $icon) {
                                                if ($icon['image_40'] != '') {
                                                    if(trim(str_replace("&amp;","&",$sel_icon)) == trim($icon['image_40'])){
                                                        ?><div class="uploaded_icons"><img class="selected" src="<?php echo $icon['image_40']; ?>"></div>
                <?php } else { ?>
                                                        <div class="uploaded_icons"><img src="<?php echo $icon['image_40']; ?>"></div>
                                                        <?php
                                                    }
                                                }
                                            }
                                            if ($webscreen->get_total_app_icon($appID) > 14) {
                                                ?><div class="more_default_icon"></div>
                                                <div class="clear"></div>
                                                <input type="button" data-page="1" data-total_page="<?php echo intval($webscreen->get_total_app_icon($appID) / 14); ?>" value="Load More" id="load_more_default_icon" class="load_more">
                                        <?php }else {
													echo '<div class="clear"></div>';
												}
                                    } ?>
                                    </div>

                                </div>
                                <div class="choose_icon">
    <?php
    if (isset($_SESSION['appid']) && $_SESSION['appid'] != '') {
        $self_icons = $webscreen->get_self_icons($_SESSION['appid']);
        //echo "<pre>"; print_r($self_icons); echo "</pre>"; 
    }
    ?>
                                    <form name='upload_icons' id='upload_icons' method='post' action='' enctype="multipart/form-data">
                                        <h2>Or, you can even upload your very own icon for your app.</h2>
                                        <p>Your icon image should not be more than <span>1 MB</span> in size, and <span>1024 x 1024</span> pixels for best results. Your app will be rejected if you don't comply with these guidelines.</p>
                                        <a class="read_more">Read More...</a>
                                        <div class="clear"></div>
                                        <div class="os_screen">
                                            <!-- <h2>iPhone</h2> -->
                                                <?php
                                                if (count($self_icons) > 0) {
                                                    $ios_size = array(1024);
													$sel_icon = $webscreen->get_selected_app_icon($_SESSION['appid']);
                                                    foreach ($self_icons as $self_icon) {
                                                        //$img_ext = explode('.',$self_icon['image_40']);
                                                        $size = end(explode('X', $self_icon['icon_name']));
                                                        if (in_array($size, $ios_size)) {
                                                            ?>
                                                        <div class="screen_size large">
                                                        <?php if(trim(str_replace("&amp;","&",$sel_icon)) == trim($icon['image_40'])){ ?>
                                                                <div class="uploaded_icons"><img class="selected" src="<?php echo $self_icon['image_40']; ?>" data-size="<?php echo $size; ?>"></div>
                <?php } else { ?>
                                                                <div class="uploaded_icons"><img src="<?php echo $self_icon['image_40']; ?>" data-size="<?php echo $size; ?>"></div>
                <?php } ?>
                                                            <div class="clear"></div>
                                                           
                                                        </div>
            <?php
            }
        }
    } else {
        ?>
                                                <div class="screen_size medium">
                                                    <img src="images/iphone_1024.jpg" data-size="1024">
                                                    <div class="clear"></div>
                                                    <span>Home Screen</span>
                                                </div>
                                        <?php } ?>
                                            <div class="clear"></div>
                                        </div>
                                        <!-- <div class="os_screen ">
                                        <?php /* if(count($self_icons) > 0){
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
                                          <?php } }} */ ?>
                                            <div class="clear"></div>
                                                                    </div> -->
																	<?php $sel_icon = $webscreen->get_selected_app_icon($_SESSION['appid']); ?>
                                        <input type='file' name='icons' value='' style='display:none;'>
                                        <input type="hidden" name='appid' value='<?php echo $_SESSION['appid']; ?>'>
                                        <div id='upload_icons_message'></div>
                                    </form>
                                </div>
                                <!-- <a href="add-splash-screens.php" class="make_app_next">Save &amp; Continue</a> -->
                                <div class="selected_app_icon" style="display:none;"><?php if (trim(str_replace("&amp;","&",$sel_icon)) != ''){ echo trim(str_replace("&amp;","&",$sel_icon)); }?></div>
                                <div id="continue_icons_message"></div>
								<a href="javascript:void(0);" class="make_app_next">Save &amp; Continue</a>
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

    <script type="text/javascript">

      
        $(document).ready(function () {
            if ($('.icons img, .os_screen img').find('.selected')) {
                var sel_img_url = $('.icons img.selected, .os_screen img.selected').attr('src');
                $('.selected_app_icon').html(sel_img_url);
            }
			else{
				var sel_img_url = $('.os_screen img').attr('src');
                $('.selected_app_icon').html(sel_img_url);
			}
           /* $('.icons img, .os_screen img').on('click', function () {
				$('.icons img, .os_screen img').removeClass('selected');
                var sel_img_url = $(this).attr('src');
                $(this).addClass('selected');
                $('.selected_app_icon').html(sel_img_url);
            });*/
			$('.icons, .os_screen').on('click', 'img', function(){
				$('.icons img, .os_screen img').removeClass('selected');
                var sel_img_url = $(this).attr('src');
                $(this).addClass('selected');
				$('.uploaded_icons').css('z-index','0');
				$(this).parent('.uploaded_icons').css('z-index','1');
                $('.selected_app_icon').html(sel_img_url);
			});
            $('.make_app_next').click(function () {
				var sel_img_url = $('.selected_app_icon').html();
				var upload_img=$(".selected").attr("src");			
			if(($("#upload_icons_message").text()=='Icons Uploaded Successfully')|| (upload_img !='images/iphone_1024.jpg' && upload_img !=undefined )|| (sel_img_url!='' && sel_img_url!='images/iphone_1024.jpg'))
				{
				var form_data = {
                    data: sel_img_url, //your data being sent with ajax
                    token: '<?php echo $token; ?>', //used token here.
                    hasid: '<?php echo $appID; ?>',
                    is_ajax: 2
                };
                $.ajax({
                    type: "POST",
                    url: 'modules/checkapp/selected_icon.php',
                    data: form_data,
                    success: function (response)
                    {
                        //console.log(response);
                        window.location = BASEURL + 'add-splash-screens.php'
                    },
                    error: function () {
                        console.log("error in ajax call");
                        //alert("error in ajax call");
                    }
                });
				}
				else{
					$('#continue_icons_message').html('Please Select An App Icon To Continue');
					return false;
				}
            });

        });
    </script>
    <script type="text/javascript" src="js/colpick.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.shapeshift.js" type="text/javascript"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 	
    <script type="text/javascript" src="js/publishJS.js"></script>
    <script src="js/html2canvas.js" type="text/javascript"></script>
    <script src="js/jquery.plugin.html2canvas.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.form.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function () {
            $('.screen_size img').click(function () {
                $('#upload_icons input[type=file]').trigger("click");
            });
          //  $(document).off("click", "nav > ul >li");
            var options = {
                target: '#upload_icons_message', // target element(s) to be updated with server response 
                url: '<?php echo $basicUrl; ?>' + 'API/index.php/uploadappicons',
                beforeSubmit: beforeSubmit, // pre-submit callback 
                //success: afterSuccess, // post-submit callback 
                complete: afterSuccess, // post-submit callback 
                resetForm: true        // reset the form after successful submit 
            };
            $('#upload_icons input[type=file]').change(function () {
               $('#upload_icons_message').text('');
                var file = this.files[0];
                var imagefile = file.type;
                var match = ["image/jpeg", "image/png", "image/jpg"];
                //var imgtest = document.getElementById("testSize");
                var imgtest = $('#upload_icons img');
                var testSize = imgtest[0].getBoundingClientRect();

                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
                {
                    //$('#previewing').attr('src','noimage.png');
                    $("#upload_icons_message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                    return false;
                }
                else if (testSize.width >= 1024 && testSize.height >= 1024)
                {
                    $("#upload_icons_message").html("<p id='error'>Please Select an Image of 1024 size</p>");
                    return false;
                }
                else
                {
                    var reader = new FileReader();
                    reader.onload = function (e) {

                    };
                    reader.readAsDataURL(this.files[0]);
                    $('#screenoverlay').fadeIn();
                    $('#upload_icons').ajaxSubmit(options);

                }
            });
            function afterSuccess(response)
            {
			
                $('#screenoverlay').fadeOut();
                $('#upload_icons_message').html(response.responseText);
                if (response.responseText == 'Icons Uploaded Successfully') {
                    window.location = window.location;
                }
            }
            //function to check file size before uploading.
            function beforeSubmit() {
                //check whether browser fully supports all File API
                if (window.File && window.FileReader && window.FileList && window.Blob)
                {

                    if (!$('#upload_icons input[type=file]').val()) //check empty input filed
                    {
						 $('#screenoverlay').fadeOut();
                        $("#upload_icons_message").html("Are you kidding me?");
                        return false
                    }

                    var fsize = $('#upload_icons input[type=file]')[0].files[0].size; //get file size
                    var ftype = $('#upload_icons input[type=file]')[0].files[0].type; // get file type


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
                            $("#upload_icons_message").html("<b>" + ftype + "</b> Unsupported file type!");
                            return false
                    }

                    //Allowed file size is less than 1 MB (1048576)
                    if (fsize > 1048576)
                    {
						 $('#screenoverlay').fadeOut();
                        $("#upload_icons_message").html("Icon image size is greater than 1 MB . Please update app icon and try and again");
                        return false
                    }

                    //$('#submit-btn').hide(); //hide submit button
                    //$('#loading-img').show(); //hide submit button
                    $("#upload_icons_message").html("");
                }
                else
                {
					 $('#screenoverlay').fadeOut();
                    //Output error to older browsers that do not support HTML5 File API
                    $("#upload_icons_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
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
        });

        /* Load More Starts */
        $(document).ready(function () {
            $("#load_more_default_icon").on("click", function () {
                var page = parseInt($(this).attr('data-page'));
                var form_data = {
                    token: '<?php echo $token; ?>', //used token here.
                    hasid: '<?php echo $appID; ?>',
                    page: page,
                    premium: 0
                };
                $.ajax({
                    type: "POST",
                    url: 'modules/checkapp/load_more_default_icon.php',
                    data: form_data,
                    success: function (response)
                    {
                        //$('.more_default_icon').append(response);
                        $(response).insertBefore('.more_default_icon');
                        $("#load_more_default_icon").attr('data-page', page + 1);
                        if (page + 1 > $("#load_more_default_icon").attr('data-total_page')) {
                            $("#load_more_default_icon").hide();
                        }
						if ($('.icons img, .os_screen img').find('.selected')) {
							var sel_img_url = $('.icons img.selected, .os_screen img.selected').attr('src');
							$('.selected_app_icon').html(sel_img_url);
						}
                    },
                    error: function () {
                        console.log("error in ajax call");
                    }
                });
            });

            $("#load_more_paid_icon").on("click", function () {
                var page = parseInt($(this).attr('data-page'));
                var form_data = {
                    token: '<?php echo $token; ?>', //used token here.
                    hasid: '<?php echo $appID; ?>',
                    page: page,
                    premium: 1
                };
                $.ajax({
                    type: "POST",
                    url: 'modules/checkapp/load_more_default_icon.php',
                    data: form_data,
                    success: function (response)
                    {
                        //$('.more_paid_icon').append(response);
                        $(response).insertBefore('.more_paid_icon');
                        $("#load_more_paid_icon").attr('data-page', page + 1);
                        if (page + 1 > $("#load_more_paid_icon").attr('data-total_page')) {
                            $("#load_more_paid_icon").hide();
                        }
						if ($('.icons img, .os_screen img').find('.selected')) {
							console.log('hi');
							var sel_img_url = $('.icons img.selected, .os_screen img.selected').attr('src');
							$('.selected_app_icon').html(sel_img_url);
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

    <?php if (empty($appID) && empty($custid)) { ?>
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
				$('#screenoverlay').css('display','block');
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
                                $(".droparea.container").css("background-color", $(".theme_head").eq(1).attr("data-appbackground"));

                                //firstUpdateSlideNames();
        //                            updateSlideNames();
                                $(".container.droparea").shapeshift({
                                    colWidth: 134.5,
                                    minColumns: 2
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
                $(".mobile nav").css("background-color",$(".theme_head").eq(1).css("background-color"));
				

            });

			$(window).load(function(){
				$('#screenoverlay').fadeOut();
			})
        </script>
    <?php } ?>
    </body>
    </html>
<?php } ?>