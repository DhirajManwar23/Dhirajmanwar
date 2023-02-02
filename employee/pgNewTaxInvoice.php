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

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");

$company_id=$_SESSION["company_id"];


$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$invoice_number = "";
$delivery_challan_no = "";
$obp_certificate_no = "";
$centre_outward_slip_no = "";
$invoice_date = "";
$date_of_supply = "";
$remark = "";
$termsofpayment = "";
$company_address_id="";
$lr_no="";
$lr_date="";
$supplier_name="";
$supplier_id="";
$bill_to_address="";
$bill_to_gst_number="";
$bill_to_pincode="";
$bill_to_state="";
$vehicle_no="";
$mode_of_transport="";
$ship_to_address="";
$ship_to_gst="";
$ship_to_pincode="";
$ship_to_state="";
$remark="";
$final_amount="";
$defaulfbillingid="";
$invID="";
	if($requesttype=="edit")
	{
		$qry="select invoice_number,delivery_challan,obp_number,centre_outward_number ,invoice_date,date_of_supply,company_address_id,lr_no,lr_date,supplier_name,bill_to_address,bill_to_gst_number,bill_to_pincode,bill_to_state,vehicle_no,mode_of_transport,ship_to_address,ship_to_gst,ship_to_pincode,ship_to_state,remark,final_amount,id,termsofpayment,supplier_id  from  tw_thirdparty_invoice where id='".$requestid."'  order by id Desc";
		$retVal = $sign->FunctionJSON($qry);
		$decodedJSON = json_decode($retVal);
		
		$invoice_number = $decodedJSON->response[0]->invoice_number;
		$delivery_challan_no = $decodedJSON->response[1]->delivery_challan;
		$obp_certificate_no = $decodedJSON->response[2]->obp_number ;
		$centre_outward_slip_no = $decodedJSON->response[3]-> 	centre_outward_number ;
		$invoice_date = $decodedJSON->response[4]->invoice_date;
		$date_of_supply = $decodedJSON->response[5]->date_of_supply;
		$company_address_id = $decodedJSON->response[6]->company_address_id;
		$lr_no = $decodedJSON->response[7]->lr_no;
		$lr_date = $decodedJSON->response[8]->lr_date;
		$supplier_name = $decodedJSON->response[9]->supplier_name;
		$bill_to_address = $decodedJSON->response[10]->bill_to_address;
		$bill_to_gst_number = $decodedJSON->response[11]->bill_to_gst_number;
		$bill_to_pincode = $decodedJSON->response[12]->bill_to_pincode;
		$bill_to_state = $decodedJSON->response[13]->bill_to_state;	
		$vehicle_no = $decodedJSON->response[14]->vehicle_no;
		$mode_of_transport = $decodedJSON->response[15]->mode_of_transport;	
		$ship_to_address = $decodedJSON->response[16]->ship_to_address;
		$ship_to_gst = $decodedJSON->response[17]->ship_to_gst;
		$ship_to_pincode = $decodedJSON->response[18]->ship_to_pincode;
		$ship_to_state = $decodedJSON->response[19]->ship_to_state;
		$remark = $decodedJSON->response[20]->remark;
		$final_amount = $decodedJSON->response[21]->final_amount;
		$invID = $decodedJSON->response[22]->id;
		$termsofpayment  = $decodedJSON->response[23]->termsofpayment ;
		$supplier_id  = $decodedJSON->response[24]->supplier_id ;
		
		
		$DefaultAddqry = "SELECT id,address_line1,address_line2,location,pincode,city,state FROM tw_company_address WHERE id='".$company_address_id."' and public_visible='true'";
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
		
		$qry="select id,CONCAT(address_line1,' ',address_line2,' ',location,' ',pincode,' ',city,' ',state)as address,address_type,country,google_map,default_address From tw_company_address  
		where company_id='".$company_id."' AND public_visible='true' ";	

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
		$google_map = $decodedJSON2->response[$count]->google_map;
		$count=$count+1;
		$default_address = $decodedJSON2->response[$count]->default_address;
		$count=$count+1;	
		$is_checked="";
		if ($defaulfbillingid ==$id)
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
		
		
		
		
		
		$Billqry="select id,CONCAT(address_line1,' ',address_line2,' ',location,' ',pincode,' ',city,' ',state)as address,pincode,address_type,country,google_map,default_address From tw_company_address  
		where company_id='".$supplier_id."' AND public_visible='true' ";	

		$BillretVal = $sign->FunctionJSON($Billqry);
		$Billqry1="Select count(*) as cnt from tw_company_address  where company_id='".$supplier_id."' AND public_visible='true'";
		$BillretVal1 = $sign->Select($Billqry1);
			
		$decodedJSON2 = json_decode($BillretVal);
		$count = 0;
		$i = 1;
		$x=$BillretVal1;
		$Main_address_ship="";
		$Main_ship="";
		while($x>=$i){
		$Billid = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$Billaddress = $decodedJSON2->response[$count]->address;
		$count=$count+1;
		$Billpincode = $decodedJSON2->response[$count]->pincode;
		$count=$count+1;
		$Billaddress_type = $decodedJSON2->response[$count]->address_type;
		$count=$count+1;
		$Billcountry = $decodedJSON2->response[$count]->country;
		$count=$count+1;
		$Billgoogle_map = $decodedJSON2->response[$count]->google_map;
		$count=$count+1;
		$Billdefault_address = $decodedJSON2->response[$count]->default_address;
		$count=$count+1;	
		$is_checked="";
		

		$billqry4="SELECT address_icon,address_type_value FROM tw_address_type_master where id='".$address_type."'";
		$billaddress_icon= $sign->SelectF($billqry4,"address_icon");

		$billqry5="SELECT address_type_value FROM tw_address_type_master where id='".$address_type."'";
		$Billaddress_type_value= $sign->SelectF($billqry5,"address_type_value");

		$BilladdPass='"'.$Billaddress.'"';
		$Main_address_ship.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
					  <div class='card'>
						<div class='card-body'>
							<h4 class='card-title'><a href='javascript:void(0)' onclick='saveBill_Address(".$Billpincode.",".$BilladdPass.")'> <input type='radio' id='radmainAddress' class='radAddress' name='radmainAddress' value=".$id." ".$is_checked."></a> <i class=".$billaddress_icon."></i> ".$Billaddress_type_value."</h4>
							<p>".$Billaddress."</p>
						</div>
					  </div>
					</div>";
		
		$Main_ship.="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
		  <div class='card'>
			<div class='card-body'>
				<h4 class='card-title'><a href='javascript:void(0)' onclick='saveShip_Address(".$Billpincode.",".$BilladdPass.")'> <input type='radio' id='radmainAddress' class='radAddress' name='radmainAddress' value=".$id." ".$is_checked."></a> <i class=".$billaddress_icon."></i> ".$Billaddress_type_value."</h4>
				<p>".$Billaddress."</p>
			</div>
		  </div>
		</div>";
		


		$i=$i+1;
		}
		
		
		
		
		

	} 

