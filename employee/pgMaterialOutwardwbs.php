<?php 
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	require "function.php";
	$sign=new Signup();
	$outwardid=$_REQUEST["id"];
	$qry="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='WBS' and outward_id='".$outwardid."' ORDER BY outward_id ASC";
	$retVal = $sign->SelectF($qry,"cnt");

	$qry1="SELECT COUNT(*) as cnt from tw_material_outward_wbs WHERE outward_id='".$outwardid."' ORDER BY outward_id ASC";
	$retVal1 = $sign->SelectF($qry1,"cnt");

	if($retVal>0 || $retVal1>0){
		$disabled="disabled";
	}
	else{
		$disabled="";
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Weigh Bridge Slip</title>
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
				
                  <h4 class="card-title">Weigh Bridge Slip</h4>
					<button type="button" class="btn btn-link btn-rounded btn-fw float-right" <?php echo $disabled;?> onclick="location.href='pgMaterialOutwardWBSForm.php?type=add&id&outwardid=<?php echo $outwardid; ?>';"><i class="icon-plus"></i> Create New Record</button>
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
$(document).ready(function(){
	showData();
});
function showData(){
		$.ajax({
			type:"POST",
			url:"apiGetViewWbs.php",
			data:{outwardid:'<?php echo $outwardid; ?>'},
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
function editRecord(id){
	window.location.href = "pgMaterialOutwardWBSForm.php?type=edit&id="+id+"&outwardid=<?php echo $outwardid; ?>";
}
function DocumentRecord(id){
	window.location.href = "pgMaterialOutwardWBSForm.php?id="+id;
}
function deleteRecord(id){
	// var ans= confirm("are you sure to delete this record?");
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
		if(confirmed==true){
			var valtablename="tw_material_outward_wbs";
			
			$.ajax({
					type:"POST",
					url:"apiDeleteData.php",
					data:{id:id,tablename:valtablename},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							showData();
							showAlert("Record Deleted Successfully","","success");
						}
						else{
							showAlert("Something Went Wrong. Please Try After Sometime","","warning");
						}
						
					}
				});
		}
	});
}
</script>
</body>

</html>