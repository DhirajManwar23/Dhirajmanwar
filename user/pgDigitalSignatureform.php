<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$form_type="";
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$created_by=$_SESSION["company_id"];
$UserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$Employeeqry="SELECT id,employee_name FROM tw_employee_registration where company_id='".$created_by."'";
$retVal2 = $sign->FunctionOption($Employeeqry,"",'employee_name',"id"); 


	
$qry1 = "SELECT id,printable_document_value from tw_printable_document_master where visibility='true' and id not in (select DISTINCT(	form_type) from tw_digital_signature where company_id='".$created_by."') order by priority asc";
$Documents = $sign->FunctionOption($qry1,$form_type,'printable_document_value','id');	
	
$ip_address= $commonfunction->getIPAddress();
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$approved_by="";
$prepared_by="";
$for_company="";
$employee_id="";

$value="";
$id="";
if($requesttype=="edit"){
	$DetailsQry="SELECT approved_by,prepared_by,for_company,employee_id,form_type,id FROM `tw_digital_signature` where id='".$requestid."'";
	$retVal3 = $sign->FunctionJSON($DetailsQry);
	$decodedJSON = json_decode($retVal3);
	$approved_by = $decodedJSON->response[0]->approved_by;
	$prepared_by = $decodedJSON->response[1]->prepared_by;
	$for_company = $decodedJSON->response[2]->for_company;
	$employee_id = $decodedJSON->response[3]->employee_id;
	$form_type = $decodedJSON->response[4]->form_type;
	$id = $decodedJSON->response[5]->id;
	
	$emailQry="SELECT value FROM `tw_company_contact` where company_id='".$created_by."' AND contact_field='".$settingValuePemail."'";
	$value=$sign->SelectF($emailQry,"value");
	
	$Documentqry="SELECT id,printable_document_value FROM tw_printable_document_master ";
	$Documents = $sign->FunctionOption($Documentqry,$form_type,'printable_document_value',"id");
	
	
	// $Documentqry="SELECT id,printable_document_value FROM tw_printable_document_master ";
	// $Documents = $sign->FunctionOption($Documentqry,$form_type,'printable_document_value',"id");
	
	$Employeeqry="SELECT id,employee_name FROM tw_employee_registration where company_id='".$created_by."'";
	$retVal2 = $sign->FunctionOption($Employeeqry,$employee_id,'employee_name',"id");
	
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |Digital Signature</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
                  <h4 class="card-title">Digital Signature</h4>
					<div class="row">
						<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<label for="cars">Select Document Type<code>*</code></label>
							<br>
						<select name="document type" id="document_type" class="form-control">
							<?php echo $Documents; ?>
						</select>
						</div>
						<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<label for="cars">Select Employee<code>*</code></label>
							<br>
							<select name="Employee" id="Employee" class="form-control">
								<?php //echo $retVal2; ?>
								<option value="0" <?php if($employee_id==0){ echo "selected";} ?>>All Employee</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
						<label for="cars">Approved By</label>
						<a type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="deleteimg(<?php echo $id; ?>,'approved_by');"><?php if($approved_by==""){  } else { ?><i class="icon-trash"></i>  <?php  } ?></a>
						<input type="file" name="Document_Proof_Approved_By" accept=".png, .jpg, .jpeg" id="Document_Proof_Approved_By" placeholder="Document" onchange="shownameApprovedBy();"/>	
								<?php if($requesttype=="edit"){ 
								if($approved_by!=""){?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $UserImagePathVerification; ?><?php echo $value."/".$approved_by;?>" target="_blank">View<a/>
									</div>
								<?php } }?>						
						</div>					
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="cars">Prepared By</label>
							<a type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="deleteimg(<?php echo  $id; ?>,'prepared_by');"><?php if($prepared_by==""){  } else { ?><i class="icon-trash"></i> <?php } ?></a>
							<input type="file" name="Document_Proof_Prepared_By" accept=".png, .jpg, .jpeg" id="Document_Proof_Prepared_By" placeholder="Document" onchange="shownamePreparedBy();" />
								<?php if($requesttype=="edit"){ 
								if($prepared_by!=""){?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $UserImagePathVerification; ?><?php echo $value."/".$prepared_by;?>" target="_blank">View<a/>
									</div>
								<?php } }?>								
						</div>
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="cars">For Company</label>
							<a type="button" class="btn btn-link btn-rounded btn-fw float-right" onclick="deleteimg(<?php echo $id; ?>,'for_company');"><?php if($for_company==""){  } else { ?><i class="icon-trash"></i><?php } ?></a>
							<input type="file" name="Document_Proof_For_Company" accept=".png, .jpg, .jpeg" id="Document_Proof_For_Company" placeholder="Document" onchange="shownameForCompany();" />	
								<?php if($requesttype=="edit"){ 
								if($for_company!=""){?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $UserImagePathVerification; ?><?php echo $value."/".$for_company;?>" target="_blank">View<a/>
									</div>
								<?php } }?>	
						</div>
					</div>
				  <button type="button" class="btn btn-success" onclick="adddata();">Upload</button>
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
var hdnIDimgApprovedBy="";
var hdnIDimgPreparedBy="";
var hdnIDimgForCompany="";
var company_id="<?php echo $created_by; ?>";
var value="<?php echo $value; ?>";
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
	var valtext = "Please upload " + valplaceholder;
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
function deleteimg(id,name){
	var blank="";
	var Imagename="";
	if(name=="approved_by"){
	var valquery="Update tw_digital_signature set approved_by='"+blank+"' where id='"+id+"' ";
	Imagename= "<?php echo $approved_by;  ?>";
	}
	else if(name=="prepared_by"){
	var valquery="Update tw_digital_signature set prepared_by='"+blank+"' where id='"+id+"' ";
	Imagename= "<?php echo $prepared_by;  ?>";
	}
	else if(name=="for_company"){
	var valquery="Update tw_digital_signature set for_company='"+blank+"' where id='"+id+"' ";
	Imagename= "<?php echo $for_company;  ?>";
	}
		 $.ajax({
				type:"POST",
				url:"apiDelteImg.php",
				data:{valquery:valquery,empvalue:value,Imagename:Imagename},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
					
							showAlertRedirect("Success","Data Deleted Successfully","success","pgDigitalSignature.php");
						
					}
					else if($.trim(response)=="Exist"){
							showAlert("Warning","Value already exist","warning");
					}else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
					}
				}
			 });


}

