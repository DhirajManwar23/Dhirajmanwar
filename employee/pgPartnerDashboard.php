<?php
session_start();
if (!isset($_SESSION["employeeusername"])) {
	header("Location:pgEmployeeLogIn.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign = new Signup();
$commonfunction = new Common();
$cur_date = date("Y-m-d");
 $YesterdayDate=date('Y-m-d',strtotime("-1 days"));
$date = $cur_date;
$year = explode('-', $date);
$Currentfetchyear=$year[0];
$fetchmonth = $year[1];
if($fetchmonth==01 || $fetchmonth==02 || $fetchmonth==03){
	$fetchyear = $year[0]-1;
	$fetchyear2 = $year[0];
}else{
$fetchyear = $year[0];
$fetchyear2 = $year[0]+1;
}


//$fiyear=$fetchyear."-04-01";
//$fiyear2=$fetchyear2."-03-31";

$fiyear="2022-04-01";
$fiyear2="2023-03-31";

$employee_photo = "";
$value = "";
$employee_id = $_SESSION["employee_id"];
$company_id = $_SESSION["company_id"];

$settingValueRightsID = $commonfunction->getSettingValue("RightsID");
//CHECK for Dashboard rights
$qrySelModulename = "Select count(*) as cnt from tw_company_rights_management where company_id='" . $company_id . "' and rights_id='" . $settingValueRightsID . "'";
$settingValueEmployeeImagePathOther  = $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueEmployeeImage = $commonfunction->getSettingValue("Employee Image");
$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");

$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus = $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus = $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus = $commonfunction->getSettingValue("Awaiting Status");
$settingValueRejectedStatus = $commonfunction->getSettingValue("Rejected status");
$settingValueNewimage = $commonfunction->getSettingValue("Newimage");
$settingValueRejectedByCompany = $commonfunction->getSettingValue("RejectedByCompany");
$settingValueRejectedByAuditor = $commonfunction->getSettingValue("RejectedByAuditor");

$qry = "select employee_name from tw_employee_registration where id='" . $employee_id . "'";
$Name = $sign->SelectF($qry, 'employee_name');

$qryStatus = "select Status from  tw_employee_registration where ID='" . $employee_id . "'";
$employeeStatus = $sign->SelectF($qryStatus, 'Status');
$verifyStatus = $commonfunction->getSettingValue("Verified Status");
$VerifiedImage = $commonfunction->getSettingValue("Verified Image");
$EmployeeImagePathOther = $commonfunction->getSettingValue("EmployeeImagePathOther");

$qry = "select er.employee_name,dm.designation_value from tw_employee_registration er INNER JOIN tw_designation_master dm ON er.employee_designation=dm.id where er.id='" . $employee_id . "'";
$Empdata = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($Empdata);
$employee_name = $decodedJSON->response[0]->employee_name;
$designation_value = $decodedJSON->response[1]->designation_value;

$query = "SELECT cd.CompanyName FROM tw_company_details cd INNER JOIN tw_employee_registration er ON er.company_id=cd.ID where cd.ID='" . $company_id . "'";
$Companydata = $sign->FunctionJSON($query);
$decodedJSON1 = json_decode($Companydata);
$CompanyName = $decodedJSON1->response[0]->CompanyName;

$query1 = "SELECT er.employee_photo,ec.value FROM tw_employee_registration er INNER JOIN tw_employee_contact ec ON ec.employee_id=er.id WHERE er.id = '" . $employee_id . "'";
$Employeedata = $sign->FunctionJSON($query1);
$decodedJSON1 = json_decode($Employeedata);
$employee_photo = $decodedJSON1->response[0]->employee_photo;
$value = $decodedJSON1->response[1]->value;

$dateCntqry = "select SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry where entry_date='" . $YesterdayDate . "'";
$dateCnt = $sign->SelectF($dateCntqry, "quantity");

$Wrokerqry = "SELECT COUNT(DISTINCT Citizen_Name) as cnt FROM tw_ragpicker_information";
$Wroker = $sign->SelectF($Wrokerqry, "cnt");

$monthcount = "select entry_date,SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry where month(entry_date)='" . $fetchmonth . "' and year(entry_date)='".$Currentfetchyear."' ";
$retmonthVal = $sign->SelectF($monthcount, "quantity");

$year = date('Y') + (int)((date('m') - 1) / 6);
$qry1 = " select SUM(quantity) as quantity FROM tw_mixwaste_manual_entry where entry_date BETWEEN '".$fiyear."' AND '".$fiyear2."'";
$retVal1 = $sign->SelectF($qry1, 'quantity');
$yearSum = $retVal1;

$WardnameQry="SELECT id,ward_name FROM tw_ward_master";
$Wardname = $sign->FunctionOption($WardnameQry,"",'ward_name',"id");

$GenderQry = "select 100*sum(case when Gender = 'm' then 1 else 0 end)/count(*) Male, 100*sum(case when Gender = 'f' then 1 else 0 end)/count(*) Female from tw_ragpicker_information";
$Genderdata = $sign->FunctionJSON($GenderQry);
$GenderdecodedJSON1 = json_decode($Genderdata);
$Male = $GenderdecodedJSON1->response[0]->Male;
$Female = $GenderdecodedJSON1->response[1]->Female;


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
	<!-- Partner Dashboard CSS only for this file -->
	<link rel="stylesheet" href="../assets/css/custom/PartnerDashboard.css">
	<!-- Partner Dashboard CSS only for this file -->
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
			<div class="content-wrapper partner-main-panel">

				<!-- First Row starts-->
				<div class="row mb-3">
					<div class="col-lg-2 col-md-2 col-sm-3 com-xs-3 col-3">
						<img style="border-radius: 15px;" src="<?php if ($employee_photo == "") {
																	echo $settingValueEmployeeImagePathOther . $settingValueEmployeeImage;
																} else {
																	echo $settingValueEmployeeImagePathVerification . $value . "/" . $employee_photo;
																} ?>" class="img-lg  mb-3" />
					</div>
					<div class="col-lg-7 col-md-7 col-sm-9 com-xs-9 col-9">
						<h3 class="font-weight-bold">Welcome <?php echo $Name;
																if ($employeeStatus == $verifyStatus) {
																	echo " <img src='" . $EmployeeImagePathOther . "" . $VerifiedImage . "'/>";
																} ?></h3>
						<h6 class="font-weight-normal mb-0"><?php echo $designation_value; ?><br><br> <?php echo $CompanyName; ?> </h6>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 com-xs-12 col-12">
						<label for="txtWard">Select Ward</label>
							<select  class="form-control" id="txtWard"  onchange="showWardData()" placeholder="Fulfilment cycle">
							<option value="">Choose...</option>
							<?php echo $Wardname; ?>
							
						  </select>
					</div>
				</div>
				
				<!-- First Row ends-->
				<!-- Second Row starts-->
				<div class="col-md-12 stretch-card ">
					<div class="card position-relative">
						<div class="card-body partner-collection-card">
						<p class="card-title">Collection</p>
						<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
						<div class="row">
						 <div class="col-lg-2 col-md-2 col-sm-12 col-12">
							<img class="Collection1" src="<?php echo $settingValueEmployeeImagePathOther; ?>/Group 31.png">
						</div>
						</div>
						</div>
						
						<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
								<div class="card ">
									<div class="card-body">
										<p class="mb-4 "><span class="Yearly ">Yearly Report</span>
										<div class="card">
										<div class="card-body partner-card">
										FY <?php echo date("m") >= 4 ? date("Y") . '-' . (date("Y") + 1) : (date("Y") - 1) . '-' . date("Y"); ?> </p>
										<p class="fs-25 mb-2"> <span id="Yearly" class="Yearly"><?php echo  round((int)$yearSum,0); ?> kg</span></p>
										</div>
										</div>
										
									</div>
								</div>
							</div>
							
							<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
								<div class="card ">
									<div class="card-body">
										<p class="mb-4"><span class="Yearly ">Monthly Report</span>
										<div class="card">
											<div class="card-body partner-card">
												<?php echo date("M Y", strtotime($cur_date)); ?></p>
												<p class="fs-25 mb-2"> <span id="Monthly" class="Yearly"><?php echo  round($retmonthVal); ?> kg</span></p>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
								<div class="card">
									<div class="card-body">
										<p class="mb-4"><span class="Yearly ">Daily Report</span>
										<div class="card">
										<div class="card-body partner-card">
										<?php echo date("d-m-Y", strtotime($YesterdayDate)); ?></p>
										<p class="fs-25 mb-2"> <span id="Daily" class="Yearly"><?php echo  round($dateCnt); ?>  kg</span></p>
										</div>
										</div>
									</div>
								</div>	
							</div>
							</div>
						   </div>
					</div>
				</div>
				
				
	<div class="col-md-12 stretch-card">
		<div class="card partner-social-inclusion-card position-relative">
			<div class="card-body ">
				<p class="card-title">Social Inclusion</p>
				<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
				<div class="row">
				 <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                    <img class="Collection2" src="<?php echo $settingValueEmployeeImagePathOther; ?>/social.png">
                </div>
				
				</div>
				</div>
				
				<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
						<div class="card ">
							<div class="card-body">
								<img  class="partner-img partner-center-img" src="<?php echo $settingValueEmployeeImagePathOther; ?>/worker.png">
								<div class="card">
								<div class="card-body partner-card">
								<p class="partner-text">No. of workers </p>
								<p class="fs-25 mb-2"> <span id="Yearly" class="Yearly"><?php echo  round((int)$Wroker,0); ?> </span></p>
								</div>
								</div>
								
							</div>
						</div>
					</div>
					
					<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
						<div class="card ">
							<div class="card-body">
								<img class="partner-img"  src="<?php echo $settingValueEmployeeImagePathOther; ?>/value unlock.png">
								<div class="card">
									<div class="card-body partner-card">
										<p class="partner-text">Value Unlock</p>
										<p class="fs-25 mb-2"> <span id="Monthly" class="Yearly">&#x20b9; 9,02,352 </span></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					
						<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
							<div class="card">
								<div class="card-body">
									<img class="partner-img"  src="<?php echo $settingValueEmployeeImagePathOther; ?>/gender above.png">
									<div class="card">
										<div class="card-body partner-card">
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
												  <p class="partner-text">Gender</p>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
												   <img class="Gender Yearly" src="<?php echo $settingValueEmployeeImagePathOther; ?>/male.png"> <span class="Yearly"><?php echo number_format($Male,0); ?>%</span>
												   <img class="Gender" src="<?php echo $settingValueEmployeeImagePathOther; ?>/female.png"> <span class="Yearly"><?php echo number_format($Female,0); ?>%</span>
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
	</div>
				
				<div class="col-md-12 stretch-card">
				<div class="card position-relative">
				<div class="card-body">
				<p class="card-title">Campaigns</p>
				<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
				<div class="row">
				 <div class="col-lg-2 col-md-2 col-sm-12 col-2">
                    <img class="Collection2 partner-speaker-img" src="<?php echo $settingValueEmployeeImagePathOther; ?>/_Group_.png">
                </div>
				
				</div>
				</div>
				
				<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
						<div class="card ">
							<div class="card-body">
								<img  class="partner-img " src="<?php echo $settingValueEmployeeImagePathOther; ?>/people _eached.png">
								<div class="card">
								<div class="card-body partner-card">
								<p class="partner-text">People Reached </p>
								<p class="fs-25 mb-2"> <span id="Yearly" class="Yearly"> <?php echo  round((int)$retVal1,0); ?> </span></p>
								</div>
								</div>
								
							</div>
						</div>
					</div>
					
					<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
						<div class="card ">
							<div class="card-body">
								<img class="partner-img"  src="<?php echo $settingValueEmployeeImagePathOther; ?>/training.png">
								<div class="card">
									<div class="card-body partner-card">
										<h6><strong>Training of Safai Saathis</strong> </h6>
										<p class="fs-25 mb-2"> <span id="Monthly" class="Yearly"> <?php echo  round($retmonthVal); ?> </span></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="col-lg-3 col-md-3 col-sm-12 col-12 stretch-card">
						<div class="card ">
							<div class="card-body">
								<img class="partner-img partner-beach-img"  src="<?php echo $settingValueEmployeeImagePathOther; ?>/beach.png">
								<div class="card">
									<div class="card-body partner-card">
										<h6><strong>Beach clean-up stats</strong> </h6>
										<p class="fs-25 mb-2"> <span id="Monthly" class="Yearly"> <?php echo  round($retmonthVal); ?> </span></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					</div>
				   </div>
				</div>
					
					
				</div>
				
				
				<br>
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
function showWardData(){
	var ward=$("#txtWard").val();
	$.ajax({
		type:"POST",
		url:"apiGetWardData.php",
		data:{ward:ward},
		success:function(response){
		console.log(response);
		 var Arrdata=response;
		var arr = $.parseJSON(Arrdata);
		// console.log(arr[0]);
		// console.log(arr[1]);
		// console.log(arr[2]);
		
		
		$("#Yearly").html(arr[0] +" "+"Kg");
		$("#Monthly").html(arr[1] +" "+"Kg");
		$("#Daily").html(arr[2] +" "+"Kg");

			
		}
	});
}
</script>
</body>

</html>