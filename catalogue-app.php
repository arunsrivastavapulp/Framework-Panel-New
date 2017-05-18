<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
$app_id = isset($_GET['appid']) ? addslashes($_GET['appid']) : '';
$themeid = isset($_GET['themeid']) ? addslashes($_GET['themeid']) : '';
$catid = isset($_GET['catid']) ? addslashes($_GET['catid']) : '';
require_once('modules/opencart-integration/opencart-integration.php');
$obj = new Opencart();
require_once('modules/checkapp/checkappclass.php');
$checkapp = new checkapp();

$custid = $_SESSION['custid'];
$getAuthor = $checkapp->getAuthorId($custid);

if ($themeid) {
    $html = $obj->get_cuurent_app_html($themeid);
    unset($_SESSION['app_id']);
}
if ($app_id) {
    $author = $obj->check_user($_SESSION['username'], $_SESSION['custid']);
    $app_data = $obj->edit_catalogue_app($app_id);
    $html = $obj->get_cuurent_app_html($app_data['theme']);
}

$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$preview = isset($_GET['page']) ? $_GET['page'] : '';
$_SESSION['catid'] = $catid;
$_SESSION['themeid'] = $themeid;
$_SESSION['currentpagevisit'] = 'catalogue-app.php';
?>
<link href="css/cropper.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="css/main.css" rel="stylesheet">
<link href="css/catalogue.css" rel="stylesheet" type="text/css">
<link href="http://bxslider.com/lib/jquery.bxslider.css" type="text/css" rel="stylesheet" />

<section class="main" ng-controller="catalogueController">
  
  <div style="height: 100%; width: 100%; position: fixed; background: #000; opacity: 0.7; top: 0; left: 0; z-index: 99999;" ng-hide="dataLoaded">
    <img class="loader_new" src="images/ajax-loader_new.gif" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%); -moz-transform: translate(-50%, -50%); -o-transform: translate(-50%, -50%);" />
  </div>
  
  <div class="common-retail-popup" ng-show="popupDtls.show" ng-cloak>
    <div class="popup-dialog">
      <div class="popup-body">
        <p ng-bind="popupDtls.msg"></p>
        <input type="button" value="OK" ng-click="popupDtls.show = false;" />
      </div>
    </div>
  </div>
  
  <header>
<!--    <a class="make_app_next" href="#" style="background:#ffcc00; float: left; margin: 10px 20px;" ng-click="postAppDtls()">Save &amp; Continue</a>-->
    <ul class="top-aside">
		<li>
			<a class="make_app_next addproduct" href="javascript:void(0);" ng-click="sendToOpencartCatProd('Products');">Add Products</a>
		</li>
		<li>
			<a href="themes.php#/?q=30">Create New <i class="fa fa-rocket"></i></a>
		</li>
		<li class="save">
			<a href="#" ng-click="postAppDtls()">Save <i class="fa fa-cloud-upload"></i></a>
		</li>
		<li>
			<a href="#" ng-click="postAppDtls('add-icons.php')">Finish <i class="fa fa-sign-in"></i></a>
		</li>
    </ul>
  </header>
  <section class="middle clear">
    <div class="right-area">
      <div class="pageIndexArea">
        <div class="pageIndexdiv"> <img src="<?php echo $basicUrl; ?>images/instappy.png" alt=""> </div>
      </div>
      <div class="mobile"> 
        <!-- Replacement Area Starts -->
        <div id="content-1" class="content mCustomScrollbar clear first-page catalogueTheme">
          <div class='theme_head' ng-style="{ 'background': appDtls.screen_properties.background_color }">
            <a class='nav_open'><img src='http://www.instappy.com/images/menu_btn.png' alt='Image Not Found'> </a>
            <a class='nav_back'><i class='fa fa-angle-left'></i></a>
            <h1>Home</h1>
            <a href='#' class='search'>
              <i class='fa fa-search'></i>
            </a>
          </div>
          <nav>
            <ul>
              <li data-link='1'>
                <a href='#'>
                  <span><img src='http://www.instappy.com/images/nav_img.png'></span>
                  <span>Home</span>
                  <div class='clear'></div>
                </a>
              </li>              
