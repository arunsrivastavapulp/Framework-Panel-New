<?php 

require_once ('config/db.php');

class Edm extends Db {

    var $db, $url;

    function __construct() {
        $this->db = $this->dbconnection();
        $this->url = $this->siteurl();
    }

function save_content_edm($data){	
        if ($_SESSION['custid'] != '') {
            $user = $this->getUserByCustId($_SESSION['custid']);
        } else {
            $user = array();
            $user['id'] = '';
            $user['custid'] = '';
        }

       $sql = "INSERT INTO contact_us_details (author_id, type, name, email, phone,organisation,Organisation_size,Industry,App_type,Additional,created,CustId,subject,is_subscribed) VALUES ('" . $user['id'] . "',6,'" . $data['fname'] . "', '" . $data['email'] . "', '" . $data['phone'] . "','" . $data['orgname'] . "','" . $data['orgsize'] . "','" . $data['industry'] . "','" . $data['apptype'] . "','" . $data['message'] . "',NOW(),'" . $user['custid'] . "','Content EDM Enquiry','".$data['subscribe']."')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        // $cto = $data['bussiness_email'];
        // $csubject = "Thank you for reaching out to the Instappy team!";

        // $basicUrl = $this->url;

        // $chtmlcontent = file_get_contents('edm/contact_us.php');
        // $clastname = $data['last_name'] != '' ? ' ' . $data['last_name'] : $data['last_name'];
        // $cname = $data['first_name'] . $clastname;
        // $chtmlcontent = str_replace('{customer_name}', ucwords($cname) . ",", $chtmlcontent);
        // $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
        // $chtmlcontent = str_replace('{blog_url}', $basicUrl . 'blog', $chtmlcontent);

        // $cformemail = 'noreply@instappy.com';
        // $key = 'f894535ddf80bb745fc15e47e42a595e';
        // //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
        // //$customerhead = file_get_contents($url);

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
            // CURLOPT_RETURNTRANSFER => 1,
            // CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
            // CURLOPT_POST => 1,
            // CURLOPT_POSTFIELDS => array(
                // 'api_key' => $key,
                // 'subject' => $csubject,
                // 'fromname' => 'Instappy',
                // 'from' => $cformemail,
                // 'content' => $chtmlcontent,
                // 'recipients' => $cto
            // )
        // ));
        // $customerhead = curl_exec($curl);

        // curl_close($curl);


        //if ($customerhead) {
		$to = "contact@instappy.com";
		//$to = "prem@pulpstrategy.com";
		$subject = "Content EDM Enquiry";
		$htmlcontent = "<html>
		<body>
		<p>Hi Strategist,</p>		
		<p> Name :: " . $data['fname'] . "</p>
		<p>Business Email :: " . $data['email'] . "</p>
		<p>Company Name :: " . $data['orgname'] . "</p>
		<p>Phone No :: " . $data['phone'] . "</p>
		<p>Organisation Size :: " . $data['orgsize'] . "</p>
		<p>Type of App :: " . $data['apptype'] . "</p>
		<p>Industry:: " . $data['industry'] . "</p>
		<p>Message :: " . $data['message'] . "</p>
		<p>Best Regards,<br />
		Team Instappy</p>
		</body>
		</html>
		";
            $body = $htmlcontent;
            //$headers  = 'Instappy Forgot Password' ;
            /* $headers  = 'MIME-Version: 1.0' . "\r\n";
              $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
              $headers .= "X-Mailer: PHP/".phpversion();
              $headers .= 'From: Pulp Strategy <contact@instappy.com>' . "\r\n"; */
            $formemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';
            //$url       = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($subject).'&fromname='.rawurlencode($subject).'&from='.$formemail.'&content='.rawurlencode($body).'&recipients='.$to;
            //$head      = file_get_contents($url);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'api_key' => $key,
                    'subject' => $subject,
                    'fromname' => 'Instappy',
                    'from' => $formemail,
                    'content' => $body,
                    'recipients' => $to
                )
            ));
            $head = curl_exec($curl);

            curl_close($curl);


            if ($head) {
                return "success";
            } else {
                return "fail";
            }
        //} 
		// else {
            // echo "fail";
        // }
	
}
function save_retail_edm($data){
	        if ($_SESSION['custid'] != '') {
            $user = $this->getUserByCustId($_SESSION['custid']);
        } else {
            $user = array();
            $user['id'] = '';
            $user['custid'] = '';
        }

       $sql = "INSERT INTO contact_us_details (author_id, type, name, email, phone,organisation,Organisation_size,Industry,App_type,Additional,created,CustId,subject,is_subscribed) VALUES ('" . $user['id'] . "',6,'" . $data['fname'] . "', '" . $data['email'] . "', '" . $data['phone'] . "','" . $data['orgname'] . "','" . $data['orgsize'] . "','" . $data['industry'] . "','" . $data['apptype'] . "','" . $data['message'] . "',NOW(),'" . $user['custid'] . "','Retail EDM Enquiry','".$data['subscribe']."')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        // $cto = $data['bussiness_email'];
        // $csubject = "Thank you for reaching out to the Instappy team!";

        // $basicUrl = $this->url;

        // $chtmlcontent = file_get_contents('edm/contact_us.php');
        // $clastname = $data['last_name'] != '' ? ' ' . $data['last_name'] : $data['last_name'];
        // $cname = $data['first_name'] . $clastname;
        // $chtmlcontent = str_replace('{customer_name}', ucwords($cname) . ",", $chtmlcontent);
        // $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
        // $chtmlcontent = str_replace('{blog_url}', $basicUrl . 'blog', $chtmlcontent);

        // $cformemail = 'noreply@instappy.com';
        // $key = 'f894535ddf80bb745fc15e47e42a595e';
        // //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
        // //$customerhead = file_get_contents($url);

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
            // CURLOPT_RETURNTRANSFER => 1,
            // CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
            // CURLOPT_POST => 1,
            // CURLOPT_POSTFIELDS => array(
                // 'api_key' => $key,
                // 'subject' => $csubject,
                // 'fromname' => 'Instappy',
                // 'from' => $cformemail,
                // 'content' => $chtmlcontent,
                // 'recipients' => $cto
            // )
        // ));
        // $customerhead = curl_exec($curl);

        // curl_close($curl);


        //if ($customerhead) {
		$to = "contact@instappy.com";
		$subject = "Retail EDM Enquiry";
		$htmlcontent = "<html>
		<body>
		<p>Hi Strategist,</p>		
		<p> Name :: " . $data['fname'] . "</p>
		<p>Business Email :: " . $data['email'] . "</p>
		<p>Company Name :: " . $data['orgname'] . "</p>
		<p>Phone No :: " . $data['phone'] . "</p>
		<p>Organisation Size :: " . $data['orgsize'] . "</p>
		<p>Type of App :: " . $data['apptype'] . "</p>
		<p>Industry:: " . $data['industry'] . "</p>
		<p>Message :: " . $data['message'] . "</p>
		<p>Best Regards,<br />
		Team Instappy</p>
		</body>
		</html>
		";
            $body = $htmlcontent;
            //$headers  = 'Instappy Forgot Password' ;
            /* $headers  = 'MIME-Version: 1.0' . "\r\n";
              $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
              $headers .= "X-Mailer: PHP/".phpversion();
              $headers .= 'From: Pulp Strategy <contact@instappy.com>' . "\r\n"; */
            $formemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';
            //$url       = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($subject).'&fromname='.rawurlencode($subject).'&from='.$formemail.'&content='.rawurlencode($body).'&recipients='.$to;
            //$head      = file_get_contents($url);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'api_key' => $key,
                    'subject' => $subject,
                    'fromname' => 'Instappy',
                    'from' => $formemail,
                    'content' => $body,
                    'recipients' => $to
                )
            ));
            $head = curl_exec($curl);

            curl_close($curl);


            if ($head) {
                return "success";
            } else {
                return "fail";
            }
	
}	
    public function getUserByCustId($custid) {

        $sql = "select * FROM author where custid='" . $custid . "'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

}