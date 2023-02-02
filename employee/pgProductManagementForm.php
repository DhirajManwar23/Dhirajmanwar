<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
$requesttype = $_REQUEST["type"];
$_SESSION["requesttype"] = $requesttype;
$id = $_REQUEST["id"];
$_SESSION["requestid"] = $id; 

// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
	
$category	 = "";
$sub_category = ""; 
$product_name = "";
$product_type = "";
$amount_per_unit = "";
$uom = "";
$tax = "";
$opening_quantity = "";
$threshold = "";
$public_visible = "";
$hsn = "";
$Status = "";

$qry3 = "select company_id from tw_employee_registration where id = '".$created_by."'";
$retVal3 = $sign->SelectF($qry3,'company_id');

if($requesttype=="edit"){
	$qry = "SELECT category,sub_category,product_name,product_type,amount_per_unit,uom,tax,opening_quantity,threshold,public_visible,hsn from  tw_product_management WHERE id = ".$id." ";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$category	= $decodedJSON->response[0]->category	;
	$sub_category = $decodedJSON->response[1]->sub_category; 
	$product_name = $decodedJSON->response[2]->product_name;
	$product_type = $decodedJSON->response[3]->product_type;
	$amount_per_unit = $decodedJSON->response[4]->amount_per_unit;
	$uom = $decodedJSON->response[5]->uom;
	$tax = $decodedJSON->response[6]->tax;
	$opening_quantity = $decodedJSON->response[7]->opening_quantity;
	$threshold = $decodedJSON->response[8]->threshold;
	$public_visible = $decodedJSON->response[9]->public_visible;
	$hsn = $decodedJSON->response[10]->hsn;
	

	
$qry5 = "select id,tax_name from tw_tax_master where visibility = 'true'  ORDER by priority, tax_name  ASC";
$retVal5 = $sign->FunctionOption($qry5,$tax,'tax_name','id');
	
$qry6 = "select id,name from tw_unit_of_measurement where visibility = 'true'  ORDER by priority, name  ASC";
$retVal7 = $sign->FunctionOption($qry6,$uom,'name','id');
}
else{
		
	$qry5 = "select id,tax_name from tw_tax_master where visibility = 'true'  ORDER by priority, tax_name  ASC";
	$retVal5 = $sign->FunctionOption($qry5,$Status,'tax_name','id');
	
	$qry6 = "select id,name from tw_unit_of_measurement where visibility = 'true'  ORDER by priority, name  ASC";
	$retVal7 = $sign->FunctionOption($qry6,$Status,'name','id');
}

	$qry1 = "select id,category_name from tw_category_master where visibility='true' Order by priority,category_name";
	$retVal1 = $sign->FunctionOption($qry1,$category,'category_name','id');

if(empty($category)){
	$qry3 = "select id from tw_category_master where visibility='true' Order by priority,category_name limit 1";
	$category=$sign->SelectF($qry3,"id");
}

$qry2 = "select id,sub_category_name from tw_subcategory_master where visibility='true' and category_id = '".$category."'";
$retVal2 = $sign->FunctionOption($qry2,$sub_category,'sub_category_name','id');

if(empty($sub_category)){
	$qry10 = "select id from tw_subcategory_master where visibility='true' and category_id = '".$category."' limit 1";
	$sub_category=$sign->SelectF($qry10,"id");
}		
$qry4 = "select id,name from tw_product_type_master where visibility = 'true' and sub_category_id = '".$sub_category."'";
$retVal4 = $sign->FunctionOption($qry4,$product_type,'name','id');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Product Management</title>
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
                  <h4 class="card-title">Product Management</h4>
                 
                  <div class="forms-sample">
				  <div class="form-group">
						<div class="row">
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							 <label for="txtcategoryname">Category Name <code>*</code></label>
							<select id="txtcategoryname" class="form-control"  onchange="fetchSubCategories(this.value)">
								<?php echo $retVal1;?>
							</select>
						</div>
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							 <label for="txtsubcategoryname">Sub Category Name <code>*</code></label>
							 <select id="txtsubcategoryname" class="form-control"  onchange="fetchMaterialType(this.value)">
							<?php echo $retVal2;?>
							</select>
						</div>
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							 <label for="txtProductType">Material Type <code>*</code></label>
							<select id="txtProductType" class="form-control form-control-sm">
								<?php echo $retVal4; ?>
							</select>  
                       </div>
                       </div>
					</div>
					<div class="form-group">
							<label for="txtProductName">Product Name <code>*</code></label>
							<input type="text" class="form-control" id="txtProductName" maxlength="100" value="<?php echo $product_name; ?>" placeholder="Material Name" />
					</div>
					<div class="form-group">
						<div class="row">
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							 <label for="txtAmountPerUnit">Amount Per Unit <code>*</code></label>
							<input type="number" class="form-control" id="txtAmountPerUnit" maxlength="100" value="<?php echo $amount_per_unit; ?>" placeholder="Amount Per Unit" />
                       </div>
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtUom">UOM <code>*</code></label>
							<select id="txtUom" class="form-control form-control-sm">
								<?php echo $retVal7; ?>
							</select> 
                       </div>
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtTax">Tax <code>*</code></label>
							<select id="txtTax" class="form-control form-control-sm">
								<?php echo $retVal5; ?>
							</select>     
                       </div>
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtHSN">HSN <code>*</code></label>
							<input type="text" class="form-control" id="txtHSN" maxlength="100" value="<?php echo $hsn; ?>" placeholder="HSN" />
                       </div>
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							 <label for="txtOpeningQuantity">Opening Quantity <code>*</code></label>
							<input type="number" class="form-control" id="txtOpeningQuantity" maxlength="100" value="<?php echo $opening_quantity; ?>" placeholder="Opening Quantity" />
                       </div>
					   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="txtThreshold">Threshold <code>*</code></label>
							<input type="text" class="form-control" id="txtThreshold" maxlength="100" value="<?php echo $threshold; ?>" placeholder="Threshold" />
                       </div>
                       </div>
					</div>
                   
					  <div class="form-group">
						<label for="chkVisibility">Visibility (Optional)</label><br>
						  <label class="switch">
						  <input type="checkbox" id="chkVisibility" <?php if ($public_visible=="true") { echo "checked"; } ?>/>
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
function fetchSubCategories(categoryId) {
	$.ajax({
				type:"POST",
				url:"apiGetSubCategories.php",
				data:{category_id:categoryId},
				success:function(response){
					$("#txtsubcategoryname").html(response);
				}
			});   

}
function fetchMaterialType(subcategoryId) {
	$.ajax({
				type:"POST",
				url:"apiGetMaterialType.php",
				data:{sub_category_id:subcategoryId},
				success:function(response){
					$("#txtProductType").html(response);
				}
			});   

}	 	 
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

