<?php 
session_start();
if(!isset($_SESSION["employeeusername"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$company_id = $_SESSION["company_id"];
$type = $_REQUEST["type"];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueEPRPO= $commonfunction->getSettingValue("PO");

$settingValueEmployeeImagePathOther = $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");
$qry6 = "SELECT id,reason from tw_rejected_reason_master where panel='".$settingValueEPRPO."' Order by id desc";
$retVal6 = $sign->FunctionOption($qry6,'','reason','id');
?>
<!DOCTYPE html>
<html lang="en">
        
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Purchase Order</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/custom/style.css">
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
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalRejectedReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
									<select name="txtInputReason" id="txtInputReason" placeholder="Rejection Reason" class="form-control">
									<option value="">Select Reason</option>
									 <?php echo $retVal6;?>
									</select>
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
      <div class="main-panel">        
        <div class="content-wrapper">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 com-xs-12 col-12 grid-margin stretch-card">
					<table id="tableData" class="table">
					</table>
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
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>
var valsettingValuePendingStatus='<?php echo $settingValuePendingStatus; ?>';
var valsettingValueApprovedStatus='<?php echo $settingValueApprovedStatus; ?>';
var valsettingValueRejectedStatus='<?php echo $settingValueRejectedStatus;?>';
var valsettingValueCompletedStatus='<?php echo $settingValueCompletedStatus;?>';

var valsettingValueEmployeeImagePathOther='<?php echo $settingValueEmployeeImagePathOther;?>';
var valsettingValuedisableicon='<?php echo $settingValuedisableicon;?>';
var valcompany_id='<?php echo $company_id;?>';
var valtype='<?php echo $type;?>';
var valRejectionId = "";
$(document).ready(function(){
	showData(valtype);
});
function showData(valtype){
	
	$.ajax({
		type:"POST",
		url:"apiGetAuditorPOList.php",
		data:{type:valtype},
		success:function(response){
			console.log(response);
			
			$("#tableData").html(response);


		}
	});
}
function ViewFullfilment(id){
	window.location.href = "pgAuditorPORecordDetails.php?id="+id;
}
function ViewFullfilmentRecords(id){
	window.location.href = "pgAuditorPORecordDetails.php?type=ongoing&po_id="+id;
}
function ViewFullfilmentAcceptRecords(id){
	window.location.href = "pgAuditorPORecordDetails.php?type=awaiting&po_id="+id;
}
function ViewFullfilmentCompletedRecords(id){
	window.location.href = "pgAuditorPORecordDetails.php?type=completed&po_id="+id;
}
function ViewFullfilmentPendingRecords(id){
	window.location.href = "pgAuditorPORecordDetails.php?type=edit&id="+id;
}
function AuditorCertificate(id){
	window.location.href = "pgAuditorCertificate.php?po_id="+id+"&req=";
}
function ViewFullfilmentRejectedRecords(id){
	window.location.href = "pgAuditorPORecordDetails.php?type=query&po_id="+id;
} 
function ViewRecord(id){
	window.location.href = "pgPrintPO.php?id="+id+"&supid="+valcompany_id;
}
function ViewInprocess(id){
	window.location.href = "pgMaterialOutward.php?type=In%20Process&po_id="+id;
}
function ViewApproved(id){
	window.location.href = "pgMaterialOutward.php?type=Approved&po_id="+id;
}
function ViewRejected(id){
	window.location.href = "pgMaterialOutward.php?type=Rejected&po_id="+id;
}
/* function AcceptRecord(id){
	showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
		if(confirmed==true){	
			var valsettingValueApprovedStatus = "<?php echo $settingValueApprovedStatus; ?>";
			var valquery = "Update tw_po_info set status = '"+valsettingValueApprovedStatus+"' where id="+id;
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
			 //$('#imgaccept').toggle('slow');
			$("#imgaccept").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
			$.ajax({
					type:"POST",
					url:"apiCommonQuerySingle.php",
					data:{valquery:valquery},
					success:function(response){
						console.log(response);

						if($.trim(response)=="Success"){
							//sendMail(id);
							showAlertRedirect("Success","Record Updated Successfully","success","pgEPRSPendingPo.php?type=Pending"); 
						}
						else{
							showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
						}
						
					}
				});
		}
	});
} */
function sendMail(id){
	$.ajax({
		type:"POST",
		url:"apiSendEmail.php",
		data:{po_id:id},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Record Updated Successfully","success","pgPoList.php"); 
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
			
		}
	});
}
function sendMailReject(id){
	$.ajax({
		type:"POST",
		url:"apiSendEmailReject.php",
		data:{po_id:id,reason:$("#txtInputReason").val()},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Record Updated Successfully","success","pgPoList.php"); 
				
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
			
		}
	});
}
function RejectRecord(id){
	showConfirmAlert('Confirm action!', 'Are you sure to reject this record?','question', function (confirmed){
		
		if(confirmed==true){
			valRejectionId=id;	
			showModal();
		}
	});
}
function showModal()
{	
	jQuery.noConflict();
	$("#modalRejectedReason").modal("show");
	
}
function closeModal() {
  $("#modalRejectedReason").modal("hide");
  location.reload();
}
function adddataReject() {
	if(!validateBlank($("#txtInputReason").val())){
		setErrorOnBlur("txtInputReason");
	}
	else{
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
			var valsettingValueRejectedStatus = '<?php echo $settingValueRejectedStatus; ?>';
			var valquery = "Update tw_po_info set status = '"+valsettingValueRejectedStatus+"' ,reason = '"+$("#txtInputReason").val()+"' where id="+valRejectionId;
			 $("#imgreject").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
			 $.ajax({
			type:"POST",
			url:"apiCommonQuerySingle.php",
			data:{valquery:valquery},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					//sendMailReject(valRejectionId);
					showAlertRedirect("Success","Record Updated Successfully","success","pgEPRSPendingPo.php?type=Pending"); 
					closeModal();

				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
				
			}
		});  
	}
}
</script>
</body>
</html>