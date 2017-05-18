<!-- start header -->
  <?php require_once('website_header_new.php');?>
  <?php
unset($_SESSION['catid']);
unset($_SESSION['themeid']);
unset($_SESSION['currentpagevisit']);
require_once('modules/login/resellerCheck.php');
	$check=new ResellerCheck();
	$res=$check->getSecurityQuestion();
	
?>
  <!-- end header --> 
  <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
<script>
/*if(typeof angular == 'undefined') {
alert('not defined');
}
else
{
alert('defined');

}*/

var app = angular.module('App', []);


app.controller('AppReseller', ['$scope','$http', function ($scope, $http) { 
$scope.emailMsg='';
$scope.reqSave=true;
$scope.validateEmail = function(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

 /*

	*/
$scope.validateEmail = function()
	{
     
    /*if(typeof $scope.email == 'undefined' || $scope.email == '')
	{
		 $scope.emailMsg='Not a valid email id';
		 $(".emlErr").text(" is not valid :");
    $(".emlErr").css("color", "red");	
		 return false;
	}
	if($scope.validateEmail($scope.email))
	{
		$scope.emailMsg='Not a valid email id';
		$(".emlErr").text("not valid");
    $(".emlErr").css("color", "red");	
		return false;
	}
	*/
	
		//$scope.emailMsg='Alrighty';
		
	
		var xsrf = $.param({type:'checkEmail',email: $scope.email});
		$http({
        method : "POST",
        url : BASEURL+'/ajax.php',
		data: xsrf,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
    }).then(function mySucces(response) {
       
		console.log(response.data);
		if(response.data.email_address)
		{
		$scope.emailMsg='This email Is present';
		$scope.reqSave='';
		$eem=$(".email_address").val();
		 $(".email_address").attr("placeholder", $eem);
		 $(".email_address").val('');
		}
		else
		{
		$scope.emailMsg = '';
		$scope.reqSave=true;
		
		}
    }, function myError(response) {
        
		console.log(response.data);
    });
	
	}
	
	
	
}]);


function getSecurityQuestions() {
	 var formData = {type:'getQuestions'};
  $.ajax({
  			 
  		url: BASEURL+'ajax.php',
  			type: "POST",
  			data: {type:'getQuestions'},
  			success: function (data) {
  				console.log(data);
  			},
  		});
  		
}
getSecurityQuestions();
</script>
<link href="css/reseller.css" rel="stylesheet" type="text/css">
<!--video-popup start -->
<div class="overlay">
  <div class="video-popup"> 
    <div id="video-popup"></div>
    <div class="popupClose"><img src="images-new/close.png"></div>
  </div>

</div>
<!--video-popup end -->

<!--login-popup start -->
<div class="overlay">
  <div class="reseller-popup"> <span class="reseller-close_popup"><img src="images/popup_close.png"></span>
    <div class="reseller-popup-header">
      <h2>Reseller Login</h2>
    </div>
    <div class="reseller-login_form">
      <form method="post" id="login_form">
        <input type="text" maxlength="50" placeholder="Enter email address" id="login_email" value="" name="email ">
        <input type="password" maxlength="50" placeholder="Enter password" value="" name="password" id="login_password" >
        <div class="keep_login_check">
                          <input type="checkbox"  value="yes" id="reseller-login">
                          <label for="reseller-login">Keep me Logged in</label>
                          <div class="clear"></div>
                        </div>
        <input type="submit" id="login" value="Login">
      </form> <div class="clear"></div>
      <a class="forgot_password" href="javascript:void(0)"> Reset / Forgot Password</a>
      <div class="clear"></div><br />
      <small class="by_tm">By logging in you agree to our <a href="terms-of-service.php" style="color:#ffcc00">Terms and Conditions</a> &amp; <a href="privacy-policy.php" style="color:#ffcc00">Privacy Policy</a>.</small> <div class="clear"></div>
    </div>
  </div>
</div>
<!--login-popup end -->

<section id="main-wrap">  
    
    <!-- start banner block -->
    <article class="banner-block">
    
    <div class="signlogin-area clearfix">
    <div class="box-row theme-width">
    <div class="signlogin"><a class="scroll-tabs" rel="request-block">Sign Up</a> <a class="receller-login">login</a></div>
    </div>
    </div>
    
      <div class="box-row theme-width">
        <div class="left-side">
          <div class="heading-area">
            <h1><span>Grow</span> Your Web And<br><span>Creative Agency</span> revenue <sub>Offer Great Quality Apps to Your Clients</sub></h1>
          </div>
          <div class="page-info-link">
            <a class="scroll-tabs" rel="program-block">Program Details</a>
            <a class="scroll-tabs" rel="reason-block">Reasons</a>
            <a class="scroll-tabs" rel="request-block">Request Info</a>
          </div>
        </div>
        <div class="right-side">
          <div class="device-area"><div class="create-app-button view-demo"> <a href="" title="Create your app">View Demo</a> </div></div>
        </div>
      </div>
    </article>
    <!-- end banner block -->
    
    <div class="clearfix"></div>
    
    <!-- start program block -->
    <article class="program-block">
      <div class="box-row">
        <div class="program-heading">
          <h2><span>Program</span> Details</h2>
        </div>
        <div class="program-list-box">
          <ul class="program-list">
            <li> 
              <div class="img-row"><img src="images-new/pro-icon-1.png"> </div>
              <span class="program-name">Add Native Mobile apps to your services</span> 
              <span class="program-detail">Our reseller program is designed to enable and equip design agencies, advertising, marketing and web development agencies to Add App development to their existing service portfolio instantly, and enable individuals to set up an app development service company within a few days. </span> 
            </li>
            <li>
              <div class="img-row"><img src="images-new/pro-icon-2.png"> </div>
              <span class="program-name">White label Solution</span> 
              <span class="program-detail">Design mobile apps for your clients and release them under your or your client’s accounts. Our enterprise plans also come with a dashboard panel with only your branding! </span>
            </li>
            <li>
              <div class="img-row"><img src="images-new/pro-icon-3.png"> </div>
              <span class="program-name">Dashboard access for your clients</span> 
              <span class="program-detail">Get individual dashboard access for your clients with a CMS panel which enables full app control and updates, unlimited notifications, app analytics, user data access and more. </span> 
            </li>
            <li>
              <div class="img-row"><img src="images-new/pro-icon-4.png"> </div>
              <span class="program-name">Discounts on bulk pricing</span> 
              <span class="program-detail">We have generous discounts with over 80% off for our partners. Build hundreds of apps with Instappy without investing in any coding or development + Earn additional revenue with App marketing and related services for your clients.  </span> 
            </li>
            <li>
              <div class="img-row"><img src="images-new/pro-icon-5.png"> </div>
              <span class="program-name">Training and premium support</span> 
              <span class="program-detail">Our Instappy partner plan is truly business success in a box. We train your teams, provide premium online support, marketing materials, promotional kits and even local business leads. What’s more? You get your very own account manager. </span> 
            </li>
            <li>
              <div class="img-row"><img src="images-new/pro-icon-6.png"> </div>
              <span class="program-name">Your own Wizard app</span> 
              <span class="program-detail">Our enterprise plans come with your very own white label Instappy wizards. With your branding and launched under your account. The wizard’s enable you to offer state of the art premium on device app testing to your clients.</span> 
            </li>
          </ul>
        </div>
      </div>
    </article>
    <!-- end programs block --> 

    <!-- start reason block -->
    <article class="reason-block">
    
      <div class="reason-heading">
        <div class="box-row theme-width">
          <h2><span>Reasons to</span> become an Instappy partner </h2> 
          <p>Gartner forecasts that by 2017, demand for enterprise mobile apps would outstrip available development capacity five to one. This inevitable demand-supply gap is a wake-up calls for businesses to jumpstart their app development, your client are building or planning to build mobile apps for their business already. The question is that are they building with you or looking for alternate companies for this service? <br><br> Mobile App development is an industry on the fast track already worth billions of dollars and growing; Building your business offering to include mobile solutions will help retain existing clients, increase new client acquisition and help your company stay ahead of competition. 
          </p>
        </div>
      </div>

      <div class="box-row"> 
        <div class="reason-list-box">
          <ul class="reason-list">
            <li> 
              <div class="img-row"><img src="images-new/reason-icon-1.png"> </div>
              <span class="reason-name">Set up your App development company instantly</span> 
            </li>
            <li> 
              <div class="img-row"><img src="images-new/reason-icon-2.png"> </div>
              <span class="reason-name">Add mobile apps to your existing business services</span> 
            </li>
            <li> 
              <div class="img-row"><img src="images-new/reason-icon-3.png"> </div>
              <span class="reason-name">Build revenue from your existing clients</span> 
            </li>
            <li> 
              <div class="img-row"><img src="images-new/reason-icon-4.png"> </div>
              <span class="reason-name">Stay ahead of Competition</span> 
            </li>
            <li> 
              <div class="img-row"><img src="images-new/reason-icon-5.png"> </div>
              <span class="reason-name">Acquire new clients</span> 
            </li>
          </ul>
        </div>
      </div>

    </article>
    <!-- end reason block --> 

    <!-- start request block -->
    <article class="request-block">
      <div class="box-row theme-width">
        <div class="request-heading">
          <h2><span>Request</span> Information</h2> 
        </div>
        <div class="request-form" ng-app="App" ng-controller="AppReseller">
          
            <ul class="request-list">
              <li class="mini-input"><input class="first_name" maxlength="50" type="text" value="" placeholder="First Name" /></li>
              <li class="mini-input right-side"><input class="last_name" maxlength="50" type="text" value="" placeholder="Last Name" /></li>
              <li class="mini-input"><input class="email_address" type="text" value="" placeholder="Email" ng-model='email' ng-blur="validateEmail();"/></li>
              
              <li class="mini-input right-side">
                <input class="area-code country_code" type="text" value="" placeholder="(+91)" />
                <input class="area-name mobile_number" type="text"  maxlength="16" minlength="5"  value="" placeholder="Phone Number" onkeypress="return isNumber(event)"/>
              </li>
              <li><input class="password" type="password"  value="" placeholder="Password" name="password"/></li>
              <li><input class="confirmpassword" type="password" value="" placeholder="Confirm-Password" /></li>
              <li>
                <select class="custom-design seqQues" data-placeholder="Security Question">
                 <option value=""></option>
                <?php
				foreach($res as $opt)
				{
				?>
                 
                  <option value="<?php echo $opt['id'];?>"><?php echo $opt['question'];?></option>
                 
                  <?php
				}
				?>
                </select>
              </li>
              <li>
                <input class="Qaswer" data-placeholder="Security Question Answer" type="text" maxlength="50" value="" placeholder="Security Question Answer"/>
                
              </li>
              <li><textarea class="information Address" maxlength="250" value="" placeholder="Address"></textarea></li>
            </ul>  
            <div class="request-info-btn">
            <label ng-if='emailMsg' class="emlErr">{{emailMsg}}</label>
              <input type="hidden" value="<?php echo $_SERVER['REMOTE_ADDR'];?>" class="ip_address">
			        <div class="result"></div>
              <button class="save">Request Information</button> 
            </div>
         
        </div>
      </div>
    </article>
    <!-- end request block --> 

    <div class="clearfix"></div>

    <article class="box-row">
      
      <div class="featured-cust">
        
        <h4><span>Our</span> Featured Customers</h4>
        <!--slide scrolll start --> 
        
        <!-- Slider Starts -->
        <div class="bottom-scroller">
          <a href="#" data-flip-testimonial="prev"><i class="fa fa-angle-left"></i></a>
          <a href="#" data-flip-testimonial="next"><i class="fa fa-angle-right"></i></a>
          
          <div id="flat">
            <ul>
              
              <li data-flip-title="Bryan">
                <p>With a Rapid Mobile App Development platform like Instappy, you neither have to be a professional developer nor you need to hire one to start your own app development venture. I am glad to be their reseller partner; it has added to my service offerings.  
                  <strong>Bryan Connor</strong> 
  								<!-- <small></small> -->
  								<!-- <span><a href=""></a></span>--> 
                </p> 
              </li>
              
              <li data-flip-title="Natasha">
                <p>We are a reseller partner with Instappy for the last 2 months. Till now the experience has been smooth and very professional. The platform is robust and advanced, be it the ease of use, the training material or the support they provided. I am looking forward to the future. 
                  <strong>Natasha Cole</strong>
                </p>   
              </li>
              
              <li data-flip-title="Michael">
                <p>Instappy is all you need to get from having no technical know-how of app development to creating dozens of professional apps in no time. We are a small web agency and have benefited immensely from the platform. 
                  <strong>Michael Burke</strong>
                </p>
              </li>

              <li data-flip-title="Daniel">
                <p>I am grateful of Instappy’s support team. They know well the technical nuances of app development but their speed in responding is what makes them really stand out. I have the support needed to respond quickly to my clients which is critical in my region. 
                  <strong>Daniel Crux</strong> 
                </p>
              </li>

              <li data-flip-title="Alicia">
                <p>Instappy’s reseller program is truly designed as a turn-key solution for all your app development needs while maximising your profitability. In the last one month, I have closed more deals and delivered more business than ever before. 
                  <strong>Alicia Bells</strong> 
                </p>
              </li>

              <li data-flip-title="Siddhartha">
                <p>Instappy has all the features and functionality (and possibly more) that clients demand. There is immense flexibility in the UI and we specially like the wizards for a seamless testing on multiple devices. A lot of our clients are online relationships in different regions and we have been able to reduce information drag as well as delivery times. Just go Instappy!  
                  <strong>Siddhartha Suman</strong> 
                </p>
              </li>

            </ul>
          </div>
        </div>
        <!-- Slider Ends --> 
        
        <!--slide scrolll end -->
        
        <div class="clearfix"></div>
      </div>
    </article>
    
    <div class="clearfix"></div>
  
</section>
<!-- end main wrap --> 



<!--Always load script files after the footer-->

<!-- start footer -->
<?php require_once('website_footer_new.php');?>
<!-- end footer --> 

<script src="js/main-reseller.js"></script>