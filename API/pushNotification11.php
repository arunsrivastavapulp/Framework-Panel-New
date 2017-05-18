<?php

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'db.php';
\Slim\Slim::registerAutoloader();
/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();
$app->post('/pushnotify', 'sendpushNotification');

/**
 * Step 3: Define the Slim application routes.
 * Here we define several Slim application routes that respond.
 * to appropriate HTTP request methods. In this example, the second.
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`.
 * is an anonymous function.
 */
function windowspush($target, $msg1, $url, $delay = 2, $messageId = NULL) {

    $sendedheaders = array(
        'Content-Type: text/xml',
        'Accept: application/*',
        "X-NotificationClass: $delay"
    );

    if ($target != NULL)
        $sendedheaders[] = "X-MessageID:$messageId";
    if ($target != NULL)
        $sendedheaders[] = "X-WindowsPhone-Target:$target";

    $req = curl_init();
    curl_setopt($req, CURLOPT_HEADER, true);
    curl_setopt($req, CURLOPT_HTTPHEADER, $sendedheaders);
    curl_setopt($req, CURLOPT_POST, true);
    curl_setopt($req, CURLOPT_POSTFIELDS, $msg1);
    curl_setopt($req, CURLOPT_URL, $url);
    curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($req);
    curl_close($req);

    $result = array();
    foreach (explode("\n", $response) as $line) {
        $tab = explode(":", $line, 2);
        if (count($tab) == 2)
            $result[$tab[0]] = trim($tab[1]);
    }
    return $result;
}

function send_gcm_notify($pushtoken, $message) {
//    define("GOOGLE_GCM_URL", "https://android.googleapis.com/gcm/send");
//    define("GOOGLE_API_KEY", "AIzaSyDwiNvX-KUDiYY_if_z0esjGOxHCUrIGMA");
    $fields = array(
        'registration_ids' => array($pushtoken),
        'data' => array("message" => $message),
    );
    $headers = array(
        'Authorization: key=AIzaSyDwiNvX-KUDiYY_if_z0esjGOxHCUrIGMA',
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
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

    $results = json_decode($result);
    if ($results->success == 1) {
        $arr_count[] = $results->multicast_id;
        return "true";
    } else {
        return "false";
    }
}

function iosSendnotification($pushtoken, $totalmsg) {

    $passphrase = 'hinducalendar';
    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.ppk');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
    $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp)
        exit("Failed to connect: $err $errstr" . PHP_EOL);
//    echo 'Connected to APNS' . PHP_EOL;
// Create the payload body
    $body['aps'] = array(
        'alert' => $totalmsg['heading'],
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
//    print_r($result);
//    die;
//    if (!$result) {
//        echo 'Message not delivered' . PHP_EOL . '<br>';
//    } else {
//        echo "Message delivered successfully." . PHP_EOL . '<br>';
//    }

    fclose($fp);
}

function sendpushNotification() {
    if (isset($_POST['heading']) && isset($_POST['layoutType']) && isset($_POST['message']) && isset($_POST['imageUrl']) && isset($_POST['action_tag']) && isset($_POST['action_data']) && isset($_POST['windowsimageUrl'])) {

        $heading = $_POST['heading'];
        $layoutType = $_POST['layoutType'];
        $message = $_POST['message'];
        $imageUrl = $_POST['imageUrl'];
        $action_tag = $_POST['action_tag'];
        $action_data = $_POST['action_data'];
        $windowsimageUrl = $_POST['windowsimageUrl'];
        if ($imageUrl != '') {
            $layoutType = 1;
        } else {
            $layoutType = 2;
        }
        if ($windowsimageUrl != '') {

            $msgWT = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
                    "<wp:Notification xmlns:wp=\"WPNotification\">" .
                    "<wp:Tile>" .
                    "<wp:Count>1</wp:Count>" .
                    "<wp:SmallBackgroundImage>" . htmlspecialchars($windowsimageUrl) . "</wp:SmallBackgroundImage>" .
                    "<wp:WideBackBackgroundImage>" . htmlspecialchars($windowsimageUrl) . "</wp:WideBackBackgroundImage>" .
                    "<wp:BackBackgroundImage>" . htmlspecialchars($windowsimageUrl) . "</wp:BackBackgroundImage>" .
                    "<wp:BackTitle>" . htmlspecialchars($heading) . "</wp:BackTitle>" .
                    "<wp:BackContent>" . htmlspecialchars($message) . "</wp:BackContent>" .
                    "</wp:Tile>" .
                    "</wp:Notification>";
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

        $dbCon = getConnection();
        $auth_query = "select u.id as id,uad.platform as platform,uad.push_token as push_token from users u left join user_appdata uad on u.id=uad.user_id where uad.push_token!=0 or uad.push_token!=null or uad.push_token!=''";
        $auth_queryExecution = $dbCon->query($auth_query);
        $result_auth = $auth_queryExecution->fetchAll(PDO::FETCH_ASSOC);
//    print_r($result_auth);
//    die;
        foreach ($result_auth as $key => $value) {
            $pushtoken = $value['push_token'];
            if ($value['platform'] == 1) {

                send_gcm_notify($pushtoken, $array);
            } else if ($value['platform'] == 2) {

                iosSendnotification($pushtoken, $array);
            } else if ($value['platform'] == 3) {
                if ($windowsimageUrl != '') {
                    windowspush('token', $msgWT, $pushtoken);
                }
                windowspush('toast', $msgW, $pushtoken);
            }
        }
        $response = array("result" => 'success', "msg" => '200');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
    } else {
        $response = array("result" => 'false', "msg" => '500');
        $Basearray = array("response" => $response);
        $basejson = json_encode($Basearray);
        echo $basejson;
    }
}

$app->run();
