<?php
require 'Slim/Slim.php';

require_once('common_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

//$app->post('/login', 'loginUser'); // Using Post HTTP Method and process loginUser function
$app->post('/getallcategories', 'getAllCategories'); // Using Post HTTP Method and process getAllCategories function
$app->post('/getcategoryrelatedtheme', 'getCategoryRelatedTheme'); // Using Post HTTP Method and process getCategoryRelatedTheme function
$app->post('/getthemedata', 'getThemeData'); // Using Post HTTP Method and process getThemeData function
$app->post('/gettipdata', 'getTipData'); // Using Post HTTP Method and process getTipData function
$app->post('/uploadappicons', 'uploadAppIcons'); // Using Post HTTP Method and process uploadAppIcons function
$app->post('/uploadappscreens', 'uploadAppScreens'); // Using Post HTTP Method and process uploadAppIcons function
$app->run();

function loginUser()
{
	$email     = $_POST['email'];
	$phone     = $_POST['mobile'];
	$password  = $_POST['password'];
	$gcm_token = $_POST['gcm_token'];
	//$device_id = $_POST['device_id'];
	//$platform  = $_POST['platform'];
	
	global $app;
    $req        = $app->request();
    $paramEmail = $req->params('email');
    $paramPhone = $req->params('phone');
	$paramPass  = $req->params('password');
	$token      = bin2hex(openssl_random_pseudo_bytes(16));
	$check      = "SELECT us.id as userID FROM address addr left join persons per on per.addressid=addr.id left join users us on us.personid = per.id WHERE addr.email='$email' and addr.mobile='$phone' and us.password='$password' and us.isDelete != 1";
	try{
		$dbCon = getConnection();
		$con=$dbCon->query($check);
		$row=$con->fetch(PDO::FETCH_ASSOC);
		$cal=$con->rowCount();
		
		if($cal>0)
		{
			$sql11   = "SELECT * FROM usersession WHERE `userid`='".$row['userID']."'";
			$check11 = $dbCon->query($sql11);
			$session = $check11->rowCount();
			if($session>0)
			{
				$sql  = "UPDATE usersession SET authToken='$token', gcmToken='$gcm_token' WHERE `userid`='".$row['userID']."'";
				$stmt = $dbCon->prepare($sql);  
				$stmt->bindParam("email", $paramEmail);
				//$stmt->bindParam("phone", $paramPhone);
				$stmt->bindParam("password", $paramPass);
				$stmt->bindParam("auth_token", $token);
				$stmt->execute();
			}
			else
			{
				$sql3 = "INSERT INTO usersession (`userid`,`authToken`,`gcmToken`) VALUES ('".$row['userID']."','$token','$gcm_token')";
				$stmt3 = $dbCon->prepare($sql3);  
				$stmt3->execute();
			}
		
			$sql1  = "INSERT INTO login_track (`email`,`auth_token`) VALUES ('$email', '$token')";
			$stmt1 = $dbCon->prepare($sql1);  
			$stmt1->bindParam("email", $paramEmail);
			//$stmt1->bindParam("phone", $paramPhone);
			$stmt1->bindParam("auth_token", $token);
			$stmt1->execute();
	 
			$sql_query="SELECT e.id, a.email, CONCAT(b.firstName,' ', b.lastName) AS name, c.authToken AS auth_token, d.description AS role, e.userLevel as level  FROM users e left join persons b on e.personid=b.id left join address a on a.id = b.addressid left join usersession c on e.id=c.userid left join type d on e.userTypeid = d.id WHERE a.mobile='$phone' && a.email='$email' and e.isDelete != 1";
			$stmt   = $dbCon->query($sql_query);
			$cal2=$stmt->rowCount();
			//echo $cal1;
			$users  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$dbCon = null;
			if($cal2>0)
			{
				$obj = json_encode($users);
				header("Content-Type: application/json");
				 echo '{
				"login": {
				"success": "200",
				"error": "","users": ' . $obj . '
						}
						}';
			}
		}
		else
		{
			header("Content-Type: application/json");
				echo '{
				"login": {
				"success": "-1",
				"error": "Authentication Failed",
					"users": {}
					}
					}';
		}
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }   
}

/* 
 * results category data to api
 * Added By Arun Srivastava on 12/6/15
 */
function getAllCategories()
{
	//$auth_token = $_POST['auth_token'];
	//$app_type   = $_POST['app_type'];
	//$platform   = $_POST['platform'];
	
	//$cal = authenticationCheck($auth_token);
   	//try{
		//if($cal>0)
		//{
			$dbCon          = getConnection();
			$category_query = "SELECT id, name FROM category WHERE deleted=0";
			$con            = $dbCon->query($category_query);
		    $categories     = $con->fetchAll(PDO::FETCH_ASSOC);
			
			if(!empty($categories))
			{
				$data = $categories;
			}
			else
			{
				$data = 'No categories found';
			}
			
			echo json_encode(
				array(
					"response" => array(
										"status" => 200,	
										"error"  => "",
										"data"   => $data
										)
				)
			);
		/*
		}
		else
		{
			header("Content-Type: application/json");
		
			echo '{
				"response": {
				"status": "-1",
				"error": "Authentication Failed"}}';
		}
	}
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
	*/
}

