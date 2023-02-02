<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$created_by=$_SESSION["username"];
$settingValueRejectedstatus=$commonfunction->getSettingValue("Rejected status");
$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
$retVal1 = $sign->FunctionOption($qry3,"",'verification_status','ID');

$qry4 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
$retVal2 = $sign->FunctionOption($qry4,"",'verification_status','ID');

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$option = "<option value='On'>On</option><option value='Off'>Off</option>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | View Company</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">

<!-- endinject -->
<!-- inject:css -->
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
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalCompanyStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Change Company Status</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
				<span aria-hidden="true" onclick="closeModal()";>×</span>
				</button>	
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
						<select id="txtStatus" class="form-control form-control-sm" onchange="myFunction('direct')">
							<?php echo $retVal1; ?>
						</select>
						<br>
					</div>
					
					<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12" id="ViewReason">
							<input type="text" readonly class="form-control form-control-sm" id="txtReason" />
					</div>
					<br>
					<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">						
						<div class="form-group row"  id="TEXTREASON">	
							<div class="card-body" >
								<label class="col-sm-12">Enter rejection reason</label>
								<input type="text" class="form-control" id="InputReason" placeholder="Rejection reason">
														
							</div>
						</div>
						<br>
						<div class="card card-inverse-danger" id="ERROR">	
							<div class="card-body">
								  <p class="card-text"> Record already updated
								</p>
										
							</div>
						</div>	  
					</div>
				</div>		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal();">Close</button>
				<button type="button" class="btn btn-success" id="Status" onclick="adddata('Company');">Submit</button>
			</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
  
   <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalComplianceStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Change Compliance Status</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
				<span aria-hidden="true" onclick="closeModal()";>×</span>
				</button>	
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
						<select id="txtComplianceStatus" class="form-control form-control-sm" onchange=>
							<?php echo $retVal2; ?>
						</select>
						<br>
					</div>
					
					<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12" id="ViewComplianceReason">
							<input type="text" readonly class="form-control form-control-sm" id="txtComplianceReason" />
					</div>
					<br>
					<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">						
						<div class="form-group row"  id="TEXTCOMPLIANCEREASON">	
							<div class="card-body" >
								<label class="col-sm-12">Enter rejection reason</label>
								<input type="text" class="form-control" id="InputComplianceReason" placeholder="Rejection reason">
														
							</div>
						</div>
						<br>
						<div class="card card-inverse-danger" id="COMPLIANCEERROR">	
							<div class="card-body">
								  <p class="card-text"> Record already updated
								</p>
										
							</div>
						</div>	  
					</div>
				</div>		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeComplianceModal();">Close</button>
				<button type="button" class="btn btn-success" id="ComplianceStatus" onclick="adddata('Compliance');">Submit</button>
			</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
  <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalCompanyLoginStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Change Company Login Status</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModalLogin()";>×</span>
			</button>
		</div>
		<div class="modal-body">
			 <div class="input-group">
				 <div class="input-group">
					<div class="form-group row">
					  <div class="col-sm-12">
						<div class="d-flex justify-content-center align-items-center container">
							<div class="form-group row">
							  <div class="col-sm-12">
								<select id="txtLoginStatus" class="form-control form-control-sm">
									<?php echo $option; ?>
								</select>
							  </div>
							</div>	
						</div>			
					  </div>
					</div>
				  </div>
			 </div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalLogin();">Close</button>
			<button type="button" class="btn btn-primary" onclick="adddata('Login');">Submit</button>
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
			  <h4 class="card-title">View Company</h4>
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>

var valhdnid = "";
var valfunctinvalue = "";
var settingValueRejectedstatus="<?php echo $settingValueRejectedstatus;  ?>";

$(document).ready(function(){
	$("#ViewReason").css("display", "none");
	showData();
});

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
		    $("#" +inputComponent).focus();
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

function myFunction(){
	
		var val = $('#txtStatus option:selected').val();
	
		if(val==settingValueRejectedstatus){
			$("#ViewReason").fadeOut();
			$("#TEXTREASON").fadeIn();
			
		}
		else if(val!==settingValueRejectedstatus){
			$("#ViewReason").fadeOut();
			$("#TEXTREASON").fadeOut();
			$("#Sub").fadeOut();
		}

}

