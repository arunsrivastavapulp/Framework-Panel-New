function appendTooltip() {
    for (var e = 0; e < tooltip.length; e++) "right" == tooltip[e].divposition && ("left" == tooltip[e].position && $(tooltip[e].location).append('<div class="hint_content"><span class="open_hint">i</span><p>' + tooltip[e].content + "</p></div>"), "right" == tooltip[e].position && $(tooltip[e].location).append('<div class="hint_content"><span class="open_hint">i</span><p class="left_tip">' + tooltip[e].content + "</p></div>")), "left" == tooltip[e].divposition && ("left" == tooltip[e].position && $(tooltip[e].location).append('<div class="hint_content left_hint"><span class="open_hint">i</span><p>' + tooltip[e].content + "</p></div>"), "right" == tooltip[e].position && $(tooltip[e].location).append('<div class="hint_content left_hint"><span class="open_hint">i</span><p class="left_tip">' + tooltip[e].content + "</p></div>"))
}

function setSelectedPageIndex(e) {
    selectedPageIndex = e, updatePageIndex()
}

function updatePageIndex() {
    $(".pageIndex li").removeClass("active"), $(".pageIndex").find("li").eq(selectedPageIndex - 1).addClass("active")
}
$(document).ready(function() {
        $('.showTutorial').on('click', function(e){
            e.stopPropagation();
            e.preventDefault();
            $('.video-tutorial-list').toggleClass('tutorialsActive');
        });
        $('html, body').on('click', function(){
            $('.video-tutorial-list').removeClass('tutorialsActive');
        });
        $('.video-tutorial-list').on('click', function(e){
            e.stopPropagation();
        });
        $(window).resize(function(){
            var screenWidth = $(window).width();
            if (screenWidth<=1023) {
              $('.screen_popup').fadeIn();
              $('.popup_container').fadeIn();
            } else {
              $('.screen_popup').fadeOut();
              $('.popup_container').fadeOut();
            };
        });
		$('.screen_popup input:button').on('click',function(){
          $('.screen_popup').fadeOut();
          $('.popup_container').fadeOut();
		});
        $("#content-2").mCustomScrollbar({
            callbacks: {
                onScrollStart: function () {
                    $('.colpick').hide();
                }
            }
        });
	$('.contactus .catalogue_detail_input ul li input:text').keyup(function(){
		$('.mobile nav ul li.contactus').attr('data-web-link',$(this).val());
	})
	$('.catalogue_add_feature.feedbackform .catalogue_detail_input a').on('click', function(){
		$('.popup_container').fadeIn();
		$('.feedback_popup').fadeIn();
	});
	$('.feedback_popup span.close_popup').on('click', function(){
		$('.popup_container').fadeOut();
		$('.feedback_popup').fadeOut();
	});
	$('.catalogue_add_feature.contactus .catalogue_detail_input a').on('click', function(){
		$('.popup_container').fadeIn();
		$('.contactus_form_popup').fadeIn();
	});
	$('.contactus_form_popup span.close_popup').on('click', function(){
		$('.popup_container').fadeOut();
		$('.contactus_form_popup').fadeOut();
	});
	$('.catalogue_add_feature.invoice .catalogue_input_group a').on('click', function(){
		$('.popup_container').fadeIn();
		$('.invoice_popup').fadeIn();
	});
	$('.invoice_popup span.close_popup').on('click', function(){
		$('.popup_container').fadeOut();
		$('.invoice_popup').fadeOut();
	});
	$('.contactus .catalogue_input_group input[type=checkbox]').on('click',function(){
		if($(this).prop('checked')){
			$('.mobile nav ul').append('<li data-link="s3" class="contactus"><a href="#"><span><img src="http://www.instappy.com/images/nav_img.png"></span> <span>Contact Us</span> <div class="clear"></div></a></li>');
			$('.mobile nav ul li.contactus').attr('data-web-link',$('.contactus .catalogue_detail_input ul li input:text').val());
		} else {
			$('.mobile nav ul li.contactus').remove();
		}
	});
	if($('.contactus .catalogue_input_group input[type=checkbox]').prop('checked')){
		$('.mobile nav ul').append('<li data-link="s3" class="contactus"><a href="#"><span><img src="http://www.instappy.com/images/nav_img.png"></span> <span>Contact Us</span> <div class="clear"></div></a></li>');
		$('.mobile nav ul li.contactus').attr('data-web-link',$('.contactus .catalogue_detail_input ul li input:text').val());
	} else {
		$('.mobile nav ul li.contactus').remove();
	}
	$('.mobile nav ul li.feedback').remove();
	if($('.feedbackform .catalogue_input_group input[type=checkbox]').prop('checked')){
		$('.mobile nav ul').append('<li class="feedback" data-link="s2"><a href="#"><span><img src="http://www.instappy.com/images/nav_img.png"></span> <span>Feedback</span> <div class="clear"></div></a></li>');
		$('.mobile nav ul li.contactus').attr('data-web-link',$('.contactus .catalogue_detail_input ul li input:text').val());
	} else {
		$('.mobile nav ul li.contactus').remove();
	}
	$('.feedbackform .catalogue_detail_input ul li input:text').keyup(function(){
		$('.mobile nav ul li.feedback').attr('data-web-link',$(this).val());
	})
	$('.feedbackform .catalogue_input_group input[type=checkbox]').on('click',function(){
		if($(this).prop('checked')){
			$('.mobile nav ul').append('<li class="feedback" data-link="s2"><a href="#"><span><img src="http://www.instappy.com/images/nav_img.png"></span> <span>Feedback</span> <div class="clear"></div></a></li>');
			$('.mobile nav ul li.feedback').attr('data-web-link',$('.feedbackform .catalogue_detail_input ul li input:text').val());
		} else {
			$('.mobile nav ul li.feedback').remove();
		}
	});
    function e(e) {
        e.preventDefault()
    }
    function t() {
        $("ul.tabs>li").removeClass("active"), $(this).addClass("active");
        var e = $("ul.tabs>li").index($(this))-2;
        $(".tab_content").hide().eq(e).show()
    }
    function a(e) {
        $(".pager div").removeClass("active"), $(".pager div").eq(e).addClass("active"), $(".banner img").hide().eq(e).fadeIn("slow")
    }
    $(".additional_options").on("click", "span", function() {
        $(".additional_options").toggleClass("open")
    }), $(".additional_items.chat").on("click", function() {
        $(".additional_options").removeClass("open"), $(".chat_window").addClass("active")
    }), $(".chat_window_head").on("click", function() {
        $(".chat_window").toggleClass("small")
    }), $(".close_chat").on("click", function(e) {
        e.stopPropagation(), $(".chat_window").removeClass("small"), $(".chat_window").removeClass("active")
    }), $("ul.chosen-results").on("click", "li:nth-child(1)", function() {
        $(".mobile").css("background", "url('images/android.png') no-repeat 0 0 / 100% auto")
    }), $("ul.chosen-results").on("click", "li:nth-child(2)", function() {
        $(".mobile").css("background", "url('images/iphone.png') no-repeat 0 0 / 100% auto")
    }), $("ul.chosen-results").on("click", "li:nth-child(3)", function() {
        $(".mobile").css("background", "url('images/window.png') no-repeat 0 0 / 100% auto")
    }), $(document).on("click", ".mobile .small_widget a", e), $(document).on("click", "ul.tabs>li", t), $("a.read_more").on("click", function(e) {
        e.stopPropagation();
        var t = $(this).prev()[0].scrollHeight;
        18 == $(this).prev().height() ? ($(this).prev().animate({
            height: t
        }), $(this).text("Close")) : ($(this).prev().animate({
            height: 18
        }), $(this).text("Read More..."))
    }), $(".pager div").click(function() {
        var e = $(this).index();
        a(e)
    });
    var n = 0;
    setInterval(function() {
        n += 1, 3 == n && (n = 0), a(n)
    }, 4e3)
});
var pageDetails = [],
    storedLayouts = [],
    staticStoredPages = [{
        index: "s1",
        name: "Report Misconduct",
        content: '<div class="feedback_form" data-ss-colspan="2"><input type="text" placeholder="Email id" /><div class="reason_select"><label><input type="radio" name="report" checked="checked" />Spam or scam</label><label><input type="radio" name="report" />Contains hate speech or attacks an individual</label><label><input type="radio" name="report" />Violence or harmful behavior</label><label><input type="radio" name="report" />Sexually explicit content</label></div><textarea placeholder="Message"></textarea><input type="button" value="Send" /></div>'
    }, {
        index: "s2",
        name: "Feedback",
        content: '<div class="feedback_form" data-ss-colspan="2"><h2>Any Suggestions/Feedback ?</h2><p>Your feedback/suggestion is valuable to us. We hope it will make FormGet app more awesome.</p><input type="text" placeholder="Name" /><input type="text" placeholder="Email id" /><textarea placeholder="Message"></textarea><input type="button" value="Send" />'
    }, {
        index: "s3",
        name: "Contact Us",
        content: '<div class="feedback_form" data-ss-colspan="2"><h2>Any Question ?</h2><p>We&apos;re happy to answer any question you have or provide you with an estimate. Just send us a message in the form below with any questions you may have.</p><input type="text" placeholder="Name" /><input type="text" placeholder="Email id" /><input type="text" placeholder="Subject" /><textarea placeholder="Message"></textarea><input type="button" value="Send" />'
    }],
    storedPages = [
      {
        index: '1',
        contentarea: '',
        banner: '',
        layouttype: '1',
        originalIndex: '1'
      }
    ],
    Links = [],
    tooltip = [{
        location: ".devices",
        content: "We recommend that you preview your app on multiple devices to make sure it looks as awesome as you made it on all the devices.",
        position: "left",
        divposition: "right"
    }, {
        location: ".icon_tip",
        content: "While choosing an app icon, we suggest that you:<br>&bull;&nbsp;&nbsp;Keep it in sync with the app colour<br>&bull;&nbsp;&nbsp;Do not put app name inside the image<br>&bull;&nbsp;&nbsp;Keep a simple, uncluttered image for better visibility<br>&bull;&nbsp;&nbsp;If you are using a vector image, keep it big and bold.",
        position: "left",
        divposition: "left"
    }];
