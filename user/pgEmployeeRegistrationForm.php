<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$_SESSION["requesttype"] = $requesttype;
$id = $_REQUEST["id"];
$_SESSION["requestid"] = $id;
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePcontact= $commonfunction->getSettingValue("Primary Contact");
$settingValueCompanyPanel = $commonfunction->getSettingValue("CompanyPanel");

$employee_name = ""; 
$employee_photo = "";
$employee_department = "";
$employee_designation = "";
$employee_role = "";
$employee_salary = "";
$employee_hire_date = "";
$employee_type = "";
$employee_status = "";
$is_working = "";
$forced_password_change = "";
$password = "";
$primary_contact = "";
$primary_email = "";
$contact_field = "";
$created_by = "";
$empgender = "";
$empdob = "";
$maritalstatus = "";
$settingValueEmployeeImagePathVerification = "";

$qry1 = "select id,role_name from tw_role_master where panel='".$settingValueCompanyPanel."' and visibility='true' Order by priority,role_name";
$retVal1 = $sign->FunctionOption($qry1,$employee_role,'role_name','id');

$qry2 = "select id,department_name from tw_department_master where visibility='true' Order by priority,department_name";
$retVal2 = $sign->FunctionOption($qry2,$employee_department,'department_name','id');
 
$qry3 = "select id,designation_value from tw_designation_master where visibility='true' Order by priority,designation_value";
$retVal3 = $sign->FunctionOption($qry3,$employee_designation,'designation_value','id');
 
$qry4 = "select id,employee_type_value from  tw_employee_type_master where visibility='true' Order by priority,employee_type_value";
$retVal4 = $sign->FunctionOption($qry4,$employee_type,'employee_type_value','id');
 
$qry5 = "select id,verification_status from tw_verification_status_master where visibility='true' Order by priority,verification_status";
$retVal5 = $sign->FunctionOption($qry5,$employee_status,'verification_status','id');

if($requesttype=="edit"){
$settingValueEmployeeImagePathVerification= $commonfunction->getSettingValue("EmployeeImagePathVerification");
$created_by = $id;
$qry="SELECT er.employee_name,er.employee_photo,er.employee_department,er.employee_designation,er.employee_role,er.employee_salary,er.employee_hire_date,er.employee_type,er.employee_status,er.is_working,er.empgender,er.empdob,er.maritalstatus, el.forced_password_change, td.department_name, tdgn.designation_value, tr.role_name, trs.verification_status 
FROM ((tw_employee_registration er INNER JOIN tw_employee_login el ON er.id = el.employee_id) 
INNER JOIN tw_department_master td ON er.employee_department = td.id 
INNER JOIN tw_designation_master tdgn ON er.employee_designation = tdgn.id 
INNER JOIN tw_role_master tr ON er.employee_role = tr.id 
INNER JOIN tw_verification_status_master trs ON er.employee_status = trs.id) WHERE er.id='".$id."'"; 

$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);

$employee_name = $decodedJSON->response[0]->employee_name; 
$employee_photo = $decodedJSON->response[1]->employee_photo;
$employee_department = $decodedJSON->response[2]->employee_department;
$employee_designation = $decodedJSON->response[3]->employee_designation;
$employee_role = $decodedJSON->response[4]->employee_role;
$employee_salary = $decodedJSON->response[5]->employee_salary;
$employee_hire_date = $decodedJSON->response[6]->employee_hire_date;
$employee_type = $decodedJSON->response[7]->employee_type;
$employee_status = $decodedJSON->response[8]->employee_status;
$is_working = $decodedJSON->response[9]->is_working;
$empgender = $decodedJSON->response[10]->empgender;
$empdob = $decodedJSON->response[11]->empdob;
$maritalstatus = $decodedJSON->response[12]->maritalstatus;
$forced_password_change = $decodedJSON->response[13]->forced_password_change;

$query="select value from tw_employee_contact WHERE employee_id='".$created_by."' and contact_field='".$settingValuePemail."'"; 
$retVal9 = $sign->FunctionJSON($query);
$decodedJSON = json_decode($retVal9);
$primary_email = $decodedJSON->response[0]->value;

$query1="select value from tw_employee_contact WHERE employee_id='".$created_by."' and contact_field='".$settingValuePcontact."'"; 
$retVal8 = $sign->FunctionJSON($query1);
$decodedJSON = json_decode($retVal8);
$primary_contact = $decodedJSON->response[0]->value;

$qry1 = "select id,role_name from tw_role_master where panel='".$settingValueCompanyPanel."' and visibility='true' Order by priority,role_name";
$retVal1 = $sign->FunctionOption($qry1,$employee_role,'role_name','id');

$qry2 = "select id,department_name from tw_department_master where visibility='true' Order by priority,department_name";
$retVal2 = $sign->FunctionOption($qry2,$employee_department,'department_name','id');
 
