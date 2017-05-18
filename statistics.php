<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/user/statistic.php');
$statics=new Statics();
if(!empty($_SESSION['custid'])){
$custid=$_SESSION['custid'];
$results=$statics->get_userdetails($custid);
$output=$statics->stataics_tables($results['id']);
}
?>
<style>
body{
	background:#fff;
}
</style>
  <section class="main">
    <section class="right_main">
    	<div class="right_inner">
	        <div class="stats_head">
            	<div class="stat-hd">
                    <div class="st_hd">For access to app analytics, please provide us your Gmail ID.
                    	<br/><span>Note: Analytics are only available for published apps.</span>
                    </div>
                   <div class="st_hdbox">
                   	<span>Gmail ID :<?php if($results['gamil_confirm']==1){echo "(Verified)";}else echo "(Not Verified)";?></span>					
                    <span><input type="text" name="google_name" id="gmail" value="<?php echo isset($results['tracking_email_id'])?$results['tracking_email_id']:''; ?>" placeholder="abc@gmail.com (Gmail id)" /><a href="javascript:void(0);" id="add_gmail">Submit</a></span>
					<p id="gerror"></p>
                    <p>(The e-mail ID you provide will be your primary ID.)</p>
                   </div>
                </div>
            
                <div class="clear"></div>
            </div>
            <div class="stats_body">
                
                <div class="stat_hd sml" style="display:none">
                	<span>Your Google analytics will be activated in 48 hours</span>
                </div>
                
                <?php echo $output;?>
	            
                <div class="clear"></div>
                
            </div>
        </div>

    </section>
  </section>
</section>
  <!-- <script>
  (function(w,d,s,g,js,fs){
    g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
    js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
    js.src='https://apis.google.com/js/platform.js';
    fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
  }(window,document,'script'));
  </script>
-->
<script>
(function(w,d,s,g,js,fs){
  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
}(window,document,'script'));
</script>
<!-- Include the ViewSelector2 component script. -->
<script src="https://ga-dev-tools.appspot.com/public/javascript/embed-api/components/view-selector2.js"></script>

