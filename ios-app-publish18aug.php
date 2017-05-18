<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
?>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner">
            <div class="how_publish">
            	<div class="how_publish_left ios-left"> <img src="images/Ios-App-Publish.png">
            <h1>Ios App Publish :</h1>
            <h2>Fields marked with * need to be filled before publishing.</h2>
          </div>
                <div class="clear"></div>
                <div class="how_publish_body">
                	<div class="how_publish_body_left">
                    	<div class="publish_content">
                            <h2>Version Information</h2>
                             <div class="publish_content_form">
                  <div class="publish_content_label">
                    <label>Name :</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input type="text" placeholder="Less then 255 characters.">
                  </div>
                  <div class="publish_content_info">
                    <p> The name of your app as it will appear on the App Store. This can't be longer than 255 characters. </p>
                  </div>
                  <div class="publish_content_label">
                    <label>Description :</label>
                  </div>
                  <div class="publish_content_textbox">
                    <textarea  style="height:230px" placeholder="Less then 4000 characters."></textarea>
                  </div>
                  <div class="publish_content_info">
                    <p>A description of your app, detailing features and functionality. It will also be used for your Apple Watch app.</p>
                  </div>
                  <div class="publish_content_label">
                    <label>Keywords:</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input type="text" placeholder="">
                  </div>
                  <div class="publish_content_info">
                    <p>One or more keywords that describe your app. Keywords make App Store search results more accurate. Separate keywords with a comma.</p>
                  </div>
                  <div class="publish_content_label">
                    <label>Support Url*:</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input type="text" placeholder="http://example.com">
                  </div>
                  <div class="publish_content_info">
                    <p> A URL with support information for your app. This URL will be visible on the App Store.</p>
                  </div>
                  <div class="publish_content_label">
                    <label>Marketting Url*:</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input type="text" placeholder="http://example.com">
                  </div>
                  <div class="publish_content_info">
                    <p>A URL with support information for your app. This URL will be visible on the App Store. </p>
                  </div>
                  <div class="publish_content_label">
                    <label>Privacy policy <br/>
                      Url*:</label>
                  </div>
                  <div class="publish_content_textbox">
                    <input type="text" placeholder="http://example.com">
                  </div>
                  <div class="publish_content_info">
                    <p> A URL that links to your organization's privacy policy. Privacy policies are required for apps that are Made for Kids or offer auto-renewable In-App Purchases or free subscriptions. They are also required for apps with account registration, apps that access a user’s existing account, or as otherwise required by law. Privacy policies are recommended for apps that collect user- or device-related data.</p>
                  </div>
                   </div>
                   
                   <!--image input-->
                   <div class="publish_content_form">
                   	   <ul class="brws">
                    <li> <span class="inch">750 x 1334Px (4.7 inch) </span> <img src="images/browse.jpg" alt=""/> </li>
                    <li> <span class="inch">1080 x 1920Px (5.5 inch)</span> <img src="images/browse.jpg" alt=""/> </li>
                    <li> <span class="inch">640 x 1136Px (4.0 inch)</span> <img src="images/browse.jpg" alt=""/> </li>
                    <li> <span class="inch">640 x 960Px (3.5 inch)</span> <img src="images/browse.jpg" alt=""/> </li>
                    <li> <span class="inch">1536 x 2048Px (Ipad)</span> <img src="images/browse.jpg" alt=""/> </li>
                  </ul>
                  <input type="file"/>
                 </div>
                   <!--image input ends-->         
                            <div class="publish_content_form">
                            	<p class="cate-heading">CATEGORIZATION</p>
                            	<div class="publish_content_label">
                                    <label>Category:</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <select>
                                    	<option>Select an application type</option>
                                    	<option>Dummy Content</option>
                                    	<option>Dummy Content</option>
                                    </select>
                                </div>
                                <div class="publish_content_label">
                                    <label>Secondary <br/> Category :</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <select>
                                    	<option>Select a category</option>
                                    	<option>Dummy Content</option>
                                    	<option>Dummy Content</option>
                                    </select>
                                </div>
                                 <div class="publish_content_info">
                                	<p>
                                    	The category that best describes this app. For more information, see the <a href="#">App Store Category Definitions</a>.
                                    </p>
                                </div>
                                 <div class="publish_content_label">
                                    <label>Rating :</label>
                                </div>
                                <div class="publish_content_textbox">
                                 <span class="edt"><a href="#">Edit</a></span>
                                </div>
                               
                                <div class="publish_content_info">
                                	<p>
                                    	<a href="#">Learn more about content rating.</a>
                                    </p>
                                </div>
                                <div class="publish_content_label full">
                    <label>Edit License Agreement</label>
                  </div>
                                <ul class="rad-list">
                    <li>
                      <input type="radio"/>
                      <span>Apply<a href="#">Apple's standard end user license agreement (EULA)</a> to all territories. </span> </li>
                    <li>
                      <input type="radio"/>
                      <span>Apply a custom EULA to all chosen territories. If you don't choose all territories, <a href="#">Apple's standard EULA</a> 
	will apply to all remaining ones. Custom EULAs must meet <a href="#">Apple's minimum terms</a>.</span> </li>
                  </ul>
                                <div class="publish_content_label">
                                    <label>Copyright* :</label>
                                </div>
                                <div class="publish_content_textbox">
                                     
                                        <input type="text" placeholder="Select application type"/>
                           		 </div>
                                 
                                 <div class="publish_content_info">
                                	<p>
                                    	The name of the person or entity that owns the exclusive rights to your app, preceded by the year the rights were obtained (for example, "2008 Acme Inc."). Do not provide a URL.
                                    </p>
                                </div>
                                 
                                 <div class="publish_content_label full">
                    <label>Trade Representative Contact Information</label>
                  </div>
                                 <ul class="rad-list">
                    <li>
                      <input type="radio"/>
                      <span>Display Trade Representative Contact Information on the Korean App Store.</span> </li>
                  </ul>
                                 
                            </div>
                            
                            
                            
                            <div class="publish_content_form">
                            	<p class="cate-heading">Select the availability date & price tier for your App
