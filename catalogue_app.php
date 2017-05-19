<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
$app_id = isset($_GET['appid']) ? $_GET['appid'] : '';
$themeid = isset($_GET['themeid']) ? $_GET['themeid'] : '';
$catid = isset($_GET['catid']) ? $_GET['catid'] : '';
require_once('modules/opencart-integration/opencart-integration.php');
$obj = new Opencart();

if ($themeid) {
    $html = $obj->get_cuurent_app_html($themeid);
}
if ($app_id) {
    $author = $obj->check_user($_SESSION['username'], $_SESSION['custid']);
    $app_data = $obj->edit_catalogue_app($app_id);
    $html = $obj->get_cuurent_app_html($app_data['theme']);
//if($author['id']!=$app_data['author_id']){echo"<script>alert('Invalid User');window.location='myapps1.php'</script>";}
}

$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$preview = isset($_GET['page']) ? $_GET['page'] : '';
$_SESSION['catid'] = $catid;
$_SESSION['themeid'] = $themeid;
$_SESSION['currentpagevisit'] = 'catalogue.php';
?>
<link href="css/cropper.css" rel="stylesheet">
<link href="css/main.css" rel="stylesheet">
<style type="text/css">
    .modal-open{overflow:hidden}
    .modal{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1050;display:none;overflow:hidden;-webkit-overflow-scrolling:touch;outline:0}
    .modal.fade .modal-dialog{-webkit-transition:-webkit-transform .3s ease-out;-o-transition:-o-transform .3s ease-out; transition:transform .3s ease-out;-webkit-transform:translate(0,-25%);-ms-transform:translate(0,-25%);-o-transform:translate(0,-25%);transform:translate(0,-25%)}
    .modal.in .modal-dialog{-webkit-transform:translate(0,0);-ms-transform:translate(0,0); -o-transform:translate(0,0);transform:translate(0,0)}
    .modal-open .modal{overflow-x:hidden;overflow-y:auto}
