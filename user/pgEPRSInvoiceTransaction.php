<?php 
	session_start();
	if(!isset($_SESSION["companyusername"])){
		header("Location:pgLogin.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$po_id = $_REQUEST["po_id"];
	
	$amount = number_format(round($_REQUEST["amount"],0),2);
	$_SESSION["totalinvoiceamount"]=$amount;
	$invoiceno = $_SESSION["varInvoiceNo"];
	$Intinvoiceamount= str_replace(',', '', $amount);
	
	$balanceAmount=0.00;
	$QryTotal="SELECT IFNULL (sum(replace(amount, ',', '')), 0) as TotalAmt FROM tw_invoice_transaction_eprs WHERE po_id='".$po_id."'";
	$retValQryTotal = $sign->FunctionJSON($QryTotal);
	$decodedJSON = json_decode($retValQryTotal);
	$TotalAmt = $decodedJSON->response[0]->TotalAmt;
	if($TotalAmt==""){
		$TotalAmt=00;
	}
	
	$balanceAmount=number_format(round(($Intinvoiceamount - $TotalAmt),0),2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Invoice Transaction </title>
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
				
                  <h4 class="card-title">Payment Details for <?php echo $invoiceno; ?> || Invoice Amount: (&#8377; <?php echo $amount;?>) || Balance Amount: (&#8377; <?php echo $balanceAmount;?>)</h4>
					<!--<button type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="location.href='pgEPRSPaymentListForm.php?type=add&id=&po_id=<?php //echo $po_id; ?>&amount=<?php //echo $amount; ?>&invoiceno=<?php //echo $invoiceno; ?>';"><?php //if($balanceAmount!=0.00 || $balanceAmount!=0) { ?><i class="icon-plus"></i> Create New Record <?php// }?></button>-->
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
var valpoi_id="<?php echo $po_id; ?>";
var amount="<?php echo $amount; ?>";
var invoiceno="<?php echo $invoiceno; ?>";

$(document).ready(function(){
	showData();
});

function showData(){
	$.ajax({
		type:"POST",
		url:"apiGetVieweprsInvoiceTransaction.php",
		data:{valpoi_id:valpoi_id,amount:amount,invoiceno:invoiceno},
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

/* function editRecord(id){
	window.location.href = "pgTransportMasterForm.php?type=edit&id="+id;
}

function deleteRecord(id){
	//var ans= confirm("are you sure to delete this record?");
	
		showConfirmAlert('Confirm action!', 'Are you sure to delete this record?','question', function (confirmed) {
			if (confirmed) {
				deleteYes(id);
			}
		});
}
function deleteYes(id)
{
	var valtablename="tw_transport_master";
		
		$.ajax({
				type:"POST",
				url:"apiDeleteData.php",
				data:{id:id,tablename:valtablename},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
						showData();
						showAlert("success","Record Deleted Successfully","success");
					}
					else{
						showAlert("Warning","Something Went Wrong","warning");
					}
					
				}
			});
	} */
</script>
</body>

</html>