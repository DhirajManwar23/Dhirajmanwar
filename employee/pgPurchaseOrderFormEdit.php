<?php
session_start();
if(!isset($_SESSION["employeeusername"])){
	header("Location:pgLogin.php");
}
include_once "function.php";	
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValuePANCard= $commonfunction->getSettingValue("PANCard");
$settingValueGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");
$employee_id=$_SESSION['employee_id'];
$company_id=$_SESSION['company_id'];
$request_type=$_REQUEST['type'];
$requestid_id=$_REQUEST['id'];
$requestid_sid=$_REQUEST['supid'];
$Start_date="";
$End_date="";
$EditPan="";
$EditGST="";
$supplieraddress="";
$supplier_address_id="";
$Biladdress="";
$bill_to_address_id ="";
$Shipaddress="";
$send_invoice_to_address_id="";
 
$PodetailsQry="SELECT po_number,date_of_po,payment_term,shipping_term,invoicing_method,supplier_id,supplier_address_id,bill_to_address_id,send_invoice_to_address_id,total_quantity,status from  tw_po_info where id='".$requestid_id."'";
$Podetail= $sign->FunctionJSON($PodetailsQry);
$decodedJSONEDIT = json_decode($Podetail);
$po_number = $decodedJSONEDIT->response[0]->po_number;
$date_of_po = $decodedJSONEDIT->response[1]->date_of_po;
$payment_term = $decodedJSONEDIT->response[2]->payment_term;
$shipping_term = $decodedJSONEDIT->response[3]->shipping_term;
$invoicing_method = $decodedJSONEDIT->response[4]->invoicing_method;
$supplier_id = $decodedJSONEDIT->response[5]->supplier_id;
$supplier_address_id = $decodedJSONEDIT->response[6]->supplier_address_id;
$bill_to_address_id = $decodedJSONEDIT->response[7]->bill_to_address_id;
$send_invoice_to_address_id = $decodedJSONEDIT->response[8]->send_invoice_to_address_id;
$total_quantity = $decodedJSONEDIT->response[9]->total_quantity;
$status = $decodedJSONEDIT->response[10]->status;

$supplierNameqry="select CompanyName FROM `tw_company_details` where ID='".$requestid_sid."'";
$supplierName= $sign->SelectF($supplierNameqry,"CompanyName");

$supplierAddQry="select concat(address_line1,' ',address_line2,' ',location) as supplieraddress,country from  tw_company_address where id='".$supplier_address_id."'";
$supplierAdd= $sign->FunctionJSON($supplierAddQry);
$decodedJSONAdd = json_decode($supplierAdd);
$supplieraddress = $decodedJSONAdd->response[0]->supplieraddress;
$country = $decodedJSONAdd->response[1]->country;

$supplierEmailqry="select value FROM `tw_company_contact` where company_id='".$requestid_sid."' AND contact_field='".$settingValuePemail."'";
$supplierEmail= $sign->SelectF($supplierEmailqry,"value");

 $supplier_noqry="select supplier_no FROM `tw_supplier_info` where supplier_id='".$requestid_sid."'";
$supplier_no= $sign->SelectF($supplier_noqry,"supplier_no");


$BiladdressQry="select concat(address_line1,' ',address_line2,' ',location) as Billaddress,country from  tw_company_address where id='".$bill_to_address_id."'";
$BiladdressADD= $sign->FunctionJSON($BiladdressQry);
$decodedJSONBillAdd = json_decode($BiladdressADD);
$Biladdress = $decodedJSONBillAdd->response[0]->Billaddress;
$Billcountry = $decodedJSONBillAdd->response[1]->country;

$ShipaddressQry="select concat(address_line1,' ',address_line2,' ',location) as Shipaddress,country from  tw_company_address where id='".$send_invoice_to_address_id."'";
$ShipaddressADD= $sign->FunctionJSON($ShipaddressQry);
$decodedJSONShipAdd = json_decode($ShipaddressADD);
$Shipaddress = $decodedJSONShipAdd->response[0]->Shipaddress;
$Shicountry = $decodedJSONShipAdd->response[1]->country;

$DocPanQry="SELECT count(document_number) as cnt,IFNULL(document_number,'NA') as document_number from tw_company_document where company_id='".$requestid_sid."' AND document_type='".$settingValuePANCard."'  ORDER by document_type ASC ";

$EditPan = $sign->SelectF($DocPanQry,"document_number");

