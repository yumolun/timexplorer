<?php
	session_start();
	$account = $_SESSION['username'];
	$yearmonth = $_GET['yearmonth'];
	$yearmonth_split=explode('-',$yearmonth);
	$yearclicked = $yearmonth_split[0];
	$monthclicked = $yearmonth_split[1];
	$year = opendir("../../accounts/".$account."");
	while (($file = readdir($year)) !== false)
	{
	  	if($file != "." && $file != ".."){
	  		echo "<div class=\"year\" title=\"".$file."\"><img src=\"images/thumbs/2.jpg\" alt=\"images/thumbs/2.jpg\"><div class=\"yearposition\">". $file ."</div></div>";
	  	}
	}
	closedir($year);

?> 