function shownameApprovedBy() {
	var name = document.getElementById('Document_Proof_Approved_By'); 
	  hdnIDimgApprovedBy = name.files.item(0).name;
	 if(hdnIDimgPreparedBy==hdnIDimgApprovedBy){
		
		 showAlert("warning","Image couldn`t be same","warning");
		$('#Document_Proof_Approved_By').val('');
  }
  else if(hdnIDimgApprovedBy==hdnIDimgForCompany){
		
		 showAlert("warning","Image couldn`t be same","warning");
		$('#Document_Proof_Approved_By').val('');
  }
	 var name = document.getElementById("Document_Proof_Approved_By").files[0].name;
	  var form_dataApproved_By = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['pdf','png','jpg','jpeg']) == -1) 
	  {
	   $('#Document_Proof_Approved_By').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof_Approved_By").files[0]);
	  var f = document.getElementById("Document_Proof_Approved_By").files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 5000000)
	  {
		  showAlert("warning","Image File Size is very big","warning");
		
		}
	  else
	  {
		form_dataApproved_By.append("Document_Proof_Approved_By", document.getElementById('Document_Proof_Approved_By').files[0]);
	   
	   $.ajax({
		url:"uploadApprovedBy.php",
		method:"POST",
		data: form_dataApproved_By,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend:function(){
		},   
		success:function(data)
		
		{
			
			hdnIDimgApprovedBy=data;
			console.log(hdnIDimgApprovedBy);
		}
	   });
	  } 
		  
		 
};

function shownamePreparedBy() {
 var name = document.getElementById('Document_Proof_Prepared_By'); 
 hdnIDimgPreparedBy = name.files.item(0).name;
 if(hdnIDimgPreparedBy==hdnIDimgApprovedBy){
		
		 showAlert("warning","Image couldn`t be same","warning");
		$('#Document_Proof_Prepared_By').val('');
  }
  else if(hdnIDimgPreparedBy==hdnIDimgForCompany){
		
		 showAlert("warning","Image couldn`t be same","warning");
		$('#Document_Proof_Prepared_By').val('');
  }
	  else{
	 var name = document.getElementById("Document_Proof_Prepared_By").files[0].name;
	  var form_dataPrepared_By = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['pdf','png','jpg','jpeg']) == -1) 
	  {
	   $('#Document_Proof_Prepared_By').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof_Prepared_By").files[0]);
	  var f = document.getElementById("Document_Proof_Prepared_By").files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 5000000)
	  {
		  showAlert("warning","Image File Size is very big","warning");
		
	  }
	  
	  else
	  { 
		form_dataPrepared_By.append("Document_Proof_Prepared_By", document.getElementById('Document_Proof_Prepared_By').files[0]);
	   $.ajax({
		url:"uploadPreparedBy.php",
		method:"POST",
		data: form_dataPrepared_By,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend:function(){
		},   
		success:function(data)
		
		{
			
			hdnIDimgPreparedBy=data;
			console.log(hdnIDimgPreparedBy);
		}
	   });
		
	  } 
		  
}	 
};

