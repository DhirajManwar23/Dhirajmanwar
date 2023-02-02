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
$date = $cur_date;
$year = explode('-', $date);
$fetchyear = $year[0];
$fetchmonth = $year[1];
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
	FROM tw_mixwaste_manual_entry where entry_date='" . $cur_date . "'";
$dateCnt = $sign->SelectF($dateCntqry, "quantity");

$weekCntQry = "select SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry where week(entry_date)= week(CURRENT_DATE())";
$weekCnt = $sign->SelectF($weekCntQry, "quantity");

$weekNumberQry = "select week(CURRENT_DATE()) weekNumber 
	FROM tw_mixwaste_manual_entry ";
$weekNumber = $sign->SelectF($weekNumberQry, "weekNumber");

$monthcount = "select entry_date,SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry where month(entry_date)='" . $fetchmonth . "'";
$retmonthVal = $sign->SelectF($monthcount, "quantity");

$year = date('Y') + (int)((date('m') - 1) / 6);
$qry1 = " select entry_date,SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry where year(entry_date)='" . $fetchyear . "'";
$retVal1 = $sign->SelectF($qry1, "quantity");
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
							<img style="border-radius: 15px;" src="<?php if ($employee_photo == "") {
																		echo $settingValueEmployeeImagePathOther . $settingValueEmployeeImage;
																	} else {
																		echo $settingValueEmployeeImagePathVerification . $value . "/" . $employee_photo;
																	} ?>" class="img-lg  mb-3" />
						</div>
						<div class="col-lg-7 col-md-7 col-sm-7 com-xs-7 col-7">
							<h3 class="font-weight-bold">Welcome <?php echo $Name;
																	if ($employeeStatus == $verifyStatus) {
																		echo " <img src='" . $EmployeeImagePathOther . "" . $VerifiedImage . "'/>";
																	} ?></h3>
							<h6 class="font-weight-normal mb-0"><?php echo $designation_value; ?><br><br> <?php echo $CompanyName; ?> </h6>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 com-xs-3 col-3">
							<img src="../assets/images/tw/wnm_logo.jpg" style="width:100%;" />
						</div>
					</div>
					<!-- First Row ends-->
					<!-- Second Row starts-->
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
							<div class="card card-tale">
								<div class="card-body">
									<p class="mb-4">Daily Report<br><?php echo date("d-m-Y", strtotime($cur_date)); ?></p>
									<p class="fs-25 mb-2"> <span id="PO_Issued"><?php echo $dateCnt; ?>kg</span></p>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
							<div class="card card-tale">
								<div class="card-body">
									<p class="mb-4">Weekly Report<br>Week Number <?php echo $weekNumber; ?></p>
									<p class="fs-25 mb-2"> <span id="PO_Issued"><?php echo $weekCnt; ?>kg</span></p>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
							<div class="card card-dark-blue">
								<div class="card-body">
									<p class="mb-4">Monthly Report<br> <?php echo date("M Y", strtotime($cur_date)); ?></p>
									<p class="fs-25 mb-2"> <span id="PO_Issued"><?php echo $retmonthVal; ?>kg</span></p>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-6 grid-margin stretch-card">
							<div class="card card-light-danger">
								<div class="card-body">
									<p class="mb-4">Yearly Report<br>FY <?php echo date("m") >= 4 ? date("Y") . '-' . (date("Y") + 1) : (date("Y") - 1) . '-' . date("Y"); ?> </p>
									<p class="fs-25 mb-2"> <span id="PO_Issued"><?php echo $retVal1; ?>kg</span></p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="start_date">Start Date<code>*</code></label>
							<input type="date" class="form-control" id="start_date" placeholder="Start Date" />
						</div>
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="end_date">End Date<code>*</code></label>
							<input type="date" class="form-control" id="end_date" placeholder="End Date" />
						</div>
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="report_type">Report Type<code>*</code></label>
							<select name="report_type" id="report_type" onchange="buildReports()" class="form-control form-control-sm">
								<option value="daily">Daily</option>
								<option value="weekly">Weekly</option>
								<option value="monthly">Monthly</option>
								<option value="yearly">Yearly</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-12 grid-margin stretch-card">
							<div class="card">
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<p class="card-title">Data Entry</p>
									</div>
									<canvas id="line_chart"></canvas>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-12 grid-margin stretch-card">
							<div class="card">
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<p class="card-title">Daily Segregation</p>
									</div>
									<canvas id="donut_chart"></canvas>
								</div>
							</div>
						</div>
					</div>

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
				var yearadd = year + 1;
				$(document).ready(function() {
					//getMixWasteMonthlyGraph();
					//getDailyCollectionData();
				});
				var myBarChartMonthly = "";

				function getMixWasteMonthlyGraph() {
					$.ajax({
						type: "POST",
						url: "apiMixWasteDataEntryGraph.php",
						data: {},
						success: function(response) {
							console.log(response);
							var json = JSON.parse(response);
							var arrdata = [];
							json.forEach((item) => {
								arrdata.push(item.sum);
							});

							var ctx = document.getElementById("lineChart1").getContext("2d");
							const datasetValue = []
							for (i = 0; i < arrdata.length; i++) {
								datasetValue[i] = {
									label: "Data Entry",
									borderColor: '#57c7d4',
									fill: false,
									data: arrdata[i]
								}
							}
							if (myBarChartMonthly != "") {
								myBarChartMonthly.destroy();
							}
							var xValues = ["April " + year, "May " + year, "June " + year, "July " + year, "Aug " + year, "Sep " + year, "Oct " + year, "Nov " + year, "Dec " + year, "Jan" + yearadd, "Feb" + yearadd, "Mar" + yearadd];
							myBarChartMonthly = new Chart(ctx, {
								type: 'line',
								data: {
									labels: xValues,
									datasets: datasetValue
								},
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

				function getDailyCollectionData() {
					$.ajax({
						type: "POST",
						url: "apigetDailyData.php",
						data: {},
						success: function(response) {
							console.log(response);
							var json = JSON.parse(response);
							var arrdataname = [];
							var arrdataquantity = [];
							json.forEach((item) => {
								arrdataname.push(item.name);
								arrdataquantity.push(item.quantity);
							});
							var numberArray = [];
							length = arrdataquantity.length;
							for (var i = 0; i < length; i++) {
								numberArray.push(parseInt(arrdataquantity[i]));
							}
							var nameArray = arrdataname.map(function(e) {
								return e.toString()
							});
							var doughnutPieData = {
								datasets: [{
									data: numberArray,
									backgroundColor: [
										'rgba(255, 99, 132, 0.5)',
										'rgba(54, 162, 235, 0.5)',
										'rgba(255, 206, 86, 0.5)',
										'rgba(75, 192, 192, 0.5)',
										'rgba(153, 102, 255, 0.5)',
										'rgba(255, 159, 64, 0.5)',
										'rgba(63, 81, 181, 0.5)',
										'rgba(0, 77, 64, 0.5)',
										'rgba(205, 220, 57, 0.5)',
										'rgba(121, 85, 72, 0.5)'
									],
									borderColor: [
										'rgba(255,99,132,1)',
										'rgba(54, 162, 235, 1)',
										'rgba(255, 206, 86, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(153, 102, 255, 1)',
										'rgba(255, 159, 64, 1)',
										'rgba(63, 81, 181, 1)',
										'rgba(0, 77, 64, 1)',
										'rgba(205, 220, 57, 1)',
										'rgba(121, 85, 72, 1)'
									],
								}],
								labels: nameArray,
							};
							var doughnutPieOptions = {
								responsive: true,
								animation: {
									animateScale: true,
									animateRotate: true
								},
								legend: {
									display: false
								}
							};
							var pieChartCanvas = $("#epr-pieChart").get(0).getContext("2d");
							var pieChart = new Chart(pieChartCanvas, {
								type: 'doughnut',
								data: doughnutPieData,
								options: doughnutPieOptions,
								responsive: true,

								maintainAspectRatio: true,
							});
						}
					});
				}

				function buildReports() {
					var reportType = $('#report_type').val();
					var startDate = $('#start_date').val();
					var endDate = $('#end_date').val();
					var request = {
						reportType: reportType,
						startDate: startDate,
						endDate: endDate
					};

					$.post("api-report-type-data.php", request, function(response) {
						buildLineChart(response.line_chart_data);
						buildDonutChart(response.donut_chart_data);
					});
				}

				function buildDonutChart(dounutChartData) {
					var wasteTypesArray = [];
					var wasteQuantityArray = [];
					var colorsArray = [];

					dounutChartData.forEach((item) => {
						wasteQuantityArray.push(item.quantity);
						wasteTypesArray.push(item.waste_type);
						colorsArray.push(getColor());
					});

					var donutCtx = document.getElementById("donut_chart").getContext("2d");
					new Chart(donutCtx, {
						type: 'doughnut',
						data: {
							labels: wasteTypesArray,
							datasets: [{
								label: 'Data',
								data: wasteQuantityArray,
								backgroundColor: colorsArray
							}]
						},
						options: {
							responsive: true,
							legend: {
								display: false
							},
							plugins: {
								title: {
									display: false,
									text: 'Doughnut Chart'
								}
							}
						},
					});

				}

				function buildLineChart(lineChartData) {
					var ctx = document.getElementById("line_chart").getContext("2d");
					var daysArray = [];
					var quantityArray = [];
					lineChartData.forEach((item) => {
						quantityArray.push(item.quantity);
						daysArray.push(item.date);
					});

					new Chart(ctx, {
						type: 'line',
						data: {
							labels: daysArray,
							datasets: [{
								label: 'Data Entry',
								borderColor: '#57c7d4',
								fill: false,
								data: quantityArray,
								borderWidth: 1
							}]
						},
						options: {
							scales: {
								y: {
									beginAtZero: true
								}
							}
						}
					});
				}

				function getColor() {
					var r = Math.floor(Math.random() * 200);
					var g = Math.floor(Math.random() * 200);
					var b = Math.floor(Math.random() * 200);
					return 'rgb(' + r + ', ' + g + ', ' + b + ')';
				}
			</script>
</body>

</html>