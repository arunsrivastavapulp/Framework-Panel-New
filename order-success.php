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
                	<li class="done">
                    	<em>2</em>
                        <span>Billing &amp; Payment</span>
                        <hr noshade>
                    </li>
                	<li class="done">
                    	<em>3</em>
                        <span>Place	Your Order</span>
                        <hr noshade>
                    </li>
                	<li class="active">
                    	<em>4</em>
                        <span>Thank You</span>
                        <hr noshade>
                    </li>
                </ul>
            </div>
            <div class="clear"></div>
            <div class="payment_box">
            	<div class="payment_left">
                	<div class="payment_thankyou">
                    	<h2>Thank You!</h2>
                        <p>Your order have been placed.</p>
                        <div class="thankyou_publishing_app">
                        	<h3>Publishing your app in store:</h3>
                        	<input type="radio" id="publish1" name="publishapp" checked>
                            <label for="publish1">I have an account, I don't need help.</label>
                            <div class="clear"></div>
                        	<input type="radio" id="publish2" name="publishapp">
                            <label for="publish2">I have an account, I need help.</label>
                            <div class="clear"></div>
                        	<input type="radio" id="publish3" name="publishapp">
                            <label for="publish3">I don't have an account, help me.</label>
                            <div class="clear"></div>
                        </div>
                        <a href="#" class="make_app_next">Next</a>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </section>
  </section>
</section>
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