function shownameForCompany() {
	var name = document.getElementById('Document_Proof_For_Company'); 
	  hdnIDimgForCompany = name.files.item(0).name;
	  if(hdnIDimgPreparedBy==hdnIDimgForCompany){
		
		 showAlert("warning","Image couldn`t be same","warning");
		$('#Document_Proof_For_Company').val('');
      }
	   else if(hdnIDimgApprovedBy==hdnIDimgForCompany){
		
		 showAlert("warning","Image couldn`t be same","warning");
		$('#Document_Proof_For_Company').val('');
      }
	  else{
	 var name = document.getElementById("Document_Proof_For_Company").files[0].name;
	  var form_dataFor_Company = new FormData();
	  var ext = name.split('.').pop().toLowerCase();
	  if(jQuery.inArray(ext, ['pdf','png','jpg','jpeg']) == -1) 
	  {
	   $('#Document_Proof_For_Company').val("");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("Document_Proof_For_Company").files[0]);
	  var f = document.getElementById("Document_Proof_For_Company").files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 5000000)
	  {
		  showAlert("warning","Image File Size is very big","warning");
		
		}
	  else
	  {
		form_dataFor_Company.append("Document_Proof_For_Company", document.getElementById('Document_Proof_For_Company').files[0]);
	   
	   $.ajax({
		url:"uploadForCompany.php",
		method:"POST",
		data: form_dataFor_Company,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend:function(){
		},   
		success:function(data)
		
		{
			
			hdnIDimgForCompany=data;
			console.log(hdnIDimgForCompany);
		}
	   });
	  } 
	  }	  
		 
}
function adddata(){

var valrequesttype = '<?php echo $requesttype;?>';
var valcreated_by = "<?php echo $created_by;?>";
var valcreated_on = "<?php echo $cur_date;?>";
var valcreated_ip = "<?php echo $ip_address;?>";
var valrequesttype = "<?php echo $requesttype;?>";

if(valrequesttype=="add"){
	 if(hdnIDimgApprovedBy=="" && hdnIDimgPreparedBy=="" && hdnIDimgForCompany==""){
			showAlert("Warning","Please Upload alteast one document","warning");
		} 
		else{
			 
			var valquery = "insert into tw_digital_signature (company_id,employee_id,form_type , 	approved_by,prepared_by,for_company,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#Employee").val()+"','"+$("#document_type").val()+"','"+hdnIDimgApprovedBy+"','"+hdnIDimgPreparedBy+"','"+hdnIDimgForCompany+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			var valquerycount = "select count(*) as cnt from tw_digital_signature  where  company_id='"+valcreated_by+"' and form_type='"+$("#document_type").val()+"'";
		
			disableButton('#btncreate','<i class="ti-timer"></i>Processing');
			$.ajax({
				type:"POST",
				url:"apiCompanyQuery.php",
				data:{valquery:valquery,valquerycount:valquerycount},
				success:function(response){
					console.log(response);
					if($.trim(response)=="Success"){
					
							showAlertRedirect("Success","Data Added Successfully","success","pgDigitalSignature.php");
						
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
else{
	
	editRecord();
}  

}

function editRecord(){

var valrequesttype = '<?php echo $requesttype;?>';
var valcreated_by = "<?php echo $created_by;?>";
var valcreated_on = "<?php echo $cur_date;?>";
var valcreated_ip = "<?php echo $ip_address;?>";
var valrequesttype = "<?php echo $requesttype;?>";
var valrequestid = "<?php echo $requestid;?>";
	 
	if(hdnIDimgApprovedBy=="" && hdnIDimgPreparedBy=="" && hdnIDimgForCompany==""){
		showAlert("Warning","Please Upload alteast one document","warning");
	} 
	else{
		
		if(hdnIDimgApprovedBy!=""){ 
			var valquery  = "Update tw_digital_signature set form_type = '"+$("#document_type").val()+"', employee_id = '"+$("#Employee").val()+"',approved_by = '"+hdnIDimgApprovedBy+"',  modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"'";
		
		}
		else if(hdnIDimgPreparedBy!="" ){
			var valquery = "Update tw_digital_signature set form_type = '"+$("#document_type").val()+"', employee_id = '"+$("#Employee").val()+"',prepared_by  = '"+hdnIDimgPreparedBy+"',  modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"'";
			
		}
		else if(hdnIDimgForCompany!="" ){
			var valquery = "Update tw_digital_signature set form_type = '"+$("#document_type").val()+"', employee_id = '"+$("#Employee").val()+"',for_company  = '"+hdnIDimgForCompany+"',  modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"'";
		}
			
		
		var valquerycount = "select count(*) as cnt from tw_digital_signature  where  id!='"+valrequestid+"'  and form_type='"+$("#document_type").val()+"' and company_id='"+valcreated_by+"'";
		disableButton('#btncreate','<i class="ti-timer"></i>Processing');
		$.ajax({
		type:"POST",
		url:"apiCompanyQuery.php",
		data:{valquery:valquery,valquerycount:valquerycount},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
						showAlertRedirect("Success","Data Added Successfully","success","pgDigitalSignature.php");
					
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
</script>
</body>
</html>