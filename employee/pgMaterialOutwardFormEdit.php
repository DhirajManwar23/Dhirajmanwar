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
$outid = $_REQUEST["id"];
$po_id = $_REQUEST["po_id"];
$Status = "";
$Cusid="";
$Billid="";
$Shipid="";

$created_by=$_SESSION["employee_id"];

$company_id=$_SESSION["company_id"];

$qry = "SELECT customer_id,final_total_amout,company_address,bill_to,ship_to,vehicle_id from tw_material_outward WHERE id = ".$outid."";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$customer_id= $decodedJSON->response[0]->customer_id;
$final_total_amout = $decodedJSON->response[1]->final_total_amout;
$company_address = $decodedJSON->response[2]->company_address;
$bill_to = $decodedJSON->response[3]->bill_to;
$ship_to = $decodedJSON->response[4]->ship_to;
$vehicle_id = $decodedJSON->response[5]->vehicle_id;


$VehicleNoQry="SELECT vehicle_number FROM tw_vehicle_details_master where id='".$vehicle_id."'"; 

$vehicle_number = $sign->SelectF($VehicleNoQry,"vehicle_number");


$cusAddqry="SELECT id,concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country) as address FROM tw_company_address where id='".$company_address."'";
$cusAdd = $sign->FunctionJSON($cusAddqry);
$decodedJSON3 = json_decode($cusAdd);
$Cusid= $decodedJSON3->response[0]->id;
$Cusaddress= $decodedJSON3->response[1]->address;


$BillAddqry="SELECT id as bill_id,concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country) as bill_address FROM tw_company_address where id='".$bill_to."'";
$BillAdd = $sign->FunctionJSON($BillAddqry);
$decodedJSON4 = json_decode($BillAdd);
$Billid= $decodedJSON4->response[0]->bill_id;
$Billaddress= $decodedJSON4->response[1]->bill_address;


$ShipAddqry="SELECT id as ship_id,concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country) as ship_address FROM tw_company_address where id='".$ship_to."'";
$ShipAdd = $sign->FunctionJSON($ShipAddqry);
$decodedJSON5 = json_decode($ShipAdd);
$Shipid= $decodedJSON5->response[0]->ship_id;
$Shipaddress= $decodedJSON5->response[1]->ship_address;

$qry1 = "SELECT id,CompanyName FROM tw_company_details where ID='".$customer_id."'";
$retVal1 = $sign->FunctionJSON($qry1);
$decodedJSON2 = json_decode($retVal1);
$mid= $decodedJSON2->response[0]->id;
$CompanyName= $decodedJSON2->response[1]->CompanyName;

$qry2 = "SELECT pod.material_id,pm.product_name FROM tw_temp_po_details pod INNER JOIN tw_product_management pm ON POD.material_id=pm.id WHERE po_id='".$po_id."' ORDER by pm.id  ASC";
$retVal2 = $sign->FunctionOption($qry2,$Status,'product_name','material_id');


$transportIDqry="SELECT transporter_id FROM tw_vehicle_details_master where id='".$vehicle_id."'";
$transporter_id=$sign->SelectF($transportIDqry,"transporter_id");

$qry6 = "SELECT id,transporter_name from tw_transport_master Order by id desc";
$retVal6 = $sign->FunctionOption($qry6,$transporter_id,'transporter_name','id');

$qry11 = "SELECT id,vehicle_number FROM tw_vehicle_details_master where id='".$vehicle_id."'";
$retVal11 = $sign->FunctionOption($qry11,$vehicle_id,'vehicle_number','id');

$qry7="select id,CONCAT(address_line1,' ',address_line2,' ',location,' ',pincode,' ',city,' ',state)as address,address_type,country,google_map from tw_company_address where company_id='".$company_id."'";

$retVal7 = $sign->FunctionJSON($qry7);
$qry8="Select count(*) as cnt from tw_company_address where company_id='".$company_id."' ";
$retVal8 = $sign->Select($qry8);
	
