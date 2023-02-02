<!--
Created By: --
Created On: --
Modified By: Nikul Chheda
Modified On: 28-04-2022 00:36
-->
<?php 
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$Status = "";
$qrycptype = "select id,collection_point_name from tw_collection_point_type_master where visibility = 'true' order by priority asc";
$retValcptype = $sign->FunctionOption($qrycptype,$Status,'collection_point_name','id'); 

$email = "";
$u1 = $_REQUEST["u1"];
$token = $_REQUEST["v2"];

$qry1 = "select count(*) as cnt from tw_collection_point_master where email = '".$u1."'";
$retVal1 = $sign->Select($qry1);

if($u1!=""){
	
	if($retVal1!=0){
		header("Location:pgExpireLink.html");
	}
	$email = $_REQUEST["u1"];
}
$settingValueCollectionPointImagePathOther =$commonfunction->getSettingValue("CollectionPointImagePathOther"); 
$settingValueCollectionPointMainLogo =$commonfunction->getSettingValue("MainLogo"); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace-Waste | Collection Point Create an Account</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
<!-- endinject -->
<!-- Plugin css for this page -->
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/custom/sweet-alert.css">
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
                <img src="<?php echo $settingValueCollectionPointImagePathOther.$settingValueCollectionPointMainLogo;?>" alt="logo" style="width:100%;">
              </div>
              <h4>New here?</h4>
              <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
              <div class="pt-3">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="txtCollectionPointName" placeholder="Collection Point Name">
                </div>
				<div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="txtContactPersonName" placeholder="Contact Person Name">
                </div>
                <div class="form-group">
                  <input type="number" class="form-control form-control-lg" id="txtMobileNumber" placeholder="Mobile Number">
                </div>
				<div class="form-group">
                 <select id="selCollectionType" class="form-control form-control-sm">
						<option value="">Select Collection Point Type</option>
						<?php echo $retValcptype; ?>
					</select>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="txtPassword" onkeyup="triggerPasswordStrength();" placeholder="Password" />
					<div class="indicator" id="indicator">
						<span class="weak"></span>
						<span class="medium"></span>
						<span class="strong"></span>
					</div>
					<small class="passwordtext" id="passwordtext"></small>
                </div>
                <div class="form-group">
						
                  <input type="password" class="form-control form-control-lg" id="txtConfirmPassword" placeholder="Confirm Password">
				  <span class="ti-eye view-password" onmousedown="viewPassword('#txtConfirmPassword');" onmouseup="viewPassword('#txtConfirmPassword');"></span>
                </div>
				<div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" id="checkbox" class="form-check-input">
                      I agree to all 
                    <i class="input-helper"></i><a href="pgTermsAndCondition.php" target="_blank" class="text-primary"> Terms &amp; Conditions</a></label>
                  </div>
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-success btn-lg font-weight-medium auth-form-btn" id="btncreate" onclick="register();">CREATE ACCOUNT</button>
                  
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Already have an account? <a href="pgCollectionPointLogin.php" class="text-primary">Login</a>
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
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script>
var valcheck = "";
var valpgName = "";
var valaction = "";
var valdata = "";
var valresult = "";
var valstatus = "";
$(document).ready(function(){
	valcheck = "";
	disableButton('#btncreate','CREATE ACCOUNT');
	userLogs(valpgName,valaction,valdata,valresult,valstatus);
});

$("#txtPassword").focus(function(){
  $("#indicator").css("display", "flex");
  $("#passwordtext").css("display", "block");
}); 

$("#txtPassword").blur(function(){
if($("#txtPassword").val()!="")
{
	if(!passwordLength($("#txtPassword").val())){
		$("#txtPassword").focus();
	}
	else
	{
		$("#indicator").css("display", "none");
		$("#passwordtext").css("display", "none");
	}
}
}); 

$('input[type="checkbox"]').click(function(){
	if($(this).prop("checked") == true){
		valcheck = "checked";
		enableButton('#btncreate','CREATE ACCOUNT');
	}
	else if($(this).prop("checked") == false){
		valcheck = "";
		disableButton('#btncreate','CREATE ACCOUNT');
	}
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

function register(){
	if(!validateBlank($("#txtCollectionPointName").val())){
		setError("txtCollectionPointName");
	}
	else if(!validateBlank($("#txtContactPersonName").val())){
		setErrorOnBlur("txtContactPersonName");
	} 
	else if(!isMobile($("#txtMobileNumber").val())){
		setError(txtMobileNumber);
		$("#txtMobileNumber").focus();
	}
	else if(!validateBlank($("#txtPassword").val())){
		setErrorOnBlur("txtPassword");
	} 
	else if(!passwordLength($("#txtPassword").val())){
		$("#txtPassword").focus();
	}
	else if(!validateBlank($("#txtConfirmPassword").val())){
		setErrorOnBlur("txtConfirmPassword");
	} 
	else if($("#txtPassword").val()!=$("#txtConfirmPassword").val()){
		$("#txtConfirmPassword").val("");
		$("#txtConfirmPassword").focus();
	}
	else if(valcheck == ""){
		showAlert("warning","Check terms and conditions","warning");
	}
	else{
		var valToken = "<?php echo $token; ?>";
		disableButton('#btncreate','<i class="ti-timer"></i> Processing...');
		$.ajax({
			type:"POST",
			url:"apiSaveCPRegisterData.php",
			data:{collectionpointname:$("#txtCollectionPointName").val(),
				collectionpointtype:$("#selCollectionType").val(),
				contactpersonname:$("#txtContactPersonName").val(),
				mobile:$("#txtMobileNumber").val(),
				password:$("#txtPassword").val(),
				valToken:valToken},
		    success:function(response){
				console.log(response);
				enableButton('#btncreate','CREATE ACCOUNT');	
				if($.trim(response)=="Success"){
					$("#txtCollectionPointName").val("");
					$("#txtContactPersonName").val("");
					$("#txtMobileNumber").val("");
					$("#txtPassword").val("");
					$("#txtConfirmPassword").val("");
					
					showAlertRedirect("Success","Register Details saved Successfully","success","pgCollectionPointLogin.php");
				}  
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Email is already Present","warning");
					$("#txtMobileNumber").focus();
					$("#txtPassword").val("");
					$("#txtConfirmPassword").val("");
				}  
				else{
					showAlert("Error","Invalid Username/Password","error");
					$("#txtPassword").val("");
					$("#txtConfirmPassword").val("");
				}
			}
		}); 
	}  
}

function triggerPasswordStrength()
{
	const indicator = document.querySelector(".indicator");
	const input = document.querySelector("#txtPassword");
	const weak = document.querySelector(".weak");
	const medium = document.querySelector(".medium");
	const strong = document.querySelector(".strong");
	const text = document.querySelector(".passwordtext");
	checkPasswordStrength(indicator,input,weak,medium,strong,text);
}
</script>
</body>
</html>
