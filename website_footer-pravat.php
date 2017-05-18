<div class="footer_btm">
  <div class="footer_btm_inner">
    <div class="link_box first"> <img src="http://www.instappy.com/images/Instappy.svg" alt="Instappy"/> Instappy.com is a leading cloud-based app
      creation platform fast gaining popularity with users worldwide. We make it simple for everyone to create instant, affordable, intuitive, and stunning, professional mobile applications. Unlimited customisation, unlimited updates, and no coding skills needed to get your business online on mobile. </div>
    <div class="link_box">
      <h2>Support</h2>
      <ul>
        <li><a href="faq.php">FAQs</a></li>
        <li><a href="languages.php">Create in 20+ languages</a></li>
        <li><a href="blog/">Blog</a></li>
        <li><a href="terms-of-service.php">Terms of Service</a></li>
      </ul>
    </div>
    <div class="link_box">
      <ul>
        <li><a href="contact-us.php">Contact Us</a></li>
        <li><a href="press">Press & Media</a></li>
        <li><a href="privacy-policy.php">Privacy Policy</a></li>
        <li><a href="careers.php">Careers</a></li>
      </ul>
    </div>
    
    <!--footer_right_start -->
    <div class="footer_right">
      <div class="footer_wizard"> <img src="images/in-wizard.png" /><br />
      	<div class="icon_wizard"><a href="https://play.google.com/store/apps/details?id=com.pulp.wizard" target="_blank"><img src="images/fot-andr.png" /></a></div>
        <div class="icon_wizard"><a href="https://itunes.apple.com/us/app/instappy/id1053874135?mt=8" target="_blank"><img src="images/fot-apple.png" /></a></div>
      </div>
      <span><img src="images/fot-border.png" /></span>
      <div class="footer_retail"> <img src="images/in-retail.png" /><br />
<div class="icon_wizard"><a href="https://play.google.com/store/apps/details?id=com.pulp.catalog" target="_blank"><img src="images/fot-andr.png" /></a></div>
        <div class="icon_wizard"><a href="https://itunes.apple.com/in/app/instappy-retail-wizard/id1055349052?mt=8" target="_blank"><img src="images/fot-apple.png" /></a></div> </div>
      <div class="clear"></div>
<p class="">Instappy Wizard and Instappy Retail Wizard are awesome Instappy tools which give you a live preview of your app while you are creating it. Every time you update your app, Wizard lets you see the change in real time. Simply log-in using your unique customer ID and app details and start testing out your native app.
</p>
    </div>
  </div>
  <!--footer_right_end -->
  
  <div class="clear"></div>
  <div class="bout">
    <ul class="btm-scl-new">
      <li class="fbk"><a href="https://www.facebook.com/pages/Instappy/1442626179375675?fref=ts" target="_blank"><i class="fa fa-facebook"></i></a></li>
      <li class="twt"><a href="https://twitter.com/Instappy" target="_blank"><i class="fa fa-twitter"></i></a></li>
      <li class="pint"><a href="https://www.pinterest.com/instappy/" target="_blank"><i class="fa fa-pinterest"></i></a></li>
      <li class="ytb"><a href="https://www.youtube.com/channel/UC2TWzMwlUiFs6pyCm3Q679g?view_as=public " target="_blank"><i class="fa fa-youtube-square"></i></a></li>
    </ul>
    © Instappy.com. 2016 Pulp Strategy. All Rights Reserved</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script src="http://bxslider.com/lib/jquery.bxslider.js"></script> 
