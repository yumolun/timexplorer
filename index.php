<?php 
session_start();
include "php/languages/functions_lang.php";

if(isset($_GET['language'])){
	include "php/languages/".$_GET['language'].".lang.php";
	$testselected = $_GET['language'];
	$_SESSION['language_signup'] = $_GET['language'];
	setrawcookie("language_cookie", $_GET['language'], time() + 60*60*24*365*10);
}else{
    if(isset($_COOKIE["language_cookie"])){
     	include "php/languages/".$_COOKIE["language_cookie"].".lang.php";
     	$testselected = $_COOKIE["language_cookie"];
     	$_SESSION['language_signup'] = $_COOKIE["language_cookie"];
    }else{
    	$_SESSION['language_signup'] = getDefaultlanguage();
    	include "php/languages/".$_SESSION['language_signup'].".lang.php";
    	$testselected = $_SESSION['language_signup'];
    }
}



if(isset($_SESSION['username'])){
    echo"<script type='text/javascript'>location='home.php';</script>";  
}else{


?>


<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<title>Timexplorer</title>
	<link href="css/index.css?version=<?=md5_file('css/index.css')?>" rel="stylesheet" type="text/css" />
	<link href="css/index_media.css?v=<?=md5_file('css/index_media.css')?>" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap.css?v=<?=md5_file('css/bootstrap.css')?>" rel="stylesheet" type="text/css" />
	<!-- html5.js for IE less than 9 -->
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- css3-mediaqueries.js for IE less than 9 -->
	<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->
	<script type="text/javascript">
	 	var RecaptchaOptions = {
		    theme : 'custom',
		    custom_theme_widget: 'recaptcha_widget'
		};
	</script>

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
		            <a class="navbar-brand" href="">Timexplorer</a>
		        </div>
		        <div class="navbar-collapse collapse">
		            <ul class="nav navbar-nav">  
		            	<li><a href="readme.html" target="_blank">施工进度 测试账号foulgit密码123456</a></li>
		            </ul>
		            <ul class="nav navbar-nav navbar-right">
		            	<li class="dropdown">
			              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo getLanguageName($testselected);?><b class="caret"></b></a>
			              <ul class="dropdown-menu">
			                <li><a href="./?language=en-US">English</a></li>
			                <li><a href="./?language=fr-FR">Français</a></li>
			                <li><a href="./?language=zh-CN">简体中文</a></li>
			              </ul>
			            </li>
		            </ul>
		        </div>
		    </div>
		</div>

	<div id="container">
		<div id="welcome">
			<article>
				<h1><?php echo $welcome_message;?></h1>
			</article>
		</div>
		<div id="forms">
			<div class="signin">
				<form class="signinform" method="post">
					<fieldset>
						<input id="inputUsernameSignIn" class="formtext" type="text" name="username" placeholder="<?php echo $username;?>" autocomplete="true" required>
						<input id="inputPasswordSignIn" class="formtext" type="password" name="password" placeholder="<?php echo $password;?>" autocomplete="true" required>
						<input class="formbutton" type="submit" value="<?php echo $signin;?>">
					</fieldset> 	
				</form>
			</div>
			<div class="signup">
				<form class="signupform" method="post">
					<fieldset>
						<input id="inputUsernameSignUp" class="formtext" type="text" name="username" placeholder="<?php echo $username;?>" autocomplete="off" required pattern="[a-zA-Z0-9]{4,20}">						
						<span class="hint"><?php echo $hintusername;?></span>
						<input id="inputEmailSignUp" class="formtext" type="email" name="email" placeholder="<?php echo $email;?>" autocomplete="off" required>
						<span class="hint"><?php echo $hintemail;?></span>
						<input id="inputPasswordSignUp" class="formtext" type="password" name="password" placeholder="<?php echo $password;?>" autocomplete="off" required pattern="[^*]{6,}">
						<span class="hint"><?php echo $hintpassword;?></span>

						<div id="recaptcha_widget" style="display:none">
						   	<div id="recaptcha_image" onclick="javascript:Recaptcha.reload()"></div>
						   	<input class="formtext" id="recaptcha_response_field" type="text" name="recaptcha_response_field" placeholder="<?php echo $entercaptcha;?>" autocomplete="off" required>
						   	<span id="hintcaptcha" class="hint"><?php echo $getanothercaptcha;?></span>
						</div>	
						<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6LclNugSAAAAAFUm0KLZRD9a5fIB4X7PuaZmZunF"></script>
						<noscript>
						   	<iframe src="http://www.google.com/recaptcha/api/noscript?k=6LclNugSAAAAAFUm0KLZRD9a5fIB4X7PuaZmZunF"
						        height="300" width="500" frameborder="0"></iframe><br>
						   	<textarea name="recaptcha_challenge_field" rows="3" cols="40">
						   	</textarea>
						   	<input type="hidden" name="recaptcha_response_field"
						        value="manual_challenge">
						</noscript> 

						<div><input class="formbutton" type="submit" value="<?php echo $signup;?>"></div>
					</fieldset> 	
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div style="width:450px;" class="modal-dialog">
	      	<div class="modal-content">
	       	 	<div class="modal-header">
	          		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          		<h3 class="signinerror" class="modal-title"><?php echo $signinerror;?></h3>
	          		<h3 class="signuperror" class="modal-title"><?php echo $signuperror;?></h3>
	          		<h3 class="servererror" class="modal-title"><?php echo $servererror;?></h3>
	          		<h3 class="verificationerror" class="modal-title"><?php echo $verificationerror;?></h3>
	        	</div>
	        	<div style="height:50px;text-align:center;" class="modal-body">
		        	<p class="signinerror"><?php echo $signinerrormsg;?></p>
		        	<p class="signuperror"><?php echo $signuperrormsg;?></p>
		        	<p class="servererror"><?php echo $servererrormsg;?></p>
		        	<p class="verificationerror"><?php echo $verificationerrormsg;?></p>
		        </div>
		        <div class="modal-footer">
		          	<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $retry;?></button>
		        </div>
	      	</div>
	    </div>
	</div>

	<footer></footer>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.md5.min.js"></script>
	<script type="text/javascript" src="js/index.js?version=<?=md5_file('js/index.js')?>"></script>
	<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
</body>
</html>

<?php
}
?>