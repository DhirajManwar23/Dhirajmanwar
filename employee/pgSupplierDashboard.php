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
	$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
	$settingValueNewimage= $commonfunction->getSettingValue("Newimage");
	$settingValueRejectedByCompany= $commonfunction->getSettingValue("RejectedByCompany");
	$settingValueRejectedByAuditor= $commonfunction->getSettingValue("RejectedByAuditor");
	
	$qry="select employee_name from tw_employee_registration where id='".$employee_id."'";
	$Name= $sign->SelectF($qry,'employee_name');
	
	$qryStatus="select Status from  tw_employee_registration where ID='".$employee_id."'";
	$employeeStatus= $sign->SelectF($qryStatus,'Status');
	$verifyStatus=$commonfunction->getSettingValue("Verified Status");
	$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
	$EmployeeImagePathOther =$commonfunction->getSettingValue("EmployeeImagePathOther");
	
	$qry="select er.employee_name,dm.designation_value from tw_employee_registration er INNER JOIN tw_designation_master dm ON er.employee_designation=dm.id where er.id='".$employee_id."'";
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
	
	$queryacceptedpocount = "SELECT IFNULL (sum(replace(final_total_amout, ',', '')), 0) as acceptedpo FROM tw_material_outward where status='".$settingValueApprovedStatus."' and po_id in (select id from tw_temp_po_info where supplier_id='".$company_id."')";
	$acceptedpo = $sign->SelectF($queryacceptedpocount,"acceptedpo");
	if($acceptedpo==""){
		$acceptedpo=0.00;
	}
	
	$queryawaitingpocount = "SELECT IFNULL (sum(replace(final_total_amout, ',', '')), 0) as awaitingpocount FROM tw_material_outward where status='".$settingValuePendingStatus."' and po_id in (select id from tw_temp_po_info where supplier_id='".$company_id."')";
	$awaitingpocount = $sign->SelectF($queryawaitingpocount,"awaitingpocount");
	if($awaitingpocount==""){
		$awaitingpocount=0.00;
	}

	$queryverifiedpocount = "SELECT IFNULL (sum(total_quantity), 0) as activepo FROM tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."' ";
	$activepo = $sign->SelectF($queryverifiedpocount,"activepo");
	if($activepo==""){
		$activepo=0.00;
	}
	
	$UNFULLFILLED = $activepo - ($acceptedpo + $awaitingpocount);
	if($UNFULLFILLED==""){
		$UNFULLFILLED=0.00;
	}
	
