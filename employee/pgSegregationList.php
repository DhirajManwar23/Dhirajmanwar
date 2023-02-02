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
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
 
$url = $_SERVER['REQUEST_URI'];
//echo $url; // Outputs: Full URL

$company_id = $_SESSION["company_id"];
$employee_id = $_SESSION["employee_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");


$qry1="select count(*) as cnt from tw_mix_waste_collection where company_id='".$company_id."' and status='".$settingValueAwaitingStatus."' and employee_id = '".$employee_id."' order by id desc";
$qryCntInprocess = $sign->Select($qry1);
$qry2="select count(*) as cnt from tw_mix_waste_lot_info where company_id='".$company_id."' and status='".$settingValuePendingStatus."' and employee_id = '".$employee_id."' order by id desc";
$qryCntApproved = $sign->Select($qry2);
$qry3="select count(*) as cnt from tw_mix_waste_collection where company_id='".$company_id."' and status='".$settingValueRejectedStatus."' and employee_id = '".$employee_id."'order by id desc";
$qryCntRejected = $sign->Select($qry3);
$qry4="select count(*) as cnt from tw_mix_waste_lot_info where company_id='".$company_id."' and status='".$settingValueCompletedStatus."' and employee_id = '".$employee_id."' order by id desc";
$qryCntCompleted = $sign->Select($qry4);
$qry5="select count(*) as cnt from tw_mix_waste_collection where company_id='".$company_id."' and employee_id = '".$employee_id."' order by id desc";
$qryCntAll = $sign->Select($qry5);

?>
<!DOCTYPE html>
<html lang="en">
        
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Segregation</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="../assets/css/custom/style.css">
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
  <div class="modal fade" id="modalQuantity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalLabel"><i class="ti-mobile"></i> Quantity Details</h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalQuantity()">
			<span aria-hidden="true" onclick="closeModalQuantity()";>×</span>
			</button>	
		</div>
		<div class="modal-body">
					<div class="form-group row">
						<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<div class="form-group row">	
								<div class="card-body" >
									<div class="form-group row">
										  <label class="col-sm-3 col-form-label">Received Quantity</label>
										  <div class="col-sm-9">
											<input type="number" disabled placeholder="Received Quantity" class="form-control form-control-sm" id="txtReceivedQuantity" value="" />
										  </div>
									</div>
									<div class="form-group row">
										  <label class="col-sm-3 col-form-label">Actual Quantity</label>
										  <div class="col-sm-9">
											<input type="number" placeholder="Actual Quantity" class="form-control form-control-sm" id="txtActualQuantity" value="" />
										  </div>
									</div>
									<div class="form-group row">
										  <label class="col-sm-3 col-form-label">Comment</label>
										  <div class="col-sm-9">
											<textarea class="form-control" id="txtComment" maxlength="5000"  placeholder="Comment"></textarea>
										  </div>
									</div>
								</div>
							</div>
									  
						</div>
					</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModalQuantity();">Close</button>
			<button type="button" class="btn btn-success" id="Status" onclick="saveApproved();">Submit</button>
		</div>
		</div>
	</div>
