<?php 
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];

$company_id = $_SESSION["company_id"];
$created_by=$_SESSION["employee_id"];

//whether ip is from share internet
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$description = "";
$norms = "";
$priority = "";
$visibility = "";
	
$settingValueStatuspending= $commonfunction->getSettingValue("Registration Status");	
	
	if($requesttype=="edit")
	{
		$qry="select description,norms,priority,visibility from tw_test_report_designation_master where ID='".$requestid."'";
		$retVal = $sign->FunctionJSON($qry);
		$decodedJSON = json_decode($retVal);
		$description = $decodedJSON->response[0]->description; 
		$norms = $decodedJSON->response[1]->norms; 
		$priority = $decodedJSON->response[2]->priority;
		$visibility = $decodedJSON->response[3]->visibility;
	
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Test Report Master</title>
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
                  <h4 class="card-title">Test Report Master</h4>
                  <div class="forms-sample">
					
                      <div class="form-group">
                      <label for="txtdescription">Description <code>*</code></label>
                      <input type="text" class="form-control" id="txtdescription" maxlength="30" placeholder="Description" value="<?php echo $description; ?>">
                    </div>
					
					 <div class="form-group">
                      <label for="txtnorms">Norms <code>*</code></label>
                      <input type="email" class="form-control" id="txtnorms" maxlength="30" value="<?php echo $norms; ?>" placeholder="Norms" />
                    </div>
					<div class="form-group">
						<label for="txtPriority">Priority <code>*</code></label>
						<input type="number" class="form-control" value="<?php echo $priority; ?>" id="txtPriority" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" placeholder="Priority" />
                    </div>
					 <div class="form-group">
						<label for="chkVisibility">Visibility </label><br>
						<label class="switch">
						<input type="checkbox" id="chkVisibility" <?php if ($visibility=="true") { echo "checked"; } ?> />
						<span class="slider round"></span>
						</label>
					</div>
					
					
					<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata();"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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
	  
	    if(!validateBlank($("#txtdescription").val())){
			setErrorOnBlur("txtdescription");
		  }		  
		  else if(!validateBlank($("#txtnorms").val())){
			setErrorOnBlur("txtnorms");
		  } else if(!validateBlank($("#txtPriority").val())){
			setErrorOnBlur("txtPriority");
		  } 
		  else if(!isNumber($("#txtPriority").val())){
			setError(txtPriority);
		  } else{ 
		   
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
		

		var valcreated_by="<?php echo $created_by;?>";
	    var valcreated_on="<?php echo $cur_date;?>";
		var valcreated_ip="<?php echo $ip_address;?>";
		var valrequesttype="<?php echo $requesttype;?>";
		var settingValueStatuspending="<?php echo $settingValueStatuspending; ?>";
		var companyID="<?php echo $_SESSION['company_id']; ?>";
		var valquery="";
		var valquerycount="";
		if(valrequesttype=="add"){
			
			valquery = "insert into tw_test_report_designation_master(company_id,description,norms,priority,visibility,created_by,created_on,created_ip) values('"+companyID+"','"+$("#txtdescription").val()+"','"+$("#txtnorms").val()+"','"+$("#txtPriority").val()+"','"+$("#chkVisibility").prop('checked')+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
			
			valquerycount = "select count(*) as cnt from tw_test_report_designation_master where description='"+$("#txtdescription").val()+"'";
		
		}
		
		else{
			var valrequestid = "<?php echo $requestid;?>";
			valquery = "update tw_test_report_designation_master set description='"+$("#txtdescription").val()+"',norms='"+$("#txtnorms").val()+"',priority='"+$("#txtPriority").val()+"', visibility='"+$("#chkVisibility").prop('checked')+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
			valquerycount = "select count(*) as cnt from tw_test_report_designation_master where description='"+$("#txtdescription").val()+"' and ID!='"+valrequestid+"'";
		} 
		

	$.ajax({
			type:"POST",
			url:"apiEmployeeProfile.php",
			data:{valquery:valquery,valquerycount:valquerycount},
			success:function(response){
				if(valrequesttype=="add"){
					enableButton('#btnAddrecord','Add Record');
				}
				else
				{
					enableButton('#btnAddrecord','Update Record');
				}
				if($.trim(response)=="Success"){
					if(valrequesttype=="add"){
						showAlertRedirect("Success","Record Added Successfully","success","pgTestReportMaster.php");
					}
					else{
						showAlertRedirect("Success","Record Updated Successfully","success","pgTestReportMaster.php");
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
 
 </body>
</html>