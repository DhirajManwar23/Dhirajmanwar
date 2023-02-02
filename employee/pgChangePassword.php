<?php 
session_start();
	if(!isset($_SESSION["employeeusername"])){
		header("Location:pgEmployeeLogIn.php");
	}	
	 $chars ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789@#!$%^&*";
     $password = trim(substr( str_shuffle( $chars ),0,16 ));
	 
	include_once "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";
	$commonfunction=new Common();
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Change Password</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/vendors/jquery-toast-plugin/jquery.toast.min.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
		<?php
			include_once("navTopHeader.php");
		?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
		<?php
			include_once("navRightSideSetting.php");
		?>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
        <?php
			include_once("navSideBar.php");
		?>
      <!-- partial -->
	  <!-- ==============MODAL START ================= -->
	  <div class="modal fade" id="modalGeneratePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel"><i class="ti-lock"></i> Auto Generate Password</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				 <div class="input-group">
					 <div class="input-group">
					 
                      <input type="text" class="form-control" id="txtGeneratePassword" value="<?php  echo "".$password;?>"> 
					  <span class="input-group-text" id="copyIcon" onclick="CopyFunction();"><i class="ti-files"></i></span>
					 
					  </input>
					  </div>
                    </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal();">Close</button>
				<button type="button" class="btn btn-primary" onclick="assignPassword();">Apply password</button>
			</div>
			</div>
		</div>
	</div>
	  <!-- ==============MODAL END ================= -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Change Password</h4>
                  <p class="card-description">
                    Make sure you have a strong password!
                  </p>
                  <div class="forms-sample">
                    <div class="form-group">
                      <label for="txtOldPassword">Old Password <span class="text-danger">*</span></label>
                      <input type="password" class="form-control" id="txtOldPassword" placeholder="Old Password" autocomplete="off">
					  <span class="ti-eye view-password" onmousedown="viewPassword('#txtOldPassword');" onmouseup="viewPassword('#txtOldPassword');"></span>
                    </div>
                    <div class="form-group">
                      <label for="txtNewPassword">New Password <span class="text-danger">*</span></label>
                      <input type="password" class="form-control form-control-lg" id="txtNewPassword" onkeyup="triggerPasswordStrength();" placeholder="New Password" />
					  <div class="indicator" id="indicator">
							<span class="weak"></span>
							<span class="medium"></span>
							<span class="strong"></span>
						</div>
						<small class="passwordtext" id="passwordtext"></small>
                    </div>
                    <div class="form-group">
                      <label for="txtConfirmPassword">Confirm Password <span class="text-danger">*</span></label>
                      <input type="password" class="form-control" id="txtConfirmPassword" placeholder="Confirm Password">
					  <div class="view-password">
							<span class="ti-eye pointer-cursor" onmousedown="viewPassword('#txtConfirmPassword');" onmouseup="viewPassword('#txtConfirmPassword');"></span>
						</div>
					</div>
                    <button type="button" class="btn btn-success" id="btnChangePassword" onclick="changepassword();">Change Password</button>
                  </div>
                </div>
              </div>
			  </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><i class="ti-lock"></i> Generate Password</h4>
                  <p class="card-description">
                    Make sure you have a strong password!
				
					<ul class="list-star">
						<li>Use long password combinations</li>
						<li>Combine numbers, special symbol, lowercase, and uppercase letters</li>
						<li>Avoid popular passwords</li>
						<br>
					</ul>
					
                  </p><button type="submit" name="copy" class="btn btn-dark btn-icon-text" valuo onclick="showModal();">Generate Strong Password <i class="ti-file btn-icon-append"></i></button>
                </div>
              </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php
			include_once("footer.php");
		?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
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
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/vendors/jquery-toast-plugin/jquery.toast.min.js"></script>
<script src="../assets/js/toastDemo.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script src="../assets/js/desktop-notification.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script type='text/javascript'>
var valcheck = "";
var valpgName = "";
var valaction = "";
var valdata = "";
var valresult = "";
var valstatus = "" 
$(document).ready(function(){
employeeLogs(valpgName,valaction,valdata,valresult,valstatus);
$("swal-overlay swal-overlay--show-modal").attr("style", "display:none");
});

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


function showModal()
{
	jQuery.noConflict();
	$("#modalGeneratePassword").modal("show");
	//CopyPassword();
}
flag=true;
 function CopyFunction(){
	 flag= false;
	 //alert("hello");
	 /* Get the text field */
var copyText = document.getElementById("txtGeneratePassword");

/* Select the text field */
copyText.select();
//copyText.setSelectionRange(0, 99999); /* For mobile devices */

navigator.clipboard.writeText(copyText.value);

/* Alert the copied text */
//alert("Copied : " + copyText.value);
showToast("","Copied to clipboard","success","#f96868");
 }
 

function closeModal() {
	
  $("#modalGeneratePassword").modal("hide");
 }
 
 function assignPassword(){
	 var password = document.getElementById("txtGeneratePassword").value;
	 $("#txtNewPassword").val(password);
	 $("#txtConfirmPassword").val(password);
	  if (flag==false){
		closeModal();
	 
	 }
	 else{
		 CopyFunction();
		 closeModal();
	 }
 }
 $("#txtNewPassword").blur(function(){
if($("#txtNewPassword").val()!="")
{
	if(!passwordLength($("#txtNewPassword").val())){
		$("#txtNewPassword").focus();
	}
	else
	{
		$("#indicator").css("display", "none");
		$("#passwordtext").css("display", "none");
	}
}
});

function changepassword(){
	if(!validateBlank($("#txtOldPassword").val())){
		setErrorOnBlur("txtOldPassword");
	}
	else if(!validateBlank($("#txtNewPassword").val())){
		setErrorOnBlur("txtNewPassword");
	}
	else if(!passwordLength($("#txtNewPassword").val())){
		$("#txtNewPassword").focus();
	}
	else if($("#txtOldPassword").val()==$("#txtNewPassword").val()){
		$("#txtNewPassword").val("");
		setErrorOnBlur("txtNewPassword");
		
	}
	else if(!validateBlank($("#txtConfirmPassword").val())){
		setErrorOnBlur("txtConfirmPassword");
	}
	else if($("#txtNewPassword").val()!=$("#txtConfirmPassword").val()){
		$("#txtConfirmPassword").val("");
		setErrorOnBlur("txtConfirmPassword");
	}
	else{
		  disableButton('#btnChangePassword','<i class="ti-timer"></i> Processing...');
		$.ajax({
			type:"POST",
			url:"apiChangeEmployeePassword.php",
			data:{oldpswd:$("#txtOldPassword").val(),newpswd:$("#txtNewPassword").val()},
			success:function(response){
				//alert(response);
				console.log(response);
				 enableButton('#btnChangePassword','Change Password');
				
				if($.trim(response)=="Success"){
					showAlertRedirect("Success","Password Changed Successfully. Please Login Again With Your New Password.","success","pgEmployeeLogIn.php");

					$("#txtOldPassword").val("");
					$("#txtNewPassword").val("");
					$("#txtConfirmPassword").val("");
					
					//window.location.href="pgLogOut.php";
				}
				else if($.trim(response)=="OldPassword"){
					showAlert("error","Old Password and New Password cannot be same","error");
					
					$("#txtNewPassword").focus();
				}else if($.trim(response)=="Invalid"){
					showAlert("error","Invalid old password","error");
					
					$("#txtOldPassword").focus();
				}else{
					showAlert("error","Something Went Wrong. Please Try After Sometime","error");
					
				}
			}
		});
		
	}
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