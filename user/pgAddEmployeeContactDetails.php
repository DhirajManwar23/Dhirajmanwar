<?php 
session_start();
	if(!isset($_SESSION["companyusername"])){
		header("Location:pgLogin.php");
	}
	include_once "function.php";
	include_once "commonFunctions.php";
	$requesttype = $_REQUEST["type"];
	$requestid = $_REQUEST["id"];
	$requestcontactfield = $_REQUEST["contactfield"];
	$commonfunction=new Common();
	$sign=new Signup();
    $ip_address= $commonfunction->getIPAddress();
	date_default_timezone_set("Asia/Kolkata");
	$cur_date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["employee_id"];
	$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
	$settingValuePemail=$sign->escapeString($settingValuePemail);
	$settingValuePothertemail=$commonfunction->getSettingValue("Other Email");
	$settingValuePcontact= $commonfunction->getSettingValue("Primary Contact");
    $settingValuePcontact=$sign->escapeString($settingValuePcontact);
	$settingValueStatuspending= $commonfunction->getSettingValue("Document Status");
	$settingValueStatuspending=$sign->escapeString($settingValueStatuspending);
	$settingValueStatusAwaiting= $commonfunction->getSettingValue("Awaiting Status");
	$settingValueStatusAwaiting=$sign->escapeString($settingValueStatusAwaiting);
	$settingValuePmobile= $commonfunction->getSettingValue("Mobile");
	$settingValuePhome= $commonfunction->getSettingValue("Home");
	$settingValuePwork= $commonfunction->getSettingValue("Work");
	$settingValuePfax= $commonfunction->getSettingValue("Fax");
	$settingValuePotherContact= $commonfunction->getSettingValue("Other Contact");
	$settingValuePwebsite= $commonfunction->getSettingValue("Website");
    $settingValueDocumentPendingStatus= $commonfunction->getSettingValue("Pending Status");
	$Status = "";
	$value = "";
	$contact_field = "";
	$public_visible = "";
	if($requesttype=="add"){
		if($requestcontactfield==$settingValuePcontact){
		    $qry3 = "select id,contact_type from tw_contact_field_master where visibility = 'true' and id='".$settingValuePcontact."' ORDER by priority, contact_type  ASC";
			$retVal1 = $sign->FunctionOption($qry3,$contact_field,'contact_type','id');
		}
		else{
		    $qry1 = "select id,contact_type from tw_contact_field_master where visibility = 'true' and (id!='".$settingValuePemail."' and id!='".$settingValuePcontact."') ORDER by priority, contact_type  ASC";
			$retVal1 = $sign->FunctionOption($qry1,$Status,'contact_type','id');
		}	
	}
	else{
			$qry2 = "SELECT contact_field,value,public_visible FROM tw_employee_contact WHERE id = '".$requestid."'";
			$retVal2 = $sign->FunctionJSON($qry2);
			$decodedJSON = json_decode($retVal2);
			$contact_field = $decodedJSON->response[0]->contact_field;
			$value = $decodedJSON->response[1]->value;
			$public_visible = $decodedJSON->response[2]->public_visible;
			if($requestcontactfield==$settingValuePcontact){
				$qry3 = "select id,contact_type from tw_contact_field_master where visibility = 'true' and id='".$settingValuePcontact."' ORDER by priority, contact_type  ASC";
				$retVal1 = $sign->FunctionOption($qry3,$contact_field,'contact_type','id');
			}
			else{
				$qry3 = "select id,contact_type from tw_contact_field_master where visibility = 'true' and (id!='".$settingValuePemail."' and id!='".$settingValuePcontact."') ORDER by priority, contact_type  ASC";
				$retVal1 = $sign->FunctionOption($qry3,$contact_field,'contact_type','id');
			}	
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Contact Details</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <!-- endinject -->
  <!-- tw-css:start -->
  <link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
                  <h4 class="card-title">Contact Details</h4>
                  <div class="forms-sample">
                    <div class="form-group row">
                      <label for="ContactField" class="col-sm-3 col-form-label">Contact Field <code>*</code></label>
                      <div class="col-sm-9">
                        <select id="selContactField" class="form-control form-control-sm">
							<?php echo $retVal1; ?>
						</select>
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Value" class="col-sm-3 col-form-label">Value <code>*</code></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $value; ?>" id="txtValue" placeholder="Value">
                      </div>
                    </div>
					<div class="form-group row">
						<label for="chkVisibility" class="col-sm-3 col-form-label">Public Visible (Optional)</label><br>
					    <div class="col-sm-9">
							<label class="switch">
							<input type="checkbox" id="chkVisibility" <?php if ($public_visible=="true") { echo "checked"; } ?>/>
							<span class="slider round"></span>
							</label>						
						</div>
                    </div>
                    <button type="submit" class="btn btn-success mr-2" id="btnAdd" onclick="adddata()"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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
  <script src="../assets/js/custom/twCommonValidation.js"></script>
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
var valcheck = "";
var valpgName = "";
var valaction = "";
var valdata = "";
var valresult = "";
var valstatus = "";
var valsettingValuePemail= <?php echo $settingValuePemail;?>;
var valsettingValuePotherContact= <?php echo $settingValuePotherContact;?>;
var valsettingValuePotherEmail= <?php echo $settingValuePothertemail;?>;
var valsettingValuePmobile = <?php echo $settingValuePmobile;?>;
var valsettingValuePhome = <?php echo $settingValuePhome;?>;
var valsettingValuePwork = <?php echo $settingValuePwork;?>;
var valsettingValuePcontact= <?php echo $settingValuePcontact;?>;
var valsettingValuePfax = <?php echo $settingValuePfax ?>;
var valsettingValueDocumentStatus = <?php echo $settingValueDocumentPendingStatus;?>;
$(document).ready(function(){
	 employeeLogs(valpgName,valaction,valdata,valresult,valstatus);
	 $("swal-overlay swal-overlay--show-modal").attr("style", "display:none");
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
 
 $("#txtValue").blur(function()
 {
	 if($("#selContactField").val()==valsettingValuePmobile || $("#selContactField").val()==valsettingValuePhome || $("#selContactField").val()==valsettingValuePcontact){
	removeError(txtValue);
	if ($("#txtValue").val()!="")
	{
		if(!isMobile($("#txtValue").val())){
			setError(txtValue);
		}
		else
		{
			removeError(txtValue);
		}
	}
	 }
	 else if($("#selContactField").val()==valsettingValuePfax){
		removeError(txtValue);
		 if ($("#txtValue").val()!="")
		{
			if(!isNumber($("#txtValue").val())){
				setError(txtValue);
			}
			else
			{
				removeError(txtValue);
			}
		} 
	  } 
	  else if($("#selContactField").val()==valsettingValuePemail || $("#selContactField").val()==valsettingValuePotherEmail || $("#selContactField").val()==valsettingValuePotherContact){
	 removeError(txtValue);
     if ($("#txtValue").val()!="")
	{
		if(!validateEmail($("#txtValue").val())){
			setError(txtValue);
		}
		else
		{
			removeError(txtValue);
		}
	}
	 }
 });
 

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
function adddata(){
var textFieldValue=$("#selContactField").val();
var doExe = true;
var number=$("#txtValue").val();

 if(!validateBlank($("#txtValue").val())){
		setErrorOnBlur("txtValue");
	  }
 else{


  if(textFieldValue==valsettingValuePmobile || textFieldValue==valsettingValuePcontact){		  
	if(!isMobile($("#txtValue").val())){
	   setError(txtValue);
	   doExe=false;
	}
	else{
		doExe=true;
	}
  }
   else if(textFieldValue==valsettingValuePhome){
   if(!isNumber($("#txtValue").val())){
	   setErrorOnBlur("txtValue");
	   doExe=false;
	}
	else{
		doExe=true;
	} 
  }
  else if(textFieldValue==valsettingValuePemail || textFieldValue==valsettingValuePotherEmail)
  {
	  if(!validateEmail($("#txtValue").val())){
	   setError(txtValue);
	   $("input[type=text]").attr("placeholder", "Email Id")
	   doExe=false;
	}
	else{
		doExe=true;
	}
	}
   else if(textFieldValue==valsettingValuePfax || textFieldValue==valsettingValuePotherContact){
   if(!isNumber($("#txtValue").val())){
   setErrorOnBlur("txtValue");
   doExe=false;
	}
	else{
		doExe=true;
	} 
  }
	 else if(textFieldValue==valsettingValuePfax || textFieldValue==valsettingValuePotherContact){
   if(!isNumber($("#txtValue").val())){
   setErrorOnBlur("txtValue");
   doExe=false;
	}
	else{
		doExe=true;
	} 
  }  
  
  else{
	  doExe=true;
	}  
		if (doExe)
		{
			var valcreated_by = "<?php echo $created_by;?>";
			 var valcreated_on = "<?php echo $cur_date;?>";
			 var valcreated_ip = "<?php echo $ip_address;?>";
			 var valrequesttype = "<?php echo $requesttype;?>";
			 var StatusPending= "<?php echo $settingValueStatuspending;?>";
			 var StatusAwaiting= "<?php echo $settingValueStatusAwaiting;?>";
			if(valrequesttype=="add"){
				var valquery = "insert into tw_employee_contact(employee_id,contact_field,value,public_visible,status,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#selContactField").val()+"','"+$("#txtValue").val()+"','"+$('#chkVisibility').prop('checked')+"','"+StatusPending+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				var valquerycount = "select count(*) as cnt from tw_employee_contact where value = '"+$("#txtValue").val()+"' and employee_id='"+valcreated_by+"'";
				
			}
			else{
				var valrequestid = "<?php echo $requestid;?>";
				var valquery = "Update tw_employee_contact set contact_field = '"+$("#selContactField").val()+"' , value = '"+$("#txtValue").val()+"', public_visible = '"+$('#chkVisibility').prop('checked')+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"',status='"+StatusPending+"' where id = '"+valrequestid+"' ";
				var valquerycount = "select count(*) as cnt from tw_employee_contact where value = '"+$("#txtValue").val()+"' and employee_id!='"+valcreated_by+"'";
			}	
			disableButton('#btnAdd','<i class="ti-timer"></i> Processing...');
		  
			$.ajax({
			type:"POST",
			url:"apiEmployeeProfile.php",
			data:{valquery:valquery,valquerycount:valquerycount},
			success:function(response){
				console.log(response);
				if(valrequesttype=="add"){
				enableButton('#btnAdd','Add');
				}
				else{
				enableButton('#btnAdd','Update');
				}
					if($.trim(response)=="Success"){
					if(valrequesttype=="add"){
						showAlertRedirect("Success","Data Added Successfully","success","pgEmployeeProfile.php?id="+valcreated_by);
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgEmployeeProfile.php?id="+valcreated_by);
					}
				}
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Value already exist","warning");
					$("#txtValue").focus();
				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
				}
			} 
		});
	}	
  }
}
 </script>
 </body>
</html>