</div>
  <!-- ==============MODAL END ================= -->
    <!-- ==============MODAL END ================= --> <!-- ==============MODAL START ================= -->
  <div class="modal fade" id="ViewInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
      <div class="main-panel">        
        <div class="content-wrapper">
		
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
					<div class="mt-4 py-2 border-top border-bottom">
                        <ul class="nav profile-navbar">
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idInprocess" onclick="showData('<?php echo $settingValueAwaitingStatus;?>');">
                              <i class="ti-timer"></i>
                              InProcess (<?php echo $qryCntInprocess; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idApproved" onclick="showDataApproved('<?php echo $settingValueApprovedStatus;?>');">
                              <i class="ti-check-box"></i>
                              Approved Lot (<?php echo $qryCntApproved; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idRejected" onclick="showData('<?php echo $settingValueRejectedStatus;?>');">
                              <i class="ti-na"></i>
                              Rejected (<?php echo $qryCntRejected; ?>)
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link" id="idCompleted" onclick="showDataApproved('<?php echo $settingValueCompletedStatus;?>');">
                              <i class="ti-target"></i>
                              Completed Lot(<?php echo $qryCntCompleted; ?>)
                            </a>
                          </li>
                          <!--<li class="nav-item">
                            <a href="#" class="nav-link" id="idAll" onclick="showData('');">
                              <i class="ti-notepad"></i>
                              All (<?php //echo $qryCntAll; ?>)
                            </a>
                          </li>-->
                        </ul>
                      </div>
					  
				</div>
			</div><br>
			
			<div class="row">
			<?php if($qryCntInprocess!=0){ ?>
			<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12' id="divCheckAll" style="display:none;">
				<p class="float-right">Select All <input class="cbCheckall" id="cbCheckall" type="checkbox" onclick='checkAll();' value="" /></p>
			</div>
			<?php }?>
			
		<!--	<div class="row">
				<div class="col-md-12 grid-margin stretch-card">
				
					<div id="row" class="table">
					</div>
				</div>
          </div>
			-->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 grid-margin stretch-card">
				
					
					<div class='card'>
				  
						<div class='card-body text-center'>
						
							<div class="row" id="tableData">
								
							</div>
						
					</div>
					
				</div>
          </div>   
		  <?php if($qryCntInprocess!=0){ ?>
		  <div id="divButton" style="display:none;">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 grid-margin stretch-card">
					<button type='button' id='btnAccept' class='btn btn-success' onclick='PutData();'>Accept</button>
					<button type='button' id='btnReject' class='btn btn-danger' onclick='showModal();'>Reject</button>
				</div>
          </div>
		 <?php }?>

				
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
var valsettingValueAwaitingStatus='<?php echo $settingValueAwaitingStatus;?>';

var valRejectionId = "";
$(document).ready(function(){
	showDataApproved('<?php echo $settingValueApprovedStatus;?>');
	
		$("#idInprocess").removeClass("active");
		$("#idApproved").addClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
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
function showData(id){
	if(id==valsettingValueAwaitingStatus){
		$("#idInprocess").addClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
		$("#divButton").css("display", "block");
		$("#divCheckAll").css("display", "block");
	}else if(id==valsettingValueApprovedStatus){
		$("#idInprocess").removeClass("active");
		$("#idApproved").addClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
		$("#divButton").css("display", "none");
		$("#divCheckAll").css("display", "none");
	}
	else if(id==valsettingValueRejectedStatus){
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").addClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
		$("#divButton").css("display", "none");
		$("#divCheckAll").css("display", "none");
	}
	else if(id==valsettingValueCompletedStatus){
		
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").addClass("active");
		$("#idAll").removeClass("active");
		$("#divButton").css("display", "none");
		$("#divCheckAll").css("display", "none");
	}
	else{
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").addClass("active");
		$("#divButton").css("display", "none");
		$("#divCheckAll").css("display", "none");
	}
	
	$.ajax({
		type:"POST",
		url:"apiGetSegregationList.php",
		data:{statustype:id},
		success:function(response){
			console.log(response);
			$("#tableData").html(response);

		}
	});
}
function showDataApproved(id){
	if(id==valsettingValueApprovedStatus){
		$("#idInprocess").removeClass("active");
		$("#idApproved").addClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").removeClass("active");
		$("#idAll").removeClass("active");
		$("#divButton").css("display", "none");
		$("#divCheckAll").css("display", "none");
	}
	else if(id==valsettingValueCompletedStatus){
		
		$("#idInprocess").removeClass("active");
		$("#idApproved").removeClass("active");
		$("#idRejected").removeClass("active");
		$("#idCompleted").addClass("active");
		$("#idAll").removeClass("active");
		$("#divButton").css("display", "none");
		$("#divCheckAll").css("display", "none");
	}
	$.ajax({
		type:"POST",
		url:"apiGetSegregationApprovedList.php",
		data:{statustype:id},
		success:function(response){
			console.log(response);
			
			$("#tableData").html(response);

		}
	});
}
function viewDetails(id){
	$.ajax({
		type:"POST",
		url:"apiGetViewSegregationList.php",
		data:{mix_waste_lot_id:id},
		success:function(response){
			console.log(response);
			
			$("#tableData").html(response);

		}
	});
}
function viewSeggregationDetails(id){
	$.ajax({
			type:"POST",
			url:"apiGetSegregationinfo.php",
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
function showModalViewInfo()
{	
	jQuery.noConflict();
	$("#ViewInfo").modal("show");
	
}
function closeModalViewReason() {
	
  $("#ViewInfo").modal("hide");
  location.reload();
}
function viewComment(id){
	$.ajax({
		type:"POST",
		url:"apiGetViewComment.php",
		data:{id:id,type:'comment'},
		success:function(response){
			console.log(response);
			showAlert("",response,"");
		}
	});
}
function viewSeggregationComment(id){
	$.ajax({
		type:"POST",
		url:"apiGetViewComment.php",
		data:{id:id,type:'segregation'},
		success:function(response){
			console.log(response);
			showAlert("",response,"");
		}
	});
}
function viewReasonDetails(id){
	$.ajax({
		type:"POST",
		url:"apiGetViewReason.php",
		data:{id:id},
		success:function(response){
			console.log(response);
			showAlert("",response,"");
		}
	});
}

function addRecord(id){
	window.location.href = "pgSegregate.php?id="+id;
}
function ViewRejected(id){
	window.location.href = "pgCollectionPointForm.php?type=Rejected&cp_id="+id;
}
function AcceptRecord(id){
	showModalQuantity();
}


function sendMail(id){
	$.ajax({
		type:"POST",
		url:"apiSendEmail.php",
		data:{po_id:id},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showData();
				showAlertRedirect("Success","Record Updated Successfully","success","pgSegregationList.php"); 
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
			
		}
	});
}
/*function sendMailReject(id){
	$.ajax({
		type:"POST",
		url:"apiSendEmailReject.php",
		data:{po_id:id,reason:$("#txtInputReason").val()},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Record Updated Successfully","success","pgSegregationList.php"); 
				
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
			
		}
	});
}*/
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
	
}
function showModalQuantity()
{	
	jQuery.noConflict();
	$("#modalQuantity").modal("show");
	
}
function closeModal() {
	
  $("#modalRejectedReason").modal("hide");
  location.reload();
}
function closeModalQuantity() {
	
  $("#modalQuantity").modal("hide");
  location.reload();
}
function adddataReject() {
	if(!validateBlank($("#txtInputReason").val())){
		setErrorOnBlur("txtInputReason");
	}
	else{
			var value="";
			$('.cbCheck:checkbox:checked').each(function(){
				value=value+$(this).val()+",";
			});
			str=value.replace(/[, ]+$/, "").trim();
				if(str==""){
					showAlert("Warning","Please select Collection Point","warning");
				}
				else{	
						$.ajax({
						type:"POST",
						url:"apiSaveLotInfoRejected.php",
						data:{str:str,reason:$("#txtInputReason").val()},	
						success:function(response){
						console.log(response);
							if(response=="Success"){
								showAlertRedirect("Success","Data Added Successfully","success","pgSegregationList.php");
							}
							else{
								showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
							}
						}
					});  
				}
	}
	
	
	
}
function checkIndividual(){
	document.getElementById("cbCheckall").checked = false;
}
function checkAll(){
	
	
	var yes = document.getElementById("cbCheckall");  
	if (yes.checked == true){  
		// selecting all checkboxes
		var checkboxes = document.getElementsByName('cbCheck');
		var values = [];
		// looping through all checkboxes
		for (var i = 0; i < checkboxes.length; i++) {
		  checkboxes[i].checked = true;
		  values.push(checkboxes[i].value);
		}
	}
	else{
		// deselecting all checkboxes
		var checkboxes = document.getElementsByName('cbCheck');
		var values = [];
		// looping through all checkboxes
		for (var i = 0; i < checkboxes.length; i++) {
		  checkboxes[i].checked = false;
		  values.push(checkboxes[i].value);
		}
		
	}
	
}
function PutData(){
	var value="";
	$('.cbCheck:checkbox:checked').each(function(){
		value=value+$(this).val()+",";
	});
	str=value.replace(/[, ]+$/, "").trim();
	if(str==""){
		showAlert("Warning","Please select Collection Point","warning");
	}
	else{	
		var strArray = str.split(",");
		var valTotalQuantity=0.00;
		for(var i = 0; i < strArray.length; i++){
			var arrStrInner = strArray[i].split("/");
            valTotalQuantity = valTotalQuantity + parseInt(arrStrInner[1]);
        }
		showModalQuantity();
		$("#txtReceivedQuantity").val(valTotalQuantity);
	}
	
}
function saveApproved(id){
	
	if(!validateBlank($("#txtReceivedQuantity").val())){
		setErrorOnBlur("txtReceivedQuantity");
	}
	else if(!validateBlank($("#txtActualQuantity").val())){
		setErrorOnBlur("txtActualQuantity");
	}
	else if(!validateBlank($("#txtComment").val())){
		setErrorOnBlur("txtComment");
	}
	else{
		showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
			if(confirmed==true){	
				
				var value="";
				$('.cbCheck:checkbox:checked').each(function(){
					value=value+$(this).val()+",";
				});
				str=value.replace(/[, ]+$/, "").trim();
				if(str==""){
					showAlert("Warning","Please select Collection Point","warning");
				}
				else{
						$.ajax({
						type:"POST",
						url:"apiSaveLotInfo.php",
						data:{str:str,txtActualQuantity:$("#txtActualQuantity").val(),txtComment:$("#txtComment").val()},	
						success:function(response){
						console.log(response);
							if(response=="Success"){
								showAlertRedirect("Success","Data Added Successfully","success","pgSegregationList.php");
							}
							else{
								showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
							}
						}
					});   
				}
	 
			}
		});
	
	}
}
</script>
</body>
</html>