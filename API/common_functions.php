<?php

require_once('db.php');

class Fwcore {
    /*
     * check authentication for api
     * Added By Arun Srivastava on 15/6/15
     */

    private function queryRun($query, $queryType) {
        $dbCon = getConnection();
        $con = $dbCon->query($query);
        if ($queryType == 'insert') {
            $lastInsertId = $dbCon->lastInsertId();
            $dbCon = null;
            return $lastInsertId;
        } else if ($queryType == 'update') {
            $row = $con->execute();
            $dbCon = null;
            return $con;
        } else if ($queryType == 'select') {
            $row = $con->fetch(PDO::FETCH_ASSOC);
            $dbCon = null;
            return $row;
        } else if ($queryType == 'select_all') {
            $row = $con->fetchAll(PDO::FETCH_ASSOC);
            $dbCon = null;
            return $row;
        } else if ($queryType == 'delete') {
//            $row = $con->execute();            
            $dbCon = null;
            return $con;
        }
    }

    public function real_json_encode($data = '', $type, $errorTxt, $errorCode) {

        if ($type == 'successData') {
            header("Content-Type: application/json");
            return json_encode((
                    array(
                        "response" => array(
                            "isSucess" => true,
                            "status" => $errorCode,
                            "errorMsg" => $errorTxt,
                            "data" => $data
                        )
                    )
                    ), JSON_NUMERIC_CHECK);
        } elseif ($type == 'success') {
            header("Content-Type: application/json");
            return json_encode((
                    array(
                        "response" => array(
                            "isSucess" => false,
                            "errorCode" => $errorCode,
                            "errorMsg" => $errorTxt
                        )
                    )
                    ), JSON_NUMERIC_CHECK);
        } elseif ($type == 'error') {
            header("Content-Type: application/json");
            return json_encode((
                    array(
                        "response" => array(
                            "isSucess" => false,
                            "errorCode" => $errorCode,
                            "errorMsg" => $errorTxt
                        )
                    )
                    ), JSON_NUMERIC_CHECK);
        } else {
            header("Content-Type: application/json");
            return json_encode(($data), JSON_NUMERIC_CHECK);
        }
    }

    public function registerUser($data) {
        if (!empty($data['custid'])) {
            $fname = $data['fname'];
            $lname = $data['lname'];
            $phone = $data['phone'];
            $country = $data['country'];
            $state = $data['state'];
            $city = $data['city'];
            $address = $data['address'];
            $zip = $data['zip'];
            $alternet_email = $data['alternet_email'];
            $email = $data['email'];
            $fax = $data['fax'];
            $mobile = $data['mobile'];
            $website = $data['website'];
            $company = $data['company'];
            $username = $data['username'];
            $custid = $data['custid'];
            $mid = $data['mid'];
            $verification_code = $data['verification_code'];
            $curl = $data['url'];
            if ($username == '') {
                if ($email != '') {
                    $query = "SELECT email_address from author WHERE email_address='$email'";
                    $emildata = $this->queryRun($query, 'select');

                    if (count($emildata) > 0 && $emildata != '') {
                        echo 'Email Address Already Exist!';
                    } else {
                        $is_confirm = '0';
                        $csubject = 'Thank you for registering with Instappy. Verify your e-mail';
                        $basicUrl = $curl;
                        $chtmlcontent = file_get_contents('../edm/Registration.php');
                        $cname = ucwords($fname);
                        $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                        $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                        $chtmlcontent = str_replace('{verify_link}', $basicUrl . 'signup-verification.php?verification=' . $verification_code, $chtmlcontent);
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
                        $query = "UPDATE author set first_name='$fname',last_name='$lname',phone_no='$phone',country='$country',state='$state',city='$city',street='$address',pincode='$zip',alternate_email='$alternet_email',email_address='$email',is_confirm='$is_confirm',fax='$fax',mobile='$mobile',website='$website', organisation_name='$company', mid='$mid' WHERE custid='$custid'";
                        $homedata = $this->queryRun($query, 'update');
                        echo 'Saved successfully!';
                    }
                }
            } elseif ($username != $email) {
                $query = "SELECT email_address from author WHERE email_address='$email' and  email_address IS NOT NULL AND email_address<>''";
                $emil_data = $this->queryRun($query, 'select');
                if (!empty($emil_data['email_address'])) {
                    echo 'Email Address Already Exist!';
                } else {
                    $is_confirm = '0';
                    $csubject = 'Thank you for registering with Instappy. Verify your e-mail';
                    $basicUrl = $curl;
                    $chtmlcontent = file_get_contents('../edm/Registration.php');
                    $cname = ucwords($fname);
                    $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                    $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                    $chtmlcontent = str_replace('{verify_link}', $basicUrl . 'signup-verification.php?verification=' . $verification_code, $chtmlcontent);
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


                    $query = "UPDATE author set first_name='$fname',last_name='$lname',phone_no='$phone',country='$country',state='$state',city='$city',street='$address',pincode='$zip',alternate_email='$alternet_email',email_address='$email',is_confirm='$is_confirm',fax='$fax',mobile='$mobile',website='$website', organisation_name='$company', mid='$mid' WHERE custid='$custid'";
                    $homedata = $this->queryRun($query, 'update');

                    if ($customerhead) {
                        echo 'Saved successfully!';
                    }
                }
            } else {
                $query = "UPDATE author set first_name='$fname',last_name='$lname',phone_no='$phone',country='$country',state='$state',city='$city',street='$address',pincode='$zip',alternate_email='$alternet_email',email_address='$email',fax='$fax',mobile='$mobile',website='$website', organisation_name='$company', mid='$mid' WHERE custid='$custid'";
                $homedata = $this->queryRun($query, 'update');
                echo 'Saved successfully!';
            }
        } else {
            echo 'error';
        }
    }

