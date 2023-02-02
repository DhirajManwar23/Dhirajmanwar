<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
// Include class definition
include_once "function.php";
include_once("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueKYC= $commonfunction->getSettingValue("KYC");
$settingValuePemail=$sign->escapeString($settingValuePemail);
$settingValueDocumentPendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueVerifiedStatus = $commonfunction->getSettingValue("Verified Status");
$UserImagePathVerification = $commonfunction->getSettingValue("UserImagePathVerification");
$CompanyPanCard = $commonfunction->getSettingValue("PANCard");
$settingValueRejectedstatus = $commonfunction->getSettingValue("Rejected status");
$settingValueKycDoc=$commonfunction->getSettingValue("KycDoc");

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$created_by=$_SESSION["company_id"];
$Status = "";
$EmailQry="Select value from tw_company_contact where company_id='".$created_by."' AND contact_field='".$settingValuePemail."' ";
$EMAIL= $sign->SelectF($EmailQry,"value");
if($requesttype=="add"){
	
	$document_type = "";
	$document_number = "";
	$document_proof = "";
	$document_verification_status = "";
	$document_size = "";
	$documennt_purpose = "";
	$value = "";
	$created_on = $cur_date;
	$created_ip = $ip_address;
	$modified_by = $created_by;
	$modified_on = $cur_date;
	$modified_ip = $ip_address;
	
	$qry1 = "select ID,document_type_value from tw_document_type_master where visibility = 'true'  ORDER by priority, document_type_value  ASC";
	$retVal1 = $sign->FunctionOption($qry1,$Status,'document_type_value','ID');
	
	$qry4 = "select ID from tw_document_purpose_master where visibility = 'true' and document_purpose_value='".$settingValueKYC."'";
	$retVal4 = $sign->SelectF($qry4,'ID');
	
	$qry5 = "select ID,document_status from tw_document_verification_status_master where visibility = 'true'";
	$retVal5 = $sign->FunctionOption($qry5,$document_verification_status,'document_status','ID');

}
else{
	$qry3 = "SELECT cd.document_type,cd.document_number, cd.document_proof, cd.document_verification_status,cd.document_size,cd.documennt_purpose, cd.created_by, cd.created_on, cd.created_ip, cd.modified_by, cd.modified_on, cd.modified_ip ,cd.document_verification_status  FROM tw_company_document cd LEFT JOIN  tw_company_details cc ON cd.company_id = cc.id WHERE cd.id ='".$requestid."' ";
	$retVal3 = $sign->FunctionJSON($qry3);
	$decodedJSON = json_decode($retVal3);
	$document_type = $decodedJSON->response[0]->document_type;
	$document_number = $decodedJSON->response[1]->document_number;
	$document_proof = $decodedJSON->response[2]->document_proof;
	$document_verification_status = $decodedJSON->response[3]->document_verification_status;
	$document_size = $decodedJSON->response[4]->document_size;
	$documennt_purpose = $decodedJSON->response[5]->documennt_purpose;
	$created_by = $decodedJSON->response[6]->created_by;
	$created_on = $decodedJSON->response[7]->created_on;
	$created_ip = $decodedJSON->response[8]->created_ip;
	$modified_by = $decodedJSON->response[9]->modified_by;
	$modified_on = $decodedJSON->response[10]->modified_on;
	$modified_ip = $decodedJSON->response[11]->modified_ip;
	//$document_verification_status = $decodedJSON->response[12]->document_verification_status;

	
	$qry1 = "select ID,document_type_value from tw_document_type_master where visibility = 'true' ORDER by priority, document_type_value  ASC";
	$retVal1 = $sign->FunctionOption($qry1,$document_type,'document_type_value','ID');
	
	$qry4 = "select ID,document_purpose_value from tw_document_purpose_master where visibility = 'true' ORDER by priority, document_purpose_value  ASC";
    $retVal4 = $sign->FunctionOption($qry4,$documennt_purpose,'document_purpose_value','ID');
	
	$qry5 = "select ID,document_status from tw_document_verification_status_master where visibility = 'true' ORDER by priority, document_status  ASC";
	$retVal5 = $sign->FunctionOption($qry5,$document_verification_status,'document_status','ID');
}

$qry6="select count(*) as cnt from  tw_company_document where document_type='".$CompanyPanCard."'AND company_id='".$created_by."' ";
$retVal6 = $sign->Select($qry6);

$reasonQry="SELECT id,reason FROM tw_rejected_reason_master where visibility='true' AND panel='".$settingValueKycDoc."' order by priority ASC";
$Reasons=$sign->FunctionOption($reasonQry,"",'reason',"id");
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Company Documents</title>
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
  <link rel="stylesheet" href="../assets/css/custom/style.css" />
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
  <div class="modal fade" id="modalRejected" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
  <div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel"><i class="ti-receipt"></i>Add Remark</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
  </div>
  <div class="modal-body">
	
	  <div class="form-group">
		<label for="message-text" class="col-form-label font-weight-bold text-dark cursor">Rejection Reason:</label>
		<select class="form-control" placeholder="Reason of Rejection" id="ReasonMsg" >
			<option value="">Choose...</option>
			<?php echo  $Reasons; ?>
		</select>
	  </div>
   
  </div>
  <div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	<button type="button" class="btn btn-success" id="submitRejection" onclick="RejectionText(<?php echo $requestid; ?>);">Submit</button>
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
			  <h4 class="card-title">Company Documents</h4>
			  <div class="forms-sample">
				<div class="form-group row">
				  <label for="Document_Type" class="col-sm-3 col-form-label">Document Type<span class="text-danger">*</span></label>
				  <div class="col-sm-9">
				   <select id="selDocument_Type" <?php if($requesttype=="edit"){ echo "disabled";} ?> class="form-control form-control-sm">
						<?php echo $retVal1; ?>
					</select>
				  </div>
				</div>
				<div class="form-group row">
				  <label for="Document_Number" class="col-sm-3 col-form-label">Document Number<span class="text-danger">*</span></label>
				  <div class="col-sm-9">
					<input type="text" class="form-control form-control-sm" <?php if($requesttype=="edit"){ echo "disabled";} ?> maxlength="50" value="<?php echo $document_number; ?>" id="txtDocument_Number" placeholder="Document Number">
				  </div>
				</div>
				<div class="form-group row">
				  <label for="Document_Proof" class="col-sm-3 col-form-label">Document Proof<span class="text-danger">*</span></label>
				  <div class="col-sm-5">
					<input type="file" name="Document_Proof" <?php if($requesttype=="edit"){ echo "disabled";} ?> accept=".png, .jpg, .jpeg, .pdf" id="Document_Proof" placeholder="Document Proof" onchange="showname();" />				
				  </div>
					<?php if($requesttype=="edit"){
							if($document_proof!=""){ ?>
								<div class="col-sm-4" id="diveditimg">          
									<a href="<?php echo $UserImagePathVerification; ?><?php echo $EMAIL."/".$document_proof;?>" target="_blank">View<a/>
								</div>
					<?php }}?>
				</div>
				
			
				<div class="form-group row">
				<?php if($requesttype=="edit"){ ?>
					<?php  if($document_verification_status==$settingValueRejectedstatus || $document_verification_status==$settingValueVerifiedStatus) {
					} else {?>
					<button type="sumbit" class="btn btn-success mr-2" id="btnaccept" onclick="accept(<?php echo $requestid; ?>);">Accept</button>
					<?php } if($document_verification_status==$settingValueRejectedstatus || $document_verification_status==$settingValueVerifiedStatus) {
					} else {?>
					
					<button type="sumbit" class="btn btn-danger mr-2" id="btnreject" onclick="reject();">Reject</button>
				<?php }
				}
				else{?>
					<button type="sumbit" class="btn btn-success mr-2" id="btnadd" onclick="adddata();">Add</button>
					
				<?php }
				?>
			   </div>
			   <div class="card card-inverse-danger hide-div" id="ERROR" >	
					<div class="card-body">
						   <span id="EXIST" class="text-danger"></span>
						</p>		
					</div>
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
  <!-- endinject -->
</body>
<script src="../assets/css/jquery/jquery.min.js"></script>

 <script>
var hdnIDimg="";
var hdnIDfsize="";
var valcreated_by = "<?php echo $created_by;?>";
$( document ).ready(function() {
     $("#ERROR").css("display", "none");
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

function showname() {
	  var name = document.getElementById('Document_Proof'); 
	  hdnIDimg = name.files.item(0).name;
	 
	 var name = document.getElementById("Document_Proof").files[0].name;
	  var form_data2 = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['pdf','png','jpg','jpeg']) == -1) 
	  {
		$('#Document_Proof').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof").files[0]);
	  var f = document.getElementById("Document_Proof").files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 5000000)
	  {
		   alert("Image File Size is very big");
	  }
	  else
	  {
		form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);
		form_data2.append("id", valcreated_by);

		hdnIDfsize=fsize;
	   
	   $.ajax({
		url:"upload.php",
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
		}
	   });
	  }	 
};

