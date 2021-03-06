// main-content-apps.js

var player;

requirejs(['jquery', 'bxslider', 'flipster'], function ($) {

  var slider,
      newpropapps = '';
  $(document).ready(function () {
    //video popup
    $('.pro-view-demo').on('click', function(e){
      e.preventDefault();
      $(".overlay > div").hide();
      $('.overlay').fadeIn();
      $('.video-popup').fadeIn();
    });
    
    $(".popupClose").on('click',function(e){
      e.preventDefault();
    $(".video-popup").hide();
    $(".overlay").hide();
     });

    $('.bxslider').bxSlider();
  
    // tabbing start
    $('.box-row .box-row-inner').eq(0).show();
    $(document).on('click', '.bx-wrapper .bx-viewport .app-category-list li', function () {
      var currentIndex = $(this).index();
      $('.bx-wrapper .bx-viewport .app-category-list li').removeClass('active');
      $(this).addClass('active');
      $('.box-row .box-row-inner').hide();
      var currentFeature = currentAppsType[currentIndex];
    $('.app-category-list-view h3').html(currentFeature.name);
    $('.app-category-list-view p').html(currentFeature.description);
    $('.app-category-list-view .icon-side span').attr('class', currentFeature['image-class']);
    $('.box-row .box-row-inner').fadeIn();
    });
    
    var appsFeaturesHtml = '';
    currentAppsType = proAppFeatures;
    $.each(currentAppsType, function (index, obj) {
      appsFeaturesHtml += '<li><span class="cat-list-icon"><span class="' + obj['image-class'] + '"></span></span><span class="cat-list-name">' + obj.name + '</span></li>';
    });
    $('.slider4').html(appsFeaturesHtml);
      // slider tabing icon
    slider = $('.slider4').bxSlider({
      slideWidth: 110,
      minSlides: 8,
      maxSlides: 8,
      moveSlides: 1,
     // slideMargin: 1,
      infiniteLoop: false,
      hideControlOnEnd: true,
      pager: false
      /*nextSelector: '<img src="images/controls.png" />',
      prevSelector: '<img src="images/controls.png" />'*/
    });
    $('.slider4 li').first().trigger('click');
    
    $(document).on('click', '.pro-app-menus > ul > li > a', function (e) {
      e.preventDefault();
      var self = this;
      $(self).toggleClass('active').next('div').slideToggle('fast');
      $('.pro-app-menus > ul > li > a').each(function () {
        if ($(self).index('.pro-app-menus > ul > li > a') !== $(this).index('.pro-app-menus > ul > li > a')) {
          $(this).removeClass('active').next('div').slideUp('fast');
        }
      });
    });
    
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    
  }); // end document ready

  
  $(window).on('resize', function () {
    activateFeaturesSliders();
  });
  
  activateFeaturesSliders();
  
}); // end require

function onYouTubeIframeAPIReady() {
  player = new YT.Player('video-popup', {
    height: '100%',
    width: '100%',
    videoId: 'EmE2E_GkWio',
    events: {
      'onReady': onPlayerReady,
      'onStateChange': onPlayerStateChange
    }
  });
}

function onPlayerReady(event) {
  $(".popupClose").on('click', function(e){
    event.target.pauseVideo();
  });
}

var done = false;

function onPlayerStateChange(event) {
  if (event.data == YT.PlayerState.PLAYING && !done) {
    // setTimeout(stopVideo, 6000);
    done = true;
  }
}

// function stopVideo() {
//   player.stopVideo();
// }

  
function activateFeaturesSliders () {

  var proFeaturesHtml = '',
      shopFeaturesHtml = '';

  $.each(proAppFeatures, function (index, obj) {
    proFeaturesHtml += '<li>\
      <a href=""><span><span class="' + obj['image-class'] + '"></span>' + obj.name + '</span></a>\
        <div class="pro-app-menus-inside-area">\
          <div class="icon-side-area"><span class="' + obj['image-class'] + '"></span></div>\
          <div class="content-side-area"><h3>' + obj.name + '</h3>\
          <p>' + obj.description + '</p></div>\
        </div>\
        <div class="clear"></div>\
    </li>';
  });

  if ($(window).width() > 979) {
    $('.app_selected').eq(0).trigger('click');
  }
  else {
    $('.pro-app-menus.pro-apps > ul').html(proFeaturesHtml);
    $('.pro-app-menus > ul > li:first > a').click();
  }
}
  
