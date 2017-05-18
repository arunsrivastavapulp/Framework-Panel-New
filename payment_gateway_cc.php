<script>
	window.onload = function() {
		var d = new Date().getTime();
		document.getElementById("tid").value = d;
	};
</script>
<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/myapp/myapps.php');
$apps = new MyApps();
$totalPrice='';
$total_saving='';
if(isset($_SESSION['totalPrice'])){
$totalPrice = $_SESSION['totalPrice'];
 }
 
 if(isset($_SESSION['total_saving'])){
$total_saving = $_SESSION['total_saving'];
 }
  $appID = $_SESSION['appid'];
if(isset($_SESSION['username'])){
	require_once('modules/user/userprofile.php');
	$user = new UserProfile();
	$user_data = $user->getUserByEmail($_SESSION['username']);
	/* echo "<pre>"; print_r($user_data); echo "</pre>"; */
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
                	<li class="done">
                    	<em>2</em>
                        <span>Billing &amp; Payment</span>
                        <hr noshade>
                    </li>
                	<li class="active">
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
                <div class="review_message">
                    <p>Review Your Order and Confirm Your Purchase</p>
                </div>
            	<div class="payment_left">
                	<div class="payment_left_common">
                    	<div class="payment_left_common_head">
                        	<h1>Billing &amp; Payment Information</h1>
                        </div>
                        <div class="payment_left_common_body">
                        	<div class="billing_payment_address">
                            	<div class="billing_payment_inner">
                                	<h2>Billing Information</h2>
                                    <p><?php echo $user_data['first_name'].' '.$user_data['last_name'];?><br>
									<?php echo $user_data['street']?><br>
									<?php echo $user_data['city']?><br>
									<?php echo $user_data['state']?><br>
									<?php echo $user_data['country']?><br>
									<?php echo $user_data['pincode']?><br>
									<?php echo $user_data['email_address']?><br>
									<?php echo $user_data['mobile']?></p>
                                    <a href="#"><i class="fa fa-pencil"></i> Edit Billing Information</a>
                                </div>
                            	<div class="billing_payment_inner">
                                	<h2>Payment Information</h2>
                                    <p>Net Banking</p>
                                    <a href="#"><i class="fa fa-pencil"></i> Edit Payment Information</a>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                	<!-- <div class="payment_left_common">
                    	<div class="payment_left_common_head">
                        	<ul class="review_order">
                            	<li>Review Order</li>
                            	<li><i class="fa fa-pencil"></i> Edit Order</li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                    	<div class="payment_left_common_body no_padding">
                            <div class="package_select">
                                <ul>
                                    <li>
                                        <img src="images/payment_app_icon.png">
                                        <div class="payment_app_name">
                                            <h2>App Name</h2>
                                            <h3>Devices : Android, iOS</h3>
                                        </div>
                                        <div class="clear"></div>
                                    </li>
                                    <li>
                                        <select>
                                            <option>Package 1</option>
                                            <option>Package 2</option>
                                            <option>Package 3</option>
                                            <option>Package 4</option>
                                        </select>
                                    </li>
                                    <li>2,995.00</li>
                                    <div class="clear"></div>
                                </ul>
                            </div>
                            <div class="payment_app_files">
                                <div class="android_files">
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>Assiatance</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>325</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>File.apk</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>55</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>Splash screen</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>424</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>App Icon</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>45</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>Content</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>345</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>Icons</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>35</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="ios_files">
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>Assiatance</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>325</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>File.apk</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>55</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>Splash screen</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>424</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>App Icon</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>45</p>
                                            <span></span>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="total_app_payment">
                                    <p>Total: <span>$2,995</span></p>
                                </div>
                            </div>
                            <div class="add_all">
                            </div>
                        </div>
                    </div> -->
                    <!--
                    <div class="payment_left_common">
                    	<div class="payment_left_common_head">
                        	<ul>
                            	<li>Application</li>
                            	<li>Package</li>
                            	<li>Subtotal</li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                    	<div class="payment_left_common_body no_padding">
                            <div class="package_select">
                                <ul>
                                    <li>
                                        <?php /*
                            $publishedappImage = $apps->publish_app_img($appID);
                            if($publishedappImage!=''){
                                echo '<img src="'.$publishedappImage.'" height="100px" width="100px">';
                            } else{
                                echo '<img src="image/payment_app_icon.png">';
                            }
                            ?>
                                        <div class="payment_app_name">
                                            <h2><?php
                            $appName = $apps->app_name($appID);
                            if($appName['summary']!=''){
                                echo $appName['summary'];
                            } */?></h2>
                                            <h3>Devices : Android, iOS</h3>
                                        </div>
                                        <div class="clear"></div>
                                    </li>
                                    <li>
                                        <select>
                                            <option>1 Year</option>
                                            <option selected="">2 Years</option>
                                        </select>
                                      <!--  <span class="discount">15% Off</span> --> <!--
                                    </li>
                                    <li><?php //echo $totalPrice?></li>
                                    <div class="clear"></div>
                                </ul>
                            </div>
                            <div class="payment_app_files">
                                <div class="android_files">
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>OS</p>
                                            <select>
                                            	<option>Android + iOS</option>
                                            	<option>iOS</option>
                                            	<option>Android</option>
                                            </select>
                                           <!-- <span>10% Off</span> --> <!--
                                        </div>
                                        <div class="files_name_right">
                                            <p><?php //echo $totalPrice?></p>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>Package</p>
                                            <select>
                                            	<option>Basic</option>
                                            	<option>Advanced</option>
                                            	<option>Enterprise</option>
                                            </select>
                                        </div>
                                        <div class="files_name_right">
                                            <p><?php //echo $totalPrice ?></p>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>Splash Screen</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>0</p>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="payment_files_name">
                                        <div class="files_name_left">
                                            <p>App Icon</p>
                                        </div>
                                        <div class="files_name_right">
                                            <p>0</p>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="total_app_payment">
                                    <p>Total: <span><?php //echo $totalPrice; */ ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                    
                	
                    <div class="next_step" >
                    	<a href="#" class="back_cart">< &nbsp;&nbsp;&nbsp;Back to Billing &amp; Payment</a>
                    	<a href="#" class="continue" onclick="payForm()">Place Your Order ></a>
                        <p>You will be billed now.</p>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="payment_right">
                	<div class="payment_right_common">
                    	<div class="order_summary">
                        	<div class="select_currency">
                            	<label>Order Summary</label>
                                <!--<select class="my-select">
                                    <option data-img-src="images/dollar_icon.png">US Dollar</option>
                                    <option data-img-src="images/ruppee_icon.png">Rupees</option>
                                </select>-->
                               <div class="clear"></div>
                            </div>
                            <!--<p class="notification_fee">Push notification fees* <span>$ 60.00</span></p>-->
                            <p class="total_cost"><span>Total cost</span><?php echo $totalPrice ;?></p>
                            <p class="total_saving">Total savings <span><?php echo $total_saving;?></span></p>
                            
                            <div class="clear"></div>
                        </div>
                        
                        
                        <div class="ordertnc">
                            	<input type="checkbox" checked /><span>I agree to the following:</span>
                                <br/>
                                <a href="terms-conditions.php">Terms and Conditions</a>
                                <a href="privacy-policy.php">Privacy Policy</a>
                                <div >
                                	 <a href="#" onclick="payForm()">Place Your Order with PayU ></a>
                                	 <a href="#" onclick="ccavForm()">Place Your Order with ccav ></a>
									 
                                </div>
                                <div class="clear"></div>
                            </div>
                           <div class="clear"></div>
                        
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </section>
  </section>
</section>
<?php 
if($totalPrice == '')
{
$totalPrice='4900';
}
	$orderId=rand(1000,5000000);
	$key='C0Dr8m';
	$txnid=$orderId;
	$amount=$totalPrice;
	$productinfo='SAU Admission21 2014';
	$firstname=$user_data['first_name'];
	$lastname=$user_data['last_name'];
	$email=$user_data['email_address'];
	/*$udf1='pulp strategy';
	$udf2='pulp strategy';
	$udf3='pulp strategy';
	$udf4='test transaction';
	$udf5='pulp strategy';*/
	$SALT='3sf0jURk';

	 $hashString=$key."|".$txnid."|".$amount."|".$productinfo."|".$firstname."|".$email."|||||||||||".$SALT;
 $hash = hash ("sha512", $hashString); 

	// $hash=openssl_digest($hashString, 'sha512');

	
	?>
<form action="https://test.payu.in/_payment" id="payuForm" method='post'> 
 <input type="hidden" name="firstname" value="<?php echo $firstname?>" /> 
 <input type="hidden" name="lastname" value="<?php echo $lastname?>" /> 
 <input type="hidden" name="surl" value="<?php echo $basicUrl;?>payment_gateway4.php" /> 
 <input type="hidden" name="phone" value="<?php echo $user_data['mobile']; ?>" /> 
 <input type="hidden" name="key" value="<?php echo $key?>" /> 
 <input type="hidden" name="hash" value = "<?php echo $hash;?>" /> 
 <input type="hidden" name="curl" value="<?php echo $basicUrl;?>" /> 
 <input type="hidden" name="furl" value="<?php $basicUrl?>failure.php" /> 
 <input type="hidden" name="txnid" value="<?php echo $txnid?>" /> 
 <input type="hidden" name="productinfo" value="<?php echo $productinfo?>" /> 
 <input type="hidden" name="amount" value="<?php echo $amount?>" /> 
 <input type="hidden" name="email" value="<?php echo $email?>" /> 

 </form>

 <form method="post" name="customerData" id="ccav" action="ccavRequestHandler.php">
		<input type="hidden" name="tid" id="tid" readonly />
		<input type="hidden" name="merchant_id" value="72267"/>
		<input type="hidden" name="order_id" value="<?php echo $orderId;?>"/>
		<input type="hidden" name="amount" value="<?php echo $amount;?>"/>
		<input type="hidden" name="currency" value="INR"/>
		<input type="hidden" name="redirect_url" value="<?php echo $basicUrl;?>payment_gateway4.php"/>
		<input type="hidden" name="cancel_url" value="<?php echo $basicUrl;?>failure.php"/>
		<input type="hidden" name="language" value="EN"/>
		<input type="hidden" name="billing_name" value="<?php echo $firstname;?>"/>
		<input type="hidden" name="billing_address" value="Room no 1101, near Railway station Ambad"/>
		<input type="hidden" name="billing_city" value="Indore"/>
		<input type="hidden" name="billing_state" value="MP"/>
		<input type="hidden" name="billing_zip" value="425001"/>
		<input type="hidden" name="billing_country" value="India"/>
		<input type="hidden" name="billing_tel" value="9876543210"/>
		<input type="hidden" name="billing_email" value="<?php echo $email?>"/>
		<input type="hidden" name="delivery_name" value="Chaplin"/>
		<input type="hidden" name="delivery_address" value="room no.701 near bus stand"/>
		<input type="hidden" name="delivery_city" value="Hyderabad"/>
		<input type="hidden" name="delivery_state" value="Andhra"/>
		<input type="hidden" name="delivery_zip" value="425001"/>
		<input type="hidden" name="delivery_country" value="India"/>
		<input type="hidden" name="delivery_tel" value="9876543210"/>
		<input type="hidden" name="merchant_param1" value="additional Info."/>
		<input type="hidden" name="merchant_param2" value="additional Info."/>
		<input type="hidden" name="merchant_param3" value="additional Info."/>
		<input type="hidden" name="merchant_param4" value="additional Info."/>
		<input type="hidden" name="merchant_param5" value="additional Info."/>
		<input type="hidden" name="promo_code" value=""/>
		<input type="hidden" name="customer_identifier" value=""/>
	      </form>
 
 
 
 <script>
function payForm(){
$("#payuForm").submit();
}


function ccavForm(){
$("#ccav").submit();
}

    $(document).ready(function() {
        $(".payment_files_name .files_name_right span").click(function() {
            if($(this).is(':empty')){
                $(this).parent().parent().addClass("item_disabled"); //line 2125
                $(this).css("background","none").append("<input type='checkbox'>");
            } else {
                $(this).parent().parent().removeClass("item_disabled");
                $(this).css("background","url('images/menu_delete.png')");
                $(this).children("input").remove();
            }
        });
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
