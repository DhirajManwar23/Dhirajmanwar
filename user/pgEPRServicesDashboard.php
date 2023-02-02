<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogIn.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
	
$userid = $_SESSION["companyusername"];
$Company_Logo = "";
$value = "";
$total_quantity = "";
$final_total_amount = "";

$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
$settingValueUserCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$ComplianceImage=$commonfunction->getCommonDataValue("Compliance Image");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValuePendingStatus =$commonfunction->getSettingValue("Pending Status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueNewimage= $commonfunction->getSettingValue("Newimage");
$settingValueEmployeeImagePathOther  = $commonfunction->getSettingValue("EmployeeImagePathOther");
$company_id=$_SESSION["company_id"];

$settingValuePrimaryEmail =$commonfunction->getSettingValue("Primary Email");
$settingValuePrimaryContact= $commonfunction->getSettingValue("Primary Contact");
	
$qryStatus="select Status,compliance_status from tw_company_details where ID='".$company_id."'";
$companyStatus= $sign->SelectF($qryStatus,'Status');
$compliance_status= $sign->SelectF($qryStatus,'compliance_status');
$verifyStatus=$commonfunction->getSettingValue("Verified Status");

$querycompanydata = "SELECT CompanyName,Company_Logo FROM tw_company_details WHERE ID = '".$company_id."'  ";
$CompanyData = $sign->FunctionJSON($querycompanydata);
$decodedJSON = json_decode($CompanyData);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$Company_Logo = $decodedJSON->response[1]->Company_Logo;

$querypendingpo = "SELECT count(*) as cnt FROM tw_po_info where status='".$settingValuePendingStatus."' and supplier_id='".$company_id."'";
$pendingpocount = $sign->SelectF($querypendingpo,"cnt");
	

$queryacceptedpocount = "SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as acceptedpo FROM tw_temp where status='".$settingValueCompletedStatus."' and po_id in (select id from tw_po_info where supplier_id='".$company_id."')";
$acceptedpo = $sign->SelectF($queryacceptedpocount,"acceptedpo");
if($acceptedpo==""){
	$acceptedpo=0.00;
}
$queryawaitingpocount = "SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as awaitingpocount FROM tw_temp where status='".$settingValueAwaitingStatus."' and po_id in (select id from tw_po_info where supplier_id='".$company_id."')";
$awaitingpocount = $sign->SelectF($queryawaitingpocount,"awaitingpocount");

$queryverifiedpocount = "SELECT IFNULL (sum(total_quantity), 0) as activepo FROM tw_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."' ";
$activepo = $sign->SelectF($queryverifiedpocount,"activepo");
if($activepo==""){
	$activepo=0.00;
}

$UNFULLFILLED = $activepo - ($acceptedpo + $awaitingpocount);
if($UNFULLFILLED==""){
	$UNFULLFILLED=0.00;
}

$queryrejectedbycompanycount = "SELECT COUNT(*) as rejectedbycompany FROM tw_temp where status='".$settingValueRejectedStatus."' and po_id in (select id from tw_po_info where supplier_id='".$company_id."') and rejected_by='Company'";
$rejectedbycompany = $sign->SelectF($queryrejectedbycompanycount,"rejectedbycompany");
if($rejectedbycompany==""){
	$rejectedbycompany=0.00;
}

$queryrejectedbyauditorcount = "SELECT COUNT(*) as rejectedbyauditor FROM tw_temp where status='".$settingValueRejectedStatus."' and po_id in (select id from tw_po_info where supplier_id='".$company_id."') and rejected_by='Auditor'";
$rejectedbyauditor = $sign->SelectF($queryrejectedbyauditorcount,"rejectedbyauditor");
if($rejectedbyauditor==""){
	$rejectedbyauditor=0.00;
}
$totalquery=$rejectedbycompany+$rejectedbyauditor;
	
$DropdownDetailsQry="SELECT DISTINCT pi.company_id,cd.CompanyName FROM tw_po_info pi INNER JOIN tw_company_details cd ON pi.company_id=cd.ID where pi.supplier_id='".$company_id."' ";
$DropdownDetails=$sign->FunctionOption($DropdownDetailsQry,"",'CompanyName',"company_id");

//----------------------------------- Profile Progress Starts ------------------------------------//
	$divCont1=0;
	$divCont2=0;
	$divCont3=0;
	$divCont4=0;
	$divCont5=0;
	$divCont6=0;
	$divCont7=0;
	$divCont8=0;
	
	
	$queryCompContactCnt="SELECT count(*) as cnt FROM tw_company_contact WHERE company_id='".$company_id."' and contact_field!='".$settingValuePrimaryEmail."' and contact_field!='".$settingValuePrimaryContact."'";
	$ValueCompContactCnt = $sign->Select($queryCompContactCnt);
	if($ValueCompContactCnt==0){
		$divCont1=0;
	}
	else{
		$divCont1=1;
	}
	
	$queryCompAddressCnt="SELECT count(*) as cnt FROM tw_company_address WHERE company_id='".$company_id."'";
	$ValueCompAddressCnt = $sign->Select($queryCompAddressCnt);
	if($ValueCompAddressCnt==0){
		$divCont2=0;
	}
	else{
		$divCont2=1;
	}
	
	
	$queryCompBankDetailsCnt="SELECT count(*) as cnt FROM tw_company_bankdetails WHERE company_id='".$company_id."'";
	$ValueCompBankDetailsCnt = $sign->Select($queryCompBankDetailsCnt);
	if($ValueCompBankDetailsCnt==0){
		$divCont3=0;
	}
	else{
		$divCont3=1;
	}
	
	$queryCompDocCnt="SELECT count(*) as cnt FROM tw_company_document WHERE company_id='".$company_id."'";
	$ValueCompDocCnt = $sign->Select($queryCompDocCnt);
	if($ValueCompDocCnt==0){
		$divCont4=0;
	}
	else{
		$divCont4=1;
	}
	
	
	$queryCompDescCnt="SELECT count(*) as cnt FROM tw_company_details WHERE ID='".$company_id."' and Description!=''";
	$ValueCompDescCnt = $sign->Select($queryCompDescCnt);
	if($ValueCompDescCnt==0){
		$divCont5=0;
	}
	else{
		$divCont5=1;
	}
	
	
	$queryCompPrimaryContactCnt="SELECT count(*) as cnt FROM tw_company_contact WHERE id='".$company_id."' and contact_field='".$settingValuePrimaryEmail."' or contact_field='".$settingValuePrimaryContact."' and status='".$settingValueVerifiedStatus."'";
	$ValueCompPrimaryContactCnt = $sign->Select($queryCompPrimaryContactCnt);
	if($ValueCompPrimaryContactCnt==0){
		$divCont7=0;
	}
	else{
		$divCont7=1;
	}
	
	$queryCompPhotoCnt="SELECT count(*) as cnt FROM tw_company_details WHERE ID='".$company_id."' and Company_Logo!=''";
	$ValueCompPhotoCnt = $sign->Select($queryCompPhotoCnt);
	if($ValueCompPhotoCnt==0){
		$divCont8=0;
	}           
	else{       
		$divCont8=1;
	}
	
	
	$Progressive = ($divCont1)+($divCont2)+($divCont3)+($divCont4)+($divCont5)+($divCont6)+($divCont7)+($divCont8);
	//echo $Progressive."-----------";
	
	$percentage=($Progressive/8)*100;

	
	//------------------------------ Progress bar starts ---------------------------------//

	if($percentage>=0 && $percentage<=24.99){	
		
			$progressstatus = "progress-bar bg-danger";
		}
		else if($percentage>=25 && $percentage<=49.99){
			$progressstatus = "progress-bar bg-warning";
		}
		else if($percentage>=50 && $percentage<=99.99){
			
			$progressstatus = "progress-bar bg-primary";
		}
		else if($percentage>=100){
			$percentage=100.00;
			$progressstatus = "progress-bar bg-success";
		}
		else{
			$percentage=0.00;
			$progressstatus = "progress-bar bg-danger";

		}

		//------------------------------ Progress bar ends ---------------------------------//
	
	//----------------------------------- Profile Progress Ends --------------------------------------//
	
		
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace-Waste | Company Dashboard </title>
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
						<div class="col-lg-6 col-md-6 col-sm-6 com-xs-6 col-6 dashboard-username">
							<img style="border-radius: 15px;" src="<?php if($Company_Logo==""){echo $settingValueUserImagePathOther.$settingValueUserCompanyImage; }else{ echo $settingValueUserImagePathVerification.$userid."/".$Company_Logo;}?>" class="img-lg  mb-3" />
							<h3 class="font-weight-bold">Welcome <?php echo $CompanyName;  if ($companyStatus==$verifyStatus) { echo " <img src='".$CommonDataValueCommonImagePath ."".$VerifiedImage."' data-toggle='tooltip' data-placement='top' title='This is a verified company' />";} ?><?php if($compliance_status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$ComplianceImage;?>" data-toggle="tooltip" data-placement="top" title="This is a compliant company"/> <?php } ?></h3><br>
							<h6 class="font-weight-normal mb-0"><?php if($totalquery>0){ ?>You have<span class="text-primary"> <a href="pgEPRSQueries.php?type=Queries" class="text-success"><br><?php echo $totalquery; ?>  queries</a><img class="New-img" src="<?php echo $settingValueEmployeeImagePathOther.$settingValueNewimage ?>"  /></span><?php } ?></h6>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 com-xs-3 col-3">
						 <div class="dropdown">
							<select id="txtSupplierName" class="form-control"  onchange="VendorSel()" >
								<option value="0">All Clients</option>
								<?php echo $DropdownDetails; ?>
							</select>
						  </div>
						</div>
						 <div class="col-lg-3 col-md-3 col-sm-3 com-xs-3 col-3 grid-margin">
							<div class="card">
								<div class="card-body">
								  <?php
									echo $progressdiv = "<div class='template-demo'>
														<div> 
															<h2 ><center>".round($percentage)."%</center></h2>
														</div>
													 <div class='progress progress-lg mt-2 '>
														  <div class='".$progressstatus."' role='progressbar' style='width:".$percentage."%' aria-valuenow='per' aria-valuemin= '".$percentage."' aria-valuemax='100'></div>
													  </div><br>
												</div>"
									?>	
								</div>			
							</div>			
						</div>
						
					</div>
				</div>
          </div>
			<!-- First Row ends-->
			<!-- Second Row starts-->
			<?php if($pendingpocount>0){ ?>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-inverse-warning">
						<div class="card-body tw-shake">
							<span class="card-text ">
								<i class="ti-announcement"></i> You have <strong><?php echo $pendingpocount; ?></strong> Pending PO
							</span>
							<span class="float-right"><a href="pgEPRSPendingPo.php?type=Pending" class="text-success">View</a></span>
						</div>
					</div>
				</div>
			</div>
			<?php } ?><br>
			  <!--Second Row Ends -->
			  <!--Third Row Start -->
			 <div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
					  <div class="card card-tale">
						<div class="card-body">
						  <p class="mb-4">Purchase Order<br>Issued</p>
						  <p class="fs-25 mb-2" > <span id="PO_Issued"><?php echo number_format($activepo); ?></span><span class="tw-small-word">kg<span></p>
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
				<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
				  <div class="card card-light-blue">
					<div class="card-body">
					  <p class="mb-4">Query Raised by<br>Company</p>
					  <p class="fs-25 mb-2"><span id="Q_Company"><?php echo number_format($rejectedbycompany); ?></span></p>
					</div>
				  </div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
				  <div class="card card-light-blue">
					<div class="card-body">
					  <p class="mb-4">Query Raised by<br>Auditor</p>
					  <p class="fs-25 mb-2"><span id="Q_Auditor"><?php echo number_format($rejectedbyauditor); ?></span></p>
					</div>
				  </div>
				</div>
              </div>
			<div class="row"  id="divCategory">
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
					<div class="card">
						  <div class="card-body">
							  <p class="card-title">Statewise Tabulated Details</p>
							  <div class="row">
								<div class="col-12">
								  <div class="table-responsive">
									<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
										<div class="row">
											<div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<table id="tableData" class="display expandable-table dataTable no-footer table-bordered" style="width: 100%;" role="grid">
												 </table>
											 </div>
										 </div>
										 <div class="row">
											 <div class="col-sm-12 col-md-5">
											 </div>
											 <div class="col-sm-12 col-md-7">
											 </div>
										 </div>
									 </div>
								  </div>
								</div>
							  </div>
							  </div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
					<div class="card">
						  <div class="card-body">
							  <p class="card-title">Category Tabulated Details</p>
							  <div class="row">
								<div class="col-12">
								  <div class="table-responsive">
									<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
										<div class="row">
											<div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<table id="tableDatacategory" class="display expandable-table dataTable no-footer table-bordered" style="width: 100%;" role="grid">
												 </table>
											 </div>
										 </div>
										 <div class="row">
											 <div class="col-sm-12 col-md-5">
											 </div>
											 <div class="col-sm-12 col-md-7">
											 </div>
										 </div>
									 </div>
								  </div>
								</div>
							  </div>
							  </div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 grid-margin stretch-card">
				  <div class="card">
					<div class="card-body">
					  <p class="card-title">Companywise Tabulated Details</p>
					  <div class="row">
						<div class="col-12">
						  <div class="table-responsive">
							<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
								<div class="row">
									<div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<table id="tablevendor" class="display expandable-table dataTable no-footer table-bordered expandeblethcolor thead th tr" style="width: 100%;" role="grid">
										 </table>
									 </div>
								 </div>
								 <div class="row">
									 <div class="col-sm-12 col-md-5">
									 </div>
									 <div class="col-sm-12 col-md-7">
									 </div>
								 </div>
							 </div>
						  </div>
						</div>
					  </div>
					  </div>
					</div>
				  </div>
            </div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
				  <div class="card">
					<div class="card-body">
					 <div class="d-flex justify-content-between">
					  <p class="card-title">Yearly EPR Fulfillment</p>
					 </div>
					  <div id="epr-legend" class="chartjs-legend mt-4 mb-2"></div>
					  <canvas id="epr-chart"></canvas>
					</div>
				  </div>
				</div>
			</div>
		  <div class="row">
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
var myBarChartYearly = "";
var myBarChartState = "";
var myBarChartMaterial = "";
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
	tablulatedCompanyDetails();
}); 
function getMonthlyCollectionData(){
	var val = $('#txtSupplierName').val();
	$.ajax({
			type:"POST",
			url:"apigetYearlyEPRFulfillmentReport.php",
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
						label: "Material Data",
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
function tablulatedDetails(){
	var val = $('#txtSupplierName').val();
	$.ajax({
		type:"POST",
		url:"apiGetEprTabulatedDetails.php",
		data:{val:val},
		dataType: 'JSON',
		success:function(response){
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
function tablulatedCompanyDetails(){
	var val = $('#txtSupplierName').val();
	$.ajax({
		type:"POST",
		url:"apiGetEprCompanyTabulatedDetails.php",
		data:{val:val},
		dataType: 'JSON',
		success:function(response){
		console.log(response);
		 $("#tablevendor").html(response);
		}		
	});
}
//var valcheck=0;
function hideRow(id) {
	$("tr#idtr-"+id).slideToggle("slow");
	/* if(valcheck==0){
		valcheck=1;
		alert("open");
	}
	else{
		valcheck=0;
		alert("close");
	} */
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
function VendorSel(){
	var val = $('#txtSupplierName').val();
		$.ajax({
			type:"POST",
			url:"apiGetEprPoDetails.php",
			data:{val:val},
			dataType: 'JSON',
			success:function(response){
			    $("#PO_Issued").html(response[0]);
			    $("#PO_Accepted").html(response[1]);
			    $("#PO_Unfullfilled").html(response[2]);
			    $("#PO_Pending").html(response[3]);
			    $("#Q_Company").html(response[4]);
			    $("#Q_Auditor").html(response[5]);
				tablulatedDetails();
				getMonthlyCollectionData();
				geteprstateData();
				geteprMaterialTypeData();
				tablulatedCategoryDetails();
				tablulatedCompanyDetails();
				$("#divCategory").html(response[6]);
			}		
		});
	
}
</script>
</body>
</html>

