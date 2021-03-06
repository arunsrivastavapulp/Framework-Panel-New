<?php

require 'Slim/Slim.php';

require_once('common_functions_content.php');

\Slim\Slim::registerAutoloader();

use \Slim\Slim AS Slim;

$app = new Slim();

$app->post('/userregister', 'userRegister'); // Using Post HTTP Method and process customer login function
$app->post('/useravatar', 'userAvatar'); // Using Post HTTP Method and process customer login function
$app->post('/getmyapps', 'getMyapps'); // Using Post HTTP Method and process getMyapps function
$app->post('/getstats', 'getStats'); // Using Post HTTP Method and process getStats function
$app->post('/getappdetails', 'getAppDetails'); // Using Post HTTP Method and process getAppDetails function
$app->post('/getdefaultlayouts', 'getDefaultLayouts'); // Using Post HTTP Method and process getDefaultLayouts function
$app->post('/resetpass', 'resetPass'); // Using Post HTTP Method and process customer login function
$app->post('/resend_code', 'resend_code'); // Using Post HTTP Method and process customer login function
$app->post('/updateuserbillingdetails', 'updateUserBillingDetails'); // Using Post HTTP Method and process user billing details
$app->run();


/*
 * Forgot Password customer
 * Added By Varun Srivastava on 16/7/15
 */

function userRegister() {
    global $app;
    $req = $app->request();
    $data = array();
    $data['fname'] = htmlentities($req->post('fname'));
    $data['lname'] = htmlentities($req->post('lname'));
    $data['phone'] = htmlentities($req->post('phone'));
    $data['country'] = htmlentities($req->post('country'));
    $data['state'] = htmlentities($req->post('state'));
    $data['city'] = htmlentities($req->post('city'));
    $data['address'] = htmlentities($req->post('address'));
    $data['zip'] = htmlentities($req->post('zip'));
    $data['alternet_email'] = htmlentities($req->post('alternet_email'));
    $data['fax'] = htmlentities($req->post('fax'));
    $data['mobile'] = htmlentities($req->post('mobile'));
    $data['website'] = htmlentities($req->post('website'));
    $data['company'] = htmlentities($req->post('company'));
    $data['username'] = htmlentities($req->post('username'));
    $data['email'] = htmlentities($req->post('email'));
    $data['mid'] = htmlentities($req->post('mid'));
    $data['custid'] = htmlentities($req->post('custid'));
    $data['verification_code'] = htmlentities($req->post('verification_code'));
    $data['url'] = htmlentities($req->post('url'));
    $cf = new Fwcore();
    $cf->registerUser($data);
    $cf->vendorrequest($data);
}

/*
 * Forgot Password customer
 * Added By Varun Srivastava on 16/7/15
 */

function resend_code() {
    global $app;
    $req = $app->request();
    $data = array();
    $data['custid'] = htmlentities($req->post('custid'));
    $data['url'] = htmlentities($req->post('url'));
    $data['name'] = htmlentities($req->post('name'));
    $data['email'] = $req->post('email');
    $cf = new Fwcore();
    $cf->resend_email($data);
}

function userAvatar() {
    $cf = new Fwcore();
    $cf->userAvatar();
}

/*
 * Reset Password customer
 * Added By Varun Srivastava on 16/7/15
 */

function resetPass() {
    $cf = new Fwcore();
    $cf->resetPass();
}

/*
 * results app data w.r.t auth token to api
 * Added By Arun Srivastava on 15/6/15
 */