else{
		$DefaultAddqry = "SELECT id,address_line1,address_line2,location,pincode,city,state FROM tw_company_address WHERE company_id='".$company_id."' and public_visible='true'";
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



		$qry="select id,CONCAT(address_line1,' ',address_line2,' ',location,' ',pincode,' ',city,' ',state)as address,address_type,country,google_map,default_address From tw_company_address  
		where company_id='".$company_id."' AND public_visible='true' ";	

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
		$google_map = $decodedJSON2->response[$count]->google_map;
		$count=$count+1;
		$default_address = $decodedJSON2->response[$count]->default_address;
		$count=$count+1;	
		$is_checked="";
		if ($defaulfbillingid ==$id)
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

		

	
}
$CategoryQry="SELECT id,product_name FROM  tw_product_management  ORDER by id ASC";
$Category = $sign->FunctionOption($CategoryQry,"",'product_name',"id");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste |Tax Invoice</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
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
  <div class="modal" id="ModalBillAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bill to address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" id="BillModal">
          <?php echo $Main_address_ship; ?>
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
      <div class="row" id="shipmodal">
            <?php echo $Main_ship; ?>
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
                  <h4 class="card-title">Tax Invoice</h4>
                  <div class="forms-sample">
						<div class="form-group">
						<div class="row">
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicenumber">Invoice Number <code>*</code></label>
								<input type="text" class="form-control" id="txtinvoicenumber" maxlength="30" value="<?php echo $invoice_number; ?>" placeholder="Invoice Number" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtdeliverychallannumber">Delivery Challan Number<code>*</code></label>
								<input type="text" class="form-control" id="txtdeliverychallannumber" maxlength="50" value="<?php echo $delivery_challan_no; ?>" placeholder="Delivery Challan Number" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtobpcertificatenumber">OBP Certificate Number <code>*</code></label>
								<input type="text" class="form-control" id="txtobpcertificatenumber" maxlength="50" value="<?php echo $obp_certificate_no ?>" placeholder="OBP Certificate Number" />
						   </div>
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtcentreoutwardslipnumber">Centre Outward Slip Number <code>*</code></label>
								<input type="text" class="form-control" id="txtcentreoutwardslipnumber" maxlength="50" value="<?php echo $centre_outward_slip_no; ?>" placeholder="Centre Outward Slip Number" />
						   </div>
						  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Invoice Date <code>*</code></label>
								<input type="Date" class="form-control" min="<?php echo $date; ?>" max="<?php echo $cur_date; ?>" value='<?php if(!empty($invoice_date)){echo date("Y-m-d",strtotime($invoice_date));}else{echo date("Y-m-d",strtotime($cur_date));}?>' id="txtinvoicedate"  placeholder="Invoice Date" />
						   </div> 
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtdateofsupply">Date Of Supply<code>*</code></label>
								<input type="date" class="form-control"  min="<?php echo $date; ?>" max="<?php echo $cur_date; ?>" value='<?php if(!empty($date_of_supply)){echo date("Y-m-d",strtotime($date_of_supply));}else{echo date("Y-m-d",strtotime($cur_date));}?>' id="txtdateofsupply"  placeholder="Date Of Supply" />
						   </div>
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Company Address <a href="#" class="primary" onclick="showModal();"><i class="ti-location-pin float-right"></i></a></h3>
									<div class="pAddress-body">
										<p id="txtcompanyaddress"><?php echo $defaulfsupplieraddress; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtcentreoutwardslipnumber">LR NO. <code>*</code></label>
								<input type="text" class="form-control" id="txtLRNO" maxlength="50" value="<?php echo $lr_no ?>" placeholder="LR NO." />
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtlrdate">LR Date <code>*</code></label>
								<input type="Date" class="form-control" min="<?php echo $date; ?>" max="<?php echo $cur_date; ?>" value='<?php if(!empty($lr_date)){echo date("Y-m-d",strtotime($lr_date));}else{echo date("Y-m-d",strtotime($cur_date));}?>' id="txtLRDate"  placeholder="LR Date" />
						   </div>
						</div>
					</div>
					<hr>
					<div class="form-group">
						<div class="row">
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtcentreoutwardslipnumber">Select Supplier Name<code>*</code></label>
								<input type="text" class="form-control" data-provide="typeahead" onclick="AutoComplete();" class="form-control basicAutoComplete"autocomplete="off"  id="txtSupplierName" maxlength="50" value="<?php echo $supplier_name; ?>" placeholder="Select Supplier Name" />
						   </div>
						  <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Bill to Address<code>*</code><a href="#" class="primary" onclick="showBillModal();"><i class="ti-location-pin float-right"></i></a></label>
								<input type="text" class="form-control"  value='<?php echo $bill_to_address ?>' id="txtBilltoAddress"  placeholder="Bill to Address" />
						   </div>
						   
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
						   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
								<label for="txtcentreoutwardslipnumber">GST Number<code>*</code></label>
								<input type="text" class="form-control" id="txtBillGstnumber" maxlength="50" value="<?php echo $bill_to_gst_number; ?>" placeholder="GST Number" />
						   </div>
						   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Pincode<code>*</code></label>
								<input type="text" class="form-control" onchange="myFunction(this.value)"   placeholder="Pincode"  value='<?php echo $bill_to_pincode; ?>' id="txtPincode"  placeholder="Pincode" />
						   </div> 
						  <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Select State<code>*</code></label>
								<input type="text" class="form-control"   value='<?php echo $bill_to_state; ?>' id="txtSelectState"  placeholder="Select State" />
						   </div> 
						   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Vehicle No<code>*</code></label>
								<input type="Text" class="form-control" min=""  value='<?php echo $vehicle_no; ?>' id="txtVehicleNo"  placeholder="Vehicle No" />
						   </div>	   
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
						  <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Ship to Address<code>*</code><a href="#" class="primary" onclick="showshipModal();"><i class="ti-location-pin float-right"></i></a></label>
								<input type="text" class="form-control"  value='<?php echo $ship_to_address; ?>' id="txtShiptoAddress"  placeholder="Ship to Address" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtcentreoutwardslipnumber">GST Number<code>*</code></label>
								<input type="text" class="form-control" id="txtGSTnumber" maxlength="50" value="<?php echo $ship_to_gst; ?>" placeholder="GST Number" />
						   </div>
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Pincode<code>*</code></label>
								<input type="text" class="form-control" onchange="myFunctionShip(this.value)"   placeholder="Pincode"  value="<?php echo $ship_to_pincode; ?>" id="txtShipPincode"  placeholder="Pincode" />
						   </div> 
						
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Select State<code>*</code></label>
								<input type="text" class="form-control"  value='<?php echo $ship_to_state; ?>' id="txtShipState"  placeholder="Select State" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
						   <label for="txtinvoicedate">Mode of Transport<code>*</code></label>
						    <select  class="form-control" id="txtTransport" onclick=""  placeholder="Fulfilment cycle">
									<option value="">Select Mode</option>
									<option value="Road" <?php  if($mode_of_transport=="Road"){ echo "selected";} ?> >Road</option>
									<option value="Air" <?php  if($mode_of_transport=="Air"){ echo "selected";} ?>>Air</option>
									<option value="Water" <?php  if($mode_of_transport=="Water"){ echo "selected";} ?>>Water</option>
									<option value="Railway" <?php  if($mode_of_transport=="Railway"){ echo "selected";} ?>>Railway</option>
								</select>
						   </div>
						   
                       </div>
					</div>
					
					<hr>
					<div class="form-group">
                      <label for="txtTermsofPayment">Terms of Payment <code>*</code></label>
                      <input type="text" class="form-control" value="<?php echo $termsofpayment; ?>" id="txtTermsofPayment"  placeholder="Terms of Payment" />
                    </div>
					<div class="form-group">
                      <label for="txtremark">Remark <code>*</code></label>
                      <textarea class="form-control" id="txtremark" maxlength="5000" value="<?php echo $remark; ?>" placeholder="Remark"></textarea>
                    </div>
					<div class="form-group">
						<div class="row">
						   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Select Material<code>*</code></label>
									<select  class="form-control" id="txtMaterial" placeholder="Select Material">
										<option selected value="">Choose...</option>
											<?php echo $Category; ?>
									</select>	
						   </div> 
						   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Quantity <code>*</code></label>
								<input type="number" class="form-control"  id="txtQuantity"  placeholder="Quantity " />
						   </div>
						   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Rate <code>*</code></label>
								<input type="text" class="form-control"  value='' id="txtRate"  placeholder="Rate" />
						   </div>
						   <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtHSN">HSN<code>*</code></label>
							<input type="text" readonly id="txtHSN" placeholder="HSN" class="form-control" />
                       </div>
					     
                       </div>
					</div>
					<div class="form-group row">
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtInwardQuantity">Tax(%)<code>*</code></label>
							<input type="number" readonly id="txtTax" placeholder="Tax" class="form-control" />
                       </div>
						 <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12">
							<label for="txtInwardQuantity">Total<code>*</code></label>
							<input type="text" readonly id="txtTotalAmount" placeholder="Total Amount" class="form-control" />
                       </div>
					</div>
					
					
					<div>
					<button data-repeater-create="" type="button" class="btn  btn-sm  ms-2 mb-2 float:left" onclick="funcaddrow();">
                          <i class="ti-plus"></i> Add
                        </button>
					</div>		
					
					<div class="table-responsive">
						<table id="tableData" class="table" >
						 
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
<script src="../assets/js/custom/sweetAlert2.min.js"></script>

