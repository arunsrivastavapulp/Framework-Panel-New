<div class="footer_btm">
    	<div class="footer_btm_inner">
        	<div class="link_box first">
            	<h2>Support</h2>
                <ul>
                	<li><a href="#">FAQs</a></li>
					<li><a href="#">Submit a question</a></li>
					<li><a href="#">Blog</a></li>
                </ul>
            </div>
        	<div class="link_box">
                <ul>
                	<li><a href="#">Contact us</a></li>
					<li><a href="#">Press & media</a></li>
					<li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
        	<div class="link_box">
                <ul>
                	<li><a href="#">Content Policy</a></li>
                    <li><a href="#">Terms of service</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
        	<div class="link_box">
            	<h2>Communities</h2>
                <ul>
                	<li><a href="#">Facebook</a></li>
					<li><a href="#">Google plus</a></li>
					<li><a href="#">Blogger</a></li>
                    <li><a href="#">Twitter</a></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script src="http://bxslider.com/lib/jquery.bxslider.js"></script>
<script src="instappy/js/custom-script.js"></script>
<script src="instappy/js/custom.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jQuery.Validate/1.6/jQuery.Validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#lets_talk").validate({
		submitHandler: function(form) {
			$('#lets_talk .preloader').show();
			jQuery.ajax({
				url:'lets_talk.php',
				type: "post",
				data: $(form).serialize(),
				success: function($response){
					//alert("success");
					$("#lets_talk_result").html('Submitted successfully!');
					$('#lets_talk .preloader').hide();
				},
				error:function(){
					$("#lets_talk_result").html('There is error while submit.');
					$('#lets_talk .preloader').hide();
				}                
			});       
		}
	});
});
</script>
</span></body>
</html>