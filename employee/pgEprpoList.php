<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}

include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
 
$url = $_SERVER['REQUEST_URI'];
//echo $url; // Outputs: Full URL

$company_id = $_SESSION["company_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueEmployeeImagePathOther= $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");

$qry1="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status='".$settingValuePendingStatus."' order by id desc";
$qryCntInprocess = $sign->Select($qry1);
$qry2="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."' order by id desc";
$qryCntApproved = $sign->Select($qry2);
$qry3="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status='".$settingValueRejectedStatus."' order by id desc";
$qryCntRejected = $sign->Select($qry3);
$qry4="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' and status='".$settingValueCompletedStatus."' order by id desc";
$qryCntCompleted = $sign->Select($qry4);
$qry5="select count(*) as cnt from tw_po_info where supplier_id='".$company_id."' order by id desc";
$qryCntAll = $sign->Select($qry5);

$reasonQry="SELECT id,reason FROM tw_rejected_reason_master where visibility='true' AND panel='EPR' order by priority ASC";
$Reasons=$sign->FunctionOption($reasonQry,"",'reason',"id");

// $pendingStatusQry="SELECT status FROM tw_po_info where id='".."'";
// $Reasons=$sign->FunctionOption($reasonQry,"",'reason',"id");

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
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  <!-- endinject -->
 <link rel="stylesheet" href="../assets/css/custom/style.css" />
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
										<select class="form-control" placeholder="Reason of Rejection" id="txtInputReason" >
										<option value="">Choose...</option>
										<?php echo  $Reasons; ?>
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
      <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="ViewInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
								<div class="card-body" id="MaterialInfo">
								
								
																	  
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

  <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="ViewRejectedInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalViewReason();">Close</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
   <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="ViewApprovedInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Material Info</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
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
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal();">Close</button>
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
                              InProcess (<?php echo $qryCntInprocess; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idApproved" onclick="showData('<?php echo $settingValueApprovedStatus;?>');">
                              <i class="ti-check-box"></i>
                              Approved (<?php echo $qryCntApproved; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idRejected" onclick="showData('<?php echo $settingValueRejectedStatus;?>');">
                              <i class="ti-na"></i>
                              Rejected (<?php echo $qryCntRejected; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idCompleted" onclick="showData('<?php echo $settingValueCompletedStatus;?>');">
                              <i class="ti-target"></i>
                              Completed (<?php echo $qryCntCompleted; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idAll" onclick="showData('');">
                              <i class="ti-notepad"></i>
                              All (<?php echo $qryCntAll; ?>)
                            </a>
                          </li>
                        </ul>
                      </div>
					  
				</div>
			</div><br>
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
var valsettingValueEmployeeImagePathOther='<?php echo $settingValueEmployeeImagePathOther;?>';
var valsettingValuedisableicon='<?php echo $settingValuedisableicon;?>';
var valRejectionId = "";
   

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
	showData('<?php echo $settingValueApprovedStatus;?>');
	
		$("#idInprocess").removeClass("active");
		$("#idApproved").addClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
});
function showData(id){
	if(id==valsettingValuePendingStatus){
		$("#idInprocess").addClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
		
	}else if(id==valsettingValueApprovedStatus){
		$("#idInprocess").removeClass("active");
		$("#idApproved").addClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
	}
	else if(id==valsettingValueRejectedStatus){
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").addClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
	}
	else if(id==valsettingValueCompletedStatus){
		
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").addClass("active");
		$("#idAll").removeClass("active");
	}
	else{
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").addClass("active");
	}
	
	$.ajax({
		type:"POST",
		url:"apiGetEprPOList.php",
		data:{statustype:id},
		success:function(response){
			console.log(response);
			
			$("#tableData").html(response);

		}
	});
}
function addRecord(id){
	window.open("pgPurchaseOrderForm.php?type=add&id=&po_id="+id,"_blank");
}
function ViewRecord(id,sid){
	
	window.open("pgPrintPO.php?id="+id+"&supid="+sid,"_blank");
	//window.location.href = "pgPrintPO.php?id="+id+"&supid="+sid;
}

function showModalViewInfo()
{	
	jQuery.noConflict();
	$("#ViewInfo").modal("show");
	
}function showModalRejectedViewInfo()
{	
	jQuery.noConflict();
	$("#ViewRejectedInfo").modal("show");
	
}
function closeModalViewReason() {
	
  $("#modalViewReason").modal("hide");
  location.reload();
}
function showApprovedModal()
{	
	jQuery.noConflict();
	$("#ViewApprovedInfo").modal("show");
	
}
function ViewApproved(id){
	$.ajax({
			type:"POST",
			url:"apiGetinfoApproved.php",
			data:{id:id},
			success:function(response){
				console.log(response);
				if(response!=""){
				showApprovedModal();
				 $("#MaterialApprovedInfo").html(response);	
		        
			
				}
			}	
		}); 

}
function ViewInprocess(id){
	
	$.ajax({
			type:"POST",
			url:"apiGetinfoEPR.php",
			data:{id:id},
			success:function(response){
				console.log(response);
				if(response!=""){
					
				 $("#MaterialInfo").html(response);	
		        showModalViewInfo();
			
				}
			}	
		}); 

}
function ViewRejected(id){
$.ajax({
			type:"POST",
			url:"apiGetinfoRejectedEPR.php",
			data:{id:id},
			success:function(response){
				console.log(response);
				if(response!=""){
					
				 $("#RejectedMaterialInfo").html(response);	
		        showModalRejectedViewInfo();
			
				}
			}	
		}); 

}
function assignproduct(id,requestCompany_id,recyclerCompnay_id){
	
	window.location.href = "pgAssignProduct.php?po_id="+id+"&requestCompany_id="+requestCompany_id+"&recyclerCompnay_id="+recyclerCompnay_id;
}
function ViewEprCerticate(id,sid){
	window.open("pgEPRCertificate.php?po_id="+id+"&supid="+sid,"_blank");
}
function ViewRecyclingCerticate(id,sid){
	window.open("pgEPRRecyclingCertificate.php?id="+id+"&supid="+sid,"_blank");
	
}function ViewTabulatedDetails(id,sid){
	window.open("pgViewTabulatedDetails.php?po_id="+id+"&supid="+sid,"_blank");
}

function AuditorCertificate(id){
	window.location.href = "pgAuditorCertificate.php?po_id="+id+"&req=";
}
function AcceptRecord(id,status){
	
	showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
		if(confirmed==true){	
		 $("#imgaccept").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
			var valsettingValueApprovedStatus = "<?php echo $settingValueApprovedStatus; ?>";
			var valquery = "Update tw_po_info set status = '"+valsettingValueApprovedStatus+"' where id="+id;
			$.ajax({
					type:"POST",
					url:"apiCommonQuerySingle.php",
					data:{valquery:valquery},
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
		url:"apiEprPoAcceptEmail.php",
		data:{po_id:id},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				//showData();
				showAlertRedirect("Success","Record Updated Successfully","success","pgEprpoList.php"); 
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
		url:"apiEprpoSendEmailReject.php",
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
			disableButton('#Status','<i class="ti-timer"></i> Processing...');
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
			$("#imgreject").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
			var valsettingValueRejectedStatus = '<?php echo $settingValueRejectedStatus; ?>';
			var valquery = "Update tw_po_info set status = '"+valsettingValueRejectedStatus+"' ,reason = '"+$("#txtInputReason").val()+"' where id="+valRejectionId;
			
			 $.ajax({
			type:"POST",
			url:"apiCommonQuerySingle.php",
			data:{valquery:valquery},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					sendMailReject(valRejectionId);
					//closeModal();

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