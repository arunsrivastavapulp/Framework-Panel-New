<?php

require_once('db.php');
// Config
require_once('../../config.php');

// VQMODDED Startup
require_once('../../system/startup.php');
if (file_exists('../../system/library/user.php')) {
    require_once('../../system/library/user.php');
}

if (file_exists('../../system/library/customer.php')) {
    require_once('../../system/library/customer.php');
}

if (file_exists('../../system/library/mail.php')) {
    require_once('../../system/library/mail.php');
}

if (file_exists('../../system/library/config.php')) {
    require_once('../../system/library/config.php');
}

class mainFunctions {

    public function queryRun($query, $queryType) {
        $dbCon = retail_db();
        $con = $dbCon->query($query);
        if ($queryType == 'insert') {
            $lastInsertId = $dbCon->lastInsertId();
            $dbCon = null;
            return $lastInsertId;
        } elseif ($queryType == 'update') {
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
            $row = $con->execute();
            $dbCon = null;
            return $con;
        }
    }

    public function query_run($query, $queryType) {
        $dbCon = content_db();
        $con = $dbCon->query($query);
        if ($queryType == 'insert') {
            $lastInsertId = $dbCon->lastInsertId();
            $dbCon = null;
            return $lastInsertId;
        } elseif ($queryType == 'update') {
            $dbCon = null;
            return $con;
        } elseif ($queryType == 'select') {
            $row = $con->fetch(PDO::FETCH_ASSOC);
            $dbCon = null;
            return $row;
        } elseif ($queryType == 'select_all') {
            $row = $con->fetchAll(PDO::FETCH_ASSOC);
            $dbCon = null;
            return $row;
        } elseif ($queryType == 'delete') {
            $dbCon = null;
            return $con;
        }
    }

