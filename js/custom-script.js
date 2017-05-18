$(document).ready(function() {
    //$.stellar();
    //$('body').prepend('<div class="ovr"></div>');
   // $('.innr-list span').wrapInner('<a href=""></a>')
    //$('.logo_nav_inner nav').prepend('<i class="fa  fa-times clo"></i>')
    $('.uprdiv').click(function() {
		 var thisInnr = $(this).next('.innr-list');
        $(this).prepend('<em></em>');
        $(this).parent().siblings().find('em').remove();
        $(this).find('i').toggleClass('rot');
		$('.innr-list').not(thisInnr).slideUp();
        $(this).next('.innr-list').slideToggle();
		$('ul.sht-lst').show();
		$('.innr-list span img').hide();
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
    if($('.ryt-sld > ul , .mob ul.msld-sl').length!=0)
    {

        $('.ryt-sld > ul , .mob ul.msld-sl').bxSlider({
            auto: true,
            pager: false,
            nextText: '<i class="fa fa-chevron-right"></i>',
            prevText: '<i class="fa fa-chevron-left"></i>',
            pause:2000
        });
    }
    $('i.fa.fa-play-circle-o.play-icon').appendTo('.about-us > a:first')
    $('.logo a:first').attr('href', 'javascript:void(0)')
}); //ready ending

$('.form_toggle_btn a').on('click',function(e){
	e.preventDefault();
  $('.lets_talk_form').slideToggle();
  if($('.form_toggle_btn a img').attr('src')=='images/lets_talk_btm_opn.jpg'){
	  $('.form_toggle_btn a img').attr('src','images/lets_talk_btm.jpg')
  } else {
	  $('.form_toggle_btn a img').attr('src','images/lets_talk_btm_opn.jpg')
  }
})

//shruti js

$(document).ready(function() {
    $(".home-tabs li").click(function() {
        $(this).addClass("tab-active").siblings().removeClass("tab-active");
    });

    /*$('.logo_nav_inner .caret , .logo_nav_inner .dropdown').remove();*/
    $('.big-cont .lft-faq ul li span').click(function(){
		
        if($(this).find('i').hasClass('rtat'))
		{
			 $(".lft-faq ul li span").find('i').removeClass('rtat');
		}
		else
		{
			 $(".lft-faq ul li span").find('i').removeClass('rtat');
			 $(this).find('i').addClass('rtat')
			 
		}
        $(this).parent().siblings().find('.ans').slideUp();
        $(this).next('.ans').slideToggle();
    })
    $('.banner .lets_talk form.lets_talk_form input[type="button"]').attr('value','submit');
     $('.row-hold  > .price-hd span ').append('<i class="fa fa-angle-down"></i>');
    $('.row-inner').not(':first').hide();
    $('.price-hd span').click(function(){

        $(this).parent().next('.row-inner').slideToggle();
    });
    //$(".logo_nav .logo_nav_inner nav ul").children("li:nth-child(3)").children('a:first').attr('href','Landing_Page_price.php')
    //$('.logo_nav .logo_nav_inner nav ul li').eq(2).find('a:first').attr('href','Landing_Page_price.html')
});