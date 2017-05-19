<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/apphtml/apphtml.php');
require_once('modules/checkapp/app_screen.php');
require_once('modules/login/login-check.php');
require_once('modules/opencart-integration/opencart-integration.php');
$webscreen = new WebScreen();
$login = new Login();
$obj = new Opencart();

$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;

$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
if ($app_id) {
    $app_data = $obj->edit_catalogue_app($app_id);
    $html = $obj->get_cuurent_app_html($app_data['theme']);
    $app_details=   $obj->get_app_details($app_id);
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
                                    <h2>Looks like you are a pro!</h2>
                                    <p>Your app is ready to be published for Android as well as iOS devices. <br/>You can preview it with the Instappy Wizard before you go ahead and purchase it.</p>
                                    <div class="congrats_banner" style="background-color:#ffcc00">
                                        <img src="images/logo_congrats.png" alt="not found">
                                        <h3 style="color:#fff;">Download the Instappy Wizard</h3>
                                        <p style="margin-left: 88px;width: 80%; color:#fff;">For the first time ever, you can get the preview of your app while you are creating it!</p>

                                        <div class="banner_button_img">
                                            <div class="banner_button_box">
                                                <a href="https://play.google.com/store/apps/details?id=com.pulp.wizard" target="_blank"><img src="images/gPlay.png" alt="not found"></a>
                                            </div>
                                            <div class="banner_button_box">
                                                <a href="https://itunes.apple.com/us/app/instappy/id1053874135?mt=8" target="_blank"><img src="images/appstore.png" alt="not found"></a>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="congrats_btns">
                                        <!--<a  href="#" class="free_down" id="appcreate" >Download Trial</a>-->
                                        
                                    
                                        <?php                                     
                                         if($_SESSION['reseller_id']){                                            
                                            if($app_data['reseller_app_qa']==0){?>
                                                    <a id="reseller_app_qa" href="javascript:void(0);">Push App To QA</a>
                                               <?php  }?>
                                                    <a id="reseller_link" href="logout.php?reseller_id=<?php echo $_SESSION['reseller_id']; ?>">Back To Dashboard</a>
                                                    <?php }

                                              else if($_SESSION['cust_reseller_id']){
                                                $reseller_details=$login->reseller_details($_SESSION['cust_reseller_id']);
                                                  if($app_details['reseller_app_qa']==0){?>
                                                  <a id="reseller_app_qa" href="javascript:void(0);">Push App To QA</a>
                                                  <?php  }?>
                                                 <a id="reseller_link" href="mailto:<?php echo $reseller_details->email_address; ?>">Contact Ressller</a> 
                                             <?php }      
                                        else{?>
                                        <p>Or buy it, now</p>
                                        <a ia-track="IA101000201" href="#" class="buy" id="appcreate1" >Buy</a>

                                        <?php
                                        }
                                                    ?>
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
        $(".theme_head").css("background-color", "<?php
    if ($app_data['background_color'])
        echo $app_data['background_color'];
    else
        echo '#ff8800';
    ?>");
        var download = 0;

    //        $("#appcreate").click(function () {
    //            download = 1;
    //            createApp();
    //        });

        $("#appcreate1").click(function () {
            download = 2;
            createApp();
        });


        function createApp() {
            var platform = $("#platform option:selected").val();
            var appName = $("#appName").val();
            var appidCheck = $('#appid').val();
            var param = {'platform': platform, 'appName': appName};
            var form_data = {
                data: param, //your data being sent with ajax
                token: '<?php echo $token; ?>', //used token here1.
                themeid: '<?php echo $theme_id; ?>',
                catid: '<?php echo $categoryid; ?>',
                confirm: 'Yes',
                hasid: appidCheck,
                is_ajax: 1
            };

            $.ajax({
                type: "POST",
                url: 'modules/myapps/addtocart.php',
                data: form_data,
                success: function (response) {
                    if(response==1){
                        $(".confirm_name_form p").text('Please empty your Cart.');
$(".popup_container").show();
$(".confirm_name").show();
//                        alert('Please empty your Cart');
                    } else{
                    if (download == 2) {
                        location.href = BASEURL + "payment_details.php";
                    }
                    }
                },
                error: function () {
                    $(".cropcancel").trigger("click");
                    console.log("error in ajax call");
                    //alert("error in ajax call");
                }
            });

        }

    //        function createApp() {
    //            $.ajax({
    //                type: "POST",
    //                url: BASEURL + 'API/htmlparser.php/getData',
    //                data: "app_id=" + "<?php echo $app_id; ?>" + "&themeId=1",
    //                success: function (response) {
    //
    //                    if (download == 2) {
    //
    //                        location.href = BASEURL + "payment_details.php";
    //                    }
    //                }
    //            });
    //
    //        }

    </script>
    <?php
}
else {
     $screenShot='';
    $appType=1;
    $appID = $_SESSION['appid'];

    require_once('modules/apphtml/apphtml.php');
    $username = $_SESSION['username'];

    $custid = $_SESSION['custid'];

    $save = new Saveapphtml();
    $app_details=   $obj->get_app_details($appID);
    if ($custid != '') {

        $author = $save->check_user_exist($username, $custid);

        if ($appID != '') {

            $showHtml = 1;
        }
    } else {
        echo "<script>alert('Please login first');window.location.href='" . $basicUrl . "'</script>";
        exit();
    }
     $pageScreen = $webscreen->app_screenshot($appID);
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
                            <div class="mid_right_box_body">
                                <div class="congrats">
                                    <h1>Thank You!</h1>
                                    <h2>Looks like you are a pro!</h2>
                                    <p>Your app is ready to be published for Android as well as iOS devices. <br/>You can preview it with the Instappy Wizard before you go ahead and purchase it.</p>
                                    <div class="congrats_banner" style="background-color:#ffcc00">

                                        <img src= "<?php echo $basicUrl; ?>images/wizard-icon.png" alt="not found">
                                        <h3 style="color:#fff;">Download the Instappy Wizard</h3>
                                        <p style="margin-left: 124px;width: 68%; color:#fff;">For the first time ever, you can get the preview of your app while you are creating it!</p>

                                        <div class="banner_button_img">
                                            <div class="banner_button_box">
                                                <a href="https://play.google.com/store/apps/details?id=com.pulp.wizard" target="_blank"><img src="<?php echo $basicUrl; ?>images/gPlay.png" alt="not found"></a>
                                            </div>
                                            <div class="banner_button_box">
                                                <a href="https://itunes.apple.com/us/app/instappy/id1053874135?mt=8"><img src="<?php echo $basicUrl; ?>images/appstore.png" alt="not found"></a>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="congrats_btns">
                                        <!--<a  href="#" class="free_down" id="appcreate" >Download Trial</a>-->
                                       
                                        <?php

                                              if($_SESSION['reseller_id']){
                                                        if($app_data['reseller_app_qa']==0){?>
                                                            <a id="reseller_app_qa" href="javascript:void(0);">Push App To QA</a>
                                                       <?php  }?>
                                                        <a id="reseller_link" href="logout.php?reseller_id=<?php echo $_SESSION['reseller_id']; ?>">Back To Dashboard</a>
                                                <?php }
                                                else if($_SESSION['cust_reseller_id']){
                                                 $reseller_details=$login->reseller_details($_SESSION['cust_reseller_id']);
                                                    if($app_details['reseller_app_qa']==0){?>
                                                    <a id="reseller_app_qa" href="javascript:void(0);">Push App To QA</a>
                                               <?php  }
                                            if($_SESSION['is_reseller']){
                                               ?>
                                               <a id="reseller_link" href="logout.php?reseller_id=<?php echo $_SESSION['reseller_id']; ?>&is_reseller=<?php echo $_SESSION['is_reseller']; ?>">Dashboard</a>
                                                <?php }
                                                else{?>
                                                 <a id="reseller_link" href="mailto:<?php echo $reseller_details->email_address; ?>">Contact Reseller</a> 
                                             <?php }
                                             }  
                                    else{?>
                                        <p>Or buy it now !</p>
                                        <a ia-track="IA101000201" href="#" class="buy" id="appcreate1" >Buy</a>

                                    <?php
                                    }
                                                ?>

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
        <script>
            function createApp() {

                var appid = '<?php echo $appID; ?>';

                var form_data = {
                    token: '<?php echo $token; ?>', //used token here2.
                    hasid: appid,
                    is_ajax: 1
                };

                $.ajax({
                    type: "POST",
                    url: 'modules/myapp/appAddtocart.php',
                    data: form_data,
                    async: false,
                    success: function (response) {
                        if(response==1){
								$(".confirm_name_form p").text('Please empty your Cart.');
$(".popup_container").show();
$(".confirm_name").show();
                    } else{
                        if (download == 2) {
                            location.href = BASEURL + "payment_details.php";
                        }
                    }
                }
                });

            }

            $(document).ready(function () {
                $('#screenoverlay').css('display', 'block');
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
                $(".mobile nav").css("background-color", $(".theme_head").eq(1).css("background-color"))
            });

            var download = 0;

        //            $("#appcreate").click(function () {
        //                download = 1;
        //                createApp();
        //            });

            $("#appcreate1").click(function () {
                download = 2;
                createApp();
            });
            $(window).load(function () {
                $('#screenoverlay').fadeOut();
            })
        $("#reseller_app_qa").on('click',function(){

            $('#screenoverlay').css('display', 'block');
                        $.ajax({
                                type: "POST",
                                url: 'ajax.php',
                                data: {'type':'reseller_app_qa','app_id':'<?php echo $appID; ?>'},
                                success: function (response)
                                {
                                        if(response>0){
                                        $('#screenoverlay').css('display', 'none');
                                        $(".popup_container").css({'display': 'block'});
                                        $(".confirm_name .confirm_name_form").html('<p>App has been pushed in QA.</p><input type="button" value="OK">');
                                        $(".confirm_name").css({'display': 'block'});
                                        window.location="app-complete.php";
                                      }
                                      else{
                                        $('#screenoverlay').css('display', 'none');
                                        $(".popup_container").css({'display': 'block'});
                                        $(".confirm_name .confirm_name_form").html('<p>Oops something went wrong .Please Try again.</p><input type="button" value="OK">');
                                        $(".confirm_name").css({'display': 'block'});
                                      }
                                }
                            });

        });
        </script>
		<script type="text/javascript" src="js/trackuser.jquery.js?v=1.1"></script>
    <?php } ?>
	
    </body>
    </html>
<?php } ?>