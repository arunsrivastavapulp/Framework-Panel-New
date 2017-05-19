<?php
session_start();
require_once ('config/db.php');
$connection = new Db();
$mysqli = $connection->dbconnection();
?>

<html>
    <body>
        <div>
            <form method="post" action="">
                <input type="text" name="appid" id="appid" value="app id"/>
                <input type="submit" value="Submit"/>

            </form>
        </div>
    </body>
</html>

<?php
if (isset($_POST['appid']) && trim($_POST['appid']) != '') {

    $appid = $_POST['appid'];

    $sqlcheck = "select distinct ad.*,ap.platform as platform from app_data ad join author_payment ap on ap.app_id=ad.id where ap.plan_id IS NOT NULL and ap.payment_done=1 and ad.published=1 and ad.deleted!=1 and ad.id='$appid' LIMIT 1;";
    $send_mail = $mysqli->prepare($sqlcheck);
    $send_mail->execute();
    $sendMail = $send_mail->fetch(PDO::FETCH_ASSOC);

    if (is_array($sendMail)) {
        $count = count($sendMail);
    } else {
        $count = 0;
    }

    if ($count > 0) {

        $sql122 = "select * FROM author WHERE id='" . $sendMail['author_id'] . "'";
        $stmt122 = $mysqli->prepare($sql122);
        $stmt122->execute();
        $user = $stmt122->fetch(PDO::FETCH_ASSOC);
        
        if ($user['email_address'] != '') {
            $csubject = 'Congratulations! Your app is now ready to launch!';
            $basicUrl = $connection->siteurl();
            $app_name = $sendMail['summary'];
            $username = $user['first_name'].' '.$user['last_name'];
            $chtmlcontent = file_get_contents('./edm-new/Instappy-go_on_launch_to_store.php');
            $chtmlcontent      = str_replace('{user_name}', $username, $chtmlcontent);
            $chtmlcontent      = str_replace('{app_name}', $app_name, $chtmlcontent);
            $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
            $chtmlcontent = str_replace('{custid}',  $user['custid'], $chtmlcontent);


            $cbcc = 'admin@instappy.com';
            $cto = $user['email_address'];
//            $cto = 'nitin@pulpstrategy.com';
            $cformemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';
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
                    'recipients' => $cto,
                    'bcc' => $cbcc
                )
            ));
            $customerhead = curl_exec($curl);

            curl_close($curl);
            
            echo 'mail send';
        }
    } else {
        echo "1";
    }
} else {
    echo "Request is not completed";
}
?>