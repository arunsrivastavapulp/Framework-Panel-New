<?php
session_start();
require_once 'modules/login/openId.php';
require_once 'config/db.php';
$db = new DB();

require_once('modules/login/login-check.php');
$login = new Login();

if (empty($_SESSION['username']) && empty($_SESSION['custid'])) {
    $currentPage = $_SERVER['REQUEST_URI'];
    $pages = $db->restrictPages();
    //echo "<pre>"; print_r($pages); echo "</pre>";
    $page = substr($currentPage, strrpos($currentPage, '/') + 1);
    $pageName = substr($page, 0, strrpos($page, '?'));
    if (!in_array($pageName, $pages)) {
        header('Location:' . $db->siteurl());
        exit;
    }
}
if (!empty($_SESSION['username']) || !empty($_SESSION['custid'])) {

    $results = $login->check_user_exist($_SESSION['username'], $_SESSION['custid']);

    if (!empty($results['avatar'])) {
        $mg = '<a href="userprofile.php"><img src="avatars/' . $results['avatar'] . '" alt="' . $_SESSION['username'] . '"></a>';
        $name = isset($results['first_name']) ? $results['first_name'] : 'Guest';
        $_SESSION['proimage'] = '<a href="http://www.instappy.com/userprofile.php"><img src="http://www.instappy.com/avatars/' . $results['avatar'] . '" alt="' . $_SESSION['username'] . '"></a>';
    } else {
        $mg = '<a href="userprofile.php"><img src="avatars/user.png" alt="Image Not Found"></a>';
        $name = isset($results['first_name']) ? $results['first_name'] : 'Guest';
        $_SESSION['proimage'] = '<a href="http://www.instappy.com/userprofile.php"><img src="http://www.instappy.com/avatars/user.png" alt="Avatar"></a>';
    }
    if (isset($_GET['appid'])) {
        $appid = $_GET['appid'];
        $cust_Id = $_SESSION['custid'];
        $results = $login->appidAuthouridcheck($cust_Id, $appid);
        if (($results['id'] == '') || ($results['id'] == null)) {
            header('Location:' . $db->siteurl());
            exit;
        }
    }
} else {
    $mg = '<img src="http://www.instappy.com/avatars/user.png" alt="Image Not Found">';
    $name = 'Guest';
}
$basicUrl = $db->siteurl();
$catalogueUrl = $db->catalogue_url();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
                                                            <head>
                                                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                                            <link href="css/custom-style.css" rel="stylesheet" type="text/css" />
                                                            <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
                                                            <link rel="stylesheet" type="text/css" href="css/colpick.css">
                                                            <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
                                                            <link rel="stylesheet" href="css/style_old.css">
                                                            <link rel="stylesheet" type="text/css" href="css/ImageSelect.css">
                                                            <link rel="stylesheet" type="text/css" href="css/chosen.css">
                                                            <link rel="stylesheet" type="text/css" href="css/price.css"/>
                                                            <link rel="stylesheet" type="text/css" href="css/price2.css"/>
                                                            <link rel="stylesheet" href="css/intlTelInput.css">
                                                            <link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
                                                            <link href='//fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
                                                            <link href='//fonts.googleapis.com/css?family=Didact+Gothic' rel='stylesheet' type='text/css'>
                                                            <link href='//fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>
                                                            <script src="js/jquery.min.js"></script>
                                                            <script type="text/javascript" src="js/jquery.form.min.js"></script>
                                                            <script src="js/login.js"></script>
                                                            
															
                                                            <script src="js/finish.js"></script>
                                                            <script src="js/html5shiv.js"></script>
                                                            <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
                                                            <meta name="google-signin-client_id" content="174561932350-at2c0h9qdr81thch52hqurl7l437fl5m.apps.googleusercontent.com">
                                                            <script src="//connect.facebook.net/en_US/all.js&appId=896029983801806" type="text/javascript"></script>
                                                            <!--                                                        <link rel="shortcut icon" href="images/favicon.ico"/>
                                                                                                                <link rel="apple-touch-icon" href="images/favicon.ico"/>-->
                                                            <link rel="icon" href="images/instappy-logo.gif" type="image/gif" />
                                                            <!-- For IcoMoon -->
                                                            <link rel="stylesheet" href="css/icomoon_style.css">
                                                            <title>Instappy</title>
                                                            <script type="text/javascript">
                                                                var BASEURL = '<?php echo $basicUrl; ?>';
                                                                var catalogueUrl = '<?php echo $catalogueUrl; ?>';
																
                                                                $(window).load(function () {
                                                                    $('#screenoverlay').fadeOut();
                                                                });
                                                                $(document).ready(function (e) {
                                                                    $('.login_popup .login_tabbing').hide().eq(0).show();
                                                                    $('.login_popup .login_popup_head h2').hide().eq(0).show();
                                                                    $('.login_popup ul.tabs li').eq(0).addClass('active');
                                                                    $("#fakeLanguage").hide();
                                                                    var location = window.location.pathname;
                                                                    if (location.indexOf('panel.php') != -1) {
                                                                        //$('.banner').css('height','170px;')
                                                                        $("#fakeLanguage").show();
                                                                    }
                                                                });
																
                                                            </script>
                                                            </head>

                                                            <body>
