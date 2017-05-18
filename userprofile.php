<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/user/userprofile.php');
if (isset($_SESSION['token'])) {
    unset($_SESSION['token']);
}
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$userprofile = new UserProfile();
$user = $userprofile->getUserByCustId($_SESSION['custid']);
if ($user == '') {
    ?>
    <script>
        $(".popup_container").css("display", "block");
        $(".confirm_name").css("display", "block");
        $(".confirm_name_form").html("<p>Please login to continue.</p><input type='button' value='OK'>");
    </script>
    <?php
    exit();
} else {

    $_SESSION['username'] = $user['email_address'];
}
/* echo "<pre>"; print_r($user); echo "</pre>"; */
?>

<section class="main">
    <div class="popup_container userprofile">
        <div class="letuscall_popup">
            <span class="close_popup"><img src="images/popup_close.png"></span>
            <div class="letuscall_popup_head">
                <h1>Please enter your mobile no.</h1>
            </div>
            <div class="letuscall_popup_body">
                <div class="letuscall_form">
                    <input type="tel" maxlength="6" minlength="2" id="mobileNocc" name='mobileNocc' class="required" value="<?php if($user['mobile_country_code']!=''){echo '+'.$user['mobile_country_code'];}else{echo '+91';} ?>">
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
    <section class="right_main">
        <div class="right_inner">
            <h1 class="account_id">Customer ID : <span><?php echo $user['custid']; ?></span></h1>
            <a class="myordersbutton"href="myorders.php">My Orders</a>

            <div class="clear"></div>
            <div class="edit_account_details">   
                <p>Your Customer ID is unique. All of your apps are directly linked to your Customer ID. You can edit your account information below</p>
                <div class="edit_email_pass">
                    <div class="edit_email_pass_left">
                        <h2>Email (Login) : <span><?php
                                if (isset($_SESSION['username'])) {
                                    echo $_SESSION['username'];
                                }
                                ?></span></h2>

                        <?php
//                        if ($user['fb_token'] == '') {
                        ?>
                        <a href="javascript:void(0);" class="edit-password">Change Password</a>
                        <?php
