<?php
session_start();
include "../languages/functions_lang.php";
include "../languages/".$_SESSION['language'].".lang.php";
?>
<div class="form-group"></div>
<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label"><?php echo $email;?></label>
    <div class="col-lg-9">
      	<input type="email" name="email" class="form-control" id="inputEmail1" autocomplete="off" placeholder="" value="<?php 				      						
	      							$emailaddress = $_SESSION['email'];
	      							echo $emailaddress;
      							?>">
    </div>
 </div>
<div class="form-group">
	<label class="col-lg-3 control-label"><?php echo $language;?></label>
	<div class="col-lg-9">
   	    <select class="form-control" name="language" id="language">
      	<?php
		    $language_array = array_language();
		    foreach($language_array as $key => $value){
		        if($_SESSION['language'] == $value){
		            $selected = "selected = 'selected' ";
		        }else{
		            $selected = "";
		        }
		?>
		    	<option value="<?php echo $value;?>" <?php echo $selected;?>><?php echo getLanguageName($value);?></option>
		<?php
		    }   
		?>
		</select>
	</div>
</div>