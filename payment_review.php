<script>
    window.onload = function () {
        var d = new Date().getTime();
        document.getElementById("tid").value = d;
    };
</script>
<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');

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
require_once('modules/pricing/pricing.php');
$custid = $_SESSION['custid'];
$mypricing = new mypricing();
require_once('modules/myapp/myapps.php');
$apps = new MyApps();
$totalPrice = '';
$total_saving = '';
if (isset($_SESSION['totalPrice'])) {
    $totalPrice = $_SESSION['totalPrice'];
}

if (isset($_SESSION['total_saving'])) {
    $total_saving = $_SESSION['total_saving'];
}
if (isset($_SESSION['orderid'])) {
    $orderid = $_SESSION['orderid'];
}

if (isset($_SESSION['custid'])) {
    require_once('modules/user/userprofile.php');
    $user = new UserProfile();
    $user_data = $user->getUserByCustidOrder($_SESSION['custid'], $orderid);
    /* echo "<pre>"; print_r($user_data); echo "</pre>"; */
}

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
                        <span>Finalise</span>
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
                                    <p><?php echo $user_data['first_name'] . ' ' . $user_data['last_name']; ?><br>
                                        <?php echo $user_data['address'] ?><br>
                                        <?php echo $user_data['city'] ?><br>
                                        <?php echo $user_data['state'] ?><br>
                                        <?php echo $user_data['country'] ?><br>
                                        <?php echo $user_data['zip'] ?><br>
                                        <?php echo $user_data['email_address'] ?><br>
                                        <?php echo $user_data['phone'] ?></p>
                                    <a href="payment_info.php"><i class="fa fa-pencil"></i> Edit Billing Information</a>
                                </div>
                                <!--                            	<div class="billing_payment_inner">
                                                                        <h2>Payment Information</h2>
                                                                    <p>Net Banking</p>
                                                                    <a href="#"><i class="fa fa-pencil"></i> Edit Payment Information</a>
                                                                </div>-->
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>

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
                                        <li><a href="payment_details.php" class="deleteincart"  id="appid_<?php echo $x; ?>"><i class="fa fa-pencil"></i></a></li>
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
                                            if ($value['is_custom'] == 0 && $value['app_customization'] == 0) {
                                                ?>
													<?php													
														$years = (int) ($planid['validity_in_days'] / 365);
														
														$totalSaving = $planid['total_saving'];
														
														if ($years == 1) {
															$selected1 = 'selected';
															} else if ($years == 2) {
															$selected2 = 'selected';
														}
													?>
													<select class="years_click" id="timePeriod_<?php echo $x; ?>" disabled="disabled">
														<option value='1' <?php echo $selected1 ?>>1 Year</option>
														<!--<option value='2' <?php //echo $selected2 ?>>2 Years</option>-->
													</select>
													<input type="hidden" name="plan_custom" id="plan_custom_<?php echo $x; ?>" value="0">
												<?php }?>
												<?php if($value['is_custom']==1){?>	
                                                <?php
                                                if ($mypricing->custom_pkg('pkg_time', $value['app_id']) > 1)
                                                    echo '<input type="hidden" name="timePeriod_' . $x . '" id="timePeriod_' . $x . '" value="' . $mypricing->custom_pkg('pkg_time', $value['app_id']) . '">' . $mypricing->custom_pkg('pkg_time', $value['app_id']) . " Days";
                                                else
                                                    echo '<input type="hidden" name="timePeriod_' . $x . '" id="timePeriod_' . $x . '" value="' . $mypricing->custom_pkg('pkg_time', $value['app_id']) . '">' . $mypricing->custom_pkg('pkg_time', $value['app_id']) . " Day";
                                                ?> 
													<input type="hidden" name="plan_custom" id="plan_custom_<?php echo $x; ?>" value="1">
													
												<?php }?>
                                            <?php
                                            if ($value['app_customization'] == 1) {
													$years = (int) ($planid['validity_in_days'] / 365);
													
													$totalSaving = $planid['total_saving'];
													
													if ($years == 1) {
														$selected = '1 Year';
														} else if ($years == 2) {
														$selected = '2 Years';
													}
													
												?>	
												<?php echo $selected;?> 
												<?php }?>
                                                
                                               <!-- <span class="discount">15% Off</span> -->
                                            </li>
                                        <?php
                                        if($value['is_partpayment']==0){
                                        if ($value['crm_discount'] > 0) {
                                            $priceS = 0;
                                            $priceI = 0;
                                            $priceIS = $mypricing->getallappsIS($value['id']);
                                            foreach ($priceIS as $key2 => $values2) {
                                                $iconid = $mypricing->geticonPrice();
                                                $splashscreenid = $mypricing->getsplashscreen();
                                                if ($values2['payment_type_id'] == $splashscreenid) {
                                                    $splash_custom = $values2['is_custom'];
                                                    $splasscreen = $mypricing->getsplashscreenCurrency($values2['payment_type_value'], $value['app_id'], $values2['is_custom']);
                                                    if ($currency == 1) {
                                                        $priceS = $splasscreen['price'];
                                                    } else {
                                                        $priceS = $splasscreen['price_in_usd'];
                                                    }
                                                } if ($values2['payment_type_id'] == $iconid) {
                                                    $icon_custom = $values2['is_custom'];
                                                    $geticonlinkAD = $mypricing->geticonlink($value['app_id']);
                                                    $iconPrice = $mypricing->geticonCurrency($geticonlinkAD['app_image'], $values2['is_custom'], $values2['payment_type_value']);
                                                    if ($currency == 1) {
                                                        $priceI = $iconPrice['price'];
                                                    } else {
                                                        $priceI = $iconPrice['price_in_usd'];
                                                    }
                                                }
                                            }
                                            $appIconSS = $priceS + $priceI;
                                            $appIconSSDisc = $appIconSS - ($appIconSS * $value['crm_discount'] / 100);
                                            $total_amountIS = $value['total_amount'];
                                            $totalAmountWithoutDiscount = $total_amountIS - $appIconSSDisc;
                                            $totalwithISS = $appIconSSDisc + $total_amountIS;
                                        } else {
                                            $priceS = 0;
                                            $priceI = 0;
                                            $priceIS = $mypricing->getallappsIS($value['id']);
                                            foreach ($priceIS as $key2 => $values2) {
                                                $iconid = $mypricing->geticonPrice();
                                                $splashscreenid = $mypricing->getsplashscreen();
                                                if ($values2['payment_type_id'] == $splashscreenid) {
                                                    $splash_custom = $values2['is_custom'];
                                                    $splasscreen = $mypricing->getsplashscreenCurrency($values2['payment_type_value'], $value2['is_default_splash'], $values2['is_custom']);
                                                    if ($currency == 1) {
                                                        $priceS = $splasscreen['price'];
                                                    } else {
                                                        $priceS = $splasscreen['price_in_usd'];
                                                    }
                                                } if ($values2['payment_type_id'] == $iconid) {
                                                    $icon_custom = $values2['is_custom'];
                                                    $geticonlinkAD = $mypricing->geticonlink($value['app_id']);
                                                    $iconPrice = $mypricing->geticonCurrency($geticonlinkAD['app_image'], $values2['is_custom'], $values2['payment_type_value']);
                                                    if ($currency == 1) {
                                                        $priceI = $iconPrice['price'];
                                                    } else {
                                                        $priceI = $iconPrice['price_in_usd'];
                                                    }
                                                }
                                            }
                                            $appIconSS = $priceS + $priceI;
                                            $totalAmountWithoutDiscount=$value['total_amount'];
                                            $totalwithISS = $value['total_amount'] + $appIconSS;
                                        }
                                        } else{
                                            $priceS = 0;
                                            $priceI = 0;
                                            $priceIS = $mypricing->getallappsIS($value['id']);
                                            foreach ($priceIS as $key2 => $values2) {
                                                $iconid = $mypricing->geticonPrice();
                                                $splashscreenid = $mypricing->getsplashscreen();
                                                if ($values2['payment_type_id'] == $splashscreenid) {
                                                    $splash_custom = $values2['is_custom'];
                                                    $splasscreen = $mypricing->getsplashscreenCurrency($values2['payment_type_value'], $values2['is_default_splash'], $values2['is_custom']);
                                                    if ($currency == 1) {
                                                        $priceS = $splasscreen['price'];
                                                    } else {
                                                        $priceS = $splasscreen['price_in_usd'];
                                                    }
                                                } if ($values2['payment_type_id'] == $iconid) {
                                                    $icon_custom = $values2['is_custom'];
                                                    $geticonlinkAD = $mypricing->geticonlink($value['app_id']);
                                                    $iconPrice = $mypricing->geticonCurrency($geticonlinkAD['app_image'], $values2['is_custom'], $values2['payment_type_value']);
                                                    if ($currency == 1) {
                                                        $priceI = $iconPrice['price'];
                                                    } else {
                                                        $priceI = $iconPrice['price_in_usd'];
                                                    }
                                                }
                                            }
                                            $appIconSS = $priceS + $priceI;
                                            $totalAmountWithoutDiscount=$value['total_amount'];
                                            $totalwithISS = $value['total_amount'] + $appIconSS;
                                        }
                                        $results_part = $mypricing->partpayment_app($value['masterpayment_id'], $value['app_id']);
                                        ?>
                                        <li><?php echo $currencyIcon; ?> <div id="finalPrice_<?php echo $x; ?>" class="finalPrice"><?php echo round($totalAmountWithoutDiscount, 2); ?></div>
												<?php if(count($results_part)>0){?><div class="app-payment-first"><span>(Advance 1) <br>Part Payment</span><input type="hidden" value="1" id="part_payment_<?php echo $x; ?>"></div><?php }?>
												</li>
                                            <div class="clear"></div>
                                        </ul>
                                    </div>
                                    <div class="payment_app_files">
                                        <div class="android_files">
												<?php
                                        if (count($results_part) > 0) {
                                            ?>
												
												
												
												<div class="payment-part">
													<div class="pay-table">
														<ul>
															<li>
																<span>Part Payment Due</span>
																<span>Amount</span>
																<span>Due Date</span>
															</li>
															<?php
																$kk=1;
																foreach($results_part as $val){
																if($kk==1){
                                                                
                                                            } else {
																	$date = new DateTime($val['paymentdate']);
                                                                //$date->modify('+15 days');
                                                                ?>
																<li>
																	<span>Advance <?php echo $kk;?></span>
																	<span><?php echo $currencyIcon; ?>  <?php echo $val['part_amount']; ?></span>
																	<span><?php echo $date->format('d/m/Y');?></span>
																</li>
																<?php 
																	
																	}
																	$kk++;	
																}
															?>
														</ul>
													</div>
												</div>
											<?php }?>
                                            <div class="payment_files_name">
                                                <div class="files_name_left">
                                                    <p>OS</p>
													<?php if($value['is_custom']==0 && $value['app_customization']==0 ){?>
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
                                                    <select id="appAllowed_<?php echo $x; ?>" disabled="disabled">
															<option value="Android-iOS" <?php echo $appAllowedS3 ?>>Android + iOS</option>
															<option value="iOS" <?php echo $appAllowedS2 ?>>iOS</option>
															<option value="Android" <?php echo $appAllowedS1 ?>>Android</option>
														</select>
													<?php }?>
													<?php if($value['is_custom']==1){?>													
														<?php if($mypricing->custom_pkg('os',$value['app_id'])==1) echo "Android".'<input type="hidden" value="Android" id="appAllowed_'.$x.'">';?>
														<?php if($mypricing->custom_pkg('os',$value['app_id'])==2) echo "iOS".'<input type="hidden" value="iOS" id="appAllowed_'.$x.'">';?>
														<?php if($mypricing->custom_pkg('os',$value['app_id'])==3) echo "Android + iOS".'<input type="hidden" value="Android-iOS" id="appAllowed_'.$x.'">';?>
														
													<?php }?>
                                                <?php
                                                if ($value['app_customization'] == 1) {
														$platform = $value['platform'];
														if ($platform == "3") {
															$appAllowedS = 'Android + iOS';
															} else if ($platform == "2") {
															$appAllowedS= 'iOS';
															} else if ($platform == "1") {
															$appAllowedS = 'Android';
														}
													?>	
													
													<?php echo $appAllowedS;?>														
													<?php }?>
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
													<?php if($value['is_custom']==0 && $value['app_customization']==0 ){?>	                                                   
														<?php
															$str='';
															$planname = $planid['plan_type'];
															$valid_plans = $mypricing->app_plans($currency,$planname,$value['app_id']);
															foreach($valid_plans as $val_plan){
																if($val_plan->id==$planname){															
																	$select='selected="selected"';
																}
																$str.='<option value="'.$val_plan->id.'" '.$select.' >'.$val_plan->plan_name.'</option>';
																$select='';
															}
															
															if ($planname == '2') {
																$selectedB = 'selected';
																} else if ($planname == '3') {
																$selectedA = 'selected';
															}
														?>
														<select id="packageType_<?php echo $x; ?>" disabled="disabled">
															<?php echo $str; ?>
														</select>
													<?php }?>
													<?php if($value['is_custom']==1){?>	
														<div><?php echo $mypricing->custom_pkg('pkg_name',$value['app_id']);?><input type="hidden" value="4" id="packageType_<?php echo $x; ?>"></div>
													<?php }?>
													
                                                <?php
                                                if ($value['app_customization'] == 1) {
														$planname = $planid['plan_type'];
														if ($planname == '4') {
															$string = 'Advanced+Customize';
															} else if ($planname == '5') {
															$string = 'Platinum Pro + Customize';
														}
														
													?>
													<input type="hidden" value="<?php echo $planname;?>" id="packageType_<?php echo $x; ?>">
													<div><?php echo $string;?></div>
													<?php }?>
												</div>

                                                <div class="clear"></div>
                                            </div>
													<?php if($value['app_customization']==1){?>												
												<div class="payment_files_name">
													<div class="files_name_left">
														<p>Customization</p>
														
													</div>
													<div class="files_name_right">
														<?php //echo "<p>".$currencyIcon." ".$mypricing->cusomization_amount($value['crm_pricing_id'],$currency)."</p>"; ?>												
														<?php echo "<p>Yes</p>"; ?>												
														
														<div class="clear"></div>
													</div>
													<div class="clear"></div>
												</div>
											<?php }?>
                                        <div class="payment_files_name">
                                            <div class="files_name_left">
                                                <p>App Icon</p>
                                            </div>
                                            <div class="files_name_right">
                                                <p><?php echo $currencyIcon; ?> <?php echo round($priceI, 2); ?></p>
                                                <?php if (count($results_part) > 0) { ?>
                                                    <input type="hidden" class="icon_part" value="<?php echo round($priceI, 2); ?>"/>
                                            <?php
                                                    } else {
                                                    ?>
                                                    <input type="hidden" id="icon_<?php echo $x; ?>" value="<?php echo round($priceI, 2); ?>"/>
                                                <?php } ?>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                                    <div class="payment_files_name">
                                                        <div class="files_name_left">
                                                            <p>Splash Screen</p>
                                                        </div>
                                                        <div class="files_name_right">
                                                            <p><?php echo $currencyIcon; ?> <?php echo round($priceS, 2); ?></p>
                                                            <?php if (count($results_part) > 0) { ?>
                                                            <input type="hidden" class="ss_screnn"  value="<?php echo round($priceS, 2); ?>"/>
                                                            <?php
                                                        } else {
                                                            ?>															

                                                            <input type="hidden" id="splashscreen_<?php echo $x; ?>" value="<?php echo round($priceS, 2); ?>"/>
            <?php } ?>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>

                                               
                                                
                                        <?php // if ($value['is_custom'] == 1 || $value['app_customization'] == 1) {  ?>
												<div class="payment_files_name">
													<h4>Comment</h4>
													<p></p>
													<?php echo $mypricing->crm_usercomments_app($value['app_id']); ?>
													<div class="clear"></div>
													</div>
                                        <?php // }  ?>
                                        </div>

                                    <?php if (($icon_custom == 2 && $splash_custom == 2) || ($icon_custom == 1 || $splash_custom == 1)) { ?>
                                            <div class="total_app_payment">
                                            
                                            <p><span><?php echo $currencyIcon; ?> <div id="apptotalPrice_<?php echo $x; ?>" class="finalPrice"><?php echo round($totalwithISS, 2); ?></div></span>
                                                <input type="hidden" id="appsaving_<?php echo $x; ?>" value="<?php echo round($totalSaving, 2); ?>"/>
                                            </p>
                                        </div>

                                     <?php
                                    } else {
                                        if (count($results_part) > 0) {
                                            ?>
                                        <div class="total_app_payment">
                                            <p><span><?php echo $currencyIcon; ?> <div id="apptotalPrice_<?php echo $x; ?>" class="finalPrice"><?php echo round(($value['total_amount']), 2); ?></div></span>
                                                <input type="hidden" id="appsaving_<?php echo $x; ?>" value="<?php echo round($totalSaving, 2); ?>"/>
                                            </p>
                                        </div>
                                        <?php } else {
                                            ?>
                                         <div class="total_app_payment">
                                                <p><span><?php echo $currencyIcon; ?> <div id="apptotalPrice_<?php echo $x; ?>" class="finalPrice"><?php echo round(($totalwithISS), 2); ?></div></span>
                                                <input type="hidden" id="appsaving_<?php echo $x; ?>" value="<?php echo round($totalSaving, 2); ?>"/>
                                            </p>
                                        </div>
                                            <?php
                                        }
                                    }
                                    ?>
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
                                        <li><a ia-track="IA101000212" href="payment_details.php" class="deleteincart" id="mpackage_<?php echo $y; ?>"><i class="fa fa-pencil"></i></a></li>
                                        <div class="clear"></div>
                                    </ul>
                                </div>

                                <div class="payment_left_common_body no_padding">
                                    <div class="package_box">
                                        <div class="package_box_left">


                                            <p><?php
                                                $mpname = $mypricing->getMPprice($mpid);
                                                echo $mpname['name'];
                                                if ($currency == 1) {
                                                if($value2['crm_discount']!=0){
                                                $mpamount = $mpname['discounted_amount']-($mpname['discounted_amount']*$value2['crm_discount']/100);
                                                } else{
                                                    $mpamount = $mpname['discounted_amount'];
                                                }
                                                } else {
                                                if($value2['crm_discount']!=0){
                                                  $mpamount = $mpname['price_in_usd']-($mpname['price_in_usd']*$value2['crm_discount']/100);  
                                                } else{
                                                    $mpamount = $mpname['price_in_usd'];
                                                }
                                            }
                                                ?></p>
                                            <input type="hidden" value="<?php echo $mpid; ?>" id="mp_id_<?php echo $y; ?>"/>
                                        </div>
                                        <div class="package_box_right">
                                        <?php if($value2['is_partpayment']==0) {?>
                                            <?php echo $currencyIcon; ?> <p class="mpackageINR" id="mptotal_<?php echo $y; ?>"><?php echo round($mpamount, 2); ?></p>
                                            <input type="hidden" value="<?php echo round($mpamount, 2); ?>" class="mpamount"/>
                                        <?php }?>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="selectdivBox">
                                        <p class="selectApptxt">Select App</p>
                                        <div class="selectdiv">
                                            
                                                <?php
                                                $countapp = count($selectedappArray);

                                                foreach ($paidapp as $key3 => $value3) {

                                                    $appname = $value3['AppName'];
                                                    $x = 0;
                                                    for ($z = 0; $z < $countapp; $z++) {

                                                        if ($value3['app_id'] == $selectedappArray[$z]) {
                                                            $option .= $appname . ',';
                                                            $x = 1;
                                                        }
                                                    }

//                                                    
                                                }
                                                echo substr($option,0,-1);
                                                ?>
                                            
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>

                            </div>

                            <?php
                            $option = '';
                        }
                        ?>
                    
                    <div class="next_step" >
                        <a href="payment_info.php" class="back_cart">< &nbsp;&nbsp;&nbsp;Back to Billing &amp; Payment</a>
                        <?php if ($currency == 1) { ?>
                            <a ia-track="IA101000215" href="#" class="continue" onclick="ccavForm()">Place Your Order ></a>
                        <?php } else { ?>
                            <div style="float: right;">
                            <input onclick="paypalForm()" type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
                            <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                            </div>
                                <?php } ?>
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
                            <p class="total_cost">Total cost <span><?php echo $totalPrice; ?></span><span style="margin-right:5px;"><?php echo $currencyIcon; ?></span></p>
                            <?php if (($total_saving != 0) && ($total_saving != '')) { ?>
                                <p class="total_saving">Total savings <span><?php echo $total_saving; ?></span><span style="margin-right:5px;"><?php echo $currencyIcon; ?></span></p>
                            <?php } ?>

                            <div class="clear"></div>
                        </div>


                        <div class="ordertnc">
                            <input type="checkbox" checked class="tandcCheck" /><span>I agree to the following:</span>
                            <br/>
                            <a href="terms-conditions.php">Terms and Conditions</a>
                            <a href="privacy-policy.php">Privacy Policy</a>
                            <div >
                                <?php if ($currency == 1) { ?>
                                    <!--<a href="#" onclick="payForm()">Place Your Order with PayU ></a>-->
                                    <a  ia-track="IA101000215" href="#" onclick="ccavForm()">Place Your Order ></a>
                                <?php } else { ?>
                                    <div style="float: right;">
                                    <input onclick="paypalForm()" type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
                                    <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                                </div>
                                        <?php } ?>					 
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
if ($totalPrice == '') {

    $totalPrice = '4900';
}
//	$transId=strtotime('now');
$key = 'C0Dr8m';
$txnid = $orderid;
$amount = $totalPrice;
$productinfo = 'SAU Admission21 2014';
$firstname = $user_data['first_name'];
$lastname = $user_data['last_name'];
$email = $user_data['email_address'];
/* $udf1='pulp strategy';
  $udf2='pulp strategy';
  $udf3='pulp strategy';
  $udf4='test transaction';
  $udf5='pulp strategy'; */
