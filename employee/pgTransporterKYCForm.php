<?php 
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	include_once "commonFunctions.php";
	$commonfunction=new Common();
	$ip_address= $commonfunction->getIPAddress();
	$requesttype = $_REQUEST["type"];
	$requestid = $_REQUEST["id"];
	$transporter_id=$_REQUEST["tid"];

	$company_id=$_SESSION["company_id"];
	date_default_timezone_set("Asia/Kolkata");
	$cur_date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["employee_id"];
	$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");	
	$settingValueEmployeePrimaryEmail = $commonfunction->getSettingValue("Primary Email");	
	$settingValueStatuspending= $commonfunction->getSettingValue("Pending Status");
	include_once "function.php";
	$sign=new Signup();
	$document_type = "";
	$document_number = "";
	$document_proof = "";
	$document_verification_status = "";

	
	$transporterqry="select id from tw_transport_master where company_id='".$company_id."'";
	$transporter=$sign->SelectF($transporterqry,"id");

	 $EmailQry="SELECT value FROM `tw_employee_contact` where employee_id='".$created_by."' AND contact_field='".$settingValueEmployeePrimaryEmail."'";
	 $Email=$sign->SelectF($EmailQry,"value");
	
	if($requesttype=="edit"){
		echo $qry="select document_type,document_number,document_proof,document_verification_status from tw_transporter_kyc where id='".$requestid."'";
		$retVal = $sign->FunctionJSON($qry);
		$decodedJSON = json_decode($retVal);
		$document_type = $decodedJSON->response[0]->document_type; 
		$document_number = $decodedJSON->response[1]->document_number; 
		$document_proof = $decodedJSON->response[2]->document_proof;
		$document_verification_status = $decodedJSON->response[3]->document_verification_status;
		//$transporter_id = $decodedJSON->response[4]->transporter_id;
	
	}
	
	$qry5 = "select id,document_type_value from tw_document_type_master where visibility='true' Order by priority,document_type_value";
	$retVal5 = $sign->FunctionOption($qry5,$document_type,'document_type_value','document_type_value');
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Transporter Documents</title>
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
                  <h4 class="card-title">Transporter Documents</h4>
                  <div class="forms-sample">
					<div class="form-group row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
							<label for="Document_Type">Document Type<code>*</code></label>
								<select id="selDocument_Type" class="form-control" >
								<?php echo $retVal5;?>
							</select>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
						  <label for="Document_Number">Document Number<code>*</code></label>
							<input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $document_number; ?>" id="txtDocument_Number" placeholder="Document Number">
						</div>
                    </div>
					
					<div class="form-group row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
						  <label for="Document_Number" >Document Proof<code>*</code></label>
							  
								<input accept="image/*" type="file" class="form-control form-control-sm" onchange="showname();" id="Document_Proof" maxlength="30" placeholder="Document Proof" value="<?php echo  $settingValueEmployeeImagePathVerification.$Email."/".$document_proof; ?>">
							  
							  <?php if($requesttype=="edit"){ 
										if($document_proof!=""){?>
											<div class="col-sm-4" id="diveditimg">          
												<a href="<?php echo $settingValueEmployeeImagePathVerification.$Email."/".$document_proof; ?>" target="_blank">View<a/>
											</div>
							  <?php } }?>
						  </div>
                    </div>
					<?php if($requesttype=="add"){ ?>		
						<button type="submit" class="btn btn-success mr-2" id="btncreate" onclick="checkvalidation()">Add </button>
					<?php }else{ ?>
						<button type="submit" class="btn btn-success mr-2" id="btncreate" onclick="checkvalidation()">Update </button>
					<?php } ?>
					
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
 var varimg="<?php echo $document_proof;?>";
 var valrequesttype = "<?php echo $requesttype;?>";
 if(varimg==""){
	 hdnIDimg="";
 }
 else{
	 hdnIDimg=varimg;
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
	  var email = "<?php echo $settingValueEmployeePrimaryEmail; ?>";
	  var path = "<?php echo $settingValueEmployeeImagePathVerification; ?>"+email+"/"+name;
	  var result = checkFileExist(path,email);

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
			$('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
		},   
		success:function(data)
		
		{
			console.log(data);
			hdnIDimg=data;
			varimg=data;
		}
	   });
	  }
		  
		 
};
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
  function checkvalidation(){
	  if(valrequesttype=="add"){
		   if(!validateBlank($("#txtDocument_Number").val())){
				setErrorOnBlur("txtDocument_Number");
			}
			else if(!validateBlank($("#Document_Proof").val())){
				setErrorOnBlur("Document_Proof");
			}	
			else{
				adddata();
			}
	  }
	  else{
		  if(!validateBlank($("#txtDocument_Number").val())){
				setErrorOnBlur("txtDocument_Number");
			}
			else{
				adddata();
			}
	  }
  }
  function adddata(){
	
		var valcreated_by = "<?php echo $created_by;?>";
	    var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";
		
		var valrequestid = "<?php echo $requestid;?>";
		var transporter_id = "<?php echo $transporter_id;?>";
		var settingValueStatuspending="<?php echo $settingValueStatuspending;?>";
		
		
		if(valrequesttype=="add"){	
			var valquery = "insert into tw_transporter_kyc(transporter_id,document_type,document_number,document_proof,document_verification_status,created_by,created_on,created_ip)values('"+transporter_id+"','"+$("#selDocument_Type").val()+"','"+$("#txtDocument_Number").val()+"','"+hdnIDimg+"','"+settingValueStatuspending+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			
			 console.log(valquery);
			var valquerycount = "select count(*) as cnt from tw_transporter_kyc where document_number = '"+$("#txtDocument_Number").val()+"' and transporter_id='"+transporter_id+"'";
		}
		else{
			if(hdnIDimg!=""){
				valquery = "update tw_transporter_kyc set document_type='"+$("#selDocument_Type").val()+"',document_number='"+$("#txtDocument_Number").val()+"',document_proof='"+hdnIDimg+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";

			}
			else{
				valquery = "update tw_transporter_kyc set document_type='"+$("#selDocument_Type").val()+"',document_number='"+$("#txtDocument_Number").val()+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
				console.log(valquery);
			}
			 valquerycount = "select count(*) as cnt from tw_transporter_kyc where document_number = '"+$("#txtDocument_Number").val()+"' and id!='"+valrequestid+"'";
		}
		
		
		$('#btncreate').attr("disabled","true");
		$('#btncreate').css('cursor', 'no-drop');
		$('#btncreate').removeClass('btn-success');
		$('#btncreate').addClass('btn-secondary');//secondary;
		$('#btncreate').html('<i class="ti-timer"></i> Processing...');
		$.ajax({
		type:"POST",
		url:"apiEmployeeProfile.php",
		data:{valquery:valquery,valquerycount:valquerycount},
		success:function(response){
			console.log(response);
			$('#btncreate').removeAttr('disabled');
			$('#btncreate').css('cursor', 'pointer');
			$('#btncreate').removeClass('btn-warning');
			$('#btncreate').addClass('btn-success');
			$('#btncreate').html('Add');
			if($.trim(response)=="Success"){
				if(valrequesttype=="add"){
					showAlertRedirect("Success","Data Added Successfully","success","pgKycDetails.php?tid=" + transporter_id)
				}
				else{
					showAlertRedirect("Success","Data Updated Successfully","success","pgKycDetails.php?tid=" + transporter_id)
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

 </script>
 
</html>