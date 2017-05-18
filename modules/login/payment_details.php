<?php
require_once('includes/header.php');
if ($checkcountry == 1) {
    $_SESSION['currency'] = "IN";
    $currency = 1;
    $currencyIcon = "Rs.";
} else {
    $_SESSION['currency'] = "USA";
    $currency = 2;
    $currencyIcon = "$";
}
require_once('modules/pricing/pricing.php');
$custid = $_SESSION['custid'];
$mypricing = new mypricing();
$carthavedata = $mypricing->carthavedata($custid);

if (!empty($carthavedata)) {
    require_once('includes/leftbar.php');
    require_once('modules/myapp/myapps.php');
    $apps = new MyApps();
    $token = md5(rand(1000, 9999)); //you can use any encryption
    $_SESSION['token'] = $token;

    $palnprice = $mypricing->getallappsplan($custid);
    $mpackages = $mypricing->getallappsMpackages($custid);
    $paidapp = $mypricing->getallPaidapps($custid);
    $distinctmp = $mypricing->getalldistinctMPprice($custid);
    $orderid = '';
    $x = 0;
    $y = 0;
    ?>
    <section class="main">
        <section class="right_main">
            <div class="right_inner">
                <div class="bannertitle">
                    <h2 style="font-size:16px;line-height:28px">You have qualified for multiple money saving offers. <br/>We have automatically applied the best offers for you giving you the lowest price.</h2>
                    <p style="margin-top:10px">Apps built using the Instappy.com are packed with features that are built for your success.
                        Instappy apps are <span>instant, affordable, stunning, intuitive</span> and will <span>change the way you interact 
                            with your customers.</span> Our business model allows us to provide everyone with world-class, feature-packed 
                        applications on Android and iOS for a fraction of the cost it takes to develop an app otherwise.</p>
                </div>
                <div class="payment_steps">
                    <ul>
                        <li class="active">
                            <em>1</em>
                            <span>Cart</span>
                            <hr noshade>
                        </li>
                        <li>
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
                            <span>Finalise</span>
                            <hr noshade>
                        </li>
                    </ul>
                </div>
                <div class="clear"></div>
                <div class="payment_box">

                    <div class="payment_left">
                        <?php
                        foreach ($palnprice as $key => $value) {
                            $x++;

                            $orderid = $value['masterpayment_orderid'];
                            ?>
                            <div class="payment_left_common app">
                                <div class="payment_left_common_head">
                                    <ul>
                                        <li>Order No. <?php echo $orderid; ?></li>
                                        <li>Term</li>
                                        <li><a class="deleteincart" data-mid="<?php echo $mpid; ?>" id="appid_<?php echo $x; ?>" data-appid ="<?php echo $value['app_id']; ?>"><i class="fa fa-trash"></i></a></li>
                                        <div class="clear"></div>
                                    </ul>
                                </div>
                                <div class="payment_left_common_body no_padding">
                                    <div class="package_select">
                                        <ul>
                                            <li>
                                                <?php
                                                $publishedappImage = $apps->publish_app_img($value['app_id']);
                                                if ($publishedappImage != '') {
                                                    echo '<img src="' . $publishedappImage . '" height="100px" width="100px">';
                                                } else {
                                                    echo '<img src="images/app_icon.jpg">';
                                                }
                                                ?>
                                                <div class="payment_app_name">
                                                    <h2> <?php
                                                        $catname = '';
                                                        $appName = $apps->app_name($value['app_id']);
                                                        if ($appName['summary'] != '') {
                                                            echo $appName['summary'];
                                                        }
                                                        if ($appName['category_name'] != '') {
                                                            $catname = $appName['category_name'];
                                                        }
                                                        ?></h2>
                                                    <h3>Category : <?php echo $catname; ?></h3>
                                                </div>
                                                <div class="clear"></div>
                                            </li>
                                            <li>
                                                <?php
                                                $planid = $mypricing->getplan($value['plan_id']);
                                                $years = (int) ($planid['validity_in_days'] / 365);

                                                if ($currency == 1) {
                                                    $totalSaving = $planid['total_saving'];
                                                } else {
                                                    $totalSaving = $planid['saving_in_usd'];
                                                }
                                                if ($years == 1) {
                                                    $selected1 = 'selected';
                                                } else if ($years == 2) {
                                                    $selected2 = 'selected';
                                                }
                                                ?>
                                                <select id="timePeriod_<?php echo $x; ?>">
                                                    <option value='1' <?php echo $selected1 ?>>1 Year</option>
                                                    <option value='2' <?php echo $selected2 ?>>2 Years</option>
                                                </select>
                                               <!-- <span class="discount">15% Off</span> -->
                                            </li>
                                            <li><?php echo $currencyIcon; ?> <div id="finalPrice_<?php echo $x; ?>" class="finalPrice"><?php echo round($value['total_amount'], 2); ?></div></li>
                                            <div class="clear"></div>
                                        </ul>
                                    </div>
                                    <div class="payment_app_files">
                                        <div class="android_files">
                                            <div class="payment_files_name">
                                                <div class="files_name_left">
                                                    <p>OS</p>
                                                    <?php
                                                    $platform = $value['platform'];
                                                    if ($platform == "3") {
                                                        $appAllowedS3 = 'selected';
                                                    } else if ($platform == "2") {
                                                        $appAllowedS2 = 'selected';
                                                    } else if ($platform == "1") {
                                                        $appAllowedS1 = 'selected';
                                                    }
                                                    ?>
                                                    <select id="appAllowed_<?php echo $x; ?>">
                                                        <option value="Android-iOS" <?php echo $appAllowedS3 ?>>Android + iOS</option>
                                                        <option value="iOS" <?php echo $appAllowedS2 ?>>iOS</option>
                                                        <option value="Android" <?php echo $appAllowedS1 ?>>Android</option>
                                                    </select>
                                                   <!--<span><label  id="percentDiscount">10</label >% Off</span> -->
                                                </div>
                                                <div class="files_name_right">
                                                    <div class="oldprice" style="color:red"></div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="payment_files_name">
                                                <div class="files_name_left">
                                                    <p>Package</p>
                                                    <?php
                                                    $planname = $planid['plan_type'];
                                                    if ($planname == '2') {
                                                        $selectedB = 'selected';
                                                    } else if ($planname == '3') {
                                                        $selectedA = 'selected';
                                                    }
                                                    ?>
                                                    <select id="packageType_<?php echo $x; ?>">
                                                        <option value="2" <?php echo $selectedB ?>>Basic</option>
                                                        <option value="3" <?php echo $selectedA ?>>Advanced</option>
                                                    </select>
                                                </div>

                                                <div class="clear"></div>
                                            </div>
                                            <?php
                                            $priceIS = $mypricing->getallappsIS($value['id']);
                                            foreach ($priceIS as $key2 => $values2) {
                                                $iconid = $mypricing->geticonPrice();
                                                $splashscreenid = $mypricing->getsplashscreen();
                                                if ($values2['payment_type_id'] == $splashscreenid) {
                                                    ?>
                                                    <div class="payment_files_name">
                                                        <div class="files_name_left">
                                                            <p>Splash Screen</p>
                                                        </div>
                                                        <div class="files_name_right">
                                                            <p><?php echo $currencyIcon; ?> <?php echo round($values2['amount'], 2); ?></p>
                                                            <input type="hidden" id="splashscreen_<?php echo $x; ?>" value="<?php echo round($values2['amount'], 2); ?>"/>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                <?php } if ($values2['payment_type_id'] == $iconid) { ?>
                                                    <div class="payment_files_name">
                                                        <div class="files_name_left">
                                                            <p>App Icon</p>
                                                        </div>
                                                        <div class="files_name_right">
                                                            <p><?php echo $currencyIcon; ?> <?php echo round($values2['amount'], 2); ?></p>
                                                            <input type="hidden" id="icon_<?php echo $x; ?>" value="<?php echo round($values2['amount'], 2); ?>"/>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="total_app_payment">
                                            <p><span><?php echo $currencyIcon; ?> <div id="apptotalPrice_<?php echo $x; ?>" class="finalPrice"><?php echo round($value['total_amount'], 2); ?></div></span>
                                                <input type="hidden" id="appsaving_<?php echo $x; ?>" value="<?php echo round($totalSaving, 2); ?>"/>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="paymentText">All basic app packages have 3rd party in app ads built in, that is how we get you world class, feature packed apps for a fraction of what it would take to develop an app other wise. If you would like to go ad free or integrate your own ads opt for the advanced package. You can upgrade from basic to advanced at any time during the year at the click of a button<br/><p>Our business model allows us to provide everyone with world-class, feature-packed applications on Android and iOS for a fraction of the cost it takes to develop an app otherwise.</p></div>

                            </div>

                        <?php } ?>

                        <!--market packages-->
                        <?php
                        foreach ($distinctmp as $key2 => $value2) {

                            $y++;
                            $mpid = $value2['payment_type_value'];
                            $mpamount = $value2['amount'];
                            $selectedappArray = array();
                            foreach ($mpackages as $key4 => $value4) {
                                $orderid = $value4['masterpayment_orderid'];
                                if ($mpid == $value4['payment_type_value']) {
                                    $selectedappArray[] = $value4['app_id'];
                                }
                            }
                            ?>
                            <div class="payment_left_common mp">
                                <div class="payment_left_common_head">
                                    <ul>
                                        <li>Order No. <?php echo $value4['masterpayment_orderid']; ?></li>
                                        <li> </li>
                                        <li><a class="deleteincart" id="mpackage_<?php echo $y; ?>" data-mid="<?php echo $mpid; ?>" data-appid=""><i class="fa fa-trash"></i></a></li>
                                        <div class="clear"></div>
                                    </ul>
                                </div>

                                <div class="payment_left_common_body no_padding">
                                    <div class="package_box">
                                        <div class="package_box_left">


                                            <p><?php
                                                $mpname = $mypricing->getMPprice($mpid);
                                                echo $mpname['name'];
                                                ?></p>
                                            <input type="hidden" value="<?php echo $mpid; ?>" id="mp_id_<?php echo $y; ?>"/>
                                        </div>
                                        <div class="package_box_right">

                                            <?php echo $currencyIcon; ?> <p class="mpackageINR" id="mptotal_<?php echo $y; ?>"><?php echo round($mpamount, 2); ?></p>
                                            <input type="hidden" value="<?php echo round($mpamount, 2); ?>" class="mpamount"/>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="selectdivBox">
                                        <p class="selectApptxt">Select App</p>
                                        <div class="selectdiv">
                                            <select multiple="multiple" class="appName" id="appName_<?php echo $y; ?>">
                                                <?php
                                                $countapp = count($selectedappArray);

                                                foreach ($paidapp as $key3 => $value3) {

                                                    $appname = $value3['AppName'];
                                                    $x = 0;
                                                    for ($z = 0; $z < $countapp; $z++) {

                                                        if ($value3['app_id'] == $selectedappArray[$z]) {
                                                            $option .= '<option value="' . $value3['app_id'] . '" selected>' . $appname . '</option>';
                                                            $x = 1;
                                                        }
                                                    }

                                                    if ($x == 0) {
                                                        $option .= '<option value="' . $value3['app_id'] . '">' . $appname . '</option>';
                                                    }
                                                }
                                                echo $option;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>

                            </div>

                            <?php
                            $option = '';
                        }
                        ?>
                        <!-- end market packages-->
                        <!-- discount-->
                        <div class="promo_code">
                            <div class="promo_code_left">
                                <label>Have a Promo Code?</label>
                            </div>
                            <div class="promo_code_right">

                                <input name="promocode" id="promocode" type="text">

                                <input type="submit" value="Apply" onclick="discount()">

                                <div class="clear"></div>
                            </div>
                            <div class="coupon_msg_box">
                                <p id="errorMsg1" style="display: none;" class="error_coupon_msg">Please enter a valid Promotional code.</p>
                                <p id="errorMsg2" style="display: none;" class="error_coupon_msg">Sorry, this offer has expired.</p>
                                <p id="errorMsg3" class="sucess_coupon_msg" style="display: none;" >Your code <span class="promocodeName"></span> is applied successfully.<br><?php echo $currencyIcon; ?> <span class="promocodePrice"></span> will be deducted from your sub total.<span class="delete_coupon deleteCoupon"><i class="fa fa-trash"></i></span></p>
                            </div>
                        </div>
                        <!-- end discount-->
                        <!-- subtotal-->
                        <div class="payment_final_price">
                            <div class="payment_final_price_inner">
                                <p class="total_cost">Sub Total <span id="finalPrice" class="finalPrice2"></span><span style="margin-right:5px;"><?php echo $currencyIcon; ?></span></p>
                                <p class="total_savings">Discount (if any) <span id="" class="total_saving tsaving">0.00</span><span style="margin-right:5px;"><?php echo $currencyIcon; ?></span></p>
                                
                                    <p class="total_savings">Service tax <span id="servicetax" class="total_saving">0.00</span><span style="margin-right:5px;"><?php echo $currencyIcon; ?></span></p>
                               
                                <p class="final_cost">Total cost <span id="finalPrice3" class="total_saving"></span><span style="margin-right:5px;"><?php echo $currencyIcon; ?></span></p>
                                <div class="clear"></div>
                                <a href="#" onclick="SetValues()">Proceed to Checkout ></a>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- end subtotal-->
                        <!-- promocode details-->
                        <div class="promo_code">
                            <div class="marketing_package">
                                <h2 class="head"> Recommended for you :</h2>
                                <div class="halfpackage package1">
                                    <div class="topHead"><img src="images/superdeal1.png"></div>
                                    <div class="readMoreSec">
                                        <p class="heading">App Launch ASO Plan</p>
                                        <p class="subheading"><span>App Store Optimisation, is possibly the single most important piece in launching your App. ASO is the process of optimizing mobile apps to rank higher in an app store’s search results. The higher your app ranks in an app store’s search results, the more visible it is to potential customers. That increased visibility tends to translate into more traffic to your app’s page in the app store. The goal of ASO is to drive more traffic to your app’s page in the app store, so searchers can take a specific action</span>
                                            <a href="javascript:void(0)" class="img-tog">Read more</a></p>
                                    </div>
                                    <div class="half_package_img">
                                        <img src="images/package-1.jpg" style="display:none">
                                        <img src="images/package-1-small.jpg">
                                    </div>
                                    <div class="addCartButton">
                                        <a class="mpackage" data-mpid="1" >Add to Cart</a>

                                    </div>

                                </div>

                                <div class="halfpackage package2">
                                    <div class="topHead"><img src="images/superdeal2.png"></div>
                                    <div class="readMoreSec">
                                        <p class="heading">Live ASO Plan</p>
                                        <p class="subheading"><span>If your app is already on the store but you missed the app store optimisation (ASO) all is not lost. You can still optimise your app for better visibility. ASO is the process of optimizing mobile apps to rank higher in an app store’s search results. The higher your app ranks in an app store’s search results, the more visible it is to potential customers. That increased visibility tends to translate into more traffic to your app’s page in the app store. The goal of ASO is to drive more traffic to your app’s page in the app store, so searchers can take a specific action</span>
                                            <a href="javascript:void(0)" class="img-tog">Read more</a></p>

                                    </div>
                                    <div class="half_package_img">
                                        <img src="images/package-2.jpg" style="display:none">
                                        <img src="images/package-2-small.jpg">
                                    </div>
                                    <div class="addCartButton">
                                        <a class="mpackage" data-mpid="2" >Add to Cart</a>

                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="fullpackage package3">
                                    <div class="topHead"><img src="images/superdeal3.png"></div>
                                    <div class="half_package_img">
                                        <div class="readMoreSec">
                                            <p class="heading">App Reviews</p>
                                            <p class="subheading">When you are launching a new app, naturally you want everyone in the world to see it! App review sites are a great way of gaining visibility, credibility and traction for your apps. Drive More Reviews & Installs Instantly. <a href="javascript:void(0)" class="img-tog">Read more</a></p>
                                        </div>
                                        <img src="images/package-3.jpg" alt="" style="display:none"/>
                                        <img src="images/package-3-small.jpg" alt="" />
                                    </div>
                                    <div class="addCartButtonBox">
                                        <div class="addCartButton">
                                            <a class="mpackage" data-mpid="3" >Add to Cart</a>
                                        </div>
                                        <div class="addCartButton">
                                            <a class="mpackage" data-mpid="4" >Add to Cart</a>
                                        </div>
                                        <div class="addCartButton">
                                            <a class="mpackage" data-mpid="5" >Add to Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="fullpackage package3">
                                    <div class="topHead"><img src="images/superdeal4.png"></div>
                                    <div class="half_package_img">
                                        <div class="readMoreSec">
                                            <p class="heading">App Downloads</p>
                                            <p class="subheading">Boost Your App Downloads, Target The RIGHT People So you need to target people from specific locations? No problem! We help you target and drive your app downloads from the audience of your choice. <a href="javascript:void(0)" class="img-tog">Read more</a></p>

                                        </div>
                                        <img src="images/package-4.jpg" alt="" style="display:none"/>
                                        <img src="images/package-4-small.jpg" alt=""/>
                                    </div>
                                    <div class="addCartButtonBox">
                                        <div class="addCartButton">
                                            <a class="mpackage" data-mpid="6" >Add to Cart</a>
                                        </div>
                                        <div class="addCartButton">
                                            <a class="mpackage" data-mpid="7" >Add to Cart</a>
                                        </div>
                                        <div class="addCartButton">
                                            <a class="mpackage" data-mpid="8" >Add to Cart</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="fullpackage package3">
                                    <div class="topHead"><img src="images/superdeal5.png"></div>
                                    <div class="half_package_img">
                                        <div class="readMoreSec">
                                            <p class="heading">Refurbishment of Content / Content Writing</p>
                                            <p class="subheading">Just writing is not enough, it's a science to infuse the right keywords which impact your App's indexing by Google and at the same time make your app content a joy to read. Let’s face it – not everyone can write.  Given the importance of rich and appealing content in the development of any application, it’s worth committing resources to ensure that the job is done well.<a href="javascript:void(0)" class="img-tog">Read more</a></p>
                                        </div>
                                        <img src="images/package-5.jpg" style="display:none" alt=""/>
                                        <img src="images/package-5-small.jpg" alt="" />
                                    </div>
                                    <div class="addCartButtonBox">
                                        <div class="addCartButton">
                                            <a class="mpackage" data-mpid="9" >Add to Cart</a>
                                        </div>
                                        <div class="addCartButton">
                                            <a class="mpackage" data-mpid="10">Add to Cart</a>
                                        </div>
                                        <div class="addCartButton">
                                            <a class="mpackage" data-mpid="11">Add to Cart</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="clear"></div>

                            </div>


                        </div>
                        <!-- end promocode details-->
                        <div id="ovr"></div>
                            <!--<span class="clk">Click here for disclaimer</span>-->
                        <span style="
                              font-size: 9px;
                              line-height: 14px;
                              color: #9d9d9d;
                              display: block;
                              margin: 5px 0;
                              ">Referral Code / coupon code discounts apply to new product purchases only.Not applicable to transaction fees, taxes, transfers, renewals or advertising budgets. Cannot be used in conjunction with any other offer, sale, discount or promotion. After the initial purchase term, discounted products will renew at the then-current renewal list price.Offers good towards new product purchases only and cannot be used on product renewals unless specifically mentioned on the coupon / offer. Savings based on Instappy.com's regular pricing.Annual discounts available on NEW purchases only.</span>
                        <div id="p_up">
                            <i>X</i>
                            <p>Referral Code / coupon code discounts apply to new product purchases only.Not applicable to transaction fees, taxes, transfers, renewals or advertising budgets. Cannot be used in conjunction with any other offer, sale, discount or promotion. After the initial purchase term, discounted products will renew at the then-current renewal list price.Offers good towards new product purchases only and cannot be used on product renewals unless specifically mentioned on the coupon / offer. Savings based on Instappy.com's regular pricing.Annual discounts available on NEW purchases only.</p>
                           <!-- <span class="closed">Close</span>-->
                        </div>
                        <!--                        <div class="next_step">
                                                    <a href="#" class="back_cart">< &nbsp;&nbsp;&nbsp;Back</a>
                                                    <div class="clear"></div>
                                                </div>-->
                    </div>
                    <!-- right side total-->
                    <div class="payment_right">
                        <div class="payment_right_common">
                            <div class="order_summary">
                                <div class="select_currency">
                                    <label>Total cost</label>
                                    <select class="my-select">
                                        <?php if ($currency == 1) { ?>
                                            <option data-img-src="images/ruppee_icon.png">INR</option>
                                        <?php } else { ?>
                                            <option data-img-src="images/dollar_icon.png">USD</option>
                                        <?php } ?>
                                    </select>
                                    <div class="clear"></div>
                                </div>
                                <p class="total_cost"><?php echo $currencyIcon; ?> <span id="finalPrice2" class="finalPrice2"></span></p>
                                <p id="tsave" class="total_savings">Total savings <span class="total_saving" id="totalSaving"></span><span style="margin-right:5px;"><?php echo $currencyIcon; ?></span></p>
                                <a href="#" onclick="SetValues()">Proceed to Checkout ></a>
                                <div class="clear"></div>
                            </div>

                        </div>
                    </div>
                    <!-- end right side total-->
                    <div class="clear"></div>
                </div>
            </div>
        </section>
    </section>
    </section>
    </body>
    <script>

    //        function round(value, exp) {
    //            if (typeof exp === 'undefined' || +exp === 0)
    //                return Math.round(value);
    //
    //            value = +value;
    //            exp = +exp;
    //
    //            if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    //                return NaN;
    //
    //            // Shift
    //            value = value.toString().split('e');
    //            value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));
    //
    //            // Shift back
    //            value = value.toString().split('e');
    //            return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    //        }

        function currency_check() {
            var currencyCh = <?php echo $currency; ?>;
            return currencyCh;
        }

        function getPrice() {

            //console.log($(this).attr("id"));
            $('#errorMsg3').hide();
            $(".tsaving").html('0');
            var tar = $(this).attr("id");
            var deleteCart = $(this).parents(".payment_left_common.app").find(".deleteincart").attr('data-appid');
            var suffix = tar.split("_");
            //     console.log(suffix[0]+'000'+suffix[1]);
            var selecttext = suffix[0];
            var selectno = suffix[1];

            var timePeriod = '';
            var packageType = '';
            var appAllowed = '';
            var priceValue = '';
            var savingTotal = '';
            var currencyCheck = currency_check();

            if (selecttext == 'timePeriod' || selecttext == 'packageType' || selecttext == 'appAllowed') {
                var currenttimePeriod = '#timePeriod_' + selectno;
                var currentpackageType = '#packageType_' + selectno;
                var currentappAllowed = '#appAllowed_' + selectno;

                timePeriod = $(currenttimePeriod).val();
                packageType = $(currentpackageType).val();
                appAllowed = $(currentappAllowed).val();

            }

            $.ajax({
                type: "POST",
                url: BASEURL + 'API/getPrice.php/getPrice',
                data: 'timePeriod=' + timePeriod + '&appAllowed=' + appAllowed + '&packageType=' + packageType + '&appid=' + deleteCart,
                dataType: 'json',
                success: function (response) {

                    if (currencyCheck == 1) {
                        priceValue = response[0].actual_price;
                        savingTotal = response[0].total_saving;
                    } else {
                        priceValue = response[0].price_in_usd;
                        savingTotal = response[0].saving_in_usd;
                    }
    //                    priceValue = response[0].actual_price;
    //                    savingTotal = response[0].total_saving;

                    var currentfinalPrice = '#finalPrice_' + selectno;
                    var currentapptotalPrice = '#apptotalPrice_' + selectno;
                    var currentappsaving = '#appsaving_' + selectno;
                    var topprice = priceValue;
                    $(currentfinalPrice).html(topprice);
                    var splashscreen = '#splashscreen_' + selectno;
                    var iconP = '#icon_' + selectno;
                    var ss = $(splashscreen).val();
                    var icon = $(iconP).val();

                    if (ss == undefined) {
                        ss = 0;
                    }
                    if (icon == undefined) {
                        icon = 0;
                    }
                    if (priceValue == undefined) {
                        priceValue = 0;
                    }

                    var apptoalPrice = (parseFloat(priceValue) + parseFloat(ss) + parseFloat(icon)).toFixed(2);

                    $(currentapptotalPrice).html(apptoalPrice);
                    $(currentappsaving).val(savingTotal);
                    calculate();
                },
            });

        }
        $(".deleteincart").click(function () {
            $('#errorMsg3').hide();
            $(".tsaving").html('0');
            var am_id = $(this).attr('id');
            var mpackage_id = $(this).attr('data-mid');
            var packageWappid = $(this).attr('data-appid');

            if (mpackage_id != '') {
                $.ajax({
                    type: "POST",
                    url: BASEURL + 'modules/pricing/deletefromcart.php',
                    data: 'mpid=' + mpackage_id + '&appid=' + packageWappid + '&appType=2' + '&token=<?php echo $token; ?>',
                    dataType: 'json',
                    success: function (response) {
                        if (response == 1) {
                            console.log(response);
                            $("#" + am_id).wrap('<div>').parents(".payment_left_common.mp").remove();
                            calculate();
                            //                            window.location = window.location;
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: BASEURL + 'modules/pricing/deletefromcart.php',
                    data: 'mpid=' + mpackage_id + '&appid=' + packageWappid + '&appType=1' + '&token=<?php echo $token; ?>',
                    dataType: 'json',
                    success: function (response) {
                        if (response == 1) {
                            console.log(response);
                            $("#" + am_id).wrap('<div>').parents(".payment_left_common.app").remove();
                            calculate();
                            //                            window.location = window.location;
                        }
                    }
                });
            }
        })

        function discount() {
            var promocode = document.getElementById("promocode").value;
            var totalamount = $("#finalPrice").html();
            $.ajax({
                type: "POST",
                url: BASEURL + 'modules/pricing/discountcheck.php',
                data: 'promocode=' + promocode + '&token=<?php echo $token; ?>' + '&od=<?php echo $orderid; ?>' + '&totalam=' + totalamount,
                success: function (response) {

                    if (response == 1) {
                        $('#errorMsg1').show();
                        $('#errorMsg2').hide();
                        $('#errorMsg3').hide();
                    } else if (response == 2) {
                        $('#errorMsg2').show();
                        $('#errorMsg1').hide();
                        $('#errorMsg3').hide();
                    } else if (response == 3) {
                        $('#errorMsg1').show();
                        $('#errorMsg2').hide();
                        $('#errorMsg3').hide();
                    } else {
                        var obj = JSON.parse(response);

                        $(".tsaving").html(obj.discount);
                        $("#finalPrice3").html(obj.afterdiscount);
                        $("#finalPrice2").html(obj.afterdiscount);
                        $(".promocodePrice").html(obj.discount);
                        $(".promocodeName").html(obj.coupon);
                        $('#errorMsg3').show();
                        $('#errorMsg2').hide();
                        $('#errorMsg1').hide();
                        //                    console.log(response);
                    }
                    calculate();
                }
            });

        }

        function finaltotal() {

            var total = $("#finalPrice").html();
            var totalsave = $("#totalSaving").html();
            var total_ad_sertax = $("#finalPrice2").html();
            var discount = $(".tsaving").html();
            $.ajax({
                type: "POST",
                url: BASEURL + 'modules/pricing/totalamount.php',
                data: 'token=<?php echo $token; ?>' + '&total=' + total + '&totalad=' + total_ad_sertax + '&od=<?php echo $orderid; ?>' + '&totalsave=' + totalsave + '&discount=' + discount,
                success: function (response) {
                    console.log(response);
                    if (response == 1) {
                        //                        window.location = window.location
                        window.location = BASEURL + 'payment_info.php';
                    } else {
                        $(".confirm_name .confirm_name_form").html('<p>Something went wrong.</p><input type="button" value="OK">');
                        $(".confirm_name").css({'display': 'block'});
                        $(".close_popup").css({'display': 'block'});
                        $(".popup_container").css({'display': 'block'});
                    }

                }
            });
        }


        function SetValues() {
            var totalapp = $("div[id^='finalPrice_']").length;
            if (totalapp > 0) {
                for (var i = 1; i <= totalapp; i++) {

                    var timePeriod = '#timePeriod_' + i;
                    var packageType = '#packageType_' + i;
                    var appAllowed = '#appAllowed_' + i;
                    var appid = '#appid_' + i;

                    var tp = $(timePeriod).val();
                    var pt = $(packageType).val();
                    var aa = $(appAllowed).val();
                    var ai = $(appid).attr('data-appid');



                    $.ajax({
                        type: "POST",
                        url: BASEURL + 'modules/pricing/s_price.php',
                        data: 'timeperiod=' + tp + '&appAllowed=' + aa + '&packageType=' + pt + '&appid=' + ai + '&token=<?php echo $token; ?>',
                        success: function (response) {
                            console.log(response);
                            var totalmp = $("select[id^='appName_']").length;
                            if (totalmp > 0) {

                                for (var y = 1; y <= totalmp; y++) {
                                    var appname = '#appName_' + y;
                                    var mpID = '#mp_id_' + y;
                                    var sapp = $(appname).val();
                                    var mp_ID = $(mpID).val();
                                    $.ajax({
                                        type: "POST",
                                        url: BASEURL + 'modules/pricing/mp_price.php',
                                        data: 'appselected=' + sapp + '&token=<?php echo $token; ?>' + '&mpid=' + mp_ID,
                                        success: function (response) {
                                            console.log(response);

                                        }
                                    });
                                }

                                finaltotal();
                            }
                            else {
                                finaltotal();
                            }

                        }
                    });
                }
            } else {
                var totalmp = $("select[id^='appName_']").length;
                if (totalmp > 0) {
                    for (var y = 1; y <= totalmp; y++) {
                        var appname = '#appName_' + y;
                        var mpID = '#mp_id_' + y;
                        var sapp = $(appname).val();
                        var mp_ID = $(mpID).val();
                        $.ajax({
                            type: "POST",
                            url: BASEURL + 'modules/pricing/mp_price.php',
                            data: 'appselected=' + sapp + '&token=<?php echo $token; ?>' + '&mpid=' + mp_ID,
                            success: function (response) {
                                console.log(response);

                            }
                        });
                    }

                    finaltotal();
                }
                else {
                    finaltotal();
                }
            }

        }


        function calculate() {
            var mp = '0';
            var appprice = '0';
            var apptotalsaving = '0';
            var currencyCheck = currency_check();

            var totalclassmp = $("p[id^='mptotal_']").length;
            for (var y = 1; y <= totalclassmp; y++) {

                var mptotal = '#mptotal_' + y;
                if ($(mptotal).length != 0) {
                    mp = (parseFloat(mp) + parseFloat($(mptotal).html())).toFixed(2);
                } else {
                    totalclassmp = parseFloat(totalclassmp + 1);
                }
            }
            var totalappsave = $("input[id^='appsaving_']").length;
            for (var y = 1; y <= totalappsave; y++) {

                var appsaving = '#appsaving_' + y;
                if ($(appsaving).length != 0) {
                    apptotalsaving = (parseFloat(apptotalsaving) + parseFloat($(appsaving).val())).toFixed(2);
                } else {
                    totalappsave = parseFloat(totalappsave + 1);
                }
            }


            var totalclassapp = $("div[id^='apptotalPrice_']").length;
            for (var y = 1; y <= totalclassapp; y++) {

                var apptotal = '#apptotalPrice_' + y;
                if ($(apptotal).length != 0) {
                    appprice = (parseFloat(appprice) + parseFloat($(apptotal).html())).toFixed(2);
                } else {
                    totalclassapp = parseFloat(totalclassapp + 1);
                }
            }

            var totalT = (parseFloat(appprice) + parseFloat(mp)).toFixed(2);

            var discountS = $(".tsaving").html();

            if ((discountS != '') && (discountS != '0')) {


                var afterdiscount = (parseFloat(totalT) - parseFloat(discountS)).toFixed(2);

               
                    var totalpay = (parseFloat(afterdiscount) * 14 / 100).toFixed(2);
                    var totalamountpay = (parseFloat(afterdiscount) + parseFloat(totalpay)).toFixed(2);
                 
    //                var totalpay = (parseFloat(afterdiscount) * 14 / 100).toFixed(2);
    //                var totalamountpay = (parseFloat(afterdiscount) + parseFloat(totalpay)).toFixed(2);
                $("#servicetax").html(totalpay);
                $("#finalPrice").html(totalT);
                $("#finalPrice3").html(totalamountpay);
                $("#finalPrice2").html(totalamountpay);
                if (apptotalsaving != 0) {
                    $("#tsave").show();
                    $("#totalSaving").html(apptotalsaving);
                } else {
                    $("#tsave").hide();
                }


            } else {
                
                    var totalp = (parseFloat(totalT) * 14 / 100).toFixed(2);
                    var totalsertax = (parseFloat(totalT) + parseFloat(totalp)).toFixed(2);
                

    //                var totalp = (parseFloat(totalT) * 14 / 100).toFixed(2);
    //                var totalsertax = (parseFloat(totalT) + parseFloat(totalp)).toFixed(2);
                $("#servicetax").html(totalp);
                $("#finalPrice").html(totalT);
                $("#finalPrice3").html(totalsertax);
                $("#finalPrice2").html(totalsertax);
                if (apptotalsaving != 0) {
                    $("#tsave").show();
                    $("#totalSaving").html(apptotalsaving);
                } else {
                    $("#tsave").hide();
                }

            }

        }


        $(document).ready(function () {

            var totalclass = $("div[id^='finalPrice_']").length;
            for (var i = 1; i <= totalclass; i++) {

                var splashs = '#splashscreen_' + i;
                var iconPrice = '#icon_' + i;
                var finalPr = '#finalPrice_' + i;
                var apptotalPr = '#apptotalPrice_' + i;
                var splashs = $(splashs).val();
                var iconsP = $(iconPrice).val();
                var finaltopP = $(finalPr).html();

                if (splashs == undefined) {
                    splashs = 0;
                }
                if (iconsP == undefined) {
                    iconsP = 0;
                }
                if (finaltopP == undefined) {
                    finaltopP = 0;
                }
                var gettotal = (parseFloat(finaltopP) + parseFloat(splashs) + parseFloat(iconsP)).toFixed(2);
                $(apptotalPr).html(gettotal);
            }

            calculate();

            $(document).on("change", ".app select", getPrice);

            $(".mpackage").click(function () {
                var mpid = $(this).attr("data-mpid");
                $.ajax({
                    type: "POST",
                    url: BASEURL + 'modules/pricing/packageaddtocart.php',
                    data: 'packageid=' + mpid + '&token=<?php echo $token; ?>',
                    success: function (response) {
                        if (response == 1) {
                            $(".confirm_name .confirm_name_form").html('<p>Create App first.</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(".close_popup").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                        } else if (response == 2) {
                            console.log("Already Added");
                            $(".confirm_name .confirm_name_form").html('<p>Already Added</p><input type="button" value="OK">');
                            $(".confirm_name").css({'display': 'block'});
                            $(".close_popup").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            return false;
                        } else if(response == 4){
                            var totalclassmp = 0;
                            var totalclassmp2 = 0;
                            var totalclassmp3 = $("p[id^='mptotal_']").length;
                            if (totalclassmp3 > 0) {
                                totalclassmp2 = $("p[id^='mptotal_']").last().prop("id").split("_")[1];
                                totalclassmp = parseFloat(totalclassmp2) + parseFloat(1);

                            } else {
                                totalclassmp = 1;
                            }

                            $.ajax({
                                type: "POST",
                                url: BASEURL + 'modules/pricing/addmp.php',
                                data: 'mpid=' + mpid + '&existmp=' + totalclassmp + '&token=<?php echo $token; ?>',
                                success: function (response) {
                                    if (response == 2) {
                                        $(".confirm_name .confirm_name_form").html('<p>Not Added. Try again.</p><input type="button" value="OK">');
                                        $(".confirm_name").css({'display': 'block'});
                                        $(".close_popup").css({'display': 'block'});
                                        $(".popup_container").css({'display': 'block'});
                                        return false;
                                    } else if (response == 3) {
                                        console.log("Already Added");
                                        $(".confirm_name .confirm_name_form").html('<p>Already Added</p><input type="button" value="OK">');
                                        $(".confirm_name").css({'display': 'block'});
                                        $(".close_popup").css({'display': 'block'});
                                        $(".popup_container").css({'display': 'block'});
                                        return false;
                                    } else {
                                        var totalclassmp3 = $(".payment_left_common.mp").length;
                                        var totalclassmp4 = $(".payment_left_common.app").length;
                                        if (totalclassmp3 != 0) {
                                            $(".payment_left_common.mp:last").after(response);
                                            $('div.selectdiv select.appName').multipleSelect();
                                            $('#errorMsg3').hide();
                                            $(".tsaving").html('0');
                                            calculate();
                                            $(".deleteincart").click(function () {

                                                var am_id = $(this).attr('id');
                                                var mpackage_id = $(this).attr('data-mid');
                                                var packageWappid = $(this).attr('data-appid');

                                                if (mpackage_id != '') {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: BASEURL + 'modules/pricing/deletefromcart.php',
                                                        data: 'mpid=' + mpackage_id + '&appid=' + packageWappid + '&appType=2' + '&token=<?php echo $token; ?>',
                                                        dataType: 'json',
                                                        success: function (response) {
                                                            if (response == 1) {
                                                                console.log(response);
                                                                $("#" + am_id).wrap('<div>').parents(".payment_left_common.mp").remove();
                                                                $('#errorMsg3').hide();
                                                                $(".tsaving").html('0');
                                                                calculate();

                                                            }
                                                        }
                                                    });
                                                } else {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: BASEURL + 'modules/pricing/deletefromcart.php',
                                                        data: 'mpid=' + mpackage_id + '&appid=' + packageWappid + '&appType=1' + '&token=<?php echo $token; ?>',
                                                        dataType: 'json',
                                                        success: function (response) {
                                                            if (response == 1) {
                                                                console.log(response);
                                                                $("#" + am_id).wrap('<div>').parents(".payment_left_common.app").remove();
                                                                $('#errorMsg3').hide();
                                                                $(".tsaving").html('0');
                                                                calculate();

                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                            $(".ms-parent.appName").click(function () {
                                                var mp_id = $(this).prev('select').val();

                                                if (mp_id != null)
                                                {
                                                    var mdlength = mp_id.length;
                                                    var amount = (parseFloat($(this).parents(".payment_left_common.mp").find(".package_box_right .mpamount").val())).toFixed(2);
                                                    //                var amount=parseFloat($(this).parents(".payment_left_common.mp").find(".package_box_right p").text());
                                                    $(this).parents(".payment_left_common.mp").find(".package_box_right p").text(amount * parseFloat(mdlength));
                                                    calculate();

                                                } else {
                                                    $(this).parents(".payment_left_common.mp").find(".package_box_right p").text('0.00');
                                                    calculate();
                                                }

                                            });
                                        } else if (totalclassmp4 != 0) {
                                            $(".payment_left_common.app:last").after(response);
                                            $('div.selectdiv select.appName').multipleSelect();
                                            calculate();
                                            $(".deleteincart").click(function () {

                                                var am_id = $(this).attr('id');
                                                var mpackage_id = $(this).attr('data-mid');
                                                var packageWappid = $(this).attr('data-appid');

                                                if (mpackage_id != '') {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: BASEURL + 'modules/pricing/deletefromcart.php',
                                                        data: 'mpid=' + mpackage_id + '&appid=' + packageWappid + '&appType=2' + '&token=<?php echo $token; ?>',
                                                        dataType: 'json',
                                                        success: function (response) {
                                                            if (response == 1) {
                                                                console.log(response);
                                                                $("#" + am_id).wrap('<div>').parents(".payment_left_common.mp").remove();
                                                                $('#errorMsg3').hide();
                                                                $(".tsaving").html('0');
                                                                calculate();

                                                            }
                                                        }
                                                    });
                                                } else {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: BASEURL + 'modules/pricing/deletefromcart.php',
                                                        data: 'mpid=' + mpackage_id + '&appid=' + packageWappid + '&appType=1' + '&token=<?php echo $token; ?>',
                                                        dataType: 'json',
                                                        success: function (response) {
                                                            if (response == 1) {
                                                                console.log(response);
                                                                $("#" + am_id).wrap('<div>').parents(".payment_left_common.app").remove();
                                                                $('#errorMsg3').hide();
                                                                $(".tsaving").html('0');
                                                                calculate();

                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                            $(".ms-parent.appName").click(function () {
                                                var mp_id = $(this).prev('select').val();

                                                if (mp_id != null)
                                                {
                                                    var mdlength = mp_id.length;
                                                    var amount = (parseFloat($(this).parents(".payment_left_common.mp").find(".package_box_right .mpamount").val())).toFixed(2);
                                                    //                var amount=parseFloat($(this).parents(".payment_left_common.mp").find(".package_box_right p").text());
                                                    $(this).parents(".payment_left_common.mp").find(".package_box_right p").text(amount * parseFloat(mdlength));
                                                    $('#errorMsg3').hide();
                                                    $(".tsaving").html('0');
                                                    calculate();

                                                } else {
                                                    $(this).parents(".payment_left_common.mp").find(".package_box_right p").text('0.00');
                                                    $('#errorMsg3').hide();
                                                    $(".tsaving").html('0');
                                                    calculate();
                                                }

                                            });
                                        } else {
                                            $(".payment_left").prepend(response);
                                            $('div.selectdiv select.appName').multipleSelect();
                                            calculate();
                                            $(".deleteincart").click(function () {

                                                var am_id = $(this).attr('id');
                                                var mpackage_id = $(this).attr('data-mid');
                                                var packageWappid = $(this).attr('data-appid');

                                                if (mpackage_id != '') {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: BASEURL + 'modules/pricing/deletefromcart.php',
                                                        data: 'mpid=' + mpackage_id + '&appid=' + packageWappid + '&appType=2' + '&token=<?php echo $token; ?>',
                                                        dataType: 'json',
                                                        success: function (response) {
                                                            if (response == 1) {
                                                                console.log(response);
                                                                $("#" + am_id).wrap('<div>').parents(".payment_left_common.mp").remove();
                                                                $('#errorMsg3').hide();
                                                                $(".tsaving").html('0');
                                                                calculate();

                                                            }
                                                        }
                                                    });
                                                } else {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: BASEURL + 'modules/pricing/deletefromcart.php',
                                                        data: 'mpid=' + mpackage_id + '&appid=' + packageWappid + '&appType=1' + '&token=<?php echo $token; ?>',
                                                        dataType: 'json',
                                                        success: function (response) {
                                                            if (response == 1) {
                                                                console.log(response);
                                                                $("#" + am_id).wrap('<div>').parents(".payment_left_common.app").remove();
                                                                $('#errorMsg3').hide();
                                                                $(".tsaving").html('0');
                                                                calculate();

                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                            $(".ms-parent.appName").click(function () {
                                                var mp_id = $(this).prev('select').val();

                                                if (mp_id != null)
                                                {
                                                    var mdlength = mp_id.length;
                                                    var amount = (parseFloat($(this).parents(".payment_left_common.mp").find(".package_box_right .mpamount").val())).toFixed(2);
                                                    //                var amount=parseFloat($(this).parents(".payment_left_common.mp").find(".package_box_right p").text());
                                                    $(this).parents(".payment_left_common.mp").find(".package_box_right p").text(amount * parseFloat(mdlength));
                                                    $('#errorMsg3').hide();
                                                    $(".tsaving").html('0');
                                                    calculate();

                                                } else {
                                                    $(this).parents(".payment_left_common.mp").find(".package_box_right p").text('0.00');
                                                    $('#errorMsg3').hide();
                                                    $(".tsaving").html('0');
                                                    calculate();
                                                }

                                            });
                                        }
                                    }
                                }
                            });

                        }
                    }
                });
            });

            $(".deleteCoupon").click(function () {

                $.ajax({
                    type: "POST",
                    url: BASEURL + 'modules/pricing/coupondelete.php',
                    data: 'od=<?php echo $orderid; ?>' + '&token=<?php echo $token; ?>',
                    success: function (response) {
                        if (response == 1) {
                            $("#promocode").val('');
                            $('#errorMsg1').hide();
                            $('#errorMsg2').hide();
                            $('#errorMsg3').hide();
                            $(".tsaving").html('0');
                            calculate();
                        } else {
                            console.log(response);
                        }
                    }
                });


            });

            //if($(".mp .ms-choice").not('.open')){
            //    var mp_id = $(this).parent().prev('select').val();
            //    alert(mp_id);
            //}
            $(".ms-parent.appName").click(function () {
                var mp_id = $(this).prev('select').val();

                if (mp_id != null)
                {
                    var mdlength = mp_id.length;
                    var amount = (parseFloat($(this).parents(".payment_left_common.mp").find(".package_box_right .mpamount").val())).toFixed(2);
                    //                var amount=parseFloat($(this).parents(".payment_left_common.mp").find(".package_box_right p").text());
                    $(this).parents(".payment_left_common.mp").find(".package_box_right p").text(amount * parseFloat(mdlength));
                    calculate();

                } else {
                    $(this).parents(".payment_left_common.mp").find(".package_box_right p").text('0.00');
                    calculate();
                }

            });

            $(".confirm_name input").click(function () {
                $(".confirm_name").css({'display': 'none'});
                $(".popup_container").css({'display': 'none'});
            });

            $(".payment_files_name .files_name_right span").click(function () {
                if ($(this).is(':empty')) {
                    $(this).parent().parent().addClass("item_disabled"); //line 2125
                    $(this).css("background", "none").append("<input type='checkbox'>");
                } else {
                    $(this).parent().parent().removeClass("item_disabled");
                    $(this).css("background", "url('images/menu_delete.png')");
                    $(this).children("input").remove();
                }
            });
            $(".stats_download a").click(function () {
                $(this).next().toggleClass("show_pop");
                $(this).parent().siblings().children("div").removeClass("show_pop");
            });
            $(document).click(function () {
                $(".stats_download a + div").removeClass("show_pop");
            });
            $('.stats_download a').on('click', function (e) {
                e.stopPropagation();
            });
            $('.stats_download_tooltip').on('click', function (e) {
                e.stopPropagation();
            });

            /*var rightHeight = $(window).height() - 45;
             $(".right_main").css("height", rightHeight + "px")*/
        });


    </script>

    <script>
        $(document).ready(function () {

            $('.img-tog').click(function () {
                var img_visb = $(this).parents('.halfpackage , .half_package_img').find('img').not('.topHead img');
                $(img_visb).toggle();
                var realHeight = $(this).siblings()[0].scrollHeight;
                if ($(this).siblings().height() == 57) {
                    $(this).siblings().animate({
                        height: realHeight
                    });
                } else {
                    $(this).siblings().animate({
                        height: 57
                    });
                }
            });

            $('.clk').click(function () {
                $('#ovr , #p_up').fadeIn();
            });

            $('#ovr , #p_up i , .closed').click(function () {
                $('#ovr , #p_up').fadeOut();
            });

            $('.app_select_box .open_app_box').on('click', function () {
                $('.app_select_box_drop').toggle();
            });
            $('.app_select_box .open_app_box').on('focus', function () {
                $(this).blur();
            });
            $('.app_select_box_drop ul li input:checkbox').on('click', function () {
                if ($(this).prop('checked') == true) {
                    $('.app_select_box').find('.open_app_box').val($('.app_select_box').find('.open_app_box').val() + $(this).siblings('label').text() + ',');
                }
                else {
                    $('.app_select_box').find('.open_app_box').val($('.app_select_box').find('.open_app_box').val().replace($(this).siblings('label').text() + ',', ""));

                }

            });
        })
    </script>
    <link href="css/multiple-select.css" rel="stylesheet"/>
    <script src="js/jquery.multiple.select.js"></script>
    <script>

        //    $(document).on("change", ".mp select", getPrice);
        $('div.selectdiv select.appName').multipleSelect({placeholder: "Select App"});

        $(window).load(function () {
    //            $('div.selectdiv select.appName').multipleSelect("refresh");
            $(".ms-parent.appName").trigger('click');
        });

    </script>
    <?php
} else {
    ?>
    <script> window.location = "<?php echo $basicUrl . 'emptyCart.php'; ?>";</script>
<?php }
?>
</html>
