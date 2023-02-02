<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogIn.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$company_id = $_SESSION["company_id"];
$_SESSION["EPRServicePOId"] = $_REQUEST["id"];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueEPRPO= $commonfunction->getSettingValue("PO");

$settingValueEmployeeImagePathOther = $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValuedisableicon= $commonfunction->getSettingValue("disableicon");
$qry6 = "SELECT id,reason from tw_rejected_reason_master where panel='".$settingValueEPRPO."' Order by id desc";
$retVal6 = $sign->FunctionOption($qry6,'','reason','id');

$qrystate = "select GROUP_CONCAT(state_name) as state from tw_state_master where id in (SELECT DISTINCT(state) FROM tw_po_details WHERE po_id='".$_REQUEST["id"]."')";
$retqrystate = $sign->SelectF($qrystate,"state");

$qrypo_number = "SELECT po_number FROM tw_po_info where id='".$_REQUEST["id"]."'";
$retqrypo_number = $sign->SelectF($qrypo_number,"po_number");

?>
<!DOCTYPE html>
<html lang="en">
        
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Fullfillment PO</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
	  	  
       <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Fullfillment PO [<?php echo $retqrypo_number."-".$retqrystate; ?>]</h4>
                  <div class="forms-sample">
					
					
					<div class="form-group row">
                      <label for="Document_Proof" class="col-sm-3 col-form-label">File Upload<span class="text-danger">*</span></label>
                      <div class="col-sm-5">
						<input type="file" name="file" accept=".csv" placeholder="File Upload" id="file" />				
                      </div>
                    </div>
                    <button type="submit" class="btn btn-success mr-2" id="btncreate" onclick="uploadFile();">Add</button>
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script type='text/javascript'>
var valpoid = '<?php echo $_REQUEST["id"]; ?>';
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


// Upload file
function uploadFile() {

   var files = document.getElementById("file").files;
   if(files.length > 0 ){

      var formData = new FormData();
      formData.append("file", files[0]);

      var xhttp = new XMLHttpRequest();
      // Set POST method and ajax file path
      xhttp.open("POST", "apiFileUpload.php", true);

      // call on request changes state
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {

           var response = this.responseText;
		   console.log(response);
           if(response == 1){
			   showAlertRedirect("Success","File Upload Successfully","success","pgEPRSDocumentUpload.php?type=add&id="+valpoid);			  
           }else{
			   showAlert("Warning","File not uploaded.","warning");
           }
         }
      };
      // Send request with data
      xhttp.send(formData);

   }else{
	   showAlert("Warning","Please select a file","warning");
   }

} 
</script>
</body>
</html>