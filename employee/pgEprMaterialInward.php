<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
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
$settingValueOtherid= $commonfunction->getSettingValue("Otherid");

	
$qry3 = "select company_id from tw_employee_registration where id = '".$_SESSION["employee_id"]."'";
$retVal3 = $sign->SelectF($qry3,'company_id');
$_SESSION["company_id"]=$retVal3;
$type=$sign->escapeString($_REQUEST["type"]);

$po_id = $_REQUEST["po_id"];
$request = $_REQUEST["req"];
$requesttype = $_REQUEST["type"];
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");

$reasonQry="SELECT id,reason FROM tw_rejected_reason_master where visibility='true' AND panel='EPR' order by priority ASC";

$Reasons=$sign->FunctionOption($reasonQry,"",'reason',"id");
if($type=="In Process"){
	$qry4 = "select count(*) as cnt from tw_temp where po_id = '".$po_id."' and status='".$settingValueAwaitingStatus."'";
}
else if($type=="Approved"){
	$qry4 = "select count(*) as cnt from tw_temp where po_id = '".$po_id."' and status='".$settingValueAwaitingStatus."'";
}
else{
	$qry4 = "select count(*) as cnt from tw_temp where po_id = '".$po_id."' and status='".$settingValueRejectedStatus."'";
}

