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
$StartDate=$_REQUEST["StartDate"];
$EndDate=$_REQUEST["EndDate"];
$Ward=$_REQUEST["Ward"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste |Data Entry </title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
   <link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
					 <button type="submit" onclick="exportTableToExcel()" class="btn btn-success btn-icon-text">Download to Excel <i class="ti-import"></i></button>
					 
					 </div> 
               
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/custom/jquery.table2excel.min.js">

</script>
<!-- endinject -->
<script src=
"../assets/js/custom/jquery.table2excel.min.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	showData();
});
var year="<?php echo $year ?>";
var Ward='<?php echo $Ward; ?>';
function showData(){
	var StartDate ='<?php echo $StartDate; ?>';
	var EndDate='<?php echo $EndDate; ?>';

	$.ajax({
		type:"POST",
		url:"apiMonthlyDownload.php",
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

function exportTableToExcel(){
    $("#tableData").table2excel({
        filename: "Monthly_Data_Report.xls"
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