    public function vendorrequest($data) {

        $mainurl = 'http://182.74.47.179/SQLServer.aspx?Process=Pulp&Campaign=Pulp&PhoneNo=' . $data['mobile'] . '&SetCallBack=1&IsFilewise=1&First_name=' . $data['fname'] . ' ' . $data['lname'] . '&Email=' . $data['email'] . '&Organisation=' . $data['company'] . '&Organisation_Size=0&Industry=&App_type=&CustomerRemark=&Source=register';

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

    public function userAvatar() {

        if (!isset($_FILES['avatar']) || !is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            echo 'Image file is Missing!';
        } else {
            $username = $_POST['username'];
            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../avatars/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['avatar']) || !is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['avatar']['name']; //file name
                $image_size = $_FILES['avatar']['size']; //file size
                $image_temp = $_FILES['avatar']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        $query = 'UPDATE author set avatar="' . $new_file_name . '" WHERE email_address="' . $_POST['username'] . '"';
                        $homedata = $this->queryRun($query, 'update');
                        echo 'Image Uploaded Successfully';
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
    }

    #####  This function will proportionally resize image ##### 

    function normal_resize_image($source, $destination, $image_type, $max_size, $image_width, $image_height, $quality) {

        if ($image_width <= 0 || $image_height <= 0) {
            return false;
        } //return false if nothing to resize
        //do not resize if image is smaller than max size
        if ($image_width <= $max_size && $image_height <= $max_size) {
            if ($this->save_image($source, $destination, $image_type, $quality)) {
                return true;
            }
        }

        //Construct a proportional size of new image
        $image_scale = min($max_size / $image_width, $max_size / $image_height);
        $new_width = ceil($image_scale * $image_width);
        $new_height = ceil($image_scale * $image_height);

        $new_canvas = imagecreatetruecolor($new_width, $new_height); //Create a new true color image
        //Copy and resize part of an image with resampling
        if (imagecopyresampled($new_canvas, $source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height)) {
            $this->save_image($new_canvas, $destination, $image_type, $quality); //save resized image
        }

        return true;
    }

    ##### This function corps image to create exact square, no matter what its original size! ######

    function crop_image_square($source, $destination, $image_type, $square_size, $image_width, $image_height, $quality) {
        if ($image_width <= 0 || $image_height <= 0) {
            return false;
        } //return false if nothing to resize

        if ($image_width > $image_height) {
            $y_offset = 0;
            $x_offset = ($image_width - $image_height) / 2;
            $s_size = $image_width - ($x_offset * 2);
        } else {
            $x_offset = 0;
            $y_offset = ($image_height - $image_width) / 2;
            $s_size = $image_height - ($y_offset * 2);
        }
        $new_canvas = imagecreatetruecolor($square_size, $square_size); //Create a new true color image
        //Copy and resize part of an image with resampling
        if (imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $square_size, $square_size, $s_size, $s_size)) {
            $this->save_image($new_canvas, $destination, $image_type, $quality);
        }

        return true;
    }

    ##### This function corps image to create exact rectangle, no matter what its original size! ######

    function crop_image_rectangle($source, $destination, $image_type, $rect_size, $image_width, $image_height, $quality) {
        if ($image_width <= 0 || $image_height <= 0) {
            return false;
        } //return false if nothing to resize

        if ($image_width > $image_height) {
            $y_offset = 0;
            $x_offset = ($image_width - $image_height) / 2;
            $s_size = $image_width - ($x_offset * 2);
        } else {
            $x_offset = 0;
            $y_offset = ($image_height - $image_width) / 2;
            $s_size = $image_height - ($y_offset * 2);
        }

        $new_canvas = imagecreatetruecolor($rect_size[0], $rect_size[1]); //Create a new true color image
        //Copy and resize part of an image with resampling
        if (imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $rect_size[0], $rect_size[1], $s_size, $s_size)) {
            $this->save_image($new_canvas, $destination, $image_type, $quality);
        }