function getMyapps() {
    $cf = new Fwcore();
    $auth_token = $_POST['auth_token'];

    $cal = $cf->authenticationCheck($auth_token);

    try {
        if (!empty($cal)) {
            $user_id = $cal['id'];

            $dbCon = content_db();
            $app_query = "SELECT ad.id, ad.app_id, ad.summary, ad.published, ad.deleted FROM app_data ad WHERE ad.author_id=:authid AND ad.deleted=0";
            $con = $dbCon->prepare($app_query);
            $con->bindParam(":authid", $user_id);
            $con->execute();
            $apps = $con->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($apps)) {
                $data = $apps;
            } else {
                $data = 'No data available';
            }

            echo json_encode(
                    array(
                        "response" => array(
                            "isSucess" => true,
                            "status" => 200,
                            "error" => "",
                            "data" => $data
                        )
                    )
            );
        } else {
            echo json_encode(
                    array(
                        "response" => array(
                            "isSucess" => false,
                            "errorCode" => 403,
                            "errorMsg" => "Authentication Failed"
                        )
                    )
            );
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

/*
 * results app stats w.r.t app_id to api
 * Added By Arun Srivastava on 16/6/15
 */

function getStats() {
    $auth_token = $_POST['auth_token'];
    $app_id = $_POST['app_id'];

    $cal = authenticationCheck($auth_token);

    try {
        if (!empty($cal)) {
            $author_id = $cal['id'];

            $check = appDetailCheck($author_id, $app_id);

            if ($check) {
                $dbCon = content_db();
                $app_query = "SELECT count(uad.id) as total FROM user_appdata uad LEFT JOIN user_devices ud ON uad.user_device_id=ud.id WHERE uad.app_id=:appid";
                $con = $dbCon->prepare($app_query);
                $con->bindParam(":app_id", $app_id,PDO::PARAM_INT);
                $con->execute();
                $appstats = $con->fetch(PDO::FETCH_ASSOC);

                if (!empty($appstats)) {
                    $data = $appstats['total'];
                } else {
                    $data = 'No data available';
                }

                echo json_encode(
                        array(
                            "response" => array(
                                "status" => 200,
                                "error" => "",
                                "data" => $data
                            )
                        )
                );
            } else {
                echo json_encode(
                        array(
                            "response" => array(
                                "status" => '-1',
                                "error" => "You are not authorised to view stats"
                            )
                        )
                );
            }
        } else {
            echo json_encode(
                    array(
                        "response" => array(
                            "status" => '-1',
                            "error" => "Authentication Failed"
                        )
                    )
            );
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

/*
 * results app details w.r.t app_id to api
 * Added By Arun Srivastava on 17/6/15
 */

function getAppDetails() {
    $auth_token = $_POST['auth_token'];
    $app_id = $_POST['app_id'];

    $cal = authenticationCheck($auth_token);

    try {
        if (!empty($cal)) {
            $author_id = $cal['id'];

            $check = appDetailCheck($author_id, $app_id);

            if ($check) {
                $dbCon = content_db();
                $app_query = "SELECT ad.*, CONCAT(a.first_name, ' ', a.last_name) as fullname, a.email_address FROM app_data ad
							  LEFT JOIN author a ON ad.author_id=a.id
							  WHERE ad.id=:app_id";
                  $con = $dbCon->prepare($app_query);
                $con->bindParam(":app_id", $app_id,PDO::PARAM_INT);
                $con->execute();
                $appstats = $con->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($appstats)) {
                    $data = $appstats;
                } else {
                    $data = 'No data available';
                }

                echo json_encode(
                        array(
                            "response" => array(
                                "status" => 200,
                                "error" => "",
                                "data" => $data
                            )
                        )
                );
            } else {
                echo json_encode(
                        array(
                            "response" => array(
                                "status" => '-1',
                                "error" => "You are not authorised to view stats"
                            )
                        )
                );
            }
        } else {
            echo json_encode(
                    array(
                        "response" => array(
                            "status" => '-1',
                            "error" => "Authentication Failed"
                        )
                    )
            );
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

/*
 * results app default layout w.r.t app_id to api
 * Added By Arun Srivastava on 17/6/15
 */

function getDefaultLayouts() {
    $auth_token = $_POST['auth_token'];
    $app_id = $_POST['app_id'];

    $cal = authenticationCheck($auth_token);

    try {
        if (!empty($cal)) {
            $author_id = $cal['id'];

            $check = appDetailCheck($author_id, $app_id);

            if ($check) {
                $dbCon = content_db();
                $app_query = "SELECT ad.*, CONCAT(a.first_name, ' ', a.last_name) as fullname, a.email_address FROM app_data ad
							  LEFT JOIN author a ON ad.author_id=a.id
							  WHERE ad.id=:app_id";
              $con = $dbCon->prepare($app_query);
                $con->bindParam(":app_id", $app_id,PDO::PARAM_INT);
                $con->execute();
                $appstats = $con->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($appstats)) {
                    $data = $appstats;
                } else {
                    $data = 'No data available';
                }

                echo json_encode(
                        array(
                            "response" => array(
                                "status" => 200,
                                "error" => "",
                                "data" => $data
                            )
                        )
                );
            } else {
                echo json_encode(
                        array(
                            "response" => array(
                                "status" => '-1',
                                "error" => "You are not authorised to view stats"
                            )
                        )
                );
            }
        } else {
            echo json_encode(
                    array(
                        "response" => array(
                            "status" => '-1',
                            "error" => "Authentication Failed"
                        )
                    )
            );
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

/*
 * Update Billing details of user
 * Added By Varun Srivastava on 12/8/15
 */

function updateUserBillingDetails() {
    $cf = new Fwcore();
    $cf->updateUserBillingDetails();
}
