<?php 
session_start();
	if(!isset($_SESSION["employeeusername"])){
		header("Location:pgEmployeeLogIn.php");
	}
	include_once "function.php";
	include_once "commonFunctions.php";
	$sign=new Signup();
	$commonfunction=new Common();
	
	$employee_photo = "";
	$value = "";
    $employee_id=$_SESSION["employee_id"];
	$company_id=$_SESSION["company_id"];
	
	$settingValueRightsID=$commonfunction->getSettingValue("RightsID");
	//CHECK for Dashboard rights
	$qrySelModulename="Select count(*) as cnt from tw_company_rights_management where company_id='".$company_id."' and rights_id='".$settingValueRightsID."'";
	$moduleCnt = $sign->Select($qrySelModulename);
	if($moduleCnt==0){
		header("Location:pgEmployeeProfile.php");
	}
	 
	$settingValueEmployeeImagePathOther  = $commonfunction->getSettingValue("EmployeeImagePathOther");
	$settingValueEmployeeImage= $commonfunction->getSettingValue("Employee Image");
	$settingValueEmployeeImagePathVerification =$commonfunction->getSettingValue("EmployeeImagePathVerification");
	
	$settingValuePendingStatus =$commonfunction->getSettingValue("Pending Status");
	$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
	$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
	$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
	$settingValueOngoingStatus= $commonfunction->getSettingValue("Ongoing Status");
	$settingValueNewimage= $commonfunction->getSettingValue("Newimage");
	
	
	$qry="select employee_name from tw_employee_registration where id='".$employee_id."'";
	$Name= $sign->SelectF($qry,'employee_name');
	
	
	$qryStatus="select Status from  tw_employee_registration where ID='".$employee_id."'";
	$employeeStatus= $sign->SelectF($qryStatus,'Status');
	$verifyStatus=$commonfunction->getSettingValue("Verified Status");
	$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
	$EmployeeImagePathOther =$commonfunction->getSettingValue("EmployeeImagePathOther");
	
	$qry="select er.employee_name,dm.designation_value from tw_employee_registration er INNER JOIN tw_designation_master dm ON er.employee_designation=dm.id where er.id='".$employee_id."'";
	//$Name= $sign->SelectF($qry,'employee_name');
	$Empdata = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($Empdata);
	$employee_name = $decodedJSON->response[0]->employee_name; 
	$designation_value = $decodedJSON->response[1]->designation_value; 
	
	
	$query="SELECT cd.CompanyName FROM tw_company_details cd INNER JOIN tw_employee_registration er ON er.company_id=cd.ID where cd.ID='".$company_id."'";
	$Companydata = $sign->FunctionJSON($query);
	$decodedJSON1 = json_decode($Companydata);
	$CompanyName = $decodedJSON1->response[0]->CompanyName; 
	
	$query1="SELECT er.employee_photo,ec.value FROM tw_employee_registration er INNER JOIN tw_employee_contact ec ON ec.employee_id=er.id WHERE er.id = '".$employee_id."'";
	$Employeedata = $sign->FunctionJSON($query1);
	$decodedJSON1 = json_decode($Employeedata);
	$employee_photo = $decodedJSON1->response[0]->employee_photo; 
	$value = $decodedJSON1->response[1]->value; 	
	
	$querypendingpocount = "SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as pendingpo FROM tw_temp where status='".$settingValuePendingStatus."' and po_id in (select id from tw_po_info where company_id='".$company_id."')";
	$pendingpo = $sign->SelectF($querypendingpocount,"pendingpo");
	if($pendingpo==""){
		$pendingpo=0.00;
	}
	
	$queryfullfillpocount = "SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as fullfillpocount FROM tw_temp where status='".$settingValueCompletedStatus."' and po_id in (select id from tw_po_info where company_id='".$company_id."')";
	$fullfillpocount = $sign->SelectF($queryfullfillpocount,"fullfillpocount");
	if($fullfillpocount==""){
		$fullfillpocount=0.00;
	}

	$queryverifiedpocount = "SELECT IFNULL (sum(total_quantity), 0) as activepo FROM tw_po_info where company_id='".$company_id."' and status='".$settingValueApprovedStatus."' ";
	$verifiedpocount = $sign->FunctionJSON($queryverifiedpocount);
	$decodedJSON3 = json_decode($verifiedpocount);
	$activepo = $decodedJSON3->response[0]->activepo;
	if($activepo==""){
		$activepo=0.00;
	}
	
	$UNFULLFILLED = $activepo - ($fullfillpocount + $pendingpo);
	if($UNFULLFILLED==""){
		$UNFULLFILLED=0.00;
	}
	
	$queryInprocess="SELECT count(*) as cnt FROM tw_temp tt INNER JOIN tw_po_info pi ON tt.po_id=pi.id WHERE pi.company_id='".$company_id."' and tt.status='".$settingValueAwaitingStatus."'";
	$retqueryInprocess = $sign->Select($queryInprocess);
	
	$po_idQry="SELECT po_id FROM `tw_auditor_po_details` where auditor_id='".$employee_id."'";
	$po_id= $sign->SelectF($po_idQry,'po_id');	
	
	$DropdownDetailsQry="SELECT DISTINCT pi.supplier_id,cd.CompanyName FROM tw_po_info pi INNER JOIN tw_company_details cd ON pi.supplier_id=cd.ID where pi.id='".$po_id."' ";
	$DropdownDetails=$sign->FunctionOption($DropdownDetailsQry,"",'CompanyName',"supplier_id");
	
	
		
	$activeCount="SELECT COUNT(*) as cnt FROM tw_temp tmp WHERE status='".$settingValueOngoingStatus."' AND po_id In (SELECT id FROM tw_po_info where id IN (SELECT po_id FROM tw_auditor_po_details where auditor_id='".$employee_id."'))";
	$active = $sign->Select($activeCount);
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace-Waste | Auditor Dashboard </title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- Plugin css for this page -->
<link rel="stylesheet" href="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="../assets/js/select.dataTables.min.css">
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html navTopHeader-->
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
		
		 <!-- First Row starts--> 
		 <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 com-xs-2 col-2">
					<img style="border-radius: 15px;" src="<?php if($employee_photo==""){echo $settingValueEmployeeImagePathOther.$settingValueEmployeeImage; }else{ echo $settingValueEmployeeImagePathVerification.$value."/".$employee_photo;}?>" class="img-lg  mb-3" />
				</div>
				
                <div class="col-lg-8 col-md-8 col-sm-0 com-xs-0 col-0">
                  <h3 class="font-weight-bold">Welcome <?php echo $Name;  if ($employeeStatus==$verifyStatus) { echo " <img src='".$EmployeeImagePathOther ."".$VerifiedImage."'/>";} ?></h3>
                  <h6 class="font-weight-normal mb-0"><?php echo $designation_value; ?> at <?php echo $CompanyName; ?> <?php if($retqueryInprocess>0){ ?>You have<span class="text-primary"> <a href="pgEprPo.php" class="text-success"><?php echo $retqueryInprocess; ?> documents for approval</a></span><?php } ?></h6>
                </div>
				
              <div class="col-lg-2 col-md-2 col-sm-0 com-xs-0 col-0">
				 <div class="dropdown">
					<select id="txtSupplierName" class="form-control"  onchange="VendorSel()" > 
						<option value="0">All Vendor</option>
						<?php echo $DropdownDetails; ?>
					</select>
                  </div>
				</div>
				
              </div>
            </div>
          </div>
			<!-- First Row ends-->
			<!-- Second Row starts-->
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
				  
					<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
					  <div class="card card-tale">
						<div class="card-body">
						  <p class="mb-4">Documents pending for approval<?php if($active!=0){ ?> <img class="New-img" src="<?php echo $settingValueEmployeeImagePathOther.$settingValueNewimage ?>"  /></span>	<?php } ?></p>
						  <p class="fs-30 mb-2" id="PO_Active"><?php echo $active;?></p>
						  <p></p>
						</div>
					  </div>
					  </div>

				 
				  
				  </div>
              </div>
		
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
<script src="../assets/vendors/chart.js/Chart.min.js"></script>
<script src="../assets/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="../assets/js/dataTables.select.min.js"></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="../assets/js/dashboard.js"></script>
<script src="../assets/js/Chart.roundedBarCharts.js"></script>
<script src="../assets/js/chart.js"></script>
<!-- End custom js for this page-->
<script>
var varUNFULLFILLED="<?php echo $UNFULLFILLED;?>";
var varfullfillpocount="<?php echo $fullfillpocount;?>";
var varpending="<?php echo $pendingpo;?>";
var po_id="<?php echo $po_id;?>";

function VendorSel(){
	var val = $('#txtSupplierName').val();
		$.ajax({
			type:"POST",
			url:"apiGetAuditorPoDetails.php",
			data:{val:val},
			dataType: 'JSON',
			success:function(response){
			console.log(response);
			    $("#PO_Active").html(response[0]);
			    $("#PO_Accepted").html(response[1]);
			    $("#PO_Unfullfilled").html(response[2]);
			    $("#PO_Pending").html(response[3]);
				
						  	}		
		});
	
}


  </script>
</body>

</html>