<!-- Include the DateRangeSelector component script. -->
<script src="https://ga-dev-tools.appspot.com/public/javascript/embed-api/components/date-range-selector.js"></script>
  
  <script>
  // gapi.analytics.ready(function() {

  //   /**
  //    * Authorize the user immediately if the user has already granted access.
  //    * If no access has been created, render an authorize button inside the
  //    * element with the ID "embed-api-auth-container".
  //    */
  //   gapi.analytics.auth.authorize({
  //   container: 'embed-api-auth-container',
  //   clientid: '174561932350-at2c0h9qdr81thch52hqurl7l437fl5m.apps.googleusercontent.com'
  //   });
     
    
  //   /**
  //    * Create a ViewSelector for the first view to be rendered inside of an
  //    * element with the id "view-selector-1-container".
  //    */
  //   var viewSelector1 = new gapi.analytics.ViewSelector({
  //   container: 'view-selector-1-container'
  //   });

  //   // Render both view selectors to the page.
  //   viewSelector1.execute();
  





  //   /**
  //    * Update the first dataChart when the first view selecter is changed.
  //    */
  //   viewSelector1.on('change', function(ids) {
    
  //   dataChart.set({query: {ids: ids}}).execute();
  //   dataChart1.set({query: {ids: ids}}).execute();
  //   dataChart3.set({query: {ids: ids}}).execute();
  //   dataChart4.set({query: {ids: ids}}).execute();
  //   dataChart5.set({query: {ids: ids}}).execute();
  //   dataChart6.set({query: {ids: ids}}).execute();
  //   });
 



  // /**
  //  * Create a new DataChart instance with the given query parameters
  //  * and Google chart options. It will be rendered inside an element
  //  * with the id "chart-container".
  //   */
  // var dataChart = new gapi.analytics.googleCharts.DataChart({
  //   query: {
  //     metrics: 'ga:sessions',
  //     dimensions: 'ga:date',
  //     'start-date': '30daysAgo',
  //     'end-date': 'yesterday'
  //   },
  //   chart: {
  //     container: 'chart-container',
  //     type: 'LINE',
  //     options: {
  //       width: '100%'
  //     }
  //   }
  // });   


  //   *
  //    * Create the first DataChart for top countries over the past 30 days.
  //    * It will be rendered inside an element with the id "chart-1-container".
     
  //   var dataChart1 = new gapi.analytics.googleCharts.DataChart({
  //   query: {
  //     metrics: 'ga:sessions',
  //     dimensions: 'ga:country',
  //     'start-date': '30daysAgo',
  //     'end-date': 'yesterday',
  //     'max-results': 5,
  //     sort: '-ga:sessions'
  //   },
  //   chart: {
  //     container: 'chart-1-container',
  //     type: 'PIE',
  //     options: {
  //     width: '100%',
  //     pieHole: 4/9
  //     }
  //   }
  //   });


  //   var dataChart3 = new gapi.analytics.googleCharts.DataChart({
  //   query: {
  //     metrics: 'ga:users',
  //     dimensions: 'ga:country',
  //     'start-date': '30daysAgo',
  //     'end-date': 'yesterday',
  //     'max-results': 5,
  //     sort: '-ga:users'
  //   },
  //   chart: {
  //     container: 'chart-4-container',
  //     type: 'PIE',
  //     options: {
  //     width: '100%',
  //     pieHole: 4/9
  //     }
  //   }
  // });

  // var dataChart4 = new gapi.analytics.googleCharts.DataChart({
  //   query: {
  //     metrics: 'ga:users',
  //     dimensions: 'ga:date',
  //     'start-date': '30daysAgo',
  //     'end-date': 'yesterday'
  //   },
  //   chart: {
  //     container: 'chart-3-container',
  //     type: 'LINE',
  //     options: {
  //       width: '100%'
  //     }
  //   }
  // });

  // var dataChart5 = new gapi.analytics.googleCharts.DataChart({
  //   query: {
  //     metrics: 'ga:screenviews',
  //     dimensions: 'ga:date',
  //     'start-date': '30daysAgo',
  //     'end-date': 'yesterday'
  //   },
  //   chart: {
  //     container: 'chart-5-container',
  //     type: 'LINE',
  //     options: {
  //       width: '100%'
  //     }
  //   }
  // });

  //  var dataChart6 = new gapi.analytics.googleCharts.DataChart({
  //   query: {
  //     metrics: 'ga:screenviews',
  //     dimensions: 'ga:country',
  //     'start-date': '30daysAgo',
  //     'end-date': 'yesterday',
  //     'max-results': 5,
  //     sort: '-ga:screenviews'
  //   },
  //   chart: {
  //     container: 'chart-6-container',
  //     type: 'PIE',
  //     options: {
  //     width: '100%',
  //     pieHole: 4/9
  //     }
  //   }
  // });





    
   
  // });
 gapi.analytics.ready(function() {
 

      
 
    /**
     * Authorize the user immediately if the user has already granted access.
     * If no access has been created, render an authorize button inside the
     * element with the ID "embed-api-auth-container".
     */
    var config={
    container: 'embed-api-auth-container',
    clientid: '174561932350-at2c0h9qdr81thch52hqurl7l437fl5m.apps.googleusercontent.com'
    }
    gapi.analytics.auth.authorize(config);

 
var commonConfigLineNewUsers = {
    query: {
      metrics: 'ga:newUsers',
      dimensions: 'ga:date'
    },
    chart: {
      type: 'LINE',
      options: {
        width: '100%'
      }
    }
  };
  var commonConfigLineSessionUsers = {
    query: {
      metrics: 'ga:sessions',
      dimensions: 'ga:date'
    },
    chart: {
      type: 'LINE',
      options: {
        width: '100%'
      }
    }
  };
  var commonConfigLineUsers = {
    query: {
      metrics: 'ga:Users',
      dimensions: 'ga:date'
    },
    chart: {
      type: 'LINE',
      options: {
        width: '100%'
      }
    }
  };
var commonConfigLineCrashes = {
    query: {
      metrics: 'ga:fatalExceptions',
      dimensions: 'ga:screenName',
      'max-results': 5,
      sort: '-ga:fatalExceptions'
    },
    chart: {
      type: 'PIE',
      options: {
        width: '100%',
        pieHole: 4/9
      }
    }
  };
  var commonConfigLineHits = {
    query: {
      metrics: 'ga:hits',
      dimensions: 'ga:operatingSystem',
      'max-results': 5,
      sort: '-ga:hits'
    },
    chart: {
      type: 'PIE',
      options: {
        width: '100%',
        pieHole: 4/9
      }
    }
  };
  var commonConfigLineTime = {
    query: {
      metrics: 'ga:users',
      dimensions: 'ga:country',
      'max-results': 5,
      sort: '-ga:country'
    },
    chart: {
      type: 'PIE',
      options: {
        width: '100%',
        pieHole: 4/9
      }
    }
  };
  var commonConfigLineViews = {
    query: {
      metrics: 'ga:screenviews',
      dimensions: 'ga:screenName',
      'max-results': 5,
      sort: '-ga:screenviews'
    },
    chart: {
      type: 'PIE',
      options: {
        width: '100%',
        pieHole: 4/9
      }
    }
  };
    var commonConfigPieSessionCountry = {
    query: {
      metrics: 'ga:sessions',
      dimensions: 'ga:country',
      'max-results': 5,
      sort: '-ga:country'
    },
    chart: {
      type: 'PIE',
      options: {
        width: '100%',
        pieHole: 4/9
      }
    }
  };



  /**
   * Query params representing the first chart's date range.
   */
  var dateRange1 = {
    'start-date': '30daysAgo',
    'end-date': '1daysAgo'
  };


  
  /**
   * Create a new ViewSelector2 instance to be rendered inside of an
   * element with the id "view-selector-container".
   */
  var viewSelector = new gapi.analytics.ext.ViewSelector2({
    container: 'view-selector-container',
  }).execute();


  /**
   * Create a new DateRangeSelector instance to be rendered inside of an
   * element with the id "date-range-selector-1-container", set its date range
   * and then render it to the page.
   */
  var dateRangeSelector1 = new gapi.analytics.ext.DateRangeSelector({
    container: 'date-range-selector-1-container'
  })
  .set(dateRange1)
  .execute();


 


  /**
   * Create a new DataChart instance with the given query parameters
   * and Google chart options. It will be rendered inside an element
   * with the id "data-chart-1-container".
   */
  var dataChart1 = new gapi.analytics.googleCharts.DataChart(commonConfigLineNewUsers)
      .set({query: dateRange1})
      .set({chart: {container: 'data-chart-1-container'}});
  var dataChart2 = new gapi.analytics.googleCharts.DataChart(commonConfigLineCrashes)
      .set({query: dateRange1})
      .set({chart: {container: 'data-chart-2-container'}});
  var dataChart3 = new gapi.analytics.googleCharts.DataChart(commonConfigLineHits)
      .set({query: dateRange1})
      .set({chart: {container: 'data-chart-3-container'}});
  var dataChart4 = new gapi.analytics.googleCharts.DataChart(commonConfigLineTime)
      .set({query: dateRange1})
      .set({chart: {container: 'data-chart-4-container'}});
  var dataChart5 = new gapi.analytics.googleCharts.DataChart(commonConfigLineViews)
      .set({query: dateRange1})
      .set({chart: {container: 'data-chart-5-container'}});
  var dataChart6 = new gapi.analytics.googleCharts.DataChart(commonConfigLineUsers)
      .set({query: dateRange1})
      .set({chart: {container: 'data-chart-6-container'}});
      
  var dataChart7 = new gapi.analytics.googleCharts.DataChart(commonConfigPieSessionCountry)
      .set({query: dateRange1})
      .set({chart: {container: 'data-chart-7-container'}});

  var dataChart8 = new gapi.analytics.googleCharts.DataChart(commonConfigLineSessionUsers)
      .set({query: dateRange1})
      .set({chart: {container: 'data-chart-8-container'}});


  /**
   * Create a new DataChart instance with the given query parameters
   * and Google chart options. It will be rendered inside an element
   * with the id "data-chart-2-container".
   */
          


  /**
   * Register a handler to run whenever the user changes the view.
   * The handler will update both dataCharts as well as updating the title
   * of the dashboard.
   */
  viewSelector.on('viewChange', function(data) {
    dataChart1.set({query: {ids: data.ids}}).execute();
    dataChart2.set({query: {ids: data.ids}}).execute();
    dataChart3.set({query: {ids: data.ids}}).execute();
    dataChart4.set({query: {ids: data.ids}}).execute();
    dataChart5.set({query: {ids: data.ids}}).execute();
    dataChart6.set({query: {ids: data.ids}}).execute();
    dataChart7.set({query: {ids: data.ids}}).execute();
    dataChart8.set({query: {ids: data.ids}}).execute();



    var title = document.getElementById('view-name');
    title.innerHTML = data.property.name + ' (' + data.view.name + ')';
  });


  /**
   * Register a handler to run whenever the user changes the date range from
   * the first datepicker. The handler will update the first dataChart
   * instance as well as change the dashboard subtitle to reflect the range.
   */
  dateRangeSelector1.on('change', function(data) {
    dataChart1.set({query: data}).execute();
    dataChart3.set({query: data}).execute();
    dataChart6.set({query: data}).execute();
    dataChart8.set({query: data}).execute();

    


    // Update the "from" dates text.
    var datefield = document.getElementById('from-dates');
    datefield.innerHTML = data['start-date'] + '&mdash;' + data['end-date'];
  });


  });
  </script>

    <!-- <div class="stats">
    <div class="selectorContainer">
      <div class="selectorContainer1">
        <div id="embed-api-auth-container" style="margin-botttom:10px !important"></div>
      </div>
      <div class="selectorContainer1">
        <div id="view-selector-1-container"></div>
      </div>
    </div>
      <div class="charts">
          <div class="chartname"><p>Sessions</p><div id="chart-container"></div></div>
          <div class="chartname"><p>Session From Country</p><div id="chart-1-container"></div></div>
      </div>
    <div class="charts">
         <div class="chartname"><p>Users</p><div id="chart-3-container"></div></div>
          <div class="chartname"><p>Users From Country</p><div id="chart-4-container"></div></div>
    </div>
    <div class="charts">
          <div class="chartname"><p>Screen View</p><div id="chart-5-container"></div></div>
          <div class="chartname"><p>Screen View From Country</p><div id="chart-6-container"></div></div>
    </div>
      
    </div> -->
    <div class="stats">
    <div class="selectorContainer">
      <div class="selectorContainer1">
      <div id="embed-api-auth-container"></div>
      </div>
      <div class="selectorContainer1">
        <div id="view-selector-container"></div>
      </div>
    </div>
    <div class="clear"></div>
  <div class="date_charts">
    <div id="date-range-selector-1-container"> </div>
  </div>
  <div class="clear"></div>
  <div class="user_charts">
    <h2>New Users</h2>
    <div id="data-chart-1-container"></div>
  </div>
  <div class="user_charts">
    <h2>Total Users</h2>
    <div id="data-chart-6-container"></div>
  </div>
  <div class="charts">
    <div class="chartname">
      <h2>Crashes</h2>
      <div id="data-chart-2-container"></div>
    </div>
    <div class="chartname">
      <h2>Number of clicks</h2>
      <div id="data-chart-3-container"></div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="charts">
    <div class="chartname">
      <h2>Users from country</h2>
      <div id="data-chart-4-container"></div>
    </div>
    <div class="chartname">
      <h2>Screen Views</h2>
      <div id="data-chart-5-container"></div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="charts">
    <div class="chartname">
      <h2>Sessions from Country</h2>
      <div id="data-chart-7-container"></div>
    </div>
    <div class="chartname">
      <h2>Sessions From Date</h2>
      <div id="data-chart-8-container"></div>
    </div>
    <div class="clear"></div>
  </div>
 </div>
