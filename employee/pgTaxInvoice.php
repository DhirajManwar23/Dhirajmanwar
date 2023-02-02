<?php 
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$outward_id=$_REQUEST["id"];
	$qry="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
	$retVal = $sign->SelectF($qry,"cnt");

	$qry1="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
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
  <title>Trace Waste |Tax Invoice </title>
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
            <div class="col-md-12 grid-margin stretch-card">
			
              <div class="card">
			  
                <div class="card-body">
				
                  <h4 class="card-title">Tax Invoice</h4>
					<button type="button" class="btn btn-link btn-rounded btn-fw float-right" <?php echo $disabled;?> onclick="location.href='pgTaxInvoiceForm.php?type=add&id=&outward_id=<?php echo $outward_id; ?>';"><i class="icon-plus"></i> Create New Record</button>
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
  <script src="../assets/css/jquery/jquery.min.js"></script>
  <script src="../assets/js/custom/sweetAlert.js"></script>
  <script src="../assets/js/custom/sweetAlert2.min.js"></script>

   <script type='text/javascript'>

$(document).ready(function(){
	showData();
});
var valoutward_id="<?php echo $outward_id; ?>";

	function showData(){
		$.ajax({
			type:"POST",
			url:"apiTaxInvoice.php",
			data:{valoutward_id:valoutward_id},
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

function taxinvoice(id){
	window.location.href = "pgTaxInvoiceDocuments.php?id="+id+"&voutward_id="+valoutward_id;
}	
function editRecord(id){
	window.location.href = "pgTaxInvoiceForm.php?type=edit&id="+id+"&outward_id="+valoutward_id;
}

function deleteRecord(id){	
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed) {
		if (confirmed) {
			deleteYes(id);
		}
	});
}
function deleteYes(id)
{
	var valtablename="tw_tax_invoice";
		
		$.ajax({
				type:"POST",
				url:"apiDeleteData.php",
				data:{id:id,tablename:valtablename},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
						showData();
						showAlert("Success","Record Deleted Successfully","success");
					}
					else{
						showAlert("Warning","Something Went Wrong","warning");
					}
					
				}
			});
	}
</script>
</body>

</html>