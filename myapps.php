<?php

require_once ('config/db.php');

class MyApps extends Db {

    var $db,$url;

    function __construct() {
        $this->db = $this->dbconnection();
        $this->url = $this->siteurl();
    }

    function check_publish_app($appid) {
        $username = $_SESSION['username'];
        $custid = $_SESSION['custid'];
        $getuserid = "select id from author where custid='$custid'";
        $stmtuserid = $this->db->prepare($getuserid);
        $stmtuserid->execute();
        $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
        $userId = $resultuserid['id'];
        
        $sql = "select id from app_data where id='$appid' and published=1 and author_id='$userId'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if($results['id']!=''){
            return 1;
        }
    }

    function publish_app_img($appid) {
        $sql = "select app_image from app_data where id='$appid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results['app_image'];
    }
    
    function app_name($appid) {
        $sql = "select summary,created,updated from app_data where id='$appid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }
function check_user_exist($email,$custid){
	$sql ="select id,first_name,avatar from author where custid=$custid";
	$stmt=$this->db->prepare($sql);
	$stmt->execute();
	$results=$stmt->fetch(PDO::FETCH_ASSOC);
	return $results;
}

    function get_all_apps($author_id, $type, $page) {
        $num_rec_per_page = 6;
        if (isset($page) && $page > 0) {
            $page = $page;
        } else {
            $page = 1;
        };
        $start_from = ($page - 1) * $num_rec_per_page;
        $sql = "select * from app_data where author_id='$author_id' and published=$type order by id DESC limit $start_from, $num_rec_per_page";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    function count_my_apps($author_id, $type) {
        $sql = "select * from app_data where author_id='$author_id' and published=$type order by id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($results);
    }

    function ajax_more_apps($data) {
        $page = $data['page'];
        $type = $data['type'];
        $user_id = $data['user_id'];
        $results = $this->get_all_apps($user_id, $type, $page);
        $cnt = $this->count_my_apps($user_id, $type);
        $str = '';
        $publish = 0;
        $draft = 0;
        foreach ($results as $val) {
				$file = 'appedit.php'; 
            if ($val['published'] == 1) {
                $img = $val['app_image'];
                           if($img==''){
                               $img='images/myapp1.jpg';
                           }
                $appid = $val['id'];
                $download = '<div class="apps_box_download">                   
                            <div data-aapid="' . $val['id'] . '" class="downloadapp"><img src="images/app_download.png"></div>
                        </div>';
                $str.='<div class="apps_box">
                        <a href="'.$file.'?appid=' . $val['id'] . '"><img src="' . $img . '"></a>
                        <div class="apps_box_name">
                            <h2><a href="'.$file.'?appid=' . $val['id'] . '">' . stripslashes($val['summary']). '</a></h2>
                            <p><a href="'.$file.'?appid=' . $val['id'] . '" id="downloadapp">Android</a></p>
                        </div>' . $download . '                      
                        <div class="clear"></div>
                    </div>';
                $publish++;
            } else {
                $img = $val['app_image'];
                if($img==''){
                               $img='images/myapp1.jpg';
                           }
                $appid = $val['id'];
                $download = '<div class="apps_box_download">
                            <div data-aapid="' . $val['id'] . '" class="deleteapp"><img src="images/app_delete.png"></div>                          
                        </div>';

                $str.='<div class="apps_box">
                        <a href="'.$file.'?appid=' . $val['id'] . '"><img src="'.$img.'"></a>
                        <div class="apps_box_name">
                            <h2><a href="'.$file.'?appid=' . $val['id'] . '">' . stripslashes($val['summary']) . '</a></h2>
                            <p><a href="'.$file.'?appid=' . $val['id'] . '">Android &amp; iOS</a></p>
                        </div>' . $download . '                      
                        <div class="clear"></div>
                    </div>';
                $draft++;
            }
        }
        echo $str . "--" . $cnt . "--" . $publish . "--" . $draft;
    }
	
	function get_app_type($appid)
	{
		$sql = "select type_app from app_data where id='$appid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results['type_app'];
	}
	function send_notification($data){
		$appid=$data['app_id'];
		$sql = "select push_token,platform from user_appdata where app_id=$appid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$title=$data['title'];
		$desc=$data['desc'];
		$result="";
		$title=isset($title)?$title:'';
		 $title= utf8_encode($title);
		$msg=isset($desc)?$desc:'';
        $heading = ucfirst($title);
        $layoutType = $data['layoutType'];
        $message = ucfirst($msg);
        $imageUrl = '';
        $action_tag = '';
        $action_data = '';
        $windowsimageUrl = '';
        if ($imageUrl != '') {
            $layoutType = 1;
        } else {
            $layoutType = 2;
        }


        $msgW = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
                "<wp:Notification xmlns:wp=\"WPNotification\">" .
                "<wp:Toast>" .
                "<wp:Text1>" . htmlspecialchars($heading) . "</wp:Text1>" .
                "<wp:Text2>" . htmlspecialchars($message) . "</wp:Text2>" .
                "<wp:Param>/Screen2.xaml?ScreenID=" . $action_data . "</wp:Param>" .
                "</wp:Toast>" .
                "</wp:Notification>";

        $array = array('heading' => $heading, 'message' => $message, 'imageUrl' => $imageUrl, 'action_tag' => $action_tag, 'action_data' => $action_data, 'layoutType' => $layoutType);

 foreach($results as $val){
	 if($val['platform']==1){
  $arr= $this->send_gcm_notify($val['push_token'], $array);
	 }  
	 	 if($val['platform']==2){
  $arr= $this->iosSendnotification($val['push_token'], $array);
	 } 
	
 }
	echo "success";
	}
function send_gcm_notify($reg_id, $message) {
        $fields = array(
            'registration_ids'  => array( $reg_id ),
            'data'              => array( "message" => $message ),
        );
        $headers = array(
            'Authorization: key=AIzaSyAD6m3oCh2AeVNTM5uPE3cmeFrSVVHW7YA',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Problem occurred: ' . curl_error($ch));
        }

        curl_close($ch);
		$results=json_decode($result);
		if($results->success==1){
		return "success";		
		}
		else{
		return "fails";
		}		

		
    }
function iosSendnotification($pushtoken, $totalmsg) {
    $passphrase = 'testpush';
    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', __DIR__.'/ck.ppk');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
    $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp)
        exit("Failed to connect: $err $errstr" . PHP_EOL);
//    echo 'Connected to APNS' . PHP_EOL;
// Create the payload body
    $body['aps'] = array(
        'alert' => $totalmsg['heading'].' - '.$totalmsg['message'],
        'sound' => 'default',
        'push_detail' => $totalmsg
    );
    $authToken = $pushtoken;

// Encode the payload as JSON
    $payload = json_encode($body);

    // Build the binary notification
    $msg = chr(0) . pack('n', 32) . pack('H*', $authToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
    $result = fwrite($fp, $msg, strlen($msg));
    fclose($fp);
   if ($result) {
       return "success";
   } else {
       return "fails";
   }
	}
}
