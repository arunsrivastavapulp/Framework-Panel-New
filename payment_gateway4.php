<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
?>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner">
        <div class="bannertitle">
           		<h2 style="font-size:16px;line-height:28px">You have qualified for multiple money saving offers. <br/>We have automatically applied the best offers for you giving you the lowest price.</h2>
                <p style="margin-top:10px">Apps built using the Instappy.com are packed with features that are built for your success.
 Instappy apps are <span>Instant, Affordable, Stunning, Intuitive</span> and will <span>change the way you interact 
with your customers.</span> Our business model allows us to provide every one with world class feature packed 
applications on Android and iOS for a fraction of the cost that it will take to develop an app otherwise. 
                </p>
                
                
           
           </div>
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
                        <p>Looks like you are a Pro!</p>
                        <span>Thank you for using Instappy for building your awesome app! Your app has been successfully registered with us for Quality Analysis. Usually it takes just a few hours for us to get through the QA process, however, in some cases it might take up to 2 Business Bays for us to email you the *.APK file depending upon the kind and quantity of the content in your app.</span>
                        <div class="thankyou_publishing_app">
                        	<h3>Publishing your app in store:</h3>
                        	<input type="radio" id="publish1" name="publishapp" checked>
                            <label for="publish1" class="optn1">I have an account. I can manage my way through.</label>
                            <div class="clear"></div>
                        	<input type="radio" id="publish2" name="publishapp">
                            <label for="publish2" class="optn2">I think I need help from here onwards.</label>
                            <div class="clear"></div>
                            <div class="inner_radio" style="display:none;">
                                <input type="radio" id="inpublish1" name="publishapp" value="" checked>
                                <label for="inpublish1">I am publishing an Android app to Play Store.</label>
                                <div class="clear"></div>
                                <input type="radio" id="inpublish2" name="publishapp" value="" >
                                <label for="inpublish2">I am publishing an iOS app to App Store.</label>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <a href="javascript:void(0);" class="make_app_next">Publish</a>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="payment_bottom">
                	<p>In the meanwhile we get your awesome app ready to be uploaded on the mobile store, it would be a good time to get the SEO ready for the store so that your app starts to show up the ranks.</p>
                    <p>Need help? <a href="#">Click here</a> for great tips on SEO!</p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </section>
  </section>
</section>
<script>
$(document).ready(function(){
 $("#appShow").show();
  $("#hideit").hide();
    $(".btn1").click(function(){
        $("#hideit").hide();
		 $("#appShow").show();
    });
    $(".btn2").click(function(){
        $("#hideit").show();
		$("#appShow").hide();
    });
	
	
});
</script>
<script type="text/javascript">
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
    /*var rightHeight = $(window).height() - 45;
    $(".right_main").css("height", rightHeight + "px")*/
	
	
  });

  $('.optn2').on('click',function(){
	  $('.inner_radio').show();
	  $('.make_app_next').text('Next');
  });
  $('.optn1').on('click',function(){
	  $('.inner_radio').hide();
	  $('.make_app_next').text('Publish');
  });
  
  /* Edited By Varun Srivastava */
	$('.make_app_next').on('click',function(){
		var sel_app_radio = $('.inner_radio input[type="radio"]:checked').attr('id');
		if(sel_app_radio == 'inpublish1')
		{
			window.location = BASEURL + 'publish_android.php';
		}
		else if(sel_app_radio == 'inpublish2')
		{
			window.location = BASEURL + 'ios-app-publish.php';
		}
	});
</script>

</body>
</html>
