<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
require "function.php";
require "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$settingValueAggregator= $commonfunction->getSettingValue("Aggregator");

$requesttype = $_REQUEST["type"];
$id = $_REQUEST["id"];
$Rid = $_REQUEST["id"];
$po_id = $_REQUEST["id"];
$company_id=$_SESSION['company_id'];

$qry3 = "select po_date,delivery_date,total_quantity,supplier_address,bill_to_address,delivery_terms,disp_instruction,payment_terms,sp_instruction,supplier_id from tw_temp_po_info where id='".$id."' ";
$retVal3 = $sign->FunctionJSON($qry3);
$decodedJSON2 = json_decode($retVal3);
$po_date = $decodedJSON2->response[0]->po_date;
$delivery_date = $decodedJSON2->response[1]->delivery_date;
$total_quantity = $decodedJSON2->response[2]->total_quantity;
$supplier_addressID = $decodedJSON2->response[3]->supplier_address;
$bill_to_addressID = $decodedJSON2->response[4]->bill_to_address;
$delivery_terms = $decodedJSON2->response[5]->delivery_terms;
$disp_instruction = $decodedJSON2->response[6]->disp_instruction;
$payment_terms = $decodedJSON2->response[7]->payment_terms;
$sp_instruction = $decodedJSON2->response[8]->sp_instruction;
$supplier_id = $decodedJSON2->response[9]->supplier_id;

$qry2 = "SELECT id,product_name FROM tw_product_management WHERE company_id='".$supplier_id."' and public_visible='true' ORDER by id  ASC";
$retVal2 = $sign->FunctionOption($qry2,"",'product_name','id');


$qry1 = "SELECT CompanyName FROM tw_company_details where ID='".$supplier_id."' ORDER by id  ASC";
$retValc = $sign->SelectF($qry1,'CompanyName');



$qry="select id,address_line1,address_line2,location,pincode,city,state,address_type,country From tw_company_address where company_id='".$company_id."' AND public_visible='true' ";	

$retVal = $sign->FunctionJSON($qry);
$qry1="Select count(*) as cnt from tw_company_address  where company_id='".$company_id."' and public_visible='true'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$Main_address_bill="";
while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$address_line1 = $decodedJSON2->response[$count]->address_line1;
$count=$count+1;
$address_line2 = $decodedJSON2->response[$count]->address_line2;
$count=$count+1;
$location = $decodedJSON2->response[$count]->location;
$count=$count+1;
$pincode = $decodedJSON2->response[$count]->pincode;
$count=$count+1;
$city = $decodedJSON2->response[$count]->city;
$count=$count+1;
$state = $decodedJSON2->response[$count]->state;
$count=$count+1;
$address_type = $decodedJSON2->response[$count]->address_type;
$count=$count+1;
$country = $decodedJSON2->response[$count]->country;
$count=$count+1;


$is_checked="";

if ($bill_to_addressID==$id)
{
	$is_checked="checked='checked'";
}		

$qry4="SELECT address_icon,address_type_value FROM tw_address_type_master where id='".$address_type."'";
$address_icon= $sign->SelectF($qry4,"address_icon");

$qry5="SELECT address_type_value FROM tw_address_type_master where id='".$address_type."'";
$address_type_value= $sign->SelectF($qry5,"address_type_value");

$address=$address_line1.",<br>".$address_line2.",<br>".$location.",<br>".$pincode." ".$city." ".$state;
$addPass='"'.$address.'"';
$Main_address_bill.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
              <div class='card'>
                <div class='card-body'>
					<h4 class='card-title'><a href='javascript:void(0)' onclick='saveBillToAddress(".$id.",".$addPass.")'> <input type='radio' id='radAddress' class='radAddress' name='radAddress' value=".$id." ".$is_checked." ></a> <i class=".$address_icon."></i> ".$address_type_value."</h4>
					<p>".$address."</p>
                </div>
              </div>
            </div>";
			
	$i=$i+1;
}


$qry6 = "SELECT id,address_line1,address_line2,location,pincode,city,state FROM tw_company_address WHERE id='".$supplier_addressID."'";
$retVal6 = $sign->FunctionJSON($qry6);
$decodedJSON = json_decode($retVal6);
$sid = $decodedJSON->response[0]->id;
$address_line1 = $decodedJSON->response[1]->address_line1;
$address_line2 = $decodedJSON->response[2]->address_line2;
$location = $decodedJSON->response[3]->location;
$pincode = $decodedJSON->response[4]->pincode;
$city = $decodedJSON->response[5]->city;
$state = $decodedJSON->response[6]->state;
$supplieraddress=$address_line1.",<br>".$address_line2.",<br>".$location.",<br>".$pincode." ".$city." ".$state;

