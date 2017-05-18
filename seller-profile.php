<?php 
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/user/userprofile.php');
$userprofile = new UserProfile();
$user = $userprofile->getUserByCustId($_SESSION['custid']);
$app_name=isset($_GET['app_name'])?$_GET['app_name']:'';

$curr_id = '';
if(isset($_GET['curr_id']))
{
	$curr_id=$_GET['curr_id'];
}


if(isset($_GET['app_id'])){
	$app_id=$_GET['app_id'];
}
else{
	if(isset($_SESSION['appid'])){
		$app_id=$_SESSION['appid'];
	}
	else{
		$app_id='';
	}
	
}
?>
  <section class="main">
    <section class="right_main">
      <div class="right_inner">
    	<div class="signup_head">
        	<h1> Customer ID : <span><?php echo isset($user['custid'])?$user['custid']:''?></span></h1>        	 
        </div>
        <div class="account_inner">
            <div class="signup_head2">
                <p>Please check and verify the information below. It is required to create your cart management system.</p>
                <div class="email_lang">
                	<div class="email_lang_left">
                    	<p><span>Email (Login):</span><?php echo isset($user['email_address'])?$user['email_address']:''?></p>                       
                    </div>
                    <div class="clear"></div>
                </div>
                        
            </div>
            <div class="account_inner_form">
			<div id="success"></div>
            	<div class="form_box">
                    <p> Personal Details: </p>
                    <div class="user_img">
					<?php if($user['avatar'] != ''){ ?>
							<img src="avatars/<?php echo $user['avatar'];?>" alt="" id='preview_avatar'>
						<?php }else{?>
						<img src="images/edit_acc_form.png" id='preview_avatar' />
                        <?php }?>
                        
                    </div>
                    <div class="user_details">
                        <form class="signup_form"  id="vendor_signup_form" method="post">
                            <div class="form_field">
                                <label><span>*</span> Username:</label> <input type="text" class="required" id="username" name="username" value="<?php echo isset($user['email_address'])?$user['email_address']:''?>" readonly="readonly" maxlength="100" ><div class="clear"></div>
                            </div>
                            <div class="form_field">
                                <label><span>*</span> Firstname:</label> <input type="text"  value="<?php echo isset($user['first_name'])?$user['first_name']:''?>" class="required" name="firstname" onblur="isAlphanumericOnly(event, this);" onkeypress="return isAlphanumeric(event, this);" maxlength="50"><div class="clear"></div>
                            </div>
                            <div class="form_field">
                                <label><span>*</span> Lastname:</label> <input type="text"  value="<?php echo isset($user['last_name'])?$user['last_name']:''?>" class="required" name="lastname" onblur="isAlphanumericOnly(event, this);" onkeypress="return isAlphanumeric(event, this);" maxlength="50"><div class="clear"></div>
                            </div>
                            <div class="form_field">
                                <label><span>*</span> E-Mail:</label> <input type="email" class="required email" readonly="readonly" value="<?php echo isset($user['email_address'])?$user['email_address']:''?>"  name="email" maxlength="100"><div class="clear"></div>
                            </div>                          
                             <div class="form_field">
                                <label><span>*</span> Telephone:</label> <input type="text" name="telephone" value="<?php echo isset($user['mobile'])?$user['mobile']:''?>"  maxlength="12" minlength="10" onKeyPress="return isNumber(event)" class="required"><div class="clear"></div>
                            </div>

							 <div class="clear"></div>
							<p class="leftalign"> Your Address </p>
                    
							<div class="form_field">
                                <label><span>*</span> Company:<span class="msg">(This will be used as vendor name)</span></label> <input type="text" name="company" class="required"  value="<?php echo isset($user['organisation_name'])?$user['organisation_name']:''?>" maxlength="100"><div class="clear"></div>
                            </div>
                            
                            <div class="form_field">
                                <label><span>*</span> Address :</label> <input type="text" name="address_1" class="required" value="<?php echo isset($user['street'])?$user['street']:''?>" maxlength="200"><div class="clear"></div>
                            </div>
                            
                            <div class="form_field">
                                <label><span>*</span> City:</label> <input type="text" class="required" onkeypress="return isAlphabet(event)"  name="city" value="<?php echo isset($user['city'])?$user['city']:''?>"  maxlength="100"><div class="clear"></div>
                            </div>
                            <div class="form_field">
                                <label><span>*</span> Post Code:</label> <input type="text" name="postcode" class="required"  minlength="6" maxlength="6" onKeyPress="return isNumber(event)" value="<?php echo isset($user['pincode'])?$user['pincode']:''?>"><div class="clear"></div>
                            </div>
                            <div class="form_field">
                                <label><span>*</span> Country:</label> <select id='country_id' class="required" name='country_id'>
                                <?php echo $userprofile->get_countries($user['custid']);?>
								</select><div class="clear"></div>
                            </div>
                            <div class="form_field">
                                <label><span> </span> Region/State:</label> <select name='zone_id' id='zone_id'><option value="">Select State</option>
                                
								         <?php 
									$datac=array();
									if(isset($user['country'])&& $user['country']>0){
									$datac['conuntry_id']=$user['country'];
									 $userprofile->get_states($datac,$user['custid']);
									}									 
									 ?>
								</select><div class="clear"></div>
                            </div>
                           
                        
				  
                        <input type="hidden" value="<?php echo isset($_SESSION['password'])?$_SESSION['password']:''?>" name="password" id="password"/>
                        <input type="hidden" value="<?php echo isset($_SESSION['password'])?$_SESSION['password']:''?>" name="confirm" id="confirm"/>
                        <input type="hidden" value="<?php echo $app_id;?>" name="appid" id="appid"/>
                        <input type="hidden" value="<?php echo $curr_id;?>" name="curr_id" id="curr_id"/>
						<input type="hidden"  name="paypal" value="<?php echo isset($user['email_address'])?$user['email_address']:''?>" >
                        <span class="signup_checkbox">
                        	<input type="checkbox" name="agree" id="check"  checked="checked" value="1">
							
                                <label for="check" id="tandc" style="font-size: 13px;color:#829198;margin-top: 5px; margin-left: 4px;">I have read and agree to the <a href="terms-conditions.php" target="_blank" style="font-size: 13px;color:#ffcc00; text-decoration:underline;">Terms & Conditions </a></label>
                        </span>
                        
                    </div>
                    <div class="clear"></div>
                </div> </div>
            </div>
            <div class="signup_foot">
          <input type="submit" value="Save & Continue" id="submit" name="submit">
            </div>
			</form>
        </div>
      </div>
    </section>
  </section>
