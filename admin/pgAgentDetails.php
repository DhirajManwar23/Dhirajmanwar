<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
$selectedOff="";
$selectedOn="";
$status="";
$reason="";

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
$retVal1 = $sign->FunctionOption($qry3,"",'verification_status','ID');

$qryAgentVerificationStatus="select status from tw_agent_details ORDER by id desc";
$AgentVerificationStatus=$sign->SelectF($qryAgentVerificationStatus,"status");

$settingValueRejectedstatus=$commonfunction->getSettingValue("Rejected status");

?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |Agent Details Master</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">

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
	
	  		<!-- ==============MODAL START ================= -->
	<div class="modal fade" id="modalAgentLoginStatus" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Change Login Status</h5>
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
										<select id="txtStatus" class="form-control form-control-sm">
											<option value='On'>On</option><option value='Off'>Off</option>
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
					<button type="button" class="btn btn-primary" onclick="updateLoginstatus();">Submit</button>
				</div>
			</div>
		</div>
	</div>
	  <!-- ==============MODAL END ================= -->
	  <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalAgentVerificationStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Change CollectionPoint Status</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
			<span aria-hidden="true" onclick="closeModal()";>×</span>
			</button>	
		</div>
		<div class="modal-body">
					<div class="form-group row">
						<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<select id="txtAgentStatus" class="form-control form-control-sm" onchange="myFunction('direct')">
								<?php echo $retVal1; ?>
							</select>
							<br>
							
						</div>
						<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12" id="ViewReason">
							<input type="text" readonly class="form-control form-control-sm" id="txtReason" />
						</div>
						<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<div class="form-group row" id="TEXTREASON">	
								<div class="card-body" >
									<label class="col-sm-12">Enter rejection reason</label>
									<textarea type="text" class="form-control" id="InputReason" placeholder="Rejection reason" ></textarea>
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
				<button type="button" class="btn btn-success" id="StatusVerification" onclick="updateverificationstatus();">Submit</button>
			</div>
		</div>
	</div>
</div>
	<!-- ==============MODAL END ================= -->	
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">	
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"> Agent Details Master</h4>
					<button type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="location.href='pgAgentDetailsForm.php?type=add&id=';"><i class="icon-plus"></i> Create New Record</button>
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
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>

var valhdnid = "";
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


var valverificationstatus = "<?php echo $AgentVerificationStatus;?>";
var selectedOn = "<?php echo $selectedOn;?>";
var selectedOff = "<?php echo $selectedOff;?>";
var valcreated_on = "<?php echo $cur_date;?>";
var valcreated_ip = "<?php echo $ip_address;?>";
var valcreated_by = "";
var settingValueRejectedstatus="<?php echo $settingValueRejectedstatus;  ?>";


