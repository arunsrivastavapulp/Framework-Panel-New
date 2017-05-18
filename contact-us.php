<?php require_once('website_header.php');?> 
<div class="clear"></div>
<div class="brdcrm">
  <div class="big-cont mt0">
    <div class="crm"> <a href="index.php">Home</a><span> >> </span><a href="#" class="active-crum">Contact Us</a> </div>
  </div>
</div>

<div class="big-cont mt0 suc">
	<div class="contact_us">
    	<div class="contact_us_left">
        	<h2 style="text-transform:none;">Say Hello!</h2>
            <p>Questions? Let us know and we'll get in touch right away.</p>
			<p>Fill up this form with basic details about yourself. Correct information will help our team provide a speedy and relevant revert.</p>
			<form class="contact_us_form" method="post">
            	<div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Name*</label>
                    </div>
                    <div class="contact_form_text">
	                    <input type="text" placeholder="First Name"  maxlength="100" name="first_name" class="required"  ondrop="return false;" onpaste="return false;">
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
                <input type="hidden" name="ip_address" id="ip_address" value="<?php echo $ip_address; ?>"> 
            	<div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Email*</label>
                    </div>
                    <div class="contact_form_text">
	                    <input type="text" placeholder="Email Address" maxlength="100" name="bussiness_email" class="required email">
                    </div>
                </div>
            	
            	<div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Phone No*</label>
                    </div>
                    <div class="contact_form_text mobileNumber">
                    	<input type="tel"  maxlength="6" minlength="2" id="mobile_country_code" name='mobile_country_code' class="required c_code">
	                   <input type="tel" class="required" placeholder="Phone Number" name="phone"  maxlength="16" minlength="5" onKeyPress="return isNumber(event)">
                    </div>
                </div>
                <div class="clear"></div>
                <div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Organisation Name*</label>
                    </div>
                    <div class="contact_form_text">
	                    <input type="text" placeholder="Company Name" name="company_name" maxlength="100" class="required" ondrop="return false;" onpaste="return false;">
                    </div>
                </div>
            	
                <div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Organisation Size*</label>
                    </div>
                    <div class="contact_form_text">
	                    <select name="og_size" class="required">
                        	<option value=''>Organisation Size</option>
							<option value='0-10 Employees'>0 - 10 Employees</option>
							<option value='10-50 Employees'>10 - 50 Employees</option>
							<option value='50-100 Employees'>50 - 100 Employees</option>
							<option value='100-500 Employees'>100 - 500 Employees</option>
							<option value='Above-500 Employees'>Above 500 Employees</option>
                        </select>
                    </div>
                </div>
                <div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Industry*</label>
                    </div>
                    <div class="contact_form_text">
	                    <select name="industry" class="required">
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

							<option value="Internet Publishing">Internet Publishing </option>

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
							<option value='Content Publishing App for SMB'>Content Publishing App</option>
							<option value='Visually Appealing Publishing App'>Visually Appealing Publishing App</option>
							<option value='Retail Catalogue App'>Retail Catalogue App</option>
							<option value='Retail App with Payment Gateway'>Retail App with Payment Gateway</option>
							<option value='Marketing Campaign Tracker App'>Marketing Campaign Tracker App</option>
							<option value='Sales Team Tracking App'>Sales Team Tracker App</option>
							<option value='Hire A Pro'>Hire A Pro</option>
                        </select>
                    </div>
                </div>
                
                <div class="contact_form_list">
                	<div class="contact_form_label">
                    	<label>Your Message</label>
                    </div>
                    <div class="contact_form_text">
					<input type="hidden" name="countryCode" id="countryCode">
					<input type="hidden" name="countryName" id="countryName"> 
	                    <textarea placeholder="Type your message" name="message" maxlength="250"></textarea>
						<p id="success"></p>
                                                <input type="hidden" name="urlsource" value="<?php echo $_GET['source']?$_GET['source']:'';?>"> 
                        <input type="submit" name="submit" id="submit" value="Submit"/>
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
                  <div id="hcard-Instappy-Instappy.com" class="vcard">
                    <div class="org" style="display:none">Instappy</div>
                    <div class="fn" style="display:none">
                      <span class="given-name">Instappy</span></div>
                    <div class="adr">
                      <div class="street-address">Plot No: 48, 2nd Floor, Okhla Industrial Estate, Phase III,
                        <span class="location">New Delhi</span>, <span class="postal-code">110020</span>
                        <span class="country-name" style="display:none">India</span></div>
                    </div>
                  </div>
                  <div class="clear"></div>
                </div>
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
              <div class="newsletter">
                <!--            <h2>Subscribe to our newsletter</h2>
                            <p>Subscribe to our newsletter for regular update s (read our <a href="privacy-policy.php">privacy policy</a>). We invite articles and views on everything marketing. We promise to never ever ever spam you!!</p>
                                                                            <p id="sub_success"></p>
                                                                            <form class="subscribe_form" method="post">
                            <input type="text" id="subscribe_email" class="required email" placeholder="email address">
                                                                            <input type="submit" name="submit" id="submit" value="Subscribe"/>    
                                                                            </form>               -->
              </div>
            </div>
        </div>
    </div>

