<?php
session_start();
require_once ('../../config/db.php');
$connection = new Db();
$mysqli = $connection->dbconnection();


if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    //Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
        //HTTP_REFERER verification
        if ($_POST['token'] == $_SESSION['token']) {
//            echo 'success';
            $data = $_POST['data'];
            $appid = $_POST['hasid'];
            $is_ajax = $_POST['is_ajax'];

            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];
			
			
			$sql110  = "select * from app_data WHERE id='".$appid."'";
			$stmt110 = $mysqli->prepare($sql110);
			$stmt110->execute();
			$splashdata = $stmt110->fetch(PDO::FETCH_ASSOC);
			
			
            if (trim($custid) != '' && $appid != '' && $data != '') {
				 $query = 'UPDATE app_data SET splash_screen_id="'.$data.'" WHERE id="'.$appid.'"';
				$stmt = $mysqli->prepare($query);
                $stmt->execute();
				
				$sql111  = "select a.*, p.name from app_data a left join platform p on p.id=a.platform where a.id='".$appid."'";
				$stmt111 = $mysqli->prepare($sql111);
				$stmt111->execute();
				$results111 = $stmt111->fetch(PDO::FETCH_ASSOC);
				
				if(!empty($results111))
				{	
					if($splashdata['splash_screen_id'] == '' || $splashdata['splash_screen_id'] == 1)
					{
						//$userprofile = new UserProfile();
						//$user        = $userprofile->getUserByEmail($_SESSION['username']);
						$sql11 = "select id,first_name,last_name from author where email_address='".$_SESSION['username']."'";
						$stmt11 = $mysqli->prepare($sql11);
						$stmt11->execute();
						$results11 = $stmt11->fetch(PDO::FETCH_ASSOC);
						
						
						$user      = $results11;
						
						$basicUrl     = $connection->siteurl();
						// send email for app save
						/*
						if(strtolower($results111['name']) == 'android')
						{
						*/
							$csubject     = 'Congratulations! Your iOS and Android app is ready to go live!';
							$chtmlcontent = file_get_contents('../../edm/android_app_about_to_live.php');
						/*
						}
						elseif(strtolower($results111['name']) == 'ios')
						{
							$csubject     = 'Congratulations! Your iOS App is ready to go live!';
							$chtmlcontent = file_get_contents('../../edm/ios_app_about_to_live.php');
						}
						*/
						
						$clastname    = $user['last_name'] != '' ? ' ' . $user['last_name'] : $user['last_name'];
						$cname        = ucwords($user['first_name'] . $clastname);
						$chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
						$chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
						$chtmlcontent = str_replace('{app_name}', $results111['summary'], $chtmlcontent);
						
						$cto          = $username;
						$cformemail   = 'noreply@instappy.com';
						$key          = 'f894535ddf80bb745fc15e47e42a595e';
						//$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
						//$customerhead = file_get_contents($url);

						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
							CURLOPT_POST => 1,
							CURLOPT_POSTFIELDS => array(
								'api_key' => $key,
								'subject' => $csubject,
								'fromname' => 'Instappy',
								'from' => $cformemail,
								'content' => $chtmlcontent,
								'recipients' => $cto
							)
						));
						$customerhead = curl_exec($curl);

						curl_close($curl);
					}
				}
			}
		}
	}
}	