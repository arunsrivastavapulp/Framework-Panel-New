<?php
require_once('modules/user/notifications.php');
$profile = new Notification();

 $timezone = "UTC";
    date_default_timezone_set($timezone);
    $utc = gmdate("M d Y h:i:s A");    
    $date = date('Y-m-d G:i:s', strtotime($utc)); 

$results = $profile->get_all_apps($date);
require_once('modules/myapp/myapps.php');
$app = new MyApps();
$data = array();

foreach ($results as $val) {
    if ($val['is_delivered'] == 0) {
        $data['app_id'] = $val['app_id'];
        $data['title'] = $val['title'];
        $data['desc'] = $val['message'];
        $data['layoutType'] = 2;
        $out = $app->send_notification($data, 'noti');
        if ($out == 'success') {
            $profile->update_schdule_notification($data['app_id'], $val['id']);
        }
    } else {
        echo "No Notification scheduled for today.";
    }
}
?>