/*    .modal-dialog{position:relative;width:68%;margin:0 auto;} */
    .modal-dialog{ position:relative; margin:0 auto; top: 30px; width: 58%; }
    .modal-content{position:relative;background-color:#fff;-webkit-background-clip:padding-box;background-clip:padding-box;border:1px solid #999;border:1px solid rgba(0,0,0,.2);border-radius:0px;outline:0;-webkit-box-shadow:0 3px 9px rgba(0,0,0,.5);box-shadow:0 3px 9px rgba(0,0,0,.5)}
    .modal-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1040;background-color:#000}
    .modal-backdrop.fade{filter:alpha(opacity=0);opacity:0}
    .modal-backdrop.in{filter:alpha(opacity=50);opacity:.5}
/*    .modal-header{min-height:16.43px;padding:15px;border-bottom:1px solid #e5e5e5}*/
    .modal-header .close{margin-top:-2px}
/*    .modal-title{margin:0;line-height:1.42857143}*/
    #bootstrap-modal-label { margin: 0; padding: 10px; background: #f4f4f4; }
    ul.modal-title { font-size: 12px; font-weight: normal; padding: 10px; color: #777; }
    ul.modal-title .resolution { font-weight: bold; }
    .upload-btn { position: relative; float: right; overflow: hidden; margin: 10px; margin-top: 20px; }
    .upload-btn .make_app_next { margin: 0; }
    .upload-btn #inputImage { position: absolute; top: 0; right: 0; margin: 0; padding: 0; font-size: 20px; cursor: pointer; opacity: 0; filter: alpha(opacity=0); }
/*    .modal-body{position:relative;padding:15px}*/
    .modal-body{ position:relative; overflow: hidden; }
    .modal-body .img-container .crop-controls { position: absolute; bottom: 2%; left: 50%; z-index: 99; display: none; }
    .modal-body .img-container img:not([src=""]) + .crop-controls { display: block; }
    .modal-body .img-container .crop-controls .zoom-controls { cursor: pointer; padding: 5px 10px; }
/*    .modal-footer{padding:15px;text-align:right;border-top:1px solid #e5e5e5}*/
    .modal-footer { padding: 15px 10px; text-align: right; }
    .modal-footer .btn+.btn{margin-bottom:0;margin-left:5px}
    .modal-footer .btn-group .btn+.btn{margin-left:-1px}
    .modal-footer .btn-block+.btn-block{margin-left:0}
    .modal-scrollbar-measure{position:absolute;top:-9999px;width:50px;height:50px;overflow:scroll}
    .alert-dismissable .close,.alert-dismissible .close{position:relative;top:-2px;right:-21px;color:inherit}
    .close{float:right;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2}
    .close:focus,.close:hover{color:#000;text-decoration:none;cursor:pointer;filter:alpha(opacity=50);opacity:.5}
    .close{-webkit-appearance:none;padding:0;cursor:pointer;background:0 0;border:0}
    .bannercropdiv .img-container .cropper-crop-box{
        height: 400px !important;
    }
    .bannercropdiv .company-img-container .cropper-crop-box{
        height: 400px !important;
    }
    
    .img-container {
        background-image: url('images/placeholder-img.png');
        background-repeat: no-repeat;
        background-position: center;
        background-color: #f4f4f4;
        min-height: 375px;
        overflow: hidden;
        margin: 0;
    }

    .bannercropdiv .modal-dialog {
        width: 70% !important;
        margin: 10px auto;
    }

    a.make_app_next2 {
        text-transform: uppercase;
        text-decoration: none;
        font-weight: 300;
        float: right;
        margin-top: 35px;
        padding: 5px 10px;
        font-size: 14px;
        background: #ffcc00;
        color: #FFF;
    }
    .companylogoeditbrowse{ width: 139px; }
    .site-tutorial{
        display: block !important;
    }
    select#fakeLanguage{
        display: block !important;
    }
</style>


<section class="main">
    <header>
        <ul class="top-aside">
            <li>
                <a href="index.php?id=createNew">Create New <i class="fa fa-rocket"></i></a>
            </li>
            <li class="save">
                <a href="#" id="save_catalogue_app">Save <i class="fa fa-cloud-upload"></i></a>
            </li>
            <li>
                <a href="#" id="finish_catalogue_app">Finish <i class="fa fa-sign-in"></i></a>
            </li>
        </ul>
    </header>
    <section class="middle clear">
        <!--<div class="right-area">
                 <div class="pageIndexArea">
                               <div class="pageIndexdiv">
                               <img alt="" src="<?php echo $basicUrl; ?>images/instappy.png">
                               </div>
               </div>
               <div class="mobile">
                               <div style="overflow:hidden; width:281px; height:470px;">
                                       <img src="images/image10.png" style="width:100%;">
                               </div>
           </div>
        </div>-->
        <div class="right-area">
            <div class="pageIndexArea">
                <div class="pageIndexdiv">
                    <img src="<?php echo $basicUrl; ?>images/instappy.png" alt="">
                </div>

            </div>
            <div class="mobile">
                <!-- Replacement Area Starts -->
                <div id="content-1" class="content mCustomScrollbar clear first-page catalogueTheme" >

                    <div class="overlay">
                    </div>

                    <div class="container droparea" style="float:left;width:100%;">
                    </div>     
                </div>

                <!-- Replacement Area Ends -->
            </div>
            <div class="choiceArea"> 
                <div class="previewEditArea" style="visibility:hidden;">


                    <p class="preview"><i class="fa fa-search"></i> Preview</p><p class="edit active"><i class="fa fa-pencil"></i> Edit</p>
                </div>

                <div class="additional_options">
                    <span></span>
                    <a href="resources/Retail_and_Catalogue_Apps.pdf" target="_blank"><div class="additional_items information">
                        <img src="<?php echo $basicUrl; ?>images/info1.png" alt="Image Not Found">
                        <div class="tooltip">Information</div>
                    </div></a>
                    <a href="#"><div class="additional_items hint">
                        <img src="<?php echo $basicUrl; ?>images/info2.png" alt="Image Not Found">
                        <div class="tooltip">Hint</div>
                    </div></a>
                    <!--<div class="additional_items chat">
                        <img src="<?php // echo $basicUrl;?>images/info4.png" alt="Image Not Found">
                        <div class="tooltip">Chat</div>
                    </div>
                    <div class="additional_items callus">
                        <img src="<?php echo $basicUrl; ?>images/info3.png" alt="Image Not Found">
                        <div class="tooltip">Call Us</div>
                    </div>-->

            </div>
                <div class="clear"></div>
                <div class="crome_msg" style="text-align:right;"><p style="font-size:12px; color:#a9a9a9; margin-right:10px; margin-top:10px;">Best Viewed in Chrome</p></div>

            </div>
        </div>
        <div class="center-area clear"><div class="mid_section" id="content-2">
                <div class="mid_section_left">
                </div>
                <div class="mid_section_right">
                    <form id="catalogue_app_form_new" method="post" action="" enctype="multipart/form-data"> 
                        <div class="mid_right_box active">
                            <div class="mid_right_box_head">
                                <h1>Basic Details</h1>
                                <h2>Let's get started</h2>
                            </div>
                            <div class="mid_right_box_body">

                                <div class="design_menu_box">
                                    <div class="color_label" style="margin-top:10px;">
                                        <label>Name Your App:*</label>
                                    </div>
                                    <div class="color_textbox">
                                        <input type="text" value="<?php
                                        if ($app_data['summary'])
                                            echo stripslashes($app_data['summary']);
                                        ?>" id="app_Name" name="appName" maxlength="30">
                                        <span>Choose a unique name for your App. You can go creative with the name but before you finalize do a final check of whether it conveys how amazing your product is? Is it easy to pronounce? Is it even accurate to what your app does? Is it unique? If the answer is yes, go ahead!</span>
                                        <a class="read_more">Read More...</a>
                                        <div class="clear"></div>
                                        <input type="hidden" value="<?php
                                        if ($app_id)
                                            echo $app_id;
                                        else
                                            echo "";
                                        ?>" name="app_id" id="appid">
                                        <input type="hidden" value="" id="author" name="author">
                                        <input type="hidden" value="<?php echo $themeid; ?>" id="theme" name="theme">
                                        <input type="hidden" value="<?php echo $catid; ?>" id="category" name="category">
                                    </div>
                                    <div class="color_label">
                                        <label>Type of App:*</label>
                                    </div>
                                    <div class="color_textbox">
                                        <?php  echo $obj->retail_catalogue_type($app_data['catalogue_type']);?>
                                       <!-- <select id="app_type" name="catalogue_app_type">
                                            <option value="">Select Type</option>
                                            <option value="1"<?php // if ($app_data['catalogue_type'] == 1) echo "selected"; ?>>Retail Commerce</option>
                                            <!-- option value="2" <?php //if ($app_data['catalogue_type'] == 2) echo "selected"; ?>>Advanced Retail Commerce</option
                                            <option value="3"<?php // if ($app_data['catalogue_type'] == 3) echo "selected"; ?>>Retail Catalogue</option>
                                            <!-- option value="4"<?php //if ($app_data['catalogue_type'] == 4) echo "selected"; ?>>Advanced Retail Catalogue</option 

                                        </select> -->
                                        <span>Choose the type of app you want to create. There are two main types of retail apps:<br>&bull;&nbsp;&nbsp;Retail Catalogue apps are meant to showcase your product portfolio, allowing you to add thousands of products under unlimited categories and subcategories.<br>&bull;&nbsp;&nbsp;Retail Commerce apps turn your business into a true mobile store with the added utility of dynamic inventory management and secure payment gateway integration.<br>Select what suits your needs best. For more details on the different packages, <a href="choose_package_retail.php" target="_blank">click here</a>.</span>
                                        <a class="read_more">Read More...</a>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="color_label">
                                        <label>Title Bar Colour:</label>
                                    </div>
                                    <div class="color_colorbox">
                                        <input type="hidden" id="bg" name="bg" value="<?php if ($app_data['background_color']) echo $app_data['background_color']; ?>"/>

                                        <span class="editPicker21"></span>
                                        <div class="clear"></div>
                                        <em>Remember soft pastels enhance great pictures. You can also try multiple combinations before you finalise. So go ahead and experiment.</em>
                                        <a class="read_more">Read More...</a>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="color_label">
                                        <label>Text Colour:</label>
                                    </div>
                                    <div class="color_colorbox">
                                        <input type="hidden" id="bg1" name="text_color" value="<?php if ($app_data['text_color']) echo $app_data['text_color']; ?>"/>

                                        <span class="editPicker22"></span>
                                        <div class="clear"></div>
                                        <em>Remember soft pastels enhance great pictures. You can also try multiple combinations before you finalise. So go ahead and experiment.</em>
                                        <a class="read_more">Read More...</a>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="color_label">
                                        <label>Discount Colour:</label>
                                    </div>
                                    <div class="color_colorbox">
                                        <input type="hidden" id="bg2" name="discount_color" value="<?php if ($app_data['discount_color']) echo $app_data['discount_color']; ?>"/>

                                        <span class="editPicker23"></span>
                                        <div class="clear"></div>
                                        <em>Remember soft pastels enhance great pictures. You can also try multiple combinations before you finalise. So go ahead and experiment.</em>
                                        <a class="read_more">Read More...</a>
                                        <div class="clear"></div>
                                    </div>

                                    <div class="color_label">
                                        <label>App Tag:</label>
                                    </div>
                                    <div class="color_textbox">
                                        <select name="app_tag" id="app_tag">
                                            <option value="1" <?php if ($app_data['app_tag_id'] == 1) echo "selected"; ?>>Special Offers</option>
                                            <option value="2" <?php if ($app_data['app_tag_id'] == 2) echo "selected"; ?>>New in Store</option>
                                            <option value="3" <?php if ($app_data['app_tag_id'] == 3) echo "selected"; ?>>Pick of the day</option>
                                            <option value="4" <?php if ($app_data['app_tag_id'] == 4) echo "selected"; ?>>Pick of the week</option>
                                            <option value="5" <?php if ($app_data['app_tag_id'] == 5) echo "selected"; ?>>Trending now</option>
                                            <option value="6" <?php if ($app_data['app_tag_id'] == 6) echo "selected"; ?>>Discounted Product</option>
                                        </select>
                                    </div>

                                    <?php
									$currencies = $obj->getDefaultCurr();
									//print_r($currencies);
									//die();
									//$currencies = array();
									?>
									<div class="color_label">
                                        <label>Currency:</label>
                                    </div>
                                    <div class="color_textbox">
                                        <select name="curr_id" id="curr_id">
											<option value="">Select Currency</option>
											<?php
											if(!empty($currencies))
											{
												foreach($currencies as $curr)
												{
												?>
												<option value="<?php echo $curr->currency_id;?>" <?php echo $app_data['curr_id'] == $curr->currency_id ? 'selected="selected"' : ''; ?>><?php echo $curr->code;?></option>
												<?php
												}
											}
											?>
                                        </select>

                                        <div class="clear"></div>
                                        <a class="make_app_next" id="save_page" href="#" style="background:#ffcc00;">Save &amp; Continue</a>
                                    </div>


                                    <div class="clear"></div>
                                </div>

                                <div class="design_menu_box bannerEdit bannercropdiv">
                                    <h2>Banner Images:</h2>
                                    <div class="banner_img_change">
                                        <div class="change_image">                                   
                                            <input type="hidden" class="<?php
                                            if ($app_data['banner1'])
                                                echo $app_data['banner1'];
                                            else
                                                echo"";
                                            ?>" value="<?php
                                                   if ($app_data['banner1'])
                                                       echo $app_data['banner1'];
                                                   else
                                                       echo"";
                                                   ?>" name="banner_img_1" id="banner_img_1">									
                                            <img src="<?php
                                            if ($app_data['banner1'])
                                                echo $app_data['banner1'];
                                            else
                                                echo "images/browse_full_img.jpg";
                                            ?>" class='appbannereditbrowse banner_img' id="banner_img1">									
                                            <span>Image 1</span>
                                        </div>
                                        <div class="change_image">									
                                            <input type="hidden" name="banner_img_2" id="banner_img_2" class="<?php
                                            if ($app_data['banner2'])
                                                echo $app_data['banner2'];
                                            else
                                                echo"";
                                            ?>" value="<?php
                                                   if ($app_data['banner2'])
                                                       echo $app_data['banner2'];
                                                   else
                                                       echo"";
                                                   ?>">
                                            <img src="<?php
                                            if ($app_data['banner2'])
                                                echo $app_data['banner2'];
                                            else
                                                echo "images/browse_full_img.jpg";
                                            ?>" class='appbannereditbrowse banner_img' id="banner_img2">									
                                            <span>Image 2</span>									
                                        </div>
                                        <div class="change_image">									
                                            <input type="hidden" name="banner_img_3" id="banner_img_3" class="<?php
                                            if ($app_data['banner3'])
                                                echo $app_data['banner3'];
                                            else
                                                echo"";
                                            ?>" value="<?php
                                                   if ($app_data['banner3'])
                                                       echo $app_data['banner3'];
                                                   else
                                                       echo"";
                                                   ?>">									
                                            <img src="<?php
                                            if ($app_data['banner3'])
                                                echo $app_data['banner3'];
                                            else
                                                echo "images/browse_full_img.jpg";
                                            ?>" class='appbannereditbrowse banner_img' id="banner_img3">									
                                            <span>Image 3</span>									
                                        </div>
                                        <div class="clear"></div>
                                        <span class="banner_cont">These images will appear on the top of your home screen and are the first thing your users might notice in your app. You have the option of keeping it as a three-image slideshow, through which you can either highlight the best deals, signature products, or anything else you want to highlight.</span>
                                        <a class="read_more">Read More...</a>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                    <div id='banner_message'></div>

                                    <div id="canvasShow" style="display: none;"></div>
                                    <input type="hidden" name="filenamest" id="filenamest"/>
                                    <input type="hidden" name="filetypest" id="filetypest"/>


                                </div>
                                <input type="hidden" name="type" value="save_catalogue_app">
                                <input type="hidden" id="save_msg" value="yes">
                                <input type="hidden" name="baseurl" value="<?php echo $basicUrl; ?>">
                                <input type="hidden" name="action" id="action" value="<?php
                                if ($app_id)
                                    echo "edit";
                                else
                                    echo "add";
                                ?>">
                                <input type="hidden" name="save_type" id="save_type" value="">						
                                <a href="#" class="make_app_next addproduct" id="oc_product"><?php
                                    if ($app_id)
                                        echo "View products";
                                    else
                                        echo"Add products";
                                    ?></a>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="mid_right_box">
                            <div class="mid_right_box_head">
                                <h1>Additional Features</h1>
                                <h2>Add additional features to your app</h2>
                            </div>
                            <div class="mid_right_box_body">
                                <div class="design_menu_box">
                                    <div class="catalogue_add_feature feedbackform">
                                        <div class="catalogue_input_group">
                                            <input type="checkbox" id="feedback" name="is_feedback" value="1" <?php if ($app_data['is_feedback'] == 1) echo 'checked="checked"'; ?> />
                                            <label for="feedback">Feedback Form</label>
                                            <div class="clear"></div>
                                            <!-- a href="#">Preview</a -->
                                        </div>
                                        <div class="catalogue_detail_input">
                                            <ul>
                                                <li><label>Email id:</label></li>
                                                <li><input type="text" placeholder="Enter email id" id="feedbackformemail" name="feedback_email" value="<?php echo $app_data['feedback_email']; ?>" /><!-- em>@</em><input type="text" placeholder="domainname.com" / --><div class="clear"></div><span>Enter email id to where you want your users to send their feedback.</span></li>
                                                <li><a href="javascript:void(0);">Preview</a></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                        <div class="catalogue_detail_input">
                                            <ul>
                                                <li><label>Support No. :</label></li>
                                                <li><input type="text" placeholder="Enter phone number" id="feedbackphone" name="feedback_no" value="<?php echo $app_data['feedback_no']; ?>" /><div class="clear"></div><span>Enter your support phone number where you want your users to contact you for help.</span></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="catalogue_add_feature contactus">
                                        <div class="catalogue_input_group">
                                            <input type="checkbox" id="contact" name="is_contactus" value="1" <?php if ($app_data['is_contactus'] == 1) echo 'checked="checked"'; ?> />
                                            <label for="contact">Contact Us</label>
                                            <div class="clear"></div>
                                            <!-- a href="#">Preview</a -->
                                        </div>
                                        <div class="catalogue_detail_input">
                                            <ul>
                                                <li><label>Email id:</label></li>
                                                <li><input type="text" placeholder="Enter email id" id="contactusemail" name="contactus_email" value="<?php echo $app_data['contactus_email']; ?>" /><!-- em>@</em><input type="text" placeholder="domainname.com" / --><div class="clear"></div><span>Enter email id to where you want your users to contact you for help.</span></li>
                                                <li><a href="javascript:void(0);">Preview</a></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                        <div class="catalogue_detail_input">
                                            <ul>
                                                <li><label>Support No. :</label></li>
                                                <li><input type="text" placeholder="Enter phone number" id="contactussupport" name="contactus_no" value="<?php echo $app_data['contactus_no']; ?>" /><div class="clear"></div><span>Enter your support phone number where you want your users to contact you for help.</span></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="catalogue_add_feature termsandcon">
                                        <div class="catalogue_input_group">
                                            <input type="checkbox" id="tandc" name="is_tnc" value="1" <?php if ($app_data['is_tnc'] == 1) echo 'checked="checked"'; ?> />
                                            <label for="tandc">Term and Conditions</label>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="catalogue_detail_input">
                                            <ul>
                                                <li><label>Link:</label></li>
                                                <li class="fullLength"><input type="text" placeholder="Enter Link" id="tnclink" name="tnc_link" value="<?php echo $app_data['tnc_link']; ?>" /><div class="clear"></div><span>Enter html link of term and conditions page</span></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="catalogue_add_feature invoice" style="display:none;">
                                        <div class="catalogue_input_group">
                                            <input type="checkbox" id="invoice" name="is_order" value="1" <?php if ($app_data['is_order'] == 1) echo 'checked="checked"'; ?> />
                                            <label for="invoice">Send Order Confirmation Email To Your Users</label>
                                            <div class="clear"></div>
                                            <a href="#">Preview</a>
                                        </div>
                                        <div class="company_logo">
                                            <input type="hidden" class="<?php
                                            if ($app_data['logo_link']) {
                                                echo $app_data['logo_link'];
                                            } else {
                                                echo"";
                                            }
                                            ?>"
                                                   value="<?php
                                                   if ($app_data['logo_link']) {
                                                       echo $app_data['logo_link'];
                                                   } else {
                                                       echo"";
                                                   }
                                                   ?>" name="logo_link" id="logo_link">

                                            <img src="<?php
                                            if ($app_data['logo_link'])
                                                echo $app_data['logo_link'];
                                            else
                                                echo $basicUrl . "images/catalogue_img_upload.png";
                                            ?>" class="editbrowse" id="logo_link_img">

                                            <div class="clear"></div>
                                            <span>Add Company Logo<br />to be visible in the form</span>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="catalogue_detail_input">
                                            <ul>
                                                <li><label>Choose Pack:</label></li>
                                                <li>
                                                    <select name="companypackage" id="companypackage">
                                                        <option value="1" <?php if ($app_data['package'] == 1) echo 'selected="selected"'; ?>>200 EDM Pack (Rs. 1000)</option>
                                                        <option value="3" <?php if ($app_data['package'] == 3) echo 'selected="selected"'; ?>>500 EDM Pack (Rs. 2000)</option>
                                                    </select>
                                                </li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                        <div class="catalogue_detail_input">
                                            <ul>
                                                <li><label>Email id:</label></li>
                                                <?php
                                                if ($app_data['orderconfirm_email'] != '') {
                                                    $explodedata = explode('@', $app_data['orderconfirm_email']);

                                                    $orderusername = $explodedata[0];
                                                    $orderdomain = $explodedata[1];
                                                    $od_domainname = 'Edit Domain';
                                                } else {
                                                    $orderusername = '';
                                                    $orderdomain = '';
                                                    $od_domainname = 'Add Domain';
                                                }
                                                ?>
                                                <li class="email_box"><input type="text" placeholder="Enter email id" name="orderusername" id="orderusername" value="<?php echo $orderusername; ?>" /><em>@</em><input type="text" readonly placeholder="domainname.com" name="orderdomain" id="orderdomain" value="<?php echo $orderdomain; ?>" /><div class="clear"></div><span>Enter email id you want to add in order confirmation mail to your users.</span></li>
                                                <li><a href="javascript:void(0);" id="od_domainname"><?php echo $od_domainname; ?></a></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>
                                        <div class="catalogue_detail_input">
                                            <ul>
                                                <li><label>Support No. :</label></li>
                                                <li><input type="text" placeholder="Enter phone number" name="orderconfirm_no" id="orderconfirm_no" value="<?php echo $app_data['orderconfirm_no']; ?>" /><div class="clear"></div><span>Enter your support phone number where you want your users to contact you for help.</span></li>
                                                <div class="clear"></div>
                                            </ul>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="clear"></div>
            </div></div>
        <div class="hint_main"><img src="<?php echo $basicUrl; ?>/images/ajax-loader.gif"></div>

        <a id="openModalW" data-target="#cropper-example-2-modal" data-toggle="modal" style="opacity:0.11;" >&nbsp;</a>

        <div class="modal fade" id="cropper-example-2-modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <a data-dismiss="modal" style="float: right; cursor: pointer; padding: 10px;">&times;</a>
                        <h4 id="bootstrap-modal-label">Upload Image</h4>
                        <div class="upload-btn">
                            <a class="make_app_next">Browse</a>
                            <input class="sr-only inputImageChange" id="inputImage" name="file" type="file" />
                        </div>
                        <ul class="modal-title">
                            <li>This image should be atleast <span class="resolution">1080px &times; 540px</span> resolution.</li>
                            <li>Maximum image size should be of 2MB.</li>
                            <li>Drag to adjust image &amp; scroll to zoomin/zoomout to fit in the area.</li>
                        </ul>
                    </div>

                    <div class="modal-body">
                        <div id="cropper-example-2" class="img-container">
                            <img src="" class="" id="modalimage1" alt="" />
                            <div class="crop-controls">
                                <span style="position: relative; left: -50%;">
                                    <button class="zoom-controls" data-method="zoom" data-option="0.02" title="Zoom In">
                                        <i class="fa fa-search-plus fa-lg"></i>
                                    </button>
                                    <button class="zoom-controls" data-method="zoom" data-option="-0.02" title="Zoom Out">
                                        <i class="fa fa-search-minus fa-lg"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">

                        <a class="make_app_next cropcancel" style="float: none; cursor: pointer; display: none !important;" data-dismiss="modal">Cancel</a>
                        <a id="getcropped" style="float: none; cursor: pointer; visibility: hidden; width: 0;" class="make_app_next banner1080" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 1080, &quot;height&quot;: 540 }" >Crop &amp; Save</a>
                        <a id="getcropped2" style="float: none; cursor: pointer; visibility: hidden; width: 0;" class="make_app_next logo500" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 500, &quot;height&quot;: 500 }" >Crop &amp; Save</a>
                    </div>
