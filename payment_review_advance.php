<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/myapp/myapps.php');
$apps = new MyApps();
	$app_id=isset($_GET['appid']) ? $_GET['appid'] : '';
	$app_part=$apps->check_app_part_payment($app_id);
	$token = md5(rand(1000, 9999)); //you can use any encryption
	$_SESSION['token'] = $token;
	$service_tax=$_SESSION['service_tax'];
	$db->get_country();
	$cureeny_arr=$db->get_current_currency($app_part->id);
	$checkcountry = $_SESSION['country'];
	$currency = $cureeny_arr['currency_type_id'];
	$currencyIcon = $cureeny_arr['currency_icon'];
	$_SESSION['currencyid']=$currency;
	$_SESSION['currencyidselect']=$currency;
	$totalPrice = '';
	$total_saving = '';
	if (isset($_SESSION['totalPrice'])) {
		$totalPrice = round($_SESSION['totalPrice'], 2);
	}
	
	if (isset($_SESSION['total_saving'])) {
		$total_saving = round($_SESSION['total_saving'], 2);
	}
	if (isset($_SESSION['custid'])) {
		require_once('modules/user/userprofile.php');
		$user = new UserProfile();
		$custid=$_SESSION['custid'];
		$masterpay = 0;
		$user_data = $user->getUserAddByCustId($custid);
		if(empty($user_data)){
			$masterpay = 1;
			$user_data = $user->getUserByCustId($custid);
		}
		
		//     echo "<pre>"; print_r($user_data); echo "</pre>"; 
		//     die;
	} else {
		echo "<script type='text/javascript'>window.location = BASEURL;</script>";
	}
	
	?>
	<section class="main">
		<section class="right_main">
			<div class="right_inner">
				<div class="bannertitle">
					<h2 style="font-size:16px;line-height:28px">You have qualified for multiple money saving offers. <br/>We have automatically applied the best offers for you giving you the lowest price.</h2>
					<p style="margin-top:10px">Apps built using the Instappy.com are packed with features that are built for your success.
						Instappy apps are <span>Instant, Affordable, Stunning, Intuitive</span> and will <span>change the way you interact 
						with your customers.</span> Our business model allows us to provide every one with world class feature packed 
						applications on Android and iOS for a fraction of the cost that it will take to develop an app otherwise. </p>


					</div>

					<div class="clear"></div>
					<div class="payment_box">
						<div class="payment_left">
							<div class="payment_left_common">
								<div class="payment_left_common_head">
									<h1>Billing Information</h1>
								</div>
								<form class="checkout_step2" name="checkout_step2" id="checkout_step2" method="post" action="">

									<div class="payment_left_common_body">
										<div class="billing_fields">
											<label>First Name</label>
											<input type="text" class="required" maxlength="50" name="billing_fname" value="<?php echo $user_data['first_name'] ?>">
										</div>
										<div class="billing_fields">
											<label>Last Name</label>
											<input type="text" class="required" maxlength="50" name="billing_lname" value="<?php echo $user_data['last_name'] ?>">
										</div>
										<div class="billing_fields">
											<label>Email Address <span>(Completing this field gives us permission to contact you.)</span></label>
											<input type="email" class="required email" maxlength="50" name="billing_email" value="<?php echo $user_data['email_address'] ?>">
											<span class="query">?</span>
										</div>
										<div class="billing_fields">
											<label>Country / Region</label>
											<select id='country' name='billing_country' class="required">
												<?php $user->get_countries($custid,$masterpay); ?>
											</select>
										</div>
										<div class="billing_fields">
											<label>Address 1</label>
											<input type="text" class="required" maxlength="50" name="billing_address1" value="<?php echo $user_data['street'] ?>">
										</div>
										<div class="billing_fields">
											<label>Address 2 <em>(optional)</em></label>
											<input type="text" maxlength="50" name="billing_address2" value="">
										</div>
										<div class="billing_fields">
											<label>Zip/Postal</em></label>
											<input type="text" class="required" maxlength="6" minlength='6' name='billing_zip' value="<?php echo $user_data['pincode'] ?>">
										</div>
										<div class="billing_fields">
											<div class="billing_fields_inner">
												<div class="city">
													<label>City</label>
													<input type="text" class="required" maxlength="50" name='billing_city' value="<?php echo $user_data['city'] ?>">
												</div>
												<div class="state">
													<label>State</label>
													<?php
													if (isset($user_data['country']) && $user_data['country'] !='') {
														$datac['conuntry_id'] = $user_data['country'];                                                    

													}
													?>
													<select class="required" name='state' id="zone_id">
														<?php
														if(!empty($datac['conuntry_id'])){
															$states = $user->get_states($datac,$custid,$masterpay);
														} else{
															echo "<option>Select State</option>";
														}

														?>
													</select>
												</div>
												<div class="clear"></div>
											</div>
										</div>
										<div class="billing_fields">
											<label>Phone Number</label>
											<input id="phonecc" type="tel" class="required" maxlength="6" minlength="2" name='billing_phonecc' readonly value="<?php if($user_data['mobile_country_code']!=''){echo '+'.$user_data['mobile_country_code'];}else{
												echo "+91";
											}  ?>">
											<input id="phone" type="tel" class="required" maxlength="10" minlength="10" name='billing_phone' value="<?php echo $user_data['mobile'] ?>" onKeyPress="return isNumber(event)">
											<div class="clear"></div>
										</div>
										<input type='hidden' name='userid' value='<?php echo $user_data['author_id']; ?>'>
										<input type='hidden' name='userip' value='<?php echo $_SERVER['REMOTE_ADDR']; ?>'>
										<input type='hidden' name='total_amount' value='<?php echo $totalPrice; ?>'>
										<input type='hidden' name='app_id' value='<?php echo $_SESSION['appid']; ?>'>
										<img src='images/ajax-loader.gif' class='preloader' style='display:none;'>
										<div id='billing_result'></div>
									</div>

								</form>
							</div>
							<div class="billing_info">
								<p>We'll take you to a third-party website to complete your transaction.</p>
								<p>Don't worry, all of your information remains safe and secure.</p>
							</div>

							<div class="next_step">
								<a href="payment_details.php" class="back_cart">< &nbsp;&nbsp;&nbsp;Back to Cart</a>
								<!-- <a href="payment_review.php" class="continue">Continue ></a> -->
								<a href="javascript:void(0);" class="checkout continue">Pay Now</a>
								<p>You will not be billed yet.</p>
								<div class="clear"></div>
							</div>
						</div>
						<div class="payment_right">
							<div class="payment_right_common">
								<div class="order_summary">
									<div class="select_currency">
										<?php 										
										$last_payment_details=$apps->last_done_payment($app_id);
										$order_details=$apps->order_details($app_part->master_payment_id);
										$amount=round($app_part->part_amount,2) + round($app_part->part_amount * ($service_tax/100),2);
										$order_id=$order_details->order_id;
										$_SESSION['totalPrice']=$amount;
										$_SESSION['orderid']=$order_id;
										?>
										<label>Plan Amount</label>
										<p class="total_cost"><?php echo $currencyIcon; ?> <?php echo round($app_part->part_amount,2); ?></p> 
										<label>Service Tax</label>
										<p class="total_cost"><?php echo $currencyIcon; ?> <?php echo round($app_part->part_amount * ($service_tax/100),2); ?></p> 
										<label>Total cost</label>
										<p class="total_cost"><?php echo $currencyIcon; ?> <?php echo $amount; ?></p>                            
										<?php if ($currency == 1) { ?>

											<form method="post" name="customerData" id="ccav" action="ccavRequestHandler.php">
												<input type="hidden" name="tid" id="tid" readonly />
												<input type="hidden" name="merchant_id" value="72267"/>
												<input type="hidden" name="order_id" value="<?php echo $orderid; ?>"/>
												<input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
												<input type="hidden" name="currency" value="INR"/>
												<input type="hidden" name="redirect_url" value="<?php echo $basicUrl; ?>payment_status.php"/>
												<input type="hidden" name="cancel_url" value="<?php echo $basicUrl; ?>failure.php"/>
												<input type="hidden" name="language" value="EN"/>
												<input type="hidden" name="billing_name" value="<?php echo $firstname; ?>"/>
												<input type="hidden" name="billing_address" value="<?php echo $user_data['address'] ?>"/>
												<input type="hidden" name="billing_city" value="<?php echo $user_data['city'] ?>"/>
												<input type="hidden" name="billing_state" value="<?php echo $user_data['state'] ?>"/>
												<input type="hidden" name="billing_zip" value="<?php echo $user_data['zip'] ?>"/>
												<input type="hidden" name="billing_country" value="<?php echo $user_data['country'] ?>"/>
												<input type="hidden" name="billing_tel" value="<?php echo $user_data['phone'] ?>"/>
												<input type="hidden" name="billing_email" value="<?php echo $email ?>"/>
												<input type="hidden" name="delivery_name" value="<?php echo $user_data['first_name'] . ' ' . $user_data['last_name']; ?>"/>
												<input type="hidden" name="delivery_address" value="<?php echo $user_data['address'] ?>"/>
												<input type="hidden" name="delivery_city" value="<?php echo $user_data['city'] ?>"/>
												<input type="hidden" name="delivery_state" value="<?php echo $user_data['state'] ?>"/>
												<input type="hidden" name="delivery_zip" value="<?php echo $user_data['zip'] ?>"/>
												<input type="hidden" name="delivery_country" value="<?php echo $user_data['country'] ?>"/>
												<input type="hidden" name="delivery_tel" value="<?php echo $user_data['phone'] ?>"/>
												<input type="hidden" name="part_payment_id" value="<?php echo $app_part->id;?>" />
																<!--		<input type="hidden" name="merchant_param1" value="additional Info."/>
																    <input type="hidden" name="merchant_param2" value="additional Info."/>
																    <input type="hidden" name="merchant_param3" value="additional Info."/>
																    <input type="hidden" name="merchant_param4" value="additional Info."/>
																    <input type="hidden" name="merchant_param5" value="additional Info."/>-->
																    <input type="hidden" name="promo_code" value=""/>
																    <input type="hidden" name="customer_identifier" value=""/>
																</form>
																<!--<a href="#" onclick="payForm()">Place Your Order with PayU ></a>-->
																<input type="checkbox" checked class="tandcCheck" /> Terms and Conditions
																<a href="#" onclick="ccavForm()">Place Your Order ></a>
																<?php 
																}
														else{
															?>
															<form action="paypalRequestHandler.php" id="paypal" method="post" style="float: right;">
																<input type="hidden" name="cmd" value="_xclick">
																<input type="hidden" name="hosted_button_id" value="AUQXPCZYGBFGA">

																<!-- Identify your business so that you can collect the payments. -->
																<input type="hidden" name="business" value="accounts@pulpstrategy.com">


																<!-- Specify details about the item that buyers will purchase. -->
																<input type="hidden" name="item_name" value="Instappy Apps">
																<input type="hidden" name="item_number" id="itemno" value="<?php echo $order_id;?>" />
																<input type="hidden" name="amount" value="<?php echo $amount;?>">
																<input type="hidden" name="currency_code" value="USD">
																<input type="hidden" name="rm" value="2" />
																<input type="hidden" name="part_payment_id" value="<?php echo $app_part->id;?>" />

															</form>

														</div>
														<!--<p class="notification_fee">Push notification fees* <span> 600.00</span></p>-->

														<?php if (($total_saving != 0) && ($total_saving != '')) { ?>
															<p class="total_saving">Total savings <span><?php echo $total_saving; ?></span><span style="margin-right:5px;"><?php echo $currencyIcon; ?></span></p>
															<?php } ?>                            
															<!-- <a href="payment_review.php">Proceed to Checkout ></a> -->
															<input type="checkbox" checked class="tandcCheck" /> Terms and Conditions
															<input class="checkout continue" type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
															<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">	
															<div class="clear"></div>
															<?php }?>
				</div>
				</div>
				</div>
				<div class="clear"></div>
		</div>
		</div>
		</section>
		</section>
		</section>

