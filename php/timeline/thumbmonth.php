<?php
	session_start();
	include "../languages/".$_SESSION['language'].".lang.php";
	$account = $_SESSION['username'];
	$yearmonth = $_GET['yearmonth'];
	$yearmonth_split=explode('-',$yearmonth);
	$yearclicked = $yearmonth_split[0];
	$monthclicked = $yearmonth_split[1];
	$path = $yearmonth_split[2];
	$monthletter = "";
	$i = 0;
	$month = opendir("../../accounts/".$account."/".$yearclicked."");
	while (($file = readdir($month)) !== false)
	{
		$arr1[$i] = $file;
		$i++;
	}
	sort($arr1);
	for($i = 0; $i < sizeof($arr1); $i++){
		if($arr1[$i] != "." && $arr1[$i] != ".."){
			if($path == "root"){
	  			echo "<div class=\"month\" title=\"".$arr1[$i]."\"><div class=\"monthposition\"><div class='calendartitle'>".$yearclicked."</div>".$months[$arr1[$i] - 1]."</div></div>";
			}else{
	  			echo "<div class=\"month\" title=\"".$arr1[$i]."\"><div class=\"monthposition\"><div class='calendartitle'>".$yearclicked."</div>".$months[$arr1[$i] - 1]."</div></div>";
			}
	  	}
	}
	closedir($month);
					

?> 