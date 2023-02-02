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
$po_id = $_REQUEST["po_id"];
$_SESSION["requesttype"] = $requesttype;
$id = $_REQUEST["id"];
$_SESSION["requestid"] = $id; 

$created_by=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
	
$customer_id = "";
$material_name = ""; 
$outward_quantity = "";
$rate = "";
$reason = "";
$total_amout = "";
$Status = "";
$option = "";

$settingValueRecycler= $commonfunction->getSettingValue("Recycler");

$qryPO = "Select supplier_id,supplier_address,bill_to_address,buyer_id from tw_temp_po_info where id='".$po_id."'";
$retValPO = $sign->FunctionJSON($qryPO);
$decodedJSON = json_decode($retValPO);
$supplier_id= $decodedJSON->response[0]->supplier_id;
$supplier_address= $decodedJSON->response[1]->supplier_address;
$bill_to_address= $decodedJSON->response[2]->bill_to_address;
$buyer_id= $decodedJSON->response[3]->buyer_id;

$qry1 = "SELECT CompanyName FROM tw_company_details where ID='".$buyer_id."' ORDER by ID  ASC";
$retValc = $sign->SelectF($qry1,'CompanyName');
	
$DefaultAddqry = "SELECT id,address_line1,address_line2,location,pincode,city,state FROM tw_company_address WHERE id='".$supplier_address."' and public_visible='true'";
$DefaultAdd = $sign->FunctionJSON($DefaultAddqry);
$decodedJSON6 = json_decode($DefaultAdd);
$defaulfbillingid = $decodedJSON6->response[0]->id;
$address_line1 = $decodedJSON6->response[1]->address_line1;
$address_line2 = $decodedJSON6->response[2]->address_line2;
$location = $decodedJSON6->response[3]->location;
$pincode = $decodedJSON6->response[4]->pincode;
$city = $decodedJSON6->response[5]->city;
$state = $decodedJSON6->response[6]->state;
$defaulfsupplieraddress=$address_line1.",<br>".$address_line2.",<br>".$location.",<br>".$pincode." ".$city." ".$state;

$Addqry = "SELECT id,address_line1,address_line2,location,pincode,city,state FROM tw_company_address WHERE id='".$bill_to_address."' and public_visible='true'";
$DefaultShipAdd = $sign->FunctionJSON($Addqry);
$decodedJSON7 = json_decode($DefaultShipAdd);
$defaultshipid = $decodedJSON7->response[0]->id;
$shipaddress_line1 = $decodedJSON7->response[1]->address_line1;
$shipaddress_line2 = $decodedJSON7->response[2]->address_line2;
$shiplocation = $decodedJSON7->response[3]->location;
$shippincode = $decodedJSON7->response[4]->pincode;
$shipcity = $decodedJSON7->response[5]->city;
$shipstate = $decodedJSON7->response[6]->state;
$defaulfaddress=$shipaddress_line1.",<br>".$shipaddress_line2.",<br>".$shiplocation.",<br>".$shippincode." ".$shipcity." ".$shipstate;	
		
$qry6 = "SELECT id,transporter_name from tw_transport_master where company_id='".$company_id."' Order by id desc";
$retVal6 = $sign->FunctionOption($qry6,'','transporter_name','id');

$qry="select id,CONCAT(address_line1,' ',address_line2,' ',location,' ',pincode,' ',city,' ',state)as address,address_type,country,google_map,default_address From tw_company_address  
where company_id='".$supplier_id."' AND public_visible='true' ";	

$retVal = $sign->FunctionJSON($qry);
$qry1="Select count(*) as cnt from tw_company_address  where company_id='".$supplier_id."' AND public_visible='true'";
$retVal1 = $sign->Select($qry1);
	
$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$Main_address_bill="";
$Main_address_ship="";
while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$address = $decodedJSON2->response[$count]->address;
$count=$count+1;
$address_type = $decodedJSON2->response[$count]->address_type;
$count=$count+1;
$country = $decodedJSON2->response[$count]->country;
$count=$count+1;
$google_map = $decodedJSON2->response[$count]->google_map;
$count=$count+1;
$default_address = $decodedJSON2->response[$count]->default_address;
$count=$count+1;	
$is_checked="";
// if ($supplier_address==$id)
{
	$is_checked="checked='checked'";
}

$qry4="SELECT address_icon,address_type_value FROM tw_address_type_master where id='".$address_type."'";
$address_icon= $sign->SelectF($qry4,"address_icon");