<script src="../assets/js/custom/twCommonValidation.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
 
 <script src="../assets/js/custom/jquery-ui.js"></script>  
 <script>
 var txtFinalTotalAmount = 0.00;
 var txtCompanyAddressID="<?php echo $defaulfbillingid; ?>"
 var supplie_id="<?php $supplier_id; ?>";
$("#txtRate").on('change keyup paste', function () { 

	var TotalAmt = $("#txtRate").val()*$("#txtQuantity").val();
	$("#txtTotalAmount").val(TotalAmt.toFixed(2));
		console.log(TotalAmt);
	});

function AutoComplete(){
		
	 const  name=[ ];
	$.ajax({
		type:"POST",
		url:"apiSupplierDetailAutoComplete.php",
		data:{},
		dataType: 'JSON',
		success:function(response){
			name.push(response[0].name);
			    $( "#txtSupplierName" ).autocomplete({  
             source:response, 
			  autoSelect: true,
			  minLength:1,     
				delay:100    
			});  
			  $("#txtSupplierName").autocomplete({
            source:response,
            minLength:1,
            
            select: function(event, ui ) { 
                   getdetails(ui.item.value);
                   
            }        
    }); 
		 $('#txtSupplierName').on('autocompleteselect', function (e, ui) {
			getdetails(ui.item.value);
			getdetailsShip(ui.item.value);
			});
		}
	});
	 //getdetails();
}


 $("#txtSupplierName").change(function(){ 
 $('#BillModal').html("");
	 $('#txtBilltoAddress').val("");
	  $('#BillModal').html("");
	 $('#txtBilltoAddress').val("");
	 $('#txtPincode').val("");
	 $('#txtBillGstnumber').html("");
	 $('#txtSelectState').val("");
	 $('#txtBillGstnumber').val("");
	 
	  $('#txtShiptoAddress').val("");
	 $('#txtShipPincode').val("");
	 $('#txtShipState').val("");
	 $('#txtGSTnumber').val("");
 });
