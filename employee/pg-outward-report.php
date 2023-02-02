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
					<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				        <label for="txtNetQuantity">Start Date<code>*</code></label>
						 <input type="date" class="form-control" id="txtStartdate" max='<?php echo $cur_date; ?>'  value='' placeholder="Start Date" />
						</div> 
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				        <label for="txtNetQuantity">End Date<code>*</code></label>
						 <input type="date" class="form-control" id="txtEnddate" max='<?php echo $cur_date; ?>'  value='' placeholder="end Date" />
						</div>
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				        <label for="txtNetQuantity">Report Type<code>*</code></label>
						 <select  class="form-control" id="ReportType"  placeholder="Fulfilment cycle">
						<option value="">Choose...</option>
						<option value="Weekly">Weekly</option>
						<option value="Monthly">Monthly</option>
						<option value="Yearly">Yearly</option>
						
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
		
          <div class="row" id="Report">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Data Entry </h4>
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
});

function genrateReport(){
	var StartDate =$("#txtStartdate").val();
	var EndDate=$("#txtEnddate").val();
	$("#ReportType").val();
	var ReportType=$("#ReportType").val()
	 buildReports();
	if(ReportType=="Weekly"){
		
		
		
	}
	else if(ReportType=="Yearly"){
		showYearlyData(StartDate,EndDate)
		
	}
	else if(ReportType=="Monthly"){
		
		showMonthlyData(StartDate,EndDate);
		
	}
	
}
function showMonthlyData(StartDate,EndDate){
	$.ajax({
		type:"POST",
		url:"apiMonthlyOutwardReport.php",
		data:{year:year,StartDate:StartDate,EndDate:EndDate},
		success:function(response){
			console.log(response);
			$("#tableData").html(response);
            $("#Chart").show();
			$("#Report").show();
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

function ViewMonthlyRecord(id,date){
	window.location.href = "pgDailyEntryReport.php?id="+id+"&date="+date;
	
}  
//Yearly Start
function showYearlyData(StartDate,EndDate){
	$.ajax({
		type:"POST",
		url:"apiYearlyMixWasteData.php",
		data:{StartDate:StartDate,EndDate:EndDate},
		success:function(response){
			$("#Chart").show();
			$("#Report").show();
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
function showData(){
	$.ajax({
		type:"POST",
		url:"apiGetViewDataEntry.php",
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
function showModalViewInfo()
{	
	jQuery.noConflict();
	$("#ViewInfo").modal("show");
	
}
function closeModalViewReason() {
	
  $("#ViewInfo").modal("hide");
  location.reload();
}

function editRecord(id,date1){
	//alert(date1);
	window.location.href = "pgDataEntryForm.php?type=edit&id="+id+"&date="+date1;

}
function ViewDailyRecord(id,name){
	$.ajax({
		type:"POST",
		url:"apiDailyReport.php",
		data:{requestid:id,name:name},
		//dataType: 'JSON',
		success:function(response){
			
			$("#MaterialInfo").html(response);
			$('#MaterialInfo').DataTable({
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
					showData();
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