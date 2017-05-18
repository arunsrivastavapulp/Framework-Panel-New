<?php
include ('config/db.php');
$connection = new createConnection();
$mysqli = $connection->dbConn();

session_start();
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$_SESSION['catid'] = $_GET['catid'];
$_SESSION['themeid'] = $_GET['themeid'];


if ((isset($_GET['themeid'])) && $_GET['themeid'] != '') {

    $catid = mysqli_real_escape_string($mysqli, $_GET['catid']);
    $themeid = mysqli_real_escape_string($mysqli, $_GET['themeid']);
    $useripadd = mysqli_real_escape_string($mysqli, $_SERVER['REMOTE_ADDR']);

    $realescape = "INSERT INTO `panel_tracking`(`user_ip`,`category_id`, `theme_id`,`created`) VALUES ('$useripadd','$catid','$themeid',NOW())";
    $mysqli->query($realescape);
//         printf("%d Row inserted.\n", $mysqli->affected_rows);
}

function unsetSessionVariable() {
    unset($_SESSION['appid']);
}

include('includes/header.php');
include('includes/leftbar.php');

$categoryid = $_SESSION['catid'];
$theme_id = $_SESSION['themeid'];
?>

<section class="main">

    <header>
        <ul class="select-phone">
            <li>
                <select class="select_os" id="platform" name="platform">
                    <option value="1" data-img-src="image/select_os.png">Android</option>
                    <option value="2" ata-img-src="image/select_os.png">iOS</option>
                    <option value="3" data-img-src="image/select_os.png">Windows</option>
                </select>
                <div class="clear"></div>
            </li>
            <div class="hint_content">
                <span class="open_hint">i</span>
                <p>Change Device and see how your application looks on other platform.</p>
            </div>
            </li>
        </ul>

        <div class="select-page">
            <div class="hint_content">
                <span class="open_hint">i</span>
                <p>From here you can directly go to any page you like to edit.</p>
            </div>
            <label>Page : </label>
            <select id="pageSelector">

            </select>
        </div>
        <ul class="top-aside">
            <li class="save"><a href="#">Save</a>
                <div class="hint_content">
                    <span class="open_hint">i</span>
                    <p>Don't let your hardwork go in vain.<br />Keep saving your work</p>
                </div>
            </li>
            <li><a href="publish1.html">Publish</a>
                <div class="hint_content">
                    <span class="open_hint">i</span>
                    <p>Don't let your hardwork go in vain.<br />Keep saving your work</p>
                </div>
            </li>
            <li><a href="#">Upgrade</a>
                <div class="hint_content">
                    <span class="open_hint">i</span>
                    <p>Don't let your hardwork go in vain.<br />Keep saving your work</p>
                </div>
            </li>
        </ul>
    </header>
    <section class="middle clear">
        <div class="right-area">
            <div class="pageIndexArea">
                <div class="pageIndexdiv">
                    <ul class="pageIndex">
                        <div class="hint_content">
                            <span class="open_hint">i</span>
                            <p>View the page you working on, and how many pages left for new package.</p>
                        </div>
                        <li class="first active"><span>1</span></li>
                        <li ><span>2</span></li>
                        <li><span>3</span></li>
                        <li><span>4</span></li>
                        <li><span>5</span></li>
                        <li><span>6</span></li>
                        <li><span>7</span></li>
                        <li><span>8</span></li>
                        <li><span>9</span></li>
                        <li><span>10</span></li>
                        <li><span>11</span></li>
                        <li><span>12</span></li>
                        <li><span>13</span></li>
                        <li><span>14</span></li>
                        <li class="last"><span>15</span></li>
                    </ul>
                </div>

            </div>
            <div class="mobile">




                <!-- Replacement Area Starts -->
                <div id="content-1" class="content mCustomScrollbar clear first-page " >




                    <div class="theme_head restro_theme">
                        <a class="nav_open"> <img src="image/menu_btn.png"> </a>
                        <a class="nav_back"> <i class="fa fa-angle-left"></i></a>
                        <h1>IndiEat</h1>
                        <a href="#" class="search"> <i class="fa fa-search"></i> </a>
                    </div>


                    <nav>
                        <ul>
                            <li data-link="1"><a href="#">Home</a></li>
                            <li data-link="2"><a href="#">About Us</a></li>
                            <li data-link="1"><a href="#">Menu</a></li>
                            <li data-link="1"><a href="#">Notification</a></li>
                            <li data-link="1"><a href="#">Chef &apos;s View</a></li>
                            <li data-link="s1" ><a href="#">Report</a></li>
                            <li data-link="s2" class="feedback"><a href="#">Feedback</a></li>
                        </ul>
                    </nav>
                    <div class="overlay">
                    </div>


                    <div class="banner">
                        <img src="image/restaurant_banner.jpg" class="active">
                        <img src="image/restaurant_banner.jpg">
                        <img src="image/restaurant_banner.jpg">
                        <div class="pager">
                            <div class="active"></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="clear"></div>
                    </div>




                    <div class="container droparea" style="float:left;width:100%;">


                        <div class="half_widget" data-component="7">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="half_widget_img">
                                    <img src="image/restaurant_widget1.png">
                                    <div class="clear"></div>
                                </div>
                                <div class="half_widget_text">
                                    <p>Chef's View</p>
                                </div>
                            </div>
                        </div>
                        <div class="half_widget" data-component="7">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="half_widget_img">
                                    <img src="image/restaurant_widget2.png">
                                    <div class="clear"></div>
                                </div>
                                <div class="half_widget_text">
                                    <p>Chef's View</p>
                                </div>
                            </div>
                        </div>
                        <div class="full_widget" data-ss-colspan="2">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="full_widget_img">
                                    <img src="image/restaurant_widget3.png">
                                    <div class="clear"></div>
                                </div>
                                <div class="full_widget_text">
                                    <p>Special Offers</p>
                                </div>
                            </div>
                        </div> 
                        <div class="small_widget addon">
                            <p class="widgetClose">x</p>
                            <div class="widget_inner">
                                <div class="small_widget_img">
                                    <img src="image/zomato.png">
                                </div>
                                <div class="small_widget_text">
                                    <p>Review Us</p>
                                    <p>On</p>
                                    <h2>Zomato</h2>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="contact_small_widget" data-component="30">
                            <p class="widgetClose">x</p>
                            <p class="contact_small_heading">Heading</p>
                            <p class="contact_small_subheading">Subheading</p>
                            <div class="contact_btns">
                                <a href="#" class="your_mail"><img src="image/contact_mail.png" /></a>
                                <a href="#" class="your_phone"><img src="image/contact_phone.png" /></a>
                            </div>
                        </div>
                    </div>     




                </div>

                <!-- Replacement Area Ends -->
            </div>
            <div class="choiceArea"> 
                <div class="previewEditArea">


                    <p class="preview"><i class="fa fa-search"></i> Preview</p><p class="edit active"><i class="fa fa-pencil"></i> Edit</p>
                </div>

                <div class="additional_options">
                    <span></span>
                    <div class="additional_items information">
                        <img src="image/info1.png" />
                        <div class="tooltip">Information</div>
                    </div>
                    <div class="additional_items hint">
                        <img src="image/info2.png" />
                        <div class="tooltip">Hint</div>
                    </div>
                    <div class="additional_items callus">
                        <img src="image/info3.png" />
                        <div class="tooltip">Call Us</div>
                    </div>
                    <div class="additional_items chat">
                        <img src="image/info4.png" />
                        <div class="tooltip">Chat</div>
                    </div>
                    <div class="chat_window">
                        <div class="chat_window_head">
                            <p>Chat</p>
                            <a href="#" class="close_chat"><img src="image/close_chat.png" /></a>
                            <div class="clear"></div>
                        </div>
                        <div class="chat_window_body">
                        </div>
                        <div class="chat_window_inputs">
                            <input type="text" />
                            <input type="button" />
                        </div>
                    </div>
                </div>
                <div class="clear"></div>

            </div>
        </div>
        <div class="center-area clear"><div class="mid_section" id="content-2">
                <div class="mid_section_left">
                </div>
                <div class="mid_section_right">




                    <div class="add_page">

                        <div class="mid_right_box">

                            <div class="mid_right_box_head">
                                <h1>Choose Layout :</h1>
                                <h2>Drag and drop theme on the phone</h2>
                                <span> <i class="fa fa-angle-down fa-rotate-180"></i> </span>
                            </div>
                            <div class="mid_right_box_body">
                                <div class="design_menu_box">
                                    <div class="content_label">
                                        <label>Page Name :</label>
                                    </div>
                                    <div class="content_textbox">
                                        <input type="text" id="newPageTextEdit">
                                    </div>
                                    <div class="clear"></div>
                                    <div class="add_page_themes">
                                        <div class="new_theme" data-layout="1">
                                            <img src="image/add_theme6.png">
                                        </div>
                                        <div class="new_theme" data-layout="2">
                                            <img src="image/add_theme1.png">
                                        </div>
                                        <div class="new_theme" data-layout="3">
                                            <img src="image/add_theme2.png">
                                        </div>
                                        <div class="new_theme" data-layout="4">
                                            <img src="image/add_theme3.png">
                                        </div>
                                        <div class="new_theme" data-layout="5">
                                            <img src="image/add_theme4.png">
                                        </div>
                                        <div class="new_theme" data-layout="6">
                                            <img src="image/add_theme5.png">
                                        </div>
                                        <div class="new_theme" data-layout="7">
                                            <img src="image/add_theme7.png">
                                        </div>
                                        <div class="new_theme" data-layout="8">
                                            <img src="image/add_theme8.png">
                                        </div>
                                        <div class="new_theme" data-layout="9">
                                            <img src="image/add_theme9.png">
                                        </div>
                                        <div class="new_theme" data-layout="10">
                                            <img src="image/add_theme10.png">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <a href="#" class="make_app_next">next</a>
                            </div>


                            <div class="clear"></div>
                        </div>

                    </div>

                    <div class="mid_right_box active">
                        <div class="mid_right_box_head">
                            <h1>Choose App Name:</h1>
                            <h2>Choose an unique name for your app.</h2>
                            <span> <i class="fa fa-angle-down fa-rotate-180"></i> </span>
                        </div>
                        <div class="mid_right_box_body">
                            <p>Lorem ipsum dolor sit amet, at duo populo nostrum, mel an tibique postulant. No vis congue salutatus, dicat legere intellegat quo no. In graece <a href="#">Read More...</a></p>
                            <div class="app_name">
                                <div class="app_name_label">
                                    <label>Application Name :</label>
                                </div>
                                <div class="app_name_textbox">
                                    <input type="text" name="appName" id="appName">
                                    <input type="hidden" name="appid" id="appid" value>
                                    <!--<input type="hidden" name="appid1" id="appid1" value="<?php // if (isset($_SESSION['appid'])) { echo $_SESSION['appid'];};   ?>">-->
                                    <span>Please select a unique name for your application.</span>
                                </div>
                            </div>
                            <a href="javascript:void(0)" id="appNameCheck" class="make_app_next">Next</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="mid_right_box">

                        <div class="mid_right_box_head ">
                            <h1>App Details :</h1>
                            <h2>Full page properties</h2>
                            <span> <i class="fa fa-angle-down"></i> </span>
                        </div>

                        <div class="mid_right_box_body GeneralDetails">
                            <div class="addPage">Add Page</div>
                            <div class="design_menu_box Page">

                                <h2>Page Background :</h2>
                                <div class="background_label">
                                    <label>Choose Background Color:</label>
                                </div>
                                <div class="background_colorbox">
                                    <span id="pagePicker"></span>
                                </div>

                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box actionBar">
                                <h2>Action Bar :</h2>
                                <div class="background_label">
                                    <label>Choose Background Color:</label>
                                </div>
                                <div class="background_colorbox">
                                    <span class="actionBarPicker"></span>
                                </div>
                                <div class="content_label">
                                    <label>Heading Text:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text">
                                    <select class="content_font">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select>
                                    <a href="#"><b class="content_bold">B</b></a>
                                    <a href="#"><i class="content_italics">I</i></a>
                                    <a href="#"><u class="content_underline">U</u></a>
                                    <span id="actionBarTextPicker"></span>
                                </div>
                            </div>
                            <div class="design_menu_box navItemEdit">
                                <h2>Action Bar Menu:</h2>
                                <div class="menu_links">
                                    <ul class="menu_head">
                                        <li>Icon</li>
                                        <li>Name</li>
                                        <li>Link</li>
                                        <li></li>
                                    </ul>
                                    <ul class="menu_body_links nav_items_edit">

                                        <li><a href="#"><img src="images/menu_img.jpg"></a></li>
                                        <li><input type="text" class="nav_item"></li>
                                        <li>
                                            <select class="linkSelector">
                                                <option value="0">Select</option>
                                            </select>
                                        </li>
                                        <li class="close"><a><img src="images/menu_delete.png"></a></li>

                                    </ul>


                                </div>
                                <div class="add_list">
                                    <a>Add List</a>
                                </div>
                                <div class="feedback report">
                                    <ul>
                                        <li><a href="#"><img src="images/menu_img.jpg"></a></li>
                                        <li><input type="text" value="Report" /></li>
                                        <li><input type="text" value="Report Popup" /></li>
                                        <li></li>
                                    </ul>
                                </div>
                                <div class="feedback">
                                    <div class="select_feedback">
                                        <input type="checkbox" class="select_feedback" id="feedback" />
                                        <label for="feedback"></label>
                                    </div>
                                    <ul>
                                        <li><a href="#"><img src="images/menu_img.jpg"></a></li>
                                        <li><input type="text" value="Feedback" /></li>
                                        <li><input type="text" placeholder="Email Id" /></li>
                                        <li class="view_feedback"><a href="#"><img src="image/feedback_view.png"></a></li>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <a href="#" class="make_app_next">next</a>
                        </div>


                        <div class="clear"></div>
                    </div>

                    <div class="mid_right_box">

                        <div class="mid_right_box_head">
                            <h1>Choose Card:</h1>
                            <h2>Drag and drop widgets on the Phone.</h2>
                            <span> <i class="fa fa-angle-down"></i> </span>
                        </div>


                        <div class="mid_right_box_body">
                            <div class="utility_api_cards">
                                <h2>Utility cards</h2>
                                <div class="utility_api_content clones container">

                                    <!-- Replacement Area Starts -->
                                    <div class="half_widget layout1" data-component="7">
                                        <p class="widgetClose">x</p>
                                        <div class="half_widget_img">
                                            <img src="image/widget_img.jpg">

                                            <div class="clear"></div>
                                        </div>
                                        <div class="half_widget_text">
                                            <p>Heading</p>
                                        </div>
                                    </div>

                                    <div class="full_widget" data-component="7" data-ss-colspan="2">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="image/widget_full_img.jpg">                                          
                                            <div class="clear"></div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p>Text</p>
                                        </div>
                                    </div>


                                    <div class="full_widget layout6" data-component="26" data-ss-colspan="2">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="image/widget_full_map_img.jpg">
                                            <div class="clear"></div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p>Heading</p>
                                        </div>
                                    </div>


                                    <div class="half_widget no_text" data-component="19">
                                        <p class="widgetClose">x</p>
                                        <div class="half_widget_img">
                                            <img src="image/widget_img.jpg">
                                            <div class="clear"></div>
                                        </div>
                                        <div class="half_widget_text">
                                            <a href="#"><img src="image/widget_share.png" /></a>
                                            <a href="#"><img src="image/widget_heart.png" /></a>
                                        </div>
                                    </div>
                                    <div class="full_widget" data-ss-colspan="2" data-component="2">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="image/widget_full_img.jpg">
                                            <div class="clear"></div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p>Heading</p>
                                            <a href="#"><img src="image/widget_heart_yellow.png" /></a>
                                        </div>
                                    </div>
                                    <div class="full_widget head_cont layout4" data-ss-colspan="2" data-component="19">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="image/widget_full_img.jpg">
                                            <div class="clear"></div>
                                            <div class="full_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <p class="img_content">Content</p>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="full_widget_text">
                                            <a href="#"><img src="image/widget_share.png" /></a>
                                            <a href="#"><img src="image/widget_heart.png" /></a>
                                        </div>
                                    </div>
                                    <div class="full_widget long_text layout7" data-ss-colspan="2" data-component="13">
                                        <p class="widgetClose">x</p>
                                        <div class="full_widget_img">
                                            <img src="image/widget_full_img.jpg">
                                            <div class="clear"></div>
                                            <div class="full_widget_img_text">
                                                <p class="img_heading">Heading1</p>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="full_widget_text">
                                            <p class="long_text_subheading">Sub Heading</p>
                                            <p class="long_text_content">Content</p>
                                            <a href="#"><img src="image/widget_share_small.png" /></a>
                                            <a href="#"><img src="image/widget_heart_small.png" /></a>
                                        </div>
                                    </div>

                                    <div class="big_widget layout2 layout 3 layout6" data-ss-colspan="2" data-component="14">
                                        <p class="widgetClose">x</p>
                                        <div class="big_widget_img">
                                            <img src="image/widget_big_img.jpg">
                                            <div class="clear"></div>
                                            <div class="big_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="big_widget_img_controls">

                                                <a href="#" class="share"><img src="image/share.png" /></a>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="big_widget_text layout2" data-ss-colspan="2" data-component="14">
                                        <p class="widgetClose">x</p>
                                        <div class="big_widget_img">
                                            <img src="image/widget_big_img.jpg">
                                            <div class="clear"></div>
                                            <div class="big_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="big_widget_img_controls">

                                                <a href="#" class="share"><img src="image/share.png" /></a>
                                                <a href="#" class="share"><img src="image/heart.png" /></a>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="big_widget_bottom_text">
                                            <p class="text_heading">Heading</p>
                                            <p class="text_subheading">Subheading</p>
                                            <p class="text_content">content</p>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="big_widget_text about layout3" data-ss-colspan="2" data-component="14">
                                        <p class="widgetClose">x</p>
                                        <div class="big_widget_img">
                                            <img src="image/widget_big_img.jpg">
                                            <div class="clear"></div>
                                            <div class="big_widget_img_text">
                                                <p class="img_heading">Heading</p>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="big_widget_img_controls">

                                                <a href="#" class="share"><img src="image/share.png" /></a>
                                                <a href="#" class="share"><img src="image/heart.png" /></a>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="big_widget_bottom_text">
                                            <p class="about">About Us</p>
                                            <div class="clear"></div>
                                        </div>
                                    </div>

                                    <div class="item_full_widget layout5" data-ss-colspan="2" data-component="9">
                                        <p class="widgetClose">x</p>
                                        <div class="item_full_widget_left">
                                            <img src="image/item_add_img.png" />
                                        </div>
                                        <div class="item_full_widget_right">
                                            <p class="item_heading">Heading</p>
                                            <p class="item_content">content</p>
                                            <div class="clear"></div>
                                            <p class="item_disc">Description</p>
                                        </div>
                                    </div>
                                    <div class="contact_widget layout2 layout3 layout6" data-ss-colspan="2" data-component="5">
                                        <p class="widgetClose">x</p>
                                        <p class="contactus">Heading</p>
                                        <p class="address">Content</p>
                                        <p class="phone">Text</p>
                                    </div>
                                    <div class="contact_small_widget layout1" data-component="30">
                                        <p class="widgetClose">x</p>
                                        <p class="contact_small_heading">Heading</p>
                                        <p class="contact_small_subheading">Subheading</p>
                                        <div class="contact_btns">
                                            <a href="#" class="your_mail"><img src="image/contact_mail.png" /></a>
                                            <a href="#" class="your_phone"><img src="image/contact_phone.png" /></a>
                                        </div>
                                    </div>
                                    <div class="head_disc_widget layout2 layout3 layout6" data-ss-colspan="2" data-component="5">
                                        <p class="widgetClose">x</p>
                                        <div class="head_disc_content">
                                            <h2>Heading</h2>
                                            <p>Add Description</p>
                                        </div> 
                                    </div>
                                    <div class="tabbing_widget layout2" data-ss-colspan="2" data-component="15">
                                        <p class="widgetClose">x</p>
                                        <div class="tabbing_widget_head">
                                            <ul class="tabs">
                                                <li><a>Tab 1</a></li>
                                                <li><a>Tab 2</a></li>
                                                <li><a>Tab 3</a></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                        <div class="tabbing_widget_body">
                                            <div class="tab_content">
                                                <p>Add Description1</p>
                                            </div>
                                            <div class="tab_content">
                                                <p>Add Description2</p>
                                            </div>
                                            <div class="tab_content">
                                                <p>Add Description3</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Replacement Area Ends -->

                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="utility_api_cards">
                                <h2>API cards</h2>
                                <div class="utility_api_content clones container">
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/facebook.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Facebook</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>     
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/twitter.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Twitter</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/picasa.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Picasa</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/grubhub.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>GrubHub</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/quora.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Quora</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/google.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Google<sup>+</sup></p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/tastykhana.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Tasty Khana</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/flickr.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Flickr</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/foodpanda.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Food Panda</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>  
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/instagram.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Instagram</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/zomato.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Zomato</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="small_widget layout1" data-component="3">
                                        <p class="widgetClose">x</p>
                                        <a href="#">
                                            <div class="small_widget_img">
                                                <img src="image/pinterest.png">
                                            </div>
                                            <div class="small_widget_text">
                                                <p>Pinterest</p>
                                            </div>
                                            <div class="clear"></div>
                                        </a>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mid_right_box change_properties">

                        <div class="mid_right_box_head selectedWidget">
                            <h1>Change Card Properties :</h1> 
                            <h2>Change Selected card properties.</h2>
                            <span> <i class="fa fa-angle-down"></i> </span>
                        </div>
                        <div class="mid_right_box_body">

                            <div class="design_menu_box bannerEdit">

                                <h2>Background </h2>

                                <div class="change_image">
                                    <input type="file" id="editbrowse_img1">
                                    <img src="image/browse_full_img.jpg" class="bannereditbrowse">
                                    <span>Image 1</span>
                                </div>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img2">
                                    <img src="image/browse_full_img.jpg" class="bannereditbrowse">
                                    <span>Image 2</span>
                                </div>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img3">
                                    <img src="image/browse_full_img.jpg" class="bannereditbrowse">
                                    <span>Image 3</span>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <div class="design_menu_box widgetEdit fullItemEdit">

                                <h2>Background </h2>
                                <div class="change_image">
                                    <input type="file" id="editbrowse_img">
                                    <img src="image/browse_full_img.jpg" class="editbrowse">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box mapwidgetEdit">
                                <h2>Location Details</h2>
                                <div class="content_label">
                                    <label>Latitude:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="latitude_edit" maxlength="20">
                                </div>
                                <div class="content_label">
                                    <label>Longitude:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="longitude_edit" maxlength="20">
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box widgetEdit mapwidgetEdit">
                                <h2>Content</h2>
                                <div class="content_label">
                                    <label>Change Text:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit" maxlength="14">
                                    <select class="content_font">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold">B</b></a>
                                    <a href="#"><i class="content_italics">I</i></a>
                                    <a href="#"><u class="content_underline">U</u></a>
                                    <span class="editPicker"></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box headContWidgetEdit">
                                <h2>Content</h2>
                                <div class="content_label">
                                    <label>Edit Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit1" maxlength="15">
                                    <select class="content_font1">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize1">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold1">B</b></a>
                                    <a href="#"><i class="content_italics1">I</i></a>
                                    <a href="#"><u class="content_underline1">U</u></a>
                                    <span class="editPicker6"></span>
                                </div>
                                <div class="content_label">
                                    <label>Edit Content:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="content_textbox_edit2" maxlength="15">
                                    <select class="content_font2">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize2">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold2">B</b></a>
                                    <a href="#"><i class="content_italics2">I</i></a>
                                    <a href="#"><u class="content_underline2">U</u></a>
                                    <span class="editPicker7"></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="design_menu_box smallWidget">
                                <h2>Edit API Card</h2>
                                <div class="content_label">
                                    <label>Enter Link:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="apiUrl">
                                </div>
                            </div>
                            <div class="design_menu_box contactWidget">
                                <h2>Contact Edit</h2>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="contactPicker"></span>
                                </div>

                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="heading" maxlength="10">
                                    <select class="content_font7">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize7">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold7">B</b></a>
                                    <a href="#"><i class="content_italics7">I</i></a>
                                    <a href="#"><u class="content_underline7">U</u></a>
                                    <span class="editPicker8"></span>
                                </div>
                                <div class="content_label">
                                    <label>Sub Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="subHeading1" maxlength="10">
                                    <select class="content_font8">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize8">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold8">B</b></a>
                                    <a href="#"><i class="content_italics8">I</i></a>
                                    <a href="#"><u class="content_underline8">U</u></a>
                                    <span class="editPicker9"></span>
                                </div>
                                <div class="content_label">
                                    <label>Sub Heading:</label>
                                </div>
                                <div class="content_textbox" >
                                    <input type="text" class="subHeading2" maxlength="22">
                                    <select class="content_font9">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize9">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold9">B</b></a>
                                    <a href="#"><i class="content_italics9">I</i></a>
                                    <a href="#"><u class="content_underline9">U</u></a>
                                    <span class="editPicker10"></span>
                                </div>
                            </div>


                            <div class="design_menu_box contact1Widget">
                                <h2>Contact Edit</h2>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="contactPicker"></span>
                                </div>

                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="heading" maxlength="25">
                                    <select class="content_font10">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize10">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold10">B</b></a>
                                    <a href="#"><i class="content_italics10">I</i></a>
                                    <a href="#"><u class="content_underline10">U</u></a>
                                    <span class="editPicker11"></span>
                                </div>
                                <div class="content_label">
                                    <label>Sub Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="subHeading1">
                                    <select class="content_font11">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize11">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold11">B</b></a>
                                    <a href="#"><i class="content_italics11">I</i></a>
                                    <a href="#"><u class="content_underline11">U</u></a>
                                    <span class="editPicker12"></span>
                                </div>
                                <div class="content_label">
                                    <label>Sub Heading:</label>
                                </div>
                                <div class="content_textbox" >
                                    <input type="text" class="subHeading2" maxlength="28">
                                    <select class="content_font12">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize12">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold12">B</b></a>
                                    <a href="#"><i class="content_italics12">I</i></a>
                                    <a href="#"><u class="content_underline12">U</u></a>
                                    <span class="editPicker13"></span>
                                </div>
                            </div>
                            <div class="design_menu_box contactSmallWidget">
                                <h2>Contact Edit</h2>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="contactPicker"></span>
                                </div>

                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="heading" maxlength="13">
                                    <select class="content_font13">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize13">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold13">B</b></a>
                                    <a href="#"><i class="content_italics13">I</i></a>
                                    <a href="#"><u class="content_underline13">U</u></a>
                                    <span class="editPicker14"></span>
                                </div>
                                <div class="content_label">
                                    <label>Sub Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="subHeading1" maxlength="15">
                                    <select class="content_font14">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize14">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold14">B</b></a>
                                    <a href="#"><i class="content_italics14">I</i></a>
                                    <a href="#"><u class="content_underline14">U</u></a>
                                    <span class="editPicker15"></span>
                                </div>
                                <div class="content_label">
                                    <label>Your Phone:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="enter_phone">
                                </div>
                                <div class="content_label">
                                    <label>Your E-mail:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="enter_email">
                                </div>

                            </div>
                            <div class="design_menu_box headDiscBigWidget">
                                <h2>Contact Edit</h2>
                                <div class="content_label">
                                    <label>Background:</label>
                                </div>
                                <div class="content_textbox">
                                    <span class="contactPicker"></span>
                                </div>

                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="heading" maxlength="25">
                                    <select class="content_font15">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize15">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold15">B</b></a>
                                    <a href="#"><i class="content_italics15">I</i></a>
                                    <a href="#"><u class="content_underline15">U</u></a>
                                    <span class="editPicker16"></span>
                                </div>
                                <div class="content_label">
                                    <label>Sub Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="subHeading1">
                                    <select class="content_font16">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize16">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold16">B</b></a>
                                    <a href="#"><i class="content_italics16">I</i></a>
                                    <a href="#"><u class="content_underline16">U</u></a>
                                    <span class="editPicker17"></span>
                                </div>
                            </div>




                            <div class="design_menu_box tabEdit">
                                <h2>Tab Head</h2>
                                <div class="content_label">
                                    <label>Background</label>
                                </div>
                                <div class="content_textbox">
                                    <span id="tabHeadBackGroundEdit"></span>
                                </div>
                                <div class="content_label">
                                    <label>Change Text:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="tabHead" maxlength="11">
                                    <select class="content_font">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold">B</b></a>
                                    <a href="#"><i class="content_italics">I</i></a>
                                    <a href="#"><u class="content_underline">U</u></a>
                                    <span class="editPicker"></span>
                                </div>
                                <div style="margin-top:35px;margin-bottom:35px;border-top:2px solid #efefef"></div>
                                <h2>Tab Content</h2>
                                <div class="content_label">
                                    <label>Background</label>
                                </div>
                                <div class="content_textbox">
                                    <span id="tabContentBackGroundEdit"></span>
                                </div>
                                <div class="content_label">
                                    <label>Change Text:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="tabContent">
                                    <select class="tabcontent_font">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="tabcontent_fontsize">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="tabcontent_bold">B</b></a>
                                    <a href="#"><i class="tabcontent_italics">I</i></a>
                                    <a href="#"><u class="tabcontent_underline">U</u></a>
                                    <span class="tabeditPicker"></span>
                                </div>
                            </div>

                            <div class="design_menu_box bigWidgetEdit">
                                <h2>Big Widget</h2>
                                <div class="change_image">
                                    <input type="file" id="bigWidget_img">
                                    <img src="image/browse_full_img.jpg" class="bigWidgetbrowse">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_widget_head" maxlength="25">
                                    <select class="content_font">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold">B</b></a>
                                    <a href="#"><i class="content_italics">I</i></a>
                                    <a href="#"><u class="content_underline">U</u></a>
                                    <span class="editPicker"></span>
                                </div>
                            </div>

                            <div class="design_menu_box bigWidgetTextEdit">
                                <h2>Big Widget</h2>
                                <div class="change_image">
                                    <input type="file" id="bigWidgetText_img">
                                    <img src="image/browse_full_img.jpg" class="bigWidgetTextbrowse">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label">
                                    <label>Heading1:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head1" maxlength="28">
                                    <select class="content_font1">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize1">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold1">B</b></a>
                                    <a href="#"><i class="content_italics1">I</i></a>
                                    <a href="#"><u class="content_underline1">U</u></a>
                                    <span class="editPicker1"></span>
                                </div>
                                <div class="content_label">
                                    <label>Heading2:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head2" maxlength="28">
                                    <select class="content_font3">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize3">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold3">B</b></a>
                                    <a href="#"><i class="content_italics3">I</i></a>
                                    <a href="#"><u class="content_underline3">U</u></a>
                                    <span class="editPicker2"></span>
                                </div>
                                <div class="content_label">
                                    <label>Subheading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head3" maxlength="14">
                                    <select class="content_font4">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize4">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold4">B</b></a>
                                    <a href="#"><i class="content_italics4">I</i></a>
                                    <a href="#"><u class="content_underline4">U</u></a>
                                    <span class="editPicker3"></span>
                                </div>
                                <div class="content_label">
                                    <label>content:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head4" maxlength="14">
                                    <select class="content_font5">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize5">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold5">B</b></a>
                                    <a href="#"><i class="content_italics5">I</i></a>
                                    <a href="#"><u class="content_underline5">U</u></a>
                                    <span class="editPicker4"></span>
                                </div>
                            </div>

                            <div class="design_menu_box fullWidgetLongText">
                                <h2>Full Widget</h2>
                                <div class="change_image">
                                    <input type="file" id="fullLongWidget_img">
                                    <img src="image/browse_full_img.jpg" class="fullLongWidgetbrowse">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head1" maxlength="25">
                                    <select class="content_font1">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize1">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold1">B</b></a>
                                    <a href="#"><i class="content_italics1">I</i></a>
                                    <a href="#"><u class="content_underline1">U</u></a>
                                    <span class="editPicker18"></span>
                                </div>

                                <div class="content_label">
                                    <label>Subheading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head6" maxlength="25">
                                    <select class="content_font17">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize17">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold17">B</b></a>
                                    <a href="#"><i class="content_italics17">I</i></a>
                                    <a href="#"><u class="content_underline17">U</u></a>
                                    <span class="editPicker19"></span>
                                </div>
                                <div class="content_label">
                                    <label>content:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head7">
                                    <select class="content_font18">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize18">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold18">B</b></a>
                                    <a href="#"><i class="content_italics18">I</i></a>
                                    <a href="#"><u class="content_underline18">U</u></a>
                                    <span class="editPicker20g"></span>
                                </div>
                            </div>

                            <div class="design_menu_box bigWidgetAboutEdit">
                                <h2>Big Widget</h2>
                                <div class="change_image">
                                    <input type="file" id="bigWidgetAbout_img">
                                    <img src="image/browse_full_img.jpg" class="bigWidgetAboutbrowse">
                                    <span>Image</span>
                                </div>
                                <div class="clear"></div>
                                <div class="content_label">
                                    <label>Heading:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head1" maxlength="25">
                                    <select class="content_font1">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize1">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold1">B</b></a>
                                    <a href="#"><i class="content_italics1">I</i></a>
                                    <a href="#"><u class="content_underline1">U</u></a>
                                    <span class="editPicker1"></span>
                                </div>
                                <div class="content_label">
                                    <label>About Us:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" class="big_text_head5" maxlength="31">
                                    <select class="content_font6">
                                        <option>Agency FB</option>
                                        <option>Antiqua</option>
                                        <option>Architect</option>
                                        <option>Arial</option>
                                        <option>BankFuturistic</option>
                                        <option>BankGothic</option>
                                        <option>Blackletter</option>
                                    </select>
                                    <select class="content_fontsize6">
                                        <option>12</option>
                                        <option>14</option>
                                        <option>16</option>
                                        <option>18</option>
                                        <option>20</option>
                                        <option>22</option>
                                        <option>24</option>
                                    </select >
                                    <a href="#"><b class="content_bold6">B</b></a>
                                    <a href="#"><i class="content_italics6">I</i></a>
                                    <a href="#"><u class="content_underline6">U</u></a>
                                    <span class="editPicker5"></span>
                                </div>
                            </div>

                            <div class="design_menu_box videoEdit">
                                <h2>Video URL</h2>
                                <div class="content_label">
                                    <label>Video URL:</label>
                                </div>
                                <div class="content_textbox">
                                    <input type="text" placeholder="Enter Video URL" class="video_url">
                                </div>
                            </div>

                            <div class="design_menu_box widgetLinkEdit">
                                <h2>Link Edit </h2>

                                <div class="background_label">
                                    <label>Choose Link:</label>
                                </div>
                                <div class="background_colorbox">
                                    <select class="widgetlinkSelector">
                                        <option value="0">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>

                </div>
                <div class="clear"></div>
            </div></div>
        <div class="hint_main">?</div>

    </section>