</body>
<script>window.jQuery || document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"><\/script>')</script> 
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
<script>
		(function($){
			$(window).load(function(){
				$("#content-1").mCustomScrollbar();
				$("#content-2").mCustomScrollbar();
				 setTimeout(function (){
                webshims.setOptions('forms-ext', {types: 'date'});
                webshims.polyfill('forms forms-ext');
            },3000);
				
			});
		})(jQuery);
	</script>
	<script src="js/chosen.jquery.js"></script>
    <script src="js/ImageSelect.jquery.js"></script>
    <script>
    //$(".my-select").chosen();
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
			$("#add_gmail").click(function(){
		var reg = /^([A-Za-z0-9_\-\.])+\@([gmail|GMAIL])+\.(com)$/;	
		var gmail=$.trim($("#gmail").val());
		var uid='<?php echo $results['id'];?>';
		if(gmail=='' || gmail==null){	
			$("#gmail").css("border", "1px solid #ff0000");
			$("#gmail").focus();
			return false;
		}
		else if(!reg.test(gmail)){
			$("#gerror").css({'color':'#ff0000','margin-left':'102px'}).text("Invalid Gmail Id");			
			$("#gmail").focus();
			return false;
		}
		else{
			$("#gerror").text('');
			$("#gmail").css("border", "1px solid #e5e5e5");
		}
        $("#screenoverlay").css("display", "block");		
		$.ajax({
        type: "POST",
        url: "ajax.php",
        data: "gmail="+gmail+"&uid="+uid+"&type=add_gaccount",
        async: false,
        success: function (response) {
          if(response=='success'){
			$("#screenoverlay").css("display", "none");
			$(".popup_container").css("display", "block");
			$(".confirm_name").css("display", "block");
			$(".confirm_name_form p").text("Please check and verify your Gmail ID. Your Google Analytics will be activated within 48 Hours.");
			$(".stat_hd").css("display","block");		
			setTimeout(function(){
			$(".popup_container").css("display", "none");
			$(".confirm_name").css("display", "none");	
			$(".confirm_name_form p").text('');	
			window.location='statistics.php';			
			},10000)	;		
		  } 
		  else if(response=='no_publish'){
			$("#screenoverlay").css("display", "none");
			$(".popup_container").css("display", "block");
			$(".confirm_name").css("display", "block");
			$(".confirm_name_form p").text("Please publish any of your apps to start using this feature and try again!");		
			setTimeout(function(){
			$(".popup_container").css("display", "none");
			$(".confirm_name").css("display", "none");	
			$(".confirm_name_form p").text('');			
			},10000)	;  
			  
		  }
		  else{
			$("#gmail").val('');
			$("#screenoverlay").css("display", "none");
			$(".popup_container").css("display", "block");
			$(".confirm_name").css("display", "block");
			$(".confirm_name_form p").text("OOps somthing went wrong.please try again later.");
			setTimeout(function(){
			$(".popup_container").css("display", "none");
			$(".confirm_name").css("display", "none");
			$(".confirm_name_form p").text('');					
			},10000)	;			
			  
		  }
            
        }
    });
				
			})
			
        });
    </script>

</body>
</html>