/* 	$queryInprocess="SELECT count(*) as cnt FROM tw_material_outward tt INNER JOIN tw_temp_po_info pi ON tt.po_id=pi.id WHERE pi.supplier_id='".$company_id."' and tt.status='".$settingValuePendingStatus."'";
	$retqueryInprocess = $sign->Select($queryInprocess); */
	
	$DropdownDetailsQry="SELECT DISTINCT pi.buyer_id,cd.CompanyName FROM tw_temp_po_info pi INNER JOIN tw_company_details cd ON pi.buyer_id=cd.ID where pi.supplier_id='".$company_id."' ";
	$DropdownDetails=$sign->FunctionOption($DropdownDetailsQry,"",'CompanyName',"buyer_id");
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace-Waste | Employee Dashboard </title>
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
				<div class="col-lg-2 col-md-2 col-sm-2 com-xs-2 col-2">
					<img style="border-radius: 15px;" src="<?php if($employee_photo==""){echo $settingValueEmployeeImagePathOther.$settingValueEmployeeImage; }else{ echo $settingValueEmployeeImagePathVerification.$value."/".$employee_photo;}?>" class="img-lg  mb-3" />
				</div>
                <div class="col-lg-7 col-md-7 col-sm-7 com-xs-7 col-7">
					<h3 class="font-weight-bold">Welcome <?php echo $Name;  if ($employeeStatus==$verifyStatus) { echo " <img src='".$EmployeeImagePathOther ."".$VerifiedImage."'/>";} ?></h3>
					<h6 class="font-weight-normal mb-0"><?php echo $designation_value; ?><br> <?php echo $CompanyName; ?></h6>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-3 com-xs-3 col-3">
				 <div class="dropdown">
					<select id="txtSupplierName" class="form-control"  onchange="VendorSel()" >
						<option value="0">All Vendor</option>
						<?php echo $DropdownDetails; ?>
					</select>
                  </div>
				</div>
          </div>
			<!-- First Row ends-->
			<!-- Second Row starts-->
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
				  <div class="card card-tale">
					<div class="card-body">
					  <p class="mb-4">Purchase Order<br>Issued</p>
					  <p class="fs-25 mb-2"> <span id="PO_Issued"><?php echo number_format($activepo); ?></span><span class="tw-small-word">kg<span></p>
					  <p></p>
					</div>
				  </div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
				  <div class="card card-dark-blue">
					<div class="card-body">
					  <p class="mb-4">Purchase Order<br>Accepted</p>
					  <p class="fs-25 mb-2"> <span id="PO_Accepted"><?php echo number_format($acceptedpo); ?></span><span class="tw-small-word">kg<span></p>
					 
					</div>
				  </div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
				  <div class="card card-light-danger">
					<div class="card-body">
					  <p class="mb-4">Purchase Order<br>Awaiting</p>
					  <p class="fs-25 mb-2"><span id="PO_Pending"><?php echo number_format($awaitingpocount); ?></span><span class="tw-small-word">kg<span></p>
					  
					</div>
				  </div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-6 grid-margin stretch-card">
				  <div class="card card-light-blue">
					<div class="card-body">
					  <p class="mb-4">Purchase Order<br>Unfullfilled</p>
					  <p class="fs-25 mb-2"><span id="PO_Unfullfilled"><?php echo number_format($UNFULLFILLED);?></span><span class="tw-small-word">kg<span></p>
					 
					</div>
				  </div>
				</div>
				
            </div>
			<!--Second Row Ends -->
			<!--<div class="row"  id="divCategory">
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
				  <div class="card">
					<div class="card-body">
					 <div class="d-flex justify-content-between">
					  <p class="card-title">Statewise Tabulated Details</p>
					  </div>
					 <div id="epr-legend" class="chartjs-legend mt-4 mb-2"></div>
					  <table id="tableData"  class="printtbl" width="100%">
					 
						</table>  
					
				  
				  </div>
				</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
				  <div class="card">
					<div class="card-body">
					 <div class="d-flex justify-content-between">
					  <p class="card-title">Categorywise Tabulated Details</p>
					  </div>
					 <div id="epr-legend" class="chartjs-legend mt-4 mb-2"></div>
					  <table id="tableDatacategory"  class="printtbl" width="100%">
					 
						</table>  
					
				  
				  </div>
				</div>
				</div>
			</div>-->
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
				  <div class="card">
					<div class="card-body">
					 <div class="d-flex justify-content-between">
					  <p class="card-title">Monthly Fulfillment</p>
					 </div>
					  <div id="epr-legend" class="chartjs-legend mt-4 mb-2"></div>
					  <canvas id="epr-chart"></canvas>
					</div>
				  </div>
				</div>
			</div>
		  <!--<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <div class="d-flex justify-content-between">
                  <p class="card-title">Statewise EPR Fulfillment</p>
                 </div>
                  <div id="epr-legend" class="chartjs-legend mt-4 mb-2"></div>
                  <canvas id="eprstate-chart"></canvas>
                </div>
              </div>
            </div>
        </div> 
		 <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <div class="d-flex justify-content-between">
                  <p class="card-title">Material Type wise EPR Fulfillment</p>
                 </div>
				 <div id="epr-legend" class="chartjs-legend mt-4 mb-2"></div>
                  <canvas id="eprmaterialtype-chart"></canvas>
                </div>
              </div>
            </div>
         
        </div>-->
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php
			include_once("footer.php");
		?>
        <!-- partial -->
     
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
const d = new Date();
let year = d.getFullYear();
var yearadd = year+1;
$(document).ready(function(){
	VendorSel();
	tablulatedDetails();
	getMonthlyCollectionData();
	geteprstateData();
	geteprMaterialTypeData();
	tablulatedCategoryDetails();
});


function VendorSel(){
	var val = $('#txtSupplierName').val();
		$.ajax({
			type:"POST",
			url:"apiGetSupplierPoDetails.php",
			data:{val:val},
			dataType: 'JSON',
			success:function(response){
			console.log(response);
			    $("#PO_Issued").html(response[0]);
			    $("#PO_Accepted").html(response[1]);
			    $("#PO_Unfullfilled").html(response[2]);
			    $("#PO_Pending").html(response[3]);
				getMonthlyCollectionData();
				geteprstateData();
				geteprMaterialTypeData();
				tablulatedDetails();
				tablulatedCategoryDetails();
			}		
		});
}
function tablulatedDetails(){
	var val = $('#txtSupplierName').val();
	$.ajax({
		type:"POST",
		url:"apiGetEprTabulatedDetails.php",
		data:{val:val},
		dataType: 'JSON',
		success:function(response){
		console.log(response);
		 $("#tableData").html(response);
		}		
	});
}
function tablulatedCategoryDetails(){
	var val = $('#txtSupplierName').val();
		$.ajax({
			type:"POST",
			url:"apiGetEprCategoryTabulatedDetails.php",
			data:{val:val},
			dataType: 'JSON',
			success:function(response){
			console.log(response);
			 $("#tableDatacategory").html(response);
		    }		
		});
}
var myBarChartYearly = "";
var myBarChartState = "";
var myBarChartMaterial = "";