$decodedJSON5 = json_decode($retVal7);
$count = 0;
$i = 1;
$x=$retVal8;
$Main_address_bill="";
$Main_address_ship="";
while($x>=$i){
$Cid = $decodedJSON5->response[$count]->id;
$count=$count+1;
$address = $decodedJSON5->response[$count]->address;
$count=$count+1;
$address_type = $decodedJSON5->response[$count]->address_type;
$count=$count+1;
$country = $decodedJSON5->response[$count]->country;
$count=$count+1;
$google_map = $decodedJSON5->response[$count]->google_map;
$count=$count+1;


$is_checked="";
if ($Cusid==$Cid)
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
					<h4 class='card-title'><a href='javascript:void(0)' onclick='saveBillToAddress(".$Cid.",".$addPass.")'> <input type='radio' id='radBillToAddress' class='radAddress' name='radBillToAddress' value=".$Cid." ".$is_checked." ></a> <i class=".$address_icon."></i> ".$address_type_value."</h4>
					<p>".$address."</p>
                </div>
              </div>
            </div>";
			
/* $Main_address_ship.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
              <div class='card'>
                <div class='card-body'>
					<h4 class='card-title'><a href='javascript:void(0)' onclick='saveShipToAddress(".$Cid.",".$addPass.")'> <input type='radio' id='radShiptoAddress' class='radAddress' name='radShiptoAddress' value=".$Cid." ".$is_checked." ></a> <i class=".$address_icon."></i> ".$address_type_value."</h4>
					<p>".$address."</p>
                </div>
              </div>
            </div>";
 */
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

	  <!--=========================== MODAL1 START ==============================-->
  <div class="modal" data-keyboard="false" data-backdrop="static" id="ModalAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Supplier address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" id="">
             <?php echo $Main_address_bill; ?>
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd" data-dismiss="modal" onclick="SubmitADD()">Save changes</button>
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
                  <h4 class="card-title">Material Outward</h4>
                 
                  <div class="forms-sample">
                    <div class="form-group">
                      <label for="txtSupplier">Customer Name <code>*</code></label>
						 <input type="hidden" id="txtCustomer" class="form-control form-control-sm"
							value="<?php echo $mid; ?>"  />
							<input id="txtCustomerName" Readonly class="form-control form-control-sm"
							value="<?php echo $CompanyName; ?>"  />
						
					</div>
					
					<div class="form-group">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Supplier Address<a href="#" class="primary" onclick="showModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtSupplierAddress"><?php echo $Cusaddress; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Bill to Address<a href="#" class="primary" onclick="showModalBill();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtBilltoAddress"><?php echo $Billaddress; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Ship to Address<a href="#" class="primary" onclick="showshipModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtSendInvoiceAddress"><?php echo $Shipaddress; ?></p>
									</div>
								</div>
							</div>
					</div>
					</div>
				
					<div class="form-group">
                       <div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
									<label for="txtTransporter_Name">Transporter Name<code>*</code></label>
									<select name="txtTransporter_Name" id="txtTransporter_Name" class="form-control" onchange="vehicalFun()" >
									<option value="">Select Transporter</option>
									
									 <?php  echo $retVal6;?>
									</select>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
										<label for="txtVehicle_Name">Vehicle Name<code>*</code></label>
										<select name="txtVehicle_Name" id="txtVehicle_Name" class="form-control" >
										<option value="">Vehicle Name</option>
										<?php echo $retVal11; ?>
										</select>
								</div>
								
							</div>
					</div>
                  
					<hr>
					
					
                   <div class="form-group row">
                       <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10">
							<label for="txtInwardQuantity">Material Name<code>*</code></label>
							<select id="txtMaterialName" placeholder="Material Name" class="form-control form-control-sm">
								<option value="">Select Material Name</option>
								<?php //---Karuna
						$qry7="Select po.id,po.material_id,pm.product_name as Description,po.quantity as Quantity,po.rate as Rate,po.hsn as hsn,po.tax as tax,um.name as UOM,po.total as Amount from tw_temp_po_details po INNER JOIN tw_product_management pm ON pm.id=po.material_id INNER JOIN tw_unit_of_measurement um ON pm.uom=um.id where po.po_id='".$po_id."'";
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
							$count=$count+1;
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
							<label for="txtInwardQuantity">Quantity<code>*</code></label>
							<input type="number" id="txtQuantity" placeholder="Quantity" class="form-control" />
                       </div>
					</div>
					<div class="form-group row">
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtInwardQuantity">HSN<code>*</code></label>
							<input type="text" readonly id="txtHSN" placeholder="HSN" class="form-control" />
                       </div>
					   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtInwardQuantity">Tax<code>*</code></label>
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
                    <button type="button" id="btnAddrecord"  class="btn btn-success" onclick="addrecord()">Update Record</button>
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
var hdnOrderID='<?php echo $outid; ?>';
var valpo_id='<?php echo $po_id; ?>';
var valShipid='<?php echo $Shipid; ?>';
var valBillid='<?php echo $Billid; ?>';
var valTotalQuantity = 0.00 ;
var txtSupplierGeoLocation = "" ;
var txtSupplierAddressID = "";
var txtSendinvoiceAddressID = "";
var txtBilltoAddressID = "";
var txtFinalTotalAmount = 0;

$(document).ready(function(){
	funcremoverowload();
	myFunction();
	vehicalFun();
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
	$("#txtTotalAmount").val(TotalAmt);
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
function vehicalFun(){
	var val = $('#txtTransporter_Name').val();
	$.ajax({
			type:"POST",
			url:"apiGetVehical.php",
			data:{id:val},
			dataType: 'JSON',
			success:function(response){
			console.log(response);
			
			$("#txtVehicle_Name").html(response[0]);
		  
		
			}
		});
}	
	
function myFunction(){
	var id=$("#txtCustomer").val();
	
	$.ajax({
			type:"POST",
			url:"apiEditGetCompanyDetails.php",
			data:{id:id,valBillid:valBillid},
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
			url:"apiEditGetCompanyDetailsShipto.php",
			data:{id:id,valShipid:valShipid},
			dataType: 'JSON',
			success:function(response){
			//const myArray = response.split("-");
			console.log(response[0]);
			console.log(response[1]);	
			//console.log(response[2]);	
			//console.log(response[3]);
			
			
	       /* $("#txtPanNumber").val(response[0])
	       $("#txtGSTNumber").val(response[1])
		   $("#txtEmail").val(response[2]) */
	       $("#MainShip").html(response[0]);
	       txtSupplierGeoLocation=response[1];
		  
		
			}
		});


}



function funcaddrow(){
   if(!validateBlank($("#txtMaterialName").val())){
		setErrorOnBlur("txtMaterialName");
   }
   else if(!validateBlank($("#txtQuantity").val())){
		setErrorOnBlur("txtQuantity");
   }
  else{
	  $.ajax({
          type:"POST",
          url:"apiAddTempMaterialOutward.php",
		  dataType: 'JSON',
          data:{txtMaterialName:$("#txtMaterialName").val(),txtQuantity:$("#txtQuantity").val(),txtTax:$("#txtTax").val(),txtRate:$("#txtRate").val(),txtHSN:$("#txtHSN").val(),txtTotalAmount:$("#txtTotalAmount").val()},
          success:function(response){
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
function funcremoverowload(){
	   $.ajax({
          type:"POST",
          url:"apiDeleteTempMaterialOutward.php",
          data:{Customer_ID:$("#hdnCustomerID").val(),requestidid:''},
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
			$("#txtMaterialName").val("");
			$("#txtQuantity").val("");
			$("#txtUOM").val("");
			$("#txtRate").val("");
			showdata();
          }
      }); 
}
function funcremoverow(id){
	   $.ajax({
          type:"POST",
          url:"apiDeleteTempMaterialOutward.php",
          data:{requestidid:id},
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
          }
      }); 
}
function showdata(){
	
	  $.ajax({
          type:"POST",
          url:"apiGetTempTableDetails.php",
          data:{hdnOrderID:hdnOrderID},
          success:function(response){
            console.log(response);
			$("#tableData").html(response);
			var resp=response;
			var array = resp.split("</tbody>,");
			var number_array=array[1].split(",");

			var array1 = resp.split("-");
			var number_array1=array[1].split("-");

			txtFinalTotalAmount=number_array1[0];
			valTotalQuantity = number_array1[1];
		   
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
var txtBilltoAddressId='<?php echo $Billid; ?>'
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
var txtSupplierAddressID='<?php echo $Cusid; ?>'
function saveBillToAddress(id,address){
	
	$("#txtSupplierAddress").html(address);
	txtSupplierAddressID=id;
	$("#txtSupplierAddress").attr('readonly', true);
	
}
var txtSendinvoiceAddressID='<?php echo $Shipid; ?>' ; 
function saveShipToAddress(id,address){
	$("#txtSendInvoiceAddress").html(address);
	txtSendinvoiceAddressID=id;
	$("#txtSendInvoiceAddress").attr('readonly', true);
}
var valrequesttype = "<?php echo $requesttype;?>";

function addrecord(){
	  if(!validateBlank($("#txtTransporter_Name").val())){
		setErrorOnBlur("txtTransporter_Name");
	  }
	  else if(!validateBlank($("#txtVehicle_Name").val())){
		setErrorOnBlur("txtVehicle_Name");
	  }
	  else{
		var buttonHtml = $('#btnAddrecord').html();
		
		$('#btnAddrecord').attr("disabled","true");
		$('#btnAddrecord').removeClass('btn-success');
		$('#btnAddrecord').addClass('btn-secondary');//secondary;
		$('#btnAddrecord').html('<i class="ti-timer"></i> Processing...');
		
		$.ajax({
			type:"POST",
			url:"apiEditMaterialOutward.php",
			data:{
			
			valTotalQuantity:valTotalQuantity,po_id:valpo_id,customer_id:$("#txtCustomer").val(),material_name:$("#txtMaterialName").val(),final_total_amout:txtFinalTotalAmount,hdnOrderID:hdnOrderID,company_address:txtSupplierAddressID,billto:txtBilltoAddressId,shiptto:txtSendinvoiceAddressID,supplier_geo_location:txtSupplierGeoLocation,txtVehicle_Name:$("#txtVehicle_Name").val()},
			success:function(response){
				console.log(response);
				$('#btnAddrecord').removeAttr('disabled');
				$('#btnAddrecord').removeClass('btn-secondary');
				$('#btnAddrecord').addClass('btn-success');
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
				}
				else if($.trim(response)=="Exist"){
					showAlertRedirect("Warning","This Form Already Accepted/Rejected","warning","pgMaterialOutward.php?type=In%20Process&po_id="+valpo_id);
					 //window.location = "pgEprPo.php";
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
</script>	
</body>
</html>