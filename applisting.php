<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
unset($_SESSION['token']);
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;

require_once('modules/pricing/pricing.php');
$mypricing = new mypricing();
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

if (!empty($_SESSION['username']) || !empty($_SESSION['custid'])) {
    require_once('modules/myapp/myapps.php');
    $apps = new MyApps();
    $results = $apps->check_user_exist($_SESSION['username'], $_SESSION['custid']);
    $user_id = $results['id'];
    $page = 0;
    $type = 1;
//    $data = array('user_id'=>$user_id,'type'=>$type,'page');
    $allapps = $apps->get_all_apps($user_id, $type, $page,$_SESSION['cust_reseller_id']);

    $contappP1 = $apps->count_my_apps($user_id, $type);

    if ($contappP1 == 0) {
    
        $type = 0;
        $allapps = $apps->get_all_apps($user_id, $type, $page, $_SESSION['cust_reseller_id']);

        $contappD1 = $apps->count_my_apps($user_id, $type);
        $setected = 'selected=selected';
    } else if($contappP1 > 6) {
       
        $setected = '';
    }
} else {

    //header('Location: http://pulpstrategist.co.in/1353/index.php');
    echo "<script>window.location.href='" . $basicUrl . "'</script>";
    exit();
}
?>
<script type="text/javascript">
    $(document).ready(function () {

        $(window).resize(function () {
            var screenWidth = $(window).width();
            if (screenWidth <= 1023) {
                $('.screen_popup').fadeIn();
                $('.popup_container').fadeIn();
            } else {
                $('.screen_popup').fadeOut();
                $('.popup_container').fadeOut();
            };
        });
		$('.screen_popup input:button').on('click',function(){
          $('.screen_popup').fadeOut();
          $('.popup_container').fadeOut();
		});


        $("aside ul li").removeClass("active");
        $("aside ul li.tablet").addClass("active");

    });
</script>
<style>
    body{
        background:#FFF;
    }