<!--
              <li data-link='s1'>
                <a href='#'>
                  <span><img src='http://www.instappy.com/images/nav_img.png'></span>
                  <span>Report Misconduct</span>
                  <div class='clear'></div>
                </a>
              </li>
-->
              <li data-link='s2' class='feedback' ng-if='appDtls.feedback_dtls.is_feedback'>
                <a href='#'>
                  <span><img src='http://www.instappy.com/images/nav_img.png'></span>
                  <span>Feedback</span>
                  <div class='clear'></div>
                </a>
              </li>
              <li data-link='s3' ng-if='appDtls.contact_details.is_contactus'>
                <a href='#'>
                  <span><img src='http://www.instappy.com/images/nav_img.png'></span>
                  <span>Contact Us</span>
                  <div class='clear'></div>
                </a>
              </li>
            </ul>
          </nav>
          <div class="overlay"></div>
          <div class="container droparea" style="float: left; width: 100%;"  ng-style="{ 'visibility': screenVisible }">
          </div>
        </div>
        
        <!-- Replacement Area Ends --> 
      </div>
    </div>
    <div class="center-area clear">
      <div class="mid_section" id="content-2">
        <div class="mid_section_left"> </div>
        <div class="mid_section_right">
            
          <div class="mid_right_box name-your-app" ng-class="{ 'active': currentBlock === 0 }" ng-init="currentBlock = 0;">
              <div class="mid_right_box_head" ng-click="currentBlock = (currentBlock !== 0 ? 0 : -1);">
                <h1>Name Your App</h1>
                <h2>The app name you choose will be displayed in the store. Choosing the right name is the most important part of this process. Choose carefully, you cannot rename your app once it's launched. The app name should ideally be the name of your business or product. Think of how the users will search for your app. You can either go by your business name, or you can pick a universal and a catchy name. It is recommended that you choose a unique name for better in-store optimisation. And do remember to search and check the App Store to confirm that no other app exists by this name.</h2>
                <a href="#" class="read_more">Read More...</a>
                <div class="clear"></div>
                <span>
                  <i class="fa fa-angle-right" ng-class="{ 'fa-rotate-90': currentBlock === 0 }"></i>
                </span>
              </div>
              <div class="mid_right_box_body" ng-show="currentBlock === 0">
                <div class="design_menu_box">
                  <form name="nameYourAppForm">
                    <div class="color_label" style="margin-top: 10px;">
                      <label>Name Your App:*</label>
                    </div>
                    <div class="color_textbox">
                      <input name="app_name" class="required-input" type="text" maxlength="30" ng-model="appDtls.screen_properties.title" ng-blur="checkAppName()" ng-required="true" />
                      <span>Choose a unique name for your App. You can go creative with the name but before you finalize do a final check of whether it conveys how amazing your product is? Is it easy to pronounce? Is it even accurate to what your app does? Is it unique? If the answer is yes, go ahead!</span>
                      <a class="read_more">Read More...</a>
                      <div class="clear"></div>
                    </div>
                    <div class="color_label">
                      <label>Type of App:*</label>
                    </div>
                    <div class="color_textbox">
                      <select name="app_type" class="required-input" ng-model="appDtls.app_type" ng-required="true">
                        <option value="">Select Type</option>
                        <option value="2">Shopping and M-Store</option>
                        <option value="3">Shopping Catalogue</option>
                      </select>
                      <span>Choose the type of app you want to create. There are two main types of shopping apps:<br>
                      &bull;&nbsp;&nbsp;Shopping and M-Store apps turn your business into a true mobile store with the added utility of dynamic inventory management and secure payment gateway integration.<br>
                      &bull;&nbsp;&nbsp;Shopping Catalogue apps are meant to showcase your product portfolio, allowing you to add thousands of products under unlimited categories and subcategories.<br>
                      Select what suits your needs best. For more details on the different packages, <a href="choose_package_retail.php" target="_blank">click here</a>.</span>
                      <a class="read_more">Read More...</a>
                      <div class="clear"></div>
                    </div>
                    <?php
                      $currencies = $obj->getDefaultCurr();
                    ?>
                    <div class="color_label">
                      <label>Currency:*</label>
                    </div>
                    <div class="color_textbox">
                      <select name="app_currency" class="required-input" ng-model="appDtls.defaultcurrency" ng-required="true">
                        <option value="">Select Currency</option>
                        <?php
                          if (!empty($currencies)) {
                              foreach($currencies as $curr) {
                        ?>
                              <option value="<?php echo $curr->currency_id;?>">
                                <?php echo $curr->code;?>
                              </option>
                        <?php
                              }
                          }
                        ?>
                      </select>
                      <div class="clear"></div>
                    </div>
                    <a class="make_app_next" href="" style="background: #ffcc00;" ng-click="postAppDtls()">Save &amp; Continue</a>
                    <div class="clear"></div>
                  </form>
                </div>
                <div class="clear"></div>
              </div>
          </div>
            
          <div class="mid_right_box basic-details" ng-class="{ 'active': currentBlock === 1 }">
            <div class="mid_right_box_head" ng-click="currentBlock = (currentBlock !== 1 ? 1 : -1);">
              <h1>Basic Details</h1>
              <h2>Let's get started</h2>
              <span>
                <i class="fa fa-angle-right" ng-class="{ 'fa-rotate-90': currentBlock === 1 }"></i>
              </span>
            </div>
            <div class="mid_right_box_body" ng-show="currentBlock === 1">
              <div class="design_menu_box">
                <div class="color_label">
                  <label>Title Bar Colour:</label>
                </div>
                <div class="color_colorbox">
                  <span class="editPickerTitle"></span>
                  <div class="clear"></div>
                  <em>Remember soft pastels enhance great pictures. You can also try multiple combinations before you finalise. So go ahead and experiment.</em> <a class="read_more">Read More...</a>
                  <div class="clear"></div>
                </div>
                <div class="color_label">
                  <label>Text Colour:</label>
                </div>
                <div class="color_colorbox">
                  <span class="editPickerText"></span>
                  <div class="clear"></div>
                  <em>Remember soft pastels enhance great pictures. You can also try multiple combinations before you finalise. So go ahead and experiment.</em> <a class="read_more">Read More...</a>
                  <div class="clear"></div>
                </div>
                <div class="color_label">
                  <label>Discount Colour:</label>
                </div>
                <div class="color_colorbox">
                  <span class="editPickerDiscount"></span>
                  <div class="clear"></div>
                  <em>Remember soft pastels enhance great pictures. You can also try multiple combinations before you finalise. So go ahead and experiment.</em> <a class="read_more">Read More...</a>
                  <div class="clear"></div>
                </div>
                <a class="make_app_next" href="" style="background:#ffcc00;" ng-click="postAppDtls()">Save &amp; Continue</a>
                <a class="make_app_next addproduct" style="margin-right: 10px;" href="" ng-click="sendToOpencart()">Add Products</a>
                <div class="clear"></div>
              </div>
            </div>
          </div>
            
          <div class="mid_right_box add-widgets" ng-class="{ 'active': currentBlock === 2 }">
            <div class="mid_right_box_head choose_card_tip" ng-click="currentBlock = (currentBlock !== 2 ? 2 : -1);">
              <h1>Add Widgets</h1>
              <h2>
                Congratulations! You have reached the stage where you can start shaping up your app, and preview it on the Instappy App Preview Simulator to check if it is shaping out to be the way you want it to. Just in case you don’t like the way your app is shaping up, you can simply drag and re-position the elements on the Instappy App Preview Simulator to re-shape it.<br />
                These small screens are called Rich Media Cards. These small components fit together to create a screen. You can choose from a variety of cards to form different layouts
              </h2>
              <a href="#" class="read_more">Read More...</a>
              <div class="clear"></div>
              <span>
                <i class="fa fa-angle-right" ng-class="{ 'fa-rotate-90': currentBlock === 2 }"></i>
              </span>
            </div>
            <div class="mid_right_box_body" ng-show="currentBlock === 2">
              <div class="cat-utility_api_cards" ng-style="{ 'visibility': compsVisible }">