$SALT = '3sf0jURk';

$hashString = $key . "|" . $txnid . "|" . $amount . "|" . $productinfo . "|" . $firstname . "|" . $email . "|||||||||||" . $SALT;
$hash = hash("sha512", $hashString);

// $hash=openssl_digest($hashString, 'sha512');
?>

<form action="paypalRequestHandler.php" id="paypal" method="post" style="float: right;">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="hosted_button_id" value="AUQXPCZYGBFGA">

    <!-- Identify your business so that you can collect the payments. -->
    <input type="hidden" name="business" value="accounts@pulpstrategy.com">


    <!-- Specify details about the item that buyers will purchase. -->
    <input type="hidden" name="item_name" value="Instappy Apps">
    <input type="hidden" name="item_number" id="itemno" value="<?php echo $orderid; ?>" />
    <input type="hidden" name="amount" value="<?php echo $totalPrice; ?>">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="rm" value="2" />

</form>

<form action="https://test.payu.in/_payment" id="payuForm" method='post'> 
    <input type="hidden" name="firstname" value="<?php echo $firstname ?>" /> 
    <input type="hidden" name="lastname" value="<?php echo $lastname ?>" /> 
    <input type="hidden" name="surl" value="<?php echo $basicUrl; ?>payment_status.php" /> 
    <input type="hidden" name="phone" value="<?php echo $user_data['phone']; ?>" /> 
    <input type="hidden" name="key" value="<?php echo $key ?>" /> 
    <input type="hidden" name="hash" value = "<?php echo $hash; ?>" /> 
    <input type="hidden" name="curl" value="<?php echo $basicUrl; ?>" /> 
    <input type="hidden" name="furl" value="<?php $basicUrl ?>failure.php" /> 
    <input type="hidden" name="txnid" value="<?php echo $txnid ?>" /> 
    <input type="hidden" name="productinfo" value="<?php echo $productinfo ?>" /> 
    <input type="hidden" name="amount" value="<?php echo $amount ?>" /> 
    <input type="hidden" name="email" value="<?php echo $email ?>" /> 
</form>

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
<!--		<input type="hidden" name="merchant_param1" value="additional Info."/>
    <input type="hidden" name="merchant_param2" value="additional Info."/>
    <input type="hidden" name="merchant_param3" value="additional Info."/>
    <input type="hidden" name="merchant_param4" value="additional Info."/>
    <input type="hidden" name="merchant_param5" value="additional Info."/>-->
    <input type="hidden" name="promo_code" value=""/>
    <input type="hidden" name="customer_identifier" value=""/>
</form>


<link href="css/multiple-select.css" rel="stylesheet"/>
    <script src="js/jquery.multiple.select.js"></script>
<script>
    function payForm() {
        $("#payuForm").submit();
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
    
    $('div.selectdiv select.appName').multipleSelect({allSelected:false});
    $(document).ready(function () {
        
        $(".leftsidemenu li").removeClass("active");
$(".leftsidemenu li.cart").addClass("active");
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
<script type="text/javascript" src="js/trackuser.jquery.js"></script>
</body>
</html>