</style>
<section class="main">
    <header>
    <?php if(empty($_SESSION['cust_reseller_id'])){?>
        <ul class="top-aside">
            <li><a href="myorders.php">My Orders<i class="fa fa-truck"></i></a></li>
            <li><a href="themes.php">Create New <i class="fa fa-rocket"></i></a></li>
        </ul>
    <?php }?>
    </header>
    <section class="right_main post_publish">
        <div class="right_inner">
            <div class="apps_head">
                <div class="apps_head_left">
                    <h1>My Apps</h1>
                    <p>You can toggle between the apps that have been published by selecting 'Published', and the ones which are still in drafts by selecting 'Drafts' from the dropdown menu below.</p>
                </div>
                <div class="apps_head_right" style="display:none">
                    <input type="text" placeholder="Search" id="search">
                    <a href="#"><img src="images/search.png"></a>
                </div>
                <div class="clear"></div>


            </div>
            <div class="apps_body">
             <?php if(empty($_SESSION['cust_reseller_id'])){?>
                <select id="app_status">
                
                    <option value="1" <?php echo $setected; ?>>Published</option>
                    <option value="0" <?php echo $setected; ?>>Drafts</option>

                </select>
                <?php }?>
                <div class="apps_list">
                    <?php
                    if ($contappP1>0) {
                        foreach ($allapps as $val) {
                            $file = 'appedit.php';
                            $img = $val['app_image'];
                            if ($img == '') {
                                $img = 'images/myapp1.jpg';
                            }

                            $paltform = $val['app_type'];
//                            $paltformtype = $val['platform'];

//                            if (trim($paltformtype) == "1") {
//                                $paltform = "Android";
//                            } else if (trim($paltformtype) == "2") {
//                                $paltform = "iOS";
//                            } else if (trim($paltformtype) == "3") {
//                                $paltform = "Android + iOS";
//                            }
			$jumpto = $val['jump_to'];
			$jumptoapp = $val['jump_to_app_id'];

if($jumpto == 1 && $jumptoapp != 0)
						{
							
							 $paltform = 'Content and M-Store';
						}
                            echo ' <div class="apps_box">
                        <a href="' . $file . '?appid=' . $val['id'] . '"><img src="' . $img . '"></a>
                        <div class="apps_box_name">
                            <h2><a href="' . $file . '?appid=' . $val['id'] . '">' . stripslashes($val['summary']) . '</a></h2>
                            <p><a href="' . $file . '?appid=' . $val['id'] . '">' . $paltform . '</a></p>
                        </div>
                        <div class="apps_box_download">
                         <div data-aapid="' . $val['id'] . '" class="downloadapp"><img src="images/app_download.png"></div>
                             <a href="" style="visibility: hidden;" id="zipUrl"></a>
                        </div>
                        <div class="clear"></div>
                    </div>';
                        }
                    }
                    else if ($contappP1 == 0) {

                        foreach ($allapps as $val) {
                            $file = 'appedit.php';
                            $img = $val['app_image'];
                             $paltform = $val['app_type'];
                            if ($img == '') {
                                $img = 'images/myapp1.jpg';
                            }
                            echo ' <div class="apps_box">
                        <a href="' . $file . '?appid=' . $val['id'] . '"><img src="' . $img . '"></a>
                        <div class="apps_box_name">
                            <h2><a href="' . $file . '?appid=' . $val['id'] . '">' . stripslashes($val['summary']) . '</a></h2>
                            <p><a href="' . $file . '?appid=' . $val['id'] . '">' . $paltform . '</a></p>
                        </div>
                        <div class="apps_box_download">
                        <div data-aapid="' . $val['id'] . '" class="deleteapp" ><a href="javascript:void(0)"><img src="images/app_delete.png"></a></div>			
                        </div>
                        <div class="clear"></div> 
                    </div>';
                        }
                    } else {
                        echo 'No Records Found';
                    }
                    ?>

                </div>
                <div class="clear"></div>
                <div id="app_loader"></div>
                <input type="hidden" id="user_id" value="<?php echo isset($_SESSION['username']) ? $user_id : '' ?>">
                <input type="hidden" id="app_type" value="">		
               <?php  if(($contappP1>6)||($contappD1>6)){?>
                <img src="images/more_button.png" id="load_more_apps"/>
                <?php }?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var select = $("#app_status").val();
                        $("#app_type").val(select);

                        $(document).on("click", ".deleteapp", deleteAppFunction);
                        function deleteAppFunction() {
                            $(".delete_app").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $(this).attr("id", "current");
                        }

                        $(".delete_app input").click(function () {
                            var confirmVal = $(this).val();
                            var appid = $("#current").attr("data-aapid");
                            $("#current").removeAttr("id");
//                            alert(appid);

                            $(".delete_app").css({'display': 'none'});
                            $(".popup_container").css({'display': 'none'});

                            if (confirmVal == "Yes") {
                                var form_data = {
                                    token: '<?php echo $token; ?>', //used token here.                      
                                    hasid: appid,
                                    confirm: confirmVal,
                                    is_ajax: 2
                                };
                                $.ajax({
                                    type: "POST",
                                    url: 'modules/myapp/deleteapp.php',
                                    data: form_data,
                                    success: function (response)
                                    {
                                        if (response == 1) {
                                            //window.location = window.location;
                                            //$('select#app_status').val(0).trigger('change');
											$('select#app_status').val(0).change();
                                            console.log(response);

                                        } else if (response != 1) {
                                            console.log(response);
                                        }
                                    },
                                    error: function () {
                                        console.log("error in ajax call");
//                                        alert("error in ajax call");
                                    }
                                });
                            }

                        });

                        $(document).on("click", ".downloadapp", createzipFunction);

                        function createzipFunction() {
                            var appid = $(this).attr("data-aapid");

                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('<img src="images/ajax-loader.gif">');

                            var form_data = {
                                token: '<?php echo $token; ?>', //used token here.                      
                                hasid: appid,
                                is_ajax: 1
                            };
                            $.ajax({
                                type: "POST",
                                url: 'modules/myapp/createzip.php',
                                data: form_data,
                                success: function (response)
                                {
                                    if ((response != 1) && (response != 2)) {
                                        window.location = response;
//                                        $("#zipUrl").attr("href", response);
//                                        $(document).on("click", "#zipUrl", function(){
//                                            $('a').eq(0).click();
//                                        });
//                                        $("#zipUrl").trigger("click");

                                        console.log(response);
                                        $(".popup_container").css({'display': 'none'});
                                        $("#page_ajax").html('');

                                    }
                                    else if (response == 1) {
                                        console.log(response);
                                        $(".popup_container").css({'display': 'none'});
                                        $("#page_ajax").html('');
                                    }
                                    else if (response == 2) {
                                        console.log("App Folder does not exist");
                                        $(".popup_container").css({'display': 'none'});
                                        $("#page_ajax").html('');
                                    }
                                },
                                error: function () {
                                    $(".popup_container").css({'display': 'none'});
                                    $("#page_ajax").html('');
                                    console.log("error in ajax call");
//                                    alert("error in ajax call");
                                }
                            });


                        }
                        ;


                    });
                    $(window).load(function () {
                        var screenWidth = $(window).width();
                        if (screenWidth <= 1023) {
                            $('.screen_popup').fadeIn();
                            $('.popup_container').fadeIn();
                        } else {
                            $('.screen_popup').fadeOut();
                            $('.popup_container').fadeOut();
                        }
                        ;
                    });
                </script>
            </div>
		<?php if(empty($_SESSION['cust_reseller_id'])){?>
            <div class="current_pack">
                <?php
                $asopackages = $mypricing->getASOprice();
                ?>
                <div class="promo_code">
                            <div class="marketing_package">
                                <h2 class="head"> Recommended for you :</h2>
                                <div class="halfpackage package1">
                                    
                                    <?php if($currency == 1) {?>
                                   <div class="topHead"><img src="images/superdeal1.png"></div>                                  
                                        <div class="readMoreSec">
                                    <?php } else{?>                                        
                                            <div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
                                    <?php }?>
                                        <p class="heading">App Launch ASO Plan</p>
                                        <p class="subheading"><span>App Store Optimisation, is possibly the single most important piece in launching your App. ASO is the process of optimizing mobile apps to rank higher in an app store’s search results. The higher your app ranks in an app store’s search results, the more visible it is to potential customers. That increased visibility tends to translate into more traffic to your app’s page in the app store. The goal of ASO is to drive more traffic to your app’s page in the app store, so searchers can take a specific action</span>
                                            <a href="javascript:void(0)" class="img-tog">Read more</a></p>
                                    </div>
                                    <div class="half_package_img">
                                        <div class="half_package_terms">
                                            <ul>
                                                <li>App Name Suggestions including Primary Suggestion and Variants</li>
                                                <li>App Title</li>
                                                <li>App Description</li>
                                                <li>Keywords (research, identification and inclusion) upto 20 key words</li>
                                                <li>Category Suggestions including Major and Sub-Categories.</li>
                                                <li>Icon Recommendations Based on Concepts</li>
                                                <li>Screenshot Recommendations Based on App Usage and compete analysis</li>
                                                <li>SEO Optimized Product Description Based on App Usage</li>
                                                <li>Professionally done Report included</li>
                                                <li>Duration: 2 - 4 weeks</li>
                                            </ul>
                                            <div class="half_package_price">
                                                <?php if($currency == 1) {?>
                                                <h2><?php echo $currencyIcon.$asopackages[0]['discounted_amount']?></h2>
                                                <p><?php echo $currencyIcon.$asopackages[0]['actual_amount']?></p>
<?php } else{?>
                                                <h2><?php echo $currencyIcon.$asopackages[0]['price_in_usd']?></h2>
                                                        <p><?php if(!empty($asopackages[0]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[0]['usddiscounted_amount'];} ?></p>
<?php }?>
                                                <span>One Time Package</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="addCartButton">
                                        <a class="mpackage" data-mpid="1" >Add to Cart</a>

                                    </div>

                                </div>

                                <div class="halfpackage package2">                                    
                                     <?php if($currency == 1) {?>
                                   <div class="topHead"><img src="images/superdeal2.png"></div>                                     
                                        <div class="readMoreSec">
                                    <?php } else{?>                                        
                                            <div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
                                    <?php }?>
                                        <p class="heading">Live ASO Plan</p>
                                        <p class="subheading"><span>If your app is already on the store but you missed the app store optimisation (ASO) all is not lost. You can still optimise your app for better visibility. ASO is the process of optimizing mobile apps to rank higher in an app store’s search results. The higher your app ranks in an app store’s search results, the more visible it is to potential customers. That increased visibility tends to translate into more traffic to your app’s page in the app store. The goal of ASO is to drive more traffic to your app’s page in the app store, so searchers can take a specific action</span>
                                            <a href="javascript:void(0)" class="img-tog">Read more</a></p>

                                    </div>
                                    <div class="half_package_img">
                                        <div class="half_package_terms">
                                            <ul>
                                                <li>Support for categories and countries on App Store and Google Play</li>
                                                <li>Icon Adjustment Based on Live Art</li>
                                                <li>Screenshot Recommendations Based on App Usage</li>
                                                <li>Rewriting of Product Description Leveraging Live Description</li>
                                                <li>App Analytics (Metrics and Insight into how users use your App create success)</li>
                                                <li>Alternative App Stores Suggestion</li>
                                                <li>Suggestion on Ongoing App Store Optimization</li>
                                                <li>App Reviews recommendation</li>
                                                <li>App Analysis and action points</li>
                                                <li>Analyze Traffic, Competition and Current app ranking of keywords</li>
                                                <li>Email/Phone Call Support</li>
                                                <li>Professionlly done Report Included</li>
                                                <li>Duration: 6 weeks</li>
                                            </ul>
                                            <div class="half_package_price">
                                                <?php if($currency == 1) {?>
                                                <h2><?php echo $currencyIcon.$asopackages[1]['discounted_amount']?></h2>
                                                <p><?php echo $currencyIcon.$asopackages[1]['actual_amount']?></p>
<?php } else{?>
                                                <h2><?php echo $currencyIcon.$asopackages[1]['price_in_usd']?></h2>
                                                        <p><?php if(!empty($asopackages[1]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[1]['usddiscounted_amount'];} ?></p>
<?php }?>
                                                <span>One Time Package</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="addCartButton">
                                        <a class="mpackage" data-mpid="2" >Add to Cart</a>

                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="fullpackage package3">                                    
                                    <?php if($currency == 1) {?>
                                   <div class="topHead"><img src="images/superdeal3.png"></div>
                                     <div class="half_package_img">
                                        <div class="readMoreSec">
                                    <?php } else{?>
                                        <div class="half_package_img">
                                            <div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
                                    <?php }?>
                                            <p class="heading">App Reviews</p>
                                            <p class="subheading">When you are launching a new app, naturally you want everyone in the world to see it! App review sites are a great way of gaining visibility, credibility and traction for your apps. Drive More Reviews & Installs Instantly.</p>
                                        </div>
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td></td>
                                                <td>Basic Plan</td>
                                                <td>Advanced Plan</td>
                                                <td>Enterprise Plan</td>
                                            </tr>
                                            <tr>
                                                <td>App Submission to relevant review sites</td>
                                                <td>50</td>
                                                <td>100</td>
                                                <td>150+</td>
                                            </tr>
                                            <tr>
                                                <td>PR Release Creation &amp; Distribution</td>
                                                <td>N</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>PR Release Distribution</td>
                                                <td>N</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>Guaranteed Reviews</td>
                                                <td>5</td>
                                                <td>10</td>
                                                <td>15</td>
                                            </tr>
                                            <tr>
                                                <td>Get Featured on our Blog</td>
                                                <td>N</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <?php if($currency == 1) {?>
                                                    <span class="main_price"><?php echo $currencyIcon.$asopackages[2]['discounted_amount']?></span>
                                                    <span class="strike_price no_strike"><?php echo $currencyIcon.$asopackages[2]['actual_amount']?></span>
<?php } else{?>
                                                <span class="main_price"><?php echo $currencyIcon.$asopackages[2]['price_in_usd']?></span>
                                                <span class="strike_price no_strike"><?php if(!empty($asopackages[2]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[2]['usddiscounted_amount'];} ?></span>
<?php }?>
                                                    <span class="package_name">One Time Package</span>
                                                </td>
                                                <td>
                                                   <?php if($currency == 1) {?>
                                                    <span class="main_price"><?php echo $currencyIcon.$asopackages[3]['discounted_amount']?></span>
                                                    <span class="strike_price"><?php echo $currencyIcon.$asopackages[3]['actual_amount']?></span>
<?php } else{?>
                                                <span class="main_price"><?php echo $currencyIcon.$asopackages[3]['price_in_usd']?></span>
                                                <span class="strike_price"><?php if(!empty($asopackages[3]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[3]['usddiscounted_amount'];} ?></span>
<?php }?>
                                                    <span class="package_name">One Time Package</span>
                                                </td>
                                                <td>
                                                   <?php if($currency == 1) {?>
                                                    <span class="main_price"><?php echo $currencyIcon.$asopackages[4]['discounted_amount']?></span>
                                                    <span class="strike_price"><?php echo $currencyIcon.$asopackages[4]['actual_amount']?></span>
<?php } else{?>
                                                <span class="main_price"><?php echo $currencyIcon.$asopackages[4]['price_in_usd']?></span>
                                                <span class="strike_price"><?php if(!empty($asopackages[4]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[4]['usddiscounted_amount'];} ?></span>
<?php }?>
                                                    <span class="package_name">One Time Package</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                            <a class="mpackage" data-mpid="3" >Add to Cart</a>
                                        </td>
                                                <td>
                                            <a class="mpackage" data-mpid="4" >Add to Cart</a>
                                        </td>
                                                <td>
                                            <a class="mpackage" data-mpid="5" >Add to Cart</a>
                                        </td>
                                            </tr>
                                        </table>
                                    </div>
                                  
                                </div>
                                <div class="fullpackage package3">                                    
                                    <?php if($currency == 1) {?>
                                   <div class="topHead"><img src="images/superdeal4.png"></div>
                                     <div class="half_package_img">
                                        <div class="readMoreSec">
                                    <?php } else{?>
                                        <div class="half_package_img">
                                            <div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
                                    <?php }?>
                                            <p class="heading">App Downloads</p>
                                            <p class="subheading">Boost Your App Downloads, Target The RIGHT People So you need to target people from specific locations? No problem! We help you target and drive your app downloads from the audience of your choice.</p>

                                        </div>
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td></td>
                                                <td>Basic Plan</td>
                                                <td>Advanced Plan</td>
                                                <td>Enterprise Plan</td>
                                            </tr>
                                            <tr>
                                                <td>Targetted App downloads</td>
                                                <td>200</td>
                                                <td>420</td>
                                                <td>750</td>
                                            </tr>
                                            <tr>
                                                <td>Guaranteed Returns</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>Positive Google Play Store Reviews</td>
                                                <td>5</td>
                                                <td>10</td>
                                                <td>15</td>
                                            </tr>
                                            <tr>
                                                <td>Unique Device</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>Professionally done Report Included</td>
                                                <td>N</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <?php if($currency == 1) {?>
                                                    <span class="main_price"><?php echo $currencyIcon.$asopackages[5]['discounted_amount']?></span>
                                                    <span class="strike_price no_strike"><?php echo $currencyIcon.$asopackages[5]['actual_amount']?></span>
<?php } else{?>
                                                <span class="main_price"><?php echo $currencyIcon.$asopackages[5]['price_in_usd']?></span>
                                                <span class="strike_price no_strike"><?php if(!empty($asopackages[5]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[5]['usddiscounted_amount'];} ?></span>
<?php }?>
                                                    <span class="package_name">One Time Package</span>
                                                </td>
                                                <td>
                                                   <?php if($currency == 1) {?>
                                                    <span class="main_price"><?php echo $currencyIcon.$asopackages[6]['discounted_amount']?></span>
                                                    <span class="strike_price"><?php echo $currencyIcon.$asopackages[6]['actual_amount']?></span>
<?php } else{?>
                                                <span class="main_price"><?php echo $currencyIcon.$asopackages[6]['price_in_usd']?></span>
                                                <span class="strike_price"><?php if(!empty($asopackages[6]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[6]['usddiscounted_amount'];} ?></span>
<?php }?>
                                                    <span class="package_name">One Time Package</span>
                                                </td>
                                                <td>
                                                   <?php if($currency == 1) {?>
                                                    <span class="main_price"><?php echo $currencyIcon.$asopackages[7]['discounted_amount']?></span>
                                                    <span class="strike_price no_strike"><?php echo $currencyIcon.$asopackages[7]['actual_amount']?></span>
<?php } else{?>
                                                <span class="main_price"><?php echo $currencyIcon.$asopackages[7]['price_in_usd']?></span>
                                                <span class="strike_price no_strike"><?php if(!empty($asopackages[7]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[7]['usddiscounted_amount'];} ?></span>
<?php }?>
                                                    <span class="package_name">One Time Package</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                        <a class="mpackage" data-mpid="6" >Add to Cart</a>
                                                    </td>
                                                <td>
                                                        <a class="mpackage" data-mpid="7" >Add to Cart</a>
                                                    </td>
                                                <td>
                                                        <a class="mpackage" data-mpid="8" >Add to Cart</a>
                                                    </td>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                </div>
                                <div class="fullpackage package3">
                                    <?php if($currency == 1) {?>
                                    <div class="topHead"><img src="images/superdeal5.png"></div>
                                     <div class="half_package_img">
                                        <div class="readMoreSec">
                                    <?php } else{?>
                                        <div class="half_package_img">
                                            <div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
                                    <?php }?>
                                    
                                            <p class="heading">Refurbishment of Content / Content Writing</p>
                                            <p class="subheading">Just writing is not enough, it's a science to infuse the right keywords which impact your App's indexing by Google and at the same time make your app content a joy to read. Let’s face it – not everyone can write.  Given the importance of rich and appealing content in the development of any application, it’s worth committing resources to ensure that the job is done well.</p>
                                        </div>
                                        <table cellpadding="0" cellspacing="0" class="big_table">
                                            <tr>
                                                <td></td>
                                                <td>Basic Plan</td>
                                                <td>Advanced Plan</td>
                                                <td>Enterprise Plan</td>
                                            </tr>
                                            <tr>
                                                <td>Screen Pages Copywriting</td>
                                                <td>25</td>
                                                <td>50</td>
                                                <td>100</td>
                                            </tr>
                                            <tr>
                                                <td>Rewrite Search Friendly Content (Base Content Provided by Client)</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>Keywords (research, identification and inclusion) Uptoo 20 key words</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>Word Count Up-To 500 per screen / page</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>Proof read by Experts</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>On-Time Delivery</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>Load time Optimized Images</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>Multiple Device & OS Compatibility Images</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td>Images Provided by the App publisher / client </td>
                                                <td>35</td>
                                                <td>65</td>
                                                <td>120</td>
                                            </tr>
                                            <tr>
                                                <td>1 Week Updation Time</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                                <td>Y</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <?php if($currency == 1) {?>
                                                    <span class="main_price"><?php echo $currencyIcon.$asopackages[8]['discounted_amount']?></span>
                                                    <span class="strike_price"><?php echo $currencyIcon.$asopackages[8]['actual_amount']?></span>
<?php } else{?>
                                                <span class="main_price"><?php echo $currencyIcon.$asopackages[8]['price_in_usd']?></span>
                                                <span class="strike_price"><?php if(!empty($asopackages[8]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[8]['usddiscounted_amount'];} ?></span>
<?php }?>
                                                    <span class="package_name">One Time Package</span>
                                                </td>
                                                <td>
                                                   <?php if($currency == 1) {?>
                                                    <span class="main_price"><?php echo $currencyIcon.$asopackages[9]['discounted_amount']?></span>
                                                    <span class="strike_price"><?php echo $currencyIcon.$asopackages[9]['actual_amount']?></span>
<?php } else{?>
                                                <span class="main_price"><?php echo $currencyIcon.$asopackages[9]['price_in_usd']?></span>
                                                <span class="strike_price"><?php if(!empty($asopackages[9]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[9]['usddiscounted_amount'];} ?></span>
<?php }?>
                                                    <span class="package_name">One Time Package</span>
                                                </td>
                                                <td>
                                                   <?php if($currency == 1) {?>
                                                    <span class="main_price"><?php echo $currencyIcon.$asopackages[10]['discounted_amount']?></span>
                                                    <span class="strike_price"><?php echo $currencyIcon.$asopackages[10]['actual_amount']?></span>
<?php } else{?>
                                                <span class="main_price"><?php echo $currencyIcon.$asopackages[10]['price_in_usd']?></span>
                                                <span class="strike_price"><?php if(!empty($asopackages[11]['usddiscounted_amount'])){ echo $currencyIcon . $asopackages[11]['usddiscounted_amount'];} ?></span>
<?php }?>
                                                    <span class="package_name">One Time Package</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                        <a class="mpackage" data-mpid="9" >Add to Cart</a>
                                                    </td>
                                                <td>
                                                        <a class="mpackage" data-mpid="10">Add to Cart</a>
                                                    </td>
                                                <td>
                                                        <a class="mpackage" data-mpid="11">Add to Cart</a>
                                                    </td>
                                            </tr>
                                        </table>
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
                   
                    <div class="clear"></div>
                </div>
                <?php }?>
            </div>
            </div>
    </section>
</section>
</section>
<script>
    $(document).ready(function () {
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
                                                    }
                                                    else if (response == 3) {
                                                        $(".confirm_name .confirm_name_form").html('<p>Your all apps has been expired. Please create a new app.</p><input type="button" value="OK">');
                                                        $(".confirm_name").css({'display': 'block'});
                                                        $(".close_popup").css({'display': 'block'});
                                                        $(".popup_container").css({'display': 'block'});
                                                    }
                                                    else {
                        var totalclassmp = 0;
                        totalclassmp = $("p[id^='mptotal_']").length;
                        totalclassmp = parseFloat(totalclassmp + 1);

                        window.location = "payment_details.php";
                        console.log(response);
                    }
                }
            });
        });

        $('.halfpackage .img-tog').click(function () {
            var img_visb = $(this).parents('.halfpackage , .half_package_img .half_package_terms').find('ul');
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
    })
</script>



<?php

  if(($_SESSION['forAPICall']=='1') && !isset($_SESSION['unsetsessiondata'])){
    ?>
    <script type="text/javascript">
    $.ajax({
        url : 'loginCrmCall.php',
        type : 'POST',
        async : true,
        success : function() {
              

        }
    });
    </script>
       <!-- $loginTime = date('Y-m-d h:i:s', time());
        $logout_time = '';
        $customerid = $_SESSION['custid'];
        $mainurl = 'http://192.168.75.173/universus/pulp_login_api.php?customerid='.$customerid.'&login_time='.$loginTime.'&logout_time='.$logout_time.'';

        $url = str_replace(' ', '-', $mainurl);
        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));
        $head2 = curl_exec($curl2);
        curl_close($curl2);-->
        <?php
        unset($_SESSION['forAPICall']);
        
    }
   
    ?>
</body>
</html>