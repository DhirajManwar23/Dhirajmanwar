<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$company_id = $_SESSION["company_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

$qry1="select count(*) as cnt from tw_temp_po_info where buyer_id='".$company_id."' and status='".$settingValuePendingStatus."' order by id desc";
$qryCntInprocess = $sign->Select($qry1);
$qry2="select count(*) as cnt from tw_temp_po_info where buyer_id='".$company_id."' and status='".$settingValueApprovedStatus."' order by id desc";
$qryCntApproved = $sign->Select($qry2);
$qry3="select count(*) as cnt from tw_temp_po_info where buyer_id='".$company_id."' and status='".$settingValueRejectedStatus."' order by id desc";
$qryCntRejected = $sign->Select($qry3);
$qry4="select count(*) as cnt from tw_temp_po_info where buyer_id='".$company_id."' and status='".$settingValueCompletedStatus."' order by id desc";
$qryCntCompleted = $sign->Select($qry4);
$qry5="select count(*) as cnt from tw_temp_po_info where buyer_id='".$company_id."' order by id desc";
$qryCntAll = $sign->Select($qry5);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | PO</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<!--<button type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="location.href='pgPOForm.php?type=add&id='"><i class="icon-plus"></i> Create New Record</button>-->
					<div class="mt-4 py-2 border-top border-bottom">
                        <ul class="nav profile-navbar">
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idInprocess" onclick="showData('<?php echo $settingValuePendingStatus;?>');">
                              <i class="ti-timer"></i>
                              InProcess (<?php echo $qryCntInprocess; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idApproved" onclick="showData('<?php echo $settingValueApprovedStatus;?>');">
                              <i class="ti-check-box"></i>
                              Approved (<?php echo $qryCntApproved; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idRejected" onclick="showData('<?php echo $settingValueRejectedStatus;?>');">
                              <i class="ti-na"></i>
                              Rejected (<?php echo $qryCntRejected; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idCompleted" onclick="showData('<?php echo $settingValueCompletedStatus;?>');">
                              <i class="ti-target"></i>
                              Completed (<?php echo $qryCntCompleted; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idAll" onclick="showData('');">
                              <i class="ti-notepad"></i>
                              All (<?php echo $qryCntAll; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="idCreate" href="pgPOForm.php?type=add&id=">
                              <i class="ti-plus"></i>
                              Create New Record
                            </a>
                          </li>
                        </ul>
                      </div>
					  
				</div>
			</div><br>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
				<table id="tableData" class="table">
				</table>
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
	var valsettingValuePendingStatus='<?php echo $settingValuePendingStatus; ?>';
	var valsettingValueApprovedStatus='<?php echo $settingValueApprovedStatus; ?>';
	var valsettingValueRejectedStatus='<?php echo $settingValueRejectedStatus;?>';
	var valsettingValueCompletedStatus='<?php echo $settingValueCompletedStatus;?>';
$(document).ready(function(){
	showData('<?php echo $settingValueApprovedStatus;?>');
	
	$("#idInprocess").removeClass("active");
	$("#idApproved").addClass("active");
	$("#idRejected").removeClass("active");
	$("#idCompleted").removeClass("active");
	$("#idAll").removeClass("active");
	$("#idCreate").removeClass("active");
});

function showData(id){
	
	if(id==valsettingValuePendingStatus){
		$("#idInprocess").addClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
		$("#idCreate").removeClass("active");
		
	}else if(id==valsettingValueApprovedStatus){
		$("#idInprocess").removeClass("active");
		$("#idApproved").addClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
		$("#idCreate").removeClass("active");
	}
	else if(id==valsettingValueRejectedStatus){
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").addClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
		$("#idCreate").removeClass("active");
	}
	else if(id==valsettingValueCompletedStatus){
		
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").addClass("active");
		$("#idAll").removeClass("active");
		$("#idCreate").removeClass("active");
	}
	else{
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").addClass("active");
		$("#idCreate").removeClass("active");
	}
	
	$.ajax({
		type:"POST",
		url:"apiGetViewPO.php",
		data:{statustype:id},
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
})
		}
	});
} 
function editRecord(id){
	window.location.href = "pgTempPOFormEdit.php?type=edit&id="+id;
}
function ViewRecord(id){
	window.location.href = ("pgPODocument.php?po_id="+id);
}
function ViewInprocess(id){
	window.location.href = "pgMaterialInward.php?type=In%20Process&po_id="+id;
}
function ViewApproved(id){
	window.location.href = "pgMaterialInward.php?type=Approved&po_id="+id;
}
function ViewRejected(id){
	window.location.href = "pgMaterialInward.php?type=Rejected&po_id="+id;
}
</script>
</body>

</html>