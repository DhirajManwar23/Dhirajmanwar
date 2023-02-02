<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Material Inward</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
    <script src="../assets/js/custom/twCommonValidation.js"></script>
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
	    <!-- ==============MODAL START ================= -->
  <div class="modal fade" id="modalRejectedReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Reason of Rejection</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body">
			<div class="form-group row">
				<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<div class="form-group row">	
						<div class="card-body" >
							<label class="col-sm-12">Enter rejection reason</label>
							<textarea class="form-control" id="txtInputReason" maxlength="5000"  placeholder="Reason of Rejection"></textarea>
						</div>
					</div>
							  
				</div>
			</div>	
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal();">Close</button>
			<button type="button" class="btn btn-success" id="Status" onclick="adddataReject();">Submit</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
  <!-- ==============MODAL START ================= -->
  <div class="modal fade" id="modalViewReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Reason of Rejection</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body">
			<div class="form-group row">
				<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<div class="form-group row">	
						<div class="card-body" >
							<label class="col-sm-12">Rejection reason</label>
							<textarea class="form-control" id="txtViewReason" maxlength="5000"  placeholder="Reason of Rejection"></textarea>
						</div>
					</div>
							  
				</div>
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
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<!--<button type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="location.href='pgPOForm.php?type=add&id='"><i class="icon-plus"></i> Create New Record</button>-->
					<div class="mt-4 py-2 border-top border-bottom">
                        <ul class="nav profile-navbar">
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idInprocess" onclick="showData('<?php echo $settingValuePendingStatus;?>');">
                              <i class="ti-timer"></i>
                              Pending
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idCompleted" onclick="showData('<?php echo $settingValueCompletedStatus;?>');">
                              <i class="ti-target"></i>
                              Completed
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idAll" onclick="showData('');">
                              <i class="ti-notepad"></i>
                              All
                            </a>
                          </li>
                        </ul>
                      </div>
					  
				</div>
			</div><br>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
			
              <div class="card">
			  
                <div class="card-body">
				
                  <h4 class="card-title">Accounts</h4>
					<!--<button type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="location.href='pgMaterialInwardForm.php?type=add&id=';"><i class="icon-plus"></i> Create New Record</button>-->
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script type='text/javascript'>
var valsettingValuePendingStatus='<?php echo $settingValuePendingStatus; ?>';
var valsettingValueCompletedStatus='<?php echo $settingValueCompletedStatus;?>';
$(document).ready(function(){
	showData('<?php echo $settingValuePendingStatus;?>');
	$("#idInprocess").addClass("active");
	$("#idCompleted").removeClass("active");
	$("#idAll").removeClass("active");
});
function showData(id){
	if(id==valsettingValuePendingStatus){
		$("#idInprocess").addClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
		
	}
	else if(id==valsettingValueCompletedStatus){
		
		$("#idInprocess").removeClass("active");
		$("#idCompleted").addClass("active");
		$("#idAll").removeClass("active");
	}
	else{
		$("#idInprocess").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").addClass("active");
	}
	$.ajax({
		type:"POST",
		url:"apiGetViewMaterialOutwardAccount.php",
		data:{statustype:id},
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

function DocumentRecord(id){
	window.location.href = "pgTaxInvoiceDocuments.php?id="+id+"&voutward_id="+id;
}
function editRecord(id){
	window.location.href = "pgPaymentListSupplier.php?outward_id="+id;
}
</script>
</body>
</html>