<div id="screenoverlay" style="height:100%;width:100%;position:fixed;background: #000;opacity: .7;top: 0;left: 0;z-index: 13;"> <img src="images/ajax-loader.gif" style="position: absolute; top: 40%; left: 0px; right: 0px; margin: 0px auto;"> </div>
<div class="popup_container">
                                                              <div id="page_ajax"></div>
                                                              <div class="icon_set_popup"> <span class="close_popup"><img src="image/popup_close.png"></span>
    <div class="icon_set_head">
                                                                  <h2>Icon Set</h2>
                                                                </div>
    <div class="icon_set_body">
                                                                  <div class="icon"> <span class="icon-alarm"></span> </div>
                                                                  <div class="icon"> <span class="icon-book"></span> </div>
                                                                  <div class="icon"> <span class="icon-bookmark-1"></span> </div>
                                                                  <div class="icon"> <span class="icon-briefcase"></span> </div>
                                                                  <div class="icon"> <span class="icon-bullhorn"></span> </div>
                                                                  <div class="icon"> <span class="icon-calendar-full"></span> </div>
                                                                  <div class="icon"> <span class="icon-camera-video"></span> </div>
                                                                  <div class="icon"> <span class="icon-camera"></span> </div>
                                                                  <div class="icon"> <span class="icon-cart-1"></span> </div>
                                                                  <div class="icon"> <span class="icon-clock"></span> </div>
                                                                  <div class="icon"> <span class="icon-coffee-cup"></span> </div>
                                                                  <div class="icon"> <span class="icon-cog"></span> </div>
                                                                  <div class="icon"> <span class="icon-dinner"></span> </div>
                                                                  <div class="icon"> <span class="icon-earth"></span> </div>
                                                                  <div class="icon"> <span class="icon-envelope"></span> </div>
                                                                  <div class="icon"> <span class="icon-flag"></span> </div>
                                                                  <div class="icon"> <span class="icon-gift"></span> </div>
                                                                  <div class="icon"> <span class="icon-graduation-hat"></span> </div>
                                                                  <div class="icon"> <span class="icon-heart-1"></span> </div>
                                                                  <div class="icon"> <span class="icon-home-1"></span> </div>
                                                                  <div class="icon"> <span class="icon-location"></span> </div>
                                                                  <div class="icon"> <span class="icon-map"></span> </div>
                                                                  <div class="icon"> <span class="icon-phone-handset"></span> </div>
                                                                  <div class="icon"> <span class="icon-picture"></span> </div>
                                                                  <div class="icon"> <span class="icon-pushpin"></span> </div>
                                                                  <div class="icon"> <span class="icon-rocket"></span> </div>
                                                                  <div class="icon"> <span class="icon-shirt"></span> </div>
                                                                  <div class="icon"> <span class="icon-smartphone"></span> </div>
                                                                  <div class="icon"> <span class="icon-star"></span> </div>
                                                                  <div class="icon"> <span class="icon-store"></span> </div>
                                                                  <div class="icon"> <span class="icon-tag-1"></span> </div>
                                                                  <div class="icon"> <span class="icon-thumbs-down"></span> </div>
                                                                  <div class="icon"> <span class="icon-thumbs-up"></span> </div>
                                                                  <div class="icon"> <span class="icon-trash"></span> </div>
                                                                  <div class="icon"> <span class="icon-user-1"></span> </div>
                                                                  <div class="icon"> <span class="icon-users"></span> </div>
                                                                  <div class="icon"> <span class="icon-warning"></span> </div>
                                                                  <div class="icon"> <span class="icon-email"></span> </div>
                                                                  <div class="icon"> <span class="icon-delivery"></span> </div>
                                                                  <div class="icon"> <span class="icon-donation"></span> </div>
                                                                  <div class="icon"> <span class="icon-call"></span> </div>
                                                                  <div class="icon"> <span class="icon-table"></span> </div>
                                                                  <div class="icon"> <span class="icon-reservation"></span> </div>
                                                                  <div class="icon"> <span class="icon-twitter"></span> </div>
                                                                  <div class="icon"> <span class="icon-share"></span> </div>
                                                                  <div class="icon"> <span class="icon-favorite"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow"></span> </div>
                                                                  <div class="icon"> <span class="icon-facebook"></span> </div>
                                                                  <div class="icon"> <span class="icon-report"></span> </div>
                                                                  <div class="icon"> <span class="icon-feedback"></span> </div>
                                                                  <div class="icon"> <span class="icon-wheelchair"></span> </div>
                                                                  <div class="icon"> <span class="icon-tennis"></span> </div>
                                                                  <div class="icon"> <span class="icon-pet-print"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-1"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-2"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-3"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-4"></span> </div>
                                                                  <div class="icon"> <span class="icon-bone"></span> </div>
                                                                  <div class="icon"> <span class="icon-train"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-5"></span> </div>
                                                                  <div class="icon"> <span class="icon-manure"></span> </div>
                                                                  <div class="icon"> <span class="icon-kennel"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-6"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-7"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-8"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-9"></span> </div>
                                                                  <div class="icon"> <span class="icon-tablet"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-10"></span> </div>
                                                                  <div class="icon"> <span class="icon-sun"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-11"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-12"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-13"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-14"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-15"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-16"></span> </div>
                                                                  <div class="icon"> <span class="icon-smile"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-17"></span> </div>
                                                                  <div class="icon"> <span class="icon-screen"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-18"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-19"></span> </div>
                                                                  <div class="icon"> <span class="icon-question-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-printer"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-20"></span> </div>
                                                                  <div class="icon"> <span class="icon-pets-21"></span> </div>
                                                                  <div class="icon"> <span class="icon-bowl"></span> </div>
                                                                  <div class="icon"> <span class="icon-dog-bone"></span> </div>
                                                                  <div class="icon"> <span class="icon-dog-belt"></span> </div>
                                                                  <div class="icon"> <span class="icon-wedding10"></span> </div>
                                                                  <div class="icon"> <span class="icon-wedding-flower"></span> </div>
                                                                  <div class="icon"> <span class="icon-wedding-rings"></span> </div>
                                                                  <div class="icon"> <span class="icon-pie-chart"></span> </div>
                                                                  <div class="icon"> <span class="icon-phone"></span> </div>
                                                                  <div class="icon"> <span class="icon-pencil"></span> </div>
                                                                  <div class="icon"> <span class="icon-paw"></span> </div>
                                                                  <div class="icon"> <span class="icon-paperclip"></span> </div>
                                                                  <div class="icon"> <span class="icon-suit2"></span> </div>
                                                                  <div class="icon"> <span class="icon-ring26"></span> </div>
                                                                  <div class="icon"> <span class="icon-mustache"></span> </div>
                                                                  <div class="icon"> <span class="icon-music-note"></span> </div>
                                                                  <div class="icon"> <span class="icon-peace10"></span> </div>
                                                                  <div class="icon"> <span class="icon-moon"></span> </div>
                                                                  <div class="icon"> <span class="icon-mic"></span> </div>
                                                                  <div class="icon"> <span class="icon-male243"></span> </div>
                                                                  <div class="icon"> <span class="icon-lover4"></span> </div>
                                                                  <div class="icon"> <span class="icon-map-marker"></span> </div>
                                                                  <div class="icon"> <span class="icon-magnifier"></span> </div>
                                                                  <div class="icon"> <span class="icon-magic-wand"></span> </div>
                                                                  <div class="icon"> <span class="icon-lock"></span> </div>
                                                                  <div class="icon"> <span class="icon-heart314"></span> </div>
                                                                  <div class="icon"> <span class="icon-gift70"></span> </div>
                                                                  <div class="icon"> <span class="icon-flower113"></span> </div>
                                                                  <div class="icon"> <span class="icon-dress12"></span> </div>
                                                                  <div class="icon"> <span class="icon-lighter"></span> </div>
                                                                  <div class="icon"> <span class="icon-license"></span> </div>
                                                                  <div class="icon"> <span class="icon-leaf"></span> </div>
                                                                  <div class="icon"> <span class="icon-layers"></span> </div>
                                                                  <div class="icon"> <span class="icon-laptop"></span> </div>
                                                                  <div class="icon"> <span class="icon-laptop-phone"></span> </div>
                                                                  <div class="icon"> <span class="icon-keyboard"></span> </div>
                                                                  <div class="icon"> <span class="icon-cake22"></span> </div>
                                                                  <div class="icon"> <span class="icon-bell56"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow649"></span> </div>
                                                                  <div class="icon"> <span class="icon-inbox"></span> </div>
                                                                  <div class="icon"> <span class="icon-hourglass"></span> </div>
                                                                  <div class="icon"> <span class="icon-history"></span> </div>
                                                                  <div class="icon"> <span class="icon-highlight"></span> </div>
                                                                  <div class="icon"> <span class="icon-heart-pulse"></span> </div>
                                                                  <div class="icon"> <span class="icon-hand"></span> </div>
                                                                  <div class="icon"> <span class="icon-funnel"></span> </div>
                                                                  <div class="icon"> <span class="icon-tea18"></span> </div>
                                                                  <div class="icon"> <span class="icon-spa24"></span> </div>
                                                                  <div class="icon"> <span class="icon-film-play"></span> </div>
                                                                  <div class="icon"> <span class="icon-file-empty"></span> </div>
                                                                  <div class="icon"> <span class="icon-spa23"></span> </div>
                                                                  <div class="icon"> <span class="icon-eye"></span> </div>
                                                                  <div class="icon"> <span class="icon-spa22"></span> </div>
                                                                  <div class="icon"> <span class="icon-spa21"></span> </div>
                                                                  <div class="icon"> <span class="icon-spa20"></span> </div>
                                                                  <div class="icon"> <span class="icon-spa19"></span> </div>
                                                                  <div class="icon"> <span class="icon-drop"></span> </div>
                                                                  <div class="icon"> <span class="icon-spa18"></span> </div>
                                                                  <div class="icon"> <span class="icon-spa17"></span> </div>
                                                                  <div class="icon"> <span class="icon-spa16"></span> </div>
                                                                  <div class="icon"> <span class="icon-dice"></span> </div>
                                                                  <div class="icon"> <span class="icon-relaxing1"></span> </div>
                                                                  <div class="icon"> <span class="icon-database"></span> </div>
                                                                  <div class="icon"> <span class="icon-relaxing"></span> </div>
                                                                  <div class="icon"> <span class="icon-massage2"></span> </div>
                                                                  <div class="icon"> <span class="icon-leaves16"></span> </div>
                                                                  <div class="icon"> <span class="icon-construction"></span> </div>
                                                                  <div class="icon"> <span class="icon-herbal1"></span> </div>
                                                                  <div class="icon"> <span class="icon-cloud"></span> </div>
                                                                  <div class="icon"> <span class="icon-cloud-upload"></span> </div>
                                                                  <div class="icon"> <span class="icon-cloud-sync"></span> </div>
                                                                  <div class="icon"> <span class="icon-cloud-download"></span> </div>
                                                                  <div class="icon"> <span class="icon-cloud-check"></span> </div>
                                                                  <div class="icon"> <span class="icon-fruit6"></span> </div>
                                                                  <div class="icon"> <span class="icon-empty33"></span> </div>
                                                                  <div class="icon"> <span class="icon-claw1"></span> </div>
                                                                  <div class="icon"> <span class="icon-bamboo4"></span> </div>
                                                                  <div class="icon"> <span class="icon-aromatherapy2"></span> </div>
                                                                  <div class="icon"> <span class="icon-writing28"></span> </div>
                                                                  <div class="icon"> <span class="icon-writing3"></span> </div>
                                                                  <div class="icon"> <span class="icon-write41"></span> </div>
                                                                  <div class="icon"> <span class="icon-worldwide1"></span> </div>
                                                                  <div class="icon"> <span class="icon-universitydegree"></span> </div>
                                                                  <div class="icon"> <span class="icon-chart-bars"></span> </div>
                                                                  <div class="icon"> <span class="icon-car"></span> </div>
                                                                  <div class="icon"> <span class="icon-bus"></span> </div>
                                                                  <div class="icon"> <span class="icon-bug"></span> </div>
                                                                  <div class="icon"> <span class="icon-bubble"></span> </div>
                                                                  <div class="icon"> <span class="icon-bold"></span> </div>
                                                                  <div class="icon"> <span class="icon-bicycle"></span> </div>
                                                                  <div class="icon"> <span class="icon-tomato"></span> </div>
                                                                  <div class="icon"> <span class="icon-stargazing"></span> </div>
                                                                  <div class="icon"> <span class="icon-sportsballoon"></span> </div>
                                                                  <div class="icon"> <span class="icon-sportsball2"></span> </div>
                                                                  <div class="icon"> <span class="icon-speechbubble19"></span> </div>
                                                                  <div class="icon"> <span class="icon-speechbubble18"></span> </div>
                                                                  <div class="icon"> <span class="icon-sound46"></span> </div>
                                                                  <div class="icon"> <span class="icon-soccercup"></span> </div>
                                                                  <div class="icon"> <span class="icon-apartment"></span> </div>
                                                                  <div class="icon"> <span class="icon-favorites-1"></span> </div>
                                                                  <div class="icon"> <span class="icon-search1"></span> </div>
                                                                  <div class="icon"> <span class="icon-sciences"></span> </div>
                                                                  <div class="icon"> <span class="icon-science26"></span> </div>
                                                                  <div class="icon"> <span class="icon-science25"></span> </div>
                                                                  <div class="icon"> <span class="icon-science24"></span> </div>
                                                                  <div class="icon"> <span class="icon-science23"></span> </div>
                                                                  <div class="icon"> <span class="icon-science22"></span> </div>
                                                                  <div class="icon"> <span class="icon-school72"></span> </div>
                                                                  <div class="icon"> <span class="icon-school71"></span> </div>
                                                                  <div class="icon"> <span class="icon-schoolmaterials10"></span> </div>
                                                                  <div class="icon"> <span class="icon-schoolmaterials9"></span> </div>
                                                                  <div class="icon"> <span class="icon-schoolmaterials7"></span> </div>
                                                                  <div class="icon"> <span class="icon-saturn15"></span> </div>
                                                                  <div class="icon"> <span class="icon-rucksack"></span> </div>
                                                                  <div class="icon"> <span class="icon-rubber9"></span> </div>
                                                                  <div class="icon"> <span class="icon-research4"></span> </div>
                                                                  <div class="icon"> <span class="icon-research3"></span> </div>
                                                                  <div class="icon"> <span class="icon-readingglasses1"></span> </div>
                                                                  <div class="icon"> <span class="icon-puzzlepiece"></span> </div>
                                                                  <div class="icon"> <span class="icon-player11"></span> </div>
                                                                  <div class="icon"> <span class="icon-people"></span> </div>
                                                                  <div class="icon"> <span class="icon-paint7"></span> </div>
                                                                  <div class="icon"> <span class="icon-paint2"></span> </div>
                                                                  <div class="icon"> <span class="icon-openbook"></span> </div>
                                                                  <div class="icon"> <span class="icon-note58"></span> </div>
                                                                  <div class="icon"> <span class="icon-mouse1"></span> </div>
                                                                  <div class="icon"> <span class="icon-monitor95"></span> </div>
                                                                  <div class="icon"> <span class="icon-maths18"></span> </div>
                                                                  <div class="icon"> <span class="icon-maths17"></span> </div>
                                                                  <div class="icon"> <span class="icon-man7"></span> </div>
                                                                  <div class="icon"> <span class="icon-alarm70"></span> </div>
                                                                  <div class="icon"> <span class="icon-light2"></span> </div>
                                                                  <div class="icon"> <span class="icon-laptops"></span> </div>
                                                                  <div class="icon"> <span class="icon-lamp31"></span> </div>
                                                                  <div class="icon"> <span class="icon-addressbook2"></span> </div>
                                                                  <div class="icon"> <span class="icon-internet"></span> </div>
                                                                  <div class="icon"> <span class="icon-international41"></span> </div>
                                                                  <div class="icon"> <span class="icon-instrument9"></span> </div>
                                                                  <div class="icon"> <span class="icon-ink17"></span> </div>
                                                                  <div class="icon"> <span class="icon-identification9"></span> </div>
                                                                  <div class="icon"> <span class="icon-greek2"></span> </div>
                                                                  <div class="icon"> <span class="icon-graduationhat"></span> </div>
                                                                  <div class="icon"> <span class="icon-graduate38"></span> </div>
                                                                  <div class="icon"> <span class="icon-geography4"></span> </div>
                                                                  <div class="icon"> <span class="icon-fruit47"></span> </div>
                                                                  <div class="icon"> <span class="icon-finger1"></span> </div>
                                                                  <div class="icon"> <span class="icon-financeandbusiness6"></span> </div>
                                                                  <div class="icon"> <span class="icon-feathers1"></span> </div>
                                                                  <div class="icon"> <span class="icon-erlenmeyer3"></span> </div>
                                                                  <div class="icon"> <span class="icon-envelope56"></span> </div>
                                                                  <div class="icon"> <span class="icon-electromagnet"></span> </div>
                                                                  <div class="icon"> <span class="icon-e-learning"></span> </div>
                                                                  <div class="icon"> <span class="icon-distance3"></span> </div>
                                                                  <div class="icon"> <span class="icon-diploma1"></span> </div>
                                                                  <div class="icon"> <span class="icon-cut35"></span> </div>
                                                                  <div class="icon"> <span class="icon-cup59"></span> </div>
                                                                  <div class="icon"> <span class="icon-computers10"></span> </div>
                                                                  <div class="icon"> <span class="icon-compactdisc4"></span> </div>
                                                                  <div class="icon"> <span class="icon-college"></span> </div>
                                                                  <div class="icon"> <span class="icon-clock145"></span> </div>
                                                                  <div class="icon"> <span class="icon-clock144"></span> </div>
                                                                  <div class="icon"> <span class="icon-chemistry21"></span> </div>
                                                                  <div class="icon"> <span class="icon-calendar182"></span> </div>
                                                                  <div class="icon"> <span class="icon-briefcase2"></span> </div>
                                                                  <div class="icon"> <span class="icon-books45"></span> </div>
                                                                  <div class="icon"> <span class="icon-books44"></span> </div>
                                                                  <div class="icon"> <span class="icon-books43"></span> </div>
                                                                  <div class="icon"> <span class="icon-books42"></span> </div>
                                                                  <div class="icon"> <span class="icon-bookmarks3"></span> </div>
                                                                  <div class="icon"> <span class="icon-book247"></span> </div>
                                                                  <div class="icon"> <span class="icon-book246"></span> </div>
                                                                  <div class="icon"> <span class="icon-book245"></span> </div>
                                                                  <div class="icon"> <span class="icon-blackboard17"></span> </div>
                                                                  <div class="icon"> <span class="icon-blackboard16"></span> </div>
                                                                  <div class="icon"> <span class="icon-basketball"></span> </div>
                                                                  <div class="icon"> <span class="icon-award2"></span> </div>
                                                                  <div class="icon"> <span class="icon-audio2"></span> </div>
                                                                  <div class="icon"> <span class="icon-audience1"></span> </div>
                                                                  <div class="icon"> <span class="icon-attach13"></span> </div>
                                                                  <div class="icon"> <span class="icon-atom28"></span> </div>
                                                                  <div class="icon"> <span class="icon-angle9"></span> </div>
                                                                  <div class="icon"> <span class="icon-alarm71"></span> </div>
                                                                  <div class="icon"> <span class="icon-addressbook1"></span> </div>
                                                                  <div class="icon"> <span class="icon-addressbook"></span> </div>
                                                                  <div class="icon"> <span class="icon-signboard"></span> </div>
                                                                  <div class="icon"> <span class="icon-rounded21"></span> </div>
                                                                  <div class="icon"> <span class="icon-progress1"></span> </div>
                                                                  <div class="icon"> <span class="icon-point8"></span> </div>
                                                                  <div class="icon"> <span class="icon-leaf8"></span> </div>
                                                                  <div class="icon"> <span class="icon-houses1"></span> </div>
                                                                  <div class="icon"> <span class="icon-hammers4"></span> </div>
                                                                  <div class="icon"> <span class="icon-for2"></span> </div>
                                                                  <div class="icon"> <span class="icon-apple63"></span> </div>
                                                                  <div class="icon"> <span class="icon-female8"></span> </div>
                                                                  <div class="icon"> <span class="icon-draft"></span> </div>
                                                                  <div class="icon"> <span class="icon-dollar12"></span> </div>
                                                                  <div class="icon"> <span class="icon-decline1"></span> </div>
                                                                  <div class="icon"> <span class="icon-close13"></span> </div>
                                                                  <div class="icon"> <span class="icon-cars8"></span> </div>
                                                                  <div class="icon"> <span class="icon-calculator14"></span> </div>
                                                                  <div class="icon"> <span class="icon-businessman8"></span> </div>
                                                                  <div class="icon"> <span class="icon-yoga13"></span> </div>
                                                                  <div class="icon"> <span class="icon-yin6"></span> </div>
                                                                  <div class="icon"> <span class="icon-wine57"></span> </div>
                                                                  <div class="icon"> <span class="icon-weightlifter3"></span> </div>
                                                                  <div class="icon"> <span class="icon-weightlifter2"></span> </div>
                                                                  <div class="icon"> <span class="icon-weightlifter1"></span> </div>
                                                                  <div class="icon"> <span class="icon-water53"></span> </div>
                                                                  <div class="icon"> <span class="icon-two328"></span> </div>
                                                                  <div class="icon"> <span class="icon-truck38"></span> </div>
                                                                  <div class="icon"> <span class="icon-trophy56"></span> </div>
                                                                  <div class="icon"> <span class="icon-thumb42"></span> </div>
                                                                  <div class="icon"> <span class="icon-thin37"></span> </div>
                                                                  <div class="icon"> <span class="icon-swimming22"></span> </div>
                                                                  <div class="icon"> <span class="icon-sunrise4"></span> </div>
                                                                  <div class="icon"> <span class="icon-bald37"></span> </div>
                                                                  <div class="icon"> <span class="icon-steroids1"></span> </div>
                                                                  <div class="icon"> <span class="icon-star157"></span> </div>
                                                                  <div class="icon"> <span class="icon-standing92"></span> </div>
                                                                  <div class="icon"> <span class="icon-sportive57"></span> </div>
                                                                  <div class="icon"> <span class="icon-skating3"></span> </div>
                                                                  <div class="icon"> <span class="icon-scale17"></span> </div>
                                                                  <div class="icon"> <span class="icon-scale16"></span> </div>
                                                                  <div class="icon"> <span class="icon-sauce3"></span> </div>
                                                                  <div class="icon"> <span class="icon-runer"></span> </div>
                                                                  <div class="icon"> <span class="icon-resting5"></span> </div>
                                                                  <div class="icon"> <span class="icon-restaurant44"></span> </div>
                                                                  <div class="icon"> <span class="icon-pencil88"></span> </div>
                                                                  <div class="icon"> <span class="icon-oxygenation"></span> </div>
                                                                  <div class="icon"> <span class="icon-notes24"></span> </div>
                                                                  <div class="icon"> <span class="icon-no67"></span> </div>
                                                                  <div class="icon"> <span class="icon-musical104"></span> </div>
                                                                  <div class="icon"> <span class="icon-musical103"></span> </div>
                                                                  <div class="icon"> <span class="icon-music224"></span> </div>
                                                                  <div class="icon"> <span class="icon-musculous"></span> </div>
                                                                  <div class="icon"> <span class="icon-meter7"></span> </div>
                                                                  <div class="icon"> <span class="icon-medical87"></span> </div>
                                                                  <div class="icon"> <span class="icon-apple64"></span> </div>
                                                                  <div class="icon"> <span class="icon-medical86"></span> </div>
                                                                  <div class="icon"> <span class="icon-medical85"></span> </div>
                                                                  <div class="icon"> <span class="icon-man429"></span> </div>
                                                                  <div class="icon"> <span class="icon-man428"></span> </div>
                                                                  <div class="icon"> <span class="icon-kickboxing1"></span> </div>
                                                                  <div class="icon"> <span class="icon-heart288"></span> </div>
                                                                  <div class="icon"> <span class="icon-heart287"></span> </div>
                                                                  <div class="icon"> <span class="icon-heart286"></span> </div>
                                                                  <div class="icon"> <span class="icon-heart285"></span> </div>
                                                                  <div class="icon"> <span class="icon-healthy7"></span> </div>
                                                                  <div class="icon"> <span class="icon-hanging14"></span> </div>
                                                                  <div class="icon"> <span class="icon-gloves5"></span> </div>
                                                                  <div class="icon"> <span class="icon-flexions"></span> </div>
                                                                  <div class="icon"> <span class="icon-fitness"></span> </div>
                                                                  <div class="icon"> <span class="icon-first41"></span> </div>
                                                                  <div class="icon"> <span class="icon-file73"></span> </div>
                                                                  <div class="icon"> <span class="icon-dumbbells3"></span> </div>
                                                                  <div class="icon"> <span class="icon-dumbbells2"></span> </div>
                                                                  <div class="icon"> <span class="icon-dumbbell24"></span> </div>
                                                                  <div class="icon"> <span class="icon-drugs6"></span> </div>
                                                                  <div class="icon"> <span class="icon-date6"></span> </div>
                                                                  <div class="icon"> <span class="icon-cross85"></span> </div>
                                                                  <div class="icon"> <span class="icon-chronometer20"></span> </div>
                                                                  <div class="icon"> <span class="icon-chronometer19"></span> </div>
                                                                  <div class="icon"> <span class="icon-carrot8"></span> </div>
                                                                  <div class="icon"> <span class="icon-calendar146"></span> </div>
                                                                  <div class="icon"> <span class="icon-burger10"></span> </div>
                                                                  <div class="icon"> <span class="icon-break1"></span> </div>
                                                                  <div class="icon"> <span class="icon-bike15"></span> </div>
                                                                  <div class="icon"> <span class="icon-bicycle14"></span> </div>
                                                                  <div class="icon"> <span class="icon-band13"></span> </div>
                                                                  <div class="icon"> <span class="icon-ball23"></span> </div>
                                                                  <div class="icon"> <span class="icon-volume"></span> </div>
                                                                  <div class="icon"> <span class="icon-volume-medium"></span> </div>
                                                                  <div class="icon"> <span class="icon-volume-low"></span> </div>
                                                                  <div class="icon"> <span class="icon-volume-high"></span> </div>
                                                                  <div class="icon"> <span class="icon-upload"></span> </div>
                                                                  <div class="icon"> <span class="icon-unlink"></span> </div>
                                                                  <div class="icon"> <span class="icon-undo"></span> </div>
                                                                  <div class="icon"> <span class="icon-underline"></span> </div>
                                                                  <div class="icon"> <span class="icon-text-size"></span> </div>
                                                                  <div class="icon"> <span class="icon-text-format"></span> </div>
                                                                  <div class="icon"> <span class="icon-text-format-remove"></span> </div>
                                                                  <div class="icon"> <span class="icon-text-align-right"></span> </div>
                                                                  <div class="icon"> <span class="icon-text-align-left"></span> </div>
                                                                  <div class="icon"> <span class="icon-text-align-justify"></span> </div>
                                                                  <div class="icon"> <span class="icon-text-align-center"></span> </div>
                                                                  <div class="icon"> <span class="icon-sync"></span> </div>
                                                                  <div class="icon"> <span class="icon-strikethrough"></span> </div>
                                                                  <div class="icon"> <span class="icon-star-half"></span> </div>
                                                                  <div class="icon"> <span class="icon-star-empty"></span> </div>
                                                                  <div class="icon"> <span class="icon-spell-check"></span> </div>
                                                                  <div class="icon"> <span class="icon-sort-amount-asc"></span> </div>
                                                                  <div class="icon"> <span class="icon-sort-alpha-asc"></span> </div>
                                                                  <div class="icon"> <span class="icon-select"></span> </div>
                                                                  <div class="icon"> <span class="icon-sad"></span> </div>
                                                                  <div class="icon"> <span class="icon-redo"></span> </div>
                                                                  <div class="icon"> <span class="icon-power-switch"></span> </div>
                                                                  <div class="icon"> <span class="icon-poop"></span> </div>
                                                                  <div class="icon"> <span class="icon-pointer-up"></span> </div>
                                                                  <div class="icon"> <span class="icon-pointer-right"></span> </div>
                                                                  <div class="icon"> <span class="icon-pointer-left"></span> </div>
                                                                  <div class="icon"> <span class="icon-pointer-down"></span> </div>
                                                                  <div class="icon"> <span class="icon-plus-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-pilcrow"></span> </div>
                                                                  <div class="icon"> <span class="icon-page-break"></span> </div>
                                                                  <div class="icon"> <span class="icon-neutral"></span> </div>
                                                                  <div class="icon"> <span class="icon-move"></span> </div>
                                                                  <div class="icon"> <span class="icon-menu"></span> </div>
                                                                  <div class="icon"> <span class="icon-menu-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-list"></span> </div>
                                                                  <div class="icon"> <span class="icon-link"></span> </div>
                                                                  <div class="icon"> <span class="icon-linearicons"></span> </div>
                                                                  <div class="icon"> <span class="icon-line-spacing"></span> </div>
                                                                  <div class="icon"> <span class="icon-italic"></span> </div>
                                                                  <div class="icon"> <span class="icon-indent-increase"></span> </div>
                                                                  <div class="icon"> <span class="icon-indent-decrease"></span> </div>
                                                                  <div class="icon"> <span class="icon-frame-expand"></span> </div>
                                                                  <div class="icon"> <span class="icon-frame-contract"></span> </div>
                                                                  <div class="icon"> <span class="icon-file-add"></span> </div>
                                                                  <div class="icon"> <span class="icon-exit"></span> </div>
                                                                  <div class="icon"> <span class="icon-exit-up"></span> </div>
                                                                  <div class="icon"> <span class="icon-enter"></span> </div>
                                                                  <div class="icon"> <span class="icon-enter-down"></span> </div>
                                                                  <div class="icon"> <span class="icon-download"></span> </div>
                                                                  <div class="icon"> <span class="icon-direction-rtl"></span> </div>
                                                                  <div class="icon"> <span class="icon-direction-ltr"></span> </div>
                                                                  <div class="icon"> <span class="icon-diamond"></span> </div>
                                                                  <div class="icon"> <span class="icon-cross"></span> </div>
                                                                  <div class="icon"> <span class="icon-cross-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-crop"></span> </div>
                                                                  <div class="icon"> <span class="icon-code"></span> </div>
                                                                  <div class="icon"> <span class="icon-circle-minus"></span> </div>
                                                                  <div class="icon"> <span class="icon-chevron-up"></span> </div>
                                                                  <div class="icon"> <span class="icon-chevron-up-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-chevron-right"></span> </div>
                                                                  <div class="icon"> <span class="icon-chevron-right-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-chevron-left"></span> </div>
                                                                  <div class="icon"> <span class="icon-chevron-left-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-chevron-down"></span> </div>
                                                                  <div class="icon"> <span class="icon-chevron-down-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-checkmark-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow-up"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow-up-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow-right"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow-right-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow-left"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow-left-circle"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow-down"></span> </div>
                                                                  <div class="icon"> <span class="icon-arrow-down-circle"></span> </div>
                                                                  <div class="clear"></div>
                                                                </div>
  </div>

