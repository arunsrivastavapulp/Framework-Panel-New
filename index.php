<!-- start header -->
  <?php require_once('website_header_new.php');?>
  <?php
unset($_SESSION['catid']);
unset($_SESSION['themeid']);
unset($_SESSION['currentpagevisit']);
?>
  <!-- end header --> 

<link href="css/layout.css" rel="stylesheet" type="text/css">
<!--video-popup start -->
<div class="overlay">
  <div class="video-popup"> 
    <div id="video-popup"></div>
    <div class="popupClose"><img src="images-new/close.png" alt="App Builder Instappy" title="App Builder Instappy"></div>
  </div>
</div>

<!--video-popup end -->
<section id="main-wrap">  
  <!-- start content wrap -->
  <section id="content-wrap"> 
    
    <!-- start banner block -->
    <article class="banner-block">
      <div class="banner-bg"></div>
      <div class="box-row">
        <div class="left-side">
          <div class="heading-area">
            <h1>Create Stunning Native Apps<sub>for iOS and Android</sub></h1>
          </div>
        </div>
        <div class="right-side">
          <div class="device-area"> <img src="images-new/device-img.png" alt="Create Your Own App" title="Create Your Own App"> </div>
        </div>
        <div class="button_position">
          <div class="create-app-button"> <a href="themes.php" title="Create your app" ia-track="IA1010007">Create your app</a> </div>
          <div class="clear"></div>
          <div class="watch-demo-box">
            <div class="demo-button"> <a href="#" title="Rich Media and Content Apps: Built with Instappy App Builder">Watch a Demo</a> </div>
            <div class="detail-hint">
             <p>
                Beautiful Apps for tablets and phones with expert support,  </p>
               
                 <p> fully loaded features, custom APIs and more.
              </p>
            </div>
          </div>
        </div>
      </div>
    </article>
    <!-- end banner block -->
    
    <div class="clearfix"></div>
    
    <!-- start create app block -->
    <article class="create-app-block">
      <div class="box-row">
        <div class="create-app-head">
          <h2>Powerful, Professional Features for Awesome Apps</h2>
          <p>Retain your customers, engage with prospects, promote your business, increase your revenue,  
            and sell on mobile with awesome Instappy features.</p>
        </div>
        <div class="app-category">
          <div class="pro-app"> <a id="pro-app-link" class="active app_selected" rel="pro_app" href="javascript:void(0);" title=""> <img src="images-new/pro-app-img.jpg" alt="Best Shopping Apps" title="Best Shopping Apps"> <span>Pro Apps</span> </a> 
            
            <!--pro-app-menu-start -->
            <div class="pro-app-menus pro-apps">
              <ul>                 
              </ul>
            </div>
            <!--pro-app-menu-end --> 
            
          </div>
          <div class="pro-app"> <a id="shopping-app-link" class="app_selected" rel="shoping_app" href="javascript:void(0);" title=""> <img src="images-new/shop-app-img.jpg" alt="Best Shopping Apps" title="Best Shopping Apps"> <span>Shopping Apps</span> </a> 
          
          <div class="pro-app-menus shop-apps">
            <ul>     
            </ul>
          </div>
          
          </div>
        </div>
        <div class="app-category-list-box">
          <ul class="app-category-list slider4">
          </ul>
        </div>
      </div>
      <div class="app-category-list-view">
        <div class="box-row">
          <div class="box-row-inner">
            <div class="icon-side"> <span> </span><!--<img src="images-new/list-icon1_b.png" alt=""> --> </div>
            <div class="content-side">
              <h3>Unlimited Customisation</h3>
              <p>Lorem ipsum dolor sit amet, at vel nusquam efficiantur voluptatibus. At est dicant tibique quaestio, vis ne suas alia case. At eam dolorem suscipit, sit <br>
                <strong>Lorem ipsum</strong><br>
                <span>App name</span>doctus facilis splendide ad.</p>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </article>
    <!-- end create app block --> 
    
    <!-- start steps block -->
    <article class="steps-block">
      <div class="box-row">
        <div class="steps-heading">
          <h2>Create Your App In 5 Easy Steps</h2>
        </div>
        <div class="steps-list-box">
          <ul class="steps-list">
            <li> <img src="images-new/step-img1.png" alt="Android App Maker" title="Android App Maker"> <span class="step-name">Step 1</span> <span class="step-detail">Choose your theme and start customising</span> </li>
            <li> <img src="images-new/step-img2.png" alt="Application Maker" title="Application Maker"> <span class="step-name">Step 2</span> <span class="step-detail">Add rich media content,
              data, or a mobile store</span> </li>
            <li> <img src="images-new/step-img3.png" alt="Android Application Maker" title="Android Application Maker"> <span class="step-name">Step 3</span> <span class="step-detail">Test your app with 
              the Instappy Wizard</span> </li>
            <li> <img src="images-new/step-img4.png" alt="Mobile App development" title="Mobile App development"> <span class="step-name">Step 4</span> <span class="step-detail">Launch your app <br>
              instantly</span> </li>
            <li> <img src="images-new/step-img5.png" alt="App development Company" title="App development Company"> <span class="step-name">Step 5</span> <span class="step-detail">Promote your app 
              and increase revenue</span> </li>
          </ul>
        </div>
      </div>
    </article>
    <!-- end steps block --> 
    
    <!-- start pick app block -->
    <article class="pick-app-block">
      <div class="box-row">
        <div class="pick-app-heading">
          <h2>Pick Your App Style</h2>
          <p>Choose from 100+ designs loaded with features in our stunning library</p>
        </div>
        <div class="pick-style-box ">
          <ul class="pick-style-list bxslider">
            <li> <img src="images-new/style-slide1.jpg" alt="Retailer App" title="Retailer App"> </li>
            <li> <img src="images-new/style-slide2.jpg" alt="Mobile shopping" title="Mobile shopping"> </li>
            <li> <img src="images-new/style-slide3.jpg" alt="Ecommerce App" title="Ecommerce App"> </li>
            <li> <img src="images-new/style-slide4.jpg" alt="Travel App" title="Travel App"> </li>
            <li> <img src="images-new/style-slide5.jpg" alt="Real Estate App" title="Real Estate App"> </li>
          </ul>
        </div>
        <div class="library-button"> <a href="themes.php" title="" ia-track="IA1010007">Go to library</a> </div>
      </div>
    </article>
    <!-- end pick app block --> 
    
    <!-- start need help block -->
    
    <article class="need-help">
      <div class="box-row">
        <h3>Need Help?</h3>
        <span>Have a team of experts help you <br>
        build your awesome app!</span> </div>
    </article>
    <!-- end need help block --> 
    
    <!-- start about and img block -->
    
    <article class="about-us">
      <h3>Test Your App Real Time On Any Device</h3>
      <div class="logo-area">
        <div class="box-row">
          <div class="logo-area-inside">
            <div class="logo-area-wizard">
              <figure><img src="images-new/wizard.png"  alt="Android App Builder-Instappy Wizard" title="Android App Creator"/></figure>
              <div class="logo-area-icon"><a href="https://play.google.com/store/apps/details?id=com.pulp.wizard" target="_blank"><img src="images-new/android.png" alt="Android App Creator" title="Android App Creator" /></a> <a href="https://itunes.apple.com/us/app/instappy/id1053874135?mt=8" target="_blank"><img src="images-new/apple.png" alt="iphone App Maker" title="iphone App Maker" /></a></div>
            </div>
            <span><img src="images-new/wizard-diveder.png" alt="App Maker For Android - Instappy Wizard" title="App Maker For Android - Instappy Wizard" /></span>
            <div class="logo-area-retail">
              <figure><img src="images-new/retail.png" alt="Retail App Maker" title="Retail App Maker" /></figure>
              <div class="logo-area-icon"><a href="https://play.google.com/store/apps/details?id=com.pulp.wizard" target="_blank"><img src="images-new/android.png" alt="Android App Creator" title="Android App Creator" /></a> <a href="https://itunes.apple.com/us/app/instappy/id1053874135?mt=8" target="_blank"><img src="images-new/apple.png" alt="iphone App Maker" title="iphone App Maker" /></a></div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="clearfix"></div>
      <div class="yellow-part">
        <div class="box-row">
          <h3>About <span>Instappy</span></h3>
          <p>Instant, affordable, intuitive, and stunning! Build fully native applications for iOS and Android instantly with Instappy. 
            It’s hassle-free, quick, and you don't need any coding skills. Instappy has everything you need to create amazing, 
            fully-loaded, and original apps. Choose from our built for success, fully-customisable templates, or create your own to 
            launch your mobile application for smartphones and tablets in an instant.</p>
        </div>
      </div>
      <div class="about-bg"> <!--<img src="images-new/rib.png" alt="">-->
        <div class="box-row">
          <div class="text"><h2>What
