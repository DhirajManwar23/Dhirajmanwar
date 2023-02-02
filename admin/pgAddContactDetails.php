<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePcontact= $commonfunction->getSettingValue("Primary Contact");
$settingValuePotherEmail= $commonfunction->getSettingValue("Other Email");
$settingValuePmobile= $commonfunction->getSettingValue("Mobile");
$settingValuePhome= $commonfunction->getSettingValue("Home");
$settingValuePwork= $commonfunction->getSettingValue("Work");
$settingValuePfax= $commonfunction->getSettingValue("Fax");
$settingValuePotherContact= $commonfunction->getSettingValue("Other Contact");
$settingValuePwebsite= $commonfunction->getSettingValue("Website");
$settingValueDocumentPendingStatus= $commonfunction->getSettingValue("Pending Status");

$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$requestcontactfield = $_REQUEST["contactfield"];


date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["username"];

$company_id = $_SESSION["company_id"];

$Status = "";
$value = "";
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
		$qry2 = "SELECT contact_field,value,public_visible FROM tw_company_contact WHERE id = '".$requestid."'";
		$retVal2 = $sign->FunctionJSON($qry2);
		$decodedJSON = json_decode($retVal2);
		$contact_field = $decodedJSON->response[0]->contact_field;
		$value = $decodedJSON->response[1]->value;
		$public_visible = $decodedJSON->response[2]->public_visible;
		
		if($requestcontactfield==$settingValuePcontact){
			$qry3 = "select id,contact_type from tw_contact_field_master where visibility = 'true' and id!='".$settingValuePemail."'";
			$retVal1 = $sign->FunctionOption($qry3,$contact_field,'contact_type','id');
		}
		else{
			$qry3 = "select id,contact_type from tw_contact_field_master where visibility = 'true' and (id!='".$settingValuePemail."' and id!='".$settingValuePcontact."') ";
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
                      <label for="ContactField" class="col-sm-3 col-form-label">Contact Field<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <select id="selContactField" class="form-control form-control-sm">
							<?php echo $retVal1; ?>
						</select>
                      </div>
                    </div>
					<div class="form-group row">
                      <label for="Value" class="col-sm-3 col-form-label">Value<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" maxlength="50" value="<?php echo $value; ?>" id="txtValue" placeholder="Value">
						<span id="ExistValue" class="text-danger"></span>
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
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/template.js"></script>
  <script src="../assets/js/settings.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
  <!-- endinject -->

<script src="../assets/css/jquery/jquery.min.js"></script>

 <script>
var valsettingValuePemail = <?php echo $settingValuePemail;?>;
var valsettingValuePcontact = <?php echo $settingValuePcontact;?>;
var valsettingValuePotherEmail = <?php echo $settingValuePotherEmail;?>;
var valsettingValuePmobile = <?php echo $settingValuePmobile;?>;
var valsettingValuePhome = <?php echo $settingValuePhome;?>;
var valsettingValuePwork = <?php echo $settingValuePwork;?>;
var valsettingValuePfax = <?php echo $settingValuePfax;?>;
var valsettingValuePotherContact = <?php echo $settingValuePotherContact;?>;
var valsettingValuePwebsite = <?php echo $settingValuePwebsite;?>;
var valsettingValueDocumentStatus = <?php echo $settingValueDocumentPendingStatus;?>;

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
		$("#" + vallblid).focus();
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

 
 $("#txtValue").blur(function()
 {
	 if($("#selContactField").val()==valsettingValuePmobile || $("#selContactField").val()==valsettingValuePcontact){
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
	  else if($("#selContactField").val()==valsettingValuePhome){
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
	  
  
  else{
	  doExe=true;
	} 
	
	if (doExe){
		var valcreated_by = "<?php echo $created_by;?>";
		var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";
		var valrequesttype = "<?php echo $requesttype;?>";
		var valcompany_id = "<?php echo $company_id;?>";
		
		if(valrequesttype=="add"){
			var valquery = "insert into tw_company_contact(company_id,contact_field,value,public_visible,status,created_by,created_on,created_ip)values('"+valcompany_id+"','"+$("#selContactField").val()+"','"+$("#txtValue").val()+"','"+$('#chkVisibility').prop('checked')+"','"+valsettingValueDocumentStatus+"','"+valcompany_id+"','"+valcreated_on+"','"+valcreated_ip+"')";
			var valquerycount = "select count(*) as cnt from tw_company_contact where value = '"+$("#txtValue").val()+"'";
			
		}
		else{
			var valrequestid = "<?php echo $requestid;?>";
			var valquery = "Update tw_company_contact set contact_field = '"+$("#selContactField").val()+"' , value = '"+$("#txtValue").val()+"', public_visible = '"+$('#chkVisibility').prop('checked')+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
			var valquerycount = "select count(*) as cnt from tw_company_contact where value = '"+$("#txtValue").val()+"' and company_id!='"+valcompany_id+"'";
		}
		disableButton('#btnAdd','<i class="ti-timer"></i> Processing...');
		$.ajax({
		type:"POST",
		url:"apiCommonQuery.php",
		data:{valquery:valquery,valquerycount:valquerycount},
		success:function(response){
			//console.log(response);
			
			if(valrequesttype=="add"){
				enableButton('#btnAdd','Add');
				
			}
			else{
				enableButton('#btnAdd','Update');
			}
				if($.trim(response)=="Success"){
				if(valrequesttype=="add"){
					showAlertRedirect("Success","Data Added Successfully","success","pgCompanyProfile.php?id="+valcompany_id);
				}
				else{
					showAlertRedirect("Success","Data Updated Successfully","success","pgCompanyProfile.php?id="+valcompany_id);
				}
			}
			else if($.trim(response)=="Exist"){
				$("#ExistValue").html("Value already exist");
				
				$("#txtValue").focus();
			}else{
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