<?php 
	session_start();
	$account = $_SESSION['username'];	
	$yearmonthday = $_GET['yearmonthday'];
	$yearmonthday_split=explode('-',$yearmonthday);
	$yearclicked = $yearmonthday_split[0];
	$monthclicked = $yearmonthday_split[1];
	$dayclicked = $yearmonthday_split[2];
	$i = 0;
	$month = opendir("../../accounts/".$account."/".$yearclicked."/".$monthclicked."/".$dayclicked."/thumbs/");
	while (($file = readdir($month)) !== false)
	{
		if($file != "." && $file != ".."){
			$files[$i] = $file;
		}	
		$i++;
	}
	sort($files,SORT_NUMERIC);
	$result = array();
	for($i = 0;$i < count($files); $i++){
		$result[$i]["file"] = $files[$i];
	}
	echo json_encode($result);

?>