<script src="js/custom-script.js"></script>
<script src="js/custom.js"></script> 
<script type="text/javascript">
    $(document).ready(function () {
        $('.contant-publishing ul.sht-lst').not(':first').hide();
        $('ul.cat-list li:first').click(function () {
            $('.contant-publishing ul.sht-lst').not(':first').hide();
        });
        var locationCheck = location.search.replace('?', '').split('=');
        if (locationCheck[1] == "check")
        {
            locatio = $(".big-contentpublish").position().top + $(".big-contentpublish").outerHeight(true) - $(".top_nav").height() - $(".logo_nav").outerHeight(true);
            $("html, body").animate({scrollTop: locatio}, 1500);

        }

        if (locationCheck[1] == "createNew")
        {
            $("html, body").animate({scrollTop: $(".bl-bg").eq(0).offset().top + $(".logo_nav.fix").height()}, 1500);
        }
        if (locationCheck[1] == "createApp")
        {
            var pageName = window.location.pathname;

            if (pageName.indexOf("enterprise-mobile-apps") != '-1')
            {
                locati = $(".box.enterprise-box.clearfix").offset().top + $(".box.enterprise-box.clearfix").outerHeight(true) - $(".logo_nav").outerHeight(true) - $(".top_nav").height();
                $("html, body").animate({scrollTop: locati}, 1500);
            }
            if (pageName.indexOf("retail-and-catalogue-app") != '-1')
            {
                locati = $(".sld").offset().top + $(".sld").outerHeight(true) - $(".logo_nav").outerHeight(true) - $(".top_nav").height()
                $("html, body").animate({scrollTop: locati}, 1500);
            }
            if (pageName.indexOf("content-publishing-apps") != '-1')
            {
                locati = $(".big-contentpublish").position().top + $(".big-contentpublish").outerHeight(true) - $(".top_nav").height() - $(".logo_nav").outerHeight(true);
                $("html, body").animate({scrollTop: locati}, 1500);
            }

        }
        //$(".create_app").click(function(event){
        //event.preventDefault();
        //$(".home-content.enterprise-home")[0].scrollIntoView({block: "end", behavior: "smooth"});
        //$(".cat-cont")[0].scrollIntoView({block: "end", behavior: "smooth"});
        //$(".bl-bg")[0].scrollIntoView({block: "end", behavior: "smooth"})
        //$(window).scrollTop(1900);
        //});
        $('.create_app').click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            var pageName = window.location.pathname;
            var location;
            if (pageName.indexOf("Landing_Page_success") != "-1" || pageName.indexOf("blog") != "-1" || pageName.indexOf("contact") != "-1" || pageName.indexOf("Landing_Page_price") != "-1")
            {
                window.location.href = BASEURL;

            }
            else
            {
                if (pageName.indexOf("enterprise-mobile-apps") != '-1')
                {
                    location = $(".box.enterprise-box.clearfix").offset().top + $(".box.enterprise-box.clearfix").outerHeight(true) - $(".logo_nav").outerHeight(true) - $(".top_nav").height();
                    $("html, body").animate({scrollTop: location}, 1500);
                }
                if (pageName.indexOf("retail-and-catalogue-app") != '-1')
                {
                    location = $(".sld").offset().top + $(".sld").outerHeight(true) - $(".logo_nav").outerHeight(true) - $(".top_nav").height()
                    $("html, body").animate({scrollTop: location}, 1500);
                }
                if (pageName.indexOf("content-publishing-apps") != '-1')
                {
                    location = $(".big-contentpublish").position().top + $(".big-contentpublish").outerHeight(true) - $(".top_nav").height() - $(".logo_nav").outerHeight(true);
                    $("html, body").animate({scrollTop: location}, 1500);
                }
                if (pageName.indexOf("enterprise-mobile-apps") == '-1' && pageName.indexOf("retail-and-catalogue-app") == '-1' && pageName.indexOf("content-publishing-apps") == '-1')
                {
                    location = $(".bl-bg").eq(0).offset().top + $(".logo_nav.fix").height();
                    $("html, body").animate({scrollTop: location}, 1500);
                }

            }
            //console.log(location)

            return false;
        });
        $(window).scroll(function () {
            var scr = $(window).scrollTop();
            var pageName = window.location.pathname;

            if (pageName.indexOf("letsThanks") == '-1' && pageName.indexOf("enterprise-mobile-apps") == '-1' && pageName.indexOf("retail-and-catalogue-app") == '-1' && pageName.indexOf("content-publishing-apps") == '-1')
            {


                var upperBl = $(".bl-bg").eq(0).offset().top;
                var lowerBl = $(".content1.content4").find("img:first").offset().top + $(".content1.content4").find("img:first").height();
                //console.log(scr +"dsada"+upperBl)
                if ($(window).width() > 1023)
                {
                    if (scr > upperBl)
                    {
                        $(".home-tabs").css("position", "fixed");
                        $(".home-tabs").css("top", "100px");
                        $(".home-tabs").css("z-index", "2");
                        //$(".content1").css("margin-left","250px")

                    }
                    if (scr > lowerBl || scr < upperBl)
                    {
                        $(".home-tabs").removeAttr("style");
                        $(".content1").css("margin-left", "0px")

                    }
                }
            }

        });

    });
