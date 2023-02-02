<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeProfile.php");
}

$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];


//whether ip is from share internet
include_once "commonFunctions.php";
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
$EmployeeImagePathVerification= $commonfunction->getSettingValue("EmployeeImagePathVerification");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$PendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueKYC= $commonfunction->getSettingValue("KYC");
// Include class definition
include_once "function.php";
$sign=new Signup();
$Status = "";
$EmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");
$EmpPanCard = $commonfunction->getSettingValue("PANCard");

$settingValuePemail=$sign->escapeString($settingValuePemail);
 $EmailQry="Select value from tw_employee_contact where employee_id='".$created_by."'  ";
$EMAIL= $sign->SelectF($EmailQry,"value");
if($requesttype=="add"){
	
	$document_type = "";
	$document_number = "";
	$document_proof = "";
	$document_verification_status = "";
	$document_size = "";
	$documennt_purpose = "";
	$value = "";
	
	$qry1 = "select ID,document_type_value from tw_document_type_master where visibility = 'true' ORDER by priority, document_type_value  ASC";
	$retVal1 = $sign->FunctionOption($qry1,$Status,'document_type_value','ID');
	
	$qry4 = "select ID from tw_document_purpose_master where visibility = 'true' and document_purpose_value='".$settingValueKYC."'";
	$retVal4 = $sign->SelectF($qry4,'ID');

}
else{
	$qry3 = "SELECT document_type,document_number, document_proof, document_verification_status,document_size,document_purpose FROM tw_employee_document  where id ='".$requestid."'";
	$retVal3 = $sign->FunctionJSON($qry3);
	$decodedJSON = json_decode($retVal3);
	$document_type = $decodedJSON->response[0]->document_type;
	$document_number = $decodedJSON->response[1]->document_number;
	$document_proof = $decodedJSON->response[2]->document_proof;
	$document_verification_status = $decodedJSON->response[3]->document_verification_status;
	$document_size = $decodedJSON->response[4]->document_size;
	$documennt_purpose = $decodedJSON->response[5]->document_purpose;
	
	$qry1 = "select ID,document_type_value from tw_document_type_master where visibility = 'true' ORDER by priority, document_type_value  ASC";
	$retVal1 = $sign->FunctionOption($qry1,$document_type,'document_type_value','ID');
	
	$qry4 = "select ID from tw_document_purpose_master where visibility = 'true' and document_purpose_value='".$settingValueKYC."'";
	$retVal4 = $sign->SelectF($qry4,'ID');
}
$qry6="select count(*) as cnt from  tw_employee_document where document_type='".$EmpPanCard."' AND employee_id='".$created_by."'";
$retVal6 = $sign->Select($qry6);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Employee Documents</title>
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
                  <h4 class="card-title">Employee Documents</h4>
                  <div class="forms-sample">
					<div class="form-group row">
                      <label for="Document_Type" class="col-sm-3 col-form-label">Document Type <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                       <select id="selDocument_Type" class="form-control form-control-sm">
							<?php echo $retVal1; ?>
						</select>
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Document_Number" class="col-sm-3 col-form-label">Document Number <span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $document_number; ?>" id="txtDocument_Number" placeholder="Document Number">
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Document_Proof" class="col-sm-3 col-form-label">Document Proof <span class="text-danger">*</span></label>
                      <div class="col-sm-5">
						<input type="file" name="Document_Proof" accept=".png, .jpg, .jpeg, .pdf" id="Document_Proof" placeholder="Document Proof" onchange="showname();" />				
                      </div>
					  	<?php if($requesttype=="edit"){
								if($document_proof!=""){ ?>
									<div class="col-sm-4" id="diveditimg">          
										<a href="<?php echo $EmployeeImagePathVerification; ?><?php echo $EMAIL."/".$document_proof;?>" target="_blank">View<a/>
									</div>
						<?php }}?>
						
						
		
                    </div>
                    <button type="submit" class="btn btn-success mr-2" id="add" onclick="adddata()"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
                  </div>
				    <div class="card card-inverse-danger" id="ERROR">	
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
var hdnIDfsize="";
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

  function adddata(){
	  
		var valcreated_by = "<?php echo $created_by;?>";
	    var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";
		var valrequesttype = "<?php echo $requesttype;?>";
		var valdocumentpurpose = "<?php echo $retVal4;?>";
		var PendingStatus="<?php echo $PendingStatus; ?>"
		var PANCard = "<?php echo $retVal6;  ?>";
		var EmpPanCard = "<?php echo $EmpPanCard; ?>";
		if(!validateBlank($("#txtDocument_Number").val())){
			setErrorOnBlur("txtDocument_Number");
		}
		else if(!validateBlank($("#Document_Proof").val()) && valrequesttype=="add"){
			setErrorOnBlur("Document_Proof");
		}
		else if(PANCard!="0" && $("#selDocument_Type").val()==EmpPanCard && valrequesttype=="add"){
			$("#ERROR").fadeIn();
			$("#EXIST").html("Pan card already uploded");
			$("#ERROR").fadeOut(5000);
			
		}
		else{
			
		if(valrequesttype=="add"){
			var valquery = "insert into tw_employee_document(employee_id,document_type,document_number,document_proof,document_verification_status,document_size,document_purpose,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#selDocument_Type").val()+"','"+$("#txtDocument_Number").val()+"','"+hdnIDimg+"','"+PendingStatus+"','"+hdnIDfsize+"','"+valdocumentpurpose+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			var valquerycount = "select count(*) as cnt from tw_employee_document where document_number = '"+$("#txtDocument_Number").val()+"' and employee_id='"+valcreated_by+"'";
		 
		}
		else{
				var valrequestid = "<?php echo $requestid;?>";
				if(hdnIDimg!=""){
					var valquery = "Update tw_employee_document set document_type = '"+$("#selDocument_Type").val()+"' , document_number = '"+$("#txtDocument_Number").val()+"' , document_proof = '"+hdnIDimg+"', document_verification_status = '"+PendingStatus+"', document_size = '"+hdnIDfsize+"', document_purpose = '"+valdocumentpurpose+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";

				}
				else{
					var valquery = "Update tw_employee_document set document_type = '"+$("#selDocument_Type").val()+"' , document_number = '"+$("#txtDocument_Number").val()+"', document_verification_status = '"+PendingStatus+"', document_purpose = '"+valdocumentpurpose+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
					
				}
				var valquerycount = "select count(*) as cnt from tw_employee_document where document_number = '"+$("#txtDocument_Number").val()+"' and employee_id!='"+valcreated_by+"'";
			}
			 disableButton('#add','<i class="ti-timer"></i> Processing...');
			  
		$.ajax({
		type:"POST",
		url:"apiEmployeeProfile.php",
		data:{valquery:valquery,valquerycount:valquerycount},
		success:function(response){
			console.log(response);
			if($.trim(response)=="Success"){
				if(valrequesttype=="add"){
					showAlertRedirect("Success","Data Added Successfully","success","pgEmployeeProfile.php");
					enableButton('#add','Add');
				
					
				}
				else{
					showAlertRedirect("Success","Data Updated Successfully","success","pgEmployeeProfile.php");
					enableButton('#add','update');
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