<!--
                    <div class="modal-footer">
                        <a class="make_app_next cropcancel" style="float: none;cursor: pointer; cursor: hand;" data-dismiss="modal">Cancel</a>
                        <div style="float: right; width: 10px;">&nbsp;</div>
                        <a id="getcropped" style="float: none;cursor: pointer; cursor: hand; visibility: hidden; width: 0;" class="make_app_next banner1080" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 1080, &quot;height&quot;: 540 }" >Crop & Save</a>
                        <a id="getcropped2" style="float: none;cursor: pointer; cursor: hand; visibility: hidden; width: 0;" class="make_app_next logo500" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 500, &quot;height&quot;: 500 }" >Crop & Save</a>
                    </div>
-->
                </div>
            </div>
        </div>
    </section>
</section>
</section>
<script src="js/colpick.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.shapeshift.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script type="text/javascript" src="js/publishJS.js"></script>
<script type="text/javascript" src="js/pramukhime.js"></script>
<script type='text/javascript' src='js/pramukhindic.js' ></script>
<script type="text/javascript" src="js/pramukhime-common.js"></script>
<script language="javascript" type="text/javascript">

                    pramukhIME.addLanguage(PramukhIndic);

                    pramukhIME.enable();
                    pramukhIME.onLanguageChange(scriptChangeCallback);
                    var lang = (getCookie('pramukhime_language', ':english')).split(':');
                    pramukhIME.setLanguage(lang[1], lang[0]);
                    var ul = document.getElementById('pi_tips');

                    var elem, len = ul.childNodes.length, i;
                    for (i = 0; i < len; i++) {
                        elem = ul.childNodes[i];
                        if (elem.tagName && elem.tagName.toLowerCase() == 'li') {
                            tips.push(elem.innerHTML);
                        }
                    }
                    for (i = len - 1; i > 1; i--) {
                        ul.removeChild(ul.childNodes[i]);
                    }
                    ul.childNodes[i].className = 'tip'; // replace small tip text with large

                    showNextTip(); // call for first time
                    setTimeout('turnOffTip()', 90000); // show tips for 1.5 minutes
                    document.getElementById('typingarea').focus();