function  getdetails(ui){
	 
	 $.ajax({
          type:"POST",
          url:"apiGetSupplierDetails.php",
          data:{requestidid:ui},
		  dataType: 'JSON',
          success:function(response){
		    console.log(response[2]);
			
			 $('#BillModal').html(response[0]);
			 $('#txtBilltoAddress').val(response[1]);
			 $('#txtPincode').val(response[2]);
			 $('#txtSelectState').val(response[3]);
			 $('#txtBillGstnumber').val(response[4]);
			 
			 $('#txtShiptoAddress').val(response[1]);
			 $('#txtShipPincode').val(response[2]);
			 $('#txtShipState').val(response[3]);
			 $('#txtGSTnumber').val(response[4]);
			 
			 
			 
			supplie_id=response[5];
			
           }
      }); 
}
function  getdetailsShip(ui){
	 $.ajax({
          type:"POST",
          url:"apiGetSupplierShipDetails.php",
          data:{requestidid:ui},
		  dataType: 'JSON',
          success:function(response){
		    console.log(response[0]);
			 $('#shipmodal').html(response[0]);
			
           }
      }); 
}

$('#autocomplete').autocomplete({
    paramName: 'searchString',
    transformResult: function(response) {
        return {
            suggestions: $.map(response.myData, function(dataItem) {
                return { value: dataItem.valueField, data: dataItem.dataField };
            })
        };
    }
})

