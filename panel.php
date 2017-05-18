<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/checkapp/checkappclass.php');
$forUrl = new DB();
$siteURL = $forUrl->siteurl();

$checkapp = new checkapp();
if (isset($_SESSION['token'])) {
    unset($_SESSION['token']);
}
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$custid = $_SESSION['custid'];
$getAuthor = $checkapp->getAuthorId($custid);
$authorId = $getAuthor['id'];
$getAppsResult = $checkapp->appDetails($authorId);
//$appname = '';
$apppage = isset($_GET['app']) ? $_GET['app'] : '';

$preview = isset($_GET['page']) ? $_GET['page'] : '';
$buttonCreate = 'Finish';
if ($apppage == "edit") {
    $appID = $_SESSION['appid'];
    $buttonCreate = 'Update';
    $appname = $checkapp->myappname($appID);
    $signData = $checkapp->myappSignUpData($appID);
} else if ($apppage == "create") {
    unset($_SESSION['appid']);
    $appID = 0;
    $_SESSION['catid'] = isset($_GET['catid']) ? $_GET['catid'] : '';
    $_SESSION['themeid'] = isset($_GET['themeid']) ? $_GET['themeid'] : '';

    $htmldataAll = $checkapp->getthemeHtml($_SESSION['themeid']);
    $htmldata = $htmldataAll['theme_html'];
    $layout_type = '1';
    if (isset($htmldataAll['layout_type'])) {
        $layout_type = $htmldataAll['layout_type'];
    }

    if ((isset($_GET['themeid'])) && ($_GET['themeid'] != '')) {

        $catid = isset($_GET['catid']) ? $_GET['catid'] : '';
        $themeid = isset($_GET['themeid']) ? $_GET['themeid'] : '';
        $useripadd = $ip_address;

        $checkapp->paneltracking($useripadd, $catid, $themeid);
//         printf("%d Row inserted.\n", $mysqli->affected_rows);
    }
} else {
    ?>

    <script> window.location = "/";</script>
    <?php
}

$categoryid = $_SESSION['catid'];
$theme_id = $_SESSION['themeid'];
$_SESSION['currentpagevisit'] = 'panel.php';
$appPublish = $appname['published']
?>

<link href="css/cropper.css" rel="stylesheet">
<link href="css/main.css" rel="stylesheet">

