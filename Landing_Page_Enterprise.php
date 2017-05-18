<?php require_once('website_header.php');?> 
    
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
    
    <div class="banner contpublish_create enterprise_create">
    	<img src="images/ban_Enterprise.png" class="active">
    	
        <div class="banner_text" style="display:none">
        	<p>The future of mobile is the future<br>of everything.</p>
        </div>
        <div class="page-tabs">
        	<li><a href="content-publishing-apps.php" >Content Publishing Apps</a></li>
                            	<li><a href="retail-and-catalogue-app.php" >Retail and Catalogue Apps</a></li>
                            	<li  class="tab-active"><a href="enterprise-mobile-apps.php" >Enterprise Apps</a></li>
        </div>
        <div class="bn-hld">
            <div class="bnt">Mobile can smartly mobilise your <br/> fieldforce in an instant.</div>
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
					<input type="text" class="required" maxlength="100" onKeyPress="return isAlphanumeric(event, this);" placeholder="Name" id="lets_talk_name" name="lets_talk_name" value="" onKeyPress="return isAlphabet(event,this)">
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
	<div class="about-us lnd enterprises_video">
    	<h2>Enterprise Apps</h2>
        <p class="clear"> 
       With Instappy’s enterprise solutions, you can now streamline your on-field initiatives and better manage your mobile workforce. Get two white label solutions- Sales Team Tracker and Campaign Tracker. Manage complete tasks like team management, on-the-go attendance and location tracking, and inventory as well as order management. Add transparency, control, and accountability to your business with your own Enterprise Mobile Application. Develop a one-stop-solution to streamline track resources, increase management visibility, and improve organisational efficiency. Get the most effective business tracker for your business, only with Instappy. </p>
        <iframe width="369" height="241" src="https://www.youtube.com/embed/sci31UlAHrM"; frameborder="0" allowfullscreen></iframe>
    </div>
</div>

<div class="big-contentpublish clearfix">
        <ul class="box enterprise-box clearfix">
                <li><a href="#"><img src="images/Enterprise_location.png" alt=""/><span>Attendance and Location Tracking</span><span>With Instappy’s GPS-based location tracking, you can keep a track of your entire mobile work-force and get regular updates of all their activities. You will significantly reduce the time and effort invested in resource tracking and management.</span></a></li>

                <li><a href="#"><img src="images/Enterprise_speak.png" alt=""/><span>On-the-go MIS Reporting</span><span>With on-the-go MIS reporting, you can achieve increased agility of management and operations as necessary information is readily available to everyone in the network.</span></a></li>

                <li><a href="#"><img src="images/Enterprise_note.png" alt=""/><span>Superior Data Management</span><span>As everything is updated in real-time in a systematic and organised manner, you can streamline processes and establish clear communication channels.</span></a></li>

                <li><a href="#"><img src="images/Enterprise_sharing.png" alt=""/><span>Seamless Order Management</span><span>You can identify opportunities and leads, issue quotations, record every client dealing in the sales cycle, all while on-the-move.</span></a></li>

                <li><a href="#"><img src="images/cloud-host-icon.png" alt=""/><span>Secure Data Hosting</span><span>With Instappy, you get secure cloud hosting and reliable backup utility so that your valuable data is in safe hands.</span></a></li>
                
                
                <li><a href="#"><img src="images/Enterprise_mobile.png" alt=""/><span>Native Apps with Slick and 
Adaptive UI Design</span><span>Enterprise apps with Instappy are fully native and have a smooth and adaptive interface that will ensure a convenient experience for your mobile fieldforce.</span></a></li>
                
               
        </ul>

</div>

</div>

<div class="home-content enterprise-home clearfix" id="createApp">
        <div class="home-tabs scr">
            <li class="tab-active"><a href="#sft">Sales Team Tracker</a></li>

            <li class="tab-active"><a href="#ct">Campaign Tracker</a></li>
        </div>
        <div class="content1">  
            <div class="msl">
                <i class="mac"></i>
                <div class="mob">
                   <div class="msld">
                       
                        <ul class="msld-sl">
                        <li><a href="#"><img src="images/campaign-1.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/campaign-2.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/campaign-3.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/campaign-4.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/campaign-5.png" alt="" /></a></li>                         
                         <li><a href="#"><img src="images/campaign-6.png" alt="" /></a></li>                         
                         <li><a href="#"><img src="images/campaign-7.png" alt="" /></a></li>                         
                         <li><a href="#"><img src="images/campaign-8.png" alt="" /></a></li>                         
                         <li><a href="#"><img src="images/campaign-9.png" alt="" /></a></li>                         
                    </ul>
                   </div>

                </div>
            </div>
            <div>
                <div class="featureSymbol" >
                   <div>
                     <i class="fa fa-code lesssymbol"></i>
                   </div> 
                 </div>
                 <div class="content-text" id="sft">
                    <h2>Sales Team Tracker</h2>
                    <p class="clear">
                       Instappy’s Sales Team Tracker is a comprehensive solution that enables you to stay on top of your sales teams at all times. Manage multiple sales teams at once in a structured, transparent, and efficient manner. 