</script> 
<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script> 
<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script> 
<script type="text/javascript">
// jQuery.validator.setDefaults({
    // debug: true,
    // success: "valid"
// });
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    $(document).ready(function () {
        if ($(".lets_talk.slide").css("display") == "none") {
            $("#lets_talk").remove();
        }
        $("#lets_talk").submit(function () {
            if ($('#lets_talk_name').val() == "") {
                $('#lets_talk_name').css('border', '2px solid #ff0000');
                $('#lets_talk_name').focus();
                return false;
            }
            else {
                $('#lets_talk_name').css('border', 'none');
            }
            if ($('#lets_talk_email').val() == "") {
                $('#lets_talk_email').css('border', '2px solid #ff0000');
                $('#lets_talk_email').focus();
                return false;
            }
            // var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            email = $('#lets_talk_email').val();
            if (!validateEmail(email)) {
                $('#lets_talk_email').css('border', '2px solid #ff0000');
                $('#lets_talk_email').focus();
                return false;

            }
            else {
                $('#lets_talk_email').css('border', 'none');
            }

            if ($('#lets_talk_phone').val().length < 5)
            {
                $('#lets_talk_phone').css('border', '2px solid #ff0000');
                $('#lets_talk_phone').focus();
                return false;
            }
            else {
                $('#lets_talk_phone').css('border', 'none');
            }
            if ($('#lets_talk_org').val() == "") {
                $('#lets_talk_org').css('border', '2px solid #ff0000');
                $('#lets_talk_org').focus();
                return false;
            }
            else {
                $('#lets_talk_org').css('border', 'none');
            }

            if ($("#lets_talk_industry").val() == '') {
                $('#lets_talk_industry').css('border', '2px solid #ff0000');
                $('#lets_talk_industry').focus();
                return false;
            }
            else {
                $('#lets_talk_industry').css('border', 'none');
            }

            if ($("#lets_talk_org_size").val() == '') {
                $('#lets_talk_org_size').css('border', '2px solid #ff0000');
                $('#lets_talk_org_size').focus();
                return false;
            }
            else {
                $('#lets_talk_org_size').css('border', 'none');
            }

            if ($("#lets_talk_app_type").val() == '') {
                $('#lets_talk_app_type').css('border', '2px solid #ff0000');
                $('#lets_talk_app_type').focus();
                return false;
            }
            else {
                $('#lets_talk_app_type').css('border', 'none');
            }

            if ($('#lets_talk_additional').val() == "") {
                $('#lets_talk_additional').css('border', '2px solid #ff0000');
                $('#lets_talk_additional').focus();
                return false;
            }
            else {
                $('#lets_talk_additional').css('border', 'none');
            }
            //}
            $("#screenoverlay").css("display", "block");
            $.ajax({
                url: 'lets_talk.php',
                type: "post",
                data: $("#lets_talk").serialize(),
                success: function (response) {
                    if (response == 'Success') {
                        $("#screenoverlay").css("display", "none");
                        $('#lets_talk')[0].reset();
                        //$("#lets_talk_success").addClass('success');
                        //$("#lets_talk_success").text('Thankyou for contacting us.'); 
                        window.location = BASEURL + 'letsThanks.php';
                        setTimeout(function () {
                            $("#lets_talk_success").text('');
                            $(".lets_talk").addClass('slide');
                        }, 3000);


                    }
                    else {
                        $("#screenoverlay").css("display", "none");
                        $('#lets_talk')[0].reset();
                        $("#lets_talk_success").addClass('fails');
                        $("#lets_talk_success").text('Oops somthing went wrong .please try again later');
                        setTimeout(function () {
                            $("#lets_talk_success").text('');
                        }, 3000);

                    }
                },
                error: function () {
                    $("#lets_talk_success").addClass('fails');
                    $("#lets_talk_success").text('There is error while submit.');
                    setTimeout(function () {
                        $("#lets_talk_success").text('');
                    }, 3000);

                    $('#lets_talk .preloader').hide();

                }
            });
            return false;
        });

        $(".alphabetic").keyup(function (evt) {
            var value = $(this).val();
            value = $.trim(value);
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (value.length == 0 && charCode == 32) {
                $(this).val('');
                return false;
            }
            else if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8)) {
                return true;
            }
            return false;
        });
    });
    /*
     * Author : Varun Srivastava
     * Function to validate Alphabets input only
     */
    function isAlphabet(evt, input) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8)) {
            return true;
        }
        return false;

    }


    function validateEmail(email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test(email);
    }

    $(document).ready(function () {
        $("#reset_password").validate({
            submitHandler: function (form) {
                $("#screenoverlay").css("display", "block");
                jQuery.ajax({
                    url: 'ajax.php',
                    type: "post",
                    data: $(form).serialize() + "&type=reset_password",
                    success: function (response) {
                        if (response == 'success') {
                            $("#screenoverlay").css("display", "none");
                            $("#reset_password")[0].reset();
                            //$j("#success").text("Thanks for contacting us.");
                            $("#reset_pass_success").addClass("success");
                            $("#reset_pass_success").text("Password updated successfully");
                            setTimeout(function () {
                                window.location = BASEURL;
                            }, 3000);

                        }
                        else {
                            $("#reset_password")[0].reset();
                            $("#screenoverlay").css("display", "none");
                            $("#reset_pass_success").addClass("fails");
                            $("#reset_pass_success").text("Oops Something went wrong.Try again later.");
                            setTimeout(function () {
                                window.location = BASEURL;
                            }, 3000);
                        }
                    },
                    error: function () {
                        $("#reset_pass_success").text('There is error while submit.');

                    }
                });
            }
        });
    });