<small>is Instappy?</small></h2><p>Instappy is the simple-to-use and built-for-success platform which develops your app idea into a mobile reality. Instappy is for businesses and individuals alike; our goal is to turn your audience's smartphones and tablets into windows of opportunity for you. You can retain your customers, engage with prospects, increase your revenue, and sell on mobile using your very own branded mobile application.
            Instappy is loaded with features that, until now, were almost always exclusively available only with full-scale development effort.</p></div>
        </div>
      </div>
    </article>
    <!-- end about and img block -->
    <div class="clearfix"></div>
    
    <!-- start white block -->
    <article  class="box-row">
      <div class="profess-app">
        <div class="profess-app-img"> <img src="images-new/profosnal.jpg" alt="Shopping Apps" title="Shopping Apps"> </div>
        <div class="profess-app-text">
          <h5>Professional Apps for Business</h5>
          <p>Designers, universities, photographers, artists, restaurants, and even magazines can now build customer loyalty by making it easier for them to stay connected! Go the extra mile by offering valuable information, inviting loyalty programs, exclusive discounts, and convenient tools. Select from a wide variety of mobile app categories and amazing templates to build your own mobile app in an instant. With superior content publishing and designing capabilities, Instappy will ensure that your vision is translated into a stunning mobile reality.</p><div class="home-button clear">
          <a href="themes.php" ia-track="IA1010007">Create App</a> 