function showModalOtp(){
		jQuery.noConflict();
		$("#modalRejected").modal("show");
		
    }
function RejectionText(reqid)
{
	
	var msg=$("#ReasonMsg").val();
	if(!validateBlank($("#ReasonMsg").val())){
			setErrorOnBlur("ReasonMsg");
	}
	else{
		
		document.getElementById("btnaccept").style.pointerEvents = "none";
		document.getElementById("btnreject").style.pointerEvents = "none";
		disableButton('#submitRejection','<i class="ti-timer"></i>Processing...');
		$.ajax({
		type:"POST",
		url:"apiRejectDocument.php",
		data:{Msg:msg,id:reqid},
		success:function(response){	
			console.log(response);
			if($.trim(response)=="Success"){
				
					showAlertRedirect("Success","Data Added Successfully","success","pgCompanyProfile.php?id="+valcreated_by);
					enableButton('#submitRejection','submit');
				}
				else if($.trim(response)=="error"){
				showAlert("warning","Value already exist","warning");
			}else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
				
			 	
		}	
		
	});
   }
}	


function adddata(){
	  
		var valcreated_by = "<?php echo $created_by;?>";
		var settingValueDocumentPendingStatus= "<?php echo $settingValueDocumentPendingStatus; ?>";
	    var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";
		var valrequesttype = "<?php echo $requesttype;?>";
		var valdocumentpurpose = "<?php echo $retVal4;?>";
		var PANCard = "<?php echo $retVal6;  ?>";
		var CompanyPanCard ="<?php echo $CompanyPanCard;  ?>";
		
		if(!validateBlank($("#txtDocument_Number").val())){
		setErrorOnBlur("txtDocument_Number");
	    }
		else if(!validateBlank($("#Document_Proof").val()) && valrequesttype=="add"  ){
		setErrorOnBlur("Document_Proof");
	   }
	   else if(PANCard!="0" && $("#selDocument_Type").val()==CompanyPanCard && valrequesttype=="add"){
			
				$("#ERROR").fadeIn();
				$("#EXIST").html("Pan card already uploded");
				$("#ERROR").fadeOut(5000);
			}
	  
		else{
		    if(valrequesttype=="add"){
		
			var valquery = "insert into tw_company_document(company_id,document_type,document_number,document_proof,document_verification_status,document_size,documennt_purpose,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#selDocument_Type").val()+"','"+$("#txtDocument_Number").val()+"','"+hdnIDimg+"','"+settingValueDocumentPendingStatus+"','"+hdnIDfsize+"','"+valdocumentpurpose+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			
			var valquerycount = "select count(*) as cnt from tw_company_document where document_number = '"+$("#txtDocument_Number").val()+"' and company_id='"+valcreated_by+"'";
		     }
		else{
				var valrequestid = "<?php echo $requestid;?>";
				if(hdnIDimg!=""){
					var valquery = "Update tw_company_document set document_type = '"+$("#selDocument_Type").val()+"' , document_number = '"+$("#txtDocument_Number").val()+"' , document_proof = '"+hdnIDimg+"', document_verification_status = '"+settingValueDocumentPendingStatus+"', document_size = '"+hdnIDfsize+"', documennt_purpose = '"+valdocumentpurpose+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";

				}
				else{
					var valquery = "Update tw_company_document set document_type = '"+$("#selDocument_Type").val()+"' , document_number = '"+$("#txtDocument_Number").val()+"', document_verification_status = '"+settingValueDocumentPendingStatus+"', documennt_purpose = '"+valdocumentpurpose+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
				}
				var valquerycount = "select count(*) as cnt from tw_company_document where document_number = '"+$("#txtDocument_Number").val()+"' and company_id!='"+valcreated_by+"'";
			}
			console.log(valquery);
			disableButton('#btncreate','<i class="ti-timer"></i> Processing...');
			$.ajax({
			type:"POST",
			url:"apiCommonQuery.php",
			data:{valquery:valquery,valquerycount:valquerycount},
			success:function(response){
			console.log(response);
			if(valrequesttype=="add"){
				enableButton('#btncreate','Add Record');
			}
			else{
				enableButton('#btncreate','Update Record');
			}
			if($.trim(response)=="Success"){
				if(valrequesttype=="add"){
					showAlertRedirect("Success","Data Added Successfully","success","pgCompanyProfile.php?id="+valcreated_by)
				}
				else{
					showAlertRedirect("Success","Data Updated Successfully","success","pgCompanyProfile.php?id="+valcreated_by)
				}
			}
			else if($.trim(response)=="Exist"){
					showAlert("warning","Value already exist","warning");
			}else{
				showAlert("error","Something Went Wrong. Please Try After Sometime","error");
			}
		}
	});   
  }

}


