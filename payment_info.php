<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$setcurrency=0;
if (isset($_SESSION['currencyidselect'])) {
    if ($_SESSION['currencyidselect'] != '') {
        $currency = $_SESSION['currencyidselect'];
        if ($currency == 1) {
            $currencyIcon = "Rs. ";
        } else {
            $currencyIcon = "$ ";
        }
    } else {
        $setcurrency = 1;
    }
} else {
    $setcurrency = 1;
}
if ($setcurrency != 0) {
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
                        <a href="payment_details.php" class="back_cart">< &nbsp;&nbsp;&nbsp;Back to Cart</a>
                        <!-- <a href="payment_review.php" class="continue">Continue ></a> -->
                        <a ia-track="IA101000213" href="javascript:void(0);" class="checkout continue">Continue ></a>
                        <p>You will not be billed yet.</p>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="payment_right">
                    <div class="payment_right_common">
                        <div class="order_summary">
                            <div class="select_currency">
                                <label>Total cost</label>
                                <p class="total_cost"><?php echo $currencyIcon; ?> <?php echo $totalPrice; ?></p>                            
                            </div>
                            <!--<p class="notification_fee">Push notification fees* <span> 600.00</span></p>-->

                            <?php if (($total_saving != 0) && ($total_saving != '')) { ?>
                                <p class="total_saving">Total savings <span><?php echo $total_saving; ?></span><span style="margin-right:5px;"><?php echo $currencyIcon; ?></span></p>
                            <?php } ?>                            
                            <!-- <a href="payment_review.php">Proceed to Checkout ></a> -->
                            <a ia-track="IA101000209" href="javascript:void(0);" class="checkout">Proceed to Checkout ></a>
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
                            window.location = BASEURL + 'payment_review.php';
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
</script>
<script type="text/javascript" src="js/trackuser.jquery.js"></script>
</body>
</html>