<div class="screen_popup">
    <div class="screen_popup_body">
                                                                  <div class="screen_popup_form">
        <p>Instappy app building experience is designed for PC's if you are creating an app it is recommended that you Log in from a PC.</p>
      </div>
</div>
</div>

                                                              <div class="confirm_name"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="confirm_name_body">
                                                                  <div class="confirm_name_form">
        <p>This app name already exist.<br/>
                                                                      Please choose another name.</p>
        <input type="button" value="OK">
      </div>
                                                                </div>
  </div>
                                                              <div class="confirm_name"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="confirm_name_body">
                                                                  <div class="confirm_name_form">
        <p>This app name already exist.<br/>
                                                                      Please choose another name.</p>
        <input type="button" value="OK">
      </div>
                                                                </div>
  </div>
                                                              <div class="confirm_name more_five"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="confirm_name_body">
                                                                  <div class="confirm_name_form">
        <p>You cannot add more than five notifications.</p>
        <input type="button" value="OK">
      </div>
                                                                </div>
  </div>
                                                              <div class="image_size"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="image_size_body">
                                                                  <div class="image_size_form">
        <p>Image size should be less than 2MB.</p>
        <input type="button" value="OK">
      </div>
                                                                </div>
  </div>
                                                              <div class="image_type"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="image_type_body">
                                                                  <div class="image_type_form">
        <p>Please upload correct image format.<br>
                                                                      (.JPG, .JPEG, .PNG)</p>
        <input type="button" value="OK">
      </div>
                                                                </div>
  </div>
                                                              <div class="confirm_screen_delete"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="confirm_name_body">
                                                                  <div class="confirm_name_form">
        <p>Are you sure you want to delete this screen?<br/>
                                                                    </p>
        <input type="button" value="OK" class="confirm_delete_screen_button">
      </div>
                                                                </div>
  </div>
                                                              <div class="confirm_screen_layout"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="confirm_name_body">
                                                                  <div class="confirm_name_form">
        <p>This would delete all the work you've done till now. Are you sure you want to delete the layout?<br/>
                                                                    </p>
        <input type="button" value="Yes" class="confirm_layout_button">
        <input type="button" value="No" class="confirm_layout_button cancel">
      </div>
                                                                </div>
  </div>
                                                              <div class="delete_app"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="delete_app_body">
                                                                  <div class="delete_app_form">
        <p>This would delete your app and all the work done till now. Are you sure you want to delete the app?</p>
        <input type="button" value="Yes">
        <input type="button" value="No">
      </div>
                                                                </div>
  </div>
                                                              <div class="login_popup"> <!--<span class="close_popup"><img src="images/popup_close.png"></span>-->
    <div class="login_popup_head">
                                                                  <h2>Login</h2>
                                                                  <h2>Sign Up</h2>
                                                                  <ul class="tabs">
        <li><a href="javascript:void(0)">Login</a></li>
        <li><a href="javascript:void(0)">Sign Up</a></li>
        <div class="clear"></div>
      </ul>
                                                                </div>
    <div class="login_popup_body">
                                                                  <div class="login_tabbing">
        <div class="login_form">
                                                                      <form id="login_form" method="post">
            <input type="text" name="email"  placeholder="Email ID" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : '' ?>" id="login_email" >
            <p id="email_error"></p>
            <input type="hidden" id="login_type" value="">
            <input type="hidden" id="author_id" value="">
            <input type="hidden" id="app_id" value="">
            <input type="password"  id="login_password" name="password"  placeholder="Password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : '' ?>" >
            <div class="keep_login_check">
                                                                          <input id="remember" type="checkbox" <?php if (isset($_COOKIE['username'])) echo "checked"; ?> value="yes" name="remember" >
                                                                          <label for="remember">Keep me Logged In</label>
                                                                          <div class="clear"></div>
                                                                        </div>
            <div id="loading"></div>
            <input type="submit" value="Login" id="login">
          </form>
                                                                      <a href="javascript:void(0)" class="forgot_password">Forgot Password</a>
                                                                      <div class="clear"></div>
                                                                    </div>
        OR
        <div class="social_connect">
                                                                      <div class="facebook_login">
            <div id="fb-root"></div>
            <a href="#" onclick="fb_login();"><img src="images/facebook-login.png" border="0" alt=""></a> 
            <!--<div class="fb-login-button" data-max-rows="1" data-size="large" scope="public_profile,email" onlogin="checkLoginState();" data-show-faces="false" data-auto-logout-link="false"></div>---> 
          </div>
                                                                      <!--<div class="twitter_login">
                                                                                                                                                                                                        <div id="my-signin2"></div>
                                                                                                                                                                                                        <div class="g-signin2" data-onsuccess="onSignIn"></div>
                                                                                                                                                                                                        </div>-->
                                                                      <div class="clear"></div>
                                                                    </div>
        <small class="by_tm">By logging in you agree to our <a href="terms-of-service.php" style="color:#ffcc00">Terms and Conditions</a> & <a href="privacy-policy.php" style="color:#ffcc00">Privacy Policy</a>.</small> </div>
                                                                  <div class="login_tabbing">
        <div class="login_form">
                                                                                <form id="signup_form" method="post">
                                                                            <div class="two_input clearfix">
                                                                                <input type="text" placeholder="First Name*" id="author_first_name" maxlength="50">
                                                                                    <p id="remail_error"></p>
                                                                                    <input type="text" placeholder="Last Name*" id="author_last_name" maxlength="50">
                                                                                        </div>
                                                                                        <div class="two_input clearfix">
                                                                                            <input type="text" placeholder="Company Name" id="author_company_name" maxlength="50">
                                                                                                <input type="text" placeholder="Mobile Number*" id="author_mobile_number" minlength="10" maxlength="15">
                                                                                                    </div>
                                                                                                    <input type="text" placeholder="Email ID*" id="register_email" maxlength="50">
																									<p id="remail_error"></p>
                                                                                                        <input type="password" placeholder="Password*" id="register_password" maxlength="50">
                                                                                                            <input type="password" placeholder="Re-Enter Password*" id="register_repeat_password" maxlength="50">
                                                                                                                <select id="author_product">
																											<option value="">Select Your Product* </option>
																											<option value="content">Content Publishing App </option>
																											<option value="catalogue">Retail App </option>
                                                                                                                </select>
                                                                                                                <select id="author_product_category" ><option value="">Select App Templates*</option>
																												</select>
																<div align="center">
	
	
	
	<!-- Captcha HTML Code -->
	
	<div id="captcha-wrap" style="display:none;">
		<div class="captcha-box">
			<img src="includes/get_captcha.php" alt="" id="captcha" />
		</div>
		<div class="text-box">
			<label>Type the two words:</label>
			<input name="captcha-code" type="text" id="captcha-code">
		</div>
		<div class="captcha-action">
			<img src="images/refresh.jpg"  alt="" id="captcha-refresh" />
		</div>
	</div>
	
	<!--  Copy and Paste above html in any form and include CSS, get_captcha.php files to show the captcha  -->
	
	
