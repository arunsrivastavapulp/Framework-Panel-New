$(document).ready(function () {
    $(document).on("click", ".confirm_name_form input[type='button']", function () {
        $(".popup_container").css("display", "none");
        $(".confirm_name").css("display", "none");
    });

//29-07-2015


    $(".free_down").click(function () {
        $(".popup_container").css("display", "block");
        $(".download_popup").css("display", "block");

    })
    $("#send_file").click(function () {
        email = $("#download_email").val();
        device = $("#download_device").val();
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if ((email == '' || email == null) && (device == '' || device == null))
        {
            $("#download_email").css("border", "1px solid #ff0000");
            $("#download_device").css("border", "1px solid #ff0000");
            return false;
        }
        if (email == '' || email == null) {
            $("#download_email").css("border", "1px solid #ff0000");
            return false;
        }
        else if (!regex.test(email)) {
            $("#download_email").css("border", "1px solid #ff0000");
            $("#demail_error").text("Invalid email");
            return false;
        }
        else {
            $("#download_email").css("border", "1px solid #e5e5e5");
            $("#demail_error").text("");
        }
        if (device == '' || device == null)
        {
            $("#download_device").css("border", "1px solid #ff0000");
            return false;
        }
        else {
            $("#download_device").css("border", "1px solid #e5e5e5");
        }
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: "email=" + email + "&device=" + device + "&type=trial",
            success: function (response) {
                if (response == 'success') {
                    $("#download_email").val('');
                    $("#download_device").val('');
                    $("#download_loading").html("Please check your email for details. ");
                }
                else if (response == 'fail') {

                    $("#download_loading").html("Invalid Username or Password.");
                }
                else {
                    $("#download_loading").html("OOps something went wrong.Try again later");
                }
            },
            beforeSend: function () {
                $("#download_loading").html("Please wait....");
            }


        });
        return false;


    })
    $("#save_app").click(function () {
        $("#screenoverlay").css("display", "block");
        app_id = $("#appid").val();
        if (app_id == '') {
            save_app();
        }

    })
    $("#oc_product").click(function () {
        result = form_validation();
        if (result > 0) {
            app_id = $("#appid").val();
            if (app_id == '') {
                output = save_app();
                if (output > 0) {
                    send_to_catalogue();
                }
            }
            else if (app_id) {
                output = save_app();
                if (output > 0) {
                    send_to_catalogue();
                }

            }
        }
    });
	
	/*
	 * edited by Arun Srivastava on 25/11/15
	 */
	var external_app_id  = '';
	var external_user_id = '';
    $("#save_catalogue_app").click(function () {
        result = save_form_validation();
        save_msg = $("#save_msg").val();
        if (result > 0) {
            out = save_app();
            if (out > 0) {
                app_id = $("#appid").val();
                if (app_id > 0) {
                    $("#screenoverlay").css("display", "none");
                    if (save_msg == 'yes') {
                        $(".popup_container").css("display", "block");
                        $(".confirm_name").css("display", "block");
                        $(".confirm_name_form p").text("Data Saved Successfully.");
                    }
                    $(".addproduct").text("View Products");
                    $("#action").val("edit");
                }
            }
        }

    });
    $("#finish_catalogue_app").click(function () {
        result = form_validation();
        if (result > 0) {
            outMob = mail_confirm("mob_confirm");
            if (outMob == 0) {
                out = save_app();
                if (out > 0) {
                    app_id = $("#appid").val();
                    if (app_id > 0) {
                        $("#screenoverlay").css("display", "none");
                        $(".addproduct").text("View Products");
                        $("#action").val("edit");
                        window.location = 'add-icons.php?app_id=' + app_id;
                    }
                }
            } else {
                save_app();
                $(".popup_container").css("display", "block");
                $(".confirm_name").css("display", "block");
                $(".confirm_name_form p").text("Please complete your profile to proceed.");
                window.location = 'userprofile.php';
                return x;
            }
        }
    })


});

/*
 * edited by Arun Srivastava on 25/11/15
 */

var external_app_id  = '';
var external_user_id = ''; 

