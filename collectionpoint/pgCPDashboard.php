<?php 
session_start();
if(!isset($_SESSION["cp_id"])){
	header("Location:pgCollectionPointLogin.php");
}	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d");

$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");

$settingValueMasterAdmin=$commonfunction->getSettingValue("MasterAdmin");
$settingValueCollectionPointImagePathOther =$commonfunction->getSettingValue("CollectionPointImagePathOther"); 
$settingValueuptriangle =$commonfunction->getSettingValue("uptriangle"); 
$settingValuedowntriangle =$commonfunction->getSettingValue("downtriangle"); 
$cp_id = $_SESSION["cp_id"];




//--Todays Start
$qry="select IFNULL (SUM(quantity), 0.00) as quantity from tw_mix_waste_collection where cp_id='".$cp_id."' and collection_date_time LIKE '".$date."%'";
$retValTodaysCollection = $sign->SelectF($qry,"quantity");
if($retValTodaysCollection==""){
	$retValTodaysCollection=0.00;
}
//--Todays End

//--Previous Start
$previousDay = date('Y-m-d', strtotime('now - 1day'));
$qry1="select IFNULL (SUM(quantity), 0.00) as quantity from tw_mix_waste_collection where cp_id='".$cp_id."' and collection_date_time LIKE '".$previousDay."%'";
$retValPreviousCollection = $sign->SelectF($qry1,"quantity");

$perPreviousCollectionDiff = $retValTodaysCollection-$retValPreviousCollection;
if($retValPreviousCollection==0){
	$PerPreviousCollection = 0.00;
}else{
	$PerPreviousCollection = number_format(($perPreviousCollectionDiff/$retValPreviousCollection)*100,2);
}
//--Previous End

//--Previous 7 Start
$previous7Day = date('Y-m-d', strtotime('now - 7day'));
$tomarrowdate = date('Y-m-d', strtotime('now + 1day'));
$qry2="select IFNULL ((Sum(quantity)/count(quantity)),0.00) as sum from tw_mix_waste_collection where cp_id='".$cp_id."' and collection_date_time BETWEEN '".$previous7Day."' and '".$tomarrowdate."'";
$retValPrevious7DaysCollection = $sign->SelectF($qry2,"sum");
//echo$retValPrevious7DaysCollection = 0.00;
$retValPrevious7DaysCollection = number_format($retValPrevious7DaysCollection,2);

$perPrevious7CollectionDiff = number_format(($retValTodaysCollection-$retValPrevious7DaysCollection),2);

if($retValPrevious7DaysCollection==0.00 || $retValPrevious7DaysCollection==0){
	$PerPrevious7Collection = number_format(($retValPrevious7DaysCollection)*100,2);
}
else{
	$PerPrevious7Collection = ($perPrevious7CollectionDiff/$retValPrevious7DaysCollection)*100;
}

//--Previous 7 End

//--Previous 30 Start
$previousMonthlyDay = date('Y-m-d', strtotime('now - 30day'));
$qry3="select IFNULL ((Sum(quantity)/count(quantity)),0.00) as sum from tw_mix_waste_collection where cp_id='".$cp_id."' and collection_date_time BETWEEN '".$previousMonthlyDay."' and '".$tomarrowdate."'";
$retValPreviousMonthlyCollection = $sign->SelectF($qry3,"sum");
//echo$retValPreviousMonthlyCollection = 0.00;

$perPrevious30CollectionDiff = $retValTodaysCollection-$retValPreviousMonthlyCollection;
if($retValPreviousMonthlyCollection==0.00 || $retValPreviousMonthlyCollection==0){
	$PerPrevious30Collection = number_format(($retValPreviousMonthlyCollection)*100,2);
}
else{
	$PerPrevious30Collection = number_format(($perPrevious30CollectionDiff/$retValPreviousMonthlyCollection)*100,2);
}

//--Previous 30 End

$qryCPData="Select collection_point_name,status,collection_point_logo,contact_person_name,mobile_number,address_line_1,address_line_2,location,pincode,city,state,country,reg_number from tw_collection_point_master where id='".$cp_id."'";
$CPData = $sign->FunctionJSON($qryCPData);
$decodedJSON = json_decode($CPData);
$collection_point_name = $decodedJSON->response[0]->collection_point_name;
$status = $decodedJSON->response[1]->status;
$collection_point_logo = $decodedJSON->response[2]->collection_point_logo;
$contact_person_name = $decodedJSON->response[3]->contact_person_name;
$mobile_number = $decodedJSON->response[4]->mobile_number;
$address_line_1 = $decodedJSON->response[5]->address_line_1;
$address_line_2 = $decodedJSON->response[6]->address_line_2;
$location = $decodedJSON->response[7]->location;
$pincode = $decodedJSON->response[8]->pincode;
$city = $decodedJSON->response[9]->city;
$state = $decodedJSON->response[10]->state;
$country = $decodedJSON->response[11]->country;
$reg_number = $decodedJSON->response[12]->reg_number;


