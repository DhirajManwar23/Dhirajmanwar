<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$company_id = $_SESSION["company_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

$qry1="select count(*) as cnt from  tw_po_info where company_id='".$company_id."' and status='".$settingValuePendingStatus."'";
$qryCntInprocess = $sign->Select($qry1);
$qry2="select count(*) as cnt from  tw_po_info where company_id='".$company_id."' and status='".$settingValueApprovedStatus."'";
$qryCntApproved = $sign->Select($qry2);
$qry3="select count(*) as cnt from  tw_po_info where company_id='".$company_id."' and status='".$settingValueRejectedStatus."'";
$qryCntRejected = $sign->Select($qry3);
$qry4="select count(*) as cnt from  tw_po_info where company_id='".$company_id."' and status='".$settingValueCompletedStatus."'";
$qryCntCompleted = $sign->Select($qry4);
$qry5="select count(*) as cnt from  tw_po_info where company_id='".$company_id."'";
$qryCntAll = $sign->Select($qry5);
$type=$_REQUEST["type"];
$valtype=0;
if($type=="In Process"){
	$valtype=$settingValuePendingStatus;
}
else if($type=="Approved"){
	$valtype=$settingValueApprovedStatus;
}
else if($type=="Rejected"){
	$valtype=$settingValueRejectedStatus;
}
else if($type=="Completed"){
	$valtype=$settingValueCompletedStatus;
}
else{
	$valtype="";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | PO</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
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
  <div class="modal fade" id="ViewRejectedInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Material Info</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body modal-body">
					
							<div class="form-group row">	
								<div class="card-body" id="RejectedMaterialInfo">
								
								
																	  
						</div>
					</div>
					
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal();">Close</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
     <!-- ==============MODAL START ================= -->
  <div class="modal fade" id="ViewApprovedInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Material Info</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()">×</span>
			</button>	
		</div>
		<div class="modal-body">
					<div class="form-group row">
						<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<div class="form-group row">	
								<div class="card-body" id="MaterialApprovedInfo">
								
								</div>
							</div>
									  
						</div>
					</div>
					
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Close</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
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
var valtype='<?php echo $valtype;?>';

$('input').blur(function()
{
	var valplaceholder = $(this).attr("placeholder");
	var vallblid = $(this).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $(this).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{
		if(!checkElementExists)
		{
			$(this).parent().addClass('has-danger');
			$(this).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
		}

	}
	else
	{
		$(this).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
});
function setError(inputComponent)
{
	var valplaceholder = $(inputComponent).attr("placeholder");
	var vallblid = $(inputComponent).attr("id");
	var valid = "errSet" + vallblid;
	var valtext = "Please enter valid " + valplaceholder;
	var checkElementExists = document.getElementById(valid);
	if(!checkElementExists)
	{
		$("#" + vallblid).parent().addClass('has-danger');
		$("#" + vallblid).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
	}
	
}
function setErrorOnBlur(inputComponent)
{
	var valplaceholder = $("#" +inputComponent).attr("placeholder");
	var vallblid = $("#" +inputComponent).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $("#" +inputComponent).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{
		if(!checkElementExists)
		{
			$("#" +inputComponent).parent().addClass('has-danger');
			$("#" +inputComponent).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
		}

	}
	else
	{
		$("#" +inputComponent).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
}
function removeError(inputComponent)
{
	var vallblid = $(inputComponent).attr("id");
	$("#" + vallblid).parent().removeClass('has-danger');
	const element = document.getElementById("errSet"+vallblid);
	if (element)
	{
		element.remove();
	}
}	
$(document).ready(function(){
	showData(valtype);
	
	$("#idInprocess").removeClass("active");
	$("#idApproved").addClass("active");
	$("#idRejected").removeClass("active");
	$("#idCompleted").removeClass("active");
	$("#idAll").removeClass("active");
	$("#idCreate").removeClass("active");
});

function showData(valtype){
	$.ajax({
		type:"POST",
		url:"apiGetViewEprPO.php",
		data:{statustype:valtype},
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
})
		}
	});
} 
function showModal()
{	
	jQuery.noConflict();
	$("#modalRejectedReason").modal("show");
	
}
function showApprovedModal()
{	
	jQuery.noConflict();
	$("#ViewApprovedInfo").modal("show");
	
}
function closeModal() {
	
  $("#modalRejectedReason").modal("hide");
  location.reload();
}
function showModalRejectedViewInfo()
{	
	jQuery.noConflict();
	$("#ViewRejectedInfo").modal("show");
	
}
function editRecord(id,sid){
	
	window.location.href = "pgPurchaseOrderFormEdit.php?type=edit&id="+id+"&supid="+sid;
}
function ViewRecord(id,sid){
	window.open( "pgPrintPO.php?id="+id+"&supid="+sid,"_blank");
	
}
function ViewInprocess(id){
	window.location.href = "pgEprMaterialInward.php?type=In%20Process&po_id="+id+"&req=";
}

function ViewRejected(id){
	window.location.href = "pgEprMaterialInward.php?type=Rejected&po_id="+id+"&req=";
}
function ViewFulfilled(id){
	window.location.href = "pgEprMaterialInward.php?type=Approved&po_id="+id+"&req=";
}
function ViewRecyclingCerticate(id,sid){
	window.open("pgEPRRecyclingCertificate.php?id="+id+"&supid="+sid,"_blank");

}function ViewTabulatedDetails(id,sid){
	window.open("pgViewTabulatedDetails.php?po_id="+id+"&supid="+sid,"_blank");
}
function ViewApproved(id){
	/* $.ajax({
			type:"POST",
			url:"apiGetinfoApproved.php",
			data:{id:id},
			success:function(response){
				console.log(response);
				if(response!=""){
					
				 $("#MaterialApprovedInfo").html(response);	
		        showApprovedModal();
			
				}
			}	
		});  */
	window.location.href = "pgEprMaterialInward.php?type=Approved&po_id="+id+"&req=";


}

function AcceptRecord(id){
	showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
		if(confirmed==true){
			$.ajax({
					type:"POST",
					url:"apiAcceptEprPo.php",
					data:{id:id},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							sendMail(id);
						}
						else{
							showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
						}
						
					}
				});
		}
	});
}

function sendMail(id){
	$.ajax({
		type:"POST",
		url:"apiEprSendEmail.php",
		data:{po_id:id},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showData();
				showAlertRedirect("Success","Record Updated Successfully","success","pgEprpo.php"); 
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
			
		}
	});
}
function sendMailReject(id){
	alert(id)
	$.ajax({
		type:"POST",
		url:"apiEprSendEmailReject.php",
		data:{po_id:id,reason:$("#txtInputReason").val()},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Record Updated Successfully","success","pgEprpoList.php"); 
				
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
			
		}
	});
}
function RejectRecord(id){
	showConfirmAlert('Confirm action!', 'Are you sure to delete this record?','question', function (confirmed){
		if(confirmed==true){
			valRejectionId=id;	
			showModal();
		}
	});
}

function adddataReject() {
	if(!validateBlank($("#txtInputReason").val())){
		setErrorOnBlur("txtInputReason");
	}
	else{
		
			var valsettingValueRejectedStatus = '<?php echo $settingValueRejectedStatus; ?>';
			var valquery = "Update  tw_epr_material_assign_info set status = '"+valsettingValueRejectedStatus+"' ,	reason = '"+$("#txtInputReason").val()+"' where id="+valRejectionId;
			
			 $.ajax({
			type:"POST",
			url:"apiCommonQuerySingle.php",
			data:{valquery:valquery},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					sendMailReject(valRejectionId);
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