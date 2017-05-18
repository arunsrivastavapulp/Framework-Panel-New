<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
?>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner">
	        <div class="stats_head">
            	<div class="stats_head_left">
                    <h1>My Apps</h1>
                    <p>Steps to publish an app</p>
                </div>
            	<div class="stats_head_right">
                    <div class="stats_download">
                    	<a><img src="image/stats_down_icon.png"></a>
                        <div class="stats_download_tooltip">
                            <h2>Increase My Downloads</h2>
                            <p>Lorem ipsum dolor sit amet, at duo populo nostrum, mel an tibique postulant. No vis congue salutatus, dicat legere intellegat quo no. In graece luptatum quo, malorum posidonium ea per, amet sumo duo ex. Appetere mediocrem id eam. Ad regione fuisset usu, ex nec alii periculis. Assum dicit signiferumque cu his, ad has</p>
                        </div>
                    </div>
                    <div class="stats_download">
                    	<a><img src="image/stats_view_icon.png"></a>
                        <div class="stats_download_tooltip">
                            <h2>Increase My Views</h2>
                            <p>Lorem ipsum dolor sit amet, at duo populo nostrum, mel an tibique postulant. No vis congue salutatus, dicat legere intellegat quo no. In graece luptatum quo, malorum posidonium ea per, amet sumo duo ex. Appetere mediocrem id eam. Ad regione fuisset usu, ex nec alii periculis. Assum dicit signiferumque cu his, ad has</p>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="stats_body">
	            <div class="stats_left">
                    <select class="my-select">
                        <option data-img-src="image/android_icon.png">Android</option>
                        <option data-img-src="image/windows_icon.png">Windows</option>
                        <option data-img-src="image/apple_icon.png">iOS</option>
                    </select>
                </div>
	            <div class="stats_right">
                	<label>Period : </label>
                    <select>
                        <option>Last 30 days</option>
                        <option>Publish</option>
                        <option>Publish</option>
                        <option>Publish</option>
                    </select>
                </div>
                <div class="clear"></div>
                <div class="stats_list">
                	<ul class="stats_list_head">
                    	<li>Icon</li>
                        <li>App Name</li>
                        <li>Platform</li>
                        <li>Downloads</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>Lorem</li>
                        <li>Android</li>
                        <li>23</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>ipsum dolor</li>
                        <li>Android</li>
                        <li>2343</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>sit amet, at</li>
                        <li>Android</li>
                        <li>2345</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>duo populo</li>
                        <li>Android</li>
                        <li>235</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>nostrum,</li>
                        <li>Android</li>
                        <li>463</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>mel an</li>
                        <li>Android</li>
                        <li>5676</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>tibique pos-</li>
                        <li>Android</li>
                        <li>35</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>tulant. No</li>
                        <li>Android</li>
                        <li>6788</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>vis congue</li>
                        <li>Android</li>
                        <li>684</li>
                    </ul>
                	<ul>
                    	<li> <img src="image/stats_icon.png"> </li>
                        <li>salutatus,</li>
                        <li>Android</li>
                        <li>7884</li>
                    </ul>
                </div>
            </div>
        </div>
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
    $(".my-select").chosen();
    </script><script>
	$(document).ready(function() {
		$(".stats_download a").click(function() {
			$(this).next().toggleClass("show_pop");
			$(this).parent().siblings().children("div").removeClass("show_pop");
		});
		$(document).click(function() {
			$(".stats_download a + div").removeClass("show_pop");
		});
		$('.stats_download a').on('click', function(e) {
			e.stopPropagation();
		});
		$('.stats_download_tooltip').on('click', function(e) {
			e.stopPropagation();
		});

	});
</script><script type="text/javascript">
	$(function() {
		var $items = $('ul.tabs>li');
		$items.click(function() {
			$items.removeClass('active');
			$(this).addClass('active');

			var index = $items.index($(this));
			$('.tab_content').hide().eq(index).show();
		}).eq(0).click();
		
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
        $(document).ready(function() {
             $("aside ul li").removeClass("active");
            $("aside ul").find(".truck").eq(0).addClass("active");
        });
    </script>
</body>
</html>
