<?php
error_reporting(0);
require_once('config/db.php');
$db = new DB();
$apiUrl = $db->catalogue_url();
$appid = $_REQUEST['app_id'];
//$appid = '3766';
$postdata = http_build_query(array(
    'app_id' => $appid
));
$opts = array('http' =>
		array(
		'method'=> 'POST',
		'header'=> 'Content-type: application/x-www-form-urlencoded',
		'content' => $postdata
		)
); 
$expertArray=array();
$context= stream_context_create($opts);
$result = file_get_contents($apiUrl.'catalogue/ecommerce_catalog_api/webcategories.php/catList', false, $context);
$values = json_decode($result);
$count = count($values->main_category_array);
if($count != 0){
?>

<div class="background_label">
<label>Choose a Category:</label>
</div>
<div class="background_colorbox">
 <select class="" id="selectedcategory" name="selectedcategory">
<option value="0">Select</option>
<?php
foreach($values->main_category_array as $value){
?>
<option haschild="<?php echo $value->is_child_category; ?>" value="<?php echo $value->itemid; ?>"><?php echo $value->itemheading; ?></option>
<?php } ?>
</select>
</div>
<?php }else{?>
<div class="background_label">
<label>Choose a Category:</label>
</div>
<div class="background_colorbox">
 <select class="" id="selectedcategory" name="selectedcategory">
<option value="0">No Category Available</option>

</select>
</div>
<?php }?>
