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
$settingValuePO= $commonfunction->getSettingValue("PO");
$settingValueOther= $commonfunction->getSettingValue("OtherReason");
$settingValueEmployeeImagePathOther= $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
 
$url = $_SERVER['REQUEST_URI'];
//echo $url; // Outputs: Full URL



$qry1="select count(*) as cnt from tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValuePendingStatus."' order by id desc";
$qryCntInprocess = $sign->Select($qry1);
$qry2="select count(*) as cnt from tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueApprovedStatus."' order by id desc";
$qryCntApproved = $sign->Select($qry2);
$qry3="select count(*) as cnt from tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueRejectedStatus."' order by id desc";
$qryCntRejected = $sign->Select($qry3);
$qry4="select count(*) as cnt from tw_temp_po_info where supplier_id='".$company_id."' and status='".$settingValueCompletedStatus."' order by id desc";
$qryCntCompleted = $sign->Select($qry4);
$qry5="select count(*) as cnt from tw_temp_po_info where supplier_id='".$company_id."' order by id desc";
$qryCntAll = $sign->Select($qry5);

$qry6 = "SELECT id,reason from tw_rejected_reason_master where panel='".$settingValuePO."' Order by id desc";
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
									<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
										<label class="col-sm-12">Select rejection reason</label>
										<select class="form-control" placeholder="Reason of Rejection" id="txtInputReason" >
										<option value="">Select Reason</option>
										<?php echo  $retVal6; ?>
										</select>
									</div>
									<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12" id="reasondiv">
										<label class="col-sm-12">Enter Rejection reason</label>
										<textarea class="form-control" id="txReason" maxlength="5000"  placeholder="Reason of Rejection"></textarea>
									</div>
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
var valsettingValueOther='<?php echo $settingValueOther;?>';

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
	document.getElementById( 'reasondiv' ).style.display = 'none';
});
$("#txtInputReason").on('change keyup paste', function () {
	var valtext = $('#txtInputReason option:selected').text();
	if(valtext.trim()==valsettingValueOther){
		document.getElementById( 'reasondiv' ).style.display = 'block';
	}
	else{
		document.getElementById( 'reasondiv' ).style.display = 'none';
	}
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
		url:"apiGetPOList.php",
		data:{statustype:id},
		success:function(response){
			console.log(response);
			
			$("#tableData").html(response);


		}
	});
}
function addRecord(id){
	window.location.href = "pgMaterialOutwardForm.php?type=add&id=&po_id="+id;
}
function ViewRecord(id){
	window.location.href = "pgPODocument.php?po_id="+id;
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
function AcceptRecord(id){
	showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
		if(confirmed==true){	
			var valsettingValueApprovedStatus = "<?php echo $settingValueApprovedStatus; ?>";
			var valquery = "Update tw_temp_po_info set status = '"+valsettingValueApprovedStatus+"' where id="+id;
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
	showConfirmAlert('Confirm action!', 'Are you sure to delete this record?','question', function (confirmed){
		
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
	document.getElementById( 'reasondiv' ).style.display = 'none';
	
}
function closeModal() {
	
  $("#modalRejectedReason").modal("hide");
  location.reload();
}
function adddataReject() {
	var valtext = $('#txtInputReason option:selected').text();
	if(!validateBlank($("#txtInputReason").val())){
		setErrorOnBlur("txtInputReason");
	}
	else if(valtext.trim()==valsettingValueOther){
		if(!validateBlank($("#txReason").val())){
			setErrorOnBlur("txReason");
		}
		else{
			SubmitReject();
		}
	}
	else{
		SubmitReject();	 
	}
}

function SubmitReject() {
	document.getElementById("alinkaccept").style.pointerEvents = "none";
	document.getElementById("alinkreject").style.pointerEvents = "none";
	var valsettingValueRejectedStatus = '<?php echo $settingValueRejectedStatus; ?>';
	var valquery = "Update tw_temp_po_info set status = '"+valsettingValueRejectedStatus+"' ,reason = '"+$("#txtInputReason").val()+"',reasontext = '"+$("#txReason").val()+"' where id="+valRejectionId;
	 $("#imgreject").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
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
</script>
</body>
</html>