$(document).ready(function(){
	$("#lets_talk").validate({ 
		rules: {           
			lets_talk_name: {
				required: true,
				minlength: 2
			},
			lets_talk_email: {
				required: true,
				minlength: 2,
				email : true
			},
			lets_talk_org: {
				required: true,
				minlength: 2
			},
			lets_talk_phone: {
				required: true,
				minlength: 10,
				maxlength: 10,
				number: true
			}
		},
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
	
	$("#business_enquiry").validate({ 
		rules: {           
			firstname: {
				required: true,
				minlength: 2
			},
			lastname: {
				required: true,
				minlength: 2
			},
			email: {
				required: true,
				minlength: 2,
				email : true
			},
			phone: {
				required: true,
				minlength: 10,
				maxlength: 10,
				number: true
			},
			designation: {
				required: true,
				minlength: 2
			},
			organization: {
				required: true,
				minlength: 2
			},
			services: {
				required: true
			},
			message: {
				required: true,
				minlength: 2
			}
		},
		submitHandler: function(form) {
			$('#business_enquiry .preloader').show();
			jQuery.ajax({
				url:'enquiry.php',
				type: "post",
				data: $(form).serialize(),
				success: function($response){
					//alert("success");
					$("#business_enquiry_result").html('Submitted successfully');
					$('#business_enquiry .preloader').hide();
				},
				error:function(){
					$("#business_enquiry_result").html('There is error while submit');
					$('#business_enquiry .preloader').hide();
				}                
			});       
		}

	});
	
});