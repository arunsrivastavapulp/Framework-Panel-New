<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/user/userprofile.php');
session_start();
$userprofile = new UserProfile();
$user = $userprofile->getUserByCustId($_SESSION['custid']);
if ($user == '') {
    echo "<script>$('.popup_container').css('display', 'block'); $('.confirm_name').css('display', 'block');  $('.confirm_name_form p').text('Please choose catalogue app type');window.location.href='" . $basicUrl . "'</script>";
    exit();
}
?>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner support_hold">
            <div class="statistics">
                <div class="sp-hol">
                    <h2>Need Help?</h2>
                    <p>Our step-by-step guides and FAQs will ensure that you have a smooth app building experience. If you need further assistance, feel free to post your own questions.</p>
                </div>
				<form id="form_support" name="form_support" method="post">
				<div id="suppor_success"></div>
                <div class="query_form_new">
                    <div class="row">
                    <label>Customer ID :</label>
                    <input type="text" placeholder="Customer Id"  name="custid" readonly="readonly" value="<?php echo isset($user['custid'])?$user['custid']:'';?>" id="custid" style="width:611px;color:#000"/>
                    </div>
                     <div class="row">
                    <label>Subject:</label>
                    <select style="width:622px" name="subject" id="subject">
                    	<option value="Adding Content/Pictures or Customizing my app">Adding Content/Pictures or Customizing my app</option>
						<option value="Adding category/sub category / product in retail app">Adding category/sub category / product in retail app</option>
						<option value="Adding How to add Social Networks">Adding How to add Social Networks</option>
						<option value="Getting my own advertising SDK integrated">Getting my own advertising SDK integrated</option>
						<option value="Setting up Push Notifications">Setting up Push Notifications</option>
						<option value="Testing my app on a device / devices">Testing my app on a device / devices</option>
						<option value="Publishing my app to Play store / App store">Publishing my app to Play store / App store</option>
						<option value="Getting analytics for my published app">Getting analytics for my published app</option>
						<option value="Billing and upgrade of my App plan">Billing and upgrade of my App plan</option>
						<option value="Billing and upgrade of my marketing plans">Billing and upgrade of my marketing plans</option>
						<option value="Need help in marketing my app">Need help in marketing my app</option>
						<option value="Need help in adding API's">Need help in adding API's</option>
						<option value="The API card I need is not on the list">The API card I need is not on the list</option>
						<option value="How do I update my app">How do I update my app</option>
						<option value="Others">Others</option>
                    </select>
                    <input type="text" placeholder="Write Your Subject"  name="cust_sub" id="Others" style="width:611px; display:none; margin-left:122px; margin-top:20px"/>
                    </div>
                    <div class="row">
                    <label>Query:</label>
                    <textarea placeholder="Write Your Query Here" id ="msg" name="msg" maxlength="500"></textarea>
                    </div>
                    <div class="row">
                    <div class="buttonSend" id="sbtm">Submit</div>
                    </div>
                </div>
				</form>
                
            </div>
	   </div>
    </section>
  </section>
</section>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
	<script src="js/chosen.jquery.js"></script>
    <script src="js/ImageSelect.jquery.js"></script>
     <script>
        $(document).ready(function() {
             $("aside ul li").removeClass("active");
            $("aside ul").find(".support").addClass("active");
			
			$("#sbtm").click(function(){
				var msg=$("#msg").val();
				if(msg=='' || msg==null){
					$("#msg").css("border", "1px solid #ff0000");
					return false;
				}
				else{
					$("#msg").css("border", "1px solid #9d9d9d");
				}
				$("#screenoverlay").css("display","block");
				jQuery.ajax({
					url:'ajax.php',
					type: "post",
					data: $("#form_support").serialize()+"&type=support",
					success: function(response){
						if(response=='success'){							
							 $("#screenoverlay").css("display","none");
							 $("#form_support")[0].reset();
							  $("#suppor_success").css("color","#ffcc00");
							 $("#suppor_success").text("Thanks, We will get back to you.");
							setTimeout(function(){
							$("#suppor_success").text("");
							},5000);
						}
						else{
								$("#form_support")[0].reset();
							 $("#screenoverlay").css("display","none");							
							 $("#suppor_success").css("color","#ff0000");							
							 $("#suppor_success").text("Oops Something went wrong.Try again later.");							
						}
					},
					error:function(){
						 $("#suppor_success").css("color","#ff0000");
						$("#suppor_success").text('There is error while submit.');
						
					}                
				});       
				
			})
       
  $('#subject').on('change',function(){
  			if($(this).val() == 'Others'){
  				$('#' + $(this).val()).show();
  			}
            else{
            	$('#Others').hide();
            }
    });
        });

    </script>





</body>
</html>
