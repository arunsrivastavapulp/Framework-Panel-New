<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
$totalPrice='';
if(isset($_SESSION['totalPrice'])){
$totalPrice = $_SESSION['totalPrice'];
 }
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
              	  <!--
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
                    </div> -->
                    <div class="next_step">
                    	<a href="#" class="back_cart">< &nbsp;&nbsp;&nbsp;Back to Cart</a>
                    	<a href="payment_gateway3.php" class="continue">Continue ></a>
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
                            <p class="total_cost"><?php echo $totalPrice;?></p>
                            <p class="total_saving">Total savings <span>$ 1,800.00</span></p>
                            <a href="payment_gateway3.php">Proceed to Checkout ></a>
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
    /*var rightHeight = $(window).height() - 45;
    $(".right_main").css("height", rightHeight + "px")*/

    });
</script>


</body>
</html>
