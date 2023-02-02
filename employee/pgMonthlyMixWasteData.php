<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$year=$_REQUEST["year"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Monthly Report</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
  <!-- ==============MODAL END ================= --> <!-- ==============MODAL START ================= -->
  <div class="modal fade" id="ViewInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Report Info</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body modal-body">
					
							<div class="form-group row">	
								<table class="printtbl" id="MaterialInfo">
								
								
																	  
						</table>
					</div>
					
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalViewReason();">Close</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->   
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
			<div class="col-lg-6 col-md-6 col-sm-12 col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
					
					 <div class="d-flex justify-content-between">
					  <p class="card-title">Monthly Data Entry</p>
					 </div>
					  <canvas id="lineChart1"></canvas>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <div class="d-flex justify-content-between">
                  <p class="card-title">Monthly Segregation</p>
                 </div>
                  <canvas id="epr-pieChart"></canvas>
                </div>
              </div>
            </div>
			</div>
		
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
              <div class="card">
			  
                <div class="card-body">
				<h4 class="card-title">Monthly Report 
				  <button type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="location.href='pgDownloadMonthlydata.php?year='"><i class="ti-import"></i></button>
				  </h4>
                  
					 <div class="table-responsive">
						<table id="tableData" class="table">
						  
						</table>
					  </div>
					  
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
<script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/custom/jquery.table2excel.min.js">
</script>
<!-- endinject -->
<script src="../assets/js/dashboard.js"></script>
<script src="../assets/js/Chart.roundedBarCharts.js"></script>
<script src="../assets/js/chart.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	showData();
});
$(document).ready(function(){
	showData();
	getMixWasteMonthlyGraph();
	getDailyCollectionData();
});
const d = new Date();
let year = d.getFullYear();
var yearadd = year+1;

var myBarChartMonthly = "";
var year3="<?php echo $year?>";
function getMixWasteMonthlyGraph(){
	$.ajax({
			type:"POST",
			url:"apiMixWasteDataEntryGraph.php",
			data:{},
			success:function(response){
				console.log(response);
				var json = JSON.parse(response);
                var arrdata = [];
				json.forEach((item) => {
					arrdata.push(item.sum);
				});
				
				var ctx = document.getElementById("lineChart1").getContext("2d");
				const datasetValue = []
				for (i = 0; i < arrdata.length; i++)
				{
					datasetValue[i] = {
						label: "Data Entry",
						borderColor: '#57c7d4',
						fill: false,
						data: arrdata[i]
					}
				}
				if(myBarChartMonthly!=""){
					myBarChartMonthly.destroy();
				}
				var xValues = ["April "+year,"May "+year,"June "+year,"July "+year,"Aug "+year,"Sep "+year,"Oct "+year,"Nov "+year,"Dec "+year,"Jan"+yearadd,"Feb"+yearadd,"Mar"+yearadd];
				myBarChartMonthly = new Chart(ctx, {
				  type: 'line',
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
function getDailyCollectionData(){
	$.ajax({
			type:"POST",
			url:"apiMonthlyPieChart.php",
			data:{},
			success:function(response){
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
				for (var i = 0; i < length; i++){
					numberArray.push(parseInt(arrdataquantity[i]));
				}
				var nameArray = arrdataname.map(function(e){return e.toString()});
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
			   
			   maintainAspectRatio:true,
			});
		}
	});
}





function showData(){
	$.ajax({
		type:"POST",
		url:"apiMonthlyMixWasteData.php",
		data:{year:year},
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
			});
		}
	});
}
function showModalViewInfo()
{	
	jQuery.noConflict();
	$("#ViewInfo").modal("show");
	
}
function closeModalViewReason() {
	
  $("#ViewInfo").modal("hide");
  location.reload();
}

function ViewMonthlyRecord(id,date){
	window.location.href = "pgDailyEntryReport.php?id="+id+"&date="+date;
	
}  

function showDataEntry(entry_date){
	
	$.ajax({
		type:"POST",
		url:"apishowMixWasteData.php",
		data:{entry_date:entry_date},
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
			});
		}
	});
	
}


</script>
</body>
</html>