<?php 
session_start();
if(!isset($_SESSION["username"])){
	header("Location:pgLogin.php");
}
// Include class definition
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

$Status = "";
$category_id = "";
$sub_category_name = "";
$priority = "";
$description = "";
$visibility = "";

if($requesttype=="add"){
	
	$qry1 = "select id,category_name from tw_category_master where visibility = 'true' ORDER by priority,category_name ASC";
	$retVal1 = $sign->FunctionOption($qry1,$Status,'category_name','id');
		
}
else{
	
	$qry = "SELECT cm.category_name,scm.sub_category_name,scm.priority,scm.description,scm.visibility from tw_subcategory_master scm LEFT JOIN tw_category_master cm ON scm.category_id = cm.id WHERE scm.id ='".$requestid."'";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$category_name = $decodedJSON->response[0]->category_name;
	$sub_category_name = $decodedJSON->response[1]->sub_category_name;
	$priority = $decodedJSON->response[2]->priority; 
	$description = $decodedJSON->response[3]->description;
	$visibility = $decodedJSON->response[4]->visibility;

	$qry1 = "select id,category_name from tw_category_master where visibility = 'true' ORDER by priority,category_name ASC";
	$retVal1 = $sign->FunctionOption($qry1,$Status,'category_name','id');

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Bank Details</title>
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
                  <h4 class="card-title">Sub Category master</h4>
					<div class="forms-sample">
						<div class="form-group">
							<label for="Category_name">Category Name<code>*</code></label>
							<div class="form-group">
							<select id="selCategoryID" class="form-control form-control-sm">
							<?php echo $retVal1; ?>
							</select>
							</div>
						</div>
						<div class="form-group">
							<label for="txtTaxName">Sub Category Name<code>*</code></label>
							<div class="form-group">
							<input type="text" class="form-control form-control-sm" maxlength="20"  value="<?php echo $sub_category_name; ?>" id="txtSub_Category_Name" placeholder="Sub Category Name"/>
							</div>
						</div>
						<div class="form-group">
							<label for="Priority">Priority<code>*</code></label>
							<div class="form-group">
							<input type="number" class="form-control form-control-sm"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" value="<?php echo $priority; ?>" id="txtPriority" placeholder="Priority">
							</div>
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
	  if(!validateBlank($("#txtSub_Category_Name").val())){
			setErrorOnBlur("txtSub_Category_Name");
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
		  
			var valcreated_by = "<?php echo $created_by;?>";
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			var valrequesttype = "<?php echo $requesttype;?>";
			
			if(valrequesttype=="add"){
				var valquery = "insert into tw_subcategory_master(category_id,sub_category_name,priority,description,visibility,created_by,created_on,created_ip) values('"+$("#selCategoryID").val()+"','"+$("#txtSub_Category_Name").val()+"','"+$("#txtPriority").val()+"','"+$("#txtDescription").val()+"','"+$('#chkVisibility').prop('checked')+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				var valquerycount = "select count(*) as cnt from tw_subcategory_master where sub_category_name = '"+$("#txtSub_Category_Name").val()+"'";
			}
			else{
				var valrequestid = "<?php echo $requestid;?>";
				
				var valquery = "Update tw_subcategory_master set category_id = '"+$("#selCategoryID").val()+"' ,sub_category_name = '"+$("#txtSub_Category_Name").val()+"' , priority = '"+$("#txtPriority").val()+"', description = '"+$("#txtDescription").val()+"', visibility = '"+$('#chkVisibility').prop('checked')+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
			
				var valquerycount = "select count(*) as cnt from tw_subcategory_master where sub_category_name = '"+$("#txtSub_Category_Name").val()+"' and id!='"+valrequestid+"'";
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
						showAlertRedirect("Success","Record Added Successfully","success","pgSubCategory.php");
					}
					else{
						$('#btnAddrecord').html('Update Record');
						showAlertRedirect("Success","Record Updated Successfully","success","pgSubCategory.php");
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