</section>
</body>



<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="js/validate.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript" src="js/vendorcontries.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
	$("#vendor_signup_form").validate({
			submitHandler: function(form) {
				$('#user_profile .preloader').show();							
				if($('#check').is(':checked')==false) {					
					$('<br><label for="lastname" generated="true" class="error">This field is required.</label>').insertAfter( "#tandc" );
					return false
				 
				} else {
					//$('<label for="lastname" generated="true" class="error">This field is required.</label>').insertAfter( "#check label" );
					$("#tandc").next().remove(); 
					
				}
				$("#screenoverlay").css("display","block");
				var app_name='<?php echo $app_name; ?>';
				jQuery.ajax({
					url:'ajax.php',
					type: "post",
					data: $(form).serialize()+"&type=vendor_register",
					success: function(response){
						
						if(response=='success'){							
							$("#success").text("Successfully Registered");
							email=$("#username").val();
							password=$("#confirm").val();
							app_id=$("#appid").val();
							curr_id = $('#curr_id').val();
							$("#vendor_signup_form")[0].reset();
							
							
							window.location=catalogueUrl+"catalogue/admin/index.php?route=common/login&email="+email+"&password="+password+"&app_id="+app_id+"&app_name="+app_name+"&curr_id="+curr_id;
						}
						else{
							$("#screenoverlay").css("display","none");
							$("#success").addClass("fails");							
							$("#success").css("color","#ff0000");							
							$("#success").text(response);							
						}
					},
					error:function(){
						$("#user_profile_result").html('There is error while submit.');
						$('#user_profile .preloader').hide();
					}                
				});       
			}
		});