$querycnt = $sign->Select($qry4);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Material Inward</title>
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
  <!-- ==============MODAL START ================= -->
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modalViewReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
					
									<label class="col-sm-12">Rejection reason</label>
                                    <textarea class="form-control" id="txtViewReason" maxlength="5000"  placeholder="Reason of Rejection"></textarea>
								
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
  <div class="modal fade" data-keyboard="false" data-backdrop="static" id="ViewInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
								<div class="card-body" id="MaterialInfo">
								
								</div>
							</div>
									  
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
            <div class="col-md-12 grid-margin stretch-card">	
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">View <?php echo $type; ?></h4>
				  
				  <div class="card" id="divPo">
					<div class="card-body">
						<div class="form-group row" id="divDate">
							<div class="col-ld-4 col-md-4 col-sm-4 col-xs-4 col-4">
								<label>Start Date</label>
								<input type="date" class="form-control" id="txtStartDate" placeholder="Start Date">							
							</div>
							<div class="col-ld-4 col-md-4 col-sm-4 col-xs-4 col-4">
								<label>End Date</label>
								<input type="date" class="form-control" id="txtEndDate" placeholder="End Date">							
							</div>
							<div class="col-ld-4 col-md-4 col-sm-4 col-xs-4 col-4">
							<?php if($type=="Approved"){ ?>
								<a onclick="filter();" class="float-right " title="Date Filter">  	Date Filter<i class="ti-filter float-right pointer-cursor"></i></a>		
								<a href="pgEPRSGenerateDocuments.php?po_id=<?php echo $po_id; ?>" class="float-right pointer-cursor" ><i class="fa fa-download" aria-hidden="true"></i> Document |  </a><a onClick="CSV();" class="float-right pointer-cursor" ><i class="fa fa-download" aria-hidden="true"></i> Export To Excel |  </a>
							<?php } ?>
												
							</div>
						</div>
				  
				  
				  
						
						
						 <div class="table-responsive tw-top-right-scroll">
							<div class="table-responsive Flipped">
								<table id="tableData" class="table FContent">
								  
								</table>
							 </div>
						 </div>
					  
                </div>
				<?php if($type=="In Process"){
					if($querycnt!=0){ ?>
				<div id="divButton">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 grid-margin stretch-card">
						<button type='button' id='btnAccept' class='btn btn-success' onclick='PutData(0,"check");'>Accept</button>
						<button type='button' id='btnReject' class='btn btn-danger' onclick='showModal(0,"check");'>Reject</button>
					</div>
			  </div>
				<?php } }?>
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
var valType = "<?php echo $type; ?>";
var valpo_id = "<?php echo $po_id; ?>";
var request = "<?php echo $request; ?>";
var valsettingValueEmployeeImagePathOther='<?php echo $settingValueEmployeeImagePathOther;?>';
var valsettingValuedisableicon='<?php echo $settingValuedisableicon;?>';
var valsettingValueOther='<?php echo $settingValueOther;?>';
var valsettingValueOtherid='<?php echo $settingValueOtherid; ?>';
var requesttype = "<?php echo $requesttype; ?>";
var valRejectionId = "";
var TableID="";
var hdnIDDocType = "";
var varrejid = "";
var varrejtype = "";
$(document).ready(function(){
	showData(valType);
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
function showData(valType){
	if(valType=="In Process" && request==""){
			$.ajax({
				type:"POST",
				url:"apiGetEprViewMaterialInwardInprocess.php",
				data:{valType:valType,po_id:valpo_id,request:request,requesttype:requesttype},
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
	else if(valType=="Approved"){
		$.ajax({
				type:"POST",
				url:"apiGetEprpolistViewMaterialApproved.php",
				data:{valType:valType,po_id:valpo_id},
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
	else if(valType=="Rejected"){
		$.ajax({
				type:"POST",
				url:"apiGetEprpolistViewMaterialApproved.php",
				data:{valType:valType,po_id:valpo_id},
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
		
	}
	
function filter(){
	valtype='date';
	$.ajax({
		type:"POST",
		url:"apiGetEPRSDocumentUpload.php",
		data:{poid:valpo_id,type:valtype,startdate:$("#txtStartDate").val(),enddate:$("#txtEndDate").val()},
		success:function(response){
			console.log(response);
			$("#tableData").html(response);
			
		}
	});

}




function RejectRecord(id,tID){
	showConfirmAlert('Confirm action!', 'Are you sure to reject this record?','question', function (confirmed){
		if(confirmed==true){
			valRejectionId=id;	
			TableID=tID
			showModal();
		}
	});
}
function viewReason(id,tempid){
	if(id==valsettingValueOtherid){
		var valquery="Select reasontext as reason from tw_temp where id="+tempid;
	}
	else{
		var valquery="Select reason from tw_rejected_reason_master where id="+id;
	}
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



/* function adddataReject() {
	if(!validateBlank($("#txtInputReason").val())){
		setErrorOnBlur("txtInputReason");
	}
	else{
			
			disableButton('#Status','<i class="ti-timer"></i> Processing...');
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
			$("#imgreject").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
			var valsettingValueRejectedStatus = '<?php echo $settingValueRejectedStatus; ?>';
			var valquery = "Update  tw_epr_material_assign_info set status = '"+valsettingValueRejectedStatus+"' ,	reason = '"+$("#txtInputReason").val()+"' where id="+TableID;
						
			$.ajax({
			type:"POST",
			url:"apiRejectMaterial.php",
			data:{valquery:valquery,TableID:TableID,reason:$("#txtInputReason").val()},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					//closeModal();
					sendMailReject(valRejectionId);
					//showAlertRedirect("Success","Record Updated Successfully","success","pgEprpo.php"); 
				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
				
			}
		});   
	}
}	 */
function sendMailReject(id){
	$.ajax({
		type:"POST",
		url:"apiEprSendEmailReject.php",
		data:{po_id:id,reason:$("#txtInputReason").val()},
		success:function(response){
			
			console.log(response);
			if($.trim(response)=="Success"){
				enableButton('#Status','Sumbit');
				showAlertRedirect("Success","Record Updated Successfully","success","pgEprpo.php"); 
				 
				
			}
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
			
		}
	});
}

function AcceptRecord(id,tid){
	showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
		$("#imgaccept").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
		if(confirmed==true){	
			
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
			$.ajax({
					type:"POST",
					url:"apiAcceptEprPo.php",
					data:{id:id,po_id:valpo_id,tid:tid},
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
				showData(valType)
				showAlertRedirect("Success","Record Updated Successfully","success","pgEprpo.php"); 
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


function showname(id){
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;
	  var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg','pdf']) == -1) 
	  {
		$('#Document_Proof').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
	  var f = document.getElementById("Document_Proof").files[0];
	  var fsize = f.size||f.fileSize;
	  
	  var path = "../assets/images/Documents/Employee/Outward"+name;
	  var result = checkFileExist(path);
	  if(fsize > 5000000)
	  {
		   alert("Image File Size is very big");
	  }
	 else if (result == true) {
				showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
					if(confirmed==true){
							form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);

						   $.ajax({
							url:"uploadoutward.php",
							method:"POST",
							data: form_data2,
							contentType: false,
							cache: false,
							processData: false,
							beforeSend:function(){
								//$('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
							},   
							success:function(data)
							
							{
								console.log(data);
								hdnIDimg=data;
								adddata(id);
							}
						   });
					}
					
				});
		} 
	  else
	  {
			form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);

		   $.ajax({
			url:"uploadoutward.php",
			method:"POST",
			data: form_data2,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend:function(){
				//$('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
			},   
			success:function(data)
			
			{
				console.log(data);
				hdnIDimg=data;
				hdnIDsize=fsize;
				adddata(id);
			}
		   });
	  }
		  
		 
};
function shownameQC(id){
	  var name = document.getElementById('Document_ProofQC'); 
	  hdnIDimg = name.files.item(0).name;
	  var name = document.getElementById("Document_ProofQC").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['png','jpg','jpeg','pdf']) == -1) 
	  {
		$('#Document_ProofQC').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_ProofQC").files[0]);
	  var f = document.getElementById("Document_ProofQC").files[0];
	  var fsize = f.size||f.fileSize;
	  
	  var path = "../assets/images/Documents/Employee/Outward"+name;
	  var result = checkFileExist(path);
	  if(fsize > 5000000)
	  {
		   alert("Image File Size is very big");
	  }
	 else if (result == true) {
				showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
					if(confirmed==true){
							form_data2.append("Document_ProofQC", document.getElementById('Document_ProofQC').files[0]);

						   $.ajax({
							url:"uploadoutwardQC.php",
							method:"POST",
							data: form_data2,
							contentType: false,
							cache: false,
							processData: false,
							beforeSend:function(){
								//$('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
							},   
							success:function(data)
							
							{
								console.log(data);
								hdnIDimg=data;
								adddata(id);
							}
						   });
					}
					
				});
		} 
	  else
	  {
			form_data2.append("Document_ProofQC", document.getElementById('Document_ProofQC').files[0]);

		   $.ajax({
			url:"uploadoutwardQC.php",
			method:"POST",
			data: form_data2,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend:function(){
				//$('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
			},   
			success:function(data)
			
			{
				console.log(data);
				hdnIDimg=data;
				hdnIDsize=fsize;
				adddata(id);
			}
		   });
	  }
		  
		 
};

function showModal()
{	
	jQuery.noConflict();
	$("#modalRejectedReason").modal("show");
	
}
function showModalViewInfo()
{	
	jQuery.noConflict();
	$("#ViewInfo").modal("show");
	
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
function PutData(id,type){
	if(type=="check")
	{
		var value="";
		$('.cbCheck:checkbox:checked').each(function(){
			value=value+$(this).val()+",";
		});
		str=value.replace(/[, ]+$/, "").trim();
	}
	else
	{
		str=id;
	}
	if(str==""){
		showAlert("Warning","Please select Data","warning");
	}
	else{	
		if(type=="check")
		{
			document.getElementById("btnAccept").style.pointerEvents = "none";
			document.getElementById("btnReject").style.pointerEvents = "none";
		}
		else{
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
		}
		showConfirmAlert('Confirm action!', 'Are you sure to accept this record?','question', function (confirmed){
			if(confirmed==true){	
				$.ajax({
					type:"POST",
					url:"apiAcceptEPRSDocumentData.php",
					data:{str:str,type:type,po_id:valpo_id},	
					success:function(response){
					console.log(response);
						if(response=="Success"){
							showAlertRedirect("Success","Data Added Successfully","success","pgEprMaterialInward.php?type=In%20Process&po_id="+valpo_id+"&req="+request);
						}
						else{
							showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
						}
					}
				}); 

			}
			else{
				location.reload();
			}
		});			
	}
	
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
	
	if(varrejtype=="check")
			{
				var value="";
				$('.cbCheck:checkbox:checked').each(function(){
					value=value+$(this).val()+",";
				});
				str=value.replace(/[, ]+$/, "").trim();
			}
			else
			{
				str=varrejid;
			}
			if(str==""){
				showAlert("Warning","Please select Data for rejection","warning");
			}
			else{
				if(varrejtype=="check")
				{
					document.getElementById("btnAccept").style.pointerEvents = "none";
					document.getElementById("btnReject").style.pointerEvents = "none";
				}
				else{
					document.getElementById("alinkaccept").style.pointerEvents = "none";
					document.getElementById("alinkreject").style.pointerEvents = "none";
				}
				showConfirmAlert('Confirm action!', 'Are you sure to Reject this record?','question', function (confirmed){
				if(confirmed==true){
						$.ajax({
							type:"POST",
							url:"apiRejectEPRSDocumentData.php",
							data:{str:str,reason:$("#txtInputReason").val(),reasontext:$("#txReason").val(),type:varrejtype},	
							success:function(response){
							console.log(response);
								if(response=="Success"){
									showAlertRedirect("Success","Data Rejected Successfully","success","pgEprMaterialInward.php?type=In%20Process&po_id="+valpo_id+"&req="+request);
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
			
}
function showModal(id,type)
{	
	varrejid = id;
	varrejtype = type;
	jQuery.noConflict();
	$("#modalRejectedReason").modal("show");
	
}
function closeModal() {
	
  $("#modalRejectedReason").modal("hide");
  location.reload();
}
function CSV(){
	var valfilertype="";
	if($("#txtStartDate").val()!="" && $("#txtEndDate").val()!="")
	{
		valfilertype="date";
		
	}
	else{
		valfilertype="viewall";
	}
	
		$.ajax({
			type:"POST",
			url:"EPRSExportDataCSV.php",
			data:{type:valfilertype,po_id:valpo_id,startdate:$("#txtStartDate").val(),enddate:$("#txtEndDate").val()},
			success:function(response){
				console.log(response);
				if($.trim(response)=="success"){
					$(location).attr('href','EPRSExportDataCSV.csv');
				} 
			}
		});
}
</script>
</body>

</html>