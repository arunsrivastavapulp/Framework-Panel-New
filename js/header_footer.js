// header_footer.js

requirejs(['jquery', 'website_login', 'jquery-validate', 'additional-methods', 'intlTelInput', 'bxslider', 'flexslider'], function ($) {

    $(document).ready(function () {

        // code from header starts here
        $('#login_reg').on('click', function () {
			$('.em_ver').fadeOut();
            $('.popup_container').fadeIn();
            $('.login_popup').fadeIn();
            $('.reset_pass').fadeOut();
        });
        $('.close_popup').on('click', function () {
            $('.login_popup').fadeOut();
            $('.popup_container').fadeOut();
        });
		
		$('.cross-me').on('click', function () {
            $('.sh-makememid').fadeOut();
            $('.full-bg').fadeOut();
        });

        $('.login_popup .login_tabbing').hide().eq(0).show();
        $('.login_popup .login_popup_head h2').hide().eq(0).show();
        $('.login_popup ul.tabs li').eq(0).addClass('active');

        if (glCheckResetPass) {
            $('.popup_container').fadeIn();
            $('.reset_pass').fadeIn();
        }
        
        // email verification script
        $('#em_opn').click(function () {
            $('.login_popup').hide();
            $('#eml, .popup_container').show();
        });

        $('#eml .close_popup').click(function () {
            $(this).parent().hide();
            $('.popup_container').hide();
        });
        // ends
        // code from header ends here
        
        
        // code from custom-script.js starts here
        $('.uprdiv').click(function() {

            $(this).prepend('<em></em>');
            $(this).parent().siblings().find('em').remove();
            $(this).find('i').toggleClass('rot')
            $('.innr-list').slideUp();
            $(this).next('.innr-list').slideDown();
        });
        $('.banner .create_app em').text('Try for free')
        $('div.innr-list span a').click(function() {
            $(this).after('<img src="images/ribbon.png" alt=""/>');
            $(this).parent().siblings().find('img').remove();
        });

        $(window).scroll(function() {
            var scr = $(window).scrollTop();
            if (scr >= 40) {
                $('.logo_nav').addClass('fix');
            } else {
                $('.logo_nav').removeClass('fix');
            }
        });
        
        if ($('.ryt-sld > ul , .mob ul.msld-sl').length) {
            $('.ryt-sld > ul , .mob ul.msld-sl').bxSlider({
                auto: true,
                pager: false,
                nextText: '<i class="fa fa-chevron-right"></i>',
                prevText: '<i class="fa fa-chevron-left"></i>',
                pause:2000
            });
        }


        $('i.fa.fa-play-circle-o.play-icon').appendTo('.about-us > a:first');
        $('.logo a:first').attr('href', 'javascript:void(0)');

        $(".home-tabs li").click(function() {
            $(this).addClass("tab-active").siblings().removeClass("tab-active");
        });

        $('.big-cont .lft-faq ul li span').click(function(){
            $(this).parent().siblings('li').find('i').removeClass('rtat');
            $(this).find('i').toggleClass('rtat');
            $(this).parent().siblings().find('.ans').slideUp();
            $(this).next('.ans').slideToggle();
        });
        $('.banner .lets_talk form.lets_talk_form input[type="button"]').attr('value','submit');
        $('.row-hold  > .price-hd span ').append('<i class="fa fa-angle-down"></i>');
        $('.row-inner').not(':first').hide();
        $('.price-hd span').click(function () {
            $(this).parent().next('.row-inner').slideToggle();
        });
        // code from custom-script.js ends here
        
        
        // code from footer starts here
        $('.contant-publishing ul.sht-lst').not(':first').hide();
        $('ul.cat-list li:first').click(function () {
            $('.contant-publishing ul.sht-lst').not(':first').hide();
        });
        var locationCheck = location.search.replace('?', '').split('=');
        if (locationCheck[1] == "check") {
            locatio = $(".big-contentpublish").position().top + $(".big-contentpublish").outerHeight(true) - $(".top_nav").height() - $(".logo_nav").outerHeight(true);
            $("html, body").animate({
                scrollTop: locatio
            }, 1500);

        }

        if (locationCheck[1] == "createNew") {
            $("html, body").animate({
                scrollTop: $(".bl-bg").eq(0).offset().top + $(".logo_nav.fix").height()
            }, 1500);
        }
        if (locationCheck[1] == "createApp") {
            var pageName = window.location.pathname;

            if (pageName.indexOf("enterprise-mobile-apps") != '-1') {
                locati = $(".box.enterprise-box.clearfix").offset().top + $(".box.enterprise-box.clearfix").outerHeight(true) - $(".logo_nav").outerHeight(true) - $(".top_nav").height();
                $("html, body").animate({
                    scrollTop: locati
                }, 1500);
            }
            if (pageName.indexOf("retail-and-catalogue-app") != '-1') {
                locati = $(".sld").offset().top + $(".sld").outerHeight(true) - $(".logo_nav").outerHeight(true) - $(".top_nav").height()
                $("html, body").animate({
                    scrollTop: locati
                }, 1500);
            }
            if (pageName.indexOf("content-publishing-apps") != '-1') {
                locati = $(".big-contentpublish").position().top + $(".big-contentpublish").outerHeight(true) - $(".top_nav").height() - $(".logo_nav").outerHeight(true);
                $("html, body").animate({
                    scrollTop: locati
                }, 1500);
            }

        }
        
        $('.create_app').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var pageName = window.location.pathname;
            var location;
            if (pageName.indexOf("Landing_Page_success") != "-1" || pageName.indexOf("blog") != "-1" || pageName.indexOf("contact") != "-1" || pageName.indexOf("Landing_Page_price") != "-1") {
                window.location.href = BASEURL;

            } else {
                if (pageName.indexOf("enterprise-mobile-apps") != '-1') {
                    location = $(".box.enterprise-box.clearfix").offset().top + $(".box.enterprise-box.clearfix").outerHeight(true) - $(".logo_nav").outerHeight(true) - $(".top_nav").height();
                    $("html, body").animate({
                        scrollTop: location
                    }, 1500);
                }
                if (pageName.indexOf("retail-and-catalogue-app") != '-1') {
                    location = $(".sld").offset().top + $(".sld").outerHeight(true) - $(".logo_nav").outerHeight(true) - $(".top_nav").height()
                    $("html, body").animate({
                        scrollTop: location
                    }, 1500);
                }
                if (pageName.indexOf("content-publishing-apps") != '-1') {
                    location = $(".big-contentpublish").position().top + $(".big-contentpublish").outerHeight(true) - $(".top_nav").height() - $(".logo_nav").outerHeight(true);
                    $("html, body").animate({
                        scrollTop: location
                    }, 1500);
                }
                if (pageName.indexOf("enterprise-mobile-apps") == '-1' && pageName.indexOf("retail-and-catalogue-app") == '-1' && pageName.indexOf("content-publishing-apps") == '-1') {
                    location = $(".bl-bg").eq(0).offset().top + $(".logo_nav.fix").height();
                    $("html, body").animate({
                        scrollTop: location
                    }, 1500);
                }
            }
            return false;
        });
        
        /*var pageName = window.location.pathname;
        
        if (pageName.length !== 1 && pageName !== '/panel/frameworkphp/' && pageName.indexOf("/index.php") == '-1' && pageName.indexOf("/themes") == '-1' && pageName.indexOf("/pricing") == '-1' && pageName.indexOf("/reseller.php") == '-1' && pageName.indexOf("letsThanks") == '-1' && pageName.indexOf("enterprise-mobile-apps") == '-1' && pageName.indexOf("retail-and-catalogue-app") == '-1' && pageName.indexOf("content-publishing-apps") == '-1') {
            
            $(window).on('scroll', function () {
                var scr = $(window).scrollTop();
                var upperBl = $(".bl-bg").eq(0).offset().top;
                var lowerBl = $(".content1.content4").find("img:first").offset().top + $(".content1.content4").find("img:first").height();
                if ($(window).width() > 1023) {
                    if (scr > upperBl) {
                        $(".home-tabs").css("position", "fixed");
                        $(".home-tabs").css("top", "100px");
                        $(".home-tabs").css("z-index", "2");
                    }
                    if (scr > lowerBl || scr < upperBl) {
                        $(".home-tabs").removeAttr("style");
                        $(".content1").css("margin-left", "0px")
                    }
                }
            });
        }*/
        
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        
        if ($(".lets_talk.slide").css("display") == "none") {
            $("#lets_talk").remove();
        }
        $("#lets_talk").submit(function () {
            if ($('#lets_talk_name').val() == "") {
                $('#lets_talk_name').css('border', '2px solid #ff0000');
                $('#lets_talk_name').focus();
                return false;
            } else {
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

            } else {
                $('#lets_talk_email').css('border', 'none');
            }

            if ($('#lets_talk_phone').val().length < 5) {
                $('#lets_talk_phone').css('border', '2px solid #ff0000');
                $('#lets_talk_phone').focus();
                return false;
            } else {
                $('#lets_talk_phone').css('border', 'none');
            }
            if ($('#lets_talk_org').val() == "") {
                $('#lets_talk_org').css('border', '2px solid #ff0000');
                $('#lets_talk_org').focus();
                return false;
            } else {
                $('#lets_talk_org').css('border', 'none');
            }

            if ($("#lets_talk_industry").val() == '') {
                $('#lets_talk_industry').css('border', '2px solid #ff0000');
                $('#lets_talk_industry').focus();
                return false;
            } else {
                $('#lets_talk_industry').css('border', 'none');
            }

            if ($("#lets_talk_org_size").val() == '') {
                $('#lets_talk_org_size').css('border', '2px solid #ff0000');
                $('#lets_talk_org_size').focus();
                return false;
            } else {
                $('#lets_talk_org_size').css('border', 'none');
            }

            if ($("#lets_talk_app_type").val() == '') {
                $('#lets_talk_app_type').css('border', '2px solid #ff0000');
                $('#lets_talk_app_type').focus();
                return false;
            } else {
                $('#lets_talk_app_type').css('border', 'none');
            }

            if ($('#lets_talk_additional').val() == "") {
                $('#lets_talk_additional').css('border', '2px solid #ff0000');
                $('#lets_talk_additional').focus();
                return false;
            } else {
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


                    } else {
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
            } else if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode == 8)) {
                return true;
            }
            return false;
        });

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

                        } else {
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
        // code from footer ends here
      
        $(".pro-btn").click(function(){
            var pro = $(".pro-code"),
                self = this;
            pro.slideToggle(function () {
                if ( pro.css('display') != 'none' ){
                  $(self).css("top","16px"); 
                }else{
                    $(self).css("top","0");
                }
            });       

        });


        //video image click


        // lets talk 
        if($(".lets_talk_btn").length!=0)
        {
            $(".lets_talk_btn").on('click', function() {
                    $(this).toggleClass('op');
                    $(".lets_talk").toggleClass("slide");
                });
        }

            // navigation toggle
        $(".logo a i.fa-navicon").click(function() {
            $(this).parents(".logo_nav_inner").children("nav").slideToggle();
        })
        /*$(".clo").click(function() {
            $(this).parent("nav").slideUp('slow').fadeOut();
        })*/
        $("nav ul li a").click(function() {
            $(this).next(".dropdown").toggle();
            $(this).toggleClass("highlight");
        })

        // years toggle
        if($(".years ul li a").length!=0)
        {
        $(".years ul li a").on('click',function(){
            $(".years ul li").removeClass("active");
            $(this).parent().addClass("active");
        });
        }

       /* $(window).resize(function() {
            var wid = $(window).width();
            if (wid >= 1025) {
                $('.logo_nav_inner nav').show();
            }
        }).resize();*/

        $('.dribbble , .linkedin , .flickr , .rss').remove();


        //tabbing js
        //$('ul.sht-lst').not(':first').hide();
        $('.uprdiv').click(function(){
            $('ul.sht-lst').show();
            $('.innr-list span img').hide();
        })
        $('.innr-list span a').click(function() {
            $(this).parent().children('img').show();
            var current = $(this).attr('href');
            $(this).parent().addClass('act').siblings().removeClass('act');
            $(current).fadeIn().siblings('ul.sht-lst').hide();

            return false
        });

        //scrolljs
        $('.scr li a').click(function() {
            $('html, body').animate({
                'scrollTop': $($.attr(this, 'href')).offset().top - 100 + 'px'
            }, 500);
            return false;
        });
      
        $('.banner .banner_text').hide();
      
        if ($("#mobile_country_code").length) {
            
            $("#mobile_country_code").intlTelInput({
                defaultCountry: "auto",
                nationalMode: true,
                preventInvalidNumbers: true,
                utilsScript: "js/utils.js"
            });
            
            $("#mobile_country_code").keydown(function (event) {
                return false;
            });
        }
        
        if ($("#mobile_country").length) {
            
            $("#mobile_country").intlTelInput({
                defaultCountry: "auto",
                nationalMode: true,
                preventInvalidNumbers: true,
                utilsScript: "js/utils.js"
            });

            $("#mobile_country").keydown(function (event) {
                return false;
            });
        }
        
        if ($("#mobile_country_code2").length) {
            
            $("#mobile_country_code2").intlTelInput({
                defaultCountry: "auto",
                nationalMode: true,
                preventInvalidNumbers: true,
                utilsScript: "js/utils.js"
            });

            $("#mobile_country_code2").keydown(function (event) {
                return false;
            });
        }

        window.$zopim || (function (d, s) {
            var z = $zopim = function (c) {
                    z._.push(c)
                },
                $ = z.s =
                d.createElement(s),
                e = d.getElementsByTagName(s)[0];
            z.set = function (o) {
                z.set.
                _.push(o)
            };
            z._ = [];
            z.set._ = [];
            $.async = !0;
            $.setAttribute("charset", "utf-8");
            $.src = "//v2.zopim.com/?3QGrcH0PaRVO9IoSUBjZ2UBmJIvb3S9U";
            z.t = +new Date;
            $.
            type = "text/javascript";
            e.parentNode.insertBefore($, e)
        })(document, "script");

    });
    $(document).ready(function () {
var path = $(location).attr('href');
//alert(path); 
if(path == "http://www.instappy.com/?vc=werttt"){ 
$(".popup_container").css("display", "block"); 
$(".forgot_popup").css("display", "block");
$("#signup_reset").css("display", "none");
}
 });
    $(window).load(function () {
        
        
        $('#screenoverlay').fadeOut();
        
        // banner-slider
        $('.banner-slider .slides li').fadeIn();
        $('.banner-slider').flexslider({
          animation: "slide",
          controlNav: true,
          directionNav: false,
          start: function(slider){
                $('body').removeClass('loading');
              }
        });
      
        $(".about-us iframe").css("display","none");
        $(".about-us .videoImg").css("display","block");

        $(".videoImg").click(function(){
            $(this).hide();
            $(this).next().css("display","block");
            var videoURL = $('.about-us iframe').prop('src');
            videoURL += "&autoplay=1";
            $('.about-us iframe').prop('src',videoURL);
            //$(".ytp-large-play-button.ytp-button.ytp-small").trigger("click");
        });
    });

});