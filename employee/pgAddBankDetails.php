<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogin.php");
}

$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];

include_once "commonFunctions.php";
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$EmployeeImagePathVerification= $commonfunction->getSettingValue("EmployeeImagePathVerification");
$PendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];

// Include class definition
include_once "function.php";
$sign=new Signup();
$Status = "";
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
	$qry3 = "SELECT cb.account_holder_name,cb.account_number, cb.bank_name, cb.ifsc_code,cb.branch_location,cb.account_type,cb.bank_account_proof_type, cb.bank_account_proof  ,cc.value FROM tw_employee_bankdetails cb LEFT JOIN tw_employee_contact cc ON cb.employee_id = cc.employee_id WHERE cb.id ='".$requestid."' and cc.contact_field='".$settingValuePemail."'";
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
	$value = $decodedJSON->response[8]->value;
	
	$qry1 = "select ID,bank_account_type_value from tw_bank_account_type_master where visibility = 'true' ORDER by priority, bank_account_type_value  ASC";
	$retVal1 = $sign->FunctionOption($qry1,$account_type,'bank_account_type_value','ID');
	
	$qry2 = "select ID,bank_account_proof_type_value from tw_bank_account_proof_type_master where visibility = 'true' ORDER by priority, bank_account_proof_type_value  ASC";
	$retVal2 = $sign->FunctionOption($qry2,$bank_account_proof_type,'bank_account_proof_type_value','ID');
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
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Bank Details</h4>
                  <div class="forms-sample">
					<div class="form-group row">
                      <label for="Account_Holder_Name" class="col-sm-3 col-form-label">Account Holder Name <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $account_holder_name; ?>" id="txtAccount_Holder_Name" placeholder="Account Holder Name">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Account_Number" class="col-sm-3 col-form-label">Account Number <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="20"  value="<?php echo $account_number; ?>" id="txtAccount_Number" placeholder="Account Number">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Bank_Name" class="col-sm-3 col-form-label">Bank Name <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $bank_name; ?>" id="txtBank_Name" placeholder="Bank Name">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="IFSC_code" class="col-sm-3 col-form-label">IFSC Code <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="11"  value="<?php echo $ifsc_code; ?>" id="txtIFSC_code" placeholder="IFSC Code">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Branch_Location" class="col-sm-3 col-form-label">Branch Location <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"  maxlength="50" value="<?php echo $branch_location; ?>" id="txtBranch_Location" placeholder="Branch Location">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Account_Type" class="col-sm-3 col-form-label">Account Type <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
						<select id="txtAccount_Type" class="form-control form-control-sm">
							<?php echo $retVal1; ?>
						</select>
					  </div>
                    </div>
					<div class="form-group row">
                      <label for="Bank_Account_Proof_Type" class="col-sm-3 col-form-label">Bank Account Proof Type <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <select id="selBank_Account_Proof_Type" class="form-control form-control-sm">
							<?php echo $retVal2; ?>
						</select>
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Document_Proof" class="col-sm-3 col-form-label">Bank Account Proof <span class="text-danger">*</span></label>
                      <div class="col-sm-5">
						<input type="file" name="Document_Proof" accept=".png, .jpg, .jpeg, .pdf" placeholder="Bank Account Proof" id="Document_Proof" onchange="showname();" />				
                      </div>
					  <?php if($requesttype=="edit"){ 
								if($bank_account_proof!=""){?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $EmployeeImagePathVerification; ?><?php echo $value; ?>/<?php echo $bank_account_proof;?>" target="_blank">View<a/>
									</div>
								<?php } }?>
                    </div>
                    <button type="submit" class="btn btn-success mr-2" id="add" onclick="adddata()"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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

<script src="../assets/css/jquery/jquery.min.js"></script>

 <script>
 var hdnIDimg="";
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
	   
	   $.ajax({
		url:"upload.php",
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
		}
	   });
	  }
		  
		 
};

  function adddata(){
	var valcreated_by = "<?php echo $created_by;?>";
	var valcreated_on = "<?php echo $cur_date;?>";
	var valcreated_ip = "<?php echo $ip_address;?>";
	var valrequesttype = "<?php echo $requesttype;?>";
	var PendingStatus = "<?php echo $PendingStatus;?>";
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
		else if(!validateBlank($("#Document_Proof").val()) && valrequesttype=="add" ){
			
		    setErrorOnBlur("Document_Proof");
	        
		}
		
		else{
		  disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
		
		
		if(valrequesttype=="add"){
			var valquery = "insert into tw_employee_bankdetails(employee_id,account_holder_name,account_number,bank_name,ifsc_code,branch_location,account_type,bank_account_proof_type,bank_account_proof,bank_account_status,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#txtAccount_Holder_Name").val()+"','"+$("#txtAccount_Number").val()+"','"+$("#txtBank_Name").val()+"','"+$("#txtIFSC_code").val()+"','"+$("#txtBranch_Location").val()+"','"+$("#txtAccount_Type").val()+"','"+$("#selBank_Account_Proof_Type").val()+"','"+hdnIDimg+"','"+PendingStatus+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			var valquerycount = "select count(*) as cnt from tw_employee_bankdetails where account_number = '"+$("#txtAccount_Number").val()+"' and employee_id='"+valcreated_by+"'";
			
		}
		else{
			var valrequestid = "<?php echo $requestid;?>";
			var valquerycount = "select count(*) as cnt from tw_employee_bankdetails where account_number = '"+$("#txtAccount_Number").val()+"' and employee_id!='"+valcreated_by+"'";
		
			
			if(hdnIDimg!=""){
				var valquery = "Update tw_employee_bankdetails set account_holder_name = '"+$("#txtAccount_Holder_Name").val()+"' , account_number = '"+$("#txtAccount_Number").val()+"', bank_name = '"+$("#txtBank_Name").val()+"', ifsc_code = '"+$("#txtIFSC_code").val()+"', branch_location = '"+$("#txtBranch_Location").val()+"', account_type = '"+$("#txtAccount_Type").val()+"', bank_account_proof_type = '"+$("#selBank_Account_Proof_Type").val()+"', bank_account_proof = '"+hdnIDimg+"', bank_account_status ='"+PendingStatus+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";

			}
			else{
				var valquery = "Update tw_employee_bankdetails set account_holder_name = '"+$("#txtAccount_Holder_Name").val()+"' , account_number = '"+$("#txtAccount_Number").val()+"', bank_name = '"+$("#txtBank_Name").val()+"', ifsc_code = '"+$("#txtIFSC_code").val()+"', branch_location = '"+$("#txtBranch_Location").val()+"', account_type = '"+$("#txtAccount_Type").val()+"', bank_account_proof_type = '"+$("#selBank_Account_Proof_Type").val()+"', bank_account_status = '"+PendingStatus+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
		
			}
		}
		$.ajax({
		type:"POST",
		url:"apiEmployeeProfile.php",
		data:{valquery:valquery,valquerycount:valquerycount},
		success:function(response){
			
			console.log(response);
			if(valrequesttype=="add"){
				enableButton('#add','Add');
			}
			else{
				enableButton('#add','Update');
			}	
			if($.trim(response)=="Success"){
				if(valrequesttype=="add"){
					showAlertRedirect("Success","Data Added Successfully","success","pgEmployeeProfile.php");
					enableButton('#add','Add');
				}
				else{
					showAlertRedirect("Success","Data Updated Successfully","success","pgEmployeeProfile.php");
					enableButton('#add','Update');
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
}
 
 </script>
 </body>
</html>