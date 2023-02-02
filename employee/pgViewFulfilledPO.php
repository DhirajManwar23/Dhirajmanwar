<?php 
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$requesttype = $_REQUEST["type"];	
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Fulfilled PO </title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
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
            <div class="col-md-12 grid-margin stretch-card">
			
              <div class="card">
			  
                <div class="card-body">
				
					<h4 class="card-title">Fulfilled PO 
						<button type="button" class="btn btn-success btn-icon-text float-right" onClick="CSV();"> Export to Excel
						<i class="ti-receipt btn-icon-append"></i></button>
						<button type="button" class="btn btn-info btn-icon-text float-right" onclick="printDiv('tableData');"> Print
						<i class="ti-printer btn-icon-append"></i></button>
					</h4><br>
					 <div class="table-responsive">
						<table id="tableData" class="table table-bordered">
						  
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
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetAlert2.min.js"></script>
<script type='text/javascript'>
var requesttype = "<?php echo $requesttype; ?>";
$(document).ready(function(){
	showData();
});

function showData(){
	$.ajax({
		type:"POST",
		url:"apiGetViewFulfilledPO.php",
		data:{requesttype:requesttype},
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
function CSV(){
		$.ajax({
			type:"POST",
			url:"EPRSExportDataConsolidateCSV.php",
			data:{},
			success:function(response){
				console.log(response);
				if($.trim(response)=="success"){
					$(location).attr('href','EPRSExportDataConsolidateCSV.csv');
				} 
			}
		});
}
function printDiv(tableData) {
    var printContents = document.getElementById(tableData).innerHTML;
    var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}
</script>
</body>

</html>