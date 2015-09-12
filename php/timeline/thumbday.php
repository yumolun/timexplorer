<?php
	session_start();
	include "../languages/".$_SESSION['language'].".lang.php";
	include "../timeline/functions.php";
	$account = $_SESSION['username'];
	$yearmonth = $_GET['yearmonth'];
	$yearmonth_split=explode('-',$yearmonth);
	$yearclicked = $yearmonth_split[0];
	$monthclicked = $yearmonth_split[1];
	$path = $yearmonth_split[2];
	$i = 0;
	$number = 0;
	$arr = array();
	$covers = array();
	$currentday = date("d");
	if($currentday[0] == 0){
		$currentday = $currentday[1];
	}
	if(!is_dir("../../accounts/".$account."/".$yearclicked."/".$monthclicked)){
        mkdir("../../accounts/".$account."/".$yearclicked."/".$monthclicked);
    }
	$day = opendir("../../accounts/".$account."/".$yearclicked."/".$monthclicked);
	while (($file = readdir($day)) !== false)
	{
		if($file != "." && $file !=".."){
			if(is_dir("../../accounts/".$account."/".$yearclicked."/".$monthclicked."/".$file."/thumbs/")){
				$number = count(scandir("../../accounts/".$account."/".$yearclicked."/".$monthclicked."/".$file."/thumbs/")) - 2;
			}
			
			if($number == 0){
				deldir("../../accounts/".$account."/".$yearclicked."/".$monthclicked."/".$file);
			}else{
				$arr[$i] = $file;
				$covers[$file] = getAlbumCover($account,$yearclicked,$monthclicked,$file,"../../"); 
				$i++;
			}
		}
		
	}
	sort($arr);
	for($i = 0; $i < sizeof($arr); $i++){
		if($arr[$i] != "." && $arr[$i] != ".."){
			if($path == "root"){
				$background = "accounts/".$account."/".$yearclicked."/".$monthclicked."/".$arr[$i]."/thumbs/".$covers[$arr[$i]];
	  			echo "<div class=\"day\" title=\"".$arr[$i]."\"><div class=\"dayposition\"><div class='calendartitle'>".$months[$monthclicked - 1]."</div>". $arr[$i] ."</div></div>";	
			}else{
				$background = "../accounts/".$account."/".$yearclicked."/".$monthclicked."/".$arr[$i]."/thumbs/".$covers[$arr[$i]];
	  			echo "<div class=\"day\" title=\"".$arr[$i]."\"><div class=\"dayposition\"><div class='calendartitle'>".$months[$monthclicked - 1]."</div>". $arr[$i] ."</div></div>";	
			}
		}
	}
							
	if($i == 0){
		$random = rand(1,5);
		if($path == "root"){
			$background = "images/thumbs/".$random.".jpg";
			echo "<div class=\"day\" title=".$currentday."><div class=\"dayposition\"><div class='calendartitle'>".$months[$monthclicked - 1]."</div><a href=\"fileupload\">$empty</a></div></div>";
	  	}else{
			$background = "../images/thumbs/".$random.".jpg";
			echo "<div class=\"day\" title=".$currentday."><div class=\"dayposition\"><div class='calendartitle'>".$months[$monthclicked - 1]."</div>$empty</div></div>";
	  	}
	}
	closedir($day);
?> 