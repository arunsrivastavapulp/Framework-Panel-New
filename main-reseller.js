// main-index.js

var flipTestimonials,
    player;

requirejs(['jquery', 'flipster', 'intlTelInput', 'chosen'], function ($) {

  var slider,
      newpropapps = '';
  $(document).ready(function () {
	  //video popup
	  $('.demo-button a, .view-demo a').on('click', function(e){
		  e.preventDefault();
		  $(".overlay > div").hide();
		  $('.overlay').fadeIn();
		  $('.video-popup').fadeIn();
	  });
	  
	  $(".popupClose").on('click',function(e){
    	e.preventDefault();
		$(".video-popup").hide();
		$(".overlay").hide();
	   });
    
    // bottoms testimonial slider
    flipTestimonials = $('#flat').flipster({
      itemContainer: 'ul',
      itemSelector: 'li',
      start: 'center',
      fadeIn: 400,
      loop: false,
      autoplay: false,
      pauseOnHover: true,
      style: 'flat',
      spacing: 0,
      click: true,
      keyboard: true,
      scrollwheel: false,
      touch: true,
      nav: false,
      buttons: false,
      onItemSwitch: false
    });
    

      // restrict space in input field other than Organization name Rock
    $(".request-list li .no-spac").keypress(function(event){
      var k = event ? event.which : window.event.keyCode;
      if (k == 32) {
        return false;
      }else{
        return true;
      }
    });
    
    $(document).on('click', 'a[data-flip-testimonial]', function (e) {
      e.preventDefault();
      flipTestimonials.flipster($(this).attr('data-flip-testimonial'));
    });

    // chosen select box
    $(".custom-design").chosen({disable_search_threshold: 10});

    // scroll tabs
    $(".scroll-tabs").on('click', function(e){
      e.preventDefault();
      var rel = $(this).attr('rel'),
          infoScroll = $('.' + rel).offset().top - $('.logo_nav').outerHeight(true);
      if (!$('.logo_nav').hasClass('fix')) {
        infoScroll -= ($('.top_nav').outerHeight(true) + 50);
      }
      $('html, body').animate({ scrollTop: infoScroll }, 600);
    });
    
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  	$('.save').click(function(){
  		var first_name = $('.first_name').val().trim();
  		var ip_address = $('.ip_address').val().trim();
  		var last_name = $('.last_name').val().trim();
  		var email_address = $('.email_address').val().trim();
  		var mobile_number = $('.mobile_number').val().trim();
  		var country_code = $('.mini-input ul.country-list').find('li.active').attr('data-dial-code');
  		var organization_name = $('.organization_name').val().trim();
		
  		var website = $('.website').val().trim();
  		var number_of_app = $(".number_of_app option:selected").val().trim();
  		
  		var interest = $(".interest option:selected").val().trim();
  		var information = $('.information').val().trim();
  		
  		
  		if (first_name == "") {
  			$('.first_name').css('border', '2px solid #ff0000');
  			$('.first_name').focus();
        $('html, body').animate({
        scrollTop: $(".request-block").offset().top - 80
        }, 600);
  			return false;
  		}
  		else {
  			$('.first_name').css('border', 'none');
  		}
  		
  		if (last_name == "" ) {
  			$('.last_name').css('border', '2px solid #ff0000');
  			$('.last_name').focus();
        $('html, body').animate({
        scrollTop: $(".request-block").offset().top - 80
        }, 600);
  			return false;
  		}
  		else {
  			$('.last_name').css('border', 'none');
  		}
  		
  		if (email_address == "" || !validateEmail(email_address) ) {
  			$('.email_address').css('border', '2px solid #ff0000');
  			$('.email_address').focus();
        $('html, body').animate({
        scrollTop: $(".request-block").offset().top - 80
        }, 600);
  			return false;
  		}
  		else {
  			$('.email_address').css('border', 'none');
  		}
  		
  		
      if (mobile_number == "") {
  			$('.mobile_number').css('border', '2px solid #ff0000');
  			$('.mobile_number').focus();
        $('html, body').animate({
        scrollTop: $(".request-block").offset().top - 80
        }, 600);
  			return false;
  		}
  		else {
  			$('.mobile_number').css('border', 'none');
  		}
  		
  		if (organization_name == "" ) {
  			$('.organization_name').css('border', '2px solid #ff0000');
  			$('.organization_name').focus();
        $('html, body').animate({
        scrollTop: $(".request-block").offset().top - 60
        }, 600);
  			return false;
  		}
  		else {
  			$('.organization_name').css('border', 'none');
  		}
  		
  		if (website == "" || !validateURL(website) ) {
  			$('.website').css('border', '2px solid #ff0000');
  			$('.website').focus();
        $('html, body').animate({
        scrollTop: $(".request-block").offset().top - 60
        }, 600);
  			return false;
  		}
  		else {
  			$('.website').css('border', 'none');
  		}
  		
  		if (number_of_app == "") {
  			$('.number_of_app').next().find('.chosen-single').css('border', '2px solid #ff0000');
  			$('.number_of_app').next().find('.chosen-single').focus();
        $('html, body').animate({
        scrollTop: $(".request-block").offset().top - 50
        }, 600);
  			return false;
  		}
  		else {
  			$('.number_of_app').next().find('.chosen-single').css('border', 'none');
  		}
  		
  		if (interest == "") {
  			$('.interest').next().find('.chosen-single').css('border', '2px solid #ff0000');
  			$('.interest').next().find('.chosen-single').focus();
        $('html, body').animate({
        scrollTop: $(".request-block").offset().top - 50
        }, 600);
  			return false;
  		}
  		else {
  			$('.interest').next().find('.chosen-single').css('border', 'none');
  		}
  		
  		if (information == "" ) {
  			$('.information').css('border', '2px solid #ff0000');
  			$('.information').focus();
        $('html, body').animate({
        scrollTop: $(".request-block").offset().top - 10
        }, 600);
  			return false;
  		}
  		else {
  			$('.information').css('border', 'none');
  		}
  		
  		 var formData = {'first_name': first_name, 'last_name': last_name, 'email_address': email_address, 'mobile_number': mobile_number, 'country_code': country_code, 'organization_name': organization_name, 'website': website, 'number_of_app':number_of_app, 'interest':interest, 'information':information, 'ip_address':ip_address};
  		$('#screenoverlay').show();
		$.ajax({
  			 
  		url: "resellerData.php",
  			type: "POST",
  			data: formData,
  			success: function (data) {
                    if (data == 1) {
  				$('#screenoverlay').hide();
			  				
  				$('.first_name').val('');	
  				$('.last_name').val('');
  				$('.email_address').val('');
  				$('.mobile_number').val('');
  				$('.organization_name').val('');
  				$('.website').val('');
  				$(".number_of_app option:selected").val('');
  				$(".interest option:selected").val('');
  				$('.information').val('');
                                window.location.href = "ThankYou.php";
                    } else {
                        $('.forgot_popup.signup_success_popup .signup_popup_head').remove();
                        $('.forgot_popup.signup_success_popup .signup_popup_body').html('<p style="font-size: 15px;margin: 7px 21px 0;height: 60px;">Opps something went wrong. Please try again.</p><p><input style="color: #FFF;font-size: 16px;font-family: inherit;background: #ffcc00;border: none;width: 100%;padding: 7px 35px;box-sizing: border-box;font-weight: 300;cursor: pointer;width: 32%;" onClick="closePopup()" type="button" class="resellerSignup" value="OK"></p>');
                        $('.forgot_popup.signup_success_popup').fadeIn();
                        $('.popup_container').fadeIn();
                    }
  			},
  		});
  		
  	});
  	
  	$(".country_code").intlTelInput({	
  		defaultCountry: "auto",
  		nationalMode: true,
  		preventInvalidNumbers: true,
  		utilsScript: "js/utils.js"
  	});
  	$(".country_code").keydown(function(event) { 
  		return false;
  	});
    
  }); // end document ready
  
}); // end require
function closePopup() {
    $('.forgot_popup.signup_success_popup').fadeOut();
    $('.popup_container').fadeOut();
    //$('.close_popup').trigger('click');
}

function onYouTubeIframeAPIReady() {
  player = new YT.Player('video-popup', {
    height: '100%',
    width: '100%',
    videoId: 'V-JZ_BRQX6Q',
    events: {
      'onReady': onPlayerReady,
      'onStateChange': onPlayerStateChange
    }
  });
}

function onPlayerReady(event) {
  $(".popupClose").on('click', function(e){
    event.target.pauseVideo();
  });
}

var done = false;

function onPlayerStateChange(event) {
  if (event.data == YT.PlayerState.PLAYING && !done) {
    // setTimeout(stopVideo, 6000);
    done = true;
  }
}

// function stopVideo() {
//   player.stopVideo();
// }

function validateEmail(email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test(email );
}

function validateURL(website) {
  var urlregex = new RegExp(
        "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
  return urlregex.test(website);
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