$qry5="SELECT address_type_value FROM tw_address_type_master where id='".$address_type."'";
$address_type_value= $sign->SelectF($qry5,"address_type_value");

$addPass='"'.$address.'"';
$Main_address_bill.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
              <div class='card'>
                <div class='card-body'>
					<h4 class='card-title'><a href='javascript:void(0)' onclick='saveBillToAddress(".$id.",".$addPass.")'> <input type='radio' id='radmainAddress' class='radAddress' name='radmainAddress' value=".$id." ".$is_checked."></a> <i class=".$address_icon."></i> ".$address_type_value."</h4>
					<p>".$address."</p>
                </div>
              </div>
            </div>";
			


$i=$i+1;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Material Outward</title>
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
      <div class="row" id="">
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
      <div class="row" id="Main">
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" onclick="SubmitADDBill()">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================--> 
  
  <!--=========================== MODAL3 START ==============================-->
  <div class="modal" data-keyboard="false" data-backdrop="static" id="ModalShipAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ship to address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" id="MainShip">
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" >Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================--> 
  <!--=========================== MODAL4 START ==============================-->
  <div class="modal" data-keyboard="false" data-backdrop="static" id="ModalTnC" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Terms and Conditions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div>
		<p> TNC</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================--> 
  
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Material Outward</h4>
                 
                  <div class="forms-sample">
                    <div class="form-group">
                      <label for="txtCustomer">Customer Name <code>*</code></label>
						<input type="text" id="txtCustomer" readonly value="<?php echo $retValc; ?>" placeholder="Customer Name" class="form-control" />
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Supplier Address<a href="#" class="primary" onclick="showModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtSupplierAddress"><?php echo $defaulfsupplieraddress; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Bill to Address<a href="#" class="primary" onclick="showModalBill();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtBilltoAddress"><?php echo $defaulfaddress; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Ship to Address<a href="#" class="primary" onclick="showshipModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtSendInvoiceAddress"><?php echo $defaulfaddress; ?></p>
									</div>
								</div>
							</div>
					</div>
					</div>
					
                  
					<div class="form-group">
						<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
									<label for="txtTransporter_Name">Transporter Name<code>*</code></label>
									<select name="txtTransporter_Name" id="txtTransporter_Name" placeholder="Transporter Name" class="form-control" onchange="vehicalFun()" >
									<option value="">Select Transporter</option>
									
									 <?php  echo $retVal6;?>
									</select>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
										<label for="txtVehicle_Name">Vehicle Name<code>*</code></label>
										<select name="txtVehicle_Name" id="txtVehicle_Name" placeholder="Vehicle Name" class="form-control" >
										<option value="">Vehicle Name</option>
										
										</select>
								</div>
								
							</div>
					   
                    </div>
					<hr>
                   <div class="form-group row">
                       <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
							<label for="txtMaterialName">Material Name<code>*</code></label>
							<select id="txtMaterialName" placeholder="Material Name" placeholder="Material Name" class="form-control form-control-sm">
								<option value="">Select Material Name</option>
									<?php //---Karuna
									$qry7="Select po.id,po.material_id,pm.product_name as Description,po.quantity as Quantity,po.rate as Rate,po.hsn as hsn,po.tax as tax,um.name as UOM,po.total as Amount from tw_temp_po_details po INNER JOIN tw_product_management pm ON pm.id=po.material_id INNER JOIN tw_unit_of_measurement um ON pm.uom=um.id where po.po_id='".$po_id."' order by po.id Asc";
									$retVal7 = $sign->FunctionJSON($qry7);
									$decodedJSON7 = json_decode($retVal7);

									$qry8="Select count(*) as cnt from tw_temp_po_details po INNER JOIN tw_product_management pm ON pm.id=po.material_id INNER JOIN tw_unit_of_measurement um ON pm.uom=um.id where po.po_id='".$po_id."'";
									$retVal8 = $sign->Select($qry8);
									$totalbeforetax = 0;
									$count = 0;
									$i = 1;
									$x=$retVal8;
									$it=1;
										while($x>=$i){
										$id = $decodedJSON7->response[$count]->id;
										$count=$count+1;
										$material_id = $decodedJSON7->response[$count]->material_id;
										$count=$count+1;/* 
										$product_id = $decodedJSON7->response[$count]->product_id;
										$count=$count+1; */
										$Description = $decodedJSON7->response[$count]->Description;
										$count=$count+1;
										$Quantity = $decodedJSON7->response[$count]->Quantity;
										$count=$count+1;
										$Rate = $decodedJSON7->response[$count]->Rate;
										$count=$count+1;
										$HSN = $decodedJSON7->response[$count]->hsn;
										$count=$count+1;
										$tax = $decodedJSON7->response[$count]->tax;
										$count=$count+1;
										$UOM = $decodedJSON7->response[$count]->UOM;
										$count=$count+1;
										$Amount = $decodedJSON7->response[$count]->Amount;
										$count=$count+1;
										
										
										$totalbeforetax=$totalbeforetax+$Amount;
										
						//----
			 ?>
				<option value="<?php echo $id; ?>"><?php echo $Description." [&#8377 ".$Rate."]"; ?></option>
			 <?php 
				$it=$i+1;
				$i=$i+1;
				}  