<style type="text/css">
    .modal-open{overflow:hidden}
    .modal{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1050;display:none;overflow:hidden;-webkit-overflow-scrolling:touch;outline:0}
    .modal.fade .modal-dialog{-webkit-transition:-webkit-transform .3s ease-out;-o-transition:-o-transform .3s ease-out; transition:transform .3s ease-out;-webkit-transform:translate(0,-25%);-ms-transform:translate(0,-25%);-o-transform:translate(0,-25%);transform:translate(0,-25%)}
    .modal.in .modal-dialog{-webkit-transform:translate(0,0);-ms-transform:translate(0,0); -o-transform:translate(0,0);transform:translate(0,0)}
    .modal-open .modal{overflow-x:hidden;overflow-y:auto}
    /*    .modal-dialog{position:relative;width:68%;margin:0 auto;}*/
    .modal-dialog{ position:relative; margin:0 auto; top: 30px; width: 58%; }
    .modal-content{position:relative;background-color:#fff;-webkit-background-clip:padding-box;background-clip:padding-box;border:1px solid #999;border:1px solid rgba(0,0,0,.2);border-radius:0px;outline:0;-webkit-box-shadow:0 3px 9px rgba(0,0,0,.5);box-shadow:0 3px 9px rgba(0,0,0,.5)}
    .modal-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1040;background-color:#000}
    .modal-backdrop.fade{filter:alpha(opacity=0);opacity:0}
    .modal-backdrop.in{filter:alpha(opacity=50);opacity:.5}
    /*    .modal-header{min-height:16.43px;padding:15px;border-bottom:1px solid #e5e5e5}*/
    .modal-header .close{ margin-top: -2px; }
    /*.modal-title{margin:0;line-height:1.42857143}
    .modal-title .zoomtext{    font-weight: 100; padding-top: 5px;font-size: 14px; }*/
    #bootstrap-modal-label { margin: 0; padding: 10px; background: #f4f4f4; }
    ul.modal-title { font-size: 12px; font-weight: normal; padding: 10px; color: #777; }
    ul.modal-title .resolution { font-weight: bold; }
    .upload-btn { position: relative; float: right; overflow: hidden; margin: 10px; margin-top: 20px; }
    .upload-btn .make_app_next { margin: 0; }
    .upload-btn #inputImage { position: absolute; top: 0; right: 0; margin: 0; padding: 0; font-size: 20px; cursor: pointer; opacity: 0; filter: alpha(opacity=0); }
    /*    .modal-body{position:relative;padding:15px}*/
    .modal-body{ position:relative; overflow: hidden; }
    .modal-body .img-container .crop-controls { position: absolute; bottom: 2%; left: 50%; z-index: 99; display: none; }
    .modal-body .img-container img:not([src=""]) + .crop-controls { display: block; }
    .modal-body .img-container .crop-controls .zoom-controls { cursor: pointer; padding: 5px 10px; }
    /*    .modal-footer{padding:15px;text-align:right;border-top:1px solid #e5e5e5}*/
    .modal-footer { padding: 15px 10px; text-align: right; }
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
    .img-container {
        background-image: url('images/placeholder-img.png');
        background-repeat: no-repeat;
        background-position: center;
        background-color: #f4f4f4;
        min-height: 375px;
        overflow: hidden;
        margin: 0;
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
    .site-tutorial{
        display: block !important;
    }
    .select#fakeLanguage{
        display: block !important;
    }
    .container.droparea{background-size:cover; background-repeat: no-repeat;background-position: top center;background-color: #f6f4f5}
    .change-popup-size { height: 450px !important; width: 300px !important; margin-top: -220px !important; margin-left: -150px !important; display: block; transform: scale(2); -webkit-transform: scale(2); -ms-transform: scale(2); -moz-transform: scale(2); -o-transform: scale(2); }
    .change-popup-size input { width: 100% !important; } .change-popup-size .login_popup_body { max-height: 75%; overflow-y: auto; }
    .change-popup-size #signup_form .mobileNumber .intl-tel-input { width: 100%; margin-bottom: 10px; }
    /*.cropper-view-box{border-radius:100%}*/
</style>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<script type="text/javascript">
            if (document.cookie.indexOf("_instance=true") === - 1) {
    document.cookie = "_instance=true";
            // Set the onunload function
            window.onunload = function(){
            document.cookie = "_instance=true;expires=Thu, 01-Jan-1970 00:00:01 GMT";
            };
    }
    else {
    document.querySelectorAll('html')[0].style.display = 'none';
            window.location = BASEURL + 'session';
    }
    function is_valid_url(url) {
    return /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(url);
    }
</script>
<script src="js/nicEdit.js" type="text/javascript"></script>
<script type="text/javascript">
            //bkLib.onDomLoaded(nicEditors.allTextAreas);
            bkLib.onDomLoaded(function () {
            nicEditors.allTextAreas();
                    $('.notEdit').each(function () {
            $parent = jQuery(this).parent();
                    $parent.children('div').hide();
                    $parent.children('textarea').show();
            });
            });</script>
<script>
            function downloadme(x) {
            myTempWindow = window.open(x);
                    //myTempWindow.document.execCommand('SaveAs','null',x); 
                    //myTempWindow.close(); 
            }
</script> 

<script type="text/javascript">
    var external_user_id = '';
            var external_app_id = '';
            function uploadImg() {
            $(this).parent().removeAttr("id");
                    $(this).parent().attr("id", "present");
            }
    ;
            function checkAppName() {
            var y = 0;
                    if (($("#appName").val()) != '') {
            y = 1;
            } else {
            y = 0;
            }

            return y;
            }
    function mail_confirm($txt) {
    var x = 0;
            $.ajax({
            type: "POST",
                    url: "login.php",
                    async: false,
                    data: "check=" + $txt,
                    success: function (response) {
                    if (response) {
                    x = response;
                    } else {
                    x = 0;
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
            return x;
    }


    /*
     * code is for saving app panel data
     * code edited by Arun Srivastava on 24/11/15
     */
    function saveAppPanel(click_button)
    {
    if ($(".preview").hasClass("active") == true)
    {
    /*    popup code will be here when user is in preview mode        */
    }
    else
    {
    var currentClick = $(this);
            $(".popup_container").css("display", "block");
            $("#page_ajax").html('<img class="loader_new" src="images/ajax-loader_new.gif">').show();
            $.ajax({
            type: "POST",
                    url: "login.php",
                    data: "check=login",
                    success: function (response) {
                    var responsediv = response.split("##");
                            if (responsediv[0] != '')
                    {
                    external_app_id = responsediv[1];
                            external_user_id = responsediv[0];
                            var mailConfirm = responsediv[2];
                            var reseller_id = responsediv[4];
                            var is_reseller = responsediv[5];
                            var app_create_reseller = '<?php echo $apppage; ?>'
                                                    //var mailConfirm = mail_confirm("mail_confirm");
                                                    if (reseller_id > 0 && app_create_reseller == 'create' && is_reseller == ''){
                    //var mailConfirm = mail_confirm("mail_confirm");
                    $(".confirm_name .close_popup").css({'display': 'none'});
                            $(".confirm_name .confirm_name_form").html('<p>You can not create app. Please contact to your reseller.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(".close_popup").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('').hide();
                            setTimeout(function () {
                            window.location.href = BASEURL + 'applisting.php';
                            }, 3000);
                            return false;
                    }
                    if (mailConfirm > 0)
                    {
                    $(".cropcancel").trigger("click");
                            $(".confirm_name .close_popup").css({'display': 'none'});
                            $(".confirm_name .confirm_name_form").html('<p>Please complete your profile to proceed.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(".close_popup").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('').hide();
                            setTimeout(function () {
                            window.location.href = BASEURL + 'userprofile.php';
                            }, 2000);
                    }
                    else
                    {
                    if (responsediv[1] == '')
                    {
                    if ($("#appName").val() != '')
                    {
                    if ($('.screen_name').val() != '')
                    {
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
                            $.ajax({
                            type: "POST",
                                    url: 'modules/checkapp/checkappname.php',
                                    data: form_data,
                                    async: false,
                                    success: function (response2)
                                    {
                                    $(".savingDetails").css('display', 'none');
                                            if (response2 == 1)
                                    {
                                    $(".cropcancel").trigger("click");
                                            $(".confirm_name .confirm_name_form").html('<p>This app name already exist.<br>Please choose another name.</p><input type="button" value="OK">');
                                            $(".confirm_name").css({'display': 'block'});
                                            $(".popup_container").css({'display': 'block'});
                                            $("#page_ajax").html('').hide();
                                    }
                                    else if (response2 != 1)
                                    {
                                    $(".confirm_app").css({'display': 'none'});
                                            $(".popup_container").css({'display': 'none'});
                                            $("#page_ajax").html('').hide();
                                            $('#appid').val(response2);
                                            $(".save_hidden").trigger("click");
                                            if (click_button == "finish")
                                    {
                                    finish();
                                    }
                                    else if (click_button == "crop")
                                    {
                                    editbrowse_cropimgFunction();
                                    }
                                    else if (click_button == "addpage")
                                    {
                                    addpages();
                                    }
                                    else
                                    {
                                    savehtml();
                                    }

                                    $(".cropcancel").trigger("click");
                                            $(".add_page").next().find(".mid_right_box_head").trigger("click");
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
                            $("#page_ajax").html('').hide();
                            $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please choose screen name.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    } else {
                    fnErrAppName();
                    }

                    }
                    else
                    {
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
                                            $(".confirm_name .confirm_name_form").html('<p>This app name already exist.<br>Please choose another name.</p><input type="button" value="OK">');
                                            $(".confirm_name").css({'display': 'block'});
                                            $(".popup_container").css({'display': 'block'});
                                            $("#page_ajax").html('').hide();
                                    } else if (response2 != 1) {
                                    $(".confirm_app").css({'display': 'none'});
                                            $(".popup_container").css({'display': 'none'});
                                            $("#page_ajax").html('').hide();
                                            $('#appid').val(response2);
                                            $(".save_hidden").trigger("click");
                                            if (click_button == "finish")
                                    {
                                    finish();
                                    }
                                    else if (click_button == "crop")
                                    {
                                    editbrowse_cropimgFunction();
                                    }
                                    else if (click_button == "addpage")
                                    {
                                    addpages();
                                    }
                                    else
                                    {
                                    savehtml();
                                    }

                                    $(".cropcancel").trigger("click");
                                            $(currentClick).parents('.mid_right_box').next().find('.mid_right_box_head').trigger('click');
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
                            $("#page_ajax").html('').hide();
                            return false;
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
            return false;
    }
    }

    function addnewAppName() {
    if (($("#appName").val()) != '') {
    if ($('.screen_name').val() != '') {
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

                    if (response2 == 1) {
                    $(".cropcancel").trigger("click");
                            $(".confirm_name .confirm_name_form").html('<p>This app name already exist.<br>Please choose another name.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('').hide();
                            return false;
                    } else if (response2 != 1) {
                    $(".cropcancel").trigger("click");
                            $('#app_id').val(response2);
                            $(".save_hidden").trigger("click");
                            savehtml();
                            $(".confirm_app").css({'display': 'none'});
                            $(".popup_container").css({'display': 'none'});
                            $("#page_ajax").html('').hide();
                    }
                    },
                    error: function () {
                    console.log("error in ajax call");
                            $(".confirm_name").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('').hide();
                            return false;
                    }
            });
    } else {
    $(".cropcancel").trigger("click");
            $("#page_ajax").html('').hide();
            $(".popup_container").css({'display': 'block'});
            $(".confirm_name .confirm_name_form").html('<p>Please choose screen name.</p><input type="button" value="OK">');
            $(".confirm_name").css({'display': 'block'});
            return false;
    }
    }
    else {
    fnErrAppName();
    }
    }
    function addnewAppNameOnClick() {
    if (($("#appName").val()) != '') {
    if ($('.screen_name').val() != '') {
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

                    if (response3 != 1) {
                    $(".cropcancel").trigger("click");
                            $('#app_id').val(response3);
                            $(".save_hidden").trigger("click");
                            savehtml();
                            $(".confirm_app").css({'display': 'block'});
                            $(".popup_container").css({'display': 'none'});
                            $("#page_ajax").html('').hide();
                    }
                    },
                    error: function () {
                    $(".cropcancel").trigger("click");
                            console.log("error in ajax call");
                            $(".confirm_name").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('').hide();
                    }
            });
    } else {
    $(".cropcancel").trigger("click");
            $("#page_ajax").html('').hide();
            $(".popup_container").css({'display': 'block'});
            $(".confirm_name .confirm_name_form").html('<p>Please choose screen name.</p><input type="button" value="OK">');
            $(".confirm_name").css({'display': 'block'});
            return false;
    }
    }
    else {
    fnErrAppName();
    }
    }

</script>

<div class="screen_loader" style="width:100%; height:100%; background:#000; opacity:0.7; position:fixed; top:0; left:0; z-index:13;"><img style="position: absolute; top: 40%; left: 0px; right: 0px; margin: 0px auto;" class="loader_new" src="images/ajax-loader_new.gif"/></div>
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
        <div class="addPage">Add New Screen</div>
        <ul class="top-aside">

            <?php if ($_SESSION['is_reseller']) { ?>
                <li><a id="reseller_link" href="logout.php?reseller_id=<?php echo $_SESSION['reseller_id']; ?>&is_reseller=<?php echo $_SESSION['is_reseller']; ?>">Dashboard<i class="fa fa-bar-chart"></i></a></li>
                <?php
            } else if ($_SESSION['reseller_id']) {
                ?>
                <li> <a id="reseller_link" href="logout.php?reseller_id=<?php echo $_SESSION['reseller_id']; ?>">Dashboard<i class="fa fa-bar-chart"></i></a></li>
                <?php
            }
            ?>
            <?php if (empty($_SESSION['cust_reseller_id'])) { ?>
                <li><a href="themes.php">Create New<i class="fa fa-rocket"></i></a></li>
            <?php } ?>
            <li class="save"><a href="#" id="savehtml">Save <i class="fa fa-cloud-upload"></i></a></li>
            <li><a id="finish"><?php echo $buttonCreate; ?> <i class="fa fa-sign-in"></i></a></li>
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

                    <div class="blur-bg"></div>

                </div>

                <!-- Replacement Area Ends -->
            </div>
            <div class="choiceArea"> 
                <div class="previewEditArea">


                    <p class="preview"><i class="fa fa-search"></i> Preview</p><p class="edit active"><i class="fa fa-pencil"></i> Edit</p>
                </div>

                <div class="additional_options">
                    <span></span>
                    <a href="resources/content_publishing_FINAL.pdf" target="_blank"><div class="additional_items information">
                            <img src="<?php echo $basicUrl; ?>images/info1.png" alt="Image Not Found">
                            <div class="tooltip">Information</div>
                        </div></a>
                    <a href="#"><div class="additional_items hint">
                            <img src="<?php echo $basicUrl; ?>images/info2.png" alt="Image Not Found">
                            <div class="tooltip">Hint</div>
                        </div></a>
                    <!--                    <div class="additional_items callus">
                                            <img src="<?php // echo $basicUrl;                  ?>images/info3.png" alt="Image Not Found">
                                            <div class="tooltip">Call Us</div>
                                        </div>
                    <div class="additional_items chat">
                        <img src="<?php // echo $basicUrl;                  ?>images/info4.png" alt="Image Not Found">
                        <div class="tooltip">Chat</div>
                    </div>
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
                    </div>-->
                </div>
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
                                    <input type="text" name="appName" id="appName" value="<?php echo $appname['summary']; ?>" maxlength="30">
                                    <input type="hidden" name="appid" id="appid" value="<?php echo $appID; ?>">

                                    <span>Choose a unique name for your app. You can go creative with the name but before you finalise do a final check of whether it conveys how amazing your product is? Is it easy to pronounce? Is it even accurate to what your app does? Is it unique? If the answer is yes, go ahead! </span>
                                    <a href="#" class="read_more">Read More...</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <img  src="<?php echo $basicUrl; ?>images/ajax-loader_new.gif" class="loader_new" style="display: none" id="loderpanel"/>
                            <a href="javascript:void(0)" class="make_app_next2 saveAndcontinue">Save & Continue</a>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="mid_right_box">
                        <div class="mid_right_box_head ">
                            <h1 class="appDetailsave">Customize Your App Colors and Navigation Menu<h2 class="savingDetails">Saving...</h2><h2 class="savedDetails">All changes have been saved.</h2></h1>
                            <h2>Wow! Looks like you got to the fun part. This is where you can start to customise your app. Start with changing the background colour of your app, colour of the title bar, choose your title text, and lots more. Remember soft pastels enhance great pictures, so your app background should ideally be a pastel.  You can also try multiple combinations before you finalise. So go ahead and experiment. </h2>
                            <a class="read_more">Read More...</a>
                            <div class="clear"></div>
                            <span> <i class="fa fa-angle-down"></i> </span>
                        </div>
                        <div class="mid_right_box_body GeneralDetails">

                            <div class="design_menu_box Page">  
                                <h2>Screen Background</h2>

                                <!--background color start-->
                                <div class="backgroundColor">
                                    <label class="screenConnectRadio"><input type="radio" id="screen" name="screenbackground" checked="checked"> Change Background Colour</label>
                                </div>
                                <div class="clear"></div>
                                <div class="background_label selectColor">
                                    <label>Select Background Colour</label>
                                </div>
                                <div class="background_colorbox selectColor">
                                    <span id="pagePicker"></span>
                                </div>

                                <div class="clear"></div>
                                <!--background color start-->

                                <!-- background image start-->
                                <div class="backgroundImage">
                                    <label class="screenConnectRadio"><input type="radio" id="screen" name="screenbackground" > Change Background Image</label>
                                </div>
                                <div class="clear"></div>
                                <div class="background_label selectImage">
                                    <label>Select Background Image</label>
                                </div>
                                <div class="clear"></div>
                                <div class="change_image selectImage">
                                    <input type="file" id="screen-bg">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse2" alt="Image Not Found">
                                    <!-- <span>Image</span> -->
                                </div>
                                <div class="clear"></div>
                                <!-- background image end-->

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
                                        <li><input type="checkbox" class="feedbackReq" name="feedback"  value="1"></li>
                                        <li>
                                            <span class="icon_upload_img1 icon-feedback"></span>
                                            <input type="file" class="icon_upload">
                                        </li>
                                        <li class="two_text"><input type="text" value="Feedback" disabled="disabled" /><input type="text" placeholder="Email Id" class="feedbackEmail" value=""/></li>
                                        <li class="view_feedback"><input type="button" value="View"></li>
                                        <li></li>
                                    </ul>
                                </div>

                                <!--  <div class="contact-us">
                                     <ul>
                                         <li><input type="checkbox" class="contactReq"></li>
                                         <li>
                                             <span class="icon_upload_img1 icon-phone-handset"></span>
                                             <input type="file" class="icon_upload">
                                         </li>
                                         <li class="two_text"><input type="text" value="Contact Us" disabled="disabled" /><input type="text" placeholder="Email Id" class="contactusEmail"/></li>
                                         <li class="view_contactUs"><input type="button" value="View"></li>
                                         <li></li>
                                     </ul>
                                 </div> -->

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

                            <form id="uploadForm"  method="post" enctype="multipart/form-data">
                                <div class="design_menu_box createLogin">
                                    <input <?php if ($appname['is_signup'] != 0) { ?> checked="checked" <?php } ?> type="checkbox" value="1" name="createLoginInput" id="createLoginInput">
                                    <label for="createLoginInput" class="createLoginLabel">Create Login For Your User</label>
                                    <p class="createLoginpara">To enable sign up for your app users click on the check box. This will add a signup feature in your app as shown in preview.</p>
                                    <a href="#" class="createLoginAddProducts">Preview</a>
                                    <!-- <div class="background_label">
                                         <label>Choose Background Colour</label>
                                     </div>
                                     <div class="background_colorbox">
                                         <span disabled="disabled" id="pagePicker1" <?php
                                    //   if ($signData['background_color'] != '') {
                                    //      echo "style=background-color:" . $signData['background_color'];
                                    //   }
                                    ?> ></span>
 
                                     </div> -->
                                    <div class="content_label">
                                        <label>Add Company Logo</label>
                                    </div>
                                    <div class="content_textbox upload_img">
                                        <img class="cropImage editbrowse"  <?php
                                        if ($signData['company_logo'] != '') {
                                            echo "src=" . $signData['company_logo'];
                                        } else {
                                            echo "src=images/bg_img_500x500.jpg";
                                        }
                                        ?> onclick="uploadImg();">
                                        <input disabled="disabled" type="file" id="uploadImages" name="uploadImages">

                                    </div>
                                    <div class="content_label">
                                        <label>Description</label>
                                    </div>
                                    <div class="content_textbox upload_img">
                                        <input <?php if ($appname['is_signup'] != 0) { ?> <?php } else { ?>disabled="disabled"<?php } ?> type="text" value="<?php echo $signData['description']; ?>" name="description" id="description" maxlength="200">
                                        <em>Maximum of 200 characters</em>
                                    </div>
                                    <div class="content_label">
                                        <?php $loginData = explode(',', $signData['app_signupsource_id']); ?>
                                        <label>Login Via</label>
                                    </div>
                                    <div class="content_textbox upload_img">

                                        <div class="login_options">
                                            <input <?php if (in_array(1, $loginData)) { ?> checked="checked" <?php } ?> <?php if ($appname['is_signup'] != 0) { ?> <?php } else { ?>disabled="disabled"<?php } ?> type="checkbox" class="login_option" value="1" name="loginVia" id="facebook">
                                            <label for="facebook"><img src="images/create_login_fb.png"><span class="create_login_fb">facebook</span></label>
                                            <div class="fb_create_login_box" <?php
                                            if ($signData['fb_id'] != '') {
                                                echo "style='height: 61px;'";
                                            }
                                            ?>>
                                                <input <?php if ($appname['is_signup'] != 0) { ?> <?php } else { ?>disabled="disabled"<?php } ?> type="text" id="facebook_app_id" name="facebook_app_id" value="<?php echo $signData['fb_id']; ?>" placeholder="Please enter facebook app id.">
                                                <a href="<?php echo $basicUrl ?>docs/Facebook_App_ID_Tutorial.pdf" target="_blank">Know more about facebook app id.</a>
                                            </div>
                                        </div>

                                        <div class="login_options">
                                            <input <?php if (in_array(2, $loginData)) { ?> checked="checked" <?php } ?> <?php if ($appname['is_signup'] != 0) { ?> <?php } else { ?>disabled="disabled"<?php } ?> type="checkbox" class="login_option" value="2"  name="loginVia" id="email" <?php
                                            if ($apppage == "create") {
                                                echo "checked=checked";
                                            }
                                            ?> >
                                            <label for="email"><img src="images/create_login_email.png"><span class="create_login_email">Email</span></label>
                                        </div>


                                    </div>


                                    <div class="clear"></div>
                                </div>
                            </form> 
                            <div class="design_menu_box">
                                <div class="catalogue_add_feature contactus">
                                    <div class="catalogue_input_group">
                                        <input <?php if ($appname['contactus_email'] != '') { ?> checked="checked" <?php } ?> type="checkbox" id="contact" class="contactReq" name="is_contactus"  value="1">
                                        <label for="contact">Contact Us</label>
                                        <div class="clear"></div>
                                        <!-- a href="#">Preview</a -->
                                    </div>
                                    <div class="catalogue_detail_input">
                                        <ul>
                                            <li><label>Email id*:</label></li>
                                            <li><input type="text" placeholder="Enter email id" class='contactusEmail'  maxlength="100" name="contactus_email" value="<?php echo isset($appname['contactus_email']) ? $appname['contactus_email'] : ''; ?>"><!-- em>@</em><input type="text" placeholder="domainname.com" / --><div class="clear"></div><span>Enter email id to where you want your users to contact you for help.</span></li>
                                            <li><a href="javascript:void(0);" class='view_contactUs'>Preview</a></li>
                                            <div class="clear"></div>
                                        </ul>
                                    </div>
                                    <div class="catalogue_detail_input">
                                        <ul>
                                            <li><label>Support No. :</label></li>
                                            <li><input type="text" placeholder="Enter phone number" class="contactusNo" maxlength="16" minLength="5" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" name="contactus_no" value="<?php echo isset($appname['contactus_phone']) ? $appname['contactus_phone'] : ''; ?>">
                                                <span id="error" style="color: Red; display: none;width:100%;font-style: italic;margin-bottom: 6px;">* Input digits (0 - 9)</span>
                                                <div class="clear"></div><span>Enter your support phone number where you want your users to contact you for help.</span></li>
                                            <div class="clear"></div>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <a href="javascript:void(0)" id="save" class="make_app_next2 saveAndcontinue">Save & Continue</a>
                            <div class="addPage">Add New Screen</div>


                        </div>
                        <div class="clear"></div>
                    </div> 
                    <div class="add_page">

                        <div class="mid_right_box">

                            <div class="mid_right_box_head">
                                <h1>Choose Screen Layout <b>( Click to Choose )</b></h1>
                                <h2>If you don't have a specific layout in mind for your app, no worries. You can choose one of the following pre-defined layout that best fits your need, and watch the changes happen on the Instappy App Preview Simulator in real time.</h2>
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
                                        <div class="new_theme" data-layout="4">
                                            <img src="<?php echo $basicUrl; ?>images/weblink.png" alt="Theme 4">
                                        </div>
                                        <div class="new_theme" data-layout="5">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme14.png" alt="Theme 14">
                                        </div>
                                        <div class="new_theme" data-layout="21">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme21.png" alt="Theme 21">
                                        </div>
                                        <div class="new_theme" data-layout="23">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme23.png" alt="Theme 23">
                                        </div>
                                        <div class="new_theme" data-layout="15">
                                            <img src="<?php echo $basicUrl; ?>images/add_theme15.png" alt="Theme 15">
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                </div>
                                <a href="javascript:void(0)" class="make_app_next2 saveAndcontinue">Save & Continue</a>

                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="mid_right_box">
                        <div class="mid_right_box_head ">
                            <h1 class="appDetailsave">Additional Features</h1>
                            <h2>Here you will find some features which can be added in your app for better user experience.</h2>
                            <div class="clear"></div>
                            <span> <i class="fa fa-angle-down"></i> </span>
                        </div>

                        <div class="mid_right_box_body GeneralDetails">
                            <div class="design_menu_box createLogin">
                                <input <?php if ($appname['jump_to'] != 0) { ?> checked="checked" <?php } ?> type="checkbox" id="addStore" value="1" name="addStore">
                                <label for="addStore" class="createLoginLabel">Add E-Store to app</label>
                                <p class="createLoginpara">Add products to your content app</p>
                                <div class="content_label">
                                    <label>Select Retail App</label>
                                </div>
                                <div class="content_textbox retailApp">

                                    <select id="optionalDropDown" name="selectedAppId" style="display:none">
                                        <option value="0">--Select--</option>

                                        <?php
                                        foreach ($getAppsResult as $AppData) {
                                            if ($appname['jump_to_app_id'] == $AppData['id']) {
                                                ?>

                                                <option  <?php if ($appname['jump_to_app_id'] == $AppData['id']) echo "selected=selected"; ?> value="<?php echo $AppData['id']; ?>"><?php echo $AppData['summary']; ?></option>
                                            <?php
                                            }
                                        }
                                        ?>


                                    </select>
                                    <select id="selectedAppId" name="selectedAppId">
                                        <option value="0">--Select--</option>

                                        <?php foreach ($getAppsResult as $AppData) {
                                            ?>

                                            <option  <?php if ($appname['jump_to_app_id'] == $AppData['id']) echo "selected=selected"; ?> value="<?php echo $AppData['id']; ?>"><?php echo $AppData['summary']; ?></option>
<?php } ?>


                                    </select>
                                    <span>OR</span>
                                    <a href="<?php echo $siteURL; ?>themes.php#/?q=30">Create New</a>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <a href="javascript:void(0)" id="save" class="make_app_next2 saveAndcontinue">Save & Continue</a>
                            <div class="addPage">Add New Screen</div>

                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="mid_right_box hidden_box">
                        <div class="mid_right_box_head choose_card_tip">
                            <h1>Add More Cards and Screens</h1>
                            <h2>Congratulations! You have reached the stage where you can start shaping up your app, and preview it on the Instappy App Preview Simulator to check if it is shaping out to be the way you want it to. Just in case you don’t like the way your app is shaping up, you can simply drag and re-position the elements on the Instappy App Preview Simulator to re-shape it.<br>These small screens are called Rich Media Cards. These small components fit together to create a screen. You can choose from a variety of cards to form different layouts.
                                .</h2>
                            <a href="#" class="read_more">Read More...</a>
                            <div class="clear"></div>
                            <span> <i class="fa fa-angle-down"></i> </span>
                        </div>
                        <div class="mid_right_box_body">
                            <div class="utility_api_cards">
                                <div class="utility_api_content clones container">

                                    <!-- Replacement Area Starts -->


                                    <div class="half_widget fontfamily layout1 layout3 layout9" data-component="7">
                                        <p class="widgetClose">x</p>
                                        <div class="half_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_img.jpg" alt="Image Not Found">
                                            <img src="<?php echo $basicUrl; ?>images/video_img_half.png" alt="" class="video_img lazy">


                                            <div class="clear"></div>
                                        </div>
                                        <div class="half_widget_text">
                                            <p>Heading</p>
                                        </div>
                                    </div>
                                    <!-- Image on text Component 58 23-5-16 start-->
                                    <div class="half_widget change_text_option layout1 layout3 layout9" data-component="58">
                                        <p class="widgetClose">x</p>
                                        <div class="half_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/text-change-option.jpg" alt="Image Not Found">
                                            <img src="<?php echo $basicUrl; ?>images/video_img_half.png" alt="" class="video_img lazy">
                                            <div class="change_text_area">
                                                <h2>Add image</h2>
                                                <p>& text</p>  
                                            </div>
                                            <div class="clear"></div>
                                        </div>

                                    </div>
                                    <!-- Image on text Component 58 23-5-16 end-->

                                    <!-- Image on text Component 58 23-5-16 start-->
                                    <div class="full_widget  change_text_option layout1 layout3 layout8 layout9 tohide_vdo" data-component="58" data-ss-colspan="2">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/text-change-option2.jpg" alt="Image Not Found" >
                                            <img src="<?php echo $basicUrl; ?>images/video_img_full.png" alt="" class="video_img lazy"> 
                                            <div class="change_text_area">
                                                <h2>Add image</h2>
                                                <p>& text</p>  
                                            </div>
                                            <div class="clear"></div>
                                        </div>

                                    </div>
                                    <!-- Image on text Component 58 23-5-16 end-->

                                    <!-- ariba Component 59 16-6-16 start-->
                                    <div class="language layout1 layout3 layout9" data-component="59" data-ss-colspan="2">
                                        <p class="widgetClose">x</p>
                                        <div class="ariba-text-change">
                                            <h2>Heading </h2>

                                            <p>Description</p>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <!-- ariba Component 59 16-6-16 end-->

                                    <div class="half_widget img_text layout1 layout3 layout9" data-component="19">
                                        <p class="widgetClose">x</p>
                                        <div class="half_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_img.jpg" alt="Image Not Found" class="lazy">
                                            <img src="<?php echo $basicUrl; ?>images/video_img_half.png" alt="" class="video_img lazy">
                                            <div class="half_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="half_widget_text">
                                            <a href="#"><span class="icon-share"></span></a>
                                            <a href="#"><span class="icon-favorite"></span></a>
                                        </div>
                                    </div>

                                    <div class="full_widget fontchange layout7 layout1 layout3 layout2 layout9" data-component="7" data-ss-colspan="2">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_full_img.jpg" alt="Image Not Found" >
                                            <img src="<?php echo $basicUrl; ?>images/video_img_full.png" alt="" class="video_img lazy">                                          
                                            <div class="clear"></div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p>Heading</p>
                                        </div>
                                    </div>


                                    <div class="map_full_widget" data-component="44" data-ss-colspan="2">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_full_map_img.jpg" alt="Image Not Found" >
                                            <div class="clear"></div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p>Heading</p>
                                        </div>
                                    </div>


                                    <div class="half_widget no_text" data-component="19">
                                        <p class="widgetClose">x</p>
                                        <div class="half_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_img.jpg" alt="Image Not Found" >
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
                                            <img src="<?php echo $basicUrl; ?>images/widget_full_img.jpg" alt="Image Not Found" >
                                            <img src="<?php echo $basicUrl; ?>images/video_img_full.png" alt="" class="video_img lazy">
                                            <div class="clear"></div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p>Heading</p>
                                            <a href="#"><span class="icon-favorite"></span></a>
                                        </div>
                                    </div>
                                    <div class="full_widget head_cont layout11" data-ss-colspan="2" data-component="19">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_full_img.jpg" alt="Image Not Found" >
                                            <img class="video_img lazy" alt="" src="<?php echo $basicUrl; ?>images/video_img_full.png">
                                            <div class="clear"></div>
                                            <div class="full_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="full_widget_text">
                                            <a href="#"><span class="icon-share"></span></a>
                                            <a href="#"><span class="icon-favorite"></span></a>
                                        </div>
                                    </div>
                                    <div class="full_widget long_text layout6" data-ss-colspan="2" data-component="13">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">

                                            <img src="<?php echo $basicUrl; ?>images/widget_full_img_only.jpg" alt="Image Not Found" >
                                            <img class="video_img lazy" alt="" src="<?php echo $basicUrl; ?>images/video_img_full.png">
                                            <div class="clear"></div>
                                            <div class="full_widget_img_text">
                                                <p class="img_heading" style="color: rgb(255, 255, 255); font-size: 16px;">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="full_widget_text">
                                            <div class="long_text_subheading">Sub Heading</div>
                                            <div class="long_text_content">Content</div>
                                            <a href="#"><span class="icon-share"></span></a>
                                            <a href="#"><span class="icon-favorite"></span></a>
                                        </div>
                                    </div>

                                    <div class="full_widget long_text layout6 with-out-share" data-ss-colspan="2" data-component="13">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">

                                            <img src="<?php echo $basicUrl; ?>images/widget_full_img_only.jpg" alt="Image Not Found" >
                                            <img class="video_img lazy" alt="" src="<?php echo $basicUrl; ?>images/video_img_full.png">
                                            <div class="clear"></div>
                                            <div class="full_widget_img_text">
                                                <p class="img_heading" style="color: rgb(255, 255, 255); font-size: 16px;">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="full_widget_text">
                                            <div class="long_text_subheading">Sub Heading</div>
                                            <div class="long_text_content">Content</div>

                                        </div>
                                    </div>

                                    <div class="big_widget layout8" data-ss-colspan="2" data-component="14">
                                        <p class="widgetClose">x</p>
                                        <div class="big_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_big_img.jpg" alt="Image Not Found" >
                                            <img class="video_img lazy" alt="" src="<?php echo $basicUrl; ?>images/video_img_big.png">
                                            <div class="clear"></div>
                                            <div class="big_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <div class="clear"></div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="big_widget_text" data-ss-colspan="2" data-component="14">
                                        <p class="widgetClose">x</p>
                                        <div class="big_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_big_img.jpg" alt="Image Not Found" >
                                            <img class="video_img lazy" alt="" src="<?php echo $basicUrl; ?>images/video_img_big.png">
                                            <div class="clear"></div>
                                            <div class="big_widget_img_text">
                                                <p class="img_heading">Heading</p>
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

                                    <div class="big_widget_text about" data-ss-colspan="2" data-component="14">
                                        <p class="widgetClose">x</p>
                                        <div class="big_widget_img">
                                            <img src="<?php echo $basicUrl; ?>images/widget_big_img.jpg" alt="Image Not Found" >
                                            <img class="video_img lazy" alt="" src="<?php echo $basicUrl; ?>images/video_img_big.png">
                                            <div class="clear"></div>
                                            <div class="big_widget_img_text">
                                                <p class="img_heading">Heading</p>
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
                                            <img src="<?php echo $basicUrl; ?>images/item_add_img.png" alt="Image Not Found" >
                                        </div>
                                        <div class="item_full_widget_right">
                                            <p class="item_heading">Heading</p>
                                            <p class="item_content">content</p>
                                            <div class="clear"></div>
                                            <p class="item_disc">Description</p>
                                        </div>
                                    </div>


                                    <div class="item_full_widget header_descrip layout12" data-ss-colspan="2" data-component="9">
                                        <p class="widgetClose">x</p>
                                        <div class="item_full_widget_left">
                                            <img src="<?php echo $basicUrl; ?>images/item_add_img.png" alt="Image Not Found" >
                                        </div>
                                        <div class="item_full_widget_right">
                                            <p class="item_heading">Heading</p>
                                            <div class="clear"></div>
                                            <p class="item_disc">Description</p>
                                        </div>
                                    </div>                          


                                    <div class="contact_widget layout8 layout7" data-ss-colspan="2" data-component="5">
                                        <p class="widgetClose">x</p>
                                        <p class="contactus">Heading</p>
                                        <div class="address">Description</div>
                                        <div class="phone">Text</div>
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
                                            <img src="<?php echo $basicUrl; ?>images/contact_img.png" alt="Image Not Found" >
                                        </div>
                                        <div class="contact_img_widget_right">
                                            <p class="contact_img_heading">Heading</p>
                                            <p class="contact_img_subheading">Subheading</p>
                                        </div>
                                    </div>

                                    <div class="contact_img_widget full" data-ss-colspan="2" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <div class="contact_img_widget_left">
                                            <img src="<?php echo $basicUrl; ?>images/contact_img.png" alt="Image Not Found" >
                                        </div>
                                        <div class="contact_img_widget_right">
                                            <p class="contact_img_heading">Heading</p>
                                            <p class="contact_img_subheading">Subheading</p>
                                        </div>
                                    </div>

                                    <div class="contact_img_widget call_mail layout1 layout3 layout9" data-component="29">
                                        <p class="widgetClose">x</p>
                                        <div class="contact_img_widget_left">
                                            <img src="<?php echo $basicUrl; ?>images/contact_img.png" alt="Image Not Found" >
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

                                    <div class="head_disc_widget" data-ss-colspan="2" data-component="5">
                                        <p class="widgetClose">x</p>
                                        <div class="head_disc_content">
                                            <h2>Heading</h2>
                                            <div class="head_disc_content_text">Add Description</div>
                                        </div> 
                                    </div>

                                    <div class="head_disc_widget social_icons layout8" data-ss-colspan="2" data-component="2">
                                        <p class="widgetClose">x</p>
                                        <div class="head_disc_content">
                                            <h2>Heading</h2>
                                            <div class="head_disc_content_text">Add Description</div>
                                            <div class="social_icons">
                                                <a><i class="fa fa-heart"></i></a>
                                                <a><i class="fa fa-share-alt"></i></a>
                                                <a><i class="fa fa-angle-right"></i></a>
                                            </div>
                                        </div> 
                                    </div> 

                                    <div class="tabbing_widget" data-ss-colspan="2" data-component="15">
                                        <div class="tabbing_widget_head">
                                            <ul class="tabs">
                                                <li class="active"><span>Tab 1</span></li>
                                                <li><span>Tab 2</span></li>
                                                <li><span>Tab 3</span></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                        <div class="tabbing_widget_body">
                                            <div class="tab_content" style="display:block;">
                                                <div class="tab_content_text">Add Description1</div>
                                            </div>
                                            <div class="tab_content" style="display:none;">
                                                <div class="tab_content_text">Add Description2</div>
                                            </div>
                                            <div class="tab_content" style="display:none;">
                                                <div class="tab_content_text">Add Description3</div>
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
                                    <div class="contact_card layout1 layout3 layout9" data-component="30">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="contact_card_img">
                                            <!--<img src="<?php echo $basicUrl; ?>images/scooter.jpg" alt="Image Not Found">-->
                                                <span class="icon-trophy56"></span>
                                            </div>
                                            <div class="contact_card_text">
                                                <p>Participate and Win</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a> 
                                    </div>

                                    <!-- form cards starts -->

                                    <div class="full_textbox common_form_box layout21" data-ss-colspan="2" data-component="24">
                                        <p class="widgetClose">x</p>
                                        <div class="full_textbox_inner">
                                            <input type="text" disabled placeholder="Hint Text" class="common_input_textbox" style="background:#fff;" maxlength="50">
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>

                                    <div class="label_left_textbox common_form_box layout21" data-ss-colspan="2" data-component="25">
                                        <p class="widgetClose">x</p>
                                        <div class="label_left">
                                            <label class="common_input_label">Label Text</label>
                                        </div>
                                        <div class="textbox_right">
                                            <input type="text" disabled placeholder="Hint Text" class="common_input_textbox" style="background:#fff;" maxlength="50">
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>

                                    <div class="full_textbox_Label common_form_box layout21" data-ss-colspan="2" data-component="26">
                                        <p class="widgetClose">x</p>
                                        <label class="common_input_label">Label Text</label>
                                        <div class="full_textbox_inner">
                                            <input type="text" disabled placeholder="Hint Text" class="common_input_textbox" style="background:#fff;" maxlength="50">
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>

                                    <div class="full_textbox_icon common_form_box layout21" data-ss-colspan="2" data-component="52">
                                        <p class="widgetClose">x</p>
                                        <div class="full_textbox_inner">
                                            <input type="text" disabled placeholder="Hint Text" class="common_input_textbox" style="background:#fff;" maxlength="50">
                                            <div class="common_icon_box">
                                                <img src="<?php echo $basicUrl; ?>images/form_icon_bg.png">
                                            </div>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>  

                                    <div class="full_textarea common_form_box layout21" data-ss-colspan="2" data-component="28">
                                        <p class="widgetClose">x</p>
                                        <div class="full_textarea_inner">
                                            <textarea disabled placeholder="Hint Text" class="common_input_textarea notEdit" style="background:#fff;" maxlength="50"></textarea>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div> 

                                    <div class="label_left_textarea common_form_box layout21" data-ss-colspan="2" data-component="51">
                                        <p class="widgetClose">x</p>
                                        <div class="textarea_label_left">
                                            <label class="common_input_label">Label Text</label>
                                        </div>
                                        <div class="textarea_right">
                                            <textarea disabled placeholder="Hint Text" class="common_input_textarea notEdit" style="background:#fff;" maxlength="50"></textarea>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>

                                    <div class="full_textarea_Label common_form_box layout21" data-ss-colspan="2" data-component="54">
                                        <p class="widgetClose">x</p>
                                        <label class="common_input_label">Label Text</label>
                                        <div class="full_textarea_inner">
                                            <textarea disabled placeholder="Hint Text" class="common_input_textarea notEdit" style="background:#fff;" maxlength="50"></textarea>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>

                                    <div class="full_textarea_icon common_form_box layout21" data-ss-colspan="2" data-component="36">
                                        <p class="widgetClose">x</p>
                                        <div class="full_textarea_inner">
                                            <textarea disabled placeholder="Hint Text" class="common_input_textarea notEdit" style="background:#fff;" maxlength="50"></textarea>
                                            <div class="common_icon_box">
                                                <img src="<?php echo $basicUrl; ?>images/form_icon_bg.png">
                                            </div>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>  

                                    <div class="full_selectbox common_form_box layout21" data-ss-colspan="2" data-component="37">
                                        <p class="widgetClose">x</p>
                                        <div class="full_selectbox_inner">
                                            <select disabled class="common_input_selectbox">
                                                <option>Select</option>
                                            </select>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>

                                    <div class="label_left_selectbox common_form_box layout21" data-ss-colspan="2" data-component="38">
                                        <p class="widgetClose">x</p>
                                        <div class="select_label_left">
                                            <label class="common_input_label">Label Text</label>
                                        </div>
                                        <div class="selectbox_right">
                                            <select disabled class="common_input_selectbox">
                                                <option>Select</option>
                                            </select>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>

                                    <div class="radio_input_box common_form_box layout21 clearfix" data-ss-colspan="2" data-component="40">
                                        <p class="widgetClose">x</p>
                                        <div class="radio_input_box_inner" data-index="0">
                                            <div class="radio_left">
                                                <input type="radio">
                                            </div>
                                            <div class="radio_label_right">
                                                <label class="common_input_label">Label Text</label>
                                            </div>
                                        </div>
                                        <div class="radio_input_box_inner" data-index="1">
                                            <div class="radio_left">
                                                <input type="radio">
                                            </div>
                                            <div class="radio_label_right">
                                                <label class="common_input_label">Label Text</label>
                                            </div>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>

                                    <div class="check_input_box common_form_box layout21 clearfix" data-ss-colspan="2" data-component="39">
                                        <p class="widgetClose">x</p>
                                        <div class="check_input_box_inner" data-index="0">
                                            <div class="check_left">
                                                <input type="checkbox">
                                            </div>
                                            <div class="check_label_right">
                                                <label class="common_input_label">Label Text</label>
                                            </div>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="full_description common_form_box layout21" data-ss-colspan="2" data-component="53">
                                        <p class="widgetClose">x</p>
                                        <div class="full_description_inner">
                                            <p class="common_description">Description - Lorem ipsum dolor sit amet, eos eripuit voluptatibus in. Est ad consul equidem insolens, eam eu luptatum. </p>
                                        </div>
                                    </div>

                                    <div class="full_label common_form_box layout21" data-ss-colspan="2" data-component="55">
                                        <p class="widgetClose">x</p>
                                        <label class="common_input_label">Label Text</label>
                                        <div class="common_input_overlay"></div>
                                    </div> 


                                    <!--  <div class="full_button common_form_box layout21" data-ss-colspan="2" data-component="45">
                                          <p class="widgetClose">x</p>
                                          <input type="submit" value="Button" class="common_main_color">
                                          <div class="common_input_overlay"></div>
                                      </div>
  
                                      <div class="small_button common_form_box layout21" data-ss-colspan="2" data-component="45">
                                          <p class="widgetClose">x</p>
                                          <input type="submit" value="Button" class="common_main_color">
                                          <div class="common_input_overlay"></div>
                                      </div> -->


                                    <div class="label_left_tabbtn common_form_box layout21" data-ss-colspan="2" data-component="47">
                                        <p class="widgetClose">x</p>
                                        <div class="tabbtn_label_left">
                                            <label class="common_input_label">Label Text</label>
                                        </div>
                                        <div class="tabbtn_right">
                                            <ul class="clearfix">
                                                <li class="active"><a href="#" class="common_main_color">Text 1</a></li>
                                                <li><a href="#">Text 2</a></li>
                                            </ul>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>





                                    <div class="label_left_switch_btn common_form_box layout21" data-ss-colspan="2" data-component="49">
                                        <p class="widgetClose">x</p>
                                        <div class="switch_label_left">
                                            <label class="common_input_label">Label Text</label>
                                        </div>
                                        <div class="switch_right">
                                            <img src="<?php echo $basicUrl; ?>images/android_btn.png">
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>


                                    <div class="full_textbox_date common_form_box layout21" data-ss-colspan="2" data-component="50">
                                        <p class="widgetClose">x</p>
                                        <div class="full_textbox_inner">
                                            <input type="date" disabled placeholder="Date" class="common_input_textbox" style="background:#fff;">
                                            <div class="common_icon_box">
                                                <span class="icon-calendar146"></span>
                                            </div>

                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>

                                    <div class="full_textbox_time common_form_box layout21" data-ss-colspan="2" data-component="50">
                                        <p class="widgetClose">x</p>
                                        <div class="full_textbox_inner">
                                            <input type="time" disabled placeholder="Time (hh/mm)" class="common_input_textbox" style="background:#fff;">

                                            <div class="common_icon_box">
                                                <span class="icon-clock"></span>
                                            </div>
                                        </div>
                                        <div class="common_input_overlay"></div>
                                    </div>  

                                    <!-- form cards ends -->

                                    <!--business screen start-->
                                    <div class="business-card business-card-name layout23" data-ss-colspan="2" data-component="60">
                                        <p class="widgetClose">x</p>
                                        <div class="business-contantarea"><span><img src="http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/panel/frameworkphp/images/name_icon.png" alt="name"></span>
                                            <p>Name</p>
                                        </div>
                                    </div>

                                    <div class="business-card business-card-phone layout23" data-ss-colspan="2" data-component="60">
                                        <p class="widgetClose">x</p>
                                        <div class="business-contantarea"><span><img src="http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/panel/frameworkphp/images/call_icon.png" alt="name"></span>
                                            <p>Phone</p>
                                        </div>
                                    </div>
                                    <div class="business-card business-card-email layout23" data-ss-colspan="2" data-component="60">
                                        <p class="widgetClose">x</p>
                                        <div class="business-contantarea"><span><img src="http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/panel/frameworkphp/images/mail_icon.png" alt="name"></span>
                                            <p>Email</p>
                                        </div>
                                    </div>
                                    <div class="business-card business-card-note layout23" data-ss-colspan="2" data-component="60">
                                        <p class="widgetClose">x</p>
                                        <div class="business-contantarea note-card-height"><span><img src="http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/panel/frameworkphp/images/edit_icon.png" alt="name"></span>
                                            <p>Note</p>
                                        </div>
                                    </div>
                                    <div class="business-card business-card-address layout23" data-ss-colspan="2" data-component="60">
                                        <p class="widgetClose">x</p>
                                        <div class="business-contantarea"><span><img src="http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/panel/frameworkphp/images/addresh.png" alt="name"></span>
                                            <p>Address</p>
                                        </div>
                                    </div>
                                    <div class="business-card business-card-map layout23" data-ss-colspan="2" data-component="62" business-longitude="0" business-latitude="0">
                                        <p class="widgetClose">x</p>
                                        <div class="mapview">
                                            <p>Map View</p>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <!--business screen end-->
                                  
                                  <!--Grid screen start-->
                                    <div class="half_widget round_widget layout15" data-component="61" >
                                      <p class="widgetClose">x</p>
                                      <div class="widget_inner">
                                        <div class="round_widget_imgbox"><img src="http://52.42.166.139/images/grid-circle.png">
                                          <div class="clear"></div>
                                        </div>
                                        <div class="round_widget_text">
                                          <p>Heading</p>
                                        </div>
                                      </div>
                                      
                                    </div>
                                  <!--Grid screen end-->

                                    <!-- Replacement Area Ends -->

                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="utility_api_cards">
                                <h2>Utility Cards</h2>
                                <p class="utility_cards">Utility Cards are predefined cards designed for your convenience. You can choose from a variety of cards on display, and drag them to the preview screen to see how they look. We’re are sure they will improve the look of your app and make it more user-friendly, so go ahead and try some.</p>
                                <a class="read_more">Read More...</a>
                                <div class="clear"></div>
                                <div class="utility_api_content clones container">

                                    <div class="audio layout1" data-ss-colspan='2'  data-component="22">

                                        <p class="widgetClose">x</p>

                                        <span>Play Audio</span>

                                        <img class="pus" src="images/pus.png" alt="Pause">
                                        <img class="bar" src="images/bar.jpg" alt="Pause"> 

                                    </div>

                                    <div class="festival-card layout1" data-component="6">

                                        <p class="widgetClose">x</p>

                                        <h2 class="festival-heading">Event List</h2>

                                        <ul class="festival-list">

                                            <li>
                                                <h3><span>Event 1</span> <sub>Event Date</sub> </h3>
                                            </li>
                                            <li>
                                                <h3><span>Event 2</span> <sub>Event Date</sub> </h3>
                                            </li>
                                            <li>
                                                <h3><span>Event 3</span> <sub>Event Date</sub> </h3>
                                            </li>

                                        </ul>

                                    </div> 


                                    <div class="calendar-card layout1" data-component="4">

                                        <p class="widgetClose">x</p>

                                        <h2 class="calendar-heading">Calendar</h2>

                                        <div class="calendar-date">
                                            <span class="date">Today's Date</span>

                                        </div>

                                        <div class="calendar-title">
                                            <div class="calendar-title-left">
                                                <span class="icon-calendar146"></span>
                                            </div>
                                            <div class="calendar-title-right">
                                                <h3 class="event-title">Title of the Event</h3>
                                                <h4 class="event-sub-title">Start Time - End Time</h4>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="utility_api_cards">
                                <h2>Social Cards</h2>
                                <p class="utility_cards">Social Card lets you display an icon of your social networking profile on your app, through which the user can follow you on your social network.</p>
                                <a class="read_more">Read More...</a>
                                <div class="clear"></div>
                                <div class="utility_api_content clones container">
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.facebook.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/facebook.png"  alt="Image Not Found" class="lazy">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Like us on </p>
                                                <h2>Facebook</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>

                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.twitter.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/twitter.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on </p>
                                                <h2>Twitter</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.picasa.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/picasa.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Connect with us on  </p>
                                                <h2>Picasa</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="https://www.grubhub.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/grubhub.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on </p>
                                                <h2>GrubHub</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.quora.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/quora.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on</p>
                                                <h2>Quora</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://plus.google.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/google.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Connect with us on </p>
                                                <h2>Google<sup>+</sup></h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <!--<div class="small_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.xyz.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/tastykhana.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Tasty Khana</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>-->
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.flicker.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/flickr.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on </p>
                                                <h2>Flickr</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.foodpanda.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/foodpanda.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on</p>
                                                <h2>Food Panda</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>  
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.instagram.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/instagram.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on  </p>
                                                <h2>Instagram</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.zomato.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/zomato.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on </p>
                                                <h2>Zomato</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.pinterest.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/pinterest.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on</p>
                                                <h2>Pinterest</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.youtube.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/youtube.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Subscribe to us on </p>
                                                <h2>YouTube</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.vimeo.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/vimeo.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Follow us on  </p>
                                                <h2>Vimeo</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.linkedin.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/linkedin.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Connect with us  </p>
                                                <h2>on LinkedIn</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>



                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.dininggrades.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/dining_grades.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on </p>
                                                <h2>Dining Grades</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.eatlocalonline.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/eat_local.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on</p>
                                                <h2>LocalEats</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.eat24hours.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/eat24.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on </p>
                                                <h2>Eat24</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.foursquare.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/Foursquare.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p> Foursquare</p>
                                                <h2> </h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.kayak.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/Kayak.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on </p>
                                                <h2>Kayak</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.opentable.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/opentable.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on </p>
                                                <h2>Opentable</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.zomato.com/restaurantFinder">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/reataurant_finder.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>View us on</p>
                                                <h2>Restaurant Finder</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.tinyowl.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/tinyowl.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on </p>
                                                <h2>TinyOwl</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.tripadvisor.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/tripadvisor.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on</p>
                                                <h2>Trip Advisor</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.zomato.com/urbanspoon">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/urbanspoon.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Recommend us on  </p>
                                                <h2>Urban Spoon</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.yelp.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/yelp.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on</p>
                                                <h2>Yelp</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.zagat.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/zagat.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Find us on </p>
                                                <h2>Zagat</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>



                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.agoda.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/agoda.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on</p>
                                                <h2>Agoda</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.asiatravel.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/asiatravel.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on</p>
                                                <h2>Asiatravel</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.burrp.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/burrp.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on</p>
                                                <h2>Burrp</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.ctrip.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/ctrip.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on </p>
                                                <h2>Ctrip</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.dineout.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/dineout.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on </p>
                                                <h2>Dineout</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.easydiner.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/easydiner.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on </p>
                                                <h2>Easydiner</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.expedia.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/expedia.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on </p>
                                                <h2>Expedia</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.hotel.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/hotel.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on </p>
                                                <h2>Hotels</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.justdial.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/justdial.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on </p>
                                                <h2>JustDial</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.justeat.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/justeat.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on </p>
                                                <h2>Justeat</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.mydala.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/mydala.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on</p>
                                                <h2>MyDala</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.otel.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/otel.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Rate us on</p>
                                                <h2>Otel</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.yatra.com/yourProfile">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/yatra.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Review us on </p>
                                                <h2>Yatra</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="Please put your RSS url.">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/rss.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p> RSS  </p>
                                                <h2>Feed</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/website.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Check our </p>
                                                <h2>Website</h2>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>
                                    <div class="small_widget custom_widget2 layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/contest.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Participate and  </p>
                                                <h2>Win</h2>

                                            </div>
                                            <div class="clear"></div>
                                        </a> 
                                    </div>


                                    <!--Edit able start -->
                                    <div class="small_widget custom_widget layout1 layout8 layout9" data-component="33">
                                        <p class="widgetClose">x</p>
                                        <a href="http://">
                                            <div class="small_widget_img">
                                                <img data-original="<?php echo $basicUrl; ?>images/demo_image.png" class="lazy" alt="Image Not Found">
                                            </div>
                                            <div class="small_widget_edit_text">
                                                <p> Heading </p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>
                                    <!--Edit able end -->





                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="utility_api_cards">
                                <h2>Social Feed Cards</h2>
                                <p class="utility_cards">Social Feed Card lets you display the content from your social networking profile to this app.</p>
                                <div class="utility_api_content clones container">
                                    <div class="social_feed layout1 layout8 layout9" data-component="42">
                                        <p class="widgetClose">x</p>

                                        <a href="http://www.facebook.com/yourProfile"><img data-original="images/FacebookFeed.jpg" class="lazy"></a>
                                    </div>
                                    <div class="social_feed layout1 layout8 layout9" data-component="43">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.twitter.com/yourProfile"><img data-original="images/TwitterFeed.jpg" class="lazy"></a>
                                    </div>
                                    <!--Full width cards-->
                                    <div class="social_feed layout1 layout8 layout9" data-ss-colspan="2" data-component="42">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.facebook.com/yourProfile"><img data-original="images/FacebookFeedFull.png" class="lazy"></a>
                                    </div>
                                    <div class="social_feed layout1 layout8 layout9" data-ss-colspan="2" data-component="43">
                                        <p class="widgetClose">x</p>
                                        <a href="http://www.twitter.com/yourProfile"><img data-original="images/TwitterFeedFull.png" class="lazy"></a>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="addPage" style="margin-right:0px;">Add New Screen</div>
                            <div class="clear"></div>
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
                                    <em>Maximum of 18 Characters.</em>
                                </div>
                                <div class="content_label">
                                    <label>Keywords:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type='text' class='contentKeywords' maxlength="100">
                                    <em>Enter comma separated keywords for your screen. Max characters 100.</em>
                                </div>
                            </div>
                            <h4 class="change_prop">Customise Your Cards</h4>
                            <p class="common_change_card">Each card that you create is fully customisable. You can at any time add or change the Heading, Image, Background Colour, Fonts, Embed a Video, and Create Links to these cards</p>


                            <div class="design_menu_box bannerEdit bannercropdiv">

                                <h2>Add Customised Banners</h2>
                                <p>Give your app a trendy look by using upto 3 banner images. Banner image is the first thing your customers will notice in your app, and hence, it should contain the featured services that you offer.<br>Though you can use the image in any aspect ratio, and Instappy will crop it to best fit the screen. However, it is recommended that you use images which are 1080 x 540 pixels, and not more than 2 MB in size.</p>

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
                            </div>

                            <div class="design_menu_box widgetEdit fullItemEdit">

                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img otherimgs">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <!-- <span>Image</span> -->
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box eventItemEdit">

                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img otherimgs">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse eventcircul m-circle" alt="Image Not Found">
                                    <!-- <span>Image</span> -->
                                </div>
                                <div class="clear"></div>
                            </div>
                            <!--circle theme start-->
                            <div class="design_menu_box circleItemEdit">
                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img otherimgs">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse eventcircul" alt="Image Not Found">
                                </div>
                                <div class="clear"></div>
                            </div>
                            <!--circle theme end-->

                            <div class="design_menu_box contactImgEdit">

                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img otherimgs">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <!-- <span>Image</span> -->
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
                                    <em>Maximum of 20 Characters</em>
                                    <select class="content_font">
                                        <option selected>Helvetica</option>
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
                                    <span class="editPicker"></span>
                                </div>
                                <div class="clear"></div>
                                <!--background color change start 14-6-16-->

                                <div class="content_label half-bg-change">
                                    <label>Background Color Change:</label>
                                </div>
                                <div class="content_textbox half-bg-change">
                                    <span class="editPicker24"></span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label full-bg-change">
                                    <label>Background Color Change:</label>
                                </div>
                                <div class="content_textbox full-bg-change">
                                    <span class="editPicker25"></span>
                                </div>


                                <!--background color change end 14-6-16-->
                                <div class="clear"></div>
                                <div class="content_label subHeadingOption">
                                    <label>Sub-Headline:</label>
                                </div>
                                <div class="content_textbox subHeadingOption">
                                    <input type="text" class="subHeading3" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
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
                                    <span class="editPicker15"></span>

                                </div>
                                <div class="clear"></div>
                            </div>

                            <!-- 11-7-16 start-->
                            <div class="design_menu_box compoNent58">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Headline:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
                                    <select class="content_font">
                                        <option selected>Helvetica</option>
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


                                    </select >
                                    <span class="editPicker"></span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label subHeadingOption">
                                    <label>Sub-Headline:</label>
                                </div>
                                <div class="content_textbox subHeadingOption">
                                    <input type="text" class="subHeading3" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
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

                                    </select >
                                    <span class="editPicker15"></span>

                                </div>
                                <div class="clear"></div>
                            </div>
                            <!-- 11-7-16 end-->

                            <!--circle theme start-->
                            <div class="design_menu_box circleTheme">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Headline:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
                                    <select class="content_font25">
                                        <option selected>Helvetica</option>
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


                                    </select >
                                    <span class="editPicker30"></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <!--circle theme end-->

                            <!--Business Widget start-->
                            <div class="design_menu_box business-card-edit">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label business-name-box">
                                    <label>Name :</label>
                                </div>
                                <div class="content_textbox business-name-box">
                                    <input type="text" class="business-name" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label business-phone-box">
                                    <label>Phone :</label>
                                </div>
                                <div class="content_textbox business-phone-box">
                                    <input type="tel" class="business-phone" maxlength="10">
                                    <em>Maximum of 10 Number</em>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label business-email-box">
                                    <label>Email :</label>
                                </div>
                                <div class="content_textbox business-email-box">
                                    <input type="email" class="business-email">
                                    <!--<em>Maximum of 20 Characters</em>-->
                                </div>
                                <div class="clear"></div>
                                <div class="content_label business-note-box">
                                    <label>Note :</label>
                                </div>
                                <div class="content_textbox business-note-box">
                                    <textarea class="business-note subHeading1 nicEditor" maxlength="20"></textarea>
                                    <em>Maximum of 20 Characters</em>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label business-addres-box">
                                    <label>Addres :</label>
                                </div>
                                <div class="content_textbox business-addres-box">
                                    <input type="text" class="business-addres" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box business-map-card">
                                <h2>Location Details</h2>
                                <p>This can be one of the coolest features on your app. Just add the coordinates of your location, and your consumers will be able to see exactly where you are. This makes it easier for them to locate you.</p>
                                <div class="content_label">
                                    <label>Latitude:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="business-latitude" maxlength="20">
                                </div>
                                <div class="content_label">
                                    <label>Longitude:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="business-longitude" maxlength="20">
                                </div>
                                <div class="clear"></div>
                            </div>
                            <!--Business Widget end-->

                            <!-- social crd's heading and sub-heading option start-->
                            <div class="design_menu_box socialwidgetEdit">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Headline:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit" maxlength="15">
                                    <em>Maximum of 15 Characters</em>
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
                                    <span class="editPicker"></span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label subHeadingOption">
                                    <label>Sub-Headline:</label>
                                </div>
                                <div class="content_textbox subHeadingOption">
                                    <input type="text" class="subHeading3" maxlength="15">
                                    <em>Maximum of 15 Characters</em>
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
                                    <span class="editPicker15"></span>

                                </div>
                                <div class="clear"></div>
                            </div>
                            <!-- social crd's heading and sub-heading option end-->

                            <div class="design_menu_box festivalList">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label festivalList content_textboxevent">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox content_textboxevent">
                                    <input type="text" class="eventCardTitle" maxlength="10">
                                    <em>Maximum of 10 Characters</em>                                


                                </div>
                                <div class="clear"></div>


                                <span class="eventcomp6">
                                    <hr class="editDevider">
                                    <h2>Edit Event Details</h2>

                                    <label class="m-label">All Day Event <input class="sh-checkbox" type="checkbox"  value="sh-option" id="all-day"></label>

                                    <div class="clear"></div>

                                    <div class="date-picker"> 
                                        <span>Date Format</span>
                                        <select class="date-select" dateformat="" name="pickMep">
                                            <option value="MM-DD-YYYY" class="pickit">MM-DD-YY</option>
                                            <option value="DD-MM-YYYY" class="pickit">DD-MM-YY</option>
                                            <option value="YYYY-MM-DD" class="pickit">YY-MM-DD</option>
                                        </select>
                                    </div>

                                    <div class="clear"></div>

                                    <div class="content_label">
                                        <label>Event Title:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" class="eventTitle" maxlength="10">
                                        <em>Maximum of 10 Characters</em>

                                    </div>
                                    <div class="clear"></div>
                                    <div class="content_label">
                                        <label>Event Date:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" class="eventDate">

                                    </div>
                                    <div class="clear"></div>
                                    <div class="content_label sh-hide-one">
                                        <label>Start Time:</label>
                                    </div>
                                    <div class="content_textbox sh-hide-one">
                                        <select class="startTime fullSelect">
                                            <option>1:00</option>
                                            <option>2:00</option>
                                            <option>3:00</option>
                                            <option>4:00</option>
                                            <option>5:00</option>
                                            <option>6:00</option>
                                            <option>7:00</option>
                                            <option>8:00</option>
                                            <option>9:00</option>
                                            <option>10:00</option>
                                            <option>12:00</option>
                                            <option>13:00</option>
                                            <option>13:00</option>
                                            <option>14:00</option>
                                            <option>15:00</option>
                                            <option>16:00</option>
                                            <option>17:00</option>
                                            <option>18:00</option>
                                            <option>19:00</option>
                                            <option>20:00</option>
                                            <option>21:00</option>
                                            <option>22:00</option>
                                            <option>23:00</option>
                                            <option>24:00</option>
                                        </select>
                                    </div>

                                    <div class="clear"></div>
                                    <div class="content_label sh-hide-one">
                                        <label>End Time:</label>
                                    </div>
                                    <div class="content_textbox sh-hide-one">
                                        <select class="endTime fullSelect">
                                            <option>1:00</option>
                                            <option>2:00</option>
                                            <option>3:00</option>
                                            <option>4:00</option>
                                            <option>5:00</option>
                                            <option>6:00</option>
                                            <option>7:00</option>
                                            <option>8:00</option>
                                            <option>9:00</option>
                                            <option>10:00</option>
                                            <option>12:00</option>
                                            <option>13:00</option>
                                            <option>13:00</option>
                                            <option>14:00</option>
                                            <option>15:00</option>
                                            <option>16:00</option>
                                            <option>17:00</option>
                                            <option>18:00</option>
                                            <option>19:00</option>
                                            <option>20:00</option>
                                            <option>21:00</option>
                                            <option>22:00</option>
                                            <option>23:00</option>
                                            <option>24:00</option>
                                        </select>
                                        <div class="clear"></div>
                                        <select class="content_font24">
                                            <option>Helvetica</option>
                                            <option>Helvetica Neue</option>
                                            <option>Helvetica Light</option>
                                            <option value="Open Sans">Open Sans</option>
                                            <option value="Didact Gothic">Gothic</option>
                                            <option value="EB Garamond">Garamond</option>
                                            <option value="Roboto">Roboto</option>
                                            <option value="sans-serif">Sans Serif</option>
                                        </select>
                                        <span class="editPicker29"></span>
                                    </div>

                                    <div class="clear"></div>
                                </span>



                                <a class="make_app_next addList" style="margin-right:15px;" href="#">Add Event</a>
                                <div class="clear"></div>
                            </div>
                            <!-- 28-6-16 start-->
                            <div class="design_menu_box eventBackground" style="display: block;">
                                <h2>Choose Background Colour</h2>
                                <p>This will be the background colour of this screen.</p>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="eventPicker"></span>
                                </div>
                            </div>
                            <!-- 28-6-16 end-->

                            <div class="design_menu_box calenderDate">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="calenderHead" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
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
                                    <span class="editPicker" style="background-color: rgb(85, 85, 85);"></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box audioWidget">
                                <h2>Add Content to your app</h2>
                                <p>And here comes the fun part! This is the section where you can add content to your app, and provide the consumer with the information they are looking for.</p>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="audioHead" maxlength="20">
                                    <em>Maximum of 20 Characters</em>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label">
                                    <label>Audio Link:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="audioLink" id="mp_source">
                                   <!-- <input type="button" id="saveAudio" style="margin-top: 10px;background:#ffcc00; height:39px; padding:10px; color:#fff; border:none;" value="Send Audio"> -->
                                    <em>Add the url for mp3 music you love or add secret link of your Soundcloud track</em>
                                    <audio style="display:none;" id="track" width="320" height="176" controls>
                                        <source id="set_source"  type="audio/mp3" >
                                    </audio>
                                    <p id="time" style="display:none;"></p>
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
                                <!--background color change start 14-6-16-->

                                <!-- <div class="content_label half-bg-change">
                                     <label>Background Color Change:</label>
                         </div>
                                 <div class="content_textbox half-bg-change">
                                         <span class="editPicker26"></span>
                                 </div>
                             <div class="clear"></div>-->

                                <!--background color change end 14-6-16-->
                            </div>

                            <div class="design_menu_box smallWidget scroll_read-more">
                                <h2>Edit API Card</h2>
                                <div class="content_label">
                                    <label>Enter Link:</label>
                                </div>
                                <div class="content_textbox" style="margin-left:190px;">
                                    <input type="text" class="apiUrl">
                                    <em>Enter the link of the website you want to direct  your customers to. For e.g. if you wish your customers to review your business on Zomato, enter the link of the Zomato review form for your business here.</em>
                                    <a class="read_more">Read More...</a>
                                    <div class="clear"></div>
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
                                    <input type="text" class="heading" maxlength="15">
                                    <em>Don't forget to mention the country code and the area code along with the number.</em>
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
                                <div class="content_label headline">
                                    <label>Headline:</label>
                                </div>
                                <div class="content_textbox headline">
                                    <input type="text" class="heading" maxlength="15">
                                    <em>Maximum of 15 Characters</em>
                                    <input type="text" class="heading header_descrip" maxlength="140">
                                    <em class="header_descrip">Maximum of 140 Characters</em>
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
                                <div class="content_label optional_text">
                                    <label>Optional Text:</label>
                                </div>
                                <div class="content_textbox optional_text">
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
                                <div class="content_textbox headline" >
                                    <input type="text" class="subHeading2 optional_text" maxlength="22">
                                    <em>Maximum of 22 Characters</em>
                                    <input type="text" class="subHeading2 header_descrip" maxlength="140">
                                    <em class="header_descrip">Maximum of 140 Characters</em>
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
                                    <textarea class="subHeading1" maxlength="100"></textarea>
                                    <!-- <select class="content_font11">
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
                                    </select > -->
                                    <!--                                    <a href="#"><b class="content_bold11">B</b></a>
                                                                        <a href="#"><i class="content_italics11">I</i></a>
                                                                        <a href="#"><u class="content_underline11">U</u></a>-->
                                    <!-- <span class="editPicker12"></span> -->
                                    <em>If you are copying text from a different source, it is recommended that you first paste it to Notepad to remove any formatting.</em>
                                    <a class="read_more" href="#">Read More...</a>
                                    <div class="clear"></div>
                                </div>
                                <div class="content_label">
                                    <label>Text:</label>
                                </div>
                                <div class="content_textbox" >
                                    <textarea class="subHeading2" maxlength="25"></textarea>
                                    <!-- <select class="content_font12">
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
                                                                 <a href="#"><b class="content_bold12">B</b></a>
                                                                        <a href="#"><i class="content_italics12">I</i></a>
                                                                        <a href="#"><u class="content_underline12">U</u></a>
                                    <span class="editPicker13"></span> -->
                                    <em>If you are copying text from a different source, it is recommended that you first paste it to Notepad to remove any formatting.</em>
                                    <a class="read_more" href="#">Read More...</a>
                                    <div class="clear"></div>
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
                                    <div class="clear"> </div>
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
                                    <div class="clear"> </div>
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
                                    <input type="text" class="heading" maxlength="50">
                                    <em>Maximum of 50 characters</em>
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
                                    <textarea class="subHeading1 nicEditor"></textarea>
                                   <!-- <select class="content_font16">
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
                                    <span class="editPicker17"></span>-->
                                    <em>If you are copying text from a different source, it is recommended that you first paste it to Notepad to remove any formatting.</em>
                                    <a class="read_more" href="#">Read More...</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <!-- ariba Component 59 16-6-16 start -->
                            <div class="design_menu_box aribaWidget">
                                <h2>Choose Background Colour</h2>
                                <p>This will be the background colour of this screen.</p>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="editPicker26"></span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label">
                                    <label>Sub-heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <textarea class="ariba-text notEdit" maxlength="100"></textarea>
                                    <em>Maximum of 100 Characters</em>
                                    <select class="content_font22">
                                        <option value="Helvetica">Helvetica</option>
                                        <option value="Helvetica Neue">Helvetica Neue</option>
                                        <option value="Helvetica Light">Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <!--
                                                                        <select class="content_fontsize22">
                                                                            <option>12</option>
                                                                            <option>13</option>
                                                                            <option>14</option>
                                                                        </select >
                                    -->
                                    <span class="editPicker27"></span>
                                </div>
                                <div class="content_label">
                                    <label>Description:</label>
                                </div>
                                <div class="content_textbox">
                                    <textarea maxlength="100" class="ariba-text2 notEdit"></textarea>
                                    <em>Maximum of 100 Characters</em>
                                    <select class="content_font23">
                                        <option value="Helvetica">Helvetica</option>
                                        <option value="Helvetica Neue">Helvetica Neue</option>
                                        <option value="Helvetica Light">Helvetica Light</option>
                                        <option value="Open Sans">Open Sans</option>
                                        <option value="Didact Gothic">Gothic</option>
                                        <option value="EB Garamond">Garamond</option>
                                        <option value="Roboto">Roboto</option>
                                        <option value="sans-serif">Sans Serif</option>
                                    </select>
                                    <!--
                                                                        <select class="content_fontsize23">
                                                                            <option>12</option>
                                                                            <option>14</option>
                                                                            <option>16</option>
                                                                            <option>18</option>
                                                                            <option>20</option>
                                                                            <option>22</option>
                                                                            <option>24</option>
                                                                        </select >
                                    -->
                                    <span class="editPicker28"></span>
                                </div>
                            </div>

                            <!-- ariba Component 59 16-6-16 end -->


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

                                    </div>

                                    <h2>Description for Tab 1</h2>

                                    <div class="content_label">
                                        <label>Change Text:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <textarea class="tabContent"></textarea>
                                        <!--<select class="tabcontent_font">
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
                                        <em>If you are copying text from a different source, it is recommended that you first paste it to Notepad to remove any formatting.</em>
                                        <a class="read_more" href="#">Read More...</a>
                                        <div class="clear"></div>
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

                                    </div>

                                    <h2>Description for Tab 2</h2>

                                    <div class="content_label">
                                        <label>Change Text:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <textarea class="tabContent"></textarea>
                                        <!-- <select class="tabcontent_font">
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
                                        <a href="#"><u class="tabcontent_underline">U</u></a> -->
                                        <!--<span class="tabeditPicker"></span>-->
                                        <em>If you are copying text from a different source, it is recommended that you first paste it to Notepad to remove any formatting.</em>
                                        <a class="read_more" href="#">Read More...</a>
                                        <div class="clear"></div>
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

                                    </div>

                                    <h2>Description for Tab 3</h2>

                                    <div class="content_label">
                                        <label>Change Text:</label>
                                    </div>
                                    <div class="content_textbox">
                                        <textarea class="tabContent"></textarea>
                                        <!-- <select class="tabcontent_font">
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
                                        <a href="#"><u class="tabcontent_underline">U</u></a> -->
                                        <!--<span class="tabeditPicker"></span>-->
                                        <em>If you are copying text from a different source, it is recommended that you first paste it to Notepad to remove any formatting.</em>
                                        <a class="read_more" href="#">Read More...</a>
                                        <div class="clear"></div>
                                    </div>
                                    <div style="margin-top:35px;margin-bottom:35px;border-top:2px solid #efefef"></div>
                                </div>
                            </div>

                            <div class="design_menu_box bigWidgetEdit">
                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" class="editbrowse_img otherimgs">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <!-- <span>Image</span> -->
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

                                    <span class="editPicker"></span>
                                </div>
                            </div>

                            <div class="design_menu_box bigWidgetTextEdit">
                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" class="editbrowse_img otherimgs">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <!-- <span>Image</span> -->
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

                            <div class="design_menu_box webLinkEdit">
                                <h2>Add weblink to your app</h2>
                                <div class="content_label">
                                    <label>Enter Web Link:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="weblink_textbox_edit">
                                </div>
                            </div>
                            <div class="design_menu_box fullWidgetLongText">
                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" class="editbrowse_img otherimgs">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <!-- <span>Image</span> -->
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
                                    <textarea class="big_text_head6 notEdit" maxlength="50"></textarea>
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
                                    <!-- <a href="#"><b class="content_bold17">B</b></a>
                                      <a href="#"><i class="content_italics17">I</i></a>
                                      <a href="#"><u class="content_underline17">U</u></a> -->
                                    <span class="editPicker19"></span>
                                </div>
                                <div class="content_label">
                                    <label>Description:</label>
                                </div>
                                <div class="content_textbox">
                                    <textarea maxlength="2000" class="big_text_head7 notEdit"></textarea>
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
                                    <!-- <a href="#"><b class="content_bold18">B</b></a>
                                       <a href="#"><i class="content_italics18">I</i></a>
                                       <a href="#"><u class="content_underline18">U</u></a> -->
                                    <span class="editPicker20"></span>
                                </div>
                            </div>

                            <div class="design_menu_box bigWidgetAboutEdit">
                                <h2>Choose Card Image</h2>
                                <p>Choose a card image (preferable size is <span>2 MB</span> or less)</p>
                                <div class="change_image">
                                    <input type="file" class="editbrowse_img otherimgs">
                                    <img src="<?php echo $basicUrl; ?>images/browse_full_img.jpg" class="editbrowse" alt="Image Not Found">
                                    <!-- <span>Image</span> -->
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

                            <div class="design_menu_box social_sharing">
                                <input type="checkbox" id="socialshare" checked="true">

                                <label class="contentss" for="socialshare">Share on Social Media <span class="icon-share"></span></label>
                                <div class="clear"></div>
                                <!--<div class="content_label">
                                    <label>Facebook app id</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="facebook_id_text" maxlength="25">
                                    <em>Facebook APP ID is required for sharing text on Facebook via Mobile Apps<a href="https://developers.facebook.com/docs/apps/register" target="_blank">Learn more...</a></em>
                                </div>-->
                                <div class="content_label">
                                    <label>Share Text:</label>
                                </div>
                                <div class="content_textbox">
                                    <textarea class="shared_text notEdit" maxlength="140"></textarea>
                                    <em>Maximum of 140 Characters</em>
                                </div>
                                <div class="content_label">
                                    <label>Enter Link:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="shared_link" maxlength="140">
                                    <em>This link will open on clicking on the post shared on social media</em>
                                </div>
                            </div>
                            <!-- Social icons Widget Click-Star-->
                            <div class="design_menu_box social_icon_replace">
                                <h2>Replace Arrow With</h2>
                                <p>Arrow is used to go to next page. you can replace the arrow with:</p>
                                <div class="content_label">
                                    <label>Text:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit" maxlength="10">
                                    <em>Maximum of 10 Characters</em>
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
                                    <span class="editPicker"></span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label">
                                    <label>Icon:</label>
                                </div>
                                <div class="content_textbox add_icon_box"> 
                                    <img src="<?php echo $basicUrl; ?>images/menu_img.jpg" alt="Image Not Found" class="icon_upload_img">
                                    <input type="file" class="icon_upload">
                                </div>

                                <div class="clear"></div>
                            </div>
                            <!-- Social icons Widget Click-End-->  
                            <div class="design_menu_box widgetLinkEdit">
                                <div class="addPage Widget">Add New Screen For Your Card </div>
                                <h2 class="card_connect">Connect Your Card to a Screen</h2> 
                                <div class="clear"></div>
                                <div class="background_label">
                                    <label class="screenConnectRadio"><input type="radio" id="screen" name="categoryRadio" checked> Choose a Screen:</label>
                                </div>
                                <div class="background_colorbox">
                                    <select class="widgetlinkSelector">
                                        <option value="0">Select</option>
                                    </select>
                                </div>

                                <div class="clear"></div>
                                <!-- Image on text Component 58 23-5-16 start-->
                                <div class="background_label link_text_option">
                                    <label class="screenConnectRadio"><input type="radio" id="screen" name="categoryRadio"> Enter URL:</label>
                                </div>
                                <div class="background_colorbox link_text_option">
                                    <input type="text" placeholder="http://" class="link_textbox">
                                </div>

                                <div class="clear"></div>

                                <!-- Image on text Component 58 23-5-16 end-->
                                <div class="background_label">
                                    <input type="radio" id="categoryRadio" name="categoryRadio">
                                    <label class="screenConnectRadio" for="categoryRadio">Link to Retail App</label>
                                </div>
                                <div class="breadcrum background_label">
                                    <label id="retailHierarchy" class="screenConnectRadio"></label>
                                </div>
                                <div class="clear"></div>
                                <div class="background_colorbox mainCategory">

                                </div>

                                <div class="subcategory">	</div>
                                <div id="product"></div>


                            </div>

                            <div class="design_menu_box form_edit_panel">
                                <div class="form_edit_inner_box editLabel">
                                    <div class="content_label">
                                        <label>Label Text</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" placeholder="Enter label" maxlength="10">
                                        <em>Maximum of 10 Characters</em>
                                        <!--
                                                                                <select class="content_font_form">
                                                                                    <option>Helvetica</option>
                                                                                    <option>Helvetica Neue</option>
                                                                                    <option>Helvetica Light</option>
                                                                                    <option value="Open Sans">Open Sans</option>
                                                                                    <option value="Didact Gothic">Gothic</option>
                                                                                    <option value="EB Garamond">Garamond</option>
                                                                                    <option value="Roboto">Roboto</option>
                                                                                    <option value="sans-serif">Sans Serif</option>
                                                                                </select>
                                                                                <select class="content_fontsize_form">
                                                                                    <option>12</option>
                                                                                    <option>14</option>
                                                                                    <option>16</option>
                                                                                    <option>18</option>
                                                                                    <option>20</option>
                                                                                    <option>22</option>
                                                                                    <option>24</option>
                                                                                </select>
                                                                                <span class="colorselect_box_form"></span>
                                        -->
                                    </div>
                                </div>
                                <!--<div class="form_edit_inner_box editRadioBox">
                                    <div class="editRadioInner">
                                        <div class="content_label">
                                            <label>Label 1</label>
                                        </div>
                                        <div class="content_textbox">
                                            <input type="text" placeholder="Enter label" maxlength="10">
                                            <em>Maximum of 10 Characters</em>
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
                                            <select class="content_fontsize_form">
                                                <option>12</option>
                                                <option>14</option>
                                                <option>16</option>
                                                <option>18</option>
                                                <option>20</option>
                                                <option>22</option>
                                                <option>24</option>
                                            </select>
                                            <span class="colorselect_box_form"></span>
                                        </div>
                                    </div>
                                    <div class="editRadioInner">
                                        <div class="content_label">
                                            <label>Label 2</label>
                                        </div>
                                        <div class="content_textbox">
                                            <input type="text" placeholder="Enter label" maxlength="10">
                                            <em>Maximum of 10 Characters</em>
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
                                            <select class="content_fontsize_form">
                                                <option>12</option>
                                                <option>14</option>
                                                <option>16</option>
                                                <option>18</option>
                                                <option>20</option>
                                                <option>22</option>
                                                <option>24</option>
                                            </select>
                                            <span class="colorselect_box_form"></span>
                                        </div>
                                    </div>
                                </div>-->
                                <!--<div class="form_edit_inner_box editCheckBox">
                                    <div class="editCheckInner">
                                        <div class="content_label">
                                            <label>Label 1</label>
                                        </div>
                                        <div class="content_textbox">
                                            <input type="text" placeholder="Enter label" maxlength="10">
                                            <em>Maximum of 10 Characters</em>
                                            <select class="content_font_form">
                                                <option>Helvetica</option>
                                                <option>Helvetica Neue</option>
                                                <option>Helvetica Light</option>
                                                <option value="Open Sans">Open Sans</option>
                                                <option value="Didact Gothic">Gothic</option>
                                                <option value="EB Garamond">Garamond</option>
                                                <option value="Roboto">Roboto</option>
                                                <option value="sans-serif">Sans Serif</option>
                                            </select>
                                            <select class="content_fontsize_form">
                                                <option>12</option>
                                                <option>14</option>
                                                <option>16</option>
                                                <option>18</option>
                                                <option>20</option>
                                                <option>22</option>
                                                <option>24</option>
                                            </select>
                                            <span class="colorselect_box_form"></span>
                                        </div>
                                    </div>
                                </div>-->
                                <div class="form_edit_inner_box editButton">
                                    <div class="content_label">
                                        <label>Button Text</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" placeholder="Enter button text">
                                    </div>
                                </div>
                                <div class="form_edit_inner_box editLeftButton">
                                    <div class="content_label">
                                        <label>Left Button</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" placeholder="Enter left button text" maxlength="8">
                                        <em>Maximum of 8 Characters</em>
                                    </div>
                                </div>
                                <div class="form_edit_inner_box editRightButton">
                                    <div class="content_label">
                                        <label>Right Button</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" placeholder="Enter right button text" maxlength="8">
                                        <em>Maximum of 8 Characters</em>
                                    </div>
                                </div>
                                <!--<div class="form_edit_inner_box editDate">
                                    <div class="content_label">
                                        <label>Select Date</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" placeholder="Select date">
                                    </div>
                                </div>-->
                                <div class="form_edit_inner_box editDateFormat">
                                    <div class="content_label">
                                        <label>Select Format</label>
                                    </div>
                                    <div class="content_textbox">
                                        <select class="content_textbox_select">
                                            <option value="">Select Format</option>
                                            <option>dd/MM/yyyy</option>
                                            <option>MM/dd/yyyy</option>
                                            <option>yyyy/MM/dd</option>
                                            <option>dd-MM-yyyy</option>
                                            <option>MM-dd-yyyy</option>
                                            <option>yyyy-MM-dd</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form_edit_inner_box editDescription">
                                    <div class="content_label">
                                        <label>Description</label>
                                    </div>
                                    <div class="content_textbox">
                                        <textarea class="subHeading1 nicEditor"></textarea>
                                    </div>
                                </div>
                                <div class="form_edit_inner_box editTime">
                                    <div class="content_label">
                                        <label>Select Time</label>
                                    </div>
                                    <div class="content_textbox">
                                        <select class="content_textbox_select">
                                            <option value="">Time (hh/mm)</option>
                                            <option value="01:00">01:00</option>
                                            <option value="02:00">02:00</option>
                                            <option value="03:00">03:00</option>
                                            <option value="04:00">04:00</option>
                                            <option value="05:00">05:00</option>
                                            <option value="06:00">06:00</option>
                                            <option value="07:00">07:00</option>
                                            <option value="08:00">08:00</option>
                                            <option value="09:00">09:00</option>
                                            <option value="10:00">10:00</option>
                                            <option value="11:00">11:00</option>
                                            <option value="12:00">12:00</option>
                                            <option value="13:00">13:00</option>
                                            <option value="14:00">14:00</option>
                                            <option value="15:00">15:00</option>
                                            <option value="16:00">16:00</option>
                                            <option value="17:00">17:00</option>
                                            <option value="18:00">18:00</option>
                                            <option value="19:00">19:00</option>
                                            <option value="20:00">20:00</option>
                                            <option value="21:00">21:00</option>
                                            <option value="22:00">22:00</option>
                                            <option value="23:00">23:00</option>
                                            <option value="24:00">24:00</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form_edit_inner_box editHintText">
                                    <div class="content_label">
                                        <label>Hint Text</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" placeholder="Enter hint text" maxlength="50">
                                    </div>
                                </div>
                                <div class="two_form_edit_input editMinMaxLength">
                                    <div class="form_edit_inner_box editMinLength">
                                        <div class="content_label">
                                            <label>Min Length</label>
                                        </div>
                                        <div class="content_textbox">
                                            <input type="text" placeholder="Field Min Length" maxlength="4">
                                        </div>
                                    </div>
                                    <div class="form_edit_inner_box editMaxLength">
                                        <div class="content_label">
                                            <label>Max Length</label>
                                        </div>
                                        <div class="content_textbox">
                                            <input type="text" placeholder="Field Max Length" maxlength="4">
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="form_edit_inner_box editFieldType">
                                    <div class="content_label">
                                        <label>Field Type</label>
                                    </div>
                                    <div class="content_textbox">
                                        <select class="content_textbox_select">
                                            <option value="">Select Field Type:</option>
                                            <option value="text">Text</option>
                                            <option value="number">Numerical</option>
                                            <option value="phone_number">Phone Number</option>
                                            <option value="email">Email</option>
                                            <option value="password">Password</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form_edit_inner_box editRequired">
                                    <div class="content_label">
                                        <label>Mandatory</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="checkbox" id="mandatory">
                                        <label for="mandatory">&nbsp;</label>
                                    </div>
                                </div>
                                <div class="form_edit_inner_box editDropdownList">
                                    <div class="content_label">
                                        <label>Dropdown List</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" placeholder="Enter text to list" class="dropdownList" maxlength="20">
                                        <input type="button" value="Add to List" class="dropdownList_btn">
                                        <div class="clear"></div>
                                        <em>Maximum of 20 Characters</em>
                                        <div class="dropdownList_box">
                                        </div>
                                    </div>
                                </div>
                                <div class="form_edit_inner_box editCheckboxList">
                                    <div class="content_label">
                                        <label>Checkbox List</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" placeholder="Enter text" class="dropdownList" maxlength="20">
                                        <input type="button" value="Add to List" class="dropdownList_btn">
                                        <div class="clear"></div>
                                        <em>Maximum of 20 Characters</em>
                                        <div class="clear"></div>
                                        <div class="checkboxListInner clear" data-index="0">
                                            <input type="text" placeholder="Enter text" class="dropdownList" maxlength="20">
                                        </div>
                                    </div>
                                </div>
                                <div class="form_edit_inner_box editRadioList">
                                    <div class="content_label">
                                        <label>Radio Button List</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" placeholder="Enter text" class="dropdownList" maxlength="10">
                                        <input type="button" value="Add to List" class="dropdownList_btn">
                                        <div class="clear"></div>
                                        <em>Maximum of 10 Characters</em>
                                        <!--<select class="content_font">
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
                                        </select>
                                        <span class="colorselect_box_form"></span>-->
                                        <div class="clear"></div>
                                        <div class="checkboxListInner clear" data-index="0">
                                            <input type="text" placeholder="Enter text" class="dropdownList" maxlength="10">
                                        </div>
                                        <div class="checkboxListInner clear" data-index="1">
                                            <input type="text" placeholder="Enter text" class="dropdownList" maxlength="10">
                                        </div>
                                    </div>
                                </div>

                                <!--
                                                                <div class="form_edit_inner_box editFieldBg">
                                                                    <div class="content_label">
                                                                        <label>Background</label>
                                                                    </div>
                                                                    <div class="content_textbox">
                                                                        <span class="colorselect_box"></span>
                                                                    </div>
                                                                </div>
                                -->

                                <div class="form_edit_inner_box editFieldIcon">
                                    <div class="content_label">
                                        <label>Icon</label>
                                    </div>
                                    <div class="content_textbox">
                                        <img src="<?php echo $basicUrl; ?>images/menu_img.jpg" class="form_icon_select">
                                    </div>
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
            <div class="crome_msg">
                <p>
                    For viewing your application you can click 'Finish' or Download Instappy Wizard by clicking following links:
                    <br />
                    <a href="https://play.google.com/store/apps/details?id=com.pulp.wizard" target="_blank"><i class="fa fa-android"></i></a>
                    <a href="https://itunes.apple.com/us/app/instappy/id1053874135?mt=8" target="_blank"><i class="fa fa-apple"></i></a>
                </p>
            </div>
        </div>
        <div class="hint_main">
            <img src="<?php echo $basicUrl; ?>images/ajax-loader11.gif">

        </div>
        <a id="openModalW" data-target="#cropper-example-2-modal" data-toggle="modal" style="opacity:0.11;" >&nbsp;</a>

        <div class="modal fade" id="cropper-example-2-modal" data-backdrop="'static'">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <a data-dismiss="modal" style="float: right; cursor: pointer; padding: 10px;">&times;</a>
                        <h4 id="bootstrap-modal-label">Upload Image</h4>
                        <div class="upload-btn">
                            <a class="make_app_next">Browse</a>
                            <input class="sr-only inputImageChange" id="inputImage" name="file" type="file" />
                        </div>
                        <ul class="modal-title">
                            <li>This image should be atleast <span class="resolution">1080 &times; 540</span> resolution.</li>
                            <li>Maximum image size should be of 2MB. Only <strong>JPG/PNG</strong> file extensions are allowed.</li>
                            <li>Drag to adjust image &amp; scroll to zoomin and zoomout to fit in the area.</li>
                        </ul>
                    </div>

                    <div class="modal-body">
                        <div id="cropper-example-2" class="img-container">
                            <img src="" class="" id="modalimage1" alt="" />
                            <div class="crop-controls">
                                <span style="position: relative; left: -50%;">
                                    <button class="zoom-controls" data-method="zoom" data-option="0.02" title="Zoom In">
                                        <i class="fa fa-search-plus fa-lg"></i>
                                    </button>
                                    <button class="zoom-controls" data-method="zoom" data-option="-0.02" title="Zoom Out">
                                        <i class="fa fa-search-minus fa-lg"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <a class="make_app_next cropcancel" style="float: none; cursor: pointer; display: none !important;" data-dismiss="modal">Cancel</a>
                        <!--
                                                <div style="float: right; width: 10px;">&nbsp;</div>
                        -->
                        <a id="getcropped" style="float: none; cursor: pointer;" class="make_app_next" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 1080, &quot;height&quot;: 424 }" >Crop &amp; Save</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
</section>
<button class="add_page_hidden" style="visibility:hidden;"></button>
<button class="save_hidden" style="visibility:hidden;"></button>
<div class="popup_userprofile"> 
    <div class="letuscall_popup">

        <span class="close_popup"><img src="images/popup_close.png"></span>

        <div class="letuscall_popup_head">
            <h1>Confirm Mobile Number</h1>
        </div>

        <span class="letsub-heading">Confirm your mobile number and <br>Get a link to test your app right now!</span>

        <div class="letuscall_popup_body">

            <div class="letuscall_form">

                <input type="tel" maxlength="6" minlength="2" id="mobileNocc" name='mobileNocc' class="required" value="<?php
                if ($user['mobile_country_code'] != '') {
                    echo '+' . $user['mobile_country_code'];
                } else {
                    echo '+91';
                }
                ?>">
                <input type="tel" id="mobileNo" maxlength="16" minlength="5" class="phoneEmpty" placeholder="Enter number">
                <input type="submit" id="sendVerificationCode" value="Send Verification Code">
                <div class="clear"></div>
                <a class="error1">Mobile no. should not be less than 5 digit</a>
            </div>
        </div>
    </div>
    <div class="codesent_popup">
        <span class="close_popup"><img src="images/popup_close.png"></span>
        <div class="codesent_popup_head">
            <h1>Please enter your OTP</h1>
            <div class="text_msg">
                <div class="text_msg_left">
                    <img src="images/text_message.jpg">
                </div>
                <div class="text_msg_right">
                    <p>A text message with your code has been sent to: ***** ***<span class="lastno">32</span></p>
                    <span class="popupTimer">05:00</span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="codesent_popup_body">
            <div class="codesent_popup_form">
                <input type="text" onKeyPress="return isNumber(event)" maxlength="4" id="verifyotpno" class="phoneEmpty" placeholder="Enter code">
                <input type="submit" id="verifyotp" value="Verify">
                <div class="clear"></div>
                <a class="error1">Problems receiving your code?</a>
            </div>
        </div>
    </div>

    <div class="code_not_recieved_popup">
        <span class="close_popup"><img src="images/popup_close.png"></span>
        <div class="code_not_recieved_popup_head">
            <h1>Let us call, to help you</h1>
            <div class="code_not_received">
                <p>Haven't received the code yet? <a id="resendotpCode">Resend Code</a></p>
            </div>
            <div class="text_msg">
                <div class="text_msg_left">
                    <img src="images/text_message.jpg">
                </div>
                <div class="text_msg_right">
                    <p>A text message with your code has been sent to: ***** ***<span class="lastno">32</span></p>
                    <span class="popupTimer">05:00</span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="code_not_recieved_popup_body">
            <div class="code_not_recieved_form">
                <input type="text" onKeyPress="return isNumber(event)" maxlength="4" id="againverifyotpno" class="phoneEmpty" placeholder="Enter code">
                <input type="submit" id="verifyotp2" value="Verify">
                <div class="clear"></div>
                <a class="error1">Problems receiving your code?</a>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="js/jquery.shapeshift.js"></script> 
<script type="text/javascript" src="js/intlTelInput.js"></script>
<script>
                            window.onload = function () {
                            setTimeout(function () {
                            mobileValidation();
                            }, 60000);
                            };
                            function mobileValidation()
                            {
                            var appnameempty = checkAppName();
                                    if (appnameempty == 1) {
                            phoneData = JSON.parse(mail_confirm("mobile_confirm"));
                                    if (phoneData != 0 || phoneData != null) {
                            m_no = phoneData.mobile;
                                    if (phoneData.mobile_country_code == null || phoneData.mobile_country_code == '')
                            {
                            cntrycode = '91';
                            }
                            else
                            {
                            cntrycode = phoneData.mobile_country_code;
                            }
                            cntry_code = '+' + cntrycode;
                                    validate = phoneData.mobile_validated;
                                    appType = phoneData.mobile_validated;
                                    smsVal = phoneData.sms_sent;
                                    if (validate == 0)
                            {
                            $('.error1').html('');
                                    $(".popup_userprofile").css({'display': 'block'});
                                    var mobno = m_no;
                                    var mobnocc = cntry_code;
                                    $("#mobileNo").val(mobno);
                                    $("#mobileNocc").val(mobnocc);
                                    $("#mobileNocc").trigger("keyup");
                                    $(".letuscall_popup").css({'display': 'block'});
                                    $("#sendVerificationCode").bind("click", sendVerificationCode);
                            }
                            else if (smsVal == 0)
                            {
                            $.ajax({
                            type: "POST",
                                    url: BASEURL + 'API/sms.php/index',
                                    data: "code=" + phoneData.mobile_country_code + "&mobile=" + m_no + "&validate=" + validate + "&app_id=<?php echo $appID; ?>&authorId=" + phoneData.custid,
                                    success: function (response) {
                                    },
                                    error: function () {
                                    }
                            });
                            }
                            }
                            }
                            }
                    function sendVerificationCode() {
                    var mobpopup = $("#mobileNo").val();
                            var mobileNocc = $("#mobileNocc").val();
                            var last2digit = mobpopup.substr(mobpopup.length - 2);
                            $(".lastno").html(last2digit);
                            $('.error1').html('');
                            $(".popup_userprofile").css({'display': 'block'});
                            timerCheck = setInterval(function () {
                            var timer = $('.popupTimer').html();
                                    timer = timer.split(':');
                                    var minutes = timer[0];
                                    var seconds = timer[1];
                                    if (seconds != "00" || minutes != "00") {
                            seconds -= 1;
                                    if (minutes < 0)
                                    return;
                                    if (seconds < 0 && minutes != 0) {
                            minutes -= 1;
                                    seconds = 59;
                            }
                            else if (seconds < 10 && length.seconds != 2)
                                    seconds = '0' + seconds;
                                    if ((minutes < 10) && ((minutes + '').length < 2))
                                    minutes = '0' + minutes;
                                    $('.popupTimer').html(minutes + ':' + seconds);
                            }
                            if (seconds == "00" && minutes == "00")
                            {
                            clearInterval(timerCheck);
                                    $('.popupTimer').text("05:00");
                                    $(".letuscall_popup").css({'display': 'none'});
                                    $(".codesent_popup").css({'display': 'none'});
                                    $(".code_not_recieved_popup").css({'display': 'none'});
                                    $(".popup_userprofile").css({'display': 'none'});
                            }
                            }, 1000);
                            if (mobpopup != '') {
                    if (mobpopup.length <= 16) {

                    var param = {'mobileno': mobpopup, 'mobilecountrycode': mobileNocc};
                            var form_data = {
                            data: param, //your data being sent with ajax
                                    token: '<?php echo $token; ?>', //used token here.
                                    is_ajax: 4
                            };
                            $.ajax({
                            type: "POST",
                                    url: 'modules/login/userMobvalidation.php',
                                    data: form_data,
                                    success: function (response)
                                    {
                                    if (response == 1) {
                                    $(".letuscall_popup").css({'display': 'none'});
                                            $(".codesent_popup").css({'display': 'block'});
                                    } else if (response == 2) {
                                    $('.error1').html('Some error in submit');
                                            console.log(response);
                                    } else if (response == 3) {
                                    $(".popup_userprofile").css({'display': 'block'});
                                            $(".letuscall_popup").css({'display': 'block'});
                                            $(".codesent_popup").css({'display': 'none'});
                                            $(".code_not_recieved_popup").css({'display': 'none'});
                                            $('.error1').html('Mobile no. should not be less than 5 digit');
                                            console.log(response);
                                    }
                                    },
                                    error: function () {
                                    console.log("error in ajax call");
                                            // alert("error in ajax call");
                                    }
                            });
                    } else {
                    $(".popup_userprofile").css({'display': 'block'});
                            $(".letuscall_popup").css({'display': 'block'});
                            $(".codesent_popup").css({'display': 'none'});
                            $(".code_not_recieved_popup").css({'display': 'none'});
                            $('.error1').html('Mobile no. should not be less than 10 digit');
                    }
                    } else {
                    $(".letuscall_popup").css({'display': 'block'});
                            $(".codesent_popup").css({'display': 'none'});
                            $(".code_not_recieved_popup").css({'display': 'none'});
                            $(".popup_userprofile").css({'display': 'block'});
                            $('.error1').html('Please enter your mobile no.');
                    }




                    // populateCountries("country", "state", '<?php echo $user['country']; ?>', '<?php echo $user['state']; ?>');

                    $("#sendVerificationCode").unbind('click');
                    }
                    $(".leftsidemenu li").removeClass("active");
                            var timerCheck;
                            var resendotpCode = 0;
                            $(".close_popup").click(function () {

                    $('.popupTimer').text("05:00");
                            clearInterval(timerCheck);
                            $(this).parent().css({'display': 'none'});
                            $('.popup_userprofile').css({'display': 'none'});
                    });
                            $("#resendotpCode").click(function () {
                    resendotpCode = 1;
                            otpcheck("", resendotpCode);
                    });
                            $("#verifyotp").click(function () {
                    $('.error1').html('');
                            $(".popup_userprofile").css({'display': 'block'});
                            var userverifyOtp = $("#verifyotpno").val();
                            if (userverifyOtp != '') {
                    if (userverifyOtp.length == 4) {
                    otpcheck(userverifyOtp, resendotpCode);
                    } else {
                    $('.error1').html('OTP is not less than 4 digit');
                    }
                    } else {
                    $(".letuscall_popup").css({'display': 'none'});
                            $(".codesent_popup").css({'display': 'block'});
                            $(".code_not_recieved_popup").css({'display': 'none'});
                            $(".popup_userprofile").css({'display': 'block'});
                            $('.error1').html('Please enter your OTP');
                    }
                    });
                            $("#verifyotp2").click(function () {
                    $('.error1').html('');
                            resendotpCode = 0;
                            $(".popup_userprofile").css({'display': 'block'});
                            var userverifyOtp = $("#againverifyotpno").val();
                            if (userverifyOtp != '') {
                    if (userverifyOtp.length == 4) {
                    otpcheck(userverifyOtp, resendotpCode);
                    } else {
                    $('.error1').html('OTP is 4 digit only');
                    }
                    } else {
                    $(".letuscall_popup").css({'display': 'none'});
                            $(".codesent_popup").css({'display': 'block'});
                            $(".code_not_recieved_popup").css({'display': 'none'});
                            $(".popup_userprofile").css({'display': 'block'});
                            $('.error1').html('Please enter your OTP');
                    }
                    });
                            $('.error1').html('');
                            $("#mobilecc").intlTelInput({
                    defaultCountry: "auto",
                            nationalMode: true,
                            preventInvalidNumbers: true,
                            utilsScript: "js/utils.js"
                    });
                            $("#mobilecc").keydown(function (event) {
                    return false;
                    });
                            $("#mobileNocc").intlTelInput({
                    defaultCountry: "auto",
                            nationalMode: true,
                            preventInvalidNumbers: true,
                            utilsScript: "js/utils.js"
                    });
                            $("#mobileNocc").keydown(function (event) {
                    return false;
                    });
                            function otpcheck(userverifyOtp, resendotpCode) {

                            var param = {'verifyotpno': userverifyOtp};
                                    var form_data = {
                                    data: param, //your data being sent with ajax
                                            token: '<?php echo $token; ?>', //used token here.
                                            resend: resendotpCode,
                                            is_ajax: 5
                                    };
                                    $.ajax({
                                    type: "POST",
                                            url: 'modules/login/otpvalidation.php',
                                            data: form_data,
                                            success: function (response)
                                            {
                                            if (response == 1) {
                                            $(".letuscall_popup").css({'display': 'none'});
                                                    $(".codesent_popup").css({'display': 'block'});
                                                    $(".code_not_recieved_popup").css({'display': 'block'});
                                                    $(".popup_userprofile").css({'display': 'block'});
                                            }
                                            else if (response == 2) {
                                            $('.error1').html('Some error in submit');
                                                    console.log(response);
                                            }
                                            else if (response == 3) {
                                            $(".letuscall_popup").css({'display': 'none'});
                                                    $(".codesent_popup").css({'display': 'none'});
                                                    $(".code_not_recieved_popup").css({'display': 'none'});
                                                    $(".popup_userprofile").css({'display': 'none'});
                                                    window.location = window.location;
                                            }
                                            else if (response == 4) {
                                            $(".letuscall_popup").css({'display': 'none'});
                                                    $(".codesent_popup").css({'display': 'none'});
                                                    $(".code_not_recieved_popup").css({'display': 'block'});
                                                    $(".popup_userprofile").css({'display': 'block'});
                                                    $('.error1').html('OTP is incorrect.');
                                                    console.log(response);
                                            }
                                            else if (response == 5) {
                                            $('.error1').html('Some error in submit');
                                                    console.log(response);
                                            }
                                            else if (response == 6) {
                                            $(".letuscall_popup").css({'display': 'block'});
                                                    $(".codesent_popup").css({'display': 'none'});
                                                    $(".code_not_recieved_popup").css({'display': 'none'});
                                                    $(".popup_userprofile").css({'display': 'block'});
                                                    $('.error1').html('Please enter your mobile no.');
                                                    console.log(response);
                                            } else if (response == 7) {
                                            $(".letuscall_popup").css({'display': 'none'});
                                                    $(".codesent_popup").css({'display': 'none'});
                                                    $(".code_not_recieved_popup").css({'display': 'block'});
                                                    $(".popup_userprofile").css({'display': 'block'});
                                                    $('.error1').html('OTP has been expired.');
                                                    console.log(response);
                                            }
                                            },
                                            error: function () {
                                            console.log("error in ajax call");
                                                    // alert("error in ajax call");
                                            }
                                    });
                            }

                    var pageDetails = [
                    {
                    index: '1',
                            name: 'Home',
                            icon: '',
                            originalIndex: '1',
                            keyword: ''
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
                                    layouttype: '1',
                                    originalIndex: '1'
                            }
                            /* ,
                             {
                             index: '10',
                             contentarea: '<div class="big_widget_text about" data-ss-colspan="2" data-component="14"><p class="widgetClose">x</p><div class="big_widget_img"><img src="<?php echo $basicUrl; ?>images/widget_big_img.jpg"><img class="video_img" alt="" src="<?php echo $basicUrl; ?>images/video_img_big.png"><div class="clear"></div><div class="big_widget_img_text"><p class="img_heading">Heading</p><div class="clear"></div></div><div class="big_widget_img_controls"><a href="#" class="share"><img src="<?php echo $basicUrl; ?>images/share.png" /></a><a href="#" class="share"><img src="<?php echo $basicUrl; ?>images/heart.png" /></a><div class="clear"></div></div></div><div class="big_widget_bottom_text"><p class="about">About Us</p><div class="clear"></div></div></div><div class="tabbing_widget" data-ss-colspan="2" data-component="15"><p class="widgetClose">x</p><div class="tabbing_widget_head"><ul class="tabs"><li class="active"><a>Tab 1</a></li><li><a>Tab 2</a></li><li><a>Tab 3</a></li><div class="clear"></div></ul></div><div class="tabbing_widget_body"><div class="tab_content" style="display:block;"><p>Add Description1</p></div><div class="tab_content" style="display:none;"><p>Add Description2</p></div><div class="tab_content" style="display:none;"><p>Add Description3</p></div></div></div>',
                             banner: '',
                             layouttype: '10'
                             } */
                            ];
                            var deletedScreenCount = 0;
                            $(document).ready(function () {
                    var screenWidth = window.screen.availWidth;
                            if (screenWidth <= 767){
                    $(".popup_container .login_popup").addClass("change-popup-size");
                    };
                            // $(document).on('paste','.nicEdit-main',function(){
                            //     var element = this;
                            //     setTimeout(function () {
                            //         $(element).html($(element).text());
                            //         $(element).trigger("propertychange");
                            //     }, 10);
                            // });

                            $('input.eventDate').datepicker({
                    dateFormat: 'mm-dd-yy'
                    });
                            $(document).on('paste', '.nicEdit-main', function (e) {
                    e.preventDefault();
                            if ((e.originalEvent || e).clipboardData) {
                    var text = (e.originalEvent || e).clipboardData.getData('text/plain') || prompt('Paste something..');
                            window.document.execCommand('insertText', false, text);
                    }
                    else if (window.clipboardData) {
                    content = window.clipboardData.getData('Text');
                            document.selection.createRange().pasteHTML(content);
                    }

                    $(this).trigger("propertychange");
                    });
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
                            $("#savehtml").click(function () {
                    var appnameempty = checkAppName();
                            if (appnameempty == 1) {

                    saveSignUpAndAddtoStore();
                            if ($('.screen_name').val() != '') {
                    if ($(".contactReq").prop("checked") == true) {
                    cEmail = $(".contactusEmail").val();
                            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                            if (cEmail == '' || cEmail == null) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please enter contact email Id');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }

                    else if (!filter.test(cEmail)) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please enter valid contact email Id');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }



                    }
					if ($(".feedbackReq").prop("checked") == true) {
                    fedEmail = $(".feedbackEmail").val();
                            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                            if (fedEmail == '' || fedEmail == null) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please Enter feedback E-mail ID.</p><input type="button" id="btn_emailEmpty" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(document).one('click', '#btn_emailEmpty', function () {
                    $('.feedbackEmail:first').parents('.mid_right_box:first').find('.mid_right_box_head').trigger('click');
                            $("#content-2").mCustomScrollbar('scrollTo', $('.catalogue_add_feature.contactus:first'));
                            $('.feedbackEmail:first').focus();
                    });
                            return false;
                    }

                    else if (!filter.test(fedEmail)) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please Enter Valid feedback E-mail ID.');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    }
                    var click_savehtml = "savehtml";
                            saveAppPanel(click_savehtml);
                    } else {
                    $(".cropcancel").trigger("click");
                            $("#page_ajax").html('').hide();
                            $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please choose screen name.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    } else {
                    fnErrAppName();
                    }

                    });
                            $("#finish").click(function () {

                    var appnameempty = checkAppName();
                            if (appnameempty == 1) {

                    saveSignUpAndAddtoStore();
                            if ($('.screen_name').val() != '') {
                    if ($(".contactReq").prop("checked") == true) {
                    cEmail = $(".contactusEmail").val();
                            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                            if (cEmail == '' || cEmail == null) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please enter contact email Id');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }

                    else if (!filter.test(cEmail)) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please enter valid contact email Id');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }



                    }
					if ($(".feedbackReq").prop("checked") == true) {
                    fedEmail = $(".feedbackEmail").val();
                            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                            if (fedEmail == '' || fedEmail == null) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please Enter feedback E-mail ID.</p><input type="button" id="btn_emailEmpty" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(document).one('click', '#btn_emailEmpty', function () {
                    $('.feedbackEmail:first').parents('.mid_right_box:first').find('.mid_right_box_head').trigger('click');
                            $("#content-2").mCustomScrollbar('scrollTo', $('.catalogue_add_feature.contactus:first'));
                            $('.feedbackEmail:first').focus();
                    });
                            return false;
                    }

                    else if (!filter.test(fedEmail)) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please Enter Valid feedback E-mail ID.');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    }

                    var mailConfirm2 = mail_confirm("mob_confirm");
                            if (mailConfirm2 > 0) {
                    $(".cropcancel").trigger("click");
                            $(".confirm_name .close_popup").css({'display': 'none'});
                            $(".confirm_name .confirm_name_form").html('<p>Please complete your profile to proceed.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(".close_popup").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('').hide();
                            setTimeout(function () {
                            window.location.href = BASEURL + 'userprofile.php';
                            }, 2000);
                    } else {
                    var click_finish = "finish";
                            saveAppPanel(click_finish);
                    }
                    } else {
                    $(".cropcancel").trigger("click");
                            $("#page_ajax").html('').hide();
                            $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please choose screen name.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    } else {
                    fnErrAppName();
                    }

                    });
                            $(document).on('click', '.saveAndcontinue', function () {
                    var appnameempty = checkAppName();
                            if (appnameempty == 1) {

                    saveSignUpAndAddtoStore();
                            if ($('.screen_name').val() != '') {
                    if ($(".contactReq").prop("checked") == true) {
                    cEmail = $(".contactusEmail").val();
                            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                            if (cEmail == '' || cEmail == null) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please Enter Contact Us E-mail ID.</p><input type="button" id="btn_emailEmpty" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(document).one('click', '#btn_emailEmpty', function () {
                    $('.contactusEmail:first').parents('.mid_right_box:first').find('.mid_right_box_head').trigger('click');
                            $("#content-2").mCustomScrollbar('scrollTo', $('.catalogue_add_feature.contactus:first'));
                            $('.contactusEmail:first').focus();
                    });
                            return false;
                    }

                    else if (!filter.test(cEmail)) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please Enter Valid Contact E-mail ID.');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    }
					if ($(".feedbackReq").prop("checked") == true) {
                    fedEmail = $(".feedbackEmail").val();
                            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                            if (fedEmail == '' || fedEmail == null) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please Enter feedback E-mail ID.</p><input type="button" id="btn_emailEmpty" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(document).one('click', '#btn_emailEmpty', function () {
                    $('.feedbackEmail:first').parents('.mid_right_box:first').find('.mid_right_box_head').trigger('click');
                            $("#content-2").mCustomScrollbar('scrollTo', $('.catalogue_add_feature.contactus:first'));
                            $('.feedbackEmail:first').focus();
                    });
                            return false;
                    }

                    else if (!filter.test(fedEmail)) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please Enter Valid feedback E-mail ID.');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    }
                    saveAppPanel();
                    } else {
                    $(".cropcancel").trigger("click");
                            $("#page_ajax").html('').hide();
                            $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please Choose Screen Name.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    } else {
                    fnErrAppName();
                    }

                    });
                            $("#getcropped").click(function () {
                    if ($("#cropper-example-2-modal").css('display') == 'block') {
                    var checkimg = $("#cropper-example-2-modal #modalimage1").attr("src");
                            if (checkimg.length != 0) {
                    var appnameempty = checkAppName();
                            if (appnameempty == 1) {
                    $(".modal").css('display', 'none');
                            $(".modal-backdrop.in").css({'display': 'none', 'opacity': '0'});
                            var click_crop = "crop";
                            saveAppPanel(click_crop);
                    } else {
                    fnErrAppName();
                    }
                    } else {
                    $(".cropcancel").trigger("click");
                            $("#page_ajax").html('').hide();
                            $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please choose image.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    } else {
                    $(".cropcancel").trigger("click");
                            $("#page_ajax").html('').hide();
                            $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please choose image.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    });
//        $(".otherimgs").change(function () {
//            var appnameempty = checkAppName();
//            if (appnameempty == 1) {
//                var click_crop = "otherimg";
//                saveAppPanel(click_crop);
//            } else {
//                $(".cropcancel").trigger("click");
//                $("#page_ajax").html('').hide();
//                $(".popup_container").css({'display': 'block'});
//                $(".confirm_name .confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
//                $(".confirm_name").css({'display': 'block'});
//            }
//
//        });

                            $(".addPage").click(function () {
                    var appnameempty = checkAppName();
                            if (appnameempty == 1) {
                    if ($('.screen_name').val() != '') {
                    var click_addpage = "addpage";
                            if ($(".contactReq").prop("checked") == true) {
                    cEmail = $(".contactusEmail").val();
                            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                            if (cEmail == '' || cEmail == null) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please enter contact email Id');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }

                    else if (!filter.test(cEmail)) {
                    $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form p").html('Please enter valid contact email Id');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }



                    }
                    var mailConfirm2 = mail_confirm("mob_confirm");
                            if (mailConfirm2 > 0) {
                    $(".cropcancel").trigger("click");
                            $(".confirm_name .close_popup").css({'display': 'none'});
                            $(".confirm_name .confirm_name_form").html('<p>Please complete your profile to proceed.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(".close_popup").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('').hide();
                            setTimeout(function () {
                            window.location.href = BASEURL + 'userprofile.php';
                            }, 2000);
                    }
                    else
                    {
                    saveAppPanel(click_addpage);
                    }

                    $('.contentKeywords').val('');
                    } else {
                    $(".cropcancel").trigger("click");
                            $("#page_ajax").html('').hide();
                            $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please choose screen name.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    } else {
                    fnErrAppName();
                    }
                    });
                            $(".deleteSlide").click(function () {
                    if (($("#appName").val()) != '') {
                    if ($('.screen_name').val() != '') {
                    $("#presentRow").removeAttr("id");
                            $(this).attr("id", "presentRow");
                            $(".popup_container").css("display", "block");
                            $(".confirm_screen_delete").css("display", "block");
                    } else {
                    $(".cropcancel").trigger("click");
                            $("#page_ajax").html('').hide();
                            $(".popup_container").css({'display': 'block'});
                            $(".confirm_name .confirm_name_form").html('<p>Please choose screen name.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            return false;
                    }
                    } else {
                    fnErrAppName();
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
                                            deletedScreenCount = obj.deletedScreenCount;
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
                            storedPages[0].layouttype = '<?php echo $layout_type; ?>';
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
                            //var deletedScreenCount = storedPages[storedPages.length - 1].originalIndex - storedPages[storedPages.length - 1].index;
                            // the variable is defined
                            // console.log("sdasdasdsad"+storedPages[storedPages.length - 1].originalIndex )

</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/cropper.js"></script>
<script src="js/main.js"></script>

<script type="text/javascript" src="js/colpick.js"></script>
<script type="text/javascript" src="js/customFramework.js?r=5"></script>
<script type="text/javascript" src="js/font_Color.js"></script>


<script type="text/javascript" src="js/jquery-ui.js"></script>

<script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script>

                            var $image = $('.img-container > img');
                            $(document).ready(function () {
                    var upreview = '<?php echo $preview; ?>';
                            if (upreview == "preview") {

                    $(".preview").trigger('click');
                    }

                    });
                            (function ($) {
                            $(window).load(function () {
                            $("#content-1").mCustomScrollbar('destroy');
                                    $("#content-1").mCustomScrollbar({
                            autoHideScrollbar: true,
                                    scrollInertia: 200
                            });
                                    $("#content-2").mCustomScrollbar({
                            autoHideScrollbar: true,
                                    scrollInertia: 200,
                                    callbacks: {
                                    onScrollStart: function () {
                                    $('.colpick').hide();
                                    }
                                    }
                            });
                                    function lazyLoad() {
                                    $('#content-2 img.lazy').each(function () {
                                    var srcPath = $(this).attr('data-original');
                                            $(this).attr('src', srcPath);
                                    });
                                    }
                            setTimeout(lazyLoad, 3000);
                            });
                            })(jQuery);</script>

<script src="js/chosen.jquery.js"></script>
<script src="js/ImageSelect.jquery.js"></script>
<script>
                            $(".select_os").chosen();
                            // onload tutorials 
                            $(window).load(function () {
                    var screenWidth = window.screen.availWidth;
                            if (screenWidth <= 767){
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
                    }
                    $('.onload_tutotial span').on('click', function () {
                    $('.onload_tutotial').hide();
                    });
                            if ($(".mobile .ad_widget").length == 1)
                    {
                    $(".mobile .ad_widget").remove();
                            $('<div class="ad_widget" data-component="100" data-ss-colspan="2" style="left: 0px; top: 390px;"><a href="#"><div class="ad_widget_img"><img src="http://www.instappy.com/images/rescue.jpg" alt="Image Not Found" class="mCS_img_loaded"></div><div class="ad_widget_text"><p>Rescue&apos;em</p><span>Play Now and Enjoy</span></div><div class="clear"></div></a></div>').insertAfter(".container.droparea")
                    }
                    else {
                    if ($(".container.droparea+.ad_widget").length == 0) {
                    $('<div class="ad_widget" data-component="100" data-ss-colspan="2" style="left: 0px; top: 390px;"><a href="#"><div class="ad_widget_img"><img src="http://www.instappy.com/images/rescue.jpg" alt="Image Not Found" class="mCS_img_loaded"></div><div class="ad_widget_text"><p>Rescue&apos;em</p><span>Play Now and Enjoy</span></div><div class="clear"></div></a></div>').insertAfter(".container.droparea")

                    }
                    }
                    globalRestart();
                            // deletedScreenCount = storedPages[storedPages.length - 1].originalIndex - storedPages[storedPages.length - 1].index;
                            // console.log("sdasdasdsad"+storedPages[storedPages.length - 1].originalIndex )
                    });</script>
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
//        function uploadImg(){
//            //$(".upload_img input[type=file]").trigger("click");
//            //$(".editbrowse_img").eq(0).trigger('click');
//            $(this).parent().removeAttr("id");
//            $(this).parent().attr("id", "present");            
//        };

                    $("#createLoginInput").on("change", function () {

                    if ($("input[name='createLoginInput']").is(":checked")) {
                    $(".login_option").removeAttr("disabled");
                            $("#pagePicker1").removeAttr("disabled");
                            $("#description").removeAttr("disabled");
                            $("#facebook_app_id").removeAttr("disabled");
                            $(".upload_img > img").on('click', uploadImg);
                    } else {
                    $(".login_option").attr("disabled", "disabled");
                            $("#colorCode").attr("disabled", "disabled");
                            $("#description").attr("disabled", "disabled");
                            $("#facebook_app_id").attr("disabled", "disabled");
                            $(".upload_img > img").off('click', uploadImg);
                    }
                    });
                            $("#fakeLanguage").on("change", function () {
                    //console.log( $(this).find(":selected").val())
                    $("#drpLanguage").val($(this).find(":selected").val()).trigger("change");
                    });
                            $("nav ul").find("[data-link='s1']").find("span:first").attr("class", "icon-report")
                            $("[class*='widget'] span[class^='icon'], .contact_card span[class^='icon']").css("color", $(".mobile .theme_head").css("background-color"))
                            $("#drpLanguage").val(":english").trigger("change");
                    });</script>
<script>
            $(window).load(function (e) {
    // feedback checked on edit
    if ($('.mobile nav ul li.feedback').css('display') == 'list-item') {
    $('.feedback ul li input.feedbackReq').prop('checked', 'true');
    }
    $('.screen_loader').fadeOut();
            $("[class*='widget'] span[class^='icon'], .contact_card span[class^='icon']").css("color", $(".mobile .theme_head").css("background-color"));
            $(".mobile nav").css("background-color", $(".mobile .theme_head").css("background-color"));
    });
            $(window).unload(function () {
    // console.log("bye");
    $(".save_hidden").trigger('click');
            savehtml();
    });</script>

<script type="text/javascript">
            $(document).ready(function () {
    $(".editbrowse").click(function () {
    var clicked;
            clicked = 0;
            //$("#presentBannerImgset").removeAttr("id");
            // $(this).attr("id", "presentBannerImgset");
            if ($(this).parent().hasClass("upload_img")) {
    if ($("input[name='createLoginInput']").is(":checked")) {
    $(".upload_img > img").parent().removeAttr("id");
            $(".upload_img > img").parent().attr("id", "present");
            clicked = 0;
    } else {
    clicked = 1;
    }
    }

    if (clicked == 0) {
    var canvasW = 250;
            var canvasH = 250;
            var aspect = 1;
            var w = $("#present").find('img').css("width").replace("px", "");
            var h = $("#present").find('img').css("height").replace("px", "");
            if ((w == "135" && h == "110") || (w == "135" && h == "111")) {

    canvasH = 250;
            canvasW = 250;
            aspect = 1;
            $(".modal-title .resolution").html("1080px &times; 1080px");
    }
    if ((w == "279" && h == "110") || (w == "279" && h == "111")) {

    canvasH = 250;
            canvasW = 500;
            aspect = 2;
            $(".modal-title .resolution").html("1080px &times; 540px");
    }
    if (w == "279" && h == "200") {

    canvasH = 250;
            canvasW = 250;
            aspect = 1;
            $(".modal-title .resolution").html("1080px &times; 1080px");
    }
    if (w == "139" && h == "139") {

    canvasH = 250;
            canvasW = 250;
            aspect = 1;
            $(".modal-title .resolution").html("500px &times; 500px");
    }
    if (w == "279" && h == "150") {

    canvasH = 250;
            canvasW = 500;
            aspect = 2;
            $(".modal-title .resolution").html("1080px &times; 1080px");
    }
    if (w == "110" && h == "110") {

    canvasH = 250;
            canvasW = 250;
            aspect = 1;
            $(".modal-title .resolution").html("500px &times; 500px");
            

    }

    /* round card*/
    if (w == "60" && h == "60") {

    canvasH = 100;
            canvasW = 100;
            aspect = 1;
            $(".modal-title .resolution").html("500px &times; 500px");
    }
    /* round card*/
    //console.log("canvas heitgh" +canvasH)
    //console.log("canvas width"+ canvasW)

    var $image = $('.img-container > img');
            $("a#openModalW").trigger("click");
            //$('#cropper-example-2-modal').parents('.bannercropdiv').show();
            //$image.cropper('destroy');


            $('#cropper-example-2-modal').on('shown.bs.modal', function () {

    $image.cropper('destroy').cropper({
    aspectRatio: aspect,
            data: {
            width: canvasW,
                    height: canvasH
            },
//                        strict: false,
            highlight: false,
            viewMode: 1,
             crop: function(data){
            if (w == 60 && h == 60){
            $('.cropper-view-box').css({'border-radius':'50%'});
            }
            if (w == 110 && h == 110){
            $('.cropper-view-box').css({'border-radius':'50%'});
            }

            }

    /*dragCrop: false,
     cropBoxMovable: false,
     cropBoxResizable: false*/
    });
    }).on('hidden.bs.modal', function () {
    $image.cropper('destroy');
            $(".modal-backdrop.in").css({'display': 'none', 'opacity': '0'});
            $(".modal").css('display', 'none');
    });
    }

    });
    });</script>


<script type="text/javascript">
            var selectedAppId = document.getElementById("selectedAppId")[document.getElementById("selectedAppId").selectedIndex].value;
            var published = '<?php echo $appPublish ?>';
            if (published == 1)
    {
    //	document.getElementById("selectedAppId").disabled=true;
    $('#selectedAppId').hide();
            $('#optionalDropDown').show();
    }
    function saveSignUpAndAddtoStore(){
    //var loginVia = $("input[name='loginVia']:checked").val();
    var logindata = [];
            $("input[name='loginVia']:checked").each(function () {
    logindata.push($(this).val());
    });
            loginVia = logindata.toString();
            var app_id = $("#appid").val();
            var addStore = $("input[name='addStore']:checked").val();
            if (addStore != 1){
    addStore = 0;
    }



    var createLoginInput;
            if ($("input[name='createLoginInput']:checked").val() != 1){
    createLoginInput = 0;
    } else{
    createLoginInput = 1;
    }
    var description = $("#description").val();
            var uploadImages = $('.cropImage').attr('src');
            var facebook_app_id = $("#facebook_app_id").val();
            function rgb2hex(rgb) {
            if (rgb) {
            rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
                    return (rgb && rgb.length === 4) ? "#" +
                    ("0" + parseInt(rgb[1], 10).toString(16)).slice( - 2) +
                    ("0" + parseInt(rgb[2], 10).toString(16)).slice( - 2) +
                    ("0" + parseInt(rgb[3], 10).toString(16)).slice( - 2) : '';
            }
            else {
            return '#FFFFFF';
            }
            }
    var selectedAppId = document.getElementById("selectedAppId")[document.getElementById("selectedAppId").selectedIndex].value;
            var hexColorCode = $("#pagePicker1").css("background-color");
            var colorCode = rgb2hex(hexColorCode);
            var formData = {'createLoginInput': createLoginInput, 'facebook_app_id': facebook_app_id, 'uploadImages': uploadImages, 'description': description, 'loginVia': loginVia, 'app_id': app_id, 'colorCode': colorCode, 'selectedAppId':selectedAppId, 'addStore':addStore};
            $.ajax({
            url: "ajax_upload.php",
                    type: "POST",
                    data: formData,
                    success: function (data) {
                    },
            });
    }
    // });



// change date and month

    // var d = new Date();
    // var mon=['Jan','Feb','Mar','April','May','June','July','Aug','Sep','Oct','Nov','Dec'];
    // document.getElementsByClassName("date")[0].innerHTML=d.getUTCDate();
    // document.getElementsByClassName("month")[0].innerHTML=mon[d.getMonth()];


//


    $('#email').on('change', function() {
    $('#facebook').prop('checked', true);
            $('.fb_create_login_box').css('height', '61px');
    });
            $('#facebook').on('change', function() {
    $('#email').prop('checked', true);
    });
            function selectedcategory() {
            $(this).parent('.background_colorbox').next('.subcategory').empty();
                    $('#product').empty();
                    $('.subcategory').empty();
                    var app_id = $("#selectedAppId").val();
                    var category_id = $("#selectedcategory").val();
                    var category_name = $('#selectedcategory option:selected').html();
                    var offset = 0;
                    // Code by Kanishka
                    $("#present").attr('data-maincategory', category_id);
                    $("#present").attr('data-maincategory-name', category_name);
                    var strAttr = '';
                    $.each($("#present")[0].attributes, function () {
                    if (this.specified && (this.name.indexOf('select-subcategory') !== - 1)) {
                    strAttr += this.name + ' ';
                    }
                    });
                    $("#present").removeAttr(strAttr);
                    $('#present').removeAttr('data-product');
                    $('#present').removeAttr('data-product-name');
                    updateHierarchy();
                    var haschild = $('#selectedcategory option:selected').attr('haschild');
                    $('#present').attr('data-hasChild', haschild);
                    var subchild = $('#selectedcategory').val();
                    if (haschild == 1 && subchild != '0'){

            var formData = {'app_id': app_id, 'category_id': category_id, 'offset': offset};
                    $(".popup_container").css("display", "block");
                    $("#page_ajax").html('<img class="loader_new" src="images/ajax-loader_new.gif">').show();
                    $.ajax({
                    url: "ajax_sub_category.php",
                            type: "POST",
                            data: formData,
                            async: true,
                            success: function (response1) {
                            $(".popup_container").css("display", "none");
                                    $("#page_ajax").html('<img class="loader_new" src="images/ajax-loader_new.gif">').hide();
                                    $('.subcategory').html(response1);
                                    if (typeof arr != 'undefined')
                            {
                            if (arr.length > 0) {
                            for (var j = 0; j < arr.length; j++)
                            {
                            if (j == 0){
                            $('.selectedsubcategory').val(arr[j]);
                                    $("#present").attr('select-subcategory0', arr[j]);
                            }
                            else{
                            selectedsubcategory(arr, j, prodlist);
                                    break;
                            }
                            }
                            }

                            }
                            else
                            {
                            selectedsubcategory();
                            }


                            },
                    });
            } else if (subchild != '0') {

            var formData = {'app_id': app_id, 'category_id': category_id};
                    $('#screenoverlay').fadeIn();
                    $.ajax({
                    url: "ajax_product.php",
                            type: "POST",
                            data: formData,
                            success: function (response2) {
                            $(".popup_container").css("display", "none");
                                    $("#page_ajax").html('<img class="loader_new" src="images/ajax-loader_new.gif">').hide();
                                    $('#product').html(response2);
                            },
                    });
            }
            }


    function selectedsubcategory() {
    // Code by Kanishka
    var strAttr = '';
            $.each($("#present")[0].attributes, function () {
            if (this.specified && (this.name.indexOf('select-subcategory') !== - 1)) {
            strAttr += this.name + ' ';
            }
            });
            $("#present").removeAttr(strAttr);
            $('.selectedsubcategory').each(function (index) {
    $("#present").attr("select-subcategory" + index, $(this).val());
            $("#present").attr("select-subcategory-name" + index, $('option:selected', this).html());
    });
            $('#present').removeAttr('data-product');
            $('#present').removeAttr('data-product-name');
            updateHierarchy();
            // Ends
            $(this).parents('.removeCategory').next('.removeCategory').remove();
            $('#product').empty();
            var app_id = $("#selectedAppId").val();
            var category_id = $(".selectedsubcategory:last option:selected").val();
            var offset = 0;
            var haschild = $('.selectedsubcategory:last option:selected').attr('haschild');
            $('#present').attr('data-hasChild', haschild);
            if (haschild == 1){
    var formData = {'app_id': app_id, 'category_id': category_id, 'offset': offset};
            $('#screenoverlay').fadeIn();
            $.ajax({
            url: "ajax_sub_category.php",
                    type: "POST",
                    data: formData,
                    success: function (response3) {
                    $(".popup_container").css("display", "none");
                            $("#page_ajax").html('<img class="loader_new" src="images/ajax-loader_new.gif">').hide();
                            $('.subcategory').append(response3);
                    },
            });
    } else{

    var formData = {'app_id': app_id, 'category_id': category_id};
            $(".popup_container").css("display", "block");
            $("#page_ajax").html('<img class="loader_new" src="images/ajax-loader_new.gif">').show();
            $.ajax({
            url: "ajax_product.php",
                    type: "POST",
                    data: formData,
                    success: function (response4) {
                    $(".popup_container").css("display", "none");
                            $("#page_ajax").html('<img class="loader_new" src="images/ajax-loader_new.gif">').hide();
                            $('#product').html(response4);
                    },
            });
    }

    }



    function categoryClick(){
    $("#present").removeAttr('data-link');
            removeEstoreDtls($("#present"));
            removeEstoreHtml();
            var addStore = $("input[name='addStore']:checked").val();
            if (addStore != 1){

    $("#page_ajax").html('').hide();
            $(".popup_container").css({'display': 'block'});
            $(".confirm_name .confirm_name_form").html('<p>Please check \'Add E-Store to app\' checkbox first!</p><input type="button" id="btn_estoreEmpty" value="OK">');
            $(document).one('click', '#btn_estoreEmpty', function () {
    $('#addStore').parents('.mid_right_box:first').find('.mid_right_box_head').trigger('click');
            $("#content-2").mCustomScrollbar('stop').mCustomScrollbar('scrollTo', $('#addStore').parent());
            $('#addStore').focus();
    });
            $(".confirm_name").css({'display': 'block'});
            $("#categoryRadio").prop('checked', false);
    }
    else{
    var app_id = $("#selectedAppId").val();
            if (app_id == '0'){
    // Kanishka
    $("#page_ajax").html('').hide();
            $(".popup_container").css({'display': 'block'});
            $(".confirm_name .confirm_name_form").html('<p>Please select an E-Store for your app.</p><input type="button" id="btn_estoreEmpty" value="OK">');
            $(document).one('click', '#btn_estoreEmpty', function () {
    $('#selectedAppId').parents('.mid_right_box:first').find('.mid_right_box_head').trigger('click');
            $("#content-2").mCustomScrollbar('stop').mCustomScrollbar('scrollTo', $('#addStore').parent());
            $('#selectedAppId').focus();
    });
            $(".confirm_name").css({'display': 'block'});
            $("#categoryRadio").prop('checked', false);
            // Ends
    } else{
    var formData = {'app_id': app_id};
            $(".popup_container").css("display", "block");
            $("#page_ajax").html('<img class="loader_new" src="images/ajax-loader_new.gif">').show();
            $.ajax({
            url: "ajax_category.php",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                    $(".popup_container").css("display", "none");
                            $("#page_ajax").html('<img class="loader_new" src="images/ajax-loader_new.gif">').hide();
                            $('.mainCategory').html(response);
                    },
            });
    }
    }
    }

    // Code by Kanishka
    function screenClick () {
    $('.mainCategory').empty()
            removeEstoreDtls($("#present"));
            removeEstoreHtml();
            $('#product').empty();
            $('.subcategory').empty();
            $("#present").removeAttr('data-maincategory');
            $("#present").removeAttr('data-maincategory-name');
            var strAttr = '';
            $.each($("#present")[0].attributes, function () {
            if (this.specified && (this.name.indexOf('select-subcategory') !== - 1)) {
            strAttr += this.name + ' ';
            }
            });
            $("#present").removeAttr(strAttr);
            $('#present').removeAttr('data-product');
    }
    // Ends

    $(document).on('click', '#categoryRadio', categoryClick);
            $(document).on('click', '#screen', screenClick);
            $(document).on('change', '#selectedcategory', selectedcategory);
            $(document).on('change', '.selectedsubcategory', selectedsubcategory);
            //end category

            // Code by Kanishka
            $(document).on('change', '#selectedproduct', selectedproduct);
            function selectedproduct() {
            $("#present").attr("data-product", $(this).val());
                    $("#present").attr("data-product-name", $('option:selected', this).val());
                    updateHierarchy();
            }

    $(document).on('change', '#selectedAppId', function () {
    $('[data-maincategory]').each(function () {
    removeEstoreDtls(this);
            removeEstoreHtml();
    });
    });
            $(document).on('change', '#addStore', function () {
    if (!$(this).prop('checked')) {
    $('[data-maincategory]').each(function () {
    removeEstoreDtls(this);
            removeEstoreHtml();
            $('.mainCategory').empty();
    });
    }
    });
            function removeEstoreDtls (elem) {
            $(elem).removeAttr('data-maincategory');
                    var strAttr = '';
                    $.each($(elem)[0].attributes, function () {
                    if (this.specified && (this.name.indexOf('select-subcategory') !== - 1)) {
                    strAttr += this.name + ' ';
                    }
                    });
                    $(elem).removeAttr(strAttr);
                    $(elem).removeAttr('data-product');
                    $(elem).removeAttr('data-hierarchy');
            }

    function removeEstoreHtml () {
    $('#selectedcategory').val('0');
            $('.subcategory').html('');
            $('#product').html('');
            $('#retailHierarchy').text('');
    }

    function updateHierarchy () {
    var strHierarchy = '';
            strHierarchy += $('#selectedcategory option:selected').text();
            $('.selectedsubcategory').each(function (index) {
    strHierarchy += (' > ' + $(this).find('option:selected').text());
    });
            if ($('#selectedproduct').val() !== '0') {
    strHierarchy += (' > ' + $('#selectedproduct option:selected').text());
    }
    $('#present').attr('data-hierarchy', strHierarchy);
            $('#retailHierarchy').text(strHierarchy);
    }

    // Ends


</script>

<script>
    $(document).ready(function(){
    var fb = $('#facebook :checkbox:checked').length;
            var em = $('#email :checkbox:checked').length;
            if (fb == 0 && em == 0){
    $('#email').prop('checked', true);
    }
    });</script>


<textarea class="clipboard notEdit" style="height:0px;width:0px;"></textarea>
<script type="text/javascript" src="js/intlTelInput.js"></script>
<script>
            $("#mobile_country").intlTelInput({
    defaultCountry: "auto",
            nationalMode: true,
            preventInvalidNumbers: true,
            utilsScript: "js/utils.js"
    });
            $("#mobile_country").keydown(function(event) {
    return false;
    });</script>
<script type="text/javascript">
            var specialKeys = new Array();
            specialKeys.push(8); //Backspace
            function IsNumeric(e) {
            var keyCode = e.which ? e.which : e.keyCode
                    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != - 1);
                    document.getElementById("error").style.display = ret ? "none" : "inline";
                    return ret;
            }

</script>
</body>
</html>
