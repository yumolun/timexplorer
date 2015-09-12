<?php
session_start();
require_once ("mysql_connect.php");
connect();
$url = "../../home.php";


//change account settings
if(isset($_POST["language"]) && isset($_POST["email"])){
	changeAccountInfo($_SESSION['username'],$_POST["language"],$_POST["email"]);
	$_SESSION['language'] = $_POST["language"];
	$_SESSION['email'] = $_POST["email"];
	header("Location: $url");
}

//change password
if(isset($_GET["noob"])){
	$passwordencryped = $_POST["oldpw"];
	$newpasswordencryped = $_POST["newpw"];
	$res = updatePassword($_SESSION['username'],$passwordencryped,$newpasswordencryped);
	echo $res;
}

//check if this is a new user
if(isset($_GET["newuser"]) && $_GET["newuser"] == "?"){
	if(isset($_SESSION['newuser']) && $_SESSION['newuser'] == "yes"){
		echo "yes";
	}else{
		echo "no";
	}
}

//change new user to old user
if(isset($_GET["statususer"])){
    unset($_SESSION['newuser']);
    echo "no";
}


function changeAccountInfo($username,$language,$email){
	$sqlupdate = "UPDATE user SET language='$language', email='$email' WHERE username='$username'";
	$result = mysql_query($sqlupdate) or die(mysql_error());
}

function checkCurrentPassword($username,$password){
	$sql = "select * from user where username = '$username' and password = '$password'";
    $result = mysql_query($sql) or die("Failure 2 ");
    if(mysql_num_rows($result) == 1) {         
    	return true;
    }else{
    	return false;
    }
}

function updatePassword($username,$password,$newpassword) {
    $checkpassword = checkCurrentPassword($username,$password);
    if($checkpassword) {         
    	$sqlupdate = "UPDATE user SET password='$newpassword' WHERE username='$username'";
    	$result = mysql_query($sqlupdate) or die(mysql_error());
    	if($result){
    		return "success";
    	}else{
    		return "update error";
    	}
    }else{
    	return "incorrect password";
    }
}

?>