With Sales Team Tracker, you can create single point of access to information, streamlining processes that currently involve multiple software applications. Your salesforce will have instant access to all the necessary account information, past interactions with the client, and the key people in the relationship. Through a simple interface, they can issue quotations, update their accounts, and manage their databases, right from opportunities to closures.
<ul>
	<li>Account management</li>
	<li>Effective forecasting</li>
	<li>Order management</li>
	<li>Meeting tracker</li>
	<li>Stay connected to your fieldforce 24*7 </li>
	<li>Better reporting of sales efforts and conversions</li>
</ul>


                    </p>

                    <small><br/>Download Demo</small>
                    <div class="home-button tr clear">
                        <a href="https://play.google.com/store/apps/details?id=com.pulp.campaigntracker&hl=en"><img src="images/play.png"></a>
                        <a href="Landing_Page_salesteam_tracker.php">Learn More</a> 
                    </div>
               </div> 
             
            </div>
           
        
        </div>
        <hr class="clear" style="margin-bottom: 5%;">
        <div class="content1 content2"> 
            <div class="msl">
                <i class="mac"></i>
                <div class="mob">
                   <div class="msld">
                       
                        <ul class="msld-sl">
                        <li><a href="#"><img src="images/SFT-1.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/SFT-2.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/SFT-3.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/SFT-4.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/SFT-5.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/SFT-6.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/SFT-7.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/SFT-8.png" alt="" /></a></li>
                         <li><a href="#"><img src="images/SFT-9.png" alt="" /></a></li>
                    </ul>
                   </div>

                </div>
            </div>
            <div>
                <div class="featureSymbol">
                   <div>
                     <i class="fa fa-code lesssymbol"></i>
                   </div> 
                 </div>
                 <div class="content-text" id="ct">
                    <h2>Campaign Tracker</h2>
                    <p class="clear">
                      Instappy’s Campaign Tracker is a campaign management solution that empowers the marketers to track, manage and communicate effectively and effortlessly with its team involved in a campaign, irrespective of their locations. 
The Campaign Tracker covers communication, tracking and location of the fieldforce working on a particular campaign, ensuring private and secure data sharing like campaign details, fieldforce details, location, images and forms. The campaign tracker blends application and intelligence to give a cutting edge experience that automatically tracks an activity and proposes robust integration of the marketers with their campaigns.
<ul>
	<li>Efficiency in operations</li>
	<li>Effective monitoring of mobile resources</li>
	<li>Reduced time on MIS</li>
	<li>Better reporting</li>
	<li>On-the-go training</li>
	<li>No overlapping of efforts</li>
</ul>





    
                    </p>
                    <small><br/>Download Demo</small>
                    <div class="home-button tr clear">
                        <a href="https://play.google.com/store/apps/details?id=com.pulp.campaigntracker&hl=en"><img src="images/play.png"></a>
                        <a href="campaign-tracker.php">Learn More</a> 
                    </div>
               </div> 
             
            </div>
            
            
           
        
        </div>
    </div>



	<div class="bl-bg">
        <div class="contpublish-grow enterprise-grow">
        	<div>
                <p>
                   Manage your mobile workforce better and <small>drive success for your company with a customised</small>
                   <small>mobile enterprise app.</small>
                </p>
            </div>
            <img src="images/ContentPublishingright_img.png"><br/>
            <p >
            	Automate tasks like attendance management, location tracking of your mobile workforce, and MIS reporting with Instappy's Campaign Tracker. 
            </p>
            <p>
            	Instappy's Sales Team Tracker will help your on-field sales team in recording and accessing necessary account information and help you keep a track of all of their activities throughout the sales process.
            </p>
        </div>
        <div class="contpublish-growImg enterprise-growImg">
        	<img src="images/Enterprise_border_white.png"/>
        </div>
    </div>

    






	
 <div class="stp clearfix">
                <span class="step contpublisb-step">Streamline your fieldforce management in <strong>6</strong> easy steps:</span>
<ul class="st-list">
        <li><img src="images/Enterprise_mobile.png" alt=""/><span>Assess your needs and get your white-label mobile enterprise app</span></li>
        <li><img src="images/Enterprise_pc.png" alt=""/><span>Integrate your mobile workforce in the web panel and get things started</span></li>
        <li><img src="images/Enterprise_people_line.png" alt=""/><span>Establish the organisational chart and assign roles and 
responsibilities</span></li>
        <li><img src="images/Enterprise_manger.png" alt=""/><span>Set up tasks and provide necessary information for all the relevant 
people to see</span></li>
        <li><img src="images/Enterprise_sharing.png" alt=""/><span>Go to market through a cloud-based web panel and mobile app, 
streamlining everything from processes to flow of information</span></li>
        <li><img src="images/Enterprise_speak.png" alt=""/><span>Take smart, data-oriented decisions through features like custom 
analytics and on-the-move reporting  </span></li>

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