function showData(){
		$.ajax({
			type:"POST",
			url:"apiGetViewCompanyDetails.php",
			data:{},
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
function editCompanyStatus(id){
	editStatus(id,'Company');
	$("#ERROR").css("display", "none");
	$("#TEXTREASON").css("display", "none");
} 
function editComplianceStatus(id){
	editStatus(id,'Compliance');
	$("#COMPLIANCEERROR").css("display", "none");
	$("#TEXTCOMPLIANCEREASON").css("display", "none");
} 
function editCompanyLoginStatus(id){
	editStatus(id,'login');
}
function editStatus(id,type)
{ 
	valhdnid = id;
	valhdntype = type;
	 $.ajax({
				type:"POST",
				url:"apiGetLoginStatus.php",
				data:{id:valhdnid,type:valhdntype},
				dataType: 'JSON',
				success:function(response){
					console.log(response);
					
						if(type=='login'){
							showModalLogin();
							$("#txtLoginStatus").html(response[3]);
						}
						else if(type=='Compliance'){
							showComplianceModal();
							
							$("#txtComplianceStatus").html(response[1]);
							
							if(settingValueRejectedstatus==response[2]){
								$("#ViewComplianceReason").css("display", "block");
							}
							else{
								$("#ViewComplianceReason").css("display", "none");
							}
						$("#txtComplianceReason").val(response[0]); 
		
						}
						else{
							showModal();
							$("#txtStatus").html(response[1]);
							
							if(settingValueRejectedstatus==response[2]){
								$("#ViewReason").css("display", "block");
							}
							else{
								$("#ViewReason").css("display", "none");
							}
						$("#txtReason").val(response[0]); 
						
					} 
                   				
				}
			});
}

function showModal()
{	
	jQuery.noConflict();
	$("#modalCompanyStatus").modal("show");
	
}
function showComplianceModal()
{	
	jQuery.noConflict();
	$("#modalComplianceStatus").modal("show");
	
}
function showModalLogin()
{	
	jQuery.noConflict();
	$("#modalCompanyLoginStatus").modal("show");
	
}
function closeComplianceModal() {
	
  $("#modalComplianceStatus").modal("hide");
  location.reload();
}
function closeModal() {
	
  $("#modalCompanyStatus").modal("hide");
  location.reload();
}
function closeModalLogin() {

$("#modalCompanyLoginStatus").modal("hide");

location.reload();
}

function adddata(id){
var valcreated_by = "<?php echo $created_by;?>";
var valcreated_on = "<?php echo $cur_date;?>";
var valcreated_ip = "<?php echo $ip_address;?>";

if(id=='Company'){
	var val = $('#txtStatus option:selected').val();
	var status=($("#txtStatus").val());
	
	if(val==settingValueRejectedstatus && !validateBlank($("#InputReason").val())){
		setErrorOnBlur("InputReason");
			
	}
	else{
		var Reason= $("#InputReason").val();
			var valquery = "Update tw_company_details set Status = '"+$("#txtStatus").val()+"',reason='"+Reason+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where ID = '"+valhdnid+"' ";
			disableButton('#Status','<i class="ti-timer"></i> Processing...');
			$.ajax({
			type:"POST",
			url:"apiAcceptCompanyStatus.php",
			data:{valquery:valquery,status:status,id:valhdnid,typeid:id},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					showAlertRedirect("Success","Data Updated Successfully","success","pgViewCompanyList.php");
				enableButton('#Status','submit');
				}else if($.trim(response)=="Exist"){
					enableButton('#Status','submit');
					$("#ERROR").fadeIn();
					$("#ERROR").fadeOut(5000);
				}
				
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");
				}
			}
		});  
	}  
}
else if(id=='Compliance'){
	var val = $('#txtComplianceStatus option:selected').val();
	var status=($("#txtComplianceStatus").val());
	
	
	/*if(val==settingValueRejectedstatus && !validateBlank($("#InputComplianceReason").val())){
		setErrorOnBlur("InputComplianceReason");
			
	}
	else{*/
		var Reason= $("#InputComplianceReason").val();
			var valquery = "Update tw_company_details set compliance_status = '"+$("#txtComplianceStatus").val()+"',reason='"+Reason+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where ID = '"+valhdnid+"' ";
			disableButton('#ComplianceStatus','<i class="ti-timer"></i> Processing...');
			$.ajax({
			type:"POST",
			url:"apiComplianceStatus.php",
			data:{valquery:valquery,typeid:id,status:status,id:valhdnid,},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					showAlertRedirect("Success","Data Updated Successfully","success","pgViewCompanyList.php");
				enableButton('#ComplianceStatus','submit');
				}else if($.trim(response)=="Exist"){
					enableButton('#ComplianceStatus','submit');
					$("#COMPLIANCEERROR").fadeIn();
					$("#COMPLIANCEERROR").fadeOut(5000);
				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");
				}
			}
		});  
	//}  
}
else{
	var valquery = "Update tw_company_login set Status = '"+$("#txtLoginStatus").val()+"' where company_id = '"+valhdnid+"' ";
   disableButton('#Status','<i class="ti-timer"></i> Processing...');
	$.ajax({
	type:"POST",
	url:"apiAcceptCompanyStatus.php",
	data:{valquery:valquery,status:status,id:valhdnid,typeid:id},
	success:function(response){
		console.log(response);
		if($.trim(response)=="Success"){
			showAlertRedirect("Success","Data Updated Successfully","success","pgViewCompanyList.php");
		 enableButton('#Status','submit');
		}else if($.trim(response)=="Exist"){
			enableButton('#Status','submit');
			$("#ERROR").fadeIn();
			$("#ERROR").fadeOut(5000);
		}
		else{
			showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");
		}
	}
	});    
}
}

function editRecord(id){
	window.location.href = "pgCompanyProfile.php?id="+id;
}
function deleteRecord(id){
	showConfirmAlert('Confirm action!', 'Are you sure to delete this record?','question', function (confirmed){
	if(ans==true){
		var valtablename="tw_company_details";
		
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