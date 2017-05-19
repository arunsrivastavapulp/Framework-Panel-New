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
     // if (window.outerWidth < 1280) {
     if (screen.width < 1180) {
       document.querySelectorAll('html')[0].style.display = 'none';
       window.location.href = 'pricing-mobile.php';
     }
   }
   fnCheckResolution();
   window.onfocus = fnCheckResolution;
   window.onresize = fnCheckResolution;
</script>
<link rel="stylesheet" href="css/pricing.css" />
<!-- start pricing wrap -->
<section id="pricing-wrap">
   <div class="clearfix"></div>
   <!-- start pricing banner block -->
   <article class="pricing-banner-block">
      <div class="box-row theme-width">
         <div class="heading-area">
            <h1>Choose Your Package</h1>
         </div>
         <div class="detail-area">
            <p>Apps built using Instappy.com are packed with features that are built for your success. Instappy apps are instant, affordable, stunning, intuitive and will change the way you interact with your customers. Our business model allows us to provide everyone with world-class feature-packed applications on Android and iOS for a fraction of the cost it takes to develop an app otherwise.</p>
            <span>All our plans are white label, with advanced data management, secure cloud hosting and unlimited downloads.</span>
         </div>
      </div>
   </article>
   <!-- end pricing banner block -->
   <!-- start Highlights block -->
   <article class="highlights-block">
      <div class="box-row theme-width">
         <ul class="pricing-table">
            <li>
               <div class="pr-block pr-block-space"></div>
               <span class="spc"></span>
               <div class="package_card pr-block">
                  <div class="package_card_head">
                     <h2>Advanced</h2>
                  </div>
                  <div class="package_card_body">
                     <h3>$29<span>PM</span></h3>
                     <i>$348</i>
                     <p>Billed Annually</p>
                     <div class="plan">
                        <a href="themes.php" class="basic">Create App</a>                         
                     </div>
                  </div>
               </div>
               <span class="spc"></span>
               <div class="package_card pr-block">
                  <div class="package_card_head">
                     <h2>Platinum Pro</h2>
                  </div>
                  <div class="package_card_body">
                     <h3>$48<span>PM</span></h3>
                     <i>$576</i>
                     <p>Billed Annually</p>
                     <div class="plan">
                        <a href="themes.php" class="basic">Create App</a>                         
                     </div>
                  </div>
               </div>
               <span class="spc"></span>
               <div class="package_card pr-block">
                  <div class="package_card_head">
                     <span>Most Popular</span>
                     <h2 style="background:#fe5d00">Advanced Catalogue</h2>
                  </div>
                  <div class="package_card_body">
                     <h3>$57<span>PM</span></h3>
                     <i>$684</i>
                     <p>Billed Annually</p>
                     <div class="plan">
                        <a href="themes.php" class="basic" style="background:#fe5d00">Create App</a>                            
                     </div>
                  </div>
               </div>
               <span class="spc"></span>
               <div class="package_card pr-block">
                  <div class="package_card_head">
                     <h2>Platinum Pro Catalogue</h2>
                  </div>
                  <div class="package_card_body">
                     <h3>$77<span>PM</span></h3>
                     <i>$924</i>
                     <p>Billed Annually</p>
                     <div class="plan">
                        <a href="themes.php" class="basic">Create App</a>                         
                     </div>
                  </div>
               </div>
               <span class="spc"></span>
               <div class="package_card pr-block">
                  <div class="package_card_head">
                     <h2>Advanced Shopping App</h2>
                  </div>
                  <div class="package_card_body">
                     <h3>$99<span>PM</span></h3>
                     <i>$1,188</i>
                     <p>Billed Annually</p>
                     <div class="plan">
                        <a href="themes.php" class="basic">Create App</a>                         
                     </div>
                  </div>
               </div>
               <span class="spc"></span>
               <div class="package_card pr-block">
                  <div class="package_card_head">
                     <span>Most Popular</span>                                                        
                     <h2 style="background:#fe5d00">Platinum Pro Shopping </h2>
                  </div>
                  <div class="package_card_body">
                     <h3>$150<span>PM</span></h3>
                     <i>$1,812</i>
                     <p>Billed Annually</p>
                     <div class="plan">
                        <a href="themes.php" class="basic" style="background:#fe5d00">Create App</a>                         
                     </div>
                  </div>
               </div>
               <span class="spc"></span>
               <div class="package_card pr-block">
                  <div class="package_card_head">
                     <h2 style="background:#9d5ea8">Hire A Pro</h2>
                  </div>
                  <div class="package_card_body">
                     <h3 class="need-more">Need more <span>customisation?</span></h3>
                     <div class="plan">
                        <a href="contact-us.php" class="basic" style="background:#9d5ea8">Contact Us</a>                         
                     </div>
                  </div>
               </div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Google indexed apps for Tablets / Phablets / Phones / Ipads / Ipad Mini</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block pack-pro-detail">
                  <p>Apps | Games | Software | Web | Enterprise Solutions <br><br> Our team of experienced developers are driven by a simple goal, to make high quality, engaging software and technology products for our clients. <br><br> Tablets / Phablets / Phones / Ipads / Ipad Mini / windows phones / 2in1's <br><br> </p>
                  <div class="price-cup">
                     <img src="images/pre-cup.png" alt="Online Shopping Apps" title="Online Shopping Apps"/>
                     <p>Winners of The Maddy's 2015</p>
                  </div>
                  <div class="price-cup">
                     <img src="images/pre-cup.png" alt="Online Shopping Apps" title="Online Shopping Apps"/>
                     <p>Featured in top 5 Apps of jan 2016</p>
                  </div>
                  <div class="price-cup">
                     <img src="images/pre-cup.png" alt="Online Shopping Apps" title="Online Shopping Apps" />
                     <p>ECHO Awards winner</p>
                  </div>
               </div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Native Apps with Core features</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">White label apps, launched under your own account</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Unlimited Push Notifications</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Unlimited Downloads</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Unlimited Sales</div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Expert Assistance in creating your app</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Live  preview's and on device trial.  (Wizard)</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">CMS panel for unlimited app updates</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Auto updates for iOS and Android OS</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">User sign up (email, FB, G+) Included</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Billing / Shopping Cart / Favorites / My orders integrated</div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Utilities,  marketing tools, social media API's included</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Secure cloud hosting and back up</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Complete design control , unlimited customization</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">App marketing and ASO consultation</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Smart App analytics + live stats reporting</div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Fully independent Inventory management system included</div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Free Payment Gateway</div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Seamlessly integrate with your ecommerce website (optional)</div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">No Transaction fees on sales</div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i>---</i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
               <span class="spc"></span>
               <div class="pr-block"><i class="fa fa-check fa-lg"></i></div>
            </li>
            <li>
               <div class="pr-block pr-block-space">Data And Products</div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>Upto 2GB data</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>Upto 3GB data</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>3GB data and 5000 products</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>5GB data and 8000 products</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>5GB data and 8000 products</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>8GB data and 12000 products</p>
               </div>
            </li>
            <li>
               <div class="pr-block pr-block-space no-bor"></div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>Runs Relevant Ads</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>Go Ad free or earn with your own ads</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>Runs Relevant Ads</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>Go Ad free or earn with your own ads</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>Runs Relevant Ads</p>
               </div>
               <span class="spc"></span>
               <div class="pr-block">
                  <p>Go Ad free or earn with your own ads</p>
               </div>
            </li>
         </ul>
      </div>
   </article>
   <!-- end Highlights block -->
   <!-- start pricing block -->
   <article class="pricing-block">
      <div class="box-row theme-width">
         <div class="price-hd">
            <span>Design <i class="fa fa-angle-right"></i></span>
         </div>
         <div class="row-inner not-show">
            <ul class="extra-table">
               <li>
                  <div class="ex-block ex-block-space">Slick adaptive native UI design</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Unlimited screens / Unlimited no. of product categories with different attributes and discount options</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Comprehensive list of categories and subcategories to choose from</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Intuitive Design</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Custom Navigation Bar</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Customised rich media icon</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Customised rich media splash screen</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
              
            </ul>
         </div>
         <div class="price-hd">
            <span>API's and Utilities <i class="fa fa-angle-right"></i></span>
         </div>
         <div class="row-inner">
            <ul class="extra-table">
               <li>
                  <div class="ex-block ex-block-space">3rd party API’s</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Rich media social API’s</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Social API’s</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Rich brilliant photo gallery</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">HQ video gallery</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Google Maps</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Calendar + event calendar</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Add and organise events</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Social network integration</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Customised widgets</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Social sharing</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Dynamic inventory management</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Real-time reporting</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Ready for secure payment gateway integration and COD</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">In-app search</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
               </li>
            </ul>
         </div>
         <div class="price-hd">
            <span>Devices and OS <i class="fa fa-angle-right"></i> </span>                    
         </div>
         <div class="row-inner">
            <ul class="extra-table">
               <li>
                  <div class="ex-block ex-block-space">Native to Android Fully suported Android 4.0 and above Android 4.0 <br> Jelly Bean: Android 4.1, 4.2, 4.3 <br> KitKat: Android 4.4 <br> Lollipop: Android 5.0 and 5.1</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Native to iOS runs on iOS 7.0, and above (iOS 7.0, 8.0 in iphones, ipads, ipad mini)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Works on all tablets, phones, phablets, that support iOS and Android</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
            </ul>
         </div>
         <div class="price-hd">
            <span>Data, Security and Analytics <i class="fa fa-angle-right"></i></span>
         </div>
         <div class="row-inner">
            <ul class="extra-table">
               <li>
                  <div class="ex-block ex-block-space">Secure hosting</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Data back-up</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Smart analytics – live stats reporting</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Traffic stats</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Social stats</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Technical stats</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Third party stats</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Fully supported data integration</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Low data-usage and offline availability</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
            </ul>
         </div>
         <div class="price-hd">
            <span>Support and Testing <i class="fa fa-angle-right"></i></span>
         </div>
         <div class="row-inner">
            <ul class="extra-table">
               <li>
                  <div class="ex-block ex-block-space">Unlimited designs</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i>NA</i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Save your app designs (unlimited for 30 days)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">App testing on multiple device formats (simulator)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">App store optimisation (paid)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Test your app on your own device</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Tech support (paid)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Marketing support (paid)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Publishing support (paid)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Indexing and Optimisation (paid)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Google search indexed</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Content publishing support (paid)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
            </ul>
         </div>
         <div class="price-hd">
            <span>Payment Gateway (M-Commerce enabled retail apps) <i class="fa fa-angle-right"></i></span>                    
         </div>
         <div class="row-inner">
            <ul class="extra-table">
               <li>
                  <div class="ex-block ex-block-space">Free integration of payment gateway (CC Avenue)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">No one-time set-up charge (CC Avenue)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">No maintenance charge</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Instappy charges NO transaction fee on sales</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
                <li>
                  <div class="ex-block ex-block-space">Order confirmation </div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
                <li>
                  <div class="ex-block ex-block-space">Order Confrmation email pack (paid)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
                <li>
                  <div class="ex-block ex-block-space">SMS and OTP (paid)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i >NA</i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
            </ul>
         </div>
         <div class="price-hd">
            <span>Customisation <i class="fa fa-angle-right"></i></span>                    
         </div>
         <div class="row-inner">
            <ul class="extra-table">
               <li>
                  <div class="ex-block ex-block-space">Unlimited users</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Unlimited updates</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Built-in CMS and inventory management (For unlimited updates)</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Unlimited content</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Unlimited rich images</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Custom splash screen</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Custom app Icon</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Customisable UI</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Custom color scheme and fonts</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Custom app name</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Custom app description</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
            </ul>
         </div>
         <div class="price-hd">
            <span>Consumer engagement <i class="fa fa-angle-right"></i></span>
         </div>
         <div class="row-inner">
            <ul class="extra-table">
               <li>
                  <div class="ex-block ex-block-space">Feedback forms integrated</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Push notifications to stay connected with users 24x7</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Custom QR Code generator</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Contact us utility</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
               <li>
                  <div class="ex-block ex-block-space">Click to call function</div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
                  <span class="spc"></span>
                  <div class="ex-block ex-plus"><i class="fa fa-check fa-lg"></i></div>
               </li>
            </ul>
         </div>
         <div class="tg-tax">
            <span>*Taxes as applicable as per country.</span>
            <span>*Prices are per platform.</span>
            <span>*Free Trial is available only for 30 days.</span>
            <div class="tag-img">
               <img src="images/tag.png" alt="" />
            </div>
         </div>
      </div>
   </article>
   <!-- end pricing block --> 
</section>
<!-- end pricing wrap -->
<div class="clearfix"></div>
<!-- start footer -->
<?php require_once('website_footer_new.php');?>
<!-- end footer -->
<script type="text/javascript" src="js/main_pricing.js"></script>