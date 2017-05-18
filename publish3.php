<?php 
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/checkapp/app_screen.php');
?>
<script type="text/javascript">
	$(document).ready(function() {
        $(".additional_options").on('click','span',function() {
            $(".additional_options").toggleClass("open");
        });
        $(".additional_items.chat").on('click',function() {
            $(".additional_options").removeClass("open");
            $(".chat_window").addClass("active");
        });
        $(".chat_window_head").on('click',function() {
            $(".chat_window").toggleClass("small");
        });
        $(".close_chat").on('click',function(e) {
			e.stopPropagation();
            $(".chat_window").removeClass("small");
            $(".chat_window").removeClass("active");
        });
		$("ul.chosen-results").on('click','li:nth-child(1)',function(){
			$(".mobile").css("background","url('image/android.png') no-repeat 0 0 / 100% auto");
		});
		$("ul.chosen-results").on('click','li:nth-child(2)',function(){
			$(".mobile").css("background","url('image/iphone.png') no-repeat 0 0 / 100% auto");
		});
		$("ul.chosen-results").on('click','li:nth-child(3)',function(){
			$(".mobile").css("background","url('image/window.png') no-repeat 0 0 / 100% auto");
		});

		$(document).on("click","ul.tabs>li",tabbingFunction);
		function tabbingFunction(){
			$("ul.tabs>li").removeClass('active');
			$(this).addClass('active');
			var index = $("ul.tabs>li").index($(this));
			$(".tab_content").hide().eq(index).show();

		}

		 //banner JS
		$(".pager div").click(function() {
			var tempindex=$(this).index();
            nextImage(tempindex);
        });
		function nextImage(index){
			$('.pager div').removeClass('active');
			$('.pager div').eq(index).addClass('active');
			
			$('.banner img').hide().eq(index).fadeIn("slow");
		}
		var newindex=0;
	
		setInterval(function(){
			newindex=newindex+1;
			if (newindex==3){
				newindex=0;
			}
			nextImage(newindex);
			
			}, 4000);


    });
</script>

<script>
var pageDetails=[
	{
		index:'1',
		name:'Home'
	},
  {
    index:'2',
    name:'About Us'
  },
  {
    index:'3',
    name:'Menu'
  }

];

var storedLayouts=[
{
	index:'1',
	layout:'<div class="half_widget"><p class="widgetClose">x</p><div class="half_widget_img"><img src="image/widget_img.jpg"><div class="clear"></div></div><div class="half_widget_text"><p>Heading</p></div></div><div class="half_widget"><p class="widgetClose">x</p><div class="half_widget_img"><img src="image/widget_img.jpg"><div class="clear"></div></div><div class="half_widget_text"><p>Heading</p></div></div><div class="small_widget"><a href=""><p class="widgetClose">x</p><div class="small_widget_img"><img src="image/facebook.png"></div><div class="small_widget_text"><p>Facebook</p></div></a><div class="clear"></div></div><div class="small_widget"><p class="widgetClose">x</p><div class="small_widget_img"><img src="image/twitter.png"></div><div class="small_widget_text"><p>Twitter</p></div><div class="clear"></div></div>'
},
{
	index:'2',
	layout:'<div class="full_widget" data-ss-colspan="2"><p class="widgetClose">x</p><div class="full_widget_img"><img src="image/widget_full_img.jpg"><div class="clear"></div></div><div class="full_widget_text"><p>Heading</p></div></div><div class="half_widget"><p class="widgetClose">x</p><div class="half_widget_img"><img src="image/widget_img.jpg"><div class="clear"></div></div><div class="half_widget_text"><p>Heading</p></div></div><div class="half_widget"><p class="widgetClose">x</p><div class="half_widget_img"><img src="image/widget_full_img.jpg"><div class="clear"></div></div><div class="half_widget_text"><p>Heading</p></div></div>'
},
{
	index:'3',
	layout:'<div class="full_widget" data-ss-colspan="2"><p class="widgetClose">x</p><div class="full_widget_img"><img src="image/widget_full_img.jpg"><div class="clear"></div></div><div class="full_widget_text"><p>Heading</p></div></div><div class="full_widget" data-ss-colspan="2"><p class="widgetClose">x</p><div class="full_widget_img"><img src="image/widget_full_img.jpg"><div class="clear"></div></div><div class="full_widget_text"><p>Heading</p></div></div>'
}
];

