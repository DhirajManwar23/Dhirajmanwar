<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["company_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueEmployeeImagePathOther= $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");
$po_id=$_REQUEST["po_id"];

$qry7 = "select id,state_name from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$po_id."')";
$statename = $sign->FunctionOption($qry7,"",'state_name','id');

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Generate Document</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/css/custom/style.css" />
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/css/style.css">
  <!-- endinject -->
  <!-- inject:css -->
  <script src="../assets/js/custom/twCommonValidation.js"></script>
  <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/css/custom/style.css">
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
                  <h4 class="card-title">Generate Document</h4>
						<div class="form-group row">
							<div class="col-ld-6 col-md-6 col-sm-6 col-xs-6 col-6">
								<label class="col-sm-12">Select State</label>
								<select id="txtState" class="form-control form-control-sm float-right" onchange="myFunction()">
								<option value="">Select State</option>
									<?php echo $statename; ?>
								</select>
								<br>								
							</div>
						</div>
						
						 <div class="table-responsive">
							<div class="table-responsive">
								<table id="tableData" class="table">
								  
								</table>
							 </div>
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
   <script src="../assets/js/custom/sweetalert2.min.js"></script>
	<script src="../assets/js/custom/sweetAlert.js"></script>
  <script src="../assets/css/jquery/jquery.min.js"></script>
  <script type='text/javascript'>
var valpo_id="<?php echo $po_id; ?>";
var valstateid="";
$(document).ready(function(){
	//showData();
});
function saveApproval(id){
	valstateid = $('#txtState').val();
	showConfirmAlert('Confirm action!', 'Are you sure to submit this record?','question', function (confirmed){
		if(confirmed==true){
		 $.ajax({
				type:"POST",
				url:"apiEPRSSubmitApproval.php",
				data:{valpo_id:valpo_id,id:id,valstateid:valstateid},	
				success:function(response){
					console.log(response);
					//enableButton('#btnAddrecord','Update Record');
					if($.trim(response)=="Success"){
						$('#btnSubmit').html('Submit');
						showAlert("Success","Data Submitted Successfully","success");
						showData();
					}
					else{
						showAlert("Error","Something Went Wrong","error");
					} 
				}
			}); 
		}
	}); 
}
function showData(){
	$.ajax({
		type:"POST",
		url:"apiGetEPRSDocumentMonths.php",
		data:{po_id:valpo_id,state_id:valstateid},
		success:function(response){
			console.log(response);
			$("#tableData").html(response);

			$('#tableData').DataTable({
				"responsive":true,
				"destroy":true,
				"bPaginate":true,
				"bLengthChange":true,
				"bFilter":true,
				"bSort":true,
				"bInfo":true,
				"retrieve": true,
				"bAutoWidth":false,
				"scrollXInner":true
			});
		}
	});
}
function viewReason(id){
	
		var valquery="Select reason from tw_rejected_reason_master where id="+id;
		 $.ajax({
			type:"POST",
			url:"apiGetViewRejectedReason.php",
			data:{valquery:valquery},
			success:function(response){
				console.log(response);
				showAlert("",response,"");
			}
	});   
}
function myFunction(){
	
	valstateid = $('#txtState').val();
	showData();
}

function AuditorCertificate(id){
	window.location.href = "pgAuditorCertificate.php?po_id="+id+"&req=";
}
</script>
</body>

</html>