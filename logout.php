
<?php
session_start();
require_once 'config/db.php';
$db = new DB();
    ?>
    <script type="text/javascript">
    $.ajax({
        url : 'loginCrmCall.php',
        type : 'POST',
        async : true,
        success : function() {
        }
    });
    </script>
      
        <?php
 if(isset($_GET['reseller_id']) && $_GET['reseller_id']>0) {
           // $return_url=$_SESSION['return_url'];
     $return_url = isset($_SESSION['return_url'])?$_SESSION['return_url']:$db->resellerurl();       
     $app_id=$_SESSION['appid'];
        session_unset();
        session_destroy();
        ob_start();
        if(strrpos($return_url, 'addapp')==false)
        header("location:". $return_url."");
        else{
		if($app_id){
        header("location:". $return_url.'?app_id='.$app_id."");
			}
			else{
       header("location:". substr($return_url,0,-7)."");
			}
		}
        ob_end_flush(); 
        exit();
    }
    else if($_SESSION['cust_reseller_id'] &&  $_GET['is_reseller']>0){
        header("location:". $db->reseller_url()."");        
    ob_end_flush(); 
        exit();
    }
 else{  
session_unset();
session_destroy();
ob_start();
header("location:index.php");
ob_end_flush(); 
exit();
}
?>