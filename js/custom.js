//banner JS
//$(function() {
//  var lis = $(".banner img"),
//    currentHighlight = 0;
//setInterval(function() {
//  currentHighlight = (currentHighlight + 1) % lis.length;
// lis.hide().eq(currentHighlight).fadeIn("slow");
//},10000);
//});

$('.banner .banner_text').hide();

$(window).load(function(){

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

$(document).ready(function() {
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
});

// banner-slider

$(window).load(function() {
  $('.banner-slider .slides li').fadeIn();
  $('.banner-slider').flexslider({
    animation: "slide",
    controlNav: true,
    directionNav: false,
    start: function(slider){
          $('body').removeClass('loading');
        }
  });
});