// set width and height of dialog
                    var w = window, d = document, e = d.documentElement, g = d.getElementsByTagName('body')[0], x = w.innerWidth || e.clientWidth || g.clientWidth, y = w.innerHeight || e.clientHeight || g.clientHeight;
                    var elem = document.getElementById('dialog');
                    elem.style.top = ((y - 550) / 2) + 'px';
                    elem.style.left = ((x - 700) / 2) + 'px';
</script>
<script>
	$(document).ready(function(){
		// default on load
		var app_type = $('#app_type').val();
		if(app_type == 1)
		{
			$('.catalogue_add_feature.invoice').fadeIn();
		}
		else
		{
			$('.catalogue_add_feature.invoice').hide();
		}
		
		// on change function
		$('#app_type').on('change', function(){
			var app_type = $(this).val();
			if(app_type == 1)
			{
				$('.catalogue_add_feature.invoice').fadeIn();
			}
			else
			{
				$('.catalogue_add_feature.invoice').hide();
			}
		});
	});

    (function ($) {
        $(window).load(function () {
            $("#content-1").mCustomScrollbar();
            $("#content-2").mCustomScrollbar();
        });
    })(jQuery);
</script>
<script type="text/javascript">

    var text_color = '<?php echo $app_data['text_color'] != '' ? $app_data['text_color'] : ''; ?>';
    var discount_color = '<?php echo $app_data['discount_color'] != '' ? $app_data['discount_color'] : ''; ?>';

    $(document).ready(function () {
        $("#fakeLanguage").on("change", function () {
            //console.log( $(this).find(":selected").val())
            $("#drpLanguage").val($(this).find(":selected").val()).trigger("change");
        });
        $('#od_domainname').on('click', function () {

            if ($('#orderdomain').attr('readonly') == 'readonly')
            {
                $('#orderdomain').removeAttr('readonly');
            }
            else
            {
                $('#orderdomain').attr('readonly', true);
            }
        });


        $("#bg1").val(text_color);
        $(".editPicker22").css("background", text_color);

        $("#bg2").val(discount_color);
        $(".editPicker23").css("background", discount_color);

        var theme_bg = $(".theme_head").eq(1).css("background-color");
        $("#bg").val(theme_bg);
        $(".editPicker21").css("background", "<?php
                                                if ($app_data['background_color'])
                                                    echo $app_data['background_color'];
                                                else
                                                    echo '#1abc9c';
                                                ?>");
        var rightHeight = $(window).height() - 95;
        $(".middle").css("height", rightHeight + "px");

        /* Banner Starts */
        /*
         $('.banner_img').click(function(){			
         $(this).prev('input').click();			
         });
         */

        $(".banner_img_change input[type=file]").on('change', function () {
            var id = $(this).attr('id');
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader)
                return; // no file selected, or no FileReader support

            if (/^image/.test(files[0].type)) {
                // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
                reader.onloadend = function () { // set image data as background of div
                    var bannerimg = parseInt(id.replace("banner_img_", "")) - 1;
                    $("#" + id).next('img').attr('src', reader.result);
                    $("#" + id).addClass(id);
                    $(".banner").find("img").eq(bannerimg).attr('src', reader.result);

                }
            }

        });

    });
    $('.editPicker21').colpick({
        colorScheme: 'dark',
        layout: 'rgbhex',
        color: '<?php
                                                if ($app_data['background_color'])
                                                    echo $app_data['background_color'];
                                                else
                                                    echo "theme_bg"
                                                    ?>',
        submit: 0,
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            $("#bg").val("#" + hex);
            $(el).css('background-color', '#' + hex);
            $("#present p.long_text_content").css("color", "#" + hex);
            $(".theme_head").css("background-color", "#" + hex);
        }
    });
    $('.editPicker22').colpick({
        colorScheme: 'dark',
        layout: 'rgbhex',
        color: '<?php
                                                if ($app_data['background_color'])
                                                    echo $app_data['background_color'];
                                                else
                                                    echo "theme_bg"
                                                    ?>',
        submit: 0,
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            $("#bg1").val("#" + hex);
            $(el).css('background-color', '#' + hex);
        }
    });
    $('.editPicker23').colpick({
        colorScheme: 'dark',
        layout: 'rgbhex',
        color: '<?php
                                                if ($app_data['background_color'])
                                                    echo $app_data['background_color'];
                                                else
                                                    echo "theme_bg"
                                                    ?>',
        submit: 0,
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            $("#bg2").val("#" + hex);
            $(el).css('background-color', '#' + hex);
        }
    });