        return true;
    }

    ##### Saves image resource to file ##### 

    function save_image($source, $destination, $image_type, $quality) {
        switch (strtolower($image_type)) {//determine mime type
            case 'image/png':
                imagepng($source, $destination);
                return true; //save png file
                break;
            case 'image/gif':
                imagegif($source, $destination);
                return true; //save gif file
                break;
            case 'image/jpeg': case 'image/pjpeg':
                imagejpeg($source, $destination, $quality);
                return true; //save jpeg file
                break;
            default: return false;
        }
    }

    public function authenticationCheck($authkey) {
        $query = "SELECT id FROM `author` WHERE auth_token='$authkey'";
        $result = $this->queryRun($query, 'select');
        //$returndata = $con->rowCount();
        return $result;
    }

    /*
     * check availability of data w.r.t app_id, author_id
     * Added By Arun Srivastava on 16/6/15
     */

    public function appDetailCheck($author_id, $app_id) {
        $query = "SELECT id FROM `app_data` WHERE author_id='" . $author_id . "' AND id='" . $app_id . "'";
        $result = $this->queryRun($query, 'select');
        $returndata = count($result);
        return $returndata;
    }

    public function planExpiryDate($id) {
        $query = "SELECT validity_in_days FROM `plans` WHERE id='" . $id . "'";
        $result = $this->queryRun($query, 'select');
        return $result;
    }

    public function auth_create($user_id) {
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $query = "UPDATE `author` SET `updated`='$servertime',`auth_token`='$token' where `id`='$user_id'";
        $this->queryRun($query, 'update');
//get token
        $query1 = "Select auth_token from `author` where id = '$user_id'";
        $authtoken = $this->queryRun($query1, 'select');
        return $authtoken['auth_token'];
    }

    public function email_check($userdata) {

        $emailC = $userdata['email_address'];
        $query = "Select id from author where email_address = '$emailC'";
        $row = $this->queryRun($query, 'select');

        $chkEmail = 0;
        if (count($row['id']) > 0) {
            $chkEmail = 1;
        }
        return $chkEmail;
    }

    public function fb_check($userdata) {

        $fbid = $userdata['fbid'];
        $query = "Select id from author where fbid = '$fbid'";
        $row = $this->queryRun($query, 'select');

        $fbid_check = 0;
        if (count($row['id']) > 0) {
            $fbid_check = 1;
        }
        return $fbid_check;
    }

    public function newUserRegister($userdata) {
        $email = $userdata['email_address'];
        $author_product = $userdata['author_product'];
        $author_product_category = $userdata['author_product_category'];
        $first_name = $userdata['first_name'];
        $last_name = $userdata['last_name'];
        $company_name = $userdata['company_name'];
        $mobile_number = $userdata['mobile_number'];
        $source = $userdata['source'];
        $password = $userdata['password'];
        $code = $userdata['verification_code'];

        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));
        $query = "INSERT INTO `author`(`plan_id`, `plan_expiry_date`,`created`, `email_address`,`first_name`,`last_name`,`organisation_name`,`mobile`,`product_prefereed`,`theme_preferred`,`source`,`custid`, `password`,`verification_code`,`active`,`deleted`) VALUES ('1','$NewDate','$servertime','$email','$first_name','$last_name','$company_name','$mobile_number','$author_product','$author_product_category','$source',UNIX_TIMESTAMP(),'$password','$code','1','0')";
        $user_id = $this->queryRun($query, 'insert');

        if ($user_id > 0) {
            $authT = $this->auth_create($user_id);
            if ($authT != '') {
                $data = array('register' => array('updatedOn' => $servertime, 'authToken' => $authT));
                $json = $this->real_json_encode($data, 'successData', '', '200');
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', '405');
            echo $json;
        }
    }

    function newuserregister_fb($userdata) {
        $email = $userdata['email_address'];
        $password = $userdata['password'];
        $fb_token = $userdata['fb_token'];
        $first_name = $userdata['first_name'];
        $last_name = $userdata['last_name'];
        $source = $userdata['source'];
        $avatar = $userdata['avatar'];
        $fbid = $userdata['fbid'];
        $code = $userdata['verification_code'];
        $base_url = $userdata['base_url'];
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));
        $query = "INSERT INTO `author`(`plan_id`, `plan_expiry_date`,`created`, `first_name`,`last_name`,`avatar`,`fb_token`,`fbid`,`email_address`,`custid`,`source`,`password`,`verification_code`,`is_confirm`,`active`,`deleted`) VALUES ('1','$NewDate','$servertime','$first_name','$last_name','$avatar','$fb_token','$fbid','$email',UNIX_TIMESTAMP(),'$source','$password','$code','1','1','0')";
        $user_id = $this->queryRun($query, 'insert');

        if ($user_id > 0) {
            $authT = $this->auth_create($user_id);
            if ($authT != '') {
                $query = "select * from author where id=$user_id";
                $userresults = $this->queryRun($query, 'select');
                if ($userresults['email_address'] != '') {
                    $lname = $userresults['last_name'];
                    $fname = $userresults['first_name'];
                    $username = $userresults['email_address'];
                    $custid = $userresults['custid'];
                    $basicUrl = $base_url;
                    $cto = $username;
                    $chtmlcontent = file_get_contents('../edm/Welcome-to-instappy.php');
                    $clastname = $lname != 'User' ? ' ' . $lname : $lname;
                    if (!empty($fname)) {
                        $cname = " " . $fname;
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
                }
                $data = array('register' => array('updatedOn' => $servertime, 'authToken' => $authT, 'custid' => $userresults['custid'], 'email_address' => $userresults['email_address'], 'first_name' => $userresults['first_name']));
                $json = $this->real_json_encode($data, 'successData', '', '200');
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', '405');
            echo $json;
        }
    }

    function updateuser_fb($userdata) {
        $email = $userdata['email_address'];
        $password = $userdata['password'];
        $fb_token = $userdata['fb_token'];
        $first_name = $userdata['first_name'];
        $last_name = $userdata['last_name'];
        $source = $userdata['source'];
        $avatar = $userdata['avatar'];
        $fbid = $userdata['fbid'];
        $code = $userdata['verification_code'];
        $is_confirm = 1;
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));
        $query = "update  author set plan_id=1,plan_expiry_date='$NewDate',updated='$servertime',first_name='$first_name',last_name='$last_name',avatar='$avatar',fb_token='$fb_token',fbid='$fbid',active=1,deleted=0 where fbid='$fbid' ";
        $user_id = $this->queryRun($query, 'update');

        if ($user_id) {
            $query = "select * from author where fbid='$fbid'";
            $userresults = $this->queryRun($query, 'select');
            $custid = $userresults['custid'];
            $data = array('register' => array('updatedOn' => $servertime, 'custid' => $custid, 'email_address' => $userresults['email_address'], 'first_name' => $userresults['first_name']));
            $json = $this->real_json_encode($data, 'successData', '', '200');
            echo $json;
        } else {
            $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
            echo $json;
        }
    }

    function newuserregister_gplus($userdata) {
        $email = $userdata['email_address'];
        $password = $userdata['password'];
        $gPlus_token = $userdata['gPlus_token'];
        $first_name = $userdata['first_name'];
        $avatar = $userdata['avatar'];
        $last_name = $userdata['last_name'];
        $fbid = $userdata['fbid'];
        $code = $userdata['verification_code'];
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));

        $query = "INSERT INTO `author`(`plan_id`, `plan_expiry_date`,`created`, `first_name`,`last_name`,`avatar`,`gPlus_token`,`fbid`,`email_address`, `password`,`verification_code`,`is_confirm`,`active`,`deleted`) VALUES ('1','$NewDate','$servertime','$first_name','$last_name','$avatar','$gPlus_token','$fbid','$email','$password','$code','1','1','0')";
        $user_id = $this->queryRun($query, 'insert');

        if ($user_id) {
            $authT = $this->auth_create($user_id);
            if ($authT != '') {
                $data = array('register' => array('updatedOn' => $servertime, 'authToken' => $authT));
                $json = $this->real_json_encode($data, 'successData', '', '200');
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', '405');
            echo $json;
        }
    }

    function updateuser_gplus($userdata) {
        $email = $userdata['email_address'];
        $password = $userdata['password'];
        $gPlus_token = $userdata['gPlus_token'];
        $first_name = $userdata['first_name'];
        $avatar = $userdata['avatar'];
        $last_name = $userdata['last_name'];
        $fbid = $userdata['fbid'];
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));
        $query = "update  author set plan_id=1,plan_expiry_date='$NewDate',updated='$servertime',first_name='$first_name',last_name='$last_name',avatar='$avatar',gPlus_token='$gPlus_token',fbid='$fbid',password='$password',active=1,deleted=0 where email_address='$email' ";
        $user_id = $this->queryRun($query, 'update');
        if ($user_id) {
            $data = array('register' => array('updatedOn' => $servertime));
            $json = $this->real_json_encode($data, 'successData', '', '200');
            echo $json;
        } else {
            $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
            echo $json;
        }
    }

    public function resetPass() {
        if ($_POST != '') {
            if (md5($_POST['old_pass']) == $this->getUserPass($_POST['username'])) {
                $query = 'UPDATE author set password="' . md5($_POST['new_pass']) . '" WHERE email_address="' . $_POST['username'] . '"';
                $homedata = $this->queryRun($query, 'update');
                //print_r($homedata);
                echo 'updated Successfully!';
            } else {
                echo 'Old Password is not correct!';
            }
        } else {
            echo 'error';
        }
    }

    public function getUserPass($email) {
        $query = 'select password from author WHERE email_address="' . $email . '"';
        $result = $this->queryRun($query, 'select');
        return $result['password'];
    }

    public function uploadAppIcons() {
        $appID = $_POST['appid'];

        if (!isset($_FILES['icons']) || !is_uploaded_file($_FILES['icons']['tmp_name'])) {
            echo 'Image file is Missing!';
        } else {
            if (!isset($_POST['appid']) && $_POST['appid'] != '') {
                die('Please select APP to upload icons!'); // output error when above checks fail.
            }

            $appID = $_POST['appid'];
            ############ Configuration ##############
            //$thumb_square_size 		= array(1024,512,192,144,114,100,96,72,60,57,50,48,36); //Thumbnails will be cropped to given array pixels
            $thumb_square_size = array(29, 36, 48, 50, 57, 60, 72, 76, 87, 96, 100, 114, 120, 144, 152, 180, 192, 512, 1024); //Thumbnails will be cropped to given array pixels
            $max_image_size = 1500; //Maximum image size (height and width)
            $thumb_prefix = "appicon_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $_POST['appid'] . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['icons']) || !is_uploaded_file($_FILES['icons']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['icons']['name']; //file name
                $image_size = $_FILES['icons']['size']; //file size
                $image_temp = $_FILES['icons']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size
                // check imageheight & imagewidth is > 1024
                if (($image_size_info[0] < 1024) || ($image_size_info[1] < 1024)) {
                    die('Icon image needs to be 1024x1024 pixels'); // output error when above checks fail.
                }

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        $delquery = "DELETE FROM `self_icons` WHERE app_id='$appID'";
                        $this->queryRun($delquery, 'delete');
                        foreach ($thumb_square_size as $thumb_square_size) {
                            $new_file_name = $image_name_only . '_' . $thumb_square_size . 'X' . $thumb_square_size . '.' . $image_extension;
                            //folder path to save resized images and thumbnails
                            $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                            //call crop_image_square() function to create square thumbnails
                            if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                                die('Error Creating thumbnail');
                            }

                            /* We have succesfully resized and created thumbnail image
                              We can now output image to user's browser or store information in the database */
                            /* echo '<div align="center">';
                              echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                              echo '<br />';
                              echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                              echo '</div>'; */
                            $baseurl = baseUrl();
                            $image_url = str_replace('API/', '', $baseurl);
                            $imageurl = $image_url . 'panelimage/' . $appID . '/' . $thumb_prefix . $new_file_name;
                            $newFileName = $image_name_only . '_' . $thumb_square_size . 'X' . $thumb_square_size;
                            $query = "INSERT INTO `self_icons` (`app_id`,`icon_name`,`icon_type`,`image_40`) VALUES ('$appID','$newFileName','1','$imageurl')";
                            $imagedata = $this->queryRun($query, 'insert');
                            $query = 'UPDATE app_data SET app_image="' . $imageurl . '" WHERE id="' . $appID . '"';
                            $stmt = $this->queryRun($query, 'update');
                            //echo 'Image Uploaded Successfully';
                        }
                        echo 'Icons Uploaded Successfully';
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
    }

    public function uploadAppScreens() {
        $appID = $_POST['appid'];

        if (!isset($_FILES['screens']) || !is_uploaded_file($_FILES['screens']['tmp_name'])) {
            echo 'Image File is Missing!';
        } else {
            if (!isset($_POST['appid']) && $_POST['appid'] != '') {
                die('Please select APP to upload screens!'); // output error when above checks fail.
            }

            $appID = $_POST['appid'];
            ############ Configuration ##############
            $thumb_rect_size = array(
                array(2208, 1242),
                array(1536, 2048),
                array(1600, 2560),
                array(1200, 1920),
                array(1334, 750),
                array(1136, 640),
                array(1024, 768),
                array(480, 320),
                array(768, 1280),
                array(600, 1024)
            ); //Thumbnails will be cropped to given array pixels
            $max_image_size = 2500; //Maximum image size (height and width)
            $thumb_prefix = "splashscreen_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $_POST['appid'] . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['screens']) || !is_uploaded_file($_FILES['screens']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['screens']['name']; //file name
                $image_size = $_FILES['screens']['size']; //file size
                $image_temp = $_FILES['screens']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size
                // check imageheight & imagewidth is > 1024
                if (($image_size_info[0] < 1080) || ($image_size_info[1] < 1920)) {
                    die('Image needs to be 1080*1920 pixels.'); // output error when above checks fail.
                }

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;


                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        $delquery = "DELETE FROM `splash_screen` WHERE app_id='$appID'";
                        $this->queryRun($delquery, 'delete');
                        foreach ($thumb_rect_size as $thumb_rect_size) {

                            $new_file_name = $image_name_only . '_' . $thumb_rect_size[0] . 'X' . $thumb_rect_size[1] . '.' . $image_extension;
                            //folder path to save resized images and thumbnails
                            $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                            //call crop_image_square() function to create square thumbnails
                            if (!$this->crop_image_rectangle($image_res, $thumb_save_folder, $image_type, $thumb_rect_size, $image_width, $image_height, $jpeg_quality)) {
                                die('Error Creating thumbnail');
                            }

                            /* We have succesfully resized and created thumbnail image
                              We can now output image to user's browser or store information in the database */
                            /* echo '<div align="center">';
                              echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                              echo '<br />';
                              echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                              echo '</div>'; */
                            $baseurl = baseUrl();
                            $image_url = str_replace('API/', '', $baseurl);
                            $imageurl = $image_url . 'panelimage/' . $appID . '/' . $thumb_prefix . $new_file_name;
                            $newFileName = $image_name_only . '_' . $thumb_rect_size[0] . 'X' . $thumb_rect_size[1];
                            $query = "INSERT INTO `splash_screen` (`name`,`app_id`,`image_link`) VALUES ('$newFileName','$appID','$imageurl')";
                            $imagedata = $this->queryRun($query, 'insert');
                            $query = 'UPDATE app_data SET splash_screen_id="' . $imagedata . '" WHERE id="' . $appID . '"';
                            $this->queryRun($query, 'update');
                            //echo 'Image Uploaded Successfully';
                        }
                        echo 'Screens Uploaded Successfully';
                    }

                    $thumb_rect_size1080 = array(1080, 1920);
                    $new_file_name = $image_name_only . '_' . $thumb_rect_size1080[0] . 'X' . $thumb_rect_size1080[1] . '.' . $image_extension;
                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $new_canvas = imagecreatetruecolor($thumb_rect_size1080[0], $thumb_rect_size1080[1]); //Create a new true color image
                    //Copy and resize part of an image with resampling
                    if (imagecopyresampled($new_canvas, $image_res, 0, 0, 0, 0, $thumb_rect_size1080[0], $thumb_rect_size1080[1],$thumb_rect_size1080[0],$thumb_rect_size1080[1])) {
                        $this->save_image($new_canvas, $thumb_save_folder, $image_type, $jpeg_quality);
                    }
                    $baseurl = baseUrl();
                    $image_url = str_replace('API/', '', $baseurl);
                    $imageurl = $image_url . 'panelimage/' . $appID . '/' . $thumb_prefix . $new_file_name;
                    $newFileName = $image_name_only . '_' . $thumb_rect_size1080[0] . 'X' . $thumb_rect_size1080[1];
                    $query = "INSERT INTO `splash_screen` (`name`,`app_id`,`image_link`) VALUES ('$newFileName','$appID','$imageurl')";
                    $imagedata = $this->queryRun($query, 'insert');
                    $query = 'UPDATE app_data SET splash_screen_id="' . $imagedata . '" WHERE id="' . $appID . '"';
                    $this->queryRun($query, 'update');

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
    }

    /*
     * Update Billing details of user
     * Added By Varun Srivastava on 12/8/15
     */

    public function updateUserBillingDetails() {
        if (isset($_POST) && $_POST['userid'] != '') {
            $query = 'SELECT * from author_payment where author_id="' . $_POST['userid'] . '" AND app_id="' . $_POST['app_id'] . '"';
            $user_trans = $this->queryRun($query, 'select');
            if (count($user_trans) > 0) {
                $query = 'UPDATE author_payment set author_ip="' . $_POST['userip'] . '", 
													transaction_id="", 
													total_amount="' . $_POST['total_amount'] . '", 
													first_name="' . trim($_POST['billing_fname']) . '", 
													last_name="' . trim($_POST['billing_lname']) . '", 
													email_address="' . trim($_POST['billing_email']) . '", 
													phone="' . trim($_POST['billing_phone']) . '", 
													country="' . $_POST['billing_country'] . '", 
													state="' . $_POST['billing_state'] . '", 
													city="' . trim($_POST['billing_city']) . '", 
													address="' . trim($_POST['billing_address1']) . '"
													WHERE author_id="' . $_POST['userid'] . '"
													AND app_id="' . $_POST['app_id'] . '"';
                $homedata = $this->queryRun($query, 'update');
            } else {
                $query = 'INSERT INTO author_payment set (author_id, author_ip, app_id, transaction_id, total_amount, first_name, last_name, email_address, phone, country, state, city, address) 
						VALUES ("' . $_POST['userid'] . '", "' . $_POST['userip'] . '", "' . $_POST['app_id'] . '", "", "' . $_POST['total_amount'] . '","' . trim($_POST['billing_fname']) . '", "' . trim($_POST['billing_lname']) . '", "' . trim($_POST['billing_email']) . '", "' . trim($_POST['billing_phone']) . '", "' . $_POST['billing_country'] . '", "' . $_POST['billing_state'] . '", "' . trim($_POST['billing_city']) . '", "' . trim($_POST['billing_address1']) . '")';
                $homedata = $this->queryRun($query, 'insert');
            }
        }
    }

    public function resend_email($data) {
        $custid = $data['custid'];
        $url = $data['url'];
        $name = $data['name'];
        $email = $data['email'];
        $uid = uniqid();
        $query = "update  author set verification_code='$uid',email_address='$email',is_confirm=0 where custid=$custid";
        $user_id = $this->queryRun($query, 'update');
        if ($user_id) {

            $csubject = 'Thank you for registering with Instappy. Verify your e-mail';
            $basicUrl = $url;
            $chtmlcontent = file_get_contents('../edm/Registration.php');
            $cname = ucwords($name);
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
        } else {
            echo "fails";
        }
    }

    function uploadAndroidPublish() {
        //print_r($_POST);
        //print_r($_FILES); exit;
        /* for Phone screen */
        $appid = $_POST['app_id'];
        $phone_screen_images = '';




        $queryAndroid = "SELECT * from android_app_data where app_id='" . $_POST['app_id'] . "' LIMIT 0,1";
        $resultAndroid = $this->queryRun($queryAndroid, 'select');

        if (empty($resultAndroid)) {
            $query = "INSERT INTO android_app_data (app_id,title,short_description,full_description,featured_banner_link,promo_video_link,applicationtype,googleplay_category,contentrating,phone_no,website_link,email_address,privacy_policy_link,androidpublisher_email,developerconsole_name) 
                            VALUES ('" . $_POST['app_id'] . "','" . $_POST['app_name'] . "','" . $_POST['app_short_desc'] . "','" . $_POST['app_full_desc'] . "','" . $_POST['app_promo_url'] . "','" . $_POST['app_type'] . "','" . $_POST['app_category'] . "','" . $_POST['app_contant_rating'] . "','" . $_POST['app_phone'] . "','" . $_POST['app_website'] . "','" . $_POST['app_email'] . "','" . $_POST['app_privacy_url'] . "','" . $_POST['app_dev_acc_email'] . "','" . $_POST['app_dev_acc_name'] . "')";
            if ($this->queryRun($query, 'insert')) {

                echo "success";
            } else {
                echo "fails";
            }
        } else {
            $query = "update  android_app_data set title='" . $_POST['app_name'] . "',short_description='" . $_POST['app_short_desc'] . "',full_description='" . $_POST['app_full_desc'] . "',promo_video_link='" . $_POST['app_promo_url'] . "',applicationtype='" . $_POST['app_type'] . "',googleplay_category='" . $_POST['app_category'] . "',contentrating='" . $_POST['app_contant_rating'] . "',phone_no='" . $_POST['app_phone'] . "',website_link='" . $_POST['app_website'] . "',email_address='" . $_POST['app_email'] . "',privacy_policy_link='" . $_POST['app_privacy_url'] . "',androidpublisher_email='" . $_POST['app_dev_acc_email'] . "',developerconsole_name='" . $_POST['app_dev_acc_name'] . "' where app_id='" . $_POST['app_id'] . "'";
            if ($this->queryRun($query, 'update')) {

                echo "success";
            } else {
                echo "fails";
            }
        }
    }

    function uploadIOSPublish() {
        $screen_image_1 = '';
        $screen_image_2 = '';
        $screen_image_3 = '';
        $screen_image_4 = '';
        $screen_image_5 = '';
        $appid = $_POST['app_id'];
        if (!isset($_FILES['upload_image_1']) || !is_uploaded_file($_FILES['upload_image_1']['tmp_name'])) {
            // echo 'Image file is Missing!';
        } else {
            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_1']) || !is_uploaded_file($_FILES['upload_image_1']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_1']['name']; //file name
                $image_size = $_FILES['upload_image_1']['size']; //file size
                $image_temp = $_FILES['upload_image_1']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        $baseurl = baseUrl();
                        $image_url = str_replace('API/', '', $baseurl);
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_1 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 2 starts */
        if (!isset($_FILES['upload_image_2']) || !is_uploaded_file($_FILES['upload_image_2']['tmp_name'])) {
            //	echo 'Image file is Missing!';
        } else {

            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_2']) || !is_uploaded_file($_FILES['upload_image_2']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_2']['name']; //file name
                $image_size = $_FILES['upload_image_2']['size']; //file size
                $image_temp = $_FILES['upload_image_2']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        $baseurl = baseUrl();
                        $image_url = str_replace('API/', '', $baseurl);
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_2 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 2 Ends */
        /* IMAGE 3 starts */
        if (!isset($_FILES['upload_image_3']) || !is_uploaded_file($_FILES['upload_image_3']['tmp_name'])) {
            //	echo 'Image file is Missing!';
        } else {

            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_3']) || !is_uploaded_file($_FILES['upload_image_3']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_3']['name']; //file name
                $image_size = $_FILES['upload_image_3']['size']; //file size
                $image_temp = $_FILES['upload_image_3']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        $baseurl = baseUrl();
                        $image_url = str_replace('API/', '', $baseurl);
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_3 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 3 Ends */
        /* IMAGE 4 starts */
        if (!isset($_FILES['upload_image_4']) || !is_uploaded_file($_FILES['upload_image_4']['tmp_name'])) {
            //	echo 'Image file is Missing!';
        } else {

            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_4']) || !is_uploaded_file($_FILES['upload_image_4']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_4']['name']; //file name
                $image_size = $_FILES['upload_image_4']['size']; //file size
                $image_temp = $_FILES['upload_image_4']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        $baseurl = baseUrl();
                        $image_url = str_replace('API/', '', $baseurl);
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_4 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 4 Ends */
        /* IMAGE 5 starts */
        if (!isset($_FILES['upload_image_5']) || !is_uploaded_file($_FILES['upload_image_5']['tmp_name'])) {
            //	echo 'Image file is Missing!';
        } else {

            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_5']) || !is_uploaded_file($_FILES['upload_image_5']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }
                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_5']['name']; //file name
                $image_size = $_FILES['upload_image_5']['size']; //file size
                $image_temp = $_FILES['upload_image_5']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        $baseurl = baseUrl();
                        $image_url = str_replace('API/', '', $baseurl);
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_5 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 5 Ends */
        $queryAndroid = "SELECT * from ios_app_data where app_id='" . $_POST['app_id'] . "' LIMIT 0,1";
        $resultAndroid = $this->queryRun($queryAndroid, 'select');

        if (empty($resultAndroid)) {
            $query = "INSERT INTO ios_app_data (app_id,title,full_description,keywords,support_url,marketing_url,privacy_policy_url,iphonesix_link,iphonesixplus_link,iphonefive_link,iphonefour_link,ios_category_one,ios_category_two,rating,copyright,publisher_first_name,publisher_last_name,publisher_phone,publisher_email) 
		VALUES ('" . $appid . "','" . $_POST['app_name'] . "','" . $_POST['app_full_desc'] . "','" . $_POST['app_keywords'] . "','" . $_POST['support_url'] . "','" . $_POST['meeting_url'] . "','" . $_POST['privacy_url'] . "','" . $screen_image_1 . "','" . $screen_image_2 . "','" . $screen_image_3 . "','" . $screen_image_4 . "','" . $_POST['app_type'] . "','" . $_POST['app_category'] . "','','" . $_POST['copyright'] . "','" . $_POST['first_name'] . "','" . $_POST['last_name'] . "','" . $_POST['phone'] . "','" . $_POST['email'] . "')";
            $this->queryRun($query, 'insert');
            echo "Uploaded Successfully!";
        } else {
            $query = "update  ios_app_data set title='" . $_POST['app_name'] . "',full_description='" . $_POST['app_full_desc'] . "',keywords='" . $_POST['app_keywords'] . "',support_url='" . $_POST['support_url'] . "',marketing_url='" . $_POST['meeting_url'] . "',privacy_policy_url='" . $_POST['privacy_url'] . "',iphonesix_link='" . $screen_image_1 . "',iphonesixplus_link='" . $screen_image_2 . "',iphonefive_link='" . $screen_image_3 . "',iphonefour_link='" . $screen_image_4 . "',ios_category_one='" . $_POST['app_type'] . "',ios_category_two='" . $_POST['app_category'] . "',rating='',copyright='" . $_POST['copyright'] . "',publisher_first_name='" . $_POST['first_name'] . "',publisher_last_name='" . $_POST['last_name'] . "',publisher_phone='" . $_POST['phone'] . "',publisher_email='" . $_POST['email'] . "' where app_id='" . $appid . "'";
            $this->queryRun($query, 'update');
            echo "Uploaded Successfully!";
        }
    }

    /*
     * function for app wizard api
     * Added by Arun Srivastava on 2/9/15
     */

    public function wizardInit($data) {
        if ((isset($data['author_id']) && $data['author_id'] != '') && (isset($data['app_name']) && $data['app_name'] != '') && (isset($data['email_id']) && $data['email_id'] != '')) {
            $query1 = "SELECT ad.summary as appName, ad.app_id as appId, ad.type_app as appType FROM app_data ad LEFT JOIN author a ON a.id=ad.author_id WHERE a.custid='" . $data['author_id'] . "' AND LOWER(ad.summary)='" . $data['app_name'] . "' AND a.email_address='" . $data['email_id'] . "' LIMIT 0,1";
            $result1 = $this->queryRun($query1, 'select');

            if (!empty($result1)) {
                $dataR = array(
                    'app' => array(
                        'appName' => $result1['appName'],
                        'appId' => $result1['appId'],
                        'appType' => $result1['appType']
                    )
                );
                $json = $this->real_json_encode($dataR, 'successData', '', '200');
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'No data found', '405');
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', '405');
            echo $json;
        }
    }

}
