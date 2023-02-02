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
$ComplianceImage=$commonfunction->getCommonDataValue("Compliance Image");
$settingValueUserCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");

$settingValuePendingStatus =$commonfunction->getSettingValue("Pending Status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");

$company_id=$_SESSION["company_id"];

$qryStatus="select Status,compliance_status from tw_company_details where ID='".$company_id."'";
$companyStatus= $sign->SelectF($qryStatus,'Status');
$compliance_status= $sign->SelectF($qryStatus,'compliance_status');
$verifyStatus=$commonfunction->getSettingValue("Verified Status");

$querycompanydata = "SELECT CompanyName,Company_Logo FROM tw_company_details WHERE ID = '".$company_id."'  ";
$CompanyData = $sign->FunctionJSON($querycompanydata);
$decodedJSON = json_decode($CompanyData);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$Company_Logo = $decodedJSON->response[1]->Company_Logo;

$querypodata = "SELECT total_quantity,final_total_amount FROM tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."'";
$POMeasurement = $sign->FunctionJSON($querypodata);
$decodedJSON1 = json_decode($POMeasurement);

if(isset($decodedJSON1->response[0]->total_quantity))
{
	$total_quantity = $decodedJSON1->response[0]->total_quantity;
}
else
{
	$total_quantity="";
}
if(isset($decodedJSON1->response[1]->final_total_amount))
{
	$final_total_amount = $decodedJSON1->response[1]->final_total_amount;
}
else
{
	$final_total_amount="";
}


	$querytotalqty = "SELECT SUM(total_quantity) AS TotalQuantity FROM tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."'";
	$totalqtysum = $sign->SelectF($querytotalqty,"TotalQuantity");
	if(isset($totalqtysum))
	{
		$TotalQuantity = $totalqtysum;
	}
	else
	{
		$TotalQuantity=0;
	}
	$querytotalamt = "SELECT SUM(final_total_amount) AS TotalAmount FROM tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."'";
	$totalamtsum = $sign->SelectF($querytotalamt,"TotalAmount");
	if(isset($totalamtsum))
	{
		$TotalAmount = $totalamtsum;
	}
	else
	{
		$TotalAmount=0;
	}
	
	$querypendingpocount = "SELECT count(*) as pendingpo FROM tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValuePendingStatus."'";
	$pendingpocount = $sign->FunctionJSON($querypendingpocount);
	$decodedJSON2 = json_decode($pendingpocount);
	$pendingpo = $decodedJSON2->response[0]->pendingpo;
	
	$queryverifiedpocount = "SELECT count(*) as activepo FROM tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueVerifiedStatus."' ";
	$verifiedpocount = $sign->FunctionJSON($queryverifiedpocount);
	$decodedJSON3 = json_decode($verifiedpocount);
	$activepo = $decodedJSON3->response[0]->activepo;
	
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
				<div class="col-md-12 grid-margin stretch-card">
					<div class="card tale-bg">
						<div class="card-body">
							<div class="row">
								<div class="col-lg-10 col-md-10 col-sm-10 com-xs-10 col-10">
									<h3 class="font-weight-bold">Welcome 
									<?php echo $CompanyName;  if($companyStatus==$verifyStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$VerifiedImage;?>" data-toggle="tooltip" data-placement="top" title="This is a verified company"/><?php }?><?php if($compliance_status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$ComplianceImage;?>" data-toggle="tooltip" data-placement="top" title="This is a compliant company"/> <?php }?>
									</h3>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2 com-xs-2 col-2">
									<img style="border-radius: 15px;" src="<?php if($Company_Logo==""){echo $settingValueUserImagePathOther.$settingValueUserCompanyImage; }else{ echo $settingValueUserImagePathVerification.$userid."/".$Company_Logo;}?>" class="img-lg  mb-3" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- First Row ends-->
			<!-- Second Row starts-->
			<div class="row">
				<div class="col-md-12 grid-margin stretch-card">
					<div class="col-md-3 grid-margin stretch-card">
						<div class="card card-tale">
							<div class="card-body">
								<p class="mb-4">Active PO Quantity</p>
								<p class="fs-25 mb-2"><?php echo $TotalQuantity; ?> Kg</p>
							</div>
						</div>
					</div>
					<div class="col-md-3 grid-margin stretch-card">
						<div class="card card-dark-blue">
							<div class="card-body">
								<p class="mb-4">Active PO Amount</p>
								<p class="fs-25 mb-2">&#8377; <?php echo $TotalAmount; ?></p>
							</div>
						</div>
					</div>
					<div class="col-md-3 grid-margin stretch-card">
						<div class="card card-light-blue">
							<div class="card-body">
								<p class="mb-4">Inprocess PO</p>
								<p class="fs-25 mb-2"><?php echo $pendingpo; ?></p>
							</div>
						</div>
					</div>
						<div class="col-md-3 grid-margin stretch-card">
							<div class="card card-light-danger">
								<div class="card-body">
									<p class="mb-4">Active PO</p>
									<p class="fs-25 mb-2"><?php echo $activepo; ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			  <!--Second Row Ends -->
           
          <!--<div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Order Details</p>
                  <p class="font-weight-500">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
                  <div class="d-flex flex-wrap mb-5">
                    <div class="mr-5 mt-3">
                      <p class="text-muted">Order value</p>
                      <h3 class="text-primary fs-30 font-weight-medium">12.3k</h3>
                    </div>
                    <div class="mr-5 mt-3">
                      <p class="text-muted">Orders</p>
                      <h3 class="text-primary fs-30 font-weight-medium">14k</h3>
                    </div>
                    <div class="mr-5 mt-3">
                      <p class="text-muted">Users</p>
                      <h3 class="text-primary fs-30 font-weight-medium">71.56%</h3>
                    </div>
                    <div class="mt-3">
                      <p class="text-muted">Sales in &#8377;</p>
                      <h3 class="text-primary fs-30 font-weight-medium">34040</h3>
                    </div> 
                  </div>
                  <canvas id="order-chart"></canvas>
                </div>
              </div>
            </div>    
          </div>
          
          <div class="row">           
            <div class="col-lg-6 grid-margin grid-margin-lg-0 stretch-card">
              <div class="card">
                <div class="card-body">
				<h4 class="card-title">Pie chart</h4>
					<canvas id="south-america-chart"></canvas>
                    <div id="south-america-legend"></div>
                </div>
              </div>
            </div>    
			<div class="col-lg-6 grid-margin grid-margin-lg-0 stretch-card">
              <div class="card">
                <div class="card-body">
				<h4 class="card-title">Pie chart</h4>
					<canvas id="north-america-chart"></canvas>
                    <div id="north-america-legend"></div>
                </div>
              </div>
            </div>   
          </div>-->
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
</body>
</html>

