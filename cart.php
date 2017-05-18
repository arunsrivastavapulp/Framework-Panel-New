<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');

require_once('modules/pricing/pricing.php');
require_once('modules/myapp/myapps.php');
$apps = new MyApps();
$mypricing = new mypricing();
$finalPrices = $mypricing->pricing();
$totalPrice=$finalPrices[0]['price'];
$_SESSION['totalPrice']=$totalPrice;
  $appID = $_SESSION['appid'];
?>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner">
        <div class="bannertitle">
           		<h2 style="font-size:16px;line-height:28px">You have qualified for multiple money saving offers. <br/>We have automatically applied the best offers for you giving you the lowest price.</h2>
                <p style="margin-top:10px">Apps built using the Instappy.com are packed with features that are built for your success.
 Instappy apps are <span>instant, affordable, stunning, intuitive</span> and will <span>change the way you interact 
with your customers.</span> Our business model allows us to provide everyone with world-class, feature-packed 
applications on Android and iOS for a fraction of the cost it takes to develop an app otherwise. 
                </p>
                
           
           </div>
            
            <div class="payment_box">
            	<div class="payment_left">
                	<div class="payment_left_common">
                    	<div class="payment_left_common_head">
                        	<ul>
                            	<li>Review Order</li>
                                <li></li>
                            	<li>Edit Order</li>
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
                                            <option>1 Year</option>
                                            <option selected>2 Years</option>
                                        </select>
                                        <span class="discount">15% Off</span>
                                    </li>
                                    <li>49000.00</li>
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
                                            <span>10% Off</span>
                                        </div>
                                        <div class="files_name_right">
                                            <p>49000.00</p>
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
                                            <p>49000.00</p>
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
                                    <p>Total: <span>49000.00</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payment_left_common">
                    	<div class="payment_left_common_head">
                        	<ul>
                            	<li>Review Order</li>
                                <li></li>
                            	<li>Edit Order</li>
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
                                            <option>1 Year</option>
                                            <option selected>2 Years</option>
                                        </select>
                                        <span class="discount">15% Off</span>
                                    </li>
                                    <li>49000.00</li>
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
                                            <span>10% Off</span>
                                        </div>
                                        <div class="files_name_right">
                                            <p>49000.00</p>
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
                                            <p>49000.00</p>
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
                                    <p>Total: <span>49000.00</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payment_left_common">
                    	<div class="payment_left_common_head">
                        	<ul>
                            	<li>Review Order</li>
                                <li></li>
                            	<li>Edit Order</li>
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
                                            <option>1 Year</option>
                                            <option selected>2 Years</option>
                                        </select>
                                        <span class="discount">15% Off</span>
                                    </li>
                                    <li>49000.00</li>
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
                                            <span>10% Off</span>
                                        </div>
                                        <div class="files_name_right">
                                            <p>49000.00</p>
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
                                            <p>49000.00</p>
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
                                    <p>Total: <span>49000.00</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    
                    <div class="next_step">
                    	<a href="#" class="back_cart">< &nbsp;&nbsp;&nbsp;Back to Cart</a>
                    	<a href="#" class="continue">Continue ></a>
                        <p>You are not billed yet.</p>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="payment_right">
                	<div class="payment_right_common">
                    	<div class="order_summary">
                        	<div class="select_currency">
                            	<label>Total cost</label>
                                <select class="my-select">
                                    <option data-img-src="images/ruppee_icon.png">INR</option>
                                    <option data-img-src="images/dollar_icon.png">US Dollar</option>
                                </select>
                               <div class="clear"></div>
                            </div>
                            <p class="total_cost"><?php echo $finalPrices[0]['price'];?></p>
                            <p class="total_saving">Total savings <span>$ 1,800.00</span></p>
                            <a href="payment_gateway2.php">Proceed to Checkout ></a>
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
</body>
<script>
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
<script>
$(document).ready(function() {
	$("aside ul li").removeClass("active");
	$("aside ul").find(".cart").eq(0).addClass("active");
});
</script>
</html>