?>
							</select>
                       </div>
					   <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12">
							<label for="txtQuantity">Quantity<code>*</code></label>
							<input type="number" id="txtQuantity" placeholder="Quantity" class="form-control" />
                       </div>
					</div>
					<div class="form-group row">
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtHSN">HSN<code>*</code></label>
							<input type="text" readonly id="txtHSN" placeholder="HSN" class="form-control" />
                       </div>
					   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtInwardQuantity">Tax(%)<code>*</code></label>
							<input type="number" readonly id="txtTax" placeholder="Tax" class="form-control" />
                       </div>
					   
					   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtInwardQuantity">Rate<code>*</code></label>
							<input type="number" readonly id="txtRate" placeholder="Rate" class="form-control" />
                       </div>
					   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtInwardQuantity">Total<code>*</code></label>
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
					<br>
					<hr>
					<div class="form-group">
						<div class="row">
					   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<div class="my-2 d-flex justify-content-between align-items-center">
							  <div class="form-check">
								<label class="form-check-label text-muted">
								  <input type="checkbox" id="checkbox" class="form-check-input">
								<i class="input-helper"></i><a onclick="showTnC()" class="text-primary">I hereby agree to all Terms &amp; Conditions</a> <code>*</code></label>
							  </div>
							</div>
                       </div>
                       </div>
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
var valgeolocation = "<?php echo $google_map; ?>" ;
var valpo_id = "<?php echo $po_id; ?>" ;
var valTotalQuantity = 0 ;
var txtSupplierGeoLocation = "" ;
var txtSupplierAddressID = "<?php echo $defaulfbillingid ?>" ;
var txtSendinvoiceAddressID = "<?php echo $defaultshipid ?>";
var txtBilltoAddressId = "<?php echo $defaultshipid ?>";
var bill_to_address = "<?php echo $bill_to_address ?>";
var valbuyer_id = "<?php echo $buyer_id ?>";
var txtFinalTotalAmount = 0.00;
$('input[type="checkbox"]').click(function(){
	if($(this).prop("checked") == true){
		valcheck = "checked";
		enableButton('#btnAddrecord','Add Record');
	}
	else if($(this).prop("checked") == false){
		valcheck = "";
		disableButton('#btnAddrecord','Add Record');
	}
});
function vehicalFun(){
	var val = $('#txtTransporter_Name').val();
	
	$.ajax({
			type:"POST",
			url:"apiGetVehical.php",
			data:{id:val},
			dataType: 'JSON',
			success:function(response){
			//console.log(response);
			console.log(response[0]);
			
			
		
	       $("#txtVehicle_Name").html(response[0]);
		  
		
			}
		});
	
}
function MaterialFun(){

	$.ajax({
			type:"POST",
			url:"apiGetMaterialList.php",
			data:{po_id:valpo_id},
			dataType: 'JSON',
			success:function(response){

			console.log(response);
			console.log(response[0]);
			
			
		
	       $("#txtMaterialName").html(response[0]);
		  
		
			}
		});
	
}