$qry3 = "select id,designation_value from tw_designation_master where visibility='true' Order by priority,designation_value";
$retVal3 = $sign->FunctionOption($qry3,$employee_designation,'designation_value','id');
 
$qry4 = "select id,user_type from tw_user_type_master where visibility='true' Order by priority,user_type";
$retVal4 = $sign->FunctionOption($qry4,$employee_type,'user_type','id');
 
$qry5 = "select id,verification_status from tw_verification_status_master where visibility='true' Order by priority,verification_status";
$retVal5 = $sign->FunctionOption($qry5,$employee_status,'verification_status','id');

}
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Employee Registration</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<!-- endinject -->
<!-- tw-css:start -->
<link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
                  <h4 class="card-title">Employee Registration</h4>
					<br>
					<div class="forms-sample">
						<label for="txtEmployeename">Employee Photo (Max image size 5MB) (Optional)</label>
						<div class="col-lg-12">
						  <input type="file" class="col-lg-12 col-form-label" name="Document_Proof" accept=".png, .jpg, .jpeg" placeholder="Select file" id="Document_Proof" onchange="showname();"/>						   
						</div><br>
						<div class="form-group">
						  <label for="txtEmployeename">Employee Name <code>*</code></label>
						  <input type="text" class="form-control" value="<?php echo $employee_name; ?>" id="txtEmployeename" maxlength="50" placeholder="Name" />
						</div>
						
						<div class="form-group">
						  <label for="txtselGender">Employee Gender</label>
						  <select name="selcontact" id="txtselGender" class="form-control" >
						    <option value="" <?php  if($empgender=="Select Gender"){ echo "selected";} ?>>Select Gender</option>
							<option value="Male" <?php  if($empgender=="Male"){ echo "selected";} ?>>Male</option>
							<option value="Female" <?php  if($empgender=="Female"){ echo "selected";} ?>>Female</option>
						  </select>
						</div>
						<div class="form-group">
						  <label for="txtEmployeeDOB">Employee Date Of Birth</label>
						  <input type="Date" class="form-control" max='<?php echo $empdob; ?>' id="txtEmployeeDOB"  placeholder="Date Of Birth" />
						</div>
						<div class="form-group">
						  <label for="txtselMaritalStatus">Employee Marital Status</label>
						   <select name="selmaritalstatus" id="txtselMaritalStatus" class="form-control" >
						    <option value="" <?php  if($maritalstatus=="Select Marital Status"){ echo "selected";} ?>>Select Marital Status</option>
							<option value="Married" <?php  if($maritalstatus=="Married"){ echo "selected";} ?>>Married</option>
							<option value="Unmarried" <?php  if($maritalstatus=="Unmarried"){ echo "selected";} ?>>Unmarried</option>
						  </select>
						</div>
						<div class="form-group" >
						<label>Employee Department<code>*</code></label>
						<select name="department" id="txtEmployeedept" class="form-control" >
						 <?php echo $retVal2;?>
						</select>
						</div>
						<div class="form-group" >
						<label>Employee Designation<code>*</code></label>
						<select name="designation" id="txtEmployeeDesignation" class="form-control" >
						 <?php echo $retVal3;?>
						</select>
						</div>
						<div class="form-group" >
						<label>Employee Role<code>*</code></label>
						<select name="role" id="txtEmployeeRole" class="form-control" >
						 <?php echo $retVal1;?>
						</select>
						</div>
						<div class="form-group">
						<label for="txtPriemail">Primary Email <code>*</code></label>
						<input type="text" class="form-control" value="<?php echo $primary_email; ?>" id="txtPriemail" maxlength="50" placeholder="Primary email" />
						</div>
						<div class="form-group">
						<label for="txtPricontact">Primary Contact <code>*</code></label>
						<input type="text" class="form-control" value="<?php echo $primary_contact; ?>" id="txtPricontact" maxlength="20" placeholder="Primary contact" />
						</div>
						<div class="form-group">
						<label for="txtEmpSalary">Employee Salary (Optional)</label>
						<input type="int" class="form-control" value="<?php echo $employee_salary; ?>" id="txtEmpSalary" maxlength="20" placeholder="Salary" />
						</div>
						<div class="form-group">
						<label for="txtHireDate">Employee Hire Date (Optional)</label>
						<input type="Date" class="form-control" max='<?php echo $date; ?>' id="HireDate"  placeholder="Hire Date" />
						</div>
						<div class="form-group" >
						<label>Employee Type<code>*</code></label>
						<select name="type" id="txtEmpType" class="form-control" >
						 <?php echo $retVal4;?>
						</select>
						</div>

						<div class="form-group" >
						<label>Employee Status<code>*</code></label>
						<select name="Status" id="txtEmpStatus" class="form-control" >
						<?php echo $retVal5;?>
						</select>
						</div>
						<div class="form-group">
						  <input type="checkbox" id="txtworking" <?php if ($is_working=="true") { echo "checked"; } ?>>
						   <label for="working">Is Working with Company (Optional)</label>
						</div>
						<div class="form-group">
						  <input type="checkbox" id="txtpasswordchg" <?php if ($forced_password_change=="true") { echo "checked"; } ?>>
						  <label for="passwordchg">Force Password Change (Optional)</label>
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
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetAlert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>
var hdnIDimg="";
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
$("#txtPriemail").blur(function()
{
	removeError(txtPriemail);
	if ($("#txtPriemail").val()!="")
	{
		if(!validateEmail($("#txtPriemail").val())){
			setError(txtPriemail);
		}
		else
		{
			removeError(txtPriemail);
		}
	}
});
$("#txtPricontact").blur(function()
{
	removeError(txtPricontact);
	if ($("#txtPricontact").val()!="")
	{
		if(!isMobile($("#txtPricontact").val())){
			setError(txtPricontact);
		}
		else
		{
			removeError(txtPricontact);
		}
	}
});