function reject(){
	showModalOtp();	
}

  function accept(reqid){
		
		var msg=$("#ReasonMsg").val();
	    var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";
		var valrequesttype = "<?php echo $requesttype;?>";
		var valdocumentpurpose = "<?php echo $retVal4;?>";
		if(!validateBlank($("#txtDocument_Number").val())){
			setErrorOnBlur("txtDocument_Number");
		}
		if(valrequesttype=="add"){
			var valquery = "insert into tw_employee_document(employee_id,document_type,document_number,document_proof,document_verification_status,document_size,document_purpose,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#selDocument_Type").val()+"','"+$("#txtDocument_Number").val()+"','"+hdnIDimg+"','Pending','"+hdnIDfsize+"','"+valdocumentpurpose+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			var valquerycount = "select count(*) as cnt from tw_employee_document where document_number = '"+$("#txtDocument_Number").val()+"' and employee_id='"+valcreated_by+"'";
		 
		}
		else{
				document.getElementById("btnaccept").style.pointerEvents = "none";
				document.getElementById("btnreject").style.pointerEvents = "none";
				var valrequestid = "<?php echo $requestid;?>";
				if(hdnIDimg!=""){
					var valquery = "Update tw_employee_document set document_type = '"+$("#selDocument_Type").val()+"' , document_number = '"+$("#txtDocument_Number").val()+"' , document_proof = '"+hdnIDimg+"', document_verification_status = 'Pending', document_size = '"+hdnIDfsize+"', document_purpose = '"+valdocumentpurpose+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";

				}
				else{
					var valquery = "Update tw_employee_document set document_type = '"+$("#selDocument_Type").val()+"' , document_number = '"+$("#txtDocument_Number").val()+"', document_verification_status = 'Pending', document_purpose = '"+valdocumentpurpose+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
					
				}
				var valquerycount = "select count(*) as cnt from tw_employee_document where document_number = '"+$("#txtDocument_Number").val()+"' and employee_id!='"+valcreated_by+"'";
			}
              disableButton('#btnaccept','<i class="ti-timer"></i> Processing...');
			  
		$.ajax({
		type:"POST",
		url:"apiAcceptDocument.php",
		data:{id:reqid,Msg:msg},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				if(valrequesttype=="add"){
					showAlertRedirect("Success","Data Added Successfully","success","pgCompanyProfile.php?id="+valcreated_by);
                    enableButton('#btnaccept','Accept');
				}
				else{
					showAlertRedirect("Success","Data Updated Successfully","success","pgCompanyProfile.php?id="+valcreated_by);
				}
			}
			else if($.trim(response)=="Exist"){
				showAlert("warning","Value already exist","warning");
				$("#txtValue").focus();
			}else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
		}
	  });
    	
 }
 </script>
 
</html>