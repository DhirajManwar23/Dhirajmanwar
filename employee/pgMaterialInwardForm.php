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
$_SESSION["requesttype"] = $requesttype;
$id = $_REQUEST["id"];
$_SESSION["requestid"] = $id; 

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
	
$supplier	 = "";
$material_name = ""; 
$inward_quantity = "";
$rejected_quantity = "";
$reason = "";
$net_quantity = "";
$Status = "";
$qry3 = "select company_id from tw_employee_registration where id = '".$_SESSION["employee_id"]."'";
$retVal3 = $sign->SelectF($qry3,'company_id');
if($requesttype=="edit"){
	$qry = "SELECT supplier,material_name,inward_quantity,rejected_quantity,reason,net_quantity from  tw_material_inward WHERE id = ".$id." ";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$supplier	= $decodedJSON->response[0]->supplier	;
	$material_name = $decodedJSON->response[1]->material_name; 
	$inward_quantity = $decodedJSON->response[2]->inward_quantity;
	$rejected_quantity = $decodedJSON->response[3]->rejected_quantity;
	$reason = $decodedJSON->response[4]->reason;
	$net_quantity = $decodedJSON->response[5]->net_quantity;
	
	$qry1 = "SELECT cm.id,cd.CompanyName FROM tw_company_network cm LEFT JOIN tw_company_details cd ON cm.sender_id = cd.ID WHERE cm.receiver_id='".$retVal3."' ORDER by id  ASC";
	$retVal1 = $sign->FunctionOption($qry1,$supplier,'CompanyName','id');
	
	$qry2 = "SELECT id,product_name FROM tw_product_management WHERE public_visible='true' ORDER by id  ASC";
	$retVal2 = $sign->FunctionOption($qry2,$material_name,'product_name','id');
		
}
else{
	//echo $qry1 = "SELECT cm.id,cd.CompanyName FROM tw_company_network cm LEFT JOIN tw_company_details cd ON cm.sender_id = cd.ID WHERE cm.receiver_id='".$retVal3."' ORDER by id  ASC";
	echo $qry1 = "SELECT id,CompanyName FROM tw_company_details WHERE ID='".$retVal3."' ORDER by id  ASC";
	$retVal1 = $sign->FunctionOption($qry1,$Status,'CompanyName','id');
	
	$qry2 = "SELECT id,product_name FROM tw_product_management WHERE public_visible='true' ORDER by id  ASC";
	$retVal2 = $sign->FunctionOption($qry2,$Status,'product_name','id');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Material Inward</title>
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
                  <h4 class="card-title">Material Inward</h4>
                 
                  <div class="forms-sample">
                    <div class="form-group">
                      <label for="txtSupplier">Supplier <code>*</code></label>
						 <select id="txtSupplier" class="form-control form-control-sm">
							<?php echo $retVal1; ?>
						</select>
					</div>
                   <div class="form-group">
                      <label for="txtProductName">Material Name<code>*</code></label>
						<select id="txtMaterialName" class="form-control form-control-sm">
							<?php echo $retVal2; ?>
						</select>
					</div>
                   <div class="form-group">
                      <label for="txtInwardQuantity">Inward Quantity<code>*</code></label>
                      <input type="number" class="form-control" id="txtInwardQuantity" maxlength="100" value="<?php echo $inward_quantity; ?>" placeholder="Inward Quantity" />
                    </div> 
					<div class="form-group">
                      <label for="txtRejectedQuantity">Rejected Quantity<code>*</code></label>
						<input type="number" class="form-control" id="txtRejectedQuantity" maxlength="100" value="<?php echo $rejected_quantity; ?>" placeholder="Rejected Quantity" />
				   </div>
                   <div class="form-group">
                      <label for="txtReason">Reason of Rejection<code>*</code></label>
                      <input type="text" class="form-control" id="txtReason" maxlength="100" value="<?php echo $reason; ?>" placeholder="Reason of Rejection" />
                    </div>
                   <div class="form-group">
                      <label for="txtNetQuantity">Net Quantity<code>*</code></label>
                      <input type="number" class="form-control" id="txtNetQuantity" maxlength="100" value="<?php echo $net_quantity; ?>" placeholder="Opening Quantity" />
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
  <script src="../assets/js/custom/sweetalert2.min.js"></script>
	<script src="../assets/js/custom/sweetAlert.js"></script>
  <script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/template.js"></script>
  <script src="../assets/js/settings.js"></script>
  <!-- endinject -->
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
var valrequesttype = "<?php echo $requesttype;?>";

function addrecord(){
		  if(!validateBlank($("#txtSupplier").val())){
			setErrorOnBlur("txtSupplier");
		  }
		  else if(!validateBlank($("#txtMaterialName").val())){
			setErrorOnBlur("txtMaterialName");
		  } 
		  else if(!validateBlank($("#txtInwardQuantity").val())){
			setErrorOnBlur("txtInwardQuantity");
		  }
		  else if(!validateBlank($("#txtRejectedQuantity").val())){
			setErrorOnBlur("txtRejectedQuantity");
		  } 
		  else if(!validateBlank($("#txtReason").val())){
			setErrorOnBlur("txtReason");
		  } 
		  else if(!validateBlank($("#txtNetQuantity").val())){
			setErrorOnBlur("txtNetQuantity");
		  }
		  else{
		  
			var valcreated_by = "<?php echo $created_by;?>";
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			if(valrequesttype=="add"){
				var valquery = "insert into tw_material_inward(employee_id,supplier,material_name,inward_quantity,rejected_quantity,reason,net_quantity,created_by,created_on,created_ip)values('"+valcreated_by+"','"+$("#txtSupplier").val()+"','"+$("#txtMaterialName").val()+"','"+$("#txtInwardQuantity").val()+"','"+$("#txtRejectedQuantity").val()+"','"+$("#txtReason").val()+"','"+$("#txtNetQuantity").val()+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				var valquerycount = "select count(*) as cnt from tw_material_inward where material_name = 'karuna'";
			}
			else{
				var valrequestid = "<?php echo $id;?>";
				var valquerycount = "select count(*) as cnt from tw_material_inward where material_name = 'karuna'";
				var valquery = "Update tw_material_inward set supplier = '"+$("#txtSupplier").val()+"' , material_name = '"+$("#txtMaterialName").val()+"', inward_quantity = '"+$("#txtInwardQuantity").val()+"', rejected_quantity = '"+$("#txtRejectedQuantity").val()+"', reason = '"+$("#txtReason").val()+"', net_quantity = '"+$("#txtNetQuantity").val()+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
			
			}
			var buttonHtml = $('#btnAddrecord').html();
			
			$('#btnAddrecord').attr("disabled","true");
			$('#btnAddrecord').removeClass('btn-success');
			$('#btnAddrecord').addClass('btn-secondary');//secondary;
			$('#btnAddrecord').html('<i class="ti-timer"></i> Processing...');
			 
			
			$.ajax({
				type:"POST",
				url:"apiEmployeeProfile.php",
				data:{valquery:valquery,valquerycount:valquerycount},
				success:function(response){
					console.log(response);
					$('#btnAddrecord').removeAttr('disabled');
					$('#btnAddrecord').removeClass('btn-secondary');
					$('#btnAddrecord').addClass('btn-success');
					if($.trim(response)=="Success"){
						if(valrequesttype=="add"){
							showAlertRedirect("Success","Data Added Successfully","success","pgMaterialInward.php");
						}
						else{
							showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialInward.php");
						}
					}
					else if($.trim(response)=="Exist"){
						showAlert("warning","Value already exist","warning");
						$("#txtValue").focus();
					}else{
						showAlert("error","Something Went Wrong. Please Try After Sometime","error");
					}
					
					$('#btnAddrecord').html(buttonHtml);
				}
			}); 
		  }
}

function alertBox(value){
	alert(value);
}
	
</script>	
</body>

</html>