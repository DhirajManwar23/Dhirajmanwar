<?php 
session_start();
if(!isset($_SESSION["agent_id"])){
	header("Location:pgAgentLogin.php");
}	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$agent_id = $_SESSION["agent_id"];

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");

$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");

$qryAgentData="Select agent_name,agent_gender,agent_marital_status,agent_dob,agent_photo,mobilenumber,email,address_line_1,address_line_2,location,pincode,city,state,country,status from tw_agent_details where id='".$agent_id."'";
//$collection_point_name = $sign->SelectF($qry4,"collection_point_name");
$AgentData = $sign->FunctionJSON($qryAgentData);
$decodedJSON = json_decode($AgentData);
$agent_name = $decodedJSON->response[0]->agent_name;
$agent_gender = $decodedJSON->response[1]->agent_gender;
$agent_marital_status = $decodedJSON->response[2]->agent_marital_status;
$agent_dob = $decodedJSON->response[3]->agent_dob;
$agent_photo = $decodedJSON->response[4]->agent_photo;
$mobilenumber = $decodedJSON->response[5]->mobilenumber;
$email = $decodedJSON->response[6]->email;
$address_line_1 = $decodedJSON->response[7]->address_line_1;
$address_line_2 = $decodedJSON->response[8]->address_line_2;
$location = $decodedJSON->response[9]->location;
$pincode = $decodedJSON->response[10]->pincode;
$city = $decodedJSON->response[11]->city;
$state = $decodedJSON->response[12]->state;
$country = $decodedJSON->response[13]->country;
$status = $decodedJSON->response[14]->status;

$qry1="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValuePendingStatus."' and  collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntInprocess = $sign->Select($qry1);

$qry2="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueApprovedStatus."' and  collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntApproved = $sign->Select($qry2);

$qry3="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueAwaitingStatus."' and  collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntAwaiting = $sign->Select($qry3);

$qry4="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueCompletedStatus."' and  collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntCompleted = $sign->Select($qry4);

$qry6="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueRejectedStatus."' and collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntRejected = $sign->Select($qry6);

$qry5="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and  collection_date_time LIKE '%".$cur_date."%'";
$qryCntAll = $sign->Select($qry5);

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
	
	if($mobilenumber=="" && $email==""){
		$divCont3=0;
	}
	else{
		$divCont3=1;
	}

	if($agent_name==""){
		$divCont4=0;
	}
	else{
		$divCont4=1;
	}
	
	if($agent_photo==""){
		$divCont5=0;
	}
	else{
		$divCont5=1;
	}
	

	$Progressive = ($divCont2)+($divCont3)+($divCont4)+($divCont5);	
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
<title>Trace-Waste | Agent Dashboard</title>
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
            <div class="col-lg-9 col-md-9 col-sm-9 com-xs-9 col-9 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Welcome <?php echo $agent_name;?> <?php if($status==$settingValueVerifiedStatus){?><img src="<?php echo $CommonDataValueCommonImagePath.$VerifiedImage;?>"/> <?php }?></h3>
                </div>
              </div>
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
		
		 <div class="col-md-12 grid-margin transparent"> 
		  <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
            
			  <div class="card tale-bg">
                <div class="card-people mt-auto">
                  <img src="../assets/images/dashboard/people.svg" alt="people">
                  <div class="weather-info">
                    <div class="d-flex">
                      <div>
                        <h2 class="mb-0 font-weight-normal"><?php echo $qryCntAll; ?></h2>
                      </div>
                      <div class="ms-2">
                        <h4 class="location font-weight-normal">Collection</h4>
                        <h6 class="font-weight-normal">Total</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
			<div class="col-md-6 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Today’s Pending Collection</p>
                      <?php echo $qryCntInprocess; ?>
                     
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Today's Awaiting Collection</p>
                      <?php echo $qryCntAwaiting; ?>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Today's Completed Collection</p>
                     <?php echo $qryCntCompleted; ?>
                     
                    </div>
                  </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Today's Rejected Collection</p>
                      <?php echo $qryCntRejected; ?>
                     
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
           </div>
		   
		   
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <!--<h3 class="font-weight-bold">Welcome <?php //echo $agent_name; ?></h3>
                  <h6 class="font-weight-normal mb-0">All systems are running smoothly!</h6>-->
                </div>
                <div class="col-12 col-xl-4">
                 <div class="justify-content-end d-flex">
               
                 </div>
                </div>
              </div>
            </div>
          </div>
        
		  
		  <div class="row">
		  <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Daily Waste Collection</p>
                  <div class="d-flex flex-wrap mb-5">
                  </div>
                  <canvas id="order-chart"></canvas>
                </div>
              </div>
            </div>
            </div>
			
			 <div class="col-md-12 grid-margin stretch-card">
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
			url:"apigetAgentCollectionDays.php",
			data:{},
			success:function(response){
			console.log(response);
				//$("#barChart").html(response);
				var json = JSON.parse(response);
				var days = [];
				var quantity = [];
				var cnt = [];
				var Collectioncount = [];
				var demo = [];
				json.forEach((item) => {
					//days.push(item.datadays.substring(0, 3) +" 22");
					days.push(item.datadays);
					quantity.push(item.dataquantity);
					//quantity.push(item.datademo);
					
					cnt.push(item.datacount);
					Collectioncount.push(item.datacollectioncount);
				});
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
				  },
				  {
					data: Collectioncount,
					borderColor: [
					  '#F09397'
					],
					borderWidth: 2,
					fill: false,
					label: "Collectioncount"
				  }
				]   
				
				
			  } ;
			  /* var areaData = {
				labels: cnt,
				 datasets: [
				  {
					data: cnt,
					borderColor: [
					  '#4747A1'
					],
					borderWidth: 2,
					fill: false,
					label: "Collectioncount"
				  }
				]  
				
				
			  } ; */
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
			url:"apigetAgentWasteCollectionMonthly.php",
			data:{},
			success:function(response){
			//console.log(response);
				//$("#barChart").html(response);
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

