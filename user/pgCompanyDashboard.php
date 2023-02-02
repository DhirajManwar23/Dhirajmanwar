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
	
	
	$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
	$settingValueUserCompanyImage= $commonfunction->getSettingValue("Company Image");
	$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
	
	$settingValuePendingStatus =$commonfunction->getSettingValue("Pending Status");
	$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
	
	$settingValuePrimaryEmail =$commonfunction->getSettingValue("Primary Email");
	$settingValuePrimaryContact= $commonfunction->getSettingValue("Primary Contact");

	$company_id=$_SESSION["company_id"];
	
	$querycompanydata = "SELECT CompanyName,CompanyType,Company_Logo FROM tw_company_details WHERE ID = '".$company_id."'";
	$CompanyData = $sign->FunctionJSON($querycompanydata);
	$decodedJSON = json_decode($CompanyData);
	$CompanyName = $decodedJSON->response[0]->CompanyName;
	$CompanyType = $decodedJSON->response[1]->CompanyType;
	$Company_Logo = $decodedJSON->response[2]->Company_Logo;
	
	$qryStatus="select Status from tw_company_details where ID='".$company_id."'";
	$companyStatus= $sign->SelectF($qryStatus,'Status');
	$verifyStatus=$commonfunction->getSettingValue("Verified Status");
	
	$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
	$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
	$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
	
	
	$qrycnt="SELECT count(*) as cnt FROM tw_temp_po_info where buyer_id='".$company_id."' and status='".$settingValueVerifiedStatus."'";
	$retqrycnt = $sign->Select($qrycnt);
	if($retqrycnt=="" || $retqrycnt==0){
		$supplier_id="";
		$total_quantity=0.00;
		$final_total_amount=0.00;
		$pendingpo=0;
		$activepo=0;
	}
	else{
		
		$querypodata = "SELECT supplier_id,total_quantity,final_total_amount FROM tw_temp_po_info where buyer_id='".$company_id."' and status='".$settingValueVerifiedStatus."'";
		$POMeasurement = $sign->FunctionJSON($querypodata);
		$decodedJSON1 = json_decode($POMeasurement);
		$supplier_id = $decodedJSON1->response[0]->supplier_id;
		$total_quantity = $decodedJSON1->response[1]->total_quantity;
		$final_total_amount = $decodedJSON1->response[2]->final_total_amount;
		

		$querypendingpocount = "SELECT count(*) as pendingpo FROM tw_temp_po_info where supplier_id='".$supplier_id."' and status='".$settingValuePendingStatus."'";
		$pendingpocount = $sign->FunctionJSON($querypendingpocount);
		$decodedJSON2 = json_decode($pendingpocount);
		$pendingpo = $decodedJSON2->response[0]->pendingpo;
	
		$queryverifiedpocount = "SELECT count(*) as activepo FROM tw_temp_po_info where supplier_id='".$supplier_id."' and status='".$settingValueVerifiedStatus."' ";
		$verifiedpocount = $sign->FunctionJSON($queryverifiedpocount);
		$decodedJSON3 = json_decode($verifiedpocount);
		$activepo = $decodedJSON3->response[0]->activepo;
	}
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
            <div class="col-lg-9 col-md-9 col-sm-9 com-xs-9 col-9 grid-margin stretch-card">
              <div class="card tale-bg">
                <div class="card-body">
				<div class="row">
				<div class="col-lg-10 col-md-10 col-sm-10 com-xs-10 col-10">
                  <h3 class="font-weight-bold">Welcome <?php echo $CompanyName;  if ($companyStatus==$verifyStatus) { echo " <img src='".$CommonDataValueCommonImagePath ."".$VerifiedImage."'/>";} ?></h3>
                </div>
				<div class="col-lg-2 col-md-2 col-sm-2 com-xs-2 col-2">
                  <img style="border-radius: 15px;" src="<?php if($Company_Logo==""){echo $settingValueUserImagePathOther.$settingValueUserCompanyImage; }else{ echo $settingValueUserImagePathVerification.$userid."/".$Company_Logo;}?>" class="img-lg  mb-3" />
					</div>
				</div>
			</div>
			
			
			<!--<div class="col-lg-6 col-md-6 col-sm-6 com-xs-6 col-6">
                   <h6 class="font-weight"><?php //echo $designation_value; ?> at <?php //echo $CompanyName; ?></h6>
             </div>-->
             </div>
			 
            </div>
			<!------------------------Progressive Div Starts------------------------------------>
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
		<!------------------------Progressive Div Ends-------------------------------------->	
			</div>
			<!-- First Row ends-->
			<!-- Second Row starts-->
			<div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              
                <div class="col-md-3 grid-margin stretch-card">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Active PO Quantity</p>
                      <p class="fs-30 mb-2"><?php echo $total_quantity; ?> Kg</p>
                    </div>
                  </div>
                  </div>
                <div class="col-md-3 grid-margin stretch-card">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Active PO Amount</p>
                       <p class="fs-30 mb-2">&#8377; <?php echo $final_total_amount; ?></p>
                    </div>
                  </div>
                 </div>
               <div class="col-md-3 grid-margin stretch-card">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Inprocess PO</p>
                      <p class="fs-30 mb-2"><?php echo $pendingpo; ?></p>
                    </div>
                  </div>
                  </div>
               <div class="col-md-3 grid-margin stretch-card">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Active PO</p>
                      <p class="fs-30 mb-2"><?php echo $activepo; ?></p>
                    
                    </div>
                  </div>
                  </div>
              
              </div>
              </div>
			  <!--Second Row Ends -->
           
          <div class="row">
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
</body>

</html>