function SubmitADD(){

}
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

 
 
 function myFunction(val) {
	 getPinCodeBill($("#txtPincode").val());
} 
function myFunctionShip(val) {
	getPinCodeShip($("#txtShipPincode").val());
}
 function getPinCodeBill(id){
	$.ajax({
		type:"GET",
		url:"https://api.postalpincode.in/pincode/"+id,
		dataType:"JSON",
		data:{},
		success: function(response){
			console.log(response);
			if (response["0"]["Status"]=="Success")
			{
				$("#txtCity").val(response["0"]["PostOffice"]["0"]["Region"]);
				$("#txtSelectState").val(response["0"]["PostOffice"]["0"]["State"]);
				$("#txtCountry").val(response["0"]["PostOffice"]["0"]["Country"]);
				$("#txtCity").attr('readonly', true);
				$("#txtSelectState").attr('readonly', true);
				$("#txtCountry").attr('readonly', true);
				$("#txtPincode").focus();
			}
			else
			{   
		        $("#txtCity").val("");
				$("#txtCity").removeAttr("disabled");
				$("#txtSelectState").val("");
				$("#txtSelectState").removeAttr("disabled");
				$("#txtCountry").val("");
				$("#txtCountry").removeAttr("disabled");
				$("#txtCity").removeAttr('readonly');
				$("#txtSelectState").removeAttr('readonly');
				$("#txtCountry").removeAttr('readonly');
				$("#txtCity").focus();
			}
		}
	});
}


