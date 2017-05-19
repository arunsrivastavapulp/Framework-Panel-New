<!-- start header -->
  <?php require_once('website_header_new.php');?>
  <?php
unset($_SESSION['catid']);
unset($_SESSION['themeid']);
unset($_SESSION['currentpagevisit']);
?>
  <!-- end header --> 
 <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/YouTubePopUp.css">
 <style type="text/css">
     

/*-------------------------------------------4th april------------------------------------*/
a{text-decoration: none!important}

.caret{border:none!important}
.sh-newpage{overflow: hidden;}
.sh-ins-banner{background: url("../sh-img/head-bg.jpg") top center no-repeat;background-size:cover!important;height: 520px}
.sh-create-app{width: 253px!important;margin-top: 38px;}
/*.right-side .device-area .slides { margin-left: 109px!important;}*/
.right-side .device-area .flex-control-nav{display: none}
.right-side .device-area  .flex-direction-nav{position: absolute;top: 217px;}
.sh-popular{float: left;width: 100%;margin-top: 55px;}
.sh-popular h3{float: left;width: 100%;font-size: 36px;color: #000;font-weight: 600;text-transform: uppercase;margin-bottom: 0;
line-height: 36px;text-align: center;}
.sh-popular h4{float: left;width: 100%;font-size:25px;color: #000;font-weight: 400;text-transform: uppercase;margin-top: 0;
line-height: 30px;text-align: center;}
.sh-newpage .create-app-block{background: #FFCC00!important;}
.sh-newpage .bx-wrapper{background: none!important;border:none!important;box-shadow: none!important}
.sh-newpage .create-app-block .create-app-head{display: block;width: 100%;float: left;}
.sh-newpage .app-category-list li .cat-list-icon{color:#fff!important;}
.sh-newpage .app-category-list li .cat-list-icon:before{color:#fff!important;}
.sh-newpage .app-category-list li .cat-list-name{color:#fff!important;}
.sh-newpage .app-category-list li span.cat-list-icon:hover{color:#fff!important;}
.sh-newpage .app-category-list li .cat-list-name:hover{color:#fff!important;}
.sh-newpage [class^="icon-"], [class*=" icon-"]:hover{color: #fff}
.sh-newpage .app-category .pro-app a:hover span, .app-category .pro-app a.active span{color: #fff}
.sh-newpage  [class^="icon-"], [class*=" icon-"]{color: #fff}
.sh-newpage  .pro-app-menus > ul > li > a > span{color: #fff}
.sh-newpage  .pro-app-menus > ul > li > a > span:hover{color: #fff}
.sh-newpage .bx-wrapper .bx-prev{background: url('sh-img/left-arr-sh.png') no-repeat;width: 17px;
height: 30px;background-size: 100% 100%;}
.sh-newpage .bx-wrapper .bx-next{background: url('sh-img/right-arr-sh.png') no-repeat;width: 17px;
height: 30px;background-size: 100% 100%;}
.bx-wrapper .bx-next:hover, .bx-wrapper .bx-next:focus,.bx-wrapper .bx-next:active {background-position:0!important;}
.sh-newpage  .app-category-list-view{background: #fff}

.sh-newpage .app-category-list-view .content-side h3{float:left;width:100%;text-align:left;font-size:31px;color:#000;font-weight: 600}
.sh-newpage .app-category-list-view .content-side p{float:left;width:100%;font-size:17px;color:#000;text-align:left;line-height: 29px;}
.sh-newpage .app-category-list-view .icon-side > span{color:#FFCC00;}

.sh-newpage .app-category-list li{background: url("sh-img/sh-top-arrow.png") no-repeat 40 127px;height: 148px;display: none;}
.sh-newpage .app-category-list li{background: url("sh-img/sh-top-arrow.png") no-repeat 40 127px;height: 148px;display: block}



.sh-type-theme{float: left;width: 100%;position: relative;}
.sh-type-theme img{width: 100%}
.create-app-head .col-lg-2{margin-right: 15px}
.sh-mid-bg-con{background: url("../sh-img/mid-banner-sh.jpg") top center no-repeat;background-size:cover;position: relative;}


/*.......................5 th april...........................*/

.sh-mid-bg-con p{padding: 64px;text-align: right;font-size: 20px;font-weight: 500;float: right;width: 100%;color:#000 }
.sh-righ-brlnt{position: relative;bottom: -175px;}
.sh-righ-brlnt h3{font-size: 50px;font-weight: 700;color: #000;text-transform: uppercase;color:#000;margin: 0; }
.sh-righ-brlnt p{font-size: 33px;padding: 0;text-align: left;font-weight: 300;color:#000;line-height: 28px; }

.sh-top-feature{text-align: center;margin-bottom: 25px;margin-top:51px}
.sh-top-feature h2{font-size:31px;color:#000;width:100%;text-align:center;font-weight:600;padding:0;margin:0;text-transform: uppercase;}
.sh-top-feature span{font-size:24px;color:#000;width:100%;text-align:center;font-weight:300;padding:0;margin:0;line-height:18px}

.sh-prop-wrap{float:left;width:100%;text-align:center}
.sh-prop-icon{float:left;width:100%}
.sh-wrap-icons{width:100px;height:100px;border-radius:50%;background:#6b809c;margin:0 auto;display:table;float:none;position: relative;}
.sh-wrap-icons img{position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);-ms-transform: translate(-50%,-50%);
-webkit-transform: translate(-50%,-50%);width: 58%;float: left;}
.sh-prop-wrap h3{font-size:24px;color:#000;text-transform: uppercase;margin-top: 20px;float: left;width: 100%;font-weight: 400;}
.sh-prop-wrap p{font-size:17px;color:#000;margin-top:9px;line-height:20px;float: none;width: 90%;margin: 0 auto;display: table;font-weight:300 }
.sh-col-4-margin{margin-bottom: 50px;margin-top: 20px;}

.sh-wrap-pencil{float:none;display:table;margin:0 auto}
.sh-wrap-pencil img{float:none;display:table;margin:0 auto}
.sh-pencil-content{float:left;width:80%;padding:10px}
.sh-pencil-content h3{float:left;width:100%;text-align:left;font-size:31px;color:#000;font-weight: 600}
.sh-pencil-content p{float:left;width:100%;font-size:17px;color:#000;text-align:left;line-height: 29px;}
.wrap-sh-pencil-con{float: left;width: 100%;margin: 30px 0}
.mx2-sh{margin: 50px 0;float: left;width: 100%}
.mx2-sh .col-lg-2{margin-left: 20px;}
.sh-newpage .right-side .device-area .flex-direction-nav{width: 610px;}
.flex-nav-prev{position: absolute;left: 0}
.flex-nav-next{position: absolute;right:0;}

/* ....................6th april.....................*/

.sh-over{background: rgba(0,0,0,0.5);
position: absolute;
top: 0;
left: 0;
right: 0;
bottom: 0;
width: 100%;
height: 100%;
z-index: 9999;
}


.sh-over-text{position: relative;
left: 50%;
top: 50%;
transform: translate(-50%,-50%);
-ms-transform: translate(-50%,-50%);
-webkit-transform: translate(-50%,-50%);
width: 110px;
height: 34px;
background: #ffcc00;
padding: 10px;
font-size: 15px;
border-radius: 23px;
line-height: 15px;
text-align: center;
text-transform: uppercase;

}

.sh-over-text a{text-decoration: none;color: #fff}

.sh-over{display: none;transition: 0.5s ease}
.sh-type-theme:hover .sh-over{display: block;transition: 0.3s ease}

.device-area .flexslider .flex-prev{background:url('sh-img/yellow-arrow-left.png') no-repeat ;width: 49px;
height: 45px;text-indent: -999999px}
.device-area .flexslider .flex-next{background:url('sh-img/yellow-arrow-right.png') no-repeat ;width: 49px;
height: 45px;text-indent: -999999px }


.sh-newpage .flexslider ul li { display: none}
.sh-newpage .flexslider ul li:first-child { display : block}
.sh-newpage .right-side .device-area ul.flex-direction-nav li{display: block;}

.sh-app-name{text-align: center;font-size:14px;color:#4c4c4b;width: 100%;float: left;margin-top: 10px }

.sm-slider{float: left;width: 100px;}




.res-left{float:left;width:100%}
.res-text-wrap{float:left;width:100%;padding: 105px 0 30px;color: #3d3d3d;}
.res-text-wrap h1{float:left;width:100%;font-size:38px;font-weight: 700;text-align:center;margin-bottom: 5px;}
.res-text-wrap h3{margin-top: 0;float:left;width:100%;display: block;font-size: 30px;font-weight: normal;line-height: 31px;text-align:center}
.res-pplr-theme{margin-bottom:40px;float: left;width: 100%;text-align: center;}
.res-create-app {height: 46px;border-radius: 30px;font-weight: 400;font-size: 17px;color:#3d3d3d;text-align: center;text-transform: uppercase;transition: all 0.3s ease-in-out;border: 2px solid #3d3d3d;padding: 25px;
line-height: 0px;}

.res-create-app:hover{text-decoration: none;color: #fff;background:#3d3d3d}

.res-left p  {color: #3d3d3d;font-size: 12px;font-weight: 400;float: left;width: 100%;text-align: center;}

.slider-shr-wrap{float:left;width:100%;position:relative}
.tab-sh-slide{float:left;width:300px;position:absolute;background: url("sh-img/tab-layout.png") no-repeat;height: 442px;
background-size: 100%;left:211px;
top: 42px;}
.mob-sh-slide{float:left;width:155px;position:absolute;background: url("sh-img/mob-sh.png") no-repeat;right: 67px;
background-size: auto;
height: 315px;top: 191px;
z-index: 99;}

.mflexslider{position: absolute;
left: 22px;
top: 40px;width:86%!important;margin: 0 auto}
.sflexslider {position: absolute;
left: 6px;
top: 2px;width:93%!important;margin: 0 auto}
.sflexslider .slides{margin-left: 0!important;top:38px;}
.sflexslider .slides li{    right: 0;
margin-right: 0;
top: 29px;
left: 6px;
}
.sflexslider .slides li img{ width: 100%;margin-top: 38px;margin-left: 0}
.mob-sh-slide li img{width: 93%;float: left;margin-top: 9px;height: 238px}
.tab-sh-slide li img{float: left;
width: 100%;
}
.tab-sh-slide .slides{float: left;width: 100%;margin-left:0!important}
.mflexslider{float: none;width: 93%;margin: 0 auto}

.mob-sh-slide .flex-control-nav{display: none}
.tab-sh-slide .flex-control-nav{display: none}

.tab-sh-slide .flex-direction-nav{width: 500px;
position: absolute;
left: 66%;
top: 50%;
transform: translate(-50%,-50%);
-ms-transform: translate(-50%,-50%);
-webkit-transform: translate(-50%,-50%);}

.tab-sh-slide .flex-prev{background:url('sh-img/yellow-arrow-left.png') no-repeat ;width: 49px;
height: 45px;text-indent: -999999px;outline: none}
.tab-sh-slide .flex-next{background:url('sh-img/yellow-arrow-right.png') no-repeat ;width: 49px;
height: 45px;text-indent: -999999px;right: -13px ;outline: none}

.tab-sh-slide .flex-nav-prev{position: absolute;left: 0}
.tab-sh-slide  .flex-nav-next{position: absolute;right:0;display: block;}
.mob-sh-slide  .flex-nav-next{display: block;}



.mflexslider ul li { display: none}
.mflexslider ul li:first-child { display : block}

.sflexslider ul li { display: none}
.sflexslider ul li:first-child { display : block}


.mob-sh-slide .flexslider .flex-prev{text-indent: -999999px}

.mob-sh-slide .flex-prev{text-indent: -999999px;}
.mob-sh-slide .flex-next{text-indent: -999999px;}
.mob-sh-slide li img{margin-top: 38px;margin-left: 6px}
.app-category{display: none}

.mob-shit{display: none;float: left;}
.web-shit{display: block;float: left;}

.sh-vid-wrap{float: left;width: 100%;margin: 20px 0 75px 0}
.vid-sh-wrap-pop{float:none;margin:0 auto;display:table;width:57%;border:4px solid #000;position: relative;}
.vid-sh-wrap-pop img{float:left;width:100%;}
.sh-icon-vid{position:absolute;top:50%;left:50%;transform: translate(-50%, -50%);-ms-transform: translate(-50%, -50%);-webkit-transform: translate(-50%, -50%);z-index: 2}
.sh-icon-vid h3{font-size:32px;font-weight:700;text-align: center;width: 100%;color: #fff;text-transform: uppercase;  margin: 0 0 10px 0}
.sh-icon-vid .wrap-vidimgs{float:none;width: 70px;margin: 0 auto;display: table;}
.sh-icon-vid .wrap-vidimgs img{float: none;margin: 0 auto;display: table;}

.vid-sh-wrap-pop:after {
    position: absolute;
    content: "";
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
    display: block;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 1;
}

.YouTubePopUp-Close{background: url("sh-img/close.png") no-repeat;}
.YouTubePopUp-Close{bottom: 506px!important}



@media only screen and (max-width : 1199px){



/*.......5 april.......*/

.sh-popular h3{text-align: center;}
.sh-popular h4{text-align: center;}  
.sh-popular{margin-top: 0;margin-bottom: 20px}
.mx2-sh .col-md-2{margin-left:31px;}
.sh-righ-brlnt{bottom: -193px;}
.sh-mid-bg-con p{font-size: 18px}
.sh-righ-brlnt p{font-size: 28px}
.sh-righ-brlnt h3{font-size: 45px}
.sh-top-feature{margin-top: 55px}
.sh-newpage .right-side .device-area .flex-direction-nav{width:55%;}




/*.sh-newpage .box-row-inner{display: block!important;}
.sh-newpage .box-row-inner .icon-side{display: block!important;}*/

.right-side .device-area .slides{margin-left:62px!important }

.sh-prop-wrap{height: 250px}


.slider-shr-wrap{width: 100%;height: 626px;top:100px;}
.tab-sh-slide{left: 30%}
.mob-sh-slide{right:29% }

.tab-sh-slide .flex-direction-nav{width: 532px;left:65%;}
.sh-ins-banner{height: 520px}
.sh-ins-banner{background-size: contain!important;height:986px}

}

@media only screen and (max-width : 991px){
/*.......5 april.......*/

.sh-righ-brlnt{bottom:unset;text-align: center;top:47px;}
.sh-righ-brlnt p{float: none;text-align: center;}
.sh-mid-bg-con p{text-align: center;}
/*.sh-newpage  .right-side{float: left!important;}*/
.mx2-sh .col-sm-2{margin-left: 87px}
.mx2-sh .col-sm-2:nth-child(4){margin-left: 183px;margin-top: 44px;}
.mx2-sh .col-sm-2:nth-child(5){margin-left: 107px;margin-top: 44px;}
.fix-it-sho{margin-left: 174px}
.box-row .button_position{float: left!important;position: relative!important;}
.right-side .device-area{float: none;margin: 0 auto;display: table;width: 60%;margin-top: 35px}

.create-app-block{display: block!important;}
/* .app-category-list-box{display: block!important;}*/

.box-row{display: `block!important;}
.bx-viewport{display: block!important;}
.sh-newpage .create-app-block{margin-top: 2px!important}
.sh-newpage .right-side .device-area .flex-direction-nav{top:59%;width: 536px}

.sh-prop-wrap{height:unset;}

.app-category-list-view{display: none}

.tab-sh-slide{left: 22%}
.mob-sh-slide{right: 26%}
.sh-popular{width: 100%}
.sh-ins-banner{height: 978px;background-size: 100% 450px!important;}
.slider-shr-wrap{height:575px;top:87px; }
.app-category{display: block;}


.mob-shit{display: block;}
.web-shit{display: none;}

.sh-vid-wrap{margin: 47px 0 57px 0}
.sh-icon-vid{width: 100%}

}

@media only screen and (max-width : 767px){

/*.......5 april.......*/
.mx2-sh .col-xs-12{margin-left: 0;margin-top:15px}
.fix-it-sho{margin-left: 0}
.sh-type-theme{float: none;width: 30%; margin: 0 auto;display: table;}
.sh-type-theme img{float: none;width: 100%; margin: 0 auto;display: table;}
.mx2-sh .col-sm-2:nth-child(4){margin-left: 0}
.mx2-sh .col-sm-2:nth-child(5){margin-left: 0}
.sh-popular{margin-bottom: 0}
.right-side{display: table!important;margin:0 auto;width: 55%!important;float: none!important}
.right-side .device-area{width:40%;max-width: 400px }
.flexslider li img{width:auto}
.sh-newpage .right-side .device-area .flex-direction-nav{top:61%;}


.tab-sh-slide{width:250px;left: 17% }
.mob-sh-slide{right: 19%}

.slider-shr-wrap{width: 500px;
float: left;
margin: 0 auto;
display: table;
position: relative;
left: 50%;
margin-left: -250px;top:19px;height: 452px}
.tab-sh-slide li img{margin-left: 10px;margin-top: 34px}
.mob-sh-slide li img{height: 194px;margin-top: 33px;
margin-left: 5px;}
.mob-sh-slide{height: 255px;background-size: 100% 100%}

.mob-sh-slide .flex-prev{text-indent: -999999px;}
.mob-sh-slide .flex-next{text-indent: -999999px;}

.tab-sh-slide .flex-direction-nav{width: 460px;left: 66%;}
.mflexslider{left: 17px;
top: 34px;}

.sh-ins-banner{height: 800px;background-size: 100% 390px!important;}
.mflexslider{left: 17px;
top: 34px;}
.tab-sh-slide li img{margin-left: 0;margin-top: 0}
.sflexslider .slides li img{width: 99%;
margin-top: 31px;}

.sh-icon-vid h3{font-size:27px }
.sh-icon-vid .wrap-vidimgs{width: 56px}
.vid-sh-wrap-pop{width: 75%}

}

@media only screen and (max-width : 639px){
/*.......5 april.......*/
.right-side{float: left!important;width: 100%!important}
.right-side .device-area .slides{margin-left:97px!important }
.flexslider li img{width:315px}
.right-side .device-area{width:50%}
.sh-type-theme{width: 40%}
.sh-type-theme img{width: 100%}
.sh-mid-bg-con p{font-size:16px;text-align: justify; }
.sh-righ-brlnt h3{font-size: 38px}
.sh-righ-brlnt p{font-size: 25px;text-align: center;}
.sh-newpage .right-side .device-area .flex-direction-nav{top:395px;width: 474px}



.res-text-wrap{padding: 61px 0 30px}
.slider-shr-wrap{top:62px;}

.res-text-wrap h1{font-size:36px }
.res-text-wrap h3{font-size: 27px} 

.sflexslider .slides li img{width: 100%} 


.YouTubePopUp-Close{bottom: 348px!important}

}

@media only screen and (max-width : 480px){

/*.......5 april.......*/
.right-side .device-area .slides{margin-left:62px!important }
.flexslider li img{width:210px}

.sh-type-theme img{width: 100%}
.sh-mid-bg-con p{font-size:14px }
.sh-righ-brlnt h3{font-size:35px }

.device-area .flexslider .flex-prev{width: 35px;height: 35px;background-size: 100%}
.device-area .flexslider .flex-next{width: 35px;height: 35px;background-size: 100%}
.sh-newpage .right-side .device-area .flex-direction-nav{top: 251px;width: 100%;}
.right-side{overflow: hidden;}
.sh-newpage .right-side .device-area .flex-direction-nav{left: 0}




.mob-sh-slide{width: 103px}



.res-text-wrap h1{font-size: 29px;}
.tab-sh-slide li img{margin-left: 7px;margin-top: 26px}
.mob-sh-slide{top:146px;}

.tab-sh-slide .flex-prev{width: 25px;height: 25px;background-size: 100%}
.tab-sh-slide .flex-next{width: 25px;height: 25px;background-size: 100%}

.tab-sh-slide{width: 190px;height:272px;left:25%;}
.mob-sh-slide{height: 176px;right: 26%}
.tab-sh-slide .flex-direction-nav{width: 310px}
.slider-shr-wrap{height: 334px}
.sh-popular h3{font-size: 32px}

.res-text-wrap h1{font-size:24px }
.res-text-wrap h3{font-size: 22px}
.mob-sh-slide li img{
height: 133px;
margin-top: 22px;
margin-left: 4px;
width: 92%;
}
.sh-over-text{width: 98px;font-size: 13px}

.sh-ins-banner{height: 697px}


.mflexslider{left: 13px;
top: 26px;}
.sflexslider{left:4%;}
.tab-sh-slide li img{margin-left: 0;margin-top: 0}
.sflexslider .slides li img{width: 99%;
margin-top: 21px;}


.sh-icon-vid h3{font-size: 22px}
.sh-icon-vid .wrap-vidimgs{width: 40px}

.YouTubePopUp-Close{bottom: 247px!important}

}


    </style> 
  
  
<link href="css/layout.css" rel="stylesheet" type="text/css">
<!--video-popup start -->
<div class="overlay">
  <div class="video-popup"> 
    <div id="video-popup"></div>
    <div class="popupClose"><img src="images-new/close.png" alt="close" title="close"></div>
  </div>
</div>

<!--video-popup end -->
<section id="main-wrap" class="sh-newpage">  
  <!-- start content wrap -->
  <section id="content-wrap"> 
    
    <!-- start banner block -->
    <div class="sh-ins-banner">
    <div class="col-lg-12">
       <div class="row">
             <div class="container">
                <div class="col-lg-12">
                     <div class="row">
                            <div class="col-lg-5">
                               <div class="row">
                                  <div class="res-left">
                                    <div class="res-text-wrap">
                                        <h1>Create Brilliant Travel Apps</h1>
                                          <h3>for iOS and Android</h3>
                                    </div>

                                    <div class="res-pplr-theme">
                                         <a href="themes.php" title="Rich Media and Content Apps: Built with Instappy App Builder" class="res-create-app">Other Popular themes</a>
                                    </div>

                                     <p> Beautiful Apps for tablets and phones with expert support,  </p>
               
                 <p> fully-loaded features, custom functionality and more. </p>
                                  </div>  
                               </div>
                            </div>




      
                     

                            <div class="col-lg-7">
                               <div class="row">
                                   <div class="slider-shr-wrap">
                                        <div class="tab-sh-slide">
                                             <div class="mflexslider">
                                                 <ul class="slides" style="margin-left: -40px">
                                                      <li>
                                                        <img src="sh-img/tab-sh1.jpeg " alt="Travel App" title="Travel App" />
                                                      </li>
                                                      <li>
                                                        <img src="sh-img/tab-sh2.jpeg" alt="Travel App Builder" title="Travel App Builder" />
                                                      </li>
                                                      <li>
                                                        <img src="sh-img/tab-sh3.jpeg" alt="Travel App Maker" title="Travel App Maker" />
                                                      </li>
                                                      <li>
                                                        <img src="sh-img/tab-sh4.jpeg" alt="Create a Travel App" title="Create a Travel App" />
                                                      </li>
                                                       <li>
                                                        <img src="sh-img/tab-sh5.jpeg" alt="Mobile App for Travel Agency" title="Mobile App for Travel Agency" />
                                                      </li>
                                                           
                                                  </ul>
                                              </div>

                                        </div>



                                        <div class="mob-sh-slide">
                                               <div class="sflexslider">
                                                 <ul class="slides" style="margin-left: -40px">
                                                      <li>
                                                        <img src="sh-img/mob-sh1.jpeg" alt="Mobile App for Travel" title="Mobile App for Travel" />
                                                      </li>
                                                      <li>
                                                        <img src="sh-img/mob-sh2.jpeg" alt="Make Travel App" title="Make Travel App" />
                                                      </li>
                                                      <li>
                                                        <img src="sh-img/mob-sh3.jpeg" alt="Tourism App Development" title="Tourism App Development" />
                                                      </li>
                                                      <li>
                                                        <img src="sh-img/mob-sh4.jpeg" alt="App Development" title="App Development" />
                                                      </li>
                                                       <li>
                                                        <img src="sh-img/mob-sh5.jpeg" alt="Travel App" title="Travel App" />
                                                      </li>
                                                           
                                                  </ul>
                                              </div>
                                        </div>


                                   </div>
                               </div>
                            </div>
                     </div>
                 </div>    

             </div>
       </div>
    </div>
  </div>
    <!-- end banner block -->
    
    <div class="clearfix"></div>
    
    <!-- start create app block -->
    <article class="col-lg-12">
      <div class="row">
        <div class="container">

          <div class="mx2-sh">
            <div class="col-lg-12">
                <div class="row">
                     <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                          <div class="row">
                                <div class="sh-popular">
                                    <h3>Popular </h3>

                                    <h4>travel Themes</h4>
                                </div>
                          </div>
                     </div>

                      <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                          <div class="row">
                              
                              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <div class="row">
                                    <div class="sh-type-theme">
                                         <img src="sh-img/travel5-3.jpeg">
                                         <div class="sh-over">
                                              <div class="sh-over-text"><a href="panel.php?themeid=66&catid=35&app=create">Create App</a></div>
                                         </div>
                                    </div>

                                    <span class="sh-app-name">Travel</span>
                                </div>
                              </div>

                              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <div class="row">
                                    <div class="sh-type-theme">
                                         <img src="sh-img/blog.jpeg" alt="Travel App Development" title="Travel App Development">
                                          <div class="sh-over">
                                              <div class="sh-over-text"><a href="panel.php?themeid=18&catid=380&app=create">Create App</a></div>
                                         </div>
                                    </div>

                                    <span class="sh-app-name">Wanderlust</span>
                                </div>
                              </div>


                              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                 <div class="row">
                                   <div class="sh-type-theme">
                                         <img src="sh-img/travel.jpeg" alt="Create a Travel App" title="Create a Travel App">
                                          <div class="sh-over">
                                              <div class="sh-over-text"><a href="catalogue-app.php?themeid=34&catid=457&app=create">Create App</a></div>
                                         </div>
                                    </div>
                                     <span class="sh-app-name">Hotel & Travel</span>
                                  </div>  
                              </div>


                              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                               <div class="row">
                               <div class="sh-type-theme">
                                         <img src="sh-img/Travel-Agency.jpeg" alt="Travel Agency" title="Travel Agency">
                                         <div class="sh-over">
                                              <div class="sh-over-text"><a href="panel.php?themeid=23&catid=70&app=create">Create App</a></div>
                                         </div>
                                    </div>
                                    <span class="sh-app-name">Paradise Travels</span>
                                </div>    
                              </div>


                              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                               <div class="row">
                                <div class="sh-type-theme">
                                         <img src="sh-img/grid-fashion4-3.jpeg" alt="Mobile App for Travel" title="Mobile App for Travel">
                                          <div class="sh-over">
                                              <div class="sh-over-text"><a href="panel.php?themeid=65&catid=35&app=create">Create App</a></div>
                                         </div>
                                    </div>
                                    <span class="sh-app-name">France Attractions</span>
                                 </div>   
                              </div>

                          </div>
                     </div>
                </div>
            </div>
           </div> 
        </div>

        <div class="clearfix"></div>

       

       

     

       <div class="col-lg-12">
           <div class="row">
              <div class="sh-mid-bg-con">
                 <div class="container">
                   <div class="col-lg-12">
                       <div class="row">


                         <div class="web-shit">
                              <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                  <div class="row">
                                     <p>Instappy has everything you need to create amazing, fully-loaded, and original travel apps - It&#39;s hassle-free, quick, and you don't need any coding skills. Choose from our built for success, fully-customisable travel templates, or create your own to launch your travel application for smartphones and tablets in an instant.
Get the perfect mobile solution to showcase deals, take bookings and grow your travel business like never before. 
</p>
                                  </div>
                              </div>

                               <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                  <div class="row sh-righ-brlnt">
                                     <h3>instappy</h3>
                                     <p>brillant for travel apps</p>
                                  </div>
                              </div>
                            </div> 


                          <div class="mob-shit">

                               <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                  <div class="row sh-righ-brlnt">
                                     <h3>instappy</h3>
                                     <p>brillant for travel apps</p>
                                  </div>
                              </div>

                              <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                  <div class="row">
                                     <p>Instappy has everything you need to create amazing, fully-loaded, and original travel apps - It&#39;s hassle-free, quick, and you don't need any coding skills. Choose from our built for success, fully-customisable travel templates, or create your own to launch your travel application for smartphones and tablets in an instant.
Get the perfect mobile solution to showcase deals, take bookings and grow your travel business like never before. 
</p>
                                  </div>
                              </div>

                              
                            </div>  
                       </div>
                   </div>
                  </div> 
              </div>
           </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-lg-12">
            <div class="row">
                <div class="container">
                     <div class="col-lg-12">
                        <div class="row">
                            <div class=" col-lg-12 sh-top-feature">
                                <h2>Top Features</h2>
                                <span>for travel app</span>
                            </div>

                             <div class="col-lg-12">
                               <div class="row">
                                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sh-col-4-margin">
                                         <div class="row">
                                              <div class="sh-prop-wrap">
                                                  <div class="sh-prop-icon">
                                                      <div class="sh-wrap-icons">
                                                           <img src="sh-img/calendar.png" alt="Travel App Development" title="Travel App Development">
                                                      </div>
                                                  </div>
                                                  <h3>Advance Bookings</h3>
                                                  <p>Let customers make hassle-free bookings - cabs, hotels and travel packages - right in your travel app. Get notified for all your bookings in one place.</p>
                                              </div>
                                          </div>
                                     </div>


                                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sh-col-4-margin">
                                         <div class="row">
                                              <div class="sh-prop-wrap">
                                                  <div class="sh-prop-icon">
                                                      <div class="sh-wrap-icons">
                                                           <img src="sh-img/chat-icon.png" alt="Mobile App for Travel Industry" title="Mobile App for Travel Industry">
                                                      </div>
                                                  </div>
                                                  <h3>Live Chat with a Travel Agent</h3>
                                                  <p>Improve your customer service with live chat feature - Answer queries, help them choose the right package, convert better and escort them all the way through to checkout.</p>
                                              </div>
                                          </div>
                                     </div>


                                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sh-col-4-margin">
                                         <div class="row">
                                              <div class="sh-prop-wrap">
                                                  <div class="sh-prop-icon">
                                                      <div class="sh-wrap-icons">
                                                           <img src="sh-img/icon-sh-th.png" alt="Hotel Mobile App Features" title="Hotel Mobile App Features">
                                                      </div>
                                                  </div>
                                                  <h3>Your Own Travel Blog</h3>
                                                  <p>Add a blog to your app on all things travel - travel tips, must-see destinations and more - and update them on the fly. It works great to engage travellers and add credibility to your business.</p>
                                              </div>
                                          </div>
                                     </div>


                                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sh-col-4-margin">
                                         <div class="row">
                                              <div class="sh-prop-wrap">
                                                  <div class="sh-prop-icon">
                                                      <div class="sh-wrap-icons">
                                                           <img src="sh-img/sh-sc-icon.png" alt="Travel App Android" title="Travel App Android">
                                                      </div>
                                                  </div>
                                                  <h3>Get Reviewed on Social</h3>
                                                  <p>Let your customers review and recommend your business, and share their experience on social - TripAdvisor, Expedia, Agoda, Instagram and 45+ platforms.</p>
                                              </div>
                                          </div>
                                     </div>


                                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sh-col-4-margin">
                                         <div class="row">
                                              <div class="sh-prop-wrap">
                                                  <div class="sh-prop-icon">
                                                      <div class="sh-wrap-icons">
                                                           <img src="sh-img/sh-bea.png" alt="Best Travel App" title="Best Travel App">
                                                      </div>
                                                  </div>
                                                  <h3>Highlight Seasonal Tours</h3>
                                                  <p>Informed customers are more likely to book. Add self-expiring seasonal cards to your app, highlighting special tour & travel packages. 
</p>
                                              </div>
                                          </div>
                                     </div>


                                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sh-col-4-margin">
                                         <div class="row">
                                              <div class="sh-prop-wrap">
                                                  <div class="sh-prop-icon">
                                                      <div class="sh-wrap-icons">
                                                           <img src="sh-img/img-sh-ic.png" alt="Travel App Maker" title="Travel App Maker">
                                                      </div>
                                                  </div>
                                                  <h3>HD Photo Gallery</h3>
                                                  <p>A picture&#39;s worth a thousand words and it especially holds true for travel. Give your customers a glimpse of their dream destinations with HD pictures on your app.
</p>
                                              </div>
                                          </div>
                                     </div>


                                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sh-col-4-margin">
                                         <div class="row">
                                              <div class="sh-prop-wrap">
                                                  <div class="sh-prop-icon">
                                                      <div class="sh-wrap-icons">
                                                           <img src="sh-img/mob-sh-pay.png" alt="Create an Travel App" title="Create an Travel App">
                                                      </div>
                                                  </div>
                                                  <h3>Loyalty & Referral Programs</h3>
                                                  <p>Mobile loyalty cards are more effective than the conventional loyalty cards doled out to every customer. Reward your regulars with mobile loyalty programs and keep them coming back for more.
</p>
                                              </div>
                                          </div>
                                     </div>


                                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sh-col-4-margin">
                                         <div class="row">
                                              <div class="sh-prop-wrap">
                                                  <div class="sh-prop-icon">
                                                      <div class="sh-wrap-icons">
                                                           <img src="sh-img/sh-mob-pay.png" alt="Travel App" title="Travel App">
                                                      </div>
                                                  </div>
                                                  <h3>Mobile Payment</h3>
                                                  <p>If you don&#39;t already have mobile payments integrated into your travel app, you are missing out on a lot of potential revenue.</p>
                                              </div>
                                          </div>
                                     </div>


                                     <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sh-col-4-margin fix-it-sho">
                                         <div class="row">
                                              <div class="sh-prop-wrap">
                                                  <div class="sh-prop-icon">
                                                      <div class="sh-wrap-icons">
                                                           <img src="sh-img/sh-im.png" alt="Mobile App Builder for Travel" title="Mobile App Builder for Travel">
                                                      </div>
                                                  </div>
                                                  <h3>Get Booked During Off Season</h3>
                                                  <p>Pull travellers to book during off season with personalised push notifications and in-app messages.</p>
                                              </div>
                                          </div>
                                     </div>


                                    

                                     
                               </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
<div class="clearfix"></div>
      
    </article>
   

    <div class="clearfix"></div>
    
   
    <article class="create-app-block">
      <div class="box-row">
        
        <div class="app-category">
          <div class="pro-app"> <a id="pro-app-link" class="active app_selected" rel="pro_app" href="javascript:void(0);" title="">  <span></span> </a> 
            
            <!--pro-app-menu-start -->
            <div class="pro-app-menus pro-apps">
              <ul>                 
              </ul>
            </div>
            <!--pro-app-menu-end --> 
            
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
              <p> <br>
                <strong></strong><br>
                <span></span></p>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </article>
   
        
        <div class="clearfix"></div>
      </div>
    </article>
    
    <div class="col-lg-12">
	    <div class="row">
	      <div class="sh-vid-wrap">
	       <div class="vid-sh-wrap-pop">
	            <img src="sh-img/footer-vid-sh.png">
	            <div class="sh-icon-vid">
	               <h3>Watch Video</h3>
	               	<div class="wrap-vidimgs">
	               		<a href="https://www.youtube.com/watch?v=kqsd5s_7HdQ&t=1s" class="various"><img src="sh-img/play-me-sh.png"></a>
	               	</div>
	            </div>
	           
	       </div>
	      </div> 
	    </div>
	</div>

    <div class="clearfix"></div>
  </section>
  <!-- end content wrap --> 
  
</section>


<!-- start footer -->
<?php require_once('website_footer_new.php');?>

<script type="text/javascript" src="js/YouTubePopUp.jquery.js"></script>

<script src="js/main-index.js"></script> 
 <script type="text/javascript">
     requirejs(['jquery'], function ($) {
   
  $(window).load(function() {
            
             $('.mflexslider').flexslider({
        animation: 'slide',
        controlNav: true,
        before: function(slider){
      $('.sflexslider').flexslider(slider.animatingTo);
    }
    });

    $('.sflexslider').flexslider({
        animation: 'slide',
        controlNav: false,
        before: function(slider){
      $('.mflexslider').flexslider(slider.animatingTo);
    }
    });
             
}); 
  $(document).on('click', '.mflexslider > .flex-direction-nav > .flex-nav-prev > a', function(){
            
             $(".sflexslider > .flex-direction-nav > .flex-nav-prev > a").trigger( "click" );


        });
      $(document).on('click', '.mflexslider > .flex-direction-nav > .flex-nav-next > a',  function(){
            
             $(".sflexslider > .flex-direction-nav > .flex-nav-next > a").trigger( "click" );


        });

      $(function () {
        $("a.various").YouTubePopUp();
    });
    
        });

    </script>


