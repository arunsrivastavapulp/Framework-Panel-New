<?php require_once('website_header.php');?> 
<?php
session_start();
require_once('config/db.php');
require_once('modules/checkapp/checkappclass.php');
$checkapp = new checkapp();

$url = $checkapp->siteurl();

//$realescape = $mysqli->real_escape_string("SELECT * FROM `action_type`");
//$query = $mysqli->query($realescape);
//$result = mysqli_fetch_assoc($query);
unset($_SESSION['appid']);
?>   
<!--<div class="banner contpublish">
    	<img class="active" src="images/ban-conten-publising.png">
        <div class="bn-hld">
            <div class="bnt">Mobile is the new hub for business and more Instantly go mobile with Instappy.</div>
        <div class="create_app">
            <a href="#">Create App</a>
            <em>Try for free</em>
        </div>
            
        </div>
        <div class="lets_talk slide">
        	<div class="lets_talk_btn"> </div>
        	<form class="lets_talk_form">
            	<input type="text" placeholder="Name">
                <div class="two_input">
	            	<input type="text" placeholder="Email">
	            	<input type="text" placeholder="Phone No.">
                </div>
                <div class="two_input">
	            	<input type="text" placeholder="Organisation Name">
	            	<input type="text" placeholder="Organisation Name">
                </div>
                <input type="text" placeholder="Industry">
                <input type="text" placeholder="Types of Apps">
                <input type="text" placeholder="additional Info.">
                <input type="button" value="send">
            </form>
        </div>
    </div>
    -->
    
    <div class="banner contpublish_create">
    	<img src="images/ban-conten-publising.png" class="active">
    	
        <div class="banner_text" style="display:none">
        	<p>The future of mobile is the future<br>of everything.</p>
        </div>
        <div class="page-tabs">
        	<li class="tab-active"><a href="content-publishing-apps.php" >Content Publishing Apps</a></li>
            <li><a href="retail-and-catalogue-app.php" >Retail and Catalogue Apps</a></li>
            <li><a href="enterprise-mobile-apps.php" >Enterprise Apps</a></li>
        </div>
        <div class="bn-hld">
            <div class="bnt">Mobile is the new hub for business and more.<br/> Instantly go mobile with Instappy.</div>
        <div class="create_app ">
            <a href="#">Create App</a>
            <em>Try for free!</em>
        </div>
        </div>
     <!--  <div class="create_app_content_pub">
        <div class="create_app">
        	<a href="javascript:void()">Create App</a>
            <em>Free</em>
        </div>-->
             <div class="lets_talk slide">
        	<div class="lets_talk_btn"> </div>
        	<form class="lets_talk_form" name="lets_talk" id="lets_talk" method="post" action="">
				<div class="cmn">
					<input type="text" class="required" onKeyPress="return isAlphanumeric(event, this);" maxlength="100" placeholder="Name" id="lets_talk_name" name="lets_talk_name" value="" onKeyPress="return isAlphabet(event,this)">
                </div>
				<div class="two_input">
					<div class="cmn">
						<input type="text" class="required email" maxlength="100" placeholder="Email" id="lets_talk_email" name="lets_talk_email" value="">
	            	</div>
					<div class="cmn">
						<input type="text" class="required" maxlength="15" minlength="10" placeholder="Phone No." id="lets_talk_phone" name="lets_talk_phone" value="" onKeyPress="return isNumber(event)">
					</div>	
                </div>
                <div class="two_input">
	            	<div class="cmn">
						<input type="text" class="required" maxlength="100" placeholder="Organisation Name" id="lets_talk_org" name="lets_talk_org" value="">
	            	</div>
					<div class="cmn">
						<select  class="required" id="lets_talk_org_size" name="lets_talk_org_size" value="">
							<option value=''>Organisation Size</option>
							<option value='0-10 Employees'>0 - 10 Employees</option>
							<option value='10-50 Employees'>10 - 50 Employees</option>
							<option value='50-100 Employees'>50 - 100 Employees</option>
							<option value='100-500 Employees'>100 - 500 Employees</option>
							<option value='Above-500 Employees'>Above 500 Employees</option>
						</select>
					</div>
                </div>
				<div class="cmn">
					<select  class="required" id="lets_talk_industry" name="lets_talk_industry" value="">
						<option value=''>Select Industry</option>
						<option value="Agriculture">Agriculture</option>

						<option value="Accounting">Accounting </option>

						<option value="Advertising">Advertising </option>

						<option value="Aerospace">Aerospace </option>

						<option value="Aircraft">Aircraft </option>

						<option value="Airline">Airline </option>

						<option value="Apparel & Accessories">Apparel & Accessories </option>

						<option value="Automotive">Automotive </option>

						<option value="Banking">Banking </option>

						<option value="Broadcasting">Broadcasting </option>

						<option value="Brokerage">Brokerage </option>

						<option value="Biotechnology">Biotechnology </option>

						<option value="Call Centres">Call Centres </option>

						<option value="Cargo Handling">Cargo Handling </option>

						<option value="Chemical">Chemical </option>

						<option value="Computer">Computer </option>

						<option value="Consulting">Consulting </option>

						<option value="Consumer Products">Consumer Products </option>

						<option value="Cosmetics">Cosmetics </option>

						<option value="Defence">Defence </option>

						<option value="Department Stores">Department Stores </option>

						<option value="Education">Education </option>

						<option value="Electronics">Electronics </option>

						<option value="Energy">Energy </option>

						<option value="Entertainment & Leisure">Entertainment & Leisure </option>

						<option value="Executive Search">Executive Search </option>

						<option value="Financial Services">Financial Services </option>

						<option value="Food, Beverage & Tobacco">Food, Beverage & Tobacco </option>

						<option value="Grocery">Grocery </option>

						<option value="Health Care">Health Care </option>

						<option value="Inernet Publishing">Internet Publishing </option>

						<option value="Investment Banking">Investment Banking </option>

						<option value="Legal">Legal </option>

						<option value="Manufacturing">Manufacturing </option>

						<option value="Motion Picture & Video">Motion Picture & Video </option>

						<option value="Music">Music </option>

						<option value="Newspaper Publishers">Newspaper Publishers </option>

						<option value="Online Auctions">Online Auctions </option>

						<option value="Pension Funds">Pension Funds </option>

						<option value="Pharmaceuticals">Pharmaceuticals </option>

						<option value="Private Equity">Private Equity </option>

						<option value="Publishing">Publishing </option>

						<option value="Real Estate">Real Estate </option>

						<option value="Retail & Wholesale">Retail & Wholesale </option>

						<option value="ecurities & Commodity Exchanges">Securities & Commodity Exchanges </option>

						<option value="Service">Service </option>

						<option value="Soap & Detergent">Soap & Detergent </option>

						<option value="Software">Software </option>

						<option value="Sports">Sports </option>

						<option value="Technology">Technology </option>

						<option value="Telecommunications">Telecommunications </option>

						<option value="Television">Television </option>

						<option value="Transportation">Transportation </option>

						<option value="Trucking">Trucking </option>

						<option value="Venture Capital">Venture Capital </option>

					</select>
				</div>
				<div class="cmn">
					<select  class="required" id="lets_talk_app_type" name="lets_talk_app_type" value="">
						<option value=''>Type of App</option>
						<option value='Content Publishing App for SMB'>Content Publishing App for SMB</option>
						<option value='Visually Appealing Publishing App'>Visually Appealing Publishing App</option>
						<option value='Retail Catalogue App'>Retail Catalogue App</option>
						<option value='Retail App with payment gateway'>Retail App with payment gateway</option>
						<option value='Marketing campaign tracker App'>Marketing campaign tracker App</option>
						<option value='App for Sales Team Tracking'>App for Sales Team Tracking</option>
						<option value='Customized Applications'>Customized Applications</option>
					</select>
				</div>
				<div class="cmn">
					<input type="text" placeholder="Additional Info/Message" class="required" maxlength="250" id="lets_talk_additional" name="lets_talk_additional" value="">
                </div>
				<div class="cmn">
					<input type="checkbox" class="required" name="agree" checked="checked"/><small>I allow you to contact me via phone calls and electronic methods.</small>
                </div>
				<input type="submit" value="SUBMIT"> 
            </form>
        </div>
    </div>
    <div class="clear"></div>



