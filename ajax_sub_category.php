<?php
error_reporting(0);
require_once('config/db.php');
$db = new DB();
$apiUrl = $db->catalogue_url();
$appid = $_REQUEST['app_id'];
$categoryid = $_REQUEST['category_id'];
$offset = $_REQUEST['offset'];
//$appid = '3766';
$postdata = http_build_query(array(
    'app_id' => $appid,
	'category_id' => $categoryid,
	'offset' => $offset
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
$result = file_get_contents($apiUrl.'catalogue/ecommerce_catalog_api/websubcategories.php/catList', false, $context);

$values = json_decode($result);

?>

<div class="removeCategory clearfix">								
<div class="background_label">
<label>Choose a Sub Category:</label>
</div>
<div class="background_colorbox">
<select  class="selectedsubcategory"  name="selectedsubcategory">
<option value="0">Select</option>
<?php
foreach($values->preference_array as $value){
?>
<option haschild="<?php echo $value->is_child_category; ?>" value="<?php echo $value->itemid; ?>"><?php echo $value->itemheading; ?></option>
<?php } ?>
</select>
</div>
</div>