//----------------------------------- Profile Progress Starts ------------------------------------//

	
	$divCont2=0;
	$divCont3=0;
	$divCont4=0;
	$divCont5=0;
	
	if($address_line_1=="" && $location==""){
		$divCont2=0;
	}
	else{
		$divCont2=1;
	}
	
	
	if($mobile_number==""){
		$divCont3=0;
	}
	else{
		$divCont3=1;
	}


	if($collection_point_logo==""){
		$divCont4=0;
	}
	else{
		$divCont4=1;
	}
	

	if($reg_number==""){
		$divCont5=0;
	}
	else{
		$divCont5=1;
	}
	

	$Progressive = ($divCont2)+($divCont3)+($divCont4)+($divCont5);
	//echo $Progressive."-----------";
	
	$percentage=($Progressive/4)*100;	
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
  <title>Trace-Waste | Collection Point Dashboard</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
 <!-- <link rel="stylesheet" href="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">-->
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <!--<link rel="stylesheet" type="text/css" href="../assets/js/select.dataTables.min.css">-->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
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
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-9 com-xs-9 col-9 col-xl-9 mb-9 mb-xl-9">
                  <h3 class="font-weight-bold">Welcome <?php echo $collection_point_name;?> <?php if($status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$VerifiedImage;?>"/> <?php }?></h3>
                </div>
				<!------------------------Progressive Div Starts------------------------------------>
			 <div class="col-lg-3 col-md-3 col-sm-3 com-xs-3 col-3 grid-margin">
				<div class="card">
					<div class="card-body">
					  <?php
						echo $progressdiv = "<div class='template-demo'>
											<div> 
												<h2 ><center>".$percentage."%</center></h2>
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
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin transparent">
              <div class="row">
                <div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Today’s Collection</p>
                      <p class="fs-30 mb-2"><?php echo $retValTodaysCollection; ?> kg</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Previous Days Collection</p>
                      <p class="fs-30 mb-2"><?php echo $PerPreviousCollection; ?>% 
					  <?php if($PerPreviousCollection<=0){?>
					  <img src="<?php echo $settingValueCollectionPointImagePathOther.$settingValueuptriangle;?>"/>
					  <?php }else{?>
					   <img src="<?php echo $settingValueCollectionPointImagePathOther.$settingValuedowntriangle;?>"/>
					   <?php }?></p>
                      <p><?php echo number_format($retValPreviousCollection,2); ?> kg (-1 day collection)</p>
                    </div>
                  </div>
                </div>
				<div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Weekly Collection</p>
                      <p class="fs-30 mb-2"><?php echo $PerPrevious7Collection; ?>%
					  <?php if($PerPrevious7Collection<=0){?>
					  <img src="<?php echo $settingValueCollectionPointImagePathOther.$settingValueuptriangle;?>"/>
					  <?php }else{?>
					   <img src="<?php echo $settingValueCollectionPointImagePathOther.$settingValuedowntriangle;?>"/>
					   <?php }?></p>
                      <p><?php echo number_format($retValPrevious7DaysCollection,2); ?> kg (7 days avg.)</p>
                    </div>
                  </div>
                </div>
				<div class="col-md-3 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Monthly Collection</p>
                      <p class="fs-30 mb-2"><?php echo $PerPrevious30Collection; ?>%
					  <?php if($PerPrevious30Collection<=0){?>
					  <img src="<?php echo $settingValueCollectionPointImagePathOther.$settingValueuptriangle;?>"/>
					  <?php }else{?>
					   <img src="<?php echo $settingValueCollectionPointImagePathOther.$settingValuedowntriangle;?>"/>
					   <?php }?>  </p>
                      <p><?php echo number_format($retValPreviousMonthlyCollection,2); ?> kg (30 days avg.)</p>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Daily Waste Collection</p>
                  <div class="d-flex flex-wrap mb-5">
                  </div>
                  <canvas id="order-chart"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <div class="d-flex justify-content-between">
                  <p class="card-title">Monthly Waste Collection</p>
                 </div>
                  <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
                  <canvas id="sales-chart"></canvas>
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
  <!--<script src="../assets/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="../assets/js/dataTables.select.min.js"></script>-->

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../assets/js/off-canvas.js"></script>
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/template.js"></script>
  <script src="../assets/js/settings.js"></script>
  <script src="../assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!--<script src="../assets/js/dashboard.js"></script>-->
  <script src="../assets/js/Chart.roundedBarCharts.js"></script>
  <!-- End custom js for this page-->
  <script>
	$(document).ready(function(){
	getMonthlyCollectionData();
	getDayCollectionData();
		
});
function getDayCollectionData(){
	$.ajax({
			type:"POST",
			url:"apigetWasteCollectionDays.php",
			data:{},
			success:function(response){
				var json = JSON.parse(response);
				var days = [];
				var quantity = [];
				var demo = [];
				json.forEach((item) => {
					days.push(item.datadays);
					quantity.push(item.dataquantity);
				});
				console.log(days);
				console.log(quantity);
				//--
				var areaData = {
				labels: days,
				datasets: [
				  {
					data: quantity,
					borderColor: [
					  '#4747A1'
					],
					borderWidth: 2,
					fill: false,
					label: "Quantity"
				  }
				]
			  };
			  var areaOptions = {
				responsive: true,
				maintainAspectRatio: true,
				plugins: {
				  filler: {
					propagate: false
				  }
				},
				scales: {
				  xAxes: [{
					display: true,
					ticks: {
					  display: true,
					  padding: 10,
					  fontColor:"#6C7383"
					},
					gridLines: {
					  display: true,
					  drawBorder: false,
					  color: 'transparent',
					  zeroLineColor: '#eeeeee'
					}, 
					scaleLabel: {
					  display: true,
					  labelString: 'Days'
					}
				  }],
				  yAxes: [{
					display: true,
					ticks: {
					  display: true,
					  autoSkip: false,
					  maxRotation: 0,
					  padding: 18,
					  fontColor:"#6C7383"
					},
					gridLines: {
					  display: true,
					  color:"#f2f2f2",
					  drawBorder: false
					}, 
					scaleLabel: {
					  display: true,
					  labelString: 'Quantity'
					}
				  }]
				},
				legend: {
					display: false
				},
				tooltips: {
				  enabled: true
				},
				elements: {
				  line: {
					tension: .35
				  },
				  point: {
					radius: 0
				  }
				}
			  }
			  var revenueChartCanvas = $("#order-chart").get(0).getContext("2d");
			  var revenueChart = new Chart(revenueChartCanvas, {
				type: 'line',
				data: areaData,
				options: areaOptions
			  });
				//--
				
			}
		});	  
}
function getMonthlyCollectionData(){
		$.ajax({
			type:"POST",
			url:"apigetWasteCollectionMonthly.php",
			data:{},
			success:function(response){
				var json = JSON.parse(response);
				var months = [];
                var sales = [];
				json.forEach((item) => {
					months.push(item.month.substring(0, 3) +" 22");
					sales.push(item.sum);
				});
				var chartdata = {
				labels: months,
				datasets: [
				{
					label: 'Monthly Collection',
					backgroundColor: [
						'rgba(244, 67, 54, 0.2)',
						'rgba(233, 30, 99, 0.2)',
						'rgba(156, 39, 176, 0.2)',
						'rgba(103, 58, 183, 0.2)',
						'rgba(63, 81, 181, 0.2)',
						'rgba(33, 150, 243, 0.2)',
						'rgba(3, 169, 244, 0.2)',
						'rgba(0, 188, 212, 0.2)',
						'rgba(76, 175, 80, 0.2)',
						'rgba(139, 195, 74, 0.2)',
						'rgba(205, 220, 57, 0.2)'
					],
					borderColor: [
						'rgba(244, 67, 54,1)',
						'rgba(233, 30, 99, 1)',
						'rgba(156, 39, 176, 1)',
						'rgba(103, 58, 183, 1)',
						'rgba(63, 81, 181, 1)',
						'rgba(33, 150, 243, 1)',
						'rgba(3, 169, 244, 1)',
						'rgba(0, 188, 212, 1)',
						'rgba(76, 175, 80, 1)',
						'rgba(139, 195, 74, 1)',
						'rgba(205, 220, 57, 1)'
					],
					borderWidth: 1,
					fill: false,
					data: sales
				}]};
		
				var options = {
					scales: {
						xAxes: [{
						scaleLabel: {
						  display: true,
						  labelString: 'Months'
						}
					  }],
						yAxes: [{
							ticks: {
							beginAtZero: true
							}, 
							scaleLabel: {
							  display: true,
							  labelString: 'Quantity'
							}
						}]
					},
					legend: {
						display: false
					},
					elements: {
						point: {
							radius: 0
						}
					}
				};
		
				var graphTarget = $("#sales-chart").get(0).getContext("2d");
		
				var barGraph = new Chart(graphTarget, {
				type: 'bar',
				data: chartdata,
				options: options
				});
			}
		});
	}
  </script>
</body>

</html>