<!--                  <div class="cat-utility_api_content clones container" ng-bind-html="componentsHtml" append-elements="componentsHtml">-->
                <div class="cat-utility_api_content clones container">
                  <!--card-start -->

                  <!--card-end --> 
                </div>
              </div>
              <a class="make_app_next" href="" style="background:#ffcc00;" ng-click="postAppDtls()">Save &amp; Continue</a>
              <div class="clear"></div>
            </div>
          </div>
            
          <div class="mid_right_box update-card-dtls" ng-show="currentComp.comp_type && (currentBlock === 3)" ng-class="{ 'active': currentBlock === 3 }">
			<form name="update_widgets_details">
            <div class="mid_right_box_head">
              <h1>Update Widget Details</h1>
              <h2>Categories, Products and Tags would be visible in the wizard once they are added in the app and selected for the widget. To add/edit categories, products and tags in the app click on Add Products button to go to the cart panel.</h2>
              <a href="#" class="read_more">Read More...</a>
              <div class="clear"></div>
            </div>
            <div class="mid_right_box_body">
              <div class="design_menu_box">
                <div class="catalogue_add_feature feedbackform">

                  <div class="catalogue_detail_input retail_input" ng-show="fnShowField('heading')">
                    <ul>
                      <li>
                        <label>Heading:</label>
                      </li>
                      <li>
                        <input type="text" ng-model="currentComp.title.name" maxlength="25" />
                        <em>Maximum of 25 Characters</em>
                        <div class="clear"></div>
                      </li>
                      <div class="clear"></div>
                    </ul>
                  </div>

                  <div class="catalogue_detail_input retail_input" ng-show="fnShowField('tag')">
                    <ul>
                      <li>
                        <label>Tag:</label>
                      </li>
                      <li>
                        <select ng-model="currentComp.title" ng-options="tag.name for tag in specialTags track by tag.id">
                        </select>
                        <em>Tag your products in cart panel and choose tag here to show in smartphone application.</em>
                        <div class="clear"></div>
                      </li>
                      <div class="clear"></div>
                    </ul>
                  </div>

                  <div class="catalogue_detail_input retail_input" ng-show="fnShowField('subHeading')">
                    <ul>
                      <li>
                        <label>Sub Heading:</label>
                      </li>
                      <li>
                        <input type="text" ng-model="currentComp.comp_dtls['subheading']" />
                        <div class="clear"></div>
                      </li>
                      <div class="clear"></div>
                    </ul>
                  </div>
                  
                  <div class="catalogue_detail_input retail_input" ng-show="fnShowField('catProdSelect')">
                    <ul>
                      <li>
                        <label>Parent Category:</label>
                      </li>
                      <li>
                        <p class="no_cats" ng-show="!cumulativeCats.length">
                          No Categories Found.<br />
                          <small>To add or edit categories click on Add Products to go to the cart panel.</small>
                        </p>
                        <select ng-show="cumulativeCats.length" ng-model="currentComp.itemcat" ng-options="cat.itemheading for cat in cumulativeCats track by cat.itemid" ng-change="fetchProds(currentComp)">
                          <option value="">Select</option>
                        </select>
                        <div class="clear"></div>
                      </li>
                      <div class="clear"></div>
                    </ul>
                    <ul ng-show="currentComp.itemcat.itemid && (currentComp.itemcat.itemid !== '0') && cumulativeCats.length && !fnShowField('compWithCatsOnly')">
                      <li>
                        <label>Products:</label>
                      </li>
                      <li>
                        <img class="inline-loader" src="images/ajax-loader_new.gif" ng-show="!currentComp.loaded" />
                        <p ng-show="currentComp.loaded && !currentComp.prods.length">No Products Found.</p>
                        <p ng-show="currentComp.loaded && currentComp.prods.length">{{ currentComp.prods.length }} Product(s) Found.</p>
                        <div class="clear"></div>
                      </li>
                      <div class="clear"></div>
                    </ul>
                  </div>

                  <div class="catalogue_detail_input retail_input background_change_color" ng-show="fnShowField('labelColpick')">
                    <ul>
                      <li>
                        <label>Background Color:</label>
                      </li>
                      <li>
                        <div class="color_colorbox">
                          <span class="editPickerLabelBg" ng-style="{ 'background-color' : currentComp.label_bg }"></span>
                          <div class="clear"></div>
                          <em>Remember soft pastels enhance great pictures. You can also try multiple combinations before you finalise. So go ahead and experiment.</em> <a class="read_more">Read More...</a>
                          <div class="clear"></div>
                        </div>
                      </li>
                    </ul>
                  </div>

                  <div class="retailpanel_detail_input retail_input" ng-show="fnShowField('imgHeadSubhead')">
                    <ul ng-repeat="imgElem in currentComp.elements.element_array" ng-init="fetchProds(imgElem)">
                      <li>
                        <div upload-image current="imgElem" comp="currentComp" index="$index" ng-click="$parent.currImgIndex = $index;"></div>
                      </li>
                      <li>
                        <div ng-show="!fnShowField('noHeadSubhead')">
                          <label>Heading:</label>
                          <input type="text" ng-model="imgElem.itemheading" maxlength="20" placeholder="Maximum of 20 characters" />
                        </div>
                        <div ng-show="!fnShowField('noHeadSubhead')">
                          <label>Sub-Heading:</label>
                          <input type="text" ng-model="imgElem.itemdesc" maxlength="20" placeholder="Maximum of 20 characters" />
                        </div>
                        <div>
                          <label>Parent Category:</label>
                          <p ng-show="!cumulativeCats.length">
                            No Categories Found.<br />
                            <small>To add or edit categories click on Add Products to go to the cart panel.</small>
                          </p>
                          <select ng-show="cumulativeCats.length" ng-model="imgElem.itemcat" ng-options="cat.itemheading for cat in cumulativeCats track by cat.itemid" ng-change="fetchProds(imgElem)">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div ng-show="imgElem.itemcat.itemid && (imgElem.itemcat.itemid !== '0') && cumulativeCats.length">
                          <label>Products:</label>
                          <img class="inline-loader" src="images/ajax-loader_new.gif" ng-show="!imgElem.loaded" />
                          <p ng-show="imgElem.loaded && !imgElem.prods.length">No Products Found.</p>
                          <select ng-show="imgElem.loaded && imgElem.prods.length" ng-model="imgElem.itemprod" ng-options="prod.itemheading for prod in imgElem.prods track by prod.itemid">
                            <option value="">Select</option>
                          </select>
                        </div>
                      </li>
                      <div class="clear"></div>
                    </ul>
                    <a class="make_app_next" href="#" style="background:#ffcc00; float: right; margin: 0;" ng-click="addMoreImages()" ng-if="currentComp.elements.element_array.length < imagesCountMap[currentComp.comp_type].count">Add More</a>
                  </div>

                  <!-- <only cat and prods start> -->
                  <div class="retailpanel_detail_input retail_input" ng-show="fnShowField('onlyCatWithProdDropdwn')">
                    <ul ng-repeat="imgElem in currentComp.elements.element_array" ng-init="fetchProds(imgElem)" class="cart_component">
                      <li>
                      
                        <div>
                          <label>Category:</label>
                          <p ng-show="!cumulativeCats.length">
                            No Categories Found.<br />
                            <small>To add or edit categories click on Add Products to go to the cart panel.</small>
                          </p>
                          <select name="itemcatagory" required="required" ng-required="true" ng-show="cumulativeCats.length" ng-model="imgElem.itemcat" ng-options="cat.itemheading for cat in cumulativeCats track by cat.itemid" ng-change="fetchProds(imgElem)">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div ng-show="imgElem.itemcat.itemid && (imgElem.itemcat.itemid !== '0') && cumulativeCats.length && !fnShowField('compWithCatsOnly')">
                          <label>Products:</label>
                          <img class="inline-loader" src="images/ajax-loader_new.gif" ng-show="!imgElem.loaded" />
                          <p ng-show="imgElem.loaded && !imgElem.prods.length">No Products Found.</p>
                          <select name="itemprod" ng-if="!fnShowField('compWithCatsOnly')" required="required" ng-required="true" ng-show="imgElem.loaded && imgElem.prods.length" ng-model="imgElem.itemprod" ng-options="prod.itemheading for prod in imgElem.prods track by prod.itemid">
                            <option value="">Select</option>
                          </select>
                        </div>
                      </li>
                      <div class="clear"></div>
                    </ul>
                    <a class="make_app_next" href="#" style="background:#ffcc00; float: right; margin: 0;" ng-click="addMoreImages()" ng-if="currentComp.elements.element_array.length < imagesCountMap[currentComp.comp_type].count">Add More</a>
                  </div>
                  <!-- <only cat and prods end> -->
                  <div class="bannerEdit bannercropdiv" ng-show="fnShowField('imageUpload')">
                    <h2>
                      <a class="make_app_next" href="#" style="background:#ffcc00; float: right; margin: 0;" ng-click="addMoreImages()" ng-if="currentComp.elements.element_array.length < imagesCountMap[currentComp.comp_type].count">Add More</a>
                    </h2>
                    <div class="clear"></div>
                    <div class="banner_img_change">
                      <div upload-image current="imgElem" comp="currentComp" index="$index" ng-repeat="imgElem in currentComp.elements.element_array" ng-click="$parent.currImgIndex = $index;"></div>
                      <div class="clear"></div>
                      <span class="banner_cont">These images will appear on the top of your home screen and are the first thing your users might notice in your app. You have the option of keeping it as a three-image slideshow, through which you can either highlight the best deals, signature products, or anything else you want to highlight.</span> <a class="read_more">Read More...</a>
                      <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <div id='banner_message'></div>
                    <div id="canvasShow" style="display: none;"></div>
                    <input type="hidden" name="filenamest" id="filenamest" />
                    <input type="hidden" name="filetypest" id="filetypest" />
                  </div>
                  <div class="clear"></div>
                </div>
                <a class="make_app_next" href="" style="background:#ffcc00;" ng-click="postAppDtls()">Save &amp; Continue</a>
                <a class="make_app_next addproduct" style="margin-right: 10px;" href="" ng-click="sendToOpencart()" ng-show="fnShowField('catProdSelect') || fnShowField('imgHeadSubhead') || fnShowField('onlyCatWithProdDropdwn')">Add Products</a>
                <div class="clear"></div>
              </div>
            </div>
			</form>
		  </div>
            
          <div class="mid_right_box additional-features" ng-class="{ 'active': currentBlock === 4 }">
            <div class="mid_right_box_head" ng-click="currentBlock = (currentBlock !== 4 ? 4 : -1);">
              <h1>Additional Features</h1>
              <h2>Add additional features to your app</h2>
              <div class="clear"></div>
              <span>
                <i class="fa fa-angle-right" ng-class="{ 'fa-rotate-90': currentBlock === 4 }"></i>
              </span>
            </div>
            <div class="mid_right_box_body" ng-show="currentBlock === 4">
              <div class="design_menu_box">
                <div class="catalogue_add_feature feedbackform">
                  <div class="catalogue_input_group">
                    <input type="checkbox" value="1" ng-model="appDtls.feedback_dtls.is_feedback" />
                    <label for="feedback">Feedback Form</label>
                    <div class="clear"></div>
                  </div>
                  <div class="catalogue_detail_input">
                    <ul>
                      <li>
                        <label>Email Id:</label>
                      </li>
                      <li class="fullLength">
                        <input type="email" class="required-input" placeholder="Enter Email Id" ng-model="appDtls.feedback_dtls.feedback_email" />
                        <div class="clear"></div>
                        <span>Enter email id to where you want your users to send their feedback.</span></li>
                      <div class="clear"></div>
                    </ul>
                  </div>
                  <div class="catalogue_detail_input">
                    <ul>
                      <li>
                        <label>Support No.:</label>
                      </li>
                      <li class="fullLength">
                        <input type="number" class="required-input" min="0" maxlength="15" placeholder="Enter Phone Number" ng-model="appDtls.feedback_dtls.feedback_no" str-to-num />
                        <div class="clear"></div>
                        <span>Enter your support phone number where you want your users to contact you for help.</span></li>
                      <div class="clear"></div>
                    </ul>
                  </div>
                </div>
                <div class="catalogue_add_feature contactus">
                  <div class="catalogue_input_group">
                    <input type="checkbox" value="1" ng-model="appDtls.contact_details.is_contactus" />
                    <label for="contact">Contact Us</label>
                    <div class="clear"></div>
                  </div>
                  <div class="catalogue_detail_input">
                    <ul>
                      <li>
                        <label>Email Id:</label>
                      </li>
                      <li class="fullLength">
                        <input type="email" class="required-input" placeholder="Enter Email Id" ng-model="appDtls.contact_details.contact_email" />
                        <div class="clear"></div>
                        <span>Enter email id to where you want your users to contact you for help.</span></li>
                      <div class="clear"></div>
                    </ul>
                  </div>
                  <div class="catalogue_detail_input">
                    <ul>
                      <li>
                        <label>Support No.:</label>
                      </li>
                      <li class="fullLength">
                        <input type="number" class="required-input" min="0" maxlength="15" placeholder="Enter Phone Number" ng-model="appDtls.contact_details.contact_phone" str-to-num />
                        <div class="clear"></div>
                        <span>Enter your support phone number where you want your users to contact you for help.</span></li>
                      <div class="clear"></div>
                    </ul>
                  </div>
                </div>
                <div class="catalogue_add_feature termsandcon">
                  <div class="catalogue_input_group">
                    <input type="checkbox" value="1" ng-model="appDtls.tnc.is_tnc" />
                    <label for="tandc">Terms &amp; Conditions</label>
                    <div class="clear"></div>
                  </div>
                  <div class="catalogue_detail_input">
                    <ul>
                      <li>
                        <label>Link:</label>
                      </li>
                      <li class="fullLength">
                        <input type="url" class="required-input" placeholder="Enter Link for Terms & Conditions" ng-model="appDtls.tnc.tnc_email" />
                        <div class="clear"></div>
                        <span>Enter URL for Terms &amp; Conditions Page</span></li>
                      <div class="clear"></div>
                    </ul>
                  </div>
                </div>
                <div class="catalogue_add_feature invoice">
                    <div class="catalogue_input_group">
                        <input type="checkbox" value="1" ng-model="appDtls.order_dtls.is_order" />
                        <label for="invoice">Send Order Confirmation Email To Your Users</label>
                        <div class="clear"></div>
                    </div>
                    <div class="company_logo">
                        <div upload-image current="appDtls.logo_dtls" comp="appDtls.logo_dtls" index="$index" ng-click="currentComp = appDtls.logo_dtls;"></div>
                        <div class="clear"></div>
                        <span>Add Company Logo<br />to be visible in the form.</span>
                    </div>
                    <div class="clear"></div>
                    <div class="catalogue_detail_input">
                        <ul>
                            <li><label>Choose Pack:</label></li>
                            <li class="fullLength">
                                <select ng-model="appDtls.order_dtls.package">
                                  <option value="">Select</option>
                                  <option value="1">200 EDM Pack (&#8377; 1000)</option>
                                  <option value="3">500 EDM Pack (&#8377; 2000)</option>
                                </select>
                            </li>
                            <div class="clear"></div>
                        </ul>
                    </div>
                    <div class="catalogue_detail_input">
                        <ul>
                            <li><label>Email Id:</label></li>
                            <li class="fullLength">
                              <input type="email" class="required-input" placeholder="Enter Email Id" ng-model="appDtls.order_dtls.orderconfirm_email" />
                              <div class="clear"></div>
                              <span>Enter email id you want to add in order confirmation mail to your users.</span>
                            </li>
                            <div class="clear"></div>
                        </ul>
                    </div>
                    <div class="catalogue_detail_input">
                        <ul>
                            <li><label>Support No.:</label></li>
                            <li>
                              <input type="number" class="required-input" min="0" maxlength="15" placeholder="Enter Phone Number" ng-model="appDtls.order_dtls.orderconfirm_no" str-to-num />
                              <div class="clear"></div>
                              <span>Enter your support phone number where you want your users to contact you for help.</span>
                            </li>
                            <div class="clear"></div>
                        </ul>
                    </div>
                </div> 
              </div>
              <a class="make_app_next" href="" style="background:#ffcc00;" ng-click="postAppDtls()">Save &amp; Continue</a>
              <div class="clear"></div>
            </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <a id="openModalW" data-target="#cropper-example-2-modal" data-toggle="modal" style="opacity:0.11;" >&nbsp;</a>
    <div class="modal fade" id="cropper-example-2-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header"> <a data-dismiss="modal" style="float: right; cursor: pointer; padding: 10px;">&times;</a>
            <h4 id="bootstrap-modal-label">Upload Image</h4>
            <div class="upload-btn"> <a class="make_app_next">Browse</a>
              <input class="sr-only inputImageChange" id="inputImage" name="file" type="file" />
            </div>
            <ul class="modal-title">
              <li>This image should be atleast <span class="resolution">{{ currentComp.dummy_dtls.width }}px &times; {{ currentComp.dummy_dtls.height }}px</span> resolution.</li>
              <li>Maximum image size should be of 2MB.</li>
              <li>Drag to adjust image &amp; scroll to zoomin/zoomout to fit in the area.</li>
            </ul>
          </div>
          <div class="modal-body">
            <div id="cropper-example-2" class="img-container"> <img src="" class="" id="modalimage1" alt="" />
              <div class="crop-controls"> <span style="position: relative; left: -50%;">
                <button class="zoom-controls" data-method="zoom" data-option="0.02" title="Zoom In"> <i class="fa fa-search-plus fa-lg"></i> </button>
                <button class="zoom-controls" data-method="zoom" data-option="-0.02" title="Zoom Out"> <i class="fa fa-search-minus fa-lg"></i> </button>
                </span> </div>
            </div>
          </div>
          <div class="modal-footer">
            <a style="float: none; cursor: pointer;" class="make_app_next" data-method="getCroppedCanvas" ng-click="updateCroppedImage($event)" ng-show="showCropBtn">Crop &amp; Save</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</section>
</section>

<script>
    var text_color = '<?php echo $app_data['text_color'] != '' ? $app_data['text_color'] : '#1abc9c'; ?>';
    var discount_color = '<?php echo $app_data['discount_color'] != '' ? $app_data['discount_color'] : '#1abc9c'; ?>';
    var app_bg_color = '<?php echo $app_data['background_color'] != '' ? $app_data['background_color'] : '#1abc9c'; ?>';
    var upreview = '<?php echo $preview; ?>';
    var gl_app_id = '<?php echo $app_id; ?>';
    var gl_app_hash_id = '<?php echo $app_data['app_id']; ?>';
    var gl_author_id = '<?php echo $getAuthor['id']; ?>';
    var gl_theme_id = '<?php echo $themeid; ?>';
    var gl_cat_id = '<?php echo $catid; ?>';
    var gl_token = '<?php echo $token; ?>';
</script>


<script src="js/require.js"></script>
<script src="js/require.config.js"></script>
<script src="js/main-catalogue.js?v=1"></script>

<!-- script src="js/retail-ug.js"></script -->

</body>

</html>