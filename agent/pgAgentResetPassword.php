<?php
include_once "commonFunctions.php";
include_once "function.php";
$commonfunction=new Common();
urlencode($_REQUEST["u1"]);
//echo $_REQUEST["v2"];
$dec_email= $commonfunction->CommonDec($_REQUEST["u1"]);
$dec_token= $commonfunction->CommonDec($_REQUEST["v2"]);
$settingValueStatus= $commonfunction->getSettingValue("Verified Status");

$settingValueAgentImagePathOther =$commonfunction->getSettingValue("AgentImagePathOther"); 

$enc_email = MD5($dec_email);
$qry="SELECT count(*) as cnt FROM tw_agent_reset_password where email='".$dec_email."' and token='".$dec_token."' and status='".$settingValueStatus."'";

$sign=new Signup();
$retVal = $sign->Select($qry);
if ($retVal>=1)
{
	header("Location:pgExpireLink.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace-Waste | Forgot Password</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/vendors/jquery-toast-plugin/jquery.toast.min.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
<!-- endinject -->
<!-- Plugin css for this page -->
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="<?php echo $settingValueAgentImagePathOther."logo.png";?>" alt="logo" style="width:100%;">
              </div>
              <h4>Reset Password?</h4>
              <h6 class="font-weight-light">Don't worry, we will help you reset it</h6>
              <form class="pt-3">
                <div class="form-group">
					<input type="password" class="form-control form-control-lg" id="txtNewPassword" onkeyup="triggerPasswordStrength();" placeholder="New Password" />
						<div class="indicator" id="indicator">
							<span class="weak"></span>
							<span class="medium"></span>
							<span class="strong"></span>
						</div>
					<small class="passwordtext" id="passwordtext"></small>
                </div>
				  <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="txtConfirmPassword" placeholder="Confirm password">
				  <div class="view-password">
					<span class="ti-eye pointer-cursor" onmousedown="viewPassword('#txtConfirmPassword');" onmouseup="viewPassword('#txtConfirmPassword');"></span>
				</div>
                </div>
                <div class="mt-3">
                  <a class="btn btn-block btn-success btn-lg font-weight-medium auth-form-btn" id="btnSignIn" onclick="funPassword();">Submit</a>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Re-collected your password? <a href="index.php" class="text-primary">Login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>
<script src="../assets/js/toastDemo.js"></script>
<script src="../assets/js/desktop-notification.js"></script>
<script src="../assets/js/sweetAlert.js"></script><!-- TW written code -->
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<!-- endinject -->
<script>
 $('input').blur(function()
{
	var valplaceholder = $(this).attr("placeholder");
	var vallblid = $(this).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $(this).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{
		
		if(!checkElementExists)
		{
			$(this).parent().addClass('has-danger');
			$(this).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
		}

	}
	else
	{
		$(this).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
});
function setErrorOnBlur(inputComponent)
{
	var valplaceholder = $("#" +inputComponent).attr("placeholder");
	var vallblid = $("#" +inputComponent).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $("#" +inputComponent).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{
		
		if(!checkElementExists)
		{
			$("#" +inputComponent).parent().addClass('has-danger');
			$("#" +inputComponent).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
			$("#" +inputComponent).focus();
		}

	}
	else
	{
		$("#" +inputComponent).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
}
function setError(inputComponent)
{
	
	var valplaceholder = $(inputComponent).attr("placeholder");
	var vallblid = $(inputComponent).attr("id");
	var valid = "errSet" + vallblid;
	var valtext = "Please enter valid " + valplaceholder;
	var checkElementExists = document.getElementById(valid);
	if(!checkElementExists)
	{
		$("#" + vallblid).parent().addClass('has-danger');
		$("#" + vallblid).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
	}
	
}

function removeError(inputComponent)
{
	var vallblid = $(inputComponent).attr("id");
	$("#" + vallblid).parent().removeClass('has-danger');
	const element = document.getElementById("errSet"+vallblid);
	if (element)
	{
		element.remove();
	}
}

function funPassword(){
 var varPassword = $("#txtNewPassword").val();
 var varUsername = "<?php echo $enc_email; ?>";
 var varToken = "<?php echo $dec_token; ?>";
 var varEmail = "<?php echo $dec_email; ?> "
 var password = $("#txtNewPassword").val();
 var confirmpassword = $("#txtConfirmPassword").val();
	
	if(!validateBlank($("#txtNewPassword").val())){
		setErrorOnBlur("txtNewPassword");
	}
	else if(!validateBlank($("#txtConfirmPassword").val())){
		setErrorOnBlur("txtConfirmPassword");
	}
	else if($("#txtNewPassword").val()!=$("#txtConfirmPassword").val()){
				$("#txtConfirmPassword").val("");
				setErrorOnBlur("txtConfirmPassword");
			}
	else if (password == confirmpassword) {
		$('#btnSignIn').attr("disabled","true");
		$('#btnSignIn').removeClass('btn-success');
		$('#btnSignIn').addClass('btn-secondary');//secondary;
		$('#btnSignIn').css('cursor', 'no-drop');
		$('#btnSignIn').html('<i class="ti-timer"></i> Processing...');
		$.ajax({
		type:"POST",
		url:"apiAgentResetPassword.php",
		data:{username:varUsername,password:varPassword,token:varToken,email:varEmail},
		success:function(response){
		console.log(response);
		$('#btnSignIn').removeAttr('disabled');
		$('#btnSignIn').css('cursor', 'pointer');
		$('#btnSignIn').removeClass('btn-warning');
		$('#btnSignIn').addClass('btn-success');
		$('#btnSignIn').html('Sign In');
		if($.trim(response)=="Success"){
		  showAlertRedirect("Success","Password Reset Successfully. Please Login Again With Your New Password","success","pgAgentLogin.php");
		}
		else{
			showAlert("Error","Something went wrong","error");
			$("#password").val("");
			$("#confirmpassword").val("");
		}
	   }
	 }); 
	}
	/* else{		
		showAlert("Warning","Password did not match","warning");	
		$("#password").val("");
		$("#confirmpassword").val("");
		$("#password").focus();
	}	  */
} 
function triggerPasswordStrength()
{
	const indicator = document.querySelector(".indicator");
	const input = document.querySelector("#txtNewPassword");
	const weak = document.querySelector(".weak");
	const medium = document.querySelector(".medium");
	const strong = document.querySelector(".strong");
	const text = document.querySelector(".passwordtext");
	checkPasswordStrength(indicator,input,weak,medium,strong,text);
}
   </script>
</body>

</html>
