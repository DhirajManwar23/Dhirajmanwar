<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
include_once "function.php";
$sign=new Signup();
$requesttype = $_REQUEST["type"];
$_SESSION["requesttype"] = $requesttype;
$id = $_REQUEST["id"];
$_SESSION["requestid"] = $id;
$submodule_name = "";
$module = "";
$sub_module_icon = "";
$pageurl = "";
$priority = ""; 
$description = "";
$visibility = "";
$dropSel = "";
	
if($requesttype=="edit"){
	
	$qry = "SELECT submodule_name,module,sub_module_icon,url,priority,description,visibility from tw_submodule_master WHERE id = ".$id."";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$submodule_name = $decodedJSON->response[0]->submodule_name;
	$module = $decodedJSON->response[1]->module;
	$sub_module_icon = $decodedJSON->response[2]->sub_module_icon;
	$pageurl = $decodedJSON->response[3]->url;
	$priority = $decodedJSON->response[4]->priority; 
	$description = $decodedJSON->response[5]->description;
	$visibility = $decodedJSON->response[6]->visibility;
	$dropSel = $module;
	
	
	$qry="SELECT mm.id,mm.module_name,pm.panel from tw_module_master mm LEFT JOIN tw_panel_master pm ON mm.panel=pm.id WHERE mm.visibility='true'";
	$retVal = $sign->FunctionJSON($qry);

	$qry1="Select count(*) as cnt from tw_module_master  WHERE visibility='true'";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$optionvalue="";
	$it=1;
	while($x>=$i){
	$idmaster = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$module_name = $decodedJSON2->response[$count]->module_name;
	$count=$count+1;
	$panelname = $decodedJSON2->response[$count]->panel;
	$count=$count+1;
	$modulename = $module_name." [".$panelname."]";
		if($idmaster==$dropSel){
			$selectval = "selected";
		}
		else{
			$selectval = "";
		}
		$optionvalue.="<option value='".$idmaster."' ".$selectval.">".$modulename."</option>";
		$it++;
	$i=$i+1;
	}
}
else{
	$qry="SELECT mm.id,mm.module_name,pm.panel from tw_module_master mm LEFT JOIN tw_panel_master pm ON mm.panel=pm.id WHERE mm.visibility='true' Order by mm.priority,mm.module_name";

	$retVal = $sign->FunctionJSON($qry);

	$qry1="Select count(*) as cnt from tw_module_master  WHERE visibility='true' Order by priority,module_name";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$optionvalue="";
	$it=1;
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$module_name = $decodedJSON2->response[$count]->module_name;
	$count=$count+1;
	$panelname = $decodedJSON2->response[$count]->panel;
	$count=$count+1;
	$modulename = $module_name." [".$panelname."]";
		$optionvalue.="<option value='".$id."'>".$modulename."</option>";
		$it++;
	$i=$i+1;
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Sub Module Master</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
<!-- endinject -->
<!-- tw-css:start -->
<link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
                  <h4 class="card-title">Sub Module Master</h4>               
                  <div class="forms-sample">
                    <div class="form-group">
						<label for="txtsubmodulename">Sub Module Name <code>*</code></label>
						<input type="text" class="form-control" id="txtsubmodule_name" maxlength="50" value="<?php echo $submodule_name; ?>" placeholder="submodule_name" />
                    </div>
					<div class="form-group" >
						<label>Module<code>*</code></label>
						<select name="role" id="txtsubmodule_type" class="form-control" >
						 <?php echo $optionvalue;?>
						</select>
					</div>
					<div class="form-group">
						<label for="txtSubModuleicon">Sub Module Icon <code>*</code></label>
						<input type="text" class="form-control" value="<?php echo $sub_module_icon; ?>" id="txtSubModuleicon" placeholder="Module icon" />
                    </div>
					<div class="form-group">
						<label for="txtUrl">Url <code>*</code></label>
						<input type="text" class="form-control" value="<?php echo $pageurl; ?>" id="txtUrl" placeholder="Url" />
                    </div>
					 <div class="form-group">
						<label for="txtPriority">Priority <code>*</code></label>
						<input type="number" class="form-control" id="txtPriority" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" value="<?php echo $priority; ?>" placeholder="Priority" />
                    </div>
                    <div class="form-group">
						<label for="txtDescription">Description <code>*</code></label>
						<textarea class="form-control" id="txtDescription" maxlength="1000"  rows="4" placeholder="Description"><?php echo $description; ?></textarea>
                    </div>
					<div class="form-group">
						<label for="chkVisibility">Visibility</label><br>
							<label class="switch">
							<input type="checkbox" id="chkVisibility" <?php if ($visibility=="true") { echo "checked"; } ?>/>
							<span class="slider round"></span>
						</label>
                    </div>				          
						<button type="button" id="btnAddrecord" class="btn btn-success" onclick="addrecord()"><?php if($requesttype=="add"){ echo "Add Record"; }else{ echo "Update Record"; } ?></button>
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
		 if(!validateBlank($("#txtsubmodule_name").val())){
			setErrorOnBlur("txtsubmodule_name");
		  }
		   else if(!validateBlank($("#txtSubModuleicon").val())){
			setErrorOnBlur("txtSubModuleicon");
		  }
		  else if(!validateBlank($("#txtUrl").val())){
			setErrorOnBlur("txtUrl");
		  }
		  else if(!validateBlank($("#txtsubmodule_type").val())){
			setErrorOnBlur("txtsubmodule_type");
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
				url:"apiAddSubModule.php",
				data:{submodule_name:$("#txtsubmodule_name").val(),
				module:$("#txtsubmodule_type").val(),
				sub_module_icon:$("#txtSubModuleicon").val(),
				url:$("#txtUrl").val(),
				priority:$("#txtPriority").val(),
				description:$("#txtDescription").val(),
				visibility:$('#chkVisibility').prop('checked')},
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
							showAlertRedirect("Success","Record Added Successfully","success","pgSubModuleMaster.php");
							}
							
						else{
							$('#btnAddrecord').html('Update Record');
							showAlertRedirect("Success","Record Updated Successfully","success","pgSubModuleMaster.php");
						}
						$("#txtsubmodule_name").val("");
						$("#txtsubmodule_type").val("");
						$("#txtSubModuleicon").val("");
						$("#txtUrl").val("");
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