function showname() {
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;
	  var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['pdf','png','jpg','jpeg']) == -1) 
	  {
		$('#Document_Proof').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
	  var f = document.getElementById("Document_Proof").files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 5000000)
	  {
			showAlert("Warning","Image size is very big","warning");
	  }
	  else
	  {
			form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);
		   
		   $.ajax({
			url:"uploademployeePhoto.php",
			method:"POST",
			data: form_data2,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend:function(){
			},   
			success:function(data)
			
			{
				console.log(data);
				hdnIDimg=data;
			}
		   });
	  }
		  
		 
};

function addrecord(){
	
	if(!validateBlank($("#txtEmployeename").val())){
		setErrorOnBlur("txtEmployeename");	
	}
	else if(!validateBlank($("#txtEmployeedept").val())){
		setErrorOnBlur("txtEmployeedept");
	}
	else if(!validateBlank($("#txtEmployeeDesignation").val())){
		setErrorOnBlur("txtEmployeeDesignation");
	}
	else if(!validateBlank($("#txtEmployeeRole").val())){
		setErrorOnBlur("txtEmployeeRole");
	} 
	else if(!validateBlank($("#txtPriemail").val())){
		setErrorOnBlur("txtPriemail");
	}
	else if(!validateEmail($("#txtPriemail").val())){
	  setError(txtPriemail);
	  $("#txtPriemail").focus();
	}
	else if(!validateBlank($("#txtPricontact").val())){
		setErrorOnBlur("txtPricontact");
	}
	else if(!isMobile($("#txtPricontact").val())){
	  setError(txtPricontact);
	  $("#txtPricontact").focus();
	}
	else if(!validateBlank($("#txtEmpType").val())){
		setErrorOnBlur("txtEmpType");
	}
	else if(!validateBlank($("#txtEmpStatus").val())){
		setErrorOnBlur("txtEmpStatus");
	}
	else{
	
		var valrequesttype = "<?php echo $requesttype;?>";
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');

		$.ajax({
			type:"POST",
			url:"apiAddEmployee.php",
			data:{employee_photo:hdnIDimg,
				employee_name:$("#txtEmployeename").val(),
				employee_department:$("#txtEmployeedept").val(),
				employee_designation:$("#txtEmployeeDesignation").val(),
				employee_role:$("#txtEmployeeRole").val(),
				primary_email:$("#txtPriemail").val(),
				primary_contact:$("#txtPricontact").val(),
				employee_salary:$("#txtEmpSalary").val(),
				employee_hire_date:$("#HireDate").val(),
				employee_type:$("#txtEmpType").val(),
				employee_status:$("#txtEmpStatus").val(),
				forced_password_change:$("#txtpasswordchg").prop('checked'),
				is_working:$("#txtworking").prop('checked'),
				employee_gender:$("#txtselGender").val(),
				employee_dob:$("#txtEmployeeDOB").val(),
				employee_marital_status:$("#txtselMaritalStatus").val()},
				
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
						$('#btnAddrecord').html('Add Record');
						showAlertRedirect("Success","Data Added Successfully","success","pgEmployeeRegistration.php");
						}
					else{
						$('#btnAddrecord').html('Update Record');
 						showAlertRedirect("Success","Data Updated Successfully","success","pgEmployeeRegistration.php");
					}
					$(hdnIDimg).val("");
					$("#txtEmployeename").val("");
					$("#txtEmployeedept").val("");
					$("#txtEmployeeDesignation").val("");
					$("#txtEmployeeRole").val("");
					$("#txtPriemail").val("");
					$("#txtPricontact").val("");
					$("#txtEmpStatus").val("");
				}
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Record already exist","warning");
					
				}
				else{
					showAlert("Error","Something Went Wrong","error");
					
				}
			}
		});
	 
	}
}
	
</script>	
</body>
</html>