// ajax tabbing start
var proAppFeatures = [{
    name: 'Unlimited Customisation',
    description: 'Make your app uniquely yours with unlimited customisation options. Personalise icons, background colours, fonts, layouts, screens and other distinctive features available in the UI.',
    'image-class': 'icon-unlimitedcustomisation'
},

  {
    name: 'Push Notifications',
    description: 'Schedule customised push notifications across any time zone for any part of the world. Run promos, showcase deals, highlight new launches and get upto 3x more engagement. ',
    'image-class': 'icon-push-notification'
},

  {
    name: 'Data Integration',
    description: 'Cloud hosted and secure data integration which allows you to upload data securely for unlimited screens. Add text, images, music, videos, infographics and more directly to your app.',
    'image-class': 'icon-data-integration'
},

  {
    name: 'Unlimited App Updates',
    description: 'Our built-in cms panel allows you to instantly update your app on-the-fly with text, rich media images and high definition videos.',
    'image-class': 'icon-unlimited-app-updates'
},

  {
    name: 'Signup Forms',
    description: 'Let your users signup to your app with inbuilt social signup options using Facebook or Email. Integrate signup using email to include customer data and get the unique option to allow gated content for your customers.',
    'image-class': 'icon-signup-forms'
},

  {
    name: 'Marketing Tools',
    description: 'Take the guesswork out of your marketing plans. Our powerful marketing tools put your app at the forefront and help you get higher engagement, revenue, and retention.',
    'image-class': 'icon-marketing-tools'
},

  {
    name: 'Customised Forms',
    description: 'Whether you are looking to gather feedback, take advance orders or get more signups, our customised forms have got you covered.',
    'image-class': 'icon-customised-forms'
},


  {
    name: 'Tools & Utilities',
    description: 'Every business has unique mobile needs. With a host of tools and utility cards, we make sure your app functions exactly the way you want it.',
    'image-class': 'icon-tools-utilities'
},

  {
    name: 'Feedback Form',
    description: 'Our integrated feedback form enables you to get actionable insights into what your customers want, fostering long lasting relationships and loyalty.',
    'image-class': 'icon-feedback-form'
},

  {
    name: 'Social APIs',
    description: 'In today’s hyper-connected age, it’s crucial to feed your app with live social media updates. With 50+ social and customised APIs, we keep your social network integrated into your app in real time.',
    'image-class': 'icon-social-apps'
},

  {
    name: 'HQ Photo Gallery',
    description: 'Redefine how you show your work to your customers. Give your app a unique look and feel with your own customised in-app photo gallery.',
    'image-class': 'icon-hq-photo-gallery'
},

  {
    name: 'HQ Video Gallery',
    description: 'Looking to get an edge over others? Create a customised in-app video gallery. Integrate HD videos and let your customers play it without ever having to leave your app.',
    'image-class': 'icon-hq-video-gallery'
},


  {
    name: 'Google Maps',
    description: 'Make it easier for your customers to reach out to you. Get directions, list your stores, or simply add your business listing. Bring the best of google maps to your app.',
    'image-class': 'icon-google-maps'
},

  {
    name: 'Events Calendar',
    description: 'Keep your users in the loop of upcoming events. Set up events, set reminders, or put up a weekly schedule for specials. We bring you gorgeous, interactive events calendars to help you get more of your app.',
    'image-class': 'icon-events-calendar'
},

  {
    name: 'Social Network Integration',
    description: 'Instappy apps come loaded with robust, built-in social network integration. Add rich media live social APIs directly into your app and expand your social presence like never before. Try it!',
    'image-class': 'icon-social-network-integration'
},

  {
    name: 'Customised Widgets',
    description: 'Relentless as ever, we keep expanding our widget library. Take your pick from our stunning range of custom widgets, matching your unique style.',
    'image-class': 'icon-customized-widgets'
},

  {
    name: 'Social Sharing',
    description: 'Instappy apps come loaded with robust social integration enabling you to share directly from your app to Facebook, twitter and other networks. Increase reach for your marketing promotions or for that exciting new launch instantly.',
    'image-class': 'icon-social-sharing'
},

  {
    name: 'In-App Search',
    description: 'Your users are longing for a smarter way to find specific content within their apps. This long-needed feature allows users to make advanced search within the app.',
    'image-class': 'icon-in-app-search'
},

  {
    name: 'Music Integration',
    description: 'Integrate music, webinars, recorded podcasts with your app. Let your users listen to your audio content on-the-go.',
    'image-class': 'icon-music-integration'
},

  {
    name: 'Video Integration',
    description: 'Enliven your app with videos. Integrate high quality videos seamlessly to your app, letting your users stream live and recorded videos on-the-go.',
    'image-class': 'icon-video-integration'
},

  {
    name: 'Download User Data',
    description: 'In today’s data native ecosystem, a data-driven marketing is a must. With our new ‘download user data’ button, everything you need to know about your users is just one click away.',
    'image-class': 'icon-download-user-data'
},

  {
    name: 'Webview Screen',
    description: 'Show pixel-perfect full screen view of your webpage on your app. Seamlessly integrate your website within your app without performing a network request.',
    'image-class': 'icon-webview-screen'
},

  {
    name: 'Free Splash Screens',
    description: 'Keep your users hooked till your app is fully loaded on the device. Ensure a good app launch experience with our free collection of rich media splash screens or upload your own.',
    'image-class': 'icon-free-splash-screens'
},

  {
    name: 'Free Icons',
    description: 'An icon can make or break your app. Our customised range of app icons are designed with a zealous attention to details to get you the eyeballs. Pick from our free collection or upload your own.',
    'image-class': 'icon-icons'
},

  {
    name: 'Contact Us Card',
    description: 'Let your users reach out to you in a single click – add a sleek contact details section to your app.',
    'image-class': 'icon-contact-us-card'
},

  {
    name: 'Click-To-Call',
    description: 'With this unique native feature, you can get your customers call you directly through your app with a single click.',
    'image-class': 'icon-click-to-call'
},

  {
    name: 'Secure Cloud Hosting',
    description: 'Instappy’s secure cloud hosting and backup facility keeps your valuable data safe. Your data is encrypted so no one but you have access to it!',
    'image-class': 'icon-secure-cloud-hosting'
},

  {
    name: 'Analytics Dashboard',
    description: 'Take smarter, data oriented decisions with real time data analytics and live stats reporting on your app. Get to know where are your customers, who are you more popular with, demographics, downloads and more.',
    'image-class': 'icon-analytics-dashboard'
},


  {
    name: 'Google Indexing',
    description: 'Save on search as our google app indexing gets your app up-and-high on google search results. Enabling an enriched deep linking infrastructure in your apps, we help you make the best of app indexing.',
    'image-class': 'icon-google-indexing'
},

  {
    name: 'Native Utility',
    description: 'Your apps are native to iOS and Android platforms. Native apps are faster, give a better user experience and make best of mobile-exclusive features like camera, calling function and more. Instappy apps have better offline support and lower data usage.',
    'image-class': 'icon-native-utility'
},

  {
    name: 'White Label Apps',
    description: 'Our white label solutions help you to create, sell & manage apps under your account. Yes! You can launch your apps for iOS and Android under your own app store and play store accounts while enjoying all of instappy’s benefits.',
    'image-class': 'icon-white-label-apps'
},

  {
    name: 'Unlimited Downloads',
    description: 'No holds barred! Enjoy unlimited user downloads on your app.',
    'image-class': 'icon-unlimited-downloads'
},

  {
    name: '20+ Languages',
    description: 'Language no bar!  Whether you’re a classy french deli or the local bengali magazine, you can instantly launch a mobile app for your local business in 20+ languages. Yes, you could have a bi-lingual app as well.',
    'image-class': 'icon-unlimited-languages'
}
];

function validateEmail(email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test(email );
}

function validateURL(website) {
  var urlregex = new RegExp(
        "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
  return urlregex.test(website);
}

