<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();

$requesttype = $_REQUEST["type"];
$_SESSION["requesttype"] = $requesttype;
$id = $_REQUEST["id"];
$_SESSION["requestid"] = $id;
$company_type = "";
$company_icon = "";
$show_in_web = "";
$show_in_app = "";
$priority = ""; 
$description = "";
$visibility = "";

if($requesttype=="edit"){

	$qry = "SELECT company_type,company_icon,show_in_web,show_in_app,priority,description,visibility from tw_company_type_master WHERE id = ".$id." ";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$company_type = $decodedJSON->response[0]->company_type;
	$company_icon = $decodedJSON->response[1]->company_icon;
	$show_in_web = $decodedJSON->response[2]->show_in_web;
	$show_in_app = $decodedJSON->response[3]->show_in_app;
	$priority = $decodedJSON->response[4]->priority; 
	$description = $decodedJSON->response[5]->description;
	$visibility = $decodedJSON->response[6]->visibility;

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Company Type Master</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<!-- inject:css -->
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
                  <h4 class="card-title">Company Type Master</h4>
					<div class="forms-sample">
						<div class="form-group">
							<label for="txtCompanyType">Company Type <code>*</code></label>
							<input type="text" class="form-control" id="txtcompany_type" maxlength="20"  value="<?php echo $company_type; ?>" placeholder="Company Type" />
						</div>
						<div class="form-group">
							<label for="txtIcon">Icon <code>*</code></label>
							<input type="text" class="form-control" id="txtcompany_icon" maxlength="20" value="<?php echo $company_icon; ?>" placeholder="Icon" />
						</div>
						<div class="form-group">
							<input type="checkbox" id="chkweb" <?php if ($show_in_web=="true") { echo "checked"; } ?> />
							<label for="chkweb">Show in Web</label>
						</div>
						<div class="form-group">
							<input type="checkbox" id="chkapp" <?php if ($show_in_app=="true") { echo "checked"; } ?> />
							<label for="chkapp">Show in App</label>
						</div>
						<div class="form-group">
							<label for="txtPriority">Priority <code>*</code></label>
							<input type="number" class="form-control" id="txtPriority" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;"  value="<?php echo $priority; ?>" placeholder="Priority" />
						</div>
						<div class="form-group">
							<label for="txtDescription">Description <code>*</code></label>
							<textarea class="form-control" id="txtDescription" maxlength="1000" rows="4" placeholder="Description"><?php echo $description; ?></textarea>
						</div>
						<div class="form-group">
							<label for="chkVisibility">Visibility</label><br>
							  <label class="switch">
							  <input type="checkbox" id="chkVisibility" <?php if ($visibility=="true") { echo "checked"; } ?> />
							  <span class="slider round"></span>					  
							</label>
						</div>
						<button type="button" id="btnAddrecord" class="btn btn-success" onclick="addrecord()"><?php if($requesttype=="add"){ ?>Add Record<?php }else{ ?>Update Record<?php } ?></button>
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
<script type='text/javascript'>
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
var valrequesttype = "<?php echo $requesttype;?>";
function addrecord(){
		var buttonHtml = $('#btnAddrecord').html();
		if(!validateBlank($("#txtcompany_type").val())){
			setErrorOnBlur("txtcompany_type");
		  }
		  else if(!validateBlank($("#txtcompany_icon").val())){
			setErrorOnBlur("txtcompany_icon");
		  } 
		  else if(!validateBlank($("#txtPriority").val())){
			setErrorOnBlur("txtPriority");
		  } 
		  else if(!isNumber($("#txtPriority").val())){
			setError(txtPriority);
		  } 
		  else if(!validateBlank($("#txtDescription").val())){
			setErrorOnBlur("txtDescription");
		  } 
		  else{
			disableButton('#btnAddrecord','Processing...');
			$.ajax({
				type:"POST",
				url:"apiAddCompanyTypeMasterStatus.php",
				data:{company_type:$("#txtcompany_type").val(),company_icon:$("#txtcompany_icon").val(),show_in_web:$("#chkweb").prop('checked'),show_in_app:$("#chkapp").prop('checked'),priority:$("#txtPriority").val(),description:$("#txtDescription").val(),visibility:$('#chkVisibility').prop('checked')},
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
							showAlertRedirect("Success","Record Added Successfully","success","pgCompanyTypeMaster.php");
							}
						else{
							$('#btnAddrecord').html('Update Record');
							showAlertRedirect("Success","Record Updated Successfully","success","pgCompanyTypeMaster.php");
						}
						$("#txtcompany_type").val("");
						$("#txtcompany_icon").val("");
						$("#chkweb").val("");
						$("#chkapp").val("");
						$("#txtPriority").val("");
						$("#txtDescription").val("");
					
					}
					else if($.trim(response)=="Exist"){
						showAlert("Warning","Record already exist","warning");
					
					}
					else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");
					
					}
					
					$('#btnAddrecord').html(buttonHtml);
				}
			});
	}
}	
</script>	
</body>
</html>