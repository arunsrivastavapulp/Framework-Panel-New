<?php
$currentTimeout= ini_get('session.gc_maxlifetime');

// Change session timeout value for a particular page load  - 1 month = ~2678400 seconds
ini_set('session.gc_maxlifetime', 2678400);
session_start();

if (isset($_POST['username'])) {
    require_once('modules/login/login-check.php');
    $login = new Login();
    if ($_POST['type'] == 'login')
        $login->check_login($_POST);
    if ($_POST['type'] == 'register'){
		// $original_captch=$_SESSION['random_number'];
		// $get_captch=trim($_POST['captcha_code']);
		// if($get_captch!=$original_captch){
			// echo "401";
		// }
		// else{
        $login->user_register($_POST);
        $login->signuprequest($_POST);
		//}
	}
		
		if ($_POST['type'] == 'autoLogin')
        $login->check_autologin($_POST);
	

    if ($_POST['type'] == 'fb') {
        $fid = $_POST['userFbId'];
        $img = file_get_contents('https://graph.facebook.com/' . $fid . '/picture?width=200&height=200');
        $file = dirname(__file__) . '/avatars/' . $fid . '.jpg';
        file_put_contents($file, $img);
        $_POST['avatar'] = $fid . '.jpg';
        $login->fb_register($_POST);
    }
    if ($_POST['type'] == 'gplus') {
        $id = $_POST['userFbId'];
        $userJSON = @file_get_contents('http://picasaweb.google.com/data/entry/api/user/' . $id . '?alt=json');
        if ($userJSON) {
            $userArray = json_decode($userJSON, true);
            if ($userArray && isset($userArray["entry"]) && isset($userArray["entry"]["gphoto\$thumbnail"]) && isset($userArray["entry"]["gphoto\$thumbnail"]["\$t"])) {

                $file = dirname(__file__) . '/avatars/' . $id . '.jpg';
            }
        }
        file_put_contents($file, file_get_contents($userArray["entry"]["gphoto\$thumbnail"]["\$t"]));
        $_POST['avatar'] = $id . '.jpg';
        $login->gplus_register($_POST);
    }
    exit;
}
if (isset($_POST['check']) && $_POST['check'] == 'login') {
    if (isset($_SESSION['custid'])) {
        require_once('modules/login/login-check.php');
        $login = new Login();
        $results = $login->check_user_exist($_SESSION['username'], $_SESSION['custid']);
        //echo $results['id'] . '##' . $_SESSION['appid'];
		echo $results['id'] . '##' . $_SESSION['appid'] . '##' . $results['eflag'] . '##' . $results['mflag'].'##'.$_SESSION['cust_reseller_id']."##".$_SESSION['is_reseller'];
    }
    exit;
}
if (isset($_POST['email']) && $_POST['type'] == 'forgot') {
    require_once('modules/login/login-check.php');
    $login = new Login();
    $login->forgot_password($_POST);
    exit;
}
if (isset($_POST['type']) && $_POST['type'] == 'author_choose_app_type') {
    require_once('modules/login/login-check.php');
    $login = new Login();
    $login->author_choose_app_type($_POST);
    exit;
}
if (isset($_POST['user_id'])) {
    require_once('modules/myapp/myapps.php');
    $myapps = new MyApps();
    $myapps->ajax_more_apps($_POST);
    exit;
}

if (isset($_POST['check']) && $_POST['check'] == 'mail_confirm') {
    require_once('modules/login/login-check.php');
    $login = new Login();
    $login->email_confirmed($_SESSION['custid'],$_POST['check']);
    exit;
}
if (isset($_POST['check']) && $_POST['check'] == 'mob_confirm') {
    require_once('modules/login/login-check.php');
    $login = new Login();
    $login->email_confirmed($_SESSION['custid'],$_POST['check']);
    exit;
}
if (isset($_POST['check']) && $_POST['check'] == 'mobile_confirm') {
    require_once('modules/login/login-check.php');
    $login = new Login();
    $login->email_confirmed($_SESSION['custid'],$_POST['check']);
    exit;
}
if (isset($_POST['type']) && $_POST['type'] == 'resend') {
    require_once('modules/login/login-check.php');
    $login = new Login();
    $login->resend_email($_POST);
    exit;
}
?>