$(document).ready(function(){
	funcremoverow('');
	myFunction(valbuyer_id);
	vehicalFun();
	disableButton('#btnAddrecord','Add Record');
	//MaterialFun();
});
 $("#txtMaterialName").on('change keyup paste', function () {
	var selectedId = $(this).children("option:selected").val();  
	$.ajax({
	  type:"POST",
	  url:"apiGetMaterialInfomo.php",
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
	  if(!validateBlank($("#txtTransporter_Name").val())){
		setErrorOnBlur("txtTransporter_Name");
	  }
	  else if(!validateBlank($("#txtVehicle_Name").val())){
		setErrorOnBlur("txtVehicle_Name");
	  }
	  else if(valcheck == ""){
		showAlert("warning","Check terms and conditions","warning");
	  }
	  else{
		var buttonHtml = $('#btnAddrecord').html();
		
			disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
			$.ajax({
			type:"POST",
			url:"apiAddMaterialOutward.php",
			data:{valTotalQuantity:valTotalQuantity,po_id:valpo_id,customer_id:valbuyer_id,company_address:txtSupplierAddressID,billto:txtBilltoAddressId,shiptto:txtSendinvoiceAddressID,supplier_geo_location:txtSupplierGeoLocation,final_total_amout:txtFinalTotalAmount,vehicle_id:$("#txtVehicle_Name").val()},
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
						showAlertRedirect("Success","Data Added Successfully","success","pgMaterialOutward.php?type=In%20Process&po_id="+valpo_id);
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialOutward.php?type=In%20Process&po_id="+valpo_id);
					}
				}
				else if($.trim(response)=="Blank"){
					showAlert("warning","Please Add Material","warning");
					$("#txtValue").focus();
				}else{
					showAlert("error","Something Went Wrong. Please Try After Sometime","error");
				}
				
				$('#btnAddrecord').html(buttonHtml);
			}
		}); 
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
          url:"apiAddTempMaterialOutward.php",
		  dataType: 'JSON',
          data:{txtMaterialName:$("#txtMaterialName").val(),txtQuantity:$("#txtQuantity").val(),txtRate:$("#txtRate").val()},
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
function showModal(){
		jQuery.noConflict();
		$("#ModalAddress").modal("show");
		txtSupplierGeoLocation=valgeolocation;
}
function showBillModal(){
		jQuery.noConflict();
		$("#ModalBillAddress").modal("show");
}
function showshipModal(){
		jQuery.noConflict();
		$("#ModalShipAddress").modal("show");
}
function showTnC(){
		jQuery.noConflict();
		$("#ModalTnC").modal("show");
}
function funcremoverow(id){
	   $.ajax({
          type:"POST",
          url:"apiDeleteTaxInvMaterial.php",
          data:{requestidid:id},
          success:function(response){
			$('#tableData').html(response);
			var resp=response;
			var array = resp.split("</tbody>,");
			var number_array=array[1].split(",");

			var array1 = resp.split("-");
			var number_array1=array[1].split("-");

			txtFinalTotalAmount=number_array1[0];
			valTotalQuantity = number_array1[1];

			$("#txtMaterialName").val("");
			$("#txtQuantity").val("");
			$("#txtHSN").val("");
			$("#txtRate").val("");
			$("#txtTax").val("");
			$("#txtTotalAmount").val(""); 
          }
      }); 
}




function myFunction(id){
	$.ajax({
			type:"POST",
			url:"apiGetCompanyDetails.php",
			data:{id:id,BIll_id:bill_to_address},
			dataType: 'JSON',
			success:function(response){	
	        $("#Main").html(response[0]);
	        txtSupplierGeoLocation=response[1];
		    myFunctionShipto(id);
		
			}
		});


}
function myFunctionShipto(id){
	$.ajax({
			type:"POST",
			url:"apiGetCompanyDetailsShipto.php",
			data:{id:id,BIll_id:bill_to_address},
			dataType: 'JSON',
			success:function(response){
	       $("#MainShip").html(response[0]);
	       txtSupplierGeoLocation=response[1];
		  
		
			}
		});


}
function showModalBill(){
	jQuery.noConflict();
	$("#ModalBillAddress").modal("show");
}
function SubmitADD(){
	
	$("#txtSupplierAddress").attr('readonly', true);
}
function SubmitADDBill(){
	
	$("#txtSupplierAddress").attr('readonly', true);
}

 function saveAdd(id,address){
	$("#txtBilltoAddress").html(address);
	txtBilltoAddressId=id;
	var selectedValue = document.getElementById('radAddress').value;
} 
 function saveAddShipto(id,address){
	$("#txtSendInvoiceAddress").html(address);
	txtSendinvoiceAddressID=id;
	var selectedValue = document.getElementById('radAddress').value;
} 
function saveBillToAddress(id,address){
	$("#txtSupplierAddress").html(address);
	txtSupplierAddressID=id;
	$("#txtSupplierAddress").attr('readonly', true);
	
}
function saveShipToAddress(id,address){
	$("#txtSendInvoiceAddress").html(address);
	txtSendinvoiceAddressID=id;
	$("#txtSendInvoiceAddress").attr('readonly', true);
}

</script>	
</body>
</html>