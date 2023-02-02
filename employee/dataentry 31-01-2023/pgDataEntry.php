<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$WardnameQry="SELECT id,ward_name FROM `tw_ward_master`";
$Wardname = $sign->FunctionOption($WardnameQry,"",'ward_name',"id");
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Data Entry </title>
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
<link rel="stylesheet" href="../assets/css/custom/style.css" />
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
	   <!-- ==============MODAL END ================= --> <!-- ==============MODAL START ================= -->
  <div class="modal fade" id="ViewInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Daily Report Info</h5>
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
	  
	  
	  
      <div class="main-panel">        
        <div class="content-wrapper">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
					<div class="row"> 
					<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
				        <label for="txtNetQuantity">Start Date<code>*</code></label>
						 <input type="date" class="form-control" id="txtStartdate" max='<?php echo $cur_date; ?>'  value='' placeholder="Start Date" />
						</div> 
						<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
				        <label for="txtNetQuantity">End Date<code>*</code></label>
						 <input type="date" class="form-control" id="txtEnddate" max='<?php echo $cur_date; ?>'  value='' placeholder="end Date" />
						</div>
						<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
				        <label for="txtNetQuantity">Report Type<code>*</code></label>
						 <select  class="form-control" id="ReportType"  placeholder="Fulfilment cycle">
						<option value="">Choose...</option>
						<option value="Daily">Daily</option>
						<option value="Weekly">Weekly</option>
						<option value="Monthly">Monthly</option>
						<option value="Yearly">Yearly</option>
						
					  </select>
						</div>
						<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12 ">
							<label for="txtWard">Select Ward<code>*</code></label>
							<select  class="form-control" id="txtWard"   placeholder="Fulfilment cycle">
							<option value="">Choose...</option>
							<?php echo $Wardname; ?>
							
						  </select>
					</div>
						
					
					<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				       <button type="submit" class="btn btn-success btn-icon-text" onclick="genrateReport();">Genrate Report</button>
				   </div>	
			    </div>
                </div>
              </div>
            </div>
			</div>
		<div class="row" id="Chart">
			<div class="col-lg-6 col-md-6 col-sm-12 col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
					 <div class="d-flex justify-content-between">
					  <p class="card-title">Inward dry waste collection Data</p>
					 </div>
					  <canvas id="line_chart"></canvas>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <div class="d-flex justify-content-between">
                  <p class="card-title">Inward dry waste collection Segregation</p>
                 </div>
                  <canvas id="donut_chart"></canvas>
                </div>
              </div>
            </div>
			</div>
          <div class="row" id="Report">
            <div class="col-md-12 grid-margin stretch-card">
			
              <div class="card">
			  
                <div class="card-body">
				
                  <h4 class="card-title">Inward Dry Waste Collection Record<span id="showType" ></span><button type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="ShowReport();"><i class="ti-import"></i></button></h4>
					
				  
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
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<!-- endinject -->
<!-- End plugin js for this page -->
<script src="../assets/vendors/chart.js/Chart.min.js"></script>
<script src="../assets/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="../assets/js/dataTables.select.min.js"></script>
<!-- inject:js -->
<!-- Custom js for this page-->
<script src="../assets/js/dashboard.js"></script>
<script src="../assets/js/Chart.roundedBarCharts.js"></script>
<script src="../assets/js/chart.js"></script>
<!-- End custom js for this page-->

<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	 $("#Chart").hide();
	 $("#Report").hide();
});
const d = new Date();
				let year = d.getFullYear();
				var yearadd = year + 1;
				$(document).ready(function() {
				});
				var myBarChartMonthly = "";


