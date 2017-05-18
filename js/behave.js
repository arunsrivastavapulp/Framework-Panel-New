/* VIEWPORT CHECKER */
(function($){
    $.fn.viewportChecker = function(useroptions){
        // Define options and extend with user
        var options = {
            classToAdd: 'visible',
            offset: 100,
            callbackFunction: function(elem){}
        };
        $.extend(options, useroptions);

        // Cache the given element and height of the browser
        var $elem = this,
            windowHeight = $(window).height();

        this.checkElements = function(){
            // Set some vars to check with
            var scrollElem = ((navigator.userAgent.toLowerCase().indexOf('webkit') != -1) ? 'body' : 'html'),
                viewportTop = $(scrollElem).scrollTop(),
                viewportBottom = (viewportTop + windowHeight);

            $elem.each(function(){
                var $obj = $(this);
                // If class already exists; quit
                if ($obj.hasClass(options.classToAdd)){
                    return;
                }

                // define the top position of the element and include the offset which makes is appear earlier or later
                var elemTop = Math.round( $obj.offset().top ) + options.offset,
                    elemBottom = elemTop + ($obj.height());

                // Add class if in viewport
                if ((elemTop < viewportBottom) && (elemBottom > viewportTop)){
                    $obj.addClass(options.classToAdd);

                    // Do the callback function. Callback wil send the jQuery object as parameter
                    options.callbackFunction($obj);
                }
            });
        };

        // Run checkelements on load and scroll
        $(window).scroll(this.checkElements);
        this.checkElements();

        // On resize change the height var
        $(window).resize(function(e){
            windowHeight = e.currentTarget.innerHeight;
        });
    };
})(jQuery);

// JavaScript Document
$(document).ready(function(){

var wid = $(window).width()
if(wid >1000 ){
				$('.left').addClass("hidden").viewportChecker({
	    classToAdd: 'visible animated fadeInLeft', // Class to add to the elements when they are visible
	    offset:5   
	   });  
	   $('.right').addClass("hidden").viewportChecker({
	    classToAdd: 'visible animated fadeInRight', // Class to add to the elements when they are visible
	    offset:5    
		});
		 $('.up').addClass("hidden").viewportChecker({
	    classToAdd: 'visible animated fadeInUp', // Class to add to the elements when they are visible
	    offset:5    
		});
	}
	});











