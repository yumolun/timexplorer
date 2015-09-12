<?php 
session_start();

include "php/languages/".$_SESSION['language'].".lang.php";
include "php/timeline/functions.php";

if(isset($_GET['url']) && $_GET['url'] == "signout")
{
	session_destroy();
	echo"<script type='text/javascript'>location='./';</script>";
}

if(isset($_SESSION['username'])){
	$account = $_SESSION['username'];
	if(isset($_SESSION['yearclicked']) && isset($_SESSION['monthclicked']) && isset($_SESSION['dayclicked'])){
	    $currentyear = $_SESSION['yearclicked'];
	    $currentmonth = $_SESSION['monthclicked'];
	    $currentday = $_SESSION['dayclicked'];
	}else{
		$currentyear = date("Y"); 
		$currentmonth = date("m"); 
		$currentday = date("d");
		if($currentmonth[0] == 0){
			$currentmonth = $currentmonth[1];
		}
		if($currentday[0] == 0){
			$currentday = $currentday[1];
		}
	}	
	$i = 0;
	$arr = "";
	$arr1 = "";
	
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<title>Timexplorer</title>

	<link href="css/home.css?version=<?=md5_file('css/home.css')?>" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap.css?v=<?=md5_file('css/bootstrap.css')?>" rel="stylesheet" type="text/css" />
	<!-- html5.js for IE less than 9 -->
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- css3-mediaqueries.js for IE less than 9 -->
	<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->
	
</head>
	<body>
		<div class="navbar navbar-default navbar-fixed-top">
		    <div class="container">
		        <div class="navbar-header">
		            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-fixed-top .navbar-collapse">
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		            </button>
		            <a class="navbar-brand" href="./home.php">Timexplorer</a>
		        </div>
		        <div class="navbar-collapse collapse">
		            <ul class="nav navbar-nav">  
		            </ul>
		            <ul class="nav navbar-nav navbar-right">
		       			<li><a href="./fileupload/"><?php echo $upload;?></a></li>
		       			<li id="settings"><a href="#"><?php echo $settings;?></a></li>
			            <li><a href="home.php?url=signout"><?php echo $signout;?></a></li>
		            </ul>
		        </div>
		    </div>
		</div>

		<div id="loading" class="loading"><span>Loading...</span></div>

		<div class="arrowleft"></div>
        <div class="arrowright"></div>
        <div class="arrowleftforthumbs"></div>
        <div class="arrowrightforthumbs"></div>

		<div id="photowall"></div>

		<div class="modal fade" id="settingsmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		      	<div class="modal-content">
		       	 	<div class="modal-header">
		          		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		          		<h3 style="font-family:Microsoft Yahei sans-serif;" class="modal-title"><?php echo $settings;?></h3>
		        	</div>
		        	<form method="post" action="php/database/requests.php" class="form-horizontal" role="form">
			        	<div style="min-height:200px;" class="modal-body">
			        		<div class="settings-left">
				        		<ul class="nav nav-pills nav-stacked">
								  	<li class="active" id="account_settings"><a href="#"><?php echo $currentaccount;?></a></li>
								  	<li id="change_password"><a href="#"><?php echo $password;?></a></li>
								  	<li id="change_theme"><a href="#"><?php echo $theme;?></a></li>
								</ul>
							</div>
							<div class="settings-right"></div>
				        </div>
				        <div class="modal-footer">
				        	<p id="change-password-success" class="col-lg-6 change-password-success"><?php echo $changepasswordsuccess;?></p>
				        	<p id="change-password-failed" class="col-lg-6 change-password-failed"><?php echo $changepasswordfailed;?></p>
				          	<button id="settings-submit" type="submit" class="btn btn-primary"><?php echo $save;?></button>
				          	<button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $cancel;?></button>
				        </div>
			    	</form>
		      	</div>
		    </div>
		</div>
		<div class="modal fade" id="introduction1">
			<div class="introduction intro1">
			    <div style="height:180px;" class="modal-body">
			        <p style="font-family:'Microsoft Yahei' sans-serif;"><?php echo $intro1;?></p>
			    </div>
			    <div class="modal-footer">
			    	<button type="submit" class="btn btn-primary" data-dismiss="modal"><?php echo $intro1ok;?></button>
			    </div> 
			</div> 
		</div>
		<div id="containertimeline">
			<div class="timeline">
				<div class="thumbday">
					<?php
						$i = 0;
						$number = 0;
						$arr = array();
						$covers = array();
						if(!is_dir("accounts/".$account."/".$currentyear."/".$currentmonth)){
							mkdir("accounts/".$account."/".$currentyear."/".$currentmonth);
						}
						$day = opendir("accounts/".$account."/".$currentyear."/".$currentmonth);
						while (($file = readdir($day)) !== false)
						{
							if($file != "." && $file !=".."){
								if(is_dir("accounts/".$account."/".$currentyear."/".$currentmonth."/".$file."/thumbs/")){
									$number = count(scandir("accounts/".$account."/".$currentyear."/".$currentmonth."/".$file."/thumbs/")) - 2;
								}
								if($number == 0){
									deldir("accounts/".$account."/".$currentyear."/".$currentmonth."/".$file);
								}else{
									$arr[$i] = $file;
									$covers[$file] = getAlbumCover($account,$currentyear,$currentmonth,$file,""); 
									$i++;
								}
							}
						}
						sort($arr);
						for($i = 0; $i < sizeof($arr); $i++){
							if($arr[$i] != "." && $arr[$i] != ".."){
								$background = "accounts/".$account."/".$currentyear."/".$currentmonth."/".$arr[$i]."/thumbs/".$covers[$arr[$i]];
						  		echo "<div class=\"day\" title=\"".$arr[$i]."\"><div class=\"dayposition\"><div class='calendartitle'>".$months[$currentmonth - 1]."</div>". $arr[$i] ."</div></div>";
							}
						}
						if($i == 0){
                   			$random = rand(1,5);
							$background = "images/thumbs/".$random.".jpg";
							echo "<div class=\"day\" title=".$currentday."><div class=\"dayposition\"><div class='calendartitle'>".$months[$currentmonth - 1]."</div><a href=\"fileupload\">$empty</a></div></div>";
						  	
						}
						closedir($day);
					?> 		
				</div>
				<div class="thumbmonth">
					<?php
						$i = 0;
						$month = opendir("accounts/".$account."/".$currentyear);
						while (($file = readdir($month)) !== false)
						{
							$arr1[$i] = $file;
							$i++;
						}
					  	sort($arr1);
						for($i = 0; $i < sizeof($arr1); $i++){
							if($arr1[$i] != "." && $arr1[$i] != ".."){
								
						  		echo "<div class=\"month\" title=\"".$arr1[$i]."\"><div class=\"monthposition\"><div class='calendartitle'>".$currentyear."</div>".$months[$arr1[$i] - 1]."</div></div>";
						  	}
						}
						closedir($month);
					?> 				
				</div>
				<div class="thumbyear">
					<?php
					$year = opendir("accounts/".$account);
					while (($file = readdir($year)) !== false)
					{
						$random = rand(1,5);
					  	if($file != "." && $file != ".."){
					  		echo "<div class=\"year\" title=\"".$file."\"><div class=\"yearposition\"><div class='calendartitle'>Timexplorer</div>". $file ."</div></div>";
					  	}
					}
					closedir($year);
					?> 				
				</div>
			</div>
		</div>
		<footer></footer>
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.smoothdivscroll-1.3-min.js"></script>
		<script type="text/javascript" src="js/timexplorer.js?version=<?=md5_file('js/timexplorer.js')?>"></script>
		<script type="text/javascript" src="js/photowall.js?version=<?=md5_file('js/photowall.js')?>"></script>
		<script type="text/javascript" src="js/languages.js?version=<?=md5_file('js/languages.js')?>"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/jquery.md5.min.js"></script>
	</body>
</html>

<?php
}else{
    echo"<script type='text/javascript'>location='./';</script>";  
}


?>