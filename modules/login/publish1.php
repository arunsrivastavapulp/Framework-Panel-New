<?php 
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/checkapp/app_screen.php');
?>
  <section class="main">
    <header>
      <ul class="select-phone">
        <li>
            <select class="select_os">
                <option data-img-src="images/select_os.png">Android</option>
                <option data-img-src="images/select_os.png">iOS</option>
                <option data-img-src="images/select_os.png">Windows</option>
            </select>
            <div class="clear"></div>
        </li>
          <div class="hint_content">
              <span class="open_hint">i</span>
              <p>Change Device and see how your application looks on other platform.</p>
          </div>
        </li>
      </ul>

      <div class="select-page">
        <div class="hint_content">
            <span class="open_hint">i</span>
            <p>From here you can directly go to any page you like to edit.</p>
        </div>
        <label>Page : </label>
        <select id="pageSelector" disabled="disabled">
          
        </select>
      </div>
      <ul class="top-aside">
        <li class="save"><a href="#">Save <i class="fa fa-cloud-upload"></i></a>
            <div class="hint_content">
                <span class="open_hint">i</span>
                <p>Don't let your hardwork go in vain.<br>Keep saving your work</p>
            </div>
        </li>
        <li><a href="publish1.php">Publish <i class="fa fa-sign-in"></i></a>
            <div class="hint_content">
                <span class="open_hint">i</span>
                <p>Don't let your hardwork go in vain.<br>Keep saving your work</p>
            </div>
        </li>
      </ul>
    </header>
    <section class="middle clear">
      <div class="right-area publish_pages">
         <div class="mobile">
         
          

         
         <!-- Replacement Area Starts -->
           <div id="content-1" class="content mCustomScrollbar clear first-page " >
           
             
            
            
            <div class="theme_head restro_theme">
                <a class="nav_open"> <img src="images/menu_btn.png"> </a>
                <h1>IndiEat</h1>
                <a href="#" class="search"> <i class="fa fa-search"></i> </a>
            </div>
                    
            <nav>
                <ul>
                    <li data-link="1"><a href="#">Home</a></li>
                    <li data-link="2"><a href="#">About Us</a></li>
                    <li data-link="1"><a href="#">Menu</a></li>
                    <li data-link="1"><a href="#">Notification</a></li>
                    <li data-link="1"><a href="#">Chef &apos;s View</a></li>
                </ul>
            </nav>
            <div class="overlay">
            </div>
            
            
             <div class="banner">
                <img src="images/restaurant_banner.jpg" class="active">
                <img src="images/restaurant_banner.jpg">
                <img src="images/restaurant_banner.jpg">
                <div class="pager">
                	<div class="active"></div>
                	<div></div>
                	<div></div>
                </div>
                <div class="clear"></div>
             </div>
             


            
            
            
            
         <div class="container droparea" style="float:left;width:100%;">
             	   
            
                <div class="half_widget" component="">
                <p class="widgetClose">x</p>
                  <div class="widget_inner">
                      <div class="half_widget_img">
                          <img src="images/restaurant_widget1.png">
                            <div class="clear"></div>
                        </div>
                        <div class="half_widget_text">
                          <p>Chef's View</p>
                        </div>
                    </div>
                </div>
                <div class="half_widget">
                <p class="widgetClose">x</p>
                  <div class="widget_inner">
                      <div class="half_widget_img">
                          <img src="images/restaurant_widget2.png">
                            <div class="clear"></div>
                        </div>
                        <div class="half_widget_text">
                          <p>Chef's View</p>
                        </div>
                    </div>
                </div>
                <div class="full_widget" data-ss-colspan="2">
                <p class="widgetClose">x</p>
                  <div class="widget_inner">
                      <div class="full_widget_img">
                          <img src="images/restaurant_widget3.png">
                            <div class="clear"></div>
                        </div>
                        <div class="full_widget_text">
                          <p>Special Offers</p>
                        </div>
                    </div>
                </div> 
                <div class="small_widget addon">
                <p class="widgetClose">x</p>
                  <div class="widget_inner">
                      <div class="small_widget_img">
                      <img src="images/zomato.png">
                        </div>
                        <div class="small_widget_text">
                          <p>Review Us</p>
                          <p>On</p>
                            <h2>Zomato</h2>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="contact_small_widget">
                                	<p class="widgetClose">x</p>
                                    <p class="contact_small_heading">Heading</p>
                                    <p class="contact_small_subheading">Subheading</p>
                                    <div class="contact_btns">
                                    	<a href="#"><img src="images/contact_mail.png" /></a>
                                    	<a href="#"><img src="images/contact_phone.png" /></a>
                                    </div>
                                </div>
              </div>
              
              
            
            
            </div>

          <!-- Replacement Area Ends -->
        </div>
        <div class="devices"> 
        	<button>Device</button>
            <div class="device_options">
            	<div class="device_list android">
                	<img src="images/android.png" />
                    <span>Android</span>
                </div>
            	<div class="device_list iphone">
                	<img src="images/iphone.png" />
                    <span>Iphone</span>
                </div>
            	<div class="device_list window">
                	<img src="images/window.png" />
                    <span>Windows</span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
      </div>
      <div class="publish_content_area">
      	<div class="mid_section ">
            <div class="mid_section_left">
            </div>
            <div class="mid_section_right">
                <div class="mid_right_box active">
                	<div class="mid_right_box_head">
                        <h1>Publish:</h1>
                        <h2>Application Icon</h2>
                    </div>
                	<div class="mid_right_box_body">
                    	<div class="choose_icon">
                        	<h2>Choose from Below <span>(it will resize automatically for all devices):</span></h2>
                            <div class="icons">
							<?php 
								$webscreen = new WebScreen();
								$icons = $webscreen->get_app_icons();
								foreach($icons as $icon){
									if($icon['image_40'] != ''){
										?><img src="<?php echo $icon['image_40'];?>"><?php
									}
								} ?>
                            </div>
                        </div>
                        <div class="choose_icon">
                        	<h2>Or upload your own:</h2>
                            <div class="os_screen">
                            	<h2>iPhone</h2>
                                <div class="screen_size large">
                                	<img src="images/iphone_1024.jpg" data-size="1024">
                                    <div class="clear"></div>
                                    <span>App Store</span>
                                </div>
                                <div class="screen_size medium">
                                	<img src="images/iphone_180.jpg" data-size="180">
                                    <div class="clear"></div>
                                    <span>Home Screen</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="os_screen ">
                            	<h2>Android</h2>
                                <div class="screen_size large">
                                	<img src="images/android_512.jpg" data-size="512">
                                    <div class="clear"></div>
                                    <span>Google Play</span>
                                </div>
                                <div class="screen_size medium">
                                	<img src="images/android_96.jpg" data-size="96">
                                    <div class="clear"></div>
                                    <span>Home Screen</span>
                                </div>
                                <div class="screen_size small">
                                	<img src="images/android_72.jpg" data-size="72">
                                    <div class="clear"></div>
                                    <span>Push Icon</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <a href="publish2.php" class="make_app_next">next</a>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
      </div>
      <input type="file" id="uploadImgFile">
      <div class="testContainer">
      	
      	<img src="" id="testImg">

      </div>
      <div class="hint_main">?</div>
    </section>
  </section>
</section>
<script type="text/javascript" src="js/colpick.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.shapeshift.js" type="text/javascript"></script>
	 <script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
	 <script src="js/chosen.jquery.js"></script>
    <script src="js/ImageSelect.jquery.js"></script>
    <script src="js/publishJS.js"></script>
 
</body>
</html>