</section>
</section>
<button class="confirm" type="button"></button>
<script></script> 
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script>
    (function ($) {
        $(window).load(function () {
            $("#content-1").mCustomScrollbar();
            $("#content-2").mCustomScrollbar();
            //$(".skiptranslate.goog-te-gadget").contents().get(1).nodeValue="";
        });
    })(jQuery);
    $(document).ready(function () {

        $("#appNameCheck").click(function () {
            if ($("#appName").val() != '') {
                var platform = $("#platform option:selected").val();
                var appName = $("#appName").val();
                var appidCheck = $('#appid').val();
                var param = {'platform': platform, 'appName': appName};

                var form_data = {
                    data: param, //your data being sent with ajax
                    token: '<?php echo $token; ?>', //used token here.
                    themeid: '<?php echo $theme_id; ?>',
                    catid: '<?php echo $categoryid; ?>',
                    confirm: 'Yes',
                    hasid: appidCheck,
                    is_ajax: 1
                };

                $.ajax({
                    type: "POST",
                    url: '/FRAMEWORK/modules/checkappname/checkappname.php',
                    data: form_data,
                    success: function (response)
                    {
//                    alert(response);
                        if (response == 1) {
                            $(".confirm_name").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                        } else if (response != 1) {
                            $('#appid').val(response);
//                            alert('check val');
                        }
                    },
                    error: function () {
                        console.log("error in ajax call");
                        alert("error in ajax call");
                    }
                });

            } else {
                alert('please fill app name');
                return false;
            }
        });

        $(".confirm_name input").click(function () {
            if ($("#appName").val() != '') {
                var confirmVal2 = $(this).val();
                var appidCheck = $('#appid').val();
                $(".confirm_name").css({'display': 'none'});
                $(".popup_container").css({'display': 'none'});
//alert(confirmVal2);
//return false;
                if (confirmVal2 == "Yes") {
                    var platform = $("#platform option:selected").val();
                    var appName = $("#appName").val();

                    var param = {'platform': platform, 'appName': appName};

                    var form_data = {
                        data: param, //your data being sent with ajax
                        token: '<?php echo $token; ?>', //used token here.
                        themeid: '<?php echo $theme_id; ?>',
                        catid: '<?php echo $categoryid; ?>',
                        hasid: appidCheck,
                        confirm: confirmVal2,
                        is_ajax: 2
                    };

                    $.ajax({
                        type: "POST",
                        url: '/FRAMEWORK/modules/checkappname/checkappname.php',
                        data: form_data,
                        success: function (response)
                        {
//                        alert(response);
                            if (response == 1) {
                                $(".confirm_name").css({'display': 'block'});
                                $(".popup_container").css({'display': 'block'});
                            } else if (response != 1) {
                                $('#appid').val(response);
//                            alert('check val');
                            }
                        },
                        error: function () {
                            console.log("error in ajax call");
                            alert("error in ajax call");
                        }
                    });
                }
            } else {
                alert('please fill app name');
                return false;
            }
        });

    });
</script>
<script src="js/chosen.jquery.js"></script>
<script src="js/ImageSelect.jquery.js"></script>
<script>
    $(".select_os").chosen();
</script>

</body>
</html>