<div class="about_instappy clearfix">
	<div class="about-us lnd content_video">
    	<h2>Content Publishing Apps	</h2>
        <p class="clear"> 
       Designers, universities, photographers, artists, restaurants, and even magazines can now build customer loyalty by making it easier for them to stay connected! Instappy makes it possible to create affordable, high quality native database-led applications in less than 48 hours across platforms, while opening up your business to a wealth of customer-base and business expansion. Go the extra mile by offering valuable information, inviting loyalty programs, exclusive discounts, and convenient tools. Select from a wide variety of mobile app categories and amazing templates to build your own mobile app in an instant. With superior content publishing and designing capabilities, Instappy will ensure that your vision is translated into a stunning mobile reality. </p>
        <iframe width="369" height="241" src="https://www.youtube.com/embed/sci31UlAHrM"; frameborder="0" allowfullscreen></iframe>
    </div>
</div>

<div class="big-contentpublish clearfix">
        <ul class="box">
                <li><a href="#"><img src="images/ContentPublishing-sharing.png" alt=""/><span>Native Apps with Slick and 
Adaptive UI Design</span><span>Create native mobile applications that do not require any coding knowledge. Your audience will get a seamless brand experience through our slick and adaptive UI design, optimum for Android devices, iPhone or iPad. </span></a></li>

                <li><a href="#"><img src="images/ContentPublishing-bages.png" alt=""/><span>Utilities, Tools, and 