</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/cropper.js"></script>
<script src="js/main.js"></script>
<!-- script src="js/main_company.js"></script -->
<script type="text/javascript">
	 
	/* 
	 * edited by Arun srivastava
	 * dated 25/11/15
	 */
    function editbrowse_cropimgFunction_cat()
	{
        //$('#screenoverlay').fadeIn();
        $(".popup_container").css({'display': 'block'});
        $(".modal").css('display', 'none');
        $(".modal-backdrop.in").css({'display': 'none', 'opacity': '0'});

        var filename = $("#filenamest").val();
        var filetype = $("#filetypest").val();

		if(external_app_id == '')
		{
			$("#save_msg").val('no');
			$('#save_catalogue_app').trigger('click');
		}
         
        //$("#page_ajax").html('<img src="images/ajax-loader.gif">').show();
		
		$.ajax({
			type: "POST",
			url: "login.php",
			data: "check=login",
			success: function (response) {
				if (response) {
					var res = response.split("##");
					
					external_app_id  = res[1];
					external_user_id = res[0]; 
					var out          = res[2];
					
					if (out == 0)
					{
						var app_id   = res[1];
						var autherId = res[0];
						if (app_id != '')
						{
							$("#app_id").val(app_id);
							$("#author_id").val(autherId);

							var canvas = document.getElementById("canvas1");

							var dataURL1 = canvas.toDataURL(filetype, 0.7);             // 70% compressed version of the original image
							var start = new Date().getTime();
							$(".popup_container").css({'display': 'block'});
							$("#page_ajax").html('<img src="images/ajax-loader.gif">').show();
							$.ajax({
								type: "POST",
								url: "imageload.php",
								data: "image=" + dataURL1 + "&imgname=" + "panelimage/" + start + filename,
								// async: false,                                         // To show the instappy loader image without Wait/Kill Pages
								success: function (response) {
									if (response) {
										var newresponse = $.parseJSON(response);
										var imagename = newresponse.imageurl;

										var imagePath = BASEURL + imagename;

										$("#filenamest").val('');
										$("#filetypest").val('');
										$(".modal-backdrop.in").css({'display': 'none', 'opacity': '0'});
										$(".modal").css('display', 'none');


										if ($("#presentBannerImgset").length == 1)
										{
											$(".banner").children("img").eq($("#presentBannerImgset").parents(".change_image").index(".change_image")).attr("src", imagePath);
											$(".appbannereditbrowse#presentBannerImgset").attr("src", imagePath);
											$("#presentBannerImgset").parents(".change_image").find('input').val(imagePath);
											$("#presentBannerImgset").parents(".change_image").find('input').addClass(imagePath);
										}
										else
										{
											$("#companyLogoImg").attr("src", imagePath);
											$("#companyLogoImg").parents(".company_logo").find('input').val(imagePath);
											$("#companyLogoImg").parents(".change_image").find('input').addClass(imagePath);

										}


										var dfd       = $.Deferred();
										var tmpImg    = new Image();
										tmpImg.src    = imagePath;
										tmpImg.onload = function () {
											dfd.resolve();
											$('#screenoverlay').fadeOut();
										};
										$(".cropcancel").trigger("click");
										$('#save_catalogue_app').trigger('click');
										$("#save_msg").val('no');
										$(".popup_container").css({'display': 'none'});
										$(".popup_container > div").css({'display': 'none'});
										$("#page_ajax").html('').hide();
									}
									else
									{
										$('#screenoverlay').fadeOut();
										$("#filenamest").val('');
										$("#filetypest").val('');
										$(".modal-backdrop.in").css({'display': 'none', 'opacity': '0'});
										$(".modal").css('display', 'none');
										$(".popup_container").css({'display': 'none'});
										$("#page_ajax").html('').hide();
									}
								}, error: function () {
									$("#save_msg").val('no');
									$('#save_catalogue_app').trigger('click');
									
									$("#filenamest").val('');
									$("#filetypest").val('');
									$(".modal-backdrop.in").css({'display': 'none', 'opacity': '0'});
									$(".modal").css('display', 'none');
								}
							});
							$(".editbrowse_img").replaceWith('<input type="file" class="editbrowse_img">');
						}
						else
						{
							$('#screenoverlay').fadeOut('fast', function () {
								$(".modal-backdrop.in").css({'display': 'none', 'opacity': '0'});
								$(".modal").css('display', 'none');
								$('.mid_section_right').find('.mid_right_box').first().find('.mid_right_box_head').trigger('click');
							});
							$(".cropcancel").trigger("click");
							$("#page_ajax").html('').hide();
							$(".popup_container").css({'display': 'block'});
							$(".confirm_name:last .confirm_name_form").html('<p>Please save app to continue.</p><input type="button" value="OK">');
							$(".confirm_name").css({'display': 'block'});
						}
					}
					else
					{
						$(".popup_container").css("display", "block");
						$(".confirm_name").css("display", "block");
						$(".confirm_name_form p").text("Please complete your profile to proceed.");
						window.location = 'userprofile.php';
						return false;
					}
				}
			}
		});
    }