appendTooltip();
var selectedPageIndex = 1;
$(document).ready(function() {

        function e() {
            $(".droparea").shapeshift({
                colWidth: 134.5,
                minColumns: 2,
                enableDrag: false
            });
            /*var e = $("ul.tabs>li");
            e.click(function() {
                e.removeClass("active"), $(this).addClass("active");
                var t = e.index($(this));
                $(".tab_content").hide().eq(t).show()
            }).eq(0).click();*/
			$(".widgetClose").addClass("disabled")
        }

        function t() {
            $(".linkSelector").empty(), $("#pageSelector").empty(), $(".widgetlinkSelector").empty();
            for (var e = 0; e < pageDetails.length; e++) $(".linkSelector").append($("<option>", {
                value: pageDetails[e].index,
                text: pageDetails[e].name
            })), $("#pageSelector").append($("<option>", {
                value: pageDetails[e].index,
                text: pageDetails[e].name
            })), $(".widgetlinkSelector").append($("<option>", {
                value: pageDetails[e].index,
                text: pageDetails[e].name
            }));
            $("#pageSelector").val(selectedPageIndex)
        }

        function a() {
            $(".navItemEdit").show(), $(".mobile nav").css("background-color", $(".mobile .theme_head").css("background-color")), $("nav").toggleClass("show_div"), $(".overlay").fadeToggle("fast")
        }

        function n() {
            $("nav").removeClass("show_div"), $(".overlay").fadeOut("fast")
        }

        function o(t) {
            var a = t.srcElement || t.target,
                n = $(a).parents(".half_widget").data("link");
            (void 0 == n || "" == n) && (n = $(a).parents(".full_widget").data("link"));
            var o = parseInt(n) - 1;
            if (void 0 != n) {
                console.log("From Widget Click" + o + n), editedContent = $(".container.droparea").html(), editedBanner = $(".banner").html();
                var i = parseInt(selectedPageIndex) - 1;
                console.log("current Array Index is " + i), storedPages[i].contentarea = editedContent, storedPages[i].banner = editedBanner, $(".container.droparea").empty(), $(".banner").remove(), void 0 == storedPages[o].banner || "" == storedPages[o].banner ? $(".container.droparea").html(storedPages[o].contentarea) : ($(".container.droparea").html(storedPages[o].contentarea), $("<div class='banner'>" + storedPages[o].banner + "</div>").insertAfter(".overlay")), $(".theme_head h1:first").text(pageDetails[o].name), $(".widgetClose").addClass("disabled"), e(), setSelectedPageIndex(n)
            }
        }

        function i() {
            $(".theme_head a.nav_open").trigger("click");
            var t = $(this).data("link");
            if ("s1" == t || "s2" == t || "s3" == t) {
                $(".container.droparea").empty(), $(".banner").remove();
                var a = t.replace("s", "") - 1;
                $(".theme_head h1:first").text(staticStoredPages[a].name), $(".container.droparea").html(staticStoredPages[a].content), setSelectedPageIndex(t)
            } else {
                var a = t - 1;
               
                $(".container.droparea").empty(), $(".banner").remove(), void 0 == storedPages[a].banner || "" == storedPages[a].banner ? $(".container.droparea").html(storedPages[a].contentarea) : ($(".container.droparea").html(storedPages[a].contentarea), $("<div class='banner'>" + storedPages[a].banner + "</div>").insertAfter(".overlay")), $(".theme_head h1:first").text("Home"), setSelectedPageIndex(t), e(), $(".widgetClose").addClass("disabled"), $("#pageSelector").val(t)
				  $(".mobile .theme_head h1:first").text(pageDetails[a].name);
                //$('.banner, .banner img').css('height','210px');
            }
        }

        function s() {
            $("#present").attr("src", "");
            var e = this.files[0],
                t = new FileReader;
            t.onload = function() {
                $("#testImg").attr("src", t.result);
                var e = document.getElementById("testImg"),
                    a = e.getBoundingClientRect(),
                    n = $("#present").data("size");
                console.log(n), console.log(a), a.width == n && a.height == n ? (console.log("if"), $("#present").attr("src", t.result)) : (console.log("else"), $("#present").removeAttr("src"), $("#present").attr("alt", "Please Upload Image of Size " + n + "*" + n))
            }, t.readAsDataURL(e), $("#uploadImgFile").replaceWith('<input type="file" id="uploadImgFile">')
        }
        $(".active").removeClass("active");
        $(".droparea").find('[class$="_widget"]').append("<p class='widgetClose'>x</p>"), $(".devices button").on("click", function(e) {
            $(".device_options").toggleClass("show"), e.stopPropagation()
        }), $(document).on("click", function() {
            $(".device_options").removeClass("show")
        }), $(".device_options").on("click", function(e) {
            e.stopPropagation()
        }), $(".device_list.android").on("click", function() {
            $(".mobile").css("background", "url('images/android.png') no-repeat 0 0 / 100% auto"), $(".device_options").removeClass("show")
        }), $(".device_list.iphone").on("click", function() {
            $(".mobile").css("background", "url('images/iphone.png') no-repeat 0 0 / 100% auto"), $(".device_options").removeClass("show")
        }), $(".device_list.window").on("click", function() {
            $(".mobile").css("background", "url('images/window.png') no-repeat 0 0 / 100% auto"), $(".device_options").removeClass("show")
        }), e(), t(), $(".hint_main").on("click", function() {
            $(this).toggleClass("active"), $(".hint_content span.open_hint").fadeToggle()
        }), $("#browse").click(function() {
            $("#browse_img").trigger("click")
        }), $(document).on("click", ".theme_head a.nav_open", a), $(document).on("click", ".overlay", n), $(document).on("click", ".droparea", o), $(document).on("click", "nav > ul >li", i), $(".screen_size img").click(function() {
            $("#present").removeAttr("id"), $("#testImg").replaceWith('<img src="" id="testImg">'), $(this).attr("id", "present"), $("#uploadImgFile").trigger("click")
        }), $(document).on("change", "#uploadImgFile", s), $(".editPicker21").css("background", $(".mobile .theme_head").css("background"));
        var l = window.location.pathname; - 1 != l.indexOf("catalogue") && ($(".banner").css("height", "210px"), $(".banner img").css("height", "210px"))
    }),
    function(e) {
        e(window).load(function() {
            storedPages[0].banner = $(".banner").html();
            storedPages[0].contentarea = $(".container.droparea").html();
            var screenWidth = $(window).width();
            if (screenWidth<=1023) {
              $('.screen_popup').fadeIn();
              $('.popup_container').fadeIn();
            } else {
              $('.screen_popup').fadeOut();
              $('.popup_container').fadeOut();
            };
            e("#content-1").mCustomScrollbar()
        })
    }(jQuery), $(window).load(function() {
        $(".mobile nav").css("background-color", $(".mobile .theme_head").css("background-color"))
		$(".widgetClose").addClass("disabled");
    });
    
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?3QGrcH0PaRVO9IoSUBjZ2UBmJIvb3S9U";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");    