function getPinCodeShip(id){
	$.ajax({
		type:"GET",
		url:"https://api.postalpincode.in/pincode/"+id,
		dataType:"JSON",
		data:{},
		success: function(response){
			console.log(response);
			if (response["0"]["Status"]=="Success")
			{
				$("#txtCity").val(response["0"]["PostOffice"]["0"]["Region"]);
				$("#txtShipState").val(response["0"]["PostOffice"]["0"]["State"]);
				$("#txtCountry").val(response["0"]["PostOffice"]["0"]["Country"]);
				$("#txtCity").attr('readonly', true);
				$("#txtShipState").attr('readonly', true);
				$("#txtCountry").attr('readonly', true);
				$("#txtPincode").focus();
			}
			else
			{   
		        $("#txtCity").val("");
				$("#txtCity").removeAttr("disabled");
				$("#txtShipState").val("");
				$("#txtShipState").removeAttr("disabled");
				$("#txtCountry").val("");
				$("#txtCountry").removeAttr("disabled");
				$("#txtCity").removeAttr('readonly');
				$("#txtShipState").removeAttr('readonly');
				$("#txtCountry").removeAttr('readonly');
				$("#txtCity").focus();
			}
		}
	});
}
 $("#txtMaterial").on('change keyup paste', function () {
	var selectedId = $(this).children("option:selected").val();  
	$.ajax({
	  type:"POST",
	  url:"apiGetTaxInvMaterialInfomo.php",
	  data:{txtMaterialName:selectedId},
	  dataType: 'JSON',
	  success:function(response){
		  console.log(response);
		  $("#txtTax").val(response[0]);
		  $("#txtHSN").val(response[1]); 
		  $("#txtQuantity").val("");
		  $("#txtTotalAmount").val(""); 
	  }
  }); 
}); 

$(document).ready(function(){
	
	
	showDATA();
	funcremoverow('');
	
});
function funcremoverow(id){
	   $.ajax({
          type:"POST",
          url:"apiDeleteTaxInvMaterial.php",
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

			$("#txtMaterialName").val("");
			$("#txtQuantity").val("");
			$("#txtHSN").val("");
			$("#txtRate").val("");
			$("#txtTax").val("");
			$("#txtTotalAmount").val(""); 
          }
      }); 
}

