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
<link rel="stylesheet" href="css/intlTelInput.css">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<script src="js/jquery.min.js"></script>


<title>Untitled Document</title>
</head>

<body>
<section class="clear framework">
  <header class="top-area"><img src="images/logo.png"></header>
  <aside>
    <div class="user_img"> <a href="userregister.html"><img src="images/user.png" alt="Image Not Found"> <span>Hi, Guest</span></a></div>
    <a href="#"> <img src="images/login_icon.png" alt="Image Not Found"> <span>Login</span></a>
    <ul>
      <li class="mobile-icon active"><a href="panel.html"><img src="images/create_app_icon.png" alt="Image Not Found"> </a>
          <div class="hint_content">
              <span class="open_hint">i</span>
              <p>From here you design your application as you want.</p>
          </div>
      </li>
      <li class="tablet"><a href="myapps1.html"><img src="images/my_app_icon.png" alt="Image Not Found"></a></li>
      <li class="user"><a href="choose_package.html"><img src="images/choose_package_icon.png" alt="Image Not Found"></a></li>
      <li class="truck"><a href="statistics.html"><img src="images/statistics_icon.png" alt="Image Not Found"></a></li>
      <li class="truck"><a href="#"><img src="images/bell_icon.png" alt="Image Not Found"></a></li>
    </ul>
  </aside>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner">
            <div class="payment_steps">
            	<ul>
                	<li class="done">
                    	<em>1</em>
                        <span>Cart</span>
                        <hr noshade>
                    </li>
                	<li class="active">
                    	<em>2</em>
                        <span>Billing &amp; Payment</span>
                        <hr noshade>
                    </li>
                	<li>
                    	<em>3</em>
                        <span>Place	Your Order</span>
                        <hr noshade>
                    </li>
                	<li>
                    	<em>4</em>
                        <span>Thank You</span>
                        <hr noshade>
                    </li>
                </ul>
            </div>
            <div class="clear"></div>
            <div class="payment_box">
            	<div class="payment_left">
                	<div class="payment_left_common">
                    	<div class="payment_left_common_head">
                        	<h1>Billing Information</h1>
                        </div>
                    	<div class="payment_left_common_body">
                        	<div class="billing_fields">
                            	<label>First Name</label>
                                <input type="text">
                            </div>
                        	<div class="billing_fields">
                            	<label>Last Name</label>
                                <input type="text">
                            </div>
                        	<div class="billing_fields">
                            	<label>Email Address <span>(Completing this field gives us permission to contact you.)</span></label>
                                <input type="text">
                                <span class="query">?</span>
                            </div>
                        	<div class="billing_fields">
                            	<label>Country / Region</label>
                                <select>
                                	<option>India</option>
                                	<option>India</option>
                                	<option>India</option>
                                	<option>India</option>
                                </select>
                            </div>
                        	<div class="billing_fields">
                            	<label>Address 1</label>
                                <input type="text">
                            </div>
                        	<div class="billing_fields">
                            	<label>Address 2 <em>(optional)</em></label>
                                <input type="text">
                            </div>
                        	<div class="billing_fields">
                            	<label>Zip/Postal</em></label>
                                <input type="text">
                            </div>
                        	<div class="billing_fields">
                            	<div class="billing_fields_inner">
                                    <div class="city">
                                        <label>City</label>
                                        <input type="text">
                                    </div>
                                    <div class="state">
                                        <label>State</label>
                                        <select>
                                            <option>Select One...</option>
                                            <option>Select One...</option>
                                            <option>Select One...</option>
                                            <option>Select One...</option>
                                        </select>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        	<div class="billing_fields">
                            	<label>Phone Number</label>
                                <input id="phone" type="tel">
                            </div>
                            <p><a href="#">Use alternate</a> domain registrant contact information</p>
                        </div>
                    </div>
                    <div class="billing_info">
                        <p>We'll take you to a third-party website to complete your transaction.</p>
                        <p>Don't worry, all of your information remains safe and secure.</p>
                    </div>
                	<div class="payment_left_common">
                    	<div class="payment_left_common_head">
                        	<h1>Payment Information</h1>
                        </div>
                        <div class="payment_option">
                            <input type="radio" name="payment" id="pay_opt1" checked>
                            <label for="pay_opt1">Net Banking</label>
                            <div class="clear"></div>
                            <a href="#">Details</a>
                        </div>
                        <div class="payment_option">
                            <input type="radio" name="payment" id="pay_opt2">
                            <label for="pay_opt2">Credit Card <img src="images/payment_cards.png"></label>
                            <span>Note: Payments will be processed through a merchant account resiging in India.</span>
                        </div>
                        <div class="payment_option">
                            <input type="radio" name="payment" id="pay_opt3">
                            <label for="pay_opt3">Debit Cards</label>
                            <div class="clear"></div>
                            <a href="#">Details</a>
                        </div>
                        <div class="payment_option">
                            <input type="radio" name="payment" id="pay_opt4">
                            <label for="pay_opt4">UnionPay <img src="images/payment_unionpay.png"></label>
                        </div>
                        <div class="payment_option">
                            <input type="radio" name="payment" id="pay_opt5">
                            <label for="pay_opt5">Cash Cards</label>
                            <div class="clear"></div>
                            <a href="#">Details</a>
                        </div>
                        <div class="payment_option">
                            <input type="radio" name="payment" id="pay_opt6">
                            <label for="pay_opt6">Mobile Payments</label>
                        </div>
                        <div class="payment_option">
                            <input type="radio" name="payment" id="pay_opt7">
                            <label for="pay_opt7">Moneybookers <img src="images/payment_moneybookers.png"></label>
                        </div>
                    </div>
                    <div class="next_step">
                    	<a href="#" class="back_cart">< &nbsp;&nbsp;&nbsp;Back to Cart</a>
                    	<a href="order_confirm.php" class="continue">Continue ></a>
                        <p>You will not be billed yet.</p>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="payment_right">
                	<div class="payment_right_common">
                    	<div class="order_summary">
                        	<div class="select_currency">
                            	<label>Total cost</label>
                                <select class="my-select">
                                    <option data-img-src="images/dollar_icon.png">US Dollar</option>
                                    <option data-img-src="images/ruppee_icon.png">Rupees</option>
                                </select>
                               <div class="clear"></div>
                            </div>
                            <p class="notification_fee">Push notification fees* <span>$ 60.00</span></p>
                            <p class="total_cost">$ 3,055.00</p>
                            <p class="total_saving">Total savings <span>$ 1,800.00</span></p>
                            <a href="order_confirm.php">Proceed to Checkout ></a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </section>
  </section>
</section>
<script src="js/intlTelInput.js"></script>
<script>
  $("#phone").intlTelInput({
	utilsScript: "js/utils.js"
  });
</script>
<script>
    $(document).ready(function() {
        $(".stats_download a").click(function() {
            $(this).next().toggleClass("show_pop");
            $(this).parent().siblings().children("div").removeClass("show_pop");
        });
        $(document).click(function() {
            $(".stats_download a + div").removeClass("show_pop");
        });
        $('.stats_download a').on('click', function(e) {
            e.stopPropagation();
        });
        $('.stats_download_tooltip').on('click', function(e) {
            e.stopPropagation();
        });
    var rightHeight = $(window).height() - 45;
    $(".right_main").css("height", rightHeight + "px")

    });
</script>


</body>
</html>
