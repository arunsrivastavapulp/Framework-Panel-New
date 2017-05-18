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
  <!--  <div class="banner">
    	<img src="images/ban-new2.png" class="active">
        <div class="bn-hld">
            <div class="bnt">Mobile Commerce is not the future, it is the now!</div>
        <div class="create_app">
            <a href="#">Create App</a>
            <em>Free</em>
        </div>
            
        </div>-->
        
        <div class="banner contpublish_create">
    	<img src="images/ban-new2.png" class="active">
    	
        <div class="banner_text" style="display:none">
        	<p>The future of mobile is the future<br>of everything.</p>
        </div>
        <div class="page-tabs">
        	<li ><a href="content-publishing-apps.php" >Content Publishing Apps</a></li>
            <li class="tab-active"><a href="retail-and-catalogue-app.php" >Retail and Catalogue Apps</a></li>
            <li><a href="enterprise-mobile-apps.php" >Enterprise Apps</a></li>
        </div>
        <div class="bn-hld">
            <div class="bnt">Mobile Commerce is not the future, it is the now!</div>
        <div class="create_app ">
            <a href="#">Create App</a>
            <em>Try for free!</em>
        </div>
        </div>
        
        
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
	<div class="about-us lnfix ecomm_video">
    	<h2>Retail and Catalogue Applications</h2>
        <p class="clear"> 
     Got something to sell? Take your business to the next level with a dedicated retail mobile app or showcase your products with a dedicated mobile catalogue app. Provide your customers the convenience of purchasing your products at their fingertips. Whether you own an export house, a niche fashion boutique, a superstore, or an exotic pet shop, your mobile commerce store is instantly ready with Instappy. You get run-time report generation to analyse and strategise product catalogue, along with inbuilt tools like unlimited push notifications to drive promos and offers.
        </p>
        <iframe width="369" height="241" src="https://www.youtube.com/embed/sci31UlAHrM"; frameborder="0" allowfullscreen></iframe>
    </div>
	</div>
</div>

<div class="big-contentpublish clearfix " id="ecmp">
        <ul class="box">
                <li><a href="#"><img src="images/tx-brd.png" alt=""/><span>Real-time Inventory Management</span><span>Our dynamic inventory management system will help you keep track of stock-in and stock-out. You can instantly update stock requirements and other details across your network so that all the involved parties can see it. </span></a></li>

                <li><a href="#"><img src="images/dollar.png" alt=""/><span>Secure Payment Gateway</span><span>Retail apps with Instappy come ready with the option of secure payment gateway integration without any set-up or maintenance cost. Your customers will love the convenience to make a purchase on the go.</span></a></li>

                <li><a href="#"><img src="images/chk2.png" alt=""/><span>Unlimited Product Categories</span><span>Add thousands of products and organise them under unlimited categories and subcategories. Your customers will be able to browse your products in a visually appealing and convenient way.</span></a></li>

                <li><a href="#"><img src="images/cloud-host-icon.png" alt=""/><span>Secure Cloud Hosting</span><span>With Instappy, you get secure cloud hosting and reliable backup utility so that your valuable data is in safe hands.</span></a></li>
                

                <li><a href="#"><img src="images/coupen-icon.png" alt=""/><span>Run Business Promotions and 
Offers for your Audience</span><span>Send out a push notification instantly to notify your audience of any special promos or exclusive offers you might be running.</span></a></li>
                
                
                <li><a href="#"><img src="images/mob1.png" alt=""/><span>Native Apps with Slick and 
Adaptive UI Design</span><span>You can create fully native retail or catalogue apps that are tailored according to different industry needs. </span></a></li>
        </ul>

</div>


<div class="sld">
<img src="images/bkg.png" class="bkimg" alt=""></img>

	<div class="main-bx"><div class="lft-sld"><span class="hd">General App Layout</span>
<p class="app-dsc">
	Take this as a general idea of how your fully native retail and catalogue app would look. You can check out the different screens to see the general layout, structure, flow, and look and feel of the app. After adding your products and product categories, you can still customise things like icons, colours, and fonts according to your preference.
</p>
</div>
<div class="ryt-sld"> 
<ul class="">
	<li><a href="#"><img src="images/Cart.png" alt=""></a></li>
	<li><a href="#"><img src="images/Cart_MyOrders.png" alt=""></a></li>
	<li><a href="#"><img src="images/Cart_MyProfile.png" alt=""></a></li>
    <li><a href="#"><img src="images/home.png" alt=""></a></li>
    <li><a href="#"><img src="images/second_page.png" alt=""></a></li>
     <li><a href="#"><img src="images/View_Product.png" alt=""></a></li>
    <li><a href="#"><img src="images/Wishlist.png" alt=""></a></li>

</ul>
</div>
</div>
</div>

<?php	$result = $checkapp->indexRetailTheme($url); 
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
		<div class="cat-head">
			<span style="padding-bottom: 15px;">Let’s get started. Choose your theme.</span>
			<span>Select from a range of industry specific themes and instantly build your own mobile store.</span>

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
       <!--<img class="wd" src="images/go-rib.png">-->
       <div class="col-ribbon">
       <i class="lnp"></i>
       	<span class="rib-data">Go the Mobile Commerce way and take <small class="rib-sec-dt">your business on mobile with a</small> <small>customised retail or catalogue mobile app.</small></span>
       		<span class="side-data">Turn your mobile business into a global mobile store with a dedicated retail app. Let your customers experience the complete range of your products on their palms.</span>
       </div>
    </div>


    <div class="stp clearfix">
                <span class="step">Take your business mobile in  <strong>6</strong>  easy steps:</span>
<ul class="st-list hg">
        <li><img src="images/6-1.png" alt=""/><span>Select your business category</span></li>
        <li><img src="images/6-2.png" alt=""/><span>Get the  desired look and feel by customising the interface</span></li>
        <li><img src="images/E-comm_08.png" alt=""/><span>Instantaneously, add as many products as you want and organise your product catalogue into categories</span></li>
        <li><img src="images/E-comm_09.png" alt=""/><span>You get dynamic inventory management to track stock-in and stock-out</span></li>
        <li><img src="images/E-comm_06.png" alt=""/><span>With ready-to-integrate payment gateway system, accept payments with no set-up or maintenance cost</span></li>
        <li><img src="images/E-comm_03.png" alt=""/><span>Promote and grow your mobile store to attract customers, market to new buyers, and keep them coming for more</span></li>

</ul>
        </div>
<script>		
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
