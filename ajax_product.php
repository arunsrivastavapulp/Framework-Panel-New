<?php
error_reporting(0);
require_once('config/db.php');
$db = new DB();
$apiUrl = $db->catalogue_url();
$appid = $_REQUEST['app_id'];
$categoryid = $_REQUEST['category_id'];
//$appid = '3045';
$postdata = http_build_query(array(
    'app_id' => $appid,
	'category_id' => $categoryid
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
$result = file_get_contents($apiUrl.'catalogue/ecommerce_catalog_api/webproducts.php/prodList', false, $context);

$values = json_decode($result);

?>
<div class="removeCategory clearfix">								
<div class="background_label">
<label>Choose a Product:</label>
</div>
<div class="background_colorbox">
<select class="" id="selectedproduct" name="selectedproduct">
<option value="0">Select</option>
<?php
foreach($values->products_array as $value){
?>
<option haschild="<?php echo $value->is_child_category; ?>" value="<?php echo $value->itemid; ?>"><?php echo $value->itemheading; ?></option>
<?php } ?>
</select>
</div>
</div>