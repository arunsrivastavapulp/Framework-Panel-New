<?php
session_start();
if(isset($_POST['type'])&& $_POST['type']=='reset_password'){
require_once('modules/login/login-check.php');
require_once('modules/user/userprofile.php');
$login=new Login();
$login->reset_password($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='country_state'){
require_once('modules/user/userprofile.php');
$profile=new UserProfile();
$profile->get_states($_POST,$_POST['custid']);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='schdule_notification'){
require_once('modules/user/notifications.php');
$profile=new Notification();
$profile->schdule_notification($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='ios_notification_ppk_file'){
require_once('modules/user/notifications.php');
$profile=new Notification();
$profile->ios_notification_ppk_file($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='delete_notification'){
require_once('modules/user/notifications.php');
$profile=new Notification();
$profile->delete_notification($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='html'){
require_once('modules/savehtml/savehtml.php');
$save=new SaveHtml();
$save->savehtml($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='app_contact_details'){
require_once('modules/savehtml/savehtml.php');
$save=new SaveHtml();
$save->app_contact_details($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='deletehtml'){
require_once('modules/savehtml/savehtml.php');
$delete=new SaveHtml();
$delete->deletehtml($_POST);
exit;
}

if(isset($_POST['type'])&& $_POST['type']=='trial'){
require_once('modules/login/send-file.php');
$send=new Send();
$_POST['author']=$_SESSION['username'];
$_POST['appid']=$_SESSION['appid'];
$send->send_app_details($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='save_phone_img'){
require_once('modules/myapp/save_imgs.php');
$send=new Save_images();
$_POST['appid']=$_SESSION['appid'];
$send->save_phone_img($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='save_7_inch_tablet_img'){
require_once('modules/myapp/save_imgs.php');
$send=new Save_images();
$_POST['appid']=$_SESSION['appid'];
$send->save_7_inch_tablet_img($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='save_10_inch_tablet_img'){
require_once('modules/myapp/save_imgs.php');
$send=new Save_images();
$_POST['appid']=$_SESSION['appid'];
$send->save_10_inch_tablet_img($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='ios_publish_images'){
require_once('modules/myapp/save_imgs.php');
$send=new Save_images();
$_POST['appid']=$_SESSION['appid'];
$send->ios_publish_images($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='save_featured_icons'){
require_once('modules/myapp/save_imgs.php');
$send=new Save_images();
$_POST['appid']=$_SESSION['appid'];
$send->save_featured_icons($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='save_graphic_icons'){
require_once('modules/myapp/save_imgs.php');
$send=new Save_images();
$_POST['appid']=$_SESSION['appid'];
$send->save_graphic_icons($_POST);
exit;
}

if(isset($_POST['type'])&& $_POST['type']=='contact-us'){
require_once('modules/login/login-check.php');
$login=new Login();
$login->contact_us($_POST);
$login->vendorrequest($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='notification'){
require_once('modules/myapp/myapps.php');
$app=new MyApps();
$app->send_notification($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='save_fb_app_id'){
require_once('modules/myapp/myapps.php');
$app=new MyApps();
$app->save_fb_app_id($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='subscribe'){
require_once('modules/login/login-check.php');
$login=new Login();
$login->subscribe($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='support'){
require_once('modules/login/login-check.php');
$login=new Login();
$login->support($_POST);
$login->vendorrequestSupport($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='add_gaccount'){
require_once('modules/user/statistic.php');
$statics=new Statics();
$statics->add_google_acc($_POST);
exit;
}
if(isset($_POST['type'])&& $_POST['type']=='send_analytics_request'){
require_once('modules/user/statistic.php');
$statics=new Statics();
$statics->send_analytics_request($_POST);
exit;
}
if(isset($_POST['type'])){
		require_once('modules/opencart-integration/opencart-integration.php');
		$send=new Opencart();
		if($_POST['type']=='save_catalogue_app'){

		//is_feedback
		if(isset($_POST['is_feedback']))
		{
			$_POST['is_feedback'] = 1;
		}
		else
		{
			$_POST['is_feedback'] = 0;
		}

		//is_contactus
		if(isset($_POST['is_contactus']))
		{
			$_POST['is_contactus'] = 1;
		}
		else
		{
			$_POST['is_contactus'] = 0;
		}

		//is_tnc
		if(isset($_POST['is_tnc']))
		{
			$_POST['is_tnc'] = 1;
		}
		else
		{
			$_POST['is_tnc'] = 0;
		}

		//is_order
		if(isset($_POST['is_order']))
		{
			$_POST['is_order'] = 1;
		}
		else
		{
			$_POST['is_order'] = 0;
		}

		if($_POST['action']=='add'){
		$send->save_catalogue_app($_POST,$_FILES);
		}	
		if($_POST['action']=='edit'){

		$send->update_catalogue_app($_POST,$_FILES);
		}
		exit;
		}

		if($_POST['type']=='catlog_app_login_check'){
		$status = $send->userDetails($_REQUEST['email']);
		if($status==true){	
		$send->send_opencart($_SESSION['username'],$_SESSION['password']);
		}
		exit;
		}
		if($_POST['type']=='vendor_register'){
		$send->vendor_register($_POST);
		exit;
		}
		if($_POST['type']=='suggest'){
		$send->suggest_catagory($_POST);
		exit;
		}
		if($_POST['type']=='reseller_app_qa'){
		$send->reseller_app_qa($_POST);
		exit;
		}
}
////for reseller login axjax
if($_POST['type']=='checkEmail'){
    $email=$_POST['email'];
	require_once('modules/login/resellerCheck.php');
	$check=new ResellerCheck();
	$res=$check->checkUserEmail($email);
	echo json_encode($res);
	exit;
}
if($_POST['type']=='getQuestions'){
	
    $email=$_POST['email'];
	require_once('modules/login/resellerCheck.php');
	$check=new ResellerCheck();
	$res=$check->getSecurityQuestion();
	return $res;
	
}
////end reseller login axjax


?>