</div>
                                                                                                                	
                                                                                                                
                                                                                                                <div id="register_loading"></div>
																												<?php if(isset($_GET['source']))
{
$_SESSION['source']=$_GET['source'];
}

?>
<input type="hidden" name="urlsource" value="<?php echo $_SESSION['source'];?>">
                                                                                                                <input type="submit" value="Sign Up" id="signup">
                                                                                                                    </form>
                                                                      <p> 
            <a href="javascript:void(0)" style="text-decoration:underline; font-size:13px; color:#hsl(5, 85%, 66%); display:block" id="em_opn">Resend Verification Email</a><br/> 
            <small class="by_tm">By signing up you agree to our <a href="terms-of-service.php" style="color:#ffcc00">Terms and Conditions</a> &amp; <a href="privacy-policy.php" style="color:#ffcc00">Privacy Policy</a>.</small>
          <div class="clear"></div>
                                                                    </div>
      </div>
                                                                </div>
  </div>
                                                              <div class="forgot_popup"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="forgot_popup_head">
                                                                  <h2>Forgot Password</h2>
                                                                </div>
    <div class="forgot_popup_body">
                                                                  <input type="text" id="forgot_email" placeholder="Enter Registered Email id">
                                                                  <p id="ferror_email"></p>
                                                                  <div id="loading_forgot"></div>
                                                                  <!-- <p>A mail will be sent to your email id to reset your password.</p> -->
                                                                  <input type="submit" value="Send" id="reset">
                                                                </div>
  </div>
                                                              <!--password reset popup-->
                                                              
                                                              <div class="reset_pass"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="reset_pass_head">
                                                                  <h1>Renew your Password</h1>
                                                                </div>
    <div class="reset_pass_body">
                                                                  <input type="Email" id="pass" placeholder="Email"/>
                                                                  <input type="password" id="pass" placeholder="Enter New Password"/>
                                                                  <input type="password" id="pass2" placeholder="Re-enter New Password"/>
                                                                  <p id="ferror_email"></p>
                                                                  <div id="loading_forgot"></div>
                                                                  <p style="text-align:center">Your password is been updated,</p>
                                                                  <p style="text-align:center">Please Login.</p>
                                                                  <input type="submit" value="Send" id="reset">
                                                                </div>
  </div>
                                                              <!--pass reset ends-->
                                                              <div class="em_ver" id="eml"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="em_ver_head">
                                                                  <h1>Email Verification</h1>
                                                                </div>
    <div class="em_ver_body">
                                                                  <input type="text"  placeholder="Enter email id" id="email_resend">
                                                                  <p id="resend_error"></p>
                                                                  <div id="loading_forgot"></div>
                                                                  <!-- <p>A mail will be sent to your email id for verification.</p> -->
                                                                  <div id="loading_resend"></div>
                                                                  <input type="submit" value="Send" id="reset_email">
                                                                </div>
  </div>
                                                              
                                                              <!--popup paymnt-->
                                                              
                                                              <div class="em_ver" id="pp1"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="em_ver_head">
                                                                  <h1>App Launch ASO Plans</h1>
                                                                </div>
    <div class="em_ver_body pp"> <span class="pp-hd">Please select the apps to which you want to apply</span>
                                                                  <ul class="pplist">
        <li>
                                                                      <input type="checkbox"/>
                                                                      <span>All<span> </li>
        <li>
                                                                      <input type="checkbox"/>
                                                                      <span>App Two<span> </li>
        <li>
                                                                      <input type="checkbox"/>
                                                                      <span>App Three<span> </li>
        <li>
                                                                      <input type="checkbox"/>
                                                                      <span>App Four<span> </li>
      </ul>
                                                                  <input type="submit" value="Send" id="ppbtn">
                                                                </div>
  </div>
                                                              <!--ends payment pop-->
                                                              
                                                              <div class="feedback_popup"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="theme_head restro_theme"> <a class="nav_back" style="display:block;"> <i class="fa fa-angle-left"></i></a>
                                                                  <h2>Feedback</h2>
                                                                </div>
    <div class="feedback_form" data-ss-colspan="2">
                                                                  <h2>Any suggestions/ feedback?</h2>
                                                                  <p>Your feedback and suggestions are valuable to us. Fill this feedback form and let us know how we can make your experience more awesome.</p>
                                                                  <input type="text" placeholder="Name" />
                                                                  <input type="text" placeholder="Email ID" />
                                                                  <textarea class="notEdit" placeholder="Message"></textarea>
                                                                  <input type="button" value="Send" />
                                                                </div>
  </div>
                                                              <div class="invoice_popup"> <span class="close_popup"><img src="images/popup_close.png"></span> <img src="images/inovice_popup.png"> </div>
                                                              <div class="contactus_form_popup"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="theme_head restro_theme"> <a class="nav_back" style="display:block;"> <i class="fa fa-angle-left"></i></a>
                                                                  <h2>Contact Us</h2>
                                                                </div>
    <div class="feedback_form" data-ss-colspan="2">
                                                                  <h2>Any Question ?</h2>
                                                                  <p>We&apos;re happy to answer any question you have or provide you with an estimate. Just send us a message in the form below with any questions you may have.</p>
                                                                  <input type="text" placeholder="Name" />
                                                                  <input type="text" placeholder="Email id" />
                                                                  <input type="text" placeholder="Phone No." />
                                                                  <input type="text" placeholder="Subject" />
                                                                  <textarea placeholder="Message"></textarea>
                                                                  <input type="button" value="Send" />
                                                                </div>
  </div>
                                                              <div class="reset_pass_popup"> <span class="close_popup"><img src="image/popup_close.png"></span>
    <div class="reset_pass_popup_head">
                                                                  <h1>Reset Password</h1>
                                                                </div>
    <div class="reset_pass_popup_body">
                                                                  <div class="reset_pass_form">
        <input type="password" id='old_pass' value='' placeholder="Old Password">
        <input type="password" id='new_pass' value='' placeholder="New Password">
        <input type="password" id='re_pass' value='' placeholder="Confirm New Password">
        <input type="submit" id='reset_password' value="Reset Password">
        <a href="#" class="forgot_old_password">Password Not Set / Forgot Old Password</a>
        <div class="clear"></div>
        <div id='reset_message'></div>
      </div>
                                                                </div>
  </div>
                                                              <div class="download_popup"> <span class="close_popup"><img src="images/popup_close.png"></span>
    <div class="download_popup_head">
                                                                  <h1>Trial App</h1>
                                                                </div>
    <div class="download_popup_body">
                                                                  <div class="download_form">
        <input type="text" placeholder="Enter your email id" id="download_email">
        <div id="demail_error"></div>
        <select id="download_device">
                                                                      <option value="">Select device type</option>
                                                                      <option value="1">IOS</option>
                                                                      <option value="2">Android</option>
                                                                    </select>
        <div id="download_loading"></div>
        <input type="submit" value="Send" id="send_file">
        <div class="clear"></div>
        <p>Application will expire in 30 days.</p>
      </div>
                                                                </div>
  </div>
                                                            </div>