</script> 
<?php
ob_start();
session_start();
$_SESSION['includeIntlinput']= 1;
if(!isset($_SESSION['includeIntlinput'])){
?>
<script type="text/javascript" src="js/intlTelInput.js"></script> 
<?php }?>
<script>

   $("#mobile_country_code").intlTelInput({
	
defaultCountry: "auto",
nationalMode: true,
preventInvalidNumbers: true,
       utilsScript: "js/utils.js"
   });
  $("#mobile_country_code").keydown(function(event) { 
    return false;
});

   $("#mobile_country").intlTelInput({
	   defaultCountry: "auto",
nationalMode: true,
preventInvalidNumbers: true,
       utilsScript: "js/utils.js"
   });
   
   $("#mobile_country").keydown(function(event) { 
    return false;
});
   
   $("#mobile_country_code2").intlTelInput({
	   defaultCountry: "auto",
nationalMode: true,
preventInvalidNumbers: true,
       utilsScript: "js/utils.js"
   });
   $("#mobile_country_code2").keydown(function(event) { 
    return false;
});

window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?3QGrcH0PaRVO9IoSUBjZ2UBmJIvb3S9U";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script> 

<!--<script src="js/jquery.smooth-scroll.js"></script>
 <script src="js/jquery.ba-bbq.js"></script>
 <script>
   $(document)
   .on('click', 'a[href*="#"]', function() {
     if ( this.hash && this.pathname === location.pathname ) {
       $.bbq.pushState( '#/' + this.hash.slice(1) );
       return false;
     }
   })
   .ready(function() {
     $(window).bind('hashchange', function(event) {
       var tgt = location.hash.replace(/^#\/?/,'');
       if ( document.getElementById(tgt) ) {
         $.smoothScroll({scrollTarget: '#' + tgt});
       }
     });

     $(window).trigger('hashchange');
   });
 </script>-->

<?php include 'foot-meta.php'; ?>
</body></html>