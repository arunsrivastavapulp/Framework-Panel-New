function savehtml() {
    $(".save_hidden").trigger("click");
    $("#login_type").val('save');
    $(".popup_container").css("display", "block");
    $("#page_ajax").html('<img src="images/ajax-loader.gif">').show();
    $.ajax({
        type: "POST",
        url: "login.php",
        data: "check=login",
        async: false,
        success: function (response) {
            var res = response.split("##");
            var app_id = res[1];
            var autherId = res[0];
            if (autherId != '') {
                $("#author_id").val(autherId);
                if (app_id != '') {
                    $("#app_id").val(app_id);
                } else {
                    if (($('#appName').val()) != '') {
                        app_id = $("#app_id").val();
                    } else {
                        $("#page_ajax").html('').hide();
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
                    }
                }
                shresponse = send_html(app_id, autherId);
                $(".popup_container").css("display", "none");
                $("#page_ajax").html('').hide();

            } else {
                $("#page_ajax").html('').hide();
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
;


function deleteHtml(temp) {
    $(".save_hidden").trigger("click");
    $("#login_type").val('save');
    $(".popup_container").css("display", "block");
    $("#page_ajax").html('<img src="images/ajax-loader.gif">').show();
    $.ajax({
        type: "POST",
        url: "login.php",
        data: "check=login",
        async: false,
        success: function (response) {

            var res = response.split("##");
            var app_id = res[1];
            var autherId = res[0];
            var deletePageindex = temp;

            if (autherId != '') {
                $("#author_id").val(autherId);
                if (app_id != '') {
                    $("#app_id").val(app_id);
                } else {
                    if (($('#appName').val()) != '') {
                        app_id = $("#app_id").val();
                    } else {
                        $("#page_ajax").html('').hide();
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');

                    }
                }

                shresponse1 = send_deleted_html(app_id, autherId, deletePageindex);


                $(".popup_container").css("display", "none");
                $("#page_ajax").html('').hide();

            } else {
                $("#page_ajax").html('').hide();
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
function saveOnDeletehtml() {
    $(".save_hidden").trigger("click");
    $("#login_type").val('save');
    $(".popup_container").css("display", "block");
    $("#page_ajax").html('<img src="images/ajax-loader.gif">').show();
    $.ajax({
        type: "POST",
        url: "login.php",
        data: "check=login",
        async: false,
        success: function (response) {
            var res = response.split("##");
            var app_id = res[1];
            var autherId = res[0];
            if (autherId != '') {
                $("#author_id").val(autherId);
                if (app_id != '') {
                    $("#app_id").val(app_id);
                } else {
                    if (($('#appName').val()) != '') {
                        app_id = $("#app_id").val();
                    } else {
                        $("#page_ajax").html('').hide();
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
                    }
                }
                shresponse = send_All_html(app_id, autherId);
                $(".popup_container").css("display", "none");
                $("#page_ajax").html('').hide();

            } else {
                $("#page_ajax").html('').hide();
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

function send_deleted_html(app_id, author_id, deletePageindex) {

    if (app_id == '' || app_id == null) {
        $(".popup_container").css("display", "none");
        $("#page_ajax").html('');
        alert("Please create App first.");
        $("#appName").focus();
        return false;
    } else {

        var bannerhtml = '';

        for (var j = 0; j < storedPages.length; j++)
        {
            if (storedPages[j].index == deletePageindex)
            {
                deletepageIndexId = storedPages[j].originalIndex;

                autherId = author_id;
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: "screen_id=" + deletepageIndexId + "&app_id=" + app_id + "&autherId=" + autherId + "&type=deletehtml",
                    success: function (response) {
                        if (response) {
                            $(".popup_container").css("display", "none");
                            $("#page_ajax").html('');
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
        }
        $(".popup_container").css("display", "none");
        $("#page_ajax").html('');
    }
}

function finish() {
    $("#login_type").val('save');
    $(".save_hidden").trigger("click");
    $("#screenoverlay").css("display", "block");
    $.ajax({
        type: "POST",
        url: "login.php",
        data: "check=login",
        async: false,
        success: function (response) {
            var res = response.split("##");
            var app_id = res[1];
            var autherId = res[0];
            if (autherId != '') {
                $("#author_id").val(autherId);
                if (app_id != '') {
                    $("#app_id").val(app_id);
                } else {
                    if (($('#appName').val()) != '') {
                        app_id = $("#app_id").val();
                    } else {
                        $("#page_ajax").html('').hide();
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');

                    }
                }
                //send html to server
                shresponse = send_html(app_id, autherId);
                createApp(app_id, autherId);

            }
            else {
                $("#page_ajax").html('').hide();
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

}

function createApp(app_id, autherId) {
 $.ajax({
        type: "POST",
        url: BASEURL + 'API/finish.php/index',
        data: "app_id=" + app_id,
        success: function (response) {
         

        },
        error: function () {
          
        }
    });
    $.ajax({
        type: "POST",
        url: BASEURL + 'parse.php',
        data: "app_id=" + app_id + "&themeId=1&app_type=1&autherId=" + autherId,
        success: function (response) {
            window.location.href = BASEURL + 'add-icons.php';

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

function addpages() {
    $("#login_type").val('page');
    $.ajax({
        type: "POST",
        url: "login.php",
        data: "check=login",
        success: function (response) {

            var res = response.split("##");
            var app_id = res[1];
            var autherId = res[0];
            if (autherId != '') {
                $("#author_id").val(autherId);
                if (app_id != '') {
                    $("#app_id").val(app_id);
                    $(".add_page_hidden").trigger("click");
                } else {
                    if (($('#appName').val()) != '') {
                        app_id = $("#app_id").val();
                        $(".add_page_hidden").trigger("click");
                    } else {
                        $("#page_ajax").html('').hide();
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');

                    }
                }
                shresponse = send_html(app_id, autherId);
                $(".popup_container").css("display", "none");
                $("#page_ajax").html('').hide();
            }
            else {
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
;


$(document).ready(function () {
//gaurav given code start
    var $items = $('.login_popup ul.tabs>li');
    $items.click(function () {
        $items.removeClass('active');
        $(this).addClass('active');

        var index = $items.index($(this));
        if (index == 0) {
            $('.login_popup').animate({
                height: '400px',
                width: '300px',
                marginTop: '-220px',
                marginLeft: '-150px'
            });
            $('.login_tabbing').hide().eq(index).show();
            $('.login_popup_head h2').hide().eq(index).show();
        } else {
            $('.login_popup').animate({
                height: '600px',
                width: '600px',
                marginTop: '-285px',
                marginLeft: '-300px'
            });
            $('.login_tabbing').hide().eq(index).show();
            $('.login_popup_head h2').hide().eq(index).show();
        }
    }).eq(0).click();

    $("#panel_login").click(function () {
        $("#login_type").val("");
        $("#login_type").val("panel");
        $("#login_email").val('');
        $("#login_password").val('');
        $("#register_email").val('');
        $("#register_password").val('');
        $("#register_repeat_password").val('');
        $(".popup_container").css("display", "block");
        $(".login_popup").css("display", "block");
        $('.login_popup .tabs li:first').trigger('click');
        $("#email_error").text("");
        $("#login_email").css("border", "1px solid #e5e5e5");
        $("#login_password").css("border", "1px solid #e5e5e5");
        $("#register_email").css("border", "1px solid #e5e5e5");
        $("#register_password").css("border", "1px solid #e5e5e5");
        $("#register_repeat_password").css("border", "1px solid #e5e5e5");
        $("#remail_error").text('');
        return false;
    });
    $(".close_popup").click(function () {
        $("#screenoverlay").fadeOut();
        $("#login_email").val('');
        $("#login_password").val('');
        $("#register_email").val('');
        $("#register_password").val('');
        $("#register_repeat_password").val('');
        $(".popup_container").css("display", "none");
        $(".login_popup").css("display", "none");
        $(".forgot_popup").css("display", "none");
        $(".reset_pass_popup").css("display", "none");
        $("#email_error").text("");
        $("#login_email").css("border", "1px solid #e5e5e5");
        $("#login_password").css("border", "1px solid #e5e5e5");
        $("#register_email").css("border", "1px solid #e5e5e5");
        $("#register_password").css("border", "1px solid #e5e5e5");
        $("#register_repeat_password").css("border", "1px solid #e5e5e5");
        $("#remail_error").text('');
        $("#login_type").val('');
        return false;
    });


    $(".forgot_password").click(function () {
        $(".popup_container").css("display", "block");
        $(".login_popup").css("display", "none");
        $(".forgot_popup").css("display", "block");
		$(".forgot_popup .forgot_popup_body #signup_reset").hide();
        $(".signup_success_popup").hide();
    })


//gaurav given code end ;

//function for crm api call start
function crmapicall(){
$.ajax({
        url : 'loginCrmCall.php',
        type : 'POST',
        async : true,
        success : function() {
              

        }
    });
}
//function for crm api call end	

//login check start	

    $("#login").click(function () {
        username = $("#login_email").val().trim();
        password = $("#login_password").val().trim();
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if ((username == '' || username == null) && (password == '' || password == null))
        {
            $("#login_email").css("border", "1px solid #ff0000");
            $("#login_password").css("border", "1px solid #ff0000");
            return false;
        }
        if (username == '' || username == null) {
            $("#login_email").css("border", "1px solid #ff0000");
            return false;
        }
        else if (!regex.test(username)) {
            $(".login_popup").css("height", "400px");
            $("#login_email").css("border", "1px solid #ff0000");
            $("#email_error").css("margin-top", "-10px");
            $("#email_error").text("Invalid email");
            return false;
        }
        else {
            $("#login_email").css("border", "1px solid #e5e5e5");
            $("#email_error").text("");
            $("#email_error").css("margin-top", "0");
        }
        if (password == '' || password == null)
        {
            $("#login_password").css("border", "1px solid #ff0000");
            return false;
        }
        else {
            $("#login_password").css("border", "1px solid #e5e5e5");
        }
        if ($("#remember").is(':checked'))
            remember = 'yes';
        else
            remember = '';
        $.ajax({
            type: "POST",
            url: "login.php",
            data: "username=" + username + "&password=" + password + "&remember=" + remember + "&type=login"+ "&unsetsessiondata=1",
            success: function (response) {
                var res = response.split("##");
                if (res[0] == 'success') {
					crmapicall();
                    var userImage = res[2];
                    var reseller_id = res[4];
                    var is_reseller = res[5];
                    if (userImage != '')
                    {
                        var imageHtml = '<img alt="Image Not Found" src="avatars/' + userImage + '">';
                    }
                    else
                    {
                        var imageHtml = '<img alt="Image Not Found" src="avatars/user.png">';
                    }
                    $(".user_img").html('<div class="user_img_icon"><a href="userprofile.php">' + imageHtml + '</a></div><div class="clear"></div><span><a href="userprofile.php"></a></span>');
                    if (res[1] == '' || res[1] == null) {
                        $(".user_img span a").text('Hi, Guest');
                    }
                    else {
                        $(".user_img span a").text('Hi,  ' + res[1]);
                    }
                    $("#panel_login").css("display", "none");
                    $("#loginout").html('<a href="logout.php" id="panel_logout" onclick="logout();"> <img src="images/login_icon.png" /> <span>Logout</span></a>');
                    $(".login_popup").css("height", "400px");
                    $(".login_popup").css("margin-top", "-210px");
                    $("#loading").addClass("success");
                    $("#loading").html("Login successfully.");
                    $(".popup_container").css("display", "none");
                    $(".login_popup").css("display", "none");
                    var out = mail_confirm();
                    if (out > 0) {
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form p").text("Please complete your profile to proceed.");
                        setTimeout(function () {
                            window.location.href = BASEURL + 'userprofile.php';
                        }, 5000);
                        return false;
                    }
                    if(reseller_id>0 && is_reseller==''){
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form p").text("You can not create app.Please contact your reseller. ");
                        /* alert(reseller_id);return false;*/
                        setTimeout(function(){                        
                          window.location =BASEURL + 'applisting.php';   
                        }, 2000);
                        return false;
                    }
                    if ($('#appName').val() != '' && $('#appName').val() != 'undefined' && $('#appName').val() != null) {
                        saveAppPanel();
                    }

                    if ($("#login_type").val() == 'product') {
                        $("#oc_product").trigger("click");
                    }
                    if ($("#login_type").val() == 'myapp') {
                        window.location = 'applisting.php';
                    }

                }
                else if (response == 'invalid') {
                    $(".login_popup").css("height", "400px");
                    $(".login_popup").css("margin-top", "-210px");
                    $("#loading").html("Please Verify Your Email To SignIn.");
                }
                else if (res[0] == 'fail') {
                    $(".login_popup").css("height", "400px");
                    $(".login_popup").css("margin-top", "-210px");
                    $("#loading").html("Invalid Username or Password.");
                    $("#loading").css("color", "#f00");
                }
                else {
                    $(".login_popup").css("height", "400px");
                    $(".login_popup").css("margin-top", "-210px");
                    $("#loading").html("OOps something went wrong.Try again later");
                }
            },
            error: function () {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('').hide();
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                $(".confirm_name").css({'display': 'block'});
                return false;
            },
            beforeSend: function () {
                $("#loading").addClass("success");
                $(".login_popup").css("height", "400px");
                $(".login_popup").css("margin-top", "-210px");
                $("#loading").html("Logging in....");
                $("#loading").css("color", "#008000");
            }


        });
        return false;
    });
//login check end	
//Register check start	

    $("#signup").click(function () {
        
        first_name = $("#author_first_name").val().trim();
        last_name = $("#author_last_name").val().trim();
        company_name = $("#author_company_name").val().trim();
        mobile_number = $("#author_mobile_number").val().trim();
        mobile_country = $("#mobile_country").val().trim();
        username = $("#register_email").val().trim();
        password = $("#register_repeat_password").val().trim();
        password1 = $("#register_password").val().trim();
        ip_address = $("#ip_address").val().trim();
        author_product = $("#author_product").val();
        author_product_category = $("#author_product_category").val();        
       var countryC = $('#signup_form ul.country-list').find('li.active').attr('data-dial-code');
		var countryN = $('#signup_form ul.country-list').find('li.active').attr('data-country-code');
		var space = '   ';
        captcha_code = $("#captcha-code").val();
        //var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/; 		  		
        var phoneno = /^\d+$/;
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        $("#register_loading").html('');
        if ((first_name == '' || first_name == null) && (last_name == '' || last_name == null) && (mobile_number == '' || mobile_number == null) && (username == '' || username == null) && (password == '' || password == null) && (password1 == '' || password1 == null) && (author_product == '' || author_product == null) && (author_product_category == '' || author_product_category == null) && (captcha_code == '' || captcha_code == null) && (captcha_code == '' || captcha_code == null)) {
            $("#author_first_name").css("border", "1px solid #ff0000");
            $("#author_last_name").css("border", "1px solid #ff0000");
            $("#author_mobile_number").css("border", "1px solid #ff0000");
            $("#author_product").css("border", "1px solid #ff0000");
            $("#author_product_category").css("border", "1px solid #ff0000");
            $("#register_email").css("border", "1px solid #ff0000");
            $("#register_password").css("border", "1px solid #ff0000");
            $("#register_repeat_password").css("border", "1px solid #ff0000");
            $("#captcha-code").css("border", "1px solid #ff0000");
            return false;
        }
        if (first_name == '' || first_name == null)
        {
            $("#author_first_name").css("border", "1px solid #ff0000");
            return false;
        }
        else {
            $("#author_first_name").css("border", "1px solid #e5e5e5");
        }
        if (last_name == '' || last_name == null)
        {
            $("#author_last_name").css("border", "1px solid #ff0000");
            return false;
        }
        else {
            $("#author_last_name").css("border", "1px solid #e5e5e5");
        }
        if (mobile_number == '' || mobile_number == null)
        {
            $("#author_mobile_number").css("border", "1px solid #ff0000");
            return false;
        }
        else if (!mobile_number.match(phoneno))
        {
            $("#author_mobile_number").css("border", "1px solid #ff0000");
            $("#register_loading").html("Invalid Phone Number");
            return false;
        }
        else if (mobile_number.length < 5)
        {
            $("#author_mobile_number").css("border", "1px solid #ff0000");
            $("#register_loading").html("Invalid Phone Number");
            return false;
        }
        else {
            $("#author_mobile_number").css("border", "1px solid #e5e5e5");
        }
        if (username == '' || username == null) {
            $("#register_email").css("border", "1px solid #ff0000");
            return false;
        }
        else if (!regex.test(username)) {
            $("#register_email").css("border", "1px solid #ff0000");
            $("#register_loading").html("Invalid email");
            return false;
        }
        else {
            $("#register_email").css("border", "1px solid #e5e5e5");
        }
        if (password == '' || password == null)
        {
            $("#register_repeat_password").css("border", "1px solid #ff0000");
            return false;
        }
        else {
            $("#register_repeat_password").css("border", "1px solid #e5e5e5");
        }
        if (password1 == '' || password1 == null)
        {
            $("#register_password").css("border", "1px solid #ff0000");
            return false;
        }
        else {
            $("#register_password").css("border", "1px solid #e5e5e5");
        }
        if (author_product == '' || author_product == null)
        {
            $("#author_product").css("border", "1px solid #ff0000");
            return false;
        }
        else {
            $("#author_product").css("border", "1px solid #e5e5e5");
        }
        if (author_product_category == '' || author_product_category == null)
        {
            $("#author_product_category").css("border", "1px solid #ff0000");
            return false;
        }
        else {
            $("#author_product_category").css("border", "1px solid #e5e5e5");
        }
        // if (captcha_code == '' || captcha_code == null)
        // {
        // $("#captcha-code").css("border", "1px solid #ff0000");
        // return false;
        // }
        // else {
        // $("#captcha-code").css("border", "1px solid #e5e5e5");
        // }


        if (password1 != password) {
            $("#register_loading").html("Password does not match.");
            return false;
        }
        if (password.length < 8) {
            $("#register_password").css("border", "1px solid #ff0000");
            $("#register_loading").html("Password should be minimum 8 characters");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "login.php",
            data: "first_name=" + first_name + "&last_name=" + last_name + "&company_name=" + company_name + "&author_product=" + author_product + "&author_product_category=" + author_product_category + "&captcha_code=" + captcha_code + "&mobile_number=" + mobile_number +"&mobile_country="+mobile_country+ "&username=" + username + "&password=" + password + "&type=register&ip_address="+ip_address + "&countryC=" + countryC + "&countryN=" + countryN,
            success: function (response) {
                $("img#captcha-refresh").trigger("click");
               	if (response == 'success') {         
					
					 $.ajax({
						type: "POST",
						url: "ajax.php",
						data: "firstname="+first_name+"&lastname="+last_name+"&company="+company_name+"&telephone="+mobile_number+"&email="+username+"&password=" + password + "&type=vendor_register&confirm="+password+"&username="+username+"&city="+space+"&postcode=0&country_id=0&zone_id=0&appid=0&curr_id=0&paypal="+username+"&agree=1&address_1="+space,
						success: function (resellerData) {			
							if (resellerData == 'success') {
								
								
							}
							}
					    });
					
                    $("#signup_form")[0].reset();
                    $(".login_popup").hide();
                    $(".signup_success_popup").show();
                    /*$("#register_loading").addClass("success");
                    $("#register_loading").html("Thank you For Signing Up. Please Verify Your Email To Sign-in.");*/
                }
                else if (response == 401) {
                    $("#register_loading").removeClass();
                    $("#register_loading").html("Captcha code not correct. Try again");
                }
                else if (response == 415) {
                    $("#register_loading").removeClass();
                    $("#register_loading").html("Email id is already registered");
                }
                else if (response == 405) {
                    $("#register_loading").removeClass();
                    $("#register_loading").html("Parameters can not empty");
                }
                else if (response == 403) {
                    $("#register_loading").removeClass();
                    $("#register_loading").html("Authentication Failed.Try again later");
                }
                else {
                    $("#register_loading").removeClass();
                    $("#register_loading").html("OOps something went wrong.Try again later");
                }
            },
            beforeSend: function () {
                $("#register_loading").addClass("success");
                $("#register_loading").html("Please Wait request in process....");
            }


        });
        return false;
    });
	$(".forgot_popup .forgot_popup_body #signup_reset").on("click" , function(){
		
        $(".popup_container").hide();
		$(".forgot_popup").hide();
        $(".signup_success_popup").hide();
    });
    $("#author_product").change(function () {
        var product = $(this).val();
        $.ajax({
            type: "POST",
            url: "login.php",
            data: "author_product=" + product + "&type=author_choose_app_type",
            success: function (response) {
                if (response) {
                    $("#author_product_category").html(response);
                    $("#register_loading").html("");
                    $("#register_loading").removeClass();
                }
                else if (response == 'fails') {
                    $("#register_loading").removeClass();
                    $("#register_loading").html("OOps something went wrong.Try again later");
                }

                else {
                    $("#register_loading").removeClass();
                    $("#register_loading").html("OOps something went wrong.Try again later");
                }
            },
            beforeSend: function () {
                $("#register_loading").addClass("success");
                $("#register_loading").html("Please Wait request in process....");
            }


        });
    });
    $('img#captcha-refresh').click(function () {

        change_captcha();
    });

    function change_captcha()
    {
        document.getElementById('captcha').src = "includes/get_captcha.php?rnd=" + Math.random();
    }
    //Register check end

    $("#reset").click(function () {
        forgot_email = $("#forgot_email").val().trim();
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (forgot_email == '' || forgot_email == null) {
            $("#forgot_email").css("border", "1px solid #ff0000");
            return false;
        }
        else if (!regex.test(forgot_email)) {
            $(".forgot_popup").css("height", "240px");            
            $("#forgot_email").css("border", "1px solid #ff0000");
            $(".forgot_popup .forgot_popup_body #ferror_email").css("color", "#ff0000");
            $(".forgot_popup .forgot_popup_body #ferror_email").text("Invalid email");
            return false;
        }
        else {
            $("#forgot_email").css("border", "1px solid #e5e5e5");
            $("#ferror_email").text('');
        }

        $.ajax({
            type: "POST",
            url: "login.php",
            data: "email=" + forgot_email + "&type=forgot",
            success: function (response) {
                if (response == 'success') {
                    $(".forgot_popup").css("height", "250px");
					$(".forgot_popup .forgot_popup_body #loading_forgot").css("color", "green");
					$(".forgot_popup .forgot_popup_body #loading_forgot").html("Please check your email to reset your password.");
                    $("#forgot_email").val('');
					$(".forgot_popup .forgot_popup_body #signup_reset").show();
					$("#reset").hide();
					$("#forgot_email").hide();
					// $(".forgot_popup_body #loading_forgot").hide();
                }
                else if (response == 'fail') {
                    $(".forgot_popup").css("height", "250px");
                    $("#loading_forgot").css("color", "#ff0000");
                    $("#loading_forgot").html("Unable to reset password .Try again later");
                    setTimeout(function () {
                        $("#loading_forgot").html('');
                    }, 5000);
                }
                else if (response == 'invalid') {
                    $(".forgot_popup").css("height", "250px");
                    $(".forgot_popup .forgot_popup_body #loading_forgot").css("color", "#ff0000");
                    $(".forgot_popup .forgot_popup_body #loading_forgot").html("This email id is not registered.");
                    // setTimeout(function () {
                    //     $("#loading_forgot").html('');
                    // }, 5000);
                }
                else {
                    $(".forgot_popup").css("height", "250px");
                    $("#loading_forgot").css("color", "#ff0000");
                    $("#loading_forgot").html("OOps something went wrong.Try again later");
                    setTimeout(function () {
                        $("#loading_forgot").html('');
                    }, 5000);
                }
            },
            error: function () {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('').hide();
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                $(".confirm_name").css({'display': 'block'});
                return false;
            },
            beforeSend: function () {
                $(".forgot_popup").css("height", "250px");
                $("#loading_forgot").html("Please wait Sending email....");
            }
        });
        return false;

    });
    //email verification link start
    $("#reset_email").click(function () {
        resend_email = $("#email_resend").val().trim();
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (resend_email == '' || resend_email == null) {
            $("#email_resend").css("border", "1px solid #ff0000");
            return false;
        }
        else if (!regex.test(resend_email)) {
            $(".em_ver").css("height", "240px");
            $("#resend_error").css("color", "#ff0000");
            $("#email_resend").css("border", "1px solid #ff0000");
            $("#resend_error").text("Invalid email");
            return false;
        }
        else {
            $("#email_resend").css("border", "1px solid #e5e5e5");
            $("#resend_error").text('');
        }
        $.ajax({
            type: "POST",
            url: "login.php",
            data: "email=" + resend_email + "&type=resend",
            success: function (response) {
                if (response == 'success') {
                   // $("#email_resend").val('');
                    $(".em_ver").css("height", "300px");
					$("#reset_email").hide();
					$("#email_resend").hide();
					$("#signup_reset").show();
					$("#test1").hide();
                    //$("#forgot_email").val('');
					  $("#loading_resend").css("color", "green");
                    $("#loading_resend").html("A verification link has been sent to your email. Please verify your email.");
                    setTimeout(function () {
                        $("#loading_resend").html('');
                    }, 5000);
                }
                else if (response == 'fail') {
                    $("#email_resend").val('');
                    $(".em_ver").css("height", "280px");
                    $("#loading_resend").css("color", "#ff0000");
                    $("#loading_resend").html("Unable to send  email .Try again later");
                    setTimeout(function () {
                        $("#loading_resend").html('');
                    }, 5000);
                }
                else if (response == 'invalid') {
                    $("#email_resend").val('');
                    $(".em_ver").css("height", "280px");
                    $("#loading_resend").css("color", "#ff0000");
                    $("#loading_resend").html("This email id is not registered.");
                    setTimeout(function () {
                        $("#loading_resend").html('');
                    }, 5000);
                }
                else {
                    $("#email_resend").val('');
                    $(".em_ver").css("height", "280px");
                    $("#loading_resend").css("color", "#ff0000");
                    $("#loading_resend").html("OOps something went wrong.Try again later");
                    setTimeout(function () {
                        $("#loading_resend").html('');
                    }, 5000);
                }
            },
            error: function () {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('').hide();
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                $(".confirm_name").css({'display': 'block'});
                return false;
            },
            beforeSend: function () {
                $(".em_ver").css("height", "280px");
                $("#loading_resend").html("Please wait Sending email....");
            }
        });
        return false;

    });
    //email verification link end
//load more apps
    var page = 2;
    $("#load_more_apps").click(function () {
        var user_id = $("#user_id").val();
        var type = $("#app_type").val();
        $.ajax({
            type: "POST",
            url: "login.php",
            data: "user_id=" + user_id + "&type=" + type + "&page=" + page,
            success: function (response) {

                var res = response.split("--");

                if (res[0] != '') {
                    if (res[2] == 6) {
                        $("#load_more_apps").show();
                        $(".apps_list").append(res[0]);
                        $("#app_loader").html('');
                    } else if (res[3] == 6) {
                        $("#load_more_apps").show();
                        $(".apps_list").append(res[0]);
                        $("#app_loader").html('');
                    } else {
                        $("#load_more_apps").hide();
                        $(".apps_list").append(res[0]);
                        $("#app_loader").html('');
                    }

                }
                else if (res[0] == '') {
                    $("#app_loader").html("No Records Found");
                    $("#load_more_apps").hide();
                }
                else {
                    $("#app_loader").html("OOps something went wrong.Try again later");
                }
            },
            error: function () {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('').hide();
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                $(".confirm_name").css({'display': 'block'});
                return false;
            },
            beforeSend: function () {
                $("#app_loader").html('<img src="images/ajax-loader.gif"/>');
            }
        });

        page++;
    });
    $("#app_status").change(function () {
        page = 2;
        $("#app_loader").html('');
        var user_id = $("#user_id").val();
        var type = $(this).val();
        $("#app_type").val(type);
        $.ajax({
            type: "POST",
            url: "login.php",
            data: "user_id=" + user_id + "&type=" + type + "&page=0",
            success: function (response) {
                var res = response.split("--");

                if (res[0]) {
                    if (res[1] > 6) {
                        $("#load_more_apps").show();
                        $(".apps_list").html(res[0]);
                    } else {
                        $("#load_more_apps").hide();
                        $(".apps_list").html(res[0]);
                    }
                } else if (res[0] == '') {
                    $(".apps_list").html("No More Records");
                    $("#load_more_apps").hide();
                }
                else {
                    $(".apps_list").html("OOps something went wrong.Try again later");
                }
            },
            error: function () {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('').hide();
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                $(".confirm_name").css({'display': 'block'});
                return false;
            },
            beforeSend: function () {
                $(".apps_list").html('<img src="images/ajax-loader.gif"/>');
            }
        });

    });

//my app redirection

    $("#myapps").click(function () {
        $("#login_type").val('myapp');
        $.ajax({
            type: "POST",
            url: "login.php",
            data: "check=login",
            success: function (response) {
                if (response) {
                    window.location = 'applisting.php';

                }
                else {
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
    });

});
window.fbAsyncInit = function () {
    FB.init({
        appId: '1648805362003514', //1648805362003514 for production
        oauth: true,
        status: true, // check login status
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true // parse XFBML
    });

};

function fb_login() {
    FB.login(function (response) {

        if (response.authResponse) {
            console.log('Welcome!  Fetching your information.... ');
            //console.log(response); // dump complete info
            //access_token = response.authResponse.accessToken; //get access token
            var authToken = response.authResponse.accessToken;
            testAPI(authToken);

        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');

        }
    }, {
        scope: 'public_profile,email'
    });
}




// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI(authToken) {
    FB.api('me?fields=id,name,email,first_name,last_name', function (response) {
        var fname = response.first_name;
        var lname = response.last_name;
        var userFbId = response.id;
        var email = response.email;
        if (fname == '' || fname == undefined)
            fname = response.name;
        if (lname == '' || lname == undefined)
            lname = '';
        if (email == '' || email == undefined)
            email = '';

        $.ajax({
            type: "POST",
            url: "login.php",
            data: "fb_token=" + authToken + "&fname=" + fname + "&lname=" + lname + "&username=" + email + "&userFbId=" + userFbId + "&type=fb",
            success: function (response) {
                if (response == 'success') {
                    $(".user_img").html('<div class="user_img_icon"><a href="userprofile.php"><img alt="Image Not Found" src="avatars/' + userFbId + '.jpg"></a></div><div class="clear"></div><span><a href="userprofile.php">Hi,  ' + fname + '</a></span>');
                    $("#panel_login").css("display", "none");
                    $("#loginout").html('<a href="logout.php" id="panel_logout" onclick="logout();"> <img src="images/login_icon.png" /> <span>Logout</span></a>');
                    $(".login_popup").css("height", "400px");
                    $(".login_popup").css("margin-top", "-210px");
                    $("#loading").addClass("success");
                    $("#loading").html("Register successfully.");
                    $(".popup_container").css("display", "none");
                    $(".login_popup").css("display", "none");
                    var out = mail_confirm();
                    if (out > 0) {
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name .confirm_name_form p").text("Please complete your profile to proceed.");
                        setTimeout(function () {
                            window.location.href = BASEURL + 'userprofile.php';
                        }, 2000);
                        return false;
                    }



                    if ($('#appName').val() != '' && $('#appName').val() != 'undefined' && $('#appName').val() != null) {
                        saveAppPanel();
                    }

                    if ($("#login_type").val() == 'product') {
                        $("#oc_product").trigger("click");
                    }
                    if ($("#login_type").val() == 'myapp') {
                        window.location = 'applisting.php';
                    }

                }
                else if (response == 415) {
                    $("#loading").removeClass();
                    $(".login_popup").css("height", "400px");
                    $(".login_popup").css("margin-top", "-210px");
                    $("#loading").html("Email id is already register");
                }
                else if (response == 405) {
                    $("#loading").removeClass();
                    $(".login_popup").css("height", "400px");
                    $(".login_popup").css("margin-top", "-210px");
                    $("#loading").html("Parameters can not empty");
                }
                else if (response == 403) {
                    $("#loading").removeClass();
                    $(".login_popup").css("height", "400px");
                    $(".login_popup").css("margin-top", "-210px");
                    $("#loading").html("Authentication Failed.Try again later");
                }
                else {
                    $("#loading").removeClass();
                    $(".login_popup").css("height", "400px");
                    $(".login_popup").css("margin-top", "-210px");
                    $("#loading").html("OOps something went wrong.Try again later");
                }
            },
            error: function () {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('').hide();
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                $(".confirm_name").css({'display': 'block'});
                return false;
            },
            beforeSend: function () {
                $("#loading").addClass("success");
                $(".login_popup").css("height", "400px");
                $(".login_popup").css("margin-top", "-210px");
                $("#loading").html("Please Wait request in process....");
            }


        });



    });
}
function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    var authToken = '';
    var fname = profile.getName();
    var lname = '';
    var userFbId = profile.getId();
    var email = profile.getEmail();
    $.ajax({
        type: "POST",
        url: "login.php",
        data: "gPlus_token=" + userFbId + "&fname=" + fname + "&lname=" + lname + "&username=" + email + "&userFbId=" + userFbId + "&type=gplus",
        success: function (response) {
//console.log(response);		
            if (response == 'success') {
                $(".user_img").html('<div class="user_img_icon"><a href="userprofile.php"><img alt="Image Not Found" src="avatars/' + userFbId + '.jpg"></a></div><div class="clear"></div><span><a href="userprofile.php">Hi,  ' + fname + '</a></span>');
                $("#panel_login").css("display", "none");
                $("#loginout").html('<a href="logout.php" id="panel_logout" onclick="logout();"> <img src="images/login_icon.png" /> <span>Logout</span></a>');
                $(".login_popup").css("height", "400px");
                $(".login_popup").css("margin-top", "-210px");
                $("#loading").addClass("success");
                $("#loading").html("Register successfully.");
                $(".popup_container").css("display", "none");
                $(".login_popup").css("display", "none");
//                if ($("#login_type").val() == 'page') {
//                    if ($('#appName').val() != '')
//                    {
//                        addnewAppName();
//                    }
//                    //$(".add_page_hidden").trigger("click");
//                    send_html($("#app_id").val(), $("#author_id").val());
//                }
//                if ($("#login_type").val() == 'save') {
//                    if ($('#appName').val() != '')
//                    {
//                        addnewAppName();
//                    }
//                    send_html($("#app_id").val(), $("#author_id").val());
//                }

                if ($('#appName').val() != '') {
                    saveAppPanel();
                }
                if ($("#login_type").val() == 'product') {
                    $(".addproduct").trigger("click");
                }
                if ($("#login_type").val() == 'myapp') {
                    window.location = 'applisting.php';
                }

            }
            else if (response == 415) {
                $(".login_popup").css("height", "400px");
                $(".login_popup").css("margin-top", "-210px");
                $("#loading").html("Email id is already register");
            }
            else if (response == 405) {
                $(".login_popup").css("height", "400px");
                $(".login_popup").css("margin-top", "-210px");
                $("#loading").html("Parameters can not empty");
            }
            else if (response == 403) {
                $(".login_popup").css("height", "400px");
                $(".login_popup").css("margin-top", "-210px");
                $("#loading").html("Authentication Failed.Try again later");
            }
            else {
                $(".login_popup").css("height", "400px");
                $(".login_popup").css("margin-top", "-210px");
                $("#loading").html("OOps something went wrong.Try again later");
            }
        },
        error: function () {
            $(".cropcancel").trigger("click");
            $("#page_ajax").html('').hide();
            $(".popup_container").css({'display': 'block'});
            $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
            $(".confirm_name").css({'display': 'block'});
            return false;
        },
        beforeSend: function () {
            $(".login_popup").css("height", "400px");
            $(".login_popup").css("margin-top", "-210px");
            $("#loading").html("Please Wait request in process....");
        }


    });


}

/*
 * code is updated by Arun Srivastava on 25/11/15
 */
function send_html(app_id, author_id)
{
    if (app_id == '' || app_id == null)
	{
        $(".popup_container").css("display", "none");
        $("#page_ajax").html('').hide();
        $("#screenoverlay").css("display", "none");
        $(".cropcancel").trigger("click");
        $(".popup_container").css({'display': 'block'});
        $(".confirm_name .confirm_name_form .textpop").html('Please choose app name');
        $(".confirm_name").css({'display': 'block'});
        $("#appName").focus();
        return false;
    }
	else
	{
		//save_app_contact_details(app_id); 
        var j = 0;
        var k = 0;
        var bannerhtml = '';
		
		var cEmail = $(".contactusEmail").val();
		var cphone = $(".contactusNo").val();

        for (var k = 0; k < storedPages.length; k++)
        {
            actualIndex = storedPages[k].index;
            pageIndex = storedPages[k].originalIndex;
			
            if (actualIndex == selectedPageIndex)
            {

                html = storedPages[k].contentarea;
                layouttype = storedPages[k].layouttype;
                pagename = pageDetails[k].name;
				var keywords='';
				
				keywords = pageDetails[k].keyword;

				
                icon = JSON.stringify(pageDetails);

                navbarhtml = navbar;

                if (storedPages[k].banner == '' || storedPages[k].banner == undefined)
                {
                    bannerhtml = '';
                }
                else {
                    bannerhtml = storedPages[k].banner;
                }
                app_id = app_id;
                autherId = author_id;
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: "html=" + encodeURIComponent(html) + "&layoutType=" + layouttype + "&linkTo=1&screen_id=" + pageIndex + "&originalIndex=" + actualIndex + "&app_id=" + app_id + "&autherId=" + autherId + "&title=" + encodeURIComponent(pagename) + "&keywords=" + encodeURIComponent(keywords) + "&banner_html=" + encodeURIComponent(bannerhtml) + "&navigation_html=" + encodeURIComponent(navbarhtml) + "&ico_icon=" + encodeURIComponent(icon) + "&cemail=" + cEmail + "&cphone=" + cphone + "&type=html",
                    async: false,
                    success: function (response) {
                        if (response) {
                            $(".popup_container").css("display", "none");
                            $("#page_ajax").html('').hide();
                        }
                    },
                    error: function () {
                        $(".cropcancel").trigger("click");
                        $("#page_ajax").html('').hide();
                        $(".popup_container").css({'display': 'block'});
                        $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                        $(".confirm_name").css({'display': 'block'});
                        return false;
                    },
                });
            }


            $(".popup_container").css("display", "none");
            $("#page_ajax").html('').hide();
        }
    }
}

function save_app_contact_details(app_id){
	var  cEmail=$(".contactusEmail").val();
	var cphone=$(".contactusNo").val();
	$.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: "cemail=" + cEmail + "&cphone=" + cphone + "&app_id=" + app_id + "&type=app_contact_details",
                    async: false,
                    success: function (response) {
                        if (response) {
                            $(".popup_container").css("display", "none");
                            $("#page_ajax").html('').hide();
                        }
                    },
                    error: function () {                       
                        $("#page_ajax").html('').hide();
                        $(".popup_container").css({'display': 'block'});
                        $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                        $(".confirm_name").css({'display': 'block'});
                        return false;
                    },
                });
	
}
function send_All_html(app_id, author_id) {
    if (app_id == '' || app_id == null) {
        $(".popup_container").css("display", "none");
        $("#page_ajax").html('').hide();
        $("#screenoverlay").css("display", "none");
        $(".cropcancel").trigger("click");
        $(".popup_container").css({'display': 'block'});
        $(".confirm_name .confirm_name_form .textpop").html('Please choose app name');
        $(".confirm_name").css({'display': 'block'});
        $("#appName").focus();
        return false;
    }
    else
    {
        var j = 0;
        var k = 0;
        var bannerhtml = '';

        for (var k = 0; k < storedPages.length; k++)
        {
            actualIndex = storedPages[k].index;
            pageIndex = storedPages[k].originalIndex;
            html = storedPages[k].contentarea;
            layouttype = storedPages[k].layouttype;
            pagename = pageDetails[k].name;
            icon = JSON.stringify(pageDetails);
            navbarhtml = navbar;
            if (storedPages[k].banner == '' || storedPages[k].banner == undefined)
            {
                bannerhtml = '';
            }
            else {
                bannerhtml = storedPages[k].banner;
            }
            autherId = author_id;
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: "html=" + encodeURIComponent(html) + "&layoutType=" + layouttype + "&linkTo=1&screen_id=" + pageIndex + "&originalIndex=" + actualIndex + "&app_id=" + app_id + "&autherId=" + autherId + "&title=" + pagename + "&banner_html=" + encodeURIComponent(bannerhtml) + "&navigation_html=" + encodeURIComponent(navbarhtml) + "&ico_icon=" + icon + "&type=html",
                async: false,
                success: function (response) {
                    if (response) {
                        $(".popup_container").css("display", "none");
                        $("#page_ajax").html('').hide();
                    }
                },
                error: function () {
                    $(".cropcancel").trigger("click");
                    $("#page_ajax").html('').hide();
                    $(".popup_container").css({'display': 'block'});
                    $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
                    $(".confirm_name").css({'display': 'block'});
                    return false;
                },
            });



            $(".popup_container").css("display", "none");
            $("#page_ajax").html('').hide();
        }
    }
}

function logout() {
    //FB.logout(function(response) {
    //console.log(response);
//});
    signOut();
}
function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        //console.log('User signed out.');
    });
}
function onFailure(error) {
    console.log(error);
}
function renderButton() {
    gapi.signin2.render('my-signin2', {
        'scope': 'https://www.googleapis.com/auth/plus.login',
        'width': 100,
        'height': 25,
        'longtitle': false,
        'theme': 'dark',
        'onsuccess': onSignIn,
        'onfailure': onFailure
    });
}