/* 
 * results theme data w.r.t category id to api
 * Added By Arun Srivastava on 12/6/15
 */
function getCategoryRelatedTheme()
{
	//$auth_token = $_POST['auth_token'];
	//$app_type   = $_POST['app_type'];
	//$platform   = $_POST['platform'];
	
	//$cal = authenticationCheck($auth_token);
   	//try{
		//if($cal>0)
		//{
			$category    = $_POST['category'];
			
			$dbCon       = getConnection();
			$theme_query = "SELECT t.id, c.id as category_id, t.name, t.description, t.image_url FROM themes t LEFT JOIN category_theme_rel ctr ON t.id=ctr.theme_id LEFT JOIN category c ON ctr.category_id=c.id WHERE c.id='".$category."' AND t.deleted=0 AND ctr.deleted=0 AND c.deleted=0";
			$con         = $dbCon->query($theme_query);
		    $themes      = $con->fetchAll(PDO::FETCH_ASSOC);
			
			if(!empty($themes))
			{
				$data = $themes;
			}
			else
			{
				$data = 'No themes found';
			}
			
			echo json_encode(
				array(
					"response" => array(
										"status" => 200,	
										"error"  => "",
										"data"   => $data
										)
				)
			);
		/*
		}
		else
		{
			header("Content-Type: application/json");
		
			echo '{
				"response": {
				"status": "-1",
				"error": "Authentication Failed"}}';
		}
	}
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
	*/
}

/* 
 * results theme data w.r.t theme id to api
 * Added By Arun Srivastava on 12/6/15
 */
function getThemeData()
{
	//$auth_token = $_POST['auth_token'];
	//$app_type   = $_POST['app_type'];
	//$platform   = $_POST['platform'];
	
	//$cal = authenticationCheck($auth_token);
   	//try{
		//if($cal>0)
		//{
			$theme_id    = $_POST['theme_id'];
			
			$dbCon       = getConnection();
			$theme_query = "SELECT t.widget_html, t.theme_html, t.socialAPI_html FROM themes t WHERE t.id='".$theme_id."' AND t.deleted=0";
			$con         = $dbCon->query($theme_query);
		    $theme       = $con->fetch(PDO::FETCH_ASSOC);
			
			if(!empty($theme))
			{
				$data = $theme;
			}
			else
			{
				$data = 'No data available';
			}
			
			echo json_encode(
				array(
					"response" => array(
										"status" => 200,	
										"error"  => "",
										"data"   => $data
										)
				)
			);
		/*
		}
		else
		{
			header("Content-Type: application/json");
		
			echo '{
				"response": {
				"status": "-1",
				"error": "Authentication Failed"}}';
		}
	}
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
	*/
}

/* 
 * results tip data w.r.t page id to api
 * Added By Arun Srivastava on 15/6/15
 */
function getTipData()
{
	//$auth_token = $_POST['auth_token'];
	//$app_type   = $_POST['app_type'];
	//$platform   = $_POST['platform'];
	
	//$cal = authenticationCheck($auth_token);
   	//try{
		//if($cal>0)
		//{
			$page_id   = $_POST['page_id'];
			
			$dbCon     = getConnection();
			$tip_query = "SELECT * FROM tip_data WHERE page_id='".$page_id."' AND deleted=0";
			$con       = $dbCon->query($tip_query);
		    $tip       = $con->fetchAll(PDO::FETCH_ASSOC);
			
			if(!empty($tip))
			{
				$data = $tip;
			}
			else
			{
				$data = 'No data available';
			}
			
			echo json_encode(
				array(
					"response" => array(
										"status" => 200,	
										"error"  => "",
										"data"   => $data
										)
				)
			);
		/*
		}
		else
		{
			header("Content-Type: application/json");
		
			echo '{
				"response": {
				"status": "-1",
				"error": "Authentication Failed"}}';
		}
	}
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
	*/
}

/* 
 * upload app icons on APP ID basis
 * Added By Varun Srivastava on 22/7/15
 */
function uploadAppIcons()
{
	$cf = new Fwcore();
	$cf->uploadAppIcons();
}

/* 
 * upload app screens on APP ID basis
 * Added By Varun Srivastava on 23/7/15
 */
function uploadAppScreens()
{
	$cf = new Fwcore();
	$cf->uploadAppScreens();
}