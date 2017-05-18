<?php
require_once ('config/db.php');

class Login extends Db {

    var $db, $url;

    function __construct() {
        $this->db = $this->dbconnection();
        $this->url = $this->siteurl();
    }

    function check_login($data) {
        $username = $data['username'];
        $password = md5($data['password']);
        $sql = "select id,first_name, avatar,is_confirm,custid from author where email_address='$username' and password='$password'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($results['id'] > 0) {
            if ($results['is_confirm'] == 1) {
                if (!empty($data['remember'])) {
                    setcookie('username', $username, time() + (60 * 60 * 24 * 1));
                    setcookie('password', $data['password'], time() + (60 * 60 * 24 * 1));
                }
                $_SESSION['username'] = $username;
                $_SESSION['custid'] = $results['custid'];
                $_SESSION['first_name'] = $results['first_name'];
                $_SESSION['password'] = $data['password'];
                echo "success##" . $results['first_name'] . "##" . $results['avatar'];
            } else {
                echo "invalid";
            }
        } else {
            echo "fail##" . $results['first_name'];
        }
    }
    
    function check_autologin($data) {
        $username = $data['username'];
        $password = $data['password'];
        $sql = "select id,first_name, avatar,is_confirm,custid from author where email_address='$username' and password='$password'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($results['id'] > 0) {
            if ($results['is_confirm'] == 1) {
                if (!empty($data['remember'])) {
                    setcookie('username', $username, time() + (60 * 60 * 24 * 1));
                    setcookie('password', $data['password'], time() + (60 * 60 * 24 * 1));
                }
                $_SESSION['username'] = $username;
                $_SESSION['custid'] = $results['custid'];
                $_SESSION['first_name'] = $results['first_name'];
                $_SESSION['password'] = $data['password'];
                echo "success##" . $results['first_name'] . "##" . $results['avatar'];
            } else {
                echo "invalid";
            }
        } else {
            echo "fail##" . $results['first_name'];
        }
    }

    function user_register($data) {
        $username = $data['username'];
        $author_product = $data['author_product'];
        $author_product_category = $data['author_product_category'];
        $company_name = $data['company_name'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $mobile_number = $data['mobile_number'];
        $password = md5($data['password']);
        $source = $_SESSION['source'];
        $uid = uniqid();
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->url . 'API/register.php/register',
            CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'email' => $username,
                'password' => $password,
                'author_product' => $author_product,
                'author_product_category' => $author_product_category,
                'company_name' => $company_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'mobile_number' => $mobile_number,
                'source' => $source,
                'verification_code' => $uid
            )
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        $results = json_decode($resp);
        // Close request to clear up some resources
        curl_close($curl);
        $final_results = $results->response;
        if ($final_results->isSucess == true) {
            $output1 = $final_results->data;
            $output = $output1->register;
            // $_SESSION['username']=$username;
            // $_SESSION['password']=$data['password'];

            $csubject = 'Thank you for registering with Instappy. Verify your e-mail';
            $basicUrl = $this->url;
            $chtmlcontent = file_get_contents('edm/Registration.php');
            $clastname = $lname != '' ? ' ' . $lname : $lname;
            $cname = ucwords($username . $clastname);
            $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
            $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
            $chtmlcontent = str_replace('{verify_link}', $basicUrl . 'signup-verification.php?verification=' . $uid, $chtmlcontent);

            $cto = $username;
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
                    'recipients' => $cto
                )
            ));
            $customerhead = curl_exec($curl);

            curl_close($curl);

            if ($customerhead) {
                echo "success";
            }
        } else {
            if ($final_results->isSucess == false) {
                if ($final_results->errorCode == 415) {
                    echo $final_results->errorCode;
                }
                if ($final_results->errorCode == 405) {
                    echo $final_results->errorCode;
                }
                if ($final_results->errorCode == 403) {
                    echo $final_results->errorCode;
                }
            }
        }
    }

    /*
      function forgot_password($data, $custid) {
      $pass_sent = bin2hex(openssl_random_pseudo_bytes(8));
      $pass_save = md5($pass_sent);
      $resutls = $this->forgot_email_exist($data['email']);
      if ($resutls['id']) {
      $user_id = $resutls['id'];
      $id = $this->update_password($user_id, $pass_save);
      if ($id) {
      if ($resutls['first_name'])
      $rname = ucwords($resutls['first_name']);
      else
      $rname = ucwords('user');
      $to = $data['email'];
      $subject = "Reset your password";

      $basicUrl = $this->url;

      $chtmlcontent = file_get_contents('edm/forget_password.php');
      $clastname = $lname != '' ? ' ' . $lname : $lname;
      $cname = $rname;
      $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
      $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
      $chtmlcontent = str_replace('{new_pass}', $pass_sent, $chtmlcontent);

      $body = $chtmlcontent;

      $formemail = 'contact@instappy.com';
      $key = 'f894535ddf80bb745fc15e47e42a595e';
      $url = 'https://api.falconide.com/falconapi/web.send.rest?api_key=' . $key . '&subject=' . rawurlencode($subject) . '&fromname=' . rawurlencode($subject) . '&from=' . $formemail . '&content=' . rawurlencode($body) . '&recipients=' . $to;
      $head = file_get_contents($url);
      if ($head) {
      echo "success";
      } else {
      echo "fail";
      }
      } else {

      echo "fail";
      }
      } else {
      echo"invalid";
      }
      }
     */
    /*
     * forgot password
     * updated by Arun Srivastava on 20/8/15
     */

    function forgot_password($data, $custid) {
        $resutls = $this->forgot_email_exist($data['email']);
        if ($resutls['id']) {
            if ($resutls['first_name']) {
                $rname = "Dear " . ucwords($resutls['first_name'] . ' ' . $resutls['last_name']) . ",";
            } else {
                $rname = "Hi,";
            }

            $cto = $data['email'];
            $csubject = "Reset your password";
            $basicUrl = $this->url;
            $chtmlcontent = file_get_contents('edm/forget_password.php');
            $clastname = $lname != '' ? ' ' . $lname : $lname;
            $cname = $rname;
            $password_url = $basicUrl . '?resetpassword=' . base64_encode($cto);
            $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
            $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
            $chtmlcontent = str_replace('{new_pass_url}', $password_url, $chtmlcontent);

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
                    'recipients' => $cto
                )
            ));
            $customerhead = curl_exec($curl);

            curl_close($curl);

            if ($customerhead) {
                echo "success";
            } else {
                echo "fail";
            }
        } else {
            echo"invalid";
        }
    }

    function check_user_exist($email, $custid) {
        $sql = "select id,first_name,avatar from author where custid=$custid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    function forgot_email_exist($email) {
        $sql = "select id,first_name, last_name, avatar from author where email_address='$email'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    function update_password($user_id, $pass) {
        $sql = "UPDATE author SET password = :password
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        if ($stmt->execute())
            return true;
        else
            return false;
    }

    function fb_register($data) {
        $fname = $data['fname'];
        $lname = $data['lname'];
        $username = $data['username'];
        $userFbId = $data['userFbId'];
        $avatar = $data['avatar'];
        $fb_token = $data['fb_token'];
        $password = md5($data['userFbId']);
		$source = $_SESSION['source'];
        $uid = uniqid();
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->url . 'API/register.php/fb-register',
            CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'email' => $username,
                'password' => $password,
                'fb_token' => $fb_token,
                'first_name' => $fname,
                'last_name' => $lname,
                'avatar' => $avatar,
                'fbid' => $userFbId,
				'source' => $source,
                'verification_code' => $uid,
                'base_url' => $this->url
            )
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        $results = json_decode($resp);
        // print_r($results);
        // Close request to clear up some resources
        curl_close($curl);
        $final_results = $results->response;
        if ($final_results->isSucess == true) {
            $output1 = $final_results->data;
            $output = $output1->register;
            $_SESSION['password'] = substr($userFbId, 0, 10);
            if ($output) {
                $_SESSION['custid'] = $output->custid;
                $_SESSION['username'] = $output->email_address;
                $_SESSION['first_name'] = $output->first_name;
                echo "success";
            }
        } else {
            if ($final_results->isSucess == false) {
                if ($final_results->errorCode == 415) {
                    echo $final_results->errorCode;
                }
                if ($final_results->errorCode == 405) {
                    echo $final_results->errorCode;
                }
                if ($final_results->errorCode == 403) {
                    echo $final_results->errorCode;
                }
            }
        }
    }

    function gplus_register($data) {
        $fname = $data['fname'];
        $lname = $data['lname'];
        $username = $data['username'];
        $userFbId = $data['userFbId'];
        $avatar = $data['avatar'];
        $gPlus_token = $data['gPlus_token'];
        $password = md5($data['userFbId']);
        $uid = uniqid();
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->url . 'API/register.php/gplus-register',
            CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'email' => $username,
                'password' => $password,
                'gPlus_token' => $gPlus_token,
                'first_name' => $fname,
                'last_name' => $lname,
                'avatar' => $avatar,
                'fbid' => $userFbId,
                'verification_code' => $uid
            )
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        $results = json_decode($resp);
        // Close request to clear up some resources
        curl_close($curl);
        $final_results = $results->response;
        if ($final_results->isSucess == true) {
            $output1 = $final_results->data;
            $output = $output1->register;
            $_SESSION['username'] = $username;
            $_SESSION['password'] = substr($userFbId, 0, 10);

            echo "success";
        } else {
            if ($final_results->isSucess == false) {
                if ($final_results->errorCode == 415) {
                    echo $final_results->errorCode;
                }
                if ($final_results->errorCode == 405) {
                    echo $final_results->errorCode;
                }
                if ($final_results->errorCode == 403) {
                    echo $final_results->errorCode;
                }
            }
        }
    }

    public function getUserByCustId($custid) {

        $sql = "select * FROM author where custid='" . $custid . "'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    function contact_us($data) {

        if ($_SESSION['custid'] != '') {
            $user = $this->getUserByCustId($_SESSION['custid']);
        } else {
            $user = array();
            $user['id'] = '';
            $user['custid'] = '';
        }

        $sql = "INSERT INTO contact_us_details (author_id, type, name, email, phone,organisation,Organisation_size,Industry,App_type,Additional,created,CustId,Source) VALUES ('" . $user['id'] . "',2,'" . $data['first_name'] . "', '" . $data['bussiness_email'] . "', '" . $data['phone'] . "','" . $data['company_name'] . "','" . $data['og_size'] . "','" . $data['industry'] . "','" . $data['type_app'] . "','" . $data['message'] . "',NOW(),'" . $user['custid'] . "','" . $data['urlsource'] . "')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $cto = $data['bussiness_email'];
        $csubject = "Thank you for reaching out to the Instappy team!";

        $basicUrl = $this->url;

        $chtmlcontent = file_get_contents('edm/contact_us.php');
        $clastname = $data['last_name'] != '' ? ' ' . $data['last_name'] : $data['last_name'];
        $cname = $data['first_name'] . $clastname;
        $chtmlcontent = str_replace('{customer_name}', ucwords($cname) . ",", $chtmlcontent);
        $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
        $chtmlcontent = str_replace('{blog_url}', $basicUrl . 'blog', $chtmlcontent);

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
                'recipients' => $cto
            )
        ));
        $customerhead = curl_exec($curl);

        curl_close($curl);


        if ($customerhead) {
            $to = "contact@instappy.com";
            $subject = "Contact Us Enquiry";
            $htmlcontent = "<html>
		<body>
		<p>Hi Strategist,</p>		
		<p>First Name :: " . $data['first_name'] . "</p>
		<p>Last Name :: " . $data['last_name'] . "</p>
		<p>Business Email :: " . $data['bussiness_email'] . "</p>
		<p>Company Name :: " . $data['company_name'] . "</p>
		<p>Phone No :: " . $data['phone'] . "</p>
		<p>Organisation Size :: " . $data['og_size'] . "</p>
		<p>Type of App :: " . $data['type_app'] . "</p>
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
                echo "success";
            } else {
                echo "fail";
            }
        } else {
            echo "fail";
        }
    }

    function vendorrequest($data) {

        $mainurl = 'http://182.74.47.179/SQLServer.aspx?Process=Pulp&Campaign=Pulp&PhoneNo=' . $data['phone'] . '&SetCallBack=1&IsFilewise=1&First_name=' . $data['first_name'] . ' ' . $data['last_name'] . '&Email=' . $data['bussiness_email'] . '&Organisation=' . $data['company_name'] . '&Organisation_Size=' . $data['og_size'] . '&Industry=' . $data['industry'] . '&App_type=' . $data['type_app'] . '&CustomerRemark=' . $data['message'] . '&Source='.$data['urlsource'];

        $url = str_replace(' ', '-', $mainurl);
        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));
        $head2 = curl_exec($curl2);
        curl_close($curl2);