var storedPages=[
{

  index:'1',
  contentarea:'',
  banner:''
},
{
  index:'2',
  contentarea:'<div class="big_widget_text about" data-ss-colspan="2"> <p class="widgetClose">x</p> <div class="big_widget_img"> <img src="image/restro_about_banner.jpg"> <div class="clear"> </div> <div class="big_widget_img_text"> <p class="img_heading">IndiEat</p> <div class="clear"> </div> </div> <div class="big_widget_img_controls"> <a href="#" class="goback"> <img src="image/go_back.png" /> </a> <a href="#" class="share"> <img src="image/share.png" /> </a> <a href="#" class="share"> <img src="image/heart.png" /> </a> <div class="clear"> </div> </div> </div> <div class="big_widget_bottom_text"> <p class="about">Kinldling taste buds since 1945</p> <div class="clear"> </div> </div> </div> <div class="tabbing_widget" data-ss-colspan="2"> <p class="widgetClose">x</p> <div class="tabbing_widget_head"> <ul class="tabs"> <li> <a>History</a> </li> <li> <a>Cuisines</a> </li> <li> <a>Chef &apos;s List</a> </li> <div class="clear"> </div> </ul> </div> <div class="tabbing_widget_body"> <div class="tab_content"> <p>1Just before the holidays we had the pleasure of photographing the updated interior of Araxi Restaurant in Whistler. I have photographed Araxi many times over the past five years and it is wonderful to see the constant changes and improvements. I guess that &apos;s why the restaurant is constantly winning awards for top restaurant in Whistler!</p> </div> <div class="tab_content"> <p>2  guess that &apos;s why the restaurant is constantly winning awards for top restaurant in Whistler!</p> </div> <div class="tab_content"> <p>3n Whistler. I have photographed Araxi many times over the past five years and it is wonderful to see the constant changes and improvements. I guess that &apos;s why the restaurant is constantly winning awards for top restaurant in Whistler!</p> </div> </div> </div>',
  banner:''

},
{
  index:'3',
  contentarea:'<div class="half_widget"> <p class="widgetClose">x</p> <div class="half_widget_img"> <img src="image/restro_menu1.jpg"> <div class="clear"> </div> </div> <div class="half_widget_text"> <p>Appetizers</p> </div> </div> <div class="half_widget"> <p class="widgetClose">x</p> <div class="half_widget_img"> <img src="image/restro_menu2.jpg"> <div class="clear"> </div> </div> <div class="half_widget_text"> <p>Cuisines</p> </div> </div> <div class="half_widget"> <p class="widgetClose">x</p> <div class="half_widget_img"> <img src="image/restro_menu3.jpg"> <div class="clear"> </div> </div> <div class="half_widget_text"> <p>Beverages</p> </div> </div> <div class="half_widget"> <p class="widgetClose">x</p> <div class="half_widget_img"> <img src="image/restro_menu4.jpg"> <div class="clear"> </div> </div> <div class="half_widget_text"> <p>Desserts</p> </div> </div> <div class="half_widget"> <p class="widgetClose">x</p> <div class="half_widget_img"> <img src="image/restro_menu5.jpg"> <div class="clear"> </div> </div> <div class="half_widget_text"> <p>Today &apos;s Special</p> </div> </div> <div class="half_widget"> <p class="widgetClose">x</p> <div class="half_widget_img"> <img src="image/restro_menu6.jpg"> <div class="clear"> </div> </div> <div class="half_widget_text"> <p>Recipe of Month</p> </div> </div>',
  banner:undefined
}
];

var Links=[
{
  from:"1",
  to:"1"
},
{
  from:"2",
  to:"2"
},
{
  from:"3",
  to:"1"
},
{
  from:"4",
  to:"1"
},
{
  from:"5",
  to:"1"
}
]
var selectedPageIndex=1;
function setSelectedPageIndex(currentPage){

selectedPageIndex=currentPage;
//$("#pageSelector").val(selectedPageIndex-1);
updatePageIndex();

}