Social API's Included</span><span>Through rich media widgets and a host of API’s, you can make your app function the way you want it to. Live social media updates allow you to  keep your social network connected 24*7.</span></a></li>

                <li><a href="#"><img src="images/ContentPublishing-setting.png" alt=""/><span>Built-in CMS for 
Unlimited Updates</span><span>Our built-in CMS will allow you to instantly update your content without any delay. Add rich media images, videos, text, and all the content that you want your audience to explore.</span></a></li>

                <li><a href="#"><img src="images/ContentPublishing-chart.png" alt=""/><span>Smart Analytics – 
Live Stats Reporting</span><span>Take advantage of real-time analytics and take smarter, data-oriented decisions.</span></a></li>

                <li><a href="#"><img src="images/ContentPublishing-coupen-icon.png" alt=""/><span>Run Business Promotions and 
Offers for your Audience</span><span>Send out a push notification instantly to notify your audience of any special promos or exclusive offers you might be running.</span></a></li>
                
                
                <li><a href="#"><img src="images/cloud-host-icon.png" alt=""/><span>Secure Cloud Hosting</span><span>With Instappy, you get secure cloud hosting and reliable backup utility so that your valuable data is in safe hands.</span></a></li>
                
                 <li><a href="#"><img src="images/blk-icon.png" alt=""/><span>Unlimited Customisation</span><span>You can customise everything from the layout of the app, to colours, icons, splash screens, and everything else you want to.</span></a></li>
                 
                 <li><a href="#"><img src="images/ContentPublishing-mobile.png" alt=""/><span>On-device Trial</span><span>With Instappy, you get free on-device trial for a period of 30 days for as many apps as you want to create. Test out the app on multiple devices and create the perfect experience through your mobile app.</span></a></li>
        </ul>

</div>

</div>
<?php	$result = $checkapp->indexCatTheme($url); 
	//echo "<pre>"; print_r($result); echo "</pre>";
?>
<div class="cat-cont clearfix" id="createApp">
	<div class="cat-lft">
		<span class="cat-hd">Categories</span>
		<ul class="cat-list">
				<!-- <li><div class="uprdiv"><em></em>Featured <i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li>
				<li><div class="uprdiv">Restraunt<i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li>
				<li><div class="uprdiv">Business & services <i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li>
				<li><div class="uprdiv">Creative <i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li>
				<li><div class="uprdiv">Publishing <i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li> -->
				<?php foreach ($result['catli'] as $key => $value) {
					echo $value;
				}?>
		</ul>

	</div>
	<div class="cat-ryt">
		<div class="cat-head contant-publishing">
			<span>Featured Templates</span>
			<span>Take your pick from some of our most popular templates, or choose 
