<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');

require_once('modules/myapp/myapps.php');
$apps = new MyApps();

if ((!empty($_SESSION['token'])) || (!empty($_SESSION['appid'])) || (!empty($_SESSION['catid'])) || (!empty($_SESSION['themeid']))) {
    unset($_SESSION['token']);
    unset($_SESSION['appid']);
    unset($_SESSION['catid']);
    unset($_SESSION['themeid']);

    $token = md5(rand(1000, 9999)); //you can use any encryption
    $_SESSION['token'] = $token;
    $appidEdit = isset($_GET['appid']) ? $_GET['appid'] : '';
    $_SESSION['appid'] = $appidEdit;
} else {
    $token = md5(rand(1000, 9999)); //you can use any encryption
    $_SESSION['token'] = $token;
    $appidEdit = isset($_GET['appid']);
    $_SESSION['appid'] = $appidEdit;
}
?>
<style>
body{
	background:#fff;
}

</style>
<section class="main">
    <section class="right_main">
        <div class="right_inner">
            <div class="myapp_name_disc">
            <div class="rightButton"><a href="support.php" id="previewbutton">Support</a></div>
            <div class="rightButton"><a href="app-complete.php" id="previewbutton">Publish</a></div>
                <div class="myapp_name">
                    <h1> <?php
                            $appName = $apps->app_name(isset($_GET['appid']) ? $_GET['appid'] : '');
                            if($appName['summary']!=''){
                                echo $appName['summary'];
                            }?></h1>
                    <div class="apps_preview_edit" style="float: left;">
                        <div class="apps_preview_edit_left">
                            <?php
                            $publishedappImage = $apps->publish_app_img(isset($_GET['appid']) ? $_GET['appid'] : '');
                            if($publishedappImage!=''){
                                echo '<img src="'.$publishedappImage.'">';
                            } else{
                                echo '<img src="image/payment_app_icon.png">';
                            }
                            ?>
                            
                        </div>
                        <div class="apps_preview_edit_right">
                           
                            <p>Created On - <?php $createddate = $appName['created']; echo date("F j, Y", strtotime("$createddate"));?></p>
                            <p>Last Updated Date - <?php $updateddate = $appName['updated']; if($updateddate!=''){echo date("F j, Y", strtotime("$updateddate"));}?></p>
                            
                            <a href="javascript:void(0);" id="previewbutton">Preview</a>
                            <a href="javascript:void(0);" id="editbutton">Edit</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="up_qr_code_box">

                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php
                $apppublished = $apps->check_publish_app(isset($_GET['appid']) ? $_GET['appid'] : '');
                if ($apppublished == 1) {
                    ?>
                    <div class="myapp_qrcode">
                        <div class="generate_qrcode">
                            <div class="generate_qrcode_left">
                                <label>Platform :</label>
                            </div>
                            <div class="generate_qrcode_right">
                                <select class="my-select" id="platform">
                                    <option value="1" data-img-src="image/android_icon.png">Android</option>
                                    <option value="2" data-img-src="image/windows_icon.png">iOS</option>
                                    <option value="3" data-img-src="image/apple_icon.png">Windows</option>
                                </select>
                                <span class="define_platform">Define platform for which you want the QR Code to redirect to.</span>
                                <div class="clear"></div>
                            </div>
                            <div class="generate_qrcode_left">
                                <label>App Url:</label>
                            </div>
                            <div class="generate_qrcode_right">
                                <input type="text" class='appUrl' value="">
                                <span class="define_platform">Your application's publicly accessable hope page, where user can go to download, make use of, or find out more infomation about your application. This fully-qualified URL is used in the source attribute for tweets created by your application and will be shown in user-facing authorization screens.</span>
                                <div class="clear"></div>
                            </div>
                            <div class="generate_btn">
                                <a href="javascript:void(0)" id="generateQR">Generate QR Code</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!--                    <div class="qr_code_box">
                                                <div class="qr_code">
                                                    <img src="image/qr_code.png">
                                                    <p><img src="image/android_icon.png"> Android</p>
                                                </div>
                                                <div class="clear"></div>
                                            </div>-->
                        <div class="get_qrcode">
                            <a>Get QR Code</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <!--<div class="myapp_disc">
                    <h2>Description :</h2>
                    <p>Lorem ipsum dolor sit amet, at duo populo nostrum, mel an tibique postulant. No vis congue salutatus, dicat legere intellegat quo no. In graece luptatum quo, malorum posidonium ea per, amet sumo duo ex. Appetere mediocrem id eam. Ad regione fuisset usu, ex nec alii periculis. Assum dicit signiferumque cu his, ad has munere habemus comprehensam, verterem consequuntur te eum. Amet semper accumsan in eos, persius expetenda qui et.</p>
                    <p>Ne congue quando contentiones ius. Legendos reformidans ex usu, at qui lorem graeci, tacimates theophrastus est ne. At aeterno percipit torquatos sit, apeirian abhorreant ex mea, eripuit hendrerit eloquentiam mei ei. Mei ut theophrastus definitiones. An nulla iisque sed.</p>
                </div>-->
            </div>
           <!-- <div class="current_pack">
                <h2>Your trial ends in 10 days.</h2>
                <p>Please select a new plan.</p>

                <p>Lorem ipsum dolor sit amet, at duo populo nostrum, mel an tibique postulant. No vis congue salutatus, dicat legere intellegat quo no. In graece luptatum quo, malorum posidonium ea per, amet sumo duo ex. Appetere mediocrem id eam. Ad regione fuisset usu, ex nec alii periculis. Assum dicit signiferumque cu his, ad has munere habemus comprehensam, verterem consequuntur te eum. Amet semper accumsan in eos, persius expetenda qui et.</p>
                <p>Ne congue quando contentiones ius. Legendos reformidans ex usu, at qui lorem graeci, tacimates theophrastus est ne. At aeterno percipit torquatos sit, apeirian abhorreant ex mea, eripuit hendrerit eloquentiam mei ei. Mei ut theophrastus definitiones. An nulla iisque sed.</p>
            </div>-->
            <!--<div class="next_plans">
                <div class="next_plans_list">
                    <h2>Standard plan</h2>
                    <p>Key features to get started on mobile</p>
                    <div class="plan_price">
                        <span class="currency">$USD</span>
                        16
                        <span class="per_month">per month</span>
                    </div>
                    <em>Billed annually, or $USD 20 month-to-month</em>
                    <a href="#">Select</a>
                    <div class="clear"></div>
                    <div class="whats_included">
                        <h3>What's included:</h3>
                        <div class="includes_group">
                            <p>Mobile app (iOS + Android)</p>
                            <p>-</p>
                            <p>HTML5 web app</p>
                        </div>
                        <div class="includes_group">
                            <p>Unlimited installs</p>
                            <p>Unlimited launches</p>
                        </div>
                        <div class="includes_group">
                            <p>5000 push notifications/mo</p>
                            <p>-</p>
                        </div>
                        <div class="includes_group">
                            <p>-</p>
                            <p>-</p>
                        </div>
                        <div class="includes_group">
                            <p>-</p>
                        </div>
                        <div class="includes_group">
                            <p>-</p>
                            <p>-</p>
                        </div>
                        <div class="includes_group">
                            <p>-</p>
                        </div>
                    </div>
                </div>
                <div class="next_plans_list">
                    <h2>Full plan</h2>
                    <p>All the features to build a comprehensive mobile app</p>
                    <div class="plan_price">
                        <span class="currency">$USD</span>
                        32
                        <span class="per_month">per month</span>
                    </div>
                    <em>Billed annually, or $USD 40 month-to-month</em>
                    <a href="#">Select</a>
                    <div class="clear"></div>
                    <div class="whats_included">
                        <h3>What's included:</h3>
                        <div class="includes_group">
                            <p>Mobile app (iOS + Android)</p>
                            <p>-</p>
                            <p>HTML5 web app</p>
                        </div>
                        <div class="includes_group">
                            <p>Unlimited installs</p>
                            <p>Unlimited launches</p>
                        </div>
                        <div class="includes_group">
                            <p>Unlimited push notifications</p>
                            <p>Automatic push notifications</p>
                        </div>
                        <div class="includes_group">
                            <p>Built-in CMS</p>
                            <p>User generated content</p>
                        </div>
                        <div class="includes_group">
                            <p>Team management</p>
                        </div>
                        <div class="includes_group">
                            <p>My themes (save 6 designs)</p>
                            <p>-</p>
                        </div>
                        <div class="includes_group">
                            <p>Internal ad network</p>
                        </div>
                    </div>
                </div>
                <div class="next_plans_list">
                    <h2>Advanced plan</h2>
                    <p>Extended features for mobile &amp; tablet</p>
                    <div class="plan_price">
                        <span class="currency">$USD</span>
                        48
                        <span class="per_month">per month</span>
                    </div>
                    <em>Billed annually, or $USD 60 month-to-month</em>
                    <a href="#">Select</a>
                    <div class="clear"></div>
                    <div class="whats_included">
                        <h3>What's included:</h3>
                        <div class="includes_group">
                            <p>Mobile app (iOS + Android)</p>
                            <p>-</p>
                            <p>HTML5 web app</p>
                        </div>
                        <div class="includes_group">
                            <p>Unlimited installs</p>
                            <p>Unlimited launches</p>
                        </div>
                        <div class="includes_group">
                            <p>Unlimited push notifications</p>
                            <p>Automatic push notifications</p>
                        </div>
                        <div class="includes_group">
                            <p>Built-in CMS</p>
                            <p>User generated content</p>
                        </div>
                        <div class="includes_group">
                            <p>Team management</p>
                        </div>
                        <div class="includes_group">
                            <p>My themes (save 6 designs)</p>
                            <p>Adaptive design for tablets</p>
                        </div>
                        <div class="includes_group">
                            <p>Internal ad network</p>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </section>
</section>
</section>
<script>
    function pageloadQR() {
        var appid = '<?php echo $appidEdit; ?>';
        var sessiontoken = '<?php echo $token; ?>';
        var param = {'appidQRPL': appid};

        var form_data = {
            data: param, //your data being sent with ajax
            token: sessiontoken, //used token here.
            is_ajax: 1
        };

        $.ajax({
            type: "POST",
            url: 'modules/myapp/generateQRcodePL.php',
            data: form_data,
            success: function (response)
            {
//                alert(response);
                if (response) {
                    $(".up_qr_code_box").html(response);
                } else if (response) {
                    console.log(response);
                }
            },
            error: function () {
                console.log("error in ajax call");
                alert("error in ajax call");
            }
        });
    }
    $(document).ready(function () {

        pageloadQR();

        $(".get_qrcode a").click(function () {
            $(this).parent().css("display", "none");
            $(".qr_code_box").css("display", "none");
            $(".generate_qrcode").fadeIn();
        });
        $(".generate_btn a").click(function () {
            $(this).parent().parent().css("display", "none");
            $(".qr_code_box").fadeIn();
            $(".get_qrcode").fadeIn();
        });
        /*var rightHeight = $(window).height() - 35;
        $(".right_main").css("height", rightHeight + "px")*/


        $("#editbutton").click(function () {

            var appid = '<?php echo $appidEdit; ?>';
            var sessiontoken = '<?php echo $token; ?>';
            var param = {'appidedit': appid};

            var form_data = {
                data: param, //your data being sent with ajax
                token: sessiontoken, //used token here.                                                            
                is_ajax: 1
            };

            $.ajax({
                type: "POST",
                url: 'modules/checkapp/editapp.php',
                data: form_data,
                success: function (response)
                {
                    //                    alert(response);
                    if (response == 1) {
                        window.location = "panel.php?app=edit";
                    } else if (response != 1) {
                        console.log(response);
                    }
                },
                error: function () {
                    console.log("error in ajax call");
                    alert("error in ajax call");
                }
            });
        });
        $("#previewbutton").click(function () {

            var appid = '<?php echo $appidEdit; ?>';
            var sessiontoken = '<?php echo $token; ?>';
            var param = {'appidedit': appid};

            var form_data = {
                data: param, //your data being sent with ajax
                token: sessiontoken, //used token here.                                                            
                is_ajax: 1
            };

            $.ajax({
                type: "POST",
                url: 'modules/checkapp/editapp.php',
                data: form_data,
                success: function (response)
                {
                    //                    alert(response);
                    if (response == 1) {
                        window.location = "panel.php?app=edit&page=preview";                       
                    } else if (response != 1) {
                        console.log(response);
                    }
                },
                error: function () {
                    console.log("error in ajax call");
//                    alert("error in ajax call");
                }
            });
        });
		
//        $("#previewbutton").click(function () {
//
//            var appid = '<?php echo $appidEdit; ?>';
//            var sessiontoken = '<?php echo $token; ?>';
//            var param = {'appidedit': appid};
//
//            var form_data = {
//                data: param, //your data being sent with ajax
//                token: sessiontoken, //used token here.                                                            
//                is_ajax: 1
//            };
//
//            $.ajax({
//                type: "POST",
//                url: 'modules/checkapp/editapp.php',
//                data: form_data,
//                success: function (response)
//                {
//                    //                    alert(response);
//                    if (response == 1) {
//                        window.location = "panel.php?app=preview";
//                    } else if (response != 1) {
//                        console.log(response);
//                    }
//                },
//                error: function () {
//                    console.log("error in ajax call");
//                    alert("error in ajax call");
//                }
//            });
//        });

        $("#generateQR").click(function () {
            $(".popup_container").css("display", "block");
            $("#page_ajax").html('<img src="images/ajax-loader.gif">');
            var platform = $("#platform option:selected").val();
            var appurl = $(".appUrl").val();
            var appid = '<?php echo $appidEdit; ?>';
            var sessiontoken = '<?php echo $token; ?>';
            var param = {'appidQR': appid, 'platform': platform};

            var form_data = {
                data: param, //your data being sent with ajax
                token: sessiontoken, //used token here.                                                            
                appurl: appurl,
                is_ajax: 1
            };

            $.ajax({
                type: "POST",
                url: 'modules/myapp/generateQRcode.php',
                data: form_data,
                success: function (response)
                {
//                    alert(response);
                    if (response) {
                        pageloadQR();
                        $(".popup_container").css("display", "none");
                        $("#page_ajax").html('');
//                        window.location.reload(true);
//                        console.log(response);

                    } else if (response) {
                        console.log(response);
                        $(".popup_container").css("display", "none");
                        $("#page_ajax").html('');
                    }
                },
                error: function () {
                    $(".popup_container").css("display", "none");
                    $("#page_ajax").html('');
                    console.log("error in ajax call");
                    alert("error in ajax call");
                }
            });
        });
    });
</script>
</body>
</html>
