<?php 
session_start();
if(!isset($_SESSION["employeeusername"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueEmployeeImagePathOther= $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");
$settingValueOther= $commonfunction->getSettingValue("OtherReason");

$settingValueEPR= $commonfunction->getSettingValue("EPR");
$po_id=$_REQUEST["po_id"];

$reasonQry="SELECT id,reason FROM tw_rejected_reason_master where visibility='true' AND panel='EPR' order by priority ASC";

$Reasons=$sign->FunctionOption($reasonQry,"",'reason',"id");
$qry7 = "select id,state_name from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$po_id."')";
$statename = $sign->FunctionOption($qry7,"",'state_name','id');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Generate Document</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/css/custom/style.css" />
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/css/style.css">
  <!-- endinject -->
  <!-- inject:css -->
  <script src="../assets/js/custom/twCommonValidation.js"></script>
  <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/css/custom/style.css">
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
											<option value="">Choose...</option>
											<?php echo  $Reasons; ?>
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
            <div class="col-md-12 grid-margin stretch-card">	
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Generate Document</h4>
						<div class="form-group row">
							<div class="col-ld-6 col-md-6 col-sm-6 col-xs-6 col-6">
								<label class="col-sm-12">Select State</label>
								<select id="txtState" class="form-control form-control-sm float-right" onchange="myFunction()">
								<option value="">Select State</option>
									<?php echo $statename; ?>
								</select>
								<br>								
							</div>
						</div>
						
						 <div class="table-responsive">
							<div class="table-responsive">
								<table id="tableData" class="table">
								  
								</table>
							 </div>
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
var valpo_id="<?php echo $po_id; ?>";
var valsettingValueRejectedStatus="<?php echo $settingValueRejectedStatus; ?>";
var valsettingValuePendingStatus="<?php echo $settingValuePendingStatus; ?>";
var valsettingValueOther='<?php echo $settingValueOther;?>';
var valstateid="";

var valRejectionId="";
$(document).ready(function(){
	//showData();
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
function showData(){
	$.ajax({
		type:"POST",
		url:"apiGetEPRSDocumentMonths.php",
		data:{po_id:valpo_id,state_id:valstateid},
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
function myFunction(){
	valstateid = $('#txtState').val();
	showData();
}
function AuditorCertificate(id){
	window.location.href = "pgAuditorCertificate.php?po_id="+id+"&req=";
}
function viewReason(id){
		var valquery="Select reason from tw_rejected_reason_master where id="+id;
		 $.ajax({
			type:"POST",
			url:"apiGetViewRejectedReason.php",
			data:{valquery:valquery},
			success:function(response){
				console.log(response);
				showAlert("",response,"");
			}
	});  
}

var rejDate="";
var valRejectionId="";
function sendMailReject(id){
	$.ajax({
		type:"POST",
		url:"apiEprpoDocSendEmailReject.php",
		data:{po_id:id,reason:$("#txtInputReason").val(),rejDate:rejDate},
		success:function(response){
			
			console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Record Updated Successfully","success","pgEPRSGenerateDocuments.php?po_id="+id); 
				showData();
				
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
			
		}
	});
}
function RejectRecord(id,date){
	valRejectionId=id;	
	showConfirmAlert('Confirm action!', 'Are you sure to reject this record?','question', function (confirmed){
		if(confirmed==true){
			showModal();
			rejDate=date;
			
		}
	});
}

function AcceptRecord(id,date,state_id,po_id){
	showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
		//$("#imgaccept").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
		if(confirmed==true){	
			
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
			$.ajax({
					type:"POST",
					url:"apiAcceptEPRSDocData.php",
					data:{eprappid:id,state_id:state_id,po_id:po_id},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							sendMail(valpo_id,date);					
						}
						else{
							showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
						}
						
					}
				});
		}
	}); 
}
function sendMail(po_id,date){
	$.ajax({
		type:"POST",
		url:"apiEprDocSendEmail.php",
		data:{po_id:po_id,date:date},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				
				showAlertRedirect("Success","Record Updated Successfully","success","pgEPRSGenerateDocuments.php?po_id="+po_id); 
				showData();
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
			
		}
	});
}

function checkFileExist(urlToFile) {
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', urlToFile, false);
    xhr.send();
     
    if (xhr.status == "404") {
        return false;
    } else {
        return true;
    }
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
function showModalViewReason()
{	
	jQuery.noConflict();
	$("#modalViewReason").modal("show");
	
}
function closeModalViewReason() {
	
  $("#modalViewReason").modal("hide");
  location.reload();
}

function getReason(id) {
	$.ajax({
	  type:"POST",
	  url:"apiInwardViewReject.php",
	  data:{id:id},
	  success:function(response){
		  console.log(response);
		  showModalViewReason();
		  $("#txtViewReason").val(response);
	  }
  }); 
}
function adddataReject(){
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
function SubmitReject(){
		document.getElementById("alinkaccept").style.pointerEvents = "none";
		document.getElementById("alinkreject").style.pointerEvents = "none";
			
		showConfirmAlert('Confirm action!', 'Are you sure to Reject this record?','question', function (confirmed){
		if(confirmed==true){
			var valquery = "Update  tw_epr_approval set company_status = '"+valsettingValueRejectedStatus+"',supplier_status = '"+valsettingValuePendingStatus+"',reason = '"+$("#txtInputReason").val()+"',reasontext = '"+$("#txReason").val()+"' where po_id="+valRejectionId;
				$.ajax({
					type:"POST",
					url:"apiCommonQuerySingle.php",
					data:{valquery:valquery},	
					success:function(response){
					console.log(response);
						if(response=="Success"){
							showAlert("Success","Document rejected successfully","success");
							sendMailReject(valRejectionId);
						}
						else{
							showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
						}
					}
				}); 
			}else{
				location.reload();
			}
			 
		});
}
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
</script>
</body>

</html>