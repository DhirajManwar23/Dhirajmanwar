<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
require "function.php";
$sign=new Signup();
$id=$_REQUEST["id"];

	
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Material Outward Documents</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
				<div class="card-body">
					<h4 class="card-title">Lorry Receipt</h4>
					<button type="button" class="btn btn-success" id="btnAttach" onclick="location.href='pgMaterialOutwardAttachDocument.php?type=LorryReceipt&id=<?php echo $id; ?>'">Attach</button>
					<button type="button" class="btn btn-success" id="btnGenerate" onclick="location.href='pgMaterialOutwardwbs.php?id=<?php echo $id; ?>'">Generate</button>
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
				<div class="card-body">
					<h4 class="card-title">Certificate of Analysis</h4>
					<button type="button" class="btn btn-success" id="btnAttach" onclick="location.href='pgMaterialOutwardAttachDocument.php?type=COA&id=<?php echo $id; ?>'">Attach</button>
					<button type="button" class="btn btn-success" id="btnGenerate" onclick="changepassword();">Generate</button>
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
				<div class="card-body">
					<h4 class="card-title">Packing List</h4>
					<button type="button" class="btn btn-success" id="btnAttach" onclick="location.href='pgMaterialOutwardAttachDocument.php?type=PackingList&id=<?php echo $id; ?>'">Attach</button>
					<button type="button" class="btn btn-success" id="btnGenerate" onclick="changepassword();">Generate</button>
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
				<div class="card-body">
					<h4 class="card-title">Eway</h4>
					<button type="button" class="btn btn-success" id="btnAttach" onclick="location.href='pgMaterialOutwardAttachDocument.php?type=Eway&id=<?php echo $id; ?>'">Attach</button>
					<button type="button" class="btn btn-success" id="btnGenerate" onclick="location.href='pgMaterialOutwardEway.php?id=<?php echo $id; ?>'">Generate</button>
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
				<div class="card-body">
					<h4 class="card-title">Invoice</h4>
					<button type="button" class="btn btn-success" id="btnAttach" onclick="location.href='pgMaterialOutwardAttachDocument.php?type=Invoice&id=<?php echo $id; ?>'">Attach</button>
					<button type="button" class="btn btn-success" id="btnGenerate" onclick="location.href='pgTaxInvoice.php?id=<?php echo $id; ?>'">Generate</button>
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
				<div class="card-body">
					<h4 class="card-title">Weigh Bridge Slip</h4>
					<button type="button" class="btn btn-success" id="btnAttach" onclick="location.href='pgMaterialOutwardAttachDocument.php?type=WBS&id=<?php echo $id; ?>'">Attach</button>
					<button type="button" class="btn btn-success" id="btnGenerate" onclick="location.href='pgMaterialOutwardwbs.php?id=<?php echo $id; ?>'">Generate</button>
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
				<div class="card-body">
					<h4 class="card-title">Photos</h4>
					<button type="button" class="btn btn-success" id="btnAttach" onclick="location.href='pgMaterialOutwardAttachDocument.php?type=Photos&id=<?php echo $id; ?>'">Attach</button>
					<button type="button" class="btn btn-success" id="btnGenerate" onclick="changepassword();">Generate</button>
                </div>
              </div>
            </div>
          </div>
		  <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
				<div class="card-body">
					<h4 class="card-title">Certificate of Origin</h4>
					<button type="button" class="btn btn-success" id="btnAttach" onclick="location.href='pgMaterialOutwardAttachDocument.php?type=COO&id=<?php echo $id; ?>'">Attach</button>
					<button type="button" class="btn btn-success" id="btnGenerate" onclick="changepassword();">Generate</button>
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
   <script src="../assets/js/custom/sweetalert2.min.js"></script>
	<script src="../assets/js/custom/sweetAlert.js"></script>
  <script src="../assets/css/jquery/jquery.min.js"></script>
  <script type='text/javascript'>
$(document).ready(function(){
});
</script>
</body>

</html>