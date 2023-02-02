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
	
$qry3 = "select company_id from tw_employee_registration where id = '".$_SESSION["employee_id"]."'";
$retVal3 = $sign->SelectF($qry3,'company_id');
$_SESSION["company_id"]=$retVal3;
$type = $_REQUEST["type"];
$po_id = $_REQUEST["po_id"];
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueMaterialOutward= $commonfunction->getSettingValue("MaterialOutward");

$settingValueEmployeeImagePathOther= $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");

$qry6 = "SELECT id,reason from tw_rejected_reason_master where panel='".$settingValueMaterialOutward."' Order by id desc";
$retVal6 = $sign->FunctionOption($qry6,'','reason','id');

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
  <!-- endinject -->
  <!-- inject:css -->
    <script src="../assets/js/custom/twCommonValidation.js"></script>
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
									
									 <?php  echo $retVal6;?>
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
						<div class="col-ld-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<div class="form-group row">	
								<div class="card-body" >
									<label class="col-sm-12">Rejection reason</label>
                                    <textarea class="form-control" id="txtViewReason" maxlength="5000"  placeholder="Reason of Rejection"></textarea>
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
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <h4 class="card-title">Material Inward <?php echo $type; ?></h4>
          </div>
		  <br>
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
<!-- End plugin js or this page -->
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
var valsettingValueEmployeeImagePathOther='<?php echo $settingValueEmployeeImagePathOther;?>';
var valsettingValuedisableicon='<?php echo $settingValuedisableicon;?>';
var valRejectionId = "";
var hdnIDDocType = "";
$(document).ready(function(){
	showData(valType);
});
function showData(valType){
	if(valType=="In Process"){
			$.ajax({
				type:"POST",
				url:"apiGetViewMaterialInwardInprocess.php",
				data:{valType:valType,po_id:valpo_id},
				success:function(response){
					console.log(response);
					$("#tableData").html(response);
					$('#ainfo').css({'cursor': 'pointer'});

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
				url:"apiGetViewMaterialInwardApproved.php",
				data:{valType:valType,po_id:valpo_id},
				success:function(response){
					console.log(response);
					$("#tableData").html(response);
					$('#ainfo').css({'cursor': 'pointer'});
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
				url:"apiGetViewMaterialInwardRejected.php",
				data:{valType:valType,po_id:valpo_id},
				success:function(response){
					console.log(response);
					$("#tableData").html(response);
					$('#ainfo').css({'cursor': 'pointer'});
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
function ViewInfo(id){
	$.ajax({
			type:"POST",
			url:"apiGetPOinfo.php",
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
function AcceptRecord(id){
	showConfirmAlert('Confirm action!', 'Are you sure to accept this record ?','question', function (confirmed){
		 $("#imgaccept").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
		if(confirmed==true){	
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";		
			$.ajax({
					type:"POST",
					url:"apiOutwardApproved.php",
					data:{id:id,po_id:valpo_id},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							sendMail(id);							
						}
						else if($.trim(response)=="Document"){
							showAlertRedirect("Warning","Please Upload/Generate all documents","warning","pgMaterialInward.php?type=In%20Process&po_id="+valpo_id);						
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
		url:"apiSendEmailMOAccept.php",
		data:{id:id},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showData();
				showAlertRedirect("Success","Record Updated Successfully","success","pgMaterialInward.php?type=Approved&po_id="+valpo_id);
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
		url:"apiSendEmailMOReject.php",
		data:{id:id,reason:$("#txtInputReason").val()},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showAlert("Success","Record Updated Successfully","success","pgMaterialInward.php?type=Rejected&po_id="+valpo_id);
				
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
function ViewReason(id){
	getReason(id);
}
function editRecord(id){
	window.location.href = "pgMaterialInwardForm.php?type=edit&id="+id;
}
function DocumentRecord(id){
	window.location.href = "pgViewInwardDocuments.php?id="+id+"&type="+valType;
}

function GRNRecordupload(id){
	window.location.href = "pgMaterialInwardAttachDocument.php?type=GRN&id="+id;
}
function GRNRecordgenerate(id){
	window.location.href = "pgInwardGRN.php?inward_id="+id;
}
function GRNRecordgenerateedit(id){
	window.location.href = "pgInwardGRN.php?inward_id="+id;
}
function QCRecordupload(id){
	window.location.href = "pgMaterialInwardAttachDocument.php?type=QC&id="+id;
}
function QCRecordgenerate(id){
	window.location.href = "pgInwardQualityCheck.php?inward_id="+id;
}
function DeleteRecordupload(modid){
	deleteRecordUpload(modid);
}
function DeleteRecordGRN(modid){
	deleteRecordGRN(modid);
}
function DeleteRecordQC(modid){
	deleteRecordQC(modid);
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
function adddata(id){
		var valcreated_by = '<?php echo $created_by; ?>';
		var valcur_date = '<?php echo $cur_date; ?>';
		var valip_address = '<?php echo $ip_address; ?>';
		var valquery = "insert into tw_material_inward_documents(inward_id,type,document,size,document_value,created_by,created_on,created_ip) values('"+id+"','"+hdnIDDocType+"','"+hdnIDimg+"','"+hdnIDsize+"','','"+valcreated_by+"','"+valcur_date+"','"+valip_address+"')";
		$.ajax({
		type:"POST",
		url:"apiCommonQuerySingle.php",
		data:{valquery:valquery},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Data Saved Successfully","success","pgMaterialInward.php?type=In Process"+"&po_id="+valpo_id);
				
			}else{
				showAlertRedirect("Error","Something Went Wrong. Please Try After Sometime","error");
			}
		}
	});     
}
function deleteRecord(id){
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
		if(confirmed==true){
			var valtablename="tw_material_inward";
			
			$.ajax({
					type:"POST",
					url:"apiDeleteData.php",
					data:{id:id,tablename:valtablename},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							showAlertRedirect("Success","Record Rejected Successfully","success","pgMaterialInward.php?type=Rejected&po_id="+valpo_id);
							window.location.reload();
						}
						else{
							showAlert("Warning","Something Went Wrong. Please Try After Sometime","warning");
						}
						
					}
				});
		}
	});
}
function deleteRecordUpload(modid){
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
		if(confirmed==true){
			var valtablename="tw_material_inward_documents";
			
			$.ajax({
					type:"POST",
					url:"apiDeleteData.php",
					data:{id:modid,tablename:valtablename},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							showAlertRedirect("Success","Record Deleted Successfully","success","pgMaterialInward.php?type=In Process&po_id="+valpo_id);
						}
						else{
							showAlert("Warning","Something Went Wrong. Please Try After Sometime","warning");
						}
						
					}
				});
		}
	});
}
function deleteRecordGRN(modid){
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
		if(confirmed==true){
			var valtablename="tw_material_inward_grn";
			
			$.ajax({
					type:"POST",
					url:"apiDeleteData.php",
					data:{id:modid,tablename:valtablename},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							showAlertRedirect("Success","Record Deleted Successfully","success","pgMaterialInward.php?type=In Process&po_id="+valpo_id);
						}
						else{
							showAlert("Warning","Something Went Wrong. Please Try After Sometime","warning");
						}
						
					}
				});
		}
	});
}
function deleteRecordQC(modid){
	showConfirmAlert('Confirm action!', 'Are you sure?','question', function (confirmed){
		if(confirmed==true){
			var valtablename="tw_material_inward_qc";
			
			$.ajax({
					type:"POST",
					url:"apiDeleteData.php",
					data:{id:modid,tablename:valtablename},
					success:function(response){
						console.log(response);
						if($.trim(response)=="Success"){
							showAlertRedirect("Success","Record Deleted Successfully","success","pgMaterialInward.php?type=In Process&po_id="+valpo_id);
						}
						else{
							showAlert("Warning","Something Went Wrong. Please Try After Sometime","warning");
						}
						
					}
				});
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
function showModalViewReason()
{	
	jQuery.noConflict();
	$("#modalViewReason").modal("show");
	
}
function closeModalViewReason() {
	
  $("#modalViewReason").modal("hide");
  location.reload();
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
function adddataReject() {

	if(!validateBlank($("#txtInputReason").val())){
		setErrorOnBlur("txtInputReason");
	}
	
	else{
			document.getElementById("alinkaccept").style.pointerEvents = "none";
			document.getElementById("alinkreject").style.pointerEvents = "none";
			$("#imgreject").attr("src",valsettingValueEmployeeImagePathOther+valsettingValuedisableicon);
			$.ajax({
			type:"POST",
			url:"apiInwardReject.php",
			data:{id:valRejectionId,reason:$("#txtInputReason").val()},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					showAlertRedirect("Success","Data Rejected Successfully","success","pgMaterialInward.php?type=In Process"+"&po_id="+valpo_id);
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


</script>
</body>
</html>