function save_app()
{
	var x = 0;
	
	if(external_app_id != '' && external_user_id != '')
	{
		$("#author").val(external_user_id);
		var formData = new FormData($("#catalogue_app_form_new")[0]);
		$.ajax({
			type: "POST",
			url: "ajax.php",
			async: false,
			data: formData,
			success: function (response) {
				if (response > 0) {
					x = 1;
					$("#appid").val(response);
					return x;
				}
				else if (response == 'app_exit') {
					$("#screenoverlay").css("display", "none");
					$(".popup_container").css("display", "block");
					$(".confirm_name").css("display", "block");
					$(".confirm_name_form p").text("This app name already exist.");
					return x;
				}
				else {
					$("#screenoverlay").css("display", "none");
					$(".popup_container").css("display", "block");
					$(".confirm_name").css("display", "block");
					$(".confirm_name_form p").text("Oops something went wrong.try again later");
					return x;
				}

			},
			cache: false,
			contentType: false,
			processData: false
		});
	}
	else
	{
		$.ajax({
			type: "POST",
			url: "login.php",
			async: false,
			data: "check=login",
			success: function (response) {
				if (response)
				{
					res              = response.split("##");
					external_app_id  = res[1];
					external_user_id = res[0]; 
					var out          = res[2];
					//out = mail_confirm("mail_confirm");
					if (out == 0)
					{
						if(res[0] > 0)
						{
							$("#author").val(res[0]);
							var formData = new FormData($("#catalogue_app_form_new")[0]);
							$.ajax({
								type: "POST",
								url: "ajax.php",
								async: false,
								data: formData,
								success: function (response) {
									if (response > 0) {
										x = 1;
										$("#appid").val(response);
										return x;
									}
									else if (response == 'app_exit') {
										$("#screenoverlay").css("display", "none");
										$(".popup_container").css("display", "block");
										$(".confirm_name").css("display", "block");
										$(".confirm_name_form p").text("This app name already exist.");
										return x;
									}
									else {
										$("#screenoverlay").css("display", "none");
										$(".popup_container").css("display", "block");
										$(".confirm_name").css("display", "block");
										$(".confirm_name_form p").text("Oops something went wrong.try again later");
										return x;
									}

								},
								cache: false,
								contentType: false,
								processData: false
							});
						}
						else
						{
							$(".cropcancel").trigger("click");
						}
					}
					else
					{
						$(".popup_container").css("display", "block");
						$(".confirm_name").css("display", "block");
						$(".confirm_name_form p").text("Please complete your profile to proceed.");
						window.location = 'userprofile.php';
						return x;
					}
				}
				else {
					$("#page_ajax").html('').hide();
					$("#login_type").val("");
					$("#login_type").val("product");
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
					return x;

				}
			},
		});
	}
	
    return x;

}


