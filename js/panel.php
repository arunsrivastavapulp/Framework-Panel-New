<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/checkapp/checkappclass.php');
$checkapp = new checkapp();
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$appname = '';
$apppage = isset($_GET['app']) ? $_GET['app'] : '';
$preview = isset($_GET['page']) ? $_GET['page'] : '';
if ($apppage == "edit") {
    $appID = $_SESSION['appid'];

    $appname = $checkapp->myappname($appID);
} else if ($apppage == "create") {
    unset($_SESSION['appid']);
    $appID = 0;
    $_SESSION['catid'] = isset($_GET['catid']) ? $_GET['catid'] : '';
    $_SESSION['themeid'] = isset($_GET['themeid']) ? $_GET['themeid'] : '';

    $htmldata = $checkapp->getthemeHtml($_SESSION['themeid']);

    if ((isset($_GET['themeid'])) && ($_GET['themeid'] != '')) {

        $catid = isset($_GET['catid']) ? $_GET['catid'] : '';
        $themeid = isset($_GET['themeid']) ? $_GET['themeid'] : '';
        $useripadd = $_SERVER['REMOTE_ADDR'];

        $checkapp->paneltracking($useripadd, $catid, $themeid);
//         printf("%d Row inserted.\n", $mysqli->affected_rows);
    }
}

$categoryid = $_SESSION['catid'];
$theme_id = $_SESSION['themeid'];
?>

