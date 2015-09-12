<?php 
	//get number of photos
	session_start();
	$account = $_SESSION['username'];	
	$yearmonthday = $_GET['yearmonthday'];
	$yearmonthday_split=explode('-',$yearmonthday);
	$yearclicked = $yearmonthday_split[0];
	$monthclicked = $yearmonthday_split[1];
	$dayclicked = $yearmonthday_split[2];
	$_SESSION['yearclicked'] = $yearclicked;
	$_SESSION['monthclicked'] = $monthclicked;
	$_SESSION['dayclicked'] = $dayclicked;
	if(!is_dir("../../accounts/".$account."/".$yearclicked."/".$monthclicked."/".$dayclicked."/thumbs/")){
		$number = 0;
	}else{
		$number = count(scandir("../../accounts/".$account."/".$yearclicked."/".$monthclicked."/".$dayclicked."/thumbs/")) - 2;
	}
	$result = array("account"=>$account,"number"=>$number);
	echo json_encode($result);
 	//echo $account;
 	//echo $number;
?>
