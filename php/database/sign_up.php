<?php
session_start();
require_once ("mysql_connect.php");
require_once('recaptchalib.php');
$privatekey = "6LclNugSAAAAAENprGwvgyP9zyVgfIi6Os46AbNJ";
$resp = recaptcha_check_answer ($privatekey,
                            $_SERVER["REMOTE_ADDR"],
                            $_POST["recaptcha_challenge_field"],
                            $_POST["recaptcha_response_field"]);
if (!$resp->is_valid) {
// What happens when the CAPTCHA was entered incorrectly
	echo "captcha error";
} else {
	connect();
	$currentyear = date("Y"); 
	$currentmonth = date("m"); 
	if($currentmonth < "10"){
		$currentmonth = $currentmonth[1];
	}
	$username = strtolower($_POST['username']);  
	$passwordencryped = $_POST['password'];
	$email = $_POST['email'];
	$language = $_SESSION['language_signup'];
	$res = addusernamepassword($username,$passwordencryped,$email,$currentyear,$currentmonth,$language); 
	echo $res;
}


function addusernamepassword($username,$password,$email,$currentyear,$currentmonth,$language){
	$sqlcreate = "INSERT INTO user (username,password,email,language) VALUES ( '$username','$password','$email','$language')";
	$sqlexist = "SELECT username FROM user WHERE username = '$username'";
	$result = mysql_query($sqlexist);
	if(mysql_num_rows($result) == 0){
		$result1 = mysql_query($sqlcreate);
		if(!$result1){
			return "server error";
		} else {  
	        $_SESSION['username'] = $username;
	        $_SESSION['email'] = $email;
	        $_SESSION['language'] = $language;
	        $_SESSION['newuser'] = "yes";
	        $newdir = "../../accounts/$username/$currentyear/$currentmonth";
	       	mkdirs($newdir);
			return "success";
	  }  
	}else{
		return "username used";
	}
	
}



function mkdirs($dir)  
{  
	if(!is_dir($dir))  
	{  
		if(!mkdirs(dirname($dir))){  
			return false;  
		}  
		if(!mkdir($dir,0777)){  
			return false;  
		}  
	}  
	return true;  
} 

?>