$("#txtOpeningQuantity").blur(function()
{
	removeError(txtOpeningQuantity);
	if ($("#txtOpeningQuantity").val()!="")
	{
		if(!isNumber($("#txtOpeningQuantity").val())){
			setError(txtOpeningQuantity);
		}
		else
		{
			removeError(txtOpeningQuantity);
		}
	}
});
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
	
		  if(!validateBlank($("#txtcategoryname").val())){
			setErrorOnBlur("txtcategoryname");
		  }
		  else if(!validateBlank($("#txtsubcategoryname").val())){
			setErrorOnBlur("txtsubcategoryname");
		  } 
		  else if(!validateBlank($("#txtProductName").val())){
			setErrorOnBlur("txtProductName");
		  }
		  else if(!validateBlank($("#txtProductType").val())){
			setErrorOnBlur("txtProductType");
		  } 
		  else if(!validateBlank($("#txtAmountPerUnit").val())){
			setErrorOnBlur("txtAmountPerUnit");
		  } 
		   
		  else if($("#txtAmountPerUnit").val()<0 || $("#txtAmountPerUnit").val()<0.00){
				setError("txtAmountPerUnit");
		  } 
		   
		  else if(!validateBlank($("#txtUom").val())){
			setErrorOnBlur("txtUom");
		  } 
		  else if(!validateBlank($("#txtHSN").val())){
			setErrorOnBlur("txtHSN");
		  }
		  else if(!validateBlank($("#txtTax").val())){
			setErrorOnBlur("txtTax");
		  } else if(!validateBlank($("#txtOpeningQuantity").val())){
			setErrorOnBlur("txtOpeningQuantity");
		  } 
		  else if(!validateBlank($("#txtThreshold").val())){
			setErrorOnBlur("txtThreshold");
		  } 
		  else{
		  
			var valcreated_by = "<?php echo $created_by;?>";
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			var valcompany_id = "<?php echo $retVal3;?>";
			if(valrequesttype=="add"){
				var valquery = "insert into tw_product_management(company_id,category,sub_category,product_name,product_type,amount_per_unit,uom,tax,opening_quantity,threshold,public_visible,hsn,created_by,created_on,created_ip)values('"+valcompany_id+"','"+$("#txtcategoryname").val()+"','"+$("#txtsubcategoryname").val()+"','"+$("#txtProductName").val()+"','"+$("#txtProductType").val()+"','"+$("#txtAmountPerUnit").val()+"','"+$("#txtUom").val()+"','"+$("#txtTax").val()+"','"+$("#txtOpeningQuantity").val()+"','"+$("#txtThreshold").val()+"','"+$('#chkVisibility').prop('checked')+"','"+$("#txtHSN").val()+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				var valquerycount = "select count(*) as cnt from tw_product_management where product_name = '"+$("#txtProductName").val()+"' and company_id='"+valcompany_id+"'";
			}
			else{
				var valrequestid = "<?php echo $id;?>";
				var valquerycount = "select count(*) as cnt from tw_product_management where product_name = '"+$("#txtProductName").val()+"' and company_id!='"+valcompany_id+"'";
				var valquery = "Update tw_product_management set category = '"+$("#txtcategoryname").val()+"' , sub_category = '"+$("#txtsubcategoryname").val()+"', product_name = '"+$("#txtProductName").val()+"', product_type = '"+$("#txtProductType").val()+"', amount_per_unit = '"+$("#txtAmountPerUnit").val()+"', uom = '"+$("#txtUom").val()+"', tax = '"+$("#txtTax").val()+"', opening_quantity = '"+$("#txtOpeningQuantity").val()+"', threshold = '"+$("#txtThreshold").val()+"', public_visible = '"+$('#chkVisibility').prop('checked')+"', hsn = '"+$('#txtHSN').val()+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
			
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
							showAlertRedirect("Success","Data Added Successfully","success","pgProductManagement.php");
						}
						else{
							showAlertRedirect("Success","Data Updated Successfully","success","pgProductManagement.php");
						}
					}
					else if($.trim(response)=="Exist"){
						showAlert("warning","Value already exist","warning");
						$("#txtValue").focus();
					}else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
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