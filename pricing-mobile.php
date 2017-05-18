<!-- start header -->
<?php require_once('website_header_new.php');?>
<?php
   unset($_SESSION['catid']);
   unset($_SESSION['themeid']);
   unset($_SESSION['currentpagevisit']);
   ?>
<!-- end header -->
<script type="text/javascript">
   function fnCheckResolution () {
     // if (window.outerWidth > 1280) {
      if (screen.width > 1180) {
       document.querySelectorAll('html')[0].style.display = 'none';
       window.location.href = 'pricing.php';
     }
   }
   fnCheckResolution();
   window.onfocus = fnCheckResolution;
   window.onresize = fnCheckResolution;
</script>
<link rel="stylesheet" type="text/css" href="css/pricing-mobile.css">
<!-- start pricing wrap -->
<section id="pricing-wrap">
   <div class="clearfix"></div>
   <!-- start pricing banner block -->
   <article class="pricing-banner-block">
      <div class="mobi-price-head">
         <div class="heading-area">
            <h1>Choose Your Package</h1>
         </div>
         <div class="detail-area">
            <p>Apps built using Instappy.com are packed with features that are built for your success. Instappy apps are instant, affordable, stunning, intuitive and will change the way you interact with your customers. Our business model allows us to provide everyone with world-class feature-packed applications on Android and iOS for a fraction of the cost it takes to develop an app otherwise.</p>
            <a href="themes.php">Create App</a>
            <span>All our plans are white label, with advanced data management, secure cloud hosting and unlimited downloads.</span>
         </div>
      </div>
   </article>
   <!-- end pricing banner block -->
   <!-- start pricing mobile block -->
   <div class="pricing-mobile-block">
      <ul class="mobi-price-list">
         <li class="header_list">
            <div class="listBorder">
               <div class="mb-cell">
                  <div class="priceClass">
                     <span class="price">$29</span><span class="pm">PM</span><br>
                     <span class="anuallyPrice"><strong> $348 </strong> Billed Annually</span>
                  </div>
               </div>
               <div class="mb-cell">
                  <div class="advanced"><span class="largeText">Advanced </span></div>
               </div>
               <div class="mb-cell">
                  <a class="closeHeader"><i class="fa fa-angle-right fa-2x"></i></a>
               </div>
            </div>
            <div class="mobi-content">
               <ul class="nested-mobile-price-list">
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Highlights
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content ">
                        <ul class="nested-list-content ">
                          <li>Google indexed apps for Tablets / Phablets / Phones / Ipads / Ipad Mini</li>
                          <li>Native Apps with Core features</li>
                          <li>White label apps, launched under your own account</li>
                          <li>Unlimited Push Notifications</li>
                          <li>Unlimited Downloads</li>
                          <li>Expert Assistance in creating your app</li>
                          <li>Live  preview's and on device trial.  (Wizard)</li>
                          <li>CMS panel for unlimited  app updates</li>
                          <li>Auto updates for iOS and Android OS</li>
                          <li>User sign up (email, FB, G+) Included</li>
                          <li>Utilities,  marketing tools, social media API's included</li>
                          <li>Secure cloud hosting and back up</li>
                          <li>Complete design control</li>
                          <li>App marketing and ASO consultation</li>
                          <li>Smart App analytics + live stats reporting</li>
                          <li>Upto 2 GB data</li>
                          <li>Runs Relevant Ads</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Design
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Slick adaptive native UI design</li>
                           <li>Unlimited screens</li>
                           <li>Intuitive design</li>
                           <li>Custom navigation bar</li>
                           <li>Customised rich media icon</li>
                           <li>Customised rich media splash screen</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">API's and Utilities
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>3rd party API’s</li>
                           <li>Rich media social API’s</li>
                           <li>Social API’s</li>
                           <li>Rich brilliant photo gallery</li>
                           <li>HD video gallery</li>
                           <li>Google Maps</li>
                           <li>Calendar + event calendar</li>
                           <li>Add and organise events</li>
                           <li>Social network integration</li>
                           <li>Customised widgets</li>
                           <li>Social sharing</li>
                           <li>Real-time reporting</li>
                           <li>In-app search</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Devices and OS
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Native to Android Fully suported Android 4.0 and above Android 4.0</li>
                           <li>Jelly Bean: Android 4.1, 4.2, 4.3 </li>
                           <li>KitKat: Android 4.4 </li>
                           <li>Lollipop: Android 5.0 and 5.1</li>
                           <li>Native to iOS runs on iOS 7.0, and above (iOS 7.0, 8.0 in iphones, ipads, ipad mini)</li>
                           <li>Works on all tablets, phones, phablets, that support iOS and Android</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Data, Security and Analytics
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Secure hosting</li>
                           <li>Data back-up</li>
                           <li>Smart analytics – live stats reporting</li>
                           <li>Traffic stats</li>
                           <li>Social stats</li>
                           <li>Technical stats</li>
                           <li>Third party stats</li>
                           <li>Fully supported data integration</li>
                           <li>Low data-usage and offline availability</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Support and Testing
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Unlimited designs</li>
                           <li>Save your app designs (unlimited for 30 days)</li>
                           <li>App testing on multiple device formats (simulator)</li>
                           <li>App store optimisation (paid)</li>
                           <li>Test your app on your own device</li>
                           <li>Tech support</li>
                           <li>Marketing support</li>
                           <li>Publishing support</li>
                           <li>Indexing and Optimisation</li>
                           <li>Google search indexed</li>
                           <li>Content publishing support (paid)</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder ">Customisation
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Unlimited users</li>
                           <li>Unlimited updates</li>
                           <li>Built-in CMS and inventory management (For unlimited updates)</li>
                           <li>Unlimited content</li>
                           <li>Unlimited rich images</li>
                           <li>Custom splash screen</li>
                           <li>Custom app Icon</li>
                           <li>Customisable UI</li>
                           <li>Custom color scheme and fonts</li>
                           <li>Custom app name</li>
                           <li>Custom app description</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Consumer engagement
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Feedback forms integrated</li>
                           <li>Push notifications to stay connected with users 24x7</li>
                           <li>Custom QR Code generator</li>
                           <li>Contact us utility</li>
                           <li>Click to call function</li>
                        </ul>
                     </div>
                  </li>
               </ul>
            </div>
            <!-- <div class="mobi-content">
               <ul class="listContent">
                  <li>Google indexed apps for Tablets / Phablets / Phones / Ipads / Ipad Mini</li>
                  <li>Native Apps with Core features</li>
                  <li>White label apps, launched under your own account</li>
                  <li>Unlimited Push Notifications</li>
                  <li>Unlimited Downloads</li>
                  <li>Expert Assistance in creating your app</li>
                  <li>Live  preview's and on device trial.  (Wizard)</li>
                  <li>CMS panel for unlimited  app updates</li>
                  <li>Auto updates for iOS and Android OS</li>
                  <li>User sign up (email, FB, G+) Included</li>
                  <li>Utilities,  marketing tools, social media API's included</li>
                  <li>Secure cloud hosting and back up</li>
                  <li>Complete design control</li>
                  <li>App marketing and ASO consultation</li>
                  <li>Smart App analytics + live stats reporting</li>
                  <li>Upto 2 GB data</li>
                  <li>Runs Relevant Ads</li>
               </ul>
               </div> -->
         </li>
         <li class="header_list">
            <div class="listBorder">
               <div class="mb-cell">
                  <div class="priceClass">
                     <span class="price">$48</span><span class="pm">PM</span><br>
                     <span class="anuallyPrice"><strong> $576 </strong> Billed Annually</span>
                  </div>
               </div>
               <div class="mb-cell">
                  <div class="advanced"><span class="largeText">Platinum Pro</span></div>
               </div>
               <div class="mb-cell">
                  <a class="closeHeader"><i class="fa fa-angle-right fa-2x"></i></a>
               </div>
            </div>
             <div class="mobi-content">
               <ul class="nested-mobile-price-list">
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Highlights
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content ">
                        <ul class="nested-list-content ">
                          <li>Google indexed apps for Tablets / Phablets / Phones / Ipads / Ipad Mini</li>
                          <li>Native Apps with Core features</li>
                          <li>White label apps, launched under your own account</li>
                          <li>Unlimited Push Notifications</li>
                          <li>Unlimited Downloads</li>
                          <li>Expert Assistance in creating your app</li>
                          <li>Live  preview's and on device trial.  (Wizard)</li>
                          <li>CMS panel for unlimited  app updates</li>
                          <li>Auto updates for iOS and Android OS</li>
                          <li>User sign up (email, FB, G+) Included</li>
                          <li>Utilities,  marketing tools, social media API's included</li>
                          <li>Secure cloud hosting and back up</li>
                          <li>Complete design control</li>
                          <li>App marketing and ASO consultation</li>
                          <li>Smart App analytics + live stats reporting</li>
                          <li>Upto 3 GB data</li>
                          <li>Go Ad free or earn with your own ads</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Design
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Slick adaptive native UI design</li>
                           <li>Unlimited screens</li>
                           <li>Intuitive design</li>
                           <li>Custom navigation bar</li>
                           <li>Customised rich media icon</li>
                           <li>Customised rich media splash screen</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">API's and Utilities
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>3rd party API’s</li>
                           <li>Rich media social API’s</li>
                           <li>Social API’s</li>
                           <li>Rich brilliant photo gallery</li>
                           <li>HD video gallery</li>
                           <li>Google Maps</li>
                           <li>Calendar + event calendar</li>
                           <li>Add and organise events</li>
                           <li>Social network integration</li>
                           <li>Customised widgets</li>
                           <li>Social sharing</li>
                           <li>Real-time reporting</li>
                           <li>In-app search</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Devices and OS
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Native to Android Fully suported Android 4.0 and above Android 4.0</li>
                           <li>Jelly Bean: Android 4.1, 4.2, 4.3 </li>
                           <li>KitKat: Android 4.4 </li>
                           <li>Lollipop: Android 5.0 and 5.1</li>
                           <li>Native to iOS runs on iOS 7.0, and above (iOS 7.0, 8.0 in iphones, ipads, ipad mini)</li>
                           <li>Works on all tablets, phones, phablets, that support iOS and Android</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Data, Security and Analytics
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Secure hosting</li>
                           <li>Data back-up</li>
                           <li>Smart analytics – live stats reporting</li>
                           <li>Traffic stats</li>
                           <li>Social stats</li>
                           <li>Technical stats</li>
                           <li>Third party stats</li>
                           <li>Fully supported data integration</li>
                           <li>Low data-usage and offline availability</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Support and Testing
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Unlimited designs</li>
                           <li>Save your app designs (unlimited for 30 days)</li>
                           <li>App testing on multiple device formats (simulator)</li>
                           <li>App store optimisation (paid)</li>
                           <li>Test your app on your own device</li>
                           <li>Tech support</li>
                           <li>Marketing support</li>
                           <li>Publishing support</li>
                           <li>Indexing and Optimisation</li>
                           <li>Google search indexed</li>
                           <li>Content publishing support (paid)</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder ">Customisation
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Unlimited users</li>
                           <li>Unlimited updates</li>
                           <li>Built-in CMS and inventory management (For unlimited updates)</li>
                           <li>Unlimited content</li>
                           <li>Unlimited rich images</li>
                           <li>Custom splash screen</li>
                           <li>Custom app Icon</li>
                           <li>Customisable UI</li>
                           <li>Custom color scheme and fonts</li>
                           <li>Custom app name</li>
                           <li>Custom app description</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Consumer engagement
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Feedback forms integrated</li>
                           <li>Push notifications to stay connected with users 24x7</li>
                           <li>Custom QR Code generator</li>
                           <li>Contact us utility</li>
                           <li>Click to call function</li>
                        </ul>
                     </div>
                  </li>
               </ul>
            </div>
         </li>
         <li class="header_list">
            <div class="listBorder" style="background:#fe5d00">
               <div class="mb-cell">
                  <div class="priceClass textWhite">
                     <span class="price">$57</span><span class="pm">PM</span><br>
                     <span class="anuallyPrice"><strong> $684 </strong> Billed Annually</span>
                  </div>
               </div>
               <div class="mb-cell">
                  <div class="advanced">
                     <span class="subText">Advanced<sub class="ms-pop">Most Popular</sub></span>
                     <span class="largeText">Catalogue </span>
                  </div>
               </div>
               <div class="mb-cell">
                  <a class="closeHeader"><i class="fa fa-angle-right fa-2x"></i></a>
               </div>
            </div>
            <div class="mobi-content">
               <ul class="nested-mobile-price-list">
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Highlights
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content ">
                        <ul class="nested-list-content ">
                          <li>Google indexed apps for Tablets / Phablets / Phones / Ipads / Ipad Mini</li>
                          <li>Native Apps with Core features</li>
                          <li>White label apps, launched under your own account</li>
                          <li>Unlimited Push Notifications</li>
                          <li>Unlimited Downloads</li>
                          <li>Expert Assistance in creating your app</li>
                          <li>Live  preview's and on device trial.  (Wizard)</li>
                          <li>CMS panel for unlimited  app updates</li>
                          <li>Auto updates for iOS and Android OS</li>
                          <li>User sign up (email, FB, G+) Included</li>
                          <li>Utilities,  marketing tools, social media API's included</li>
                          <li>Secure cloud hosting and back up</li>
                          <li>Complete design control</li>
                          <li>App marketing and ASO consultation</li>
                          <li>Smart App analytics + live stats reporting</li>
                          <li>3 GB data and 5000 products</li>
                          <li>Runs Relevant Ads</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Design
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Slick adaptive native UI design</li>
                           <li>Unlimited no. of product categories with different attributes and discount options</li>
                           <li>Comprehensive list of categories and subcategories to choose from</li>
                           <li>Intuitive design</li>
                           <li>Custom navigation bar</li>
                           <li>Customised rich media icon</li>
                           <li>Customised rich media splash screen</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">API's and Utilities
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>3rd party API’s</li>
                           <li>Rich media social API’s</li>
                           <li>Social API’s</li>
                           <li>Rich brilliant photo gallery</li>
                           <li>HD video gallery</li>
                           <li>Google Maps</li>
                           <li>Calendar + event calendar</li>
                           <li>Add and organise events</li>
                           <li>Social network integration</li>
                           <li>Customised widgets</li>
                           <li>Social sharing</li>
                           <li>Real-time reporting</li>
                           <li>Ready for secure payment gateway integration and COD</li>
                           <li>In-app search</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Devices and OS
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Native to Android Fully suported Android 4.0 and above Android 4.0</li>
                           <li>Jelly Bean: Android 4.1, 4.2, 4.3 </li>
                           <li>KitKat: Android 4.4 </li>
                           <li>Lollipop: Android 5.0 and 5.1</li>
                           <li>Native to iOS runs on iOS 7.0, and above (iOS 7.0, 8.0 in iphones, ipads, ipad mini)</li>
                           <li>Works on all tablets, phones, phablets, that support iOS and Android</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Data, Security and Analytics
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Secure hosting</li>
                           <li>Data back-up</li>
                           <li>Smart analytics – live stats reporting</li>
                           <li>Traffic stats</li>
                           <li>Social stats</li>
                           <li>Technical stats</li>
                           <li>Third party stats</li>
                           <li>Fully supported data integration</li>
                           <li>Low data-usage and offline availability</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Support and Testing
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Save your app designs (unlimited for 30 days)</li>
                           <li>App testing on multiple device formats (simulator)</li>
                           <li>App store optimisation (paid)</li>
                           <li>Test your app on your own device</li>
                           <li>Tech support</li>
                           <li>Marketing support</li>
                           <li>Publishing support</li>
                           <li>Indexing and Optimisation</li>
                           <li>Google search indexed</li>
                           <li>Content publishing support (paid)</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder ">Customisation
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Unlimited users</li>
                           <li>Unlimited updates</li>
                           <li>Built-in CMS and inventory management (For unlimited updates)</li>
                           <li>Unlimited content</li>
                           <li>Unlimited rich images</li>
                           <li>Custom splash screen</li>
                           <li>Custom app Icon</li>
                           <li>Customisable UI</li>
                           <li>Custom color scheme and fonts</li>
                           <li>Custom app name</li>
                           <li>Custom app description</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Consumer engagement
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Feedback forms integrated</li>
                           <li>Push notifications to stay connected with users 24x7</li>
                           <li>Custom QR Code generator</li>
                           <li>Contact us utility</li>
                           <li>Click to call function</li>
                        </ul>
                     </div>
                  </li>
               </ul>
            </div>
         </li>
         <li class="header_list">
            <div class="listBorder">
               <div class="mb-cell">
                  <div class="priceClass">
                     <span class="price">$77</span><span class="pm">PM</span><br>
                     <span class="anuallyPrice"><strong> $924 </strong> Billed Annually</span>
                  </div>
               </div>
               <div class="mb-cell">
                  <div class="advanced"><span class="subText">Platinum</span><span class="largeText">Pro Catalogue</span></div>
               </div>
               <div class="mb-cell">
                  <a class="closeHeader"><i class="fa fa-angle-right fa-2x"></i></a>
               </div>
            </div>
             <div class="mobi-content">
               <ul class="nested-mobile-price-list">
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Highlights
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content ">
                        <ul class="nested-list-content ">
                          <li>Google indexed apps for Tablets / Phablets / Phones / Ipads / Ipad Mini</li>
                          <li>Native Apps with Core features</li>
                          <li>White label apps, launched under your own account</li>
                          <li>Unlimited Push Notifications</li>
                          <li>Unlimited Downloads</li>
                          <li>Expert Assistance in creating your app</li>
                          <li>Live  preview's and on device trial.  (Wizard)</li>
                          <li>CMS panel for unlimited  app updates</li>
                          <li>Auto updates for iOS and Android OS</li>
                          <li>User sign up (email, FB, G+) Included</li>
                          <li>Utilities,  marketing tools, social media API's included</li>
                          <li>Secure cloud hosting and back up</li>
                          <li>Complete design control</li>
                          <li>App marketing and ASO consultation</li>
                          <li>Smart App analytics + live stats reporting</li>
                          <li>5 GB data and 8000 products</li>
                          <li>Go Ad free or earn with your own ads</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Design
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Slick adaptive native UI design</li>
                           <li>Unlimited no. of product categories with different attributes and discount options</li>
                           <li>Comprehensive list of categories and subcategories to choose from</li>
                           <li>Intuitive design</li>
                           <li>Custom navigation bar</li>
                           <li>Customised rich media icon</li>
                           <li>Customised rich media splash screen</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">API's and Utilities
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>3rd party API’s</li>
                           <li>Rich media social API’s</li>
                           <li>Social API’s</li>
                           <li>Rich brilliant photo gallery</li>
                           <li>HD video gallery</li>
                           <li>Google Maps</li>
                           <li>Calendar + event calendar</li>
                           <li>Add and organise events</li>
                           <li>Social network integration</li>
                           <li>Customised widgets</li>
                           <li>Social sharing</li>
                           <li>Real-time reporting</li>
                           <li>Ready for secure payment gateway integration and COD</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Devices and OS
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Native to Android Fully suported Android 4.0 and above Android 4.0</li>
                           <li>Jelly Bean: Android 4.1, 4.2, 4.3 </li>
                           <li>KitKat: Android 4.4 </li>
                           <li>Lollipop: Android 5.0 and 5.1</li>
                           <li>Native to iOS runs on iOS 7.0, and above (iOS 7.0, 8.0 in iphones, ipads, ipad mini)</li>
                           <li>Works on all tablets, phones, phablets, that support iOS and Android</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Data, Security and Analytics
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Secure hosting</li>
                           <li>Data back-up</li>
                           <li>Smart analytics – live stats reporting</li>
                           <li>Traffic stats</li>
                           <li>Social stats</li>
                           <li>Technical stats</li>
                           <li>Third party stats</li>
                           <li>Fully supported data integration</li>
                           <li>Low data-usage and offline availability</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Support and Testing
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Save your app designs (unlimited for 30 days)</li>
                           <li>App testing on multiple device formats (simulator)</li>
                           <li>App store optimisation (paid)</li>
                           <li>Test your app on your own device</li>
                           <li>Tech support</li>
                           <li>Marketing support</li>
                           <li>Publishing support</li>
                           <li>Indexing and Optimisation</li>
                           <li>Google search indexed</li>
                           <li>Content publishing support (paid)</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder ">Customisation
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Unlimited users</li>
                           <li>Unlimited updates</li>
                           <li>Built-in CMS and inventory management (For unlimited updates)</li>
                           <li>Unlimited content</li>
                           <li>Unlimited rich images</li>
                           <li>Custom splash screen</li>
                           <li>Custom app Icon</li>
                           <li>Customisable UI</li>
                           <li>Custom color scheme and fonts</li>
                           <li>Custom app name</li>
                           <li>Custom app description</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Consumer engagement
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Feedback forms integrated</li>
                           <li>Push notifications to stay connected with users 24x7</li>
                           <li>Custom QR Code generator</li>
                           <li>Contact us utility</li>
                           <li>Click to call function</li>
                        </ul>
                     </div>
                  </li>
               </ul>
            </div>
         </li>
         <li class="header_list">
            <div class="listBorder">
               <div class="mb-cell">
                  <div class="priceClass">
                     <span class="price">$99</span><span class="pm">PM</span><br>
                     <span class="anuallyPrice"><strong> $1,188 </strong> Billed Annually</span>
                  </div>
               </div>
               <div class="mb-cell">
                  <div class="advanced"><span class="subText">Advanced</span><span class="largeText">Shopping App</span></div>
               </div>
               <div class="mb-cell">
                  <a class="closeHeader"><i class="fa fa-angle-right fa-2x"></i></a>
               </div>
            </div>
            <div class="mobi-content">
               <ul class="nested-mobile-price-list">
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Highlights
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content ">
                        <ul class="nested-list-content ">
                          <li>Google indexed apps for Tablets / Phablets / Phones / Ipads / Ipad Mini</li>
                          <li>Native Apps with Core features</li>
                          <li>White label apps, launched under your own account</li>
                          <li>Unlimited Push Notifications</li>
                          <li>Unlimited Downloads / unlimited sales</li>
                          <li>Expert Assistance in creating your app</li>
                          <li>Live  preview's and on device trial.  (Wizard)</li>
                          <li>CMS panel for unlimited  app updates</li>
                          <li>Auto updates for iOS and Android OS</li>
                          <li>User sign up (email, FB, G+) Included</li>
                          <li>Billing / Shopping Cart / Favorites / My orders integrated</li>
                          <li>Utilities,  marketing tools, social media API's included</li>
                          <li>Secure cloud hosting and back up</li>
                          <li>Complete design control , unlimited customization</li>
                          <li>App marketing and ASO consultation</li>
                          <li>Smart App analytics + live stats reporting</li>
                          <li>Fully independent Inventory management system included</li>
                          <li>Free Payment Gateway</li>
                          <li>Seamlessly integrate with your ecommerce website (optional)</li>
                          <li>No Transaction fees on sales</li>
                          <li>5 GB data and 8000 products</li>
                          <li>Runs Relevant Ads</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Design
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Slick adaptive native UI design</li>
                           <li>Unlimited no. of product categories with different attributes and discount options</li>
                           <li>Comprehensive list of categories and subcategories to choose from</li>
                           <li>Intuitive design</li>
                           <li>Custom navigation bar</li>
                           <li>Customised rich media icon</li>
                           <li>Customised rich media splash screen</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">API's and Utilities
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>3rd party API’s</li>
                           <li>Rich media social API’s</li>
                           <li>Social API’s</li>
                           <li>Rich brilliant photo gallery</li>
                           <li>HD video gallery</li>
                           <li>Google Maps</li>
                           <li>Calendar + event calendar</li>
                           <li>Add and organise events</li>
                           <li>Social network integration</li>
                           <li>Customised widgets</li>
                           <li>Social sharing</li>
                           <li>Dynamic inventory management</li>
                           <li>Real-time reporting</li>
                           <li>Ready for secure payment gateway integration and COD</li>
                           <li>In-app search</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Devices and OS
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Native to Android Fully suported Android 4.0 and above Android 4.0</li>
                           <li>Jelly Bean: Android 4.1, 4.2, 4.3 </li>
                           <li>KitKat: Android 4.4 </li>
                           <li>Lollipop: Android 5.0 and 5.1</li>
                           <li>Native to iOS runs on iOS 7.0, and above (iOS 7.0, 8.0 in iphones, ipads, ipad mini)</li>
                           <li>Works on all tablets, phones, phablets, that support iOS and Android</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Data, Security and Analytics
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Secure hosting</li>
                           <li>Data back-up</li>
                           <li>Smart analytics – live stats reporting</li>
                           <li>Traffic stats</li>
                           <li>Social stats</li>
                           <li>Technical stats</li>
                           <li>Third party stats</li>
                           <li>Fully supported data integration</li>
                           <li>Low data-usage and offline availability</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Support and Testing
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Save your app designs (unlimited for 30 days)</li>
                           <li>App testing on multiple device formats (simulator)</li>
                           <li>App store optimisation (paid)</li>
                           <li>Test your app on your own device</li>
                           <li>Tech support</li>
                           <li>Marketing support</li>
                           <li>Publishing support</li>
                           <li>Indexing and Optimisation</li>
                           <li>Google search indexed</li>
                           <li>Content publishing support (paid)</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder"><span class="sublist-heading">Payment Gateway (m-comerce enabled retail apps)</span>
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Free integration of payment gateway (CC Avenue)</li>
                           <li>No one-time set-up charge (CC Avenue)</li>
                           <li>No maintenance charge</li>
                           <li>Instappy charges NO transaction fee on sales</li>
                           <li>Order confirmation </li>
                           <li>Order Confrmation email pack (paid)</li>
                           <li>SMS and OTP (paid)</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder ">Customisation
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Unlimited users</li>
                           <li>Unlimited updates</li>
                           <li>Built-in CMS and inventory management (For unlimited updates)</li>
                           <li>Unlimited content</li>
                           <li>Unlimited rich images</li>
                           <li>Custom splash screen</li>
                           <li>Custom app Icon</li>
                           <li>Customisable UI</li>
                           <li>Custom color scheme and fonts</li>
                           <li>Custom app name</li>
                           <li>Custom app description</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Consumer engagement
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Feedback forms integrated</li>
                           <li>Push notifications to stay connected with users 24x7</li>
                           <li>Custom QR Code generator</li>
                           <li>Contact us utility</li>
                           <li>Click to call function</li>
                        </ul>
                     </div>
                  </li>
               </ul>
            </div>
         </li>
         <li class="header_list">
            <div class="listBorder" style="background:#fe5d00">
               <div class="mb-cell">
                  <div class="priceClass textWhite">
                     <span class="price">$150</span><span class="pm">PM</span><br>
                     <span class="anuallyPrice"><strong> $1,812 </strong> Billed Annually</span>
                  </div>
               </div>
               <div class="mb-cell">
                  <div class="advanced">
                     <span class="subText">Platinum<sub class="ms-pop">Most Popular</sub></span>
                     <span class="largeText">Pro Shopping</span>
                  </div>
               </div>
               <div class="mb-cell">
                  <a class="closeHeader"><i class="fa fa-angle-right fa-2x"></i></a>
               </div>
            </div>
             <div class="mobi-content">
               <ul class="nested-mobile-price-list">
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Highlights
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content ">
                        <ul class="nested-list-content ">
                          <li>Google indexed apps for Tablets / Phablets / Phones / Ipads / Ipad Mini</li>
                          <li>Native Apps with Core features</li>
                          <li>White label apps, launched under your own account</li>
                          <li>Unlimited Push Notifications</li>
                          <li>Unlimited Downloads / unlimited sales</li>
                          <li>Expert Assistance in creating your app</li>
                          <li>Live  preview's and on device trial.  (Wizard)</li>
                          <li>CMS panel for unlimited  app updates</li>
                          <li>Auto updates for iOS and Android OS</li>
                          <li>User sign up (email, FB, G+) Included</li>
                          <li>Billing / Shopping Cart / Favorites / My orders integrated</li>
                          <li>Utilities,  marketing tools, social media API's included</li>
                          <li>Secure cloud hosting and back up</li>
                          <li>Complete design control , unlimited customization</li>
                          <li>App marketing and ASO consultation</li>
                          <li>Smart App analytics + live stats reporting</li>
                          <li>Fully independent Inventory management system included</li>
                          <li>Free Payment Gateway</li>
                          <li>Seamlessly integrate with your ecommerce website (optional)</li>
                          <li>No Transaction fees on sales</li>
                          <li>8 GB data and 12000 products</li>
                          <li>Go Ad free or earn with your own ads</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Design
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Slick adaptive native UI design</li>
                           <li>Unlimited no. of product categories with different attributes and discount options</li>
                           <li>Comprehensive list of categories and subcategories to choose from</li>
                           <li>Intuitive design</li>
                           <li>Custom navigation bar</li>
                           <li>Customised rich media icon</li>
                           <li>Customised rich media splash screen</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">API's and Utilities
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>3rd party API’s</li>
                           <li>Rich media social API’s</li>
                           <li>Social API’s</li>
                           <li>Rich brilliant photo gallery</li>
                           <li>HD video gallery</li>
                           <li>Google Maps</li>
                           <li>Calendar + event calendar</li>
                           <li>Add and organise events</li>
                           <li>Social network integration</li>
                           <li>Customised widgets</li>
                           <li>Social sharing</li>
                           <li>Dynamic inventory management</li>
                           <li>Real-time reporting</li>
                           <li>Ready for secure payment gateway integration and COD</li>
                           <li>In-app search</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Devices and OS
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Native to Android Fully suported Android 4.0 and above Android 4.0</li>
                           <li>Jelly Bean: Android 4.1, 4.2, 4.3 </li>
                           <li>KitKat: Android 4.4 </li>
                           <li>Lollipop: Android 5.0 and 5.1</li>
                           <li>Native to iOS runs on iOS 7.0, and above (iOS 7.0, 8.0 in iphones, ipads, ipad mini)</li>
                           <li>Works on all tablets, phones, phablets, that support iOS and Android</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Data, Security and Analytics
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Secure hosting</li>
                           <li>Data back-up</li>
                           <li>Smart analytics – live stats reporting</li>
                           <li>Traffic stats</li>
                           <li>Social stats</li>
                           <li>Technical stats</li>
                           <li>Third party stats</li>
                           <li>Fully supported data integration</li>
                           <li>Low data-usage and offline availability</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Support and Testing
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Save your app designs (unlimited for 30 days)</li>
                           <li>App testing on multiple device formats (simulator)</li>
                           <li>App store optimisation (paid)</li>
                           <li>Test your app on your own device</li>
                           <li>Tech support</li>
                           <li>Marketing support</li>
                           <li>Publishing support</li>
                           <li>Indexing and Optimisation</li>
                           <li>Google search indexed</li>
                           <li>Content publishing support (paid)</li>
                        </ul>
                     </div>
                  </li>
                   <li class="nested_header_list">
                     <div class="nested_listBorder"><span class="sublist-heading">Payment Gateway (m-comerce enabled retail apps)</span>
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Free integration of payment gateway (CC Avenue)</li>
                           <li>No one-time set-up charge (CC Avenue)</li>
                           <li>No maintenance charge</li>
                           <li>Instappy charges NO transaction fee on sales</li>
                           <li>Order confirmation </li>
                           <li>Order Confrmation email pack (paid)</li>
                           <li>SMS and OTP (paid)</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder ">Customisation
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Unlimited users</li>
                           <li>Unlimited updates</li>
                           <li>Built-in CMS and inventory management (For unlimited updates)</li>
                           <li>Unlimited content</li>
                           <li>Unlimited rich images</li>
                           <li>Custom splash screen</li>
                           <li>Custom app Icon</li>
                           <li>Customisable UI</li>
                           <li>Custom color scheme and fonts</li>
                           <li>Custom app name</li>
                           <li>Custom app description</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Consumer engagement
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Feedback forms integrated</li>
                           <li>Push notifications to stay connected with users 24x7</li>
                           <li>Custom QR Code generator</li>
                           <li>Contact us utility</li>
                           <li>Click to call function</li>
                        </ul>
                     </div>
                  </li>
               </ul>
            </div>
         </li>
         <li class="header_list">
            <div class="listBorder listBorderColor">
               <div class="mb-cell apro">
                  <div class="advanced">
                     <span class="largeText hire-pro">Hire A Pro</span>
                  </div>
                  <div class="need-more">
                     <h4>Need more customization?</h4>
                  </div>
               </div>
               <div class="mb-cell apro-arrow">
                  <a class="closeHeader"><i class="fa fa-angle-right fa-2x"></i></a>
               </div>
            </div>
             <div class="mobi-content">
               <ul class="nested-mobile-price-list">
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Highlights
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content ">
                        <div class="hire-pro-content">
                  <p>Apps | Games | Software | Web | Enterprise Solutions</p>
                  <p>Our team of experienced developers are driven by a simple goal, to make high quality, engaging software and technology products for our clients.</p>
                  <p>Tablets / Phablets / Phones / Ipads / Ipad Mini / windows phones / 2in1's</p>
                  <p><img class="cup" src="images/pre-cup.png"><span>Winners of The Maddy's 2015</span></p>
                  <p><img class="cup" src="images/pre-cup.png"><span>Featured in top 5 Apps of jan 2016</span></p>
                  <p><img class="cup" src="images/pre-cup.png"><span>ECHO Awards winner</span></p>
               </div>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Design
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Slick adaptive native UI design</li>
                           <li>Unlimited screens / Unlimited no. of product categories with different attributes and discount options</li>
                           <li>Comprehensive list of categories and subcategories to choose from</li>
                           <li>Intuitive design</li>
                           <li>Custom navigation bar</li>
                           <li>Customised rich media icon</li>
                           <li>Customised rich media splash screen</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">API's and Utilities
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>3rd party API’s</li>
                           <li>Rich media social API’s</li>
                           <li>Social API’s</li>
                           <li>Rich brilliant photo gallery</li>
                           <li>HD video gallery</li>
                           <li>Google Maps</li>
                           <li>Calendar + event calendar</li>
                           <li>Add and organise events</li>
                           <li>Social network integration</li>
                           <li>Customised widgets</li>
                           <li>Social sharing</li>
                           <li>Dynamic inventory management</li>
                           <li>Real-time reporting</li>
                           <li>Ready for secure payment gateway integration and COD</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Devices and OS
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Native to Android Fully suported Android 4.0 and above Android 4.0</li>
                           <li>Jelly Bean: Android 4.1, 4.2, 4.3 </li>
                           <li>KitKat: Android 4.4 </li>
                           <li>Lollipop: Android 5.0 and 5.1</li>
                           <li>Native to iOS runs on iOS 7.0, and above (iOS 7.0, 8.0 in iphones, ipads, ipad mini)</li>
                           <li>Works on all tablets, phones, phablets, that support iOS and Android</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Data, Security and Analytics
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Secure hosting</li>
                           <li>Data back-up</li>
                           <li>Smart analytics – live stats reporting</li>
                           <li>Traffic stats</li>
                           <li>Social stats</li>
                           <li>Technical stats</li>
                           <li>Third party stats</li>
                           <li>Fully supported data integration</li>
                           <li>Low data-usage and offline availability</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Support and Testing
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Save your app designs (unlimited for 30 days)</li>
                           <li>App testing on multiple device formats (simulator)</li>
                           <li>App store optimisation (paid)</li>
                           <li>Test your app on your own device</li>
                           <li>Tech support</li>
                           <li>Marketing support</li>
                           <li>Publishing support</li>
                           <li>Indexing and Optimisation</li>
                           <li>Google search indexed</li>
                           <li>Content publishing support (paid)</li>
                        </ul>
                     </div>
                  </li>
                   <li class="nested_header_list">
                     <div class="nested_listBorder"><span class="sublist-heading">Payment Gateway (m-comerce enabled retail apps)</span>
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Free integration of payment gateway (CC Avenue)</li>
                           <li>No one-time set-up charge (CC Avenue)</li>
                           <li>No maintenance charge</li>
                           <li>Instappy charges NO transaction fee on sales</li>
                           <li>Order confirmation </li>
                           <li>Order Confrmation email pack (paid)</li>
                           <li>SMS and OTP (paid)</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder ">Customisation
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Unlimited users</li>
                           <li>Unlimited updates</li>
                           <li>Built-in CMS and inventory management (For unlimited updates)</li>
                           <li>Unlimited content</li>
                           <li>Unlimited rich images</li>
                           <li>Custom splash screen</li>
                           <li>Custom app Icon</li>
                           <li>Customisable UI</li>
                           <li>Custom color scheme and fonts</li>
                           <li>Custom app name</li>
                           <li>Custom app description</li>
                        </ul>
                     </div>
                  </li>
                  <li class="nested_header_list">
                     <div class="nested_listBorder">Consumer engagement
                         <span class="sub-arrow">
                            <i class="fa fa-angle-right fa-2x"></i>
                         </span>
                     </div>
                     <div class="nested-mobi-content">
                        <ul class="nested-list-content">
                           <li>Feedback forms integrated</li>
                           <li>Push notifications to stay connected with users 24x7</li>
                           <li>Custom QR Code generator</li>
                           <li>Contact us utility</li>
                           <li>Click to call function</li>
                        </ul>
                     </div>
                  </li>
               </ul>
            </div>
         </li>
      </ul>
   </div>
   <!-- end pricing mobile block -->      
   <div class="tg-tax">
      <span>*All apps carry "Powered by Instappy" as copyright information</span>
      <span>*14.5% tax extra as applicable</span>
      <span>*Prices are per platform.</span>
      <span>*Free Trial is available only for 30 days.</span> 
      <div class="tag-img">
         <img src="images/tag.png" alt="">
      </div>
      <div class="tag-imgs">
        <ul class="tag-imgs-list">
          <li><img src="images/updates.jpg" alt=""></li>
          <li><img src="images/usage.jpg" alt=""></li>
          <li><img src="images/additional.jpg" alt=""></li>
        </ul>
      </div>
   </div>
</section>
<!-- end pricing wrap -->
<div class="clearfix"></div>
<!-- start footer -->
<?php require_once('website_footer_new.php');?>
<!-- end footer -->
<script type="text/javascript" src="js/main_pricing.js"></script>