<a href="content-apps.php" ia-track="IA1010002">Learn more</a>

</div>
        </div>
        <div class="clearfix"></div>
      </div>
    </article>
    <div class="clearfix"></div>
    <article  class="box-row">
      <div class="shopping-app">
        <div class="shopping-app-img"> <img src="images-new/shop-app.jpg" alt="Online Shopping Apps" title="Online Shopping Apps" > </div>
        <div class="shopping-app-text">
          <h5>Shopping Apps</h5>
          <p>Got something to sell? Take your business to the next level with a dedicated retail and catalogue mobile app. Retail and catalogue apps with Instappy are built for mobile commerce, equipped with stock and inventory management, shopping carts, as well as ready for secure payment gateway integration. Provide your customers the convenience of purchasing your products at their fingertips. Whether you own an export house, a niche fashion boutique, a superstore, or an exotic pet shop, your mobile commerce store is instantly ready with Instappy.</p><div class="home-button clear">
<a href="themes.php" ia-track="IA1010007">Create App</a>
<a href="shopping-apps.php" ia-track="IA1010003">Learn more</a>
 
</div>
        </div>
        <div class="clearfix"></div>
      </div>
    </article>
    <div class="clearfix"></div>
    <article  class="box-row">
      <div class="profess-app">
        <div class="profess-app-img"> <img src="images-new/enterpris-app.jpg" alt="Enterprise Application" title="Enterprise Application"> </div>
        <div class="profess-app-text">
          <h5>Enterprise Apps</h5>
          <p>Build sales management applications, campaign management applications, and HR ERP applications. Fully customisable with team management, on-the-go attendance and location tracking, and inventory as well as order management. Add transparency, control, and accountability to your business with your own ERP mobile application. Develop a one-stop-solution to streamline processes, track resources, increase management visibility, and improve organisational efficiency. Get the most effective business tracker for your business, only with Instappy.</p><div class="home-button clear">
<a href="enterprise-mobile-apps.php" ia-track="IA1010004">Learn more</a>
 
