<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
?>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner">
            <h1 class="account_id">Account ID</h1>
            <div class="edit_account_details">   
                <p>Your Account ID is unique. It gives you access to the back office of all the websites you are subscribed to. Edit the details of this account below:</p>
                <div class="edit_email_pass">
                    <div class="edit_email_pass_left">
                        <h2>Email (Login) : <span>sam@gmail.com</span></h2>
                        <a href="#">Edit Password</a>
                    </div>
                    <div class="edit_email_pass_right">
                        <label>Backend Language</label>
                        <select>
                            <option>English</option>
                            <option>Hindi</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
				<form action='' method='post' name='user_profile' id='user_profile'>
                <div class="edit_account_form">
                    <div class="edit_account_form_left">
                        <img src="images/edit_acc_form.png" alt="">
                    </div>
                    <div class="edit_account_form_right">
                        <div class="edit_inputs">
                            <div class="edit_inputs_left">
                                <label>First name * :</label>
                                <input type="text" class="required" maxlength="50" name='fname'>
                            </div>
                            <div class="edit_inputs_right">
                                <label>Last name * :</label>
                                <input type="text" class="required" maxlength="50" name='lname'>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="edit_inputs">
                            <div class="edit_inputs_left">
                                <label>Alternative email :</label>
                                <input type="text" class="required email" name='email'>
                            </div>
                            <div class="edit_inputs_right">
                                <label>Company :</label>
                                <input type="text" class="required" maxlength="50" name='company'>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="edit_inputs">
                            <div class="edit_inputs_left">
                                <label>Address :</label>
                                <textarea class="required" maxlength="150" name='address'></textarea>
                            </div>
                            <div class="edit_inputs_right">
                                <div class="zip">
                                    <label>ZIP/Post code :</label>
                                    <input type="text" class="required" maxlength="6" minlength='6' name='zip'>
                                </div>
                                <div class="city">
                                    <label>City :</label>
                                    <input type="text" class="required" maxlength="50" name='city'>
                                </div>
                                <label>Country * :</label>
                                <select class="required" id='country' name='country'>
                                </select>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="edit_inputs">
                            <div class="edit_inputs_left">
                                <label>Phone number :</label>
                                <input type="text" class="required" maxlength="12" minlength="10" name='phone' onKeyPress="return isNumber(event)">
                            </div>
                            <div class="edit_inputs_right">
                                <label>State :</label>
                                <select class="required" name='state' id='state'>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="edit_inputs">
                            <div class="edit_inputs_left">
                                <label>Fax :</label>
                                <input type="text" class="required" maxlength="12" minlength="10" name='fax' onKeyPress="return isNumber(event)">
                            </div>
                            <div class="edit_inputs_right">
                                <label>Mobile :</label>
                                <input type="text" class="required" maxlength="12" minlength="10" name='mobile' onKeyPress="return isNumber(event)">
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="edit_inputs">
                            <div class="edit_inputs_left">
                                <label>Website :</label>
                                <input type="text" class="required url" maxlength="50" name='website' value="http://">
                            </div>
                            <div class="clear"></div>
                        </div>
						<input type='hidden' name='username' value='<?php echo $_SESSION['username'];?>'>
						<img src='images/ajax-loader.gif' class='preloader' style='display:none;'>
						<input type="submit" value="Save" class="save_account_details">
                    </div>
                    <div class="clear"></div>
                </div>
				</form>
				<div id='user_profile_result'></div>
            </div>
            
        </div>
    </section>
  </section>
</section>
</body>
<script type="text/javascript" src="js/jquery1.4.4.min.js"></script>
<script type="text/javascript" src="js/validate.min.js"></script>
<script type="text/javascript" src="js/countries.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        /*var rightHeight = $(window).height() - 45;
        $(".right_main").css("height", rightHeight + "px");*/
		$("#user_profile").validate({
			submitHandler: function(form) {
				$('#user_profile .preloader').show();
				jQuery.ajax({
					url:'http://localhost/framework_api/index.php/userregister',
					type: "post",
					data: $(form).serialize(),
					success: function(response){
						//alert("success");
						$("#user_profile_result").html('Submitted successfully!');
						$('#user_profile .preloader').hide();
						console.log(response);
					},
					error:function(){
						$("#user_profile_result").html('There is error while submit.');
						$('#user_profile .preloader').hide();
					}                
				});       
			}
		});
		populateCountries("country", "state");
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
</html>