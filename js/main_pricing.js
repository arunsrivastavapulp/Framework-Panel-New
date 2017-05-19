requirejs(["jquery"], function ($) {
	$(document).ready(function(){
		
		$('.price-hd').on('click', function () {
		  $(this).find('span > i').toggleClass('rotate');
		});

		$('.listBorder').on('click', function () {

          var self = this;
          $(self).toggleClass('active').next().slideToggle('fast');
          $('.listBorder').each(function () {
            if ($(this).hasClass('active') && ($(this).index('.listBorder') !== $(self).index('.listBorder'))) {
          $(this).removeClass('active').next().slideUp('fast' , function() {
            if ($(self).hasClass('active')) {
              $('html, body').animate({scrollTop: $(self).offset().top }, 600);
            }
          });
        }
        // $(".nested-mobile-price-list li:first-child").find(".nested-mobi-content").show();
      });

    });

    $('.nested_listBorder').on('click', function () {
      var self = this;
      $(self).toggleClass('active').next().slideToggle('fast');

      var parentIndex = $(self).parents('.mobi-content').prev('.listBorder').index('.listBorder');
      $('.listBorder').eq(parentIndex).next('.mobi-content').find('.nested_listBorder').each(function () {
        if ($(this).hasClass('active') && ($(this).index('.nested_listBorder ') !== $(self).index('.nested_listBorder'))) {
          $(this).removeClass('active').next().slideUp('fast', function() {
            if ($(self).hasClass('active')) {
              $('html, body').animate({scrollTop: $(self).offset().top }, 600);
            }
          });
        }
      });
    });

    $('.mobi-content').each(function () {
      $(this).find('.nested_listBorder:eq(0)').trigger('click');
    	});

	});	
});
