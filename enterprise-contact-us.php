<?php require_once('website_header.php');?> 
<div class="clear"></div>
<div class="brdcrm">
  <div class="big-cont mt0">
    <div class="crm"> <a href="index.php">Home</a><span> >> </span><a href="Landing_Page_Enterprise.php">Enterprise</a><span> >> </span><a href="#" class="active-crum">Contact Us</a> </div>
  </div>
</div>

<div class="big-cont mt0 suc">
	<div class="contact_us">
    	<div class="contact_us_left">
        	<h2>Say hello</h2>
            <p>For your business queries let us call you now!</p>
			<p>We request you to mention the name of your organization and city of base for all business queries. Correct Information will help our strategists provide a speedy and relevant revert.</p>
			<p id="success"></p>
            <form class="contact_us_form" method="post">
            	<div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Name*</label>
                    </div>
                    <div class="contact_form_text">
	                    <input type="text" placeholder="First Name"  maxlength="50" name="first_name" class="required">
                    </div>
                </div>
            	<!--<div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Last Name*</label>
                    </div>
                    <div class="contact_form_text">
	                    <input type="text" placeholder="Last Name" maxlength="50" name="last_name" class="required">
                    </div>
                </div>-->
            	<div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Email*</label>
                    </div>
                    <div class="contact_form_text">
	                    <input type="email" placeholder="Email Address" maxlength="100" name="bussiness_email" class="required email">
                    </div>
                </div>
            	
            	<div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Phone No*</label>
                    </div>
                    <div class="contact_form_text">
	                    <input type="text" placeholder="Phone Number" name="phone" class="required"  maxlength="12" minlength="10" onKeyPress="return isNumber(event)">
                    </div>
                </div>
                <div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Organisation Name*</label>
                    </div>
                    <div class="contact_form_text">
	                    <input type="text" placeholder="Company Name" name="company_name" maxlength="150" class="required">
                    </div>
                </div>
            	
                <div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Organisation Size*</label>
                    </div>
                    <div class="contact_form_text">
	                    <select name="og_size" class="required">
                        	<option value=''>Organisation Size</option>
							<option value='0-10'>0 - 10</option>
							<option value='10-50'>10 - 50</option>
							<option value='50-100'>50 - 100</option>
							<option value='100-500'>100 - 500</option>
							<option value='Above-500'>Above 500</option>
                        </select>
                    </div>
                </div>
                <div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Industry*</label>
                    </div>
                    <div class="contact_form_text">
	                    <select name="industry" class="required">
                        	<option value=''>Industry</option>
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

							<option value="ernet Publishing">Internet Publishing </option>

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
                </div>
                <div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Type of App*</label>
                    </div>
                    <div class="contact_form_text">
	                    <select name="type_app" class="required">
                        	<option value=''>Type of App</option>
							<option value='Marketing campaign tracker App'>Marketing campaign tracker App</option>
							<option value='Sales Team Tracker App'>Sales Team Tracker App</option>
							<option value='Customized Applications'>Customized Applications</option>
                        </select>
                    </div>
                </div>
                
                <div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Your Message</label>
                    </div>
                    <div class="contact_form_text">
	                    <textarea placeholder="Type your message" name="message" maxlength="150"></textarea>
                        <input type="submit" name="submit" id="submit" value="Call me now!"/>
                    </div>
                </div>
            </form>
        </div>
    	<div class="contact_us_right">
        	<div class="add_email">
            	<div class="add_email_box">
                	<div class="add_email_img">
                    	<img src="images/add_icon.png">
                    </div>
                	<div class="add_email_text">
                    	<h2>Address:</h2>
                        <p>Plot No: 48, 2nd Floor, Okhla Industrial Estate, Phase III, New Delhi, 110020</p>
                    </div>
                    <div class="clear"></div>
                </div>
            	<div class="add_email_box">
                	<div class="add_email_img">
                    	<img src="images/email_icon.png">
                    </div>
                	<div class="add_email_text">
                    	<h2>Email:</h2>
                        <p>contact@instappy.com</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="newsletter">
            	<!--<h2>Subscribe to our newsletter</h2>
                <p>Subscribe to our newsletter for regular update s (read our <a href="privacy-policy.php">privacy policy</a>). We invite articles and views on everything marketing. We promise to never ever ever spam you!!</p>
				<p id="sub_success"></p>
				<form class="subscribe_form" method="post">
                <input type="text" id="subscribe_email" class="required email" placeholder="email address">
				<input type="submit" name="submit" id="submit" value="Subscribe"/>    
				</form>			-->	
            </div>
        </div>
    </div>

</div>
</div>
<?php require_once('website_footer.php');?>
<script type="text/javascript" src="js/jquery1.4.4.min.js"></script>
<script type="text/javascript" src="js/validate.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
	$(".contact_us_form").validate({
			submitHandler: function(form) {
				$('#user_profile .preloader').show();
				$("#screenoverlay").css("display","block");
				jQuery.ajax({
					url:'ajax.php',
					type: "post",
					data: $(form).serialize()+"&type=contact-us",
					success: function(response){
						if(response=='success'){							
							$("#screenoverlay").css("display","none");
							$(".contact_us_form")[0].reset();
							$("#success").text("Thanks for contacting us .");
							$("#success").addClass("success");
						}
						else{
							$(".contact_us_form")[0].reset();
							$("#screenoverlay").css("display","none");	
							$("#success").addClass("fails");
							$("#success").text("Opps Something went wrong.Try again later.");							
						}
					},
					error:function(){
						$("#success").text('There is error while submit.');
						
					}                
				});       
			}
		});
			$(".subscribe_form").validate({
			submitHandler: function(form) {
				email=$("#subscribe_email").val();
				$("#screenoverlay").css("display","block");
				jQuery.ajax({
					url:'ajax.php',
					type: "post",
					data: "email="+email+"&type=subscribe",
					success: function(response){
						if(response=='success'){							
							$("#screenoverlay").css("display","none");
							$(".subscribe_form")[0].reset();
							$("#sub_success").text("Thanks for subscribing.");
							$("#sub_success").addClass("success");
						}
						else{
							$(".subscribe_form")[0].reset();
							$("#screenoverlay").css("display","none");	
							$("#sub_success").addClass("fails");
							$("#sub_success").text("Opps Something went wrong.Try again later.");							
						}
					},
					error:function(){
						$("#sub_success").text('There is error while submit.');						
					}      
							});       
			}
		});
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