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
	<div class="main-panel">        
        <div class="content-wrapper">
	     <div class="row">
		
					
           
			
				<table id="tableData" class="table">
				<div class="card">
				  
					<div class="card-body">
				
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12" style="text-align:center;">
								<img src="https://is1-2.housingcdn.com/4f2250e8/393854a57c75b6a68969c00d5a2d7416/v0/fs/aura_zephyr-wadala-mumbai-aura_creators.jpeg" style="width:100px;height:100px;" class="img-sm mb-3 " width="100%">
							</div>
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12">
								<strong>raheja princess dadar </strong><a href="pgMaterialInward.php?type=In%20Process&amp;po_id=9"></a><p>Location : dadar</p>
								 <p><i class="ti-map-alt"></i> Address: Raheja princess , SK Bole Road, Chandrakant Dhuru Wadi, Dadar West, Mumbai </p>
								 <p><i class="ti-pin"></i> K-Ward</p>
								 
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