$qry7 = "SELECT id,address_line1,address_line2,location,pincode,city,state FROM tw_company_address WHERE id='".$bill_to_addressID."'";
$retVal7 = $sign->FunctionJSON($qry7);
$decodedJSON = json_decode($retVal7);
$bid = $decodedJSON->response[0]->id;
$address_line1 = $decodedJSON->response[1]->address_line1;
$address_line2 = $decodedJSON->response[2]->address_line2;
$location = $decodedJSON->response[3]->location;
$pincode = $decodedJSON->response[4]->pincode;
$city = $decodedJSON->response[5]->city;
$state = $decodedJSON->response[6]->state;
$bill_toaddress=$address_line1.",<br>".$address_line2.",<br>".$location.",<br>".$pincode." ".$city." ".$state;

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | PO</title>
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
  <link rel="stylesheet" href="../assets/css/custom/style.css">
</head>

<body>
  <!--=========================== MODAL1 START ==============================-->
  <div class="modal" data-keyboard="false" data-backdrop="static" id="ModalAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pick up address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" id="Main">
          
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" onclick="">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================-->
  
  
	 <!--=========================== MODAL2 START ==============================-->
  <div class="modal" data-keyboard="false" data-backdrop="static" id="ModalBillAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bill to address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row">
           <?php echo $Main_address_bill; ?>
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" onclick="SubmitADD()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================--> 



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
                  <h4 class="card-title">PO</h4>
                 
                  <div class="forms-sample">
				  <div class="form-group">
						<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								  <label for="txtProductType">Supplier Name<code>*</code></label>
									<input type="text" readonly value="<?php echo $retValc; ?>" id="txtSupplier" placeholder="Supplier Name" class="form-control" />
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3">
									<label for="txtProductName">PO Date<code>*</code></label>
									 <input type="date" class="form-control" id="txtPODate" maxlength="100" value='<?php if(!empty($cur_date)){echo date("Y-m-d",strtotime($po_date));}?>' placeholder="PO Date" />
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3">
									<label for="txtDeliverDate">Delivery Date<code>*</code></label>
									<input type="date" class="form-control" id="txtDeliverDate" maxlength="100" value="<?php if(!empty($cur_date)){echo date("Y-m-d",strtotime($delivery_date));}?>" placeholder="Delivery Date" />
								</div>
							</div>
					   
                    </div>
				
					 <div class="form-group">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								<div class="pAddress">
									<h3 class="pAddress-header">Supplier Address<a href="#" class="primary" onclick="showModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtSupplierAddress"><?php echo $supplieraddress; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								<div class="pAddress">
									<h3 class="pAddress-header">Bill to Address<a href="#" class="primary" onclick="showBillModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtBilltoAddress"><?php echo $bill_toaddress; ?></p>
									</div>
								</div>
							</div>
					</div>
					</div>
					 <div class="form-group">
						<div class="row">
								
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
									<label for="txtDeliverTerms">Delivery Terms</label>
									<input type="text" value="<?php echo $delivery_terms; ?>" id="txtDeliverTerms" placeholder="Delivery Terms" class="form-control" />
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
									<label for="txtInstruction">Disp. Instruction</label>
									<input type="text" id="txtInstruction" value="<?php echo $disp_instruction; ?>" placeholder="Disp. Instruction" class="form-control" />
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
									<label for="txtPaymentTerms">Payment Terms(Days)</label>
									<input type="text" id="txtPaymentTerms" value="<?php echo $payment_terms; ?>" placeholder="Payment Terms(Days)" class="form-control" />
								</div>	
							</div>
					   
                    </div>
					<div class="form-group">
						<label for="txtSpecialInstruction">Sp. Instruction</label>
						<textarea class="form-control" id="txtSpecialInstruction" maxlength="5000" placeholder="Sp. Instruction"><?php echo $sp_instruction; ?></textarea>
					</div>
					<hr>
					<div class="form-group row">
                       <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
							<label for="txtMaterialName">Material Name<code>*</code></label>
							<select id="txtMaterialName" placeholder="Material Name" class="form-control form-control-sm">
								<option value="">Select Material Name</option>
								<?php //echo $retVal2; ?>
							</select>
                       </div>
					   <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12">
							<label for="txtQuantity">Quantity(kg)<code>*</code></label>
							<input type="number" id="txtQuantity" placeholder="Quantity" class="form-control" />
                       </div>
					</div>
					<div class="form-group row">
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtHSN">HSN<code>*</code></label>
							<input type="text" readonly id="txtHSN" placeholder="HSN" class="form-control" />
                       </div>
					   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtTax">Tax(%)<code>*</code></label>
							<input type="number" readonly id="txtTax" placeholder="Tax" class="form-control" />
                       </div>
					   
					   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtRate">Rate<code>*</code></label>
							<input type="number" id="txtRate" placeholder="Rate" class="form-control" />
                       </div>
					   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtTotalAmount">Total<code>*</code></label>
							<input type="number" readonly id="txtTotalAmount" placeholder="Total Amount" class="form-control" />
                       </div>
					</div>
					
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12" style="text-align:right;">
							<i class="ti-plus"  onclick="funcaddrow();" style="cursor:pointer;"> Add</i>
						</div>
					</div>
					<div class="table-responsive">
						<table id="tableData" class="table">
						 
						</table>
					</div>
					<hr>
                    <button type="button" class="btn btn-success" onclick="addrecord();">Update Record</button>
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
var valTotalQuantity =0;
var hdnOrderID = <?php echo $po_id; ?>; 
var valrequested = <?php echo $id; ?>; 
var valRrequested = <?php echo $Rid; ?>; 
var valsupplier_id = <?php echo $supplier_id; ?>; 
var txtFinalTotalAmount = 0; 
var txtSupplierAddressID =<?php echo $sid; ?>; 
var txtBilltoAddressId = <?php echo $bid; ?>; 
var supplier_addressID = <?php echo $supplier_addressID; ?>; 

