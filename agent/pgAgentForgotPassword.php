<?php
include_once "commonFunctions.php";
$commonfunction=new Common();
$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$MainLogo=$commonfunction->getCommonDataValue("MainLogo");
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
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<!-- endinject -->
<!-- Plugin css for this page -->
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
                <img src="<?php echo $CommonDataValueCommonImagePath.$MainLogo;?>" alt="logo" style="width:100%;">
              </div>
              <h4>Forgot Password?</h4>
              <h6 class="font-weight-light">Don't worry, we will help you reset it</h6>
              <div class="pt-3">
				<div class="form-group">
				  <input type="email" class="form-control form-control-lg" id="txtEmail" placeholder="Email">
				</div>
				<div class="mt-3">
				  <button class="btn btn-block btn-success btn-lg font-weight-medium auth-form-btn" id="btnForgotPassword" onclick="funReset();">SEND RESET LINK</button>
				</div>
				<div class="text-center mt-4 font-weight-light">
				  Re-collected your password? <a href="pgAgentLogin.php" class="text-primary">Login</a>
				</div>
              </div>
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
<script src="../assets/js/sweetAlert.js"></script><!-- TW written code -->
<script src="../assets/js/sweetalert2.min.js"></script>
<script src="../assets/js/sweetalert2.all.min.js"></script>
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
$("#txtEmail").blur(function()
{
	removeError(txtEmail);
	if ($("#txtEmail").val()!="")
	{
		if(!validateEmail($("#txtEmail").val())){
			setError(txtEmail);
		}
		else
		{
			removeError(txtEmail);
		}
	}
});
function funReset(){
	if(!validateBlank($("#txtEmail").val())){
		setErrorOnBlur("txtEmail");
	}
	else if(!validateEmail($("#txtEmail").val())){
		setError(txtEmail);
		$("#txtEmail").focus();
	}
	
    else{
		showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed) {
			if (confirmed) {
				deleteYes();
			}
			else {
				deleteNo();
			}
		});  
	}
  } 
  
function deleteYes()
{
	disableButton('#btnForgotPassword','<i class="ti-timer"></i> Processing...');
	$.ajax({
	  type:"POST",
	  url:"apiAgentForgotPassword.php",
	  data:{ email:$("#txtEmail").val()},
	  success:function(response){
		console.log(response);
		enableButton('#btnForgotPassword','SEND RESET LINK');
		if($.trim(response)=="Success"){	
			showAlertRedirect("Success","We have sent reset password instructions to your registered email id, please check and follow the same to reset your password","success","index.php");
		}
		else if($.trim(response)=="NoRecord"){
						
			showAlert("Warning","Email Not Present..","warning");
			$("#txtEmail").val("");
		}
        else if($.trim(response)=="Exist"){	
			showAlert("Warning","Email id doesn't exist","warning");
			$("#txtEmail").val("");
		}			
		 else{
			showAlert("Error","Something went wrong","error");
			$("#txtEmail").val("");
		}
	  }
  });
}
function deleteNo()
{
	//location.href = "pgForgotPassword.php";

}
</script>
</body>
</html>
