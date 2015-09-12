$(document).ready(function(){

    //hide the hint of recaptcha
    $("#recaptcha_response_field").keydown(function(){
        $("#hintcaptcha").hide();
    });

	//sign in authentication
	$(".signinform").submit(function(event){
		var username = $("#inputUsernameSignIn").val();
		var password = $("#inputPasswordSignIn").val();
		var passwordencrypted = $.md5(password);
        var $signinerror = $(".signinerror");
        var $signuperror = $(".signuperror");
        var $servererror = $(".servererror");
        var $verificationerror = $(".verificationerror");
        
        $signinerror.hide();
        $signuperror.hide();
        $servererror.hide();
        $verificationerror.hide();

		event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "php/database/sign_in.php", 
            data: {
                username:username,
                password:passwordencrypted
            },
            async:false,
            success:function(data){
                result = data;
                if(result == "success"){
                    location.href = "./home.php";
                }else{
                    $("#errorModal").modal("show");
                    $(".signinerror").show();
                }
            }
        });
    });  

	//sign up check
    $(".signupform").submit(function(event){
		var username = $("#inputUsernameSignUp").val();
		var email = $("#inputEmailSignUp").val();
		var password = $("#inputPasswordSignUp").val();
		var passwordencrypted = $.md5(password);
        var $signinerror = $(".signinerror");
		var $signuperror = $(".signuperror");
		var $servererror = $(".servererror");
        var $verificationerror = $(".verificationerror");
		var $errorModal = $("#errorModal");
		var recaptcha_challenge_field = Recaptcha.get_challenge();
		var recaptcha_response_field = Recaptcha.get_response();

        $signinerror.hide();
		$signuperror.hide();
		$servererror.hide();
        $verificationerror.hide();

		event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "php/database/sign_up.php", 
            data: {
                username:username,
                email:email,
                password:passwordencrypted,
                recaptcha_challenge_field:recaptcha_challenge_field,
                recaptcha_response_field:recaptcha_response_field
            },
            async:false,
            success:function(data){
                result = data;
                if(result == "success"){
                    location.href = "./home.php";
                }else if(result == "username used"){
                    $errorModal.modal("show");
                    $signuperror.show();
                }else if(result == "server error"){
                	$errorModal.modal("show");
                    $servererror.show();
                }else if(result == "captcha error"){
                    $errorModal.modal("show");
                    $verificationerror.show();
                }
            }
        });
    });  
});

//refresh the recaptcha
$('#errorModal').on('hidden.bs.modal', function () {
    Recaptcha.reload();
})

