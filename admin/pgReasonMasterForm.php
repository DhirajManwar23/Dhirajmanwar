<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["username"];
$settingValuePO=$commonfunction->getSettingValue("PO");
$settingValueEPR=$commonfunction->getSettingValue("EPR");
$settingValueBankDoc=$commonfunction->getSettingValue("BankDoc");
$settingValueKycDoc=$commonfunction->getSettingValue("KycDoc");
$settingValueMaterialOutward=$commonfunction->getSettingValue("MaterialOutward");
$settingValueComplianceDoc=$commonfunction->getSettingValue("ComplianceDoc");
$panel = "";
$reason = "";
$priority = "";
$visibility = "";
//$dropSel = "";

if($requesttype=="edit")
{
	$qry="select panel,reason,priority,visibility from tw_rejected_reason_master where ID='".$requestid."' order by id Desc";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	
	$panel = $decodedJSON->response[0]->panel;
	$reason = $decodedJSON->response[1]->reason; 
	$priority = $decodedJSON->response[2]->priority; 
	$visibility = $decodedJSON->response[3]->visibility;
	//$dropSel=$panel;
}

//$query = "select id,panel from tw_panel_master where visibility='true' Order by priority,panel";
//$retVal1 = $sign->FunctionOption($query,$dropSel,'panel','id');

?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |Rejected Reason Master</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
                  <h4 class="card-title">Rejected Reason Master</h4>
					<div class="forms-sample">
					
					
					<div class="form-group" >
						<label for="selModule">Panel <code>*</code></label>
						<select name="Panel" id="txtmodule_value" class="form-control" >
						<option value="EPR" <?php  if($panel==$settingValueEPR){ echo "selected";} ?>>EPR</option>
						<option value="PO" <?php  if($panel==$settingValuePO){ echo "selected";} ?>>PO</option>
						<option value="BankDoc" <?php  if($panel==$settingValueBankDoc){ echo "selected";} ?>>Bank Doc</option>
						<option value="KycDoc" <?php  if($panel==$settingValueKycDoc){ echo "selected";} ?>>Kyc Doc</option>
						<option value="MaterialOutward" <?php  if($panel==$settingValueMaterialOutward){ echo "selected";} ?>>Material Outward</option>
						<option value="ComplianceDoc" <?php  if($panel==$settingValueComplianceDoc){ echo "selected";} ?>>Compliance Document</option>
						
						</select>
						</div>
						<div class="form-group">
						<label for="txtreason">Reason <code>*</code></label>
						<input type="text" class="form-control" id="txtreason" maxlength="30" placeholder="Reason" value="<?php echo $reason; ?>">
						</div>
						<div class="form-group">
							<label for="txtPriority">Priority <code>*</code></label>
							<input type="number" class="form-control" id="txtPriority" maxlength="3" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" value="<?php echo $priority; ?>" placeholder="Priority" />
						</div>
						<div class="form-group">
							<label for="chkVisibility">Visibility</label><br>
							<label class="switch">
							<input type="checkbox" id="chkVisibility" <?php if ($visibility=="true") { echo "checked"; } ?> />
							<span class="slider round"></span>
							</label>
						</div>				        
						<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata()"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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
<!-- endinject -->
</body>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script>
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
	var valplaceholder = $("#"+inputComponent).attr("placeholder");
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
$("#txtPriority").blur(function()
{
	removeError(txtPriority);
	if ($("#txtPriority").val()!="")
	{
		if(!isNumber($("#txtPriority").val())){
			setError(txtPriority);
		}
		else
		{
			removeError(txtPriority);
		}
	}
});

  function adddata(){
	    if(!validateBlank($("#txtreason").val())){
			setErrorOnBlur("txtreason");
		}
		else if(!validateBlank($("#txtPriority").val())){
		setErrorOnBlur("txtPriority");
		} 
		else if(!isNumber($("#txtPriority").val())){
		setError(txtPriority);
		}
		
		else{ 
			disableButton('#btnAddrecord','Processing...');
			var valcreated_by="<?php echo $created_by;?>";
			var valcreated_on="<?php echo $cur_date;?>";
			var valcreated_ip="<?php echo $ip_address;?>";
			var valrequesttype="<?php echo $requesttype;?>";
			if(valrequesttype=="add"){
				var valquery = "insert into tw_rejected_reason_master(panel,reason,priority,visibility,created_by,created_on,created_ip) values('"+$("#txtmodule_value").val()+"','"+$("#txtreason").val()+"','"+$("#txtPriority").val()+"','"+$('#chkVisibility').prop('checked')+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				var valquerycount = "select count(*) as cnt from tw_rejected_reason_master where reason='"+$("#txtreason").val()+"' and panel='"+$("#txtmodule_value").val()+"'";
			}
			else{
				var valrequestid = "<?php echo $requestid;?>";
				var valquery = "Update tw_rejected_reason_master set panel='"+$("#txtmodule_value").val()+"',reason='"+$("#txtreason").val()+"',priority='"+$("#txtPriority").val()+"',visibility='"+$('#chkVisibility').prop('checked')+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
				var valquerycount = "select count(*) as cnt from tw_rejected_reason_master where reason='"+$("#txtreason").val()+"' and id!='"+valrequestid+"' and panel='"+$("#txtmodule_value").val()+"'";
			}
				$.ajax({
				type:"POST",
				url:"apiCommonQuery.php",
				data:{valquery:valquery,valquerycount:valquerycount},
				success:function(response){
				console.log(response);
					if(valrequesttype=="add"){
							enableButton('#btnAddrecord','Add Record');
						}
					else{
						enableButton('#btnAddrecord','Update Record');
					}	
					if($.trim(response)=="Success"){
						if(valrequesttype=="add"){
							$('#btnAddrecord').html('Add Record');
							showAlertRedirect("Success","Record Added Successfully","success","pgReasonMaster.php");
						}
						else{
							$('#btnAddrecord').html('Update Record');
							showAlertRedirect("Success","Record Updated Successfully","success","pgReasonMaster.php");
						}
					}
					else if($.trim(response)=="Exist"){
						showAlert("Warning","Record already exist","warning");
					}else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
					}
				}
			});   
	} 
}
</script> 
</html>