$DocGstQry="SELECT count(document_number) as cnt,IFNULL(document_number,'NA') as document_number from tw_company_document where company_id='".$requestid_sid."' AND document_type='".$settingValueGSTDocuments."'  ORDER by document_type ASC ";

$EditGST = $sign->SelectF($DocGstQry,"document_number");

$qry2="SELECT ID,CompanyName FROM tw_company_details where id!='".$company_id."' ORDER by CompanyName ASC";
$retVal2 = $sign->FunctionOption($qry2,"",'CompanyName',"ID");

$qry3="SELECT er.employee_name,ec.value FROM `tw_employee_registration` er INNER join tw_employee_contact ec on er.id=ec.employee_id WHERE employee_id='".$employee_id."' and ec.contact_field='".$settingValuePemail."'";
$retVal3 = $sign->FunctionJSON($qry3);	
$decodedJSON = json_decode($retVal3);
$employee_name = $decodedJSON->response[0]->employee_name;
$EMP_EMAIL = $decodedJSON->response[1]->value;

$CountryQry="SELECT currency FROM tw_country_master where country_name='".$country."'";
$Currency= $sign->SelectF($CountryQry,"currency");

$CategoryQry="SELECT id,epr_category_name FROM  tw_epr_category_master ORDER by priority ASC";
$Category = $sign->FunctionOption($CategoryQry,"",'epr_category_name',"id");

$StateQry="SELECT id,state_name FROM tw_state_master ";
$State = $sign->FunctionOption($StateQry,"",'state_name',"id");

$measurementQry="SELECT id,name FROM `tw_unit_of_measurement` ORDER by priority ASC";
$measurement = $sign->FunctionOption($measurementQry,"",'name',"id");

$qry="select CP.id,CONCAT(CP.address_line1,' ',CP.address_line2,' ',CP.location,' ',CP.pincode,' ',CP.city,' ',CP.state)as address,CP.address_type,CP.country,CP.default_address From tw_company_address CP
where CP.company_id='".$company_id."'  AND CP.public_visible='true'";

$retVal = $sign->FunctionJSON($qry);
$qry1="Select count(*) as cnt from tw_company_address  where company_id='".$company_id."' AND public_visible='true'";
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
$default_address_ship = $decodedJSON2->response[$count]->default_address;
$count=$count+1;	

$is_checked_ship="";
$is_checked_bill="";


if ($bill_to_address_id==$id)
{
	$is_checked_bill="checked='checked'";
}

$qry4="SELECT address_icon,address_type_value FROM tw_address_type_master where id='".$address_type."'";
$address_icon= $sign->SelectF($qry4,"address_icon");

$qry5="SELECT address_type_value FROM tw_address_type_master where id='".$address_type."'";
$address_type_value= $sign->SelectF($qry5,"address_type_value");

$addPass='"'.$address.'"';
$Main_address_bill.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
              <div class='card'>
                <div class='card-body'>
					<h4 class='card-title'><a href='javascript:void(0)' onclick='saveBillToAddress(".$id.",".$addPass.")'> <input type='radio' id='radAddressBill' class='radAddress' name='radAddressBill' value=".$id." ".$is_checked_bill." ></a> <i class=".$address_icon."></i> ".$address_type_value."</h4>
					<p>".$address."</p>
                </div>
              </div>
            </div>";
		
if ($send_invoice_to_address_id==$id)
{
	$is_checked_ship="checked='checked'";
}		
$Main_address_ship.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
              <div class='card'>
                <div class='card-body'>
					<h4 class='card-title'><a href='javascript:void(0)' onclick='saveShipToAddress(".$id.",".$addPass.")'> <input type='radio' id='radAddressShip' class='radAddress' name='radAddressShip' value=".$id." ".$is_checked_ship."></a> <i class=".$address_icon."></i> ".$address_type_value."</h4>
					<p>".$address."</p>
                </div>
              </div>
            </div>";

$i=$i+1;
}

$Supplierqry="select CP.id,CONCAT(CP.address_line1,' ',CP.address_line2,' ',CP.location,' ',CP.pincode,' ',CP.city,' ',CP.state)as address,CP.address_type,CP.country,CP.default_address From tw_company_address CP where CP.company_id='".$supplier_id."' AND public_visible='true' ";	