</div>
</div>
<?php require_once('website_footer.php');?>
<!--<script type="text/javascript" src="js/jquery1.4.4.min.js"></script>-->
<script type="text/javascript" src="js/validate.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
     var $j = jQuery.noConflict();
     

	$j(".contact_us_form").validate({
		
			submitHandler: function(form) {
	
				$j('#user_profile .preloader').show();
				$j("#screenoverlay").css("display","block");
				var countryCode = $j('.contact_form_text ul.country-list').find('li.active').attr('data-dial-code');
								  $j('#countryCode').val(countryCode);
				var countryName = $j('.contact_form_text ul.country-list').find('li.active').attr('data-country-code');
								  $j('#countryName').val(countryName);
				jQuery.ajax({
					url:'ajax.php',
					type: "post",
					data: $j(form).serialize()+"&type=contact-us",
					success: function(response){
						if(response=='success'){							
							$j("#screenoverlay").css("display","none");
							$j(".contact_us_form")[0].reset();
							//$j("#success").text("Thanks for contacting us.");
							//$j("#success").addClass("success");
							window.location='letsThanks.php';
						}
						else{
							$j(".contact_us_form")[0].reset();
							$j("#screenoverlay").css("display","none");	
							$j("#success").addClass("fails");
                                                        $("#mobile_country_code").trigger("keyup");
							$j("#success").text("Oops Something went wrong.Try again later.");	
							//window.location='letsThanks.php';						
						}
					},
					error:function(){
						$j("#success").text('Request Failed.Please Try Again.');
						
					}                
				});       
			}
		});
			$j(".subscribe_form").validate({
			submitHandler: function(form) {
				email=$j("#subscribe_email").val();
				$j("#screenoverlay").css("display","block");
				jQuery.ajax({
					url:'ajax.php',
					type: "post",
					data: "email="+email+"&type=subscribe",
					success: function(response){
						if(response=='success'){							
							$j("#screenoverlay").css("display","none");
							$j(".subscribe_form")[0].reset();
							$j("#sub_success").text("Thanks for subscribing.");
							$j("#sub_success").addClass("success");
						}
						else{
							$j(".subscribe_form")[0].reset();
							$j("#screenoverlay").css("display","none");	
							$j("#sub_success").addClass("fails");
							$j("#sub_success").text("Oops Something went wrong.Try again later.");							
						}
					},
					error:function(){
						$j("#sub_success").text('Request Failed.Please Try Again.');						
					}      
							});       
			}
		});
		
		$j( ".alphabetic" ).keyup(function(evt) {
			var value = $j(this).val();
			value = $j.trim(value);
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if(value.length == 0 && charCode == 32){
				$j(this).val('');
				return false;
			}
			
			if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8)) {
				return true;
			}
			return false;
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
var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right
        function IsAlphaNumeric(e,input) {
            var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
            var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
            if(ret==false){
				$j(input).next().remove();
				$j(input).after('<label  generated="true" class="error">* Only Alphabetical Characters are allowed</label>');
			}
			else{
			$j(input).next().remove();	
			}
            //document.getElementById("error").style.display = ret ? "none" : "inline";
			
            return ret;
        }
		
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