<link href="css/cropper.css" rel="stylesheet">
<link href="css/main.css" rel="stylesheet">
<style type="text/css">
    .modal-open{overflow:hidden}
    .modal{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1050;display:none;overflow:hidden;-webkit-overflow-scrolling:touch;outline:0}
    .modal.fade .modal-dialog{-webkit-transition:-webkit-transform .3s ease-out;-o-transition:-o-transform .3s ease-out; transition:transform .3s ease-out;-webkit-transform:translate(0,-25%);-ms-transform:translate(0,-25%);-o-transform:translate(0,-25%);transform:translate(0,-25%)}
    .modal.in .modal-dialog{-webkit-transform:translate(0,0);-ms-transform:translate(0,0); -o-transform:translate(0,0);transform:translate(0,0)}
    .modal-open .modal{overflow-x:hidden;overflow-y:auto}
    .modal-dialog{position:relative;width:auto;margin:10px}
    .modal-content{position:relative;background-color:#fff;-webkit-background-clip:padding-box;background-clip:padding-box;border:1px solid #999;border:1px solid rgba(0,0,0,.2);border-radius:0px;outline:0;-webkit-box-shadow:0 3px 9px rgba(0,0,0,.5);box-shadow:0 3px 9px rgba(0,0,0,.5)}
    .modal-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1040;background-color:#000}
    .modal-backdrop.fade{filter:alpha(opacity=0);opacity:0}
    .modal-backdrop.in{filter:alpha(opacity=50);opacity:.5}
    .modal-header{min-height:16.43px;padding:15px;border-bottom:1px solid #e5e5e5}
    .modal-header .close{margin-top:-2px}
    .modal-title{margin:0;line-height:1.42857143}
    .modal-body{position:relative;padding:15px}
    .modal-footer{padding:15px;text-align:right;border-top:1px solid #e5e5e5}
    .modal-footer .btn+.btn{margin-bottom:0;margin-left:5px}
    .modal-footer .btn-group .btn+.btn{margin-left:-1px}
    .modal-footer .btn-block+.btn-block{margin-left:0}
    .modal-scrollbar-measure{position:absolute;top:-9999px;width:50px;height:50px;overflow:scroll}
    .alert-dismissable .close,.alert-dismissible .close{position:relative;top:-2px;right:-21px;color:inherit}
    .close{float:right;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2}
    .close:focus,.close:hover{color:#000;text-decoration:none;cursor:pointer;filter:alpha(opacity=50);opacity:.5}
    .close{-webkit-appearance:none;padding:0;cursor:pointer;background:0 0;border:0}
    .bannercropdiv .img-container .cropper-crop-box{
        height: 400px !important;
    }

    .bannercropdiv .modal-dialog {
        width: 70% !important;
        margin: 10px auto;
    }

    a.make_app_next2 {
        text-transform: uppercase;
        text-decoration: none;
        font-weight: 300;
        float: right;
        margin-top: 35px;
        padding: 5px 10px;
        font-size: 14px;
        background: #ffcc00;
        color: #FFF;
    }
</style>
<script type="text/javascript">

    function checkAppName() {
        var y = 0;

        if (($("#appName").val()) != '') {
            y = 1;
        } else {
            y = 0;
        }

        return y;
    }
    function mail_confirm() {
        var x = 0;
        $.ajax({
            type: "POST",
            url: "login.php",
            async: false,
            data: "check=mail_confirm",
            success: function (response) {
                if (response) {
                    x = response;
                } else {
                    x = 0;
                }
            },
        });
        return x;
    }


    function saveAppPanel(click_button) {
        var currentClick = $(this);
        $(".popup_container").css("display", "block");
        $("#page_ajax").html('<img src="images/ajax-loader.gif">');
        $.ajax({
            type: "POST",
            url: "login.php",
            data: "check=login",
            success: function (response) {
                var responsediv = response.split("##");
                if (responsediv[0] != '') {

                    var mailConfirm = mail_confirm();
                    if (mailConfirm > 0) {
                        $(".cropcancel").trigger("click");
                        $(".confirm_name .close_popup").css({'display': 'none'});
                        $(".confirm_name_form").html('<p>Please complete your profile to proceed.</p><input type="button" value="OK">');
                        $(".confirm_name").css({'display': 'block'});
                        $(".close_popup").css({'display': 'block'});
                        $(".popup_container").css({'display': 'block'});
                        $("#page_ajax").html('');
                        setTimeout(function () {
                            window.location.href = BASEURL + 'userprofile.php';
                        }, 2000);


                    } else {

                        if (responsediv[1] == '') {
                            if (($("#appName").val()) != '') {
                                $(".savingDetails").css('display', 'block');
                                var platform = $("#platform option:selected").val();
                                var appName = $("#appName").val();
                                var appidCheck = $('#appid').val();
                                var param = {'platform': platform, 'appName': appName};
                                var form_data = {
                                    data: param, //your data being sent with ajax
                                    token: '<?php echo $token; ?>', //used token here.
                                    themeid: '<?php echo $theme_id; ?>',
                                    catid: '<?php echo $categoryid; ?>',
                                    confirm: 'Yes',
                                    hasid: appidCheck,
                                    is_ajax: 1
                                };
//                            console.log(form_data);

                                $.ajax({
                                    type: "POST",
                                    url: 'modules/checkapp/checkappname.php',
                                    data: form_data,
                                    async: false,
                                    success: function (response2)
                                    {
                                        $(".savingDetails").css('display', 'none');
                                        if (response2 == 1) {
                                            $(".cropcancel").trigger("click");
                                            $(".confirm_name_form").html('<p>This app name already exist.<br>Please choose another name.</p><input type="button" value="OK">');
                                            $(".confirm_name").css({'display': 'block'});
                                            $(".popup_container").css({'display': 'block'});
                                            $("#page_ajax").html('');
                                        } else if (response2 != 1) {
                                            $(".confirm_app").css({'display': 'none'});
                                            $(".popup_container").css({'display': 'none'});
                                            $("#page_ajax").html('');
                                            $('#appid').val(response2);
                                            $(".save_hidden").trigger("click");
                                            if (click_button == "finish") {
                                                finish();
                                            } else {
                                                savehtml();
                                            }
                                            if (click_button == "crop") {
                                                editbrowse_cropimgFunction();
                                            }
                                            $(".cropcancel").trigger("click");
                                            $(currentClick).parents('.mid_right_box').next().find('.mid_right_box_head').trigger('click');
                                            //$(".mid_right_box:first").find(".make_app_next").trigger("click");
                                            //alert('check val');
                                            $(".savedDetails").css('display', 'block');
                                            setTimeout(function () {
                                                $(".savedDetails").css('display', 'none');
                                            }, 2000);
                                        }

                                    },
                                    error: function () {
                                        $(".cropcancel").trigger("click");
                                        console.log("error in ajax call");
                                        //alert("error in ajax call");
                                    }
                                });
                            } else {
                                $(".cropcancel").trigger("click");
                                $("#page_ajax").html('');
                                $(".popup_container").css({'display': 'block'});
                                $(".confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
                                $(".confirm_name").css({'display': 'block'});
                                return false;
                            }
                        } else {
                            $(".save_hidden").trigger("click");
                            if (click_button == "finish") {
                                finish();
                            } else {
                                savehtml();
                            }
                            if (click_button == "crop") {
                                editbrowse_cropimgFunction();
                            }
                            $(".cropcancel").trigger("click");
                        }

                    }

                } else {
                    $(".cropcancel").trigger("click");
                    $("#login_type").val("");
                    $("#login_type").val("page");
                    $("#login_email").val('');
                    $("#login_password").val('');
                    $("#register_email").val('');
                    $("#register_password").val('');
                    $("#register_repeat_password").val('');
                    $(".popup_container").css("display", "block");
                    $(".login_popup").css("display", "block");
                    $("#email_error").text("");
                    $("#login_email").css("border", "1px solid #e5e5e5");
                    $("#login_password").css("border", "1px solid #e5e5e5");
                    $("#register_email").css("border", "1px solid #e5e5e5");
                    $("#register_password").css("border", "1px solid #e5e5e5");
                    $("#register_repeat_password").css("border", "1px solid #e5e5e5");
                    $("#remail_error").text('');
                    $("#page_ajax").html('');
                    return false;
                }
            }
        });

        return false;
    }

    function addnewAppName() {
        if (($("#appName").val()) != '') {
            var platform = $("#platform option:selected").val();
            var appName = $("#appName").val();
            var appidCheck = $('#appid').val();
            var param = {'platform': platform, 'appName': appName};
            var form_data = {
                data: param, //your data being sent with ajax
                token: '<?php echo $token; ?>', //used token here.
                themeid: '<?php echo $theme_id; ?>',
                catid: '<?php echo $categoryid; ?>',
                confirm: 'Yes',
                hasid: appidCheck,
                is_ajax: 1
            };
            //                            console.log(form_data);

            $.ajax({
                type: "POST",
                url: 'modules/checkapp/checkappname.php',
                data: form_data,
                async: false,
                success: function (response2)
                {
                    //              alert(response);
                    if (response2 == 1) {
                        $(".cropcancel").trigger("click");
                        $(".confirm_name_form").html('<p>This app name already exist.<br>Please choose another name.</p><input type="button" value="OK">');
                        $(".confirm_name").css({'display': 'block'});
                        $(".popup_container").css({'display': 'block'});
                        $("#page_ajax").html('');
                        return false;
                    } else if (response2 != 1) {
                        $(".cropcancel").trigger("click");
                        $('#app_id').val(response2);
                        $(".save_hidden").trigger("click");
                        savehtml();
                        $(".confirm_app").css({'display': 'none'});
                        $(".popup_container").css({'display': 'none'});
                        $("#page_ajax").html('');

                        //                            alert('check val');
                    }
                },
                error: function () {
                    console.log("error in ajax call");
                    $(".confirm_name").css({'display': 'block'});
                    $(".popup_container").css({'display': 'block'});
                    $("#page_ajax").html('');
                    return false;
                    //alert("error in ajax call");
                }
            });

        }
        else {
            $(".cropcancel").trigger("click");
            $("#page_ajax").html('');
            $(".popup_container").css({'display': 'block'});
            $(".confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
            $(".confirm_name").css({'display': 'block'});
            return false;

        }
    }
    function addnewAppNameOnClick() {
        if (($("#appName").val()) != '') {
            var platform = $("#platform option:selected").val();
            var appName = $("#appName").val();
            var appidCheck = $('#appid').val();
            var param = {'platform': platform, 'appName': appName};
            var form_data = {
                data: param, //your data being sent with ajax
                token: '<?php echo $token; ?>', //used token here.
                themeid: '<?php echo $theme_id; ?>',
                catid: '<?php echo $categoryid; ?>',
                confirm: 'Yes',
                hasid: appidCheck,
                is_ajax: 3
            };
            //                            console.log(form_data);

            $.ajax({
                type: "POST",
                url: 'modules/checkapp/checkappname.php',
                data: form_data,
                async: false,
                success: function (response3)
                {
                    //                    alert(response);
                    if (response3 != 1) {
                        $(".cropcancel").trigger("click");
                        $('#app_id').val(response3);
                        $(".save_hidden").trigger("click");
                        savehtml();
                        $(".confirm_app").css({'display': 'block'});
                        $(".popup_container").css({'display': 'none'});
                        $("#page_ajax").html('');
                    }
                },
                error: function () {
                    $(".cropcancel").trigger("click");
                    console.log("error in ajax call");
                    $(".confirm_name").css({'display': 'block'});
                    $(".popup_container").css({'display': 'block'});
                    $("#page_ajax").html('');
                    //alert("error in ajax call");
                }
            });
        }
        else {
            $(".cropcancel").trigger("click");
            $("#page_ajax").html('');
            $(".popup_container").css({'display': 'block'});
            $(".confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
            $(".confirm_name").css({'display': 'block'});
            return false;
        }
    }

</script>
<section class="main">
    <header>
        <ul class="select-phone">
            <li>
                <select class="select_os" id="platform" name="platform">               
                    <option value="1" data-img-src="<?php echo $basicUrl; ?>images/select_os.png">Android</option>
                    <option value="2" data-img-src="<?php echo $basicUrl; ?>images/select_os.png">iOS</option>
                    <!--<option value="3" data-img-src="<?php echo $basicUrl; ?>images/select_os.png">Windows</option>-->
                </select>
                <div class="clear"></div>
            </li>
        </ul>

        <div class="select-page">
            <label>Screen : </label>
            <select id="pageSelector">
                <?php
//                $apppage = $checkapp->myapppages($appID);
//                foreach ($apppage as $key => $value) {
//                    echo '<option selected="selected" value="' . $value['id'] . '">' . $value['title'] . '</option>';
//                }
//                print_r($apppage);
                ?>
            </select>
        </div>
        <ul class="top-aside">
            <li><a href="index.php">Create New<i class="fa fa-rocket"></i></a></li>
            <li class="save"><a href="#" id="savehtml">Save <i class="fa fa-cloud-upload"></i></a></li>
            <li><a id="finish">Finish <i class="fa fa-sign-in"></i></a></li>
        </ul>
    </header>
    <section class="middle clear">
        <div class="right-area">
            <div class="pageIndexArea">
                <div class="pageIndexdiv">
                    <img src="<?php echo $basicUrl; ?>images/instappy.png" alt="">
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
            <div class="choiceArea"> 
                <div class="previewEditArea">


                    <p class="preview"><i class="fa fa-search"></i> Preview</p><p class="edit active"><i class="fa fa-pencil"></i> Edit</p>
                </div>

                <div class="additional_options">
                    <span></span>
                    <div class="additional_items information">
                        <img src="<?php echo $basicUrl; ?>images/info1.png" alt="Image Not Found">
                        <div class="tooltip">Information</div>
                    </div>
                    <div class="additional_items hint">
                        <img src="<?php echo $basicUrl; ?>images/info2.png" alt="Image Not Found">
                        <div class="tooltip">Hint</div>
                    </div>
                    <!--                    <div class="additional_items callus">
                                            <img src="<?php // echo $basicUrl;       ?>images/info3.png" alt="Image Not Found">
                                            <div class="tooltip">Call Us</div>
                                        </div>
                                        <div class="additional_items chat">
                                            <img src="<?php // echo $basicUrl;       ?>images/info4.png" alt="Image Not Found">
                                            <div class="tooltip">Chat</div>
                                        </div>-->
                    <div class="chat_window">
                        <div class="chat_window_head">
                            <p>Chat</p>
                            <a href="#" class="close_chat"><img src="<?php echo $basicUrl; ?>images/close_chat.png" alt="Image Not Found"></a>
                            <div class="clear"></div>
                        </div>
                        <div class="chat_window_body">
                        </div>
                        <div class="chat_window_inputs">
                            <input type="text">
                            <input type="button" value="">
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="crome_msg" style="text-align:right;"><p style="font-size:12px; color:#a9a9a9; margin-right:10px; margin-top:10px;">Best Viewed in Chrome</p></div>

            </div>
        </div>
        <div class="center-area clear">
            <div class="mid_section" id="content-2">
                <div class="mid_section_left"></div>
                <div class="mid_section_right">

                    <div class="mid_right_box active">
                        <div class="mid_right_box_head">
                            <h1 class="appNamesave">Name Your App<h2 class="savingDetails">Saving...</h2><h2 class="savedDetails">All changes have been saved.</h2></h1>
                            <h2>The app name you choose will be displayed in the store. Choosing the right name is the most important part of this process. Choose carefully, you cannot rename your app once it&apos;s launched. The app name should ideally be the name of your business or product. Think of how the users will search for your app. You can either go by your business name, or you can pick a universal and a catchy name. It is recommended that you choose a unique name for better in-store optimisation. And do remember to search and check the App Store to confirm that no other app exists by this name.</h2>
                            <a href="#" class="read_more">Read More...</a>
                            <div class="clear"></div>
                            <span> <i class="fa fa-angle-down fa-rotate-180"></i> </span>
                        </div>
                        <div class="mid_right_box_body">
                            <div class="clear"></div>
                            <div class="app_name">
                                <div class="app_name_label">
                                    <label>App Name</label>
                                </div>
                                <div class="app_name_textbox">
                                    <input type="text" name="appName" id="appName" value="<?php echo $appname; ?>" maxlength="30">
                                    <input type="hidden" name="appid" id="appid" value="<?php echo $appID; ?>">

                                    <span>Choose a unique name for your app. You can go creative with the name but before you finalise do a final check of whether it conveys how amazing your product is? Is it easy to pronounce? Is it even accurate to what your app does? Is it unique? If the answer is yes, go ahead! </span>
                                    <a href="#" class="read_more">Read More...</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <img src="<?php echo $basicUrl; ?>images/ajax-loader.gif" style="display: none" id="loderpanel"/>
                            <a href="javascript:void(0)" class="make_app_next2 saveAndcontinue">Save & Continue</a>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="mid_right_box">
                        <div class="mid_right_box_head ">
                            <h1 class="appDetailsave">Customise Your App Details<h2 class="savingDetails">Saving...</h2><h2 class="savedDetails">All changes have been saved.</h2></h1>
                            <h2>Wow! Looks like you got to the fun part. This is where you can start to customise your app. Start with changing the background colour of your app, colour of the title bar, choose your title text, and lots more. Remember soft pastels enhance great pictures, so your app background should ideally be a pastel.  You can also try multiple combinations before you finalise. So go ahead and experiment. </h2>
                            <a class="read_more">Read More...</a>
                            <div class="clear"></div>
                            <span> <i class="fa fa-angle-down"></i> </span>
                        </div>
                        <div class="mid_right_box_body GeneralDetails">
                            <div class="addPage">Add Screen</div>
                            <div class="design_menu_box Page">	
                                <h2>Screen Background</h2>
                                <div class="background_label">
                                    <label>Choose Background Colour</label>
                                </div>
                                <div class="background_colorbox">
                                    <span id="pagePicker"></span>
                                </div>

                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box actionBar">
                                <h2>Title Bar</h2>
                                <div class="background_label">
                                    <label>Choose Title Bar Colour:</label>
                                </div>
                                <div class="background_colorbox">
                                    <span class="actionBarPicker"></span>
                                </div>
                            </div>
                            <div class="design_menu_box navItemEdit">
                                <h2>Navigation Menu</h2>
                                <div class="menu_links">

                                    <ul class="menu_head">
                                        <li></li>
                                        <li>Icon</li>
                                        <li>Name</li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                    <ul class="menu_body_links nav_items_edit" data-ind="1">
                                        <li>
                                            <input type="checkbox" class="addToSlide">
                                        </li>
                                        <li>
                                            <img src="<?php echo $basicUrl; ?>images/menu_img.jpg" alt="Image Not Found" class="icon_upload_img1">
                                            <input type="file" class="icon_upload">
                                        </li>
                                        <li class="navText"><input type="text" class="nav_item" disabled="disabled" maxlength="15"></li>
                                        <li><input type="button" value="View &amp; Edit" class="viewScreen"></li>
                                        <li><i class="fa fa-trash deleteSlide"></i></li>
                                    </ul>
                                </div>
                                <!--<div class="add_list">
                                    <a>Add List</a>
                                </div>-->
                                <div class="feedback">
                                    <ul>
                                        <li><input type="checkbox" class="feedbackReq"></li>
                                        <li>
                                            <span class="icon_upload_img1 icon-feedback"></span>
                                            <input type="file" class="icon_upload">
                                        </li>
                                        <li class="two_text"><input type="text" value="Feedback" /><input type="text" placeholder="Email Id" /></li>
                                        <li class="view_feedback"><input type="button" value="View"></li>
                                        <li></li>
                                    </ul>
                                </div>
                                <div class="feedback report">
                                    <ul>
                                        <li><input type="checkbox" checked="checked" disabled="disabled"></li>
                                        <li>
                                            <span class="icon_upload_img1 icon-report"></span>
                                            <input type="file" class="icon_upload">
                                        </li>
                                        <li><input type="text" value="Report Misconduct" disabled="disabled" /></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                </div>




                                <div class="clear"></div>
                            </div>
                            <a href="javascript:void(0)" class="make_app_next2 saveAndcontinue">Save & Continue</a>

                        </div>
                        <div class="clear"></div>
                    </div> 
                    <div class="add_page">

                        <div class="mid_right_box">

                            <div class="mid_right_box_head">
                                <h1>Choose Layout</h1>
                                <h2>If you don’t have a specific layout in mind for your app, no worries. You can choose one of the following pre-defined layout that best fits your need, and watch the changes happen on the Instappy App Preview Simulator in real time.</h2>
                                <a href="#" class="read_more">Read More...</a>
                                <div class="clear"></div>
                                <span> <i class="fa fa-angle-down fa-rotate-180"></i> </span>
                            </div>
                            <div class="mid_right_box_body">
                                <div class="design_menu_box">
                                    <div class="content_label">
                                        <label>Screen Name :</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" id="newPageTextEdit" maxlength="18">
                                    </div>
                                    <div class="clear"></div>
                                    <div class="add_page_themes">
                                        <div class="new_theme" data-layout="1">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme6.png" alt="Theme 1">
                                        </div>
                                        <div class="new_theme" data-layout="10">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme1.png" alt="Theme 10">
                                        </div>
                                        <div class="new_theme" data-layout="8">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme2.png" alt="Theme 8">
                                        </div>
                                        <div class="new_theme" data-layout="11">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme3.png" alt="Theme 11">
                                        </div>
                                        <div class="new_theme" data-layout="12">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme4.png" alt="Theme 12">
                                        </div>
                                        <div class="new_theme" data-layout="6">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme7.png" alt="Theme 6">
                                        </div>
                                        <div class="new_theme" data-layout="7">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme5.png" alt="Theme 7">
                                        </div>
                                        <div class="new_theme" data-layout="3">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme8.png" alt="Theme 3">
                                        </div>
                                        <div class="new_theme" data-layout="9">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme9.png" alt="Theme 9">
                                        </div>
                                        <div class="new_theme" data-layout="2">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme10.png" alt="Theme 2">
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                </div>
                                <a href="javascript:void(0)" class="make_app_next2 saveAndcontinue">Save & Continue</a>

                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="mid_right_box hidden_box">
                        <div class="mid_right_box_head choose_card_tip">
                            <h1>Add More Card</h1>
                            <h2>Congratulations! You have reached the stage where you can start shaping up your app, and preview it on the Instappy App Preview Simulator to check if it is shaping out to be the way you want it to. Just in case you don’t like the way your app is shaping up, you can simply drag and re-position the elements on the Instappy App Preview Simulator to re-shape it.<br>These small screens are called Rich Media Cards. These small components fit together to create a screen. You can choose from a variety of cards to form different layouts.
                                .</h2>
                            <a href="#" class="read_more">Read More...</a>
                            <div class="clear"></div>
                            <span> <i class="fa fa-angle-down"></i> </span>
                        </div>
                        <div class="mid_right_box_body">
                            <div class="utility_api_cards">
                                <h2>Utility Cards</h2>
                                <p class="utility_cards">Utility Cards are predefined cards designed for your convenience. You can choose from a variety of cards on display, and drag them to the preview screen to see how they look. We’re are sure they will improve the look of your app and make it more user-friendly, so go ahead and try some.</p>
                                <a class="read_more">Read More...</a>
                                <div class="clear"></div>
                                <div class="utility_api_content clones container">

                                    <!-- Replacement Area Starts -->
                                    <div class="half_widget layout1 layout3 layout9" data-component="7">
                                        <p class="widgetClose">x</p>
                                        <div class="half_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_img.jpg" alt="Image Not Found">
                                            <img src="<?php echo $basicUrl; ?>images/video_img_half.png" alt="" class="video_img">


                                            <div class="clear"></div>
                                        </div>
                                        <div class="half_widget_text">
                                            <p>Heading</p>
                                        </div>
                                    </div>

                                    <div class="full_widget layout1 layout3 layout2" data-component="7" data-ss-colspan="2">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_full_img.jpg" alt="Image Not Found">
                                            <img src="<?php echo $basicUrl; ?>images/video_img_full.png" alt="" class="video_img">                                          
                                            <div class="clear"></div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p>Text</p>
                                        </div>
                                    </div>


                                    <div class="map_full_widget layout7" data-component="44" data-ss-colspan="2">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_full_map_img.jpg" alt="Image Not Found">
                                            <div class="clear"></div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p>Heading</p>
                                        </div>
                                    </div>


                                    <div class="half_widget no_text" data-component="19">
                                        <p class="widgetClose">x</p>
                                        <div class="half_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_img.jpg" alt="Image Not Found">
                                            <div class="clear"></div>
                                        </div>
                                        <div class="half_widget_text">
                                            <a href="#"><img src="<?php echo $basicUrl; ?>images/widget_share.png" alt="Image Not Found"></a>
                                            <a href="#"><img src="<?php echo $basicUrl; ?>images/widget_heart.png" alt="Image Not Found"></a>
                                        </div>
                                    </div>
                                    <div class="full_widget" data-ss-colspan="2" data-component="2">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_full_img.jpg" alt="Image Not Found">
                                            <img src="<?php echo $basicUrl; ?>images/video_img_full.png" alt="" class="video_img">
                                            <div class="clear"></div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p>Heading</p>
                                            <a href="#"><img src="<?php echo $basicUrl; ?>images/widget_heart_yellow.png" alt="Image Not Found"></a>
                                        </div>
                                    </div>
                                    <div class="full_widget head_cont layout11" data-ss-colspan="2" data-component="19">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_full_img.jpg" alt="Image Not Found">
                                            <img class="video_img" alt="" src="<?php echo $basicUrl; ?>images/video_img_full.png">
                                            <div class="clear"></div>
                                            <div class="full_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="full_widget_text">
                                            <a href="#"><img src="<?php echo $basicUrl; ?>images/widget_share.png" alt="Image Not Found"></a>
                                            <a href="#"><img src="<?php echo $basicUrl; ?>images/widget_heart.png" alt="Image Not Found"></a>
                                        </div>
                                    </div>
                                    <div class="full_widget long_text layout6" data-ss-colspan="2" data-component="13">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_full_img.jpg" alt="Image Not Found">
                                            <img class="video_img" alt="" src="<?php echo $basicUrl; ?>images/video_img_full.png">
                                            <div class="clear"></div>
                                            <div class="full_widget_img_text">
                                                <p class="img_heading">Heading1</p>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p class="long_text_subheading">Sub Heading</p>
                                            <p class="long_text_content">Content</p>
                                            <a href="#"><img src="<?php echo $basicUrl; ?>images/widget_share_small.png" alt="Image Not Found"></a>
                                            <a href="#"><img src="<?php echo $basicUrl; ?>images/widget_heart_small.png" alt="Image Not Found"></a>
                                        </div>
                                    </div>

                                    <div class="big_widget layout8" data-ss-colspan="2" data-component="14">
                                        <p class="widgetClose">x</p>
                                        <div class="big_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_big_img.jpg" alt="Image Not Found">
                                            <img class="video_img" alt="" src="<?php echo $basicUrl; ?>images/video_img_big.png">
                                            <div class="clear"></div>
                                            <div class="big_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="big_widget_img_controls">

                                                <a href="#" class="share"><img src="<?php echo $basicUrl; ?>images/share.png" alt="Image Not Found"></a>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="big_widget_text" data-ss-colspan="2" data-component="14">
                                        <p class="widgetClose">x</p>
                                        <div class="big_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_big_img.jpg" alt="Image Not Found">
                                            <img class="video_img" alt="" src="<?php echo $basicUrl; ?>images/video_img_big.png">
                                            <div class="clear"></div>
                                            <div class="big_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="big_widget_img_controls">

                                                <a href="#" class="share"><img src="<?php echo $basicUrl; ?>images/share.png" alt="Image Not Found"></a>
                                                <a href="#" class="share"><img src="<?php echo $basicUrl; ?>images/heart.png" alt="Image Not Found"></a>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="big_widget_bottom_text">
                                            <p class="text_heading">Heading</p>
                                            <p class="text_subheading">Subheading</p>
                                            <p class="text_content">content</p>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="big_widget_text about layout8" data-ss-colspan="2" data-component="14">
                                        <p class="widgetClose">x</p>
                                        <div class="big_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_big_img.jpg" alt="Image Not Found">
                                            <img class="video_img" alt="" src="<?php echo $basicUrl; ?>images/video_img_big.png">
                                            <div class="clear"></div>
                                            <div class="big_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="big_widget_img_controls">

                                                <a href="#" class="share"><img src="<?php echo $basicUrl; ?>images/share.png" alt="Image Not Found"></a>
                                                <a href="#" class="share"><img src="<?php echo $basicUrl; ?>images/heart.png" alt="Image Not Found"></a>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="big_widget_bottom_text">
                                            <p class="about">About Us</p>
                                            <div class="clear"></div>
                                        </div>
                                    </div>

                                    <div class="item_full_widget layout12" data-ss-colspan="2" data-component="9">
                                        <p class="widgetClose">x</p>
                                        <div class="item_full_widget_left">
                                            <img src="<?php echo $basicUrl; ?>images/item_add_img.png" alt="Image Not Found">
                                        </div>
                                        <div class="item_full_widget_right">
                                            <p class="item_heading">Heading</p>
                                            <p class="item_content">content</p>
                                            <div class="clear"></div>
                                            <p class="item_disc">Description</p>
                                        </div>
                                    </div>
                                    <div class="contact_widget layout8 layout7" data-ss-colspan="2" data-component="5">
                                        <p class="widgetClose">x</p>
                                        <p class="contactus">Heading</p>
                                        <p class="address">Description</p>
                                        <p class="phone">Footer</p>
                                    </div>
                                    <div class="contact_small_widget layout1 layout3 layout9" data-component="29">
                                        <p class="widgetClose">x</p>
                                        <p class="contact_small_heading">Heading</p>
                                        <p class="contact_small_subheading">Subheading</p>
                                        <div class="contact_btns">
                                            <a href="#" class="your_mail"><img src="<?php echo $basicUrl; ?>images/contact_mail.png" alt="Image Not Found"></a>
                                            <a href="#" class="your_phone"><img src="<?php echo $basicUrl; ?>images/contact_phone.png" alt="Image Not Found"></a>
                                        </div>
                                    </div>

                                    <div class="contact_img_widget" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <div class="contact_img_widget_left">
                                            <img src="<?php echo $basicUrl; ?>images/contact_img.png" alt="Image Not Found">
                                        </div>
                                        <div class="contact_img_widget_right">
                                            <p class="contact_img_heading">Heading</p>
                                            <p class="contact_img_subheading">Subheading</p>
                                        </div>
                                    </div>

                                    <div class="contact_img_widget full" data-ss-colspan="2" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <div class="contact_img_widget_left">
                                            <img src="<?php echo $basicUrl; ?>images/contact_img.png" alt="Image Not Found">
                                        </div>
                                        <div class="contact_img_widget_right">
                                            <p class="contact_img_heading">Heading</p>
                                            <p class="contact_img_subheading">Subheading</p>
                                        </div>
                                    </div>

                                    <div class="contact_img_widget call_mail layout1 layout3 layout9" data-component="29">
                                        <p class="widgetClose">x</p>
                                        <div class="contact_img_widget_left">
                                            <img src="<?php echo $basicUrl; ?>images/contact_img.png" alt="Image Not Found">
                                        </div>
                                        <div class="contact_img_widget_right">
                                            <p class="contact_img_heading">Heading</p>
                                            <p class="contact_img_subheading">Subheading</p>
                                            <div class="contact_btns">
                                                <a class="your_mail" href="#"><img alt="Image Not Found" src="<?php echo $basicUrl; ?>images/contact_mail.png"></a>
                                                <a class="your_phone" href="#"><img alt="Image Not Found" src="<?php echo $basicUrl; ?>images/contact_phone.png"></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="head_disc_widget layout8" data-ss-colspan="2" data-component="5">
                                        <p class="widgetClose">x</p>
                                        <div class="head_disc_content">
                                            <h2>Heading</h2>
                                            <p>Add Description</p>
                                        </div> 
                                    </div>
                                    <div class="tabbing_widget" data-ss-colspan="2" data-component="15">
                                        <div class="tabbing_widget_head">
                                            <ul class="tabs">
                                                <li class="active"><a>Tab 1</a></li>
                                                <li><a>Tab 2</a></li>
                                                <li><a>Tab 3</a></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                        <div class="tabbing_widget_body">
                                            <div class="tab_content" style="display:block;">
                                                <p>Add Description1</p>
                                            </div>
                                            <div class="tab_content" style="display:none;">
                                                <p>Add Description2</p>
                                            </div>
                                            <div class="tab_content" style="display:none;">
                                                <p>Add Description3</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contact_card layout1 layout3 layout9" data-component="30">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="contact_card_img">
                                                <!--<img src="<?php echo $basicUrl; ?>images/donation.png" alt="Image Not Found">-->
                                                <span class="icon-donation"></span>
                                            </div>
                                            <div class="contact_card_text">
                                                <p>Make a Donation</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>
                                    <div class="contact_card layout1 layout3 layout9" data-component="30">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="contact_card_img">
                                                <!--<img src="<?php echo $basicUrl; ?>images/booking.png" alt="Image Not Found">-->
                                                <span class="icon-table"></span>
                                            </div>
                                            <div class="contact_card_text">
                                                <p>Book a Table</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>
                                    <div class="contact_card layout1 layout3 layout9" data-component="30">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="contact_card_img">
                                                <!--<img src="<?php echo $basicUrl; ?>images/contact.png" alt="Image Not Found">-->
                                                <span class="icon-call"></span>
                                            </div>
                                            <div class="contact_card_text">
                                                <p>Call Us</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>
                                    <div class="contact_card layout1 layout3 layout9" data-component="30">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="contact_card_img">
                                                <!--<img src="<?php echo $basicUrl; ?>images/reservation.png" alt="Image Not Found">-->
                                                <span class="icon-reservation"></span>
                                            </div>
                                            <div class="contact_card_text">
                                                <p>Make a Reservation</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>
                                    <div class="contact_card layout1 layout3 layout9" data-component="30">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="contact_card_img">
                                                <!--<img src="<?php echo $basicUrl; ?>images/scooter.jpg" alt="Image Not Found">-->
                                                <span class="icon-delivery"></span>
                                            </div>
                                            <div class="contact_card_text">
                                                <p>Call For Delivery</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>



                                    <!-- Replacement Area Ends -->

                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="utility_api_cards">
                                <h2>Regular APIs</h2>
                                <p class="utility_cards">Regular API lets you display an icon of your social networking profile on your app, through which the user can follow you on your social network.</p>
                                <a class="read_more">Read More...</a>
                                <div class="clear"></div>
                                <div class="utility_api_content clones container">
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/facebook.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Like us on <br/><span>Facebook</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/twitter.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on <br/><span>Twitter</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/picasa.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Connect with us on <br/><span>Picasa</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/grubhub.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on <br/><span>GrubHub</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/quora.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on <br/><span>Quora</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/google.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Connect with us on <br/><span>Google<sup>+</sup></span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <!--<div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/tastykhana.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Tasty Khana</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>-->
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/flickr.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on <br/><span>Flickr</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/foodpanda.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on<br/><span>Food Panda</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>  
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/instagram.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on <br/><span>Instagram</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/zomato.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on <br/><span>Zomato</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/pinterest.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on <br/><span>Pinterest</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/youtube.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Subscribe to us <br/><span>YouTube</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/vimeo.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on <br/><span>Vimeo</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/linkedin.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Connect with us <br/> on <span>LinkedIn</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>



                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/dining_grades.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>Dining Grades</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/eat_local.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on<br><span>LocalEats</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/eat24.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on<br><span>Eat24</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/Foursquare.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p><span>Foursquare</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/Kayak.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on<br><span>Kayak</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/opentable.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on<br><span>Opentable</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/reataurant_finder.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>View us on<br><span>Restaurant Finder</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/tinyowl.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on<br /><span>TinyOwl</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/tripadvisor.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on<br><span>Trip Advisor</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/urbanspoon.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Recommend us on <br><span>Urban Spoon</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/yelp.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on<br><span>Yelp</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/zagat.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on<br><span>Zagat</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>



                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/agoda.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>Agoda</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/asiatravel.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on<br><span>Asiatravel</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/burrp.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>Burrp</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/ctrip.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>Ctrip</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/dineout.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on<br><span>Dineout</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/easydiner.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>Easydiner</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/expedia.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>Expedia</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/hotel.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on<br><span>Hotels</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/justdial.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>JustDial</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/justeat.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>Justeat</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/mydala.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>MyDala</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/otel.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on<br><span>Otel</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="<?php echo $basicUrl; ?>images/yatra.png" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on<br><span>Yatra</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>





                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="utility_api_cards">
                                <h2>Rich Media APIs</h2>
                                <p class="utility_cards">Rich Media API lets you display the content from your social networking profile to this app.</p>
                                <div class="utility_api_content clones container">
                                    <div class="social_feed layout1 layout8 layout9" data-component="42">
                                        <p class="widgetClose">x</p>

                                        <a href="#"><img src="images/FacebookFeed.jpg"></a>
                                    </div>
                                    <div class="social_feed layout1 layout8 layout9" data-component="43">
                                        <p class="widgetClose">x</p>
                                        <a href="#"><img src="images/TwitterFeed.jpg"></a>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mid_right_box change_properties">

                        <div class="mid_right_box_head">
                            <h1>Change Screen and Card Properties</h1> 
                            <h2>Customise Your Screens and Cards</h2>
                            <span> <i class="fa fa-angle-down"></i> </span>
                        </div>
                        <div class="mid_right_box_body">
                            <div class="design_menu_box screenDetails">
                                <div class="screen_details">
                                    <h3>Screen Details</h3>
                                </div>
                                <div class="content_label">
                                    <label>Screen Name</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" placeholder="Name of the page" class="screen_name" maxlength="18">
                                </div>
                            </div>
                            <h4 class="change_prop">Customise Your Cards</h4>
                            <p class="common_change_card">Each card that you create is fully customisable. You can at any time add or change the Heading, Image, Background Colour, Fonts, Embed a Video, and Create Links to these cards</p>


                            <div class="design_menu_box bannerEdit bannercropdiv">

                                <h2>Add Customised Banners</h2>
                                <p>Give your app a trendy look by using up to 3 banner images. Banner images will be the head of your app, and should contain the featured services that you offer.<br>Though you can use image in any aspect ratio, and Instappy will crop it to best fit the screen. However, it is recommended that you use images which are 1080 x 430 pixels, and not more than 2 MB in size.</p>

                                <div class="change_image">
                                    <input type="file" id="editbrowse_img1">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="appbannereditbrowse" alt="Image Not Found">
                                    <span>Image 1</span>
                                </div>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img2">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="appbannereditbrowse" alt="Image Not Found">
                                    <span>Image 2</span>
                                </div>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img3">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="appbannereditbrowse" alt="Image Not Found">
                                    <span>Image 3</span>
                                </div>
                                <div class="clear"></div>

                                <div id="canvasShow" style="display: none;"></div>
                                <input type="hidden" name="filenamest" id="filenamest"/>
                                <input type="hidden" name="filetypest" id="filetypest"/>
                                <a id="openModalW" data-target="#cropper-example-2-modal" data-toggle="modal" style="opacity:0.11;" >&nbsp;</a>

                                <div class="modal fade" id="cropper-example-2-modal">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <a data-dismiss="modal" style="float: right;cursor: pointer; cursor: hand;">X</a>
                                                <h4 id="bootstrap-modal-label" class="modal-title">Cropper</h4>
                                            </div>

                                            <div class="modal-body">                                                
                                                <input class="sr-only inputImageChange" id="inputImage" name="file" type="file">

                                                <div id="cropper-example-2" class="img-container">
                                                    <img src="" class="" id="modalimage1"  alt="Choose Image">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a class="make_app_next cropcancel" style="float: none;cursor: pointer; cursor: hand;" data-dismiss="modal">Cancel</a>
                                                <div style="float: right; width: 10px;">&nbsp;</div>
                                                <a id="getcropped" style="float: none;cursor: pointer; cursor: hand;" class="make_app_next" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 1080, &quot;height&quot;: 424 }" >Crop & Save</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="design_menu_box widgetEdit fullItemEdit">

                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <div class="design_menu_box contactImgEdit">

                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="contactPicker"></span>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <div class="design_menu_box mapwidgetEdit">
                                <h2>Location Details</h2>
                                <p>This can be one of the coolest features on your app. Just add the coordinates of your location, and your consumers will be able to see exactly where you are. This makes it easier for them to locate you.</p>
                                <div class="content_label">
                                    <label>Latitude:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="latitude_edit" maxlength="20">
                                </div>
                                <div class="content_label">
                                    <label>Longitude:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="longitude_edit" maxlength="20">
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box widgetEdit mapwidgetEdit">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Headline:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit" maxlength="20">
                                    <select class="content_font">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--<a href="#"><b class="content_bold">B</b></a>
                                    <a href="#"><i class="content_italics">I</i></a>
                                    <a href="#"><u class="content_underline">U</u></a>-->
                                    <span class="editPicker"></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box headContWidgetEdit">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit1" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
                                    <select class="content_font1">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize1">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold1">B</b></a>
                                                                        <a href="#"><i class="content_italics1">I</i></a>
                                                                        <a href="#"><u class="content_underline1">U</u></a>-->
                                    <span class="editPicker6"></span>
                                </div>
                                <!--<div class="content_label">
                                    <label>Description:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit2" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
                                    <select class="content_font2">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize2">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                                                   <a href="#"><b class="content_bold2">B</b></a>
                                                                        <a href="#"><i class="content_italics2">I</i></a>
                                                                        <a href="#"><u class="content_underline2">U</u></a>
                                    <span class="editPicker7"></span>
                                </div>-->
                                <div class="clear"></div>
                            </div>

                            <div class="design_menu_box smallWidget">
                                <h2>Edit API Card</h2>
                                <div class="content_label">
                                    <label>Enter Link:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="apiUrl">
                                </div>
                                <div class="content_label socialFeedLabel">
                                    <label>Enter ID:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="socialFeedInput">
                                </div>
                            </div>
                            <div class="design_menu_box phoneNumberEdit">
                                <h2>Change Phone Number</h2>
                                <p>This is the section where you can add the contact number where the consumner can contact you.</p>
                                <div class="content_label">
                                    <label>Contact No. :</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="heading" maxlength="10">
                                </div>

                            </div>
                            <div class="design_menu_box contactWidget">
                                <h2>Choose Background Colour</h2>
                                <p>This will be the background colour of this screen.</p>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="contactPicker"></span>
                                </div>
                            </div>
                            <div class="design_menu_box contactWidget with_tip">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Headline:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="heading" maxlength="15">
                                    <em>Maximum of 15 Characters</em>
                                    <select class="content_font7">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <!-- <select class="content_fontsize7">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                                                       <a href="#"><b class="content_bold7">B</b></a>
                                                                        <a href="#"><i class="content_italics7">I</i></a>
                                                                        <a href="#"><u class="content_underline7">U</u></a>-->
                                    <span class="editPicker8"></span>
                                </div>
                                <div class="content_label">
                                    <label>Optional Text:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="subHeading1" maxlength="7">
                                    <em>Maximum of 7 Characters</em>
                                    <em class="optional">This is optional Text</em>
                                    <select class="content_font8">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <!--<select class="content_fontsize8">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                                                        <a href="#"><b class="content_bold8">B</b></a>
                                                                        <a href="#"><i class="content_italics8">I</i></a>
                                                                        <a href="#"><u class="content_underline8">U</u></a>-->
                                    <span class="editPicker9"></span>
                                </div>
                                <div class="content_label">
                                    <label>Description:</label>
                                </div>
                                <div class="content_textbox" >
                                    <input type="text" class="subHeading2" maxlength="22">
                                    <em>Maximum of 22 Characters</em>
                                    <select class="content_font9">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <!--<select class="content_fontsize9">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                                                        <a href="#"><b class="content_bold9">B</b></a>
                                                                        <a href="#"><i class="content_italics9">I</i></a>
                                                                        <a href="#"><u class="content_underline9">U</u></a>-->
                                    <span class="editPicker10"></span>
                                </div>
                            </div>


                            <div class="design_menu_box contact1Widget">
                                <h2>Choose Background Colour</h2>
                                <p>This will be the background colour of this screen.</p>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="contactPicker"></span>
                                </div>
                            </div>
                            <div class="design_menu_box contact1Widget with_tip">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="heading" maxlength="25">
                                    <em>Maximum of 25 characters</em>
                                    <select class="content_font10">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize10">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold10">B</b></a>
                                                                        <a href="#"><i class="content_italics10">I</i></a>
                                                                        <a href="#"><u class="content_underline10">U</u></a>-->
                                    <span class="editPicker11"></span>
                                </div>
                                <div class="content_label">
                                    <label>Description:</label>
                                </div>
                                <div class="content_textbox">
                                    <textarea class="subHeading1"></textarea>
                                    <select class="content_font11">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize11">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold11">B</b></a>
                                                                        <a href="#"><i class="content_italics11">I</i></a>
                                                                        <a href="#"><u class="content_underline11">U</u></a>-->
                                    <span class="editPicker12"></span>
                                </div>
                                <div class="content_label">
                                    <label>Footer:</label>
                                </div>
                                <div class="content_textbox" >
                                    <textarea class="subHeading2"></textarea>
                                    <select class="content_font12">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize12">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold12">B</b></a>
                                                                        <a href="#"><i class="content_italics12">I</i></a>
                                                                        <a href="#"><u class="content_underline12">U</u></a>-->
                                    <span class="editPicker13"></span>
                                </div>
                            </div>
                            <div class="design_menu_box contactSmallWidget">
                                <h2>Choose Background Colour</h2>
                                <p>This will be the background colour of this screen.</p>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="contactPicker"></span>
                                </div>
                            </div>
                            <div class="design_menu_box contactSmallWidget">
                                <h2>Edit Your Contact Details</h2>
                                <p>You can add your business&apos; name, along with instructions on how your consumers should get in touch with you.</p>
                                <div class="content_label head_tip">
                                    <label>Headline:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="heading" maxlength="13">
                                    <select class="content_font13">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize13">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold13">B</b></a>
                                                                        <a href="#"><i class="content_italics13">I</i></a>
                                                                        <a href="#"><u class="content_underline13">U</u></a>-->
                                    <span class="editPicker14"></span>
                                </div>
                                <div class="content_label">
                                    <label>Sub-Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="subHeading1" maxlength="15">
                                    <select class="content_font14">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize14">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold14">B</b></a>
                                                                        <a href="#"><i class="content_italics14">I</i></a>
                                                                        <a href="#"><u class="content_underline14">U</u></a>-->
                                    <span class="editPicker15"></span>
                                </div>


                            </div>

                            <div class="design_menu_box contactSmallWidgetPhoneEmail">
                                <h2>Contact Us Card</h2>
                                <p>One of the coolest features on your app, your consumers can get in touch with you with a simple tap on their cell phone screens. Thy can choose from either calling you by tapping on the phone icon, or email you by touching the email icon.</p>
                                <div class="content_label">
                                    <label>Phone No.:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="enter_phone" >
                                </div>
                                <div class="content_label">
                                    <label>Email ID:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="enter_email">
                                </div>
                            </div>
                            <div class="design_menu_box contactImgSmallWidget">
                                <h2>Edit Your Contact Details</h2>
                                <p>You can add your business’ name, along with instructions on how your consumers should get in touch with you.</p>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="contact_img_heading" maxlength="15">
                                    <select class="content_font20">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize20">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold13">B</b></a>
                                                                        <a href="#"><i class="content_italics13">I</i></a>
                                                                        <a href="#"><u class="content_underline13">U</u></a>-->
                                    <span class="editPicker22"></span>
                                </div>
                                <div class="content_label">
                                    <label>Sub Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="contact_img_subheading" maxlength="20">
                                    <select class="content_font21">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize21">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold14">B</b></a>
                                                                        <a href="#"><i class="content_italics14">I</i></a>
                                                                        <a href="#"><u class="content_underline14">U</u></a>-->
                                    <span class="editPicker23"></span>
                                </div>
                            </div>
                            <div class="design_menu_box headDiscBigWidget">
                                <h2>Choose Background Colour</h2>
                                <p>This will be the background colour of this screen.</p>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="contactPicker"></span>
                                </div>
                            </div>
                            <div class="design_menu_box headDiscBigWidget with_tip">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="heading" maxlength="25">
                                    <em>Maximum of 25 characters</em>
                                    <select class="content_font15">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize15">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold15">B</b></a>
                                                                        <a href="#"><i class="content_italics15">I</i></a>
                                                                        <a href="#"><u class="content_underline15">U</u></a>-->
                                    <span class="editPicker16"></span>
                                </div>
                                <div class="content_label">
                                    <label>Content:</label>
                                </div>
                                <div class="content_textbox">
                                    <textarea class="subHeading1"></textarea>
                                    <select class="content_font16">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize16">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold16">B</b></a>
                                    <a href="#"><i class="content_italics16">I</i></a>
                                    <a href="#"><u class="content_underline16">U</u></a>
                                    <span class="editPicker17"></span>
                                </div>
                            </div>



                            <!--
                            
                                                        <div class="design_menu_box tabEdit">
                                                            <h2>Change Colour Theme of this Screen</h2>
                                                            <p>This is the place where you can completely customise the colour theme of the selected screen. The Tab section allows you to change the colour of the Tabs, and the Description section lets you change the colour of the Description Box.</p>
                                                                                            <div class="background_label">
                                                                <label>Tab Color</label>
                                                            </div>
                                                            <div class="background_colorbox">
                                                                <span id="tabHeadBackGroundEdit"></span>
                                                            </div>
                                                            <div class="background_label">
                                                                <label>Description Color</label>
                                                            </div>
                                                            <div class="background_colorbox">
                                                                <span id="tabContentBackGroundEdit"></span>
                                                            </div>
                                                     
                                                        </div>
                            -->
                            <div class="design_menu_box tabEdit">
                                <div class="tabbing_1">
                                    <h2>Headline for Tab 1</h2>

                                    <div class="content_label">
                                        <label>Change Text:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" class="tabHead" maxlength="11">
                                        <em>Maximum of 11 Characters</em>
                                        <select class="content_font19">
                                            <option>Helvetica</option>
                                            <option>Helvetica Neue</option>
                                            <option>Helvetica Light</option>
                                            <option value="Open Sans">Open Sans</option>
                                            <option value="Didact Gothic">Gothic</option>
                                            <option value="EB Garamond">Garamond</option>
                                            <option value="Roboto">Roboto</option>
                                            <option value="sans-serif">Sans Serif</option>
                                        </select>
                                        <select class="content_fontsize19">
                                            <option>12</option>
                                            <option>14</option>
                                            <option>16</option>
                                            <option>18</option>
                                            <option>20</option>
                                            <option>22</option>
                                            <option>24</option>
                                        </select >
                                        <!--                                    <a href="#"><b class="content_bold">B</b></a>
                                                                            <a href="#"><i class="content_italics">I</i></a>
                                                                            <a href="#"><u class="content_underline">U</u></a>-->
                                       <!-- <span class="editPicker21"></span>-->
                                    </div>

                                    <h2>Description for Tab 1</h2>

                                    <div class="content_label">
                                        <label>Change Text:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <textarea class="tabContent"></textarea>
                                        <select class="tabcontent_font">
                                            <option>Helvetica</option>
                                            <option>Helvetica Neue</option>
                                            <option>Helvetica Light</option>
                                            <option value="Open Sans">Open Sans</option>
                                            <option value="Didact Gothic">Gothic</option>
                                            <option value="EB Garamond">Garamond</option>
                                            <option value="Roboto">Roboto</option>
                                            <option value="sans-serif">Sans Serif</option>
                                        </select>
                                        <select class="tabcontent_fontsize">
                                            <option>12</option>
                                            <option>14</option>
                                            <option>16</option>
                                            <option>18</option>
                                            <option>20</option>
                                            <option>22</option>
                                            <option>24</option>
                                        </select >
                                        <a href="#"><b class="tabcontent_bold">B</b></a>
                                        <a href="#"><i class="tabcontent_italics">I</i></a>
                                        <a href="#"><u class="tabcontent_underline">U</u></a>
                                        <!--<span class="tabeditPicker"></span>-->
                                    </div>
                                </div>
                                <div style="margin-top:35px;margin-bottom:35px;border-top:2px solid #efefef"></div>
                                <div class="tabbing_1">
                                    <h2>Headline for Tab 2</h2>
                                    <div class="content_label">
                                        <label>Change Text:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" class="tabHead" maxlength="11">
                                        <em>Maximum of 11 Characters</em>
                                        <select class="content_font19">
                                            <option>Helvetica</option>
                                            <option>Helvetica Neue</option>
                                            <option>Helvetica Light</option>
                                            <option value="Open Sans">Open Sans</option>
                                            <option value="Didact Gothic">Gothic</option>
                                            <option value="EB Garamond">Garamond</option>
                                            <option value="Roboto">Roboto</option>
                                            <option value="sans-serif">Sans Serif</option>
                                        </select>
                                        <select class="content_fontsize19">
                                            <option>12</option>
                                            <option>14</option>
                                            <option>16</option>
                                            <option>18</option>
                                            <option>20</option>
                                            <option>22</option>
                                            <option>24</option>
                                        </select >
                                        <!--                                    <a href="#"><b class="content_bold">B</b></a>
                                                                            <a href="#"><i class="content_italics">I</i></a>
                                                                            <a href="#"><u class="content_underline">U</u></a>-->
                                       <!-- <span class="editPicker21"></span>-->
                                    </div>

                                    <h2>Description for Tab 2</h2>

                                    <div class="content_label">
                                        <label>Change Text:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <textarea class="tabContent"></textarea>
                                        <select class="tabcontent_font">
                                            <option>Helvetica</option>
                                            <option>Helvetica Neue</option>
                                            <option>Helvetica Light</option>
                                            <option value="Open Sans">Open Sans</option>
                                            <option value="Didact Gothic">Gothic</option>
                                            <option value="EB Garamond">Garamond</option>
                                            <option value="Roboto">Roboto</option>
                                            <option value="sans-serif">Sans Serif</option>
                                        </select>
                                        <select class="tabcontent_fontsize">
                                            <option>12</option>
                                            <option>14</option>
                                            <option>16</option>
                                            <option>18</option>

                                            <option>20</option>
                                            <option>22</option>
                                            <option>24</option>
                                        </select >
                                        <a href="#"><b class="tabcontent_bold">B</b></a>
                                        <a href="#"><i class="tabcontent_italics">I</i></a>
                                        <a href="#"><u class="tabcontent_underline">U</u></a>
                                        <!--<span class="tabeditPicker"></span>-->
                                    </div>
                                </div>
                                <div style="margin-top:35px;margin-bottom:35px;border-top:2px solid #efefef"></div>
                                <div class="tabbing_1">
                                    <h2>Headline for Tab 3</h2>

                                    <div class="content_label">
                                        <label>Change Text:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" class="tabHead" maxlength="11">
                                        <em>Maximum of 11 Characters</em>
                                        <select class="content_font19">
                                            <option>Helvetica</option>
                                            <option>Helvetica Neue</option>
                                            <option>Helvetica Light</option>
                                            <option value="Open Sans">Open Sans</option>
                                            <option value="Didact Gothic">Gothic</option>
                                            <option value="EB Garamond">Garamond</option>
                                            <option value="Roboto">Roboto</option>
                                            <option value="sans-serif">Sans Serif</option>
                                        </select>
                                        <select class="content_fontsize19">
                                            <option>12</option>
                                            <option>14</option>
                                            <option>16</option>
                                            <option>18</option>
                                            <option>20</option>
                                            <option>22</option>
                                            <option>24</option>
                                        </select >
                                        <!--                                    <a href="#"><b class="content_bold">B</b></a>
                                                                            <a href="#"><i class="content_italics">I</i></a>
                                                                            <a href="#"><u class="content_underline">U</u></a>-->
                                        <!--<span class="editPicker21"></span>-->
                                    </div>

                                    <h2>Description for Tab 3</h2>

                                    <div class="content_label">
                                        <label>Change Text:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <textarea class="tabContent"></textarea>
                                        <select class="tabcontent_font">
                                            <option>Helvetica</option>
                                            <option>Helvetica Neue</option>
                                            <option>Helvetica Light</option>
                                            <option value="Open Sans">Open Sans</option>
                                            <option value="Didact Gothic">Gothic</option>
                                            <option value="EB Garamond">Garamond</option>
                                            <option value="Roboto">Roboto</option>
                                            <option value="sans-serif">Sans Serif</option>
                                        </select>
                                        <select class="tabcontent_fontsize">
                                            <option>12</option>
                                            <option>14</option>
                                            <option>16</option>
                                            <option>18</option>

                                            <option>20</option>
                                            <option>22</option>
                                            <option>24</option>
                                        </select >
                                        <a href="#"><b class="tabcontent_bold">B</b></a>
                                        <a href="#"><i class="tabcontent_italics">I</i></a>
                                        <a href="#"><u class="tabcontent_underline">U</u></a>
                                        <!--<span class="tabeditPicker"></span>-->
                                    </div>
                                    <div style="margin-top:35px;margin-bottom:35px;border-top:2px solid #efefef"></div>
                                </div>
                            </div>

                            <div class="design_menu_box bigWidgetEdit">
                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" class="editbrowse_img">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box bigWidgetEdit cont_tip">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_widget_head" maxlength="25">
                                    <em>Maximum of 25 Characters</em>
                                    <select class="content_font">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold">B</b></a>
                                                                        <a href="#"><i class="content_italics">I</i></a>
                                                                        <a href="#"><u class="content_underline">U</u></a>-->
                                    <span class="editPicker"></span>
                                </div>
                            </div>

                            <div class="design_menu_box bigWidgetTextEdit">
                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" class="editbrowse_img">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box bigWidgetTextEdit">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Headline:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head1" maxlength="25">
                                    <em>Maximum of 25 Characters</em>
                                    <select class="content_font1">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize1">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold1">B</b></a>
                                                                        <a href="#"><i class="content_italics1">I</i></a>
                                                                        <a href="#"><u class="content_underline1">U</u></a>-->
                                    <span class="editPicker1"></span>
                                </div>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head2" maxlength="30">
                                    <em>Maximum of 30 Characters</em>
                                    <select class="content_font3">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize3">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold3">B</b></a>
                                                                        <a href="#"><i class="content_italics3">I</i></a>
                                                                        <a href="#"><u class="content_underline3">U</u></a>-->
                                    <span class="editPicker2"></span>
                                </div>
                                <div class="content_label">
                                    <label>Subheading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head3" maxlength="14">
                                    <em>Maximum of 14 Characters</em>
                                    <select class="content_font4">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize4">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold4">B</b></a>
                                                                        <a href="#"><i class="content_italics4">I</i></a>
                                                                        <a href="#"><u class="content_underline4">U</u></a>-->
                                    <span class="editPicker3"></span>
                                </div>
                                <div class="content_label">
                                    <label>Optional Text:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head4" maxlength="14">
                                    <em>Maximum of 14 Characters</em>
                                    <em></em>
                                    <select class="content_font5">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize5">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold5">B</b></a>
                                                                        <a href="#"><i class="content_italics5">I</i></a>
                                                                        <a href="#"><u class="content_underline5">U</u></a>-->
                                    <span class="editPicker4"></span>
                                </div>
                            </div>
                            <div class="design_menu_box fullWidgetLongText">
                                <h2>Choose Background Colour</h2>
                                <p>This will be the background colour of this screen.</p>
                                <div class="change_image">
                                    <input type="file" class="editbrowse_img">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box fullWidgetLongText with_tip">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Headline:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head1" maxlength="25">
                                    <em>Maximum of 25 Characters</em>
                                    <select class="content_font1">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize1">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold1">B</b></a>
                                                                        <a href="#"><i class="content_italics1">I</i></a>
                                                                        <a href="#"><u class="content_underline1">U</u></a>-->
                                    <span class="editPicker18"></span>
                                </div>

                                <div class="content_label">
                                    <label>Sub-heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <textarea class="big_text_head6" maxlength="50"></textarea>
                                    <em>Maximum of 50 Characters</em>
                                    <select class="content_font17">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize17">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold17">B</b></a>
                                                                        <a href="#"><i class="content_italics17">I</i></a>
                                                                        <a href="#"><u class="content_underline17">U</u></a>-->
                                    <span class="editPicker19"></span>
                                </div>
                                <div class="content_label">
                                    <label>Description:</label>
                                </div>
                                <div class="content_textbox">
                                    <textarea maxlength="2000" class="big_text_head7"></textarea>
                                    <em>Maximum of 2000 Characters</em>
                                    <select class="content_font18">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize18">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold18">B</b></a>
                                                                        <a href="#"><i class="content_italics18">I</i></a>
                                                                        <a href="#"><u class="content_underline18">U</u></a>-->
                                    <span class="editPicker20"></span>
                                </div>
                            </div>

                            <div class="design_menu_box bigWidgetAboutEdit">
                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" class="editbrowse_img">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <span>Image</span>
                                </div>

                                <div class="clear"></div>
                                <!--<div class="background_label">
                                    <label>Choose Background Colour:</label>
                                </div>
                                <div class="background_colorbox">
                                    <span class="editPickerfortabBackground"></span>
                                </div>
                                <div class="clear"></div>-->
                            </div>

                            <div class="design_menu_box bigWidgetAboutEdit cont_tip">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head1" maxlength="25">
                                    <em>Maximum of 25 Characters</em>
                                    <select class="content_font1">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize1">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold1">B</b></a>
                                                                        <a href="#"><i class="content_italics1">I</i></a>
                                                                        <a href="#"><u class="content_underline1">U</u></a>-->
                                    <span class="editPicker1"></span>
                                </div>
                                <div class="content_label">
                                    <label>Description:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head5" maxlength="30">
                                    <em>Maximum of 30 Characters</em>
                                    <select class="content_font6">
                                        <option>Helvetica</option>
                                        <option>Helvetica Neue</option>
                                        <option>Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <select class="content_fontsize6">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <!--                                    <a href="#"><b class="content_bold6">B</b></a>
                                                                        <a href="#"><i class="content_italics6">I</i></a>
                                                                        <a href="#"><u class="content_underline6">U</u></a>-->
                                    <span class="editPicker5"></span>
                                </div>
                            </div>

                            <div class="design_menu_box videoEdit">
                                <h2>Add Video</h2>
                                <div class="content_label">
                                    <label>Video URL:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" placeholder="Enter Video URL" class="video_url">
                                </div>
                            </div>

                            <div class="design_menu_box widgetLinkEdit">
                                <div class="addPage Widget">Add New Screen For Your Card </div>
                                <h2>Connect Your Card to a Screen</h2> 
                                <div class="background_label">
                                    <label>Choose a Screen:</label>
                                </div>
                                <div class="background_colorbox">
                                    <select class="widgetlinkSelector">
                                        <option value="0">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <a href="javascript:void(0)" class="make_app_next2 saveAndcontinue">Save & Continue</a>
                            <div class="clear"></div>
                        </div>
                    </div>

                </div>
                <div class="clear"></div>
            </div>

        </div>
        <div class="hint_main">
            <img src="<?php echo $basicUrl; ?>images/ajax-loader11.gif">

        </div>
    </section>
</section>
</section>
<button class="add_page_hidden" style="visibility:hidden;"></button>
<button class="save_hidden" style="visibility:hidden;"></button>
<script type="text/javascript" src="js/jquery.shapeshift.js"></script> 
<script>

    var pageDetails = [
        {
            index: '1',
            name: 'Home'
        }
        /* ,
         {
         index: '2',
         name: 'About Us'
         } */

    ];
    var navbar = '';

    var storedPages = [
        {
            index: '1',
            contentarea: '',
            banner: '',
            layouttype: '1'
        }
        /* ,
         {
         index: '10',
         contentarea: '<div class="big_widget_text about" data-ss-colspan="2" data-component="14"><p class="widgetClose">x</p><div class="big_widget_img"><img src="<?php echo $basicUrl; ?>images/widget_big_img.jpg"><img class="video_img" alt="" src="<?php echo $basicUrl; ?>images/video_img_big.png"><div class="clear"></div><div class="big_widget_img_text"><p class="img_heading">Heading</p><div class="clear"></div></div><div class="big_widget_img_controls"><a href="#" class="share"><img src="<?php echo $basicUrl; ?>images/share.png" /></a><a href="#" class="share"><img src="<?php echo $basicUrl; ?>images/heart.png" /></a><div class="clear"></div></div></div><div class="big_widget_bottom_text"><p class="about">About Us</p><div class="clear"></div></div></div><div class="tabbing_widget" data-ss-colspan="2" data-component="15"><p class="widgetClose">x</p><div class="tabbing_widget_head"><ul class="tabs"><li class="active"><a>Tab 1</a></li><li><a>Tab 2</a></li><li><a>Tab 3</a></li><div class="clear"></div></ul></div><div class="tabbing_widget_body"><div class="tab_content" style="display:block;"><p>Add Description1</p></div><div class="tab_content" style="display:none;"><p>Add Description2</p></div><div class="tab_content" style="display:none;"><p>Add Description3</p></div></div></div>',
         banner: '',
         layouttype: '10'
         } */
    ];



    $(document).ready(function () {
        $(".leftsidemenu li a").click(function (e) {
            if (($("#appName").val()) != '') {
                var link = $(this).attr("href");
                e.preventDefault();
                $(".save_hidden").trigger("click");
                saveAppPanel();
                setTimeout(function () {
                    window.location.href = BASEURL + link;
                }, 10000);
            }
        });

//        $(".top-aside li:last-child a").click(function (e) {
//            if (($("#appName").val()) != '') {
//                var link = $(this).attr("href");
//                e.preventDefault();
//                saveAppPanel();
//                window.location.href = BASEURL + link;
//            }
//        });

        $("#savehtml").click(function () {
            var appnameempty = checkAppName();
            if (appnameempty == 1) {
                var click_savehtml = "savehtml";
                saveAppPanel(click_savehtml);
            } else {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('');
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
                $(".confirm_name").css({'display': 'block'});
            }

        });
        $("#finish").click(function () {
            var appnameempty = checkAppName();
            if (appnameempty == 1) {
                var click_finish = "finish";
                saveAppPanel(click_finish);
            } else {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('');
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
                $(".confirm_name").css({'display': 'block'});
            }

        });

        $(".saveAndcontinue").click(function () {
            var appnameempty = checkAppName();
            if (appnameempty == 1) {
                saveAppPanel();
            } else {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('');
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
                $(".confirm_name").css({'display': 'block'});
            }

        });

        $("#getcropped").click(function () {
            var appnameempty = checkAppName();
            if (appnameempty == 1) {
                var click_crop = "crop";
                saveAppPanel(click_crop);
            } else {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('');
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
                $(".confirm_name").css({'display': 'block'});
            }

        });
//        $("#appNameCheck").click(function () {
//            if ($("#appName").val() != '') {
//                var platform = $("#platform option:selected").val();
//                var appName = $("#appName").val();
//                var appidCheck = $('#appid').val();
//                var param = {'platform': platform, 'appName': appName};
//
//                var form_data = {
//                    data: param, //your data being sent with ajax
//                    token: '<?php echo $token; ?>', //used token here.
//                    themeid: '<?php echo $theme_id; ?>',
//                    catid: '<?php echo $categoryid; ?>',
//                    confirm: 'Yes',
//                    hasid: appidCheck,
//                    is_ajax: 1
//                };
//
//                $.ajax({
//                    type: "POST",
//                    url: '/frameworkphp/modules/checkapp/checkappname.php',
//                    data: form_data,
//                    success: function (response)
//                    {
////                    alert(response);
//                        if (response == 1) {
//                            $(".confirm_name").css({'display': 'block'});
//                            $(".popup_container").css({'display': 'block'});
//                        } else if (response != 1) {
//                            $('#appid').val(response);
////                            alert('check val');
//                        }
//                    },
//                    error: function () {
//                        console.log("error in ajax call");
//                        alert("error in ajax call");
//                    }
//                });
//
//            } else {
//                alert('please fill app name');
//                return false;
//            }
//        });

        // $(".confirm_name input").click(function () {
        //  $(".confirm_name").css({'display': 'none'});
        //  $(".popup_container").css({'display': 'none'});
        //  });

        var appaction = '<?php echo $apppage; ?>';
        if (appaction == "edit") {
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
                        populateLink();
                        firstUpdateSlideNames();
                        updateSlideNames();
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
        } else {
            var page = '<?php echo addslashes($htmldata); ?>';
            //if(page != '')
            //{
            obj = JSON.parse(page);
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
            //}
        }

//        console.log(page);

		


    });

</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/cropper.js"></script>
<script src="js/main.js"></script>

<script type="text/javascript" src="js/colpick.js"></script>
<script type="text/javascript" src="js/customFramework.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

<script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script>
    var $image = $('.img-container > img');
    $(document).ready(function () {
        var upreview = '<?php echo $preview; ?>';
        if (upreview == "preview") {

            $(".preview").trigger('click');
        }

        $image.cropper('destroy').cropper({
            minCanvasWidth: 800,
            minCanvasHeight: 400,
            minCropBoxWidth: 800,
            minCropBoxHeight: 400,
            minContainerWidth: 800,
            minContainerHeight: 400,
            strict: false,
            guides: false,
            highlight: false,
            dragCrop: false,
            cropBoxMovable: false,
            cropBoxResizable: false
        });
    });
    (function ($) {
        $(window).load(function () {
            $("#content-1").mCustomScrollbar();
            $("#content-2").mCustomScrollbar();
            $(document).keydown(function (e) {
                if (e.keyCode == 37) {
                    return true;
                }
            });
        });
    })(jQuery);</script>

<script src="js/chosen.jquery.js"></script>
<script src="js/ImageSelect.jquery.js"></script>
<script>
    $(".select_os").chosen();

    // onload tutorials 
    $(window).load(function () {
        $('.first_tutorial').addClass('current');
        $('.onload_tutotial, .first_tutorial').fadeIn();
        $('.continue_tutorial a').on('click', function () {
            $('.current').hide();
            $('.current').next().fadeIn();
            $('.current').next().addClass('current');
            $('.current').eq(0).removeClass('current');
            if ($('.third_tutorial').hasClass('current')) {
                $('.additional_options span').trigger('click');
                $('.continue_tutorial a img').attr('src', 'images/finish.png');
            }
            if ($('.continue_tutorial a img').attr('src') == 'images/finish.png') {
                $('.continue_tutorial a img').on('click', function () {
                    $('.onload_tutotial').hide();
                    $('.additional_options span').trigger('click');
                })
            }
        })
        $('.onload_tutotial span').on('click', function () {
            $('.onload_tutotial').hide();
        })
    });
	
	
</script>
<script type="text/javascript" src="js/pramukhime.js"></script>
<script type='text/javascript' src='js/pramukhindic.js' ></script>
<script type="text/javascript" src="js/pramukhime-common.js"></script>
<script language="javascript" type="text/javascript">

    pramukhIME.addLanguage(PramukhIndic);

    pramukhIME.enable();
    pramukhIME.onLanguageChange(scriptChangeCallback);
    var lang = (getCookie('pramukhime_language', ':english')).split(':');
    pramukhIME.setLanguage(lang[1], lang[0]);
    var ul = document.getElementById('pi_tips');

    var elem, len = ul.childNodes.length, i;
    for (i = 0; i < len; i++) {
        elem = ul.childNodes[i];
        if (elem.tagName && elem.tagName.toLowerCase() == 'li') {
            tips.push(elem.innerHTML);
        }
    }
    for (i = len - 1; i > 1; i--) {
        ul.removeChild(ul.childNodes[i]);
    }
    ul.childNodes[i].className = 'tip'; // replace small tip text with large

    showNextTip(); // call for first time
    setTimeout('turnOffTip()', 90000); // show tips for 1.5 minutes
    document.getElementById('typingarea').focus();

// set width and height of dialog
    var w = window, d = document, e = d.documentElement, g = d.getElementsByTagName('body')[0], x = w.innerWidth || e.clientWidth || g.clientWidth, y = w.innerHeight || e.clientHeight || g.clientHeight;
    var elem = document.getElementById('dialog');
    elem.style.top = ((y - 550) / 2) + 'px';
    elem.style.left = ((x - 700) / 2) + 'px';
    $(document).ready(function () {

        $("#fakeLanguage").on("change", function () {
            //console.log( $(this).find(":selected").val())
            $("#drpLanguage").val($(this).find(":selected").val()).trigger("change");
        });
        $("nav ul").find("[data-link='s1']").find("span:first").attr("class", "icon-report")
        $("span[class^='icon']").css("color", $(".theme_head").eq(1).css("background-color"))
        $("#drpLanguage").val(":english").trigger("change");

    });
</script>
<script>
$(window).load(function(e) {
    // feedback checked on edit
	if($('.mobile nav ul li.feedback').css('display')=='block'){
		$('.feedback ul li input.feedbackReq').prop('checked','true');
	}
});
</script>

</body>
</html>