function funcaddrow(){
	
   if(!validateBlank($("#txtMaterial").val())){
	   
		setErrorOnBlur("txtMaterial");
   }
  
   else if(!validateBlank($("#txtQuantity").val())){
		setErrorOnBlur("txtQuantity");
   }
   else if(!isNumber($("#txtQuantity").val())){
		setError(txtQuantity);
	} 
	 else if(!validateBlank($("#txtRate").val())){
		setErrorOnBlur("txtRate");
   }
  else{
	  $.ajax({
          type:"POST",
          url:"apiAddTempTaxInvMaterial.php",
		  dataType: 'JSON',
          data:{txtMaterialName:$("#txtMaterial").val(),txtQuantity:$("#txtQuantity").val(),txtRate:$("#txtRate").val()},
          success:function(response){
			 console.log(response);
			 $("#tableData").html(response[0]);
			 txtFinalTotalAmount=response[1];
			 valTotalQuantity=response[2];
			 if(response[3]=="exist"){
				 showAlert("Warning","Product already exist","warning");
			 }
			 
			$("#txtMaterial").val("");
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
		
}
function saveBillToAddress(id,address){
	$("#txtcompanyaddress").html(address);
	txtCompanyAddressID=id;
	alert(txtCompanyAddressID);
	$("#txtcompanyaddress").attr('readonly', true);	
}
function saveBill_Address(pincode,address){
	$("#txtBilltoAddress").val(address);
	$("#txtPincode").val(pincode);
		
}
function saveShip_Address(pincode,address){

	$("#txtShiptoAddress").val(address);
	$("#txtShipPincode").val(pincode);

		
}
function showBillModal(){
		jQuery.noConflict();
		$("#ModalBillAddress").modal("show");
	}

function saveAdd(pincode,state){
	$("#txtPincode").html(pincode);
	$("#txtSelectState").html(state);
	
	var selectedValue = document.getElementById('radAddress').value;       
}

function showshipModal(){
	jQuery.noConflict();
	$("#ModalShipAddress").modal("show");
}	




  function adddata(){
		if(txtFinalTotalAmount==0){
			showAlert("error","Add Material","error");	
		}
        else{
			if(!validateBlank($("#txtinvoicenumber").val())){
				setErrorOnBlur("txtinvoicenumber");
			} 
			else if(!validateBlank($("#txtinvoicedate").val())){
				setErrorOnBlur("txtinvoicedate");
			} 
			else if(!validateBlank($("#txtdateofsupply").val())){
				setErrorOnBlur("txtdateofsupply");
			} 
			else if(!validateBlank($("#txtLRNO").val())){
				setErrorOnBlur("txtLRNO");
			} 
			else if(!validateBlank($("#txtLRDate").val())){
				setErrorOnBlur("txtLRDate");
			} 
			else if(!validateBlank($("#txtSupplierName").val())){
				setErrorOnBlur("txtSupplierName");
			} 
			else if(!validateBlank($("#txtBilltoAddress").val())){
				setErrorOnBlur("txtBilltoAddress");
			} 
			else if(!validateBlank($("#txtBillGstnumber").val())){
				setErrorOnBlur("txtBillGstnumber");
			} 
			else if(!validateBlank($("#txtPincode").val())){
				setErrorOnBlur("txtPincode");
			} 
			else if(!validateBlank($("#txtSelectState").val())){
				setErrorOnBlur("txtSelectState");
			} 
			else if(!validateBlank($("#txtVehicleNo").val())){
				setErrorOnBlur("txtVehicleNo");
			}
			else if(!validateBlank($("#txtShiptoAddress").val())){
				setErrorOnBlur("txtShiptoAddress");
			} 
			else if(!validateBlank($("#txtGSTnumber").val())){
				setErrorOnBlur("txtGSTnumber");
			} 
			else if(!validateBlank($("#txtShipPincode").val())){
				setErrorOnBlur("txtShipPincode");
			} 
			else if(!validateBlank($("#txtShipState").val())){
				setErrorOnBlur("txtShipState");
			} 
			else if(!validateBlank($("#txtTransport").val())){
				setErrorOnBlur("txtTransport");
			} 
			else {
				
				var valrequesttype="<?php echo  $requesttype; ?>";
				var requestid="<?php echo  $requestid; ?>";
				$.ajax({
				type:"POST",
				url:"apiAddTaxInvoiceDetails.php",
				data:{txtinvoicenumber:$("#txtinvoicenumber").val(),delivery_challan:$("#txtdeliverychallannumber").val(),obp_number:$("#txtobpcertificatenumber").val(),centreoutwardslipnumber:$("#txtcentreoutwardslipnumber").val(),invoice_date:$("#txtinvoicedate").val(),date_of_supply:$("#txtdateofsupply").val(),company_address_id:txtCompanyAddressID,lr_no:$("#txtLRNO").val(),lr_date:$("#txtLRDate").val(),supplier_name:$("#txtSupplierName").val(), supplie_id:supplie_id,bill_to_address:$("#txtBilltoAddress").val(),bill_to_gst_number:$("#txtBillGstnumber").val(),bill_to_pincode:$("#txtPincode").val(),bill_to_state:$("#txtSelectState").val(),vehicle_no:$("#txtVehicleNo").val(),mode_of_transport:$("#txtTransport").val(),ship_to_address :$("#txtShiptoAddress").val(),ship_to_gst :$("#txtGSTnumber").val(),ship_to_pincode :$("#txtShipPincode").val(),ship_to_state:$("#txtShipState").val(),remark:$("#txtremark").val(),txtTermsofPayment:$("#txtTermsofPayment").val(),final_amount:txtFinalTotalAmount,valrequesttype:valrequesttype,requestid:requestid},
				success:function(response){
					console.log(response);
					if(valrequesttype=="add"){
						enableButton('#btnAddrecord','Add Record');
					}
					else
					{
						enableButton('#btnAddrecord','Update Record');
					}
					
				if(response="Success"){
					if(valrequesttype=="add"){
							 showAlertRedirect("Success","Data Added Successfully","success","pgRecordTaxInvoice.php");
						}
						else
						{
							 showAlertRedirect("Success","Data Update Successfully","success","pgRecordTaxInvoice.php");
						
				  
						}
					}
			else{
					showAlert("error","Something Went Wrong. Please Try After Sometime","error");		
				}
				}
			}); 
	  } 
	}
}
 
  function showDATA(){
	  
	 var Inv_id = "<?php echo $invID; ?>";
	$.ajax({
          type:"POST",
          url:"apiAddTempMaterialTaxInvoice.php",
		  dataType: 'JSON',
          data:{Inv_id:Inv_id},
          success:function(response){
			  console.log(response);
			 $("#tableData").html(response);
			 $("#tableData").html(response[0]);
			 txtFinalTotalAmount=response[1];
			 
			
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