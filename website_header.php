<?php
session_start();
require_once 'config/db.php';
$db = new DB();
$basicUrl = $db->siteurl();

$page = substr($_SERVER['PHP_SELF'], 1);

if (isset($_COOKIE['action'])) {
    unset($_SESSION['currencyid']);
    unset($_SESSION['country']);
    unset($_SESSION['currency']);

    $db->get_country();
    $checkcountry = $_SESSION['country'];
    $currency = $_SESSION['currencyid'];
    $currencyIcon = $_SESSION['currency'];
} else {
    setcookie('action');
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
}

if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip_address=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip_address=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip_address=$_SERVER['REMOTE_ADDR'];
    }

?>
  <!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml" lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
      <?php echo $db->meta_tags($page, 'meta_title'); ?>
    </title>
    <meta name="description" content="<?php echo $db->meta_tags($page, 'meta_description'); ?>">
    <meta name="keywords" content="<?php echo $db->meta_tags($page, 'keywords'); ?>">
    <meta name="google-site-verification" content="ZRrJCctwPxeKaVQJpNvlicrIyO8oMEV7Pjs-6NRz2X4" />
    <!-- script src='https://www.google.com/recaptcha/api.js'></script -->
    <meta name="author" content="Instappy">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@Instappy" />
    <meta name="twitter:creator" content="@Instappy" />
    <meta name="twitter:url" content="<?php echo rtrim($basicUrl, " / ") . htmlentities($_SERVER['REQUEST_URI']); ?>" />
    <meta name="twitter:title" content="<?php echo $db->meta_tags($page, 'meta_title'); ?>" />
    <meta name="twitter:description" content="<?php echo $db->meta_tags($page, 'meta_description'); ?>" />
    <meta name="twitter:image" content="http://www.instappy.com/images/instappymeta.png" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo $db->meta_tags($page, 'meta_title'); ?>" />
    <meta property="og:description" content="<?php echo $db->meta_tags($page, 'meta_description'); ?>" />
    <meta property="og:url" content="<?php echo rtrim($basicUrl, " / ") . htmlentities($_SERVER['REQUEST_URI']); ?>" />
    <meta property="og:site_name" content="Instappy" />
    <meta property="og:image" content="http://www.instappy.com/images/instappymeta.png" />

    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css" href="css/website.css?v=1" />
    <link rel="stylesheet" type="text/css" href="css/style-sh.css" />
    <link rel="stylesheet" type="text/css" href="css/price.css" />
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/intlTelInput.css">
    <link rel="stylesheet" href="css/icomoon_style.css">
	<link rel="canonical" href="http://www.instappy.com<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>" />

    <!--<link rel="shortcut icon" href="images/favicon.ico"/>
                        <link rel="apple-touch-icon" href="images/favicon.ico"/>-->
    <link rel="icon" href="images/instappy-logo.gif" ; type="image/gif" />
    <!--<script src="js/jquery.min.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!--  <script src="http://instappy.com/pdf/1954/core.js"></script> -->
    <script type="text/javascript" src="js/jquery.flexslider.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
                            <script src="js/html5shiv.js"></script>
                            <script src="js/respond.min.js"></script>
                        <![endif]-->

    <script type="text/javascript">
      var BASEURL = '<?php echo $basicUrl; ?>';


      $(document).ready(function () {



        $('#login_reg').click(function () {
          $('.popup_container').fadeIn();
          $('.login_popup').fadeIn();
          $('.reset_pass').fadeOut();
        });
        $('.close_popup').click(function () {
          $('.popup_container').fadeOut();
          $('.login_popup').fadeOut();
        });


        $('.login_popup .login_tabbing').hide().eq(0).show();
        $('.login_popup .login_popup_head h2').hide().eq(0).show();
        $('.login_popup ul.tabs li').eq(0).addClass('active');

      });
      $(window).load(function () {
        var $j = jQuery.noConflict();
        $j('#screenoverlay').fadeOut();
      });
    </script>
    <?php if (isset($_GET['resetpassword'])) {
                            ?>
      <script>
        $(document).ready(function () {
          $('.popup_container').fadeIn();
          $('.reset_pass').fadeIn();
        });
      </script>
      <?php }
                        ?>
        <!--<script src="http://connect.facebook.net/en_US/all.js"></script>-->

        <?php include 'head-meta.php'; ?>
  </head>

  <body>

    <div id="screenoverlay" style="height: 100%; width: 100%; position: fixed; background: #000; opacity: 0.7; top: 0; left: 0; z-index: 99999;">
      <img class="loader_new" src="images/ajax-loader_new.gif" style="position: absolute; width: 45px; top: 50%; left: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%); -moz-transform: translate(-50%, -50%); -o-transform: translate(-50%, -50%);" />
    </div>
    <div class="top_nav">
      <div class="top_nav_inner">
        <div class="top_nav_left">
          <ul>
            <li class="facebook"><a href="https://www.facebook.com/pages/Instappy/1442626179375675?fref=ts" target="_blank"><i class="fa fa-facebook"></i></a></li>
            <li class="twitter"><a href="https://twitter.com/Instappy" target="_blank"><i class="fa fa-twitter"></i></a></li>
            <li class="pinterest"><a href=" https://www.pinterest.com/instappy/" target="_blank"><i class="fa fa-pinterest"></i></a></li>
            <li class="google"><a href="https://www.youtube.com/channel/UC2TWzMwlUiFs6pyCm3Q679g?view_as=public" target="_blank"><i class="fa fa-youtube"></i></a></li>
            <div class="clear"></div>
          </ul>
        </div>
        <div class="top_nav_right">
          
          <div id="showTollFree"></div>

          <?php if (!empty($_SESSION['custid'])) { ?>
            <p><a href="<?php echo $basicUrl . 'applisting.php'; ?>" ia-track="IA10100028">My Apps</a></p>
            <?php } else { ?>
              <a href="javascript:void(0);" ia-track="IA10100041"><p id="login_reg">My Apps / Register</p></a>
              <?php } ?>
        </div>
        <div class="clear"></div>
        <div id="showTollFreeMob"></div>
        <div class="clear"></div>
		    
        <div id="showTollFreeMob" class="call-mob" style="display:none"></div>    
      </div>
    </div>
    <div class="clear"></div>
    <div class="logo_nav">
      <div class="logo_nav_inner">
        <div class="logo"> <a href="#"><i class="fa fa-navicon"></i></a>
          <a href="index.php" ia-track="IA1010001"><img src="images/Instappy.svg" alt="Instappy"></a>
        </div>
        <nav>
          <ul>
            <li>
              <a href="index.php" ia-track="IA1010001">
                <div class="line"></div>
                Home</a>
            </li>
            <li>
              <a href="javascript:void(0);">
                <div class="line"></div>
                Products <span class="caret"><i class="fa fa-angle-down"></i></span></a>
              <div class="dropdown">
                <ul>
                  <li><a href="content-apps.php" ia-track="IA1010002">Pro Content Apps</a></li>
                  <li><a href="shopping-apps.php" ia-track="IA1010003">Pro Shopping Apps</a></li>
                  <li><a href="enterprise-mobile-apps.php" ia-track="IA1010004">Enterprise Apps</a></li>
                </ul>
              </div>
            </li>
            <li>
              <a href="pricing.php" ia-track="IA1010005">
                <div class="line"></div>
                Features &amp; Pricing
              </a>
            </li>
            <li>
              <a href="success-stories" ia-track="IA1010006">
                <div class="line"></div>
                Success Stories </a>
            </li>
            <li>
              <a href="themes.php" ia-track="IA1010007">
                <div class="line"></div>
                Create App
              </a>
            </li>
            <li>
                <a href="reseller.php" ia-track="IA1010008">
                    <div class="line"></div>
                    Reseller</a>
            </li>            
            <li>
              <a href="<?php echo $basicUrl . 'blog'; ?>" ia-track="IA1010009">
                <div class="line"></div>
                Blog</a>
            </li>
            <li>
              <a href="contact-us.php" ia-track="IA10100010">
                <div class="line"></div>
                Contact Us</a>
            </li>
            <?php //if ($currency == 1) { ?>
              <!--li><a href="javascript:void(0)"><i class="fa fa-phone"></i><em>+91-11-40452222</em></a></li-->
              <?php //} ?>
                <div class="clear"></div>
          </ul>
        </nav>
        <div class="clear"></div>
      </div>
    </div>
    <div class="clear"></div>
    <div class="popup_container">
      <div id="page_ajax"></div>
        <!--  <div class="christmas_popup" style="display:none; width:90%; position:fixed; top:50%; left:50%; transform: translate(-50%, -50%);-webkit-transform: translate(-50%, -50%);-ms-transform: translate(-50%, -50%);-moz-transform: translate(-50%, -50%);-o-transform: translate(-50%, -50%);">
        <span class="christmasClose icon-cross" style="background: #fff; border: 3px solid red; border-radius: 100%; box-shadow: -1px 1px 4px #5a5a5a; color: #000; cursor: pointer; font-size: 12px; font-weight: bold; padding: 5px; position: absolute; right: -15px; top: -15px;"></span>
        <a href="#" class="christmas_img"><img src="images/christmas-banner.png" style="width:100%;"></a>
        </div> -->
      <div class="login_popup"> <span class="close_popup"><img src="images/popup_close.png"></span>
        <div class="login_popup_head">
          <h2>Login</h2>
          <h2>Sign Up</h2>
          <ul class="tabs">
            <li><a href="javascript:void(0)" ia-track="IA10100042">Login</a></li>
            <li><a href="javascript:void(0)" ia-track="IA10100043">Sign Up</a></li>
            <div class="clear"></div>
          </ul>
        </div>
        <div class="login_popup_body">
          <div class="login_tabbing">
            <div class="login_form">
              <form id="login_form" method="post">
                <input type="text" name="email" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : '' ?>" id="login_email" placeholder="Email ID" maxlength="50" />
                <p id="email_error"></p>
                <input type="hidden" id="login_type" value="">
                <input type="hidden" id="author_id" value="">
                <input type="hidden" id="app_id" value="">
                <input type="password" id="login_password" name="password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : '' ?>" placeholder="Password" maxlength="50" />
                <div class="keep_login_check">
                  <input id="remember" type="checkbox" <?php if (isset($_COOKIE[ 'username'])) echo "checked"; ?> value="yes" name="remember" />
                  <label for="remember">Keep me Logged in</label>
                  <div class="clear"></div>
                </div>
                <div id="loading"></div>
                <input type="submit" value="Login" id="login" ia-track="IA10100019">
              </form>
              <a href="javascript:void(0)" class="forgot_password">Reset / Forgot Password</a>
              <a href="javascript:void(0)" class="forgot_password" id="em_opn">Resend Verification Email</a>
              <div class="clear"></div>
            </div>

            <div class="social_connect">
              <div class="facebook_login">
                <div id="fb-root"></div>
                <!-- <a href="#" onclick="fb_login();"><img src="images/facebook-login.png" border="0" alt=""></a>  -->
                <!--<div class="fb-login-button" data-max-rows="1" data-size="large" scope="public_profile,email" onlogin="checkLoginState();" data-show-faces="false" data-auto-logout-link="false"></div>--->
              </div>
              <!--<div class="twitter_login">
                <div id="my-signin2"></div>
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
              </div>-->
              <div class="clear"></div>
              
            </div>
            <small class="by_tm">By logging in you agree to our </br><a href="terms-of-service.php" style="color:#ffcc00">Terms and Conditions</a> & <a href="privacy-policy.php" style="color:#ffcc00">Privacy Policy</a>.</small>
          </div>

          <div class="login_tabbing">
            <div class="login_form">
              <form id="signup_form" method="post">
                <input type="hidden" name="ip_address" id="ip_address" value="<?php echo $ip_address; ?>">

                <div class="two_input clearfix">
                  <input type="text" placeholder="First Name*" id="author_first_name" maxlength="50">
                  <p id="remail_error"></p>
                  <input type="text" placeholder="Last Name*" id="author_last_name" maxlength="50">
                </div>
                <!-- <div class="two_input clearfix"> -->
                <input type="text" placeholder="Company Name" id="author_company_name" maxlength="50">
                <div class='mobileNumber'>
                  <input type="tel" maxlength="6" minlength="2" id="mobile_country" name="mobile_country">
                  <input type="tel" placeholder="Mobile Number*" id="author_mobile_number" minlength="5" maxlength="16">
                </div>
                <input type="text" placeholder="Email ID*" id="register_email" maxlength="50">
                <p id="remail_error"></p>
                <input type="password" placeholder="Password*" id="register_password" maxlength="50">
                <input type="password" placeholder="Re-Enter Password*" id="register_repeat_password" maxlength="50">
                <select id="author_product">
                  <option value="">Select Your Product* </option>
                  <option value="content">Pro Content Apps</option>
                  <option value="catalogue">Pro Shopping Apps</option>
                </select>
                <select id="author_product_category">
                  <option value="">Select App Templates*</option>
                </select>
                <div align="center">



                  <!-- Captcha HTML Code -->

                  <div id="captcha-wrap" style="display:none;">
                    <div class="captcha-box">
                      <img src="<?php echo $basicUrl; ?>includes/get_captcha.php" alt="" id="captcha" />
                    </div>
                    <div class="text-box">
                      <label>Type the two words:</label>
                      <input name="captcha-code" type="text" id="captcha-code">
                    </div>
                    <div class="captcha-action">
                      <img src="<?php echo $basicUrl; ?>images/refresh.jpg" alt="" id="captcha-refresh" />
                    </div>
                  </div>

                  <!--  Copy and Paste above html in any form and include CSS, get_captcha.php files to show the captcha  -->


                </div>


                <div id="register_loading"></div>
                <?php
                  if (isset($_GET['source'])) {
                      $_SESSION['source'] = $_GET['source'];
                  }
                ?>
                  <input type="hidden" name="urlsource" value="<?php echo $_SESSION['source']; ?>">
                  <input type="submit" value="Sign Up" id="signup" ia-track="IA10100020">
              </form>
              <p>
                <a href="javascript:void(0)" style="text-decoration:underline; font-size:13px; color:#hsl(5, 85%, 66%); display:block" id="em_opn">Resend Verification Email</a>
                <br/>
                <small class="by_tm">By signing up you agree to our <a href="terms-of-service.php" style="color:#ffcc00">Terms and Conditions</a> &amp; <a href="privacy-policy.php" style="color:#ffcc00">Privacy Policy</a>.</small>
                <div class="clear"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="forgot_popup"> <span class="close_popup"><img src="images/popup_close.png"></span>
        <div class="forgot_popup_head">
          <h2>Reset / Forgot Password</h2>
        </div>
        <div class="forgot_popup_body">
          <input type="text" id="forgot_email" placeholder="Enter Registered Email id">
          <p id="ferror_email"></p>
          <div id="loading_forgot"></div>
          <p>A mail will be sent to your email id to reset your password.</p>
          <input type="submit" value="Send" id="reset" ia-track="IA10100021">
          <input type="submit" value="GOT IT!" id="signup_reset" ia-track="IA10100022">
        </div>
      </div>



      <!--password reset popup-->
      <div class="reset_pass"> <span class="close_popup"><img src="images/popup_close.png"></span>
        <div class="reset_pass_head">
          <h2>Renew your Password</h2>
        </div>
        <div class="reset_pass_body">
          <form action="" method="post" name="reset_password" id="reset_password">
            <input type="Email" id="reset_pass_email" name="reset_pass_email" placeholder="Email" value="<?php echo base64_decode($_GET['resetpassword']); ?>" readonly />
            <input type="password" class="required" minlength="8" name="reset_pass_newpass" id="reset_pass_newpass" placeholder="Enter New Password" />
            <input type="password" class="required" minlength="8" equalto="#reset_pass_newpass" name="reset_pass_cnfrmpass" id="reset_pass_cnfrmpass" placeholder="Re-enter New Password" />
            <p id="ferror_email"></p>
            <div id="loading_forgot"></div>
            <div id="reset_pass_success"></div>
            <input type="submit" value="Reset" id="reset_pass" ia-track="IA10100021">

          </form>
        </div>
      </div>
      <!--pass reset ends-->




      <div class="em_ver" id="eml"> <span class="close_popup"><img src="images/popup_close.png"></span>
        <div class="em_ver_head">
          <h2>Email Verification</h2>
        </div>
        <div class="em_ver_body">
          <input type="text" placeholder="Enter email id" id="email_resend">
          <p id="resend_error"></p>
          <div id="loading_forgot"></div>
          <p>A mail will be sent to your email id for verification.</p>
          <div id="loading_resend"></div>
          <input type="submit" value="Send" id="reset_email" ia-track="IA10100022">
        </div>
      </div>

    </div>
    <!--email verificaion script-->
    <script type="text/javascript">
      $(document).ready(function () {
        $('#em_opn').click(function () {
          $('.login_popup').hide();
          $('#eml , .popup_container').show()
        })

        $('#eml .close_popup').click(function () {
          $(this).parent().hide();
          $('.popup_container').hide();
          //$('.by_tm').show()
        })

      });
    </script>