function getMonthlyCollectionData(){
	var val = $('#txtSupplierName').val();
	$.ajax({
			type:"POST",
			url:"apigetMonthlySupplierReport.php",
			data:{val:val},
			success:function(response){
				console.log(response);
				var json = JSON.parse(response);
                var arrdata = [];
				json.forEach((item) => {
					arrdata.push(item.sum);
				});  
				var ctx = document.getElementById("epr-chart").getContext("2d");
				const datasetValue = []
				for (i = 0; i < arrdata.length; i++)
				{
					datasetValue[i] = {
						label: "Monthly Fulfillment",
						backgroundColor: "#57c7d4",
						data: arrdata[i]
					}
				}
				if(myBarChartYearly!=""){
					myBarChartYearly.destroy();
				}
				var xValues = ["April "+year,"May "+year,"June "+year,"July "+year,"Aug "+year,"Sep "+year,"Oct "+year,"Nov "+year,"Dec "+year,"Jan "+yearadd,"Feb "+yearadd,"Mar "+yearadd];
				myBarChartYearly = new Chart(ctx, {
				  type: 'bar',
				  data: {labels: xValues, datasets: datasetValue},
				  options: {
					barValueSpacing: 20,
					scales: {
					  yAxes: [{
						ticks: {
						  min: 0,
						}
					  }]
					}
				  }
				});
			}
		});
} 
function geteprstateData(){
	var val = $('#txtSupplierName').val();
	$.ajax({
			type:"POST",
			url:"apigetPoductEPRFulfillmentReport.php",
			data:{val:val},
			success:function(response){
				var json = JSON.parse(response);
				var arrlabel = [];
                var arrbackgroundColor = [];
                var arrdata = [];
				json.forEach((item) => {
					arrlabel.push(item.state);
					arrdata.push(item.month);
					arrbackgroundColor.push(item.arraycolor);
				});  
				var ctx = document.getElementById("eprstate-chart").getContext("2d");
				const datasetValue = []
				
				for (i = 0; i < arrlabel.length; i++)
				{
					datasetValue[i] = {
						label: arrlabel[i],
						backgroundColor: arrbackgroundColor[i],
						data: arrdata[i]
					}
				}
				if(myBarChartState!=""){
					myBarChartState.destroy();
				}
				var xValues = ["April "+year,"May "+year,"June "+year,"July "+year,"Aug "+year,"Sep "+year,"Oct "+year,"Nov "+year,"Dec "+year,"Jan "+yearadd,"Feb "+yearadd,"Mar "+yearadd];

				myBarChartState = new Chart(ctx, {
				  type: 'bar',
				  data: {labels: xValues, datasets: datasetValue},
				  options: {
					barValueSpacing: 20,
					scales: {
					  yAxes: [{
						ticks: {
						  min: 0,
						}
					  }]
					}
				  }
				});
			}
		});
} 
function geteprMaterialTypeData(){
	var val = $('#txtSupplierName').val();
	$.ajax({
			type:"POST",
			url:"apigetMaterialTypeEPRFulfillmentReport.php",
			data:{val:val},
			success:function(response){
				var json = JSON.parse(response);
				 var arrlabel = [];
                var arrbackgroundColor = [];
                var arrdata = [];
				json.forEach((item) => {
					arrlabel.push(item.state);
					arrdata.push(item.month);
					arrbackgroundColor.push(item.arraycolor);
				});  
				var ctx = document.getElementById("eprmaterialtype-chart").getContext("2d");
				const datasetValue = []
				
				for (i = 0; i < arrlabel.length; i++)
				{
					datasetValue[i] = {
						label: arrlabel[i],
						backgroundColor: arrbackgroundColor[i],
						data: arrdata[i]
					}
				}
				if(myBarChartMaterial!=""){
					myBarChartMaterial.destroy();
				}
				var xValues = ["April "+year,"May "+year,"June "+year,"July "+year,"Aug "+year,"Sep "+year,"Oct "+year,"Nov "+year,"Dec "+year,"Jan "+yearadd,"Feb "+yearadd,"Mar "+yearadd];

				myBarChartMaterial = new Chart(ctx, {
				  type: 'bar',
				  data: {labels: xValues, datasets: datasetValue},
				  options: {
					barValueSpacing: 20,
					scales: {
					  yAxes: [{
						ticks: {
						  min: 0,
						}
					  }]
					}
				  }
				});
			}
		});
} 

  </script>
</body>

</html>