$(document).ready(function(){
	funcremoverowload();
	myFunction(valsupplier_id);
	myFunctionFetchMaterialDetails()
});
function myFunctionFetchMaterialDetails(){
	$.ajax({
			type:"POST",
			url:"apiFetchMaterialDetails.php",
			data:{},
			dataType: 'JSON',
			success:function(response){
				$("#txtMaterialName").append(response);
			 }
			
		});


}
$("#txtMaterialName").on('change keyup paste', function () {
	var selectedId = $(this).children("option:selected").val();  
	$.ajax({
	  type:"POST",
	  url:"apiGetMaterialInfo.php",
	  data:{txtMaterialName:selectedId},
	  dataType: 'JSON',
	  success:function(response){
		  console.log(response);
		  
		  $("#txtRate").val(response[0]);
		  $("#txtTax").val(response[1]);
		  $("#txtHSN").val(response[2]); 
		  $("#txtQuantity").val("");
		  $("#txtTotalAmount").val("");
	  }
  }); 
}); 
$("#txtQuantity").on('change keyup paste', function () {
	var TotalAmt = $("#txtRate").val()*$("#txtQuantity").val();
	$("#txtTotalAmount").val(TotalAmt.toFixed(2));
});  
$("#txtRate").on('change keyup paste', function () {
	var TotalAmt = $("#txtRate").val()*$("#txtQuantity").val();
	$("#txtTotalAmount").val(TotalAmt.toFixed(2));
}); 
$("#txtQuantity").blur(function()
{
	removeError(txtQuantity);
	if ($("#txtQuantity").val()!="")
	{
		if(!isNumber($("#txtQuantity").val())){
			setError(txtQuantity);
		}
		else
		{
			removeError(txtQuantity);
		}
	}
});
 function saveAdd(id,address){
	 
	$("#txtSupplierAddress").html(address);
	txtSupplierAddressID=id;
	

 
}


function myFunction(val){

	$("#Main").html("");
	$.ajax({
			type:"POST",
			url:"apiEditGetPoCompanyDetails.php",
			data:{id:val,supplier_addressID:supplier_addressID,},
		    dataType: 'JSON',
			success:function(response){
			console.log(response);
			console.log(response[0]);
		   if(response[0]==""){
		   }
		   else{
			   $("#Main").html(response[0]);
			  
		   }
			 }
			
		});


}
function showModal(){
		jQuery.noConflict();
		$("#ModalAddress").modal("show");
}

function showBillModal(){
		jQuery.noConflict();
		$("#ModalBillAddress").modal("show");
	}

