<?php 
session_start();
 if(!isset($_SESSION["company_id"])){
	header("Location:pgLogin.php");
}
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
include_once "commonFunctions.php";
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
$requesttype = $_REQUEST["type"];
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["company_id"];


// Include class definition
include_once "function.php";
$sign=new Signup();
$Status = "";
$settingValueDocumentPendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$UserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueRejectedstatus = $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus = $commonfunction->getSettingValue("Verified Status");
$settingValuePemail=$sign->escapeString($settingValuePemail);
$settingValueBankDoc=$commonfunction->getSettingValue("BankDoc");
$settingValueKycDoc=$commonfunction->getSettingValue("KycDoc");

$EmailQry="Select value from tw_company_contact where company_id='".$created_by."' AND contact_field='".$settingValuePemail."' ";
$EMAIL= $sign->SelectF($EmailQry,"value");

if($requesttype=="add"){
	
	$qry1 = "select ID,bank_account_type_value from tw_bank_account_type_master where visibility = 'true' ORDER by priority, bank_account_type_value  ASC";
	$retVal1 = $sign->FunctionOption($qry1,$Status,'bank_account_type_value','ID');
	
	$qry2 = "select ID,bank_account_proof_type_value from tw_bank_account_proof_type_master where visibility = 'true' ORDER by priority, bank_account_proof_type_value  ASC";
	$retVal2 = $sign->FunctionOption($qry2,$Status,'bank_account_proof_type_value','ID');
	
	$account_holder_name = "";
	$account_number = "";
	$bank_name = "";
	$ifsc_code = "";
	$branch_location = "";
	$account_type = "";
	$bank_account_proof_type = "";
	$bank_account_proof = "";
	$value = "";
	
}
else{

	$qry3 = "SELECT cb.account_holder_name,cb.account_number, cb.bank_name, cb.ifsc_code,cb.branch_location,cb.account_type,cb.bank_account_proof_type, cb.bank_account_proof,cb.bank_account_status FROM tw_company_bankdetails cb LEFT JOIN tw_company_details cc ON cb.company_id = cc.id WHERE cb.id ='".$requestid."'";
	$retVal3 = $sign->FunctionJSON($qry3);
	$decodedJSON = json_decode($retVal3);
	$account_holder_name = $decodedJSON->response[0]->account_holder_name;
	$account_number = $decodedJSON->response[1]->account_number;
	$bank_name = $decodedJSON->response[2]->bank_name;
	$ifsc_code = $decodedJSON->response[3]->ifsc_code;
	$branch_location = $decodedJSON->response[4]->branch_location;
	$account_type = $decodedJSON->response[5]->account_type;
	$bank_account_proof_type = $decodedJSON->response[6]->bank_account_proof_type;
	$bank_account_proof = $decodedJSON->response[7]->bank_account_proof;
	$bank_account_status = $decodedJSON->response[8]->bank_account_status;
	
	
	$qry1 = "select ID,bank_account_type_value from tw_bank_account_type_master where visibility = 'true' ORDER by priority, bank_account_type_value  ASC";
	$retVal1 = $sign->FunctionOption($qry1,$account_type,'bank_account_type_value','ID');
	
	$qry2 = "select ID,bank_account_proof_type_value from tw_bank_account_proof_type_master where visibility = 'true' ORDER by priority, bank_account_proof_type_value  ASC";
	$retVal2 = $sign->FunctionOption($qry2,$bank_account_proof_type,'bank_account_proof_type_value','ID');
	
	$qry3 = "select ID,description from tw_bank_account_status_master where visibility = 'true' ORDER by priority, description ASC";
	$retVal3 = $sign->FunctionOption($qry3,$bank_account_proof_type,'description','ID');
	
	$reasonQry="SELECT id,reason FROM tw_rejected_reason_master where visibility='true' AND panel='".$settingValueBankDoc."' order by priority ASC";
    $Reasons=$sign->FunctionOption($reasonQry,"",'reason',"id");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Bank Details</title>
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
                  <h4 class="card-title">Bank Details</h4>
                  <div class="forms-sample">
					<div class="form-group row">
                      <label for="Account_Holder_Name" class="col-sm-3 col-form-label">Account Holder Name<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50" <?php if($requesttype=="edit"){ echo "disabled";} ?> value="<?php echo $account_holder_name; ?>" id="txtAccount_Holder_Name" placeholder="Account Holder Name">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Account_Number" class="col-sm-3 col-form-label">Account Number<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" <?php if($requesttype=="edit"){ echo "disabled";} ?> maxlength="100"  value="<?php echo $account_number; ?>" id="txtAccount_Number" placeholder="Account Number">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Bank_Name" class="col-sm-3 col-form-label">Bank Name<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" <?php if($requesttype=="edit"){ echo "disabled";} ?>  maxlength="50" value="<?php echo $bank_name; ?>" id="txtBank_Name" placeholder="Bank Name">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="IFSC_code" class="col-sm-3 col-form-label">IFSC Code<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" <?php if($requesttype=="edit"){ echo "disabled";} ?> maxlength="11"  value="<?php echo $ifsc_code; ?>" id="txtIFSC_code" placeholder="IFSC Code">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Branch_Location" class="col-sm-3 col-form-label">Branch Location<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" <?php if($requesttype=="edit"){ echo "disabled";} ?>  maxlength="50" value="<?php echo $branch_location; ?>" id="txtBranch_Location" placeholder="Branch Location">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Account_Type" class="col-sm-3 col-form-label">Account Type<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
						<select id="txtAccount_Type" <?php if($requesttype=="edit"){ echo "disabled";} ?> class="form-control form-control-sm">
							<?php echo $retVal1; ?>
						</select>
					  </div>
                    </div>
					<div class="form-group row">
                      <label for="Bank_Account_Proof_Type" class="col-sm-3 col-form-label">Bank Account Proof Type<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <select id="selBank_Account_Proof_Type" <?php if($requesttype=="edit"){ echo "disabled";} ?> class="form-control form-control-sm">
							<?php echo $retVal2; ?>
						</select>
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Document_Proof" class="col-sm-3 col-form-label">Bank Account Proof<span class="text-danger">*</span></label>
                      <div class="col-sm-5">
						<input type="file" name="Document_Proof" <?php if($requesttype=="edit"){ echo "disabled";} ?> accept=".png, .jpg, .jpeg, .pdf" placeholder="Bank Account Proof" id="Document_Proof" onchange="showname();" />				
                      </div>
					  
					  <?php if($requesttype=="edit"){ 
								if($bank_account_proof!=""){?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $UserImagePathVerification.$EMAIL."/".$bank_account_proof; ?>" target="_blank">View<a/>
									</div>
								<?php } }?>
                    </div>
					<?php if($requesttype=="edit"){ ?>
							<?php if($bank_account_status==$settingValueVerifiedStatus || $bank_account_status==$settingValueRejectedstatus )  {  ?>
						
						<?php }  else {?>
						<button type="sumbit" class="btn btn-success mr-2" id="btnaccept" onclick="accept(<?php echo $requestid; ?>);">Accept</button>
						
						
						<?php } if($bank_account_status==$settingValueRejectedstatus ||  $bank_account_status==$settingValueVerifiedStatus)  {  ?>
						
						<?php }  else {?>
						<button type="sumbit" class="btn btn-danger mr-2" id="btnreject" onclick="reject();">Reject</button>
					<?php }
					}
					else{?>
						<button type="sumbit" class="btn btn-success mr-2" id="btnadd" onclick="adddata();">Add</button>
					<?php }
					?>
                    
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
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
  <!-- endinject -->
</body>
<script src="../assets/css/jquery/jquery.min.js"></script>

 <script>
 var hdnIDimg="";
 var valcreated_by = "<?php echo $created_by;?>";
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
$("#txtIFSC_code").blur(function()
{
	removeError(txtIFSC_code);
	if ($("#txtIFSC_code").val()!="")
	{
		if(!isIfsc($("#txtIFSC_code").val())){
			setError(txtIFSC_code);
		}
		else
		{
			removeError(txtIFSC_code);
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
		  showAlert("warning","Image File Size is very big","warning");
	  
	  }
	  else
	  {
		form_data2.append("Document_Proof", document.getElementById('Document_Proof').files[0]);
		form_data2.append("id", valcreated_by);
	   
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
function adddata()
{
	
	var valcreated_by = "<?php echo $created_by;?>";
	var valcreated_on = "<?php echo $cur_date;?>";
	var valcreated_ip = "<?php echo $ip_address;?>";
	var valrequesttype = "<?php echo $requesttype;?>";
	var valsettingValueDocumentStatus = "<?php echo $settingValueDocumentPendingStatus;?>";
		
	
	if(!validateBlank($("#txtAccount_Holder_Name").val())){
		setErrorOnBlur("txtAccount_Holder_Name");
	}
	else if(!validateBlank($("#txtAccount_Number").val())){
		setErrorOnBlur("txtAccount_Number");
	}
	else if(!validateBlank($("#txtBank_Name").val())){
		setErrorOnBlur("txtBank_Name");
	}
	else if(!validateBlank($("#txtIFSC_code").val())){
		setErrorOnBlur("txtIFSC_code");
	}
	else if(!isIfsc($("#txtIFSC_code").val())){
		setError(txtIFSC_code);
		$("#txtIFSC_code").focus();
	}
	else if(!validateBlank($("#txtBranch_Location").val())){
		setErrorOnBlur("txtBranch_Location");
	}
	else if(!validateBlank($("#txtAccount_Type").val())){
		setErrorOnBlur("txtAccount_Type");
	}
	else if(!validateBlank($("#selBank_Account_Proof_Type").val())){
		setErrorOnBlur("selBank_Account_Proof_Type");
	}
	else if(!validateBlank($("#Document_Proof").val()) && valrequesttype=="add" ){
			
		    setErrorOnBlur("Document_Proof");
	        
	}
	else{
		
		if(valrequesttype=="add"){
			var valquery = "insert into tw_company_bankdetails(company_id,account_holder_name,account_number,bank_name,ifsc_code,branch_location,account_type,bank_account_proof_type,bank_account_proof,bank_account_status,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#txtAccount_Holder_Name").val()+"','"+$("#txtAccount_Number").val()+"','"+$("#txtBank_Name").val()+"','"+$("#txtIFSC_code").val()+"','"+$("#txtBranch_Location").val()+"','"+$("#txtAccount_Type").val()+"','"+$("#selBank_Account_Proof_Type").val()+"','"+hdnIDimg+"','"+valsettingValueDocumentStatus+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			var valquerycount = "select count(*) as cnt from tw_company_bankdetails where account_number = '"+$("#txtAccount_Number").val()+"' and company_id='"+valcreated_by+"'";
		}
		else{
			var valrequestid = "<?php echo $requestid;?>";
			var valquerycount = "select count(*) as cnt from tw_company_bankdetails where account_number = '"+$("#txtAccount_Number").val()+"' and company_id!='"+valcreated_by+"'";
			if(hdnIDimg!=""){
				var valquery = "Update tw_company_bankdetails set account_holder_name = '"+$("#txtAccount_Holder_Name").val()+"' , account_number = '"+$("#txtAccount_Number").val()+"', bank_name = '"+$("#txtBank_Name").val()+"', ifsc_code = '"+$("#txtIFSC_code").val()+"', branch_location = '"+$("#txtBranch_Location").val()+"', account_type = '"+$("#txtAccount_Type").val()+"', bank_account_proof_type = '"+$("#selBank_Account_Proof_Type").val()+"', bank_account_proof = '"+hdnIDimg+"', bank_account_status = '"+valsettingValueDocumentStatus+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";

			}
			else{
				var valquery = "Update tw_company_bankdetails set account_holder_name = '"+$("#txtAccount_Holder_Name").val()+"' , account_number = '"+$("#txtAccount_Number").val()+"', bank_name = '"+$("#txtBank_Name").val()+"', ifsc_code = '"+$("#txtIFSC_code").val()+"', branch_location = '"+$("#txtBranch_Location").val()+"', account_type = '"+$("#txtAccount_Type").val()+"', bank_account_proof_type = '"+$("#selBank_Account_Proof_Type").val()+"', bank_account_status = '"+valsettingValueDocumentStatus+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
		
			}
		}
		  disableButton('#btncreate','<i class="ti-timer"></i> Processing...');
		  
		$.ajax({
		type:"POST",
		url:"apiCommonQuery.php",
		data:{valquery:valquery,valquerycount:valquerycount},
		success:function(response){
			if(valrequesttype=="add"){
				enableButton('#btncreate','Add');
			}
			else{
				enableButton('#btncreate','Update');
			}
			
			if($.trim(response)=="Success"){
				if(valrequesttype=="add"){
					showAlertRedirect("Success","Data Added Successfully","success","pgCompanyProfile.php?id="+valcreated_by);
				}
				else{
					showAlertRedirect("Success","Data Updated Successfully","success","pgCompanyProfile.php");
				}
			}
			else if($.trim(response)=="Exist"){
					showAlert("Warning","Value already exist","warning");
			}else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
			}
		}
	});   
  }
}

function RejectionText(reqid)
{
	var msg=$("#ReasonMsg").val();
	if(!validateBlank($("#ReasonMsg").val())){
		setErrorOnBlur("ReasonMsg");
	}
	else if(!validateBlank($("#ReasonMsg").val())){
		setErrorOnBlur("ReasonMsg");
	}
    	
	else{
		document.getElementById("btnaccept").style.pointerEvents = "none";
		document.getElementById("btnreject").style.pointerEvents = "none";
		disableButton('#submitRejection','<i class="ti-timer"></i> Processing...');
	
		$.ajax({
			type:"POST",
			url:"apiRejectBankDocument.php",
			data:{Msg:msg,id:reqid},
			success:function(response){	
				console.log(response);
				if($.trim(response)=="Success"){
					
						showAlertRedirect("Success","Status Rejected Successfully","success","pgCompanyProfile.php?type=edit&id="+valcreated_by);
						enableButton('#submitRejection','Submit');
					}
					else if($.trim(response)=="error"){
					showAlert("Warning","Value already exist","warning");
				}else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
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
	  if(!validateBlank($("#txtAccount_Holder_Name").val())){
			setErrorOnBlur("txtAccount_Holder_Name");
		}
		else if(!validateBlank($("#txtAccount_Number").val())){
			setErrorOnBlur("txtAccount_Number");
		}
		else if(!validateBlank($("#txtBank_Name").val())){
			setErrorOnBlur("txtBank_Name");
		}else if(!validateBlank($("#txtIFSC_code").val())){
			setErrorOnBlur("txtIFSC_code");
		}
		else if(!isIfsc($("#txtIFSC_code").val())){
			setError(txtIFSC_code);
			$("#txtIFSC_code").focus();
		}
		else if(!validateBlank($("#txtBranch_Location").val())){
			setErrorOnBlur("txtBranch_Location");
		}else if(!validateBlank($("#txtAccount_Type").val())){
			setErrorOnBlur("txtAccount_Type");
		}else if(!validateBlank($("#selBank_Account_Proof_Type").val())){
			setErrorOnBlur("selBank_Account_Proof_Type");
		}
		
		else{
			document.getElementById("btnaccept").style.pointerEvents = "none";
			document.getElementById("btnreject").style.pointerEvents = "none";
			disableButton('#btnaccept','<i class="ti-timer"></i> Processing...');
			 
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			var valrequesttype = "<?php echo $requesttype;?>";
			
			
			$.ajax({
			type:"POST",
			url:"apiAcceptbankDetails.php",
			data:{Msg:msg,reqid:reqid},
			success:function(response){
				
				console.log(response);
				if(valrequesttype=="add"){
					enableButton('#btnaccept','Accept');
				}
				else{
					enableButton('#btnaccept','Accept');
				}
				
				if($.trim(response)=="Success"){
					if(valrequesttype=="add"){
						showAlertRedirect("Success","Status Accepted Successfully","success","pgCompanyProfile.php?id="+valcreated_by);
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgCompanyProfile.php?id="+valcreated_by);
					}
				}
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Value already exist","warning");
					$("#txtValue").focus();
				}else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
			}
	});
  }    
}
 
 </script>
 
</html>