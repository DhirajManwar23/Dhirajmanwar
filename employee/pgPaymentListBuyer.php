<?php 
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$requestid = $_REQUEST["outward_id"];
	
	//--Karuna Invoice Amount Start
		$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$requestid."'";
		$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

		$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$requestid."'";
		$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
		
		if($retValInvoice>0){
			$qryInvoice="SELECT id,document,document_value,amount,created_on from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$requestid."' ORDER BY outward_id ASC";
			$retVal = $sign->FunctionJSON($qryInvoice);
			$decodedJSON = json_decode($retVal);
			$invid = $decodedJSON->response[0]->id;
			$document = $decodedJSON->response[1]->document;
			$invoice_number = $decodedJSON->response[2]->document_value;
			$invoiceamount = $decodedJSON->response[3]->amount;
			$invoice_date = $decodedJSON->response[4]->created_on;
			$invoice_date = date("d-m-Y", strtotime($invoice_date));
			
		}
		else{
			$qry1Invoice="SELECT id,invoice_number,final_total_amount,invoice_date from tw_tax_invoice WHERE outward_id='".$requestid."' ORDER BY outward_id ASC";
			$retVal = $sign->FunctionJSON($qry1Invoice);
			$decodedJSON = json_decode($retVal);
			$invid = $decodedJSON->response[0]->id;
			$invoice_number = $decodedJSON->response[1]->invoice_number;
			$invoiceamount = $decodedJSON->response[2]->final_total_amount;
			$invoice_date = $decodedJSON->response[3]->invoice_date;
			$invoice_date = date("d-m-Y", strtotime($invoice_date));
		}
		
		$qrymo="SELECT cd.CompanyName from tw_material_outward mo INNER JOIN tw_company_details cd ON mo.company_id=cd.ID WHERE mo.id='".$requestid."'";
		$retValmo = $sign->SelectF($qrymo,"CompanyName");
		
		//---Karuna Invoice Amount End
		
		$QryTotal="SELECT SUM(amount) as TotalAmt FROM tw_invoice_transaction WHERE outward_id='".$requestid."'";
		$retValQryTotal = $sign->FunctionJSON($QryTotal);
		$decodedJSON = json_decode($retValQryTotal);
		$TotalAmt = $decodedJSON->response[0]->TotalAmt;
		if($TotalAmt==""){
			$TotalAmt=0.00;
		}
		$Balanceamount=($invoiceamount-$TotalAmt);
		$valInfo = $retValmo." | ".$invoice_number." | ".$invoice_date." | &#8377;".$invoiceamount." | Balance Amount: &#8377;".number_format(round($Balanceamount,2),2); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Payment List </title>
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
				
                  <h4 class="card-title"><?php echo $valInfo; ?></h4>
					<?php if($Balanceamount!=0 || $Balanceamount!=0.00){ ?><button type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="location.href='pgPaymentListForm.php?type=add&id=&outward_id=<?php echo $requestid;?>';"><i class="icon-plus"></i> Create New Record</button><?php } ?>
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
var valoutwardid="<?php echo $requestid; ?>";

	function showData(){
		$.ajax({
			type:"POST",
			url:"apiGetViewPaymentListBuyer.php",
			data:{valoutwardid:valoutwardid},
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
	window.location.href = "pgPaymentListForm.php?type=edit&id="+id+"&outward_id="+valoutwardid;
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
	var valtablename="tw_invoice_transaction";
		
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
	}
</script>
</body>

</html>