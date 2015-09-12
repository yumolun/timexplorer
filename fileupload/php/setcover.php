<?php
	session_start();
	$account = $_SESSION['username'];
	$year = $_SESSION['yearclicked'];
	$month = $_SESSION['monthclicked'];
	$day = $_SESSION['dayclicked'];
	$file = $_POST["filename"];
	$userFile = "../../accounts/".$account."/".$year."/".$month."/".$day."/user.json";
	$coverFile = array("cover"=>$file);
	$res = file_put_contents($userFile, json_encode($coverFile)); 
	echo $res;
?>