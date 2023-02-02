<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Yearly Report</title>
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
					  <p class="card-title">Yearly Data Entry</p>
					 </div>
					  <canvas id="lineChart1"></canvas>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <div class="d-flex justify-content-between">
                  <p class="card-title">Yearly Segregation</p>
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
				
                  <h4 class="card-title">Yearly Report
				   <button type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="location.href='pgDownloadYearlydata.php?year='"><i class="ti-import"></i></button>
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
<script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/vendors/chart.js/Chart.min.js"></script>
<script src="../assets/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="../assets/js/dataTables.select.min.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/dashboard.js"></script>
<script src="../assets/js/Chart.roundedBarCharts.js"></script>
<script src="../assets/js/chart.js"></script>
<!-- endinject -->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	showData();
});

$(document).ready(function(){
	getMixWasteDailyLineGraph();
	getDailyCollectionData();
});
function getMixWasteDailyLineGraph(){
	$.ajax({
			type:"POST",
			url:"apiYearlyLineChart.php",
			data:{},
				success:function(response){
				console.log(response);
				var json = JSON.parse(response);
				var days_array= [];
				var quantity_array= [];
                var arrdata = [];
				json.forEach((item) => {
					quantity_array.push(item.quantity);
					days_array.push(item.entry_date);
				});
				
				var ctx = document.getElementById("lineChart1").getContext("2d");
				new Chart(ctx, {
						type: 'line',
						data: {
						  labels: [2022,2023,2024,2025,2026],
						  datasets: [{
							label: 'Data Entry',
							borderColor: '#57c7d4',
							fill: false,
							data: quantity_array,
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
		});
} 


function getDailyCollectionData(){
	$.ajax({
			type:"POST",
			url:"apiYearlyPieChart.php",
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
		url:"apiYearlyMixWasteData.php",
		data:{},
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
function showDataEntry(entry_date){

	const myArray = entry_date.split("-");
    let year = myArray[0];
	
	window.location.href = "pgMonthlyMixWasteData.php?year="+year;
	
}
function exportTableToExcel(){
    $("#tableData").table2excel({
        filename: "Collection_Report.xls"
    });
}
function printDiv(printableArea) {
    var printContents = document.getElementById(printableArea).innerHTML;
    var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}
</script>
</body>
</html>