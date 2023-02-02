<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$_SESSION["requesttype"] = $requesttype;


$id = $_REQUEST["id"];
$_SESSION["requestid"]=$id;

$name = "";
$email = ""; 
$role= "";
$sub_admin_status= "";

$settingValueAdminPanel=$commonfunction->getSettingValue("AdminPanel"); 
	
if($requesttype=="edit"){
	
	$qry = "SELECT sa.name,sa.email,rm.role_name,sa.sub_admin_status from tw_sub_admin sa INNER JOIN tw_role_master rm ON sa.role = rm.id WHERE sa.id='".$id."'";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$name = $decodedJSON->response[0]->name;
	$email = $decodedJSON->response[1]->email;
	$role_name = $decodedJSON->response[2]->role_name;
	$sub_admin_status = $decodedJSON->response[3]->sub_admin_status;
	
	
	$qry1 = "SELECT id,role_name from tw_role_master WHERE visibility='true' and panel='".$settingValueAdminPanel."' Order by priority,role_name ";
	$retVal1 = $sign->FunctionOption($qry1,$role,'role_name','id');
	
	
}
else{
	$qry1 = "SELECT id,role_name from tw_role_master WHERE visibility='true' and panel='".$settingValueAdminPanel."' Order by priority,role_name ";
	$retVal1 = $sign->FunctionOption($qry1,$role,'role_name','id');
	
	
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Sub Admin Master</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
<!-- endinject -->
<!-- inject:css -->
<!-- endinject -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<!-- tw-css:start -->
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
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Sub Admin Master</h4>
                  <div class="forms-sample">
                    <div class="form-group">
						<label for="txtbankaccountprooftype">Name<code>*</code></label>
						<input type="text" class="form-control" id="txtname" maxlength="100" value="<?php echo $name; ?>" placeholder="Name" />
                    </div>
					<div>
						<label for="txtbankaccountprooftype">Email <code>*</code></label>
						<input type="text" class="form-control" id="txtemail" maxlength="100" value="<?php echo $email; ?>" placeholder="Email" />
                    </div><br>
					<div class="form-group" >
						<label for="selRole">Role <code>*</code></label>
						<select name="role" id="txtrole" class="form-control" >
						 <?php echo $retVal1;?>
						</select>
					</div>
						<button type="button" id="btnAddrecord" class="btn btn-success" onclick="addrecord();"><?php if($requesttype=="add"){ ?>Add Record<?php }else{ ?>Update Record<?php } ?></button>
                  </div>
                </div>
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
<script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<script src="../assets/js/sweetAlert.js"></script>
<script src="../assets/js/validationAlert.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<!-- endinject -->

<script type='text/javascript'>
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
 $("#txtemail").blur(function()
 {
 removeError(txtemail);
     if ($("#txtemail").val()!="")
	{
		if(!validateEmail($("#txtemail").val())){
			setError(txtemail);
		}
		else
		{
			removeError(txtemail);
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
var valrequesttype = "<?php echo $requesttype;?>";
function addrecord(){
		var buttonHtml = $('#btnAddrecord').html();
		if(!validateBlank($("#txtname").val())){
			setErrorOnBlur("txtname");
		  }
		  else if(!validateBlank($("#txtemail").val())){
			setErrorOnBlur("txtemail");
		  }
		  else if(!validateEmail($("#txtemail").val())){
			setErrorOnBlur("txtemail");
		  }
		  else if(!validateBlank($("#txtrole").val())){
			setErrorOnBlur("txtrole");
		  }
		 
		  else{
			disableButton('#btnAddrecord','<i class="ti-timer"> </i> Processing...');
			$.ajax({
				type:"POST",
				url:"apiAddSubAdmin.php",
				data:{name:$("#txtname").val(),email:$("#txtemail").val(),role:$("#txtrole").val(),status : status,},
				success:function(response){
				console.log(response);
				if(valrequesttype=="add"){
					enableButton('#btnAddrecord','Add Record');
				}
				else{
					enableButton('#btnAddrecord','Update Record');
				}	
					
					if($.trim(response)=="Success"){
						if(valrequesttype=="add"){
							enableButton('#btnAddrecord','Add Record');
							//showAlertRedirect("Success","Data Added Successfully","success","pgSubAdmin.php");
							showAlertRedirect("Success","Data Added Successfully","success","pgSubAdmin.php");
							}
							
						else{
							enableButton('#btnAddrecord','Update Record');
							//showAlertRedirect("Success","Data Updated Successfully","success","pgSubAdmin.php");
							showAlertRedirect("Success","Data Updated Successfully","success","pgSubAdmin.php");
							
						}
						
						$("#txtname").val("");
						$("#txtemail").val("");
						$("#txtrole").val("");
						$("#txtSubAdminStatus").val("");
					}
					
					else if($.trim(response)=="Exist"){
						showAlert("Warning","Record already exist","warning");
						
					}
					else{
						
						showAlert("error","Something Went Wrong","error");
						
					}
					
					$('#btnAddrecord').html(buttonHtml);
				}
			});		 
	}
}	
</script>	
</body>

</html>