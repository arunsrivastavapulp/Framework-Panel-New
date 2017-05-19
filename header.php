<?php
session_start();
require_once 'modules/login/openId.php';
if (!empty($_SESSION['username'])) {
    require_once('modules/login/login-check.php');
    $login = new Login();
    $results = $login->check_user_exit($_SESSION['username']);
    if (!empty($results['avatar'])) {
        $mg = '<img src="avatars/' . $results['avatar'] . '" alt="Image Not Found">';
    } else {
        $mg = '<img src="avatars/user.png" alt="Image Not Found">';
    }
} else {
    $mg = '<img src="avatars/user.png" alt="Image Not Found">';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="css/custom-style.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" type="text/css" href="css/colpick.css">
                <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
                    <link rel="stylesheet" href="css/style_old.css">
                        <link rel="stylesheet" type="text/css" href="css/ImageSelect.css">
                            <link rel="stylesheet" type="text/css" href="css/chosen.css">
							<link rel="stylesheet" type="text/css" href="css/price.css"/> 
                                <link rel="stylesheet" href="css/intlTelInput.css">
                                    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
                                        <script src="js/jquery.min.js"></script>
                                        <script src="js/login.js"></script>
                                        <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
								<meta name="google-signin-client_id" content="174561932350-at2c0h9qdr81thch52hqurl7l437fl5m.apps.googleusercontent.com">


                                            <title>Instappy</title>
                                            </head>

                                            <body>
                                                <div class="popup_container">
                                                    <div id="page_ajax"></div>
                                                    <div class="confirm_name">
                                                        <span class="close_popup"><img src="images/popup_close.png"></span>
                                                        <div class="confirm_name_body">
                                                            <div class="confirm_name_form">
                                                                <p>This app name already exist. Are you sure to want to continue?</p>
                                                                <input type="button" value="Yes">
                                                                    <input type="button" value="No">
                                                                        </div>
                                                                        </div>
                                                                        </div>
                                                    
                                                    <div class="delete_app">
                                                        <span class="close_popup"><img src="images/popup_close.png"></span>
                                                        <div class="delete_app_body">
                                                            <div class="delete_app_form">
                                                                <p>Are you sure, you want to delete this app?</p>
                                                                <input type="button" value="Yes">
                                                                    <input type="button" value="No">
                                                                        </div>
                                                                        </div>
                                                                        </div>
                                                                                                  
                                                                        <div class="login_popup">
                                                                            <span class="close_popup"><img src="images/popup_close.png"></span>
                                                                            <div class="login_popup_head">
                                                                                <h1>Login</h1>
                                                                                <h1>Sign Up</h1>
                                                                                <ul class="tabs">
                                                                                    <li><a href="javascript:void(0)">Login</a></li>
                                                                                    <li><a href="javascript:void(0)">Sign Up</a></li>
                                                                                    <div class="clear"></div>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="login_popup_body">
                                                                                <div class="login_tabbing">
                                                                                    <div class="login_form">
                                                                                        <form id="login_form">
                                                                                            <input type="text" name="email" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : '' ?>" id="login_email" placeholder="Enter email id" />
                                                                                            <p id="email_error"></p>	
                                                                                            <input type="hidden" id="login_type" value="">					
                                                                                                <input type="hidden" id="author_id" value="">					
                                                                                                    <input type="hidden" id="app_id" value="">					
                                                                                                        <input type="password"  id="login_password" name="password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : '' ?>" placeholder="Enter password"/>						
                                                                                                        <div class="keep_login_check">
                                                                                                            <input id="remember" type="checkbox" <?php if (isset($_COOKIE['username'])) echo "checked"; ?> value="yes" name="remember" /> 
                                                                                                            <label for="login">Keep me Logged in</label>
                                                                                                            <div class="clear"></div>
                                                                                                        </div>
                                                                                                        <div id="loading"></div>
                                                                                                        <input type="submit" value="Login" id="login">
                                                                                                            </form>
                                                                                                            <a href="javascript:void(0)" class="forgot_password">Forgot Password</a>
                                                                                                            <div class="clear"></div>
                                                                                                            </div>
                                                                                                            OR
                                                                                                            <div class="social_connect">
                                                                                                                <div class="facebook_login">

                                                                                                                    <div id="fb-root"></div>

                                                                                                                    <!--<fb:login-button  scope="public_profile,email" onlogin="checkLoginState();" autologoutlink="true">
                                                                                                                    </fb:login-button>-->
                                                                                                                    <div class="fb-login-button" data-max-rows="1" data-size="large" scope="public_profile,email" onlogin="checkLoginState();" data-show-faces="false" data-auto-logout-link="false"></div>
                                                                                                                </div>
                                                                                                                <div class="twitter_login">
                                                                                                                    <div id="my-signin2"></div>
                                                                                                                    <!--<div class="g-signin2" data-onsuccess="onSignIn"></div>-->
                                                                                                                </div>
                                                                                                                <div class="clear"></div>
                                                                                                            </div>
                                                                                                            </div>
                                                                                                            <div class="login_tabbing">
                                                                                                                <div class="login_form">
                                                                                                                    <form id="signup_form">
                                                                                                                        <input type="text" id="register_email" placeholder="Enter email id">
                                                                                                                            <p id="remail_error"></p>
                                                                                                                            <input type="password" id="register_password" placeholder="Enter password">
                                                                                                                                <input type="password" id="register_repeat_password" placeholder="Re enter password">
                                                                                                                                    <div id="register_loading"></div>
                                                                                                                                    <input type="submit" value="Sign Up" id="signup">
                                                                                                                                        </form>
                                                                                                                                        <p>By clicking "Sign up" you agree<br>to our <a href="#">Terms &amp; Conditions</a> and  <a href="#">Privacy Policy</a>.</p>
                                                                                                                                        <div class="clear"></div>
                                                                                                                                        </div>
                                                                                                                                        </div>
                                                                                                                                        </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="forgot_popup">
                                                                                                                                            <span class="close_popup"><img src="images/popup_close.png"></span>
                                                                                                                                            <div class="forgot_popup_head">
                                                                                                                                                <h1>Forgot Password</h1>
                                                                                                                                            </div>
                                                                                                                                            <div class="forgot_popup_body">
                                                                                                                                                <input type="text" id="forgot_email" placeholder="Enter registered email id">
                                                                                                                                                    <p id="ferror_email"></p>
                                                                                                                                                    <div id="loading_forgot"></div>
                                                                                                                                                    <p>A mail will be sent to your email id to reset your password.</p>
                                                                                                                                                    <input type="submit" value="Reset" id="reset">
                                                                                                                                                        </div>
                                                                                                                                                        </div>


                                                                                                                                                        <div class="feedback_popup">
                                                                                                                                                            <span class="close_popup"><img src="images/popup_close.png"></span>
                                                                                                                                                            <div class="theme_head restro_theme">
                                                                                                                                                                <a class="nav_back" style="display:block;"> <i class="fa fa-angle-left"></i></a>
                                                                                                                                                                <h2>Feedback</h2>
                                                                                                                                                            </div>
                                                                                                                                                            <div class="feedback_form" data-ss-colspan="2">
                                                                                                                                                                <h2>Any Suggestions/Feedback ?</h2>
                                                                                                                                                                <p>Your feedback/suggestion is valuable to us. We hope it will make FormGet app more awesome.</p>
                                                                                                                                                                <input type="text" placeholder="Name" />
                                                                                                                                                                <input type="text" placeholder="Email id" />
                                                                                                                                                                <textarea placeholder="Message"></textarea>
                                                                                                                                                                <input type="button" value="Send" />
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                        </div>
                                                                                                                                                        <section class="clear framework">
                                                                                                                                                            <header class="top-area"><img src="images/logo.png"></header>
