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
                <input type="text" name="appid" id="appid" placeholder="App Id"/>
                  <input type="text" name="app_version"  placeholder="App Version"/>
                  
                <input type="submit" name="submit" value="Submit"/>

            </form>
        </div>
    </body>
</html>

<?php
if($_POST['submit']=="Submit"){
if ((isset($_POST['appid']) && trim($_POST['appid']) != '') && (isset($_POST['app_version']) && trim($_POST['app_version']) != '')) {
    $appid = $_POST['appid'];
    $app_version = $_POST['app_version'];

    $sqlcheck = "select distinct ad.*,ap.platform as platform from app_data ad join author_payment ap on ap.app_id=ad.id where ap.plan_id IS NOT NULL and ap.payment_done=1 and ad.published=1 and ad.deleted!=1 and ad.id='$appid' LIMIT 1";
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
            $csubject = 'Congratulations! Your app has just been updated!';
            $basicUrl = $connection->siteurl();
			
            $app_name = $sendMail['summary'];
			$username = htmlentities($user['first_name']).' '.htmlentities($user['last_name']);
            $chtmlcontent = file_get_contents('./edm-new/simulator.php');
            $chtmlcontent = str_replace('{user_name}', $username, $chtmlcontent);
            $chtmlcontent = str_replace('{app_name}', $app_name, $chtmlcontent);
            $chtmlcontent = str_replace('{app_version}', $app_version, $chtmlcontent);
            $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
            $chtmlcontent = str_replace('{custid}', $user['custid'], $chtmlcontent);

            $cbcc = 'admin@instappy.com';
            
            $cto = $user['email_address'];
            //$cto = 'ravi.tiwari@pulpstrategy.com';
            $cformemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';
           
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
            
            echo 'Mail Sent!';
        }
    } else {
        echo "Wrong Data!";
    }
}else{
	echo "Please enter App Id and App Version!";
}
}
?>