//            if($head2 == "Success"){
//                echo "sent to vendor";
//            } else{
//                echo "not sent to vendor";
//            }           
    }

    function subscribe($data) {
        $email = $data['email'];
        $sql = "INSERT INTO newsletter_subs(email,created) VALUES (:email,NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $lid = $this->db->lastInsertId();
        if ($lid > 0) {
            $to = $email;
            $subject = "Instappy Subscription";

            $basicUrl = $this->url;

            $chtmlcontent = file_get_contents('edm/verification.php');
            $clastname = $data['last_name'] != '' ? ' ' . $data['last_name'] : $data['last_name'];
            $cname = $data['first_name'] . $clastname;
            $chtmlcontent = str_replace('{customer_name}', $email, $chtmlcontent);
            $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);


            /*
              $htmlcontent="<html>
              <body>
              <p>Dear User,</p>
              Thanks for subscribing to us.
              <p>Best Regards,<br />
              Team Instappy</p>
              </body>
              </html>
              ";
             */
            $body = $chtmlcontent;
            //$headers  = 'Instappy Forgot Password' ;
            /* $headers  = 'MIME-Version: 1.0' . "\r\n";
              $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
              $headers .= "X-Mailer: PHP/".phpversion();
              $headers .= 'From: Pulp Strategy <contact@instappy.com>' . "\r\n"; */
            $formemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';
            $url = 'https://api.falconide.com/falconapi/web.send.rest?api_key=' . $key . '&subject=' . rawurlencode($subject) . '&fromname=' . rawurlencode($subject) . '&from=' . $formemail . '&content=' . rawurlencode($body) . '&recipients=' . $to;
            $head = file_get_contents($url);
            if ($head) {
                echo "success";
            } else {
                echo "fail";
            }
        }
    }

    function signup_verification($code) {
        $sql = "select * from author where verification_code='$code' and is_confirm=0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($results['id'] > 0) {
            $is_confirm = 1;
            $sql = "UPDATE author SET is_confirm = :is_confirm
            WHERE verification_code = :verification_code";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':is_confirm', $is_confirm, PDO::PARAM_STR);
            $stmt->bindParam(':verification_code', $code, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $lname = $results['last_name'];
                $fname = $results['first_name'];
                $username = $results['email_address'];
                $password = $results['password'];
                $custid = $results['custid'];

                $basicUrl = $this->url;
                $cto = $username;
                $chtmlcontent = file_get_contents('edm/Welcome-to-instappy.php');
                $clastname = $lname != 'User' ? ' ' . $lname : $lname;
                if (!empty($fname)) {
                    $cname = " ".ucwords($fname);
                } else {
                    $cname = "";
                }
                $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                $chtmlcontent = str_replace('{customer_id}', $custid, $chtmlcontent);

                $csubject = 'Welcome to Instappy';
                $cformemail = 'noreply@instappy.com';
                $key = 'f894535ddf80bb745fc15e47e42a595e';
                //$url = 'https://api.falconide.com/falconapi/web.send.rest?api_key=' . $key . '&subject=' . rawurlencode($csubject) . '&fromname=' . rawurlencode($csubject) . '&from=' . $cformemail . '&content=' . rawurlencode($chtmlcontent) . '&recipients=' . $cto;
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
				$allready_cust_id=$_SESSION['custid'];
				if(empty($allready_cust_id)){
				?>
				<script type="text/javascript">
				$(document).ready(function(){
				
				checkloginForRes();
				});
				function checkloginForRes()
				{
						$.ajax({
						type: "POST",
						url: "login.php",
						async: false,
						data: "username=<?php echo $username?>&password=<?php echo $password?>&remember=yes&type=autoLogin",
						
					success: function (response) {
                                            console.log('logeed in');
            },
			error: function () {
                                          $(".cropcancel").trigger("click");
            $("#page_ajax").html('').hide();
            $(".popup_container").css({'display': 'block'});
            $(".confirm_name .confirm_name_form").html('<p>Oops! Something went wrong</p>Please try again or check your internet connection');
            $(".confirm_name").css({'display': 'block'});
            return false;
                                        },
						});
						}
		</script>
		<?php
			}
                if ($customerhead) {
						if($results['theme_preferred']){
						$theme_id=$results['theme_preferred'];
					}
					else{
						$theme_id=15;
					}
					if($this->get_category_id($theme_id)){
						$cat_id=$this->get_category_id($theme_id);
					}
					else{
						$cat_id=18;
					}
                    return "success##".$theme_id.'##'.$cat_id.'##'.$results['product_prefereed'];
                }
            } else
                return false;
        }
        else {
            return false;
        }
    }

    function email_confirmed($custid) {
        $sql = "select * from author where custid='$custid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($results['email_address'] == '' || $results['email_address'] == null) {
            echo 1;
        } else {
            if ($results['is_confirm'] == 0) {
                echo 2;
            } else {
                echo 0;
            }
            if ($results['first_name'] == '' || $results['first_name'] == null) {
                echo 3;
            } else {
                echo 0;
            }
            if ($results['mobile'] == '' || $results['mobile'] == null) {
                echo 4;
            } else {
                if ($results['mobile_validated'] == 0) {
                    echo 2;
                } else {
                    echo 0;
                }
            }
        }
    }

    function appidAuthouridcheck($custid, $appid) {
        $sql = "SELECT id FROM app_data WHERE author_id IN (SELECT id FROM author WHERE `custid`='$custid') and id='$appid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    function support($data) {
        $custid = $data['custid'];
        $sql = "SELECT * FROM author WHERE custid='$custid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data['subject'] == 'Others') {
            $data['subject'] = $data['cust_sub'];
        }
        $sql = "INSERT INTO contact_us_details (author_id, type, name,subject,Additional,created,CustId) VALUES ('" . $results['id'] . "',3,'" . $results['first_name'] . "','" . $data['subject'] . "','" . addslashes($data['msg']) . "',NOW(),'" . $data['custid'] . "')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        // send admin

        $to = "support@instappy.com";
        $subject = "Instappy Support Enquiry";
        $htmlcontent = "<html>
		<body>
		<p>Hi Instappy Team,</p>		
		<p>First Name :: " . $results['first_name'] . "</p>
		<p>CustId :: " . $custid . "</p>
		<p>Subject:: " . $data['subject'] . "</p>
		<p>Message :: " . addslashes($data['msg']) . "</p>

		<p>Best Regards,<br />
		Team Instappy</p>
		</body>
		</html>";
        $body = $htmlcontent;
        $formemail = 'noreply@instappy.com';
        $key = 'f894535ddf80bb745fc15e47e42a595e';
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

        //send user
        $cto = $results['email_address'];
        $csubject = "Thank you for reaching out to the Instappy team!";

        $basicUrl = $this->url;

        $chtmlcontent = file_get_contents('edm/contact_us.php');
        $clastname = $results['last_name'] != '' ? ' ' . $results['last_name'] : $results['last_name'];
        $cname = $results['first_name'] . $clastname;
        $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
        $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
        $chtmlcontent = str_replace('{blog_url}', $basicUrl . 'blog', $chtmlcontent);

        $cformemail = 'noreply@instappy.com';
        $key = 'f894535ddf80bb745fc15e47e42a595e';
        //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
        //$customerhead = file_get_contents($url);

        $curl = curl_init();