</div>
        </div>
        <div class="clearfix"></div>
      </div>
    </article>
    <div class="clearfix"></div>
    <article  class="box-row">
      <div class="featured-cust">
        <h4><span>Our</span> Featured Customers</h4>
        <!--slide scrolll start --> 
        <!-- Slider Starts -->
        <div class="bottom-scroller">
          <a href="#" data-flip-testimonial="prev"><i class="fa fa-angle-left"></i></a>
          <a href="#" data-flip-testimonial="next"><i class="fa fa-angle-right"></i></a>
          <div id="flat">
            <ul>

              <li data-flip-title="PREET GYMNASIUM">
                <p>“Gymnasium app has had a dramatic impact on business and customer engagement. Not only have we witnessed an upsurge in new member registration, but also a substantial improvement in member attendance and engagement on social platforms. Our members are simply thrilled at all what they can do with this app.”
                  <strong>PREET</strong> 
                  <small>FOUNDER, PREET GYMNASIUM - FITNESS CENTER</small>
                  <span><a href="http://www.instappy.com/success-stories/preet-gymnasium-app-a-complete-fitness-center/">PREET GYMNASIUM</a></span> 
                </p>  
              </li>            

              <li data-flip-title="GAURAV HAIR CLINIC">
                <p>“Ours is the kind of business that runs on word-of-mouth. We needed a robust marketing channel and that’s when we thought of going mobile. The idea was to reach the potential customers before the competitors did and in this smartphone age, I think having a mobile app for our business was the best way to get ahead of the game.”
                  <strong>BANWARI LAL</strong> 
                  <small>CO-FOUNDER, GAURAV HAIR CLINIC - HAIR WIG MANUFACTURER AND SUPPLIER</small>
                  <span><a href="http://www.instappy.com/success-stories/gaurav-hair-clinic-hair-today-and-forever/">GAURAV HAIR CLINIC</a></span> 
                </p>  
              </li>    

              <li data-flip-title="LET’S START">
                <p>“It took us just few hours to put together an app on Instappy; we shot some fresh photographs of our school and kids in candid, carefree moments. Instappy allowed us to use them on their vibrant templates. It was amazingly simple. Definitely, a boon for people hard-pressed for time, like me,”
                  <strong>RICHA AGGARWAL</strong> 
                  <small>FOUNDER, LET’S START - PLAYSCHOOL</small>
                  <span><a href="http://www.instappy.com/success-stories/lets-start/">LET’S START</a></span> 
                </p>  
              </li>                                     
              
              <li data-flip-title="EVOQUE">
                <p>“It took us just few hours to put together an app on Instappy; we shot some fresh photographs of our school and kids in candid, carefree moments. Instappy allowed us to use them on their vibrant templates. It was amazingly simple. Definitely, a boon for people hard-pressed for time, like me,”
                  <strong>HITESH SOOD</strong> 
                  <small>CO-FOUNDER, EVOQUE – SPA AND BEAUTY SALON</small>
                  <span><a href="http://www.instappy.com/success-stories/evoque-app/">EVOQUE</a></span> 
                  </p>
              </li>  

              <li data-flip-title="TCB">
                <p>“The California Boulevard app begins by helping our visitors choose the meal of their choice and ends at capturing their delighted reviews.”
                  <strong>RAJAN SETHI</strong> 
  								<small>FOUNDER, TCB - HIGH END RESTAURANT CHAIN</small>
  								<span><a href="http://www.instappy.com/success-stories/the-california-boulevard/">TCB </a></span> </p>
              </li>
              <li data-flip-title="SOGNO">
                <p>“Our products have come alive on the Sogno app! Our close customers and the first group of people we took an opinion from are highly impressed with the way the app has enlivened the brand.”
                  <strong>POOJA CHOUDHARY</strong> 
  								<small>FOUNDER, SOGNO - ITALIAN DESIGNER FURNITURE</small>
  								<span><a href="http://www.instappy.com/success-stories/sognointerno/">SOGNO</a></span> </p>
              </li>
              <!-- <li data-flip-title="GARTNER">
                <p>“As per Gartner, app development is heading towards 5:1 demand-supply ratio. A major fraction of businesses will switch to DIY App Building platforms to fill this app gap”
                  <strong><a href="http://www.instappy.com/success-stories/sognointerno/">GARTNER</a></strong> 
  								<small>
GARTNER'S PREDICTION 2017</small>
  								 </p>
              </li> -->
              <li data-flip-title="Studio J">
                <p>“They see, they like, they order. We have got a few calls from people saying they like the purple gown on our app, and would like to place an order. That's when we knew we did the right thing.”
                  <strong>JASMEET</strong> 
  								<small>co-founder, Studio J - ONLINE FASHION BOUTIQUE</small>
  								<span><a href="http://www.instappy.com/success-stories/studio-j-app/">STUDIO J</a></span> </p>
              </li>
               
               <li data-flip-title="ABSS GROUP">
                <p>“Since the launch of our app, our followers and audience have been growing steadily. I am sure it'll take our theatre group to places”
                  <strong>RAJENDRA TIWARI </strong> 
  								<small>FOUNDER, ABSS GROUP - THEATRE SCHOOL</small>
  								<span><a href="http://www.instappy.com/success-stories/abss-theatre-group/">ABSS THEATRE GROUP</a></span>
               </li>
               
               <li data-flip-title="ASTROWORLD">
                <p>“I always wished to take the online route to reach out in a better way to people seeking astro-help. When I got to know about instant apps by Instappy, I had no reasons left to delay my app any further.”
                  <strong>DR. H. S. RAWAT  </strong> 
  								<small>FOUNDER, ASTROWORLD - ASTROLOGY CONSULTANTS</small>
  								<span><a href="http://www.instappy.com/success-stories/h-s-rawat/">DR H S RAWAT</a></span>
               </li>
               
                
               
            </ul>
          </div>
        </div>
        <!-- Slider Ends --> 
        
        <!--slide scrolll end -->
        
        <div class="clearfix"></div>
      </div>
    </article>
    
    <!-- end white block -->
    <div class="clearfix"></div>
  </section>
  <!-- end content wrap --> 
  
</section>
<!-- end main wrap --> 
<!-- start footer -->
<?php require_once('website_footer_new.php');?>
<!-- end footer --> 

<!--Always load script files after the footer-->
<script src="js/main-index.js"></script> 