$SupplierDetails = $sign->FunctionJSON($Supplierqry);
$Suppliercnt="Select count(*) as cnt from tw_company_address  where company_id='".$supplier_id."' AND public_visible='true'";
$cnt = $sign->Select($Suppliercnt);

	
$decodedJSON3 = json_decode($SupplierDetails);
$count = 0;
$i1 = 1;
$x1=$cnt;
$Main_address_supplier="";
while($x1>=$i1){
$Supid = $decodedJSON3->response[$count]->id;
$count=$count+1;
$address1 = $decodedJSON3->response[$count]->address;
$count=$count+1;
$address_type1 = $decodedJSON3->response[$count]->address_type;
$count=$count+1;
$country1 = $decodedJSON3->response[$count]->country;
$count=$count+1;	
$default_address1 = $decodedJSON3->response[$count]->default_address;
$count=$count+1;	
$is_checked="";

if ($supplier_address_id==$Supid)
{
	 $is_checked="checked='checked'";
}
$qry8="SELECT address_icon,address_type_value FROM tw_address_type_master where id='".$address_type1."'";
$address_icon= $sign->SelectF($qry8,"address_icon");

$qry9="SELECT address_type_value FROM tw_address_type_master where id='".$address_type1."'";
$address_type_value1= $sign->SelectF($qry9,"address_type_value");

$SupplieraddPass='"'.$address1.'"';
$Main_address_supplier.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
              <div class='card'>
                <div class='card-body'>
					<h4 class='card-title'><a href='javascript:void(0)' onclick='saveAdd(".$Supid.",".$SupplieraddPass.")'> <input type='radio' id='radAddress' class='radAddress' name='radAddress' value=".$Supid." ".$is_checked."  ></a> <i class=".$address_icon."></i> ".$address_type_value1."</h4>
					<p>".$address1."</p>
                </div>
              </div>
            </div>";
			
$i1=$i1+1;
}

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
  <div class="modal" id="ModalAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pick up address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" id="">
           <?php echo $Main_address_supplier; ?>
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
  <div class="modal" id="ModalBillAddress" tabindex="-1" role="dialog">
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
  
  <!--=========================== MODAL3 START ==============================-->
  <div class="modal" id="ModalShipAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ship to address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row">
           <?php echo $Main_address_ship; ?>
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" >Save changes</button>
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
			  <h4 class="card-title">Purchase order</h4>
			 
			 <div class="row">
			  <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
				  <label for="txtSupplier">Enter PO number<code>*</code></label>
					  <input type="text" readonly class="form-control" id="textPONumber" value="<?php echo $po_number; ?>" placeholder="PO Number"  />
				</div>
				
				<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
				  <label for="txtSupplier">Supplier Name <code>*</code></label>
						<input type="text" readonly class="form-control" id="textPONumber" value="<?php echo $supplierName; ?>"  />
				</div>
			  </div>
				 <div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Supplier Address<a href="#" class="primary" onclick="showModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtSupplierAddress"><?php echo $supplieraddress ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Bill to Address<a href="#" class="primary" onclick="showBillModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtBilltoAddress"><?php echo $Biladdress; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Send invoice to address<a href="#" class="primary" onclick="showshipModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtSendinvoiceAddress"><?php echo $Shipaddress; ?></p>
									</div>
								</div>
							</div>
					</div>
				<br>
			   <div class="row">	
			   <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				  <label for="txtInwardQuantity">Supplier Pan Card Number<code>*</code></label>
				  <input type="text" class="form-control" id="txtPanNumber" readonly value="<?php echo $EditPan ?>" placeholder="Pan Card Number"  />
				</div> 
			   <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Supplier GST Number<code>*</code></label>
				  <input type="text" class="form-control" id="txtGSTNumber" readonly value="<?php echo $EditGST ?>" placeholder="GST Number"/>
				</div>
				<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Supplier Email id<code>*</code></label>
				  <input type="text" class="form-control" id="txtEmail" readonly value="<?php echo $supplierEmail ?>" placeholder="Supplier Email id"  />
				</div>
			   </div>	
				<div class="row">	
				<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				<label for="txtSupplier">Requester <code>*</code></label>
					<input type="text" readonly class="form-control" id="txtREQUESTERName" value="<?php echo $employee_name; ?>"  />
				</div>
				<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Requester Email id<code>*</code></label>
				  <input type="text" class="form-control" id="txtREQUESTEREmail" readonly value="<?php echo $EMP_EMAIL; ?>"  />
				</div>
				<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Order Date<code>*</code></label>
				  <input type="text" class="form-control" id="txtdate" readonly  value= "<?php echo date("Y-m-d",strtotime($date_of_po)) ?>"  />
				</div>
			   </div> 	
				<div class="row">
				<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Payment Terms<code>*</code></label>
				  <input type="text" readonly class="form-control" id="txtPYMENTTERMS" readonly value="<?php echo $payment_term; ?>" placeholder="Payment Terms"  />
				</div>
				<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Shipping Terms<code>*</code></label>
				  <input type="text" readonly class="form-control" id="txtSHIPPINGTERMS" readonly value="<?php echo $shipping_term; ?>"  placeholder="Shipping Terms" />
				</div>
				<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Currency<code>*</code></label>
				  <input type="text" readonly class="form-control" id="txtCURRENCY" readonly  value="<?php echo $Currency; ?>" placeholder="Currency" />
				</div>
			   </div>	
				<div class="row">
				<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Invoicing Method<code>*</code></label>
				  <input type="text" readonly class="form-control" id="txtINVOICINGMETHOD"  readonly value="<?php echo $invoicing_method; ?>" placeholder="Invoicing Method" />
				</div>
				<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Your Supplier No<code>*</code></label>
				  <input type="text" readonly class="form-control" id="txtYOURSUPPLIER"  value="<?php echo $supplier_no; ?>" placeholder="Your Supplier No"  />
				</div>
			   </div>	
				<hr>
			<div class="row ">
		 
				<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">Start Date<code>*</code></label>
				  <input type="date" class="form-control" id="txtStartdate"  value='<?php if(!empty($Start_date)){echo date("Y-m-d",strtotime($Start_date));}else{echo date("Y-m-d",strtotime($cur_date));}?>' placeholder="Start Date"  />
				</div>
				<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
				  <label for="txtNetQuantity">End Date<code>*</code></label>
				  <input type="date" class="form-control" id="txtEnddate"  value='<?php if(!empty($End_date)){echo date("Y-m-d",strtotime($End_date));}else{echo date("Y-m-d",strtotime($cur_date));}?>' placeholder="End Date" />
				</div>
			</div>	
				<div class="form-row">
					<div class="form-group col-md-6">
					  <label for="inputState">Select Category<code>*</code></label>
					  <select  class="form-control" id="selCategory" onchange="loadProduct();" placeholder="Select Category" >
						<option value="">Choose...</option>
						<?php echo $Category; ?>
					  </select>
					</div>
						<div class="form-group col-md-6">
					  <label for="inputState">Select Product<code>*</code></label>
					  <select  class="form-control" id="selProduct" onchange="loadRate();" placeholder="Select Product" >
						<option selected>Choose...</option>
						
					  </select>
					</div>
				</div>
				<div class="form-group">
				  <label for="txtDescription">Description<code>*</code></label>
				  <textarea  class="form-control" id="txtDescription"  value="" rows="3" placeholder="Description" ></textarea>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
					  <label for="selState">State<code>*</code></label>
					  <select  class="form-control" id="selState" onchange="loadCity();" placeholder="Select State" >
						<option value="">Choose...</option>
						<?php echo $State; ?>
					  </select>
					</div>
					<div class="form-group col-md-4">
					  <label for="selcity">City<code>*</code></label>
					  <select  class="form-control" id="selcity" onchange="loadCountry();" placeholder="Select city" >
						<option value="">Choose...</option>
						
					  </select>
					</div>
					<div class="form-group col-md-4">
					  <label for="txtRate">Rate<code>*</code></label>
					  <input type="number" class="form-control" id="txtRate"  value=""  placeholder="Rate" />
					</div>
				</div>
				
				<div class="form-row">
					<div class="form-group col-md-4">
					  <label for="selcycle">Fulfilment Cycle<code>*</code></label>
					  <select  class="form-control" id="selcycle" onclick=""  placeholder="Fulfilment cycle" >
						<option value="">Choose...</option>
						<option value="Monthly">Monthly</option>
						<option value="Quarterly">Quarterly</option>
						<option value="Semi Annual">Semi Annual</option>
						<option value="Yearly">Yearly</option>
						
					  </select>
					</div>
					<div class="form-group col-md-4">
					  <label for="selUnit">Unit<code>*</code></label>
					 <input type="text" class="form-control" id="txtUnit"  value="" placeholder="Unit"  />
					</div>
						<div class="form-group col-md-4">
					  <label for="txtQty">Qty<code>*</code></label>
					  <input type="number" class="form-control" id="txtQty"  value="" placeholder="Quantity"  />
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
					  <label for="txtTotal">Total Amount<code>*</code></label>
					  <input type="text" class="form-control" readonly id="txtTotal"  value="" placeholder="Total Amount" />
					</div>
					<div class="form-group col-md-4">
					  <label for="txtSupplier_Part_Number">Supplier Part Number<code></code></label>
					  <input type="text" class="form-control" id="txtSupplier_Part_Number"  value="" placeholder="Supplier Part Number" />
					</div>
				</div>
				
				<hr>
				<div>
					<button data-repeater-create="" type="button" class="btn  btn-sm  ms-2 mb-2 float:left" onclick="funcaddrow();">
                          <i class="ti-plus"></i> Add
                        </button>
				</div>		
				
			   <div class="table-responsive">
						<table id="tableData" class="table">
						 
						</table>
					  </div>
			<br>
					
				<button type="button" id="btnAddrecord"  class="btn btn-success" onclick="genratePO();" >Update </button>
			  </div>
			  
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
var valTotalQty=0;
var valStatus=<?php echo $status; ?>;
var valApprovedStatus=<?php echo $settingValueApprovedStatus; ?>;