//        curl_setopt_array($curl, array(
//            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
//            CURLOPT_POST => 1,
//            CURLOPT_POSTFIELDS => array(
//                'api_key' => $key,
//                'subject' => $csubject,
//                'fromname' => 'Instappy',
//                'from' => $cformemail,
//                'content' => $chtmlcontent,
//                'recipients' => $cto
//            )
//        ));
        $customerhead = curl_exec($curl);

        curl_close($curl);
        if ($customerhead)
            echo "success";
        else
            echo "success";
    }
    
    function vendorrequestSupport($data) {
            
        $mainurl = 'http://182.74.47.179/SQLServer.aspx?Process=PulpSupport&Campaign=Support&PhoneNo='.$data['custid'].'&Customer_ID='.$data['custid'].'&SetCallBack=1&IsFilewise=1&Source=Support&Subject='.$data['subject'].'&Subject_Others='.$data['cust_sub'].'&Query='.$data['msg'];

        $url = str_replace(' ', '-', $mainurl);
        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));
        $head2 = curl_exec($curl2);
        curl_close($curl2);

//            if($head2 == "Success"){
//                echo "sent to vendor";
//            } else{
//                echo "not sent to vendor";
//            }           
    }
    
    function resend_email($data) {
        $email = $data['email'];
        $sql = "SELECT * from author  WHERE email_address='$email'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $uid = uniqid();
        if ($results['id'] > 0) {
            $confirm = 0;
            $custid = $results['custid'];
            $sql = "UPDATE author SET verification_code = :verification_code,is_confirm=:is_confirm
			WHERE custid = :custid";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':verification_code', $uid, PDO::PARAM_STR);
            $stmt->bindParam(':is_confirm', $confirm, PDO::PARAM_STR);
            $stmt->bindParam(':custid', $custid, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $csubject = 'Thank you for registering with Instappy. Verify your e-mail';
                $basicUrl = $this->url;
                $chtmlcontent = file_get_contents('edm/Registration.php');
                $cname = ucwords($results['first_name']);
                $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                $chtmlcontent = str_replace('{verify_link}', $basicUrl . 'signup-verification.php?verification=' . $uid, $chtmlcontent);
                $cto = $email;
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
                        'recipients' => $cto
                    )
                ));
                $customerhead = curl_exec($curl);
                curl_close($curl);
                if ($customerhead) {
                    echo "success";
                }
            }
        } else {

            echo "invalid";
        }
    }

    function reset_password($data) {
        $resutls = $this->forgot_email_exist($data['reset_pass_email']);
        if (count($resutls) && $resutls['id'] != '') {
            $sql = "UPDATE author SET password = '" . md5($data['reset_pass_newpass']) . "' WHERE id='" . $resutls['id'] . "'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            echo 'success';
        } else {
            echo 'false';
        }
    }
	function author_choose_app_type($data){
		$str='';
		$app_type=$data['author_product'];
		if($app_type=='content'){
		 $sql = "SELECT id,name FROM themes WHERE image_url LIKE 'images/themes/Content%20Publishing_C/%'";
		 }
		else if($app_type=='catalogue'){
		$sql = "SELECT id,name FROM themes WHERE image_url LIKE 'images/themes/Retail%20and%20Catalogue_C/%'";        
		}
		else {			
			$str="fails";
		}
		$stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$str.='<option value="">Select App Templates*</option>';
		foreach($results as $val){
			$str.='<option value="'.$val['id'].'">'.$val['name'].'</option>';
		}
		echo $str;
	}
    
function get_category_id($theme_id){	
		$sql = "SELECT c.id  AS category_id FROM category_theme_rel cc
		JOIN category c ON cc.category_id=c.id
		WHERE cc.theme_id=$theme_id AND
		c.parent_id<>'' AND cc.deleted=0 AND c.`name`<>'Featured'";
		$stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
		return $results['category_id'];
}
}
