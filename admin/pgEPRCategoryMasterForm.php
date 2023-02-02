<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();

$requesttype = $_REQUEST["type"];
$_SESSION["requesttype"] = $requesttype;
$id = $_REQUEST["id"];
$_SESSION["requestid"] = $id; 

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["username"];
$requesttype=$_SESSION["requesttype"];
$requestid=$_SESSION["requestid"];
$ip_address= $commonfunction->getIPAddress();

$epr_category_name	 = "";
$priority = ""; 
$description = "";
$visibility = "";

if($requesttype=="edit"){
	
	$qry = "SELECT epr_category_name,priority,description,visibility from tw_epr_category_master WHERE id = ".$id." ";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$epr_category_name	= $decodedJSON->response[0]->epr_category_name	;
	$priority = $decodedJSON->response[1]->priority; 
	$description = $decodedJSON->response[2]->description;
	$visibility = $decodedJSON->response[3]->visibility;
	
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | EPR Category Master</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<!-- endinject -->
<!-- tw-css:start -->
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
                  <h4 class="card-title">EPR Category Master</h4>
					<div class="forms-sample">
						<div class="form-group">
						  <label for="txtEprCategoryName">EPR Category Name <code>*</code></label>
						  <input type="text" class="form-control" id="txtEprCategoryName" maxlength="20" value="<?php echo $epr_category_name; ?>" placeholder="EPR Category Name" />
						</div>
						<div class="form-group">
						  <label for="txtPriority">Priority <code>*</code></label>
						  <input type="number" class="form-control" id="txtPriority" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" value="<?php echo $priority; ?>" placeholder="Priority" />
						</div>
						<div class="form-group">
						  <label for="txtDescription">Description <code>*</code></label>
						  <textarea class="form-control" id="txtDescription" maxlength="1000" rows="4" placeholder="Description"><?php echo $description; ?></textarea>
						</div>
						<div class="form-group">
							<label for="chkVisibility">Visibility</label><br>
							  <label class="switch">
							  <input type="checkbox" id="chkVisibility" <?php if ($visibility=="true") { echo "checked"; } ?>/>
							  <span class="slider round"></span>
							  </label>
							</div>
						<button type="button" id="btnAddrecord"  class="btn btn-success" onclick="addrecord()"><?php if($requesttype=="add"){ ?>Add Record<?php }else{ ?>Update Record<?php } ?></button>
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
	 if(!validateBlank($("#txtEprCategoryName").val())){
		setErrorOnBlur("txtEprCategoryName");
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
			disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
		  
			var valcreated_by = "<?php echo $created_by;?>";
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			var valrequesttype = "<?php echo $requesttype;?>";
			
			if(valrequesttype=="add"){
				var valquery = "insert into tw_epr_category_master (epr_category_name,priority,description,visibility,created_by,created_on,created_ip) values('"+$("#txtEprCategoryName").val()+"','"+$("#txtPriority").val()+"','"+$("#txtDescription").val()+"','"+$('#chkVisibility').prop('checked')+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				
				var valquerycount = "select count(*) as cnt from tw_epr_category_master where epr_category_name = '"+$("#txtEprCategoryName").val()+"'";
			}
			else{
				var valrequestid = "<?php echo $requestid;?>";
				
				var valquery = "Update tw_epr_category_master set epr_category_name = '"+$("#txtEprCategoryName").val()+"', priority = '"+$("#txtPriority").val()+"', description = '"+$("#txtDescription").val()+"', visibility = '"+$('#chkVisibility').prop('checked')+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
			
				var valquerycount = "select count(*) as cnt from tw_epr_category_master where epr_category_name = '"+$("#txtEprCategoryName").val()+"' and id!='"+valrequestid+"'";
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
						showAlertRedirect("Success","Record Added Successfully","success","pgEPRCategoryMaster.php");
					}
					else{
						$('#btnAddrecord').html('Update Record');
						showAlertRedirect("Success","Record Updated Successfully","success","pgEPRCategoryMaster.php");
					}
				}
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Record already exist","warning");
				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
				}
			}
		});   
  }
}
	
</script>	
</body>
</html>