<script type="text/javascript" src="js/countries.js"></script>

<script>
	$(document).ready(function () {
		$("#country").trigger("click");
		$(".stats_download a").click(function () {
			$(this).next().toggleClass("show_pop");
			$(this).parent().siblings().children("div").removeClass("show_pop");
		});
		$(document).click(function () {
			$(".stats_download a + div").removeClass("show_pop");
		});
		$('.stats_download a').bind('click', function (e) {
			e.stopPropagation();
		});
		$('.stats_download_tooltip').bind('click', function (e) {
			e.stopPropagation();
		});
			/*var rightHeight = $(window).height() - 45;
			$(".right_main").css("height", rightHeight + "px")*/
			
		});
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
</script>
<!--<script type="text/javascript" src="js/jquery1.4.4.min.js"></script>-->
<script type="text/javascript" src="js/validate.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$(".leftsidemenu li").removeClass("active");
		$(".leftsidemenu li.cart").addClass("active");
			//	populateCountries("country", "state", '<?php echo $user_data['country']; ?>', '<?php echo $user_data['state']; ?>');
			$("#country").change("on", function () {
				var country_id = $(this).val();
				jQuery.ajax({
					url: 'ajax.php',
					type: "post",
					data: "conuntry_id=" + country_id +"&custid=<?php echo $custid; ?>&type=country_state",
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
			
			$("#checkout_step2").validate({
				submitHandler: function (form) {
					$('#checkout_step2 .preloader').show();
					var form_data = {
						data: $(form).serialize() + "&countryName=" + $("#country option:selected").text() + "&stateName=" + $("#zone_id option:selected").text(), //your data being sent with ajax
						token: '<?php echo $token; ?>', //used token here.                            
						is_ajax: 1
					}
					
					jQuery.ajax({
						url: BASEURL + 'modules/pricing/adduserbillingadd.php',
						type: "post",
						data: form_data,
						success: function (response) {
							if (response == 1) {
								$("#billing_result").html('Submitted successfully!');
								$('#checkout_step2 .preloader').hide();
								paypalForm();
							} else {
								$("#billing_result").html('There is error while submit.');
								$('#checkout_step2 .preloader').hide();
							}
						},
						error: function () {
							$("#billing_result").html('There is error while submit.');
							$('#checkout_step2 .preloader').hide();
						}
					});
				}
			});
			$('.checkout').bind('click', function () {
				$('#checkout_step2').submit();
			});
			//$("#checkout_step2").validate();
		});
	</script>
	<script src="js/intlTelInput.js"></script>
	<script>
		$("#phonecc").intlTelInput({
			utilsScript: "js/utils.js"
		});
		function paypalForm() {
			
			if ($(".tandcCheck").prop("checked") == true) {
				$("#paypal").submit();
			}
			else
			{
				$("#screenoverlay").css("display", "none");
				$(".popup_container").css("display", "block");
				$(".confirm_name").css("display", "block");
				$(".confirm_name_form p").text("Please accept the terms and Conditions.");
				return false;
				
			}
			
			
		}

		function ccavForm() {
			if ($(".tandcCheck").prop("checked") == true) {
				$("#ccav").submit();
			}
			else
			{
				$("#screenoverlay").css("display", "none");
				$(".popup_container").css("display", "block");
				$(".confirm_name").css("display", "block");
				$(".confirm_name_form p").text("Please accept the terms and Conditions.");
				return false;

			}

		}
	</script>
</body>
</html>