function buildReports() {

	var reportType = $('#ReportType').val();
	var startDate = $('#txtStartdate').val();
	var endDate = $('#txtEnddate').val();
	var Ward=$("#txtWard").val()	
	var request = {
		reportType: reportType,
		startDate: startDate,
		endDate: endDate,
		Ward : Ward
	};

	$.post("api-report-type-data.php", request, function(response) {
		console.log(response);
		buildLineChart(response.line_chart_data);
		buildDonutChart(response.donut_chart_data);
	});
}
var mypieChart =null;
function buildDonutChart(donut_chart_data) {
	var wasteTypesArray = [];
	var wasteQuantityArray = [];
	var colorsArray = [];
	donut_chart_data.forEach((item) => {
		wasteQuantityArray.push(item.quantity);
		wasteTypesArray.push(item.waste_type);
		colorsArray.push(getColor());
	});	
	// donut_chart_data_seg.forEach((item) => {
		// wasteQuantityArray.push(item.quantity);
		// wasteTypesArray.push(item.waste_type);
		// colorsArray.push(getColor());
	// });
	if(mypieChart !=null){
        mypieChart .destroy();
    }
	var donutCtx = document.getElementById("donut_chart").getContext("2d");
	mypieChart=new Chart(donutCtx, {
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
            display: false,
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
var mylineChart =null;
function buildLineChart(lineChartData) {
	var ctx = document.getElementById("line_chart").getContext("2d");
	var daysArray = [];
	var quantityArray = []
	lineChartData.forEach((item) => {
		quantityArray.push(item.quantity);
		daysArray.push(item.date);
	});
	 
	// line_chart_data_seg.forEach((item) => {
		// quantityArray.push(item.quantity);
		// daysArray.push(item.date);
	// });
	if(mylineChart !=null){
        mylineChart .destroy();
    }
	mylineChart=new Chart(ctx, {
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
function genrateReport(){
	var StartDate =$("#txtStartdate").val();
	var EndDate=$("#txtEnddate").val();
	$("#ReportType").val();
	var ReportType=$("#ReportType").val()
	var Ward=$("#txtWard").val()
	 buildReports();
	if(ReportType=="Weekly"){
		showWeeklyData(StartDate,EndDate,Ward)
		  $("#showType").text("[Weekly in kgs]");
		
	}
	else if(ReportType=="Daily"){
		showData(StartDate,EndDate,Ward)
		$("#showType").text("[Daily in kgs]");
	}
	else if(ReportType=="Yearly"){
		showYearlyData(StartDate,EndDate,Ward)
		$("#showType").text("[Yearly in kgs]");
	}
	else if(ReportType=="Monthly"){
		
		showMonthlyData(StartDate,EndDate,Ward);
		$("#showType").text("[Monthly in kgs]");
	}
	
}
function showMonthlyData(StartDate,EndDate,Ward){
	$.ajax({
		type:"POST",
		url:"apiMonthlyMixWasteData.php",
		data:{year:year,StartDate:StartDate,EndDate:EndDate,Ward:Ward},
		success:function(response){
			console.log(response);
			$("#tableData").html(response);
            $("#Chart").show();
			$("#Report").show();
		}	
	});

}
function showWeeklyData(StartDate,EndDate,Ward){
	$.ajax({
		type:"POST",
		url:"apiWeeklyMixWasteData.php",
		data:{year:year,StartDate:StartDate,EndDate:EndDate,Ward:Ward},
		success:function(response){
			console.log(response);
			$("#tableData").html(response);
            $("#Chart").show();
			$("#Report").show();
			
		}
	});
}

function ViewMonthlyRecord(id,date,Ward){
	window.open("pgDailyEntryReport.php?id="+id+"&date="+date+"&Ward="+Ward, '_blank');

}  


function ShowReport(){
	var StartDate =$("#txtStartdate").val();
	var EndDate=$("#txtEnddate").val();
	var ReportType=$("#ReportType").val()
	var Ward=$("#txtWard").val()

	 if(ReportType=="Yearly"){
		window.open('pgDownloadYearlydata.php?year=&StartDate='+StartDate+'&EndDate='+EndDate+"&Ward="+Ward, '_blank');
		
	}else if(ReportType=="Weekly"){
		window.open('pgDownloadWeeklydata.php?year=&StartDate='+StartDate+'&EndDate='+EndDate+"&Ward="+Ward, '_blank');

		
	}else if(ReportType=="Daily"){
		window.open('pgDownloadDailydata.php?year=&StartDate='+StartDate+'&EndDate='+EndDate+"&Ward="+Ward, '_blank');
		
		
	}
	else if(ReportType=="Monthly"){
		window.open('pgDownloadMonthlydata.php?year=&StartDate='+StartDate+'&EndDate='+EndDate+"&Ward="+Ward, '_blank');
	
	}
}



//Yearly Start
function showYearlyData(StartDate,EndDate,Ward){
	$.ajax({
		type:"POST",
		url:"apiYearlyMixWasteData.php",
		data:{StartDate:StartDate,EndDate:EndDate,Ward:Ward},
		success:function(response){
			$("#Chart").show();
			$("#Report").show();
			console.log(response);
			$("#tableData").html(response);

			$('#tableData').DataTable({
				
			});
		}
	});
}




//yearly END
function showDataEntry(entry_date){
 
	
    $.ajax({
		type:"POST",
		url:"apishowMixWasteData.php",
		data:{entry_date:entry_date},
		success:function(response){
			console.log(response);
			
			$("#tableData").html(response);

			$('#tableData').DataTable({
				
			});
		}
	});	
}
function showData(StartDate,EndDate,Ward){
	
	$.ajax({
		type:"POST",
		url:"apiGetViewDataEntry.php",
		data:{StartDate:StartDate,EndDate:EndDate,Ward:Ward},
		success:function(response){
			console.log(response);
			$("#Chart").show();
			$("#Report").show();
			$("#tableData").html(response);

			$('#tableData').DataTable({
				
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
}

function editRecord(id,date1){
	//alert(date1);
	window.location.href = "pgDataEntryForm.php?type=edit&id="+id+"&date="+date1;

}
function ViewDailyRecord(id,name,startDate,EndDate){
	var Ward=$("#txtWard").val();
	$.ajax({
		type:"POST",
		url:"apiDailyReport.php",
		data:{requestid:id,name:name,Ward:Ward,StartDate:startDate,EndDate:EndDate},
		//dataType: 'JSON',
		success:function(response){
			
			$("#MaterialInfo").html(response);
			// $('#MaterialInfo').DataTable({
				// "responsive":true,
				// "destroy":true,
				// "bPaginate":true,
				// "bLengthChange":true,
				// "bFilter":true,
				// "bSort":true,
				// "bInfo":true,
				// "retrieve": true,
				// "bAutoWidth":false,
				// "scrollXInner":true
			// });
			
		}
	});
	
	
	showModalViewInfo()
	

}
function DeleteRecord(name,date){
		showConfirmAlert('Confirm action!', 'Do you want to delete the selected record?','question', function (confirmed) {
			if (confirmed) {
				deleteYes(name,date);
			}
		});
}
function deleteYes(name,date)
{
		$.ajax({
			type:"POST",
			url:"apiDeleteManualData.php",
			data:{name:name,date:date},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					//showData();
					showAlertRedirect("Success","Record Deleted Successfully","success","pgDataEntry.php");
				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
				}
				
			}
	   });
}

</script>
</body>
</html>