$("#country_id").change("on",function(){
	var country_id=$(this).val();
				jQuery.ajax({
					url:'ajax.php',
					type: "post",
					data: "conuntry_id="+country_id+"&type=country_state",
					success: function(response){
						if(response){							
						$("#zone_id").html(response);	
						}
						
					},
					error:function(){
						$("#user_profile_result").html('There is error while submit.');
						
					}                
				});
	
})
		/* User Profile upload */
		var options = { 
			target: '#avatar_message',   // target element(s) to be updated with server response 
			url : BASEURL+'API/user.php/useravatar',
			beforeSubmit: beforeSubmit,  // pre-submit callback 
			success: afterSuccess,  // post-submit callback 
			resetForm: true        // reset the form after successful submit 
		}; 
		/* User Profile Upload Ends */
		$("#avatar").change(function() {
			$("#avatar_message").empty(); // To remove the previous error message
			var file = this.files[0];
			var imagefile = file.type;
			var match= ["image/jpeg","image/png","image/jpg"];
			if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
			{
				//$('#previewing').attr('src','noimage.png');
				$("#avatar_message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
				return false;
			}
			else
			{
				var reader = new FileReader();
				reader.onload = imageIsLoaded;
				reader.readAsDataURL(this.files[0]);
				$('#user_profile').ajaxSubmit(options);
				
			}
		});
		function afterSuccess()
		{
			//$('#submit-btn').show(); //hide submit button
			//$('#loading-img').hide(); //hide submit button

		}
		//function to check file size before uploading.
		function beforeSubmit(){
			//check whether browser fully supports all File API
		   if (window.File && window.FileReader && window.FileList && window.Blob)
			{
				
				if( !$('#avatar').val()) //check empty input filed
				{
					$("#avatar_message").html("Are you kidding me?");
					return false
				}
				
				var fsize = $('#avatar')[0].files[0].size; //get file size
				var ftype = $('#avatar')[0].files[0].type; // get file type
				

				//allow only valid image file types 
				switch(ftype)
				{
					case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
						break;
					default:
						$("#avatar_message").html("<b>"+ftype+"</b> Unsupported file type!");
						return false
				}
				
				//Allowed file size is less than 1 MB (1048576)
				if(fsize>1048576) 
				{
					$("#avatar_message").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
					return false
				}
						
				//$('#submit-btn').hide(); //hide submit button
				//$('#loading-img').show(); //hide submit button
				$("#avatar_message").html("");  
			}
			else
			{
				//Output error to older browsers that do not support HTML5 File API
				$("#avatar_message").html("Please upgrade your browser, because your current browser lacks some new features we need!");
				return false;
			}
		}

		//function to format bites bit.ly/19yoIPO
		function bytesToSize(bytes) {
		   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		   if (bytes == 0) return '0 Bytes';
		   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		}
		function imageIsLoaded(e) {
			//$("#file").css("color","green");
			//$('#image_preview').css("display", "block");
			$('#preview_avatar').attr('src', e.target.result);
			$('#preview_avatar').attr('width', '250px');
			$('#preview_avatar').attr('height', '230px');
		};
    });
	
function isNumber(evt) {
evt = (evt) ? evt : window.event;
var charCode = (evt.which) ? evt.which : evt.keyCode;
if (charCode > 31 && (charCode < 48 || charCode > 57)) {
return false;
}
return true;
}
 function isAlphabet(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8) || (charCode == 32)) {
		return true;
	}
	return false;

}

function isAlphanumeric(evt, input) {
evt = (evt) ? evt : window.event;
var charCode = (evt.which) ? evt.which : evt.keyCode;
if ((charCode >= 48 && charCode <= 57) || (charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8) || (charCode == 32)) {
	return true;
}
return false;
			}
 function isAlphanumericOnly(evt, input) {
		var value = $(input).val();
		if (!isNaN(value))
		{
		$(input).focus();
		$(input).removeClass('valid');
		$(input).addClass('error');
		if (!$(input).next().find('.error')) {
		$('<label generated="true" class="error">Please input Alphabetic or Alphanumeric Value.</label>').insertAfter($(input));
		}
		return false;
		}
		else {
		$(input).removeClass('error');
		$(input).addClass('valid');
		$(input).next('.error').remove();
		}
	}
</script>

</html>