$(document).ready(function(){
	funcremoverowload('');
	if(valStatus==valApprovedStatus)
	{
		var path = 'pgPurchaseOrderFormEdit.php'; //write here name of your page 
		history.pushState(null, null, path + window.location.search);
		window.addEventListener('popstate', function (event) {
		history.pushState(null, null, path + window.location.search);
		});

		 window.location = "pgEprPo.php";
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
$("#txtQty").on('change keyup paste', function () { 
	var TotalAmt = $("#txtRate").val()*$("#txtQty").val();
	$("#txtTotal").val(TotalAmt.toFixed(2));
});
$("#txtRate").on('change keyup paste', function () { 
	var TotalAmt = $("#txtRate").val()*$("#txtQty").val();
	$("#txtTotal").val(TotalAmt.toFixed(2));
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
$( document ).ready(function() {
   $("#txtREQUESTERName").attr('readonly', true);
   $("#txtREQUESTEREmail").attr('readonly', true);
});
function showModal(){
		jQuery.noConflict();
		$("#ModalAddress").modal("show");
	}
function showBillModal(){
		jQuery.noConflict();
		$("#ModalBillAddress").modal("show");
	}
function showshipModal(){
	jQuery.noConflict();
	$("#ModalShipAddress").modal("show");
}	
function loadSubCategory(){
	if ($("#selCategory").val()!="")
	{
		var SELECTID=$("#selCategory").val();
		$.ajax({
				type:"POST",
				url:"apiGetCategory.php",
				data:{SELECTID:SELECTID},
				dataType: 'JSON',
				success:function(response){
					$("#selSubCategory").html("<option value=''>Choose</option>" +response[0]);
				}
			});	
	}
}
function loadProduct()	{
	if ($("#selCategory").val()!="")
	{
		var SELECTID=$("#selCategory").val();
	
		$.ajax({
				type:"POST",
				url:"apiGetProduct.php",
				data:{SELECTID:SELECTID},
				dataType: 'JSON',
				success:function(response){
					 $("#selProduct").html("<option value=''>Choose</option>" +response[0]);
					
				}
			});	
	}
}
var txtRate="";
function loadRate(){
	if ($("#selProduct").val()!="")
	{
		var SELECTID=$("#selProduct").val();
		$.ajax({
				type:"POST",
				url:"apiGetRate.php",
				data:{SELECTID:SELECTID},
				dataType: 'JSON',
				success:function(response){
					 if(console.log(response[0])!=""){
					 }
					 else{
						  $("#txtRate").val("");
					 }
						$("#txtUnit").val(response[2]);
						$("#txtUnit").attr('readonly', true);
					
				}
			});	
	 }
}
function loadCity(){
	if ($("#selState").val()!="")
	{
		var SELECTID=$("#selState").val();
	  
	
		$.ajax({
				type:"POST",
				url:"apiGetCity.php",
				data:{SELECTID:SELECTID},
				dataType: 'JSON',
				success:function(response){	
					$("#selcity").html("<option value=''>Choose</option>" +response[0]);
				}
			});	
	}
	
}
function loadCountry(){
	if ($("#selcity").val()!="")
	{
		var SELECTID=$("#selcity").val();
		$.ajax({
				type:"POST",
				url:"apiGetCountry.php",
				data:{SELECTID:SELECTID},
				dataType: 'JSON',
				success:function(response){	
					 $("#selCountry").html(response[0]);
				}
			});	
	}
	
}
function SubmitADD(){
	$("#txtSupplierAddress").attr('readonly', true);
}
var txtBilltoAddressId='<?php echo $bill_to_address_id; ?>';
function saveBillToAddress(id,address){
	$("#txtBilltoAddress").html(address);
	txtBilltoAddressId=id;
	$("#txtBilltoAddress").attr('readonly', true);
	$("#txtBilltoAddressId").attr('readonly', true);
}
var txtSendinvoiceAddressID='<?php echo $send_invoice_to_address_id; ?>';
function saveShipToAddress(id,address){
$("#txtSendinvoiceAddress").html(address);
 txtSendinvoiceAddressID=id;
$("#txtSendInvoiceAddress").attr('readonly', true);
$("#txtSendinvoiceAddressID").attr('readonly', true);
}
function myFunction(){
	var val = $('#txtSupplierName').val();
	$.ajax({
			type:"POST",
			url:"apiGetCompanyDetails.php",
			data:{id:val},
			dataType: 'JSON',
			success:function(response){
	       $("#txtPanNumber").val(response[0]);
	       $("#txtGSTNumber").val(response[1]);
		   $("#txtEmail").val(response[2]);
	       $("#Main").html(response[3]);
	       $("#txtCURRENCY").val(response[4]);
		   if(console.log(response[5])!=""){
	       $("#txtYOURSUPPLIER").val(response[5]);
		   $("#txtYOURSUPPLIER").attr('readonly', true);
		   }
		   else{
			   $("#txtYOURSUPPLIER").val("");
		   }
		
			}
		});
}
var txtSupplierAddressID='<?php echo $supplier_address_id ?>';
function saveAdd(id,address){
	$("#txtSupplierAddress").html(address);
	txtSupplierAddressID=id;
	var selectedValue = document.getElementById('radAddress').value;
	$("#txtSupplierAddressID").attr('readonly', true); 
}
function funcremoverowload(){
   $.ajax({
	  type:"POST",
	  url:"apiDeleteTempPO.php",
	  data:{requestidid:''},
	  success:function(response){
		  console.log(response)
		$('#tableData').html(response);
		$("#txtDescription").val("");
		$("#txtQuantity").val("");
		$("#txtUOM").val("");
		$("#txtRate").val("");
		showdata();
	  }
  }); 
}
function funcaddrow(){
	if(!validateBlank($("#txtStartdate").val())){
		setErrorOnBlur("txtStartdate");
   }else if(!validateBlank($("#txtEnddate").val())){
		setErrorOnBlur("txtEnddate");
   }else if(!validateBlank($("#txtDescription").val())){
		setErrorOnBlur("txtDescription");
   }else if(!validateBlank($("#selCategory").val())){
		setErrorOnBlur("selCategory");
   }else if(!validateBlank($("#selProduct").val())){
		setErrorOnBlur("selProduct");
   }else if(!validateBlank($("#selState").val())){
		setErrorOnBlur("selState");
   }else if(!validateBlank($("#selcity").val())){
		setErrorOnBlur("selcity");
   }else if(!validateBlank($("#txtRate").val())){
		setErrorOnBlur("txtRate");
   }else if(!isNumber($("#txtRate").val())){
			setError(txtRate);
	}else if(!validateBlank($("#selcycle").val())){
		setErrorOnBlur("selcycle");
   }else if(!validateBlank($("#txtQty").val())){
		setErrorOnBlur("txtQty");
   }else if(!validateBlank($("#txtTotal").val())){
		setErrorOnBlur("txtTotal");
   }
   else{
 
	  $.ajax({
          type:"POST",
          url:"apiAddTempPO.php",
		  dataType: 'JSON',
          data:{txtStartdate:$("#txtStartdate").val(),txtEnddate:$("#txtEnddate").val(),selCategory:$("#selCategory").val(),selProduct:$("#selProduct").val(),txtDescription:$("#txtDescription").val(),selState:$("#selState").val(),selcity:$("#selcity").val(),txtRate:$("#txtRate").val(),selcycle:$("#selcycle").val(),selUnit:$("#selUnit").val(),txtQty:$("#txtQty").val(),txtSupplier_Part_Number:$("#txtSupplier_Part_Number").val(),txtTotal:$("#txtTotal").val(),state:$("#selState").val()},
          success:function(response){
			if(response[3]=="exist"){
				showAlert("Warning","Product already exist","warning");
				enableButton('#btnAddrecord','Update');
			}
			else{
				$("#tableData").html(response[0]);
				txtFinalTotalAmount=response[1];
				valTotalQty=response[2];
			    $("#selCategory").val("");
				$("#selSubCategory").val("");
				$("#selProduct").val("");
				$("#txtDescription").val("");
				$("#selState").val("");
				$("#selcity").val("");
				$("#txtRate").val("");
				$("#selcycle").val("");
				$("#selUnit").val("");
				$("#txtQty").val("");
				$("#txtTotal").val("");
				$("#txtSupplier_Part_Number").val("");
				enableButton('#btnAddrecord','Update');
			}
           }
       });
  
	}
}
function genratePO(){
	var id="<?php echo $requestid_id ?> ";
	var sid="<?php echo $requestid_sid ?> ";
    
	var select_id=$("#txtSupplierName").val();
	var PONumber=$("#textPONumber").val();
	var company_id = "<?php echo $company_id; ?>";
	var supplier_id=$('#txtSupplierName').val();
	var supplier_address_id=txtSupplierAddressID;
	var employee_id= "<?php echo $employee_id; ?>";
	var date=$("#txtdate").val();
	var PYMENTTERMS=$("#txtPYMENTTERMS").val();
	var SHIPPINGTERMS=$("#txtSHIPPINGTERMS").val();
	var INVOICINGMETHOD=$("#txtINVOICINGMETHOD").val();
	var BILLTOADDRESS_ID=txtBilltoAddressId;	
	var SHIPTOADDRESS_ID=txtSendinvoiceAddressID;	
	if(valTotalQty==0){
		showAlert("warning","Please Add Material","warning");
	}
	else{
		$.ajax({
			type:"POST",
			url:"apiUpdateGenratePo.php",
			data:{id:id,sid:sid,PONumber:PONumber,company_id:company_id,supplier_id:select_id,supplier_address_id:supplier_address_id,employee_id:employee_id,date:date,PYMENTTERMS:PYMENTTERMS,SHIPPINGTERMS:SHIPPINGTERMS,INVOICINGMETHOD:INVOICINGMETHOD,BILLTOADDRESS_ID:BILLTOADDRESS_ID,SHIPTOADDRESS_ID:SHIPTOADDRESS_ID,TotalQty:valTotalQty},
			success:function(response){	
				
			   if($.trim(response)=="Success"){
					showAlertRedirect("Success","Data Updated Successfully","success","pgEprPo.php?type=In%20Process");
					enableButton('#btnAddrecord','Update');
				}
				else if($.trim(response)=="error"){
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
					enableButton('#btnAddrecord','Update');
				}					
				else if($.trim(response)=="Exist"){
					showAlertRedirect("Warning","This Form Already Accepted/Rejected","warning","pgEprPo.php?type=In%20Process");
					enableButton('#btnAddrecord','Update');
				}
				else{
					showAlert("Error","Something Went Wrong. Please Try After Sometime","error");
					enableButton('#btnAddrecord','Update');
				}
			}	
			
		});
	}
}
function funcremoverow(id){
	   $.ajax({
          type:"POST",
          url:"apiDeleteTempPO.php",
		  dataType: 'JSON',
          data:{requestidid:id},
          success:function(response){
			$("#tableData").html(response[0]);
			txtFinalTotalAmount=response[1];
			valTotalQty=response[2];
          }
      }); 
}
//---Karuna End

function showdata(){
	var hdnOrderID= "<?php echo $requestid_id ?>";
	console.log(hdnOrderID);
	  $.ajax({
          type:"POST",
          url:"apiGetTempTableDetailsPo.php",
          data:{hdnOrderID:hdnOrderID},
          success:function(response){
			$("#tableData").html(response);
			var resp=response;
			var array = resp.split("</tbody>,");
			var number_array=array[1].split(",");

			var array1 = resp.split("-");
			var number_array1=array[1].split("-");

			$("#txtFinalTotalAmount").val(number_array1[0]);
			valTotalQty = number_array1[1]; 
          }
      });
  }
</script>

</body>

</html>