//                        }
                        ?>
                    </div>

                    <div class="clear"></div>
                </div>

                <form action='' method='post' name='user_profile' id='user_profile' enctype="multipart/form-data">
                    <div class="edit_account_form">
                        <div class="edit_account_form_left" style="height: 140px;">
                            <?php if ($user['avatar'] != '') { ?>
                                <img src="avatars/<?php echo $user['avatar']; ?>" alt="" onerror="this.src='avatars/user.png'"  id='preview_avatar'>
                            <?php } else { ?>
                                <img src="avatars/user.png" alt="" id='preview_avatar'>
                            <?php } ?>
                            <input type='file' name='avatar' id='avatar' style='display:none;'>
                            <div id="avatar_message"></div>
                        </div>
                        <div class="edit_account_form_right">
                            <div class="edit_inputs">
                                <div class="edit_inputs_left">
                                    <label>First name * :</label>
                                    <input type="text" class="required" maxlength="50"  onblur="isAlphanumericOnly(event, this);" name='fname' value='<?php echo $user['first_name']; ?>'>
                                </div>
                                <div class="edit_inputs_right">
                                    <label>Last name :</label>
                                    <input type="text" maxlength="50" name='lname'  onblur="isAlphanumericOnly(event, this);" value='<?php echo $user['last_name']; ?>'>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="edit_inputs">
                                <div class="edit_inputs_left">
                                    <label>Email * : <small style=" font-size:inherit ; font-family:inherit; color:inherit">(
                                            <?php
                                            if ($user['is_confirm'] == 1 && ($user['email_address'] != '' || $user['email_address'] != null)) {
                                                echo "";
                                                $resend = '';
                                            } else {
                                                if ($user['is_confirm'] == 0 && ($user['email_address'] != '' || $user['email_address'] != null)) {
                                                    $resend = '<a href="javascript:void(0);" onclick="resend_link(' . $user['custid'] . ');">Resend Verification Link</a>';
                                                } else {
                                                    $resend = '';
                                                }
                                                echo "Not";
                                            }
                                            ?> Verified)</small></label>
                                    <input type="text" class="required email" maxlength="100" name='email' id="email_address" value="<?php echo $user['email_address']; ?>">
                                    <?php echo $resend; ?>
                                </div>								 
                                <div class="edit_inputs_right">
                                    <label>Company :</label>
                                    <input type="text" maxlength="100" value="<?php echo $user['organisation_name']; ?>" name='company'>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="edit_inputs">
                                <div class="edit_inputs_left">
                                    <label>Alternative email :</label>
                                    <input type="text" class="email" class="email" maxlength="100" name='alternet_email' value="<?php echo $user['alternate_email']; ?>">
                                </div>                                
                                <div class="edit_inputs_right">
                                    <label>Country :</label>
                                    <select id="country_id" name='country'>
                                        <?php $userprofile->get_countries($user['custid']); ?>
                                    </select>

                                </div>
                                <div class="clear"></div>
                            </div>
                            <input type="hidden" id="custid" name="custid" value="<?php echo $user['custid']; ?>">
                            <input type="hidden" id="custid" name="verification_code" value="<?php echo $user['verification_code']; ?>">
                            <input type="hidden" id="custid" name="url" value="<?php echo $basicUrl; ?>">
                            <div class="edit_inputs">
                                <div class="edit_inputs_left">
                                    <label>Mobile * : <small style=" font-size:inherit ; font-family:inherit; color:inherit">(<?php
                                            if ($user['mobile_validated'] == 1)
                                                echo "";
                                            else
                                                echo "Not";
                                            ?> Verified)</small><small><br/>Click to edit number and send verification code</small></label>
                                    <input type="tel" maxlength="6" minlength="2" id="mobilecc" name='mobilecc' class="required" value="<?php if($user['mobile_country_code']!=''){echo '+'.$user['mobile_country_code'];}else{echo '+91';} ?>" readonly>
                                    <input type="tel" maxlength="16" minlength="5" id="mobile" name='mobile' class="required" value="<?php echo $user['mobile']; ?>"  class='formMobile' readonly="readonly">
                                    <?php
                                    $validate = $user['mobile_validated'];
                                    if ($validate == 0) {
                                        echo '<a id="addmobileno">Validate Your Mobile No.</a>';
                                    } else {
                                        echo '<a id="addmobileno">Change Mobile No.</a>';
                                    }
                                    ?>
                                </div>

                                <div class="edit_inputs_right">
                                    <div class="zip">
                                        <label>ZIP/Post code :</label>
                                        <input type="text" maxlength="6" minlength='6' name='zip' value='<?php echo $user['pincode']; ?>'>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="city">
                                        <label>City :</label>
                                        <input type="text" maxlength="100" name='city' value='<?php echo $user['city']; ?>'>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="edit_inputs">
                                <div class="edit_inputs_left">
                                    <label>Address :</label>
                                    <textarea maxlength="200" name='address'><?php echo $user['street']; ?></textarea>
                                </div>

                                <div class="edit_inputs_right">
                                    <label>State :</label>
                                    <?php
                                    $countrynotfound=0;
                                    $datac = array();
                                    if (!empty($user['country'])) {
                                        $datac['conuntry_id'] = $user['country'];
                                        $countrynotfound=1;
                                    }
                                   
                                    ?>
                                    <select name='state' id="zone_id">
                                        <option value="">Select State</option>
                                        <?php
                                        if($countrynotfound==1){
                                        $sates = $userprofile->get_states($datac,$user['custid']);
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="edit_inputs">
                                <div class="edit_inputs_left">
                                    <label>Landline number :</label>
                                    <input type="text" maxlength="15" minlength="10" name='phone' value='<?php echo $user['phone_no']; ?>' onKeyPress="return isNumber(event)">
                                </div>

                                <div class="edit_inputs_right">
                                    <label>Website :</label>
                                    <input type="text" class="url" maxlength="100" name='website' placeholder="http://" value="<?php echo $user['website'] != '' ? $user['website'] : ''; ?>">
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="edit_inputs">
                                <div class="edit_inputs_left">
                                    <label>Fax :</label>
                                    <input type="text" maxlength="15" minlength="10" name='fax' onKeyPress="return isNumber(event)" value="<?php echo $user['fax']; ?>">
                                </div>


                                <div class="clear"></div>
                            </div>
                            <input type='hidden' name='username' value='<?php echo $_SESSION['username']; ?>'>

                            <div id="ms" style="clear: both;float: left;margin-right: 20px;margin-top: 10px; width: 100%;top: -5px; position:relative">
                                <input class="required" type="checkbox" name="agree" style="display: inline-block;vertical-align: middle;margin-top: 1px;margin-right: 1px;" checked><span style="
                                                                                                                                                                                           font-size: 13px;color:#829198;margin-top: 5px; margin-left: 4px;">By signing in or signing up you agree to our <a href="terms-conditions.php" style='font-size: 13px;color:#ffcc00; text-decoration:underline'>Terms and Conditions.</a></span>
                            </div>
                            <input type="submit" value="Save" class="save_account_details">
                            <div id='user_profile_result'></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </form>

            </div>

        </div>
    </section>
</section>
</section>
</body>

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<script type="text/javascript" src="js/validate.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>


<script type="text/javascript">    
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
                                                        $(".popup_container.userprofile").css({'display': 'block'});
                                                    }
                                                    else if (response == 2) {
                                                        $('.error1').html('Some error in submit');
                                                        console.log(response);
                                                    }
                                                    else if (response == 3) {
                                                        $(".letuscall_popup").css({'display': 'none'});
                                                        $(".codesent_popup").css({'display': 'none'});
                                                        $(".code_not_recieved_popup").css({'display': 'none'});
                                                        $(".popup_container.userprofile").css({'display': 'none'});
                                                        window.location = window.location;
                                                    }
                                                    else if (response == 4) {
                                                        $(".letuscall_popup").css({'display': 'none'});
                                                        $(".codesent_popup").css({'display': 'none'});
                                                        $(".code_not_recieved_popup").css({'display': 'block'});
                                                        $(".popup_container.userprofile").css({'display': 'block'});
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
                                                        $(".popup_container.userprofile").css({'display': 'block'});
                                                        $('.error1').html('Please enter your mobile no.');
                                                        console.log(response);
                                                    } else if (response == 7) {
                                                        $(".letuscall_popup").css({'display': 'none'});
                                                        $(".codesent_popup").css({'display': 'none'});
                                                        $(".code_not_recieved_popup").css({'display': 'block'});
                                                        $(".popup_container.userprofile").css({'display': 'block'});
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
                                        $(document).ready(function () {
                                            $(".leftsidemenu li").removeClass("active");
                                            var timerCheck;
                                            var resendotpCode = 0;
                                            $(".close_popup").click(function () {

                                                $('.popupTimer').text("05:00");
                                                clearInterval(timerCheck);

                                                $(this).parent().css({'display': 'none'});
                                            });
                                            $("#resendotpCode").click(function () {
                                                resendotpCode = 1;
                                                otpcheck("", resendotpCode);
                                            });
                                            $("#verifyotp").click(function () {
                                                $('.error1').html('');
                                                $(".popup_container.userprofile").css({'display': 'block'});
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
                                                    $(".popup_container.userprofile").css({'display': 'block'});
                                                    $('.error1').html('Please enter your OTP');
                                                }
                                            });
                                            $("#verifyotp2").click(function () {
                                                $('.error1').html('');
                                                resendotpCode = 0;
                                                $(".popup_container.userprofile").css({'display': 'block'});
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
                                                    $(".popup_container.userprofile").css({'display': 'block'});
                                                    $('.error1').html('Please enter your OTP');
                                                }
                                            });

                                            $('.error1').html('');
                                            $("#addmobileno").click(function () {
                                                $('.error1').html('');
                                                $(".popup_container.userprofile").css({'display': 'block'});
                                                var mobno = $("#mobile").val();
                                                var mobnocc = $("#mobilecc").val();
                                                $("#mobileNo").val(mobno);
                                                $("#mobileNocc").val(mobnocc);
                                                $("#mobileNocc").trigger("keyup");
                                                $(".letuscall_popup").css({'display': 'block'});
                                                $("#sendVerificationCode").bind("click", sendVerificationCode);
                                                
                                            });



                                            function sendVerificationCode() {
                                                var mobpopup = $("#mobileNo").val();
                                                var mobileNocc = $("#mobileNocc").val();
                                                var last2digit = mobpopup.substr(mobpopup.length - 2);
                                                $(".lastno").html(last2digit);
                                                $('.error1').html('');
                                                $(".popup_container.userprofile").css({'display': 'block'});


                                                jQuery.ajax({
                                                    url: BASEURL + 'API/user.php/userregister',
                                                    type: "post",
                                                    data: $("form#user_profile").serialize(),
                                                    success: function (response) {

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
                                                                $(".popup_container.userprofile").css({'display': 'none'});
                                                            }
                                                        }, 1000);


                                                        if (mobpopup != '') {
                                                            if (mobpopup.length <= 16) {

                                                                var param = {'mobileno': mobpopup,'mobilecountrycode': mobileNocc};
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
                                                                            $(".popup_container.userprofile").css({'display': 'block'});
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
                                                                $(".popup_container.userprofile").css({'display': 'block'});
                                                                $(".letuscall_popup").css({'display': 'block'});
                                                                $(".codesent_popup").css({'display': 'none'});
                                                                $(".code_not_recieved_popup").css({'display': 'none'});
                                                                $('.error1').html('Mobile no. should not be less than 10 digit');
                                                            }
                                                        } else {
                                                            $(".letuscall_popup").css({'display': 'block'});
                                                            $(".codesent_popup").css({'display': 'none'});
                                                            $(".code_not_recieved_popup").css({'display': 'none'});
                                                            $(".popup_container.userprofile").css({'display': 'block'});
                                                            $('.error1').html('Please enter your mobile no.');
                                                        }
                                                    }
                                                });


                                                // populateCountries("country", "state", '<?php echo $user['country']; ?>', '<?php echo $user['state']; ?>');

                                                $("#sendVerificationCode").unbind('click');
                                            }
                                            
                                            $("#country_id").change("on",function () {
                                                var country_id = $(this).val();
                                               
                                                jQuery.ajax({
                                                    url: 'ajax.php',
                                                    type: "post",
                                                    data: "conuntry_id=" + country_id +"&custid=<?php echo $user['custid']; ?>&type=country_state",
                                                    success: function (response) {
                                                        if (response) {
                                                            
                                                            $("#zone_id").html(response);
                                                        }

                                                    },
                                                    error: function () {
                                                        $("#user_profile_result").html('There is error while submit.');

                                                    }
                                                });

                                            });


                                            $('.edit-password').click(function () {
                                                $('.popup_container').show();
                                                $(".popup_container.userprofile").hide();
                                                $('.reset_pass_popup').show();
                                            });
                                            $(".forgot_old_password").click(function () {
                                                $('.reset_pass_popup').hide();
                                                $('.forgot_popup').show();

                                            });
                                            $('#reset_password').click(function () {
                                                var old_pass = $('#old_pass').val();
                                                var new_pass = $('#new_pass').val();
                                                var re_pass = $('#re_pass').val();
                                                if (old_pass == '')
                                                {
                                                    $("#old_pass").css("border", "1px solid #ff0000");
                                                    return false;
                                                }
                                                if (new_pass == '')
                                                {
                                                    $("#new_pass").css("border", "1px solid #ff0000");
                                                    return false;
                                                }
                                                if (re_pass == '')
                                                {
                                                    $("#re_pass").css("border", "1px solid #ff0000");
                                                    return false;
                                                }
                                                if (new_pass != re_pass)
                                                {
                                                    $("#new_pass").css("border", "1px solid #ff0000");
                                                    $("#re_pass").css("border", "1px solid #ff0000");
                                                    return false;
                                                }
                                                $("#screenoverlay").css("display", "block");
                                                jQuery.ajax({
                                                    url: BASEURL + 'API/user.php/resetpass',
                                                    type: "post",
                                                    data: {
                                                        old_pass: old_pass,
                                                        new_pass: new_pass,
                                                        username: '<?php echo $_SESSION['username'] ?>',
                                                        catalogueUrl: catalogueUrl
                                                    },
                                                    success: function (response) {
                                                        //alert("success");
                                                        if (response == 'updated Successfully!') {
                                                            $("#screenoverlay").css("display", "none");
                                                            $(".popup_container").css("display", "block");
                                                            $(".popup_container.userprofile").hide();
                                                            $(".confirm_name").css("display", "block");
                                                            $(".reset_pass_popup").css("display", "none");
                                                            $(".userprofile_popup").css("display", "none");
                                                            $(".userprofile").css("display", "none");
                                                            $(".confirm_name_form p").text("Saved successfully!");
                                                        }
                                                        else {
                                                            $("#screenoverlay").css("display", "none");
                                                            $(".popup_container").css("display", "block");
                                                            $(".popup_container.userprofile").hide();
                                                            $(".confirm_name").css("display", "block");
                                                            $(".reset_pass_popup").css("display", "none");
                                                            $(".userprofile_popup").css("display", "none");
                                                            $(".userprofile").css("display", "none");
                                                            $(".confirm_name_form p").text("Error in Saving. Please try again.");
                                                            //$("#reset_message").html('Old Password is incorrect!');
                                                        }
                                                        //console.log(response);
                                                    },
                                                    error: function () {
                                                        $("#screenoverlay").css("display", "none");
                                                        $(".popup_container").css({'display': 'none'});
                                                        $(".popup_container.userprofile").hide();
                                                        $(".confirm_name").css("display", "block");
                                                        $(".reset_pass_popup").css("display", "none");
                                                        $(".userprofile_popup").css("display", "none");
                                                        $(".userprofile").css("display", "none");
                                                        $(".confirm_name_form p").text("Error in Saving.Please try again.");
                                                        //$("#reset_message").html('There is error while submit.');
                                                    }
                                                });
                                            });


                                            /*var rightHeight = $(window).height() - 45;
                                             $(".right_main").css("height", rightHeight + "px");*/
                                            $("#user_profile").validate({
                                                submitHandler: function (form) {
                                                    var confirm = '<?php echo $user['is_confirm']; ?>';
                                                    var orginal_email = '<?php echo $user['email_address']; ?>';
                                                    var new_email = $("#email_address").val();
                                                    $("#screenoverlay").css("display", "block");
                                                    jQuery.ajax({
                                                        url: BASEURL + 'API/user.php/userregister',
                                                        type: "post",
                                                        data: $(form).serialize(),
                                                        success: function (response) {
                                                            $("#screenoverlay").css("display", "none");
                                                            $(".popup_container").css("display", "block");
                                                            $(".confirm_name").css("display", "block");
                                                            $(".reset_pass_popup").css("display", "none");
                                                            $(".userprofile_popup").css("display", "none");
                                                            $(".userprofile").css("display", "none");
                                                            if (response == 'Email Address Already Exist!') {
                                                                response = 'Email Address Already Exist!';
                                                            }
                                                            else if (confirm == 0) {
                                                                response = 'Save successfully.Please Verify Your Email To SignIn';
                                                                setTimeout(function () {
                                                                    window.location = 'userprofile.php'
                                                                }, 4000)
                                                            }
                                                            else if (orginal_email != new_email) {
                                                                response = 'Save successfully.Please Verify Your Email To SignIn';
                                                                setTimeout(function () {
                                                                    window.location = 'userprofile.php'
                                                                }, 4000)
                                                            }

                                                            $(".confirm_name_form p").text(response);
                                                            setTimeout(function () {
                                                                window.location = 'applisting.php'
                                                            }, 2000)

                                                        },
                                                        error: function () {
                                                            $("#screenoverlay").css("display", "none");
                                                            $(".popup_container").css("display", "block");
                                                            $(".confirm_name").css("display", "block");
                                                            $(".reset_pass_popup").css("display", "none");
                                                            $(".userprofile_popup").css("display", "none");
                                                            $(".userprofile").css("display", "none");
                                                            $(".confirm_name_form p").text("Error in Saving. Please try again.");

                                                        }
                                                    });
                                                }
                                            });
                                            // populateCountries("country", "state", '<?php echo $user['country']; ?>', '<?php echo $user['state']; ?>');

                                            $('#preview_avatar').click(function () {
                                                $('#avatar').click();
                                            });

                                            /* User Profile upload */
                                            var options = {
                                                target: '#avatar_message', // target element(s) to be updated with server response 
                                                url: BASEURL + 'API/user.php/useravatar',
                                                beforeSubmit: beforeSubmit, // pre-submit callback 
                                                success: afterSuccess, // post-submit callback 
                                                resetForm: true        // reset the form after successful submit 
                                            };
                                            /* User Profile Upload Ends */
                                            $("#avatar").change(function () {
												$("#screenoverlay").css("display","block");												
                                                $("#avatar_message").empty(); // To remove the previous error message
                                                var file = this.files[0];
                                                var imagefile = file.type;
                                                var match = ["image/jpeg", "image/png", "image/jpg"];
                                                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2])))
                                                {
                                                    //$('#previewing').attr('src','noimage.png');
                                                    $("#avatar_message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                                                    return false;
                                                }
                                                else
                                                {
                                                    var reader = new FileReader();
                                                    reader.onload = imageIsLoaded;
                                                    reader.readAsDataURL(this.files[0]);
                                                    $('#user_profile').ajaxSubmit(options);

                                                }
                                            });
                                            function afterSuccess()
                                            {
                                                //$('#submit-btn').show(); //hide submit button
                                                //$('#loading-img').hide(); //hide submit button
												$("#screenoverlay").css("display","none");

                                            }
                                            //function to check file size before uploading.
                                            function beforeSubmit() {
                                                //check whether browser fully supports all File API
                                                if (window.File && window.FileReader && window.FileList && window.Blob)
                                                {

                                                    if (!$('#avatar').val()) //check empty input filed
                                                    {
                                                        $("#avatar_message").html("Are you kidding me?");
                                                        return false
                                                    }

                                                    var fsize = $('#avatar')[0].files[0].size; //get file size
                                                    var ftype = $('#avatar')[0].files[0].type; // get file type


                                                    //allow only valid image file types 
                                                    switch (ftype)
                                                    {
                                                        case 'image/png':
                                                        case 'image/gif':
                                                        case 'image/jpeg':
                                                        case 'image/pjpeg':
                                                            break;
                                                        default:
                                                            $("#avatar_message").html("<b>" + ftype + "</b> Unsupported file type!");
                                                            return false
                                                    }

                                                    //Allowed file size is less than 1 MB (1048576)
                                                    if (fsize > 1048576)
                                                    {
                                                        $("#avatar_message").html("<b>" + bytesToSize(fsize) + "</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
                                                        return false
                                                    }

                                                    //$('#submit-btn').hide(); //hide submit button
                                                    //$('#loading-img').show(); //hide submit button
                                                    $("#avatar_message").html("");
                                                }
                                                else
                                                {
                                                    //Output error to older browsers that do not support HTML5 File API
                                                    $("#avatar_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
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
                                                $('#preview_avatar').attr('src', e.target.result);
                                                $('#preview_avatar').attr('width', '250px');
                                                $('#preview_avatar').attr('height', '230px');
                                            }
                                            ;
                                        });
                                        function isNumber(evt) {
                                            evt = (evt) ? evt : window.event;
                                            var charCode = (evt.which) ? evt.which : evt.keyCode;
                                            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                                                return false;
                                            }
                                            return true;
                                        }
                                        function isAlphabet(evt) {
                                            evt = (evt) ? evt : window.event;
                                            var charCode = (evt.which) ? evt.which : evt.keyCode;
                                            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8)) {
                                                return true;
                                            }
                                            return false;

                                        }
                                        $(document).ready(function () {
                                            $('.confirm_name_form input[type="button"]').click(function (event) {
                                                event.preventDefault();

                                                $(".popup_container").css("display", "none");
                                                $(".confirm_name").css("display", "none");

                                            });

                                        });

                                        /* Edited By Varun */
                                        function isAlphanumeric(evt, input) {
                                            evt = (evt) ? evt : window.event;
                                            var charCode = (evt.which) ? evt.which : evt.keyCode;
                                            if ((charCode >= 48 && charCode <= 57) || (charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8) || (charCode == 32)) {
                                                return true;
                                            }
                                            return false;

                                        }
                                        /* Edited By Varun */
                                        function isAlphanumericOnly(evt, input) {
                                            var value = $(input).val();
                                            if (!isNaN(value))
                                            {
                                                $(input).focus();
                                                $(input).removeClass('valid');
                                                $(input).addClass('error');
                                                if (!$(input).next().find('.error')) {
                                                    $('<label generated="true" class="error">Please input Alphabetic or Alphanumeric Value.</label>').insertAfter($(input));
                                                }
                                                return false;
                                            }
                                            else {
                                                $(input).removeClass('error');
                                                $(input).addClass('valid');
                                                $(input).next('.error').remove();
                                            }
                                        }


                                        function resend_link(custid) {
                                            var email = $("#email_address").val();
                                            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                            if (email == '' || email == null) {
                                                $("#email_address").css("border", "1px solid #ff0000");
                                                $("#email_address").focus();
                                                return false;
                                            }
                                            else if (!regex.test(email)) {
                                                $("#email_address").css("border", "1px solid #ff0000");
                                                $("#email_address").focus();
                                                return false;
                                            }
                                            else {
                                                $("#email_address").css("border", "1px solid #e5e5e5");
                                            }

                                            $("#screenoverlay").css("display", "block");
                                            jQuery.ajax({
                                                url: BASEURL + 'API/user.php/resend_code',
                                                type: "post",
                                                data: 'custid=' + custid + '&url=' + BASEURL + '&name=' + '<?php echo $user['first_name']; ?>&email=' + email,
                                                success: function (response) {
                                                    if (response == 'success') {
                                                        $("#screenoverlay").css("display", "none");
                                                        $(".popup_container").css("display", "block");
                                                        $(".confirm_name").css("display", "block");
                                                        $(".reset_pass_popup").css("display", "none");
                                                        $(".userprofile_popup").css("display", "none");
                                                        $(".userprofile").css("display", "none");
                                                        $(".confirm_name_form p").text('A verification link has been sent to your email. Please verify your email.');
                                                    }
                                                    // else if(response=='exist'){
                                                    // $("#screenoverlay").css("display", "none");
                                                    // $(".popup_container").css("display", "block");
                                                    // $(".confirm_name").css("display", "block");
                                                    // $(".reset_pass_popup").css("display", "none");
                                                    // $(".userprofile_popup").css("display", "none");
                                                    // $(".userprofile").css("display", "none");
                                                    // $("#email_address").val('');
                                                    // $(".confirm_name_form p").text('This email Id already registered.');														
                                                    // }
                                                    else {
                                                        $("#screenoverlay").css("display", "none");
                                                        $(".popup_container").css("display", "block");
                                                        $(".confirm_name").css("display", "block");
                                                        $(".reset_pass_popup").css("display", "none");
                                                        $(".userprofile_popup").css("display", "none");
                                                        $(".userprofile").css("display", "none");
                                                        $(".confirm_name_form p").text("Oops something went wrong try again later.");
                                                    }

                                                },
                                                error: function () {
                                                    $("#screenoverlay").css("display", "none");
                                                    $(".popup_container").css("display", "block");
                                                    $(".confirm_name").css("display", "block");
                                                    $(".reset_pass_popup").css("display", "none");
                                                    $(".userprofile_popup").css("display", "none");
                                                    $(".userprofile").css("display", "none");
                                                    $(".confirm_name_form p").text("Error in Saving. Please try again.");

                                                }
                                            });

                                        }
</script>
<style type="text/css">
    input[readonly]{ background-color:#eee;}
    #ms label{position: absolute;
              top: 31px;
              font-size: 12px;
              left: 0px;
    }
</style>

<script type="text/javascript" src="js/intlTelInput.js"></script>
 <script>
      $("#mobilecc").intlTelInput({
       defaultCountry: "auto",
nationalMode: true,
preventInvalidNumbers: true,
       utilsScript: "js/utils.js"
   });
   $("#mobilecc").keydown(function(event) { 
    return false;
});

      $("#mobileNocc").intlTelInput({
        defaultCountry: "auto",
nationalMode: true,
preventInvalidNumbers: true,
       utilsScript: "js/utils.js"
   });
   $("#mobileNocc").keydown(function(event) { 
    return false;
});

    </script>
</html>