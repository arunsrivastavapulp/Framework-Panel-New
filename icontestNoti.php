<?php 
$pushtoken='f41a9b108e788f39e805a7709aecb4dfbf9b58f7c5a13e06cbd26457fca6ca51f';
$ioscertificate='';
$ios_passphrase='';
$appid='2232';
$totalmsg= array('heading' => 'test', 'message' => 'This is test mail', 'imageUrl' => '', 'action_tag' => '', 'action_data' => "$appid", 'layoutType' => '2');
	
	if($ioscertificate !='' ||  $ioscertificate != NULL)
	{		
	 $path= '/var/www/html/panelimage/'.$appid.'/ioscertificate/'.$ioscertificate; 
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert',$path);
	stream_context_set_option($ctx, 'ssl', 'passphrase', $ios_passphrase);	
		
	}
else
{
	
	$passphrase = 'pulp123';
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', __DIR__ . '/ck.ppk');
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
}
	
			// Open a connection to the APNS server
	$fp = stream_socket_client(
		'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
	
	//if (!$fp)
	//	exit("Failed to connect: $err $errstr" . PHP_EOL);
			//    echo 'Connected to APNS' . PHP_EOL;
			// Create the payload body
	$body['aps'] = array(
		'alert' => $totalmsg['heading'] . ' - ' . $totalmsg['message'],
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
		echo "success";
	} else {
		echo "fails";
	}