function updatePageIndex(){

  $(".pageIndex li").removeClass("active");
  $(".pageIndex").find("li").eq(selectedPageIndex-1).addClass("active");
}
$(document).ready(function(e) {
    
	//For ShapeShift

    var presentWidget;
	/*var height = $(window).height()-85;
	$(".middle").css("height",height+"px");*/
	$(".droparea").find('[class$="_widget"]').append("<p class='widgetClose'>x</p>");
	function restartShapeShift(){


	$(".droparea").shapeshift({
			colWidth: 130,
			minColumns: 2
		  });
	var $items = $('ul.tabs>li');
		$items.click(function() {
			$items.removeClass('active');
			$(this).addClass('active');

			var index = $items.index($(this));
			$('.tab_content').hide().eq(index).show();
		}).eq(0).click();


 

	
	}
 
		
    restartShapeShift();
	 function populateLink(){
              $(".linkSelector").empty();
              $("#pageSelector").empty();
              $(".widgetlinkSelector").empty();
            
            for(var i=0;i<pageDetails.length;i++)
            {
              console.log(pageDetails[i])
              console.log(pageDetails[i].index)

              $(".linkSelector").append($("<option>", {
                  value:pageDetails[i].index,text:pageDetails[i].name}));
              $("#pageSelector").append($("<option>", {
                  value:pageDetails[i].index,text:pageDetails[i].name}));
              $(".widgetlinkSelector").append($("<option>",{
                value:pageDetails[i].index,text:pageDetails[i].name}));
            }
            updateLinkNavItems();

            $("#pageSelector").val(selectedPageIndex);
            
          };
          populateLink();     
    
	
	
  Drupal.behaviors.nwsJS = {
	attach: function(context, settings) {
	  $('.form-select').once('nws-arrow', function() {
		$wrap_width = $(this).outerWidth();
		$element_width = $wrap_width + 20;
		$(this).css('width',$element_width);
		$(this).wrap('<div class="nws-select"></div>');
		$(this).parent('.nws-select').css('width',$wrap_width);
	  });
	}
  };

});
</script>
  <section class="main">
    
    <section class="middle publish_middle clear">
      <div class="right-area publish_pages">
      	<div class="pageIndexArea">
                <div class="pageIndexdiv">
                    <img alt="" src="<?php echo $basicUrl; ?>images/instappy.png">
                </div>

            </div>
         <div class="mobile">
         
          

         
         <!-- Replacement Area Starts -->
           <div id="content-1" class="content mCustomScrollbar clear first-page " >
           
             
            
            
            <div class="theme_head restro_theme">
                <a class="nav_open"> <img src="images/menu_btn.png"> </a>
                <h1>IndiEat</h1>
                <a href="#" class="search"> <i class="fa fa-search"></i> </a>
            </div>
                    
            <nav>
                <ul>
                    <li data-link="1"><a href="#">Home</a></li>
                    <li data-link="2"><a href="#">About Us</a></li>
                    <li data-link="1"><a href="#">Menu</a></li>
                    <li data-link="1"><a href="#">Notification</a></li>
                    <li data-link="1"><a href="#">Chef &apos;s View</a></li>
                </ul>
            </nav>
            <div class="overlay">
            </div>
            
            
             <div class="banner">
                <img src="images/restaurant_banner.jpg" class="active">
                <img src="images/restaurant_banner.jpg">
                <img src="images/restaurant_banner.jpg">
                <div class="pager">
                	<div class="active"></div>
                	<div></div>
                	<div></div>
                </div>
                <div class="clear"></div>
             </div>
             


            
            
            
            
         <div class="container droparea" style="float:left;width:100%;">
             	   
            
                <div class="half_widget" component="">
                <p class="widgetClose">x</p>
                  <div class="widget_inner">
                      <div class="half_widget_img">
                          <img src="images/restaurant_widget1.png">
                            <div class="clear"></div>
                        </div>
                        <div class="half_widget_text">
                          <p>Chef's View</p>
                        </div>
                    </div>
                </div>
                <div class="half_widget">
                <p class="widgetClose">x</p>
                  <div class="widget_inner">
                      <div class="half_widget_img">
                          <img src="images/restaurant_widget2.png">
                            <div class="clear"></div>
                        </div>
                        <div class="half_widget_text">
                          <p>Chef's View</p>
                        </div>
                    </div>
                </div>
                <div class="full_widget" data-ss-colspan="2">
                <p class="widgetClose">x</p>
                  <div class="widget_inner">
                      <div class="full_widget_img">
                          <img src="images/restaurant_widget3.png">
                            <div class="clear"></div>
                        </div>
                        <div class="full_widget_text">
                          <p>Special Offers</p>
                        </div>
                    </div>
                </div> 
                <div class="small_widget addon">
                <p class="widgetClose">x</p>
                  <div class="widget_inner">
                      <div class="small_widget_img">
                      <img src="images/zomato.png">
                        </div>
                        <div class="small_widget_text">
                          <p>Review Us</p>
                          <p>On</p>
                            <h2>Zomato</h2>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="contact_small_widget">
                                	<p class="widgetClose">x</p>
                                    <p class="contact_small_heading">Heading</p>
                                    <p class="contact_small_subheading">Subheading</p>
                                    <div class="contact_btns">
                                    	<a href="#"><img src="images/contact_mail.png" /></a>
                                    	<a href="#"><img src="images/contact_phone.png" /></a>
                                    </div>
                                </div>
              </div>
              
              
            
            
            </div>

          <!-- Replacement Area Ends -->
        </div>
        <div class="devices"> 
        	<button>Device</button>
            <div class="device_options">
            	<div class="device_list android">
                	<img src="images/android.png" />
                    <span>Android</span>
                </div>
            	<div class="device_list iphone">
                	<img src="images/iphone.png" />
                    <span>Iphone</span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
      </div>
      <div class="publish_content_area">
      	<div class="mid_section">
            <div class="mid_section_left">
            </div>
            <div class="mid_section_right">
            	<div class="mid_right_box active">
                	<div class="mid_right_box_body">
                    	<div class="congrats">
                        	<h1>Congratulations!</h1>
                            <h2>Your awesome app is ready to roll!</h2>
                            <p>It is now available for all platforms.<br>You can either try it for free, or go ahead and purchase it.</p>
                            <div class="congrats_btns">
                            	<a href="#" class="free_down">Download Trial</a>
                            	<a href="#" class="buy">Buy</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
      </div>
      
      <div class="hint_main"><img src="<?php echo $basicUrl; ?>images/ajax-loader.gif"></div>
    </section>
  </section>
</section>
<script>window.jQuery || document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"><\/script>')</script> 
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>  
<script>
		(function($){
			$(window).load(function(){
				$("#content-1").mCustomScrollbar();
				$("#content-2").mCustomScrollbar();
				
				
			});
		})(jQuery);
	</script>
	<script src="js/chosen.jquery.js"></script>
    <script src="js/ImageSelect.jquery.js"></script>
    <script>
    $(".select_os").chosen();
    </script>
</body>
</html>
