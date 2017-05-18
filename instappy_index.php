<?php require_once('includes/instappy_header.php');?>
<?php
session_start();
require_once('config/db.php');
require_once('modules/checkapp/checkappclass.php');
$checkapp = new checkapp();

$url = $checkapp->siteurl();

//$realescape = $mysqli->real_escape_string("SELECT * FROM `action_type`");
//$query = $mysqli->query($realescape);
//$result = mysqli_fetch_assoc($query);
unset($_SESSION['appid']);
?>
    <div class="banner">
    	<img src="instappy/images/banner1.jpg" class="active">
    	<img src="instappy/images/banner1.jpg">
    	<img src="instappy/images/banner1.jpg">
        <div class="banner_text">
        	<p>The future of mobile is the future<br>of everything.</p>
        </div>
        <div class="create_app" id="cp">
        	<a href="#create-app">Create App</a>
            <em>Free</em>
        </div>
        <div class="lets_talk slide">
        	<div class="lets_talk_btn"> </div>
        	<form name="lets_talk" id="lets_talk" class="lets_talk_form" method='post' action=''>
            	<input type="text" class="required" maxlength="50" placeholder="Name" id="lets_talk_name" name="lets_talk_name" value="">
                <div class="two_input">
	            	<input type="text"  class="required email" placeholder="Email" id="lets_talk_email" name="lets_talk_email" value="">
	            	<input type="text" class="required" maxlength="12" minlength="10" placeholder="Phone No." id="lets_talk_phone" name="lets_talk_phone" value="" onKeyPress="return isNumber(event)">
                </div>
                <div class="two_input">
	            	<input type="text" class="required" maxlength="50" placeholder="Organisation Name" id="lets_talk_org" name="lets_talk_org" value="">
	            	<select class="required" id="lets_talk_org_size" name="lets_talk_org_size">
						<option value="">Organisation Size</option>
						<option value="0-50">0-50</option>
						<option value="50-500">50-500</option>
						<option value="50-above">500-above</option>
					</select>
                </div>
                <select class="required" id="lets_talk_industry" name="lets_talk_industry">
					<option value="">Industry</option>
				</select>
                <select class="required" id="lets_talk_app_type" name="lets_talk_app_type">
					<option value="">Types of Apps</option>
					<option value="Marketing Team">Marketing Team</option>
					<option value="Showcase Catalogue">Showcase Catalogue</option>
					<option value="Visual App for SMB">Visual App for SMB</option>
					<option value="App for Sales Team">App for Sales Team</option>
					<option value="M-Commerce">M-Commerce</option>
				</select>
                <input type="text" class="required"  maxlength="50" placeholder="additional Info." id="lets_talk_additional_info" name="lets_talk_additional_info" value="">
                <input type="submit" value="send">
				<img src="images/ajax-loader.gif" class="preloader">
				<div id="lets_talk_result"></div>
            </form>
        </div>
    </div>
    <div class="clear"></div>



<div class="mn-cont clearfix">
	<span class="ct-head">Content Publishing ?</span>
	<div class="dsc-holder">
			<div class="lf-tx">
				
				<p>Build instant, affordable, intuitive, and stunning! Fully native applications for iOS, Android, and Windows Phone instantly with Instappy. It’s hassle free, it’s quick, and you don't need any coding skills.Instappy has everything you need to create amazing, fully loaded, and original apps. Choose from our built for success, fully customisable templates or create your own to launch your mobile application for smartphones and tablets in an instant</p>
			</div>
			<div class="rt-vd">
				<a href="#"><img src="instappy/images/vid.png" alt=""/></a>
			</div>

	</div>

</div>
<?php	$result = $checkapp->indexCatTheme($url); 
	//echo "<pre>"; print_r($result); echo "</pre>";
?>

<div class="cat-cont clearfix" id="create-app">
	<div class="cat-lft">
		<span class="cat-hd">Categories</span>
		<ul class="cat-list">
				<!-- <li><div class="uprdiv"><em></em>Featured <i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li>
				<li><div class="uprdiv">Restraunt<i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li>
				<li><div class="uprdiv">Business & services <i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li>
				<li><div class="uprdiv">Creative <i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li>
				<li><div class="uprdiv">Publishing <i class="fa fa-angle-down"></i></div>
				<div class="innr-list">
						<span>Band/DJ</span>
						<span>Film</span>
						<span>Performing arts</span>
						<span>Photographer</span>
						<span>Author/Writer</span>
						<span>Handicraft</span>
						<span>Portfolio</span>
				</div>
				</li> -->
				<?php foreach ($result['catli'] as $key => $value) {
					echo $value;
				}?>
		</ul>

	</div>
	<div class="cat-ryt">
		<div class="cat-head">
			<span>Featured Template</span>
			<span>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</span>
			 
			<!-- <ul class="sht-lst">
				<li>
					<a href="#">
						<img src="instappy/images/1.png" alt="">
						<span>Name 1</span>
					</a>
				</li>
				<li>
					<a href="#">
						<img src="instappy/images/2.png" alt="">
						<span>Name 1</span>
					</a>
				</li>
				<li>
					<a href="#">
						<img src="instappy/images/1.png" alt="">
						<span>Name 1</span>
					</a>
				</li>
				<li>
					<a href="#">
						<img src="instappy/images/1.png" alt="">
						<span>Name 1</span>
					</a>
				</li>
				<li>
					<a href="#">
						<img src="instappy/images/2.png" alt="">
						<span>Name 1</span>
					</a>
				</li>
				<li>
					<a href="#">
						<img src="instappy/images/1.png" alt="">
						<span>Name 1</span>
					</a>
				</li>
				
			</ul> -->
			<?php foreach ($result['themes'] as $key2 => $value2) {
                    echo $value2;
                }
//          ?>
		</div>

	</div>

</div>




	<div class="process">
    	<div class="process_inner">
            <h2>Process</h2>
            <p>Infographic which needs to be HTMLISED</p>
        </div>
    </div>
<?php require_once('includes/instappy_footer.php');?>    