function showData(){
	$.ajax({
		type:"POST",
		url:"apiAgentDetails.php",
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
function myFunction(){
		//alert(settingValueRejectedstatus);
		var val = $('#txtAgentStatus option:selected').val();
		
		if(val==settingValueRejectedstatus){
			$("#ViewReason").fadeOut();
			$("#TEXTREASON").fadeIn();
			
		}
		else if(val!==settingValueRejectedstatus){
			
			$("#TEXTREASON").fadeOut();
			$("#ViewReason").fadeOut();
			$("#Sub").fadeOut();
		}
}


//=========== Status Update Starts =============//
function editAgentLoginStatus(id){
	
	valhdnid = id;
	editStatus(id,'login');
}
function showModalLogin()
{	
	jQuery.noConflict();
	$("#modalAgentLoginStatus").modal("show");
}
 function closeModalLogin(){
	
  $("#modalAgentLoginStatus").modal("hide");
 }
function showModal()
{	
	jQuery.noConflict();
	$("#modalAgentVerificationStatus").modal("show");
	$("#TEXTREASON").fadeOut();
	//$("#txtReason").modal("hide");
}
function closeModal() {
	
  $("#modalAgentVerificationStatus").modal("hide");
 }
 
 function editAgentVerificationStatus(id){
	
	valhdnid = id;
	 $("#ERROR").css("display", "none");
	 editStatus(id,'verification');
}

function editStatus(id,type)
{ 
	$.ajax({
				type:"POST",
				url:"apiGetAgentDetailsStatus.php",
				data:{id:id,type:type},
				dataType: 'JSON',
				success:function(response){
					console.log(response);
					if(type=='login'){
						showModalLogin();
						$("#txtStatus").html(response[3]);
					}
					else{
						showModal();
						$("#txtAgentStatus").html(response);
						console.log(response[0]);
						console.log(response[1]);
						console.log(response[2]);
						if(settingValueRejectedstatus==response[2]){
							$("#ViewReason").css("display", "block");
						}
						else{
							$("#ViewReason").css("display", "none");
						}
						$("#txtReason").val(response[1]);
						
					}			
				}
			});
}

function updateLoginstatus(id){
	var valquery = "Update tw_agent_login set status = '"+$("#txtStatus").val()+"' where agent_id = '"+valhdnid+"' ";
	//disableButton('#Status','<i class="ti-timer"></i> Processing...');
	
	$.ajax({
	type:"POST",
	url:"apiCommonQuerySingle.php",
	data:{valquery:valquery},
	success:function(response){
		console.log(response);
		if($.trim(response)=="Success"){
			showAlertRedirect("Success","Data Updated Successfully","success","pgAgentDetails.php");
			 //enableButton('#Status','submit');
		}else{
			showAlert("Error","Something Went Wrong","error");
		}
	}
});     
}

function updateverificationstatus(id){
	var val = $('#txtAgentStatus option:selected').val();
	if(val==settingValueRejectedstatus && !validateBlank($("#InputReason").val())){
		
			setErrorOnBlur("InputReason");	
		
	}else{

	var Reason= $("#InputReason").val();
	disableButton('#StatusVerification','<i class="ti-timer"></i> Processing...');	
		$.ajax({
				type:"POST",
				url:"apiGetAgentStatus.php",
				data:{id:valhdnid,Reason:Reason,txtAgentStatus:$("#txtAgentStatus").val(),valcreated_by:valcreated_by,valcreated_on:valcreated_on,valcreated_ip:valcreated_ip},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
						showAlertRedirect("Success","Data Updated Successfully","success","pgAgentDetails.php");
						enableButton('#StatusVerification','submit');
					}
					else if($.trim(response)=="Exist"){
						enableButton('#StatusVerification','submit');
						$("#ERROR").fadeIn();
						$("#ERROR").fadeOut(5000);
					}	
					else{
						showAlertRedirect("Error","Something Went Wrong. Please Try After Sometime","success","pgAgentDetails.php");
					}
				
				}
		});
	}			
}			

//=========== Status Update Ends =============//

function ResetPassword(id){
showConfirmAlert('Confirm action!','Do you wish to reset the password?','question', function (confirmed) {
	if (confirmed) {
           
           $("#Reset"+id).attr("disabled","true");
           $("#Reset"+id).css("pointer-events","none");
           $("#Reset"+id).addClass('text-muted');
           $("#Reset"+id).html('<i class="ti-timer"></i>Processing...');
           $("#Reset"+id).css("cursor","no-drop");
		$.ajax({
			type:"POST",
			url:"apiAgentResetPassword.php",
			data:{id:id},
			success:function(response){
			console.log(response);
				if($.trim(response)=="Success"){
					showAlert("Success","Password Reset Successfully.","success","pgLogin.php");
                       $("#Reset"+id).removeAttr("disabled");
					   $("#Reset"+id).css("pointer-events","auto");
					   $("#Reset"+id).removeClass('text-muted');
					   $("#Reset"+id).html('Reset Password');
					   $("#Reset"+id).css("cursor","pointer"); 

				}else if($.trim(response)=="NoRecord"){
						
					showAlert("Warning","Please Enter Email..","warning");
						$("#Reset"+id).removeAttr("disabled");
						$("#Reset"+id).css("pointer-events","auto");
						$("#Reset"+id).removeClass('text-muted');
						$("#Reset"+id).html('Reset Password');
						$("#Reset"+id).css("cursor","pointer"); 
					
				}
				else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
			}
		});
				
	}
});
	
}

function editRecord(id){
	window.location.href = "pgAgentDetailsForm.php?type=edit&id="+id;
}
function deleteRecord(id){
		showConfirmAlert('Confirm action!','Do you want to delete the selected record?','question', function (confirmed) {
			if (confirmed) {
				deleteYes(id);
			}
		});
}
function deleteYes(id)
{
	var valtablename="tw_agent_details";

		$.ajax({
			type:"POST",
			url:"apiDeleteData.php",
			data:{id:id,tablename:valtablename},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					showData();
					showAlertRedirect("Success","Record Deleted Successfully","success","pgAgentDetails.php");
				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");
				}
				
			}
		});
	}
</script>
</body>
</html>