    public function real_json_encode($data = '', $type, $errorTxt, $errorCode, $lastlogin = '', $authtoken = '') {
        header('Content-Type: text/html; charset=utf-8');
        if ($type == 'successData') {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', preg_replace('/\: *([0-9]+\.:?[0-9e+\-]*)/', ':"\\1"', json_encode((
                                    array(
                                        "response" => array(
                                            "result" => "success",
                                            "msg" => "",
                                            "last_login" => $lastlogin,
                                            "auth_token" => $authtoken
                                        ),
                                        "screen_data" => $data
                                    )
                                    ), JSON_NUMERIC_CHECK)));
        } elseif ($type == 'prodsuccessData') {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', json_encode((
                            array(
                                "response" => array(
                                    "result" => "success",
                                    "msg" => "",
                                    "last_login" => $lastlogin,
                                    "auth_token" => $authtoken
                                ),
                                "screen_data" => $data
                            )
            )));
        } elseif ($type == 'success') {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', preg_replace('/\: *([0-9]+\.:?[0-9e+\-]*)/', ':"\\1"', json_encode((
                                    array(
                                        "response" => array(
                                            "result" => "success",
                                            "isSucess" => true,
                                            "code" => $errorCode,
                                            "msg" => $errorTxt
                                        ),
                                        "screen_data" => $data
                                    )
                                    ), JSON_NUMERIC_CHECK)));
        } elseif ($type == 'error') {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', preg_replace('/\: *([0-9]+\.:?[0-9e+\-]*)/', ':"\\1"', json_encode((
                                    array(
                                        "response" => array(
                                            "result" => "fail",
                                            "isSucess" => false,
                                            "errorCode" => $errorCode,
                                            "errorMsg" => $errorTxt
                                        )
                                    )
                                    ), JSON_NUMERIC_CHECK)));
        } else {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', preg_replace('/\: *([0-9]+\.:?[0-9e+\-]*)/', ':"\\1"', json_encode($data, JSON_NUMERIC_CHECK)));
        }
    }

    public function authenticationCheck($authkey) {
        $query = "SELECT id FROM `author` WHERE auth_token='$authkey'";
        $result = $this->queryRun($query, 'select');
        //$returndata = $con->rowCount();
        return $result;
    }

    function authCheck($authToken, $device_id = '') {
        if ($device_id != '') {
            $auth_query = "select u.id, u.device_id, uad.* from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token='" . $authToken . "' AND u.device_id = '" . $device_id . "'";
        } else {
            $auth_query = "select u.id, u.device_id, uad.* from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token='" . $authToken . "' AND uad.user_device_id != '0'";
        }
        $auth_queryExecution = $this->query_run($auth_query, 'select');
        $result_auth = count($auth_queryExecution);
        return $auth_queryExecution;
    }

    function lastLogin($authToken, $user_device_id, $app_id = '') {
        $date = date('Y-m-d H:i:s');
        if ($app_id != '') {
            $query = "UPDATE user_appdata SET last_login='" . $date . "', auth_token='" . $authToken . "' WHERE user_device_id='" . $user_device_id . "' AND app_id = '" . $app_id . "'";
        } else {
            $query = "UPDATE user_appdata SET last_login='" . $date . "', auth_token='" . $authToken . "' WHERE user_device_id='" . $user_device_id . "'";
        }
        $result = $this->query_run($query, 'update');
        //$returndata = $con->rowCount();
        return $date;
    }

    function lastLogin_new($authToken, $user_device_id, $app_id = '') {
        $date = date('Y-m-d H:i:s');
        if ($app_id != '') {
            $query = "UPDATE user_appdata SET last_login='" . $date . "', auth_token='" . $authToken . "' WHERE user_device_id='" . $user_device_id . "' AND app_id = '" . $app_id . "'";
            $result = $this->query_run($query, 'update');
        }


        //$returndata = $con->rowCount();
        return $date;
    }

    function signup($app_idString, $device_id, $name, $lastname, $plateform, $api_version, $email, $pwd, $api_version) {
        $dbCon = content_db();
        $password = md5($pwd);
        //$app_idString = $app_id;


        $appQueryData = "select * from app_data where app_id=:appid";
        $app_screenData = $dbCon->prepare($appQueryData);
        $app_screenData->bindParam(':appid', $app_idString, PDO::PARAM_STR);
        $app_screenData->execute();
        $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);

        if ($result_screenData != '') {
            $app_id = $result_screenData->id;
            $summary = $result_screenData->summary;
        }


        try {

            $app_query = "select u.id,u.device_id , u.user_name from users u where u.device_id=:deviceid";
            $appQueryRun = $dbCon->prepare($app_query);
            $appQueryRun->bindParam(':deviceid', $device_id, PDO::PARAM_STR);
            $appQueryRun->execute();
            $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);
            $calRow = $appQueryRun->rowCount();

            $userId = '';

            if ($calRow == 0) {
                $sqlUserInsert = "INSERT INTO users (`user_name`,`email_address`,`first_name`,`last_name`,`country`,`state`,`city`,`device_id`) VALUES ('', '" . $email . "', '" . $name . "', '','','','','" . $device_id . "')";
                $statementUserInsert = $dbCon->prepare($sqlUserInsert);
                $statementUserInsert->execute();
                $userId = $dbCon->lastInsertId();
            } else {
                $userId = $rowFetch['id'];
            }

            $appQueryData = "select * from user_appdata where app_id='" . $app_id . "' and email_address = '" . $email . "'";
            $app_screenData = $dbCon->query($appQueryData);
            $result_screenData = $app_screenData->fetch(PDO::FETCH_OBJ);
            $FetchCount = $app_screenData->rowCount();

            if ($FetchCount == 0) {
                $digits = 4;
                $auto_otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
                $sqlUserDetails = "INSERT INTO user_appdata (`user_id`,`app_id`,`platform`,`api_version`,`firstname`,`lastname`,`email_address`,`password`,`user_device_id`,`verification_code`) VALUES ('" . $userId . "', '" . $app_id . "', '" . $plateform . "', '" . $api_version . "', '" . $name . "', '" . $lastname . "', '" . $email . "', '" . $password . "', '" . $device_id . "', '" . $auto_otp . "')";

                $UserDetailsInsert = $dbCon->prepare($sqlUserDetails);
                $UserDetailsInsert->execute();

                //$response = 1;
                //echo $response;
                $user_adddataId = $dbCon->lastInsertId();
                return $user_adddataId;
            } else {
                //$response = 0;
                //$json = $this->real_json_encode('', 'error', 'Email Already Exists.', 405);
                //echo $json;
                //die;

                return $result_screenData->id;
            }
        } catch (Exception $e) {
            $response = array("result" => 'error', "msg" => 'Something went wrong');
            $Basearray = array("response" => $response);
            $basejson = json_encode($Basearray);
            echo $basejson;
            die;
        }
    }

    function loginCheck($appID, $email, $pwd) {
        $result_auth = 0;
        if ($appID != '') {
            $auth_query = "select * from user_appdata uad where uad.email_address = '" . $email . "' AND uad.app_id = '" . $appID . "'";
            $auth_queryExecution = $this->query_run($auth_query, 'select');
            if (!empty($auth_queryExecution)) {
                $result_auth = count($auth_queryExecution);
            }
        }
        return $result_auth;
    }

    function loginCheckPurist($appID, $email, $pwd) {
        $result_auth = 0;
        if ($appID != '') {
            $auth_query = "SELECT uad.*, puv.is_verified as purist_admin_verified FROM user_appdata uad LEFT JOIN purist_user_verified puv ON uad.id=puv.customer_id WHERE uad.email_address = '" . $email . "' AND uad.password='" . md5($pwd) . "' AND uad.app_id = '" . $appID . "'";
            $auth_queryExecution = $this->query_run($auth_query, 'select');
            if (!empty($auth_queryExecution)) {
                if ($auth_queryExecution['purist_admin_verified'] > 0) {
                    $result_auth = count($auth_queryExecution);
                } else {
                    $result_auth = '999999999'; // for not verified account
                }
            }
        }
        return $result_auth;
    }

    function lastLoginUpdateToken($authToken, $email = '') {
        $date = date('Y-m-d H:i:s');
        if ($email == '') {
            $query = "UPDATE oc_customer SET last_login='" . $date . "' WHERE auth_token='" . $authToken . "'";
        } else {
            $query = "UPDATE oc_customer SET last_login='" . $date . "', auth_token='" . $authToken . "' WHERE email='" . $email . "'";
        }
        $result = $this->queryRun($query, 'update');
        //$returndata = $con->rowCount();
        return $date;
    }

    function updateCategoryViewCount($category_id) {
        $query = "UPDATE oc_category SET viewed = viewed + 1 WHERE category_id = '" . $category_id . "'";
        $result = $this->queryRun($query, 'update');
    }

    function updateProductViewCount($product_id, $app_id = '') {
        $queryB = "UPDATE oc_product SET viewed = viewed + 1 WHERE product_id = '" . $product_id . "'";
        $this->queryRun($queryB, 'update');

        $queryS = "SELECT count(*) as total_views FROM product_view_count WHERE product_id = '" . $product_id . "' AND app_id = '" . $app_id . "'";
        $resultS = $this->queryRun($queryS, 'select');

        if ($resultS['total_views'] == 0) {
            $queryI = "INSERT INTO product_view_count (product_id, app_id, viewed) VALUES ('" . $product_id . "', '" . $app_id . "', viewed + 1)";
            $resultI = $this->queryRun($queryI, 'insert');
        } else {
            $queryU = "UPDATE product_view_count SET viewed = viewed + 1 WHERE product_id = '" . $product_id . "' AND app_id = '" . $app_id . "'";
            $result = $this->queryRun($queryU, 'update');
        }
    }

    function apiRegisterCheck($data) {
    
        $auth_query = "SELECT * FROM users WHERE device_id='" . $data['device_id'] . "'";
        $auth_queryExecution = $this->query_run($auth_query, 'select');
         
        $auth_query = "SELECT a.id,b.catalogue_type FROM app_data a JOIN app_catalogue_attr b ON a.id = b.app_id WHERE a.app_id='" . $data['app_id'] . "' AND a.type_app='" . $data['app_type'] . "'";
        $app_data = $this->query_run($auth_query, 'select');
        if (!empty($app_data)) {
            if (!empty($auth_queryExecution)) {
                $query = "SELECT * FROM users WHERE device_id = '" . $data['device_id'] . "'";
                $result = $this->query_run($query, 'select');

                if (empty($result)) {
                    // insert user
                    $query = "INSERT INTO users (user_name, device_id) VALUES('" . $data['device_id'] . "', '" . $data['device_id'] . "')";
                    $id = $this->query_run($query, 'insert');
                } else {
                    $id = $result['id'];
                }


                $date = date('Y-m-d H:i:s');
                // update into user devices
                $query = "UPDATE user_devices SET updated= '" . $date . "' WHERE user_id = '" . $id . "' AND device_id = '" . $data['device_id'] . "'";
                $result1 = $this->query_run($query, 'update');

                // select user app data
                $query1 = "SELECT * FROM user_appdata WHERE user_id = '" . $id . "' AND app_id = '" . $app_data['id'] . "'";
                $result2 = $this->query_run($query1, 'select');

                if (empty($result2)) {
                    $authtoken = bin2hex(openssl_random_pseudo_bytes(16));
                    // insert into user app data
                    $query = "INSERT INTO user_appdata (user_id, app_id, user_device_id, download_date, latest_version, api_version, auth_token) VALUES('" . $id . "', '" . $app_data['id'] . "', '" . $data['device_id'] . "', '" . $date . "', '" . $data['app_version'] . "', '" . $data['api_version'] . "', '" . $authtoken . "')";
                    $result = $this->query_run($query, 'insert');
                } else {
                    // update into user app data
                    $query = "UPDATE user_appdata SET last_login = '" . $date . "',latest_version = '" . $data['app_version'] . "',api_version = '" . $data['api_version'] . "' WHERE id='" . $result2['id'] . "'";
                    $result = $this->query_run($query, 'insert');
                    $authtoken = $result2['auth_token'];
                }

                return array($authtoken, $id, $app_data['id']);
            } else {
                $date = date('Y-m-d H:i:s');
                // insert user
                $query = "INSERT INTO users (user_name, created, device_id) VALUES('" . $data['device_id'] . "', '" . $date . "', '" . $data['device_id'] . "')";
                $result = $this->query_run($query, 'insert');


                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $date = date('Y-m-d H:i:s');

                // insert into user devices
                $query = "INSERT INTO user_devices (user_id, device_id, device_platform, auth_token, created) VALUES('" . $result . "', '" . $data['device_id'] . "', '" . $data['platform'] . "', '" . $token . "', '" . $date . "')";
                $result1 = $this->query_run($query, 'insert');

                /*
                  $authtoken = bin2hex(openssl_random_pseudo_bytes(16));
                  // insert into user app data
                  $query = "INSERT INTO user_appdata (user_id, app_id, user_device_id, download_date, latest_version, api_version, auth_token) VALUES('".$result."', '".$app_data['id']."', '".$data['device_id']."', '".$date."', '".$data['app_version']."', '".$data['api_version']."', '".$authtoken."')";
                  $result2 = $this->query_run($query, 'insert');

                  //return $authtoken;
                  return array($authtoken, $result, $result2);
                 */

                // select user app data
                $query1 = "SELECT * FROM user_appdata WHERE user_id = '" . $result . "' AND app_id = '" . $app_data['id'] . "'";
                $result2 = $this->query_run($query1, 'select');

                if (empty($result2)) {
                    $authtoken = bin2hex(openssl_random_pseudo_bytes(16));
                    // insert into user app data
                    $query = "INSERT INTO user_appdata (user_id, app_id, user_device_id, download_date, latest_version, api_version, auth_token) VALUES('" . $result . "', '" . $app_data['id'] . "', '" . $data['device_id'] . "', '" . $date . "', '" . $data['app_version'] . "', '" . $data['api_version'] . "', '" . $authtoken . "')";
                    $result3 = $this->query_run($query, 'insert');
                } else {
                    // update into user app data
                    $query = "UPDATE user_appdata SET last_login = '" . $date . "',latest_version = '" . $data['app_version'] . "',api_version = '" . $data['api_version'] . "'";
                    $result = $this->query_run($query, 'insert');
                    $authtoken = $result2['auth_token'];
                }

                return array($authtoken, $result, $app_data['id']);
            }
        } else {
            return 'fail';
        }
    }

    function newAuthCheck($device_id, $app_type) {
        $query1 = "SELECT ad.* FROM app_data ad LEFT JOIN user_appdata uad ON ad.id=uad.app_id LEFT JOIN users u ON uad.user_id=u.id WHERE u.device_id = '" . $device_id . "' AND ad.type_app = '" . $app_type . "'";
        $result2 = $this->query_run($query1, 'select');
        return $result2;
    }

    function getprofiledata($email, $app_id = '') {
        if ($app_id != '') {
            $query = "SELECT CONCAT(c.firstname, ' ', c.lastname) as fullname, c.email, c.telephone as phone, c.gender, c.customer_id FROM oc_customer c WHERE c.email='" . $email . "' AND c.app_id='" . $app_id . "'";
        } else {
            $query = "SELECT CONCAT(c.firstname, ' ', c.lastname) as fullname, c.email, c.telephone as phone, c.gender, c.customer_id FROM oc_customer c WHERE c.email='" . $email . "'";
        }

        $result = $this->queryRun($query, 'select');
        return $result;
    }

    function getTopParent($cat_id) {
        $usedCats = $cat_id;
        $cquery2 = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc, c.parent_id 
						FROM oc_category c 
						LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
						WHERE c.category_id='" . $cat_id . "'";
        $catdata2 = $this->queryRun($cquery2, 'select');

        if (!empty($catdata2)) {
            if ($catdata2['parent_id'] > 0) {
                $finaldata = $this->getTopParent($catdata2['parent_id']);

                $usedCats = $usedCats . ',' . $finaldata['childs'];

                return array('cats' => $finaldata['cats'], 'childs' => $usedCats);
            } else {
                $finaldata = $catdata2;

                return array('cats' => $finaldata, 'childs' => $usedCats);
            }
        }
    }

    /*
     * function is used to search in multidimentional array
     * Added By Arun Srivastava on 25/9/15
     */

    function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }

    /*
     * get email check pointers by id
     * Added by Arun Srivastava on 29/7/16
     */

    public function get_email_checkpointer($type_id, $app_id) {
        $query = "SELECT eca.email FROM email_checkpointers ec 
				LEFT JOIN email_checkpointers_app_rel eca ON ec.id=eca.checkpoint_id 
				WHERE eca.app_id='" . $app_id . "' AND ec.id='" . $type_id . "'";
        $result = $this->query_run($query, 'select');
        return $result;
    }

    /*
     * send EDM
     * Added by Arun Srivastava on 29/7/16
     */

    public function sendEDM($type, $emaildata) {
        $subject_admin = '';
        $content_admin = '';
        $storename = $emaildata['storename'];
        $fromemail = $emaildata['from'];
        $myto = $emaildata['to'];
        $username = $emaildata['username'];
        $key = 'f894535ddf80bb745fc15e47e42a595e';

        if ($type == 1) {
            $auto_otp = $emaildata['otp'];

            if ($auto_otp != '') {
                $subject = 'Thank you for registering with ' . $storename . '. Verify your e-mail address';
                $content = "Hi " . $username . ",<br /><br />
							In order to activate your " . $storename . " account, we need to verify your email address. Please use the authentication code below to log in:<br /><br />
							" . $auto_otp . "<br /><br />
							Wishing you a great experience at " . $storename . ".<br /><br />
							Best Regards,<br />
							Team " . $storename;
            } else {
                $subject = 'Congratulations! You are now registered on ' . $storename;
                $content = "Hi " . $username . ",<br /><br />
							Thank you for verifying your email address. You are now successfully registered on " . $storename . " app. Get your palate ready for some delicious food!<br /><br />
							Best Regards,<br />
							Team " . $storename;
            }
        } elseif ($type == 2) {
            $subject = 'Congratulations! You are now registered on ' . $storename;
            $content = "Hi " . $username . ",<br /><br />
						Thank you for verifying your email address. You are now successfully registered on " . $storename . " app. Get your palate ready for some delicious food!<br /><br />
						Best Regards,<br />
						Team " . $storename;
        } elseif ($type == 3) {
            $subject = 'Thank you for registering with ' . $storename;
            $content = "Hi " . $username . ",<br /><br />
						Thank you for your registration request with " . $storename . ". We will verify the registration and revert within 24 hrs.<br /><br />
						Best Regards,<br />
						Team " . $storename;

            // email to Admin
            if ($emaildata['is_verified'] == 0) { // if not whitelisted domain
                $name = $emaildata['name'];
                $time = date('Y-m-d H:i:s');
                $mob = $emaildata['phone'];
                $timestamp = strtotime("now");
                $base_user_id = $emaildata['base_user_id'];
                $subject_admin = 'Admin, you have received a registration request';
                $content_admin = 'Hi Admin,<br /><br />
									You have received a registration request for PuristMeal app. Here are the details: <br /><br />
									User Name: ' . $name . '<br />
									Registration Time: ' . $time . '
									User Email: <b>' . $myto . '</b><br />
									User Mobile: ' . $mob . '<br /><br /> 
									Please <a href="http://52.41.179.72/users/' . $base_user_id . '/1/' . $timestamp . '">ACCEPT</a> or <a href="http://52.41.179.72/users/' . $base_user_id . '/0/' . $timestamp . '">DECLINE</a> the registration.
									<br /><br />
									Best Regards,<br />
									Team ' . $storename;
            }
        } elseif ($type == 4) {
            $status = $emaildata['status'];
            if ($status == 1) {
                $subject = 'Congratulations! You are now registered on ' . $storename;
                $content = "Hi " . $username . ",<br /><br />
							Congratulations! You have got successfully registered on PuristMeal App. Get your palate ready for some delicious food.<br /><br />
							Just log in and start ordering.<br /><br />
							Best Regards,<br />
							Team " . $storename;
            } else {
                $subject = 'Sorry! Your registration request was not approved';
                $content = "Hi " . $username . ",<br /><br />
							Oops! It seems like your registration request was not approved.  If you have any queries, please contact us at <a href='mailto:info@puristmeals.com'>info@puristmeals.com</a><br /><br />
							Best Regards,<br />
							Team " . $storename;
            }
        } elseif ($type == 5) {
            $password = $emaildata['password'];
            $subject = $storename . ' App - Reset your password';
            $content = "Hi " . $username . ",<br /><br />
						Oops! It seems like you have forgotten your password. Don't worry. Just use the activation code below to reset your password:<br /><br />
						" . $password . "<br /><br />
						If you did not submit a password reset request for your account, please ignore this e-mail.<br /><br />
						Best Regards,<br />
						Team " . $storename;
        } elseif ($type == 6) {
            $subject = 'Your meal is on the way!';
            $content = "Hi " . $username . ",<br /><br />
						 Thanks for ordering with " . $storename . " app. We are preparing your order now.<br /><br />";
            if ($emaildata['date_time'] == 1) {
                $content .= "Your order will be delivered on " . $emaildata['delivery_date'] . " between " . $emaildata['time_slot'] . "<br /><br />";
            } elseif ($emaildata['date_time'] == 2) {
                $content .= "Your order will be delivered between " . $emaildata['time_slot'] . "<br /><br />";
            }
            $content .= "Best Regards,<br />
						 Team " . $storename;
        } elseif ($type == 7) {
            $password = $emaildata['password'];
            $subject = $storename . ' App - New password';
            $content = "Hi " . $username . ",<br /><br />
						A new password was requested from " . $storename . "<br /><br />
						Your new password is:" . $password . "<br /><br />
						Best Regards,<br />
						Team " . $storename;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'api_key' => $key,
                'subject' => $subject,
                'fromname' => $storename,
                'from' => $fromemail,
                'content' => $content,
                'recipients' => $myto
            )
        ));
        $customerhead = curl_exec($curl);

        curl_close($curl);

        // if email need to send to Admin
        if ($subject_admin != '' && $content_admin != '') {
            $curl_admin = curl_init();
            curl_setopt_array($curl_admin, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'api_key' => $key,
                    'subject' => $subject_admin,
                    'fromname' => $storename,
                    'from' => $fromemail,
                    'content' => $content_admin,
                    'recipients' => $fromemail
                )
            ));
            $adminSendCheck = curl_exec($curl_admin);

            curl_close($curl_admin);
        }
    }

    /*     * ********************************************************************* common function ends ********************************************************************** */

    /*
     * add new customer according to api version
     * Added By Arun Srivastava on 3/12/15
     */

    public function addNewCustomer($postdata, $login_type, $api_version, $app_data) {
        $storename = ucfirst($app_data['summary']);
        /*
          if($api_version < '1.0.2')
          {
          $customer_id = $this->addCustomer($postdata, $login_type, '', $app_data);

          $subject   = 'Registration Successful';
          $formemail = 'noreply@instappy.com';
          $key       = 'f894535ddf80bb745fc15e47e42a595e';
          $content   = 'Congratilation! You are successfully registered.';
          $myto      = $postdata['email'];
          //$url       = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($subject).'&fromname='.rawurlencode("Catalog Email Verification").'&from='.$formemail.'&content='.rawurlencode($content).'&recipients='.$myto;

          //file_get_contents($url);
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
          'content' => $content,
          'recipients' => $myto
          )
          ));
          $customerhead = curl_exec($curl);

          curl_close($curl);
          }
          else
          {
         */
        $digits = 4;
        $auto_otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $customer_id = $this->addCustomer($postdata, $login_type, $auto_otp, $app_data);
        //}
        return $customer_id;
    }

    public function forgotUpdateCustomer($postdata, $api_version, $app_data) {
        $customerquery = "SELECT firstname, customer_id, user_id, email, otp, otp_date FROM oc_customer WHERE email='" . $postdata['email'] . "' AND app_id='" . $app_data['id'] . "'";
        $customer__data = $this->queryRun($customerquery, 'select');

        if ($api_version < '1.0.2') {
            $store_name = $this->getConfig('config_name');
            $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

            $this->editPassword($postdata['email'], $password, $app_data['id']);

            $validemail = $this->get_email_checkpointer(5, $app_screenData['id']);

            $retuendata = 0;
            if (!empty($validemail) && $validemail['email'] != '') {
                $sendData = array();
                $sendData['storename'] = $store_name;
                $sendData['from'] = $validemail['email'];
                $sendData['to'] = $customer__data['email'];
                $sendData['password'] = $password;
                $sendData['username'] = ucfirst($customer__data['firstname']);
                $this->sendEDM(5, $sendData);
            }

            return $password;
        } else {
            $store_name = ucfirst($app_data['summary']);
            //$store_name = $this->getConfig('config_name');
            $digits = 4;
            $password = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            //$password = substr(sha1(uniqid(mt_rand(), true)), 0, 4);

            $this->addPasswordOTP($postdata['email'], $password, $app_data['id']);

            //$this->editPassword($request->post['email'], $password);
            $validemail = $this->get_email_checkpointer(5, $app_data['id']);

            if (!empty($validemail) && $validemail['email'] != '') {
                $sendData = array();
                $sendData['storename'] = $store_name;
                $sendData['from'] = $validemail['email'];
                $sendData['to'] = $postdata['email'];
                $sendData['password'] = $password;
                $sendData['username'] = ucfirst($customer__data['firstname']);
                $this->sendEDM(5, $sendData);
            }

            return $password;
        }
    }

    /*
     * add customer
     * Added By Arun Srivastava on 3/12/15
     */

    public function addCustomer($data, $login_type, $auto_otp = '', $app_data) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        // request
        $request = new Request();
        $registry->set('request', $request);

        // $login_type = 0 means login with facebook else email register
        if ($login_type == 0) {
            $approved = 1;
        } else {
            $approved = 0;
        }

        if ($auto_otp != '') {
            $db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '0', firstname = '" . $db->escape($data['firstname']) . "', lastname = '" . $db->escape($data['lastname']) . "', email = '" . $db->escape($data['email']) . "', telephone = '" . $db->escape($data['telephone']) . "', fax = '" . $db->escape($data['fax']) . "', salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', customer_group_id = '1', ip = '" . $db->escape($request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . $approved . "', gender = '" . $data['gender'] . "', user_id = '" . $data['user_id'] . "', date_added = NOW(), otp = '" . $auto_otp . "', otp_date = NOW(), app_id='" . $data['app_id'] . "'");
        } else {
            // for version < 1.0.1, automatic activation
            $approved = 1;
            $db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '0', firstname = '" . $db->escape($data['firstname']) . "', lastname = '" . $db->escape($data['lastname']) . "', email = '" . $db->escape($data['email']) . "', telephone = '" . $db->escape($data['telephone']) . "', fax = '" . $db->escape($data['fax']) . "', salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int) $data['newsletter'] : 0) . "', customer_group_id = '1', ip = '" . $db->escape($request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . $approved . "', gender = '" . $data['gender'] . "', user_id = '" . $data['user_id'] . "', date_added = NOW(), app_id='" . $data['app_id'] . "'");
        }

        $customer_id = $db->getLastId();

        $db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', firstname = '" . $db->escape($data['firstname']) . "', lastname = '" . $db->escape($data['lastname']) . "', company = '" . $db->escape($data['company']) . "', company_id = '" . $db->escape($data['company_id']) . "', tax_id = '" . $db->escape($data['tax_id']) . "', address_1 = '" . $db->escape($data['address_1']) . "', address_2 = '" . $db->escape($data['address_2']) . "', city = '" . $db->escape($data['city']) . "', postcode = '" . $db->escape($data['postcode']) . "', country_id = '" . (int) $data['country_id'] . "', zone_id = '" . (int) $data['zone_id'] . "'");

        $address_id = $db->getLastId();

        $db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $customer_id . "'");


        $storename = ucfirst($app_data['summary']);
        $validemail = $this->get_email_checkpointer(1, $data['app_id']);

        if (!empty($validemail) && $validemail['email'] != '') {
            $sendData = array();
            $sendData['storename'] = $storename;
            $sendData['from'] = $validemail['email'];
            $sendData['to'] = $data['email'];
            $sendData['username'] = ucfirst($data['firstname']);
            $sendData['otp'] = $auto_otp;
            $this->sendEDM(1, $sendData);
        }

        return $customer_id;
    }

    /*
     * get username while signing up
     * Added By Arun Srivastava on 3/12/15
     */

    public function getUsernameBySignUp($username) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);
        $query = $db->query("SELECT count(*) AS total FROM `" . DB_PREFIX . "user` WHERE username = '" . $db->escape($username) . "'");
        return $query->row['total'];
    }

    /*
     * get email while signing up
     * Added By Arun Srivastava on 3/12/15
     */

    public function getEmailBySignUp($email) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);
        $query = $db->query("SELECT count(*) as total FROM `" . DB_PREFIX . "user` WHERE email = '" . $db->escape($email) . "'");
        return $query->row['total'];
    }

    /*
     * get country by country id
     * Added By Arun Srivastava on 3/12/15
     */

    public function getCountry($country_id) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);
        $query = $db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int) $country_id . "' AND status = '1'");
        return $query->row;
    }

    /*
     * get customer count by email id
     * Added By Arun Srivastava on 3/12/15
     */

    public function getTotalCustomersByEmail($email, $app_id = '') {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        if ($app_id != '') {
            $query = $db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "' AND app_id='" . $app_id . "'");
        } else {
            $query = $db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "'");
        }

        return $query->row['total'];
    }

    /*
     * get customer by email
     * Added By Arun Srivastava on 3/12/15
     */

    public function getCustomerByEmail($email, $customer_id = '') {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        if ($customer_id != '') {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "' AND user_id != '" . $customer_id . "'");
        } else {
            $query = $db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "'");
        }

        return $query->row;
    }

    /*
     * get customer by email
     * Added By Arun Srivastava on 3/12/15
     */

    public function getCustomerByAppEmail($email, $app_id) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        $query = $db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "' AND app_id = '" . $app_id . "'");

        return $query->row;
    }

    /*
     * get user by email
     * Added By Arun Srivastava on 15/7/16
     */

    public function getUserByEmail($email, $app_id) {
        $basesql = "SELECT puv.is_verified, ad.id as app_id FROM users u 
					LEFT JOIN user_appdata uad on uad.user_id=u.id 
					LEFT JOIN purist_user_verified puv on puv.customer_id=uad.id 
					LEFT JOIN app_data ad on ad.id=uad.app_id 
					WHERE uad.email_address = '" . $email . "' AND puv.is_verified='1' AND ad.id='" . $app_id . "'";
        $userdata = $this->query_run($basesql, 'select');

        return $userdata;
    }

    /*
     * get user by email
     * Added By Arun Srivastava on 15/7/16
     */

    public function logoutUser($device_id, $app_id) {
        $basesql = "UPDATE user_appdata SET push_token = '' WHERE user_device_id = '" . $device_id . "' AND app_id='" . $app_id . "'";
        $userdata = $this->query_run($basesql, 'update');

        if ($userdata) {
            return 1;
        } else {
            return 0;
        }
    }

    /*
     * get customer by user id
     * Added By Arun Srivastava on 3/12/15
     */

    public function getCustomerByUserId($customer_id) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        $query = $db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE user_id = '" . $customer_id . "'");

        return $query->row;
    }

    /*
     * edit password
     * Added By Arun Srivastava on 3/12/15
     */

    public function editPassword($email, $password, $app_id = '') {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        if ($app_id != '') {
            $db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "' AND app_id='" . $app_id . "'");
        } else {
            $db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "'");
        }
    }

    /*
     * update user status
     * Added By Arun Srivastava on 3/12/15
     */

    public function updateUserStatus($email) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);
        $db->query("UPDATE " . DB_PREFIX . "customer SET status = 1, approved = 1 WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "'");
    }

    /*
     * update password otp 
     * Added By Arun Srivastava on 3/12/15
     */

    public function addPasswordOTP($email, $otp, $app_id = '') {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        if ($app_id != '') {
            $db->query("UPDATE " . DB_PREFIX . "customer SET otp = '" . $otp . "', otp_date = '" . date('Y-m-d H:i:s') . "' WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "' AND app_id='" . $app_id . "'");
        } else {
            $db->query("UPDATE " . DB_PREFIX . "customer SET otp = '" . $otp . "', otp_date = '" . date('Y-m-d H:i:s') . "' WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "'");
        }
    }

    /*
     * get config
     * Added By Arun Srivastava on 3/12/15
     */

    public function getConfig($key) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);
        $query = $db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key` = '" . $db->escape(utf8_strtolower($key)) . "'");

        return $query->row['value'];
    }

    public function getCurrentCatChild($app_id, $cat_id, $limit = '0') {
        if ($limit == '0') {
            $ProdLimit = '';
        } else {
            $ProdLimit = 'limit ' . $limit;
        }
        $basesql = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc, c.parent_id, cd.icomoon AS icomoon_code
					FROM oc_app_id ai JOIN oc_product p ON ai.product_id=p.product_id 
					LEFT JOIN oc_product_to_category pc ON p.product_id=pc.product_id 
					LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
					LEFT JOIN oc_category c ON pc.category_id=c.category_id
					LEFT JOIN oc_vendor vd ON (pd.product_id = vd.vproduct_id) 
					LEFT JOIN oc_vendors vds ON (vd.vendor = vds.vendor_id) 
					LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
					WHERE ai.app_id='" . $app_id . "' AND c.category_id<>'' AND ai.app_id<>'' AND pd.language_id = '1'  
					GROUP BY c.category_id ORDER BY c.parent_id ASC ";
        $catdata = $this->queryRun($basesql, 'select_all');
        $catArr = array();
        if (!empty($catdata)) {
            $singlecat = array();
            $lastParent = '';
            foreach ($catdata as $singlecat) {
                if (count($catArr) == $limit && $limit != 0) {

                    break;
                }
                if ($singlecat['parent_id'] == $cat_id) {


                    $catArr[] = $singlecat;
                } else {

                    $topImgCat = array();

                    if ($lastParent != $singlecat['parent_id']) {
                        $topImgCat = $this->getAllTopLevelCat($singlecat['parent_id'], $cat_id);
                    }
                    $lastParent = $singlecat['parent_id'];

                    if (!empty($topImgCat)) {
                        //Dont know why this is happening/ PLs check @Hemant-Ritu
                        $catArr[] = $topImgCat;
                    }
                }
            }
        }


        return $catArr;
    }

    public function getAllTopLevelCat($cat_id, $needle) {
//                echo "\ngetAllTopLevelCat  0    ";
//                echo $cat_id;
//                echo "\ngetAllTopLevelCat  1   ";
//                echo $needle;
//                
//                echo "\ngetAllTopLevelCat 2   ";

        $basesql = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc, c.parent_id, cd.icomoon AS icomoon_code
					FROM oc_category c 
					LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
					WHERE c.category_id='" . $cat_id . "'";

        $catdata = $this->queryRun($basesql, 'select');



        if (!empty($catdata)) {
            if ($catdata['parent_id'] == $needle) {
//                            echo "\nin here";
//                            echo $catdata['parent_id'];
//                            var_dump($catdata);

                return $catdata;
            } else {
//                                echo "\nin else";
//                                echo $catdata['parent_id'];
                if ($catdata['parent_id'] != 0)
                    $this->getAllTopLevelCat($catdata['parent_id'], $needle);
            }
        }
    }

    /*
     * function is to login to customer
     * added by Arun Srivastava on 24/8/16
     */

    public function insideCustomerLogin($email, $password, $app_id) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        if ($password == '') {
            $customer_query = $db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "' AND status = '1' AND approved = '1' AND app_id='" . $app_id . "'");
        } else {
            $customer_query = $db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $db->escape($password) . "'))))) OR password = '" . $db->escape(md5($password)) . "') AND status = '1' AND approved = '1' AND app_id='" . $app_id . "'");
        }

        $customer_return = array();
        if ($customer_query->num_rows) {
            $customer_id = $customer_query->row['customer_id'];
            $db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $db->escape($_SERVER['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int) $customer_id . "'");
            $customer_return = $customer_query->row;
        }

        return $customer_return;
    }

}