<div class="onload_tutotial"> <span><img src="images/tutorial_close.png"></span>
                                                              <div class="first_tutorial"> <img src="images/first_tutorial.png"> </div>
                                                              <div class="second_tutorial">
    <div class="second_tutorial1"> <img src="images/second_tutorial1.png"> </div>
    <div class="second_tutorial2"> <img src="images/second_tutorial2.png"> </div>
  </div>
                                                              <div class="third_tutorial"> <img src="images/third_tutorial.png"> </div>
                                                              <div class="continue_tutorial"> <a class="next_tutorial"><img src="images/continue_tutorial.png"></a> <a class="finish_tutorial" style="display:none;"><img src="images/finish.png"></a> </div>
                                                            </div>
<section class="clear framework">
<header class="top-area"><a href="index.php"><i class="fa fa-home" style="float: left; line-height: 45px; margin-left: 10px; font-size: 18px;"></i> <img src="images/panel_logo.svg"></a>
                                                              <select id="fakeLanguage" name="drpLanguage" title="Choose Language">
    <option value='pramukhindic:assamese'>Assamese</option>
    <option value='pramukhindic:bengali'>Bengali</option>
    <option value='pramukhindic:bodo'>Bodo</option>
    <option value='pramukhindic:dogri'>Dogri</option>
    <option value='pramukhindic:gujarati'>Gujarati</option>
    <option value='pramukhindic:hindi'>Hindi</option>
    <option value='pramukhindic:kannada'>Kannada</option>
    <option value='pramukhindic:konkani'>Konkani</option>
    <option value='pramukhindic:maithili'>Maithili</option>
    <option value='pramukhindic:malayalam'>Malayalam</option>
    <option value='pramukhindic:manipuri'>Manipuri</option>
    <option value='pramukhindic:marathi'>Marathi</option>
    <option value='pramukhindic:nepali'>Nepali</option>
    <option value='pramukhindic:oriya'>Oriya</option>
    <option value='pramukhindic:punjabi'>Punjabi</option>
    <option value='pramukhindic:sanskrit'>Sanskrit</option>
    <option value='pramukhindic:santali'>Santali</option>
    <option value='pramukhindic:sindhi'>Sindhi</option>
    <option value='pramukhindic:telugu'>Telugu</option>
    <option value='pramukhindic:tamil'>Tamil</option>
    <option value=':english'>French</option>
    <option value=':english'>German</option>
    <option value=':english'>Spanish</option>
    <option value=':english'>Portugese</option>
    <option value=":english" selected="selected">English</option>
  </select>
                                                              <select id="drpLanguage"
                                                                                                                                                                                                        onchange="javascript:changeLanguage(this.options[this.selectedIndex].value);" 
                                                                                                                                                                                                        name="drpLanguage" title="Choose Language" >
    <option value='pramukhindic:assamese'>Assamese</option>
    <option value='pramukhindic:bengali'>Bengali</option>
    <option value='pramukhindic:bodo'>Bodo</option>
    <option value='pramukhindic:dogri'>Dogri</option>
    <option value='pramukhindic:gujarati'>Gujarati</option>
    <option value='pramukhindic:hindi'>Hindi</option>
    <option value='pramukhindic:kannada'>Kannada</option>
    <option value='pramukhindic:konkani'>Konkani</option>
    <option value='pramukhindic:maithili'>Maithili</option>
    <option value='pramukhindic:malayalam'>Malayalam</option>
    <option value='pramukhindic:manipuri'>Manipuri</option>
    <option value='pramukhindic:marathi'>Marathi</option>
    <option value='pramukhindic:nepali'>Nepali</option>
    <option value='pramukhindic:oriya'>Oriya</option>
    <option value='pramukhindic:punjabi'>Punjabi</option>
    <option value='pramukhindic:sanskrit'>Sanskrit</option>
    <option value='pramukhindic:santali'>Santali</option>
    <option value='pramukhindic:sindhi'>Sindhi</option>
    <option value='pramukhindic:tamil'>Tamil</option>
    <option value='pramukhindic:telugu'>Telugu</option>
    <option value=":english" selected="selected">English</option>
  </select>
                                                              <a href="javascript:;" onclick="showHelp(this);" id="cmdhelp" title="Typing help" style="display:none"></a> <img src="img/pramukhime-english.png" alt="character map" id="pramukhimecharmap" style="display:none"/>
                                                              <iframe src="" id="pramukhimehelpdetailed"  style="display:none"></iframe>
                                                              <ul id="pi_tips" style="display:none">
    <li class='smalltip'>Press F9 to switch between selected language and English.</li>
    <li class='smalltip'>Copy and paste the typed text into Microsoft Word to save it.</li>
    <li class='smalltip'>Download PramukhIME for Windows to directly type in MS Office in Windows XP/Vista/7/8.</li>
  </ul>
                                                              <textarea id="typingarea" name="typingarea" rows="5" cols="64" class="bigger" spellcheck="false" style="display:none"></textarea>
                                                              <div id="dialog" style="display:none">
    <div id="dialogheader"></div>
    <div id="pramukhimehelp">
                                                                  <div style="text-align:center;" id="pramukhimehelptypetitle">
        <input type="radio" name="helptype" value="quick" checked="checked" onclick="selectHelpType();"/>
        Quick Help
        <input type="radio" name="helptype" value="detailed" onclick="selectHelpType();"/>
        Detailed Help<br />
      </div>
                                                                  <div id="pramukhimehelpquick"> Pramukh Type Pad is used for typing easily into Indian languages. It follows 'The way you speak, the way you type' rule. 
        Following image shows which character will be shown by pressing which corresponding English letter. 
        Example for various combination of letters are given only for one character but it is true for each alphabet.<br />
        <div style="text-align:center;"> <img src="img/pramukhime-english.png" alt="character map" id="pramukhimecharmap" /> </div>
      </div>
                                                                  <iframe src="" id="pramukhimehelpdetailed"></iframe>
                                                                </div>
    <div id="dialogfooter">
                                                                  <input type="button" value="Cancel" onclick="closeDialog();" />
                                                                </div>
  </div>
                                                              
                                                              <!--email verificaion script--> 
                                                              <script type="text/javascript">
                                                                                                                                                                                                            $(document).ready(function () {
                                                                                                                                                                                                                $('#em_opn').click(function () {
                                                                                                                                                                                                                    $('.login_popup').hide();
                                                                                                                                                                                                                    $('#eml , .popup_container').show()
                                                                                                                                                                                                                })

                                                                                                                                                                                                                $('#eml .close_popup').click(function () {
                                                                                                                                                                                                                    $(this).parent().hide();
                                                                                                                                                                                                                    $('.popup_container').hide();
                                                                                                                                                                                                                    //$('.by_tm').show()
                                                                                                                                                                                                                })
                                                                                                                                                                                                            });
                                                                                                                                                                                                        </script> 
                                                            </header>
