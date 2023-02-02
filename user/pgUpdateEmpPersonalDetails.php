<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();

$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["company_id"];

$ip_address= $commonfunction->getIPAddress();

$qryEmployeeDetails = "SELECT employee_name,employee_gender,employee_marital_status,date_of_birth FROM tw_employee_registration WHERE id = '".$requestid."'";
$retValEmployeeDetails = $sign->FunctionJSON($qryEmployeeDetails);
$decodedJSON = json_decode($retValEmployeeDetails);
$employee_name = $decodedJSON->response[0]->employee_name;
$employee_gender = $decodedJSON->response[1]->employee_gender;
$employee_marital_status = $decodedJSON->response[2]->employee_marital_status;
$employee_date_of_birth = $decodedJSON->response[3]->date_of_birth;
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Employee Personal Details</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<!-- endinject -->
<!-- tw-css:start -->
<link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
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
                  <h4 class="card-title">Employee Personal Details</h4>
                  <div class="forms-sample">
					<div class="form-group row">
                      <label for="EmployeeName" class="col-sm-3 col-form-label">Employee Name <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $employee_name; ?>" id="txtEmployeeName" placeholder="Employee Name" >
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="EmployeeGender" class="col-sm-3 col-form-label">Employee Gender</label>
                      <div class="col-sm-9">
						 <select name="selcontact" id="txtselGender" class="form-control" >
						    <option value="" <?php  if($employee_gender=="Select Gender"){ echo "selected";} ?>>Select Gender</option>
							<option value="Male" <?php  if($employee_gender=="Male"){ echo "selected";} ?>>Male</option>
							<option value="Female" <?php  if($employee_gender=="Female"){ echo "selected";} ?>>Female</option>
						  </select>
						
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="EmployeeMaritalStatus" class="col-sm-3 col-form-label">Employee Marital Status</label>
                      <div class="col-sm-9">
						 <select name="selmaritalstatus" id="txtselMaritalStatus" class="form-control" >
						    <option value="" <?php  if($employee_marital_status=="Select Marital Status"){ echo "selected";} ?>>Select Marital Status</option>
							<option value="Married" <?php  if($employee_marital_status=="Married"){ echo "selected";} ?>>Married</option>
							<option value="Unmarried" <?php  if($employee_marital_status=="Unmarried"){ echo "selected";} ?>>Unmarried</option>
						  </select>
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="EmployeeDOB" class="col-sm-3 col-form-label">Employee DOB</label>
                      <div class="col-sm-9">
                        <input type="Date" class="form-control form-control-sm" value="<?php echo $employee_date_of_birth; ?>" id="txtEmployeeDOB" placeholder="Employee DOB" >
                      </div>
                    </div>
					<button type="submit" class="btn btn-success mr-2" id="btncreate" onclick="updatedata()">Update</button>
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
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
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


function updatedata(){
		if(!validateBlank($("#txtEmployeeName").val())){
			setErrorOnBlur("txtEmployeeName");	
		}
		else{
			var valcreated_by = "<?php echo $created_by;?>";
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			var valrequesttype = "<?php echo $requesttype;?>";
			var valrequestid = "<?php echo $requestid;?>";
			
			var valquery = "Update tw_employee_registration set employee_name = '"+$("#txtEmployeeName").val()+"' , employee_gender='"+$("#txtselGender").val()+"', employee_marital_status ='"+$("#txtselMaritalStatus").val()+"', date_of_birth = '"+$('#txtEmployeeDOB').val()+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
				
			disableButton('#btncreate','<i class="ti-timer"></i>Processing');
			$.ajax({
			type:"POST",
			url:"apiCommonQuerySingle.php",
			data:{valquery:valquery},
			success:function(response){
					
					if($.trim(response)=="Success"){
						
							showAlertRedirect("Success","Personal Details Updated Successfully","success","pgEmployeeProfile.php?type=edit&id="+valrequestid);
						
					}
					else if($.trim(response)=="Exist"){
						showAlert("Warning","Value already exist","warning");
						$("#txtValue").focus();
					}else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
					}
				} 
			});  
		}
   }

</script>
</body>
</html>