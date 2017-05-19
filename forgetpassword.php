<?php
require_once('config/db.php');
$db = new DB();

$email_address = $_REQUEST['token'];
$password = $_REQUEST['secret'];

$dcrypt = base64_decode($email_address);

if($_POST['change']=='Reset'){
	$newPassword = $_POST['newPassword'];
	$confirmPassword = $_POST['confirmPassword'];

	if($newPassword=='' || $confirmPassword==''){
		echo "Please enter your password";
	}elseif($newPassword !=$confirmPassword){
		echo "Password and Confirm Password not matched";
	}else{
			$mysqli = $db->dbconnection();
		 	$app_query = "select password from user_appdata where email_address='" . $dcrypt . "'"; 
            $appQueryRun = $mysqli->query($app_query);
            $rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC);

            $fetchedPassword = $rowFetch['password'];
            $newPass = md5($confirmPassword);
            if($fetchedPassword == $password){		
				
				$sql = "update user_appdata set password='".$newPass."' where email_address='".$dcrypt."' and password = '".$password."'";
				$stmt = $mysqli->prepare($sql);
				$stmt->execute();
				echo "Your password has been reset successfully";
			}else{
				echo "Your password reset link has been expired";
			}
	}
}
?>

<form method="post">
New Password : <input type="password" name="newPassword"><br><br>
Confirm Password : <input type="password" name="confirmPassword"><br><br>
<input type="submit" name="change" value="Reset">
</form>