<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogIn.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
//$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["companyusername"];

$Auditorname = "";

$queryGetPONumber = "select po_number from tw_po_info where id = '".$requestid."' ORDER by id ASC";
$GetPONumber = $sign->SelectF($queryGetPONumber,'po_number');


$qrySelectAuditor = "select id,employee_name from tw_employee_registration where employee_role='18'";
$SelectAuditor = $sign->FunctionOption($qrySelectAuditor,$Auditorname,'employee_name','id');

$qryAuditorcnt="Select count(*) as cnt from tw_auditor_po_details where  po_id = '".$requestid."'";
$Auditorcnt = $sign->Select($qryAuditorcnt);

if($Auditorcnt>0){
	$queryAudid = "select auditor_id from tw_auditor_po_details where po_id = '".$requestid."' ORDER by id ASC";
	$GetAudid = $sign->SelectF($queryAudid,'auditor_id');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Assign Auditor</title>
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
				<?php if($Auditorcnt==0) {?>
                  <h4 class="card-title">Assign Auditor</h4>
				  
					<div class="forms-sample">
						
						<div class="form-group">
							<label for="txtPONumber">PO Number</label>
							<div class="form-group">
							<input type="text" disabled class="form-control form-control-sm" maxlength="20"  value="<?php echo $GetPONumber; ?>" id="txtPONumber" placeholder="PO Number"/>
							</div>
						</div>
						<div class="form-group">
							<label for="txtAuditorName">Auditor Name<code>*</code></label>
							<div class="form-group">
								<select id="txtAuditorName" class="form-control form-control-sm">
									<?php echo $SelectAuditor; ?>
								</select>
							</div>
						</div>	
					<?php if($Auditorcnt==0){ ?>						
							<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata('Update')"> Update</button>
							<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata('Skip')"> Skip/Save</button>
							<?php }
							
							else{

								if($Auditorcnt==1 && $GetAudid==-1){ ?><button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata('Skip')"> Skip/Save</button><?php }
							
								else if($Auditorcnt==1 && $GetAudid!=-1){ ?><button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata('Update')"> Update</button><?php }

								} ?>
						
							
					</div>
				<?php } else{
					if($Auditorcnt>0 && $GetAudid==-1){
						echo "You Have Already Skipped this step!....";
					} else if($Auditorcnt>0 && $GetAudid!=-1){ ?>
						<h4 class="card-title">Assign Auditor</h4>
				  
					<div class="forms-sample">
						
						<div class="form-group">
							<label for="txtPONumber">PO Number</label>
							<div class="form-group">
							<input type="text" disabled class="form-control form-control-sm" maxlength="20"  value="<?php echo $GetPONumber; ?>" id="txtPONumber" placeholder="PO Number"/>
							</div>
						</div>
						<div class="form-group">
							<label for="txtAuditorName">Auditor Name<code>*</code></label>
							<div class="form-group">
								<select id="txtAuditorName" class="form-control form-control-sm">
									<?php echo $SelectAuditor; ?>
								</select>
							</div>
						</div>	
					<?php if($Auditorcnt==0){ ?>						
							<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata('Update')"> Update</button>
							<button type="button" class="btn btn-success" id="btnSkiprecord" onclick="adddata('Skip')"> Skip/Save</button>
							<?php }
							
							else{

								if($Auditorcnt==1 && $GetAudid==-1){ ?><button type="button" class="btn btn-success" id="btnSkiprecord" onclick="adddata('Skip')"> Skip/Save</button><?php }
							
								else if($Auditorcnt==1 && $GetAudid!=-1){ ?><button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata('Update')"> Update</button><?php }

								} ?>
						
							
					</div>
						
					
					
				<?php } ?>
				<?php } ?>
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

function adddata(type){
	
		var valcreated_by = "<?php echo $created_by;?>";
		var valcreated_on = "<?php echo $cur_date;?>";
		var valcreated_ip = "<?php echo $ip_address;?>";
		var valrequesttype = type;
		var valrequestid = "<?php echo $requestid;?>";
		var employee_role = "18";
		
		disableButton('#btnSkiprecord','<i class="ti-timer"></i> Processing...');
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
			
		$.ajax({
		type:"POST",
		url:"apiAssignAuditor.php",
		data:{AuditorName:$("#txtAuditorName").val(),valrequestid:valrequestid,valcreated_by:valcreated_by,valcreated_on:valcreated_on,valcreated_ip:valcreated_ip,employee_role:employee_role,valrequesttype:valrequesttype},
		success:function(response){
			console.log(response);
				if(valrequesttype=="Update"){
					disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
					enableButton('#btnAddrecord','Update');
						//disableButton('#btnSkiprecord','Skip');
					}
					else{
						disableButton('#btnSkiprecord','<i class="ti-timer"></i> Processing...');
						//enableButton('#btnSkiprecord','Skip');
							
					}
			if($.trim(response)=="Success"){
				showAlertRedirect("Success","Record Added Successfully","success","pgAssignAuditor.php?id="+valrequestid);
			}
			/* else if($.trim(response)=="Exist"){
				showAlert("Warning","Record already exist","warning");
			} */
			else{
				showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
			}
		}
	});   
//}
}
</script>
</body> 
</html>