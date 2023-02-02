<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogin.php");
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
$created_by=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];

$sub_category_id = "";
$state_id = "";
$product_name = "";
$quantity = "";
$months_and_year = "";

	$qry = "SELECT ccm.sub_category_name,cm.state_id,cm.product_name,cm.quantity,cm.months_and_year from tw_consumption cm LEFT JOIN tw_subcategory_master ccm ON cm.sub_category_id = ccm.id WHERE cm.id ='".$requestid."'";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$sub_category_name = $decodedJSON->response[0]->sub_category_name;
	$state_id = $decodedJSON->response[1]->state_id;
	$product_name = $decodedJSON->response[2]->product_name;
	$quantity = $decodedJSON->response[3]->quantity; 
	$months_and_year = $decodedJSON->response[4]->months_and_year;

if($requesttype=="add"){
	
	$qry1 = "select id,sub_category_name from tw_subcategory_master where visibility = 'true' ORDER by priority,sub_category_name asc";
	$retVal1 = $sign->FunctionOption($qry1,$sub_category_name,'sub_category_name','id');
}
else{
	$qry1 = "select id,sub_category_name from tw_subcategory_master where visibility = 'true' ORDER by priority asc";
	$retVal1 = $sign->FunctionOption($qry1,$sub_category_name,'sub_category_name','id');
}
$qry3 = "select id,state_name from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id = twpi.id WHERE twpi.company_id ='".$company_id."')";
	$retVal3 = $sign->FunctionOption($qry3,$state_id,'state_name','id');

	 $qrycnt = "select count(*) as cnt from tw_state_master where id in (SELECT DISTINCT(twpd.state) FROM tw_po_details twpd LEFT JOIN tw_po_info twpi ON twpd.po_id = twpi.id WHERE twpi.company_id ='".$company_id."')";
	 $retValcnt = $sign->select($qrycnt);

?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Consumption Form</title>
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
                  <h4 class="card-title">Consumption</h4>
					<div class="forms-sample">
					
						<div class="form-group">
							<label for="txtName">Material Type<code>*</code></label>
							<div class="form-group">
							<select id="selSubCategoryID" class="form-control form-control-sm">
							<?php echo $retVal1; ?>
							</select>
							</div>
						</div>
						<div class="form-group">
							<label for="txtstatename">State Name <code>*</code></label>
							<div class="form-group">
							<select id="txtstatename" class="form-control" >
								<?php echo $retVal3;?>
							</select>
							</div>
						</div>
						<div class="form-group">
							<label for="txtProductName">Product Name<code>*</code></label>
							<div class="form-group">
							<input type="text" class="form-control form-control-sm" maxlength="20"  value="<?php echo $product_name; ?>" id="txtProductName" placeholder="Product Name"/>
							</div>
						</div>
	
						<div class="form-group ">
							<label for="txtQty">Quantity<code>*</code></label>
							<div class="form-group">
							<input type="number" class="form-control form-control-sm" id="txtQty" value="<?php echo $quantity; ?>" placeholder="Quantity" />
							</div>
						</div>
						
						<div class="form-group">
							<label for="txtMonthYear">Month & year<code>*</code></label>
							<div class="form-group">
							<input type="month" class="form-control form-control-sm" maxlength="20"  value="<?php echo $months_and_year; ?>" id="txtMonthYear" placeholder="Month and Year"/>
							</div>
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
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
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
$("#txtQty").blur(function()
{
	removeError(txtQty);
	if ($("#txtQty").val()!="")
	{
		if(!isNumber($("#txtQty").val())){
			setError(txtQty);
		}
		else
		{
			removeError(txtQty);
		}
	}
});
function adddata(){
		if(!validateBlank($("#txtProductName").val())){
			setErrorOnBlur("txtProductName");
		  }
		else if(!validateBlank($("#txtQty").val())){
			setErrorOnBlur("txtQty");
		 }
		else if($("#txtQty").val()<=0){
			setError(txtQty);
		}  
		else if(!validateBlank($("#txtMonthYear").val())){
		setErrorOnBlur("txtMonthYear");
		  } 
		  else{
			disableButton('#btnAddrecord','Processing...');
		  
			var valcreated_by = "<?php echo $created_by;?>";
			var valcreated_on = "<?php echo $cur_date;?>";
			var valcreated_ip = "<?php echo $ip_address;?>";
			var valrequesttype = "<?php echo $requesttype;?>";
			var valcompany_id = "<?php echo $company_id;?>";

			if(valrequesttype=="add"){
				var valquery = "insert into tw_consumption(company_id,state_id,sub_category_id,product_name,quantity,months_and_year,created_by,created_on,created_ip) values('"+valcompany_id+"','"+$("#txtstatename").val()+"','"+$("#selSubCategoryID").val()+"','"+$("#txtProductName").val()+"','"+$("#txtQty").val()+"','"+$("#txtMonthYear").val()+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				var valquerycount = "select count(*) as cnt from tw_consumption where months_and_year = '"+$("#txtMonthYear").val()+"'";
	
			}
			else{
				var valrequestid = "<?php echo $requestid;?>";
				var valquery = "Update tw_consumption set company_id = '"+valcompany_id+"' ,state_id = '"+$("#txtstatename").val()+"' , sub_category_id = '"+$("#selSubCategoryID").val()+"', product_name = '"+$("#txtProductName").val()+"' , quantity = '"+$("#txtQty").val()+"', months_and_year = '"+$("#txtMonthYear").val()+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"'";
				var valquerycount = "select count(*) as cnt from tw_consumption where months_and_year = '"+$("#txtMonthYear").val()+"' and id!='"+valrequestid+"'";

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
						showAlertRedirect("Success","Record Added Successfully","success","pgConsumption.php");
					}
					else{
						$('#btnAddrecord').html('Update Record');
						showAlertRedirect("Success","Record Updated Successfully","success","pgConsumption.php");
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