what suits your business the best. You can also build your app from scratch. Just select 
an option and start customising with all the changes to suit your style.</span>

			<!-- <ul class="sht-lst">
				<li>
					<a href="#">
						<img src="images/1.png" alt="">
						<span>Name 1</span>
					</a>
                    <div>
                    	<a href="#">Create App</a>
                    </div>
				</li>
				<li>
					<a href="#">
						<img src="images/2.png" alt="">
						<span>Name 1</span>
					</a>
                    <div>
                    	<a href="#">Create App</a>
                    </div>
				</li>
				<li>
					<a href="#">
						<img src="images/1.png" alt="">
						<span>Name 1</span>
					</a>
                    <div>
                    	<a href="#">Create App</a>
                    </div>
				</li>
				<li>
					<a href="#">
						<img src="images/1.png" alt="">
						<span>Name 1</span>
					</a>
                    <div>
                    	<a href="#">Create App</a>
                    </div>

				</li>
				<li>
					<a href="#">
						<img src="images/2.png" alt="">
						<span>Name 1</span>
					</a>
                    <div>
                    	<a href="#">Create App</a>
                    </div>
				</li>
				<li>
					<a href="#">
						<img src="images/1.png" alt="">
						<span>Name 1</span>
					</a>
                    <div>
                    	<a href="#">Create App</a>
                    </div>
				</li>

			</ul> -->
			<?php foreach ($result['themes'] as $key2 => $value2) {
                    echo $value2;
                }
			?>
		</div>

	</div>

</div>



	<div class="bl-bg">
        <div class="contpublish-grow">
        	<div>
                <p>
                   <span>Grow your audience</span> and stay connected with<small> them 24*7 with a <span>customised mobile app.</span></small>
                </p>
            </div>
            <img src="images/ContentPublishingright_img.png"><br/>
            <p>
            	Take your business the app way with a fully customised mobile app. With superior content publishing and designing capabilities, Instappy will ensure that your vision is translated into a stunning mobile reality.
            </p>
        </div>
        <div class="contpublish-growImg">
        	<img src="images/ContentPublishing-soical.png" />
        </div>
    </div>

    






	
 <div class="stp clearfix">
                <span class="step contpublisb-step">Take your business mobile in  <strong>6</strong>  easy steps:</span>
<ul class="st-list">
        <li><img src="images/st1.png" alt=""/><span>Select the category that suits you the best</span></li>
        <li><img src="images/ContentPublishing_layout.png" alt=""/><span>Create your high quality UI design with full customisation options</span></li>
        <li><img src="images/ContentPublishing-bages.png" alt=""/><span>Drag and drop<br/> rich media widgets <br/>and other APIs for desired functionality</span></li>
        <li><img src="images/ContentPublishing_document.png" alt=""/><span>Add all of your content in a structured and seamless manner</span></li>
        <li><img src="images/ContentPublishing_ios.png" alt=""/><span>You can now publish your app across devices on all the leading 
platforms</span></li>
        <li><img src="images/ContentPublishing_yellowsharing.png" alt=""/><span>Engage audience with your own native mobile app, 
built for success</span></li>

</ul>
        </div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.innr-list span a').click(function() {
			$(this).parent().children('img').show();
			var current = $(this).attr('href');
			$(this).parent().addClass('act').siblings().removeClass('act');
			
			if(current == '#sht-featured'){
				$(current).fadeIn().siblings('ul.sht-lst').hide();
			}
			else
			{
				$("#screenoverlay").css("display","block");
				var ID = current.replace('#sht-', '');
				jQuery.ajax({
					url: BASEURL + 'modules/checkapp/get_themes.php',
					type: "post",
					data: {'ID' : ID,'type' : 'panel' },
					success: function(response){
						if(response!='error'){							
							$("#screenoverlay").css("display","none");
							$(current).html(response);
							$(current).fadeIn().siblings('ul.sht-lst').hide();
						}
						else{
							//console.log('OOps Something went wrong.');
							$("#screenoverlay").css("display","none");						
						}
					},
					error:function(){
						//console.log('There is error while submit.');
						
					}                
				}); 
			}
			return false
		});
	});
	/* Edited By Varun */
	function isAlphanumeric(evt, input) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if ((charCode >= 48 && charCode <= 57) || (charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8) || (charCode == 32)) {
			return true;
		}
		return false;

	}
</script>
<?php require_once('website_footer.php');?>


