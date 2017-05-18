<!-- start header -->
  <?php require_once('website_header_new.php');?>
  <?php
unset($_SESSION['catid']);
unset($_SESSION['themeid']);
unset($_SESSION['currentpagevisit']);
?>
  <!-- end header --> 

<link href="css/content-apps.css" rel="stylesheet" type="text/css">

<!--video-popup start -->
<div class="overlay">
  <div class="video-popup"> 
    <div id="video-popup"></div>  
    <div class="popupClose"><img src="images-new/close.png"></div>
  </div>
</div>
<!--video-popup end -->

<section id="main-wrap">  
    
  <!-- start banner block -->
  <article class="banner-block">
    <div class="box-row theme-width">
      <div class="heading-area">
        <h1>Mobile<span> is the new hub <br>for </span> business and more.</h1>
        <p>Instantly go mobile with Instappy.</p>
      </div>
    </div>
    <div class="banner-overlay"></div>
  </article>
  <!-- end banner block -->
  
  <div class="clearfix"></div>
  
  <!-- start create app block -->
  <article class="create-app-block">
   
    <div class="box-row">

      <div class="app-category">
        
        <div class="pro-app">
        
          <a id="pro-app-link" class="active app_selected" rel="pro_app" href="javascript:void(0);" title=""> 
            <img src="images-new/pro-app-img.jpg"> 
            <!-- <span>Pro Apps</span>  -->
          </a> 
          
        </div>

        <div class="pro-app-content">
          
          <div class="pro-app-heading">
            <h2><span>Professional Apps</span> for Business</h2>
          </div>

          <div class="pro-app-details">
            <P>Designers, universities, photographers, artists, restaurants, and even magazines can now build customer loyalty by making it easier for them to stay connected! Instappy makes it possible to create affordable, high quality native database-led applications in less than 48 hours across platforms, while opening up your business to a wealth of customer-base and business expansion. Go the extra mile by offering valuable information, inviting loyalty programs, exclusive discounts, and convenient tools. Select from a wide variety of mobile app categories and amazing templates to build your own mobile app in an instant. With superior content publishing and designing capabilities, Instappy will ensure that your vision is translated into a stunning mobile reality.</P>
          </div>
          
          <div class="pro-app-button">
            <a class="pro-view-demo" href="">view demo</a>
            <a class="pro-create-app" href="themes.php">create app</a>
          </div>
        </div>
  
        <div class="clearfix"></div>

      </div>

      <div class="category-list-heading">
        <h2>Features<span> for Awesome Apps</span></h2>
      </div>

      <!--pro-app-menu-start -->
      <div class="pro-app-menus pro-apps">
        <ul>
           
        </ul>
      </div>
      <!--pro-app-menu-end --> 
      
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
  
  <!-- start pick app block -->
  <article class="pick-app-block">
    <div class="box-row">
      <div class="pick-app-heading">
        <h2><span>Pick Your</span> App Style</h2>
        <p>Choose from 100+ designs loaded with features in our stunning library</p>
      </div>
      <div class="pick-style-box ">
        <ul class="pick-style-list bxslider">
          <li> <img src="images-new/style-slide1.jpg"> </li>
          <li> <img src="images-new/style-slide2.jpg"> </li>
          <li> <img src="images-new/style-slide3.jpg"> </li>
          <li> <img src="images-new/style-slide4.jpg"> </li>
          <li> <img src="images-new/style-slide5.jpg"> </li>
        </ul>
      </div>
      <div class="library-button"> <a href="themes.php" title="">Go to library</a> </div>
    </div>
  </article>
  <!-- end pick app block --> 

  <!-- start steps block -->
  <article class="steps-block">
    
    <div class="box-row">
      
      <div class="steps-heading">
        <h2><span>Create Your App In</span> 5 Easy Steps</h2>
      </div>

      <div class="steps-list-box">
        <ul class="steps-list">
          <li> <img src="images-new/step-img1.png"> <span class="step-name">Step 1</span> <span class="step-detail">Choose your theme and start customising</span> </li>
          <li> <img src="images-new/step-img2.png"> <span class="step-name">Step 2</span> <span class="step-detail">Add rich media content,
            data, or a mobile store</span> </li>
          <li> <img src="images-new/step-img3.png"> <span class="step-name">Step 3</span> <span class="step-detail">Test your app with 
            the Instappy Wizard</span> </li>
          <li> <img src="images-new/step-img4.png"> <span class="step-name">Step 4</span> <span class="step-detail">Launch your app <br>
            instantly</span> </li>
          <li> <img src="images-new/step-img5.png"> <span class="step-name">Step 5</span> <span class="step-detail">Promote your app 
            and increase revenue</span> </li>
        </ul>
      </div>
    </div>
  </article>
  <!-- end steps block --> 

</section>
<!-- end main wrap --> 

<!-- start footer -->
<?php require_once('website_footer_new.php');?>
<!-- end footer --> 

<!--Always load script files after the footer-->
<script src="js/main-content-apps.js"></script> 