</script>
<?php if ($app_id > 0) {
    ?>
    <script>
        var page = '<?php echo addslashes(json_decode($html, false)); ?>';
        //if(page != '')
        //{
        var obj = JSON.parse(page);

        //        alert(obj.navigationbar);
        if (obj.banner == undefined || obj.banner == "")
        {
            $(obj.navigationbar).insertBefore(".overlay");
            $(".container.droparea").html(obj.contentarea);
        }
        else
        {
            $(obj.navigationbar).insertBefore(".overlay");
            $(".container.droparea").html(obj.contentarea);
            $("<div class='banner'>" + obj.banner + "</div>").insertAfter(".overlay");
        }
        for (var i = 0; i < 3; i++) {
            if (i == 0)
                $(".banner").find("img").eq(i).attr('src', '<?php echo $app_data['banner1']; ?>');
            if (i == 1)
                $(".banner").find("img").eq(i).attr('src', '<?php echo $app_data['banner2']; ?>');
            if (i == 2)
                $(".banner").find("img").eq(i).attr('src', '<?php echo $app_data['banner3']; ?>');

        }
        $(".theme_head").css("background-color", "<?php
    if ($app_data['background_color'])
        echo $app_data['background_color'];
    else
        echo '#ff8800';
    ?>");
    </script>
    <?php
}
else {
    ?>
    <script>
        var page = '<?php echo addslashes(json_decode($html, false)); ?>';
        //if(page != '')
        //{
        obj = JSON.parse(page);
        //        alert(obj.navigationbar);
        if (obj.banner == undefined || obj.banner == "")
        {
            $(obj.navigationbar).insertBefore(".overlay");
            $(".container.droparea").html(obj.contentarea);
        }
        else
        {
            $(obj.navigationbar).insertBefore(".overlay");
            $(".container.droparea").html(obj.contentarea);
            $("<div class='banner'>" + obj.banner + "</div>").insertAfter(".overlay");
        }

    </script>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function () {
        $(".editPicker21").css("background-color", $(".mobile .theme_head").css("background-color"));

        /* $("#appName").val($(".theme_head").eq(1).find("h1").text())
         $("#appName").on("keyup",function(){
         $(".theme_head").eq(1).find("h1").text( $(this).val())
         
         
         });*/
        $("#save_page").on('click', function () {
            $("a#save_catalogue_app").trigger("click");
        });
        $(".theme_head h1").text("Home");
    });
</script>


<!--<script type="text/javascript" src="js/customFramework.js"></script>-->
<script>

    function checkAppName() {
        var y = 0;

        if ($("#app_Name").val() != '') {
            y = 1;
        } else {
            y = 0;
        }

        return y;
    }

    $(document).ready(function () {


        $("#getcropped").click(function () {

            if ($("#cropper-example-2-modal").css('display') == 'block') {

                var checkimg = $("#cropper-example-2-modal img#modalimage1").attr("src");

                if (checkimg.length != 0) {
                    var appnameempty = checkAppName();

                    if (appnameempty == 1) {
                        editbrowse_cropimgFunction_cat();
                    } else {
                        $(".cropcancel").trigger("click");
                        $("#page_ajax").html('').hide();
                        $(".popup_container").css({'display': 'block'});
                        $(".confirm_name .confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
                        $(".confirm_name").css({'display': 'block'});
                    }
                } else {

                    $(".cropcancel").trigger("click");
                    $("#page_ajax").html('').hide();
                    $(".popup_container").css({'display': 'block'});
                    $(".confirm_name .confirm_name_form").html('<p>Please choose image.</p><input type="button" value="OK">');
                    $(".confirm_name").css({'display': 'block'});
                    return false;
                }
            } else {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('').hide();
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name .confirm_name_form").html('<p>Please choose image.</p><input type="button" value="OK">');
                $(".confirm_name").css({'display': 'block'});
                return false;
            }
        });
        $("#getcropped2").click(function () {

            if ($("#cropper-example-2-modal").css('display') == 'block') {

                var checkimg = $("#cropper-example-2-modal img#modalimage1").attr("src");
                if (checkimg.length != 0) {
                    var appnameempty = checkAppName();

                    if (appnameempty == 1) {

                        editbrowse_cropimgFunction_cat();
                    } else {
                        $(".cropcancel").trigger("click");
                        $("#page_ajax").html('').hide();
                        $(".popup_container").css({'display': 'block'});
                        $(".confirm_name .confirm_name_form").html('<p>Please choose app name.</p><input type="button" value="OK">');
                        $(".confirm_name").css({'display': 'block'});
                    }
                } else {

                    $(".cropcancel").trigger("click");
                    $("#page_ajax").html('').hide();
                    $(".popup_container").css({'display': 'block'});
                    $(".confirm_name .confirm_name_form").html('<p>Please choose image.</p><input type="button" value="OK">');
                    $(".confirm_name").css({'display': 'block'});
                    return false;
                }
            } else {
                $(".cropcancel").trigger("click");
                $("#page_ajax").html('').hide();
                $(".popup_container").css({'display': 'block'});
                $(".confirm_name .confirm_name_form").html('<p>Please choose image.</p><input type="button" value="OK">');
                $(".confirm_name").css({'display': 'block'});
                return false;
            }
        });


        $(".appbannereditbrowse").click(function () {
            $(".modal-title .resolution").html("1080px &times; 540px");
            $(".logo500").css({"width": "0", "visibility": "hidden", "float": "left"});
            $(".banner1080").css({"width": "auto", "visibility": "visible", "float": "none"});

            $("#presentBannerImgset").removeAttr("id");
            $("#companyLogoImg").removeAttr("id");

            $(this).attr("id", "presentBannerImgset");

            var $image = $('.img-container > img');
            $("a#openModalW").trigger("click");

            $('#cropper-example-2-modal').on('shown.bs.modal', function () {
                $image.cropper('destroy').cropper({
                    aspectRatio: 2,
                    /*minContainerWidth: 800,
                    minContainerHeight: 400,
                    minCropBoxWidth: 800,
                    minCropBoxHeight: 400,*/
                    data: {
                        width: 500,
                        height: 250
                    },
                    /*center: true,
                    strict: false,
                    guides: false,*/
                    highlight: false,
                    viewMode: 1
                    /*dragCrop: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false*/
                });
            }).on('hidden.bs.modal', function () {
                $(".modal-backdrop.in").css({'display': 'none', 'opacity': '0'});
                $(".modal").css('display', 'none');
                $image.cropper('destroy');
            });
        });

    });



    $(document).ready(function () {
        var upreview = '<?php echo $preview; ?>';
        if (upreview == "preview") {

            $(".preview").trigger('click');
        }

        $(".editbrowse").click(function () {
            $(".modal-title .resolution").html("500px &times; 500px");
            $(".banner1080").css({"width": "0", "visibility": "hidden", "float": "left"});
            $(".logo500").css({"width": "auto", "visibility": "visible", "float": "none"});

            $("#presentBannerImgset").removeAttr("id");
            $("#companyLogoImg").removeAttr("id");
            $(this).attr("id", "companyLogoImg");

            var $image = $('.img-container > img');
            $("a#openModalW").trigger("click");

            $('#cropper-example-2-modal').on('shown.bs.modal', function () {
                $image.cropper('destroy').cropper({
                    aspectRatio: 1,
                    data: {
                        width: 500,
                        height: 500
                    },
                    /*strict: false,
                    guides: false,*/
                    highlight: false,
                    viewMode: 1
                    /*dragCrop: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false*/
                });
            }).on('hidden.bs.modal', function () {
                $(".modal-backdrop.in").css({'display': 'none', 'opacity': '0'});
                $(".modal").css('display', 'none');
                $image.cropper('destroy');

            });




        });
    });
    (function ($) {
        $(window).load(function () {
            $("#content-1").mCustomScrollbar();
            $("#content-2").mCustomScrollbar({
                callbacks: {
                    onScrollStart: function () {
                        $('.colpick').hide();
                    }
                }
            });
        });
    })(jQuery);
</script>
<script type="text/javascript" src="js/intlTelInput.js"></script>
<script>
   $("#mobile_country").intlTelInput({
       utilsScript: "js/utils.js"
   });
</script>
</body>
</html>
