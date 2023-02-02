<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$empid = $_REQUEST["id"];
$company_id = $_SESSION["company_id"];
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePcontact= $commonfunction->getSettingValue("Primary Contact");
$settingValueCompanyPanel = $commonfunction->getSettingValue("CompanyPanel");
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by = $company_id;
$qry="SELECT er.employee_department,er.employee_designation,er.employee_role, td.department_name, tdgn.designation_value, tr.role_name 
FROM ( tw_employee_registration er 
INNER JOIN tw_department_master td ON er.employee_department = td.id 
INNER JOIN tw_designation_master tdgn ON er.employee_designation = tdgn.id 
INNER JOIN tw_role_master tr ON er.employee_role = tr.id ) WHERE er.id='".$empid."'"; 

$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);

$employee_department = $decodedJSON->response[0]->employee_department;
$employee_designation = $decodedJSON->response[1]->employee_designation;
$employee_role = $decodedJSON->response[2]->employee_role;
$department_name = $decodedJSON->response[3]->department_name;
$designation_value = $decodedJSON->response[4]->designation_value;
$role_name = $decodedJSON->response[5]->role_name;

$qry1 = "select id,role_name from tw_role_master where panel='".$settingValueCompanyPanel."' and visibility='true' Order by priority,role_name";
$retVal1 = $sign->FunctionOption($qry1,$employee_role,'role_name','id');

$qry2 = "select id,department_name from tw_department_master where visibility='true' Order by priority,department_name";
$retVal2 = $sign->FunctionOption($qry2,$employee_department,'department_name','id');
 
$qry3 = "select id,designation_value from tw_designation_master where visibility='true' Order by priority,designation_value";
$retVal3 = $sign->FunctionOption($qry3,$employee_designation,'designation_value','id');

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
							<button type="button" id="btnAddrecord" class="btn btn-success" onclick="addrecord();">Update Record</button>
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
function addrecord(){
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
		var empid = "<?php echo $empid;?>";
		var valcreated_by = "<?php echo $created_by;?>";
		var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";
		var valcompany_id = "<?php echo $company_id;?>";
		
		var valquery = "Update tw_employee_registration set employee_department='"+$("#txtEmployeedept").val()+"',employee_designation='"+$("#txtEmployeeDesignation").val()+"',employee_role='"+$("#txtEmployeeRole").val()+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id="+empid;
		 $.ajax({
			type:"POST",
			url:"apiCommonQuerySingle.php",
			data:{valquery:valquery},	
			success:function(response){
				enableButton('#btnAddrecord','Update Record');
				if($.trim(response)=="Success"){
					$('#btnAddrecord').html('Update Record');
 					showAlertRedirect("Success","Data Updated Successfully","success","pgCompanyProfile.php?id="+valcompany_id);
					$("#txtEmployeedept").val("");
					$("#txtEmployeeDesignation").val("");
					$("#txtEmployeeRole").val("");	
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
</script>	
</body>

</html>