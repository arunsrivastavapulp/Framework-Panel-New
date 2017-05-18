<?php function getConnection() {
    try {
    
$db_username = "hemant";
        $db_password = "Fdfjkhg%#$#@4312AS";
        $conn = new PDO('mysql:host=pulpstrategyinstance.cil4anb91ydi.us-west-2.rds.amazonaws.com;dbname=app_design_new', $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
 
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    return $conn;
}

function baseUrl() {
    $baseURL = "http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/panel/frameworkphp/";
    return $baseURL;
}
function uploadImg() {
	$uploadImg = "../uploadImages/";
    return $uploadImg;
}
function uploadImgPath() {
    $uploadImgPath = "uploadImages/";
    return $uploadImgPath;
}
?>