function send_to_catalogue() {
    app_id = $("#appid").val();
    app_name = $("#app_Name").val();
    var curr_id = $("#curr_id").val();
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: "type=catlog_app_login_check",
        success: function (response) {
            if (response == 'fails') {
                $("#screenoverlay").css("display", "none");
                $(".popup_container").css("display", "block");
                $(".confirm_name").css("display", "block");
                $(".confirm_name_form p").text("App created successfully");
                window.location = "seller-profile.php?app_id=" + app_id + "&app_name=" + app_name + "&curr_id=" + curr_id;

            }
            else {
                var res = response.split("##");
                password = res[1];
                email = res[0];
                $("#screenoverlay").css("display", "none");
                if ($("#action").val() == 'add') {
                    $(".popup_container").css("display", "block");
                    $(".confirm_name").css("display", "block");
                    $(".confirm_name_form p").text("App created successfully");
                }
                if ($("#action").val() == 'edit') {
                    $(".popup_container").css("display", "block");
                    $(".confirm_name").css("display", "block");
                    $(".confirm_name_form p").text("App updated successfully");
                }

                window.location = catalogueUrl + "catalogue/admin/index.php?route=common/login&email=" + email + "&password=" + password + "&app_id=" + app_id + "&app_name=" + app_name + "&curr_id=" + curr_id;
            }
        },
    });


}
function form_validation() {
    var y = 0;
    $("#screenoverlay").css("display", "block");
    app_name = $("#app_Name").val();
    app_type = $("#app_type").val();
    banner1 = $("#banner_img_1").attr('class');
    banner2 = $("#banner_img_2").attr('class');
    banner3 = $("#banner_img_3").attr('class');
    banner_check1 = $("#banner_img_1").val();
    banner_check2 = $("#banner_img_2").val();
    banner_check3 = $("#banner_img_3").val();
    var curr_id = $("#curr_id").val();

    if (app_name == '' || app_name == null) {
        $("#screenoverlay").css("display", "none");
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form p").text("Please choose app name ");
        $("#app_Name").focus();
        return false;
    }
    if (app_type == '' || app_type == null) {
        $("#screenoverlay").css("display", "none");
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form p").text("Please choose catalogue app type");
        $("#app_type").focus();
        return false;
    }
    if (curr_id == '' || curr_id == null) {
        $("#screenoverlay").css("display", "none");
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form p").text("Please select currency");
        $("#curr_id").focus();
        return false;
    }
    if (banner1 == '' || banner1 == null) {
        $("#screenoverlay").css("display", "none");
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form p").text("Please add all banners");
        $("#banner_img_1").focus();
        return false;
    }
    else if (banner_check1 != '') {
        switch (banner_check1.substring(banner_check1.lastIndexOf('.') + 1).toLowerCase()) {
            case 'jpg':
            case 'jpeg':
            case 'png':
                //alert("an image");										
                break;
            default:
                $("#banner_img_1").val('')
                $("#screenoverlay").css("display", "none");
                $(".popup_container").css("display", "block");
                $(".confirm_name").css("display", "block");
                $(".confirm_name_form p").text("Check image extension")
                return false;
                break;
        }
    }
    if (banner2 == '' || banner2 == null) {
        $("#screenoverlay").css("display", "none");
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form p").text("Please add all banners");
        $("#banner_img_2").focus();
        return false;
    }
    else if (banner_check2 != '') {
        switch (banner_check2.substring(banner_check2.lastIndexOf('.') + 1).toLowerCase()) {
            case 'jpg':
            case 'jpeg':
            case 'png':
                //alert("an image");										
                break;
            default:
                $("#banner_img_2").val('')
                $("#screenoverlay").css("display", "none");
                $(".popup_container").css("display", "block");
                $(".confirm_name").css("display", "block");
                $(".confirm_name_form p").text("Check image extension");
                return false;
                break;
        }
    }
    if (banner3 == '' || banner3 == null) {
        $("#screenoverlay").css("display", "none");
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form p").text("Please add all banners");
        $("#banner_img_3").focus();
        return false;
    }
    else if (banner_check3 != '') {
        switch (banner_check3.substring(banner_check3.lastIndexOf('.') + 1).toLowerCase()) {
            case 'jpg':
            case 'jpeg':
            case 'png':
                //alert("an image");										
                break;
            default:
                $("#banner_img_3").val('')
                $("#screenoverlay").css("display", "none");
                $(".popup_container").css("display", "block");
                $(".confirm_name").css("display", "block");
                $(".confirm_name_form p").text("Check image extension");
                return false;
                break;
        }
    }
    y = 1;
    return y;

}
function save_form_validation() {
    var y = 0;
    $("#screenoverlay").css("display", "block");
    app_name = $("#app_Name").val();
    app_type = $("#app_type").val();
    curr_id = $("#curr_id").val();

    if (app_name == '' || app_name == null) {
        $("#screenoverlay").css("display", "none");
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form p").text("Please choose app name ");
        $("#app_Name").focus();
        return false;
    }
    if (app_type == '' || app_type == null) {
        $("#screenoverlay").css("display", "none");
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form p").text("Please choose catalogue app type");
        $("#app_type").focus();
        return false;
    }
    if (curr_id == '' || curr_id == null) {
        $("#screenoverlay").css("display", "none");
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form p").text("Please select currency");
        $("#curr_id").focus();
        return false;
    }

    /*
     * code edit by Arun Srivastava on 29/9/15		
     */
    var feedbackcheck = $('#feedback').is(":checked");
    var feedbackformemail = $('#feedbackformemail').val();
    var feedbackphone = $('#feedbackphone').val();

    if (feedbackcheck === true)
    {
        if (feedbackformemail == '')
        {
            $("#screenoverlay").css("display", "none");
            $(".popup_container").css("display", "block");
            $(".confirm_name").css("display", "block");
            $(".confirm_name_form p").text("Please add feedback form email id");
            $("#feedbackformemail").focus();
            return false;
        }
    }

    var contactcheck = $('#contact').is(":checked");
    var contactusemail = $('#contactusemail').val();
    var contactussupport = $('#contactussupport').val();

    if (contactcheck === true)
    {
        if (contactusemail == '')
        {
            $("#screenoverlay").css("display", "none");
            $(".popup_container").css("display", "block");
            $(".confirm_name").css("display", "block");
            $(".confirm_name_form p").text("Please add contact us form email id");
            $("#contactusemail").focus();
            return false;
        }
    }

    var tandccheck = $('#tandc').is(":checked");
    var tnclink = $('#tnclink').val();

    if (tandccheck === true)
    {
        if (tnclink == '')
        {
            $("#screenoverlay").css("display", "none");
            $(".popup_container").css("display", "block");
            $(".confirm_name").css("display", "block");
            $(".confirm_name_form p").text("Please add terms and conditions link");
            $("#tnclink").focus();
            return false;
        }
    }
    y = 1;
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
            }
            else {
                x = 0;

            }
        },
    });
    return x;
}
