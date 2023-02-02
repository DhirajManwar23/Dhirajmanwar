<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$StartDate=$_REQUEST["StartDate"];
$EndDate=$_REQUEST["EndDate"];
$Ward=$_REQUEST["Ward"];
?>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste |Download Weekly Report </title>
  <!-- plugins:css -->
 <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />
<link rel="stylesheet" href="../assets/css/custom/style.css" />
</head>

<body>

      
					 <div class="table-responsive" id="printableArea">
						<table id="tableData" class="table printtbl">
							

						</table>
					  </div>
                      <br>
					  <div class="center-text" id="Exl">
					  <button type="submit" onclick="printDiv('printableArea')"class="btn btn-info btn-icon-text">Print <i class="ti-printer btn-icon-append"></i></button>
					 <button type="submit" onclick="exportToExcel('printableArea')" class="btn btn-success btn-icon-text">Download to Excel <i class="ti-import"></i></button>
					 
					 </div> 
<!-- plugins:js -->
<!-- plugins:js -->
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<!-- endinject -->
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/custom/jquery.table2excel.min.js">
</script>
<!-- endinject -->
<!-- End plugin js for this page -->
<script src="../assets/vendors/chart.js/Chart.min.js"></script>
<script src="../assets/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="../assets/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<!-- inject:js -->

<!-- Custom js for this page-->
<script src="../assets/js/dashboard.js"></script>
<script src="../assets/js/Chart.roundedBarCharts.js"></script>
<script src="../assets/js/chart.js"></script>
<!-- End custom js for this page-->

<script src=
"../assets/js/custom/jquery.table2excel.min.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	showData();
	buildReports();
});
const d = new Date();
				let year = d.getFullYear();
				var yearadd = year + 1;
var StartDate ='<?php echo $StartDate; ?>';
var EndDate='<?php echo $EndDate; ?>';
var Ward='<?php echo $Ward; ?>';
function showData(){

	$.ajax({
		type:"POST",
		url:"apiWeeklyDownloadData.php",
		data:{year:year,StartDate:StartDate,EndDate:EndDate,Ward:Ward},
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

function getColor() {
	var r = Math.floor(Math.random() * 200);
	var g = Math.floor(Math.random() * 200);
	var b = Math.floor(Math.random() * 200);
	return 'rgb(' + r + ', ' + g + ', ' + b + ')';
}
function buildReports() {

	var reportType = "Weekly";
	var startDate = StartDate;
	var endDate = EndDate;
		
	var request = {
		reportType: reportType,
		startDate: startDate,
		endDate: endDate
	};

	$.post("api-report-type-data.php", request, function(response) {
		console.log(response);
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
	var DonutChart =new Chart(donutCtx, {
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
    var DonutChartImg=DonutChart.toBase64Image();
	alert(DonutChartImg);
}
var DonutChart="";
function buildLineChart(lineChartData) {
	var ctx = document.getElementById("line_chart").getContext("2d");
	var daysArray = [];
	var quantityArray = [];
	lineChartData.forEach((item) => {
		quantityArray.push(item.quantity);
		daysArray.push(item.date);
	});
	var LineChart=new Chart(ctx, {
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
			legend: {
        display: false
    },
			scales: {
				y: {
					beginAtZero: true
				}
			},
	
		}
	});
	
	var LineChartImg=LineChart.toBase64Image();
}





function showDataEntry(entry_date){

	const myArray = entry_date.split("-");
    let year = myArray[0];
	
	window.location.href = "pgMonthlyMixWasteData.php?year="+year;
	
}
function printDiv(printableArea) {
    var printContents = document.getElementById(printableArea).innerHTML;
    var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}

function exportToExcel(tableId){
	
	let tableData = document.getElementById(tableId).outerHTML;
	tableData = tableData.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
    tableData = tableData.replace(/<input[^>]*>|<\/input>/gi, ""); //remove input params
	

	let a = document.createElement('a');
	a.href = `data:application/vnd.ms-excel, ${encodeURIComponent(tableData)}`
	a.download = 'downloaded_file_' + getRandomNumbers() + '.xls'
	a.click()
}
function getRandomNumbers() {
	let dateObj = new Date()
	let dateTime = `${dateObj.getHours()}${dateObj.getMinutes()}${dateObj.getSeconds()}`

	return `${dateTime}${Math.floor((Math.random().toFixed(2)*100))}`
}

</script>
</body>
</html>