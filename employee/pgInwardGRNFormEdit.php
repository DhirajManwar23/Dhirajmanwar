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
$id = $_REQUEST["id"];
$inward_id = $_REQUEST["inward_id"];

$company_id = $_SESSION["company_id"];
$created_by=$_SESSION["employee_id"];

//whether ip is from share internet
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$supplier_address = "";
$company_address = "";
$date = "";
$supplier_id = "";
$Status = "";

$customer_id = "";
$bill_to = "";
$ship_to = "";
$CompanyName = "";
$address_line1 = "";
$address_line2 = "";
$location = "";
$pincode = "";
$city = "";
$state = "";
$country = "";
$address_line1 = "";
$address_line2 = "";
$location = "";
$pincode = "";
$city = "";
$state = "";
$country = "";
$remark = "";
$DocNo="";

$settingValueStatuspending= $commonfunction->getSettingValue("Registration Status");	
$query="SELECT customer_id,bill_to,ship_to,po_id,company_address FROM `tw_material_outward` where id='".$inward_id."'";
$data = $sign->FunctionJSON($query);
$decodedJSON = json_decode($data);
$customer_id = $decodedJSON->response[0]->customer_id; 
$bill_to = $decodedJSON->response[1]->bill_to;
$ship_to = $decodedJSON->response[2]->ship_to;
$po_id = $decodedJSON->response[3]->po_id;
$company_address = $decodedJSON->response[4]->company_address;
	if($requesttype=="edit")
	{
		echo $qry="select date,remark,supplier_id from tw_material_inward_grn where id='".$requestid."'";
		echo $retVal = $sign->FunctionJSON($qry);
		$decodedJSON = json_decode($retVal);
		$date = $decodedJSON->response[0]->date;
		$remark = $decodedJSON->response[1]->remark;
		$supplier_id = $decodedJSON->response[2]->supplier_id;
		
	}

	$qry1="select CompanyName from tw_company_details where ID='".$customer_id."'";
	$CustomerCompanyName = $sign->SelectF($qry1,"CompanyName");
	
	$qry3="select address_line1,address_line2,location,pincode,city,state,country from tw_company_address where id='".$company_address."'";
	
	$retVal3 = $sign->FunctionJSON($qry3);
	$decodedJSON3 = json_decode($retVal3);
	if(isset($decodedJSON3->response[0]->address_line1))
	{
		$baddress_line1 = $decodedJSON3->response[0]->address_line1;
	}
	else
	{
		$baddress_line1="";
	}
	if(isset($decodedJSON3->response[1]->address_line2))
	{
		$baddress_line2 = $decodedJSON3->response[1]->address_line2;
	}
	else
	{
		$baddress_line2="";
	}
	if(isset($decodedJSON3->response[2]->location))
	{
		$blocation = $decodedJSON3->response[2]->location;
	}
	else
	{
		$blocation="";
	}
	if(isset($decodedJSON3->response[3]->pincode))
	{
		$bpincode = $decodedJSON3->response[3]->pincode;
	}
	else
	{
		$bpincode="";
	}
	if(isset($decodedJSON3->response[4]->city))
	{
		$bcity = $decodedJSON3->response[4]->city;
	}
	else
	{
		$bcity="";
	}
	if(isset($decodedJSON3->response[5]->state))
	{
		$bstate = $decodedJSON3->response[5]->state;
	}
	else
	{
		$bstate="";
	}
	if(isset($decodedJSON3->response[6]->country))
	{
		$bcountry = $decodedJSON3->response[6]->country;
	}
	else
	{
		$bcountry="";
	}
	
	$bill=$baddress_line1.",".$baddress_line2.",".$blocation.",".$bpincode.",".$bcity.",".$bstate.",".$bcountry;

	
	$qry4="select address_line1,address_line2,location,pincode,city,state,country from tw_company_address where id='".$ship_to."'";
	$retVal4 = $sign->FunctionJSON($qry4);
	$decodedJSON3 = json_decode($retVal4);
	//var_dump($decodedJSON4);
	if(isset($decodedJSON3->response[0]->address_line1))
	{
		$saddress_line1 = $decodedJSON3->response[0]->address_line1;
	}
	else
	{
		$saddress_line1="";
	}
	if(isset($decodedJSON3->response[1]->address_line2))
	{
		$saddress_line2 = $decodedJSON3->response[1]->address_line2;
	}
	else
	{
		$saddress_line2="";
	}
	if(isset($decodedJSON3->response[2]->location))
	{
		$slocation = $decodedJSON3->response[2]->location;
	}
	else
	{
		$slocation="";
	}
	if(isset($decodedJSON3->response[3]->pincode))
	{
		$spincode = $decodedJSON3->response[3]->pincode;
	}
	else
	{
		$spincode="";
	}
	if(isset($decodedJSON3->response[4]->city))
	{
		$scity = $decodedJSON3->response[4]->city;
	}
	else
	{
		$scity="";
	}
	if(isset($decodedJSON3->response[5]->state))
	{
		$sstate = $decodedJSON3->response[5]->state;
	}
	else
	{
		$sstate="";
	}
	if(isset($decodedJSON3->response[6]->country))
	{
		$scountry = $decodedJSON3->response[6]->country;
	}
	else
	{
		$scountry="";
	}
	
	$ship=$saddress_line1.",".$saddress_line2.",".$slocation.",".$spincode.",".$scity.",".$sstate.",".$scountry;
	
	$qry2 = "SELECT id,product_name FROM tw_product_management WHERE public_visible='true' ORDER by id  ASC";
	$retVal2 = $sign->FunctionOption($qry2,$Status,'product_name','id'); 
	
	$qryDocCnt="Select  count(*) as cnt from tw_material_outward_documents WHERE outward_id='".$inward_id."' and type='Invoice' ";
	$DocCnt = $sign->Select($qryDocCnt);
	if($DocCnt==1){
		$qryDocno = "SELECT document_value FROM tw_material_outward_documents WHERE outward_id='".$inward_id."' and type='Invoice'";
		 $DocNo = $sign->SelectF($qryDocno,'document_value'); 
	}
	else{
		$qryDocCnt1="Select count(*) as cnt from tw_tax_invoice WHERE outward_id='".$inward_id."'";
		$DocCnt1 = $sign->Select($qryDocCnt1);
		if($DocCnt1==1){
			$qryDocno = "SELECT invoice_number FROM tw_tax_invoice WHERE outward_id='".$inward_id."'";
			$DocNo = $sign->SelectF($qryDocno,'invoice_number'); 
		}
		
		
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste |GRN Master</title>
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
<link rel="stylesheet" href="../assets/css/custom/style.css">
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
                  <h4 class="card-title">GRN Master</h4>
                  <div class="forms-sample">
					 <div class="form-group">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicenumber">Invoice Number <code>*</code></label>	
								<input type="text" disabled Placeholder="Invoice Number" class="form-control" id="txtinvoicenumber" 
								value="<?php echo $DocNo; ?>" />
							 </div>
						</div>
                    </div>
					<div class="form-group">
						<div class="row">
					   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<label for="txtsuppliername">Company Name <code>*</code></label>	
							<input type="text" disabled class="form-control" id="txtsuppliername" Placeholder="Company Name" maxlength="30" value=" <?php echo $CustomerCompanyName; ?>" />
                       </div>
					   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<label for="txtDate"> Date <code>*</code></label>
							<input type="Date" class="form-control" value='<?php if(!empty($cur_date)){echo date("Y-m-d",strtotime($cur_date));}?>' id="txtDate"  placeholder="Date" />
                       </div>
                       </div>
					</div>
					
					<div class="form-group">
						<div class="row">
							
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Company Address</h3>
									<div class="pAddress-body">
										<p id="txtsupplieraddress"><?php echo $ship; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Vendor Address</h3>
									<div class="pAddress-body">
										<p id="txtcompanyaddress"><?php echo $bill; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
                      <label for="txtremark">Remark<code>*</code></label>
                      <textarea type="text" class="form-control" id="txtremark" maxlength="5000" placeholder="Remark"><?php echo $remark; ?></textarea>
                    </div>
					
					<hr>
					<div class="table-responsive">
						<table id="tableData" class="table">
						 
						</table>
					</div>
					<br>
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
var hdnOrderID = '<?php echo $inward_id; ?>'; 
var valrequested = '<?php echo $requestid; ?>'; 
var valpo_id = "<?php echo $po_id; ?>";

 $(document).ready(function(){
	funcremoverowload();
});
$("#txtMaterialName").on('change keyup paste', function () {
	var selectedId = $(this).children("option:selected").val();  
	$.ajax({
	  type:"POST",
	  url:"apiGetMaterialInwardGrnInfo.php",
	  data:{txtMaterialName:selectedId},
	  dataType: 'JSON',
	  success:function(response){
		  console.log(response);
		  
		  $("#txtRate").val(response[0]);
		  $("#txtUOM").val(response[1]);
		  $("#txtTax").val(response[2]);
	  }
  }); 
}); 
$("#txtQuantity").on('change keyup paste', function () {
	var TotalAmt = $("#txtRate").val()*$("#txtQuantity").val();
	$("#txtTotal").val(TotalAmt);
}); 
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

 function adddata(){
	    if(!validateBlank($("#txtDate").val())){
			setErrorOnBlur("txtDate");
		 } 
		else if(!validateBlank($("#txtremark").val())){
			setErrorOnBlur("txtremark");
		  } else { 
		
		var buttonHtml = $('#btnAddrecord').html();
		var valcreated_by="<?php echo $created_by;?>";
		var valcreated_on="<?php echo $cur_date;?>";
		var valcreated_ip="<?php echo $ip_address;?>";
		var varinward_id = "<?php echo $inward_id; ?>";
		var valrequesttype="<?php echo $requesttype;?>";
		var valcompany_id = "<?php echo $company_id;?>";
		
		var valcustomer_id = "<?php echo $customer_id;?>";
		
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
		
		$.ajax({
			type:"POST",
			url:"apiEditMaterialInwardGRN.php",
			data:{company_id:valcompany_id,txtsuppliername:valcustomer_id,txtDate:$("#txtDate").val(),txtremark:$("#txtremark").val(),inward_id:varinward_id,hdnOrderID:valrequested},
			success:function(response){
				console.log(response);
				if(valrequesttype=="add"){
					enableButton('#btnAddrecord','Add Record');
				}
				else
				{
					enableButton('#btnAddrecord','Update Record');
				}
				if($.trim(response)=="Success"){
					if(valrequesttype=="add"){
						showAlertRedirect("Success","Data Added Successfully","success","pgMaterialInward.php?type=In%20Process&po_id="+valpo_id);
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialInward.php?type=In%20Process&po_id="+valpo_id);
					}
				}/* 
				else if($.trim(response)=="Blank"){
					showAlert("warning","Please Add Description","warning");
					$("#selDescription").focus();
				} */else{
					showAlert("error","Something Went Wrong. Please Try After Sometime","error");
				}
				
				$('#btnAddrecord').html(buttonHtml);
			}
		}); 
		//---
		
		
  } 
}

/* function funcaddrow(){
   if(!validateBlank($("#txtMaterialName").val())){
		setErrorOnBlur("txtMaterialName");
   }
   else if(!validateBlank($("#txtQuantity").val())){
		setErrorOnBlur("txtQuantity");
   }
   else if(!validateBlank($("#txtRate").val())){
		setErrorOnBlur("txtRate");
   }
   else if(!validateBlank($("#txtTax").val())){
		setErrorOnBlur("txtTax");
   }
   else if(!validateBlank($("#txtTotal").val())){
		setErrorOnBlur("txtTotal");
   }
  else{
	  $.ajax({
          type:"POST",
          url:"apiAddTempMaterialInwardGRN.php",
          data:{txtMaterialName:$("#txtMaterialName").val(),txtQuantity:$("#txtQuantity").val(),txtRate:$("#txtRate").val(),txtUOM:$("#txtUOM").val(),txtTax:$("#txtTax").val(),txtTotal:$("#txtTotal").val()},
          success:function(response){
			  console.log(response);
			$("#tableData").html(response);
			var resp=response;
			var array = resp.split("</tbody>,");
			$("#txtFinalTotalAmount").val(array[1]);
			$("#txtMaterialName").val("");
			$("#txtQuantity").val("");
			$("#txtRate").val("");
			$("#txtTotal").val("");
          }
      });
  }
} */
function funcremoverowload(){
	
	   $.ajax({
          type:"POST",
          url:"apiDeleteTempMaterialInwardGRN.php",
          data:{requestidid:''},
          success:function(response){
			console.log(response);
			$('#tableData').html(response);
			$("#txtDescription").val("");
			$("#txtQuantity").val("");
			$("#txtUOM").val("");
			$("#txtRate").val("");
			showdata();
          }
      }); 
}
 function showdata(){
	
	  $.ajax({
          type:"POST",
          url:"apiGetInwardTempTableDetails.php",
          data:{hdnOrderID:hdnOrderID},
          success:function(response){
            console.log(response);
			$("#tableData").html(response);
			var resp=response;
			var array = resp.split("</tbody>,");
		   
          }
      });
  } 
</script>
</body>
</html>