function saveBillToAddress(id,address){
	$("#txtBilltoAddress").html(address);
	txtBilltoAddressId = id;
	
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
function funcaddrow(){
   if(!validateBlank($("#txtMaterialName").val())){
		setErrorOnBlur("txtMaterialName");
   }
   else if(!validateBlank($("#txtQuantity").val())){
		setErrorOnBlur("txtQuantity");
   }
	else if(!isNumber($("#txtQuantity").val())){
		setError(txtQuantity);
	} 
  else{
	   $.ajax({
          type:"POST",
          url:"apiAddTempPOInward.php",
		  dataType: 'JSON',
          data:{txtMaterialName:$("#txtMaterialName").val(),txtQuantity:$("#txtQuantity").val(),txtTax:$("#txtTax").val(),txtRate:$("#txtRate").val(),txtHSN:$("#txtHSN").val(),txtTotalAmount:$("#txtTotalAmount").val()},
          success:function(response){
			  console.log(response);
			$("#tableData").html(response[0]);
			 txtFinalTotalAmount=response[1];
			 valTotalQuantity=response[2];
			 if(response[3]=="exist"){
				 showAlert("Warning","Product already exist","warning");
			 }
			$("#txtMaterialName").val("");
			$("#txtQuantity").val("");
			$("#txtHSN").val("");
			$("#txtRate").val("");
			$("#txtTax").val("");
			$("#txtTotalAmount").val(""); 
		
          }
      }); 
  }
}

var valrequesttype = "<?php echo $requesttype;?>";

function addrecord(){
	  if(!validateBlank($("#txtSupplier").val())){
		setErrorOnBlur("txtSupplier");
	  }
	  else if(!validateBlank($("#txtPODate").val())){
		setErrorOnBlur("txtPODate");
	  } 
	  else if(!validateBlank($("#txtDeliverDate").val())){
		setErrorOnBlur("txtDeliverDate");
	  }
	  else if(valTotalQuantity==0){
		showAlert("warning","Please Add Material","warning");
	  }
	  else{
		var buttonHtml = $('#btnAddrecord').html();
		
		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
		$.ajax({
		
			type:"POST",
			url:"apiUpdateTempPOInward.php",
			data:{valTotalQuantity :valTotalQuantity,customer_id:valsupplier_id,Address_id:txtSupplierAddressID,BillAddress_id:txtBilltoAddressId,po_date:$("#txtPODate").val(),delivery_date:$("#txtDeliverDate").val(),final_total_amount:txtFinalTotalAmount,delivery_terms:$("#txtDeliverTerms").val(),disp_instruction:$("#txtInstruction").val(),payment_terms:$("#txtPaymentTerms").val(),sp_instruction:$("#txtSpecialInstruction").val(),hdnOrderID:hdnOrderID,po_id:valRrequested},
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
						showAlertRedirect("Success","Data Added Successfully","success","pgPO.php");
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgPO.php");
					}
				}
				else if($.trim(response)=="Blank"){
					showAlert("warning","Please Add Material","warning");
					$("#txtValue").focus();
				}
				else if($.trim(response)=="Exist"){
					showAlertRedirect("Warning","This Form Already Accepted/Rejected","warning","pgPO.php");
					enableButton('#btnAddrecord','Update');
				}
				else{
					showAlert("error","Something Went Wrong. Please Try After Sometime","error");
				}
				
				$('#btnAddrecord').html(buttonHtml);
			}
		}); 
	  }
}
function funcremoverowload(){
	   $.ajax({
          type:"POST",
          url:"apiTempDeletePO.php",
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

function funcremoverow(id){
	var Vid=id
	//alert(Vid);
	    $.ajax({
          type:"POST",
          url:"apiTempDeletePO.php",
          data:{requestidid:Vid},
          success:function(response){
			console.log(response);
			$('#tableData').html(response);
			var resp=response;
			var array = resp.split("</tbody>,");
			var number_array=array[1].split(",");

			var array1 = resp.split("-");
			var number_array1=array[1].split("-");

			txtFinalTotalAmount=number_array1[0];
			valTotalQuantity = number_array1[1];
			$("#txtDescription").val("");
			$("#txtQuantity").val("");
			$("#txtUOM").val("");
			$("#txtRate").val("");
           }
      });  
}

function showdata(){
	
	  $.ajax({
          type:"POST",
          url:"apiGetTempPO.php",
		  dataType: 'JSON',
          data:{hdnOrderID:hdnOrderID},
          success:function(response){
            console.log(response);
			  $("#tableData").html(response[0]);
			 txtFinalTotalAmount=response[1];
			 valTotalQuantity=response[2];
			 $("#txtMaterialName").val("");
			$("#txtQuantity").val("");
			$("#txtHSN").val("");
			$("#txtRate").val("");
			$("#txtTax").val("");
			$("#txtTotalAmount").val(""); 
		
          }
      });
  }
</script>	
</body>
</html>