</p>
                            	<div class="publish_content_label">
                                    <label>Availibility <br/> Date:</label>
                                </div>
                                <div class="publish_content_textbox">
                                    <input type="text" placeholder="Dummy Content">
                                </div>
                                <div class="publish_content_info">
                                	<p>
                                    	The date your app will be avaialble in appstore
                                    </p>
                                </div>
                                <div class="publish_content_label">
                                    <label>Price <br/> Tier:</label>
                                </div>
                                <div class="publish_content_textbox" style="position:relative">
                                    <input type="text" placeholder="Dummy Content">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <div class="publish_content_info">
                                	<p>
                                    	<span class="edt"><a href="#">View Pricing Matrix</a></span>
                                    	The level that determines both the customer price as well as your proceeds (which is the net price after taxes, where applicable, have been taken out). <br/>
If your app is free, select Free. If you are charging for your app, you must have a paid commercial agreement in place before you can sell in your chosen tier.
                                    </p>
                                </div>
                               <span class="full" style="font-size:14px">*by default all countries are selected</span>
                                 
                            </div>
                            
                            <div class="developer_account">
	                            <h3>Please make us the admin of the appstore account by just adding the following email

and give us the admin rights, by following process
</h3>
                                <div class="developer_account_info">
                                	<p>You need to <a href="mailto:invite support@instaappy.com">invite support@instaappy.com</a> to use your Android dev account as an 
administrator.
</p>
                                    <ul>
                                    	<li> Visit <a href="https://itunesconnect.apple.com" target="_blank">https://itunesconnect.apple.com</a></li>
                                        <li> Sign in using your apple developer account.</li>
                                        <li>Go to "users and roles" section.</li>
                                        <li>Tap on + icon appearing on right side of “users” text.</li>
                                        <li>On the next page you will see title "Add iTunes Connect User”, Enter First Name, Last Name and Email id as following :- (We will provide the details to be entered for these fields).</li>
                                        <li>You may see this warning :- This email address is already associated with an Apple ID. After you add this user, they can use this existing Apple ID and password to sign in to iTunes Connect. Click on Next.</li>
                                        <li>Check on “Admin” under select roles. Click on Next.</li>
                                        <li>On the right hand side, select "All Territories”.Click on Save.</li>
                                        
                                    </ul>
                                </div>
                                
                            </div>
                        <a href="#" class="make_app_next" style="font-size:23px">Finish</a>
                        <div class="clear"></div>
                        </div>
                    </div>
                	<div class="how_publish_body_right">
                    	<div class="common_publish_right_box">
                        	<h2>Let us help you !</h2>
                            <p>Need any help at any point, Let us guide you till the end.</p>
                            <a href="#">Give a Call</a>
                            <div class="clear"></div>
                        </div>
                    	<div class="common_publish_right_box">
                        	<h2>Need More Help ?</h2>
                            <p>For better view on the topic visit Devloper Console.</p>
                            <a href="#">Go to Developer Console</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  </section>
</section>
<script>window.jQuery || document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"><\/script>')</script> 
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script>
	(function($){
		$(window).load(function(){
			$("#content-1").mCustomScrollbar();
			$("#content-2").mCustomScrollbar();
			
			
		});
	})(jQuery);
</script>
	<script src="js/chosen.jquery.js"></script>
    <script src="js/ImageSelect.jquery.js"></script>
    <script>
    $(".my-select").chosen();
    </script>
    <script>
	   $(document).ready(function(e) {
        	$(".paid").click(function(){
				 $(".paid").removeClass("paid_active")
				$(this).addClass("paid_active")
				
			});
    });
	</script>
    
    <!--dpk script--> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script>
    $(document).ready(function(){
		$('.brws li').click(function(){
			$(this).parent().next('input[type="file"]').trigger('click');
			});
		});
    </script>
</body>
</html>
