<?php
session_start();
include "../languages/".$_SESSION['language'].".lang.php";
if($_SESSION['language'] == "en-US"){
	$a = 5;
	$b = 7;
}else if($_SESSION['language'] == "fr-FR"){
	$a = 6;
	$b = 6;
}else if($_SESSION['language'] == "zh-CN"){
	$a = 3;
	$b = 9;
}

?>

<div class="form-group">
    <label for="inputCurrentPassword" class="col-lg-<?php echo $a;?> control-label"><?php echo $currentpassword;?></label>
    <div class="col-lg-<?php echo $b;?>">
      	<input type="password" name="currentpassword" class="form-control" id="inputCurrentPassword" autocomplete="off" required pattern="[^*]{6,}">
 		<span class="hint" id="hintpassword"><?php echo $errorpassword;?></span>   
    </div>
    <h5></h5>
</div>
<div class="form-group">
    <label for="inputNewPassword" class="col-lg-<?php echo $a;?> control-label"><?php echo $newpassword;?></label>
    <div class="col-lg-<?php echo $b;?>">
      	<input type="password" name="newpassword" class="form-control" id="inputNewPassword" placeholder="<?php echo $hintpassword;?>" autocomplete="off" required pattern="[^*]{6,}">
   		<span class="hint" id="hintnewpassword"><?php echo $errornewpassword;?></span>
    </div>
</div>
<div class="form-group">
    <label for="inputNewPasswordAgain" class="col-lg-<?php echo $a;?> control-label"><?php echo $newpasswordagain;?></label>
    <div class="col-lg-<?php echo $b;?>">
      	<input type="password" name="newpasswordagain" class="form-control" id="inputNewPasswordAgain" autocomplete="off" required pattern="[^*]{6,}">
   		<span class="hint" id="hintpasswordagain"><?php echo $errorpasswordagain;?></span>
    </div>
</div>



















































